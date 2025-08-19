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

Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/showProfile', [UserController::class, 'showProfile']);
    Route::post('/updateProfile', [UserController::class, 'updateProfile']);
    Route::delete('/deleteProfileImage', [UserController::class, 'deleteProfileImage']);

});
Route::get('index-category', [ServiceCategoryController::class, 'index']);
Route::post('create-category', [ServiceCategoryController::class, 'store']);
Route::get('show-category/{id}', [ServiceCategoryController::class, 'show']);
Route::delete('delete-category/{id}', [ServiceCategoryController::class, 'destroy']);

