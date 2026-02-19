<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\EnsureAdminMfa;
use App\Http\Middleware\VerifyPaystackSignature;
use App\Models\Sponsor;
use Illuminate\Support\Facades\Schema;

Route::get('/', function () {
    $sponsors = collect();
    if (Schema::hasTable('sponsors')) {
        $sponsors = Sponsor::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->limit(7)
            ->get();
    }

    return view('welcome', ['sponsors' => $sponsors]);
})->name('home');

// Auth aliases for discoverability
Route::redirect('/signin', '/login');
Route::redirect('/auth/login', '/login');

// Pricing page (placeholder — full implementation in Phase 4 Step 2)
Route::get('/pricing', function () {
    return view('pricing');
})->name('pricing');

// About page
Route::get('/about', function () {
    return view('about');
})->name('about');

// Programme page
Route::get('/programme', function () {
    return view('programme');
})->name('programme');

// Venue page
Route::get('/venue', function () {
    return view('venue');
})->name('venue');

// FAQs page
Route::get('/faqs', function () {
    return view('faqs');
})->name('faqs');

// Contact page
Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Terms & privacy page
Route::get('/terms', function () {
    return view('terms');
})->name('terms');

// Sponsors page
Route::get('/sponsors', function () {
    $sponsors = collect();
    if (Schema::hasTable('sponsors')) {
        $sponsors = Sponsor::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    return view('sponsors', ['sponsors' => $sponsors]);
})->name('sponsors');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/settings.php';

// Convenience logout endpoint for direct URL hits.
Route::get('logout', function (Request $request) {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('home');
})->middleware('auth');

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

// Virtual attendance stream
Route::get('stream', [\App\Http\Controllers\StreamController::class, 'show'])->name('stream.show');
Route::get('stream/progress', [\App\Http\Controllers\StreamController::class, 'showProgress'])->name('stream.progress.view');
Route::post('stream/start', [\App\Http\Controllers\StreamController::class, 'start'])->name('stream.start');
Route::post('stream/heartbeat', [\App\Http\Controllers\StreamController::class, 'heartbeat'])->name('stream.heartbeat');
Route::post('stream/end', [\App\Http\Controllers\StreamController::class, 'end'])->name('stream.end');
Route::post('stream/progress', [\App\Http\Controllers\StreamController::class, 'progress'])->name('stream.progress');

// Registration status (participant view)
Route::get('registration-status', [\App\Http\Controllers\RegistrationController::class, 'status'])
    ->middleware(['auth', 'verified'])
    ->name('registration.status');

// QR Scanner
Route::get('qr/scanner', [\App\Http\Controllers\QRScannerController::class, 'show'])->name('qr.scanner');
Route::post('qr/process', [\App\Http\Controllers\QRScannerController::class, 'process'])->name('qr.process');

// Certificates
Route::get('certificate', [\App\Http\Controllers\CertificateController::class, 'show'])->name('certificate.show');
Route::post('certificate/download', [\App\Http\Controllers\CertificateController::class, 'download'])->name('certificate.download');
Route::get('verify', [\App\Http\Controllers\CertificateController::class, 'verifyLookup'])->name('certificate.verify.lookup');
Route::get('verify/{certificateId}', [\App\Http\Controllers\CertificateController::class, 'verify'])
    ->middleware('throttle:certificate-verify')
    ->name('certificate.verify');

// Admin routes (basic)
Route::prefix('admin')->name('admin.')->middleware(['auth','verified', EnsureAdminMfa::class, 'role:super_admin,finance_admin,registration_admin,accreditation_officer,support_agent'])->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
        ->middleware('role:super_admin,finance_admin,registration_admin,accreditation_officer,support_agent')
        ->name('dashboard');

    // Registrations
    Route::get('registrations', [\App\Http\Controllers\Admin\RegistrationsController::class, 'index'])
        ->middleware('role:super_admin,registration_admin,support_agent')
        ->name('registrations.index');
    Route::get('registrations/export', [\App\Http\Controllers\Admin\RegistrationsController::class, 'export'])
        ->middleware('role:super_admin,registration_admin')
        ->name('registrations.export');
    Route::get('registrations/{id}', [\App\Http\Controllers\Admin\RegistrationsController::class, 'show'])
        ->middleware('role:super_admin,registration_admin,support_agent')
        ->name('registrations.show');
    Route::put('registrations/{id}', [\App\Http\Controllers\Admin\RegistrationsController::class, 'update'])
        ->middleware('role:super_admin,registration_admin')
        ->name('registrations.update');

    // Finance
    Route::get('finance', [\App\Http\Controllers\Admin\FinanceController::class, 'index'])
        ->middleware('role:super_admin,finance_admin')
        ->name('finance.index');
    Route::post('finance/refund/{id}', [\App\Http\Controllers\Admin\FinanceController::class, 'refund'])
        ->middleware('role:super_admin,finance_admin')
        ->name('finance.refund');

    // Accreditation / QR
    Route::get('accreditation', [\App\Http\Controllers\Admin\AccreditationController::class, 'index'])
        ->middleware('role:super_admin,accreditation_officer')
        ->name('accreditation.index');
    Route::post('accreditation/scan', [\App\Http\Controllers\Admin\AccreditationController::class, 'scan'])
        ->middleware('role:super_admin,accreditation_officer')
        ->name('accreditation.scan');
    Route::get('accreditation/offline-cache', [\App\Http\Controllers\Admin\AccreditationController::class, 'offlineCache'])
        ->middleware('role:super_admin,accreditation_officer')
        ->name('accreditation.offline');
    Route::post('accreditation/sync-cache', [\App\Http\Controllers\Admin\AccreditationController::class, 'syncCache'])
        ->middleware('role:super_admin,accreditation_officer')
        ->name('accreditation.sync');

    // Audit Logs
    Route::get('audit', [\App\Http\Controllers\Admin\AuditController::class, 'index'])
        ->middleware('role:super_admin,finance_admin,registration_admin')
        ->name('audit.index');
    Route::get('audit/export', [\App\Http\Controllers\Admin\AuditController::class, 'export'])
        ->middleware('role:super_admin,finance_admin,registration_admin')
        ->name('audit.export');
    Route::get('audit-trail/{entityType}/{entityId}', [\App\Http\Controllers\Admin\AuditController::class, 'trail'])
        ->middleware('role:super_admin,finance_admin,registration_admin')
        ->name('audit.trail');
    Route::get('audit/{auditLog}', [\App\Http\Controllers\Admin\AuditController::class, 'show'])
        ->middleware('role:super_admin,finance_admin,registration_admin')
        ->name('audit.show');

    // Stream settings
    Route::get('stream', [\App\Http\Controllers\Admin\StreamController::class, 'index'])
        ->middleware('role:super_admin')
        ->name('stream.index');
    Route::post('stream', [\App\Http\Controllers\Admin\StreamController::class, 'update'])
        ->middleware('role:super_admin')
        ->name('stream.update');

    // Certificates
    Route::get('certificates', [\App\Http\Controllers\Admin\CertificatesController::class, 'index'])
        ->middleware('role:super_admin,finance_admin,registration_admin')
        ->name('certificates.index');
    Route::post('certificates/generate-batch', [\App\Http\Controllers\Admin\CertificatesController::class, 'generateBatch'])
        ->middleware('role:super_admin')
        ->name('certificates.generate-batch');
    Route::post('certificates/issue', [\App\Http\Controllers\Admin\CertificatesController::class, 'issue'])
        ->middleware('role:super_admin')
        ->name('certificates.issue');
    Route::post('certificates/{certificate}/revoke', [\App\Http\Controllers\Admin\CertificatesController::class, 'revoke'])
        ->middleware('role:super_admin')
        ->name('certificates.revoke');

    // Pricing management
    Route::get('pricing', [\App\Http\Controllers\Admin\PricingController::class, 'index'])
        ->middleware('role:super_admin')
        ->name('pricing.index');
    Route::post('pricing/versions', [\App\Http\Controllers\Admin\PricingController::class, 'storeVersion'])
        ->middleware('role:super_admin')
        ->name('pricing.versions.store');
    Route::put('pricing/versions/{version}', [\App\Http\Controllers\Admin\PricingController::class, 'updateVersion'])
        ->middleware('role:super_admin')
        ->name('pricing.versions.update');
    Route::delete('pricing/versions/{version}', [\App\Http\Controllers\Admin\PricingController::class, 'deleteVersion'])
        ->middleware('role:super_admin')
        ->name('pricing.versions.delete');
    Route::post('pricing/items', [\App\Http\Controllers\Admin\PricingController::class, 'storeItem'])
        ->middleware('role:super_admin')
        ->name('pricing.items.store');
    Route::put('pricing/items/{item}', [\App\Http\Controllers\Admin\PricingController::class, 'updateItem'])
        ->middleware('role:super_admin')
        ->name('pricing.items.update');
    Route::delete('pricing/items/{item}', [\App\Http\Controllers\Admin\PricingController::class, 'deleteItem'])
        ->middleware('role:super_admin')
        ->name('pricing.items.delete');

    // User & role management
    Route::get('users', [\App\Http\Controllers\Admin\UsersController::class, 'index'])
        ->middleware('role:super_admin')
        ->name('users.index');
    Route::put('users/{user}/role', [\App\Http\Controllers\Admin\UsersController::class, 'updateRole'])
        ->middleware('role:super_admin')
        ->name('users.role.update');

    // System settings
    Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])
        ->middleware('role:super_admin')
        ->name('settings.index');
    Route::post('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])
        ->middleware('role:super_admin')
        ->name('settings.update');

    // Sponsors management
    Route::get('sponsors', [\App\Http\Controllers\Admin\SponsorsController::class, 'index'])
        ->middleware('role:super_admin')
        ->name('sponsors.index');
    Route::post('sponsors', [\App\Http\Controllers\Admin\SponsorsController::class, 'store'])
        ->middleware('role:super_admin')
        ->name('sponsors.store');
    Route::put('sponsors/{sponsor}', [\App\Http\Controllers\Admin\SponsorsController::class, 'update'])
        ->middleware('role:super_admin')
        ->name('sponsors.update');
    Route::delete('sponsors/{sponsor}', [\App\Http\Controllers\Admin\SponsorsController::class, 'destroy'])
        ->middleware('role:super_admin')
        ->name('sponsors.delete');
});
