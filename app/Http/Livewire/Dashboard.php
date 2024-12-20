<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\Session;

class Dashboard extends Component
{
    protected $listeners = ['cartCountUpdated' => '$refresh'];

    public function render()
    {
        $activeProductsCount = Product::where('active', 1)->count();
        $activeCategoriesCount = Category::where('active', 1)->count();
        $currentCartCount = Cart::count();
        $orderCount = Order::count();

        $previousCartCount = Session::get('previousCartCount', 0);

        if ($currentCartCount !== $previousCartCount) {
            Session::put('previousCartCount', $currentCartCount);
            if (app()->has('global_dashboard_newcart_sound') && app('global_dashboard_newcart_sound') === 'true') {

                $this->emit('cartCountUpdated', $currentCartCount);
            }
        }
        $previousOrderCount = Session::get('previousOrderCount', 0);

        if ($orderCount !== $previousOrderCount) {
            Session::put('previousOrderCount', $orderCount);
            if (app()->has('global_dashboard_neworder_sound') && app('global_dashboard_neworder_sound') === 'true') {

                $this->emit('orderCountUpdated', $orderCount);
            }
        }

        return view('livewire.dashboard')
            ->with('activeProductsCount', $activeProductsCount)
            ->with('activeCategoriesCount', $activeCategoriesCount)
            ->with('cartCount', $currentCartCount)
            ->with('orderCount', $orderCount);
    }
}
