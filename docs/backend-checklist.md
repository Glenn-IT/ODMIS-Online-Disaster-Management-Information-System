# Backend & Database Implementation Checklist

**Project:** ODMIS — Online Disaster Management Information System
**Stack:** PHP 8.x · MySQL 8.x · REST API · JWT Auth
**Environment:** XAMPP (Windows)

---

## Phase 1 — Environment & Project Setup

- [x] Verify XAMPP is running Apache 2.4+ and MySQL 8.x — PHP 8.2.12 · MariaDB 10.4.32
- [x] Create MySQL database: `odmis_db`
- [ ] ~~Create a dedicated DB user with limited privileges (not root)~~ — Skipped: `mysql.db` Aria table corrupted on local XAMPP; using `root` for dev. Fix before production.
- [x] Create `/api/` folder at project root for all backend endpoints
- [x] Create `/config/` folder for DB connection and constants
- [x] Create `/uploads/` folder for user photo uploads (set permissions)
- [x] Install Composer (PHP dependency manager) — v2.9.5
- [x] Install PHP libraries via Composer:
  - [x] `firebase/php-jwt` v7.1.0 — JWT token generation and verification
  - [ ] ~~`PHPMailer/PHPMailer`~~ — Skipped (email feature not needed)
  - [x] `mpdf/mpdf` v8.3.1 — PDF report generation
- [x] Create `config/env.php` for secrets (DB credentials, JWT secret, upload limits)
- [x] Add `config/env.php` to `.gitignore`
- [x] Create `config/database.php` — PDO connection singleton (tested ✅)
- [x] Constants merged into `config/env.php` (JWT expiry, upload limits, app URL)
- [x] Create `api/helpers/response.php` — standard JSON response helper (`success()`, `error()`)
- [x] Create `api/middleware/auth.php` — JWT verification middleware (`require_auth()`, `require_admin()`)
- [x] Enable CORS headers in `api/.htaccess` + OPTIONS preflight handler

---

## Phase 2 — Database Schema

### 2.1 Users Table
- [x] Create `users` table:
  ```sql
  id, username (unique), email (unique), password_hash,
  role (enum: admin/user), full_name, contact_number,
  date_of_birth, address, status (enum: active/inactive),
  security_question, security_answer_hash, created_at, updated_at
  ```
- [x] Add unique index on `username` and `email`

### 2.2 Disaster Incidents Table
- [x] Create `incidents` table:
  ```sql
  id, incident_code (unique), disaster_type, title, description,
  location, barangay, municipality, incident_date, incident_time,
  severity (enum: Low/Moderate/High/Critical), status (enum: Active/Resolved),
  reported_by, created_at, updated_at
  ```

### 2.3 Evacuation Centers Table
- [x] Create `evacuation_centers` table:
  ```sql
  id, center_code (unique), center_name, location, barangay,
  capacity, occupied_slots, contact_person, contact_number,
  status (enum: Open/Closed), created_at, updated_at
  ```

### 2.4 Relief Operations Table
- [x] Create `relief_operations` table:
  ```sql
  id, batch_number (unique), operation_date, barangay,
  relief_type, quantity, unit, status (enum: Pending/In Progress/Completed),
  distributed_by, notes, created_at, updated_at
  ```

### 2.5 Announcements Table
- [x] Create `announcements` table:
  ```sql
  id, title, body, category, published_by (FK → users.id),
  published_at, is_active, created_at, updated_at
  ```

### 2.6 Disaster Alerts Table
- [x] Create `disaster_alerts` table:
  ```sql
  id, alert_type, title, description, affected_areas,
  severity (enum: Low/Moderate/High/Critical), status (enum: Active/Resolved),
  issued_by (FK → users.id), issued_at, expires_at, created_at, updated_at
  ```

### 2.7 User Reports Table
- [x] Create `user_reports` table:
  ```sql
  id, user_id (FK → users.id), incident_type, description,
  location, report_date, photo_path, status (enum: Pending/Reviewed/Resolved),
  reviewed_by (FK → users.id), created_at, updated_at
  ```

### 2.8 Migrations
- [x] Write SQL migration file: `database/migrations/001_create_tables.sql`
- [x] Write SQL seed file: `database/seeds/001_sample_data.sql` (ported from mock-data.js, passwords bcrypt-hashed)
- [x] Test: migrations and seeds ran successfully on `odmis_db` — 5 users · 10 incidents · 5 evac centers · 8 relief ops · 6 announcements · 6 alerts ✅

---

## Phase 3 — Authentication API

- [x] `POST /api/auth/login.php`
  - Validate username + password against `users` table
  - Compare password with `password_verify()` (bcrypt) ✅
  - Return JWT token + user role on success ✅
  - Return 401 on failure (generic message — no username hint) ✅
- [x] `POST /api/auth/register.php`
  - Validate all fields server-side (length, email format, PH phone) ✅
  - Check username and email uniqueness in DB ✅
  - Hash password with `password_hash()` (PASSWORD_BCRYPT) ✅
  - Hash security answer (lowercased before hashing) ✅
  - Insert new user (role = 'user', status = 'active') ✅
  - Return success + JWT ✅
- [x] `POST /api/auth/forgot-password.php`
  - Step 1: Verify username exists → return security question ✅
  - Step 2: Verify security answer hash (case-insensitive) ✅
  - Step 3: Hash and update new password ✅
- [x] `POST /api/auth/logout.php` — validates token then acknowledges (client deletes JWT) ✅
- [x] `GET /api/auth/me.php` — return current user profile from JWT ✅
- [x] Rate limiting on login: 5 attempts per 15 min per IP (file-based cache) ✅
- [x] Lockout clears automatically after 15-minute window ✅

---

## Phase 4 — Core CRUD APIs

### 4.1 Incidents
- [x] `GET /api/incidents/index.php` — list all (admin); filters: type, barangay, status, date range
- [x] `GET /api/incidents/show.php?id=` — single incident detail
- [x] `POST /api/incidents/store.php` — create (admin only); validates type, severity, unique code
- [x] `PUT /api/incidents/update.php?id=` — partial update (admin only)
- [x] `DELETE /api/incidents/destroy.php?id=` — delete (admin only)

### 4.2 Evacuation Centers
- [x] `GET /api/evacuation/index.php` — list all; includes `available_slots` computed column
- [x] `GET /api/evacuation/show.php?id=` — single center with available_slots
- [x] `POST /api/evacuation/store.php` — create (admin only); validates capacity vs occupied
- [x] `PUT /api/evacuation/update.php?id=` — partial update (admin only)
- [x] `DELETE /api/evacuation/destroy.php?id=` — delete (admin only)

### 4.3 Relief Operations
- [x] `GET /api/relief/index.php` — list all (admin); filters: barangay, status, date range
- [x] `GET /api/relief/show.php?id=` — single operation (admin only)
- [x] `POST /api/relief/store.php` — create (admin only); validates unique batch number
- [x] `PUT /api/relief/update.php?id=` — partial update (admin only)

### 4.4 Residents (Users)
- [x] `GET /api/residents/index.php` — list role='user' (admin only); filters: status, search
- [x] `GET /api/residents/show.php?id=` — single resident (admin only)
- [x] `PUT /api/residents/update.php?id=` — edit info (admin only); validates email & phone
- [x] `PATCH /api/residents/toggle-status.php?id=` — toggles active ↔ inactive (admin only)

### 4.5 Announcements
- [x] `GET /api/announcements/index.php` — active announcements (public); JOIN to users for name
- [x] `POST /api/announcements/store.php` — create (admin only); records published_by from JWT
- [x] `PUT /api/announcements/update.php?id=` — partial update incl. is_active toggle (admin only)
- [x] `DELETE /api/announcements/destroy.php?id=` — delete (admin only)

### 4.6 Disaster Alerts
- [x] `GET /api/alerts/index.php` — active alerts by default (public); ?all=1 for admin
- [x] `POST /api/alerts/store.php` — issue alert (admin only); records issued_by from JWT
- [x] `PUT /api/alerts/update.php?id=` — partial update (admin only)
- [x] `PATCH /api/alerts/deactivate.php?id=` — sets status=Resolved (admin only)

### 4.7 User Reports
- [x] `GET /api/user-reports/index.php` — own reports (user) or all reports (admin)
- [x] `GET /api/user-reports/show.php?id=` — single report; ownership enforced for users
- [x] `POST /api/user-reports/store.php` — submit with optional photo upload (multipart)
- [x] `PUT /api/user-reports/update.php?id=` — edit own Pending report (or admin any)
- [x] `PATCH /api/user-reports/update-status.php?id=` — admin sets Pending/Reviewed/Resolved

### 4.8 Profile
- [x] `GET /api/profile/index.php` — own profile (authenticated user)
- [x] `PUT /api/profile/update.php` — update own fields; checks email uniqueness
- [x] `PUT /api/profile/change-password.php` — verifies old password before hashing new one

---

## Phase 5 — File Uploads

- [x] Configure `php.ini`: already set to 40M — app enforces 5MB cap in `config/env.php` (UPLOAD_MAX_SIZE)
- [x] `/uploads/reports/` directory created (Phase 1); confirmed writable ✅
- [x] `user-reports/store.php`: validates MIME via `finfo` (not just extension), enforces 5MB, moves file with unique `uniqid` filename
- [x] `photo_path` stored as `uploads/reports/{filename}` in `user_reports` table
- [x] `GET /api/uploads/serve.php?file=` — serves files with auth check, MIME validation, path-traversal protection, and `Cache-Control` header
- [x] `/uploads/.htaccess` — `Options -Indexes`, `php_flag engine off`, PHP files denied

---

## Phase 6 — Dashboard Analytics API

- [x] `GET /api/analytics/summary.php` — returns 8 stat-card values:
  - total_residents (4), total_reports (10), active_incidents (4), resolved_incidents (6)
  - active_alerts (5), open_evac_centers (4), pending_user_reports, relief_in_progress ✅
- [x] `GET /api/analytics/by-type.php` — all 5 types with total/active/resolved + chart labels/values array ✅
- [x] `GET /api/analytics/monthly.php?year=` — full 12-month scaffold (zeroed gaps); multi-year support ✅
- [x] `GET /api/analytics/by-barangay.php` — per-barangay totals + critical/high breakdown ✅
- [x] `GET /api/analytics/frequency.php` — monthly pivot by type with Chart.js `series` format ✅

---

## Phase 7 — Reports & Export

- [x] `GET /api/reports/incidents.php` — filters: start, end, type, barangay, status, severity; returns count + filters + data ✅
- [x] `GET /api/reports/residents.php` — filters: status; returns count + active/inactive breakdown; password_hash excluded ✅
- [x] `GET /api/reports/relief.php` — filters: start, end, barangay, status; returns count + total_items ✅
- [x] `GET /api/reports/evacuation.php` — filters: status, barangay; returns count + total_capacity + total_occupied + total_available ✅
- [x] `GET /api/reports/export-pdf.php?report=` — mPDF generates styled PDF (29KB+); DRRM header, green theme, filter summary; landscape for wide tables; force download ✅
- [x] `GET /api/reports/export-csv.php?report=` — streams CSV with UTF-8 BOM (Excel-safe), meta header rows, all columns; force download ✅

---

## Phase 8 — Email Notifications

> **PERMANENTLY SKIPPED** — PHPMailer is not installed. Email/Gmail feature is not needed.

- [x] ~~Configure PHPMailer with SMTP credentials in `.env`~~ — Skipped
- [x] ~~Create `api/helpers/mailer.php`~~ — Skipped
- [x] ~~Send welcome email on registration~~ — Skipped
- [x] ~~Send password reset confirmation email~~ — Skipped
- [x] ~~Send alert notification emails~~ — Skipped

---

## Phase 9 — Frontend Integration

- [x] Create `assets/js/api.js` — centralized fetch wrapper (BASE URL, JWT header injection, error handler, `download()` for PDF/CSV)
- [x] Rewrite `assets/js/auth.js` — JWT-based session; `getSession()`, `requireAdmin()`, `requireUser()`, `logout()`
- [x] Update `login.html` — async `handleLogin()` → `ApiClient.post('/auth/login.php')`
- [x] Update `register.html` — async submit → `ApiClient.post('/auth/register.php')`; server-side field errors surfaced
- [x] Update `forgot-password.html` — all 3 steps async → `ApiClient.post('/auth/forgot-password.php', { step })`
- [x] Update `admin/dashboard.html` — `Promise.all` analytics + incidents; snake_case fields; chart aggregation fixed
- [x] Update `admin/incidents.html` — full CRUD via API; snake_case fields; toggle status via PATCH
- [x] Update `admin/evacuation.html` — full CRUD via API; Open/Closed status; snake_case fields
- [x] Update `admin/residents.html` — load + toggle-status via API; snake_case fields
- [x] Update `admin/relief.html` — full CRUD via API; snake_case fields
- [x] Update `admin/reports.html` — async data loaders with query strings; `ApiClient.download()` for PDF/CSV export
- [x] Update `admin/settings.html` — change password + security question via API; no localStorage
- [x] Update `user/dashboard.html` — `Promise.all` alerts + announcements + evacuation + analytics
- [x] Update `user/alerts.html` — load alerts via API; snake_case fields
- [x] Update `user/announcements.html` — load announcements + alerts badge via API; `date_posted`/`posted_by`
- [x] Update `user/evacuation-centers.html` — load centers via API; `center_name`, `occupied_slots`, `contact_person`, Open/Closed
- [x] Update `user/report-incident.html` — submit via `ApiClient.upload()` multipart; load own reports from API
- [x] Update `user/profile.html` — load from `/profile/index.php`; save via PUT; password change via API
- [x] All `initMockData()` calls removed from every page
- [x] All `DOMContentLoaded` handlers converted to `async function`
- [x] Auth guards updated to `if (!Auth.requireAdmin/User()) return;` pattern

---

## Phase 10 — Security Hardening

> **SKIPPED** — Deferred; not required for current development/demo build.

- [ ] ~~Enforce HTTPS~~ — Skipped
- [ ] ~~Add CSRF token generation and validation~~ — Skipped
- [ ] ~~Add security headers (CSP, X-Frame-Options, etc.)~~ — Skipped
- [ ] ~~Log failed login attempts to `security_logs` table~~ — Skipped

---

## Phase 11 — Testing

> **SKIPPED** — Deferred; not required for current development/demo build.

## Phase 12 — Deployment Prep

> **SKIPPED** — Deferred; not required for current development/demo build.

---

## Progress Tracker

| Phase | Title                        | Status      |
|-------|------------------------------|-------------|
| 1     | Environment & Setup          | ✅ Done        |
| 2     | Database Schema              | ✅ Done        |
| 3     | Authentication API           | ✅ Done        |
| 4     | Core CRUD APIs               | ✅ Done        |
| 5     | File Uploads                 | ✅ Done        |
| 6     | Analytics API                | ✅ Done        |
| 7     | Reports & Export             | ✅ Done        |
| 8     | Email Notifications          | ⏭️ Skipped     |
| 9     | Frontend Integration         | ✅ Done        |
| 10    | Security Hardening           | ⏭️ Skipped     |
| 11    | Testing                      | ⏭️ Skipped     |
| 12    | Deployment Prep              | ⏭️ Skipped     |
