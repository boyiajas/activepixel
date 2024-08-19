
<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DigitalDownloadController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentMethodController;








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
Auth::routes();
//require __DIR__.'/auth.php';

Route::get('/', function () {
    return view('welcome');
})->name('welcome');


Route::controller(PageController::class)->group(function () {
    Route::get('/contact-us', 'contact');
    Route::get('/about-us', 'about');
});

// Category Routes
Route::resource('categories', CategoryController::class)->only(['index', 'show']);
Route::get('categories/{category}/photos', [CategoryController::class, 'photos'])->name('categories.photos');

// Event Routes
Route::resource('events', EventController::class)->only(['index', 'show']);
Route::get('events/{event}/photos', [EventController::class, 'photos'])->name('events.photos');
Route::get('events/search', [EventController::class, 'search'])->name('events.search');
Route::get('events/{event}/categories', [EventController::class, 'categories'])->name('events.categories');



// Photo Routes
Route::resource('photos', PhotoController::class)->only(['index', 'show']);
Route::get('photos/download/{photo}', [PhotoController::class, 'download'])->name('photos.download');
Route::get('photos/search', [PhotoController::class, 'search'])->name('photos.search');
Route::get('photos/race/{race_number}', [PhotoController::class, 'photosByRace'])->name('admin.photos.race');


/** set side bar active dynamic */
function set_active($route) {
    if(is_array($route)) {
        return in_array(Request::path(), $route) ? 'active' : '';
    }
    return Request::path() == $route ? 'active': '';
}

/* Route::get('/', function () {
    return view('auth.login');
}); */

Route::group(['middleware'=>'auth'],function()
{
    Route::get('home',function()
    {
        return view('home');
    });
});

Route::group(['middleware' => ['sync.guest.cart']], function () {
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
});

  // Admin Routes
Route::prefix('customer')->middleware(['auth', 'role:admin|manager|user'])->group(function() {
        
    Route::get('/dashboard', [CustomerController::class, 'index'])->name('customer.dashboard'); 
    Route::get('/orders', [CustomerController::class, 'index2'])->name('customer.orders');
    Route::get('/invoices', [CustomerController::class, 'index'])->name('customer.invoices');
    Route::get('/downloads', [CustomerController::class, 'index'])->name('customer.downloads');
    Route::get('/account/settings', [CustomerController::class, 'index'])->name('customer.account.settings');


     // Cart
     //Route::resource('cart', CartController::class)->only(['index', 'store', 'destroy']);

     // Orders
     //Route::resource('orders', OrderController::class)->only(['index', 'store', 'show']);
 
     // Payment
     Route::resource('payments', PaymentController::class)->only(['index', 'store']);
 
     // Digital Downloads
     Route::get('/digital-downloads', [DigitalDownloadController::class, 'index'])->name('digital.downloads.index');
     Route::get('/downloads/{download}', [DigitalDownloadController::class, 'show'])->name('downloads.show');

});

// Newsletter Subscription
Route::post('/subscribe', [PageController::class, 'subscribe'])->name('subscribe');


// User Profile & Shopping Cart
Route::middleware(['auth', 'role:user|admin|manager|photographer'])->group(function () {
    Route::get('profile', [ProfileController::class, 'index'])->name('profile');

    //Route::resource('cart', CartController::class)->only(['index']);
    Route::post('cart/add/{photo}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('cart/remove/{photo}', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout and Payment
    Route::prefix('checkout')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('checkout');
        Route::post('/process', [CheckoutController::class, 'process'])->name('checkout.process');
    });

    Route::prefix('payment')->group(function () {
        Route::get('/success', [PaymentController::class, 'success'])->name('payment.success');
        Route::get('/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
    });

    // Payment Methods
    Route::resource('payment-methods', PaymentMethodController::class)->only(['index', 'create', 'store', 'destroy']);
});

// The notify route should not have the `auth` middleware as it is accessed by PayFast's servers
Route::middleware('throttle:60,1')->post('/payment/notify', [PaymentController::class, 'notify'])->name('payment.notify');


Route::group(['namespace' => 'App\Http\Controllers\Auth'], function()
{
    // -----------------------------login----------------------------------------//
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::post('/login', 'authenticate');
        Route::get('/logout', 'logout')->name('logout');
    });

    // ------------------------------ register ---------------------------------//
    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', 'register')->name('register');
        Route::post('/register', 'storeUser')->name('register');
    });

    // ----------------------------- forget password ----------------------------//
    Route::controller(ForgotPasswordController::class)->group(function () {
        Route::get('forget-password', 'getEmail')->name('forget-password');
        Route::post('forget-password', 'postEmail')->name('forget-password');
    });

    // ----------------------------- reset password -----------------------------//
    Route::controller(ResetPasswordController::class)->group(function () {
        Route::get('reset-password/{token}', 'getPassword');
        Route::post('reset-password', 'updatePassword');
    });
});

Route::group(['namespace' => 'App\Http\Controllers'], function()
{
    // Admin Routes
    Route::prefix('admin')->middleware(['auth', 'role:admin|manager|photographer'])->group(function() {
        
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

         // ----------------------- user management -------------------------//
        Route::controller(UserManagementController::class)->group(function () {
            Route::get('users/list', 'userList')->name('admin.users.index');
            Route::get('users/create', 'userAddNew')->name('admin.users.create'); /** add new users */
            Route::get('users/edit/{user_id}', 'userView'); /** add new users */
            Route::post('users/update', 'userUpdate')->name('users/update'); /** update record */
            Route::get('users/delete/{id}', 'userDelete')->name('users/delete'); /** delere record */
            Route::get('get-users-data', 'getUsersData')->name('get-users-data'); /** get all data users */
            Route::get('users/log', 'userList')->name('admin.users.log');
            
        });

        Route::resource('photos', PhotoController::class)->except(['show'])->names([
            'index' => 'admin.photos.index',
            'create' => 'admin.photos.create',
            'edit' => 'admin.photos.edit',
            'update' => 'admin.photos.update',
            'store' => 'admin.photos.store',
            'destroy' => 'admin.photos.destroy',
            
        ]);

        Route::get('photos/data', [PhotoController::class, 'getPhotosData'])->name('get-photos-data');
        Route::get('photos/import', [PhotoController::class,'importPhotos'])->name('admin.photos.import');


        Route::controller(UploadController::class)->group(function (){

            Route::post('store', 'storeImage')->name('uploads.image.store');
            Route::get('image/delete', 'deleteImage')->name('upload.image.delete');
        });
    
       /*  Route::resource('users', AdminController::class)->except(['show'])->names([
            'index' => 'admin.users.index',
            'create' => 'admin.users.create',
            'edit' => 'admin.users.edit',
            'destroy' => 'admin.users.destroy',
        ]); */
    
        Route::resource('orders', AdminController::class)->except(['show'])->names([
            'index' => 'admin.orders.index',
            'edit' => 'admin.orders.edit',
            'destroy' => 'admin.orders.destroy',
        ]);
    
        Route::resource('categories', CategoryController::class)->except(['show'])->names([
            'index' => 'admin.categories.index',
            'create' => 'admin.categories.create',
            'edit' => 'admin.categories.edit',
            'update' => 'admin.categories.update',
            'store' => 'admin.categories.store',
            'destroy' => 'admin.categories.destroy',
        ]);

        Route::get('categories/data', [CategoryController::class, 'getCategoryData'])->name('get-categories-data');

        Route::resource('events', EventController::class)->except(['show'])->names([
            'index' => 'admin.events.index',
            'create' => 'admin.events.create',
            'edit' => 'admin.events.edit',
            'update' => 'admin.events.update',
            'store' => 'admin.events.store',
            /* 'show' => 'admin.events.show', */
            'destroy' => 'admin.events.destroy',
        ]);

        Route::get('events/data', [EventController::class, 'getEventData'])->name('get-events-data');
        Route::get('events/{event}', [EventController::class, 'show'])->name('admin.events.show');


        Route::controller(SettingController::class)->group(function () {
            Route::get('/settings', 'index')->name('settings');
        });

        // Order Management
        Route::controller(OrderController::class)->group(function (){
            Route::get('orders', 'index')->name('admin.orders.index'); 
            Route::get('orders/create', 'create')->name('admin.orders.create');
        });

        Route::get('orders/data', [OrderController::class, 'getOrderData'])->name('get-orders-data');

        // Cart Management
        //Route::resource('cart', CartController::class);

        // Digital Download Management
        Route::resource('digital-downloads', DigitalDownloadController::class);

        /* Route::controller(RoleController::class)->group(function () {
            Route::get('/roles-permissions', 'index')->name('roles-permissions');
        }); */

        Route::resource('roles', RoleController::class);
    });

    // ----------------------------- main dashboard ------------------------------//
    Route::controller(HomeController::class)->group(function () {
        Route::get('/home', 'index')->name('home');
        Route::get('/profile', 'profile')->name('profile');
    });
});