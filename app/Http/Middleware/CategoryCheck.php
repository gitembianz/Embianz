<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Category;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;



class CategoryCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $categorySlug = $request->segment(2);
        if (is_numeric($categorySlug)) {
            $category = Category::find($categorySlug);
            if ($category != null) {
                if ($category->seo_id) {
                    return redirect()->route('products', ['categorySlug' => $category->seo_id]);
                }
            } else {

                throw new NotFoundHttpException();
            }
        }
        if ($categorySlug == null) {

            $category = Category::find(app('global_default_category'));
            if ($category != null) {
                if ($category->seo_id) {
                    return redirect()->route('products', ['categorySlug' => $category->seo_id]);
                }
            } else {
                throw new NotFoundHttpException();
            }
        }

        return $next($request);
    }
}
