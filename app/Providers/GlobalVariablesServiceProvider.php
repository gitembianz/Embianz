<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Status;
use App\Models\Payment;
use App\Models\Product;
use App\Models\PriceList;
use App\Models\TextLabel;
use App\Models\CustomScript;
use App\Models\Store_Settings;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class GlobalVariablesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }



    /**
     * Bootstrap services.
     */
    public function boot()
    {
        $this->loadGlobalVariables();
        $this->loadLabelVariables();
        $this->loadGlobalStatuses();
        $this->loadGlobalPayments();
        $this->loadGlobalCustomScripts();
        $this->loadGlobalCurrencies();
        $this->loadHighestPopularity();
        $this->loadAllProductsIntoCache();
        $this->loadAllCategoriesIntoCache();
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
                    'product_categories',
                    'product_specs',
                    'related_product',
                    'variants',
                    'parent',
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
        $categories = Cache::rememberForever('cached_categories', function () {
            return Category::with([
                'media' => function ($query) {
                    $query->select('path', 'name', 'sequence', 'type', 'width', 'height');
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
}