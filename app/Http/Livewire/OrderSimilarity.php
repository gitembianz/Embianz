<?php

namespace App\Http\Livewire;

use App\Models\Order;
use App\Models\Order_Item;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;


class OrderSimilarity extends Component
{
  use WithPagination;
  public $columns = ['Order', 'Products', 'Similarity'];
  public $selectedColumns = [];
  public $order;
  public $orderId;
  public $showrelated = false;
  public $checked = [];


  public function mount($order)
  {
    $this->orderId = $order->id;
    $this->order = $order;
    $this->selectedColumns = $this->columns;
  }
  public function isChecked($ids)
  {
    return in_array($ids, $this->checked);
  }
  public function dowlandproducts()
  {
    $orderIds = collect($this->checked)
      ->flatMap(fn($idString) => explode(',', $idString))
      ->push($this->orderId)
      ->unique()
      ->toArray();

    $products = Order_Item::whereIn('order_id', $orderIds)
      ->with(['product'])
      ->get()
      ->groupBy(fn($item) => $item->product->name . '|' . $item->product->sku)
      ->map(function ($items) {
        return [
          'name' => $items->first()->product->name,
          'sku' => $items->first()->product->sku,
          'quantity' => $items->sum('quantity'),
        ];
      })
      ->values();

    $bom = "\xEF\xBB\xBF";

    $csvData = "Name,SKU,Quantity\n";

    foreach ($products as $product) {
      $csvData .= '"' . addslashes($product['name']) . '",'
        . '"' . $product['sku'] . '",'
        . $product['quantity'] . "\n";
    }
    $this->checked = [];
    session()->flash('notification', [
      'message' => 'Record downland successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
    return Response::streamDownload(function () use ($bom, $csvData) {
      echo $bom . $csvData;
    }, 'orders_products.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
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
      ->where('status_id', app('global_order_processing'))
      ->with([
        'orders.product' => function ($query) {
          $query->withCount([
            'orders_item as interim_quantity' => function ($query) {
              $query->whereHas('order', function ($q) {
                $q->where('status_id', app('global_order_processing'));
              })->select(DB::raw('sum(quantity)'));
            }
          ]);
        }
      ])
      ->get();

    $similarOrders = collect();

    foreach ($allOrders as $order) {
      $orderProducts = $order->orders;
      if ($orderProducts->isEmpty()) {
        continue;
      }

      $orderProductIds = $orderProducts->pluck('product_id')->unique();
      $matchingCount = $orderProductIds->intersect($referenceProductIds)->count();

      if ($matchingCount === 0) {
        continue;
      }

      $minProductCount = min($referenceProductCount, $orderProductIds->count());
      $similarityPercentage = ($matchingCount / $minProductCount) * 100;

      $allProductsValid = $orderProducts->every(function ($product) {
        $pr = $product->product;
        if (!$pr) return false;
        $interimQuantity = ($pr->quantity ?? 0) + ($pr->interim_quantity ?? 0);
        return $interimQuantity >= $product->quantity;
      });

      if ($similarityPercentage >= 50 && $allProductsValid) {
        $products = $orderProducts->map(function ($product) {
          return [
            'id' => $product->product_id,
            'sku' => $product->product->sku ?? null,
            'name' => $product->product->name ?? 'Unknown Product',
            'quantity' => $product->quantity,
          ];
        })->values();

        $similarOrders->push([
          'order' => $order,
          'similarity' => round($similarityPercentage, 2),
          'products' => $products,
        ]);
      }
    }

    return $similarOrders->sortByDesc('similarity')->values();
  }

  public function render()
  {
    return view('livewire.order-similarity', [
      'similarities' => $this->similarities
    ]);
  }
}
