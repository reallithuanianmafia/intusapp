<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShortUrlController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/create-short-url', [ShortUrlController::class, 'createShortUrl']);
Route::get('/{shortCode}', [ShortUrlController::class, 'redirectToOriginalUrl']);
Route::post('/check-safe-browsing', [ShortUrlController::class, 'checkSafeBrowsing']);
