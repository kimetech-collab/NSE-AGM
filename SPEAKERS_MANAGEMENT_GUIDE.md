# Speakers Management System

## Overview

The Speakers Management System provides a complete solution for managing conference speakers, including both keynote speakers and invited speakers. The system features a professional public-facing speakers page with filtering/search capabilities and a comprehensive admin management interface for super administrators.

## Features

### Public Speakers Page (`/speakers`)

- **Professional Display**: Beautiful speaker cards with photos, titles, organizations, and biographies
- **Separated Sections**: Keynote speakers and invited speakers displayed in distinct sections
- **Search Functionality**: Real-time search across speaker names, organizations, and expertise topics
- **Filter Options**: Quick filters to view all speakers, keynote only, or invited speakers only
- **Responsive Design**: Mobile-friendly layout using Tailwind CSS
- **Social Links**: Display speaker profiles on Twitter, LinkedIn, and personal websites
- **Session Information**: Display speaker session titles, descriptions, and scheduled times
- **Expertise Display**: Visual tags showing speaker areas of expertise

### Admin Management Portal (`/admin/speakers`)

- **Speaker Listing**: Table view with sortable data and quick actions
- **Search & Filtering**: Search by name, filter by status (active/inactive) and type (keynote/invited)
- **Bulk Actions**: Select multiple speakers and perform bulk activate, deactivate, or delete operations
- **Create Speaker**: Comprehensive form for adding new speakers with all relevant information
- **Edit Speaker**: Full editing capabilities for all speaker fields and properties
- **Delete Speaker**: Remove speakers with confirmation dialog
- **Sort Management**: Control speaker display order with numeric sort orders
- **Photo Upload**: Support for both URL-based photos and file uploads (JPG, PNG - max 5MB)
- **Audit Logging**: All changes logged in the audit trail system

## Database Schema

### Speakers Table

```sql
CREATE TABLE speakers (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    
    -- Basic Information
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    title VARCHAR(255) NULLABLE,
    organization VARCHAR(255) NULLABLE,
    bio LONGTEXT NULLABLE,
    
    -- Contact Information
    email VARCHAR(255) NULLABLE,
    phone VARCHAR(20) NULLABLE,
    
    -- Media & Social
    photo_url VARCHAR(1000) NULLABLE,
    website_url VARCHAR(1000) NULLABLE,
    twitter_url VARCHAR(1000) NULLABLE,
    linkedin_url VARCHAR(1000) NULLABLE,
    
    -- Expertise & Session
    expertise_topics JSON NULLABLE,
    session_title VARCHAR(500) NULLABLE,
    session_description LONGTEXT NULLABLE,
    session_time DATETIME NULLABLE,
    
    -- Status & Sorting
    is_keynote BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    sort_order UNSIGNED INTEGER DEFAULT 0,
    
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    -- Indexes
    KEY is_active (is_active),
    KEY is_keynote (is_keynote),
    KEY sort_order (sort_order),
    KEY active_keynote (is_active, is_keynote),
    FULLTEXT KEY search (first_name, last_name, organization, bio)
);
```

## Model: Speaker

### Location
`app/Models/Speaker.php`

### Key Methods

**Accessor:**
- `getFullNameAttribute()` - Returns formatted "First Last" name

**Scopes:**
- `active()` - Filters to only active speakers
- `keynote()` - Filters to only keynote speakers
- `invited()` - Filters to only invited (non-keynote) speakers
- `ordered()` - Sorts by is_keynote DESC, sort_order ASC, name ASC
- `search($term)` - Full-text search across name, organization, and expertise topics

### Example Usage

```php
// Get all active keynote speakers in order
$speakers = Speaker::active()->keynote()->ordered()->get();

// Search speakers
$results = Speaker::search('innovation')->get();

// Get invited speakers sorted by name
$invited = Speaker::invited()->ordered()->get();
```

## Controllers

### Admin SpeakersController
Location: `app/Http/Controllers/Admin/SpeakersController.php`

**Methods:**
- `index()` - Display speakers list with admin interface
- `create()` - Show create speaker form
- `store()` - Store new speaker in database
- `edit($speaker)` - Show edit form for speaker
- `update($speaker)` - Update speaker data
- `destroy($speaker)` - Delete speaker
- `bulk()` - Perform bulk operations (activate/deactivate/delete) on multiple speakers

**Access Control:**
- All routes restricted to `super_admin` role
- Requires:
  - Authentication (`auth`)
  - Email verification (`verified`)
  - MFA (`EnsureAdminMfa`)
  - Admin access logging

## Routes

### Public Routes

```php
// Public speakers page
Route::get('/speakers', [...])->name('speakers');
```

### Admin Routes

```php
/* All routes require: auth, verified, MFA, super_admin role */

// Listing and operations
Route::get('admin/speakers', 'index')->name('admin.speakers.index');
Route::get('admin/speakers/create', 'create')->name('admin.speakers.create');
Route::post('admin/speakers', 'store')->name('admin.speakers.store');

// Edit operations
Route::get('admin/speakers/{speaker}/edit', 'edit')->name('admin.speakers.edit');
Route::put('admin/speakers/{speaker}', 'update')->name('admin.speakers.update');
Route::delete('admin/speakers/{speaker}', 'destroy')->name('admin.speakers.delete');

// Bulk operations
Route::post('admin/speakers/bulk', 'bulk')->name('admin.speakers.bulk');
```

## Views

### Public View: `resources/views/speakers.blade.php`

Displays:
- Keynote speakers section (green-accented cards)
- Invited speakers section (neutral-accented cards)
- Search bar and filter buttons
- Social media links
- Session information
- Empty state when no speakers available

**Search & Filter Logic:**
- Client-side JavaScript filtering
- Real-time search as user types
- Maintains display state for selected filters

### Admin Views

**Index:** `resources/views/admin/speakers/index.blade.php`
- Speaker listing table with thumbnails
- Search and multiple filter dropdowns
- Bulk action selection with checkboxes
- Quick edit/delete action buttons
- Status and type indicators

**Create:** `resources/views/admin/speakers/create.blade.php`
- Comprehensive form across 7 fieldsets
- Photo upload (URL or file)
- All speaker information fields
- Boolean toggles for keynote/active status
- Sort order input
- Form validation display

**Edit:** `resources/views/admin/speakers/edit.blade.php`
- Same fields as create form
- Pre-populated with existing speaker data
- Photo preview for current photo
- Handles photo updates (URL or file)
- Expertise topics format conversion

## Field Descriptions

| Field | Type | Description |
|-------|------|-------------|
| first_name | string | Speaker's first name (required) |
| last_name | string | Speaker's last name (required) |
| title | string | Professional title (e.g., "CEO") |
| organization | string | Company/organization name |
| bio | text | Speaker biography (up to 5000 chars) |
| email | email | Contact email address |
| phone | string | Contact phone number |
| photo_url | url | Direct URL to speaker photo |
| photo_file | file | Upload speaker photo (JPG/PNG, max 5MB) |
| website_url | url | Speaker's personal website |
| twitter_url | url | Twitter profile URL |
| linkedin_url | url | LinkedIn profile URL |
| expertise_topics | array | Comma-separated expertise areas |
| session_title | string | Title of speaker's session |
| session_description | text | Detailed session description |
| session_time | datetime | Scheduled session date and time |
| is_keynote | boolean | Mark as keynote speaker |
| is_active | boolean | Visible on public page |
| sort_order | integer | Display order (0-9999) |

## Validation Rules

**Create/Update Operations:**
- `first_name` - Required, string, max 255 chars
- `last_name` - Required, string, max 255 chars
- `title` - String, max 255 chars
- `organization` - String, max 255 chars
- `bio` - String, max 5000 chars
- `email` - Valid email format
- `phone` - String, max 20 chars
- `photo_url` - URL format, max 1000 chars
- `photo_file` - Image file, max 5MB
- `website_url`, `twitter_url`, `linkedin_url` - Valid URLs, max 1000 chars
- `expertise_topics` - String (comma-separated)
- `session_title` - String, max 500 chars
- `session_description` - String, max 5000 chars
- `session_time` - DateTime format (Y-m-d H:i)
- `sort_order` - Integer, min 0, max 9999
- `is_active`, `is_keynote` - Boolean

## Audit Logging

All speaker operations are automatically logged in the audit trail:

- `speaker.created` - When a new speaker is created
- `speaker.updated` - When a speaker is updated (with before/after data)
- `speaker.deleted` - When a speaker is deleted
- `speaker.bulk_activate` - Bulk activation operation
- `speaker.bulk_deactivate` - Bulk deactivation operation
- `speaker.bulk_delete` - Bulk deletion operation

Access audit logs at `/admin/audit`

## File Upload Handling

### Photo Upload
- Stored in `storage/app/public/speakers/` directory
- Accessible via `storage:link` (symlink from `public/storage/`)
- Supports JPG and PNG formats
- Maximum file size: 5MB
- Automatically resized/optimized through Intervention Image (if configured)

### URL Patterns
```
/storage/speakers/filename.jpg
/storage/speakers/speaker-photo-[timestamp]-[hash].jpg
```

## Search Implementation

### Public Page
- Client-side JavaScript search
- Searches across:
  - Full name (first + last)
  - Organization
  - Expertise topics
- Real-time filtering as user types
- Combined with type filter (keynote/invited/all)

### Admin Page
- Server-side database search using FULLTEXT indexes
- Search fields: first_name, last_name, organization, expertise_topics
- Queryable via scope: `Speaker::search($term)`

## Display Order

Speakers are displayed in this priority order:

1. **Keynote Status** (keynote speakers first)
2. **Sort Order** (ascending, 0 first)
3. **Name** (first name then last name, alphabetical)

Example sort: Keynote speakers with sort_order 0-9, then keynote speakers 10+, then invited speakers 0+

## Styling

The system uses the NSE color palette and Tailwind CSS:

**Color Classes Used:**
- `nse-green-*` - Primary action color (keynote accent)
- `nse-neutral-*` - General text and backgrounds
- `nse-blue-*` - Info/admin interface accents
- Green: `#16a34a`, `#84cc16`, `#6b21a8`
- Neutral: `#f5f5f4` to `#1c1917`

**Component Classes:**
- `.speaker-card` - Individual speaker card
- `.speaker-filter` - Filter button
- `.speaker-checkbox` - Bulk action checkbox
- `line-clamp-*` - Text truncation utilities

## Accessibility Features

- Proper `aria-label` attributes on interactive elements
- Semantic HTML5 elements (`<article>`, `<fieldset>`, `<legend>`)
- Table headers with proper scoping
- Form labels with `for` attributes
- Role attributes (e.g., `role="list"`, `role="listitem"`)
- Empty states explaining what to do next
- Keyboard-accessible buttons and forms
- High contrast text colors

## Performance Optimizations

- Database indexes on:
  - `is_active`
  - `is_keynote`
  - `sort_order`
  - Combined index on `(is_active, is_keynote)`
- FULLTEXT index for search queries
- Client-side admin filtering to reduce requests
- Eager loading where applicable
- Photo URLs served directly without API calls

## Future Enhancements

Possible future improvements:

1. **Speaker Schedule Integration**: Link speakers to calendar/agenda system
2. **Session Registration**: Allow attendees to register for specific sessions
3. **Rating/Feedback**: Collect attendee ratings for speakers and sessions
4. **Speaker Portal**: Self-service speaker profile management
5. **Session Conflict Prevention**: Detect overlapping speaker schedules
6. **Email Notifications**: Auto-email speakers when sessions are scheduled
7. **Video Recording Integration**: Attach session video recordings
8. **Speaker Badge/QR Codes**: Generate digital speaker credentials
9. **Analytics Dashboard**: Track speaker popularity, session attendance
10. **CSV Import/Export**: Bulk import/export speaker data

## Troubleshooting

### Photos Not Displaying
- Verify symlink exists: `php artisan storage:link`
- Check file permissions on `storage/app/public/`
- Ensure URL format is correct

### Search Not Working
- Verify migration was run: `php artisan migrate`
- Check full-text index created: `ALTER TABLE speakers ADD FULLTEXT search (...)`
- Try refreshing browser cache

### Bulk Actions Not Working
- Verify CSRF token in form
- Check browser console for JavaScript errors
- Ensure speaker IDs are valid in database

### Permission Denied
- Verify user has `super_admin` role
- Check MFA is enabled
- Verify email is verified

## API Integration

The Speaker model can be accessed via API. Example implementation:

```php
Route::get('api/speakers', function() {
    return Speaker::active()->ordered()->get();
});

Route::get('api/speakers/search', function(Request $request) {
    return Speaker::active()
        ->search($request->get('q'))
        ->ordered()
        ->get();
});
```

## Testing

Example tests:

```php
// Create speaker
$speaker = Speaker::factory()->create([
    'first_name' => 'John',
    'is_keynote' => true,
]);

// Test scopes
$keynotes = Speaker::keynote()->get();
$this->assertTrue($keynotes->contains($speaker));

// Test search
$found = Speaker::search('John')->first();
$this->assertEquals($speaker->id, $found->id);

// Test ordering
$speakers = Speaker::ordered()->get();
$this->assertTrue($speakers[0]->is_keynote);
```

## Migration

To deploy the speakers system:

```bash
# Create migration
php artisan make:model Speaker -m

# Run migration
php artisan migrate

# Clear cache
php artisan cache:clear
php artisan view:clear
```

## Support

For issues, questions, or feature requests regarding the Speakers Management System, contact the development team or refer to the main project documentation.
