<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\BlogCategoryController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PostController;
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

// Route for PostController API
Route::apiResource('posts', PostController::class);

// Route for CommentController API
Route::apiResource('comments', CommentController::class);

// Route for CommentController API
// Route::prefix('posts/{post}/comments')->group(function () {
//     Route::get('/', [CommentController::class, 'index']);
//     Route::post('/', [CommentController::class, 'store']);
//     Route::get('/{id}', [CommentController::class, 'show']);
//     Route::put('/{id}', [CommentController::class, 'update']);
//     Route::delete('/{id}', [CommentController::class, 'destroy']);
// });
