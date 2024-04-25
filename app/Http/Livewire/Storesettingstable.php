<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Store_Settings;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Carbon;



class Storesettingstable extends Component
{

  use WithPagination;
  public $loadAmount = 10;
  public $search = '';
  public $orderBy = 'id';
  public $orderAsc = true;
  public $checked = [];
  public $selectPage = false;
  public $selectAll = false;
  public $itemidbeingremoved = null;
  public $columns = ['Id', 'Value', 'Description', 'Created At', 'Updated At'];
  public $selectedColumns = [];
  public $indexstoresettings = null;
  public $settings = [];

  public function render()
  {
    return view('livewire.storesettingstable', [
      'storesettings' => $this->storesettings
    ]);
  }
  public function mount()
  {
    $this->selectedColumns = $this->columns;
  }
  public function showColumn($column)
  {
    if ($column === 'Parameter') {
      return true;
    }
    return in_array($column, $this->selectedColumns);
  }
  public function actualizeaza()
  {
    Artisan::call('cache:clear');
    Artisan::call('clear-compiled');
    Artisan::call('view:clear');
    Artisan::call('config:cache');
    Artisan::call('config:clear');
    Artisan::call('event:clear');
    Artisan::call('queue:clear');
    Artisan::call('optimize:clear');
    Artisan::call('migrate');
    Cache::forget('global_variables');
    Cache::forget('global_statuses');
    Cache::forget('global_payments');
    Cache::forget('global_scripts');
    session()->flash('notification', [
      'message' => 'Website is updated!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function updatedSelectPage($value)
  {
    if ($value) {
      $this->checked = $this->storesettings->pluck('id')->map(fn ($item) => (string) $item)->toArray();
    } else {
      $this->checked = [];
    }
  }
  public function loadMore()
  {
    $this->loadAmount += 10;
  }
  public function updatedChecked()
  {
    $this->selectPage = false;
  }
  public function sortBy($columnName)
  {
    if ($this->orderBy === $columnName) {
      $this->orderAsc = $this->swapSortDirection();
    } else {
      $this->orderAsc = '1';
    }
    $this->orderBy = $columnName;
  }
  public function swapSortDirection()
  {
    return $this->orderAsc === '1' ? '0' : '1';
  }
  public function selectAll()
  {
    $this->selectAll = true;
    $this->checked = $this->storesettingsQuery->pluck('id')->map(fn ($item) => (string) $item)->toArray();
  }
  public function getStoresettingsProperty()
  {
    return $this->storesettingsQuery->limit($this->loadAmount)->get();
  }
  public function getStoresettingsQueryProperty()
  {
    return Store_Settings::search($this->search)
      ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc');
  }
  public function deleteRecords()
  {
    $items = Store_Settings::whereKey($this->checked)->get();
    foreach ($items as $item) {
      $id = $item->id;
      $itemdel = Store_Settings::find($id);
      $itemdel->delete();
    }
    $this->checked = [];
    $this->selectPage = false;
    session()->flash('notification', [
      'message' => 'Records deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function deleteSingleRecord()
  {
    $id = $this->itemidbeingremoved;
    $item = Store_Settings::findOrFail($id);
    $item->delete();
    $this->checked = array_diff($this->checked, [$id]);
    session()->flash('notification', [
      'message' => 'Record deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function confirmItemRemoval($id)
  {
    $this->itemidbeingremoved = $id;
    $this->dispatchBrowserEvent('show-delete-modal');
  }
  public function confirmItemsRemovalmultiple()
  {
    $this->dispatchBrowserEvent('show-delete-modal-multiple');
  }
  public function isChecked($id)
  {
    return in_array($id, $this->checked);
  }
  public function edititem($index, $id)
  {
    $record = Store_Settings::find($id);
    $this->indexstoresettings = $index;
    $this->settings = [
      $index . '.value' => $record->value,
      $index . '.description' => $record->description,
    ];
  }
  public function saveitem($index, $id)
  {
    $update = $this->settings[$index] ?? NULL;
    if (!is_null($update)) {
      $item = Store_Settings::find($id);

      if (array_key_exists('value', $update)) {
        $item->value = $update['value'];
      }
      if (array_key_exists('description', $update)) {
        $item->description = $update['description'];
      }
      $item->save();
      if ($item->parameter == 'app_debug') {
        if ($item->value == 'true') {
          $envPath = base_path('.env');
          $content = File::get($envPath);

          $content = preg_replace('/^APP_DEBUG=.*/m', "APP_DEBUG=true", $content);

          File::put($envPath, $content);
        } else {
          $envPath = base_path('.env');
          $content = File::get($envPath);

          $content = preg_replace('/^APP_DEBUG=.*/m', "APP_DEBUG=false", $content);

          File::put($envPath, $content);
        }
      }
      if ($item->parameter == 'robots_txt') {
        if (array_key_exists('value', $update)) {
          if ($item->value = !'') {
            $filepath = public_path('robots.txt');
            $content = str_replace('<br>', "\r\n", $update['value'], $content);
            File::put($filepath, $content);
            chmod($filepath, 0755);
          }
        }
      }
      if ($item->parameter == 'time_zone') {
        if (preg_match('/^[-+]?([0-9]|1[0-2])$/', $item->value)) {
          $envPath = base_path('.env');
          $content = File::get($envPath);
          if (strpos($item->value, '-') !== false) {
            $adjustedValue = str_replace('-', '+', $item->value);
          } elseif (strpos($item->value, '+') !== false) {
            $adjustedValue = str_replace('+', '-', $item->value);
          }
          $content = preg_replace('/^APP_TIMEZONE=.*/m', "APP_TIMEZONE=Etc/GMT" . $adjustedValue, $content);
          File::put($envPath, $content);
        }
      }
      Cache::forget('global_variables');
      session()->flash('notification', [
        'message' => 'Record edited successfully!',
        'type' => 'success',
        'title' => 'Success'
      ]);
    } else {
      session()->flash('notification', [
        'message' => 'Nothing chnaged!',
        'type' => 'success',
        'title' => 'Success'
      ]);
    }
    $this->settings = [];
    $this->indexstoresettings = null;
  }
  public function cancelitem()
  {
    $this->indexstoresettings = null;
    $this->settings = [];
  }

  public function initializeSitemap()
  {
    $filePath = public_path('sitemap.xml');

    return $this->createNewSitemap($filePath);
  }

  private function createNewSitemap($filePath)
  {
    $xmlString = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL .
    '</urlset>';
file_put_contents($filePath, $xmlString);
return simplexml_load_string($xmlString);
}


public function sitemap()
{
$filePath = public_path('sitemap.xml');
$xml = $this->initializeSitemap();

//homepage
$url = $xml->addChild('url');
$url->addChild('loc', url('/'));
$url->addChild('lastmod', now()->toDateString());
$url->addChild('priority', '1.0');

//static pages
$pages = [
'/faq' => '0.5',
'/cookies' => '0.5',
'/privacy' => '0.5',
'/contact' => '0.5',
'/about' => '0.5',
'/terms' => '0.5'
];

foreach ($pages as $page => $priority) {
$url = $xml->addChild('url');
$url->addChild('loc', url($page));
$url->addChild('lastmod', now()->toDateString());
$url->addChild('priority', $priority);
}
// Fetch and add active products
$products = Product::where('active', true)
->where('start_date', '<=', Carbon::now())->where('end_date', '>=', Carbon::now())
    ->get();

    foreach ($products as $product) {
    $url = $xml->addChild('url');
    $productUrl = route('product', ['product' => $product->seo_id ?? $product->id]);
    $url->addChild('loc', htmlspecialchars($productUrl));
    $url->addChild('lastmod', $product->updated_at);
    $url->addChild('priority', '0.8');
    }

    if (app()->has('global_default_category') && app('global_default_category') != "") {
    $default_category = Category::find(app('global_default_category'));
    if ($default_category != null) {
    $url = $xml->addChild('url');
    $default_categoryUrl = route('products', ['categorySlug' => $default_category->seo_id ??
    $default_category->id]);
    $url->addChild('loc', htmlspecialchars($default_categoryUrl));
    $url->addChild('lastmod', $default_category->updated_at);
    $url->addChild('priority', '0.9');
    }
    $categories = Category::where('active', true)
    ->where('start_date', '<=', Carbon::now())->where('end_date', '>=', Carbon::now())
        ->where('id', '!=', $default_category->id)->get();
        }

        foreach ($categories as $category) {
        $url = $xml->addChild('url');
        $categoryUrl = route('products', ['categorySlug' => $category->seo_id ?? $category->id]);
        $url->addChild('loc', htmlspecialchars($categoryUrl));
        $url->addChild('lastmod', $category->updated_at);
        $url->addChild('priority', '0.9');
        }


        $xml->asXML($filePath);
        chmod($filePath, 0755);

        session()->flash('notification', [
        'message' => 'Sitemap generated successfully!',
        'type' => 'success',
        'title' => 'Success'
        ]);
        }
        }