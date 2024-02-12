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
        return Product::name($this->search)->where('active', true)->with([
            'media' => function ($query) {
                $query->where('type', 'min'); // Filter and limit the media relationship
            },
            'product_prices.pricelist.currency'
        ])->orderBy('popularity', 'desc')->limit(app('global_limit_searchitems'))->get();
    }

    public function getCatsProperty()
    {
        return Category::name($this->search)->where('active', true)->with([
            'media' => function ($query) {
                $query->where('type', 'min'); // Filter and limit the media relationship
            }
        ])->limit(app('global_limit_searchitems'))->get();
    }
}