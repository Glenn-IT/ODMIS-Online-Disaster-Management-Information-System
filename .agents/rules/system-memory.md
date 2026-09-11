---
name: system-memory
description: Enforces full-stack cross-file synchronization across Database, API, Admin UI, User UI, and Reports in ODMIS.
always_on: true
---

# ODMIS Synchronization Memory Rule

When performing ANY modification to ODMIS:
1. Consult `SYSTEM_MEMORY.md` to identify all files connected to the entity you are changing.
2. Ensure database schema changes propagate to:
   - Backend API queries & input validation (`api/`)
   - Client API wrapper (`assets/js/api.js`) and session decoder (`assets/js/auth.js`)
   - Admin module pages (`admin/`)
   - User module pages (`user/`)
   - Reports exports in CSV & PDF (`api/reports/` and `admin/reports.php`)
   - Seed data and test fixtures (`database/seeds/`)
3. Adhere strictly to the naming and casing invariants:
   - `announcements`: `body`, `published_at`, `published_by_name`.
   - `users.status`: `'active'` | `'inactive'`.
   - `incidents.status`: `'Active'` | `'Resolved'`.
   - `evacuation_centers.status`: `'Open'` | `'Closed'`.
   - `relief_operations.status`: `'Pending'` | `'In Progress'` | `'Completed'`.
   - `user_reports.status`: `'Pending'` | `'Reviewed'` | `'Resolved'`.
4. Run `php scripts/sync-check.php` to verify full system integrity and PHP syntax.
