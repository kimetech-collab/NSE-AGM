# ARCHITECTURAL PATTERNS & BEST PRACTICES

## 1. SERVICE LAYER ARCHITECTURE

**Benefit:** Decouple business logic from controllers. Testable, reusable, maintainable.

```php
// app/Services/RegistrationService.php
class RegistrationService {
  public function __construct(
    private RegistrationRepository $registrations,
    private PricingRepository $pricing,
    private AuditService $audit,
  ) {}
  
  public function registerParticipant(RegisterParticipantDTO $dto): Registration {
    // 1. Validate membership format if NSE member
    if ($dto->is_nse_member && !$this->validateMembershipNumber($dto->membership_number)) {
      throw new InvalidMembershipException();
    }
    
    // 2. Get active pricing version
    $pricing = $this->pricing->getActivePricing();
    $price = $pricing->getPriceForCategory($dto->category, $dto->attendance_type);
    
    // 3. Create registration record
    $registration = $this->registrations->create([
      'user_id' => $dto->user_id,
      'membership_category' => $dto->category,
      'attendance_type' => $dto->attendance_type,
      'amount_due' => $price,
      'pricing_version_id' => $pricing->id,
      'price_locked_at' => now(),
      'is_early_bird' => $pricing->isEarlyBirdActive(),
    ]);
    
    // 4. Generate QR token
    $qr_token = $this->generateSecureQRToken();
    $registration->qr_token_hash = hash('sha256', $qr_token);
    $registration->qr_token_generated_at = now();
    $registration->save();
    
    // 5. Dispatch event (queue email verification)
    event(new RegistrationCreated($registration));
    
    // 6. Audit log
    $this->audit->log('registration.created', 'Registration', $registration->id);
    
    return $registration;
  }
}

// app/Services/PaymentService.php
class PaymentService {
  public function __construct(
    private PaymentRepository $payments,
    private RegistrationRepository $registrations,
    private AuditService $audit,
  ) {}
  
  public function processPaystackWebhook(array $payload): void {
    // 1. Verify signature + timestamp + API
    $this->verifyPaystackSignature($payload);
    
    // 2. Find registration by paystack_reference
    $registration = $this->registrations->findByPaystackReference($payload['reference']);
    
    // 3. Idempotency check
    if ($registration->payment_status === 'Paid') {
      return;  // Already processed
    }
    
    // 4. Update payment status
    $registration->update([
      'payment_status' => 'Paid',
      'paystack_reference' => $payload['reference'],
      'amount_paid' => $payload['amount'] / 100,  // kobo to naira
      'payment_timestamp' => now(),
    ]);
    
    // 5. Dispatch event
    event(new PaymentConfirmed($registration));
    
    // 6. Audit + cache invalidation
    $this->audit->log('payment.confirmed', 'Registration', $registration->id);
    Cache::forget('kpi:total_paid');
  }
  
  public function initiateRefund(Registration $registration, RefundDTO $dto): void {
    if ($registration->payment_status !== 'Paid') {
      throw new RefundException('Registration not paid');
    }
    
    // Call Paystack Refund API
    $refund = PaystackClient::refund($registration->paystack_reference, $dto->amount);
    
    // Update registration
    $registration->update([
      'refund_status' => $dto->is_partial ? 'Partially Refunded' : 'Refunded',
      'refund_amount' => $dto->amount,
      'refund_reason' => $dto->reason,
    ]);
    
    // Dispatch event
    event(new RefundInitiated($registration));
  }
}

// app/Services/CertificateService.php
class CertificateService {
  public function __construct(
    private RegistrationRepository $registrations,
    private CertificateRepository $certificates,
    private AuditService $audit,
  ) {}
  
  public function generateCertificatesForEligible(): void {
    // 1. Find eligible registrations (Paid + Attended)
    $eligible = $this->registrations
      ->where('payment_status', 'Paid')
      ->where('attendance_status', '!=', 'Not Attended')
      ->where('certificate_status', 'Not Issued')
      ->get();
    
    // 2. Generate certificates in batch
    foreach ($eligible as $registration) {
      $certificate_id = 'NSE59-2026-' . $this->generateRandomId(6);
      $pdf = $this->generateCertificatePDF($registration, $certificate_id);
      
      $this->certificates->create([
        'registration_id' => $registration->id,
        'certificate_id' => $certificate_id,
        'participant_name' => $registration->user->full_name,
        'category' => $registration->membership_category,
        'issued_at' => now(),
        'issued_by' => Auth::id(),
        'pdf_path' => $pdf,
      ]);
      
      $registration->update([
        'certificate_status' => 'Issued',
        'certificate_id' => $certificate_id,
      ]);
      
      event(new CertificateIssued($registration));
      $this->audit->log('certificate.issued', 'Certificate', $certificate_id);
    }
  }
}

// app/Services/AttendanceService.php
class AttendanceService {
  public function __construct(
    private RegistrationRepository $registrations,
    private SessionRepository $sessions,
  ) {}
  
  public function recordVirtualHeartbeat(Registration $registration, string $session_id): void {
    // 1. Check if session already has recent heartbeat (prevent duplicates)
    $recent_session = $this->sessions
      ->whereRegistrationAndSessionId($registration->id, $session_id)
      ->latest('created_at')
      ->first();
    
    if ($recent_session && $recent_session->created_at->diffInSeconds(now()) < 60) {
      return;  // Ignore duplicate
    }
    
    // 2. Accumulate virtual time
    $registration->increment('virtual_total_seconds', 60);
    
    // 3. Check if threshold reached (>= 600)
    if ($registration->virtual_total_seconds >= 600 &&
        $registration->attendance_status == 'Not Attended') {
      $registration->update([
        'attendance_status' => 'Virtual',
        'certificate_eligible' => true,
      ]);
      event(new VirtualAttendanceConfirmed($registration));
    }
    
    // 4. Log session
    $this->sessions->createOrIgnore([
      'registration_id' => $registration->id,
      'session_id' => $session_id,
    ]);
  }
}
```

---

## 2. EVENT-DRIVEN DESIGN

**Events + Listeners** (decouple concerns, enable async):

```php
// app/Events
RegistrationCreated
PaymentConfirmed
RefundInitiated
CertificateIssued
VirtualAttendanceConfirmed
QRScanned

// app/Listeners

class SendEmailVerificationOTP implements ShouldQueue {
  public function handle(RegistrationCreated $event) {
    Mail::queue(new EmailVerificationOTPMail($event->registration));
  }
}

class LogRegistrationAudit {
  public function handle(RegistrationCreated $event) {
    AuditLog::create(['action' => 'registration.created', ...]);
  }
}

class GenerateQRTicket implements ShouldQueue {
  public function handle(PaymentConfirmed $event) {
    $this->ticketService->generateAndStoreQRTicket($event->registration);
  }
}

class SendPaymentConfirmationEmail implements ShouldQueue {
  public function handle(PaymentConfirmed $event) {
    Mail::queue(new PaymentConfirmationMail($event->registration));
  }
}

class InvalidatePricingCache {
  public function handle(PaymentConfirmed $event) {
    Cache::forget('kpi:total_paid');
  }
}

class SendCertificateEmail implements ShouldQueue {
  public function handle(CertificateIssued $event) {
    Mail::queue(new CertificateIssuedMail($event->registration));
  }
}
```

**Benefits:**
- ✅ Avoid god controllers
- ✅ Listeners can be queued (async)
- ✅ Easy to add new side effects without core code changes
- ✅ Testable in isolation

---

## 3. REPOSITORY PATTERN

**abstraction layer for data access:**

```php
// app/Repositories/RegistrationRepositoryContract.php
interface RegistrationRepositoryContract {
  public function create(array $data): Registration;
  public function find(int $id): ?Registration;
  public function findByPaystackReference(string $ref): ?Registration;
  public function findByQRToken(string $token): ?Registration;
  public function getPaidAndAttended(): Collection;
  public function getEligibleForCertificates(): Collection;
}

// app/Repositories/RegistrationRepository.php
class RegistrationRepository implements RegistrationRepositoryContract {
  public function __construct(private Registration $model) {}
  
  public function create(array $data): Registration {
    return $this->model->create($data);
  }
  
  public function find(int $id): ?Registration {
    return $this->model->with('user', 'pricing_version')->find($id);
  }
  
  public function findByPaystackReference(string $ref): ?Registration {
    return $this->model->where('paystack_reference', $ref)->first();
  }
  
  public function getPaidAndAttended(): Collection {
    return $this->model
      ->where('payment_status', 'Paid')
      ->where('attendance_status', '!=', 'Not Attended')
      ->get();
  }
}

// Register in AppServiceProvider
public function register(): void {
  $this->app->singleton(
    RegistrationRepositoryContract::class,
    RegistrationRepository::class
  );
}

// Usage in controllers
public function show(int $id, RegistrationRepositoryContract $repo) {
  $registration = $repo->find($id);
  return view('registration.show', ['registration' => $registration]);
}
```

**Benefit:** Swap implementations without changing controllers (e.g., MySQL → DynamoDB).

---

## 4. DTOs & REQUEST VALIDATION

**Data Transfer Objects** (multi-step forms):

```php
// app/DTOs/RegisterParticipantDTO.php
class RegisterParticipantDTO {
  public function __construct(
    public string $first_name,
    public string $surname,
    public string $email,
    public string $phone,
    public string $gender,
    public bool $is_nse_member,
    public ?string $membership_number,
    public ?string $membership_category,
    public string $attendance_type,
    public string $organization,
    public bool $self_attestation,
  ) {}
  
  public static function fromRequest(RegistrationRequest $request): self {
    return new self(
      first_name: $request->validated('first_name'),
      surname: $request->validated('surname'),
      // ... map all fields
    );
  }
}

// app/Http/Requests/RegistrationRequest.php (Step 1)
class RegistrationRequest extends FormRequest {
  public function rules(): array {
    return [
      'first_name' => 'required|string|max:100',
      'surname' => 'required|string|max:100',
      'email' => 'required|email|unique:registrations',
      'phone' => 'required|regex:/^(070|071|080|081|090|091)\d{8}$/',
      'is_nse_member' => 'required|boolean',
      'membership_number' => Rule::requiredIf($this->is_nse_member) . '|regex:/^NSE-\d{5}$/',
      'membership_category' => Rule::requiredIf($this->is_nse_member) . '|in:Student,Graduate,Corporate,Fellow',
      'attendance_type' => 'required|in:Physical,Virtual',
      'self_attestation' => 'required|accepted',
    ];
  }
  
  public function messages(): array {
    return [
      'membership_number.regex' => 'Membership number must be in format NSE-12345',
      'phone.regex' => 'Phone number must be valid Nigerian mobile number',
    ];
  }
}

// app/Http/Requests/EmailVerificationRequest.php (Step 2)
class EmailVerificationRequest extends FormRequest {
  public function rules(): array {
    return [
      'otp' => 'required|string|size:6|regex:/^[0-9]{6}$/',
    ];
  }
}

// Usage in controller
class RegistrationController extends Controller {
  public function step1_submit(
    RegistrationRequest $request,
    RegistrationService $service
  ) {
    $dto = RegisterParticipantDTO::fromRequest($request);
    $registration = $service->registerParticipant($dto);
    return redirect('/email/verify')->with('registration_id', $registration->id);
  }
}
```

**Benefits:**
- ✅ Type-safe data transfer
- ✅ Clear contracts between layers
- ✅ Testable independently
- ✅ Self-documenting code
