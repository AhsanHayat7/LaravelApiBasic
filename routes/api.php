<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/test', function(){
    return response()->json("Hello");
});

// Route::get('/posts', [PostController::class, 'index']);
// Route::post('/posts', [PostController::class, 'store']);
// Route::get('/posts/{post}',[PostController::class, 'show']);
// Route::put('/posts/{post}/',[PostController::class,  'update']);
// Route::delete('/posts/{post}', [PostController::class, 'destroy']);
Route::apiResource('posts',PostController::class);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::controller(AuthController::class)->group(function(){
    Route::post('login','login');
    Route::post('register', 'register');
});
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('posts',PostController::class);
    Route::post('/logout', [AuthController::class, 'logout']);
});
