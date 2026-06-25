# ODMIS — Online Disaster Management Information System

A web-based disaster management information system for the DRRM Office, Sta. Cruz, Davao del Sur.

---

## Project Status

> **Current Phase:** Frontend Prototype (localStorage)
> **Next Phase:** Backend & Database Implementation

---

## Tech Stack

| Layer      | Technology                          |
|------------|-------------------------------------|
| Markup     | HTML5                               |
| Styling    | CSS3 + Bootstrap 5.3.2              |
| Icons      | Font Awesome 6.5.0                  |
| Charts     | Chart.js                            |
| Scripting  | Vanilla JavaScript (ES6+)           |
| Storage    | localStorage (prototype) → MySQL 8.x (planned) |
| Backend    | None (planned: PHP 8.x REST API)    |
| Local Host | XAMPP (Windows)                     |

---

## Project Structure

```
ODMIS-Online-Disaster-Management-Information-System/
│
├── admin/                          # Admin module pages (desktop only, ≥1366px)
│   ├── dashboard.html              # Summary cards + Chart.js analytics
│   ├── incidents.html              # Disaster incident management (CRUD)
│   ├── evacuation.html             # Evacuation center management (CRUD)
│   ├── residents.html              # Registered resident management
│   ├── relief.html                 # Relief operations tracking
│   ├── reports.html                # Report generation (print/export UI)
│   └── settings.html               # Security settings + system info
│
├── user/                           # User/Citizen module pages (mobile responsive)
│   ├── dashboard.html              # Alerts, emergency contacts, announcements
│   ├── report-incident.html        # Incident report submission form
│   ├── alerts.html                 # Disaster alerts viewer (color-coded)
│   ├── evacuation-centers.html     # Nearby evacuation center finder
│   ├── announcements.html          # DRRM office announcements feed
│   └── profile.html                # User profile view and edit
│
├── assets/
│   ├── css/
│   │   └── style.css               # Global styles, CSS variables, components
│   └── js/
│       ├── app.js                  # Core CRUD logic and UI handlers
│       ├── auth.js                 # Session detection, login/logout routing
│       └── mock-data.js            # Mock data seed for localStorage (DATA_VERSION=2)
│
├── docs/
│   ├── Prompt.md                   # Original system specification / design brief
│   ├── audit.md                    # System audit report (frontend completeness + gaps)
│   ├── backend-checklist.md        # Step-by-step checklist for backend implementation
│   └── issues.md                   # Known layout/bug issues log
│
├── index.html                      # Entry point — redirects to login.html
├── login.html                      # Dual-tab login (Admin + User)
├── register.html                   # User registration with validation
└── forgot-password.html            # 3-step password recovery via security question
```

---

## User Roles

| Role          | Access                          | Device        |
|---------------|---------------------------------|---------------|
| Administrator | All admin module pages          | Desktop only (≥1366px) |
| Citizen/User  | All user module pages           | Desktop + Mobile |

### Demo Credentials (localStorage prototype)

| Role  | Username | Password  |
|-------|----------|-----------|
| Admin | `admin`  | `admin123` |
| User  | `juan`   | `user123`  |
| User  | `maria`  | `user123`  |

---

## Features

### Authentication
- Login (Admin + User dual-tab)
- User Registration with form validation
- Forgot Password (3-step: username → security question → reset)
- Session management via localStorage

### Admin Module
- Dashboard with stat cards and 4 Chart.js charts
- Disaster Incident Management (Create / Read / Update / Delete)
- Evacuation Center Management (CRUD)
- Resident Management (View / Edit / Activate / Deactivate)
- Relief Operations Tracking (Add / Edit / Track)
- Report Generation (Disaster, Residents, Relief, Evacuation) — UI only
- Settings (Security question, Change password)

### User Module
- Personal dashboard (alerts, contacts, announcements)
- Incident report submission with photo upload (UI)
- Disaster alerts viewer with severity color-coding
- Evacuation center finder
- DRRM announcements feed
- Profile management

---

## Local Setup

1. Clone or copy the project into `C:\xampp\htdocs\`
2. Start XAMPP Apache (MySQL not required for prototype)
3. Open browser: `http://localhost/ODMIS-Online-Disaster-Management-Information-System/`
4. Login with demo credentials above

---

## Documentation

| File | Description |
|------|-------------|
| [`docs/audit.md`](docs/audit.md) | Full system audit — current state, data model, known issues, backend readiness |
| [`docs/backend-checklist.md`](docs/backend-checklist.md) | 12-phase checklist for implementing PHP/MySQL backend |
| [`docs/Prompt.md`](docs/Prompt.md) | Original frontend specification and design brief |
| [`docs/issues.md`](docs/issues.md) | Logged UI/layout bugs |

---

## Planned Backend Architecture

```
ODMIS (planned)
├── api/
│   ├── auth/          (login, register, forgot-password, logout, me)
│   ├── incidents/     (CRUD)
│   ├── evacuation/    (CRUD)
│   ├── relief/        (CRUD)
│   ├── residents/     (list, view, edit, toggle-status)
│   ├── announcements/ (CRUD)
│   ├── alerts/        (CRUD)
│   ├── user-reports/  (CRUD + file upload)
│   ├── profile/       (get, update, change-password)
│   ├── analytics/     (summary, by-type, monthly, by-barangay)
│   ├── reports/       (export-pdf, export-csv)
│   ├── helpers/       (response.php, mailer.php)
│   └── middleware/    (auth.php — JWT verification)
├── config/
│   ├── database.php
│   └── constants.php
├── database/
│   ├── migrations/    (SQL table definitions)
│   └── seeds/         (SQL sample data)
└── uploads/
    └── reports/       (user-submitted incident photos)
```

**Database:** MySQL 8.x (`odmis_db`)
**Auth:** JWT (firebase/php-jwt) + bcrypt password hashing
**PDF Export:** mPDF
**Email:** PHPMailer + SMTP

---

## Known Issues

| ID  | Page                   | Issue                            |
|-----|------------------------|----------------------------------|
| 01  | `admin/reports.html`   | Layout not working properly      |
| 02  | `admin/settings.html`  | Layout not working properly      |

See [`docs/issues.md`](docs/issues.md) and [`docs/audit.md`](docs/audit.md) for full details.
