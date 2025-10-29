<?php

namespace App\Http\Livewire;


use App\Models\Product;
use App\Models\ProductReviews;
use Livewire\Component;
use App\Models\Wishlist;


class StoreShowProduct extends Component
{
  public $productId;
  public $session_id;
  public $back = false;
  public $wishlistItems;
  public $lastVisited;
  public $reviews45;
  public $score;
  public $avrage;
  public $rating5;
  public $rating4;
  public $rating3;
  public $rating2;
  public $rating1;

  public $addrating = null;
  public bool $showaddreview = false;
  public $acronym = '';
  public $message = '';

  protected $rules = [
    'addrating' => 'required|integer|min:1|max:5',
    'acronym'   => 'required|string|max:50',
    'message'   => 'required|string|max:5000',
];

protected $messages = [
    'addrating.required' => 'Te rugăm să selectezi o notă între 1 și 5 stele.',
    'addrating.integer'  => 'Valoarea ratingului trebuie să fie un număr întreg.',
    'addrating.min'      => 'Ratingul minim este 1 stea.',
    'addrating.max'      => 'Ratingul maxim este 5 stele.',

    'acronym.required' => 'Te rugăm să introduci un acronim.',
    'acronym.string'   => 'Acronimul trebuie să fie un text valid.',
    'acronym.max'      => 'Acronimul nu poate depăși 50 de caractere.',

    'message.required' => 'Te rugăm să scrii un mesaj.',
    'message.string'   => 'Mesajul trebuie să conțină doar text.',
    'message.max'      => 'Mesajul nu poate depăși 5000 de caractere.',
];


  public function render()
  {
    return view('livewire.store-show-product', [
      'product' => $this->product,
      'last_visited_products' => $this->lastproduct,
      'product_reviews' => $this->productreviews,
    ]);
  }
  public function mount($productId)
  {
    $this->productId = $productId;
    $this->session_id = request()->cookie('sessionId') ?? session()->getId();

    $this->wishlistItems = Wishlist::where('session_id', $this->session_id)->pluck('product_id')->toArray();

    $this->lastVisited = json_decode(request()->cookie('last_visited_products', '[]'), true);
    if (($key = array_search($productId, $this->lastVisited)) !== false) {
      unset($this->lastVisited[$key]);
    }
    array_unshift($this->lastVisited, $productId);
    cookie()->queue(cookie()->make('last_visited_products', json_encode($this->lastVisited), 60 * 24 * 30));

    $this->score = $this->product->reviews->avg('score') ?? 0;
    $this->reviews45 = $this->product->reviews->whereIn('score', [4, 5])->count() ?? 0;
    $this->avrage = round(($this->reviews45 / $this->product->reviews->count()) * 100);
    if ($this->product->reviews->count() > 0) {
      $this->rating5 = $this->product->reviews->where('score', 5)->count() ?? 0;
      $this->rating4 = $this->product->reviews->where('score', 4)->count() ?? 0;
      $this->rating3 = $this->product->reviews->where('score', 3)->count() ?? 0;
      $this->rating2 = $this->product->reviews->where('score', 2)->count() ?? 0;
      $this->rating1 = $this->product->reviews->where('score', 1)->count() ?? 0;
    }
  }

  public function getLastProductProperty()
  {
    if ($this->lastVisited) {
      return Product::whereIn('id', $this->lastVisited)
        ->orderByRaw("FIELD(id, " . implode(',', $this->lastVisited) . ")")
        ->where('active', 1)
        ->where('id', '!=', $this->productId)
        ->where('start_date', '<=', now()->format('Y-m-d'))
        ->where('end_date', '>=', now()->format('Y-m-d'))
        ->orderBy('quantity', 'DESC')
        ->select('id', 'name', 'preorder', 'low_stock', 'sku', 'long_description', 'brand', 'popularity', 'seo_id', 'short_description', 'quantity', 'active', 'end_date', 'start_date')
        ->with([
          'media' => function ($query) {
            $query->select('path', 'name', 'type')->where('type', 'main');
          },
          'reviews' => function ($query) {
            $query->select('product_id', 'count', 'value');
          },
          'product_categories' => function ($query) {
            $query->select('product_id', 'category_id', 'primary_category')
              ->where('primary_category', true);
            $query->with(['category' => function ($query) {
              $query->select('id', 'short_description', 'seo_id');
            }]);
          },
          'product_prices' => function ($query) {
            $query->select('product_id', 'value', 'discount', 'value_no_discount');
          },
        ])
        ->get() ?? collect();
    } else {
      return collect();
    }
  }

  public function getProductReviewsProperty()
  {
    if (app('global_review_system') === 'true') {
      return ProductReviews::where('product_id', $this->productId)
        ->where('approved', 1)
        ->select('id', 'acronim', 'score', 'approved', 'comment', 'product_id')
        ->get() ?? collect();
    } else {
      return collect();
    }
  }

  public function isInWishlist($productId)
  {
    return in_array($productId, $this->wishlistItems);
  }

  public function getProductProperty()
  {
    if (app()->has('global_cache_data') && app('global_cache_data') === 'true') {
      $cachedProduct = app()->make('cached_products')->firstWhere('id', $this->productId);

      if ($cachedProduct) {
        $cachedProduct->media = collect($cachedProduct->media)->filter(function ($media) {
          return in_array($media['type'], ['full', 'original']);
        })->sortBy('sequence')->values();

        return $cachedProduct;
      }
    } else {

      return Product::select('id', 'name', 'seo_id', 'long_description')
        ->with([
          'media' => function ($query) {
            $query->select('name', 'path', 'type', 'sequence')
              ->whereIn('type', ['full', 'original'])
              ->orderBy('sequence');
          },
          'reviews' => function ($query) {
            $query->where('approved', true)
              ->select('id', 'product_id', 'acronim', 'score', 'approved', 'comment');
          },
          'product_specs' => function ($query) {
            $query->select('product_id', 'spec_id', 'value', 'id')->with('spec:id,name');
          },
          'product_categories' => function ($query) {
            $query->select('product_id', 'category_id');
            $query->with(['category' => function ($query) {
              $query->select('id', 'name', 'short_description', 'seo_id');
            }]);
          },
          'related_product' => function ($query) {
            $query->select('product_id', 'id', 'parent_id')
              ->orderBy('sequence', 'desc')
              ->orderByRaw('(SELECT innerid FROM products WHERE products.id = product_id) DESC')
              ->with(['product' => function ($query) {
                $query->where('active', 1)
                  ->where('start_date', '<=', now()->format('Y-m-d'))
                  ->where('end_date', '>=', now()->format('Y-m-d'))
                  ->orderByRaw('CASE WHEN quantity > 0 THEN 0 ELSE 1 END')
                  ->orderBy('innerid', 'ASC')
                  ->select('id', 'preorder', 'name', 'sku', 'low_stock', 'long_description', 'brand', 'popularity', 'seo_id', 'short_description', 'quantity', 'active', 'end_date', 'start_date')
                  ->with([
                    'media' => function ($query) {
                      $query->select('path', 'name', 'type')->where('type', 'main');
                    },
                    'product_categories' => function ($query) {
                      $query->select('product_id', 'category_id', 'primary_category')
                        ->where('primary_category', true);
                      $query->with(['category' => function ($query) {
                        $query->select('id', 'short_description', 'seo_id');
                      }]);
                    },
                    'product_prices' => function ($query) {
                      $query->select('product_id', 'value', 'discount', 'value_no_discount');
                    },
                  ]);
              }]);
          }
        ])
        ->where('id', $this->productId)
        ->first();
    }
  }

  public function addreview()
  {
    $this->showaddreview = true;
    $this->emit('review__modal');
  }

  public function saveReview()
  {
    $this->validate();

    $this->product->reviews()->create([
      'acronim' => $this->acronym,
      'score' => $this->addrating,
      'comment' => $this->message,
      'approved' => false,
    ]);
    $this->showaddreview = false;

  }
}
