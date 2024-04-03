<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/phone-books', [\App\Http\Controllers\Api\PhoneBookController::class, 'index'])->name('phone.books');;
Route::get('/retrieve-phone-book/{id}', [\App\Http\Controllers\Api\PhoneBookController::class, 'retrieve'])->name('retrieve.phone.book');
Route::post('/store-phone-book', [\App\Http\Controllers\Api\PhoneBookController::class, 'store'])->name('store.phone.book');
Route::put('/update-phone-book/{id}', [\App\Http\Controllers\Api\PhoneBookController::class, 'update'])->name('update.phone.book');
Route::delete('/delete-phone-book/{id}', [\App\Http\Controllers\Api\PhoneBookController::class, 'delete'])->name('delete.phone.book');
