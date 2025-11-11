<?php

namespace App\Jobs;

use App\Models\AllJob;
use App\Models\Product;
use App\Models\Exchange;
use Illuminate\Bus\Queueable;
use App\Models\PricelistEntries;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RefreshPricesChunkJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
  protected int $allJobId;

  public function __construct(int $allJobId)
  {
    $this->allJobId = $allJobId;
  }
  public function handle(): void
  {
    AllJob::where('id', $this->allJobId)->update(['status' => 'processing']);

    try {

      $epsilon = 0.0099;

      PricelistEntries::chunk(500, function ($entries) {
        foreach ($entries as $price) {
          if (!is_null($price->value_no_vat) && is_null($price->value)) {
            $price->value_no_discount = $price->value_no_vat * (1 + $price->vat / 100);
            $price->value = $price->value_no_discount * (1 - $price->discount / 100);
          } elseif (!is_null($price->value) && $price->discount > 0) {
            $price->value_no_discount = $price->value / (1 - $price->discount / 100);
            $price->value_no_vat = $price->value_no_discount / (1 + $price->vat / 100);
          } elseif (!is_null($price->value) && $price->discount == 0) {
            $price->value_no_vat = $price->value / (1 + $price->vat / 100);
            $price->value_no_discount = $price->value_no_vat * (1 + $price->vat / 100);
          }

          $price->save();
        }
      });

      Product::where('active', true)
        ->where('start_date', '<=', now(config('app.timezone'))->format('Y-m-d'))
        ->where('end_date', '>=', now(config('app.timezone'))->format('Y-m-d'))
        ->chunk(500, function ($products) use ($epsilon) {
          foreach ($products as $product) {
            $cartPrices = $product->carts_item->pluck('price');
            $averagePrice = $cartPrices->isNotEmpty()
              ? $cartPrices->avg()
              : optional($product->product_prices->first())->value;

            $totalCost = 0;
            $count = 0;

            foreach ($product->order_suppliers->where('order.status', 'closed') as $orderSupplier) {
              $cost = $orderSupplier->price;
              $supplierCurrency = $orderSupplier->order->currency ?? null;
              $productCurrency = optional($product->product_prices->first())->pricelist->currency->name ?? null;

              if ($supplierCurrency && $productCurrency && $supplierCurrency !== $productCurrency) {
                $exchange = Exchange::whereHas('base_currency', fn($q) => $q->where('name', $supplierCurrency))
                  ->whereHas('quote_currency', fn($q) => $q->where('name', $productCurrency))
                  ->latest()->first();

                if (!$exchange) {
                  $exchange = Exchange::whereHas('base_currency', fn($q) => $q->where('name', $productCurrency))
                    ->whereHas('quote_currency', fn($q) => $q->where('name', $supplierCurrency))
                    ->latest()->first();

                  if ($exchange) {
                    $cost /= $exchange->value;
                  }
                } else {
                  $cost *= $exchange->value;
                }
              }

              if ($cost) {
                $totalCost += $cost;
                $count++;
              }
            }

            $averageCost = $count > 0 ? ($totalCost / $count) : null;
            $oldPrice = optional($product->costs->last())->price ?? null;

            if ($oldPrice && abs($oldPrice - $averagePrice) > $epsilon) {
              DB::table('product_costs')->insert([
                'product_id' => $product->id,
                'price' => $averagePrice,
                'cost' => $averageCost,
                'date' => now(config('app.timezone')),
                'created_by' => Auth::user()->name ?? 'system',
                'last_modified_by' => Auth::user()->name ?? 'system',
                'created_at' => now(config('app.timezone')),
                'updated_at' => now(config('app.timezone')),
              ]);
            } elseif (!$oldPrice) {
              DB::table('product_costs')->updateOrInsert(
                ['product_id' => $product->id],
                [
                  'price' => $averagePrice,
                  'cost' => $averageCost,
                  'date' => now(config('app.timezone')),
                  'created_by' => Auth::user()->name ?? 'system',
                  'last_modified_by' => Auth::user()->name ?? 'system',
                  'created_at' => now(config('app.timezone')),
                  'updated_at' => now(config('app.timezone')),
                ]
              );
            }
          }
        });
      AllJob::where('id', $this->allJobId)->update([
        'status' => 'finished',
        'finished_at' => now(config('app.timezone')),
      ]);
      Log::info('RefreshPricesChunkJob completed successfully.');
    } catch (\Throwable $e) {
      AllJob::where('id', $this->allJobId)->update([
        'status' => 'failed',
        'error' => $e->getMessage(),
      ]);

      throw $e;
    }
  }
}
