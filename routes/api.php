<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::prefix('auth')->group(function (){
    Route::post('login',[\App\Http\Controllers\AuthController::class,'login'])->name('login');
    Route::post('register',[\App\Http\Controllers\AuthController::class,'register'])->name('register');
});
Route::prefix('admin')->middleware(['auth:sanctum',\App\Http\Middleware\CheckAdminRole::class])->group(function (){
    Route::prefix('books')->group(
        function (){
            Route::get('/',[\App\Http\Controllers\Book\BookController::class,'index']);
            Route::post('/',[\App\Http\Controllers\Book\BookController::class,'store']);
            Route::put('/{book_id}',[\App\Http\Controllers\Book\BookController::class,'update']);
            Route::delete('/{book_id}',[\App\Http\Controllers\Book\BookController::class,'destroy']);
        }
    );
    Route::prefix('writers')->group(
        function (){
            Route::get('/',[\App\Http\Controllers\Writer\WriterController::class,'index']);
            Route::post('/',[\App\Http\Controllers\Writer\WriterController::class,'store']);
            Route::put('/{writer_id}',[\App\Http\Controllers\Writer\WriterController::class,'update']);
            Route::delete('/{book_id}',[\App\Http\Controllers\Writer\WriterController::class,'destroy']);
        }
    );
    Route::prefix('categories')->group(
        function (){
            Route::get('/',[\App\Http\Controllers\Category\CategoryController::class,'index']);
            Route::post('/',[\App\Http\Controllers\Category\CategoryController::class,'store']);
            Route::put('/{category_id}',[\App\Http\Controllers\Category\CategoryController::class,'update']);
            Route::delete('/{category_id}',[\App\Http\Controllers\Category\CategoryController::class,'destroy']);
        }
    );
});
Route::prefix('reserv')->middleware('auth:sanctum')->group(function (){
    Route::post('/',[\App\Http\Controllers\Reserv\ReservController::class,'reserv']);
    Route::get('/',[\App\Http\Controllers\Reserv\ReservController::class,'index']);
    Route::get('search',[\App\Http\Controllers\Reserv\ReservController::class,'search']);
});


