<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->prefix('admin')->namespace('Backend')->group(function () {
    Route::get('/', 'DashboardController@index')->name('admin.index');

    Route::get('/setting', 'SettingController@index')->name('admin.setting.index');
    Route::post('/setting/store','SettingController@store')->name('admin.setting.store');

    Route::post('/setting/setwebhook','SettingController@setwebhook')->name('admin.setting.setwebhook');
    Route::post('/setting/getwebhookinfo','SettingController@getwebhookinfo')->name('admin.setting.getwebhookinfo');
});

Route::any(\Telegram\Bot\Laravel\Facades\Telegram::getAccessToken(), function (){


    app(\App\Http\Controllers\Backend\TelegramController::class)->webhook();

    return 'ok';
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
