<?php

namespace App\Providers;

use App\Models\Status;
use App\Models\Static_Page;
use App\Models\Country;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Category;
use App\Models\PriceList;
use App\Models\Promotion;
use App\Models\TextLabel;
use App\Models\CustomScript;
use App\Models\Product_Spec;
use App\Models\Store_Settings;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;


class GlobalVariablesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        ///
    }
    /**
     * Bootstrap services.
     */
    public function boot()
    {
      $this->loadActivePages();
        $this->loadGlobalVariables();
        $this->loadLabelVariables();
        $this->loadGlobalStatuses();
        $this->loadGlobalPayments();
        $this->loadGlobalCustomScripts();
        $this->loadGlobalCurrencies();
        $this->loadHighestPopularity();
        $this->loadAllSpecificationsIntoCache();
        $this->loadActiveCountries();

        if (app()->has('global_one_product_page_system') && app('global_one_product_page_system') === 'true') {

            $this->loadCategoryOneProduct();
        }


        if (app()->has('global_promotion_on') && app('global_promotion_on') === 'true') {

            $this->loadAllPromotionsIntoCache();
        }
        if (app()->has('global_cache_data') && app('global_cache_data') === 'true') {

            $this->loadAllProductsIntoCache();
            $this->loadAllCategoriesIntoCache();
        }
    }
    private function loadHighestPopularity()
    {
        if (Schema::hasTable('products')) {

            $highestPopularity = Cache::rememberForever('max_popularity', function () {
                return Product::max('popularity');
            });

            $this->app->instance('max_popularity', $highestPopularity);
        }
    }
    private function loadActivePages()
    {
        if (Schema::hasTable('static__pages')) {

            $pages = Cache::rememberForever('static_pages', function () {
                return Static_Page::where('active', true)->get();
            });
            $this->app->instance('static_pages', $pages);

            foreach ($pages as $page) {
              Route::get($page->route, function () use ($page) {
                  return view('store.page', ['page' => $page]);
              })->name($page->route);
          }
        }
    }

    private function loadGlobalVariables()
    {
        if (Schema::hasTable('store__settings')) {

            $globalVariables = Cache::rememberForever('global_variables', function () {
                $storeSettings = Store_Settings::all()->pluck('value', 'parameter')->toArray();
                return $storeSettings;
            });

            foreach ($globalVariables as $key => $value) {
                $this->app->instance('global_' . $key, $value);
            }
        }
    }
    private function loadLabelVariables()
    {
        if (Schema::hasTable('text_labels')) {

            $labelVariables = Cache::rememberForever('label_variables', function () {
                $labels = TextLabel::all()->pluck('value', 'parameter')->toArray();
                return $labels;
            });

            foreach ($labelVariables as $key => $value) {
                $this->app->instance('label_' . $key, $value);
            }
        }
    }

private function loadCategoryOneProduct()
{
    if (
        Schema::hasTable('categories') &&
        app()->has('global_one_product_page_system') &&
        app('global_one_product_page_system') === "true"
    ) {
        $category = Cache::rememberForever('category_one_product', function () {
            return Category::where('one_product_page_category', true)
                ->with([
                    'product_categories.product' => function ($query) {
                        $query->select('id', 'seo_id', 'innerid', 'active', 'start_date', 'end_date')
                            ->where('active', true)
                            ->whereDate('start_date', '<=', now())
                            ->whereDate('end_date', '>=', now())
                            ->orderBy('innerid');
                    }
                ])
                ->first();
        });

        if ($category) {
            $this->app->instance('one_product_category', $category->id);

            $products = $category->product_categories
                ->filter(fn ($pc) => $pc->product)
                ->sortBy(fn ($pc) => $pc->product->innerid ?? PHP_INT_MAX)
                ->map(fn ($pc) => [
                    'id' => $pc->product->id,
                    'seo_id' => $pc->product->seo_id,
                ])
                ->unique('id')
                ->values()
                ->toArray();

            $this->app->instance('one_product_ids', $products);
        }else{
            $this->app->instance('one_product_ids', []);
            $this->app->instance('one_product_category', null);

        }
    }
}




    private function loadActiveCountries()
    {
        if (Schema::hasTable('countries') && Schema::hasTable('counties') && Schema::hasTable('cities')) {
            $activeCountries = Cache::rememberForever('active_countries', function () {
                return Country::where('status', true)
                    ->select(['id', 'name', 'iso_code'])
                    ->with(['counties' => function ($query) {
                        $query->where('status', true)
                            ->select(['id', 'country_id', 'name', 'iso_code'])
                            ->with(['cities' => function ($query) {
                                $query->where('status', true)
                                    ->select(['id', 'county_id', 'name']);
                            }]);
                    }])
                    ->get()
                    ->map(function ($country) {
                        return [
                            'id' => $country->id,
                            'name' => $country->name,
                            'iso_code' => $country->iso_code,
                            'counties' => $country->counties->map(function ($county) {
                                return [
                                    'id' => $county->id,
                                    'country_id' => $county->country_id,
                                    'name' => $county->name,
                                    'iso_code' => $county->iso_code,
                                    'cities' => $county->cities->map(function ($city) {
                                        return [
                                            'id' => $city->id,
                                            'county_id' => $city->county_id,
                                            'name' => $city->name,
                                        ];
                                    })->toArray(),
                                ];
                            })->toArray(),
                        ];
                    })
                    ->toArray();
            });

            $this->app->instance('active_countries', $activeCountries);
        }
    }

    private function loadGlobalCustomScripts()
    {
        if (Schema::hasTable('custom_scripts')) {

            $globalScripts = Cache::rememberForever('global_scripts', function () {
                $scripts = CustomScript::select(['id', 'name', 'type', 'content', 'active'])->where('active', true)->get()->groupBy('type');
                return $scripts->map(function ($group) {
                    return $group->pluck('content')->implode(PHP_EOL);
                });
            });

            foreach ($globalScripts as $type => $content) {
                $this->app->instance('global_script_' . $type, $content);
            }
        }
    }

    private function loadGlobalPayments()
    {
        if (Schema::hasTable('payments')) {

            $globalPayments = Cache::rememberForever('global_payments', function () {
                $payments = Payment::all(['id', 'active', 'type', 'name'])->keyBy('id')->toArray();
                return $payments;
            });

            foreach ($globalPayments as $payment) {
                $this->app->instance('global_' . $payment['name'], $payment);
            }
        }
    }

    private function loadGlobalStatuses()
    {
        if (Schema::hasTable('statuses')) {

            $globalStatuses = Cache::rememberForever('global_statuses', function () {
                $statuses = Status::whereIn('type', ['cart', 'order', 'voucher'])->get();
                $statusesByType = $statuses->groupBy('type');

                $globalStatuses = [];

                foreach ($statusesByType as $type => $typeStatuses) {
                    foreach ($typeStatuses as $status) {
                        $globalStatuses[$type . '_' . $status->name] = $status->id;
                    }
                }

                return $globalStatuses;
            });

            foreach ($globalStatuses as $key => $value) {
                $this->app->instance('global_' . $key, $value);
            }
        }
    }

    private function loadGlobalCurrencies()
    {
        if (Schema::hasTable('price_lists') && Schema::hasTable('currencies')) {

            $globalCurrencies = Cache::rememberForever('global_currencies', function () {
                return PriceList::join('currencies', 'price_lists.currency_id', '=', 'currencies.id')
                    ->where('price_lists.active', true)
                    ->get(['price_lists.name as price_list_name', 'currencies.name as currency_name', 'currencies.symbol as currency_symbol'])
                    ->keyBy('price_list_name')
                    ->toArray();
            });

            foreach ($globalCurrencies as $priceListName => $currency) {
                $this->app->instance('global_currency_' . strtolower($priceListName) . '_name', $currency['currency_name']);
                $this->app->instance('global_currency_' . strtolower($priceListName) . '_symbol', $currency['currency_symbol']);
            }
        }
    }

    private function loadAllProductsIntoCache()
    {

        $products = Cache::rememberForever('cached_products', function () {

            return Product::where('active', true)
                ->where('start_date', '<=', now()->format('Y-m-d'))
                ->where('end_date', '>=', now()->format('Y-m-d'))
                ->with([
                    'product_categories' => function ($query) {
                        $query->select('product_id', 'category_id', 'primary_category');
                        $query->with(['category' => function ($query) {
                            $query->select('id', 'short_description', 'seo_id');
                        }]);
                    },
                    'reviews' => function ($query) {
                        $query->select('product_id', 'count', 'value');
                    },
                    'product_specs' => function ($query) {
                        $query->select('product_id', 'spec_id', 'value', 'id')->with('spec:id,name');
                    },
                    'related_product' => function ($query) {
                        $query->orderBy('sequence')->select('parent_id', 'product_id', 'sequence', 'id')->with([
                            'product' => function ($query) {
                                $query->where('active', 1)->where('start_date', '<=',  now()->format('Y-m-d'))
                                    ->where('end_date', '>=',  now()->format('Y-m-d'))->select('id', 'name', 'popularity', 'seo_id', 'short_description', 'long_description', 'quantity', 'active', 'end_date', 'start_date')->with([
                                        'media' => function ($query) {
                                            $query->select('path', 'name', 'type')->where('type', 'main');
                                        },
                                        'reviews' => function ($query) {
                                            $query->select('product_id', 'count', 'value');
                                        },
                                        'product_prices' => function ($query) {
                                            $query->select('product_id', 'value', 'discount', 'value_no_discount');
                                        },
                                        'product_categories' => function ($query) {
                                            $query->select('product_id', 'category_id', 'primary_category');
                                            $query->with(['category' => function ($query) {
                                                $query->select('id', 'short_description', 'seo_id');
                                            }]);
                                        }
                                    ]);
                            }
                        ]);
                    },
                    'variants',
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
                    'product_prices' => function ($query) {
                        $query->select('product_id', 'value', 'discount', 'value_no_discount');
                    },
                    'wishlists',
                    'media',
                ])->get();
        });

        $this->app->instance('cached_products', $products);
    }

    private function loadAllCategoriesIntoCache()
    {
        $defaultCategoryId = app('global_default_category');
        $defaultCategory = Category::with([
            'media' => function ($query) {
                $query->select('path', 'name', 'sequence', 'type', 'width', 'height');
            },
            'parent',
            'subcategory' => function ($query) {
                $query->with([
                    'category' => function ($query) {
                        $query->select('id', 'name', 'seo_id', 'sequence')->with([
                            'media' => function ($query) {
                                $query->select('media_id', 'path', 'name');
                            }
                        ]);
                    }
                ]);
            }
        ])->find($defaultCategoryId);
        $categories = Cache::rememberForever('cached_categories', function () {
            return Category::with([
                'media' => function ($query) {
                    $query->select('path', 'name', 'sequence', 'type', 'width', 'height');
                },
                'parent',
                'subcategory' => function ($query) {
                    $query->whereHas('category', function ($query) {
                        $this->applySubcategoryConditions($query);
                    })->with([
                        'category' => function ($query) {
                            $query->select('id', 'name', 'seo_id', 'sequence');
                            $this->applySubcategoryConditions($query);
                            $query->with([
                                'media' => function ($query) {
                                    $query->where('type', 'min')->select('media_id', 'path', 'name');
                                },
                                'subcategory' => function ($query) {
                                    $query->whereHas('category', function ($query) {
                                        $this->applySubcategoryConditions($query);
                                    })->with([
                                        'category' => function ($query) {
                                            $query->select('id', 'name', 'seo_id', 'sequence');
                                            $this->applySubcategoryConditions($query);
                                            $query->with([
                                                'media' => function ($query) {
                                                    $query->where('type', 'min')->select('media_id', 'path', 'name');
                                                }
                                            ]);
                                        }
                                    ]);
                                }
                            ]);
                        }
                    ]);
                }
            ])
                ->where('active', 1)
                ->where('start_date', '<=', now()->format('Y-m-d'))
                ->where('end_date', '>=', now()->format('Y-m-d'))
                ->get();
        });
        if ($defaultCategory && !$categories->contains('id', $defaultCategoryId)) {
            $categories->push($defaultCategory);
        }

        $this->app->instance('cached_categories', $categories);
    }
    protected function applySubcategoryConditions($query)
    {
        $query->where('active', 1)
            ->where('store_tab', 1)
            ->where('start_date', '<=', now()->format('Y-m-d'))
            ->where('end_date', '>=', now()->format('Y-m-d'))
            ->orderBy('sequence');
    }
    private function loadAllSpecificationsIntoCache()
    {
        $productSpecs = Cache::rememberForever('cached_specifications', function () {
            $productSpecs = Product_Spec::select('value', 'spec_id', 'product_id')
                ->with([
                    'spec' => function ($query) {
                        $query->select('id', 'name', 'sequence');
                    },
                    'product' => function ($query) {
                        $query->select('id', 'type', 'parent_id')->whereHas('product_categories');
                    }
                ])
                ->whereHas('spec', function ($query) {
                    $query->where('mark_as_filter', true);
                })
                ->whereHas('product', function ($query) {
                    $query->where('active', true)
                        ->where('type', '!=', 'parent')
                        ->where('start_date', '<=', now()->format('Y-m-d'))
                        ->where('end_date', '>=', now()->format('Y-m-d'))
                        ->whereHas('product_categories');
                })
                ->get();

            $formattedSpecs = $productSpecs->groupBy('spec_id')->map(function ($specs) {
                $firstSpec = $specs->first();

                $uniqueValues = $specs->groupBy('value')->map(function ($items) {

                    $productsWithCategories = $items->map(function ($item) {
                        $categoryIds = $item->product->product_categories->pluck('category_id')->toArray();
                        $parentId = $item->product->type === 'variant' ? $item->product->parent_id : null;

                        return [
                            'product_id' => $item->product_id,
                            'categories' => $categoryIds,
                            'parent_id' => $parentId,
                            'type' => $item->product->type,
                        ];
                    });

                    $uniqueCategories = $items->flatMap(function ($item) {
                        return $item->product->product_categories->pluck('category_id');
                    })->unique();

                    return [
                        'products' => $productsWithCategories->toArray(),
                        'categories' => $uniqueCategories->toArray(),
                    ];
                });

                return [
                    'spec' => $firstSpec->spec->name,
                    'sequence' => $firstSpec->spec->sequence,
                    'values' => $uniqueValues,
                ];
            })->sortBy('sequence')->values();

            return $formattedSpecs->toArray();
        });

        $this->app->instance('cached_specifications', $productSpecs);
    }

    private function loadAllPromotionsIntoCache()
    {
        if (Schema::hasTable('promotions')) {
            $promotions = Cache::rememberForever('promotions', function () {
                $promotions = Promotion::where('active', true)
                    ->where('start_date', '<=', now()->format('Y-m-d'))
                    ->where('end_date', '>=', now()->format('Y-m-d'))
                    ->get();

                return $promotions->toArray();
            });

            $this->app->instance('promotions', $promotions);
        }
    }
}
