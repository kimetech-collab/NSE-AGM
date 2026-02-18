# NSE 59th AGM Portal — MVP Quick Start

## Status
✅ **MVP Ready** (Feb 18, 2026) — All core flows complete and tested.

## Stack
- **Backend:** Laravel 12 + PHP 8.5
- **Frontend:** Blade + Tailwind CSS
- **Database:** SQLite (local) / MySQL (production)
- **Queue:** Redis
- **Cache:** Redis
- **Payments:** Paystack (sandbox for testing)

## Quick Start

### 1. Install & Setup
```bash
cd /Users/apple/Desktop/Developments/nse_portal
composer install
npm install
npm run build
php artisan migrate
php artisan db:seed
php artisan serve
```

Server runs on `http://localhost:8000`

### 2. Test User
- **Email:** test@example.com
- **Password:** password

### 3. Test Flows

**Register Flow:**
- Visit `/register` → fill form → select pricing → submit
- OTP appears in console (development)
- Enter OTP → redirected to payment page
- Amount locked and displayed

**Payment (Sandbox):**
- Click "Pay Now" on payment page
- **Test Card:** 4111 1111 1111 1111
- **CVV:** any 3 digits
- **Expiry:** any future date
- Complete payment and see ticket

**Admin Dashboard:**
- Login with test@example.com / password
- Visit `/admin/dashboard` to see KPI counts
- `/admin/registrations` to view all registrations
- `/admin/finance` for transaction list

### 4. Tests
```bash
php artisan test
```

**Test Coverage:**
- ✅ Registration with OTP (2 tests)
- ✅ OTP verification flow
- ✅ Paystack webhook idempotency
- ✅ Full end-to-end flow

## Configuration

### Paystack (Sandbox)
Edit `.env`:
```
PAYSTACK_KEY=pk_test_xxx
PAYSTACK_SECRET=sk_test_xxx
```

Get keys from [Paystack Dashboard](https://dashboard.paystack.com/settings/developer)

### Email (Development)
Mail logs to console. Enable Postmark/Resend in production:
```
MAIL_MAILER=postmark
POSTMARK_API_KEY=xxx
```

## File Structure
```
app/
  ├── Http/Controllers/
  │   ├── RegistrationController        # Registration 3-step flow
  │   ├── PaymentController             # Paystack initiate + callback
  │   └── Admin/
  │       ├── DashboardController       # KPI cards
  │       ├── RegistrationsController   # List/search
  │       └── FinanceController         # Transactions + refund
  ├── Services/
  │   ├── RegistrationService           # OTP generation/verification
  │   └── PaymentService                # Webhook handler
  └── Models/
      ├── Registration
      ├── PaymentTransaction
      ├── PricingItem
      └── ...

resources/views/
  ├── register.blade.php               # Registration form
  ├── verify.blade.php                 # OTP verification
  ├── payment.blade.php                # Payment initiation
  ├── ticket.blade.php                 # Ticket QR display
  └── admin/
      ├── dashboard.blade.php
      ├── registrations/
      │   ├── index.blade.php
      │   └── show.blade.php
      └── finance/
          └── index.blade.php

tests/Feature/
  ├── RegistrationTest.php
  ├── EndToEndFlowTest.php
  └── PaystackWebhookTest.php
```

## API Endpoints

### Public
- `GET /` — Landing page
- `GET /register` — Registration form
- `POST /register` — Submit registration
- `GET /email/verify/{id}` — OTP form
- `POST /email/verify` — Verify OTP
- `GET /payment?registrationId={id}` — Payment page
- `POST /payment/initiate` — Initialize Paystack
- `GET /payment/callback` — Paystack success
- `POST /paystack/webhook` — Webhook listener (HMAC-verified)
- `GET /ticket/{token}` — Ticket view

### Admin (auth required)
- `GET /admin/dashboard` — KPIs
- `GET /admin/registrations` — List & search
- `GET /admin/registrations/{id}` — Detail view
- `GET /admin/finance` — Transactions
- `POST /admin/finance/refund/{id}` — Initiate refund

## Recent Fixes (Day 4)
- ✅ Fixed registration price: now reads from selected PricingItem
- ✅ Added payment form with Paystack API integration
- ✅ Implemented callback handler for post-payment processing
- ✅ Added error display to registration form
- ✅ Seeded pricing items (Early Bird §10,000, Standard §15,000)
- ✅ All 5 feature tests passing

## Next Steps (Post-MVP)
- [ ] Email notifications (Postmark)
- [ ] SMS OTP (Termii)
- [ ] QR code generation with logo
- [ ] Batch export (CSV + PDF)
- [ ] Refund workflow (admin initiation)
- [ ] Rate limiting & DDoS protection
- [ ] Performance monitoring (New Relic)
- [ ] Load testing (k6)

## Support
- **Config:** [NSE-AGM-Portal_design_Architecture.md](NSE-AGM-Portal_design_Architecture.md)
- **Security:** [SECURITY_AUDIT.md](SECURITY_AUDIT.md)
- **Architecture:** [CLAUDE.md](CLAUDE.md)
