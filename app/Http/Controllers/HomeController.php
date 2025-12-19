<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function redirect()
    {
        return Auth::user()?->usertype == '1'
            ? view('admin.home')
            : redirect()->route('home');
    }

    public function home(CategoryService $categoryService)
    {
        $preload = $this->resolveHomePreload($categoryService);

        if (app('global_one_product_page_system') === true) {

            abort_unless(app()->bound('one_product_ids'), 404);

            $ids = app('one_product_ids');

            if (empty($ids)) {

                abort_unless(app()->bound('one_product_category'), 404);

                return redirect()->route('products', [
                    'categorySlug' => app('one_product_category')
                ]);
            }

            $first = $ids[0];

            return redirect()->route('product', [
                'product' => $first['seo_id'] ?? $first['id']
            ]);
        }

        return view('store.home', compact('preload'));
    }

    protected function resolveHomePreload(CategoryService $categoryService): string
    {
        $first = collect($categoryService->get())
            ->filter(fn ($c) => !empty($c['slider_sequence']))
            ->sortBy('slider_sequence')
            ->first();

        return $first['slider_media'][4]['src']
            ?? $first['slider_media'][3]['src']
            ?? $first['slider_media'][2]['src']
            ?? '/images/store/default/default300.webp';
    }
}
