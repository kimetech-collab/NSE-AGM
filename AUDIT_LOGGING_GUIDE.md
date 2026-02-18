# Audit Logging System

## Overview

The NSE Portal now includes a comprehensive audit logging system that tracks all administrative actions, state changes, and system events. Every action is immutably recorded with full context including actor, timestamp, changes, and metadata.

## Features

### ✅ Comprehensive Logging
- All admin actions automatically logged
- Before/after state capture for change tracking
- Metadata storage for contextual information
- IP address and user agent tracking
- Immutable records (no updates after creation)

### ✅ Advanced Filtering
- Filter by actor/user
- Filter by action type
- Filter by entity type
- Filter by status (success/failure)
- Date range filtering
- Full-text search

### ✅ Detailed Views
- List view with pagination (50 per page)
- Detail view showing full context
- Related audit trail for same entity
- Visual diff of state changes
- Metadata inspection

### ✅ Export Capability
- CSV export with timestamp
- All filters applied
- Includes actor names, actions, entity details
- Before/after diffs in JSON format

### ✅ RESTful API
- JSON endpoint for audit trails
- Entity-specific audit history
- Useful for AJAX integrations

---

## Database Schema

### audit_logs Table

```sql
CREATE TABLE audit_logs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  actor_id BIGINT UNSIGNED NULLABLE,
  action VARCHAR(150) NOT NULL,
  entity_type VARCHAR(100) NOT NULL,
  entity_id BIGINT UNSIGNED NOT NULL,
  before_state JSON NULLABLE,
  after_state JSON NULLABLE,
  metadata JSON NULLABLE,
  ip_address VARCHAR(45) NULLABLE,
  user_agent TEXT NULLABLE,
  status ENUM('Success', 'Failure') DEFAULT 'Success',
  error_message TEXT NULLABLE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  INDEX idx_actor_id (actor_id),
  INDEX idx_action (action),
  INDEX idx_entity_type (entity_type),
  INDEX idx_entity (entity_type, entity_id),
  INDEX idx_created_at (created_at),
  INDEX idx_action_entity (action, entity_type),
  INDEX idx_actor_created (actor_id, created_at)
);
```

---

## Usage

### 1. Logging Actions with AuditService

#### Basic Logging
```php
use App\Services\AuditService;

$audit = app(AuditService::class);

// Log a simple action
$audit->log(
    action: 'registration.created',
    entityType: 'Registration',
    entityId: 123,
    metadata: ['name' => 'John Doe', 'email' => 'john@example.com']
);
```

#### Logging with State Changes
```php
$registration = Registration::findOrFail(123);
$beforeState = $registration->toArray();

// Make changes
$registration->update(['status' => 'verified']);

$afterState = $registration->toArray();

// Log with before/after
$audit->log(
    action: 'registration.updated',
    entityType: 'Registration',
    entityId: 123,
    beforeState: $beforeState,
    afterState: $afterState,
    metadata: ['reason' => 'Email verified']
);
```

#### Helper Methods
```php
// Log registration actions
$audit->logRegistration('created', $registrationId, $metadata, $before, $after);

// Log payment actions
$audit->logPayment('received', $transactionId, $metadata, $before, $after);

// Log refund actions
$audit->logRefund('initiated', $refundId, $metadata, $before, $after);

// Log certificate actions
$audit->logCertificate('issued', $certificateId, $metadata, $before, $after);

// Log user actions
$audit->logUser('created', $userId, $metadata, $before, $after);

// Log settings changes
$audit->logSettings('updated', $settingId, $metadata, $before, $after);

// Log failed actions
$audit->logFailure(
    'payment.processed',
    'PaymentTransaction',
    123,
    'Insufficient funds',
    ['amount' => 5000]
);
```

### 2. Controller Integration

#### In FinanceController
```php
class FinanceController extends Controller
{
    public function __construct(
        private PaymentService $payments,
        private AuditService $audit
    ) {}

    public function refund(Request $request, $id)
    {
        $tx = PaymentTransaction::findOrFail($id);
        $beforeState = $tx->toArray();
        
        $result = $this->payments->initiateRefund($tx);
        
        if ($result['success']) {
            $tx->refresh();
            $this->audit->logRefund('initiated', $tx->id, [
                'provider_reference' => $tx->provider_reference,
                'amount_naira' => $tx->amount_cents / 100,
            ], $beforeState, $tx->toArray());
            
            return redirect()->back()->with('success', 'Refund initiated');
        }
        
        $this->audit->logFailure(
            'refund.initiated',
            'PaymentTransaction',
            $id,
            $result['message']
        );
    }
}
```

### 3. Model Attributes

The AuditLog model provides convenient attributes for formatting:

```php
$log = AuditLog::first();

// Formatted action (human-readable)
echo $log->formatted_action; // "Refund Initiated"

// Status badge class for HTML
echo $log->status_badge; // "bg-green-100 text-green-800"

// Formatted date
echo $log->formatted_date; // "Feb 18, 2026 @ 14:30:45"

// Relative time
echo $log->relative_time; // "2 hours ago"

// Changes between states
$changes = $log->changes; // ['email' => ['from' => 'old@example.com', 'to' => 'new@example.com']]

// Actor name
echo $log->actor_name; // "Admin User" or "System"
```

### 4. Query Scopes

```php
// Get all logs for a specific action
AuditLog::byAction('registration.created')->get();

// Get all logs for a specific entity
AuditLog::byEntity('Registration', 123)->get();

// Get all logs by a specific actor
AuditLog::byActor($userId)->get();

// Get logs in a date range
use Carbon\Carbon;
AuditLog::byDateRange(
    Carbon::now()->subDays(7),
    Carbon::now()
)->get();

// Get only failed actions
AuditLog::byStatus('Failure')->get();

// Get latest logs first
AuditLog::latest()->get();

// Combine scopes
AuditLog::byEntity('Registration')
    ->byStatus('Success')
    ->byDateRange($from, $to)
    ->latest()
    ->paginate();
```

---

## Admin UI

### Access the Audit Log Viewer

1. Navigate to `/admin` (requires authentication)
2. Click on "Audit Logs" in the admin dashboard
3. URL: `/admin/audit`

### Features

#### Filtering
- **Actor/User**: Filter by who performed the action
- **Action**: Filter by action type (e.g., "Registration Created")
- **Entity Type**: Filter by entity type (e.g., "Payment Transaction")
- **Status**: Filter by Success or Failure
- **Date Range**: Filter by date range
- **Entity ID**: Find logs for specific entity
- **Search**: Full-text search across action, entity type, and metadata

#### Display
- **Pagination**: 50 entries per page
- **Timestamp**: Shows both formatted date and relative time ("2 hours ago")
- **Actor**: Shows user name and ID
- **Action**: Shows formatted, human-readable action name
- **Entity**: Shows entity type and ID
- **Status**: Color-coded badge (green for success, red for failure)
- **IP Address**: Source IP of the request

#### Actions
- **View**: Click to see full details
- **Export CSV**: Export filtered results to CSV

### Detail View

Clicking "View" on any log entry shows:

1. **Basic Info**
   - Log ID and Status
   - Timestamp with relative time
   - Action and Entity details
   - Actor information

2. **State Changes**
   - Side-by-side comparison of before/after values
   - Only shows fields that changed
   - Color-coded (red for before, green for after)

3. **Metadata**
   - Additional contextual information in JSON format
   - Any custom data logged with the action

4. **Related Audit Trail**
   - All other logs for the same entity
   - Shows history of all changes to that entity
   - Click to navigate between related logs

---

## Action Naming Convention

Actions follow a hierarchical naming pattern: `noun.verb.action`

Examples:
- `registration.created` - Registration was created
- `registration.updated` - Registration was updated
- `payment.received` - Payment was received
- `refund.initiated` - Refund was initiated
- `refund.completed` - Refund was completed
- `certificate.issued` - Certificate was issued
- `certificate.revoked` - Certificate was revoked
- `user.created` - User account was created
- `role.assigned` - Role was assigned to user

---

## Setup Instructions

### 1. Run Migration
```bash
php artisan migrate
```

This creates the `audit_logs` table with proper indexes.

### 2. Create Audit Service in Service Provider (optional)

The `AuditService` is automatically resolved by Laravel's service container, but you can bind it in a service provider if needed:

```php
// In app/Providers/AppServiceProvider.php
public function register()
{
    $this->app->singleton(AuditService::class, function () {
        return new AuditService();
    });
}
```

### 3. Test the System

```bash
php artisan test tests/Feature/AuditLoggingTest.php
```

---

## Examples

### Example 1: Log Registration Creation
```php
$registration = Registration::create([
    'user_id' => $user->id,
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'category' => 'NSE Member',
]);

$audit->logRegistration('created', $registration->id, [
    'user_name' => $user->name,
    'category' => 'NSE Member',
    'automatic_coupon' => true,
]);
```

### Example 2: Log Payment Processing
```php
$transaction = PaymentTransaction::create([
    'registration_id' => $registration->id,
    'amount_cents' => 500000,
    'currency' => 'NGN',
    'status' => 'paid',
    'provider_reference' => $paystackRef,
]);

$audit->logPayment('received', $transaction->id, [
    'amount_naira' => 5000,
    'provider' => 'Paystack',
    'reference' => $paystackRef,
]);
```

### Example 3: Log Refund Failure
```php
try {
    $result = PaymentService::initiateRefund($transaction);
} catch (Exception $e) {
    $audit->logFailure(
        'refund.initiated',
        'PaymentTransaction',
        $transaction->id,
        $e->getMessage(),
        [
            'amount' => $transaction->amount_cents / 100,
            'provider' => 'Paystack',
            'exception_type' => class_basename($e),
        ]
    );
}
```

---

## Security Considerations

### ✅ Immutability
- Audit logs have no `updated_at` column
- Records cannot be modified after creation
- Ensures integrity and compliance

### ✅ Access Control
- All audit log routes require authentication
- Consider adding authorization checks for specific roles

### ✅ Data Privacy
- User agent is stored (can help identify suspicious activity)
- IP address is stored (logged for compliance)
- PII is captured only when relevant to the action

### ✅ Retention Policy
- Default: Indefinite storage
- Recommended: Archive after 12 months to cold storage
- Legal requirement: 5-year retention for financial audit trail

---

## Performance Optimization

### Indexing
The migration creates optimal indexes:
- `idx_actor_id` - Quick filtering by user
- `idx_action` - Quick filtering by action type
- `idx_entity` - Query by entity type + ID
- `idx_created_at` - Time-based queries
- `idx_action_entity` - Combined filtering

### Partitioning (Optional, Post-Launch)
For very high volume (1M+ records):
```sql
ALTER TABLE audit_logs PARTITION BY RANGE (YEAR(created_at)) (
    PARTITION p2025 VALUES LESS THAN (2026),
    PARTITION p2026 VALUES LESS THAN (2027),
    PARTITION p_future VALUES LESS THAN MAXVALUE
);
```

### Query Optimization
- Use scopes to build efficient queries
- Paginate large result sets (default 50 per page)
- Add more specific indexes if needed based on query patterns

---

## Troubleshooting

### Q: Migration fails with "Unknown column"
**A:** Run fresh migrations or check for conflicting schemas.

### Q: No audit logs appear after actions
**A:** Ensure you're calling the audit service in the relevant controllers/services.

### Q: CSV export is slow
**A:** Reduce date range or add more filters before exporting.

### Q: Audit log table keeps growing
**A:** Implement archival strategy (move to S3, separate cold storage DB)

---

## Future Enhancements

- [ ] Real-time audit log dashboard with WebSockets
- [ ] Audit log diff viewer with syntax highlighting
- [ ] Automated alerts for suspicious patterns
- [ ] Webhooks for critical audit events
- [ ] Multi-language action descriptions
- [ ] Audit log signing/signatures for compliance
- [ ] Integration with third-party SIEM systems

---

## References

- [Laravel Eloquent Documentation](https://laravel.com/docs/eloquent)
- [Database Migrations](https://laravel.com/docs/migrations)
- [Authentication & Authorization](https://laravel.com/docs/authorization)
