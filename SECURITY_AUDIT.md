# SECURITY & COMPLIANCE AUDIT

## Data Protection

**Encryption in Transit:**
- ✅ HTTPS only (TLS 1.3 minimum) for all endpoints
- ✅ No HTTP fallback
- ✅ HSTS header: `Strict-Transport-Security: max-age=31536000; includeSubDomains`

**Encryption at Rest:**
- ❌ Database encryption: Email + phone NOT encrypted at rest (HTTPS transit sufficient)
- ✅ S3 storage: Enable SSE-S3 for PDF certificates
- ✅ Redis: Minimal sensitive data. Disable persistence if possible.

**PII Handling:**
- Email: Searchable (needed for payment receipt, OTP delivery). No hashing.
- Phone: Stored plaintext (SMS-ready for future). Tokenize if SMS enabled.
- Membership number: Plaintext (self-declared, no validation).
- Certificates: PDFs on S3 with random file paths (not sequential).

**Data Retention:**
- Registrations: Indefinite (legal/financial audit)
- Audit logs: 5 years minimum (archived after 12 months)
- Certificates: 5 years minimum (downloadable indefinitely)
- Session data: 1 hour (Redis TTL)
- OTP codes: 10 minutes (auto-expire)
- Deleted accounts: Soft-delete 90 days, then hard delete

---

## Webhook Verification (Mission-Critical)

**Paystack Webhook Security** (3-layer verification):

```php
// 1. HMAC-SHA512 signature verification
VerifyPaystackWebhookSignature Middleware:
  $secret = config('services.paystack.secret_key');
  $expected_hash = hash_hmac('sha512', request()->getContent(), $secret);
  $provided_hash = request()->header('X-Paystack-Signature');
  abort_if($expected_hash !== $provided_hash, 403, 'Invalid signature');

// 2. Timestamp replay prevention
  $webhook_time = Carbon::parse($payload['createdAt']);
  $now = Carbon::now('UTC');
  abort_if($now->diffInMinutes($webhook_time) > 5, 403, 'Webhook too old');

// 3. Transaction verification (double-check with Paystack API)
  $paystack = new PaystackClient(config('services.paystack.public_key'));
  $api_transaction = $paystack->verifyTransaction($payload['reference']);
  abort_if(
    $api_transaction['status'] != $payload['status'] ||
    $api_transaction['amount'] != $payload['amount'] * 100,
    403,
    'Transaction mismatch'
  );

// 4. Idempotency (unique constraint on paystack_reference)
  Registration::firstOrCreate(
    ['paystack_reference' => $payload['reference']],
    ['payment_status' => 'Paid', ...]
  );
```

**Webhook IP Allowlist** (Cloudflare WAF rule):
- Only allow Paystack IPs (verify on Paystack docs)
- Rate limit: 100 webhooks per minute (DDoS prevention)

---

## Rate Limiting & CAPTCHA

**Public Endpoints:**

| Endpoint | Limit | Method |
|---|---|---|
| `POST /register` | 10 requests per 5 min per IP | CAPTCHA + IP-based |
| `POST /email/verify` | 3 per minute per IP | IP-based + lockout after 5 failures |
| `GET /verify/{certificate_id}` | 5 per minute per IP | IP-based + cache |
| Webhook | IP allowlist only | Paystack IPs only |

**CAPTCHA Configuration:**
- Cloudflare Turnstile (managed mode)
- Enforce on: /register form, failed OTP loops (> 3 attempts), failed MFA (> 5)
- Token TTL: 300 seconds
- Log success/failure to audit_logs

**MFA Rate Limiting:**
```
Email OTP: /email/verify
  - 10 min expiry (user-friendly)
  - 5 attempts per IP before 30-min lockout
  - Tracked in registrations.otp_attempts
  - Configurable by Super Admin
```

---

## Audit Trail

**Immutable Audit Logging** (all admin actions):

```php
// Middleware: LogAdminAction (all /admin/* routes)

EventListener on AdminActionPerformed:
  AuditLog::create([
    'actor_id' => Auth::id(),
    'action' => 'payment.refund.initiated',  // verb.noun.action
    'entity_type' => 'Registration',
    'entity_id' => $registration->id,
    'before_state' => $registration->getOriginal(),
    'after_state' => $registration->toArray(),
    'metadata' => [
      'refund_amount' => 50000,
      'reason' => 'User requested cancellation',
      'paystack_reference' => 'REF-001234',
    ],
    'ip_address' => request()->ip(),
    'user_agent' => request()->header('User-Agent'),
  ]);
```

**Searchable Audit Log** (/admin/audit):
- Filter: actor_id, action, entity_type, date range
- Export: CSV/JSON with signatures
- Access: Super Admin + Compliance Officer only
- Retention: 5 years (cold storage after 12 months)

---

## Security Checklist

- ✅ HTTPS + HSTS
- ✅ SQL Injection: Parameterized queries (Eloquent ORM)
- ✅ Mass Assignment: Explicit $fillable on models
- ✅ CSRF: Laravel middleware (enabled)
- ✅ XSS: Blade auto-escaping + Content-Security-Policy header
- ✅ Authentication: Session-based + MFA (email OTP)
- ✅ Authorization: Policies + Gates per action
- ✅ Rate Limiting: Per-IP global throttle
- ✅ Webhook Verification: HMAC + IP allowlist + timestamp + API double-check
- ✅ Audit Logging: Immutable, all admin actions recorded
- ✅ PII Protection: HTTPS transit, no root-level encryption (cost-benefit)
- ✅ Secrets: .env with restricted permissions, quarterly key rotation
