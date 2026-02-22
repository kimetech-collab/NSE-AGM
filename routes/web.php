<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\EnsureAdminMfa;
use App\Http\Middleware\VerifyPaystackSignature;
use App\Http\Middleware\LogAdminRouteAccess;
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
Route::get('/programme', [\App\Http\Controllers\ProgrammeController::class, 'index'])->name('programme');

// Venue page
Route::get('/venue', [\App\Http\Controllers\VenueController::class, 'index'])->name('venue');

// FAQs page
Route::get('/faqs', [\App\Http\Controllers\FaqsController::class, 'index'])->name('faqs');

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

// Speakers page
Route::get('/speakers', function () {
    $speakers = collect();
    $keynote_speakers = collect();
    $invited_speakers = collect();

    if (Schema::hasTable('speakers')) {
        $speakers = \App\Models\Speaker::query()
            ->active()
            ->ordered()
            ->get();

        $keynote_speakers = $speakers->where('is_keynote', true);
        $invited_speakers = $speakers->where('is_keynote', false);
    }

    return view('speakers', [
        'speakers' => $speakers,
        'keynote_speakers' => $keynote_speakers,
        'invited_speakers' => $invited_speakers,
    ]);
})->name('speakers');

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
Route::prefix('admin')->name('admin.')->middleware(['auth','verified', EnsureAdminMfa::class, 'role:super_admin,finance_admin,registration_admin,accreditation_officer,support_agent', LogAdminRouteAccess::class])->group(function () {
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
    Route::get('finance/export', [\App\Http\Controllers\Admin\FinanceController::class, 'export'])
        ->middleware('role:super_admin,finance_admin')
        ->name('finance.export');

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
    Route::get('certificates/export', [\App\Http\Controllers\Admin\CertificatesController::class, 'export'])
        ->middleware('role:super_admin,finance_admin,registration_admin')
        ->name('certificates.export');

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
        ->middleware('role:super_admin,finance_admin,registration_admin,accreditation_officer,support_agent')
        ->name('users.index');
    Route::get('users/{user}', [\App\Http\Controllers\Admin\UsersController::class, 'show'])
        ->middleware('role:super_admin,finance_admin,registration_admin,accreditation_officer,support_agent')
        ->name('users.show');
    Route::post('users', [\App\Http\Controllers\Admin\UsersController::class, 'store'])
        ->middleware('role:super_admin')
        ->name('users.store');
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

    // Account settings (profile & password)
    Route::get('account/profile', [\App\Http\Controllers\Admin\AccountController::class, 'profile'])
        ->name('account.profile');
    Route::patch('account/profile', [\App\Http\Controllers\Admin\AccountController::class, 'updateProfile'])
        ->name('account.profile.update');
    Route::get('account/password', [\App\Http\Controllers\Admin\AccountController::class, 'password'])
        ->name('account.password');
    Route::patch('account/password', [\App\Http\Controllers\Admin\AccountController::class, 'updatePassword'])
        ->name('account.password.update');

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

    // Programme management
    Route::get('programme', [\App\Http\Controllers\Admin\ProgrammesController::class, 'index'])
        ->middleware('role:super_admin')
        ->name('programme.index');
    Route::get('programme/create', [\App\Http\Controllers\Admin\ProgrammesController::class, 'create'])
        ->middleware('role:super_admin')
        ->name('programme.create');
    Route::post('programme', [\App\Http\Controllers\Admin\ProgrammesController::class, 'store'])
        ->middleware('role:super_admin')
        ->name('programme.store');
    Route::get('programme/{programmeItem}/edit', [\App\Http\Controllers\Admin\ProgrammesController::class, 'edit'])
        ->middleware('role:super_admin')
        ->name('programme.edit');
    Route::put('programme/{programmeItem}', [\App\Http\Controllers\Admin\ProgrammesController::class, 'update'])
        ->middleware('role:super_admin')
        ->name('programme.update');
    Route::delete('programme/{programmeItem}', [\App\Http\Controllers\Admin\ProgrammesController::class, 'destroy'])
        ->middleware('role:super_admin')
        ->name('programme.delete');

    // FAQs management
    Route::get('faqs', [\App\Http\Controllers\Admin\FaqsController::class, 'index'])
        ->middleware('role:super_admin')
        ->name('faqs.index');
    Route::get('faqs/create', [\App\Http\Controllers\Admin\FaqsController::class, 'create'])
        ->middleware('role:super_admin')
        ->name('faqs.create');
    Route::post('faqs', [\App\Http\Controllers\Admin\FaqsController::class, 'store'])
        ->middleware('role:super_admin')
        ->name('faqs.store');
    Route::get('faqs/{faqItem}/edit', [\App\Http\Controllers\Admin\FaqsController::class, 'edit'])
        ->middleware('role:super_admin')
        ->name('faqs.edit');
    Route::put('faqs/{faqItem}', [\App\Http\Controllers\Admin\FaqsController::class, 'update'])
        ->middleware('role:super_admin')
        ->name('faqs.update');
    Route::delete('faqs/{faqItem}', [\App\Http\Controllers\Admin\FaqsController::class, 'destroy'])
        ->middleware('role:super_admin')
        ->name('faqs.delete');

    // Venue management
    Route::get('venues', [\App\Http\Controllers\Admin\VenuesController::class, 'index'])
        ->middleware('role:super_admin')
        ->name('venues.index');
    Route::get('venues/create', [\App\Http\Controllers\Admin\VenuesController::class, 'create'])
        ->middleware('role:super_admin')
        ->name('venues.create');
    Route::post('venues', [\App\Http\Controllers\Admin\VenuesController::class, 'store'])
        ->middleware('role:super_admin')
        ->name('venues.store');
    Route::get('venues/{venueItem}/edit', [\App\Http\Controllers\Admin\VenuesController::class, 'edit'])
        ->middleware('role:super_admin')
        ->name('venues.edit');
    Route::put('venues/{venueItem}', [\App\Http\Controllers\Admin\VenuesController::class, 'update'])
        ->middleware('role:super_admin')
        ->name('venues.update');
    Route::delete('venues/{venueItem}', [\App\Http\Controllers\Admin\VenuesController::class, 'destroy'])
        ->middleware('role:super_admin')
        ->name('venues.delete');

    // Roles and permissions management
    Route::get('roles', [\App\Http\Controllers\Admin\RolesController::class, 'index'])
        ->middleware('role:super_admin')
        ->name('roles.index');
    Route::post('roles', [\App\Http\Controllers\Admin\RolesController::class, 'store'])
        ->middleware('role:super_admin')
        ->name('roles.store');
    Route::put('roles/{role}', [\App\Http\Controllers\Admin\RolesController::class, 'update'])
        ->middleware('role:super_admin')
        ->name('roles.update');
    Route::delete('roles/{role}', [\App\Http\Controllers\Admin\RolesController::class, 'destroy'])
        ->middleware('role:super_admin')
        ->name('roles.delete');

    // Speakers management
    Route::get('speakers', [\App\Http\Controllers\Admin\SpeakersController::class, 'index'])
        ->middleware('role:super_admin')
        ->name('speakers.index');
    Route::get('speakers/create', [\App\Http\Controllers\Admin\SpeakersController::class, 'create'])
        ->middleware('role:super_admin')
        ->name('speakers.create');
    Route::post('speakers', [\App\Http\Controllers\Admin\SpeakersController::class, 'store'])
        ->middleware('role:super_admin')
        ->name('speakers.store');
    Route::get('speakers/{speaker}/edit', [\App\Http\Controllers\Admin\SpeakersController::class, 'edit'])
        ->middleware('role:super_admin')
        ->name('speakers.edit');
    Route::put('speakers/{speaker}', [\App\Http\Controllers\Admin\SpeakersController::class, 'update'])
        ->middleware('role:super_admin')
        ->name('speakers.update');
    Route::delete('speakers/{speaker}', [\App\Http\Controllers\Admin\SpeakersController::class, 'destroy'])
        ->middleware('role:super_admin')
        ->name('speakers.delete');
    Route::post('speakers/bulk', [\App\Http\Controllers\Admin\SpeakersController::class, 'bulk'])
        ->middleware('role:super_admin')
        ->name('speakers.bulk');
});
