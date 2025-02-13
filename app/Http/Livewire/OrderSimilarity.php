<?php

namespace App\Http\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;


class OrderSimilarity extends Component
{
    use WithPagination;
    public $columns = ['Reference', 'Orders'];
    public $selectedColumns = [];
    public $order;
    public $orderId;
    public $showrelated = false;

    public function mount($order)
    {
        $this->orderId = $order->id;
        $this->order = $order;
        $this->selectedColumns = $this->columns;
    }
    public function showColumn($column)
    {
        return in_array($column, $this->selectedColumns);
    }
    public function getSimilaritiesProperty()
    {
        if (!$this->order || !$this->order->orders) {
            return collect(); // Return empty collection if no orders exist
        }

        $referenceOrder = $this->order;
        $referenceProductIds = $referenceOrder->orders->pluck('product_id')->unique();
        $referenceProductCount = $referenceProductIds->count();

        if ($referenceProductCount === 0) {
            return collect();
        }

        $allOrders = Order::where('id', '!=', $referenceOrder->id)
            ->with([
                'orders.product' => function ($query) {
                    $query->withCount([
                        'orders_item as interim_quantity' => function ($query) {
                            $query->whereHas('order', function ($q) {
                                $q->where('status_id', 31);
                            })->select(DB::raw('sum(quantity)'));
                        }
                    ]);
                }
            ])
            ->get();

        $similarOrders = collect();

        foreach ($allOrders as $order) {
            $orderProducts = $order->orders;
            $orderProductIds = $orderProducts->pluck('product_id')->unique();
            $matchingCount = $orderProductIds->intersect($referenceProductIds)->count();

            $orderProductCount = $orderProductIds->count();
            if ($orderProductCount === 0) {
                continue;
            }

            $similarityPercentage = ($matchingCount / $orderProductCount) * 100;

            $allProductsValid = $orderProducts->every(function ($product) {
                $pr = $product->product;
                $interimQuantity = ($pr->quantity ?? 0) + ($pr->interim_quantity ?? 0);
                return $interimQuantity >= $product->quantity;
            });

            if ($similarityPercentage >= (app()->has('global_	min_limit_similarity') ? app('global_	min_limit_similarity') : 50) && $allProductsValid) {
                $similarOrders->push([
                    'order' => $order,
                    'similarity' => $similarityPercentage,
                ]);
            }
        }

        return $similarOrders->groupBy('similarity')->sortKeysDesc();
    }



    public function render()
    {
        return view('livewire.order-similarity', [
            'similarities' => $this->similarities
        ]);
    }
}
