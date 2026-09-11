# AGENTS.md — ODMIS System Instructions & Memory Directive

> **MANDATORY DIRECTIVE FOR ALL AGENTS & DEVELOPERS:**  
> Before modifying or adding ANY file in this project, you **MUST** consult [SYSTEM_MEMORY.md](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/SYSTEM_MEMORY.md).
>
> ODMIS is an interconnected system where changes in one layer (e.g. database schema or API endpoint) ripple through to API responses, client fetchers, Admin UI, User UI, and Reports/Analytics exports.

---

## 1. Golden Rules of Synchronization

1. **Never make isolated schema edits**:
   - If you add, rename, or remove a database column, you **MUST** update:
     - The corresponding migration file in `database/migrations/`.
     - The API endpoint queries in `api/<entity>/` (`index.php`, `show.php`, `store.php`, `update.php`).
     - The JSON payload keys and validation arrays.
     - The Admin UI table & modal in `admin/<entity>.php`.
     - The User UI cards & forms in `user/<entity>.php` (if applicable).
     - The Report generator queries & CSV/PDF exporter in `api/reports/` and `admin/reports.php`.
2. **Field Name Invariants**:
   - `announcements`: uses `body` (NOT `content`), `published_at` (NOT `date_posted`), `published_by_name`.
   - `incidents`: uses `incident_code`, `incident_date`, `incident_time` (nullable, never empty string).
   - `relief_operations`: uses `batch_number`, `operation_date`, `relief_type`.
   - `evacuation_centers`: uses `capacity`, `occupied_slots`, `available_slots` (computed).
   - `user_reports`: uses `incident_type` (includes 'Other'), `report_date`, `incident_time`.
3. **Casing Invariants for ENUMs**:
   - `users.status`: `'active'` | `'inactive'` (all lowercase).
   - `incidents.status`: `'Active'` | `'Resolved'` (TitleCase).
   - `evacuation_centers.status`: `'Open'` | `'Closed'` (TitleCase).
   - `relief_operations.status`: `'Pending'` | `'In Progress'` | `'Completed'`.
   - `user_reports.status`: `'Pending'` | `'Reviewed'` | `'Resolved'`.
   - `disaster_alerts.status`: `'Active'` | `'Resolved'`.
4. **Apache Authorization Header**:
   - `api/.htaccess` must preserve:
     ```apache
     RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
     ```
5. **Validation Verification**:
   - Always run the diagnostic sync checker script after any changes:
     ```powershell
     php scripts/sync-check.php
     ```

Refer to [SYSTEM_MEMORY.md](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/SYSTEM_MEMORY.md) for full entity maps and change impact matrices.
