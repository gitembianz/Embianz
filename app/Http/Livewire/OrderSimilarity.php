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
  public $limitselect;
  public $orderIds = [];
  public $notprocesabbleorderIds = [];
  public $reachmaxselect = false;
  public $message = '';
  public $minlimitsimilarity;


  public function mount($order)
  {
    $this->orderId = $order->id;
    $this->order = $order;
    $this->selectedColumns = $this->columns;
    $this->limitselect = app()->has('global_order_similarity_limit')
      ? app('global_order_similarity_limit')
      : 5;

    $this->minlimitsimilarity = app()->has('global_min_limit_similarity')
      ? app('global_min_limit_similarity')
      : 50;
  }

  public function getSimilaritiesProperty()
  {
    if (!$this->order || !$this->order->orders) {
      $this->orderIds = [];
      return collect();
    }

    $referenceOrder = $this->order;
    $referenceProductIds = $referenceOrder->orders->pluck('product_id')->unique();
    $referenceProductCount = $referenceProductIds->count();

    if ($referenceProductCount === 0) {
      $this->orderIds = [];
      return collect();
    }

    $allOrders = Order::where('status_id', app('global_statuses')['order_processing'])
      ->with([
        'orders.product' => function ($query) {
          $query->withCount([
            'orders_item as interim_quantity' => function ($q) {
              $q->whereHas('order', function ($orderQ) {
                $orderQ->where('status_id', app('global_statuses')['order_processing']);
              })
                ->select(DB::raw('coalesce(sum(quantity), 0)'));
            },
          ]);
        },
      ])->get();

    $similarOrders = collect();
    $collectedIds = [];

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

      $allProductsValid = $orderProducts->every(function ($orderItem) {
        $product = $orderItem->product;
        if (!$product) return false;

        $totalAvailable = ($product->quantity ?? 0) + ($product->interim_quantity ?? 0);
        return $totalAvailable >= $orderItem->quantity;
      });

      if ($similarityPercentage >= $this->minlimitsimilarity) {
        $products = $orderProducts->map(function ($item) {
          $product = $item->product;
          return [
            'id' => $item->product_id,
            'sku' => $product->sku ?? null,
            'name' => $product->name ?? 'Unknown Product',
            'quantity' => $item->quantity,
            'available' => ($product->quantity ?? 0) + ($product->interim_quantity ?? 0),
          ];
        })->values();

        $similarOrders->push([
          'order' => $order,
          'similarity' => round($similarityPercentage, 2),
          'products' => $products,
          'valid' => $allProductsValid,
        ]);

        $collectedIds[] = $order->id;
      }
    }

    $this->orderIds = $collectedIds;

    return $similarOrders->sortByDesc('similarity')->values();
  }

  public function isChecked($ids)
  {
    $this->checked = is_array($this->checked) ? $this->checked : [];

    if (count($this->checked) > $this->limitselect) {
      $this->reachmaxselect = true;
      $this->message = "You can select a maximum of {$this->limitselect} items.";

      return false;
    }
    if (in_array($ids, $this->checked)) {
      return true;
    }
    return false;
  }

  public function dowlandproducts()
  {
    $orderIds = collect($this->checked)
      ->flatMap(fn($idString) => explode(',', $idString))
      ->unique()
      ->toArray();

    $orderNames = Order::whereIn('id', $orderIds)
      ->pluck('name', 'id')
      ->map(fn($name, $id) => $name ?: 'Order #' . $id);

    $items = Order_Item::whereIn('order_id', $orderIds)
      ->with('product')
      ->get();

    if ($items->isEmpty()) {
      session()->flash('notification', [
        'message' => 'No products found for selected orders.',
        'type' => 'warning',
        'title' => 'Notice'
      ]);
      return;
    }

    $products = $items
      ->map(fn($i) => [
        'id' => $i->product_id,
        'key' => trim(($i->product->name ?? 'Unknown') . ' ' . ($i->product->sku ?? '')),
        'order_id' => $i->order_id,
        'quantity' => $i->quantity,
      ])
      ->groupBy('key');

    $orderColumns = collect($orderIds)->map(fn($id) => $orderNames[$id] ?? 'Order #' . $id);
    $header = collect(['Product (Name + SKU)'])
      ->merge($orderColumns)
      ->push('Total')
      ->toArray();

    $rows = [];

    foreach ($products as $key => $entries) {
      $row = [$key];
      $total = 0;

      foreach ($orderIds as $orderId) {
        $qty = $entries
          ->where('order_id', $orderId)
          ->sum('quantity');
        $row[] = $qty ?: 0;
        $total += $qty;
      }

      $row[] = $total;
      $rows[] = $row;
    }

    $bom = "\xEF\xBB\xBF";
    $csvData = implode(',', $header) . "\n";

    foreach ($rows as $row) {
      $csvData .= implode(',', array_map(
        fn($v) => '"' . str_replace('"', '""', $v) . '"',
        $row
      )) . "\n";
    }

    $this->checked = [];
    session()->flash('notification', [
      'message' => 'Orders downloaded successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);

    return Response::streamDownload(function () use ($bom, $csvData) {
      echo $bom . $csvData;
    }, 'orders_products.csv', [
      'Content-Type' => 'text/csv; charset=UTF-8'
    ]);
  }


  public function showColumn($column)
  {
    return in_array($column, $this->selectedColumns);
  }

  public function updatedChecked()
  {
    $this->notprocesabbleorderIds = [];

    $diffids = array_values(array_diff(
      $this->orderIds,
      collect($this->checked)
        ->flatMap(fn($idString) => explode(',', $idString))
        ->push($this->orderId)
        ->unique()
        ->toArray()
    ));

    $productAvailability = [];

    foreach ($this->similarities as $item) {
      foreach ($item['products'] as $product) {
        $productId = $product['id'];
        $productAvailability[$productId] = $product['available'];
      }
    }

    foreach ($this->similarities as $item) {
      $orderId = $item['order']->id;

      if (in_array($orderId, $this->checked)) {
        foreach ($item['products'] as $product) {
          $productId = $product['id'];
          $productAvailability[$productId] -= $product['quantity'];

          if ($productAvailability[$productId] < 0) {
            $productAvailability[$productId] = 0;
          }
        }
      }
    }

    foreach ($this->similarities as $item) {
      $orderId = $item['order']->id;

      if (in_array($orderId, $diffids)) {
        $canProcess = true;

        foreach ($item['products'] as $product) {
          $productId = $product['id'];
          $needed = $product['quantity'];
          $available = $productAvailability[$productId] ?? 0;

          if ($available < $needed) {
            $canProcess = false;
            break;
          }
        }

        if (!$canProcess) {
          $this->notprocesabbleorderIds[] = $orderId;
        }
      }
    }
  }


  public function render()
  {
    return view('livewire.order-similarity', [
      'similarities' => $this->similarities
    ]);
  }
}
