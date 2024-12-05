<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Order;
use App\Models\UserPromotions;
use App\Models\UserSessions;
use App\Models\Wishlist;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;



class RelatedSession extends Component
{
    use WithPagination;
    public $perPage = 10;
    public $search = '';
    public $orderBy = 'id';
    public $orderAsc = true;
    public $relatedby;
    public $session_id;
    public $selectedColumns = [];
    public $columns = [];
    public $row = null;
    public $loadAmount = 15;
    public $showrelated = false;
    public $delete = false;
    public $idbeingremoved = null;



    public function render()
    {
        return view('livewire.related-session', [
            'relateds' => $this->relateds
        ]);
    }
    public function confirmItemRemoval($id)
    {
        $this->delete = true;
        $this->idbeingremoved = $id;
    }

    public function cancelItemRemoval()
    {
        $this->delete = false;
    }

    public function deleteRecord()
    {
        DB::table($this->relatedby)->where('id', $this->idbeingremoved)->delete();
        $this->delete = false;
        session()->flash('notification', [
            'message' => 'Record deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }

    public function mount($relatedby, $session_id)
    {
        $this->relatedby = $relatedby;
        $this->session_id = $session_id;
        $this->columns = Schema::getColumnListing($relatedby);
        $this->selectedColumns = $this->columns;
    }
    public function expandRow($index)
    {
        if ($this->row  === null) {
            $this->row = $index;
        } elseif ($this->row != $index) {
            $this->row = $index;
        } else {
            $this->row = null;
        }
    }
    public function showColumn($column)
    {
        return in_array($column, $this->selectedColumns);
    }
    public function swapSortDirection()
    {
        return $this->orderAsc === '1' ? '0' : '1';
    }
    public function sortBy($columnName)
    {

        if ($this->orderBy === $columnName) {
            $this->orderAsc = $this->swapSortDirection();
        } else {
            $this->orderAsc = '1';
        }

        $this->orderBy = $columnName;
    }
    public function getRelatedsProperty()
    {
        if ($this->relatedby === 'sessions') {
            return DB::table($this->relatedby)
                ->where('innersession', $this->session_id)
                ->where(function ($query) {
                    $query->where('user_agent', 'like', '%' . $this->search . '%')
                        ->orWhere('last_activity', 'like', '%' . $this->search . '%');
                })
                ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
                ->paginate($this->loadAmount);
        } elseif ($this->relatedby === 'carts') {
            return Cart::search($this->search)->where('session_id', $this->session_id)
                ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->paginate($this->loadAmount) ?? collect();
        } elseif ($this->relatedby === 'orders') {
            return Order::search($this->search)->where('session_id', $this->session_id)
                ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->paginate($this->loadAmount) ?? collect();
        } elseif ($this->relatedby === 'wishlist') {
            return Wishlist::where('session_id', $this->session_id)->with('product')
                ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->paginate($this->loadAmount) ?? collect();
        } elseif ($this->relatedby === 'user_promotions') {
            $id = UserSessions::where('sessions', $this->session_id)->first()->id;
            return UserPromotions::where('session_id', $id)->with('promotion')
                ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->paginate($this->loadAmount) ?? collect();
        } else {
            return collect();
        }
    }
}