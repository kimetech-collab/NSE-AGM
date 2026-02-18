<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\PaymentTransaction;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Carbon;

class AuditLoggingTest extends TestCase
{
    use RefreshDatabase;

    protected AuditService $auditService;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->auditService = app(AuditService::class);
        $this->user = User::factory()->create([
            'email_verified_at' => now(),
            'two_factor_confirmed_at' => now(),
        ]);
    }

    public function test_audit_log_can_be_created()
    {
        $this->actingAs($this->user);

        $log = $this->auditService->log(
            'registration.created',
            'Registration',
            1,
            ['name' => 'John Doe', 'email' => 'john@example.com']
        );

        $this->assertDatabaseHas('audit_logs', [
            'id' => $log->id,
            'actor_id' => $this->user->id,
            'action' => 'registration.created',
            'entity_type' => 'Registration',
            'entity_id' => 1,
            'status' => 'Success',
        ]);
    }

    public function test_audit_log_captures_before_and_after_states()
    {
        $this->actingAs($this->user);

        $before = ['email' => 'old@example.com'];
        $after = ['email' => 'new@example.com'];

        $log = $this->auditService->log(
            'registration.updated',
            'Registration',
            1,
            [],
            $before,
            $after
        );

        $this->assertEquals($before, $log->before_state);
        $this->assertEquals($after, $log->after_state);
    }

    public function test_audit_log_can_record_failure()
    {
        $this->actingAs($this->user);

        $log = $this->auditService->logFailure(
            'payment.processed',
            'PaymentTransaction',
            1,
            'Insufficient funds',
            ['attempt' => 1]
        );

        $this->assertEquals('Failure', $log->status);
        $this->assertEquals('Insufficient funds', $log->error_message);
    }

    public function test_audit_log_query_scopes()
    {
        $this->actingAs($this->user);

        // Create multiple logs
        $this->auditService->log('registration.created', 'Registration', 1);
        $this->auditService->log('payment.received', 'PaymentTransaction', 1);
        $this->auditService->log('certificate.issued', 'Certificate', 1);

        // Test byAction scope
        $logs = AuditLog::byAction('registration.created')->get();
        $this->assertEquals(1, $logs->count());

        // Test byEntity scope
        $logs = AuditLog::byEntity('Certificate', 1)->get();
        $this->assertEquals(1, $logs->count());

        // Test byActor scope
        $logs = AuditLog::byActor($this->user->id)->get();
        $this->assertEquals(3, $logs->count());
    }

    public function test_audit_log_formatted_action_attribute()
    {
        $this->actingAs($this->user);

        $log = $this->auditService->log('registration.created', 'Registration', 1);

        $this->assertEquals('Registration Created', $log->formatted_action);
    }

    public function test_audit_log_changes_attribute()
    {
        $this->actingAs($this->user);

        $before = ['name' => 'John', 'email' => 'old@example.com', 'status' => 'pending'];
        $after = ['name' => 'John', 'email' => 'new@example.com', 'status' => 'verified'];

        $log = $this->auditService->log(
            'registration.updated',
            'Registration',
            1,
            [],
            $before,
            $after
        );

        $changes = $log->changes;
        
        // Only changed fields should be in changes
        $this->assertArrayHasKey('email', $changes);
        $this->assertArrayHasKey('status', $changes);
        $this->assertArrayNotHasKey('name', $changes);
        
        $this->assertEquals('old@example.com', $changes['email']['from']);
        $this->assertEquals('new@example.com', $changes['email']['to']);
    }

    public function test_audit_log_relative_time()
    {
        $this->actingAs($this->user);

        $log = $this->auditService->log('registration.created', 'Registration', 1);

        $this->assertStringContainsString('ago', $log->relative_time);
    }

    public function test_audit_log_actor_name_attribute()
    {
        $this->actingAs($this->user);

        $log = $this->auditService->log('registration.created', 'Registration', 1);

        $this->assertEquals($this->user->name, $log->actor_name);
    }

    public function test_audit_log_status_badge()
    {
        $this->actingAs($this->user);

        $successLog = $this->auditService->log('registration.created', 'Registration', 1);
        $this->assertStringContainsString('green', $successLog->status_badge);

        $failureLog = $this->auditService->logFailure(
            'payment.processed',
            'PaymentTransaction',
            1,
            'Error occurred'
        );
        $this->assertStringContainsString('red', $failureLog->status_badge);
    }

    public function test_audit_log_immutable()
    {
        $this->actingAs($this->user);

        $log = $this->auditService->log('registration.created', 'Registration', 1);

        // Attempting to update should not change updated_at since it's immutable
        $this->assertNull($log->updated_at);

        // directly manipulating the model
        $oldCreatedAt = $log->created_at;
        sleep(1);
        $log->save();
        
        // created_at should not change on save
        $this->assertEquals($oldCreatedAt, $log->created_at);
    }

    public function test_refund_logging_helper()
    {
        $this->actingAs($this->user);

        $log = $this->auditService->logRefund('initiated', 1, [
            'amount' => 50000,
            'reason' => 'User requested',
        ]);

        $this->assertEquals('refund.initiated', $log->action);
        $this->assertEquals('Refund', $log->entity_type);
    }

    public function test_audit_log_by_date_range()
    {
        $this->actingAs($this->user);

        // Create logs
        $log1 = $this->auditService->log('registration.created', 'Registration', 1);
        
        // Fake time progression
        Carbon::setTestNow(now()->addDay());
        $log2 = $this->auditService->log('payment.received', 'PaymentTransaction', 1);

        // Query by date range
        $from = now()->subDays(2)->startOfDay();
        $to = now()->endOfDay();
        $logs = AuditLog::byDateRange($from, $to)->get();

        $this->assertGreaterThanOrEqual(2, $logs->count());
    }

    public function test_audit_controller_index()
    {
        $this->actingAs($this->user);

        // Create audit logs
        $this->auditService->log('registration.created', 'Registration', 1);
        $this->auditService->log('payment.received', 'PaymentTransaction', 1);

        $response = $this->get(route('admin.audit.index'));

        $response->assertStatus(200);
        $response->assertViewHas('logs');
        $response->assertViewHas('actions');
        $response->assertViewHas('entityTypes');
    }

    public function test_audit_controller_show()
    {
        $this->actingAs($this->user);

        $log = $this->auditService->log('registration.created', 'Registration', 1);

        $response = $this->get(route('admin.audit.show', $log));

        $response->assertStatus(200);
        $response->assertViewHas('auditLog');
        $response->assertViewHas('relatedLogs');
    }

    public function test_audit_controller_filters()
    {
        $this->actingAs($this->user);

        $user2 = User::factory()->create([
            'email_verified_at' => now(),
            'two_factor_confirmed_at' => now(),
        ]);

        // Create logs with different actors
        $this->actingAs($this->user);
        $this->auditService->log('registration.created', 'Registration', 1);

        $this->actingAs($user2);
        $this->auditService->log('payment.received', 'PaymentTransaction', 1);

        // Filter by actor
        $response = $this->get(route('admin.audit.index', ['actor_id' => $this->user->id]));

        $response->assertStatus(200);
        $this->assertEquals(1, $response->viewData('logs')->count());
    }

    public function test_audit_controller_export_csv()
    {
        $this->actingAs($this->user);

        $this->auditService->log('registration.created', 'Registration', 1);

        $response = $this->get(route('admin.audit.export'));

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', (string) $response->headers->get('Content-Type'));
        $response->assertHeader('Content-Disposition');
    }
}
