
<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

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
});


Route::controller(PageController::class)->group(function () {
    Route::get('/contact-us', 'contact');
    Route::get('/about-us', 'about');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


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
    Route::get('home',function()
    {
        return view('home');
    });
});



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
    // ----------------------- user management -------------------------//
    Route::group(['middleware' => ['auth', 'role:admin|manager|photographer']], function() {
        // for admin
        Route::controller(UserManagementController::class)->group(function () {
            Route::get('users/list/page', 'userList')->name('users/list/page');
            Route::get('users/add/new', 'userAddNew')->name('users/add/new'); /** add new users */
            Route::get('users/add/edit/{user_id}', 'userView'); /** add new users */
            Route::post('users/update', 'userUpdate')->name('users/update'); /** update record */
            Route::get('users/delete/{id}', 'userDelete')->name('users/delete'); /** delere record */
            Route::get('get-users-data', 'getUsersData')->name('get-users-data'); /** get all data users */
            
        });

        Route::controller(SettingController::class)->group(function () {
            Route::get('/settings', 'index')->name('settings');
        });

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