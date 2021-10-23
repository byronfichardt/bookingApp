<?php

use App\V1\Application\Controllers\BookingController;
use App\V1\Application\Models\Detail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $details = Detail::first();
    $important = explode(';', $details->important);
    $about = $details->about;

    return view('app', compact(['important', 'about']));
});

Route::get('cancel', [BookingController::class, 'cancel']);
Route::get('bookings/{id}/approve', [BookingController::class, 'approve']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';
