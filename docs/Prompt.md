You are an expert frontend developer.

Create a fully interactive prototype (UI/UX only) for the following system.

PROJECT TITLE:

ONLINE DISASTER MANAGEMENT INFORMATION SYSTEM (ODMIS)

OBJECTIVE:

Build a presentation-ready system prototype.

NO backend.
NO database.

Use localStorage and mock JSON data to simulate system behavior.

---

## TECH STACK

- HTML5
- CSS3
- Bootstrap 5
- JavaScript
- Chart.js
- Font Awesome

---

## SYSTEM TYPE

Desktop Responsive Web Application

Target Devices:

- Desktop
- Laptop

Minimum Width:

- 1366px

If viewed on mobile devices, display:

"This system is optimized for desktop devices."

---

## USER ROLES

1. Administrator - WEBPAGE NOT MOBILE OK
2. Citizen/User - MOBILE RESPONSIVE

---

## GENERAL FEATURES

- Login
- Registration
- Forgot Password
- Security Questions
- Profile Management
- Logout Confirmation
- Dashboard Analytics
- Reports

---

## AUTHENTICATION MODULE

ADMIN LOGIN

Fields:

- Username
- Password

Features:

- Forgot Password
- Security Question Verification
- Reset Password

USER LOGIN

Fields:

- Username
- Password

Features:

- Forgot Password
- Security Question Verification
- Reset Password

USER REGISTRATION

Fields:

- Username
- Email
- Password
- Confirm Password
- Contact Number
- Date of Birth

Actions:

- Create Account
- Cancel

Store data in localStorage.

---

## ADMIN MODULE

DASHBOARD

Display Summary Cards:

- Total Residents Registered
- Total Disaster Reports
- Active Incidents
- Resolved Incidents

Charts:

- Disaster Types
- Monthly Reports
- Affected Barangays
- Disaster Frequency

---

DISASTER INCIDENT MANAGEMENT

Create Incident

Fields:

- Incident ID
- Disaster Type
- Incident Title
- Description
- Location
- Barangay
- Municipality
- Date
- Time
- Severity Level

Actions:

- Add
- Edit
- Delete
- View

---

EVACUATION CENTER MANAGEMENT

Fields:

- Center Name
- Location
- Capacity
- Occupied Slots
- Contact Person
- Contact Number

Actions:

- Add
- Edit
- Delete
- View

---

RESIDENT MANAGEMENT

Display Registered Users

Columns:

- Name
- Contact Number
- Address
- Status

Actions:

- View
- Edit
- Deactivate

---

RELIEF OPERATIONS

Fields:

- Relief Batch Number
- Date
- Barangay
- Relief Type
- Quantity

Actions:

- Add
- Edit
- Track Distribution

---

REPORTS

Generate Reports For:

- Disaster Incidents
- Residents
- Relief Operations
- Evacuation Centers

Filters:

- Date Range
- Disaster Type
- Barangay

Actions:

- Print
- Export PDF (UI Only)

---

SETTINGS

- Security Question
- Change Password
- System Information

---

## USER MODULE

USER DASHBOARD

Display:

- Active Disaster Alerts
- Emergency Contacts
- Recent Announcements
- Personal Reports

Cards:

- My Reports
- Active Alerts
- Evacuation Centers Nearby

---

REPORT INCIDENT

Fields:

- Incident Type
- Description
- Location
- Date
- Upload Photo

Actions:

- Submit Report
- Edit Report
- Cancel

---

DISASTER ALERTS

Display:

- Typhoon Alerts
- Flood Alerts
- Earthquake Alerts
- Fire Alerts

Status Colors:

- Green = Safe
- Yellow = Warning
- Orange = High Risk
- Red = Critical

---

EVACUATION CENTERS

Display:

- Center Name
- Location
- Capacity
- Available Slots

Action:

- View Details

---

ANNOUNCEMENTS

Display latest announcements from DRRM Office.

---

PROFILE

Display:

- Personal Information
- Contact Information
- Address

Actions:

- Edit Profile
- Change Password

---

## UI/UX REQUIREMENTS

Theme:

- Government / DRRM Professional Design

Color Palette:

- Blue
- White
- Red (Emergency Alerts)

Components:

- Sidebar
- Top Navbar
- Cards
- Charts
- Data Tables
- Modals
- Toast Notifications

Icons:

- Font Awesome

---

## MOCK DATA REQUIREMENTS

Generate Sample Data:

Disaster Types:

- Flood
- Typhoon
- Earthquake
- Fire
- Landslide

Sample Barangays:

- Minanga
- Lubo
- Sto. Niño
- Poblacion

Sample Residents

Sample Disaster Reports

Sample Relief Operations

Sample Evacuation Centers

---

## OUTPUT REQUIREMENTS

1. Complete Folder Structure
2. Reusable Components
3. Working Navigation
4. Dashboard Analytics
5. Mock Data
6. Fully Clickable Prototype

IMPORTANT:

This is a FRONTEND PROTOTYPE ONLY.

DO NOT CREATE:

- PHP
- MySQL
- APIs
- Backend Logic

Focus on:

- User Experience
- Presentation Quality
- Realistic Government System Design
