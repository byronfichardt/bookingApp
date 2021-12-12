<?php

use App\V1\Application\Controllers\BlockedDatesController;
use App\V1\Application\Controllers\DatesController;
use App\V1\Application\Controllers\BookingController;
use App\V1\Application\Controllers\DetailsController;
use App\V1\Application\Controllers\ProductController;
use App\V1\Application\Controllers\ProductPriceController;
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

Route::get('details', [DetailsController::class, 'index']);
Route::post('details', [DetailsController::class, 'store']);
Route::get('bookings', [BookingController::class, 'index']);
Route::post('blocked', [BlockedDatesController::class, 'store']);
Route::delete('blocked/{id}', [BlockedDatesController::class, 'delete']);
Route::get('blocked', [BlockedDatesController::class, 'index']);
Route::get('bookings/pending', [BookingController::class, 'pending']);
Route::get('bookings/availableTimes', [DatesController::class, 'getAvailableTimes']);
Route::get('bookings/nextAvailable', [DatesController::class, 'getNextAvailable']);
Route::patch('bookings/{id}', [BookingController::class, 'edit']);
Route::get('bookings/{id}/approve', [BookingController::class, 'approve']);
Route::get('bookings/{id}/cancel', [BookingController::class, 'remove']);
Route::post('bookings', [BookingController::class, 'store']);
Route::post('bookings/file-upload', [BookingController::class, 'upload']);

Route::get('products', [ProductController::class, 'index']);
Route::get('product-prices', [ProductPriceController::class, 'index']);
Route::post('product-prices', [ProductPriceController::class, 'store']);
Route::post('products', [ProductController::class, 'store']);
Route::delete('products/{id}', [ProductController::class, 'delete']);
Route::patch('products/{id}', [ProductController::class, 'edit']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
