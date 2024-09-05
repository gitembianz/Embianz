<?php

// UpdateSeoIdsCommand.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class UpdateSeoIdsCommand extends Command
{
    protected $signature = 'update:seo_ids';

    protected $description = 'Update SEO IDs for existing products and categories';

    public function handle()
    {
        $this->updateProducts();
        $this->updateCategories();
        $this->info('SEO IDs updated successfully.');
    }

    private function updateProducts()
    {
        $products = Product::all();
        foreach ($products as $product) {
            $product->update(['seo_id' => $this->generateUniqueSeoId($product->name)]);
        }
    }

    private function updateCategories()
    {
        $categories = Category::all();
        foreach ($categories as $category) {
            $category->update(['seo_id' => $this->generateUniqueSeoId($category->name)]);
        }
    }

    private function generateUniqueSeoId($name)
    {
        $seoId = Str::slug($name, '-');
        $baseSeoId = $seoId;
        $counter = 1;
        while (
            Product::where('seo_id', $seoId)->orWhere('seo_id', $seoId . '-' . $counter)->exists() ||
            Category::where('seo_id', $seoId)->orWhere('seo_id', $seoId . '-' . $counter)->exists()
        ) {
            $seoId = $baseSeoId . '-' . $counter;
            $counter++;
        }
        return $seoId;
    }
}
