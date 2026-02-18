# IMPLEMENTATION ROADMAP — Phase 4 Build Plan

## Timeline: 13 Weeks (Q1 2026)

---

## **Week 1-2: Foundation & Database**

### Tasks:
- [ ] Create all migrations (001-012)
- [ ] Seed pricing_versions (initial rates)
- [ ] Seed sponsors table (5-7 entries)
- [ ] Set up read replica + ProxySQL
- [ ] Configure Redis + Sentinel
- [ ] Generate Eloquent models + relationships
- [ ] Create Repository interfaces + implementations

### Deliverables:
- ✅ Database schema live on staging
- ✅ Model layer complete + tested

---

## **Week 2-3: Authentication & Security**

### Tasks:
- [ ] Implement Login/Logout (email + password)
- [ ] Email OTP verification (10 min expiry, 5 attempts/IP)
- [ ] MFA setup (admin only, Super Admin configurable)
- [ ] Password reset flow
- [ ] Middleware: CheckAdminMFA
- [ ] Middleware: VerifyPaystackSignature
- [ ] Rate limiting: 10 req/5min on /register, 3 req/min on /email/verify, 5 req/min on /verify/{id}

### Tests:
- [ ] Feature test: Login + OTP verification
- [ ] Feature test: MFA lockout (> 5 attempts)
- [ ] Feature test: Rate limiting enforcement

### Deliverables:
- ✅ Authentication complete
- ✅ MFA enforced on admin routes
- ✅ Rate limiting tested

---

## **Week 3-4: Registration Flow**

### Tasks:
- [ ] GET /pricing (render pricing matrix)
- [ ] GET /register (form + CAPTCHA)
- [ ] POST /register (RegistrationService::registerParticipant)
- [ ] GET /email/verify (OTP entry)
- [ ] POST /email/verify (verify OTP, unlock payment)
- [ ] POST /email/verify/resend (send new OTP)
- [ ] Event: RegistrationCreated → SendEmailVerificationOTPListener
- [ ] Event: RegistrationCreated → LogRegistrationAuditListener

### Tests:
- [ ] Feature test: Register NSE member (category validation)
- [ ] Feature test: Register non-member
- [ ] Feature test: Early bird pricing applied
- [ ] Unit test: RegistrationService

### Deliverables:
- ✅ 3-step registration complete
- ✅ Early bird pricing working
- ✅ OTP verification end-to-end

---

## **Week 4-5: Payment Processing**

### Tasks:
- [ ] GET /payment (Paystack handoff screen)
- [ ] POST /paystack/webhook (VerifyPaystackWebhookSignature middleware)
- [ ] PaymentService::processPaystackWebhook (idempotency + 3-layer verification)
- [ ] PaymentService::initiateRefund
- [ ] GET /admin/finance (KPI cards + revenue breakdown)
- [ ] POST /admin/finance/refund (refund initiation)
- [ ] Event: PaymentConfirmed → GenerateQRTicketListener
- [ ] Event: PaymentConfirmed → SendPaymentConfirmationEmailListener
- [ ] Event: PaymentConfirmed → InvalidateCacheListener
- [ ] Event: RefundInitiated → SendRefundEmailListener

### Tests:
- [ ] Unit test: Webhook signature verification (valid + invalid)
- [ ] Unit test: Payment idempotency (duplicate webhook)
- [ ] Feature test: Refund initiation
- [ ] Load test: 1,000 webhook events/min

### Deliverables:
- ✅ Payment flow complete
- ✅ Webhook verified + idempotent
- ✅ Refund functionality working

---

## **Week 5-6: QR & Accreditation**

### Tasks:
- [ ] GET /ticket (QR display + participant details)
- [ ] POST /ticket/download-pdf (PDF generation + serve)
- [ ] GET /admin/accreditation (QR scanner interface)
- [ ] POST /admin/accreditation/scan (QRService::validateToken + logScan)
- [ ] GET /admin/accreditation/offline-cache (download participant list)
- [ ] POST /admin/accreditation/sync-cache (upload scans after reconnect)
- [ ] QRService::generateToken (secure random)
- [ ] QRService::validateToken (lookup + status check)

### Tests:
- [ ] Feature test: QR scan (valid + invalid + already checked in)
- [ ] Feature test: PDF ticket download
- [ ] Feature test: Offline cache download + sync
- [ ] Load test: 500 QR scans/min (< 100ms response)

### Deliverables:
- ✅ QR generation + scanning working
- ✅ Offline accreditation functional
- ✅ PDF ticket generation tested

---

## **Week 6-7: Virtual Attendance**

### Tasks:
- [ ] GET /stream (platform selection + embed)
- [ ] POST /stream/start (session start log)
- [ ] POST /stream/heartbeat (60s interval, accumulate seconds)
- [ ] POST /stream/end (session end log)
- [ ] AttendanceService::recordVirtualHeartbeat (deduplication)
- [ ] AttendanceService::checkAttendanceThreshold (>= 600s → eligible)
- [ ] GET /admin/stream (configure YouTube, Zoom, Jitsi)
- [ ] Platform switching during event (admin action)

### Tests:
- [ ] Feature test: Virtual session start + heartbeat accumulation
- [ ] Feature test: Attendance threshold (>= 600s) triggers eligibility
- [ ] Feature test: Heartbeat deduplication (multiple tabs)
- [ ] Feature test: Platform switch mid-event

### Deliverables:
- ✅ Virtual attendance tracking working
- ✅ Certificate eligibility triggered automatically
- ✅ Platform configuration functional

---

## **Week 7-8: Certificates**

### Tasks:
- [ ] GET /certificate (eligibility status + download link)
- [ ] GET /certificate/download (serve PDF)
- [ ] GET /admin/certificates (list eligible/issued/revoked)
- [ ] POST /admin/certificates/generate-batch (manual 4 PM trigger)
- [ ] POST /admin/certificates/issue (override for ineligible)
- [ ] POST /admin/certificates/revoke (mark revoked + audit)
- [ ] GET /verify/{certificate_id} (public verification, rate-limited)
- [ ] CertificateService::generateCertificatesForEligible
- [ ] CertificateService::issueCertificate (override)
- [ ] CertificatePDFService::generate

### Tests:
- [ ] Unit test: Certificate PDF generation
- [ ] Feature test: Batch generation (after event end)
- [ ] Feature test: Public verification (rate limited)
- [ ] Feature test: Revoke + audit log

### Deliverables:
- ✅ Certificate generation working
- ✅ Manual override functional
- ✅ Public verification live

---

## **Week 8-9: Admin Dashboard & Reporting**

### Tasks:
- [ ] GET /admin/dashboard (KPI cards + daily registration chart)
- [ ] GET /admin/registrations (search/filter/edit)
- [ ] GET /admin/finance (revenue breakdown)
- [ ] GET /admin/audit (immutable audit log)
- [ ] POST /admin/*/export (CSV/PDF exports)
- [ ] Redis KPI caching (5 min TTL)
- [ ] Cache invalidation on payment/refund/certificate events
- [ ] Audit logging on all /admin/* routes

### Tests:
- [ ] Feature test: KPI dashboard (cache hit)
- [ ] Feature test: Registration search/filter
- [ ] Feature test: CSV/PDF export
- [ ] Feature test: Audit log search

### Deliverables:
- ✅ Admin dashboard complete
- ✅ Reporting functional
- ✅ Audit trail searchable

---

## **Week 9-10: Security & Performance Hardening**

### Tasks:
- [ ] Audit logging middleware (all /admin/* routes)
- [ ] N+1 query audit + eager loading fixes
- [ ] Cache configuration review
- [ ] Database connection pooling setup (pgBouncer)
- [ ] Query optimization (indexes verified)
- [ ] Cloudflare Turnstile integration on /register
- [ ] Rate limiting per-endpoint verification
- [ ] MFA lockout enforcement (5 attempts/IP, 30 min)
- [ ] Webhook signature verification tests

### Tests:
- [ ] Load test: 3,000 concurrent users
- [ ] Security test: Rate limiting
- [ ] Security test: Webhook verification (invalid signatures)
- [ ] Performance test: QR scan latency (< 100ms)

### Deliverables:
- ✅ Security audit complete
- ✅ Performance benchmarks met
- ✅ All mitigations in place

---

## **Week 10-11: Frontend & Polish**

### Tasks:
- [ ] Blade templates: Homepage, registration, dashboard, admin
- [ ] Alpine.js components: membership toggle, countdown, OTP input, QR overlay
- [ ] Tailwind CSS: responsive mobile-first design
- [ ] WCAG AA audit: Axe DevTools on all public pages
- [ ] Keyboard navigation testing
- [ ] Color contrast verification
- [ ] Mobile testing (iPhone SE, Android)
- [ ] Tablet testing (iPad)
- [ ] Desktop testing (1200px+)

### Tests:
- [ ] Axe DevTools: All pages green
- [ ] Keyboard navigation: Tab order correct
- [ ] Mobile responsiveness: All breakpoints
- [ ] Browser testing: Chrome, Safari, Firefox

### Deliverables:
- ✅ Frontend complete
- ✅ WCAG AA compliant
- ✅ Responsive on all devices

---

## **Week 11-12: Staging & UAT**

### Tasks:
- [ ] Deploy to staging (mirror production infra)
- [ ] Seed with 1,000+ test registrations
- [ ] Client UAT: Registration flow sign-off
- [ ] Client UAT: Admin dashboard sign-off
- [ ] Client review: Email templates (OTP, receipt, certificate)
- [ ] Client review: PDF tickets, certificates
- [ ] Paystack sandbox testing (success + failure scenarios)
- [ ] Load testing: 3,000 concurrent users
- [ ] Backup/restore testing
- [ ] Failover testing (manual Redis/DB failover)

### Tests:
- [ ] Load test: 3,000 concurrent, 15,000 req/min
- [ ] QR scan load test: 500 scans/min
- [ ] Webhook load test: 1,000 events/min
- [ ] Backup/restore: Full data integrity

### Deliverables:
- ✅ Client sign-off on all flows
- ✅ Paystack sandbox validated
- ✅ Load targets met
- ✅ Disaster recovery tested

---

## **Week 12-13: Production Deployment**

### Pre-Launch Checklist:
- [ ] SSL certificate issued + auto-renew configured
- [ ] Cloudflare WAF rules deployed (anti-DDoS)
- [ ] Database backups: Daily snapshots + PITR 30 days
- [ ] Monitoring: Sentry, New Relic, CloudWatch
- [ ] Runbooks: Incident response procedures
- [ ] Load balancer health checks verified
- [ ] Redis Sentinel verified
- [ ] Read replica verified
- [ ] On-call schedule: 24/7 support team

### Go-Live Tasks:
- [ ] DNS TTL reduced to 5 min (pre-cutover)
- [ ] Smoke tests run: registration, payment, QR, cert verification
- [ ] Monitor error rates (target: < 0.1%)
- [ ] Support team on-call during cutover
- [ ] Communications: Team + client notified
- [ ] Daily monitoring for 1 week post-launch

### Post-Launch:
- [ ] Error rate monitoring (< 0.1%)
- [ ] Payment webhook monitoring
- [ ] QR scan performance (< 100ms)
- [ ] Cache hit rates (> 90%)
- [ ] Database replica lag (< 1s)
- [ ] Daily backup verification

### Deliverables:
- ✅ Production live + stable
- ✅ All systems monitored
- ✅ Team trained + on-call

---

## Success Metrics

| Metric | Target | Sample Tool |
|---|---|---|
| Payment webhook latency | < 500ms | New Relic APM |
| QR scan latency | < 100ms | CloudWatch |
| Error rate | < 0.1% | Sentry |
| Cache hit rate | > 90% | Redis INFO |
| DB replica lag | < 1s | MySQL SHOW SLAVE STATUS |
| Certificate generation | < 5s per cert | Batch timing logs |
| Availability | 99.5% | Uptime monitoring |

---

## Risk Mitigation

| Risk | Mitigation |
|---|---|
| Payment webhook failures | 3-layer verification + idempotency + Paystack API double-check + alerting |
| QR scan bottleneck | Database indexing + caching + connection pooling + load balancing |
| Virtual attendance accuracy | Server-side accumulation + heartbeat deduplication + audit logging |
| Certificate eligibility disputes | Manual admin override + audit trail + versioning |
| Data loss | Daily automated backups + PITR + read replica + tested restore procedure |
| DDoS attacks | Cloudflare WAF + rate limiting + CAPTCHA throttling |
| Auth bypass | MFA on admins + session timeouts + audit logging on all actions |

---

## Go-Live: Late April 2026 (before April 28 Early Bird deadline)

## Event: November 1-4, 2026
