<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\Category;

class StoreSearch extends Component
{
    public $search = "";
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
    public function mount()
    {
        $this->session_id = $this->getSessionId();
        $this->quantity = app('global_low_stock');
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
                                $query->select('id', 'currency_id')->with('currency:id,name');
                            }]);
                    },
                    'wishlists' => function ($query) {
                        $query->select('id', 'product_id')->where('session_id', $this->session_id);
                    },
                ])
                ->get();
        } else {
            return collect();
        }
    }


    public function getCategoriesProperty()
    {
        if ($this->search != "") {
            return Category::search_by_name($this->search)
                ->select('id', 'name', 'seo_id')
                ->where('active', true)
                ->where('start_date', '<=',  now()->format('Y-m-d'))
                ->where('end_date', '>=',  now()->format('Y-m-d'))
                ->with([
                    'media' => function ($query) {
                        $query->select('path', 'name')->where('type', 'min');
                    }
                ])
                ->get();
        } else {
            return collect();
        }
    }
}
