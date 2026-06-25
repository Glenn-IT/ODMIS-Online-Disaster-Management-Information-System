# ODMIS System Audit

**Date:** 2026-06-26
**Auditor:** System Review
**Project:** Online Disaster Management Information System (ODMIS)
**Client:** DRRM Office — Sta. Cruz, Davao del Sur

---

## 1. Executive Summary

ODMIS is currently a **frontend-only prototype**. All 20 pages are built and functional using HTML5, Bootstrap 5, vanilla JavaScript, and localStorage as a simulated data store. No backend, database, or server-side logic exists. The system is ready for backend integration.

---

## 2. Current Tech Stack

| Layer        | Technology            | Status         |
|--------------|-----------------------|----------------|
| Markup       | HTML5                 | ✅ Implemented |
| Styling      | CSS3 + Bootstrap 5.3.2 | ✅ Implemented |
| Icons        | Font Awesome 6.5.0    | ✅ Implemented |
| Charts       | Chart.js              | ✅ Implemented |
| Scripting    | Vanilla JavaScript ES6 | ✅ Implemented |
| Data Storage | localStorage (mock)   | ⚠️ Temporary   |
| Backend      | None                  | ❌ Missing     |
| Database     | None                  | ❌ Missing     |
| APIs         | None                  | ❌ Missing     |
| Auth (real)  | None (localStorage)   | ❌ Missing     |
| Hosting      | XAMPP (local)         | ⚠️ Development only |

---

## 3. Page Inventory

### Authentication Pages

| Page                  | File                    | Status      | Notes                          |
|-----------------------|-------------------------|-------------|--------------------------------|
| Entry / Redirect      | `index.html`            | ✅ Done     | Redirects to login             |
| Login (Admin + User)  | `login.html`            | ✅ Done     | Dual-tab, toast feedback       |
| Registration          | `register.html`         | ✅ Done     | Full validation, security Q    |
| Forgot Password       | `forgot-password.html`  | ✅ Done     | 3-step flow via security Q     |

### Admin Module (Desktop Only ≥1366px)

| Page                    | File                       | Status      | Notes                               |
|-------------------------|----------------------------|-------------|-------------------------------------|
| Admin Dashboard         | `admin/dashboard.html`     | ✅ Done     | Stat cards + 4 Chart.js charts      |
| Incident Management     | `admin/incidents.html`     | ✅ Done     | Full CRUD via localStorage          |
| Evacuation Centers      | `admin/evacuation.html`    | ✅ Done     | Full CRUD via localStorage          |
| Resident Management     | `admin/residents.html`     | ✅ Done     | View/Edit/Deactivate                |
| Relief Operations       | `admin/relief.html`        | ✅ Done     | Add/Edit/Track distribution         |
| Reports                 | `admin/reports.html`       | ⚠️ Layout issue | UI only, no actual export      |
| Settings                | `admin/settings.html`      | ⚠️ Layout issue | Security Q + Change Password   |

### User Module (Mobile Responsive)

| Page                    | File                              | Status  | Notes                         |
|-------------------------|-----------------------------------|---------|-------------------------------|
| User Dashboard          | `user/dashboard.html`             | ✅ Done | Alerts, contacts, reports     |
| Report Incident         | `user/report-incident.html`       | ✅ Done | Submit/Edit/Cancel            |
| Disaster Alerts         | `user/alerts.html`                | ✅ Done | Status color-coded alerts     |
| Evacuation Centers      | `user/evacuation-centers.html`    | ✅ Done | Center list with slots        |
| Announcements           | `user/announcements.html`         | ✅ Done | DRRM announcements feed       |
| Profile                 | `user/profile.html`               | ✅ Done | View/Edit, Change Password    |

---

## 4. Current Data Model (localStorage)

The mock data structure in `assets/js/mock-data.js` defines the following entities. These will map directly to database tables during backend implementation.

| localStorage Key         | Entity              | Key Fields                                                                 |
|--------------------------|---------------------|----------------------------------------------------------------------------|
| `odmis_users`            | Users               | id, username, email, password, role, fullName, contactNumber, dateOfBirth, address, status, securityQuestion, securityAnswer, createdAt |
| `odmis_incidents`        | Disaster Incidents  | id, disasterType, title, description, location, barangay, municipality, date, time, severity, status, reportedBy, createdAt |
| `odmis_evacuation_centers` | Evacuation Centers | id, name, location, capacity, occupied, contactPerson, contactNumber      |
| `odmis_relief_operations` | Relief Operations  | id, batchNumber, date, barangay, reliefType, quantity                     |
| `odmis_announcements`    | Announcements       | id, title, description, source, date                                       |
| `odmis_alerts`           | Disaster Alerts     | id, type, severity, message, date                                          |
| `odmis_user_reports`     | User Reports        | id, userId, incidentType, description, location, date, photo              |
| `odmis_session`          | Active Session      | username, role, createdAt                                                  |

---

## 5. Authentication Audit

| Feature                     | Current State            | Required State             |
|-----------------------------|--------------------------|----------------------------|
| Password storage            | Plaintext in localStorage | Hashed (bcrypt/Argon2)    |
| Session management          | localStorage key         | Server-side sessions / JWT |
| Role-based access control   | Client-side JS check     | Server-side middleware     |
| Registration uniqueness     | localStorage scan        | DB unique constraint       |
| Forgot password             | Security Q in localStorage | DB-backed + email token  |
| Brute-force protection      | None                     | Rate limiting / lockout    |
| CSRF protection             | None                     | CSRF tokens on forms       |

---

## 6. Known Issues

| ID  | Page                     | Issue                                 | Severity |
|-----|--------------------------|---------------------------------------|----------|
| 01  | `admin/reports.html`     | Layout not working properly           | Medium   |
| 02  | `admin/settings.html`    | Layout not working properly           | Medium   |
| 03  | All pages                | Passwords stored as plaintext         | Critical |
| 04  | All pages                | No server-side auth — easily bypassed | Critical |
| 05  | All pages                | Data lost on localStorage clear       | High     |
| 06  | `admin/reports.html`     | Print/Export PDF is UI-only, no output | High    |
| 07  | All forms                | No CSRF protection                    | High     |
| 08  | All pages                | No input sanitization server-side     | High     |

---

## 7. Assets Overview

```
assets/
├── css/
│   └── style.css          — Global styles, CSS variables, component styles
└── js/
    ├── app.js             — Core application logic, CRUD operations, UI handlers
    ├── auth.js            — Session detection, login/logout, path-depth navigation
    └── mock-data.js       — Mock data seed (DATA_VERSION = 2), localStorage init
```

---

## 8. Backend Readiness Assessment

| Area                     | Readiness                                                                      |
|--------------------------|--------------------------------------------------------------------------------|
| Data model               | ✅ Fully defined in mock-data.js — maps cleanly to relational tables          |
| UI forms                 | ✅ All fields match required DB columns                                        |
| API surface              | ✅ CRUD operations identified for all 7 entities                               |
| Auth flow                | ✅ Login/register/forgot-password UI complete — needs real server endpoints    |
| File uploads             | ⚠️ Photo upload UI exists — needs server storage (disk or object storage)     |
| Charts / Analytics       | ⚠️ Chart.js wired to mock data — needs real aggregate queries                 |
| Report export            | ⚠️ UI buttons exist — needs server-side PDF/CSV generation                   |
| Email notifications      | ❌ Not started — needed for registration confirmation and forgot-password      |

---

## 9. Recommended Backend Stack

| Component         | Recommendation         | Reason                                              |
|-------------------|------------------------|-----------------------------------------------------|
| Runtime           | PHP 8.x                | Already on XAMPP; matches team's local environment  |
| Database          | MySQL 8.x              | Available on XAMPP; good fit for relational data    |
| Auth              | JWT + bcrypt           | Stateless, secure, works with vanilla JS frontend   |
| API style         | REST (JSON)            | Simple, matches current JS fetch calls needed       |
| PDF Export        | mPDF or TCPDF          | PHP-native PDF generation                          |
| File Upload       | PHP move_uploaded_file | Store in `/uploads/` directory                      |
| Email             | PHPMailer + SMTP       | For registration and password reset emails          |

---

## 10. Summary Scorecard

| Category              | Score   | Notes                                     |
|-----------------------|---------|-------------------------------------------|
| UI Completeness       | 18/20   | 2 pages have layout issues                |
| Feature Coverage      | 16/20   | Reports export and email missing          |
| Code Quality (FE)     | 15/20   | No modularity; all in inline `<script>`   |
| Security Posture      | 4/20    | No backend = no real security             |
| Data Persistence      | 3/20    | localStorage only — volatile              |
| Backend Readiness     | 12/20   | Data model defined; no server code yet    |
| **Overall**           | **68/120** | Solid prototype — backend needed to ship |
