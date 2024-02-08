<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Related_Products;
use Livewire\Component;
use Livewire\WithPagination;


class RelatedProducts extends Component
{
    use WithPagination;

    //related delclaration
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
    public $columns = ['Id', 'Short Description', 'Created At'];
    public $selectedColumns = [];
    public $product;

    //add declaration
    public $searchadd = '';
    public $orderByadd = 'id';
    public $orderAscadd = true;
    public $checkedadd = [];
    public $selectPageadd = false;
    public $selectAlladd = false;
    public $coladd = false;
    public $alladd = false;
    public $columnsadd = ['Id', 'Short Description', 'Created At'];
    public $selectedColumnsadd = [];
    public $idbeinglink = null;
    public $showTable = false;
    public $totalRecords;
    public $loadAmount = 20;

    // function for add categories
    public function toggleTable()
    {
        $this->showrelated = true;
        $this->showTable = !$this->showTable;
    }
    public function cancel()
    {
        $this->showTable = false; // Set $showTable to false to hide the table
    }
    public function loadMore()
    {
        $this->loadAmount += 10;
    }
    public function showColumnadd($column)
    {
        if ($column === 'Name') {
            return true;
        }
        return in_array(
            $column,
            $this->selectedColumnsadd
        );
    }
    public function updatedSelectPageadd($value)
    {
        if ($value) {
            $this->checkedadd = $this->products->pluck('id')->map(fn ($item) => (string) $item)->toArray();
        } else {
            $this->checkedadd = [];
        }
    }
    public function swapSortDirectionadd()
    {
        return $this->orderAscadd === '1' ? '0' : '1';
    }
    public function isCheckedadd($id)
    {
        return in_array(
            $id,
            $this->checked
        );
    }
    public function sortByadd($columnName)
    {

        if ($this->orderByadd === $columnName) {
            $this->orderAscadd = $this->swapSortDirectionadd();
        } else {
            $this->orderAscadd = '1';
        }

        $this->orderByadd = $columnName;
    }
    public function selectAlladd()
    {
        $this->selectAlladd = true;
        $this->checkedadd = $this->products->pluck('id')->map(fn ($item) => (string) $item)->toArray();
    }
    public function getProductsProperty()
    {
        $relatedIds = $this->relatedproducts->pluck('product_id')->merge([$this->product->id])->toArray();
        $unrelatedQuery = Product::whereNotIn('id', $relatedIds);
        if (!empty($this->searchadd)) {
            $unrelatedQuery->where('name', 'like', '%' . $this->searchadd . '%');
        }
        $unrelatedQuery->orderBy($this->orderByadd, $this->orderAscadd ? 'asc' : 'desc');
        if ($this->selectAlladd) {
            return $unrelatedQuery->get();
        } else {
            return $unrelatedQuery->limit($this->loadAmount)->get();
        }
    }
    public function confirmitemlink($id)
    {
        $this->idbeinglink = $id;
        $this->dispatchBrowserEvent('show-link-modal');
    }
    public function linkSingleRecord()
    {
        $item = Product::find($this->idbeinglink);
        $rec = new  Related_Products();
        $rec->product_id = $item->id;
        $rec->parrent_id = $this->product->id;
        $rec->save();
        $this->checkedadd = array_diff($this->checkedadd, [$this->idbeinglink]);
        session()->flash('notification', [
            'message' => 'Record related successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
    public function linkRecords()
    {
        $products = Product::whereKey($this->checkedadd)->get();
        foreach ($products as $product) {
            $item = Product::find($product->id);
            $rec = new  Related_Products();
            $rec->product_id = $item->id;
            $rec->parrent_id = $this->product->id;
            $rec->save();
        }

        $this->checkedadd = [];
        session()->flash('notification', [
            'message' => 'Records related successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
    public function updatedCheckedadd()
    {
        $this->selectPageadd = false;
    }
    public function confirmLinkmultiple()
    {
        $this->dispatchBrowserEvent('show-link-modal-multiple');
    }

    //related subcatecory functions
    public function showColumn($column)
    {
        if ($column === 'Name') {
            return true;
        }
        return in_array($column, $this->selectedColumns);
    }
    public function load()
    {
        $this->perPage += 10;
    }
    public function updatedSelectPage($value)
    {
        if ($value) {
            $this->checked = $this->relatedproducts->pluck('id')->map(fn ($item) => (string) $item)->toArray();
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
        return in_array(
            $id,
            $this->checked
        );
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
        $this->checked = $this->relatedproductsQuery->pluck('id')->map(fn ($item) => (string) $item)->toArray();
    }
    public function getRelatedproductsProperty()
    {
        return $this->relatedproductsQuery->get();
    }
    public function getRelatedproductsQueryProperty()
    {
        return Related_Products::where('parrent_id', $this->product->id)
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->with('product');
    }
    public function confirmItemRemoval($productid)
    {
        $this->idbeingremoved = $productid;
        $this->dispatchBrowserEvent('show-deleterelatedprod-modal');
    }
    public function deleteSingleRecord()
    {
        $record = Related_Products::findOrFail($this->idbeingremoved);
        $record->delete();

        $this->checked = array_diff($this->checked, [$this->idbeingremoved]);
        session()->flash('notification', [
            'message' => 'Record deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
    public function deleteRecords()
    {
        $records = Related_Products::whereKey($this->checked)->get();
        foreach ($records as $record) {
            $recordtodel = Related_Products::find($record->id);
            $recordtodel->delete();
        }
        $this->checked = [];
        session()->flash('notification', [
            'message' => 'Records deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
        $this->selectPage = false;
    }
    public function confirmItemsRemoval()
    {
        $this->dispatchBrowserEvent('show-deleterelatedprod-modal-multiple');
    }
    public function render()
    {
        $relatedproducts = $this->relatedproducts
            ->filter(function ($relpord) {
                return strpos(strtolower($relpord->product->name), strtolower($this->search)) !== false;
            });


        if ($this->showTable === true) {
            return view('livewire.related-products', [
                'relatedproducts' => $relatedproducts,
                'products' => $this->products,
            ]);
        } else {
            return view('livewire.related-products', [
                'relatedproducts' => $relatedproducts,
            ]);
        }
    }
    public function mount(Product $product)
    {
        $this->product = $product;
        $this->selectedColumns = $this->columns;
        $this->selectedColumnsadd = $this->columnsadd;
    }
}
