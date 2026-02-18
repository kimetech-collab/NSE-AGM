# PHASE 4 — DATABASE DESIGN

## 5.1 SCHEMA & RELATIONSHIPS

### Primary Tables

```sql
-- USERS (authentication + admin accounts)
CREATE TABLE users (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) NOT NULL UNIQUE,
  email_verified_at TIMESTAMP NULL,
  password VARCHAR(255) NOT NULL,
  first_name VARCHAR(100) NOT NULL,
  surname VARCHAR(100) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  gender ENUM('Male', 'Female', 'Other') DEFAULT NULL,
  role ENUM('Super Admin', 'Finance Admin', 'Registration Admin', 'Accreditation Officer', 'Support Agent', 'Registrant') DEFAULT 'Registrant',
  is_admin BOOLEAN DEFAULT FALSE,
  mfa_enabled BOOLEAN DEFAULT FALSE,
  mfa_method ENUM('Email OTP', 'TOTP') DEFAULT 'Email OTP',
  mfa_secret VARCHAR(255) NULL,
  last_mfa_at TIMESTAMP NULL,
  failed_mfa_attempts INT DEFAULT 0,
  last_failed_mfa_ip VARCHAR(45),
  last_failed_mfa_at TIMESTAMP NULL,
  remember_token VARCHAR(100) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at TIMESTAMP NULL
);

-- REGISTRATIONS (participant registrations)
CREATE TABLE registrations (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NOT NULL,
  membership_category ENUM('Student', 'Graduate', 'Corporate', 'Fellow', 'Honorary Fellow', 'Non-Member') DEFAULT 'Non-Member',
  membership_number VARCHAR(50) NULL,
  is_nse_member BOOLEAN DEFAULT FALSE,
  attendance_type ENUM('Physical', 'Virtual') NOT NULL,
  organization VARCHAR(255) NULL,
  self_attestation_accepted BOOLEAN DEFAULT FALSE,
  
  -- Payment tracking
  payment_status ENUM('Pending', 'Paid', 'Failed', 'Refunded', 'Partially Refunded') DEFAULT 'Pending',
  amount_paid DECIMAL(10, 2) NULL,
  paystack_reference VARCHAR(100) NULL UNIQUE,
  payment_timestamp TIMESTAMP NULL,
  refund_status ENUM('No Refund', 'Refunded', 'Partially Refunded') DEFAULT 'No Refund',
  refund_amount DECIMAL(10, 2) NULL,
  refund_reason TEXT NULL,
  
  -- Pricing
  pricing_version_id BIGINT UNSIGNED NOT NULL,
  price_locked_at TIMESTAMP NOT NULL,
  amount_due DECIMAL(10, 2) NOT NULL,
  is_early_bird BOOLEAN DEFAULT FALSE,
  
  -- QR & Tickets
  qr_token_hash VARCHAR(255) NOT NULL UNIQUE,
  qr_token_generated_at TIMESTAMP NOT NULL,
  
  -- Attendance tracking
  attendance_status ENUM('Not Attended', 'Physical', 'Virtual') DEFAULT 'Not Attended',
  physical_checkin_at TIMESTAMP NULL,
  
  -- Virtual session tracking
  virtual_total_seconds INT DEFAULT 0,
  
  -- Certificate eligibility
  certificate_eligible BOOLEAN DEFAULT FALSE,
  certificate_status ENUM('Not Issued', 'Ready for Release', 'Issued', 'Revoked') DEFAULT 'Not Issued',
  certificate_id VARCHAR(50) NULL UNIQUE,
  certificate_issued_at TIMESTAMP NULL,
  
  -- Email verification
  email_verified_at TIMESTAMP NULL,
  otp_code VARCHAR(6) NULL,
  otp_expires_at TIMESTAMP NULL,
  otp_attempts INT DEFAULT 0,
  
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at TIMESTAMP NULL,
  
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (pricing_version_id) REFERENCES pricing_versions(id),
  
  INDEX idx_user_id (user_id),
  INDEX idx_payment_status (payment_status),
  INDEX idx_paystack_reference (paystack_reference),
  INDEX idx_qr_token_hash (qr_token_hash),
  INDEX idx_attendance_status (attendance_status),
  INDEX idx_certificate_id (certificate_id),
  INDEX idx_created_at (created_at)
);

-- PRICING_VERSIONS (immutable pricing history)
CREATE TABLE pricing_versions (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  version_number INT NOT NULL,
  is_active BOOLEAN DEFAULT TRUE,
  early_bird_active BOOLEAN DEFAULT FALSE,
  early_bird_start_at TIMESTAMP NULL,
  early_bird_end_at TIMESTAMP NULL,
  created_by BIGINT UNSIGNED NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  FOREIGN KEY (created_by) REFERENCES users(id),
  INDEX idx_active (is_active),
  INDEX idx_created_at (created_at)
);

-- PRICING_ITEMS (matrix: category × attendance type)
CREATE TABLE pricing_items (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  pricing_version_id BIGINT UNSIGNED NOT NULL,
  category ENUM('Student', 'Graduate', 'Corporate', 'Fellow', 'Honorary Fellow', 'Non-Member') NOT NULL,
  attendance_type ENUM('Physical', 'Virtual') NOT NULL,
  amount DECIMAL(10, 2) NOT NULL,
  category_cap INT NULL,
  category_current_count INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  UNIQUE KEY unique_pricing (pricing_version_id, category, attendance_type),
  FOREIGN KEY (pricing_version_id) REFERENCES pricing_versions(id) ON DELETE CASCADE,
  INDEX idx_pricing_version (pricing_version_id)
);

-- PAYMENT_TRANSACTIONS (immutable payment log)
CREATE TABLE payment_transactions (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  registration_id BIGINT UNSIGNED NOT NULL,
  paystack_reference VARCHAR(100) NOT NULL UNIQUE,
  amount DECIMAL(10, 2) NOT NULL,
  status ENUM('Pending', 'Success', 'Failed') NOT NULL,
  payment_method VARCHAR(100) NULL,
  paystack_response JSON NULL,
  webhook_received_at TIMESTAMP NULL,
  webhook_verified_at TIMESTAMP NULL,
  webhook_signature VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (registration_id) REFERENCES registrations(id) ON DELETE CASCADE,
  INDEX idx_paystack_reference (paystack_reference),
  INDEX idx_registration_id (registration_id),
  INDEX idx_created_at (created_at)
);

-- REFUNDS (immutable refund log)
CREATE TABLE refunds (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  payment_transaction_id BIGINT UNSIGNED NOT NULL,
  refund_amount DECIMAL(10, 2) NOT NULL,
  refund_reference VARCHAR(100) NOT NULL UNIQUE,
  refund_reason TEXT NOT NULL,
  status ENUM('Initiated', 'Pending', 'Completed', 'Failed') DEFAULT 'Initiated',
  initiated_by BIGINT UNSIGNED NOT NULL,
  initiated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  completed_at TIMESTAMP NULL,
  paystack_response JSON NULL,
  
  FOREIGN KEY (payment_transaction_id) REFERENCES payment_transactions(id),
  FOREIGN KEY (initiated_by) REFERENCES users(id),
  INDEX idx_status (status),
  INDEX idx_initiated_at (initiated_at)
);

-- CERTIFICATES (immutable certificate records)
CREATE TABLE certificates (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  registration_id BIGINT UNSIGNED NOT NULL UNIQUE,
  certificate_id VARCHAR(50) NOT NULL UNIQUE,
  participant_name VARCHAR(255) NOT NULL,
  category VARCHAR(100) NOT NULL,
  attendance_type VARCHAR(50) NOT NULL,
  issued_at TIMESTAMP NOT NULL,
  issued_by BIGINT UNSIGNED NOT NULL,
  pdf_path VARCHAR(500) NULL,
  pdf_hash VARCHAR(255) NULL,
  revoke_reason TEXT NULL,
  revoked_at TIMESTAMP NULL,
  revoked_by BIGINT UNSIGNED NULL,
  public_verification_count INT DEFAULT 0,
  last_verified_at TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  FOREIGN KEY (registration_id) REFERENCES registrations(id) ON DELETE CASCADE,
  FOREIGN KEY (issued_by) REFERENCES users(id),
  FOREIGN KEY (revoked_by) REFERENCES users(id),
  INDEX idx_certificate_id (certificate_id),
  INDEX idx_registration_id (registration_id),
  INDEX idx_created_at (created_at)
);

-- ATTENDANCE_SESSIONS (virtual attendance heartbeats)
CREATE TABLE attendance_sessions (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  registration_id BIGINT UNSIGNED NOT NULL,
  session_id VARCHAR(100) NOT NULL,
  session_start TIMESTAMP NOT NULL,
  session_end TIMESTAMP NULL,
  total_seconds INT DEFAULT 0,
  user_agent TEXT NULL,
  ip_address VARCHAR(45) NULL,
  platform ENUM('YouTube', 'Zoom', 'Jitsi') DEFAULT 'YouTube',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (registration_id) REFERENCES registrations(id) ON DELETE CASCADE,
  INDEX idx_registration_id (registration_id),
  INDEX idx_session_id (session_id),
  INDEX idx_created_at (created_at)
);

-- QR_SCANS (check-in log)
CREATE TABLE qr_scans (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  registration_id BIGINT UNSIGNED NOT NULL,
  qr_token_hash VARCHAR(255) NOT NULL,
  scanned_by BIGINT UNSIGNED NOT NULL,
  scan_result ENUM('Valid', 'Invalid', 'Already Checked In', 'Unpaid', 'Refunded', 'Not Found') NOT NULL,
  scan_timestamp TIMESTAMP NOT NULL,
  device_info VARCHAR(500) NULL,
  location VARCHAR(255) NULL,
  ip_address VARCHAR(45) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  FOREIGN KEY (registration_id) REFERENCES registrations(id) ON DELETE SET NULL,
  FOREIGN KEY (scanned_by) REFERENCES users(id),
  INDEX idx_qr_token (qr_token_hash),
  INDEX idx_scan_timestamp (scan_timestamp),
  INDEX idx_registration_id (registration_id),
  INDEX idx_scanned_by (scanned_by)
);

-- AUDIT_LOGS (immutable audit trail)
CREATE TABLE audit_logs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  actor_id BIGINT UNSIGNED NOT NULL,
  action VARCHAR(150) NOT NULL,
  entity_type VARCHAR(100) NOT NULL,
  entity_id BIGINT UNSIGNED NOT NULL,
  before_state JSON NULL,
  after_state JSON NULL,
  metadata JSON NULL,
  ip_address VARCHAR(45) NOT NULL,
  user_agent TEXT NULL,
  status ENUM('Success', 'Failure') DEFAULT 'Success',
  error_message TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  INDEX idx_actor_id (actor_id),
  INDEX idx_action (action),
  INDEX idx_entity (entity_type, entity_id),
  INDEX idx_created_at (created_at),
  INDEX idx_action_entity (action, entity_type)
);

-- SPONSORS (homepage showcase)
CREATE TABLE sponsors (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  logo_url VARCHAR(500) NOT NULL,
  website_url VARCHAR(500) NULL,
  tier ENUM('Platinum', 'Gold', 'Silver') DEFAULT 'Gold',
  display_order INT NOT NULL,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  INDEX idx_active (is_active),
  INDEX idx_display_order (display_order)
);

-- SYSTEM_SETTINGS (configuration)
CREATE TABLE system_settings (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  setting_key VARCHAR(100) NOT NULL UNIQUE,
  setting_value JSON NOT NULL,
  description TEXT NULL,
  updated_by BIGINT UNSIGNED NOT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (updated_by) REFERENCES users(id),
  INDEX idx_setting_key (setting_key)
);
```

---

## 5.2 INDEXING STRATEGY

**Read-Heavy Queries** (all hit replica):
```sql
-- Most frequent: QR scan lookup (< 100ms required)
SELECT * FROM registrations 
WHERE qr_token_hash = ? AND payment_status = 'Paid' AND deleted_at IS NULL;
INDEX: (qr_token_hash, payment_status, deleted_at)

-- Certificate verification (public, rate-limited)
SELECT c.*, r.* FROM certificates c
JOIN registrations r ON c.registration_id = r.id
WHERE c.certificate_id = ?;
INDEX: (certificate_id) on certificates

-- Admin dashboard KPIs
SELECT payment_status, COUNT(*) 
FROM registrations
GROUP BY payment_status;
OPTIMIZATION: Cache in Redis (5 min TTL), invalidate on webhook
```

---

## 5.3 MIGRATION EXECUTION ORDER

1. **001 — Create users table** (Laravel default)
2. **002 — Create pricing_versions table**
3. **003 — Create pricing_items table**
4. **004 — Create registrations table** (core)
5. **005 — Create payment_transactions table**
6. **006 — Create refunds table**
7. **007 — Create certificates table**
8. **008 — Create attendance_sessions table**
9. **009 — Create qr_scans table**
10. **010 — Create audit_logs table** (immutable)
11. **011 — Create sponsors table**
12. **012 — Create system_settings table**

---

## 5.4 PERFORMANCE CONSIDERATIONS

**Partitioning** (post-launch):
- `audit_logs`: Partition by month for archival
- `payment_transactions`: Partition by year for retention policy

**Replication Setup**:
- Primary: Writes only
- Read Replica: All SELECT queries from dashboards/reports/verification
- Failover: Automatic via MHA or Percona XtraDB Cluster
- Replica lag tolerance: < 1 second

**Archive Strategy** (post-event):
- Month after event: Archive audit_logs older than 12 months to cold storage
- Certificates: Archive PDFs to S3 (keep DB record for 5 years for legal)
