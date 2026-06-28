# ODMIS System Test Report

**Date:** 2026-06-26  
**Tester:** Claude (automated via PowerShell + curl)  
**Environment:** XAMPP · Apache · PHP 8.2.12 · MariaDB 10.4.32 · Windows 11  
**Base URL:** `http://localhost/ODMIS-Online-Disaster-Management-Information-System/api`

---

## Overall Result: PASS (after 2 bugs fixed)

All 40+ API endpoints tested. Two bugs were found and fixed during this session.

---

## Bugs Found & Fixed

### BUG 1 — CRITICAL: All Authenticated Endpoints Returned 401

**File:** `api/.htaccess`  
**Symptom:** Every endpoint protected by `require_auth()` or `require_admin()` returned:
```json
{"success": false, "message": "Unauthorized: no token provided."}
```
even when a valid `Authorization: Bearer <token>` header was sent.

**Root Cause:** Apache on XAMPP does not automatically populate `$_SERVER['HTTP_AUTHORIZATION']`. The `api/.htaccess` was missing the rewrite rule that forwards the header into PHP's environment.

**Fix Applied:**
```apache
# api/.htaccess — added at the bottom
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
```

**Impact:** Without this fix, no user could access any protected resource — the entire authenticated API surface was broken.

---

### BUG 2 — Alerts Public Endpoint Returned "Database error."

**File:** `api/alerts/index.php`  
**Symptom:** `GET /api/alerts/index.php` (without `?all=1`) returned:
```json
{"success": false, "message": "Database error."}
```
The `?all=1` variant worked correctly.

**Root Cause:** Ambiguous `status` column. The SQL query joins `disaster_alerts (da)` with `users (u)`, and both tables have a `status` column. The WHERE clause `WHERE status = 'Active'` caused a `PDOException` because MariaDB couldn't resolve which table's column to use.

**Fix Applied:**
```php
// api/alerts/index.php — changed
$where[] = "status = 'Active'";
// to
$where[] = "da.status = 'Active'";
```

---

## Test Results by Module

### Authentication
| Endpoint | Method | Result | Notes |
|---|---|---|---|
| `/auth/login.php` | POST | ✅ PASS | Returns JWT + role |
| `/auth/register.php` | POST | ✅ PASS | Validates fields, hashes password/security answer |
| `/auth/me.php` | GET | ✅ PASS | Returns user profile from JWT |
| `/auth/logout.php` | POST | ✅ PASS | Acknowledges logout |
| `/auth/forgot-password.php` | POST step 1 | ✅ PASS | Returns security question |
| Wrong password | POST | ✅ PASS | Returns 401, no username hint |
| Inactive account login | POST | ✅ PASS | Returns clear error message |

### Incidents (Admin)
| Endpoint | Method | Result |
|---|---|---|
| `/incidents/index.php` | GET | ✅ PASS — 10 records |
| `/incidents/show.php?id=1` | GET | ✅ PASS |
| `/incidents/store.php` | POST | ✅ PASS |
| `/incidents/update.php?id=` | PUT | ✅ PASS |
| `/incidents/destroy.php?id=` | DELETE | ✅ PASS |

### Evacuation Centers
| Endpoint | Method | Result |
|---|---|---|
| `/evacuation/index.php` | GET | ✅ PASS — 5 records, includes `available_slots` |
| `/evacuation/show.php?id=1` | GET | ✅ PASS |
| `/evacuation/store.php` | POST | ✅ PASS |
| `/evacuation/destroy.php?id=` | DELETE | ✅ PASS |

### Relief Operations
| Endpoint | Method | Result |
|---|---|---|
| `/relief/index.php` | GET | ✅ PASS — 8 records |
| `/relief/show.php?id=1` | GET | ✅ PASS |
| `/relief/store.php` | POST | ✅ PASS |

### Residents (Admin)
| Endpoint | Method | Result |
|---|---|---|
| `/residents/index.php` | GET | ✅ PASS — 4 users |
| `/residents/show.php?id=2` | GET | ✅ PASS |
| `/residents/toggle-status.php?id=` | PATCH | ✅ PASS — toggles active ↔ inactive |

### Announcements
| Endpoint | Method | Result |
|---|---|---|
| `/announcements/index.php` | GET (public) | ✅ PASS — 6 records |
| `/announcements/store.php` | POST | ✅ PASS |
| `/announcements/destroy.php?id=` | DELETE | ✅ PASS |

### Disaster Alerts
| Endpoint | Method | Result | Notes |
|---|---|---|---|
| `/alerts/index.php` | GET (public) | ✅ PASS (after fix) | Was failing with DB error before fix |
| `/alerts/index.php?all=1` | GET (admin) | ✅ PASS — 6 seed records |
| `/alerts/store.php` | POST | ✅ PASS |
| `/alerts/deactivate.php?id=` | PATCH | ✅ PASS — sets status=Resolved |

### User Reports
| Endpoint | Method | Result | Notes |
|---|---|---|---|
| `/user-reports/index.php` | GET (admin) | ✅ PASS | 0 records (no seed data — expected) |
| `/user-reports/show.php?id=` | GET | ✅ PASS | Returns 404 when no reports exist |

### Profile
| Endpoint | Method | Result |
|---|---|---|
| `/profile/index.php` | GET | ✅ PASS |

### Analytics
| Endpoint | Result | Sample Data |
|---|---|---|
| `/analytics/summary.php` | ✅ PASS | 4 residents, 4 active incidents, 5 active alerts, 4 open centers |
| `/analytics/by-type.php` | ✅ PASS | 5 types, 2 incidents each |
| `/analytics/monthly.php?year=2025` | ✅ PASS | 12-month scaffold, 4 incidents in January |
| `/analytics/by-barangay.php` | ✅ PASS | Per-barangay breakdown |
| `/analytics/frequency.php` | ✅ PASS | 5 series for Chart.js |

### Reports & Export
| Endpoint | Result | Notes |
|---|---|---|
| `/reports/incidents.php` | ✅ PASS | count=10 |
| `/reports/residents.php` | ✅ PASS | count=4 |
| `/reports/relief.php` | ✅ PASS | count=8 |
| `/reports/evacuation.php` | ✅ PASS | count=5 |
| `/reports/export-pdf.php?report=incidents` | ✅ PASS | 33 KB PDF |
| `/reports/export-csv.php?report=residents` | ✅ PASS | UTF-8 BOM CSV |

### File Serving
| Scenario | Result |
|---|---|
| Non-existent file | ✅ PASS — returns 404 JSON |

---

## Authorization & Edge Case Tests

| Scenario | Expected | Result |
|---|---|---|
| No token on protected route | 401 | ✅ PASS |
| Regular user accessing admin endpoint | 403 | ✅ PASS |
| Wrong password | 401 | ✅ PASS |
| Inactive account login attempt | 401 | ✅ PASS |

---

## Observations (Not Bugs)

- **No delete for residents or relief operations** — by design per checklist. Residents can only be toggled active/inactive; relief ops have no delete. No action needed.
- **No user_reports seed data** — the DB starts empty for user reports. This is expected; reports are submitted by end users.
- **JWT revocation** — tokens issued before an account is deactivated remain valid until expiry (24h). This is a known trade-off with stateless JWT; addressed if/when security hardening phase is implemented.
- **Test artifact** — a test user `testuser99` and a resolved test alert (id=7) were left in the DB during testing. They are harmless but can be manually deleted via phpMyAdmin if desired.

---

## Summary

| Category | Count | Status |
|---|---|---|
| Bugs found | 2 | Fixed |
| Endpoints tested | 42 | All passing |
| Auth/authorization checks | 4 | All passing |
| CRUD write operations | 9 | All passing |
| Export/download | 2 | All passing |
