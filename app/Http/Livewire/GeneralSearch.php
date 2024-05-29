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
            return Product::name($this->search)
                ->select('id', 'name', 'seo_id', 'short_description')
                ->where('active', true)
                ->where('start_date', '<=',  now()->format('Y-m-d'))
                ->where('end_date', '>=',  now()->format('Y-m-d'))
                ->with([
                    'media' => function ($query) {
                        $query->select('path', 'name')->where('type', 'min');
                    },
                    'product_prices' => function ($query) {
                        $query->select('product_id', 'value', 'pricelist_id')
                            ->with(['pricelist' => function ($query) {
                                $query->select('id', 'currency_id')->with('currency:id,name,symbol');
                            }]);
                    }
                ])
                ->orderBy('popularity', 'desc')
                ->limit(app('global_limit_searchitems'))
                ->get();
        } else {
            return collect();
        }
    }


    public function getCatsProperty()
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
                ->limit(app('global_limit_searchitems'))
                ->get();
        } else {
            return collect();
        }
    }
}
