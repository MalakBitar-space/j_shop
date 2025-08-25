<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->middleware('auth:sanctum');

use App\Http\Controllers\UserController;
use App\Http\Controllers\ServiceCategoryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceProviderController;

Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/showProfile', [UserController::class, 'showProfile']);
    Route::post('/updateProfile', [UserController::class, 'updateProfile']);
    Route::delete('/deleteProfileImage', [UserController::class, 'deleteProfileImage']);

    Route::get('/index-provider', [ServiceProviderController::class, 'index']);
    Route::get('/show-provider/{id}', [ServiceProviderController::class, 'show']);
    Route::post('/create-provider', [ServiceProviderController::class, 'store']);
    Route::put('/update-provider/{id}', [ServiceProviderController::class, 'update']);
    Route::delete('/delete-providers/{id}', [ServiceProviderController::class, 'destroy']);
    Route::get('/providers-by-service/{service_id}', [ServiceProviderController::class, 'getByService']);
});
Route::get('index-category', [ServiceCategoryController::class, 'index']);
Route::post('create-category', [ServiceCategoryController::class, 'store']);
Route::get('show-category/{id}', [ServiceCategoryController::class, 'show']);
Route::delete('delete-category/{id}', [ServiceCategoryController::class, 'destroy']);


Route::get('index-service', [ServiceController::class, 'index']);
Route::post('create-service', [ServiceController::class, 'store']);
Route::get('show-service/{id}', [ServiceController::class, 'show']);
Route::post('update-service/{id}', [ServiceController::class, 'update']);
Route::delete('delete-service/{id}', [ServiceController::class, 'destroy']);
Route::get('services-by-category/{category_id}', [ServiceController::class, 'getByCategory']);



    Route::get('/index-provider', [ServiceProviderController::class, 'index']);
    Route::get('/show-provider/{id}', [ServiceProviderController::class, 'show']);
    Route::post('/create-provider', [ServiceProviderController::class, 'store'])->middleware('auth:sanctum');
    Route::post('/update-provider/{id}', [ServiceProviderController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/delete-providers/{id}', [ServiceProviderController::class, 'destroy'])->middleware('auth:sanctum');
    Route::get('/providers-by-service/{service_id}', [ServiceProviderController::class, 'getByService']);
