<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\registerController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\changePasswordController;
use App\Http\Controllers\LawfirmController;
use App\Http\Controllers\BlogsController;
use App\Http\Controllers\PaymentsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!

*/

Route::post('/register',[registerController::class, 'register']);
Route::post('/login',[loginController::class, 'login']);
Route::post('/changePassword/user',[changePasswordController::class, 'changePassword']);
Route::post('/getfunds',[loginController::class, 'funds']);
Route::post('/money/rate/add',[loginController::class, 'rates']);
Route::post('/money/rate/get',[loginController::class, 'get_rates']);
Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('/logout',[loginController::class, 'logout']);
});

Route::post('/meet',[LawfirmController::class, 'index']);
Route::post('/search',[LawfirmController::class, 'search']);
Route::post('/lawfirms/get',[LawfirmController::class, 'all']);
Route::post('/lawyers/get/gene',[LawfirmController::class, 'lawyers_get']);
Route::post('/lawyers/get/gene/dash',[LawfirmController::class, 'lawyers_get_dash']);
Route::post('/lawyers/get/info',[LawfirmController::class, 'lawyers_get_info']);
Route::post('/lawfirms/view',[LawfirmController::class, 'view']);
Route::post('/lawfirms/more_info',[LawfirmController::class, 'more_info']);
Route::post('/lawyers/get',[LawfirmController::class, 'lawyers']);
Route::post('/lawyers/get/all',[LawfirmController::class, 'lawyers_all']);
Route::post('/post/review',[LawfirmController::class, 'save_review']);
Route::post('/schedule/sent',[LawfirmController::class, 'save_schedule']);
Route::post('/schedule/get',[LawfirmController::class, 'get_schedule']);
Route::post('/set/availability',[LawfirmController::class, 'set_availability'])->middleware(['auth:sanctum']);
Route::post('/reset/availability',[LawfirmController::class, 'reset_availability'])->middleware(['auth:sanctum']);
Route::post('/dashboard/all',[LawfirmController::class, 'dash_board']);
Route::post('/get/reviews',[LawfirmController::class, 'get_review']);
Route::post('/get/token/eco',[LawfirmController::class, 'get_token_eco']);
Route::post('/user_profile/get',[LawfirmController::class, 'user_profile']);
Route::post('/withdraw/money',[LawfirmController::class, 'withdraw_money'])->middleware(['auth:sanctum']);
Route::post('/note/add',[LawfirmController::class, 'add_note'])->middleware(['auth:sanctum']);
Route::post('/note/update',[LawfirmController::class, 'update_note'])->middleware(['auth:sanctum']);
Route::post('/message/save',[LawfirmController::class, 'add_msg'])->middleware(['auth:sanctum']);
Route::post('/message/get',[LawfirmController::class, 'get_msg'])->middleware(['auth:sanctum']);
Route::post('/message/delete',[LawfirmController::class, 'delete_msg'])->middleware(['auth:sanctum']);
Route::post('/note/get',[LawfirmController::class, 'get_note'])->middleware(['auth:sanctum']);
Route::post('/notifications/get',[LawfirmController::class, 'get_notitifications']);
Route::post('/note/delete',[LawfirmController::class, 'delete_note'])->middleware(['auth:sanctum']);
Route::post('/user/delete',[registerController::class, 'delete_user'])->middleware(['auth:sanctum']);
Route::post('/user/update',[registerController::class, 'update_user'])->middleware(['auth:sanctum']);
Route::post('/check/token',[LawfirmController::class, 'check_token']);
Route::post('/blog/add',[BlogsController::class, 'add_blog'])->middleware(['auth:sanctum']);
Route::post('/blog/get',[BlogsController::class, 'all']);
Route::post('/blog/get/all',[BlogsController::class, 'all_cat']);
Route::post('/blog/get/all/latest',[BlogsController::class, 'all_blogs']);
Route::post('/blog/edit',[BlogsController::class, 'edit_blog'])->middleware(['auth:sanctum']);
Route::post('/blog/delete',[BlogsController::class, 'delete_blog'])->middleware(['auth:sanctum']);
Route::post('/getBrainTreeToken',[PaymentsController::class, 'getBrainTreeToken']);
Route::post('/buytoken/visa',[PaymentsController::class, 'makePayment']);

Route::post('/verify', 'registerController@verify')->name('verify');

Route::post('forgotpassword','forgotPasswordController@checkUser');

