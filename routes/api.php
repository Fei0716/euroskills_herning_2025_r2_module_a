<?php

use App\Http\Controllers\PlanController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::prefix('v1')->group(function(){
    Route::apiResource('users' ,  UserController::class);
    Route::get('restaurants' ,  [RestaurantController::class , 'index']);
    Route::apiResource('plans' ,  PlanController::class)->only('index' , 'update');
    Route::get('roles' , [RoleController::class , 'index']);
    Route::get('reviews' , [ReviewController::class , 'index']);
    Route::delete('reviews/{id}' , [ReviewController::class , 'destroy']);
    Route::post('registration' , [RegistrationController::class, 'store']);
    Route::post('reset-db', [RegistrationController::class , 'resetDB']);
});
