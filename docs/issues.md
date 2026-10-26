# ODMIS Issues & Enhancements Log

This document tracks known issues, feature requests, layout adjustments, and their resolution status in the Online Disaster Management Information System (ODMIS).

---

## Issue #1: Registration Name Fields (Separate First Name, Middle Name, Last Name)
- **Status:** ✅ **RESOLVED**
- **Reported Date:** 2026-10-26
- **Module Affected:** User Registration (`register.php`, `api/auth/register.php`)
- **Requirement:** Replace single "Full Name" textbox with distinct inputs for First Name, Middle Name (optional), and Last Name.
- **Resolution:**
  - Added dedicated textboxes for First Name (`#regFirstName`), Middle Name (`#regMiddleName` optional), and Last Name (`#regLastName`).
  - Added validation for required First and Last names.
  - Automatically concatenates names into `full_name` payload while also supplying individual fields (`first_name`, `middle_name`, `last_name`).
  - Updated `api/auth/register.php` to accept both concatenated and discrete name inputs.

---

## Issue #2: Evacuation Center Multi-Location and 500 Capacity Limit
- **Status:** ✅ **RESOLVED**
- **Reported Date:** 2026-10-26
- **Module Affected:** Evacuation Management (`admin/evacuation.php`, `api/evacuation/store.php`, `api/evacuation/update.php`)
- **Requirement:** Allow multiple evacuation centers/different locations within the same barangay, but enforce a 500 total capacity limit.
- **Resolution:**
  - Made Center Name editable so admins can customize center names when multiple sites exist in the same barangay.
  - Set `max="500"` on capacity input and added validation in `admin/evacuation.php`.
  - Added real-time remaining capacity helper text under the Barangay and Capacity inputs.
  - Backend validation in `api/evacuation/store.php` and `api/evacuation/update.php`:
    - Validates individual center capacity `cap <= 500`.
    - Queries `SELECT SUM(capacity) FROM evacuation_centers WHERE barangay = ?` to verify the total barangay capacity does not exceed 500.

---

## Issue #3: Forgot Password Failed-Attempt Lockout
- **Status:** ✅ **RESOLVED**
- **Reported Date:** 2026-10-26
- **Module Affected:** Account Recovery (`forgot-password.php`)
- **Requirement:** Implement failed attempt tracking and lockout for security question & answer verification (just like in `login.php`).
- **Resolution:**
  - Implemented 3-attempt limit with 30-second lockout timer in `forgot-password.php`.
  - Tracks attempts in `sessionStorage` (`fpAttempts_fp_sec_answer` and `fpAttempts_fp_username`).
  - On incorrect answer: notifies user of remaining attempts (e.g. `2 attempts remaining before lockout`).
  - At 3 failed attempts: locks the verify button and disables input with live countdown (`Locked (30s)`).
  - Countdown automatically resumes even if page is reloaded mid-lockout.
  - Clears attempts upon successful answer verification.

---

## Issue #4: Live Auto-Population / UI Refresh on Data Save or Update
- **Status:** ✅ **RESOLVED**
- **Reported Date:** 2026-10-26
- **Module Affected:** Global API Client (`assets/js/api.js`), Global API Helpers (`api/helpers/response.php`), and UI Modules
- **Requirement:** Ensure all saving, updating, status toggling, and deletion automatically populates and refreshes the UI without requiring manual browser page reload.
- **Resolution:**
  - **HTTP Cache Prevention (`api/helpers/response.php`):** Added `Cache-Control: no-store, no-cache, must-revalidate, max-age=0`, `Pragma: no-cache`, and `Expires: 0` headers to all JSON responses from `send_json()`.
  - **Client-Side Cache Prevention (`assets/js/api.js`):**
    - Set `cache: 'no-store'` in `fetch()`.
    - Added `Cache-Control: no-cache, no-store, must-revalidate` and `Pragma: no-cache` request headers.
    - Added dynamic timestamp query parameter `_t=${Date.now()}` on all GET requests so browser cache is completely bypassed.
  - **Instant Profile Session Sync (`api/profile/update.php`, `user/profile.php`):** Updated backend to issue refreshed JWT token with new profile values and updated frontend to refresh `localStorage` token, topbar name, and avatar chips immediately upon save.
  - Verified that all admin and user pages (`incidents`, `evacuation`, `relief`, `announcements`, `residents`, `resident-reports`, `sms`, `report-incident`, `profile`) immediately re-render updated data upon submission without manual page reload.
