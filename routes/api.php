<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\BlogCategoryController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::group(['prefix' => 'auth'], function ($router) {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

Route::middleware('auth:api')->group(function () {
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('me', [AuthController::class, 'me']);
    Route::post('index', [AuthController::class, 'index']);
});


// Route::post('product/create', [ProductController::class, 'create']);
// Route::post('product/store', [ProductController::class, 'store']);



// Route for ProductController API
Route::apiResource('products', ProductController::class);

// Route for BlogCategoryController API
Route::apiResource('blog-categories', BlogCategoryController::class);

// Route for BlogController API
Route::apiResource('blogs', BlogController::class);
