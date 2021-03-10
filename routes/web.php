<?php

use Illuminate\Support\Facades\Route;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\New_;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();

Route::group([], function () {
    //Auth
    Route::get('/login', 'App\Http\Controllers\Auth\LoginController@showLoginForm');
    Route::post('/login', 'App\Http\Controllers\Auth\LoginController@login')->name('login');
    Route::match(['POST', 'GET'], '/logout', 'App\Http\Controllers\Auth\LogoutController@index')->name('logout');
    Route::get('/register', 'App\Http\Controllers\Auth\RegisterController@showRegisterForm');
    Route::post('/register', 'App\Http\Controllers\Auth\RegisterController@register')->name('register');

    //App
    Route::group(['middleware' => ['auth']], function () {
        Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('user.home');
        //Woocommerce
        Route::get('/woocommerce/webhook', 'App\Http\Controllers\WoocommerceController@createWebhook')->name('woocommerce.createwebhook');
        Route::get('/woocommerce/return', 'App\Http\Controllers\WoocommerceController@wooReturn')->name('woocommerce.return');
        Route::get('/woocommerce/sync/{store_id}', 'App\Http\Controllers\WoocommerceController@wooSync')->name('woocommerce.sync');
        Route::get('/woocommerce/authorize', 'App\Http\Controllers\WoocommerceController@wooAuthorize');
        Route::resource('woocommerce', \App\Http\Controllers\WoocommerceController::class);
    });
});

Route::get('user/create', function () {
    $user = New User();
});

