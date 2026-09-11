# ODMIS — Official Barangay Directory
**Municipality:** Sto. Niño (Faire)  
**Province:** Cagayan  
**Region:** Cagayan Valley (Region II)  
**System Scope:** Online Disaster Management Information System (ODMIS)  

---

## 1. Overview

This document provides the authoritative directory of all **31 official Barangays** within the Municipality of Sto. Niño (Faire), Cagayan. 

All modules in ODMIS—including incident logging, citizen reporting, evacuation center assignments, relief operations tracking, and DRRM analytical reports—are synchronized to this master list.

---

## 2. Directory of Official Barangays (31 Total)

| # | Barangay Name | Classification | LGU / DRRMO Cluster | Status in ODMIS |
|:---:|:---|:---:|:---:|:---:|
| **1** | Abariongan Ruar | Rural / Riverbank | Cluster A | Active |
| **2** | Abariongan Uneg | Rural / Riverbank | Cluster A | Active |
| **3** | Balagan | Rural / Agricultural | Cluster B | Active |
| **4** | Balanni | Rural / Agricultural | Cluster B | Active |
| **5** | Cabayo | Rural / Agricultural | Cluster B | Active |
| **6** | Calapangan | Rural / Agricultural | Cluster C | Active |
| **7** | Calassitan | Rural / Agricultural | Cluster C | Active |
| **8** | Campo | Rural / Inland | Cluster C | Active |
| **9** | Centro Norte | Urban / Center | Poblacion Zone | Active |
| **10** | Centro Sur | Urban / Center | Poblacion Zone | Active |
| **11** | Dungao | Rural / Agricultural | Cluster D | Active |
| **12** | Lattac | Rural / Riverbank | Cluster D | Active |
| **13** | Lipatan | Rural / Agricultural | Cluster D | Active |
| **14** | Lubo | Rural / Riverbank | Cluster E | Active |
| **15** | Mabitbitnong | Rural / Agricultural | Cluster E | Active |
| **16** | Masical | Rural / Agricultural | Cluster E | Active |
| **17** | Matalao | Rural / Inland | Cluster F | Active |
| **18** | Nag-uma | Rural / Agricultural | Cluster F | Active |
| **19** | Namuccayan | Rural / Agricultural | Cluster F | Active |
| **20** | Niug Norte | Rural / Riverbank | Cluster G | Active |
| **21** | Niug Sur | Rural / Riverbank | Cluster G | Active |
| **22** | Palusao | Rural / Agricultural | Cluster G | Active |
| **23** | Poblacion | Urban / Municipal Core | Poblacion Zone | Active |
| **24** | San Manuel | Rural / Agricultural | Cluster H | Active |
| **25** | San Roque | Rural / Agricultural | Cluster H | Active |
| **26** | Santa Felicitas | Rural / Agricultural | Cluster H | Active |
| **27** | Santa Maria | Rural / Agricultural | Cluster I | Active |
| **28** | Sidiran | Rural / Agricultural | Cluster I | Active |
| **29** | Tabang | Rural / Riverbank | Cluster I | Active |
| **30** | Tamucco | Rural / Riverbank | Cluster J | Active |
| **31** | Virginia | Rural / Agricultural | Cluster J | Active |

---

## 3. Location Zones (Sub-Barangay Targeting)

In addition to the 31 barangays, ODMIS provides zone-level targeting for precise incident mapping:
- `Zone 1`
- `Zone 2`
- `Zone 3`
- `Zone 4`
- `Zone 5`
- `Zone 6`
- `Zone 7`

---

## 4. Excluded / Deprecated Entries

The following entries were previously present in legacy drafts or mock prototypes and are **excluded** from production:

| Name | Reason for Exclusion | Resolution |
|:---|:---|:---|
| `Abarriongan` | Typo duplicate of Abariongan | Use `Abariongan Ruar` or `Abariongan Uneg` |
| `Minanga` | Non-jurisdictional / deprecated entry | Excluded from active options |
| `Sto. Niño` | Municipal name (not a barangay) | Set as default `municipality`, excluded from `barangay` select |

---

## 5. System File Connections

This master directory is enforced across the following files:

1. **Admin Disaster Incident Management:**
   - Table Filter: [`admin/incidents.php`](../admin/incidents.php) (`#filterBarangay`)
   - Creation / Edit Modal: [`admin/incidents.php`](../admin/incidents.php) (`#fieldBarangay`)

2. **Admin Evacuation Center Management:**
   - Table Filter: [`admin/evacuation.php`](../admin/evacuation.php) (`#filterBarangay`)
   - Center Modal: [`admin/evacuation.php`](../admin/evacuation.php) (`#fBarangay`)

3. **Admin Relief Operations Tracking:**
   - Table Filter: [`admin/relief.php`](../admin/relief.php) (`#filterBarangay`)
   - Distribution Batch Modal: [`admin/relief.php`](../admin/relief.php) (`#fBarangay`)

4. **Admin Reports & Analytics Generator:**
   - Filter Query: [`admin/reports.php`](../admin/reports.php) (`#filterBarangay`)

5. **Citizen / User Incident Reporting:**
   - Citizen Submission Form: [`user/report-incident.php`](../user/report-incident.php) (`#incidentBarangay`)

6. **System Memory & Directives:**
   - Architecture Reference: [`SYSTEM_MEMORY.md`](../SYSTEM_MEMORY.md) (Section 6)
