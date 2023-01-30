<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\registerController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\changePasswordController;
use App\Http\Controllers\RoomsController;
use App\Http\Controllers\BlogsController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\Withdrawal;
use App\Http\Controllers\PrivacyController;

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
Route::post('/meet',[RoomsController::class, 'index']);
Route::post('/search',[RoomsController::class, 'search']);
Route::post('/rooms/get',[RoomsController::class, 'all']);
Route::post('/creators/get/gene',[RoomsController::class, 'creators_get']);
Route::post('/creators/get/gene/dash',[RoomsController::class, 'creators_get_dash']);
Route::post('/creators/get/info',[RoomsController::class, 'creators_get_info']);
Route::post('/rooms/view',[RoomsController::class, 'view']);
Route::post('/rooms/more_info',[RoomsController::class, 'more_info']);
Route::post('/creators/get',[RoomsController::class, 'creators']);
Route::post('/creators/get/all',[RoomsController::class, 'creators_all']);
Route::post('/post/review',[RoomsController::class, 'save_review']);
Route::post('/schedule/sent',[RoomsController::class, 'save_schedule']);
Route::post('/schedule/get',[RoomsController::class, 'get_schedule']);
Route::post('/set/availabilty',[RoomsController::class, 'set_availability'])->middleware(['auth:sanctum']);
Route::post('/reset/availability',[RoomsController::class, 'reset_availability'])->middleware(['auth:sanctum']);
Route::post('/dashboard/all',[RoomsController::class, 'dash_board']);
Route::post('/get/reviews',[RoomsController::class, 'get_review']);
Route::post('/promotions',[RoomsController::class, 'promotion']);
Route::post('/get/token/eco',[RoomsController::class, 'get_token_eco']);
Route::post('/user_profile/get',[RoomsController::class, 'user_profile']);
Route::post('/withdraw/money/get',[Withdrawal::class, 'withdraw_money'])->middleware(['auth:sanctum']);
Route::post('/withdraw/money/save',[Withdrawal::class, 'withdraw_money_save'])->middleware(['auth:sanctum']);
Route::post('/note/add',[RoomsController::class, 'add_note'])->middleware(['auth:sanctum']);
Route::post('/note/update',[RoomsController::class, 'update_note'])->middleware(['auth:sanctum']);
Route::post('/message/save',[RoomsController::class, 'add_msg'])->middleware(['auth:sanctum']);
Route::post('/message/get',[RoomsController::class, 'get_msg'])->middleware(['auth:sanctum']);
Route::post('/message/delete',[RoomsController::class, 'delete_msg'])->middleware(['auth:sanctum']);
Route::post('/note/get',[RoomsController::class, 'get_note'])->middleware(['auth:sanctum']);
Route::post('/notifications/get',[RoomsController::class, 'get_notitifications']);
Route::post('/note/delete',[RoomsController::class, 'delete_note'])->middleware(['auth:sanctum']);
Route::post('/user/delete',[registerController::class, 'delete_user'])->middleware(['auth:sanctum']);
Route::post('/user/update',[registerController::class, 'update_user'])->middleware(['auth:sanctum']);
Route::post('/check/token',[RoomsController::class, 'check_token']);
Route::post('/blog/add',[BlogsController::class, 'add_blog'])->middleware(['auth:sanctum']);
Route::post('/blog/get',[BlogsController::class, 'all']);
Route::post('/blog/get/all',[BlogsController::class, 'all_cat']);
Route::post('/blog/get/all/latest',[BlogsController::class, 'all_blogs']);
Route::post('/blog/edit',[BlogsController::class, 'edit_blog'])->middleware(['auth:sanctum']);
Route::post('/blog/delete',[BlogsController::class, 'delete_blog'])->middleware(['auth:sanctum']);
Route::post('/getBrainTreeToken',[PaymentsController::class, 'getBrainTreeToken']);
Route::post('/buytoken/visa',[PaymentsController::class, 'makePayment']);
Route::post('/room/confirmed',[TokenController::class, 'updateToken']);
Route::post('/privacyPolicy/add',[PrivacyController::class, 'save_privacy']);
Route::post('/privacyPolicy/get',[PrivacyController::class, 'get_html_data']);

Route::post('/verify', 'registerController@verify')->name('verify');

Route::post('forgotpassword',[ForgotPasswordController::class, 'forgot_pass']);

