# Speakers System Architecture

## System Overview

```
┌─────────────────────────────────────────────────────────────────────────┐
│                     NSE SPEAKERS MANAGEMENT SYSTEM                      │
└─────────────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────────┐
│                           PUBLIC LAYER (Web)                             │
├──────────────────────────────────────────────────────────────────────────┤
│                                                                           │
│                    Route: GET /speakers                                   │
│                           ↓                                               │
│                 View: speakers.blade.php                                 │
│                           ↓                                               │
│    ┌──────────────────────────────────────────────────┐                 │
│    │  PUBLIC SPEAKERS PAGE (/speakers)                │                 │
│    ├──────────────────────────────────────────────────┤                 │
│    │                                                   │                 │
│    │  [Search Box]                [Filters]           │                 │
│    │   - Search speakers          - All               │                 │
│    │   - By name                   - Keynote          │                 │
│    │   - By organization          - Invited          │                 │
│    │   - By expertise                                 │                 │
│    │                                                   │                 │
│    │  ┌─────────────────┐  ┌─────────────────┐      │                 │
│    │  │  KEYNOTE BLOCK  │  │  KEYNOTE BLOCK  │      │                 │
│    │  │                 │  │                 │      │                 │
│    │  │ [Photo]         │  │ [Photo]         │      │                 │
│    │  │ Name (Large)    │  │ Name (Large)    │      │                 │
│    │  │ Title (Green)   │  │ Title (Green)   │      │                 │
│    │  │ Organization    │  │ Organization    │      │                 │
│    │  │ Bio (preview)   │  │ Bio (preview)   │      │                 │
│    │  │ [Topics Tags]   │  │ [Topics Tags]   │      │                 │
│    │  │ Session Info    │  │ Session Info    │      │                 │
│    │  │ [Social Links]  │  │ [Social Links]  │      │                 │
│    │  └─────────────────┘  └─────────────────┘      │                 │
│    │                                                   │                 │
│    │  ┌─────────────────┐  ┌─────────────────┐      │                 │
│    │  │ INVITED BLOCK   │  │ INVITED BLOCK   │      │                 │
│    │  │                 │  │                 │      │                 │
│    │  │ [Photo]         │  │ [Photo]         │      │                 │
│    │  │ Name            │  │ Name            │      │                 │
│    │  │ Title           │  │ Title           │      │                 │
│    │  │ Organization    │  │ Organization    │      │                 │
│    │  │ Bio (preview)   │  │ Bio (preview)   │      │                 │
│    │  │ [Topics Tags]   │  │ [Topics Tags]   │      │                 │
│    │  │ Session Info    │  │ Session Info    │      │                 │
│    │  │ [Social Links]  │  │ [Social Links]  │      │                 │
│    │  └─────────────────┘  └─────────────────┘      │                 │
│    │                                                   │                 │
│    └──────────────────────────────────────────────────┘                 │
│                                                                           │
└──────────────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────────┐
│                       ADMIN LAYER (Protected)                            │
├──────────────────────────────────────────────────────────────────────────┤
│  Authentication: auth, verified, MFA, super_admin role                   │
│                                                                           │
│  Routes: /admin/speakers/*                                               │
│          ├── GET  /speakers (index)                                       │
│          ├── GET  /speakers/create (create form)                          │
│          ├── POST /speakers (store)                                       │
│          ├── GET  /speakers/{id}/edit (edit form)                         │
│          ├── PUT  /speakers/{id} (update)                                 │
│          ├── DELETE /speakers/{id} (delete)                               │
│          └── POST /speakers/bulk (bulk operations)                        │
│                                                                           │
│  ┌────────────────────────────────────────────────────────────────────┐  │
│  │              ADMIN SPEAKERS LIST VIEW                              │  │
│  ├────────────────────────────────────────────────────────────────────┤  │
│  │  [Search] [Status Filter] [Type Filter] [Add Speaker Button]     │  │
│  │                                                                    │  │
│  │  ☐ Photo │ Name │ Title │ Type │ Status │ Session │ Order │ Act  │  │
│  │  ├────────────────────────────────────────────────────────────     │  │
│  │  ☐ [✓] │ John │ CEO │ Keynote │ Active │ Future  │ 0 │ E/D  │  │
│  │  ☐ [ ] │ Jane │ CFO │ Invited │ Active │ Future  │ 1 │ E/D  │  │
│  │  ☐ [ ] │ Bob  │ CTO │ Keynote │ Inactive │ Future  │ 0 │ E/D  │  │
│  │                                                                    │  │
│  │  [Bulk Action Dropdown] [Apply] [Clear]                          │  │
│  │                                                                    │  │
│  └────────────────────────────────────────────────────────────────────┘  │
│                                                                           │
│  ┌────────────────────────────────────────────────────────────────────┐  │
│  │           ADD/EDIT SPEAKER FORM (7 FIELDSETS)                     │  │
│  ├────────────────────────────────────────────────────────────────────┤  │
│  │                                                                    │  │
│  │  1. BASIC INFORMATION                                             │  │
│  │     [First Name] [Last Name]                                      │  │
│  │     [Title] [Organization]                                        │  │
│  │     [Biography Textarea]                                          │  │
│  │                                                                    │  │
│  │  2. CONTACT INFORMATION                                           │  │
│  │     [Email] [Phone]                                               │  │
│  │                                                                    │  │
│  │  3. PHOTO & MEDIA                                                 │  │
│  │     [Current Photo Preview]                                       │  │
│  │     [Photo URL Input]                                             │  │
│  │     [Photo File Upload]                                           │  │
│  │                                                                    │  │
│  │  4. SOCIAL LINKS                                                  │  │
│  │     [Website URL] [Twitter URL] [LinkedIn URL]                    │  │
│  │                                                                    │  │
│  │  5. EXPERTISE & SESSION                                           │  │
│  │     [Expertise Topics] (comma-separated)                          │  │
│  │     [Session Title]                                               │  │
│  │     [Session Description]                                         │  │
│  │     [Session Date/Time]                                           │  │
│  │                                                                    │  │
│  │  6. SETTINGS                                                      │  │
│  │     ☐ Mark as Keynote Speaker                                    │  │
│  │     ☐ Active (visible on public page)                            │  │
│  │                                                                    │  │
│  │  7. DISPLAY ORDER                                                 │  │
│  │     [Sort Order: 0-9999]                                          │  │
│  │                                                                    │  │
│  │  [Save/Create Button] [Cancel Button]                            │  │
│  │                                                                    │  │
│  └────────────────────────────────────────────────────────────────────┘  │
│                                                                           │
└──────────────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────────┐
│                      APPLICATION LAYER (Logic)                           │
├──────────────────────────────────────────────────────────────────────────┤
│                                                                           │
│  ┌─────────────────────────────────────────────────────────────────┐   │
│  │           SpeakersController (Admin)                            │   │
│  ├─────────────────────────────────────────────────────────────────┤   │
│  │                                                                  │   │
│  │  • index() - List all speakers                                 │   │
│  │  • create() - Show create form                                 │   │
│  │  • store() - Save new speaker                                  │   │
│  │  • edit() - Show edit form                                     │   │
│  │  • update() - Update speaker                                   │   │
│  │  • destroy() - Delete speaker                                  │   │
│  │  • bulk() - Bulk operations                                    │   │
│  │                                                                  │   │
│  └─────────────────────────────────────────────────────────────────┘   │
│                           ↓                                              │
│  ┌─────────────────────────────────────────────────────────────────┐   │
│  │           Speaker Model (Eloquent ORM)                          │   │
│  ├─────────────────────────────────────────────────────────────────┤   │
│  │                                                                  │   │
│  │  Attributes:                                                     │   │
│  │  • first_name, last_name                                        │   │
│  │  • title, organization, bio                                     │   │
│  │  • email, phone                                                 │   │
│  │  • photo_url                                                    │   │
│  │  • website_url, twitter_url, linkedin_url                       │   │
│  │  • expertise_topics (JSON)                                      │   │
│  │  • session_title, session_description, session_time             │   │
│  │  • is_keynote, is_active, sort_order                            │   │
│  │                                                                  │   │
│  │  Methods & Scopes:                                               │   │
│  │  • getFullNameAttribute()  - Return "First Last"                │   │
│  │  • active()  - Where is_active = 1                              │   │
│  │  • keynote()  - Where is_keynote = 1                            │   │
│  │  • invited()  - Where is_keynote = 0                            │   │
│  │  • ordered()  - Order by keynote desc, sort_order asc, name     │   │
│  │  • search($term)  - Full-text search                            │   │
│  │                                                                  │   │
│  └─────────────────────────────────────────────────────────────────┘   │
│                           ↓                                              │
│  Audit Service Integration:                                             │
│  • Log speaker.created                                                  │
│  • Log speaker.updated (before/after)                                   │
│  • Log speaker.deleted                                                  │
│  • Log speaker.bulk_* operations                                        │
│                                                                           │
└──────────────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────────┐
│                      DATA PERSISTENCE LAYER                              │
├──────────────────────────────────────────────────────────────────────────┤
│                                                                           │
│  Database: MySQL/MariaDB                                                 │
│                                                                           │
│  ┌────────────────────────────────────────────────────────────────────┐  │
│  │ TABLE: speakers                                                    │  │
│  ├────────────────────────────────────────────────────────────────────┤  │
│  │                                                                    │  │
│  │  Column         │ Type         │ Index  │ Description             │  │
│  │  ─────────────────────────────────────────────────────────────   │  │
│  │  id             │ BIGINT       │ PK     │ Primary Key             │  │
│  │  first_name     │ VARCHAR(255) │ FT     │ Indexed for search      │  │
│  │  last_name      │ VARCHAR(255) │ FT     │ Indexed for search      │  │
│  │  title          │ VARCHAR(255) │        │                         │  │
│  │  organization   │ VARCHAR(255) │ FT     │ Indexed for search      │  │
│  │  bio            │ LONGTEXT     │ FT     │ Indexed for search      │  │
│  │  email          │ VARCHAR(255) │        │                         │  │
│  │  phone          │ VARCHAR(20)  │        │                         │  │
│  │  photo_url      │ VARCHAR(1000)│        │                         │  │
│  │  website_url    │ VARCHAR(1000)│        │                         │  │
│  │  twitter_url    │ VARCHAR(1000)│        │                         │  │
│  │  linkedin_url   │ VARCHAR(1000)│        │                         │  │
│  │  expertise_topics│ JSON        │        │ Array of topics         │  │
│  │  session_title  │ VARCHAR(500) │        │                         │  │
│  │  session_desc   │ LONGTEXT     │        │                         │  │
│  │  session_time   │ DATETIME     │        │                         │  │
│  │  is_keynote     │ BOOLEAN      │ ✓      │ Indexed                 │  │
│  │  is_active      │ BOOLEAN      │ ✓      │ Indexed                 │  │
│  │  sort_order     │ INT UNSIGNED │ ✓      │ Indexed                 │  │
│  │  created_at     │ TIMESTAMP    │        │                         │  │
│  │  updated_at     │ TIMESTAMP    │        │                         │  │
│  │                                                                    │  │
│  │  Indexes:                                                         │  │
│  │  • PRIMARY (id)                                                   │  │
│  │  • INDEX is_active (is_active)                                    │  │
│  │  • INDEX is_keynote (is_keynote)                                  │  │
│  │  • INDEX sort_order (sort_order)                                  │  │
│  │  • INDEX active_keynote (is_active, is_keynote)                   │  │
│  │  • FULLTEXT search (first_name, last_name, organization, bio)    │  │
│  │                                                                    │  │
│  └────────────────────────────────────────────────────────────────────┘  │
│                                                                           │
│  File Storage:                                                           │
│  • Location: storage/app/public/speakers/                                │
│  • Access: /storage/speakers/filename.jpg                                │
│  • Symlink: public/storage → storage/app/public                          │
│                                                                           │
└──────────────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────────┐
│                    DATA FLOW DIAGRAMS                                     │
├──────────────────────────────────────────────────────────────────────────┤
│                                                                           │
│  USER VIEWING SPEAKERS (/speakers)                                       │
│  ─────────────────────────────────────────────────────────────────────   │
│                                                                           │
│  User visits /speakers                                                   │
│         ↓                                                                 │
│  Router matches GET /speakers                                            │
│         ↓                                                                 │
│  Controller query:                                                       │
│    • Speaker::active()->ordered()->get()                                 │
│    • .where('is_keynote', true) for keynote section                      │
│    • .where('is_keynote', false) for invited section                     │
│         ↓                                                                 │
│  Database returns speaker records                                        │
│         ↓                                                                 │
│  View renders speakers.blade.php                                         │
│    • Passes keynote_speakers collection                                  │
│    • Passes invited_speakers collection                                   │
│    • Passes all speakers collection                                       │
│         ↓                                                                 │
│  Browser receives HTML                                                   │
│         ↓                                                                 │
│  JavaScript attaches:                                                     │
│    • Search event listener                                               │
│    • Filter event listeners                                              │
│         ↓                                                                 │
│  User interacts (search/filter)                                          │
│         ↓                                                                 │
│  JavaScript filters DOM elements                                         │
│    (No server request, all client-side)                                  │
│         ↓                                                                 │
│  Filtered speakers display                                               │
│                                                                           │
│                                                                           │
│  ADMIN ADDING SPEAKER (/admin/speakers/create → store)                   │
│  ─────────────────────────────────────────────────────────────────────   │
│                                                                           │
│  Admin fills form                                                        │
│         ↓                                                                 │
│  Submits POST /admin/speakers                                            │
│         ↓                                                                 │
│  Middleware checks:                                                       │
│    • auth (user logged in)                                               │
│    • verified (email verified)                                           │
│    • MFA (multi-factor enabled)                                          │
│    • super_admin role                                                    │
│         ↓                                                                 │
│  SpeakersController@store()                                              │
│         ↓                                                                 │
│  Validate request data                                                   │
│         ↓                                                                 │
│  Handle photo upload (if provided):                                      │
│    • Save to storage/app/public/speakers/                                │
│    • Get URL via Storage::disk('public')->url()                          │
│         ↓                                                                 │
│  Convert expertise_topics (comma → array)                                │
│         ↓                                                                 │
│  Speaker::create($validated_data)                                        │
│         ↓                                                                 │
│  Database INSERT                                                         │
│         ↓                                                                 │
│  AuditService logs "speaker.created"                                     │
│  INSERT INTO audit_logs                                                  │
│         ↓                                                                 │
│  Redirect to /admin/speakers with success message                        │
│         ↓                                                                 │
│  Speaker now visible:                                                     │
│    • In admin list (if active)                                           │
│    • On public page (if active)                                          │
│                                                                           │
└──────────────────────────────────────────────────────────────────────────┘
```

## Component Relationships

```
┌─────────────────────────────────────────────────────────────────────────┐
│ RELATIONSHIPS & DEPENDENCIES                                            │
└─────────────────────────────────────────────────────────────────────────┘

SpeakersController
    ├── uses → Speaker model
    ├── uses → AuditService
    ├── uses → Storage facade
    └── uses → Schema facade

Speaker Model
    ├── extends → Eloquent Model
    ├── uses → HasFactory trait
    ├── casts → JSON, Boolean
    └── provides → Scopes (active, keynote, invited, ordered, search)

Authentication Middleware
    ├── auth → Verify user is logged in
    ├── verified → Verify email is verified
    ├── EnsureAdminMfa → Verify MFA is enabled
    └── role:super_admin → Verify user role

Routes
    ├── public /speakers → Controller closure → View: speakers.blade.php
    └── admin /admin/speakers/* → SpeakersController → Views (index, create, edit)

Blade Templates
    ├── speakers.blade.php (public)
    │   ├── Extends layouts.public
    │   ├── Uses Tailwind CSS
    │   ├── Includes JavaScript for search/filter
    │   └── Displays speaker cards
    │
    ├── admin/speakers/index.blade.php
    │   ├── Extends layouts.admin
    │   ├── Displays table view
    │   ├── Includes bulk action JavaScript
    │   └── XAdmin components
    │
    ├── admin/speakers/create.blade.php
    │   ├── Extends layouts.admin
    │   ├── Form with 7 fieldsets
    │   └── XAdmin components
    │
    └── admin/speakers/edit.blade.php
        ├── Extends layouts.admin
        ├── Pre-populated form
        └── XAdmin components

View Components (used)
    ├── x-admin.page-header
    ├── x-admin.panel
    └── x-admin.table
```

## Query Flow Diagram

```
PUBLIC PAGE QUERIES:
─────────────────────────────────────────────────────────────────────────

GET /speakers
    ↓
if (Schema::hasTable('speakers'))
    ├─ YES:
    │   ├─ Speaker::active()->ordered()->get()
    │   │   └─ WHERE is_active = 1
    │   │       ORDER BY is_keynote DESC, sort_order ASC, first_name ASC
    │   │
    │   ├─ Split collection:
    │   │   ├─ keynote_speakers = where(is_keynote, true)
    │   │   └─ invited_speakers = where(is_keynote, false)
    │   │
    │   └─ Return speakers.blade.php with all collections
    │
    └─ NO: Cache hit or table doesn't exist → Return empty collections

ADMIN PAGE QUERIES:
─────────────────────────────────────────────────────────────────────────

GET /admin/speakers
    ↓
Speaker::ordered()->get()
    └─ ORDER BY is_keynote DESC, sort_order ASC, first_name ASC, last_name ASC

SEARCH QUERIES:
─────────────────────────────────────────────────────────────────────────

Speaker::search($term)->get()
    ├─ WHERE MATCH(first_name, last_name, organization, bio)
    │       AGAINST($term IN BOOLEAN MODE)
    └─ OR WHERE first_name LIKE "%$term%"
        OR WHERE last_name LIKE "%$term%"
        OR WHERE organization LIKE "%$term%"

BULK OPERATIONS:
─────────────────────────────────────────────────────────────────────────

Speaker::whereIn('id', $speaker_ids)->get()
    ├─ activate: update(['is_active' => true])
    ├─ deactivate: update(['is_active' => false])
    └─ delete: each(fn($speaker) => $speaker->delete())
```

## Security & Validation Flow

```
┌─────────────────────────────────────────────────────────────────────────┐
│ SECURITY LAYERS                                                         │
└─────────────────────────────────────────────────────────────────────────┘

REQUEST LIFECYCLE (Admin Operations):
─────────────────────────────────────────────────────────────────────────

1. HTTP Request
   ↓
2. Middleware Authentication
   ├─ auth → Check user exists
   ├─ verified → Check email verified
   ├─ EnsureAdminMfa → Check MFA enabled
   └─ role:super_admin → Check admin role
   ↓
3. Route Binding
   ├─ Cast route model: {speaker} → Speaker instance
   └─ Verify speaker exists (404 if not)
   ↓
4. Controller Method
   ├─ Validate inputs (server-side)
   │  └─ Form Request Validator
   │
   ├─ Handle file uploads
   │  ├─ Check file type (JPG/PNG)
   │  ├─ Check file size (< 5MB)
   │  ├─ Store to storage/app/public/speakers/
   │  └─ Get public URL
   │
   ├─ Process data
   │  └─ Convert expertise topics (string → array)
   │
   ├─ Database operations
   │  ├─ Prepare data
   │  └─ Insert/Update with mass assignment
   │
   └─ Audit logging
      └─ Log operation with user ID and changes
   ↓
5. Response
   └─ Redirect with success/error message

VALIDATION RULES:
─────────────────────────────────────────────────────────────────────────

All User Inputs → Validate:
├─ Type check (string, email, integer, boolean, etc.)
├─ Length validation (max characters)
├─ Format validation (email, URL date/time)
├─ Business logic (0-9999 for sort_order)
└─ Transform/sanitize (trim whitespace, convert types)

FILE UPLOADS:
├─ Check file exists and is readable
├─ Check MIME type
├─ Check file size < 5MB
├─ Generate unique filename
├─ Store in public disk
└─ Return public URL

CSRF PROTECTION:
├─ Token required in all POST/PUT/DELETE forms
├─ Verified by middleware
└─ Fails if token missing or invalid
```

---

This comprehensive architecture ensures:
- ✅ Clean separation of concerns (MVC pattern)
- ✅ Security at every layer
- ✅ Performance optimization with indexes
- ✅ Audit trail for compliance
- ✅ User-friendly interfaces (admin & public)
- ✅ Scalability through proper design
