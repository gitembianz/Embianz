<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\AllJob;
use App\Models\Exchange;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use App\Models\Order_Supplier_Item;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CalculateOrdersCost implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
  protected int $allJobId;
  /**
   * Create a new job instance.
   */
  public function __construct(int $allJobId)
  {
    $this->allJobId = $allJobId;
  }

  /**
   * Execute the job.
   */
  public function handle(): void
  {
    AllJob::where('id', $this->allJobId)->update(['status' => 'processing']);

    try{
      Order_Supplier_Item::wherehas('order_supplier', function ($query) {
        $query->where('status', 'closed');
      })->chunk(500, function ($entries) {
        foreach ($entries as $item) {


        $cartPrices = $item->product->carts_item()->pluck('price');
        if ($cartPrices->isNotEmpty()) {
          $averagePrice = $cartPrices->avg();
        } else {
          $averagePrice = optional($item->product->product_prices->first())->value;
        }
        $cost = $item->price;
        $supplierCurrency = $item->order->currency ?? null;
        $productCurrency = optional($item->product->product_prices->first())->pricelist->currency->name ?? null;

        if ($supplierCurrency && $productCurrency && $supplierCurrency !== $productCurrency) {
          $exchange = Exchange::whereHas('base_currency', function ($q) use ($supplierCurrency) {
            $q->where('name', $supplierCurrency);
          })->whereHas('quote_currency', function ($q) use ($productCurrency) {
            $q->where('name', $productCurrency);
          })->latest()->first();

          if (!$exchange) {
            $exchange = Exchange::whereHas('base_currency', function ($q) use ($productCurrency) {
              $q->where('name', $productCurrency);
            })->whereHas('quote_currency', function ($q) use ($supplierCurrency) {
              $q->where('name', $supplierCurrency);
            })->latest()->first();

            if ($exchange) {
              $cost /= $exchange->value;
            }
          } else {
            $cost *= $exchange->value;
          }
        }

        if (!$item->product->costs->count()) {
          DB::table('product_costs')->updateOrInsert(
            ['product_id' => $item->product->id],
            ['price' => $averagePrice, 'cost' => $cost, 'date' => now(config('app.timezone')), 'created_by' => auth()->user()->name, 'last_modified_by' => auth()->user()->name, 'created_at' => now(config('app.timezone')), 'updated_at' => now(config('app.timezone'))]
          );
        } else {
          $oldcost = $item->product->costs()->latest()->first()->cost;
          if ($oldcost != $item->price) {
            $averageCost = ($oldcost + $cost) / 2;
            DB::table('product_costs')->insert([
              'product_id' => $item->product->id,
              'price' => $averagePrice,
              'cost' => $averageCost,
              'date' => now(config('app.timezone')),
              'created_by' => auth()->user()->name,
              'last_modified_by' => auth()->user()->name,
              'created_at' => now(config('app.timezone')),
              'updated_at' => now(config('app.timezone'))
            ]);
          }
        }
      }
      });
    } catch (\Throwable $e) {
      AllJob::where('id', $this->allJobId)->update([
        'status' => 'failed',
        'error' => $e->getMessage(),
      ]);

      throw $e;
    }

    try {

      Order::with('orders.product.costs')->chunk(500, function ($entries) {
        foreach ($entries as $order) {
          $cost = 0;
          foreach ($order->orders as $item) {
            if ($item->product->costs->isEmpty()) {
              $cost = 0;
              break;
            }
            if (!$item->product->costs->last()->cost) {
              $possiblecost = $item->product->costs->where('cost', '!=', null)->last();
              if ($possiblecost) {
                $cost += $possiblecost->cost * $item->quantity;
              } else {
                $cost = 0;
                break;
              }
            } else {
              $cost += optional($item->product->costs->last())->cost  * $item->quantity;
            }
          }
          if ($cost > 0) {
            $order->update(['avg_cost' => $cost]);
          }
        }
      });


      AllJob::where('id', $this->allJobId)->update([
        'status' => 'finished',
        'finished_at' => now(config('app.timezone')),
      ]);
    } catch (\Throwable $e) {
      AllJob::where('id', $this->allJobId)->update([
        'status' => 'failed',
        'error' => $e->getMessage(),
      ]);

      throw $e;
    }
  }
}
