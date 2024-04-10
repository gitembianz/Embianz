<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Wishlist;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class Wishliststable extends Component
{
    use WithPagination;

    public $tableName;
    public $loadAmount = 10;
    public $columns;
    public $search = '';
    public $selectedColumns;
    public $checked = [];
    public $selectPage = false;
    public $orderBy = 'id';
    public $orderAsc = true;
    public $selectAll = false;

    public function render()
    {
        return view('livewire.wishliststable', [
            'wishlists' => $this->wishlists
        ]);
    }
    public function mount($tableName)
    {
        $this->tableName = $tableName;
        $this->columns = Schema::getColumnListing($this->tableName);
        $this->selectedColumns = $this->columns;
    }
    public function showColumn($column)
    {
        return in_array($column, $this->selectedColumns);
    }
    public function updatedSelectPage($value)
    {
        if ($value) {
            $this->checked = $this->wishlists->pluck('id')->map(fn ($item) => (string) $item)->toArray();
        } else {
            $this->checked = [];
        }
    }
    public function updatedChecked()
    {
        $this->selectPage = false;
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
    public function swapSortDirection()
    {
        return $this->orderAsc === '1' ? '0' : '1';
    }
    public function selectAll()
    {
        $this->selectAll = true;
        $this->checked = $this->wishlists->pluck('id')->map(fn ($item) => (string) $item)->toArray();
    }
    public function isChecked($id)
    {
        return in_array($id, $this->checked);
    }
    public function getWishlistsProperty()
    {
        return Wishlist::select('session_id', DB::raw('GROUP_CONCAT(product_id) as product_ids'), DB::raw('COUNT(*) as count'))
            ->groupBy('session_id')
            ->limit($this->loadAmount)
            ->get();
    }
}