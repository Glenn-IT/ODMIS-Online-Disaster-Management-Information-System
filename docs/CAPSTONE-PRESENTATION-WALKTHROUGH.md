# ODMIS — Capstone Defense Presentation Walkthrough & Panelist Demonstration Guide
<!-- System: Online Disaster Management Information System (ODMIS) for Santo Niño (Faire), Cagayan -->
<!-- Target Audience: Capstone Panelists, Advisers, Technical Evaluators, and MDRRMO Stakeholders -->

---

## 🧭 Executive Summary & Timing Strategy

| Phase | Section | Recommended Duration | Primary Interface |
| :--- | :--- | :--- | :--- |
| **Phase 1** | Project Rationale, Local Context & Vulnerability Profile | 1.5 mins | Title Slide / [login.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/login.php) |
| **Phase 2** | System Architecture, REST API, RBAC & Security Baseline | 1.0 min | [SYSTEM_MEMORY.md](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/SYSTEM_MEMORY.md) |
| **Phase 3** | Resident Portal Gateway, Registration & Security Questions | 1.0 min | [register.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/register.php) / [login.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/login.php) |
| **Phase 4** | Resident Command Center & Real-Time Emergency Alerts Feed | 1.0 min | [user/dashboard.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/user/dashboard.php) / [user/alerts.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/user/alerts.php) |
| **Phase 5** | Citizen Incident Reporting & Photo Evidence Upload | 1.5 mins | [user/report-incident.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/user/report-incident.php) |
| **Phase 6** | Interactive Evacuation Center Finder & Live Capacity Gauge | 1.0 min | [user/evacuation-centers.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/user/evacuation-centers.php) |
| **Phase 7** | MDRRMO Admin Command Center & High-Density Visual Analytics | 1.5 mins | [admin/dashboard.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/admin/dashboard.php) |
| **Phase 8** | Incident Master Record Management & Rapid Lifecycle Triage | 1.0 min | [admin/incidents.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/admin/incidents.php) |
| **Phase 9** | Evacuation Center Operations & Dynamic Capacity Control | 1.0 min | [admin/evacuation.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/admin/evacuation.php) |
| **Phase 10** | Relief Aid Inventory & Distribution Batch Tracking | 1.0 min | [admin/relief.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/admin/relief.php) |
| **Phase 11** | Resident Citizen Report Verification & Photographic Dossier | 1.0 min | [admin/resident-reports.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/admin/resident-reports.php) |
| **Phase 12** | Multi-Barangay Emergency SMS Broadcast via PhilSMS API | 1.0 min | [admin/sms.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/admin/sms.php) |
| **Phase 13** | Analytical Reporting Engine & Multi-Format Export (PDF/CSV) | 1.0 min | [admin/reports.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/admin/reports.php) |
| **Phase 14** | System Governance, Security Audit & Transition to Panel Q&A | 0.5 min | [admin/residents.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/admin/residents.php) / [admin/dashboard.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/admin/dashboard.php) |
| **Total** | **Full System Presentation** | **~15.0 mins** | — |

---

## 🛠️ Pre-Defense Staging & Credentials Setup

Before stepping in front of the capstone panel, configure your demonstration environment:

1. **Browser Workstation Configuration**:
   * **Window 1 (Main Desktop Monitor):** Logged in as **Admin** at [admin/dashboard.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/admin/dashboard.php) (Desktop resolution $\ge$ 1366px, satisfying the operations desk `#mobileGuard`).
   * **Window 2 (Incognito / Mobile View):** Logged in as **Resident (Juan Dela Cruz)** at [user/dashboard.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/user/dashboard.php) (Emulating a smartphone or tablet screen).
   * *Benefit:* Enables real-time dual-screen demonstration—submitting an incident or dispatching an SMS on one screen and watching it reflect instantly on the other without logging in and out.
2. **System Health Verification**:
   * Run the automated diagnostic synchronization script to ensure 100% integrity across database tables, JWT secret, Apache Authorization headers, and PHP syntax:
     ```powershell
     php scripts/sync-check.php
     ```
   * *Expected Output:* `Status: SUCCESS! All systems are synchronized and healthy (0 error(s), 0 warning(s))`.
3. **Core Standard Accounts**:
   * **MDRRMO Administrator:** Username `admin` or Email `admin@odmis.gov.ph` | Password: `admin123`
   * **Active Resident Accounts:** Uniform password: `user123`
     * `juan` (`juan.delacruz@email.com`) — Resident of Minanga / Sto. Niño
     * `maria` (`maria.santos@email.com`) — Resident of Lubo
     * `pedro` (`pedro.reyes@email.com`) — Resident of Purok 2, Sto. Niño
   * **Inactive Resident Account (Access Control Demo):**
     * `ana` (`ana.garcia@email.com`) | Password: `user123` — Status: `inactive` (demonstrates that disabled accounts are locked out at the authentication gate).

---

### 📋 Live Seeded Data Snapshot (`odmis_db`)

The database is pre-populated via [`database/seeds/001_sample_data.sql`](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/database/seeds/001_sample_data.sql) with realistic disaster scenarios localized for the **31 Barangays of Sto. Niño (Faire), Cagayan**:

#### 1. Official Disaster Incidents (`incidents`)
| Code | Type | Title | Barangay | Severity | Status | Reported By |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **INC-001** | Flood | Flash Flood in Minanga Riverbanks | Minanga | High | **Resolved** | Brgy. Capt. R. Villanueva |
| **INC-002** | Typhoon | Super Typhoon Carina Landfall Effects | Poblacion | Critical | **Resolved** | MDRRMO Office |
| **INC-003** | Earthquake | Magnitude 5.8 Earthquake | Poblacion | Moderate | **Resolved** | PHIVOLCS / MDRRMO |
| **INC-004** | Fire | Residential Fire — Purok 3 | Lubo | High | **Resolved** | BFP Santo Niño Station |
| **INC-005** | Landslide | Landslide Blocks National Highway | Sto. Niño | High | **Resolved** | Brgy. Officials |
| **INC-006** | Flood | Low-Lying Inundation (Knee-Deep) | Sto. Niño | Moderate | **Active** | Sto. Niño Brgy Council |
| **INC-007** | Typhoon | Tropical Storm Domeng Damage | Minanga | Moderate | **Active** | MDRRMO Monitoring Team |
| **INC-008** | Fire | Grass Fire in Agricultural Zone | Minanga | Low | **Resolved** | Concerned Farmer D. Lim |
| **INC-009** | Earthquake | M5.8 Aftershocks Series | Poblacion | Low | **Active** | PHIVOLCS Regional |
| **INC-010** | Landslide | Critical Landslide Risk Slope | Lubo | Critical | **Active** | Geohazard Team |

#### 2. Evacuation Centers (`evacuation_centers`)
| Code | Facility Name | Barangay | Capacity | Occupied | Available | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **EVC-001** | Santo Niño Municipal Gymnasium | Poblacion | 500 | 180 | **320** | **Open** |
| **EVC-002** | Centro Sur Elementary School | Centro Sur | 200 | 60 | **140** | **Open** |
| **EVC-003** | Lubo Barangay Hall (Emergency Wing) | Lubo | 150 | 0 | **150** | **Closed** |
| **EVC-004** | Sto. Niño Multi-Purpose Hall | Sto. Niño | 300 | 120 | **180** | **Open** |
| **EVC-005** | Santo Niño NHS Covered Court | Poblacion | 400 | 95 | **305** | **Open** |

#### 3. Relief Aid Batches (`relief_operations`)
| Batch No. | Date | Barangay | Relief Type | Quantity & Unit | Status | Distributor Agency |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **BATCH-001** | 2024-10-22 | Poblacion | Food Pack | 250 packs | **Completed** | MDRRMO Team Alpha |
| **BATCH-002** | 2024-10-23 | Minanga | Food Pack | 150 packs | **Completed** | MDRRMO Team Beta |
| **BATCH-003** | 2024-10-24 | Lubo | Non-Food Items (NFI) | 80 family kits | **Completed** | DSWD Sto. Niño |
| **BATCH-004** | 2024-11-14 | Minanga | Food Pack | 90 packs | **Completed** | MDRRMO / BFP |
| **BATCH-005** | 2024-12-03 | Lubo | Shelter Materials | 15 sets | **Completed** | MDRRMO / LGU |
| **BATCH-006** | 2025-01-09 | Sto. Niño | Food Pack | 60 packs | **In Progress** | MDRRMO Team Alpha |
| **BATCH-007** | 2025-01-22 | Lubo | Food Pack | 24 packs | **Pending** | MDRRMO Standby |
| **BATCH-008** | 2025-01-23 | Minanga | Non-Food Items (NFI) | 40 family kits | **Pending** | DSWD / MDRRMO |

---

## 🎬 Step-by-Step Presentation Script (From First to Last)

---

### Step 1: Opening, Local Problem Statement & Vulnerability Rationale
* **Screen Display:** Title Slide / [login.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/login.php) (Hero banner with official seal [`img/odmis_logo.jpg`](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/img/odmis_logo.jpg))
* **Estimated Time:** 1.5 minutes
* **Screen Action:** Present the landing interface showing the official tricolor government header and the seal of the Municipality of Santo Niño (Faire), Cagayan.
* **🗣️ Verbal Script:**
  > *"Good morning, honorable members of the panel, our adviser, and distinguished evaluators. Today, we present **ODMIS — the Online Disaster Management Information System for the Municipality of Santo Niño (Faire), Cagayan**.*
  >
  > *Geographically, Santo Niño is bordered by the Cagayan River basin and rugged mountain slopes across its 31 barangays. The municipality experiences recurrent seasonal flash floods, tropical typhoons, landslide risks in hillside communities like Lubo, and agricultural fire hazards. Traditionally, the Municipal Disaster Risk Reduction and Management Office (MDRRMO) relied on dispersed radio calls, handwritten logs, and manual spreadsheets.*
  >
  > *This manual operational model causes dangerous blind spots during calamities: citizens cannot ascertain real-time evacuation center capacity, emergency alerts fail to penetrate remote barangays lacking internet data, and disaster aid tracking suffers from duplicate entries.*
  >
  > *ODMIS bridges this critical gap by providing a decoupled, real-time command platform that integrates citizen incident crowdsourcing, automated evacuation capacity management, relief logistics auditing, and direct SMS broadcasting via the PhilSMS gateway."*

---

### Step 2: System Architecture, REST API & Security Baseline
* **Screen Display:** Architecture Diagram or [SYSTEM_MEMORY.md](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/SYSTEM_MEMORY.md)
* **Estimated Time:** 1.0 minute
* **Screen Action:** Walk through the system topology diagram showing the decoupled client, JSON REST API, and MySQL persistence layer.
* **🗣️ Verbal Script:**
  > *"ODMIS is engineered with a modern, decoupled architecture:
  > 1. **Stateless JSON REST API Layer:** Every module communicates through dedicated endpoints in [`api/`](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/api/) utilizing standardized JSON response envelopes (`success`, `message`, `data`).
  > 2. **JWT Bearer Authentication:** Sessions are secured via JSON Web Tokens passed in HTTP headers. In [`api/.htaccess`](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/api/.htaccess), we explicitly enforce Apache authorization header forwarding to guarantee seamless token parsing on Windows XAMPP environments.
  > 3. **Defensive Data Layer:** 100% of database interactions in [`config/database.php`](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/config/database.php) utilize PDO parameterized prepared statements, preventing SQL injection vulnerabilities.
  > 4. **Strict Role-Based Access Control (RBAC):** Rigid separation between `admin` (MDRRMO Incident Commanders) and `user` (Citizen Residents), guarded on both client routes and API middleware."*

---

### Step 3: Public Gateway, Resident Registration & Security Protocol
* **Screen Display:** [register.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/register.php) and [login.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/login.php)
* **Estimated Time:** 1.0 minute
* **Screen Action:**
  1. Open [register.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/register.php).
  2. Demonstrate the Philippine phone validation regex enforcing `^09\d{9}$`.
  3. Show the restricted dropdown populated with the **31 official barangays of Sto. Niño**.
  4. Highlight the security question and answer mechanism hashed via BCrypt for self-service recovery in [forgot-password.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/forgot-password.php).
  5. Attempt to login with disabled account `ana` to demonstrate the instant access lockout guard.
* **🗣️ Verbal Script:**
  > *"When residents register, the platform validates vital emergency details. Mobile numbers are locked to standard Philippine 11-digit mobile formats (`09XXXXXXXXX`) to ensure compatibility with our SMS dispatch engine.
  >
  > *Barangays are strictly bound to the 31 official territorial subdivisions of Sto. Niño to ensure accurate spatial categorization.
  >
  > *Furthermore, passwords and security answers are irreversibly encrypted using BCrypt (`PASSWORD_DEFAULT`), and inactive accounts are immediately blocked by the authentication middleware."*

---

### Step 4: Resident Command Center & Real-Time Emergency Alerts
* **Screen Display:** Window 2 (Incognito / Mobile View): [user/dashboard.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/user/dashboard.php) and [user/alerts.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/user/alerts.php)
* **Estimated Time:** 1.0 minute
* **Screen Action:**
  1. Log in as resident `juan` (`user123`).
  2. Point out the top emergency alert banner highlighting active disasters.
  3. Show the quick hotline cards (MDRRMO, BFP, PNP, Municipal Health Office).
  4. Navigate to [user/alerts.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/user/alerts.php) and click the multi-hazard filter chips (*All*, *Typhoon*, *Flood*, *Earthquake*, *Landslide*).
  5. Explain the severity badges (*Low*, *Moderate*, *High*, *Critical*).
* **🗣️ Verbal Script:**
  > *"Switching to the resident's mobile viewpoint. Residents immediately see active alerts issued by the MDRRMO command center.
  >
  > *The alerts feed in [user/alerts.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/user/alerts.php) aggregates official hazard declarations and active incidents, color-coded by severity from Moderate to Critical. Residents can filter warnings by disaster type to assess conditions in their specific barangay."*

---

### Step 5: Citizen Incident Reporting & Photo Evidence Upload
* **Screen Display:** Window 2: [user/report-incident.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/user/report-incident.php)
* **Estimated Time:** 1.5 minutes
* **Screen Action:**
  1. Click **"Report Incident"** from the resident navigation.
  2. Select Incident Type: `Flood`.
  3. Enter Title: `Rising Water Level along Riverbank Purok 4`.
  4. Select Barangay: `Minanga`.
  5. Enter Landmark/Location: `Near Minanga Hanging Bridge`.
  6. Date & Time: Today's date and current time.
  7. Attach a photo evidence file (demonstrating MIME validation and 5MB ceiling).
  8. Click **"Submit Incident Report"** and show the confirmation prompt.
* **🗣️ Verbal Script:**
  > *"In a disaster, timely ground intelligence is vital. ODMIS empowers residents to act as first-line eyes and ears for the municipal government.
  >
  > *Through this mobile-optimized form, a citizen can submit a hazard report complete with precise landmark coordinates, incident time, and photographic proof.
  >
  > *On submission, the file is sanitized, validated for image MIME types, stored in [`uploads/user-reports/`](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/uploads/), and queued in the MDRRMO triage desk as a 'Pending' item."*

---

### Step 6: Interactive Evacuation Center Finder & Live Capacity Gauge
* **Screen Display:** Window 2: [user/evacuation-centers.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/user/evacuation-centers.php)
* **Estimated Time:** 1.0 minute
* **Screen Action:**
  1. Open [user/evacuation-centers.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/user/evacuation-centers.php).
  2. Point out the summary badges: Total Centers, Total Capacity, Total Occupied, and Available Slots.
  3. Filter by barangay: Select `Poblacion`.
  4. Highlight the real-time progress bar of `Santo Niño Municipal Gymnasium` (500 capacity, 180 occupied, 320 available slots).
  5. Click the contact phone number showing direct telephone dialer integration.
* **🗣️ Verbal Script:**
  > *"During evacuations, families often flood into the nearest gym only to find it already full.
  >
  > *ODMIS eliminates this hazard through real-time capacity meters. Residents can see exactly how many slots remain available before leaving their homes. Closed centers are clearly marked in gray badges, and open centers show assigned facility managers with direct click-to-call mobile links."*

---

### Step 7: MDRRMO Admin Command Center & High-Density Visual Analytics
* **Screen Display:** Window 1 (Main Desktop Monitor): [admin/dashboard.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/admin/dashboard.php)
* **Estimated Time:** 1.5 minutes
* **Screen Action:**
  1. Switch to the Admin workstation.
  2. Point out the 4 primary KPI statistic cards: *Active Incidents, Open Evacuation Centers, Evacuees Sheltered, and Active Broadcast Alerts*.
  3. Showcase the 4 interactive Chart.js widgets:
     * **Incidents by Disaster Type** (Doughnut chart)
     * **Barangay Incident Distribution** (Horizontal Bar chart)
     * **Monthly Incident Trend** (Line chart)
     * **Evacuation Center Occupancy vs. Capacity** (Stacked Bar chart)
  4. Scroll down to show the **Recent Incidents Table** and **Active Disaster Alerts**.
* **🗣️ Verbal Script:**
  > *"Now transitioning to the MDRRMO Executive Command Center. This interface is strictly guarded by our desktop resolution guard, ensuring full situational awareness on high-density operations screens.
  >
  > *Four live Chart.js engines deliver real-time visual analytics: the distribution of disasters across hazard classes, barangay vulnerability rankings, historical monthly curves, and evacuation center saturation levels. This allows the Incident Commander to make data-driven resource allocations."*

---

### Step 8: Incident Master Record Management & Rapid Lifecycle Triage
* **Screen Display:** [admin/incidents.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/admin/incidents.php)
* **Estimated Time:** 1.0 minute
* **Screen Action:**
  1. Open [admin/incidents.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/admin/incidents.php).
  2. Filter by Status: `Active`.
  3. Filter by Severity: `Critical`.
  4. Click **"New Incident"** to reveal the modal form; note the automated `INC-XXX` code formatting, barangay selection, and date/time handlers.
  5. Demonstrate changing an incident status from `Active` to `Resolved`.
* **🗣️ Verbal Script:**
  > *"In [admin/incidents.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/admin/incidents.php), operations officers maintain authoritative disaster records.
  >
  > *Every incident is assigned an immutable alphanumeric identifier, such as `INC-010`. Officers can dynamically filter by hazard type or barangay, track reporting officers, and transition records from Active to Resolved as field teams contain the emergency."*

---

### Step 9: Evacuation Center Operations & Dynamic Capacity Control
* **Screen Display:** [admin/evacuation.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/admin/evacuation.php)
* **Estimated Time:** 1.0 minute
* **Screen Action:**
  1. Open [admin/evacuation.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/admin/evacuation.php).
  2. Click **"Edit"** on `Santo Niño Municipal Gymnasium`.
  3. Update occupied slots from `180` to `220`.
  4. Click **"Save Changes"**.
  5. Show that `available_slots` automatically re-computes to `280` in the database and UI.
  6. Switch momentarily to Window 2 (Resident view) and refresh to demonstrate that the new occupancy is instantly reflected to the public.
* **🗣️ Verbal Script:**
  > *"In the Evacuation Management module, center managers update evacuee counts as buses and rescue trucks arrive.
  >
  > *The system mathematically calculates available capacity on the fly (`capacity - occupied_slots`). When we update the gym's head count here in the command center, the resident mobile portal updates instantaneously, ensuring complete public transparency."*

---

### Step 10: Relief Aid Inventory & Distribution Batch Tracking
* **Screen Display:** [admin/relief.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/admin/relief.php)
* **Estimated Time:** 1.0 minute
* **Screen Action:**
  1. Open [admin/relief.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/admin/relief.php).
  2. Point out the batch tracking badges (`BATCH-001` through `BATCH-008`).
  3. Demonstrate filtering by status: *Pending*, *In Progress*, and *Completed*.
  4. Show how DSWD and MDRRMO distribute food packs, medical kits, and shelter materials with specific quantity units and audit remarks.
* **🗣️ Verbal Script:**
  > *"Relief operations during natural disasters often face scrutiny regarding transparency.
  >
  > *ODMIS tracks relief supplies by distinct batch numbers, documenting the target barangay, item type (whether food packs, hygiene kits, or GI roofing sheets), exact unit counts, distributing agency, and operational status. This guarantees full accountability for COA and DSWD post-disaster audits."*

---

### Step 11: Resident Citizen Report Verification & Photographic Dossier
* **Screen Display:** [admin/resident-reports.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/admin/resident-reports.php)
* **Estimated Time:** 1.0 minute
* **Screen Action:**
  1. Open [admin/resident-reports.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/admin/resident-reports.php).
  2. Locate the incident report submitted earlier by resident Juan Dela Cruz.
  3. Click **"View Details"** to trigger the modal.
  4. Inspect the photographic evidence thumbnail and click to view full resolution.
  5. Update the report status from `Pending` to `Reviewed` (or `Resolved`).
* **🗣️ Verbal Script:**
  > *"Returning to the administrative queue, we see the live citizen report submitted just minutes ago by Juan Dela Cruz.
  >
  > *Command staff can inspect the citizen's photographic evidence, verify the reporting timestamp, review the exact landmark description, and upgrade its status to 'Reviewed' or escalate it directly into an official municipal incident record."*

---

### Step 12: Multi-Barangay Emergency SMS Broadcast via PhilSMS API
* **Screen Display:** [admin/sms.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/admin/sms.php)
* **Estimated Time:** 1.0 minute
* **Screen Action:**
  1. Navigate to [admin/sms.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/admin/sms.php).
  2. Showcase the **PhilSMS Gateway Balance Card** (displaying real-time balance and expiration via `api/sms/balance.php`).
  3. Select Barangay: `Lubo`.
  4. Type Emergency Notice: `MDRRMO ALERT: Mandatory evacuation ordered for Lubo hillside due to high landslide risk. Proceed to designated centers immediately.`
  5. Demonstrate the character counter (160 chars per SMS segment).
  6. Click **"Send Broadcast"** (dispatches via PhilSMS v3 endpoint or simulated provider in offline dev).
  7. Point out the **SMS Transmission Audit Table** recording recipient, timestamp, message UID, and status (`Sent` / `Failed`).
* **🗣️ Verbal Script:**
  > *"The reality of rural municipalities in Cagayan is that during severe storms, electrical lines fall and mobile data fails. Citizens cannot browse websites without cellular internet.
  >
  > *ODMIS solves this through integrated SMS broadcasting powered by the **PhilSMS API v3**. Administrators can broadcast critical evacuation advisories directly to all registered residents of a target barangay.
  >
  > *The system tracks carrier message IDs, delivery statuses, and remaining API wallet balances to guarantee prompt emergency alert delivery."*

---

### Step 13: Analytical Reporting Engine & Multi-Format Export (PDF/CSV)
* **Screen Display:** [admin/reports.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/admin/reports.php)
* **Estimated Time:** 1.0 minute
* **Screen Action:**
  1. Navigate to [admin/reports.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/admin/reports.php).
  2. Select Report Category: `Incidents Report` (or `Evacuation Centers`, `Relief Operations`, `Registered Residents`).
  3. Apply date range filters or barangay filters.
  4. Click **"Generate Report"** to render the dynamic tabular summary.
  5. Click **"Export CSV"** to demonstrate immediate spreadsheet download.
  6. Click **"Export PDF"** to showcase the formatted official PDF document with municipal headers, generation timestamp, and authorized signatory line.
* **🗣️ Verbal Script:**
  > *"For municipal disaster councils, PDRRMO provincial reporting, and executive briefings, ODMIS provides a multi-entity reporting suite.
  >
  > *Officers can generate filtered summaries for incidents, evacuation centers, relief aid, or resident demographics, and instantly export them into CSV for spreadsheet analysis or formal, print-ready PDF documents formatted for executive presentation."*

---

### Step 14: System Governance, Security Audit & Transition to Panel Q&A
* **Screen Display:** [admin/residents.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/admin/residents.php) / [admin/dashboard.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/admin/dashboard.php)
* **Estimated Time:** 0.5 minute
* **Screen Action:**
  1. Briefly show [admin/residents.php](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/admin/residents.php) highlighting user status toggles (`active` / `inactive`) and security profiles.
  2. Return to the main command dashboard.
  3. Turn to face the panel members for concluding remarks.
* **🗣️ Verbal Script:**
  > *"In summary, ODMIS transforms disaster management in Santo Niño from a reactive, paper-bound process into a proactive, data-driven, and resilient digital ecosystem.
  >
  > *By unifying incident monitoring, citizen field intelligence, evacuation capacity balancing, relief distribution auditing, and telecommunication SMS broadcasting, ODMIS empowers local government to safeguard lives more effectively.
  >
  > *Thank you very much, honorable members of the panel. We are now open and eager to receive your questions."*

---

## 🛡️ Capstone Defense Panelist Q&A Cheat Sheet

| Question | Recommended Technical & Operational Response |
| :--- | :--- |
| **Q1: Why build a custom disaster management system instead of using Facebook or Google Forms?** | *"While social media is good for generic announcements, it lacks structural database integrity. Facebook cannot compute evacuation center capacity in real-time, cannot filter incidents by specific barangay coordinates, cannot prevent duplicate relief aid distribution, and cannot export COA/DSWD-compliant audit reports. ODMIS provides dedicated relational data governance designed specifically for LGU DRRM protocols."* |
| **Q2: What happens if the internet goes down completely during a typhoon? How do residents receive alerts?** | *"ODMIS is designed with a dual-pipeline strategy. For connected residents and command center staff, the web portal provides visual maps and dashboards. For residents in offline or low-connectivity barangays, the system integrates the **PhilSMS API v3** gateway ([docs/philsms-api-reference.md](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/docs/philsms-api-reference.md)), allowing administrators to dispatch GSM SMS alerts directly to regular cellular phones without requiring internet or data plans."* |
| **Q3: How do you prevent citizens from spamming or submitting false incident reports?** | *"Citizen incident reports in [`user_reports`](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/database/migrations/002_add_user_reports_incident_fields.sql) do not immediately appear on official public disaster channels. All submissions enter a triage queue with a default `Pending` status in [`admin/resident-reports.php`](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/admin/resident-reports.php). Submissions require registered user authentication (traceable to a verified phone number and account), photo proof attachment, and explicit administrative review before being confirmed or escalated."* |
| **Q4: How is security handled across your REST API, especially on Windows XAMPP?** | *"All API endpoints in [`api/`](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/api/) are guarded by JWT middleware ([`api/middleware/auth.php`](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/api/middleware/auth.php)). Because Apache under Windows XAMPP strips the HTTP `Authorization` header by default, our [`api/.htaccess`](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/api/.htaccess) explicitly includes `RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]`. Additionally, 100% of queries use PDO prepared statements to eliminate SQL injection, passwords use BCrypt hashing, and file uploads enforce strict MIME and size checks."* |
| **Q5: How do you handle simultaneous updates to evacuation center capacity to avoid race conditions?** | *"In our schema, `available_slots` is not a static column that can drift out of sync; it is dynamically evaluated as `(capacity - occupied_slots)`. Capacity updates in [`api/evacuation/update.php`](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/api/evacuation/) are executed using transactional atomic SQL updates, ensuring that occupancy tallies remain consistent even during peak intake."* |
| **Q6: Why did you separate the Admin UI and User UI instead of using a single responsive interface?** | *"Administrative disaster operations require high-density data tables, multi-chart spatial overviews, and simultaneous management controls that demand an operational desktop screen. Therefore, [`admin/`](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/admin/) enforces a 1366px screen width requirement (`#mobileGuard`). Conversely, the [`user/`](file:///C:/xampp/htdocs/ODMIS-Online-Disaster-Management-Information-System/user/) module is engineered with a mobile-first philosophy, allowing citizens evacuating on foot to report incidents or check shelter slots on any mobile browser."* |

---

## 💡 Pro-Tips for Defense Day

1. **Dual-Screen Live Demonstration:** Keep Window 1 (Admin Command Center) displayed on the main projector and Window 2 (Resident Mobile View) ready on a side screen or emulated mobile viewport. Submitting a report or changing an evacuation status on one window and watching the change reflect on the other without logging out is the most compelling live demonstration technique.
2. **Master the Local Geography:** Panelists value local relevance. Refer naturally to real geographic conditions of **Santo Niño (Faire), Cagayan**—such as riverbank overflow in *Minanga*, landslide risks in *Lubo*, and municipal staging in *Poblacion*.
3. **Run Diagnostic Verification Ahead of Time:** Execute `php scripts/sync-check.php` in your terminal prior to presentation. Knowing that all 8 tables, Apache directives, and 86 PHP files pass with zero errors gives the team unshakable technical confidence.
4. **Offline Preparedness:** Keep pre-generated PDF and CSV reports in your downloads folder alongside screenshots of the system so that even if projector HDMI or local WiFi momentarily disconnects, the presentation continues seamlessly.
