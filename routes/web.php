<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome_mvp');
})->name('home'); 

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/settings.php';

// Paystack webhook endpoint (sandbox)
Route::post('paystack/webhook', function (Request $request) {
    return app(\App\Services\PaymentService::class)->handleWebhook($request);
});

// Registration routes
Route::get('register', [\App\Http\Controllers\RegistrationController::class, 'show'])->name('register.show');
Route::post('register', [\App\Http\Controllers\RegistrationController::class, 'register'])->name('register.store');
Route::get('email/verify/{registrationId}', [\App\Http\Controllers\RegistrationController::class, 'showVerify'])->name('register.verify.show');
Route::post('email/verify', [\App\Http\Controllers\RegistrationController::class, 'verify'])->name('register.verify');
Route::get('ticket/{token}', [\App\Http\Controllers\RegistrationController::class, 'ticket'])->name('ticket.view');

// Payment
Route::get('payment', [\App\Http\Controllers\PaymentController::class, 'show'])->name('payment.show');
Route::post('payment/initiate', [\App\Http\Controllers\PaymentController::class, 'initiate'])->name('payment.initiate');
Route::get('payment/callback', [\App\Http\Controllers\PaymentController::class, 'handleCallback'])->name('payment.callback');

// Admin routes (basic)
Route::prefix('admin')->name('admin.')->middleware(['auth','verified'])->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    // Registrations
    Route::get('registrations', [\App\Http\Controllers\Admin\RegistrationsController::class, 'index'])->name('registrations.index');
    Route::get('registrations/export', [\App\Http\Controllers\Admin\RegistrationsController::class, 'export'])->name('registrations.export');
    Route::get('registrations/{id}', [\App\Http\Controllers\Admin\RegistrationsController::class, 'show'])->name('registrations.show');

    // Finance
    Route::get('finance', [\App\Http\Controllers\Admin\FinanceController::class, 'index'])->name('finance.index');
    Route::post('finance/refund/{id}', [\App\Http\Controllers\Admin\FinanceController::class, 'refund'])->name('finance.refund');
});
