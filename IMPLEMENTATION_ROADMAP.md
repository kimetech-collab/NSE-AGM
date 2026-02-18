# IMPLEMENTATION ROADMAP — Phase 4 Build Plan

## Timeline: 13 Weeks (Q1 2026)
# IMPLEMENTATION ROADMAP — One-Week MVP Sprint

This file replaces the prior 13-week plan with an accelerated one-week MVP sprint focused on shipping a usable product: complete front-end, registration flows, payment processing, and admin dashboards. Other features (QR offline sync, certificates batch, extra admin tools) move to the post-MVP backlog.

## Goal (1 week)
- Ship MVP by end of the week consisting of:
	- Front-end: Homepage, Pricing, Register, Dashboard, Ticket view
	- Backend: Registration steps (OTP verification), Payment handoff + Paystack webhook processing
	- Admin: KPI dashboard, Registrations list, Finance (payment + refund initiation)
	- Basic security: MFA for admin, webhook verification, rate-limits, Cloudflare Turnstile

## High-level approach
- Parallelize work across small teams/engineers: Frontend, Backend (Auth & Payments), Admin UI, QA/DevOps.
- Prioritize end-to-end flows; stub or defer non-critical integrations.
- Use feature flags for any risky functionality.

## Day-by-day plan

Day 0 (Planning — 4 hours)
- Align team, split backlog, confirm owners for each task
- Ensure dev/staging infra available (DB, Redis, storage)
- Produce minimal acceptance criteria for each deliverable

Day 1 (Foundation & Minimal DB)
- Tasks:
	- Create minimal migrations: `users`, `registrations`, `pricing_versions`, `pricing_items`, `payment_transactions`, `audit_logs`, `system_settings`.
	- Seed pricing and sponsors (initial values)
	- Configure Redis and queue worker (Redis driver)
	- Configure .env secrets for Paystack sandbox
- Deliverable: Basic DB + seeded pricing; staging env ready

Day 2 (Frontend skeleton + Registration UI)
- Tasks:
	- Implement Tailwind/Blade templates for: Homepage, Pricing table, Register (3-step UI), Email OTP page, Dashboard (status tracker)
	- Add Alpine.js interactions: membership toggle, progress indicator, OTP UI
	- Wire form submissions to API endpoints (stubbed initially)
- Deliverable: End-to-end front-end flows wired to backend endpoints (stubs acceptable)

Day 3 (Registration backend + OTP)
- Tasks:
	- Implement `POST /register` to create registration record (validate, lock price by registration timestamp)
	- Implement OTP generation & email queueing (use queue, Postmark or Mailtrap for staging)
	- Implement `POST /email/verify` to verify OTP and mark registration.email_verified_at
	- Basic tests: registration + OTP success/failure
- Deliverable: Fully working registration with OTP gate that unlocks payment

Day 4 (Paystack integration & webhook)
- Tasks:
	- Implement Paystack handoff page and redirect flow on frontend
	- Implement `POST /paystack/webhook` with HMAC-SHA512 verification, timestamp check, and Paystack API verification (sandbox)
	- Update registration on successful payment (payment_status = Paid), create `payment_transactions` entry, dispatch PaymentConfirmed event
	- Generate QR token (hash stored) after payment and queue email send
- Deliverable: Payment flow end-to-end (sandbox) and idempotent webhook handling

Day 5 (Admin dashboards: KPIs + Registrations + Finance)
- Tasks:
	- Implement `/admin/dashboard` KPI cards (cached via Redis)
	- Implement `/admin/registrations` list with search and filters (name, email, payment_status)
	- Implement basic finance view with payment list and refund modal (refund initiation calls Paystack refund API; sandbox)
	- Add admin MFA enforcement (email OTP) middleware for admin routes
- Deliverable: Admin can view KPIs, registrations, and initiate refunds

Day 6 (Testing, polish, staging deploy)
- Tasks:
	- Run feature tests: registration flow, OTP, payment webhook idempotency, admin access + refund initiation
	- Manual UAT walkthrough: register, pay (sandbox), verify admin dashboard shows payment
	- Fix critical bugs and polish UI
	- Deploy to staging and run smoke tests
- Deliverable: Stable staging build of MVP

Day 7 (Release & Handover)
- Tasks:
	- Final smoke tests on staging
	- Create release notes, runbook for payment incidents
	- Deploy to production (DNS cutover optional per ops)
	- Hand off to support + client demo
- Deliverable: MVP released; client demo and handover

## Minimum scope cut (what we defer)
- Offline QR sync & tablet provisioning
- Certificate batch generation (post-event scheduling) — admin manual issuance may be stubbed
- Advanced reporting exports (complex PDFs)
- Full partitioning, archival policies (post-MVP)

## Acceptance Criteria (MVP)
- User can register (member or non-member), verify email via OTP, and proceed to payment
- Paystack sandbox webhook sets registration to `Paid` and generates ticket token
- User dashboard shows ticket with QR and download button
- Admin dashboard shows updated KPIs and can view registration details and initiate refunds
- Admin routes require MFA; webhooks validate signature and are idempotent

## Quick dev checklist (commands)
Set up local environment:
```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan queue:work --tries=3 &
npm install && npm run dev
```

Run tests:
```bash
./vendor/bin/pest   # or phpunit if project uses phpunit
```

## Post-MVP backlog (to schedule after week)
- QR offline sync and accreditation UX
- Certificate generation + public verification optimizations
- Audit log viewer filters + export
- Read replica query routing and ProxySQL tuning
- Partitioning & archival

## Notes
- This one-week plan assumes multi-engineer parallel work and staging infra already available. If team size is 1-2 engineers, adjust scope or add extra days.

---

**Document Version:** 2.1
**Last Updated:** February 2026
**Status:** MVP one-week sprint plan (ready to execute)
