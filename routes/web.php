 <?php

  use App\Http\Controllers\AdminController;
  use App\Http\Controllers\CartController;
  use Illuminate\Support\Facades\Route;
  use Illuminate\Support\Facades\Artisan;
  use App\Http\Controllers\HomeController;
  use App\Http\Controllers\ProductController;
  use App\Http\Controllers\CategoryController;
  use App\Http\Controllers\PriceListController;
  use App\Http\Controllers\SpecsController;
  use App\Http\Controllers\StoreController;
  use App\Http\Controllers\TodolistController;
  use Illuminate\Support\Facades\Cache;


  /*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



  route::middleware(['auth', 'usertype'])->group(function () {


    //Category routes
    route::get('/category', [CategoryController::class, 'category'])->name('category');
    route::post('/add_category', [CategoryController::class, 'add_category']);
    route::get('/show_category/{id}/', [CategoryController::class, 'show'])->name('show_category');
    route::get('/new_categories', [CategoryController::class, 'new'])->name('newcategory');

    //Products routes
    route::get('/products', [ProductController::class, 'products']);
    route::get('/add_product', [ProductController::class, 'add'])->name('add_product');
    route::post('/new_products', [ProductController::class, 'new'])->name('new_products');
    route::get('/show_product/{id}/', [ProductController::class, 'show'])->name('show_product');

    //todolist routes
    route::post('/new', [TodolistController::class, 'store'])->name('store');
    route::delete('/{todolist:id}', [TodolistController::class, 'destroy'])->name('destroy');

    //carts route
    route::get('/carts', [CartController::class, 'index'])->name('carts');
    route::get('/show_cart/{id}/', [CartController::class, 'show'])->name('show_cart');

    //specs route
    route::get('/specs', [SpecsController::class, 'index'])->name('specs');
    route::get('/newspec', [SpecsController::class, 'create'])->name('newspec');
    route::post('/add_spec', [SpecsController::class, 'store']);
    route::get('/show_spec/{id}/', [SpecsController::class, 'show'])->name('show_spec');

    //pricelist route
    route::get('/pricelists', [PriceListController::class, 'index'])->name('pricelists');
    route::get('/newpricelist', [PriceListController::class, 'create'])->name('newpricelist');
    route::post('/add_pricelist', [PriceListController::class, 'store']);
    route::get('/show_pricelist/{id}/', [PriceListController::class, 'show'])->name('show_pricelis');

    route::get(
      '/embadmin',
      [HomeController::class, 'redirect']
    )->middleware('auth', 'verified')->name('dashboard');

    //general routes
    route::get('/show_script/{id}/', [AdminController::class, 'show_script'])->name('show_script');
    route::get('/show_account/{id}/', [AdminController::class, 'show_account'])->name('show_account');
    route::get('/accounts', [AdminController::class, 'accounts'])->name('accounts');
    route::get('/orders', [AdminController::class, 'orders']);
    route::get('/show_order/{id}/', [AdminController::class, 'show_order'])->name('show_order');
    route::get('/payments', [AdminController::class, 'payments'])->name('payments');
    route::get('/vouchers', [AdminController::class, 'vouchers'])->name('vouchers');
    route::get('/newvoucher', [AdminController::class, 'create_voucher'])->name('newvoucher');
    route::post('/add_voucher', [AdminController::class, 'store_voucher']);
    route::get('/storesettings', [AdminController::class, 'storesettings'])->name('storesettings');
    route::get('/customscripts', [AdminController::class, 'customscripts'])->name('customscripts');
    route::get('/new_script', [AdminController::class, 'create_script'])->name('newscript');
    route::post('/add_script', [AdminController::class, 'store_script']);

    route::get('/addstoresettings', [AdminController::class, 'addstoresetting'])->name('addstoresetting');

    //specific routes 
    route::get('/cleareverything', function () {
      Artisan::call('cache:clear');
      Artisan::call('clear-compiled');
      Artisan::call('view:clear');
      Artisan::call('config:cache');
      Artisan::call('config:clear');
      Artisan::call('event:clear');
      Artisan::call('queue:clear');
      Artisan::call('optimize:clear');
      Artisan::call('migrate');
      echo "App is optimized and updated";
    });

    route::get('/friendlyurl', function () {
      Artisan::call('update:seo_ids');
      echo 'New URL-s updated!';
    });

    route::get('/seed', function () {
      Artisan::call('db:seed');
      echo 'Database seeded';
    });

    route::get('/clear-cache', function () {
      Cache::forget('global_variables');
      Cache::forget('global_statuses');
      Cache::forget('global_payments');
      echo 'Cache cleared for global variables';
    });

    route::get('/update', function () {
      Artisan::call('migrate:fresh --seed');
      echo "New fresh app";
    });
    route::get('/corectsequence', [AdminController::class, 'correctMediaSequence']);
  });


  // Storefront
  route::get('/', [StoreController::class, 'index'])->name('home');
  route::get('/cart', [StoreController::class, 'cart'])->name('cart');
  route::get('/wishlist', [StoreController::class, 'wislist'])->name('wislist');
  route::get('/complete', [StoreController::class, 'complete'])->name('complete');
  route::get('/order', [StoreController::class, 'order'])->name('order');
  route::get('/product/{product}', [StoreController::class, 'show'])->name('product');
  route::get('/storeproducts/{categorySlug?}', [StoreController::class, 'products'])->name('products');
  route::get('/faq', [StoreController::class, 'faq'])->name('faq');
  route::get('/cookie', [StoreController::class, 'cookie'])->name('cookie');
  route::get('/privacy', [StoreController::class, 'privacy'])->name('privacy');
  route::get('/contact', [StoreController::class, 'contact'])->name('contact');
  route::get('/about', [StoreController::class, 'about'])->name('about');
  route::get('/confirm', [StoreController::class, 'confirm'])->name('confirm');
  route::get('/terms', [StoreController::class, 'terms'])->name('terms');
