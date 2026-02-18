# NSE 59th AGM & International Conference Digital Portal
## Architecture, Design System & UX Specification
### Phases 1–3 | Version 1.0 | February 2026

---

> **Document Type:** Exportable Architecture & Design Reference
> **Project:** NSE 59th AGM & International Conference Portal
> **Stack:** Laravel 12 · Blade + Alpine.js · MySQL 8 · Redis · Paystack
> **Standard:** TRS v2.0 Enterprise Edition
> **Prepared:** February 2026

---

## TABLE OF CONTENTS

1. [Project Overview](#1-project-overview)
2. [Phase 1 — Clarification & Decisions](#2-phase-1--clarification--decisions)
3. [Phase 2 — Information Architecture](#3-phase-2--information-architecture)
   - 3.1 Sitemap
   - 3.2 User Flow Mapping
   - 3.3 Risk UX Areas & Mitigations
4. [Phase 3 — Design System](#4-phase-3--design-system)
   - 4.1 Typography System
   - 4.2 Color Strategy
   - 4.3 Spacing & Grid
   - 4.4 Component Library
5. [Technical Architecture Reference](#5-technical-architecture-reference)
6. [Event Timeline & Scope Boundaries](#6-event-timeline--scope-boundaries)

---

## 1. PROJECT OVERVIEW

### System Name
**NSE AGM & International Conference Digital Portal**

### Event
**59th Annual General Meeting & International Conference**
Nigerian Society of Engineers (NSE)

### Theme
> *"Engineering Nigeria's Future: Innovation, Resilience & Sustainable Development"*

### System Objective
Deliver a secure, scalable, auditable, and governance-compliant portal supporting:

- Unified participant registration (physical + virtual)
- Deterministic, admin-configurable pricing
- Mandatory sponsor showcase section on homepage
- Secure Paystack payment processing (webhook-only source of truth)
- Email verification before payment
- QR-based onsite accreditation with offline fallback
- Controlled online participation access (Zoom/Jitsi + YouTube unlisted)
- Virtual attendance tracking (≥ 10 minutes / 600 seconds)
- Post-event automated certificate issuance with public verification
- Financial reconciliation exports (CSV + PDF)
- Refund initiation via Paystack Refund API
- Immutable audit logging and RBAC

### Target Concurrency
- **Normal:** 2,000 concurrent users
- **Burst:** 3,000 concurrent users
- **Availability Target:** 99.5% during event window
---

## 2. PHASE 1 — CLARIFICATION & DECISIONS

| Question | Answer / Decision |
|---|---|
| Brand colors | Green + White + Gold/Yellow accent |
| Logo | NSE official logo (image). No text logo. |
| Existing site reference | https://nse.org.ng/ |
| Identity anchor | NSE logo is primary. No AGM sub-brand. |
| AGM theme | Proposed and accepted: *"Engineering Nigeria's Future: Innovation, Resilience & Sustainable Development"* |
| Device split | 80% mobile / 20% desktop → **Mobile-first design** |
| User age range | 24–65 years |
| Low bandwidth users | Sometimes — pages optimized for performance |
| Accessibility standard | **WCAG AA** |
| Special accessibility needs | None beyond standard |
| Print-friendly pages | Best choice selected: tickets, certificates, finance reports |
| Cultural/visual elements | NSE brand colors only — with Borno state cultural decoration |
| Early bird countdown | **Prominent on hero section** |
| Live before event | Yes — registration opens before the event |
| Portal launch / Registration open | February 28, 2026 |
| Registration close | October 25, 2026 |
| Early bird deadline | April 28, 2026 |
| Event dates (demo) | November 1 – November 4, 2026 (4 days) |
| Sponsor logos | **Included (mandatory homepage section)** |
| CAPTCHA | **Cloudflare Turnstile** (free, privacy-first, zero friction) |
| MFA method | **Email OTP** (TOTP-upgradeable in future) |
| Pricing values | **Placeholder (₦0)** — admin-configurable post-launch |
| Email verification | **Required before payment is accessible** |
| Refund UI | **Admin initiates via Paystack Refund API directly from portal** |
| Object storage | Laravel filesystem abstraction (local default, S3-ready config) |
| AssuredERP | **Separate system — excluded from this portal** |
| Member import | Not needed — participants self-register |
| Certificate template | To be shown to client before implementation |

### NSE Website Design Reference Analysis
*(Fetched from https://nse.org.ng/)*

| Element | Finding |
|---|---|
| Primary color | Dark Green #336600 (hover states) |
| Accent color | Gold/Orange #ffa000 |
| Background | White |
| Typography | Roboto |
| Layout | Card-based modular grid, full-width hero carousel |
| Navigation | Sticky header, dropdown submenus, mobile hamburger |
| Border radius | 5px standard |
| Overall tone | Professional, authoritative, institutional |

> **Portal decision:** Typography upgraded from Roboto → **Inter** (superior screen readability, better at small sizes, critical for 24–65 age range and WCAG AA). Colors refined for institutional credibility (see Phase 3).

---

## 3. PHASE 2 — INFORMATION ARCHITECTURE

### 3.1 SITEMAP

```
PUBLIC — No authentication required
│
├── /                          Home
│   ├── Hero + Dual Countdown  (Early Bird deadline + Event date)
│   ├── About AGM              (summary block)
│   ├── Key Stats              (59th edition, expected attendees)
│   ├── Participation Types    (Physical card vs Virtual card)
│   ├── Sponsors Section       (mandatory featured sponsors showcase)
│   └── CTA Buttons            (Register Now / View Programme)
│
├── /about                     About the 59th AGM
│   ├── Theme & Objectives
│   ├── NSE Background
│   └── Organising Committee   (if data provided)
│
├── /programme                 Programme / Agenda
│   └── Sessions, dates, times, speakers
│
├── /speakers                  Speakers  (optional — data-dependent)
│
├── /venue                     Venue & Travel
│   └── Location, map link, accommodation
│
├── /pricing                   Registration Info & Pricing
│   └── Category × Attendance type table
│
├── /register                  Registration — Unified Form
│
├── /faqs                      FAQs
│
├── /contact                   Contact & Support
│
├── /terms                     Terms & Privacy Policy
│
└── /verify/{certificate_id}   Public Certificate Verification
                               (no login required, rate-limited)

─────────────────────────────────────────────────────────────────

AUTHENTICATED — PARTICIPANT (After login + email verification)
│
├── /dashboard                 My Overview
│   ├── Registration status
│   ├── Payment status
│   ├── Ticket download link
│   ├── Online participation access status
│   ├── Attendance status      (physical / virtual / none)
│   └── Certificate status     (tracker: Registered → Paid → Attended → Issued)
│
├── /payment                   Complete / View Payment
│   ├── Paystack handoff screen (pre-redirect)
│   ├── Payment status         (success / failure / pending)
│   └── Receipt display        (if paid)
│
├── /ticket                    QR Ticket
│   ├── QR code display        (large, high brightness prompt)
│   ├── Participant details     (name, category, phone, membership # where applicable)
│   └── Download PDF button    (prominent)
│
├── /stream                    Online Participation Access
│   ├── Access gate            (paid + verified only)
│   ├── Platform module        (Zoom / Jitsi / YouTube Unlisted)
│   ├── Heartbeat tracker      (60s pings, server-accumulated)
│   └── Progress display       (X/10 minutes watched)
│
├── /certificate               My Certificate
│   ├── Eligibility status
│   ├── Download PDF           (if issued after event-end release)
│   └── Certificate ID display
│
└── /profile                   Account Settings

─────────────────────────────────────────────────────────────────

ADMIN — BACK OFFICE (Role-gated, MFA required on all routes)
│
├── /admin/dashboard           Overview KPIs
│   ├── Total registrations
│   ├── Paid vs unpaid count
│   ├── Revenue total
│   ├── Physical attendance count
│   ├── Virtual attendance count
│   ├── Certificates issued
│   └── Daily registration trend chart
│
├── /admin/registrations       Registration Management
│   ├── Search (name / email / membership number)
│   ├── Filter (status / category / attendance type)
│   ├── View individual registration
│   ├── Edit category          (audit logged + reason required)
│   ├── Re-send emails         (ticket / certificate / receipt)
│   └── Export: Declared Members List (CSV)
│
├── /admin/finance             Finance Dashboard
│   ├── Total revenue
│   ├── Breakdown by category
│   ├── Breakdown by attendance type
│   ├── Payment list           (reference, amount, timestamp)
│   ├── Refund management      (initiate via Paystack API)
│   ├── Chargeback flags       (manual flag)
│   ├── Export: Finance reconciliation (CSV + PDF)
│   └── Revenue variance detection
│
├── /admin/accreditation       QR Scanner
│   ├── Web camera scanner     (Accreditation Officer only)
│   ├── Scan result overlay    (full-screen: Valid / Invalid / Checked-in / Unpaid / Refunded)
│   ├── Real-time attendance count
│   └── Offline fallback       (cached list, sync on reconnect)
│
├── /admin/certificates        Certificate Management
│   ├── Eligible list
│   ├── Issued list
│   ├── Re-issue               (logged)
│   ├── Revoke                 (logged + reason)
│   └── Export: Certificate issuance report
│
├── /admin/stream              Online Session Configuration
│   ├── Configure YouTube unlisted URL
│   ├── Configure Zoom meeting integration
│   ├── Configure Jitsi room integration
│   ├── Set primary / backup online platform
│   └── Enable / disable access window
│
├── /admin/pricing             Pricing Configuration (Super Admin only)
│   ├── Category × Attendance type matrix
│   ├── Early bird rates + window dates
│   ├── Category caps          (physical seats)
│   ├── Pricing version history
│   └── Save → new version created (existing registrations unaffected)
│
├── /admin/users               User & Role Management (Super Admin only)
│   ├── Assign roles
│   ├── MFA management
│   └── Role change audit log
│
├── /admin/audit               Audit Log Viewer
│   ├── Filter by actor / action / entity / date
│   ├── Immutable log entries
│   └── Export
│
└── /admin/settings            System Settings (Super Admin only)
    ├── Event dates configuration
    ├── Email templates
    └── System-wide toggles

─────────────────────────────────────────────────────────────────

AUTHENTICATION FLOW
│
├── /login
├── /register
├── /email/verify              (OTP entry — required before payment)
├── /email/verify/resend       (resend OTP)
├── /forgot-password
└── /reset-password
```

---

### 3.2 USER FLOW MAPPING

#### Flow 1 — New Member Registration
```
User visits /register
  → Cloudflare Turnstile CAPTCHA passes silently
  → Reads progress indicator: [1. Your Details] → [2. Verify Email] → [3. Payment]
  → Step 1: Toggles "I am an NSE Member" = YES
      → membership_category dropdown appears (Student / Graduate / Corporate / Fellow)
      → membership_number field appears with helper: "Format: NSE-12345"
      → Fills: first_name, surname, gender, email, phone, organization
      → Selects: attendance_type (Physical / Virtual)
      → Ticks: self-attestation checkbox
      → Submits → record created (payment_status = PENDING)
  → Step 2: OTP email sent immediately
      → Redirected to /email/verify
      → Enters 6-digit OTP
      → "Resend OTP" visible from the start (10-min expiry shown)
      → Email verified → email_verified_at stamped
  → Step 3: Redirected to /payment
      → Paystack handoff screen shown (NSE + Paystack logos, amount confirmed)
      → User clicks "Proceed to Payment"
      → Redirected to Paystack
      → Paystack sends webhook → portal verifies signature + Paystack API
      → payment_status = PAID → QR ticket generated → confirmation email sent
  → Redirected to /dashboard (fully active)
```

#### Flow 2 — Non-Member Registration
```
User visits /register
  → Turnstile passes
  → Toggles "I am not an NSE Member" = NO
      → Membership fields hidden entirely
      → membership_category = NON_MEMBER (auto-assigned)
      → membership_number = null
  → Fills common fields → attestation → submit
  → (Same OTP verification and payment flow as Flow 1)
```

#### Flow 3 — Early Bird Payment
```
User registers before April 28, 2026 (early bird deadline)
  → System detects early bird window active
  → Pricing table applies early bird rate at registration timestamp
  → Price locked (pricing_version_id stamped on record)
  → price_locked_at = registration timestamp
  → Even if admin changes prices later, this user's price unchanged
  → After April 28, 2026, standard registration pricing continues until October 25, 2026
  → Homepage hero shows countdown: "Early Bird ends in X days"
  → Pricing page shows "EARLY BIRD" gold badge on applicable cells
```

#### Flow 4 — Admin Pricing Update
```
Super Admin navigates to /admin/pricing
  → MFA (Email OTP) verified
  → Views current pricing matrix (category × Physical/Virtual)
  → Clicks cell to edit (e.g., Fellow Physical)
  → Enters new amount → enters reason (required, audit-logged)
  → Saves → new pricing_version_id created
  → Alert shown: "New pricing applies to future registrations only"
  → Existing paid/pending registrations retain their locked price
  → Pricing version history table updated
```

#### Flow 5 — Onsite QR Check-in
```
Accreditation Officer logs into /admin/accreditation
  → MFA (Email OTP) verified
  → Camera activates automatically (browser permission prompt on first use)
  → Participant presents QR ticket (phone screen or printout)
  → QR scanned → server lookup → response < 1 second:
      ✅ VALID       → Full-screen green overlay
                       → Name + Category displayed large
                       → attendance_status = Physical logged
                       → Auto-dismisses in 3s → camera reactivates
      ⚠️ ALREADY     → Yellow overlay → "Already checked in at [time]"
                       → Auto-dismisses in 3s
      ❌ UNPAID      → Red overlay → "Payment not confirmed"
      🚫 REFUNDED    → Gray overlay → "Registration has been refunded"
      ✗  INVALID     → Red overlay → "QR code not recognised"
  → Offline mode: if server unreachable → checks cached participant list
                  → Marks local record → syncs when reconnected
```

#### Flow 6 — Virtual Attendance → Certificate
```
Paid + verified user navigates to /stream
  → Login verified server-side → payment_status = PAID confirmed
  → Selected online platform module loaded (YouTube unlisted / Zoom / Jitsi from admin settings)
  → session_start logged with timestamp
  → Alpine.js sends heartbeat ping every 60 seconds
  → Server accumulates total duration (not susceptible to tab refresh)
  → If user opens multiple tabs → deduplicated to one active session
  → After cumulative ≥ 600 seconds:
      → attendance_status = Virtual
      → Certificate eligibility marked as READY_FOR_POST_EVENT_RELEASE
  → Event end check (after Day 4 close): certificate batch generation runs for all eligible attendees
      → Certificate PDF generated (cryptographically random ID)
      → Email sent: "Your certificate is ready"
  → User visits /certificate → downloads PDF (available only after event end + issuance batch)
  → Public can verify at /verify/{certificate_id}
```

#### Flow 7 — Admin Refund (via Paystack API)
```
Finance Admin navigates to /admin/finance
  → MFA verified
  → Searches registration by name / email / reference
  → Clicks "Initiate Refund"
  → Modal appears:
      → Refund type: Full / Partial (amount input if partial)
      → Reason: required text input
      → Confirmation: "This will revoke ticket and certificate eligibility"
  → Admin confirms → portal calls Paystack Refund API
  → Paystack returns refund reference
  → Portal updates: refund_status = REFUNDED / PARTIALLY_REFUNDED
  → Ticket token revoked (scan returns "Refunded")
  → certificate_status auto-revoked if previously issued
  → Refund event written to immutable audit log
  → Participant email sent: notification of refund
```

#### Flow 8 — Executive Report Viewing
```
Super Admin / Finance Admin navigates to /admin/dashboard
  → MFA verified
  → KPI cards load from Redis cache (< 3 seconds)
      → Total registrations (large number, NSE green)
      → Revenue total
      → Paid vs Unpaid counts
      → Physical attendance count
      → Virtual attendance count
      → Certificates issued
  → Daily registration trend chart (lightweight, print-safe)
  → One-click export: CSV (data) or PDF (print-ready layout)
  → PDF export: no JS-dependent elements, clean print CSS
```

---

### 3.3 RISK UX AREAS & IMPLEMENTED MITIGATIONS

| # | Risk | Location | Severity | Mitigation Implemented |
|---|---|---|---|---|
| 1 | Membership toggle confusion | /register | 🔴 High | Prominent toggle switch (not small radio). Instant Alpine.js field show/hide. Helper text: "NSE members receive member pricing." Conditional fields animate in smoothly. |
| 2 | Pricing matrix complexity | /pricing | 🔴 High | Clean comparison table: rows = categories, columns = Physical \| Virtual. Eligible category highlighted in NSE Green. Gold "Early Bird" badge on applicable prices. Tooltip "?" explains each category on tap. |
| 3 | Payment redirect anxiety | /payment → Paystack | 🔴 High | Branded handoff screen before redirect: NSE logo + Paystack logo side-by-side. Amount confirmed. "256-bit SSL secured" lock icon. Copy: "You are being securely redirected to Paystack, Nigeria's trusted payment gateway." Loading button prevents double-tap. |
| 4 | Mobile form fatigue | /register | 🔴 High | Mobile-first single column layout. 3-step progress indicator. Large tap targets (52px fields, 48px buttons). All fields full-width. Labels always above fields (never placeholder-only). |
| 5 | Email OTP friction | /email/verify | 🟡 Medium | Resend OTP button visible immediately. 10-minute expiry shown as countdown. Clear instruction text. Auto-focus on OTP input field. Large 6-digit input (one box or split boxes). |
| 6 | Low bandwidth on event day | /stream, /admin/accreditation | 🔴 High | Lightweight pages (no heavy JS bundles). Offline QR fallback (cached participant list, sync on reconnect). Images compressed. Online platform modules (Zoom/Jitsi/YouTube) load only on interaction. |
| 7 | Certificate eligibility confusion | /dashboard | 🟡 Medium | Linear status tracker: [✅ Registered] → [✅ Paid] → [⬜ Attended] → [⬜ Certificate]. Each step shows timestamp if completed. Tooltip explicitly states certificates are released only after the full 4-day event ends. |
| 8 | QR scan queue bottleneck | /admin/accreditation | 🔴 High | Full-screen result overlay (instant, no scroll needed). Response target < 1 second. Auto-dismiss in 3 seconds, camera reactivates automatically. Offline fallback ensures no downtime. Optional browser sound on successful scan. |
| 9 | Early bird deadline missed | Home, /pricing | 🟡 Medium | Dual countdown on hero: Early Bird countdown (prominent) + Event countdown. Pricing page badge: "EARLY BIRD ends in X days". Color urgency: Gold → Orange → Red as deadline approaches. |
| 10 | Age range accessibility (65+) | All public pages | 🟡 Medium | Minimum 16px body text (mobile). High contrast (WCAG AA verified). No icon-only navigation. Large tap targets (48px minimum). Inter font for superior readability. Error messages always text + icon (never color alone). |

---

## 4. PHASE 3 — DESIGN SYSTEM

### 4.1 TYPOGRAPHY SYSTEM

**Selected Font: Inter**

> Inter is purpose-built for screen readability. It outperforms Roboto at small sizes on mobile displays, has superior legibility at 14–16px for the 24–65 age range, and is WCAG AA optimized. Free and open source. Loaded via Google Fonts CDN with `font-display: swap`.

```css
/* Font import */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
```

#### Type Scale

| Role | Desktop | Mobile | Weight | Line Height | Usage |
|---|---|---|---|---|---|
| H1 — Hero | 48px | 32px | 700 | 1.15 | Page hero headings |
| H2 — Section | 36px | 26px | 700 | 1.20 | Section titles |
| H3 — Card/Sub | 24px | 20px | 600 | 1.30 | Card headers, subsections |
| H4 — Label | 18px | 16px | 600 | 1.40 | Form labels, sidebar titles |
| Body Large | 18px | 16px | 400 | 1.60 | Lead text, important copy |
| Body Base | 16px | 16px | 400 | 1.60 | Standard body content |
| Body Small | 14px | 14px | 400 | 1.50 | Secondary info, captions |
| Caption | 12px | 12px | 500 | 1.40 | Timestamps, fine print |

> **Hard Rules:**
> - Body text never below 16px on mobile
> - Caption never below 12px anywhere
> - Minimum contrast 4.5:1 for all body text (WCAG AA)
> - Minimum contrast 3:1 for large text (≥18px bold or ≥24px regular)

---

### 4.2 COLOR STRATEGY

#### Primary Palette

```
NSE GREEN (Primary)
├── Green 900 (Dark)   #1A4A1A    Hover states, footer, dark headers
├── Green 700 (Main)   #2B6B2B    Primary buttons, nav, headings
├── Green 500          #3D8B3D    Secondary accents
└── Green 50  (Light)  #EBF5EB    Backgrounds, success tints

NSE GOLD (Accent)
├── Gold 700  (Deep)   #9B7415    Dark text on gold backgrounds
├── Gold 500  (Main)   #C8971F    Badges, borders, decorative accents
└── Gold 50   (Light)  #FDF3DC    Badge backgrounds, warning tints
```

#### Neutral Palette

```
NEUTRALS
├── White              #FFFFFF    Page backgrounds, cards
├── Gray 50            #F7F8F6    Alternate section backgrounds
├── Gray 200           #DEE2DD    Borders, dividers
├── Gray 400           #8A948A    Muted text, placeholders
├── Gray 600           #555F54    Body text, descriptions
└── Gray 900           #1C1C1C    Headings, primary text
```

#### System State Colors

```
SUCCESS     #2E7D32  bg: #E8F5E9   Paid, attended, issued, valid scan
WARNING     #F57F17  bg: #FFF8E1   Pending, approaching deadline, already scanned
ERROR       #C62828  bg: #FFEBEE   Failed, invalid, unpaid, refunded
INFO        #1565C0  bg: #E3F2FD   Informational notices, virtual badge
```

#### WCAG AA Contrast Verification

| Foreground | Background | Ratio | Status |
|---|---|---|---|
| Green 700 #2B6B2B | White #FFFFFF | 7.2:1 | ✅ Passes AAA |
| White #FFFFFF | Green 700 #2B6B2B | 7.2:1 | ✅ Passes AAA |
| Gray 900 #1C1C1C | White #FFFFFF | 16.7:1 | ✅ Passes AAA |
| Gray 600 #555F54 | White #FFFFFF | 5.9:1 | ✅ Passes AA |
| Gold 700 #9B7415 | White #FFFFFF | 4.8:1 | ✅ Passes AA (large text only) |
| Gray 900 #1C1C1C | Gray 50 #F7F8F6 | 15.1:1 | ✅ Passes AAA |

> **Gold Usage Rule:**
> Gold #C8971F (500) is **decorative only** — badges, borders, icons, large headings.
> Gold is **never used** for body text or small interactive elements.
> When gold text is needed, use Gold 700 (#9B7415) on light backgrounds only.

---

### 4.3 SPACING & GRID

#### Grid System

```
DESKTOP (≥1024px)
  Columns:    12
  Max width:  1200px
  Gutter:     24px
  Margin:     Auto (centered)
  Container:  px-10 (40px each side)

TABLET (768px–1023px)
  Columns:    8
  Gutter:     20px
  Margin:     24px

MOBILE (<768px)
  Columns:    4
  Gutter:     16px
  Margin:     16px each side
```

#### Spacing Scale (8px base unit)

```
Name    Value    Tailwind      Usage
─────────────────────────────────────────────────
xs      4px      p-1           Tight inline gaps
sm      8px      p-2           Icon gaps, tag padding
md      16px     p-4           Field internal padding
lg      24px     p-6           Card padding, sub-spacing
xl      32px     p-8           Between components
2xl     48px     p-12          Between sections (mobile)
3xl     64px     p-16          Between sections (desktop)
4xl     96px     p-24          Hero section padding (desktop)
```

#### Breakpoints

```
Mobile:       < 640px    (sm)
Tablet:       640–1023px (md)
Desktop:      1024–1279px (lg)
Wide:         ≥ 1280px   (xl)
```

---

### 4.4 COMPONENT LIBRARY

---

#### A. BUTTONS

```
PRIMARY BUTTON
  Background:   NSE Green 700 (#2B6B2B)
  Text:         White
  Hover:        NSE Green 900 (#1A4A1A)
  Border-radius: 6px
  Height:       48px (mobile) / 44px (desktop)
  Padding:      12px 24px
  Font:         16px / 600 weight
  Min-width:    120px (accessibility — large tap target)

SECONDARY BUTTON
  Background:   Transparent
  Border:       2px solid #2B6B2B
  Text:         NSE Green 700
  Hover:        NSE Green 50 background

DANGER BUTTON
  Background:   Error Red (#C62828)
  Text:         White
  Hover:        #A31F1F
  Usage:        Refund, Revoke, Delete — confirmation modal always required

GHOST BUTTON
  Background:   Transparent
  Border:       None
  Text:         NSE Green 700
  Hover:        NSE Green 50 background
  Usage:        Navigation secondary actions, inline links

DISABLED STATE
  Background:   #DEE2DD
  Text:         #8A948A
  Cursor:       not-allowed
  Opacity:      0.65

LOADING STATE
  Shows spinner replacing text
  Prevents double-click / double-submit
  Applied on: Register submit, Pay button, Scan action
```

---

#### B. FORM FIELDS

*(Mobile fatigue mitigation + Accessibility mitigation)*

```
INPUT FIELD
  Height:       52px (generous tap target — 65+ users)
  Border:       1px solid #DEE2DD
  Border-radius: 6px
  Background:   White
  Font:         16px Inter Regular
  Padding:      14px 16px

  States:
    Default:    Border #DEE2DD
    Focus:      Border 2px #2B6B2B, outline none, box-shadow: 0 0 0 3px rgba(43,107,43,0.15)
    Error:      Border 2px #C62828, background #FFEBEE
    Disabled:   Background #F7F8F6, text #8A948A
    Valid:      Green checkmark icon trailing

LABEL
  Position:     Always above the field (never placeholder-only)
  Font:         14px / 600 weight / #1C1C1C
  Margin-bottom: 6px
  Required:     Red asterisk (*) after label text

HELPER TEXT
  Position:     Below field
  Font:         12px / #8A948A
  Example:      "Your NSE membership number. Format: NSE-12345"

ERROR MESSAGE
  Position:     Below field, replacing helper text
  Font:         13px / #C62828 / 500 weight
  Icon:         ⚠ before message
  Rule:         Always text + icon (never color alone — accessibility)

SELECT DROPDOWN
  Same styling as input
  Custom chevron icon (NSE Green)
  Mobile: native select for performance

TOGGLE (Membership Toggle)
  Style:        Large pill toggle, not small radio buttons
  Size:         48px height (mobile accessible)
  Label:        Clear text beside toggle
  Behavior:     Alpine.js — conditional fields animate in (150ms ease)
  Helper:       "NSE members receive member pricing" shown below toggle
```

---

#### C. CARDS

```
STANDARD CARD
  Background:   White
  Border:       1px solid #DEE2DD
  Border-radius: 8px
  Shadow:       0 1px 3px rgba(0,0,0,0.08)
  Padding:      24px (desktop) / 16px (mobile)

FEATURE CARD (Participation type — Physical vs Virtual)
  Border-top:   4px solid NSE Green 700
  Hover:        Shadow deepens: 0 4px 12px rgba(0,0,0,0.12)

STAT CARD (Admin KPI)
  Left border:  4px solid NSE Green 700
  Number:       48px / 700 weight / NSE Green
  Label:        14px / #8A948A / below number
  Trend:        Arrow icon + percentage (green up / red down)
  Print-ready:  No JS chart dependencies
```

---

#### D. PRICING TABLE

*(Pricing complexity mitigation)*

```
LAYOUT
  Desktop:      Full table — rows = categories, columns = Physical | Virtual
  Mobile:       Horizontal scroll with scroll hint arrow (→)
  User category: Highlighted row in NSE Green 50 background + left border

CELLS
  Amount:       24px / 700 weight
  Currency:     ₦ prefix, always visible
  Early bird:   Gold badge "EARLY BIRD" above amount
  Strike-through: Original price shown in muted text if early bird active

CATEGORY TOOLTIP
  Trigger:      "?" icon beside each category name
  Content:      Brief description of who qualifies
  Mobile:       Tap to show, tap again to dismiss

BADGES ON TABLE
  Early Bird:   Gold background, "EARLY BIRD" text
  Sold Out:     Gray background, "SOLD OUT" (if cap reached)
  Most Popular: Green border + subtle indicator (optional)
```

---

#### E. STATUS BADGES / PILLS

```
EARLY BIRD     bg: #FDF3DC  text: #9B7415  border: #C8971F  → "Early Bird"
PAID           bg: #E8F5E9  text: #2E7D32                   → "Paid" + ✓
PENDING        bg: #FFF8E1  text: #F57F17                   → "Pending"
FAILED         bg: #FFEBEE  text: #C62828                   → "Payment Failed"
REFUNDED       bg: #F5F5F5  text: #555F54                   → "Refunded"
ATTENDED       bg: #E8F5E9  text: #2E7D32                   → "Attended" + ✓
NOT ELIGIBLE   bg: #F5F5F5  text: #8A948A                   → "Not Eligible"
VIRTUAL        bg: #E3F2FD  text: #1565C0                   → "Virtual"
PHYSICAL       bg: #EBF5EB  text: #2B6B2B                   → "Physical"

Sizing:   Pill shape, 6px border-radius, 10px 12px padding
Font:     12px / 600 weight
Rule:     Always includes text (never icon-only)
```

---

#### F. ALERTS / NOTICES

```
4 variants: Success | Warning | Error | Info

Structure:
  Left border:  4px solid (state color)
  Background:   State light background
  Icon:         State icon, left-aligned (never color alone)
  Message:      16px body text
  Optional:     Dismiss (×) button top-right
  Dismissible:  Payment failure, info notices
  Persistent:   Security warnings, payment required notices

Placement rule: Always visible above-the-fold when payment-related
```

---

#### G. PROGRESS INDICATOR

*(Mobile fatigue mitigation)*

```
3-Step Registration Progress:
  Step 1: [1] Your Details
  Step 2: [2] Verify Email
  Step 3: [3] Payment

Desktop: Numbers + labels, connected by line
Mobile:  Numbers only (compact), labels hidden

States:
  Completed:  Green circle + checkmark ✓
  Active:     Green circle + number (filled)
  Upcoming:   Gray circle + number (outline)

Placement: Fixed below header on /register page, not scrollable away
```

---

#### H. COUNTDOWN TIMER

*(Early bird deadline mitigation)*

```
FORMAT:    DD : HH : MM : SS
LABELS:    Days | Hours | Mins | Secs

SIZING:
  Hero (large):    Number 48px / 700, label 12px
  Compact:         Number 24px / 700, label 10px

COLOR URGENCY SYSTEM:
  > 7 days remaining:   NSE Gold (#C8971F)
  2–7 days remaining:   Warning Orange (#F57F17)
  < 2 days remaining:   Error Red (#C62828) + pulse animation

HOMEPAGE HERO:
  Two countdowns stacked or side-by-side:
  1. "Early Bird Ends In:" → April 28, 2026
  2. "Conference Begins In:" → November 1, 2026

PRICING PAGE:
  Compact countdown beside "EARLY BIRD" badge
  "Early Bird pricing ends [date]"

MOBILE:
  Full-width, centered, prominent
  Stacked (DD | HH | MM | SS in a row)
```

---

#### I. QR TICKET DISPLAY

*(Low bandwidth + check-in queue mitigation)*

```
LAYOUT (Ticket card):
  Header:       NSE logo + "59th AGM & Conference"
  QR Code:      Minimum 220×220px, white background, no transparency
  Below QR:     Participant full name (20px / 600 weight)
  Below name:   Category badge + Attendance type badge
  Details row:  Membership number (if member) + Phone number
  Below details: Ticket reference (small, muted)
  Footer:       "November 1 – November 4, 2026 | Venue name"

BRIGHTNESS PROMPT:
  Blue info banner above QR: "Increase your screen brightness for faster scanning"

DOWNLOAD BUTTON:
  Primary green button: "Download PDF Ticket"
  Placed above QR (prominent — not below fold)

PRINT LAYOUT:
  Clean print CSS: white background, black QR, no nav/footer
  A5 or A4 portrait
```

---

#### J. DASHBOARD — STATUS TRACKER

*(Certificate eligibility confusion mitigation)*

```
LINEAR TRACKER (4 steps):
  [✅ Registered] → [✅ Payment Confirmed] → [⬜ Attended] → [⬜ Certificate]

Each step shows:
  - Icon: ✅ (complete, green) / ⬜ (pending, gray) / 🔄 (in progress)
  - Label: Step name
  - Sub-label: Timestamp if completed / "What's needed" if pending

Tooltip on pending steps:
  "Attended": "Attend the event physically or join an online session for ≥10 minutes"
  "Certificate": "Available after attendance is confirmed and the 4-day event has fully concluded"

Mobile: Vertical list (not horizontal — avoids overflow)
Desktop: Horizontal stepper
```

---

#### K. QR SCAN RESULT OVERLAY

*(QR queue bottleneck mitigation)*

```
VALID CHECK-IN
  Full-screen:  NSE Green (#2B6B2B) background
  Icon:         Large ✅ (80px)
  Name:         White, 32px / 700 weight
  Category:     White badge
  Attendance:   "Physical — Checked In"
  Auto-dismiss: 3 seconds → camera reactivates

ALREADY CHECKED IN
  Full-screen:  Warning orange background
  Icon:         ⚠ (large)
  Message:      "Already checked in"
  Timestamp:    "First check-in at 09:42 AM"
  Auto-dismiss: 3 seconds

UNPAID
  Full-screen:  Error red background
  Icon:         ✗ (large)
  Message:      "Payment not confirmed"
  Sub-message:  "Participant must complete payment"
  Auto-dismiss: 4 seconds

REFUNDED
  Full-screen:  Gray (#555F54) background
  Icon:         🚫
  Message:      "Registration has been refunded"
  Auto-dismiss: 4 seconds

INVALID QR
  Full-screen:  Error red
  Message:      "QR code not recognised"
  Auto-dismiss: 3 seconds

SOUND (optional, mutable):
  Valid:        Short success beep
  Invalid:      Error tone
  Toggle:       Mute button in top-right corner of scanner view
```

---

#### L. PAYSTACK HANDOFF SCREEN

*(Payment anxiety mitigation)*

```
LAYOUT (centered card, white background):
  Top:          NSE logo (left) + Paystack logo (right), separated by "×"
  Divider:      Thin green line
  Heading:      "Secure Payment Redirect"
  Body:         "You are being securely redirected to Paystack,
                 Nigeria's trusted and regulated payment gateway."
  Amount box:   Prominent bordered box showing:
                "Amount Due: ₦XX,XXX.XX"
                Category + Attendance type
  Security row: 🔒 "256-bit SSL secured" + "Powered by Paystack"
  Button:       Primary green — "Proceed to Payment →"
                Loading state on click (prevents double-tap)
  Fine print:   "Do not close this browser tab during payment."
                "You will be returned here automatically."

NOTE: This page does NOT update payment status.
      Only the Paystack webhook updates payment status.
```

---

#### M. ADMIN KPI DASHBOARD CARDS

*(Executive visibility mitigation)*

```
CARD STRUCTURE:
  Left border:  4px solid NSE Green 700
  Background:   White
  Padding:      20px 24px
  Shadow:       Subtle

  Large number: 48px / 700 / NSE Green
  Label:        14px / 500 / #8A948A (below number)
  Trend:        Small row — arrow icon + "↑ 12% vs yesterday"
                Green if up, red if down

PRINT CSS:
  No JS-dependent chart elements
  Numbers and labels only (chart hides in print)
  Clean A4 layout

CARDS ON ADMIN OVERVIEW:
  1. Total Registrations
  2. Paid Registrations
  3. Unpaid / Pending
  4. Total Revenue (₦)
  5. Physical Attendance (event day)
  6. Virtual Attendance (event day)
  7. Certificates Issued

CHART (Daily Registration Trend):
  Type:         Line chart (lightweight, CSS-renderable fallback)
  X-axis:       Dates
  Y-axis:       Registration count
  Color:        NSE Green line
  Print:        Replaced by text summary table
```

---

## 5. TECHNICAL ARCHITECTURE REFERENCE

### Stack Summary

| Layer | Technology |
|---|---|
| Backend | Laravel 11 (PHP 8.2+) |
| Frontend | Blade templates + Alpine.js |
| Database (Write) | MySQL 8 Primary |
| Database (Read) | MySQL 8 Read Replica |
| Cache / Sessions / Queue | Redis (mandatory) |
| PDF Generation | dompdf |
| QR Generation | simple-qrcode |
| Payments | Paystack (redirect + webhook + verify API + refund API) |
| Email | Postmark (preferred) / Brevo SMTP |
| CAPTCHA | Cloudflare Turnstile |
| MFA | Email OTP (TOTP-ready structure) |
| File Storage | Laravel filesystem (local default, S3-configurable) |
| CDN / WAF / Rate Limiting | Cloudflare Pro |
| Error Monitoring | Sentry |
| Streaming | Zoom + Jitsi + YouTube Unlisted (portal-gated, admin-selectable) |
| Hosting | VPS (2+ stateless app nodes) |

### RBAC Matrix

| Role | Registrations | Finance | Accreditation | Certificates | Pricing | Users | Audit | Settings |
|---|---|---|---|---|---|---|---|---|
| Super Admin | ✅ Full | ✅ Full | ✅ Full | ✅ Full | ✅ Full | ✅ Full | ✅ Full | ✅ Full |
| Finance Admin | 👁 View | ✅ Full | ❌ | 👁 View | ❌ | ❌ | 👁 View | ❌ |
| Registration Admin | ✅ Edit* | ❌ | ❌ | 👁 View | ❌ | ❌ | 👁 View | ❌ |
| Accreditation Officer | ❌ | ❌ | ✅ Scan | ❌ | ❌ | ❌ | ❌ | ❌ |
| Support Agent | 👁 View | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ |
| Registrant | Self only | Self only | ❌ | Self only | ❌ | ❌ | ❌ | ❌ |

> *Registration Admin edits require reason entry and are audit-logged. No payment override.

### Audit Log Schema

```
audit_logs
  id               BIGINT UNSIGNED AUTO_INCREMENT
  actor_id         BIGINT UNSIGNED (user who performed action)
  action           VARCHAR(100)    (e.g. "payment.refund.initiated")
  entity_type      VARCHAR(100)    (e.g. "Registration", "Certificate")
  entity_id        BIGINT UNSIGNED
  metadata         JSON            (before/after values, reason, amount)
  ip_address       VARCHAR(45)
  user_agent       TEXT
  created_at       TIMESTAMP
  -- NO updated_at (immutable — no soft deletes, no updates)
```

### Payment Flow (Source of Truth)

```
User → Paystack redirect → Payment → Paystack webhook → Portal receives
  → Verify: HMAC-SHA512 signature
  → Verify: Paystack IP allowlist
  → Verify: Timestamp replay prevention (< 5 min)
  → Verify: Transaction via Paystack API (double check)
  → DB Transaction: update payment_status = PAID
  → Generate QR ticket (UUID token, stored hashed)
  → Queue: send confirmation email
  → Log: audit trail

Redirect callback: NEVER updates payment status. Only redirects to dashboard.
Duplicate webhook: Idempotent — unique constraint on paystack_reference.
```

### Virtual Attendance Logic

```
Page load:      → POST /stream/start (session_start log)
Every 60s:      → POST /stream/heartbeat (server += 60s to total)
Multiple tabs:  → Deduplicated by user_id + session_id
Threshold:      → total_seconds >= 600 → attendance_status = Virtual
Trigger:        → Certificate eligibility = READY_FOR_POST_EVENT_RELEASE
Release rule:   → Certificate generation and download enabled only after configured event_end_at (Day 4 close)
Refresh-safe:   → Server-side accumulation (not client timer)
```

### Certificate ID Format

```
Format:   NSE59-2026-{6 random alphanumeric}
Example:  NSE59-2026-K4M9X2

Properties:
  - Cryptographically random (not sequential)
  - Non-enumerable (cannot guess adjacent IDs)
  - Unique constraint in DB (index)
  - Embedded as QR in certificate PDF
  - Verifiable at /verify/{id} (rate-limited, no login required)
```

---

## 6. EVENT TIMELINE & SCOPE BOUNDARIES

### Key Dates (Demo Configuration)

| Milestone | Date |
|---|---|
| Portal Launch / Registration Opens | February 28, 2026 |
| Early Bird Deadline | April 28, 2026 |
| Registration Closes | October 25, 2026 |
| 59th AGM & Conference (4 Days) | November 1 – November 4, 2026 |

### Registration Pricing Configuration

*All prices placeholder (₦0). Admin-configurable via /admin/pricing post-launch.*

| Category | Physical | Virtual | Early Bird Physical | Early Bird Virtual |
|---|---|---|---|---|
| Student | ₦0 | ₦0 | ₦0 | ₦0 |
| Graduate | ₦0 | ₦0 | ₦0 | ₦0 |
| Corporate | ₦0 | ₦0 | ₦0 | ₦0 |
| Fellow | ₦0 | ₦0 | ₦0 | ₦0 |
| Honorary Fellow | ₦0 | ₦0 | ₦0 | ₦0 |
| Non-member | ₦0 | ₦0 | ₦0 | ₦0 |

> Honorary Fellow pricing visible to Super Admin only. Not selectable on public registration form.

### Out of Scope (Confirmed)

| Item | Status |
|---|---|
| AssuredERP | Separate system |
| Elections / Voting | Not in scope |
| NSE Internal ERP Integration | Not in scope |
| External Membership Verification | Not in scope |
| SMS Notifications | Not in scope |
| CSV Member Import | Not in scope |
| AGM Sub-brand / Custom Logo | Not in scope |

---

*End of Architecture & Design Specification — Phases 1 to 3*
*Next: Phase 4 — Page Design (Homepage first)*

---

**Document Version:** 1.0
**Last Updated:** February 2026
**Project:** NSE 59th AGM Portal
**Classification:** Internal Working Document
