# PERFORMANCE OPTIMIZATION STRATEGIES

## Caching Strategy (Redis)

**Cache Tiers** (all with TTL):

| Data | TTL | Refresh Trigger | Use Case |
|---|---|---|---|
| KPI Dashboard counts | 5 min | Manual refresh + webhook | Admin overview |
| Pricing matrix | 10 min | Admin price update event | /pricing page |
| Sponsor logos | 30 min | Manual sponsor edit | Homepage render |
| User sessions | 1 hour | User logout | Auth state |
| Certificate count | 1 day | Certificate issued event | /verify/{id} page |
| Offline QR cache | Hourly | Scheduled sync job | Accreditation tablet |

**Cache Invalidation Events**:
```php
// Event: PaymentConfirmed
Cache::forget('kpi:total_paid');
Cache::forget('kpi:revenue_total');

// Event: CertificateIssued
Cache::forget('kpi:certificates_issued');
Cache::increment('certificate:public_lookup_count');

// Event: PricingUpdated
Cache::forget('pricing:matrix');
Cache::forget('homepage:pricing_badge');
```

---

## Database Query Optimization

**Eager Loading** (prevent N+1):
```php
// Admin registration list
Registration::with('user', 'pricing_version', 'certificates')
  ->where('payment_status', 'Paid')
  ->get();

// Dashboard stats (cached)
$stats = Cache::remember('dashboard:stats', 300, function() {
  return Registration::selectRaw('payment_status, COUNT(*) as count')
    ->groupBy('payment_status')
    ->get();
});
```

**Connection Pooling** (pgBouncer):
```bash
# MySQL equivalent: ProxySQL or Percona XtraDB Cluster
pool_mode = transaction      # Reuse after transaction
max_client_conn = 1000       # Total limit
default_pool_size = 100      # Per-user pool
```

**Prepared Statements:**
- Laravel Eloquent: Auto-prepared
- Raw queries: Use `DB::statement('...', $bindings)`

**Batch Operations:**
```php
// Bulk attendance update
Registration::whereIn('id', $scanned_ids)
  ->update(['attendance_status' => 'Physical']);

// Bulk certificate generation
Certificate::upsert(
  $certificates_to_issue,
  ['registration_id'],
  ['certificate_id', 'issued_at']
);
```

---

## Infrastructure Scaling

**Redis Failover** (HA recommended):

```
Redis Sentinel (3 nodes):
  - 1 Master + 2 Replicas
  - Automatic failover: < 10s
  - Persistence: RDB snapshots every 5 min
  - Memory: 4 GB per node (session + cache)
```

**Database Scaling:**

```
Write Layer:
  - Primary: MySQL 8 (r5.2xlarge: 64 GB, 8 vCPU)
  - Replication: Semi-synchronous (1 replica ACK required)
  - Backup: Daily snapshots + transaction logs (PITR 30 days)
  
Read Layer:
  - 2+ Read Replicas (MySQL 8, r5.xlarge)
  - All SELECT → Replica via ProxySQL
  - Replica lag: Monitor < 1 second (alert if > 5s)
```

**Application Layer:**

```
Stateless nodes:
  - 2+ Laravel app servers (t3.2xlarge: 32 GB, 8 vCPU)
  - Load balancer: Cloudflare (health check 30s)
  - Session storage: Redis (not file-based)
  - Horizontal scale: Add node, Cloudflare auto-discovers
```

**Load Profile:**

| Scenario | Concurrent | Requests/min | Response Time |
|---|---|---|---|
| Normal | 500 | 1,000 | < 200 ms |
| Registration spike | 2,000 | 10,000 | < 500 ms |
| Burst (event day) | 3,000 | 15,000 | < 1 s |
| QR scan peak | 500 | 2,000 | < 100 ms ⚡ |

---

## Monitoring & Alerts

**Metrics to Track:**
- Request latency (P50, P95, P99)
- Error rate (target: < 0.1%)
- Cache hit rate (target: > 90%)
- Database replica lag (alert: > 5s)
- Redis memory usage (alert: > 80%)
- Payment webhook latency (target: < 500ms)
- QR scan latency (target: < 100ms)

**Tools:**
- Sentry: Error tracking + source maps
- New Relic: APM + infrastructure monitoring
- CloudWatch: Logs + custom metrics
- PagerDuty: On-call alerts
