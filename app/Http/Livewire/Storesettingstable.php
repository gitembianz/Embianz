<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Models\Static_Page;

use App\Models\Exchange;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Store_Settings;
use Illuminate\Support\Carbon;
use App\Models\PricelistEntries;
use Database\Seeders\StoreSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use App\Models\ProductReviews as ModelsProductReviews;
use Illuminate\Support\Facades\Storage;


class Storesettingstable extends Component
{

  use WithPagination;
  use WithFileUploads;

  public $loadAmount = 30;
  public $search = '';
  public $orderBy = 'id';
  public $orderAsc = true;
  public $itemidbeingremoved = null;
  public $columns = ['Id', 'Value', 'Description', 'Created At', 'Updated At'];
  public $selectedColumns = [];
  public $editindex = null;
  public $settings = [];
  public $row = null;
  public $changelogodark = false;
  public $changelogolight = false;
  public $changefavicon = false;
  public $external = false;
  public $media;
  public $mediaurl =  null;


public function saveexternal()
  {
    $filespath = 'images/store/';

    $path = $filespath . $this->product->id . "/";


      $urlComponents = parse_url($this->mediaurl);

      $urlWithoutParams = $urlComponents['scheme'] . '://' . $urlComponents['host'] . $urlComponents['path'];
      $this->mediaurl = $urlWithoutParams;
      if($this->changefavicon) {
        $name = 'favicon.' . pathinfo($this->mediaurl, PATHINFO_EXTENSION);
      } elseif ($this->changelogodark) {
        $name = 'logo_dark.' . pathinfo($this->mediaurl, PATHINFO_EXTENSION);
      } elseif ($this->changelogolight) {
        $name = 'logo_light.' . pathinfo($this->mediaurl, PATHINFO_EXTENSION);
      }
      $allowedExtensions = ['ico', 'svg'];
      $fileExtension = strtolower(pathinfo($this->mediaurl, PATHINFO_EXTENSION));

      if (!in_array($fileExtension, $allowedExtensions)) {
        return session()->flash('notification', [
          'message' => 'File type not allowed!',
          'type' => 'warning',
          'title' => 'Warning'
        ]);
      }
      $fileContent = file_get_contents($this->mediaurl);
      if ($fileContent == false) {
        return session()->flash('notification', [
          'message' => 'Not image file!',
          'type' => 'warning',
          'title' => 'Warning'
        ]);
      }
      if ($this->changefavicon) {
        $name = 'favicon.' . $fileExtension;
      } elseif ($this->changelogodark) {
        $name = 'logo_dark.' . $fileExtension;
      } elseif ($this->changelogolight) {
        $name = 'logo_light.' . $fileExtension;
      }
      Storage::disk('public_upload')->put($path . $name, $fileContent);



      session()->flash('notification', [
        'message' => 'Record related successfully!',
        'type' => 'success',
        'title' => 'Success'
      ]);
    $this->mediaurl = null;
    $this->external = false;
    $this->changelogodark = false;
    $this->changelogolight = false;
    $this->changefavicon = false;
  }

public function closeModalLogo()
{
    $this->changelogodark = false;
    $this->changelogolight = false;
    $this->changefavicon = false;
    $this->external = false;
}


  public function expandRow($index)
  {
    if ($this->editindex === $index) {
      return;
    } else {

      if ($this->row  === null) {
        $this->row = $index;
      } elseif ($this->row != $index) {
        $this->row = $index;
      } else {
        $this->row = null;
      }
    }
  }
  public function seedreviews()
  {
    $prods = Product::where('active', true)
      ->where('start_date', '<=', now()->format('Y-m-d'))
      ->where('end_date', '>=', now()->format('Y-m-d'))->get();

    foreach ($prods as $product) {
      if (!$product->reviews->first()) {
        $value = (100 / (app('max_popularity') / $product->popularity)) / 20;

        ModelsProductReviews::create([
          'product_id' => $product->id,
          'count' => 1,
          'value' => $value
        ]);
      }
    }
    session()->flash('notification', [
      'message' => 'Reviews added successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }

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

    exec('rm -rf bootstrap/cache/*.php');

    Cache::forget('global_variables');
    Cache::forget('global_statuses');
    Cache::forget('global_payments');
    Cache::forget('global_scripts');
    Cache::forget('static_pages');

    session()->flash('notification', [
      'message' => 'Website is updated!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }


  public function loadMore()
  {
    $this->loadAmount += 10;
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
  public function getStoresettingsProperty()
  {
    return $this->storesettingsQuery->paginate($this->loadAmount);
  }
  public function getStoresettingsQueryProperty()
  {
    return Store_Settings::search($this->search)
      ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc');
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
    $this->editindex = $index;
    $this->row = $index;
    $this->settings = [
      $index . '.value' => $record->value,
    ];
  }
  public function saveitem($index, $id)
  {
    $update = $this->settings[$index] ?? NULL;
    if (!is_null($update)) {
      $item = Store_Settings::find($id);

      if (array_key_exists('value', $update)) {
        if ($item->parameter == 'numberformat_element') {
          if ($update['value'] != '.' && $update['value'] != ',') {
            session()->flash('notification', [
              'message' => 'Value must be . or ,',
              'type' => 'warning',
            ]);
            return;
          }
        }
        $item->value = $update['value'];
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
      if ($item->parameter == 'mailserver_mail') {
        if ($item->value != '') {
          $envPath = base_path('.env');
          $content = File::get($envPath);

          $content = preg_replace('/^MAIL_USERNAME=.*/m', "MAIL_USERNAME=" . $item->value, $content);

          File::put($envPath, $content);
        }
      }
      if ($item->parameter == 'mailserver_from_mail') {
        if ($item->value != '') {
          $envPath = base_path('.env');
          $content = File::get($envPath);

          $content = preg_replace('/^MAIL_FROM_ADDRESS=.*/m', "MAIL_FROM_ADDRESS=" . $item->value, $content);

          File::put($envPath, $content);
        }
      }
      if ($item->parameter == 'mailserver_password') {
        if ($item->value != '') {
          $envPath = base_path('.env');
          $content = File::get($envPath);

          // Replace the MAIL_PASSWORD line with the new value wrapped in double quotes
          $content = preg_replace(
            '/^MAIL_PASSWORD=.*/m',
            'MAIL_PASSWORD="' . addslashes($item->value) . '"', // Escape quotes or special characters in the password
            $content
          );

          File::put($envPath, $content);
        }
      }
      if ($item->parameter == 'mailserver_from_name') {
        if ($item->value != '') {
          $envPath = base_path('.env');
          $content = File::get($envPath);

          // Replace the MAIL_PASSWORD line with the new value wrapped in double quotes
          $content = preg_replace(
            '/^MAIL_FROM_NAME=.*/m',
            'MAIL_FROM_NAME="' . addslashes($item->value) . '"', // Escape quotes or special characters in the password
            $content
          );

          File::put($envPath, $content);
        }
      }
      if ($item->parameter == 'cache_data') {
        if ($item->value != 'true') {
          Cache::forget('cached_products');
          Cache::forget('cached_categories');
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
      if ($item->parameter == 'lang') {
        if (array_key_exists('value', $update)) {
          if ($update['value'] = !'') {
            $envPath = base_path('.env');
            $content = File::get($envPath);
            $content = preg_replace('/^APP_LANG=.*/m', "APP_LANG=" . $item->value, $content);
            File::put($envPath, $content);
          }
        }
      }
      if ($item->parameter == 'locale') {
        if (array_key_exists('value', $update)) {
          if ($update['value'] = !'') {
            $envPath = base_path('.env');
            $content = File::get($envPath);
            $content = preg_replace('/^APP_LOCALE=.*/m', "APP_LOCALE=" . $item->value, $content);
            File::put($envPath, $content);
          }
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
    $this->editindex = null;
  }
  public function cancelitem()
  {
    $this->editindex = null;
    $this->settings = [];
  }

  public function refreshprices()
  {
    $prices = PricelistEntries::all();

    foreach ($prices as $price) {
      if (!is_null($price->value_no_vat) && is_null($price->value)) {
        $price->value_no_discount = round($price->value_no_vat * (1 + $price->vat / 100), 2);

        $price->value = round($price->value_no_discount * (1 - $price->discount / 100), 2);
      } elseif (!is_null($price->value) && $price->discount > 0) {
        $price->value_no_discount = round($price->value / (1 - $price->discount / 100), 2);

        $price->value_no_vat = round($price->value_no_discount / (1 + $price->vat / 100), 2);
      } elseif (!is_null($price->value) && $price->discount == 0) {
        $price->value_no_vat = round($price->value / (1 + $price->vat / 100), 2);

        $price->value_no_discount = round($price->value_no_vat * (1 + $price->vat / 100), 2);
      }

      $price->save();
    }
    $products = Product::where('active', true)
      ->where('start_date', '<=', now()->format('Y-m-d'))
      ->where('end_date', '>=', now()->format('Y-m-d'))
      ->get();

    foreach ($products as $product) {
      $cartPrices = $product->carts_item()->pluck('price');
      if ($cartPrices->isNotEmpty()) {
        $averagePrice = $cartPrices->avg();
      } else {
        $averagePrice = optional($product->product_prices->first())->value;
      }

      $totalCost = 0;
      $count = 0;
      foreach ($product->order_suppliers->where('order.status', 'closed') as $orderSupplier) {
        $cost = $orderSupplier->price;
        $supplierCurrency = $orderSupplier->order->currency ?? null;
        $productCurrency = optional($product->product_prices->first())->pricelist->currency->name ?? null;

        if ($supplierCurrency && $productCurrency && $supplierCurrency !== $productCurrency) {
          $exchange = Exchange::whereHas('base_currency', function ($q) use ($supplierCurrency) {
            $q->where('name', $supplierCurrency);
          })->whereHas('quote_currency', function ($q) use ($productCurrency) {
            $q->where('name', $productCurrency);
          })->latest()->first();

          if (!$exchange) {
            $exchange = Exchange::whereHas('base_currency', function ($q) use ($productCurrency) {
              $q->where('name', $productCurrency);
            })->whereHas('quote_currency', function ($q) use ($supplierCurrency) {
              $q->where('name', $supplierCurrency);
            })->latest()->first();

            if ($exchange) {
              $cost /= $exchange->value;
            }
          } else {
            $cost *= $exchange->value;
          }
        }

        if ($cost) {
          $totalCost += $cost;
          $count++;
        }
      }


      $averageCost = $count > 0 ? ($totalCost / $count) : null;

      $oldprice = optional($product->costs()->latest()->first())->price ?? null;

      if ($oldprice && $oldprice != $averagePrice) {
        DB::table('product_costs')->insert([
          'product_id' => $product->id,
          'price' => $averagePrice,
          'cost' => $averageCost,
          'date' => now(),
          'created_by' => auth()->user()->name,
          'last_modified_by' => auth()->user()->name,
          'created_at' => now(),
          'updated_at' => now()
        ]);
      } elseif (!$oldprice) {

        DB::table('product_costs')->updateOrInsert(
          ['product_id' => $product->id],
          ['price' => $averagePrice, 'cost' => $averageCost, 'date' => now(), 'created_by' => auth()->user()->name, 'last_modified_by' => auth()->user()->name, 'created_at' => now(), 'updated_at' => now()]
        );
      }
    }

    session()->flash('notification', [
      'message' => 'Prices corrected successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
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

// Homepage
$url = $xml->addChild('url');
$url->addChild('loc', url('/'));
$url->addChild('lastmod', now()->toAtomString());
$url->addChild('priority', '1.0');
$staticpages = Cache::rememberForever('static_pages', function () {
return Static_Page::all();
});

// Static pages
$pages = [];
foreach ($staticpages as $staticpage) {
$pages[$staticpage->route] = '0.5';
}

foreach ($pages as $page => $priority) {
$url = $xml->addChild('url');
$url->addChild('loc', url($page));
$url->addChild('lastmod', now()->toAtomString());
$url->addChild('priority', $priority);
}

// Active Products
$products = Product::where('active', true)
->where('type', '!=', 'parent')
->where('start_date', '<=', Carbon::now())->where('end_date', '>=', Carbon::now())
    ->get();

    foreach ($products as $product) {
    $url = $xml->addChild('url');
    $productUrl = route('product', ['product' => $product->seo_id ?? $product->id]);
    $url->addChild('loc', htmlspecialchars($productUrl));
    $url->addChild('lastmod', now()->toAtomString());
    $url->addChild('priority', '0.8');
    }

    // Global default category
    if (app()->has('global_default_category') && app('global_default_category') != "") {
    $defaultCategory = Category::find(app('global_default_category'));
    if ($defaultCategory) {
    $this->generateCategoryPages($xml, $defaultCategory, true);
    }
    }

    // All other categories
    $categories = Category::where('active', true)
    ->where('start_date', '<=', Carbon::now())->where('end_date', '>=', Carbon::now())
        ->get();

        foreach ($categories as $category) {
        if (isset($defaultCategory) && $category->id == $defaultCategory->id) {
        continue;
        }
        $this->generateCategoryPages($xml, $category, false);
        }

        $xml->asXML($filePath);
        chmod($filePath, 0755);

        session()->flash('notification', [
        'message' => 'Sitemap generated successfully!',
        'type' => 'success',
        'title' => 'Success'
        ]);
        }

        /**
        * Generate paginated URLs for a category in the sitemap
        *
        * @param SimpleXMLElement $xml
        * @param Category $category
        * @param bool $isDefaultCategory
        * @return void
        */
        private function generateCategoryPages(&$xml, $category, $isDefaultCategory = false)
        {
        // Count the products associated with the category that meet the conditions
        $productsCount = $category->product_categories()
        ->whereHas('product', function ($query) {
        $query->where('active', true)
        ->where('start_date', '<=', Carbon::now())->where('end_date', '>=', Carbon::now());
            })
            ->count();

            $limit = config('global.global_limit_load', 16); // Fetch the global limit, default to 16
            $totalPages = ceil($productsCount / $limit);

            for ($page = 1; $page <= $totalPages; $page++) { $url=$xml->addChild('url');

                // Generate the category URL with pagination
                $categoryUrl = route('products', [
                'categorySlug' => $category->seo_id ?? $category->id
                ]);

                if ($page > 1) {
                $categoryUrl .= "?page=" . $page; // Append ?page=N for paginated pages
                }

                $url->addChild('loc', htmlspecialchars($categoryUrl));
                $url->addChild('lastmod', now()->toAtomString());
                $url->addChild('priority', $isDefaultCategory ? '0.9' : '0.8');
                }
                }



                public function refreshfilters()
                {
                Cache::forget('cached_specifications');
                session()->flash('notification', [
                'message' => 'Fileters update successfully!',
                'type' => 'success',
                'title' => 'Success'
                ]);
                }



                public function addSettingsIfNotExist()
                {
                $settings = StoreSeeder::settings();
                foreach ($settings as $setting) {
                $exists = DB::table('store__settings')
                ->where('parameter', $setting['parameter'])
                ->exists();

                if (!$exists) {
                DB::table('store__settings')->insert([
                'parameter' => $setting['parameter'],
                'value' => $setting['value'],
                'description' => $setting['description'],
                'createdby' => 'admin',
                'lastmodifiedby' => 'admin',
                'created_at' => $setting['created_at'],
                'updated_at' => $setting['updated_at']
                ]);
                }
                }
                Cache::forget('global_variables');
                session()->flash('notification', [
                'message' => 'Parameters update successfully!',
                'type' => 'success',
                'title' => 'Success'
                ]);
                }
                }
