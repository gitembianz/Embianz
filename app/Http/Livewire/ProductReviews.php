<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Schema;
use App\Models\ProductReviews as ModelsProductReviews;
use Illuminate\Support\Facades\Cache;


class ProductReviews extends Component
{
  use WithPagination;
  public $product;
  public $selectedColumns = [];
  public $columns = [];
  public $showrelated = false;
  public $search = '';
  public $orderBy = 'id';
  public $orderAsc = true;
  public $loadAmount = 20;
  public $checked = [];
  public $editindex = null;
  public $selectPage = false;
  public $selectAll = false;
  public $record = [];


  public function render()
  {
    $reviews = $this->reviews
      ->where(function ($query) {
        $query->whereHas('product', function ($subQuery) {
          $subQuery->where('name', 'LIKE', '%' . $this->search . '%');
        });
      })->paginate($this->loadAmount);
    return view('livewire.product-reviews', [
      'reviews' => $reviews
    ]);
  }
  public function mount(Product $product, $tableName)
  {
    $this->product = $product;
    $this->columns = Schema::getColumnListing($tableName);
    $this->selectedColumns = $this->columns;
  }

  public function getReviewsProperty()
  {
    return ModelsProductReviews::where('product_id', $this->product->id)
      ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->with('product');
  }
  public function showColumn($column)
  {
    return in_array($column, $this->selectedColumns);
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
  public function edititem($index, $id)
  {
    $this->editindex = $index;
    $record = ModelsProductReviews::find($id);
    $this->record[$index] = [
      'acronim' => $record->acronim,
      'score' => $record->score,
      'approved' => $record->approved == 1 ? true : false,
    ];
  }
  public function approveitem($id)
  {
    $record = ModelsProductReviews::find($id);
    $record->approved = 1;
    Cache::forget("product:{$record->product_id}:review_stats");
    Cache::forget("product:{$record->product_id}:rating_breakdown");
    $record->save();
    session()->flash('notification', [
      'message' => 'Review approved successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function dennyitem($id)
  {
    $record = ModelsProductReviews::find($id);
    $record->approved = false;
    Cache::forget("product:{$record->product_id}:review_stats");
    Cache::forget("product:{$record->product_id}:rating_breakdown");
    $record->save();
    session()->flash('notification', [
      'message' => 'Review denny successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function canceledit()
  {
    $this->editindex = null;
    $this->record = [];
  }
  public function saveitem($index, $id)
  {
    $record = $this->record[$index] ?? null;
    if (!is_null($record)) {
      $new = ModelsProductReviews::find($id);

      if (array_key_exists('acronim', $record)) {
        $new->acronim = $record['acronim'];
      }
      if (array_key_exists('score', $record)) {
        if ($record['score'] < 0) {
          $record['score'] = 0;
        }
        if ($record['score'] > 5) {
          $record['score'] = 5;
        }
        $new->score = $record['score'];
      }
      if (array_key_exists('approved', $record)) {
        $new->approved = $record['approved'] ? 1 : 0;
      }
      $new->save();
      Cache::forget("product:{$new->product_id}:review_stats");
      Cache::forget("product:{$new->product_id}:rating_breakdown");
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
    $this->record = [];
  }
}
