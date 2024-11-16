<?php

namespace App\Http\Livewire;

use App\Models\Invoice;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;



class RelatedInvoices extends Component
{
    use WithPagination;
    public $perPage = 10;
    public $search = '';
    public $orderBy = 'id';
    public $orderAsc = true;
    public $checked = [];
    public $selectPage = false;
    public $selectAll = false;
    public $showrelated = false;
    public $col = false;
    public $all = false;
    public $idbeingremoved = null;
    public $columns = ['Id', 'Order', 'Type', 'Account', 'Date', 'Path', 'Created At', 'Updated At'];
    public $selectedColumns = [];
    public $object;
    public $objectid;
    public $row = null;
    public $single = false;
    public $multiple = false;
    public $previewurl = null;

    public function render()
    {
        $invoices = $this->invoices
            ->where(function ($query) {
                $query->whereHas('order', function ($subQuery) {
                    $subQuery->where('name', 'LIKE', '%' . $this->search . '%');
                })->orwhereHas('account', function ($subQuery) {
                    $subQuery->where('name', 'LIKE', '%' . $this->search . '%');
                });
            })->get();
        return view('livewire.related-invoices', [
            'invoices' => $invoices
        ]);
    }
    public function mount($relatedby, $id)
    {
        $this->object = $relatedby;
        $this->objectid = $id;
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
    public function updatedSelectPage($value)
    {
        if ($value) {
            $this->checked = $this->invoices->pluck('id')->map(fn($item) => (string) $item)->toArray();
        } else {
            $this->checked = [];
        }
    }
    public function swapSortDirection()
    {
        return $this->orderAsc === '1' ? '0' : '1';
    }
    public function updatedChecked()
    {
        $this->selectPage = false;
    }
    public function isChecked($id)
    {
        return in_array($id, $this->checked);
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
    public function selectAll()
    {
        $this->selectAll = true;
        $this->checked = $this->invoicesQuery->pluck('id')->map(fn($item) => (string) $item)->toArray();
    }
    public function load()
    {
        $this->perPage += 10;
    }
    public function getInvoicesProperty()
    {
        return $this->invoicesQuery;
    }
    public function getInvoicesQueryProperty()
    {
        if ($this->object === 'order') {
            return Invoice::where('order_id', $this->objectid)
                ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc');
        } else {
            return Invoice::where('account_id', $this->objectid)
                ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc');
        }
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
    public function deleteSingleRecord()
    {
        $item = Invoice::findOrFail($this->idbeingremoved);
        if (File::exists($item->path)) {
            File::delete($item->path);
        }
        $item->delete();
        $this->checked = array_diff($this->checked, [$this->idbeingremoved]);
        $this->single = false;
        session()->flash('notification', [
            'message' => 'Record deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
    public function deleteRecords()
    {
        $items = Invoice::whereKey($this->checked)->get();
        foreach ($items as $item) {
            $del = Invoice::find($item->id);
            if (File::exists($del->path)) {
                File::delete($del->path);
            }
            $del->delete();
        }

        $this->checked = [];
        $this->selectPage = false;
        $this->multiple = false;
        session()->flash('notification', [
            'message' => 'Records deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
        $this->emit('orderUpdated');
    }

    public function downloadInvoice($id)
    {
        $item = Invoice::findOrFail($id);
        $filePath = public_path($item->path);

        if (!file_exists($filePath)) {
            session()->flash('notification', [
                'message' => 'The requested file does not exist.',
                'type' => 'error',
                'title' => 'Error',
            ]);
            return;
        }

        return response()->download($filePath);
    }
    public function previewInvoice($id)
    {
        $item = Invoice::findOrFail($id);
        $filePath = $item->path;
        if (!file_exists($filePath)) {
            session()->flash('notification', [
                'message' => 'The requested file does not exist.',
                'type' => 'error',
                'title' => 'Error',
            ]);
            return;
        } else {
            $this->previewurl = $item->path;
            return;
        }
    }
    public function cancel_preview()
    {
        $this->previewurl = null;
    }
}
