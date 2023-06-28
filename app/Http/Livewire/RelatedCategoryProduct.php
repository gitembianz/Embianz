<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class RelatedCategoryProduct extends Component
{

  use WithPagination;
  public $showTable = false;

  //related variables

  //add variables
  public $perPageadd = 10;
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


  public function toggleTable()
  {
      $this->showTable = !$this->showTable;
  }
  public function cancel()
  {
      $this->showTable = false;
  }
  public function showColumnadd($column)
  {
    if ($column === 'Name') {
      return true;
    }
    return in_array($column, $this->selectedColumnsadd);
  }
  public function updatedSelectPageadd($value)
  {
    if ($value) {
      //de modificat prodds
      $this->checkedadd = $this->prodds->pluck('id')->map(fn ($item) => (string) $item)->toArray();
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
    return in_array($id, $this->checked);
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


  //render function
    public function render()
    {
        return view('livewire.related-category-product');
    }
}
