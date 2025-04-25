<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MedController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FavoriteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/Register',[AuthController::class,'Register']);
Route::post('/login',[AuthController::class,'login']);

Route::group(["middleware" => ["auth:sanctum"]], function(){
    //==========Logout=============
    Route::post('/logout',[AuthController::class,'logout']);
    //==========medicine=============
    Route::get('/Show_all_meds',[MedController::class,'show_all_meds']);
    Route::get('/Show_category/{category_id}',[MedController::class,'show_categorysMeds']);
    Route::get('/medicine_info/{id}',[MedController::class,'show_med']);
    Route::post('/add_medicine',[MedController::class,'store_med']);
    Route::delete('/delete_medicine/{id}',[MedController::class,'destroy_med']);
    Route::post('/create_order',[OrderController::class,'create_order']);
    Route::get('/Show_all_orders',[OrderController::class,'show_all_orders']);
    Route::post('/Show_all_orders_with_update',[OrderController::class,'show_all_orders_with_update']);
    Route::get('/weekly_report',[OrderController::class,'weekly_report']);
    Route::get('/monthly_report',[OrderController::class,'monthly_report']);

    //show ahbabk
    Route::get('/favorite',[FavoriteController::class,'my_favorites']);
    //Love Button=>if not fav make it |if fav make it not
    Route::get('/love_button/{medicine_id}',[FavoriteController::class,'change_to']);

    Route::post('/notification}',[OrderController::class,'sendOrderNotification']);



});
