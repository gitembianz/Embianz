<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        
if (empty($query)) {
    return response()->json([
        'products' => [],
        'categories' => [],
        'count' => 0
    ]);
}

        // Number format settings
        if (app()->has('global_numberformat_element')) {
            $decimal = app('global_numberformat_element') === '.' ? ',' : '.';
            $mill = app('global_numberformat_element') === '.' ? '.' : ',';
        } else {
            $decimal = ',';
            $mill = '.';
        }

        // Check if we should search categories
        $searchCategories = !(app()->has('global_one_product_page_system') && app('global_one_product_page_system') === 'true');

        // Search products using Laravel Scout (Product::search())
        $productsQuery = Product::search($query)
            ->select('id', 'name', 'seo_id', 'type', 'short_description')
            ->where('active', true)
            ->where('type', '!=', 'parent')
            ->where('start_date', '<=', now(config('app.timezone'))->format('Y-m-d'))
            ->where('end_date', '>=', now(config('app.timezone'))->format('Y-m-d'));

        // If one_product_page_system is enabled, filter by specific IDs
        if (app()->has('global_one_product_page_system') && app('global_one_product_page_system') === 'true') {
            if (app()->has('one_product_ids') && app('one_product_ids') != null) {
                $ids = array_column(app()->make('one_product_ids'), 'id');
                $productsQuery->whereIn('id', $ids);
            }
        }

        $products = $productsQuery
            ->with([
                'media' => function ($q) {
                    $q->select('path', 'name', 'type')->where('type', 'min');
                },
                'product_prices' => function ($q) {
                    $q->select('product_id', 'value', 'pricelist_id');
                }
            ])
            ->orderBy('innerid', 'ASC')
            ->limit(app('global_limit_searchitems') ?? 10)
            ->get()
            ->map(function ($product) use ($decimal, $mill) {
                $price = null;
                if ($product->product_prices->count() > 0) {
                    $price = number_format(
                        $product->product_prices->first()->value,
                        2,
                        $decimal,
                        $mill
                    );
                }

                $image = null;
                $mediaItem = $product->media->where('type', 'min')->first();
                if ($mediaItem) {
                    $image = '/' . $mediaItem->path . $mediaItem->name;
                }

                return [
                    'type' => 'product',
                    'name' => $product->name,
                    'short_description' => $product->short_description ?? '',
                    'price' => $price,
                    'image' => $image,
                    'url' => route('product', [
                        'product' => $product->seo_id ?: $product->id
                    ])
                ];
            });

        // Search categories (only if not in one_product_page_system mode)
        $categories = collect();
        if ($searchCategories) {
            $categories = Category::search($query)
                ->select('id', 'name', 'seo_id')
                ->where('active', true)
                ->where('start_date', '<=', now(config('app.timezone'))->format('Y-m-d'))
                ->where('end_date', '>=', now(config('app.timezone'))->format('Y-m-d'))
                ->with([
                    'media' => function ($q) {
                        $q->select('path', 'name')->where('type', 'min');
                    }
                ])
                ->limit(app('global_limit_searchitems') ?? 10)
                ->get()
                ->map(function ($category) {
                    $image = null;
                    if ($category->media->first()) {
                        $mediaItem = $category->media->first();
                        $image = '/' . $mediaItem->path . $mediaItem->name;
                    }

                    return [
                        'type' => 'category',
                        'name' => $category->name,
                        'image' => $image,
                        'url' => route('products', [
                            'categorySlug' => $category->seo_id ?: $category->id
                        ])
                    ];
                });
        }

        return response()->json([
            'products' => $products,
            'categories' => $categories,
            'count' => $products->count() + $categories->count(),
            'currency' => app('global_currency_primary_symbol') ?? 'RON',
            'no_results_message' => app('label_message_no_elements') ?? 'Nu am găsit rezultate'
        ]);
    }
}
