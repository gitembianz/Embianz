<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SpecificationCacheService
{
    public function getSpecs()
    {
        return Cache::rememberForever('cached_specifications', function () {
            return $this->buildCache();
        });
    }

    private function buildCache()
    {
        $specRows = DB::table('product__specs as ps')
            ->join('specs as s', 'ps.spec_id', '=', 's.id')
            ->join('products as p', 'ps.product_id', '=', 'p.id')
            ->where('s.mark_as_filter', 1)
            ->where('p.active', 1)
            ->where('p.type', '!=', 'parent')
            ->whereDate('p.start_date', '<=', now())
            ->whereDate('p.end_date', '>=', now())
            ->select(
                'ps.product_id',
                'ps.spec_id',
                'ps.value',
                's.name as spec_name',
                's.sequence',
                'p.type as product_type',
                'p.parent_id'
            )
            ->get();

        if ($specRows->isEmpty()) {
            return [];
        }

        $productIds = $specRows->pluck('product_id')->unique();

        $categories = DB::table('products_categories')
            ->whereIn('product_id', $productIds)
            ->select('product_id', 'category_id')
            ->get()
            ->groupBy('product_id');


        $grouped = $specRows->groupBy('spec_id')->map(function ($rows) use ($categories) {

            $first = $rows->first();

            $values = $rows->groupBy('value')->map(function ($items) use ($categories) {

                $products = $items->map(function ($item) use ($categories) {
                    return [
                        'product_id' => $item->product_id,
                        'categories' => ($categories[$item->product_id] ?? collect())
                            ->pluck('category_id')
                            ->values()
                            ->toArray(),
                        'parent_id'  => $item->product_type === 'variant' ? $item->parent_id : null,
                        'type'       => $item->product_type,
                    ];
                })->values();

                $uniqueCats = $items->flatMap(function ($item) use ($categories) {
                    return ($categories[$item->product_id] ?? collect())
                        ->pluck('category_id');
                })->unique()->values();

                return [
                    'products'   => $products->toArray(),
                    'categories' => $uniqueCats->toArray(),
                ];
            });

            return [
                'spec'     => $first->spec_name,
                'sequence' => $first->sequence,
                'values'   => $values,
            ];
        });

        return $grouped
            ->sortBy('sequence')
            ->values()
            ->toArray();
    }
}
