<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;


class StoreSearch extends Component
{
    use WithPagination;

    public $search;
    public $loadAmount;

    public $showproducts = true;
    public $showcategories = false;
    public $session_id;
    public $quantity;


    public function render()
    {
        return view('livewire.store-search', [
            'products' => $this->products,
            'categories' => $this->categories
        ]);
    }
    public function mount($data = null)
    {
        if ($data != null) {
            $this->search = urldecode($data);
            $search_from_session = session()->get('search_values', []);
            if (isset($search_from_session['value']) && $search_from_session['value'] != $data) {
                session()->put('search_values', [
                    'value' => $data,
                    'loadAmount' => app('global_limit_load')
                ]);
                $this->loadAmount = app('global_limit_load');
            } else {
                if (isset($search_from_session['loadAmount'])) {
                    $this->loadAmount = $search_from_session['loadAmount'];
                } else {
                    $this->loadAmount = app('global_limit_load');
                }
            }
        } else {
            session()->forget('search_values');

            $this->search = "";
        }
        $this->session_id = request()->cookie('sessionId') ?? session()->getId();

        $this->quantity = app('global_low_stock');
    }

    public function loadMore()
    {
        $this->loadAmount += app('global_limit_load');
        session()->put('search_values', [
            'value' => $this->search,
            'loadAmount' =>  $this->loadAmount
        ]);
    }

    public function toggle($item)
    {
        if ($item == 'products') {
            $this->showproducts = true;
            $this->showcategories = false;
        }
        if ($item == 'categories') {
            $this->showproducts = false;
            $this->showcategories = true;
        }
    }

    public function getProductsProperty()
    {
        if ($this->search != "") {
            if (app()->has('global_cache_data') && app('global_cache_data') === 'true') {
                $searchTerms = explode(' ', $this->search);

                $filteredProducts = app()->make('cached_products')->filter(function ($product) use ($searchTerms) {
                    $matches = false;

                    foreach ($searchTerms as $term) {
                        if (
                            str_contains($product->id, $term) ||
                            str_contains(strtolower($product->name), $term) ||
                            str_contains(strtolower($product->ean), $term) ||
                            str_contains(strtolower($product->short_description), $term) ||
                            str_contains(strtolower($product->sku), $term)
                        ) {
                            $matches = true;
                            break;
                        }
                    }

                    return $matches
                        && $product->active
                        && $product->type != 'parent'
                        && $product->start_date <= now()->format('Y-m-d')
                        && $product->end_date >= now()->format('Y-m-d');
                })->sortByDesc('popularity')->sortByDesc('innerid');

                $currentPage = LengthAwarePaginator::resolveCurrentPage();

                $currentPageItems = $filteredProducts->slice(($currentPage - 1) * $this->loadAmount, $this->loadAmount);

                return new LengthAwarePaginator(
                    $currentPageItems,
                    $filteredProducts->count(),
                    $this->loadAmount,
                    $currentPage,
                    ['path' => LengthAwarePaginator::resolveCurrentPath()]
                );
            } else {
                return Product::search($this->search)
                    ->select('id', 'name', 'seo_id', 'short_description', 'type', 'quantity')
                    ->where('active', true)
                    ->where('type', '!=', 'parent')
                    ->where('start_date', '<=',  now()->format('Y-m-d'))
                    ->where('end_date', '>=',  now()->format('Y-m-d'))
                    ->with([
                        'media' => function ($query) {
                            $query->select('path', 'name', 'type')->where('type', 'main');
                        },
                        'product_prices' => function ($query) {
                            $query->select('product_id', 'value', 'discount', 'value_no_discount', 'pricelist_id');
                        },
                        'wishlists' => function ($query) {
                            $query->select('id', 'product_id')->where('session_id', $this->session_id);
                        },
                        'product_categories' => function ($query) {
                            $query->select('product_id', 'category_id', 'primary_category')
                                ->where('primary_category', true);
                            $query->with(['category' => function ($query) {
                                $query->select('id', 'short_description', 'seo_id');
                            }]);
                        }
                    ])
                    ->orderBy('popularity', 'DESC')
                    ->orderBy('innerid', 'ASC')
                    ->paginate($this->loadAmount);
            }
        } else {
            return collect();
        }
    }



    public function getCategoriesProperty()
    {
        if ($this->search != "") {
            if (app()->has('global_cache_data') && app('global_cache_data') === 'true') {
                $searchTerms = explode(' ', strtolower($this->search));

                $filteredCategories = app()->make('cached_categories')->filter(function ($category) use ($searchTerms) {
                    $matches = false;

                    foreach ($searchTerms as $term) {
                        if (
                            str_contains(strtolower(strip_tags($category->name)), $term) ||
                            str_contains(strtolower($category->short_description), $term)
                        ) {
                            $matches = true;
                            break;
                        }
                    }

                    return $matches
                        && $category->active
                        && $category->start_date <= now()->format('Y-m-d')
                        && $category->end_date >= now()->format('Y-m-d');
                });

                $currentPage = LengthAwarePaginator::resolveCurrentPage();

                $currentPageItems = $filteredCategories->slice(($currentPage - 1) * $this->loadAmount, $this->loadAmount);

                return new LengthAwarePaginator(
                    $currentPageItems,
                    $filteredCategories->count(),
                    $this->loadAmount,
                    $currentPage,
                    ['path' => LengthAwarePaginator::resolveCurrentPath()]
                );
            } else {
                return Category::search($this->search)
                    ->select('id', 'name', 'seo_id', 'short_description', 'long_description')
                    ->where('active', true)
                    ->where('start_date', '<=', now()->format('Y-m-d'))
                    ->where('end_date', '>=', now()->format('Y-m-d'))
                    ->with([
                        'media' => function ($query) {
                            $query->select('path', 'name')->where('type', 'min');
                        }
                    ])
                    ->paginate($this->loadAmount);
            }
        } else {
            return collect();
        }
    }
}
