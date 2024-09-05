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
    public $search = '';
    public $orderBy = 'id';
    public $orderAsc = true;
    public $checked = [];
    public $selectPage = false;
    public $selectAll = false;
    public $showrelated = false;
    public $idbeingremoved = null;
    public $columns = ['Id', 'Name', 'Short Description', 'Sequence', 'Created At', 'Updated At'];
    public $selectedColumns = [];
    public $item;
    public $sec = [];

    //add declaration
    public $productsAndValues = [];
    public $searchadd = '';
    public $showTable = false;
    public $loadAmount = 20;
    public $row = 1;
    public $single = false;
    public $multiple = false;
    public $rind2 = null;
    public $rind = null;
    public $editindex;

    public function edititem($index, $id)
    {
        $this->editindex = $index;
        $record = Related_Products::find($id);
        $this->sec = [
            $index . '.sec' => $record->sequence
        ];
    }
    public function canceledit()
    {
        $this->editindex = null;
        $this->sec = [];
    }
    public function saveitem($index, $id)
    {
        $record = $this->sec[$index] ?? null;
        if (!is_null($record)) {
            $new = Related_Products::find($id);

            if (array_key_exists('sec', $record)) {
                $new->sequence = $record['sec'];
            }
            $new->save();
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
        $this->sec = [];
    }

    public function expandRow2($index)
    {
        if ($this->rind2  === null) {
            $this->rind2 = $index;
        } elseif ($this->rind2 != $index) {
            $this->rind2 = $index;
        } else {
            $this->rind2 = null;
        }
    }

    public function expandRow($index)
    {
        if ($this->rind  === null) {
            $this->rind = $index;
        } elseif ($this->rind != $index) {
            $this->rind = $index;
        } else {
            $this->rind = null;
        }
    }

    // function for add categories
    public function addrelated()
    {
        $this->showrelated = true;
        $this->showTable = true;
    }
    public function dennyselect($index)
    {
        $this->productsAndValues[$index]['allow'] = false;
        $this->searchadd = '';
    }
    public function selectitem($index, $id, $name)
    {
        $this->productsAndValues[$index]['itemselected'] = $name;
        $this->productsAndValues[$index]['product']['idrel'] = $id;
        $this->productsAndValues[$index]['allow'] = false;
        $this->searchadd = '';
    }
    public function plus()
    {
        $this->row++;
        $this->productsAndValues[] = [
            'allow' => false,
            'itemselected' => null,
            'product' => ['name' => null, 'sequence' => 0]
        ];
    }
    public function clear($index)
    {
        unset($this->productsAndValues[$index]);

        $this->productsAndValues = array_values($this->productsAndValues);

        $this->row--;
        if ($this->row < 1) {
            $this->showTable = false;
            $this->productsAndValues[] = [
                'allow' => false,
                'itemselected' => null,
                'product' => ['name' => null, 'sequence' => 0]
            ];
            $this->row = 1;
        }
    }
    public function allowselect($index)
    {
        foreach ($this->productsAndValues as &$item) {
            $item['allow'] = false;
        }
        $this->productsAndValues[$index]['allow'] = true;
        $this->searchadd = $this->productsAndValues[$index]['itemselected'];
    }
    public function closemodal()
    {
        $this->productsAndValues = [];
        $this->productsAndValues[] = [
            'allow' => false,
            'itemselected' => null,
            'product' => ['name' => null, 'sequence' => 0]
        ];
        $this->row = 1;
        $this->showTable = false;
    }
    public function saveitems()
    {
        foreach ($this->productsAndValues as  $array) {
            if (isset($array['product']['sequence']) && isset($array['product']['idrel'])) {
                Related_Products::create([
                    'parent_id' => $this->item->id,
                    'product_id' => $array['product']['idrel'],
                    'sequence' => $array['product']['sequence']
                ]);
            } else {
                session()->flash('notification', [
                    'message' => 'Please provide values',
                    'type' => 'warning',
                    'title' => 'Missing Values'
                ]);
                return;
            }
        }

        $this->productsAndValues = [];
        $this->row = 1;
        $this->showTable = false;
        session()->flash('notification', [
            'message' => 'Record related successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
        $this->mount($this->item);
    }
    public function loadMore()
    {
        $this->loadAmount += 10;
    }


    public function getProductsProperty()
    {
        $relatedIds = $this->relatedproducts->pluck('product_id')->merge([$this->item->id])->toArray();
        $unrelatedQuery = Product::whereNotIn('id', $relatedIds)->where('type', '!=', 'parent');
        if (!empty($this->searchadd)) {
            $unrelatedQuery->where('name', 'like', '%' . $this->searchadd . '%');
        }
        return $unrelatedQuery->get();
    }


    //related subcatecory functions
    public function showColumn($column)
    {
        return in_array($column, $this->selectedColumns);
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
        return $this->relatedproductsQuery;
    }
    public function getRelatedproductsQueryProperty()
    {
        return Related_Products::where('parent_id', $this->item->id)
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->with('product');
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
        $record = Related_Products::findOrFail($this->idbeingremoved);
        $record->delete();

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
        $records = Related_Products::whereKey($this->checked)->get();
        foreach ($records as $record) {
            $recordtodel = Related_Products::find($record->id);
            $recordtodel->delete();
        }
        $this->checked = [];
        $this->multiple = false;
        session()->flash('notification', [
            'message' => 'Records deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
        $this->selectPage = false;
    }

    public function render()
    {
        $relatedproducts = $this->relatedproductsQuery
            ->where(function ($query) {
                $query->whereHas('product', function ($subQuery) {
                    $subQuery->where('name', 'LIKE', '%' . $this->search . '%');
                });
            })->paginate($this->loadAmount);

        return view('livewire.related-products', [
            'relatedproducts' => $relatedproducts,
            'products' => $this->products,
        ]);
    }
    public function mount(Product $product)
    {
        $this->item = $product;
        $this->selectedColumns = $this->columns;
        $this->productsAndValues[] = [
            'allow' => false,
            'itemselected' => null,
            'product' => ['name' => null, 'sequence' => 0]
        ];
    }
}
