<?php

namespace App\Services;

use App\Models\Category;
use App\DTO\CategoryDTO;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class CategoryService
{
  protected string $defaultImage = '/images/store/default/default300.webp';


  public function get(array $with = ['tree', 'media']): array
  {
    return Cache::tags($this->tags())
      ->rememberForever($this->treeKey(), fn() => $this->buildTree($with));
  }

  public function getHeader(): array
  {
    return Cache::tags($this->tags())
    ->rememberForever($this->headerKey(), function () {
        $limit = (int) (app('global_limit_category') ?? 5);

        return collect($this->get())
            ->where('has_parent', 0)
            ->where('store_tab', true)
            ->sortBy('sequence')
            ->take($limit)
            ->values()
            ->toArray();
    });

  }
  public function getAll(): array
  {
    return Cache::tags($this->tags())
      ->rememberForever($this->allKey(), function () {
        return $this->flattenWithoutChildren($this->get());
      });
  }
  protected function flattenWithoutChildren(array $tree): array
  {
    $flat = [];

    $walk = function (array $nodes) use (&$walk, &$flat) {
      foreach ($nodes as $node) {
        $item = $node;
        unset($item['children']);

        $flat[] = $item;

        if (!empty($node['children'])) {
          $walk($node['children']);
        }
      }
    };

    $walk($tree);

    return $flat;
  }


  public function getLookup(): array
  {
    return Cache::tags($this->tags())
      ->rememberForever($this->lookupKey(), fn() => $this->buildLookup($this->get()));
  }

  public function getBreadcrumbs(int $categoryId): array
  {
    $lookup = $this->getLookup();
    $breadcrumbs = [];

    while (isset($lookup[$categoryId])) {
      $cat = $lookup[$categoryId];

      array_unshift($breadcrumbs, [
        'id'   => $cat['id'],
        'name' => strip_tags($cat['name']),
        'slug' => $cat['slug'],
      ]);

      $categoryId = $cat['parent_id'];
    }

    return $breadcrumbs;
  }

  public function getSliderItems(): array
  {
    return collect($this->get())
      ->filter(fn($c) => $c['slider_sequence'] > 0)
      ->sortBy('slider_sequence')
      ->values()
      ->toArray();
  }

  public function rebuild(): void
  {
    Cache::tags($this->tags())->flush();
  }



  protected function buildTree(array $with): array
  {
    $today = Carbon::now()->toDateString();

    $query = Category::query()
      ->where('active', 1)
      ->whereDate('start_date', '<=', $today)
      ->whereDate('end_date', '>=', $today)
      ->orderBy('sequence');

    if (in_array('media', $with)) {
      $query->with('media:id,path,name,sequence,width,height');
    }

    if (in_array('tree', $with)) {
      $query->with([
        'subcategory.category.media:id,path,name,sequence,width,height',
        'subcategory.category.subcategory.category.media:id,path,name,sequence,width,height',
      ]);
    }

    $categories = $query->get();

    return $categories
      ->map(fn($cat) => $this->mapCategory($cat))
      ->values()
      ->map(fn(CategoryDTO $dto) => $dto->toArray())
      ->toArray();
  }

  protected function mapCategory($category, ?int $parentId = null): CategoryDTO
  {
    $media = $category->media->keyBy('sequence');

    return new CategoryDTO(
      id: $category->id,
      name: $category->name,
      slug: $category->seo_id ?: (string) $category->id,
      sequence: $category->sequence,
      sliderSequence: (int) $category->slider_sequence,
      storeTab: (bool) $category->store_tab,
      hasParent: (bool) $category->has_parent,
      minImage: $this->resolveMinImage($media),
      sliderMedia: $this->resolveSliderMedia($media),
      children: $category->subcategory
        ->map(fn($s) => $this->mapCategory($s->category, $category->id))
        ->values()
        ->all(),
      parentId: $parentId
    );
  }



  protected function resolveMinImage($media): string
  {
    return isset($media[1])
      ? '/' . $media[1]->path . $media[1]->name
      : $this->defaultImage;
  }

  protected function resolveSliderMedia($media): array
  {
    return [
      2 => $this->formatMedia($media[2] ?? null),
      3 => $this->formatMedia($media[3] ?? null),
      4 => $this->formatMedia($media[4] ?? null),
    ];
  }

  protected function formatMedia($media): ?array
  {
    return $media ? [
      'src'    => '/' . $media->path . $media->name,
      'width'  => $media->width,
      'height' => $media->height,
      'name'   => $media->name,
    ] : null;
  }



  protected function buildLookup(array $tree): array
  {
    $map = [];

    $walk = function ($nodes) use (&$walk, &$map) {
      foreach ($nodes as $node) {
        $map[$node['id']] = $node;

        if (!empty($node['children'])) {
          $walk($node['children']);
        }
      }
    };

    $walk($tree);

    return $map;
  }



  protected function treeKey(): string
  {
    return "categories:tree";
  }

  protected function lookupKey(): string
  {
    return "categories:lookup";
  }

  protected function tags(): array
  {
    return ['categories'];
  }
  protected function headerKey(): string
  {
    return 'categories:header';
  }

  protected function allKey(): string
  {
    return 'categories:all';
  }
}
