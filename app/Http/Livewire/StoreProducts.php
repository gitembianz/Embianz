<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Models\Wishlist;
use Livewire\WithPagination;

class StoreProducts extends Component
{
  use WithPagination;

  public $loadAmount;
  public $search = "";
  public $session_id;
  public $orderBy = 'best_selling';
  public $category;
  public $queryfilters = [];
  public $selectedKeys = [];
  public $selectedvariantsKeys = [];
  public $selectedfilters = [];
  public $wishlistItems;

  public function render()
  {
    return view('livewire.store-products', [
      'products' => $this->products,
      'filtervalues' => $this->filtervalues
    ]);
  }

  public function isInWishlist($productId)
  {
    return in_array($productId, $this->wishlistItems);
  }

  public function mount($category = null)
  {
    $this->session_id = request()->cookie('sessionId') ?? session()->getId();
    $this->wishlistItems = Wishlist::where('session_id', $this->session_id)->pluck('product_id')->toArray();
    if ($category) {
      $decodedCategory = json_decode(htmlspecialchars_decode($category), true);
      $this->category = Category::select('id', 'name', 'short_description', 'long_description', 'seo_id', 'accepted_items', 'display_variant_price')->find($decodedCategory['id']);
    } else {
      if (app()->has('global_default_category')) {
        $this->category = Category::select('id', 'name', 'short_description', 'long_description', 'seo_id', 'accepted_items', 'display_variant_price')->find(app('global_default_category')) ?? null;
      }
    }
    if (isset($filteredValues['loadAmount'])) {
      $this->loadAmount = $filteredValues['loadAmount'];
    } else {
      $this->loadAmount = app('global_limit_load');
    }

    $filteredValues = session()->get('filtered_values', []);
    if (isset($filteredValues['category_id']) && $this->category != null && $filteredValues['category_id'] == $this->category->id) {
      if (isset($filteredValues['queryfilters'])) {
        $this->queryfilters = $filteredValues['queryfilters'];
        $this->applyFilter();
      }
    } else {
      session()->forget('filtered_values');
      $this->loadAmount = app('global_limit_load');
    }
  }

  public function loadMore()
  {
    $this->loadAmount += app('global_limit_load');
    if ($this->category != null) {
      session()->put('filtered_values', [
        'category_id' => $this->category->id,
        'loadAmount' =>  $this->loadAmount
      ]);
    } else {
      session()->put('filtered_values', [
        'loadAmount' =>  $this->loadAmount
      ]);
    }
  }

  // products function only for query, not from cache
  public function getProductsProperty()
  {
    $query = Product::search($this->search)
      ->where('active', true)
      ->where('start_date', '<=', now()->format('Y-m-d'))
      ->where('end_date', '>=', now()->format('Y-m-d'))
      ->with([
        'variants' => function ($query) {
          $query->with('product');
        },
        'reviews' => function ($query) {
          $query->select('product_id', 'count', 'value');
        },
        'product_categories' => function ($query) {
          $query->select('product_id', 'category_id', 'primary_category')
            ->where('primary_category', true);
          $query->with(['category' => function ($query) {
            $query->select('id', 'short_description', 'seo_id');
          }]);
        },
        'product_prices' => function ($query) {
          $query->select('product_id', 'value', 'discount', 'value_no_discount');
        },
        'media' => function ($query) {
          $query->select('path', 'name')->where('type', 'main');
        },
      ]);

    if ($this->category != null) {
      $query->whereHas('product_categories.category', function ($query) {
        $query->where('id', $this->category->id);
      });
      if ($this->category->accepted_items == 'default') {
        $query->where('type', '!=', 'parent');
      } else {
        if (empty($this->selectedKeys)) {
          $query->where('type', '!=', 'variant');
        }
      }
    }
    switch ($this->orderBy) {
      case 'best_selling':
        $query->orderBy('popularity', 'desc');
        break;
      case 'name_az':
        $query->orderBy('name');
        break;
      case 'name_za':
        $query->orderBy('name', 'desc');
        break;
      case 'date_old_new':
        $query->orderBy('created_at');
        break;
      case 'date_new_old':
        $query->orderBy('created_at', 'desc');
        break;
      case 'quantity_as':
        $query->where('quantity', '>', 0)->orderBy('quantity');
        break;
      case 'quantity':
        $query->where('quantity', '>', 0)->orderBy('quantity', 'desc');
        break;
      case 'price_as':
        $query->orderByRaw("(SELECT CAST(value AS DECIMAL(10, 2)) FROM pricelist_entries WHERE product_id = products.id) asc");
        break;
      case 'price_ds':
        $query->orderByRaw("(SELECT CAST(value AS DECIMAL(10, 2)) FROM pricelist_entries WHERE product_id = products.id) desc");
        break;
    }
    if (!empty($this->selectedKeys)) {
      $query->whereIn('id', $this->selectedKeys);
    }

    return $query->orderBy('popularity', 'DESC')
      ->orderBy('innerid', 'ASC')->paginate($this->loadAmount);
  }

  // filters fro cache
  public function getFilterValuesProperty()
  {
    $query = app('cached_specifications');

    if ($this->category != null) {
      $query = collect($query)->map(function ($spec) {
        $filteredValues = collect($spec['values'])->map(function ($value) {
          $filteredProducts = collect($value['products'])->filter(function ($product) {
            return in_array($this->category->id, $product['categories']);
          });

          $productData = $filteredProducts->map(function ($product) {
            if ($this->category->accepted_items === 'parents') {
              if ($product['parent_id'] != "" && $product['type'] != 'standard') {
                return [
                  'product_id' => $product['product_id'],
                  'parent_id' => $product['parent_id'],
                ];
              } elseif ($product['type'] != 'variant') {
                return [
                  'product_id' => $product['product_id'],
                ];
              }
            } else {
              return [
                'product_id' => $product['product_id'],
              ];
            }
            return null;
          })->filter();

          if ($productData->isNotEmpty()) {
            return [
              'product_data' => $productData->toArray(),
            ];
          }
          return null;
        })->filter();

        if ($filteredValues->isNotEmpty()) {
          return [
            'spec' => $spec['spec'],
            'sequence' => $spec['sequence'],
            'values' => $filteredValues->filter()->toArray(),
          ];
        }
        return null;
      })->filter();
    }

    return $query;
  }

  public function applyFilter()
  {
    $this->selectedKeys = [];
    $this->selectedfilters = [];
    $productIdsPerSpec = [];
    $ids = [];
    $specVariantIds = [];
    $totalspecs = 0;

    if (!empty($this->queryfilters)) {
      foreach ($this->queryfilters as $specName => $values) {
        $specProductIds = [];

        foreach ($values as $value => $isfilterselected) {
          if (array_values($isfilterselected)[0]) {

            $fullString = array_keys($isfilterselected)[0];
            $productIdsWithTypes = explode(',', $fullString);
            foreach ($productIdsWithTypes as $item) {
              $parts = explode('|', $item);
              if (isset($parts[1])) {
                $specVariantIds[$parts[1]][] = (string)$parts[0];
              } else {
                $specProductIds[] = $parts[0];
              }
            }
            $sanitizedValue = str_replace(',', '.', $value);

            $this->selectedfilters[$sanitizedValue] = $specName;
          }
        }
        if (!empty($specProductIds)) {
          $productIdsPerSpec[] = array_unique($specProductIds);
        } else {
          $productIdsPerSpec[] = [];
        }
        $totalspecs++;
      }

      if (!empty($productIdsPerSpec)) {
        $ids[] = array_intersect(...$productIdsPerSpec);
      }
      if (!empty($specVariantIds)) {
        foreach ($specVariantIds as $parentId => $variants) {
          $variantCount = array_count_values($variants);

          $maxCount = max($variantCount);
          if ($maxCount == $totalspecs) {

            $mostFrequentVariant = array_search($maxCount, $variantCount);
            $ids[] = [(string)$mostFrequentVariant];
          }
        }
      }
      if (!empty($ids)) {

        $ids = [array_merge(...$ids)];
      }

      if (count($ids) > 0) {
        $this->selectedKeys = $ids[0];
      } else {
        $this->selectedKeys =  [];
      }

      session()->put('filtered_values', [
        'category_id' => $this->category->id,
        'queryfilters' => $this->queryfilters
      ]);

      if (count($this->selectedKeys) > 0) {
        $this->emit('filtersApplied', count($this->selectedKeys));
      } else {
        $this->emit('filtersApplied', $this->products->total());
      }
    }
    return $this->products->whereIn('id', $this->selectedKeys);
  }


  public function clearall()
  {
    $this->queryfilters = [];
    session()->forget('filtered_values');
    $this->applyFilter();
  }

  public function removeSpec($key, $specname)
  {
    $sanitizedkey = str_replace('.', ',', $key);

    unset($this->queryfilters[$specname][$sanitizedkey]);
    if (empty($this->queryfilters[$specname])) {
      unset($this->queryfilters[$specname]);
    }
    if (empty($this->queryfilters)) {
      unset($this->queryfilters);
      session()->forget('filtered_values');
    } else {
      session()->put('filtered_values', [
        'category_id' => $this->category->id,
        'queryfilters' => $this->queryfilters
      ]);
    }
    unset($this->selectedfilters[$sanitizedkey]);
    $this->applyFilter();
  }
}
