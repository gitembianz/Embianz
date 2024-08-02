<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Variant;
use Livewire\Component;
use Livewire\WithPagination;

class RelatedVariants extends Component
{

    use WithPagination;
    public $item;
    //related delclaration
    public $loadAmount = 15;
    public $search = '';
    public $orderBy = 'id';
    public $orderAsc = true;
    public $checked = [];
    public $selectPage = false;
    public $selectAll = false;
    public $showvariant = false;
    public $productId;
    public $columns = ['Id', 'parent Name', 'default variant', 'Variant Name', 'Reference', 'Value', 'Dispalyed type', 'Created At', 'Updated At'];
    public $selectedColumns = [];
    public $idbeingremoved = null;
    public $editindex;
    public $var = [];

    //Add specs declaration
    public $addvariant = false;
    public $searchadd = '';
    public $variantAndValues = [];
    public $row = 1;
    public $single = false;
    public $multiple = false;
    public $rind2 = null;
    public $rind = null;


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

    public function render()
    {
        $variants = $this->variantsQuery
            ->where(function ($query) {
                $query->whereHas('product', function ($subQuery) {
                    $subQuery->where('name', 'LIKE', '%' . $this->search . '%');
                });
            })->paginate($this->loadAmount);
        return view('livewire.related-variants', [
            'variants' => $variants,
            'addvariants' => $this->addvariants,
            'references' => $this->references,

        ]);
    }

    public function mount(Product $product)
    {
        $this->selectedColumns = $this->columns;
        $this->item = $product;


        $this->variantAndValues[] = [
            'allow' => false,
            'itemselected' => null,
            'variant' => ['name' => null, 'reference' => optional($this->references->first())->id, 'display' => 'text', 'def' => false],
        ];
    }
    public function load()
    {
        $this->loadAmount += 10;
    }

    public function showColumn($column)
    {
        return in_array($column, $this->selectedColumns);
    }

    public function updatedSelectPage($value)
    {
        if ($value) {
            $this->checked = $this->variants->pluck('id')->map(fn ($item) => (string) $item)->toArray();
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
        $this->checked = $this->variantsQuery->pluck('id')->map(fn ($item) => (string) $item)->toArray();
    }
    //function for realted

    public function getVariantsProperty()
    {
        return $this->variantsQuery;
    }
    public function getVariantsQueryProperty()
    {
        return ProductVariant::where('parent_id', $this->item->id)
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
        $id = $this->idbeingremoved;
        $item = ProductVariant::findOrFail($id);
        $items = ProductVariant::where('parent_id', $this->item->id)->where('product_id', $item->product_id)->get();
        if ($items->count() == 1) {

            $item->product->update(['parent_id' => null]);
        }
        $item->delete();
        $this->checked = array_diff($this->checked, [$id]);
        $this->single = false;

        session()->flash('notification', [
            'message' => 'Record deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
    public function deleteRecords()
    {
        $items = ProductVariant::whereKey($this->checked)->get();
        foreach ($items as $item) {
            $id = $item->id;
            $itemtodel = ProductVariant::find($id);
            $itemss = ProductVariant::where('parent_id', $this->item->id)->where('product_id', $itemtodel->product_id)->get();
            if ($itemss->count() == 1) {

                $itemtodel->product->update(['parent_id' => null]);
            }
            $itemtodel->delete();
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


    //function for add new variant
    public function addrelated()
    {

        if ($this->references != null && $this->references->count() > 0) {

            if ($this->item->type == 'parent') {
                $this->showvariant = true;
                $this->addvariant = true;
            } else {
                session()->flash('notification', [
                    'message' => 'Please change the product type first!',
                    'type' => 'warning',
                ]);
            }
        } else {
            session()->flash('notification', [
                'message' => 'Please add first a reference!',
                'type' => 'warning',
            ]);
        }
    }

    public function closemodal()
    {
        $this->variantAndValues = [];
        $this->variantAndValues[] = [
            'allow' => false,
            'itemselected' => null,
            'variant' => ['name' => null, 'reference' => $this->references->first()->id, 'display' => 'text', 'def' => false],
        ];
        $this->row = 1;
        $this->addvariant = false;
    }
    public function allowselect($index)
    {
        foreach ($this->variantAndValues as &$item) {
            $item['allow'] = false;
        }
        $this->variantAndValues[$index]['allow'] = true;
        $this->searchadd = $this->variantAndValues[$index]['itemselected'];
    }
    public function dennyselect($index)
    {
        $this->variantAndValues[$index]['allow'] = false;
        $this->searchadd = '';
    }
    public function selectitem($index, $id, $name)
    {
        $this->variantAndValues[$index]['itemselected'] = $name;
        $this->variantAndValues[$index]['variant']['idrel'] = $id;
        $this->variantAndValues[$index]['allow'] = false;
        $this->searchadd = '';
    }
    public function plus()
    {
        $this->row++;
        $this->variantAndValues[] = [
            'allow' => false,
            'itemselected' => null,
            'variant' => ['name' => null, 'reference' => $this->references->first()->id, 'display' => 'text', 'def' => false],
        ];
    }
    public function clear($index)
    {
        unset($this->variantAndValues[$index]);

        $this->variantAndValues = array_values($this->variantAndValues);

        $this->row--;
        if ($this->row < 1) {
            $this->addvariant = false;
            $this->variantAndValues[] = [
                'allow' => false,
                'itemselected' => null,
                'variant' => ['name' => null, 'reference' => $this->references->first()->id, 'display' => 'text', 'def' => false],
            ];
            $this->row = 1;
        }
    }
    public function saveitems()
    {
        foreach ($this->variantAndValues as  $array) {
            if (isset($array['variant']['value']) && isset($array['variant']['idrel'])) {
                if ($array['variant']['def']) {
                    ProductVariant::where('parent_id', $this->item->id)
                        ->where('default_variant', true)
                        ->update(['default_variant' => false]);
                }
                ProductVariant::create([
                    'parent_id' => $this->item->id,
                    'product_id' => $array['variant']['idrel'],
                    'variant_id' => $array['variant']['reference'],
                    'value' => $array['variant']['value'],
                    'displayed' => $array['variant']['display'],
                    'default_variant' => $array['variant']['def'],
                ]);

                Product::where('id', $array['variant']['idrel'])->update([
                    'parent_id' => $this->item->id,
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

        $this->variantAndValues = [];
        $this->row = 1;
        $this->addvariant = false;
        session()->flash('notification', [
            'message' => 'Record related successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
        $this->mount($this->item);
    }

    public function getAddvariantsProperty()
    {
        $unrelated = Product::where('type', 'variant');
        if (!empty($this->searchadd)) {
            $unrelated->where('name', 'like', '%' . $this->searchadd . '%');
        }
        return $unrelated->get();
    }
    public function getReferencesProperty()
    {
        return Variant::select('id', 'name')->get();
    }

    public function edititem($index, $id)
    {
        $this->editindex = $index;
        $record = ProductVariant::find($id);
        $this->var = [
            $index . '.ref' => $record->variant_id,
            $index . '.value' => $record->value,
            $index . '.displayed' => $record->displayed,
            $index . '.def' => $record->default_variant == 1 ? true : false,
        ];
    }
    public function canceledit()
    {
        $this->editindex = null;
        $this->var = [];
    }
    public function saveitem($index, $id)
    {
        $record = $this->var[$index] ?? null;
        if (!is_null($record)) {
            $new = ProductVariant::find($id);
            if (array_key_exists('def', $record)) {
                if ($record['def']) {
                    ProductVariant::where('parent_id', $this->item->id)
                        ->where('default_variant', true)
                        ->update(['default_variant' => false]);
                    $new->default_variant = $record['def'];
                }
            }
            if (array_key_exists('ref', $record)) {
                $new->variant_id = $record['ref'];
            }
            if (array_key_exists('value', $record)) {
                $new->value = $record['value'];
            }
            if (array_key_exists('displayed', $record)) {
                $new->displayed = $record['displayed'];
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
        $this->var = [];
    }
}
