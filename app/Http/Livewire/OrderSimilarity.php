<?php

namespace App\Http\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;


class OrderSimilarity extends Component
{
    use WithPagination;
    public $columns = ['Reference', 'Orders', 'Products', 'Similarity'];
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
            return collect();
        }

        $referenceOrder = $this->order;
        $referenceProductIds = $referenceOrder->orders->pluck('product_id')->unique();
        $referenceProductCount = $referenceProductIds->count();

        if ($referenceProductCount === 0) {
            return collect();
        }

        $allOrders = Order::where('id', '!=', $referenceOrder->id)
            ->where('status_id', 31)
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
        $allProducts = collect();

        foreach ($allOrders as $order) {
            $orderProducts = $order->orders;
            $orderProductIds = $orderProducts->pluck('product_id')->unique();
            $matchingCount = $orderProductIds->intersect($referenceProductIds)->count();
            $orderProductCount = $orderProductIds->count();

            if ($orderProductCount === 0) {
                continue;
            }


            $minProductCount = min($referenceProductCount, $orderProductCount);
            $similarityPercentage = ($matchingCount / $minProductCount) * 100;

            $allProductsValid = $orderProducts->every(function ($product) {
                $pr = $product->product;
                $interimQuantity = ($pr->quantity ?? 0) + ($pr->interim_quantity ?? 0);
                return $interimQuantity >= $product->quantity;
            });

            if ($similarityPercentage >= 50 && $allProductsValid) {
                foreach ($orderProducts as $product) {
                    $allProducts->push([
                        'id' => $product->product_id,
                        'sku' => $product->product->sku,
                        'name' => $product->product->name ?? 'Unknown Product',
                        'quantity' => $product->quantity,
                    ]);
                }

                $similarOrders->push([
                    'order' => $order,
                    'similarity' => $similarityPercentage,
                ]);
            }
        }


        $groupedProducts = $allProducts->groupBy('id')->map(function ($products) {
            return [
                'name' => $products->first()['name'],
                'sku' => $products->first()['sku'],
                'total_quantity' => $products->sum('quantity'),
            ];
        })->values();

        $referenceProductCounts = $referenceOrder->orders->groupBy('product_id')->map(function ($products) {
            $firstProduct = $products->first()->product;
            return [
                'name' => $firstProduct->name ?? 'Unknown Product',
                'sku' => $firstProduct->sku ?? 'Unknown Product',
                'total_quantity' => $products->sum('quantity'),
            ];
        })->values();

        return [
            'reference' => [
                'order' => $referenceOrder,
                'products' => $referenceProductCounts,
            ],
            'similar' => $similarOrders->groupBy('similarity')->map(function ($orders, $similarityPercentage) use ($groupedProducts) {
                return [
                    'orders' => $orders,
                    'products' => $groupedProducts,
                ];
            })->sortKeysDesc(),
        ];
    }






    public function render()
    {
        return view('livewire.order-similarity', [
            'similarities' => $this->similarities
        ]);
    }
}
