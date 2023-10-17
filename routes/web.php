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

  Route::get('/', function () {
    return view('store.home');
  });

  Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {

    //Category routes
    route::get('/category', [CategoryController::class, 'category'])->name('category');
    route::post('/add_category', [CategoryController::class, 'add_category']);
    route::get('/show_category/{id}/', [CategoryController::class, 'show'])->name('show_category');
    route::get('/new_categories', [CategoryController::class, 'new'])->name('newcategory');

    //Products routes
    route::get('/products', [ProductController::class, 'products'])->name('products');
    route::get('/add_product', [ProductController::class, 'add'])->name('add_product');
    route::post('/new_products', [ProductController::class, 'new'])->name('new_products');
    route::get('/show_product/{id}/', [ProductController::class, 'show'])->name('show_product');

    //accounts routes
    route::get('/accounts', [AdminController::class, 'accounts'])->name('accounts');
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
    route::get(
      '/pricelists',
      [PriceListController::class, 'index']
    )->name('pricelists');
    route::get('/newpricelist', [PriceListController::class, 'create'])->name('newpricelist');
    route::post('/add_pricelist', [PriceListController::class, 'store']);
    route::get('/show_pricelist/{id}/', [PriceListController::class, 'show'])->name('show_pricelis');

    route::get(
      '/dashboard',
      [HomeController::class, 'redirect']
    )->middleware('auth', 'verified')->name('dashboard');

    //general routes
    route::get('/orders', [AdminController::class, 'orders']);
    route::get('/vouchers', [AdminController::class, 'vouchers'])->name('vouchers');
    route::get('/newvoucher', [AdminController::class, 'create_voucher'])->name('newvoucher');
    route::post('/add_voucher', [AdminController::class, 'store_voucher']);
    route::get('/storesettings', [AdminController::class, 'storesettings'])->name('storesettings');
    route::get('/addstoresettings', [AdminController::class, 'addstoresetting'])->name('addstoresetting');
  });

  //store routes
  route::get('/home', [StoreController::class, 'index'])->name('home');
  route::get('/cart', [StoreController::class, 'cart'])->name('cart');
  route::get('/wislist', [StoreController::class, 'wislist'])->name('wislist');
  route::get('/complete', [StoreController::class, 'complete'])->name('complete');
  route::get('/order', [StoreController::class, 'order'])->name('order');
  route::get('/product/{id}/', [StoreController::class, 'show'])->name('product');
  Route::get('/storeproducts/{category?}', [StoreController::class, 'products']);
  route::get('/terms', [StoreController::class, 'terms'])->name('terms');




  //Clear Cache

  Route::get('/cleareverything', function () {
    $clearcache = Artisan::call('cache:clear');
    echo "Cache cleared<br>";
    $clearview = Artisan::call('view:clear');
    echo "View cleared<br>";
    $cacheconfig = Artisan::call('config:cache');
    echo "Config cache<br>";
    $cacheclear = Artisan::call('config:clear');
    echo "Config clear<br>";
    $eventclear = Artisan::call('event:clear');
    echo "event clear<br>";
    $queueclear = Artisan::call('queue:clear');
    echo "queue clear<br>";
    $optimize = Artisan::call('optimize:clear');
    echo "Optimize clear<br>";
    $migrate = Artisan::call('migrate');
    echo "DB updated<br>";
  });
  Route::get('/seeddatabase', function () {
    $seed = Artisan::call('db:seed --class=CurrencySeeder');
    echo "Databese seeded<br>";
  });

  //Update app
  Route::get('/updateapp', function () {
    exec('composer dump-autoload');
    echo 'composer dump-autoload';
  });
