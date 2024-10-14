
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
use App\Http\Controllers\CaptchaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\EmailPreviewController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\DownloadController;








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
//Auth::routes();
//require __DIR__.'/auth.php';

Route::get('/phpmyadmin', function () {
    abort(403);
});

// Subscription Routes
Route::get('/subscribe/{encoded}', [SubscriptionController::class, 'subscribe'])->name('subscribe');
Route::get('/unsubscribe/{encoded}', [SubscriptionController::class, 'unsubscribe'])->name('unsubscribe');

//build email
Route::get('/email-preview', [EmailPreviewController::class, 'purchaseEmailPreview']);



Route::get('/download-page/{photo_id}/{file}', [DownloadController::class, 'downloadFile'])->name('downloadFile');



Route::get('/reload-captcha', [CaptchaController::class, 'reloadCaptcha'])->name('reload-captcha');

Route::controller(PageController::class)->group(function () {
    Route::get('/', 'index')->name('welcome');
    Route::get('/contact-us', 'contact');
    Route::get('/about-us', 'about');
    Route::get('/terms-and-conditions', 'terms');
    Route::get('/privacy-policy', 'privacy');
    Route::get('/refund-policy', 'refundPolicy');
});

Route::get('/individual-photos/{photo}', [PhotoController::class, 'individualPhoto'])->name('individual.photos');

// Category Routes
Route::resource('categories', CategoryController::class)->only(['index', 'show']);
Route::get('categories/{category}/photos', [CategoryController::class, 'photos'])->name('categories.photos');

// Event Routes
//Route::resource('events', EventController::class)->only(['index', 'show']);
Route::controller(EventController::class)->group(function (){
    Route::get('events/all', 'allEvents')->name('events.all');
    Route::get('events/{event}', 'individualEvents')->name('events.individual');
    Route::get('events/{event}/photos',  'showEventPhotos')->name('events.photos');
    Route::get('events/search', 'search')->name('events.search');
    Route::get('events/{event}/categories',  'categories')->name('events.categories');
    Route::post('events/{event}/import-photos', 'importPhotosSpreadSheet')->name('admin.events.importPhotos');

});

Route::get('uploads/photos/{filename}', [PhotoController::class, 'getImage'])->name('image.view');



Route::get('admin/photos/import/bulk/{event_id}', [PhotoController::class,'checkChunk'])->name('admin.photos.bulk.store');

// Photo Routes
Route::resource('photos', PhotoController::class)->only(['index', 'show']);
Route::get('photos/download/{photo}', [PhotoController::class, 'download'])->name('photos.download');
Route::get('photos/search', [PhotoController::class, 'search'])->name('photos.search');
Route::get('photos/race/{race_number}', [PhotoController::class, 'photosByRace'])->name('admin.photos.race');
Route::get('photos/filter/all', [PhotoController::class, 'allPhotos'])->name('photos.all');

Route::get('items/search', [PhotoController::class, 'itemsSearch'])->name('items.search');



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
    Route::delete('/cart/remove/{cart}', [CartController::class, 'removeFromCart'])->name('cart.remove');

    // Authentication routes
    Auth::routes();
});

  // Admin Routes
Route::prefix('customer')->middleware(['auth', 'role:admin|manager|user'])->group(function() {
        
    Route::controller(CustomerController::class)->group(function () {

        Route::get('show/{order}', 'show')->name('customer.orders.show');
        Route::get('show/{download}', 'show')->name('customer.downloads.show');


        Route::get('/dashboard', 'index')->name('customer.dashboard');
        Route::get('/orders', 'orderHistory')->name('customer.orders');
        Route::get('/invoices', 'invoices')->name('customer.invoices');
        Route::get('/downloads', 'digitalDownloads')->name('customer.downloads');
        Route::get('/account/settings', 'settings')->name('customer.account.settings');
        Route::put('/account/settings', 'accountUpdate')->name('customer.account.update');

         // Data fetching routes for DataTables (Server-Side Processing)
        Route::get('/get-order-history-data', 'getOrderHistoryData')->name('get-order-history-data');
        Route::get('/get-invoices-data', 'getInvoicesData')->name('get-invoices-data');
        Route::get('/get-digital-downloads-data', 'getDigitalDownloadsData')->name('get-digital-downloads-data');
    });


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
//Route::post('/subscribe', [PageController::class, 'subscribe'])->name('subscribe');


// User Profile & Shopping Cart
Route::middleware(['auth', 'role:user|admin|manager|photographer', 'sync.guest.cart'])->group(function () {
    Route::get('profile', [ProfileController::class, 'index'])->name('profile');

    //Route::resource('cart', CartController::class)->only(['index']);
    //Route::post('cart/add/{photo}', [CartController::class, 'add'])->name('cart.add');
    //Route::delete('cart/remove/{photo}', [CartController::class, 'remove'])->name('cart.remove');

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
        Route::post('/login', 'authenticate')->middleware('sync.guest.cart');
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
            Route::get('users/edit/{user_id}', 'userView')->name('admin.users.edit'); /** add new users */
            Route::post('users/update', 'userUpdate')->name('users/update'); /** update record */
            Route::get('users/delete/{user_id}', 'userDelete')->name('admin.users.delete'); /** delere record */
            Route::get('get-users-data', 'getUsersData')->name('get-users-data'); /** get all data users */
            Route::get('users/log', 'userList')->name('admin.users.log');
            Route::delete('users/delete', 'deleteSelected')->name('delete-users-data');
            
        });

        Route::prefix('photos')->controller(PhotoController::class)->group(function () {
            Route::get('index', 'index')->name('admin.photos.index');
            Route::get('create', 'create')->name('admin.photos.create');
            Route::get('edit/{photo}', 'edit')->name('admin.photos.edit');
            Route::put('update/{photo}', 'update')->name('admin.photos.update');
            Route::post('store', 'store')->name('admin.photos.store');
            Route::delete('destroy/{photo}', 'destroy')->name('admin.photos.destroy');
            Route::get('show/{photo}', 'show')->name('admin.photos.show');
            Route::delete('/photos/delete', 'deleteSelected')->name('delete-photos-data');
        });

        Route::get('photos/data', [PhotoController::class, 'getPhotosData'])->name('get-photos-data');
        Route::get('photos/import/{event_id}', [PhotoController::class,'importBulkPhotos'])->name('admin.photos.bulk.import');
        //Route::get('photos/import/bulk/{event_id}', [PhotoController::class,'checkChunk'])->name('admin.photos.bulk.store');
        Route::post('photos/import/bulk/{event_id}', [PhotoController::class,'importBulkPhotosStore'])->name('admin.photos.bulk.store');
        


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
    /* 
        Route::resource('orders', AdminController::class)->except(['show'])->names([
            'index' => 'admin.orders.index',
            'edit' => 'admin.orders.edit',
            'destroy' => 'admin.orders.destroy',
        ]); */
/* 
        Route::prefix('orders')->controller(AdminController::class)->group(function () {
            Route::get('index', 'index')->name('admin.orders.index');
            Route::get('create', 'create')->name('admin.orders.create');
            Route::get('edit/{order}', 'edit')->name('admin.orders.edit');
            Route::put('update/{order}', 'update')->name('admin.orders.update');
            Route::post('store', 'store')->name('admin.orders.store');
            Route::delete('destroy/{order}', 'destroy')->name('admin.orders.destroy');
            Route::get('show/{order}', 'show')->name('admin.orders.show');
            Route::delete('delete', 'deleteSelected')->name('delete-orders-data');
            Route::get('data', 'getOrderData')->name('get-orders-data');
        }); */

        Route::prefix('categories')->controller(CategoryController::class)->group(function () {
            Route::get('index', 'index')->name('admin.categories.index');
            Route::get('create', 'create')->name('admin.categories.create');
            Route::get('edit/{category}', 'edit')->name('admin.categories.edit');
            Route::put('update/{category}', 'update')->name('admin.categories.update');
            Route::post('store', 'store')->name('admin.categories.store');
            Route::delete('destroy/{category}', 'destroy')->name('admin.categories.destroy');
            Route::get('show/{category}', 'show')->name('admin.categories.show');
            Route::delete('delete', 'deleteSelected')->name('delete-categories-data');
            Route::get('data', 'getCategoryData')->name('get-categories-data');
        });
    
        Route::prefix('events')->controller(EventController::class)->group(function () {
            Route::get('index', 'index')->name('admin.events.index');
            Route::get('create', 'create')->name('admin.events.create');
            Route::get('edit/{event}', 'edit')->name('admin.events.edit');
            Route::put('update/{event}', 'update')->name('admin.events.update');
            Route::post('store', 'store')->name('admin.events.store');
            Route::delete('destroy/{event}', 'destroy')->name('admin.events.destroy');
            Route::get('show/{event}', 'show')->name('admin.events.show');
            Route::delete('/photos/delete', 'deleteSelected')->name('delete-events-data');
        });

        Route::get('events/data', [EventController::class, 'getEventData'])->name('get-events-data');
              


        Route::controller(SettingController::class)->group(function () {
            Route::get('/settings', 'index')->name('settings');
        });

        // Order Management
        Route::prefix('orders')->controller(OrderController::class)->group(function (){
            Route::get('/', 'index')->name('admin.orders.index'); 
            Route::get('show/{order}', 'show')->name('admin.orders.show');
            Route::get('create', 'create')->name('admin.orders.create');
            Route::get('edit/{order}', 'edit')->name('admin.orders.edit');
            Route::delete('destroy/{order}', 'destroy')->name('admin.orders.destroy');
            Route::delete('delete', 'deleteSelected')->name('delete-orders-data');
            Route::get('data', 'getOrderData')->name('get-orders-data');
        });

        //Route::get('orders/data', [OrderController::class, 'getOrderData'])->name('get-orders-data');

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