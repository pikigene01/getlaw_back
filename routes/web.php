<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeneController;
use App\Http\Controllers\RobController;
use App\Http\Controllers\MailController;


Route::get('/gene', [RobController::class, 'index']);

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
Route::get('*', function () {
    return view('welcome');
});
Route::get('/mail',[MailController::class, 'html_mail']);


// Route::get('rob', 'RobController@index')->name('rob');
