# Speakers System - Quick Reference Guide

## 🎯 Quick Start

### For End Users (Public Site)

**Access the speakers page:**
- Navigate to `/speakers`
- Browse keynote and invited speakers
- Use search to find speakers by name or organization
- Click filters to view specific speaker types
- Click social links to visit speaker profiles

### For Administrators

**Access admin panel:**
- Login as `super_admin`
- Navigate to `/admin/speakers`
- Use the interface to manage all speaker information

---

## 📋 Common Tasks

### Add a New Speaker

1. Go to `/admin/speakers`
2. Click "Add Speaker" button
3. Fill in the form:
   - **Required:** First name, Last name
   - **Important:** Title, Organization, Bio
   - **Media:** Upload photo or paste photo URL
   - **Session:** Add session title and schedule if applicable
   - **Social:** Add website/Twitter/LinkedIn links
4. Choose "Keynote" if applicable
5. Click "Create Speaker"

### Edit an Existing Speaker

1. Go to `/admin/speakers`
2. Find the speaker in the list
3. Click the "Edit" button
4. Modify the desired fields
5. Click "Save Changes"

### Delete a Speaker

1. Go to `/admin/speakers`
2. Find the speaker in the list
3. Click the "Delete" button
4. Confirm deletion

### Manage Multiple Speakers

1. Go to `/admin/speakers`
2. Check the boxes next to speakers you want to manage
3. Click "Select All" to select all visible speakers
4. Choose an action (Activate/Deactivate/Delete)
5. Click "Apply"

### Search for Speakers

**On Public Page (`/speakers`):**
- Type in the search box to find speakers by name or organization
- Use filter buttons to narrow results

**In Admin Panel:**
- Use the search box to find speakers quickly
- Use status dropdown to filter (Active/Inactive)
- Use type dropdown to filter (Keynote/Invited)

---

## 📊 Database Info

### Table: `speakers`

**Key Fields:**
- `id` - Unique identifier
- `first_name`, `last_name` - Speaker name
- `title` - Job title (e.g., "CEO")
- `organization` - Company name
- `bio` - Biography (up to 5000 chars)
- `photo_url` - Photo link or upload
- `session_title` - Session name
- `session_time` - When the session happens
- `is_keynote` - True if keynote speaker
- `is_active` - True if visible to public
- `sort_order` - Display order (lower first)

### Quick Queries

```php
// Get all active speakers
Speaker::active()->get();

// Get only keynote speakers
Speaker::keynote()->get();

// Get speakers in display order
Speaker::ordered()->get();

// Search for a speaker
Speaker::search('innovation')->first();
```

---

## 🔗 Important URLs

| Purpose | URL |
|---------|-----|
| Public page | `/speakers` |
| Admin list | `/admin/speakers` |
| Create new | `/admin/speakers/create` |
| Edit speaker | `/admin/speakers/{id}/edit` |
| View audit | `/admin/audit` |

---

## 🎨 Display Rules

**Speaker Order on Public Page:**
1. Keynote speakers first
2. Within each group, sorted by "Sort Order" (0 first)
3. Then alphabetically by first name, then last name

**Example:**
- Keynote speaker (Sort: 0)
- Keynote speaker (Sort: 0)
- Keynote speaker (Sort: 1)
- Invited speaker (Sort: 0)
- Invited speaker (Sort: 0)

---

## 📸 Photo Management

**Upload Options:**
1. **From File:** Click "Upload Photo" and choose JPG/PNG (max 5MB)
2. **From URL:** Paste a direct link to an image

**Where Photos Are Stored:**
- Uploaded photos: `storage/app/public/speakers/`
- Accessible via: `storage/speakers/filename.jpg`

**Recommended Photo Size:**
- Minimum: 400x400 pixels
- Format: JPG or PNG
- Aspect ratio: Square (1:1) works best

---

## 🔐 Permissions

Only **super_admin** users can:
- ✅ View speaker management panel
- ✅ Add new speakers
- ✅ Edit speakers
- ✅ Delete speakers
- ✅ Perform bulk actions

Other roles:
- ❌ Cannot access `/admin/speakers`
- ✅ Can view public `/speakers` page

---

## ✅ Validation Rules

| Field | Rules |
|-------|-------|
| First Name | Required, max 255 chars |
| Last Name | Required, max 255 chars |
| Title | Max 255 chars |
| Bio | Max 5000 chars |
| Email | Valid email format |
| Photo File | JPG/PNG, max 5MB |
| URLs | Valid format |
| Sort Order | 0-9999 |

---

## 📱 Public Page Features

### Desktop View
- 3 columns of speaker cards
- Large photos (300x300px visible)
- Full biography visible
- All social links clickable

### Mobile View
- 1 column of speaker cards
- Responsive sizing
- Touch-friendly buttons
- Optimized spacing

---

## ⚙️ Admin Features

### List View
- Search by name/organization
- Filter by status (active/inactive)
- Filter by type (keynote/invited)
- Select multiple speakers
- Bulk actions (activate/deactivate/delete)
- Edit/delete individual speakers

### Add/Edit Forms
- 7 organized sections
- Photo preview
- Form validation
- Clear field labels
- Help text for each field

---

## 🔍 Search & Filter

**Public Page Search:**
- Searches: Name, Organization, Expertise Topics
- Real-time as you type
- Combines with type filter

**Admin Panel Search:**
- Searches: Name, Organization, Bio
- Server-side database search
- Combines with status and type filters

---

## 📝 Expertise Topics

**Format:** Comma-separated list
- Input: "Finance, Technology, Innovation"
- Stored: `["Finance", "Technology", "Innovation"]`
- Display: Tags on public page (first 3 shown)

---

## ⏰ Session Scheduling

**Session Fields:**
- `session_title` - Title of the session
- `session_description` - Detailed description
- `session_time` - Date and time (format: YYYY-MM-DD HH:MM)

**Display:**
- Shows on speaker card
- Time format: "Feb 20, 2026 14:00"

---

## 📊 Audit Trail

**All changes logged:**
- Speaker created
- Speaker updated (with before/after data)
- Speaker deleted
- Bulk operations

**View at:** `/admin/audit`

**Information captured:**
- Who made the change
- What changed
- When it changed
- Before/after values

---

## 🚀 Deployment Checklist

- [ ] Run migration: `php artisan migrate`
- [ ] Clear cache: `php artisan cache:clear`
- [ ] Clear views: `php artisan view:clear`
- [ ] Create storage symlink: `php artisan storage:link`
- [ ] Test public page at `/speakers`
- [ ] Test admin panel at `/admin/speakers`
- [ ] Add first speaker
- [ ] Verify photos upload
- [ ] Test search functionality
- [ ] Test filters
- [ ] Check audit logs

---

## 🐛 Troubleshooting

| Issue | Solution |
|-------|----------|
| Photos not showing | Run `php artisan storage:link` |
| Search not working | Ensure migration completed |
| Admin page not accessible | Check user has `super_admin` role |
| Can't upload photos | Check file size < 5MB and format is JPG/PNG |
| Bulk actions not working | Check browser console for errors |

---

## 💡 Tips & Best Practices

1. **Photo Tips:**
   - Use professional, headshot-style photos
   - Square aspect ratio works best
   - Ensure adequate lighting in photos
   - Minimum 400x400px for quality

2. **Bio Writing:**
   - Keep to 100-200 words
   - Highlight key achievements
   - Use professional language
   - Include relevant experience

3. **Session Scheduling:**
   - Ensure no overlapping sessions
   - Include appropriate lead time
   - Consider speaker timezone
   - Add buffer between sessions

4. **Sorting:**
   - Use sort_order 0-5 for featured speakers
   - Sort_order 10+ for other speakers
   - Keynotes appear before invited regardless

5. **Social Links:**
   - Verify links are correct before saving
   - Use full URLs (https://...)
   - LinkedIn format: `linkedin.com/in/username`
   - Twitter format: `twitter.com/username`

---

## 📞 Support

For issues:
1. Check the Troubleshooting section above
2. Review validation rules
3. Check audit trail for what changed
4. Verify permissions (must be super_admin)
5. Contact development team with error details

---

**Last Updated:** February 20, 2026
**System Version:** 1.0
**Status:** Production Ready
