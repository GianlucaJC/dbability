<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DemoDownloadController;
use App\Http\Controllers\DemoPdfController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\MyResourcesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StorefrontController;
use App\Http\Controllers\StripeWebhookController;
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

Route::get('/', [StorefrontController::class, 'index'])->name('home');
Route::get('/pdf-di-prova', [DemoPdfController::class, 'show'])->name('demo.pdf');
Route::get('/demo-download/{product:slug}', [DemoDownloadController::class, 'show'])->name('demo.download');
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])
    ->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
    ->name('stripe.webhook');

Route::get('/dashboard', [MyResourcesController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/downloads/{product:slug}', [DownloadController::class, 'show'])->name('downloads.show');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
