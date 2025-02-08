<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LibrarianAuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;

// User Authentication
Route::get('/', function () {
    return response()->json(['message' => 'Welcome to the Library API'], 200);
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');

// Librarian Authentication
Route::post('/librarian/login', [LibrarianAuthController::class, 'login']);
Route::post('/librarian/logout', [LibrarianAuthController::class, 'logout'])->middleware('auth:librarian_api');

// User Routes
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/books', [UserController::class, 'availableBooks']);
    Route::post('/books/{book}/borrow', [UserController::class, 'borrowBook']);
    Route::post('/books/{book}/return', [UserController::class, 'returnBook']);
});

// Librarian Routes
Route::group(['middleware' => 'auth:librarian_api'], function () {
    Route::get('/books', [BookController::class, 'index']);
    Route::post('/books', [BookController::class, 'store']);
    Route::get('/books/{book}', [BookController::class, 'show']);
    Route::put('/books/{book}', [BookController::class, 'update']);
    Route::delete('/books/{book}', [BookController::class, 'destroy']);
});