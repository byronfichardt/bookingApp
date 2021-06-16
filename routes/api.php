<?php

use App\Application\Controllers\BookingController;
use App\Application\Controllers\ProductController;
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

Route::get('bookings', [BookingController::class, 'index']);
Route::get('bookings/pending', [BookingController::class, 'pending']);
Route::get('bookings/{id}/approve', [BookingController::class, 'approve']);
Route::get('bookings/{id}/cancel', [BookingController::class, 'remove']);
Route::post('bookings', [BookingController::class, 'store']);
Route::get('bookings/{date}', [BookingController::class, 'fetch']);

Route::get('products', [ProductController::class, 'index']);
Route::post('products', [ProductController::class, 'store']);
Route::delete('products/{id}', [ProductController::class, 'delete']);
Route::patch('products/{id}', [ProductController::class, 'edit']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
