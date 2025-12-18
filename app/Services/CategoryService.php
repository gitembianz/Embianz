<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class CategoryService
{
  protected string $cacheKey = 'categories_generic_tree';

  protected string $defaultImage = '/images/store/default/default300.webp';

  public function get(): array
  {
    return Cache::rememberForever($this->cacheKey, fn() => $this->buildTree());
  }

  public function rebuild(): array
  {
    Cache::forget($this->cacheKey);
    return $this->get();
  }

  protected function buildTree(): array
  {
    $today = Carbon::now(config('app.timezone'))->format('Y-m-d');

    $categories = Category::query()
      ->where('active', 1)
      ->whereDate('start_date', '<=', $today)
      ->whereDate('end_date', '>=', $today)
      ->with([
        'media:id,path,name,sequence',
        'subcategory.category.media:id,path,name,sequence',
        'subcategory.category.subcategory.category.media:id,path,name,sequence'
      ])
      ->orderBy('sequence')
      ->get();

    return $this->formatTree($categories);
  }

  protected function formatTree($categories): array
  {
    return $categories
      ->where('has_parent', 0)
      ->sortBy('sequence')
      ->map(fn($cat) => $this->mapCategory($cat))
      ->values()
      ->toArray();
  }

  protected function mapCategory($category): array
  {
    return [
      'id'        => $category->id,
      'name'      => $category->name,
      'slug'      => $category->seo_id ?: $category->id,
      'sequence'  => $category->sequence,
      'store_tab' => (bool) $category->store_tab,

      'min_image' => $this->resolveMinImage($category),

      'children' => $category->subcategory
        ->map(fn($s) => $this->mapCategory($s->category))
        ->sortBy('sequence')
        ->values()
        ->toArray(),
    ];
  }

  protected function resolveMinImage($category): string
  {
    $media = $category->media
      ->firstWhere('sequence', 1); // sequence 1 = min image

    return $media
      ? '/' . $media->path . $media->name
      : $this->defaultImage;
  }
  public function getBreadcrumbs(int $categoryId): array
  {
    $categories = collect($this->get());

    $breadcrumbs = [];
    $current = $categories->firstWhere('id', $categoryId);

    while ($current) {
      array_unshift($breadcrumbs, [
        'name' => strip_tags($current['name']),
        'slug' => $current['slug'],
        'id'   => $current['id'],
      ]);

      $current = $this->findParent($categories, $current['id']);
    }

    return $breadcrumbs;
  }

  protected function findParent($categories, int $childId): ?array
  {
    foreach ($categories as $cat) {
      if (!empty($cat['children'])) {
        foreach ($cat['children'] as $child) {
          if ($child['id'] === $childId) {
            return $cat;
          }

          // second level
          if (!empty($child['children'])) {
            foreach ($child['children'] as $subChild) {
              if ($subChild['id'] === $childId) {
                return $child;
              }
            }
          }
        }
      }
    }

    return null;
  }
}
