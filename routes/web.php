<?php

use App\Events\DemoEvent;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Auth;
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
    // broadcast(new DemoEvent('someData'));
    return view('welcome');
});
Route::get('/chat','ChatController@index');
Route::get('/contact','ChatController@showContact');
Route::post('contact','ChatController@joinUser');

Route::get('messages','ChatController@fetchMessages');
Route::post('messages','ChatController@sendMessages');
Route::get('users','UserController@getAllUsers');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
