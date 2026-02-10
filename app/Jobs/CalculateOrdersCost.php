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
    protected string $userName;

    /**
     * Create a new job instance.
     */
    public function __construct(int $allJobId, string $userName)
    {
        $this->allJobId = $allJobId;
        $this->userName = $userName;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        AllJob::where('id', $this->allJobId)->update(['status' => 'processing']);

        try {
            // 1. Fetch ALL exchanges once to avoid DB hits inside loops
            $exchanges = Exchange::with(['base_currency', 'quote_currency'])->get();

            // 2. Process Supplier Items and Calculate Product Costs
            Order_Supplier_Item::with([
                'order_supplier.currency_info', 
                'product.carts_item', 
                'product.product_prices.pricelist.currency', 
                'product.costs'
            ])
            ->whereHas('order_supplier', fn($query) => $query->where('status', 'closed'))
            ->chunk(500, function ($entries) use ($exchanges) {
                foreach ($entries as $item) {
                    $product = $item->product; 
                    if (!$product) continue;

                    $cartPrices = $product->carts_item->pluck('price');
                    $averagePrice = $cartPrices->isNotEmpty() 
                        ? $cartPrices->avg() 
                        : optional($product->product_prices->first())->value;

                    $cost = $item->price;
                    
                    // Currency logic using the new currency_info relationship
                    $supplierCurrency = $item->order_supplier->currency_info->name ?? $item->order_supplier->currency;
                    $productCurrency = optional($product->product_prices->first())->pricelist->currency->name ?? null;

                    if ($supplierCurrency && $productCurrency && $supplierCurrency !== $productCurrency) {
                        $exchange = $exchanges->where('base_currency.name', $supplierCurrency)
                                              ->where('quote_currency.name', $productCurrency)
                                              ->first();
                        
                        if ($exchange) {
                            $cost *= $exchange->value;
                        } else {
                            $reverse = $exchanges->where('base_currency.name', $productCurrency)
                                                 ->where('quote_currency.name', $supplierCurrency)
                                                 ->first();
                            if ($reverse) $cost /= $reverse->value;
                        }
                    }

                    $this->updateProductCost($product, $averagePrice, $cost);
                }
            });

            // 3. Update Order Average Costs
            Order::with('orders.product.costs')->chunk(200, function ($orders) {
                foreach ($orders as $order) {
                    $totalOrderCost = 0;
                    foreach ($order->orders as $orderItem) {
                        $lastCostRecord = $orderItem->product->costs->last();
                        $itemCost = $lastCostRecord ? $lastCostRecord->cost : 0;
                        $totalOrderCost += ($itemCost * $orderItem->quantity);
                    }
                    
                    if ($totalOrderCost > 0) {
                        $order->update(['avg_cost' => $totalOrderCost]);
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
                'error' => $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine(),
            ]);
            throw $e;
        }
    }

    /**
     * Logic to insert or update the product_costs table.
     */
    private function updateProductCost($product, $price, $cost)
    {
        $lastCostRecord = $product->costs->last();
        $timestamp = now(config('app.timezone'));

        if (!$lastCostRecord) {
            DB::table('product_costs')->insert([
                'product_id' => $product->id,
                'price' => $price,
                'cost' => $cost,
                'date' => $timestamp,
                'created_by' => $this->userName,
                'last_modified_by' => $this->userName,
                'created_at' => $timestamp,
                'updated_at' => $timestamp
            ]);
        } elseif ((float)$lastCostRecord->cost !== (float)$cost) {
            $averageCost = ($lastCostRecord->cost + $cost) / 2;
            DB::table('product_costs')->insert([
                'product_id' => $product->id,
                'price' => $price,
                'cost' => $averageCost,
                'date' => $timestamp,
                'created_by' => $this->userName,
                'last_modified_by' => $this->userName,
                'created_at' => $timestamp,
                'updated_at' => $timestamp
            ]);
        }
    }
}