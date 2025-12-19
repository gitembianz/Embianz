<?php

namespace App\Observers;

use App\Models\Category;
use App\Services\CategoryService;

class CategoryObserver
{
    public function saved(Category $category)
    {
        app(CategoryService::class)->rebuild($category);
    }

    public function deleted(Category $category)
    {
        app(CategoryService::class)->rebuild($category);
    }
}
