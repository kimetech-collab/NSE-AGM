<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Middleware\EnsureAdminMfa;
use App\Http\Middleware\VerifyPaystackSignature;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Pricing page (placeholder — full implementation in Phase 4 Step 2)
Route::get('/pricing', function () {
    return view('pricing');
})->name('pricing');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/settings.php';

// Paystack webhook endpoint (sandbox)
Route::post('paystack/webhook', function (Request $request) {
    return app(\App\Services\PaymentService::class)->handleWebhook($request);
})->middleware(['throttle:paystack-webhook', VerifyPaystackSignature::class]);

// Registration routes
Route::get('register', [\App\Http\Controllers\RegistrationController::class, 'show'])->name('register');
Route::post('register', [\App\Http\Controllers\RegistrationController::class, 'register'])
    ->middleware('throttle:register')
    ->name('register.store');
Route::get('email/verify/{registrationId}', [\App\Http\Controllers\RegistrationController::class, 'showVerify'])->name('register.verify.show');
Route::post('email/verify', [\App\Http\Controllers\RegistrationController::class, 'verify'])
    ->middleware('throttle:verify-otp')
    ->name('register.verify');
Route::post('email/verify/resend', [\App\Http\Controllers\RegistrationController::class, 'resendOtp'])
    ->middleware('throttle:verify-otp-resend')
    ->name('register.verify.resend');
Route::get('ticket/{token}', [\App\Http\Controllers\RegistrationController::class, 'ticket'])->name('ticket.view');
Route::post('ticket/download-pdf', [\App\Http\Controllers\RegistrationController::class, 'downloadTicketPdf'])->name('ticket.download');

// Payment
Route::get('payment', [\App\Http\Controllers\PaymentController::class, 'show'])->name('payment.show');
Route::post('payment/initiate', [\App\Http\Controllers\PaymentController::class, 'initiate'])->name('payment.initiate');
Route::get('payment/callback', [\App\Http\Controllers\PaymentController::class, 'handleCallback'])->name('payment.callback');

// Admin routes (basic)
Route::prefix('admin')->name('admin.')->middleware(['auth','verified', EnsureAdminMfa::class])->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    // Registrations
    Route::get('registrations', [\App\Http\Controllers\Admin\RegistrationsController::class, 'index'])->name('registrations.index');
    Route::get('registrations/export', [\App\Http\Controllers\Admin\RegistrationsController::class, 'export'])->name('registrations.export');
    Route::get('registrations/{id}', [\App\Http\Controllers\Admin\RegistrationsController::class, 'show'])->name('registrations.show');
    Route::put('registrations/{id}', [\App\Http\Controllers\Admin\RegistrationsController::class, 'update'])->name('registrations.update');

    // Finance
    Route::get('finance', [\App\Http\Controllers\Admin\FinanceController::class, 'index'])->name('finance.index');
    Route::post('finance/refund/{id}', [\App\Http\Controllers\Admin\FinanceController::class, 'refund'])->name('finance.refund');

    // Accreditation / QR
    Route::get('accreditation', [\App\Http\Controllers\Admin\AccreditationController::class, 'index'])->name('accreditation.index');
    Route::post('accreditation/scan', [\App\Http\Controllers\Admin\AccreditationController::class, 'scan'])->name('accreditation.scan');
    Route::get('accreditation/offline-cache', [\App\Http\Controllers\Admin\AccreditationController::class, 'offlineCache'])->name('accreditation.offline');
    Route::post('accreditation/sync-cache', [\App\Http\Controllers\Admin\AccreditationController::class, 'syncCache'])->name('accreditation.sync');

    // Audit Logs
    Route::get('audit', [\App\Http\Controllers\Admin\AuditController::class, 'index'])->name('audit.index');
    Route::get('audit/export', [\App\Http\Controllers\Admin\AuditController::class, 'export'])->name('audit.export');
    Route::get('audit-trail/{entityType}/{entityId}', [\App\Http\Controllers\Admin\AuditController::class, 'trail'])->name('audit.trail');
    Route::get('audit/{auditLog}', [\App\Http\Controllers\Admin\AuditController::class, 'show'])->name('audit.show');

    // Pricing management
    Route::get('pricing', [\App\Http\Controllers\Admin\PricingController::class, 'index'])->name('pricing.index');
    Route::post('pricing/versions', [\App\Http\Controllers\Admin\PricingController::class, 'storeVersion'])->name('pricing.versions.store');
    Route::put('pricing/versions/{version}', [\App\Http\Controllers\Admin\PricingController::class, 'updateVersion'])->name('pricing.versions.update');
    Route::delete('pricing/versions/{version}', [\App\Http\Controllers\Admin\PricingController::class, 'deleteVersion'])->name('pricing.versions.delete');
    Route::post('pricing/items', [\App\Http\Controllers\Admin\PricingController::class, 'storeItem'])->name('pricing.items.store');
    Route::put('pricing/items/{item}', [\App\Http\Controllers\Admin\PricingController::class, 'updateItem'])->name('pricing.items.update');
    Route::delete('pricing/items/{item}', [\App\Http\Controllers\Admin\PricingController::class, 'deleteItem'])->name('pricing.items.delete');
});
