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
- [ ] `GET /api/incidents/index.php` — list all incidents (admin)
- [ ] `GET /api/incidents/show.php?id=` — single incident detail
- [ ] `POST /api/incidents/store.php` — create incident (admin only)
- [ ] `PUT /api/incidents/update.php?id=` — update incident (admin only)
- [ ] `DELETE /api/incidents/destroy.php?id=` — delete incident (admin only)

### 4.2 Evacuation Centers
- [ ] `GET /api/evacuation/index.php` — list all centers
- [ ] `GET /api/evacuation/show.php?id=` — single center
- [ ] `POST /api/evacuation/store.php` — create center (admin only)
- [ ] `PUT /api/evacuation/update.php?id=` — update center (admin only)
- [ ] `DELETE /api/evacuation/destroy.php?id=` — delete center (admin only)

### 4.3 Relief Operations
- [ ] `GET /api/relief/index.php` — list all operations
- [ ] `GET /api/relief/show.php?id=` — single operation
- [ ] `POST /api/relief/store.php` — create (admin only)
- [ ] `PUT /api/relief/update.php?id=` — update (admin only)

### 4.4 Residents (Users)
- [ ] `GET /api/residents/index.php` — list all users with role='user' (admin only)
- [ ] `GET /api/residents/show.php?id=` — single resident (admin only)
- [ ] `PUT /api/residents/update.php?id=` — edit resident info (admin only)
- [ ] `PATCH /api/residents/toggle-status.php?id=` — activate/deactivate (admin only)

### 4.5 Announcements
- [ ] `GET /api/announcements/index.php` — list active announcements (public)
- [ ] `POST /api/announcements/store.php` — create (admin only)
- [ ] `PUT /api/announcements/update.php?id=` — update (admin only)
- [ ] `DELETE /api/announcements/destroy.php?id=` — delete (admin only)

### 4.6 Disaster Alerts
- [ ] `GET /api/alerts/index.php` — list active alerts (public)
- [ ] `POST /api/alerts/store.php` — issue new alert (admin only)
- [ ] `PUT /api/alerts/update.php?id=` — update alert (admin only)
- [ ] `PATCH /api/alerts/deactivate.php?id=` — deactivate alert (admin only)

### 4.7 User Reports
- [ ] `GET /api/user-reports/index.php` — list user's own reports (user), all reports (admin)
- [ ] `GET /api/user-reports/show.php?id=` — single report
- [ ] `POST /api/user-reports/store.php` — submit report with optional photo upload
- [ ] `PUT /api/user-reports/update.php?id=` — edit report (owner only, if still pending)
- [ ] `PATCH /api/user-reports/update-status.php?id=` — admin updates report status

### 4.8 Profile
- [ ] `GET /api/profile/index.php` — get own profile (authenticated user)
- [ ] `PUT /api/profile/update.php` — update own profile fields
- [ ] `PUT /api/profile/change-password.php` — change own password (verify old password first)

---

## Phase 5 — File Uploads

- [ ] Configure `php.ini`: set `upload_max_filesize = 5M`, `post_max_size = 6M`
- [ ] Create `/uploads/reports/` directory
- [ ] In `user-reports/store.php`: validate file type (JPEG/PNG only), validate size, move to `/uploads/reports/`
- [ ] Store relative path in `user_reports.photo_path`
- [ ] Create `GET /api/uploads/{filename}` or serve directly via Apache
- [ ] Restrict direct directory browsing (`Options -Indexes` in `.htaccess`)

---

## Phase 6 — Dashboard Analytics API

- [ ] `GET /api/analytics/summary.php` — return:
  - Total registered residents
  - Total disaster reports
  - Active incidents count
  - Resolved incidents count
- [ ] `GET /api/analytics/by-type.php` — incidents grouped by disaster_type (for pie chart)
- [ ] `GET /api/analytics/monthly.php?year=` — incidents per month (for bar chart)
- [ ] `GET /api/analytics/by-barangay.php` — incidents grouped by barangay (for bar chart)
- [ ] `GET /api/analytics/frequency.php` — disaster frequency over time (for line chart)

---

## Phase 7 — Reports & Export

- [ ] `GET /api/reports/incidents.php?start=&end=&type=&barangay=` — filtered incident data
- [ ] `GET /api/reports/residents.php` — resident list data
- [ ] `GET /api/reports/relief.php?start=&end=` — relief operations data
- [ ] `GET /api/reports/evacuation.php` — evacuation center data
- [ ] `GET /api/reports/export-pdf.php?report=&...filters` — generate and stream PDF using mPDF
- [ ] `GET /api/reports/export-csv.php?report=&...filters` — generate and stream CSV

---

## Phase 8 — Email Notifications

- [ ] Configure PHPMailer with SMTP credentials in `.env`
- [ ] Create `api/helpers/mailer.php` — shared mailer setup function
- [ ] Send welcome email on successful registration
- [ ] Send password reset confirmation email after forgot-password flow completes
- [ ] (Optional) Send alert notification emails to all active users when a new alert is issued

---

## Phase 9 — Frontend Integration

- [ ] Create `assets/js/api.js` — centralized fetch wrapper (base URL, JWT header injection, error handler)
- [ ] Replace all localStorage reads/writes in `app.js` with `fetch()` calls to the new API
- [ ] Replace `auth.js` localStorage session with JWT stored in `localStorage` (token key)
- [ ] Update `login.html` forms to POST to `/api/auth/login.php`
- [ ] Update `register.html` to POST to `/api/auth/register.php`
- [ ] Update `forgot-password.html` steps to call `/api/auth/forgot-password.php`
- [ ] Update all admin pages CRUD operations to call respective API endpoints
- [ ] Update chart data in `admin/dashboard.html` to fetch from `/api/analytics/`
- [ ] Update report buttons to link to `/api/reports/export-pdf.php` with correct filters
- [ ] Update profile page to fetch from and PUT to `/api/profile/`
- [ ] Update user report page to POST multipart/form-data for photo upload
- [ ] Remove `mock-data.js` from all HTML files once live data is confirmed working
- [ ] Fix layout issues in `admin/reports.html` and `admin/settings.html` (see audit.md)

---

## Phase 10 — Security Hardening

- [ ] Enforce HTTPS (configure SSL certificate in XAMPP or reverse proxy)
- [ ] Add CSRF token generation and validation on all state-changing forms
- [ ] Sanitize all inputs with `htmlspecialchars()` and `filter_var()` on the backend
- [ ] Use PDO prepared statements for all DB queries (no raw string concatenation)
- [ ] Set `HttpOnly` and `Secure` flags on any cookies used
- [ ] Add `Content-Security-Policy`, `X-Frame-Options`, `X-Content-Type-Options` headers
- [ ] Implement role check middleware on every protected endpoint
- [ ] Log all failed login attempts to a `security_logs` table
- [ ] Validate file uploads: whitelist MIME types, scan file headers (not just extension)
- [ ] Store uploaded files outside webroot or behind authenticated routes

---

## Phase 11 — Testing

- [ ] Test all API endpoints with a REST client (Postman or Insomnia)
- [ ] Test CRUD operations for each entity (create, read, update, delete)
- [ ] Test auth: valid login, wrong password, inactive user, expired JWT
- [ ] Test role enforcement: user cannot access admin endpoints
- [ ] Test report exports: PDF and CSV download correctly
- [ ] Test photo upload: valid image passes, non-image file rejected
- [ ] Test forgot-password: wrong username, wrong answer, successful reset
- [ ] Verify all chart data is accurate after seeding
- [ ] Regression test all admin pages after removing mock-data.js
- [ ] Regression test all user pages after removing mock-data.js
- [ ] Test on minimum 1366px desktop (admin) and mobile (user module)

---

## Phase 12 — Deployment Prep

- [ ] Move project to production Apache virtual host (or shared hosting)
- [ ] Import `odmis_db` via mysqldump to production server
- [ ] Update `.env` with production DB, SMTP, and JWT credentials
- [ ] Set `display_errors = Off` in `php.ini` for production
- [ ] Set up daily automated MySQL backups
- [ ] Verify HTTPS is enforced on all routes
- [ ] Remove `docs/Prompt.md` and other dev-only files from production build
- [ ] Document admin credentials and handoff to DRRM Office IT staff

---

## Progress Tracker

| Phase | Title                        | Status      |
|-------|------------------------------|-------------|
| 1     | Environment & Setup          | ✅ Done        |
| 2     | Database Schema              | ✅ Done        |
| 3     | Authentication API           | ✅ Done        |
| 4     | Core CRUD APIs               | ⬜ Not Started |
| 5     | File Uploads                 | ⬜ Not Started |
| 6     | Analytics API                | ⬜ Not Started |
| 7     | Reports & Export             | ⬜ Not Started |
| 8     | Email Notifications          | ⬜ Not Started |
| 9     | Frontend Integration         | ⬜ Not Started |
| 10    | Security Hardening           | ⬜ Not Started |
| 11    | Testing                      | ⬜ Not Started |
| 12    | Deployment Prep              | ⬜ Not Started |
