# ODMIS Issues & Enhancements Log

This document tracks known issues, feature requests, layout adjustments, and their resolution status in the Online Disaster Management Information System (ODMIS).

---

## Issue #1: Evacuation Center Name Standardization (Building/Center/Place Dropdown)

- **Status:**  **RESOLVED**
- **Reported Date:** 2026-09-11
- **Module Affected:** Admin Evacuation Center Management (`admin/evacuation.php`)
- **Category:** Feature Enhancement / Data Normalization

### 1. Problem Description / Requirement
When adding an evacuation center, instead of requiring free-form manual text entry for "Center Name", the system should provide a structured dropdown list for **Building / Center / Place**:
- High School
- Elementary School
- Barangay Hall
- Evacuation Center
- Day Care Center
- Gymnasium

The admin selects the Building/Place, Zone, and Barangay. The resulting Evacuation Center Record name displayed in the table (after the Center Code/ID) should automatically follow the standardized format:
```
[Building/Center/Place] - [Barangay]
```
*(Example: `Elementary School - Abariongan Uneg`)*

---

### 2. Implementation Details

1. **Modal Form UI (`admin/evacuation.php`):**
   - Added `<select id="fBuildingType" class="form-select">` with the 6 standard choices:
     - `High School`
     - `Elementary School`
     - `Barangay Hall`
     - `Evacuation Center`
     - `Day Care Center`
     - `Gymnasium`
   - Added auto-generated and read-only `<input id="fName" class="form-control bg-light" readonly>` displaying the formatted center name in real-time.
   - Kept Zone (`#fLocation`) and Barangay (`#fBarangay`) selections connected in the grid.

2. **Real-time Name Generation (`admin/evacuation.php`):**
   - Implemented `updateGeneratedCenterName()` that reacts to `change` events on both `#fBuildingType` and `#fBarangay`:
     ```javascript
     nameInput.value = `${bldg} - ${brgy}`;
     ```
   - Automatically resets in `openAddModal()`.
   - In `editCenter(id)`, detects existing standard prefix from `center_name` to pre-select the corresponding dropdown value.

3. **Data Records & Table Display:**
   - The table row renders:
     - Column 1: `center_code` (e.g. `EVAC-001`)
     - Column 2: `center_name` (e.g. `Elementary School - Abariongan Uneg`)
   - Fully synced with REST API `/api/evacuation/index.php`, `store.php`, and `update.php`.

---

## Change History

| Date | Issue ID | Summary | Status |
| :--- | :--- | :--- | :--- |
| 2026-09-11 | #1 | Standardize Evacuation Center Name via Building/Center/Place dropdown + Barangay auto-formatting |  Resolved |