<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;


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
            $this->search = $data;
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
        $this->session_id = $this->getSessionId();
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


    private function getSessionId()
    {
        if (array_key_exists('sessionId', $_COOKIE)) {
            return $_COOKIE['sessionId'];
        } else {
            $sessionId = session()->getId();
            setcookie('sessionId', $sessionId, time() + 30 * 24 * 60 * 60, '/', null, false, true);
            return $sessionId;
        }
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
            return Product::name($this->search)
                ->select('id', 'name', 'seo_id', 'short_description', 'quantity')
                ->where('active', true)
                ->where('start_date', '<=',  now()->format('Y-m-d'))
                ->where('end_date', '>=',  now()->format('Y-m-d'))
                ->with([
                    'media' => function ($query) {
                        $query->select('path', 'name')->where('type', 'main');
                    },
                    'product_prices' => function ($query) {
                        $query->select('product_id', 'value', 'pricelist_id')
                            ->with(['pricelist' => function ($query) {
                                $query->select('id', 'currency_id')->with('currency:id,name,symbol');
                            }]);
                    },
                    'wishlists' => function ($query) {
                        $query->select('id', 'product_id')->where('session_id', $this->session_id);
                    },
                ])
                ->orderBy('popularity', 'desc')
                ->paginate($this->loadAmount);
        } else {
            return collect();
        }
    }


    public function getCategoriesProperty()
    {
        if ($this->search != "") {
            return Category::search_by_name($this->search)
                ->select('id', 'name', 'seo_id', 'short_description', 'long_description')
                ->where('active', true)
                ->where('start_date', '<=',  now()->format('Y-m-d'))
                ->where('end_date', '>=',  now()->format('Y-m-d'))
                ->with([
                    'media' => function ($query) {
                        $query->select('path', 'name')->where('type', 'min');
                    }
                ])
                ->paginate($this->loadAmount);
        } else {
            return collect();
        }
    }
}
