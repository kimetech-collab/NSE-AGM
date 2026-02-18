# NSE 59th AGM Portal

A comprehensive registration and payment portal for the NSE (Nigerian Stock Exchange) 59th Annual General Meeting, built with Laravel, Livewire, and Paystack payment integration.

## 🎯 Project Overview

The NSE AGM Portal is a 1-week MVP designed to handle:
- **User Registration** with OTP verification
- **Dynamic Pricing** with early-bird discounts
- **Paystack Payment Integration** (sandbox-ready)
- **Admin Dashboard** for financial oversight
- **Refund Management** with audit logging
- **Ticket Generation** with unique tokens

## ✨ Key Features

### User-Facing
- 🔐 Email-based registration with OTP verification
- 💳 Seamless Paystack payment checkout
- 🎫 Instant ticket generation post-payment
- 📱 Responsive design with Tailwind CSS
- ⚡ Real-time validation with Livewire

### Admin-Facing
- 📊 Finance dashboard with transaction overview
- ♻️ One-click refund processing with confirmation modal
- 📝 Comprehensive audit logging for compliance
- 🔍 Transaction details and status tracking
- 💾 Flash notifications for success/error feedback

### Technical
- 🔄 Webhook verification with HMAC signature validation
- 🛡️ Idempotent payment processing
- 💾 Paystack transaction ID persistence for reliable refunds
- 🧪 Comprehensive test coverage (registration, payments, refunds)
- 📦 Cache and queue support

## 🚀 Quick Start

### Prerequisites
- PHP 8.1+
- Composer
- Node.js & npm
- SQLite (for development)
- Laravel 11+

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd nse_portal
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure Paystack** (in `.env`)
   ```env
   PAYSTACK_KEY=pk_test_xxxxxxxxxxxx
   PAYSTACK_SECRET=sk_test_xxxxxxxxxxxx
   ```

5. **Setup database**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Start development server**
   ```bash
   php artisan serve
   ```

Visit `http://localhost:8000` in your browser.

## 📋 Environment Configuration

Create a `.env` file in the project root with the following key variables:

```env
APP_NAME="NSE AGM Portal"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
QUEUE_CONNECTION=redis
CACHE_DRIVER=redis

# Paystack Configuration (Sandbox)
PAYSTACK_KEY=pk_test_your_key_here
PAYSTACK_SECRET=sk_test_your_secret_here

# Mail Configuration
MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@nse-agm.local
```

## 🔧 Development

### Running Tests
```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test tests/Feature/RegistrationTest.php
php artisan test tests/Feature/PaystackWebhookTest.php
php artisan test tests/Feature/RefundTest.php

# Run with coverage
php artisan test --coverage
```

### Building Assets
```bash
# Development build
npm run dev

# Production build
npm run build

# Watch for changes
npm run watch
```

### Database Commands
```bash
# Run migrations
php artisan migrate

# Seed test data
php artisan db:seed

# Clear cache
php artisan cache:clear
php artisan config:clear
```

## 📁 Project Structure

```
nse_portal/
├── app/
│   ├── Actions/              # Fortify actions
│   ├── Concerns/             # Shared traits (validation)
│   ├── Http/
│   │   ├── Controllers/      # Route controllers
│   │   └── Middleware/       # HTTP middleware
│   ├── Models/               # Database models
│   ├── Providers/            # Service providers
│   └── Services/             # Business logic
│       ├── RegistrationService.php    # OTP handling
│       └── PaymentService.php         # Paystack integration
├── database/
│   ├── migrations/           # Database schemas
│   ├── factories/            # Model factories
│   └── seeders/              # Database seeds
├── resources/
│   ├── views/                # Blade templates
│   │   ├── components/       # Reusable components
│   │   ├── layouts/          # Layout templates
│   │   └── pages/            # Page templates
│   ├── css/                  # Styles
│   └── js/                   # JavaScript
├── routes/
│   ├── web.php               # Web routes
│   ├── console.php           # Console commands
│   └── settings.php          # Settings routes (Fortify)
├── tests/
│   ├── Feature/              # Feature tests
│   └── Unit/                 # Unit tests
├── storage/                  # Logs, cache, sessions
├── public/                   # Public assets
└── config/                   # Configuration files
```

## 💳 Payment Flow

### User Perspective
1. Complete registration with email
2. Receive OTP via email
3. Verify OTP
4. Enter payment details (redirected to Paystack)
5. Complete payment on Paystack
6. Receive ticket and confirmation email

### Technical Flow
```
Registration Form
  ↓
Register Request → RegistrationController::register()
  ↓
Generate & Send OTP (cached for 10 minutes)
  ↓
OTP Verification
  ↓
Payment Page
  ↓
POST /api/payment/initiate
  ↓
Create PaymentTransaction + Paystack Initialize
  ↓
Return checkout URL
  ↓
User redirected to Paystack checkout
  ↓
Paystack callback → /payment/callback
  ↓
Verify transaction with Paystack
  ↓
Mark registration as paid + Generate ticket_token
  ↓
Redirect to ticket page
  ↓
Webhook verification (idempotency check)
```

## 🔄 Refund Process

### Admin Workflow
1. Navigate to Admin → Finance Dashboard
2. Find transaction to refund
3. Click "Refund" button
4. Confirm in modal dialog
5. System processes refund via Paystack
6. Transaction marked as refunded
7. Audit log entry created

### Technical Details
- Paystack transaction IDs stored for reliable verification
- Fallback ID retrieval if not captured during initialization
- Comprehensive error messages for troubleshooting
- Full audit trail with timestamp, user, and action details

See [REFUND_IMPLEMENTATION.md](REFUND_IMPLEMENTATION.md) for detailed implementation.

## 🧪 Testing

### Test Files
- **RegistrationTest.php** - Registration and OTP flow
- **PaystackWebhookTest.php** - Webhook idempotency
- **RefundTest.php** - Refund scenarios

### Test Results
```
Tests: 6 passed (28 assertions)
- Registration: 2 tests
- Webhook: 1 test
- Refunds: 3 tests
```

### Running Specific Tests
```bash
php artisan test tests/Feature/RegistrationTest.php
php artisan test tests/Feature/PaystackWebhookTest.php --no-coverage
php artisan test tests/Feature/RefundTest.php --verbose
```

## 📊 Key Models

### Registration
- Stores user registration data
- Links to PricingItem and PaymentTransaction
- Tracks payment status and ticket generation

### PaymentTransaction
- Records all payment attempts
- Stores Paystack transaction ID and provider reference
- Maintains idempotency via webhook processing

### AuditLog
- Logs all admin actions (refunds, etc.)
- Records user IP, timestamp, and action details
- Provides compliance trail

### PricingItem
- Defines ticket tiers (Early Bird, Standard, etc.)
- Locked at registration time for consistency
- Supports multiple versions for pricing changes

## 🔐 Security Features

- ✅ CSRF protection via middleware
- ✅ HMAC signature verification for Webhooks
- ✅ Verified email requirement for admin access
- ✅ Hash-based ticket token generation
- ✅ Transaction idempotency handling
- ✅ Audit logging for compliance

## 📞 API Endpoints

### Public
- `POST /register` - User registration
- `POST /verify-otp` - OTP verification
- `GET /payment` - Payment page
- `POST /api/payment/initiate` - Initialize Paystack transaction
- `GET /payment/callback` - Paystack callback
- `POST /webhook/paystack` - Paystack webhook

### Admin (Authenticated)
- `GET /admin/finance` - Finance dashboard
- `POST /admin/finance/refund/{id}` - Process refund

## 🚢 Deployment

### Pre-Deployment Checklist
- [ ] Environment variables configured
- [ ] Database migrated
- [ ] Assets built for production
- [ ] Tests passing
- [ ] Paystack keys updated (production)
- [ ] Email provider configured
- [ ] Cache and queue drivers configured

### Deployment Steps
```bash
# SSH into production server
ssh user@server

# Pull latest code
git pull origin main

# Install dependencies
composer install --optimize-autoloader --no-dev

# Build assets
npm install && npm run build

# Run migrations
php artisan migrate --force

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:cache

# Restart queue worker
systemctl restart nsn-queue
```

## 📝 Documentation

- [REFUND_IMPLEMENTATION.md](REFUND_IMPLEMENTATION.md) - Refund system design
- [IMPLEMENTATION_COMPLETE.md](IMPLEMENTATION_COMPLETE.md) - Complete implementation summary
- [NSE-AGM-Portal_design_Architecture.md](NSE-AGM-Portal_design_Architecture.md) - System architecture
- [CLAUDE.md](CLAUDE.md) - Development notes

## 🛠️ Troubleshooting

### Paystack Integration Issues
- Verify sandbox keys in `.env`
- Check Authorization header format: `Bearer sk_test_xxx`
- Ensure callback_url includes https:// for production
- Verify HMAC signature calculation

### Database Issues
- Run `php artisan migrate:refresh --seed` to reset
- Check SQLite permissions: file should be writable
- Clear cache: `php artisan cache:clear`

### Email Delivery
- In dev, use `MAIL_MAILER=log` to view emails in logs
- In production, configure appropriate SMTP
- Check spam folder for OTP emails

### Payment Stuck in Pending
- Manual verification: `php artisan tinker` then `PaymentTransaction::find(id)->status`
- Webhook may have failed; check logs
- Admin can manually update or issue refund

## 📄 License

This project is proprietary and confidential. All rights reserved.

## 👥 Team

- **Backend:** Laravel + Paystack Integration
- **Frontend:** Blade Templates + Livewire
- **Styling:** Tailwind CSS
- **Testing:** Pest/PHPUnit

## 📞 Support

For issues or questions:
1. Check documentation in CLAUDE.md
2. Review test files for usage examples
3. Check application logs: `storage/logs/laravel.log`
4. Contact development team

---

**Last Updated:** February 18, 2026  
**Status:** MVP - Production Ready
