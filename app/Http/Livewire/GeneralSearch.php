<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\Category;

class GeneralSearch extends Component
{
    public $search = '';
    public $active = false;
    protected $listeners = [
        'showsearch' => 'searchshow',
    ];

    public function render()
    {
        if ($this->active) {

            $data = [
                'objects' => $this->objects,
                'cats' => $this->cats,
            ];

            return view('livewire.general-search', $data);
        } else {
            return view('livewire.general-search');
        }
    }

    public function searchshow()
    {
        $this->active = true;
    }

    public function close()
    {
        $this->active = false;
        $this->search = '';
    }

    public function getObjectsProperty()
    {
        if ($this->search != "") {
            if (app()->has('global_cache_data') && app('global_cache_data') === 'true') {
                $searchTerms = explode(' ', $this->search);

                return app()->make('cached_products')->filter(function ($product) use ($searchTerms) {
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
                })->sortByDesc('popularity')->sortByDesc('innerid')->take(app('global_limit_searchitems'));
            } else {
                return Product::search($this->search)
                    ->select('id', 'name', 'seo_id', 'type', 'short_description')
                    ->where('active', true)
                    ->where('type', '!=', 'parent')
                    ->where('start_date', '<=', now()->format('Y-m-d'))
                    ->where('end_date', '>=', now()->format('Y-m-d'))
                    ->with([
                        'media' => function ($query) {
                            $query->select('path', 'name', 'type')->where('type', 'min');
                        },
                        'product_prices' => function ($query) {
                            $query->select('product_id', 'value', 'pricelist_id');
                        }
                    ])
                    ->orderBy('popularity', 'DESC')
                    ->orderBy('innerid', 'ASC')
                    ->limit(app('global_limit_searchitems'))
                    ->get();
            }
        } else {
            return collect();
        }
    }

    public function getCatsProperty()
    {
        if ($this->search != "") {
            if (app()->has('global_cache_data') && app('global_cache_data') === 'true') {
                $searchTerms = explode(' ', $this->search);

                return app()->make('cached_categories')->filter(function ($category) use ($searchTerms) {
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
                })->take(app('global_limit_searchitems'));
            } else {
                return Category::search($this->search)
                    ->select('id', 'name', 'seo_id')
                    ->where('active', true)
                    ->where('start_date', '<=',  now()->format('Y-m-d'))
                    ->where('end_date', '>=',  now()->format('Y-m-d'))
                    ->with([
                        'media' => function ($query) {
                            $query->select('path', 'name')->where('type', 'min');
                        }
                    ])
                    ->limit(app('global_limit_searchitems'))
                    ->get();
            }
        } else {
            return collect();
        }
    }
}
