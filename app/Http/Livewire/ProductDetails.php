<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Cart_Item;
use Livewire\Component;

class ProductDetails extends Component
{

    public $quantity;
    public $limit = null;
    public $maxlimit = null;
    public $product;
    public $session_id;
    public $prodid;
    public $wishlistItems;
    public $is_in_wishlist;

    public function render()
    {
        return view('livewire.product-details', [
            'variants' => $this->variants
        ]);
    }
    public function mount($product)
    {
        $this->session_id = request()->cookie('sessionId') ?? session()->getId();

        $prodid = $this->product->id;

        if (app()->has('global_cache_data') && app('global_cache_data') === 'true') {
            $cachedProduct = app()->make('cached_products')->firstWhere('id', $prodid);


            $cachedProduct->with([
                'media' => function ($query) {
                    $query->select('name', 'path', 'type', 'sequence')
                        ->whereIn('type', ['full', 'original'])
                        ->orderBy('sequence');
                },
                'product_prices',
                'parent' => function ($query) {
                    $query->with(['variants' => function ($query) {
                        $query->distinct('variant_id')->with(['product' => function ($query) {
                            $query->where('active', true)
                                ->where('start_date', '<=', now()->format('Y-m-d'))
                                ->where('end_date', '>=', now()->format('Y-m-d'))
                                ->with([
                                    'media' => function ($query) {
                                        $query->select('path', 'name')->where('type', 'min');
                                    },
                                    'beeingvariants'
                                ]);
                        }]);
                    }]);
                },
                'beeingvariants'
            ]);

            $this->product = $cachedProduct;
        } else {
            $this->product = $product->select('id', 'end_date', 'sku', 'brand', 'name', 'seo_id', 'popularity', 'long_description', 'quantity', 'short_description', 'type', 'parent_id')
                ->with([
                    'product_prices' => function ($query) {
                        $query->select('product_id', 'value', 'vat', 'discount', 'value_no_discount');
                    },
                    'wishlists' => function ($query) {
                        $query->where('session_id', $this->session_id);
                    },
                    'parent' => function ($query) {
                        $query->with(['variants' => function ($query) {
                            $query->distinct('variant_id')->with(['product' => function ($query) {
                                $query->where('active', true)
                                    ->where('start_date', '<=', now()->format('Y-m-d'))
                                    ->where('end_date', '>=', now()->format('Y-m-d'))
                                    ->with([
                                        'media' => function ($query) {
                                            $query->select('path', 'name')->where('type', 'min');
                                        },
                                        'beeingvariants'
                                    ]);
                            }]);
                        }]);
                    },
                    'beeingvariants',
                    'reviews' => function ($query) {
                        $query->select('product_id', 'count', 'value');
                    }
                ])
                ->findOrFail($prodid);
        }

        $this->quantity = 1;
        $this->is_in_wishlist = in_array($prodid, $this->wishlistItems);
    }


    public function getVariantsProperty()
    {
        if (!$this->product) {
            return collect([]);
        }

        $parentProduct = $this->product->parent;
        if (!$parentProduct) {
            return collect([]);
        }

        $allVariants = $parentProduct->variants->map->product->where('active', true)
            ->where('start_date', '<=', now()->format('Y-m-d'))
            ->where('end_date', '>=', now()->format('Y-m-d'))->unique();

        $filterVariants = function ($variants, $currentProduct, $variantIdToExclude) {
            return $variants->filter(function ($variant) use ($currentProduct, $variantIdToExclude) {
                $sameAttributes = true;
                foreach ($currentProduct->beeingvariants as $currentVariant) {
                    if ($currentVariant->variant_id != $variantIdToExclude) {
                        $matchingVariant = $variant->beeingvariants->firstWhere('variant_id', $currentVariant->variant_id);
                        if (!$matchingVariant || $matchingVariant->value != $currentVariant->value) {
                            $sameAttributes = false;
                            break;
                        }
                    }
                }
                return $sameAttributes;
            })->unique();
        };

        $variantIds = $this->product->beeingvariants
            ->sortBy(function ($beeingvariant) {
                return $beeingvariant->reference->sequence;
            })
            ->pluck('variant_id')
            ->unique()
            ->values();

        $variantsGroupedByVariantId = [];

        foreach ($variantIds as $variantId) {
            $variantsGroupedByVariantId[$variantId] = $filterVariants($allVariants, $this->product, $variantId);
        }

        return $variantsGroupedByVariantId;
    }




    public function incrementCounter()
    {
        $this->limit = $this->product->quantity;
        if ($this->quantity >= $this->limit && (app()->has('global_preorder') && app('global_preorder') != 'true')) {
            $this->maxlimit = true;
            $this->quantity = $this->limit;
        } else {
            $this->quantity++;
        }
    }

    public  function decrementCounter()
    {
        if ($this->quantity > 1) {
            if ($this->quantity == $this->limit) {
                $this->maxlimit = false;
            }
            $this->quantity--;
        }
    }

    public function addToCart($productId)
    {
        $cart = Cart::where('session_id', $this->session_id)->where('status_id', '!=', app('global_cart_closed'))->latest()->first();

        if (!$cart) {
            $baseName = class_basename(Cart::class);
            $cartNumber = 1;
            $uniqueName = $baseName . '_' . str_pad($cartNumber, 2, '0', STR_PAD_LEFT);
            while (Cart::where('name', $uniqueName)->exists()) {
                $cartNumber++;
                $uniqueName = $baseName . '_' . str_pad($cartNumber, 2, '0', STR_PAD_LEFT);
            }
            $cart = Cart::create([
                'session_id' => $this->session_id,
                'name' => $uniqueName,
                'delivery_price' => app('global_delivery_price'),
                'status_id' => app('global_cart_new'),
                'currency_id' => $this->product->product_prices->first()->pricelist->currency_id,
            ]);
            $this->emit('newcart');
        }

        $cartItem = Cart_Item::where('cart_id', $cart->id)->where('product_id', $productId)->first();

        if (!$cartItem) {
            $cartItem = Cart_Item::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'price' => $this->product->product_prices->first()->value,
                'quantity' => $this->quantity,
                'vat' => $this->product->product_prices->first()->vat,
            ]);

            $cart->quantity_amount += $this->quantity;
            $cart->delivery_price = app('global_delivery_price');
            $cart->sum_amount += ($this->product->product_prices->first()->value * $this->quantity);
            if ($cart->voucher && $cart->voucher->percent !== null) {
                $cart->voucher_value = ($cart->voucher->percent / 100) * $cart->sum_amount;
            }
            $cart->final_amount = $cart->sum_amount + app('global_delivery_price');
            $cart->final_amount -= $cart->voucher_value;
            $cart->save();
            $this->maxlimit = false;
        } else {
            if (($cartItem->quantity + $this->quantity) <= $this->product->quantity || (app()->has('global_preorder') && app('global_preorder') === 'true')) {
                $cartItem->quantity += $this->quantity;
                $cartItem->save();
                $cart->quantity_amount += $this->quantity;
                $cart->delivery_price = app('global_delivery_price');
                if ($cartItem->price != $this->product->product_prices->first()->value) {
                    $cartItem->price = $this->product->product_prices->first()->value;
                    $cartItem->save();
                    $sum_amount = 0;
                    foreach ($cart->carts as $item) {
                        $sum_amount = $sum_amount + $item->price * $item->quantity;
                    }
                    $cart->sum_amount = $sum_amount;
                    $cart->seen_by_customer = true;
                } else {
                    $cart->sum_amount += ($this->product->product_prices->first()->value * $this->quantity);
                }

                if ($cart->voucher && $cart->voucher->percent !== null) {
                    $cart->voucher_value = ($cart->voucher->percent / 100) * $cart->sum_amount;
                }
                $cart->final_amount = $cart->sum_amount + app('global_delivery_price');
                $cart->final_amount -= $cart->voucher_value;
                $this->maxlimit = false;
                if (!$cartItem->vat) {
                    $cartItem->vat = $this->product->product_prices->first()->vat;
                    $cartItem->save();
                }
                $cart->save();
            } else {
                $this->maxlimit = true;
                $this->quantity = 1;
                $this->limit = $this->product->quantity;
                return;
            }
        }
        $cart->status_id = app('global_cart_new');
        $cart->save();
        $this->quantity = 1;
        $this->emit('cartUpdated');
    }
}
