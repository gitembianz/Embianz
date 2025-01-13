<?php

namespace App\Http\Livewire;

use App\Models\Promotion;
use App\Models\Voucher;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;


class Promotiontable extends Component
{
    use WithPagination;
    public $loadAmount = 20;
    public $search = '';
    public $orderBy = 'id';
    public $orderAsc = true;
    public $checked = [];
    public $selectPage = false;
    public $selectAll = false;
    public $idbeingremoved = null;
    public $selectedColumns = [];
    public $columns;
    public $row = null;
    public $single = false;
    public $multiple = false;
    public $editindex = null;
    public $item = [];
    public $vouchers;

    public function render()
    {
        return view('livewire.promotiontable', ['promotions' => $this->promotions]);
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
    public function mount($tableName)
    {
        $this->columns = Schema::getColumnListing($tableName);
        $this->selectedColumns = $this->columns;
    }
    public function showColumn($column)
    {
        return in_array($column, $this->selectedColumns);
    }
    public function updatedSelectPage($value)
    {
        if ($value) {
            $this->checked = $this->promotions->pluck('id')->map(fn($item) => (string) $item)->toArray();
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
        $this->checked = $this->promotionsQuery->pluck('id')->map(fn($item) => (string) $item)->toArray();
    }
    public function getPromotionsProperty()
    {
        return $this->promotionsQuery->paginate($this->loadAmount);
    }
    public function loadMore()
    {
        $this->loadAmount += 10;
    }
    public function getPromotionsQueryProperty()
    {
        return Promotion::search($this->search)
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc');
    }
    public function confirmItemRemoval($id)
    {
        $this->idbeingremoved = $id;
        $this->single = true;
    }
    public function confirmItemsRemoval()
    {
        $this->multiple = true;
    }
    public function cancel_delete()
    {
        $this->multiple = false;
        $this->single = false;
    }
    public function isChecked($id)
    {
        return in_array($id, $this->checked);
    }

    public function deleteRecords()
    {
        $items = Promotion::whereKey($this->checked)->get();
        foreach ($items as $item) {
            $item->delete();
        }
        $this->checked = [];
        $this->selectPage = false;
        $this->multiple = false;

        session()->flash('notification', [
            'message' => 'Records deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
    public function deleteSingleRecord()
    {
        $id = $this->idbeingremoved;
        $item = Promotion::findOrFail($id);

        $item->delete();
        $this->checked = array_diff($this->checked, [$id]);
        $this->single = false;
        session()->flash('notification', [
            'message' => 'Record deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
    public function edititem($index, $id)
    {
        $this->vouchers = Voucher::get();
        $this->editindex = $index;
        $this->row = $index;
        $record = Promotion::find($id);
        $this->item[$index] = [
            'name' => $record->name,
            'type' => $record->type,
            'details' => $record->details,
            'promotion_percent' => $record->promotion_percent,
            'promotion_value' => $record->promotion_value,
            'start_date' => $record->start_date,
            'end_date' => $record->end_date,
            'active' => $record->active == 1 ? true : false,
            'cooldown_timer' => $record->cooldown_timer,
            'cart_amount' => $record->cart_amount
        ];
    }
    public function canceledit()
    {
        $this->editindex = null;
        $this->item = [];
    }
    public function saveitem($index, $id)
    {
        $record = $this->item[$index] ?? null;
        if (!is_null($record)) {
            $new = Promotion::find($id);
            if (array_key_exists('name', $record)) {
                $new->name = $record['name'];
            }
            if (array_key_exists('type', $record)) {
                $new->type = $record['type'];
            }
            if (array_key_exists('details', $record)) {
                $new->details = $record['details'];
            }
            if (array_key_exists('promotion_percent', $record) && $record['promotion_percent'] != "") {
                $new->promotion_percent = $record['promotion_percent'];
                $new->promotion_value = null;
            }
            if (array_key_exists('promotion_value', $record) && $record['promotion_value'] != "") {
                $new->promotion_value = $record['promotion_value'];
                $new->promotion_percent = null;
            }
            if (array_key_exists('start_date', $record)) {
                $new->start_date = $record['start_date'];
            }
            if (array_key_exists('end_date', $record)) {
                $new->end_date = $record['end_date'];
            }
            if (array_key_exists('active', $record)) {
                $new->active = $record['active'];
            }
            if (array_key_exists('cooldown_timer', $record)) {
                if ($record['cooldown_timer'] == "") {
                    $new->cooldown_timer = 0;
                } else {
                    $new->cooldown_timer = $record['cooldown_timer'];
                }
            }
            if (array_key_exists('cart_amount', $record)) {
                if ($record['cart_amount'] == "") {
                    $new->cart_amount = 0;
                } else {
                    $new->cart_amount = $record['cart_amount'];
                }
            }
            $new->save();
            if ($new->type == "counter" && $new->active) {
                Promotion::where('active', true)->where('id', '!=', $new->id)->where('type', 'counter')->update(['active' => false]);
            }
            Cache::forget('promotions');
            session()->flash('notification', [
                'message' => 'Record edited successfully!',
                'type' => 'success',
                'title' => 'Success'
            ]);
        } else {
            session()->flash('notification', [
                'message' => 'Nothing was edited!',
                'type' => 'warning',
                'title' => 'Warning'
            ]);
        }
        $this->editindex = null;
        $this->item = [];
    }
    public function getcookieid($id)
    {
        $newCookieId = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 15);

        Promotion::where('id', $id)->update(['cookieid' => $newCookieId]);
        Cache::forget('promotions');

        session()->flash('notification', [
            'message' => 'Cookie ID updated successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
    public function getcookieids()
    {

        foreach ($this->promotions as $promotion) {
            $newCookieId = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 15);
            $promotion->cookieid = $newCookieId;
            $promotion->save();
        }
        Cache::forget('promotions');

        session()->flash('notification', [
            'message' => 'All cookie IDs have been refreshed successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
}
