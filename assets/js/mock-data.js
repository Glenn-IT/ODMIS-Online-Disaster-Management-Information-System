/* ============================================================
   ODMIS - Online Disaster Management Information System
   Mock Data Initialization
   Populates localStorage with sample data for prototype use
   ============================================================ */

function initMockData() {
  'use strict';

  // ── Helpers ────────────────────────────────────────────────
  function stored(key) {
    try {
      const val = localStorage.getItem(key);
      return val ? JSON.parse(val) : null;
    } catch (e) {
      return null;
    }
  }

  function store(key, value) {
    try {
      localStorage.setItem(key, JSON.stringify(value));
    } catch (e) {
      console.error('[ODMIS] Failed to store key:', key, e);
    }
  }

  // ── Storage Keys ────────────────────────────────────────────
  const KEYS = {
    USERS         : 'odmis_users',
    INCIDENTS     : 'odmis_incidents',
    EVACUATION    : 'odmis_evacuation_centers',
    RELIEF        : 'odmis_relief_operations',
    ANNOUNCEMENTS : 'odmis_announcements',
    ALERTS        : 'odmis_alerts',
    USER_REPORTS  : 'odmis_user_reports',
    INITIALIZED   : 'odmis_data_initialized'
  };

  // Version bump forces re-seed when mock data is updated
  const DATA_VERSION = 2;

  // Skip if already initialized with current version
  if (stored(KEYS.INITIALIZED) === DATA_VERSION) {
    console.log('[ODMIS] Mock data already initialized. Skipping.');
    return;
  }

  // ── 1. USERS ────────────────────────────────────────────────
  const users = [
    {
      id              : 'USR-001',
      username        : 'admin',
      email           : 'admin@odmis.gov.ph',
      password        : 'admin123',
      role            : 'admin',
      fullName        : 'Administrator',
      contactNumber   : '09171234567',
      dateOfBirth     : '1985-03-15',
      address         : 'Municipal Hall, Poblacion, Santo Niño (Faire), Cagayan, Region II',
      status          : 'active',
      securityQuestion: "What is your mother's maiden name?",
      securityAnswer  : 'Santos',
      createdAt       : '2024-01-01T08:00:00.000Z'
    },
    {
      id              : 'USR-002',
      username        : 'juan',
      email           : 'juan.delacruz@email.com',
      password        : 'user123',
      role            : 'user',
      fullName        : 'Juan Dela Cruz',
      contactNumber   : '09181234568',
      dateOfBirth     : '1990-06-22',
      address         : 'Blk 5 Lot 3, Minanga, Santo Niño (Faire), Cagayan',
      status          : 'active',
      securityQuestion: 'What is the name of your first pet?',
      securityAnswer  : 'Bantay',
      createdAt       : '2024-02-10T09:15:00.000Z'
    },
    {
      id              : 'USR-003',
      username        : 'maria',
      email           : 'maria.santos@email.com',
      password        : 'user123',
      role            : 'user',
      fullName        : 'Maria Santos',
      contactNumber   : '09191234569',
      dateOfBirth     : '1993-11-05',
      address         : '123 Rizal Street, Lubo, Santo Niño (Faire), Cagayan',
      status          : 'active',
      securityQuestion: 'What is the name of your elementary school?',
      securityAnswer  : 'Lubo Elementary School',
      createdAt       : '2024-02-14T10:30:00.000Z'
    },
    {
      id              : 'USR-004',
      username        : 'pedro',
      email           : 'pedro.reyes@email.com',
      password        : 'user123',
      role            : 'user',
      fullName        : 'Pedro Reyes',
      contactNumber   : '09201234570',
      dateOfBirth     : '1988-08-17',
      address         : 'Purok 2, Sto. Niño, Santo Niño (Faire), Cagayan',
      status          : 'active',
      securityQuestion: "What is your mother's maiden name?",
      securityAnswer  : 'Gomez',
      createdAt       : '2024-03-01T07:45:00.000Z'
    },
    {
      id              : 'USR-005',
      username        : 'ana',
      email           : 'ana.garcia@email.com',
      password        : 'user123',
      role            : 'user',
      fullName        : 'Ana Garcia',
      contactNumber   : '09211234571',
      dateOfBirth     : '1995-04-30',
      address         : 'Purok 5, Poblacion, Santo Niño (Faire), Cagayan',
      status          : 'inactive',
      securityQuestion: 'What city were you born in?',
      securityAnswer  : 'Digos City',
      createdAt       : '2024-03-20T11:00:00.000Z'
    }
  ];

  // ── 2. DISASTER INCIDENTS ───────────────────────────────────
  const incidents = [
    {
      id          : 'INC-001',
      disasterType: 'Flood',
      title       : 'Flash Flood in Minanga Barangay',
      description : 'Heavy rainfall caused flash flooding along the riverbanks of Minanga affecting approximately 45 families. Water levels rose to 1.5 meters in low-lying areas. Several houses partially submerged.',
      location    : 'Riverside Area, Minanga',
      barangay    : 'Minanga',
      municipality: 'Santo Niño (Faire)',
      date        : '2024-11-12',
      time        : '03:30',
      severity    : 'High',
      status      : 'Resolved',
      reportedBy  : 'Barangay Captain Rodrigo Villanueva',
      createdAt   : '2024-11-12T03:45:00.000Z'
    },
    {
      id          : 'INC-002',
      disasterType: 'Typhoon',
      title       : 'Typhoon Carina Landfall Effects',
      description : 'Super typhoon Carina made landfall bringing sustained winds of 180 kph and heavy rains. Significant damage to infrastructure, agricultural crops, and residential structures across all barangays.',
      location    : 'All Barangays, Santo Niño (Faire)',
      barangay    : 'Poblacion',
      municipality: 'Santo Niño (Faire)',
      date        : '2024-10-20',
      time        : '08:00',
      severity    : 'Critical',
      status      : 'Resolved',
      reportedBy  : 'MDRRMO Office',
      createdAt   : '2024-10-20T08:00:00.000Z'
    },
    {
      id          : 'INC-003',
      disasterType: 'Earthquake',
      title       : 'Magnitude 5.8 Earthquake',
      description : 'A magnitude 5.8 earthquake with epicenter approximately 12 km northeast of Santo Niño (Faire) caused moderate structural damage to several buildings in Poblacion and surrounding barangays. No casualties reported.',
      location    : 'Poblacion and Nearby Barangays',
      barangay    : 'Poblacion',
      municipality: 'Santo Niño (Faire)',
      date        : '2024-09-05',
      time        : '14:22',
      severity    : 'Moderate',
      status      : 'Resolved',
      reportedBy  : 'PHIVOLCS / MDRRMO',
      createdAt   : '2024-09-05T14:30:00.000Z'
    },
    {
      id          : 'INC-004',
      disasterType: 'Fire',
      title       : 'House Fire — Purok 3, Lubo',
      description : 'A residential fire broke out in Purok 3, Lubo at approximately 2:00 AM. The fire consumed three houses before fire fighters contained the blaze. One family of five left homeless.',
      location    : 'Purok 3, Lubo',
      barangay    : 'Lubo',
      municipality: 'Santo Niño (Faire)',
      date        : '2024-12-01',
      time        : '02:00',
      severity    : 'High',
      status      : 'Resolved',
      reportedBy  : 'BFP Santo Niño (Faire) Fire Station',
      createdAt   : '2024-12-01T02:15:00.000Z'
    },
    {
      id          : 'INC-005',
      disasterType: 'Landslide',
      title       : 'Landslide Blocks National Highway',
      description : 'Heavy rains triggered a significant landslide on the slope adjacent to the national highway near Sto. Niño. Approximately 200 cubic meters of soil and debris blocked the road, cutting off access to three barangays.',
      location    : 'National Highway near Sto. Niño',
      barangay    : 'Sto. Niño',
      municipality: 'Santo Niño (Faire)',
      date        : '2024-11-28',
      time        : '17:45',
      severity    : 'High',
      status      : 'Resolved',
      reportedBy  : 'Brgy. Sto. Niño Officials',
      createdAt   : '2024-11-28T18:00:00.000Z'
    },
    {
      id          : 'INC-006',
      disasterType: 'Flood',
      title       : 'Flooding in Low-Lying Areas of Sto. Niño',
      description : 'Continuous rainfall over 48 hours resulted in flooding in the low-lying areas of Sto. Niño. Water reached knee-deep levels in some areas. Approximately 30 families sought temporary shelter.',
      location    : 'Low-lying areas, Sto. Niño',
      barangay    : 'Sto. Niño',
      municipality: 'Santo Niño (Faire)',
      date        : '2025-01-08',
      time        : '06:00',
      severity    : 'Moderate',
      status      : 'Active',
      reportedBy  : 'Sto. Niño Barangay Council',
      createdAt   : '2025-01-08T06:30:00.000Z'
    },
    {
      id          : 'INC-007',
      disasterType: 'Typhoon',
      title       : 'Tropical Storm Domeng — Damage Assessment',
      description : 'Tropical Storm Domeng caused strong winds and heavy rainfall affecting the northern barangays. Roofing damage reported in Minanga and Lubo. Rice farms flooded.',
      location    : 'Minanga and Lubo Barangays',
      barangay    : 'Minanga',
      municipality: 'Santo Niño (Faire)',
      date        : '2025-01-15',
      time        : '11:00',
      severity    : 'Moderate',
      status      : 'Active',
      reportedBy  : 'MDRRMO Monitoring Team',
      createdAt   : '2025-01-15T11:30:00.000Z'
    },
    {
      id          : 'INC-008',
      disasterType: 'Fire',
      title       : 'Grass Fire in Agricultural Area',
      description : 'A grass fire broke out in the agricultural area on the eastern side of Minanga. The fire threatened nearby residential structures before being contained. Approximately 3 hectares of farmland were affected.',
      location    : 'Eastern Agricultural Zone, Minanga',
      barangay    : 'Minanga',
      municipality: 'Santo Niño (Faire)',
      date        : '2024-12-15',
      time        : '13:00',
      severity    : 'Low',
      status      : 'Resolved',
      reportedBy  : 'Concerned Farmer — Domingo Lim',
      createdAt   : '2024-12-15T13:20:00.000Z'
    },
    {
      id          : 'INC-009',
      disasterType: 'Earthquake',
      title       : 'Aftershocks Following M5.8 Event',
      description : 'A series of aftershocks (ranging M2.1–M3.8) were recorded following the September earthquake. Minor cracking observed in weakened structures in Poblacion. Residents advised to remain vigilant.',
      location    : 'Poblacion',
      barangay    : 'Poblacion',
      municipality: 'Santo Niño (Faire)',
      date        : '2025-01-20',
      time        : '09:15',
      severity    : 'Low',
      status      : 'Active',
      reportedBy  : 'PHIVOLCS Regional Office',
      createdAt   : '2025-01-20T09:30:00.000Z'
    },
    {
      id          : 'INC-010',
      disasterType: 'Landslide',
      title       : 'Critical Landslide Risk — Lubo Hillside',
      description : 'Geo-hazard monitoring teams identified a critical landslide risk zone on the hillside area of Lubo. Soil saturation levels are dangerously high. Pre-emptive evacuation of 12 families along the slope has been ordered.',
      location    : 'Hillside Zone, Lubo',
      barangay    : 'Lubo',
      municipality: 'Santo Niño (Faire)',
      date        : '2025-01-22',
      time        : '07:00',
      severity    : 'Critical',
      status      : 'Active',
      reportedBy  : 'MDRRMO Geohazard Assessment Team',
      createdAt   : '2025-01-22T07:15:00.000Z'
    }
  ];

  // ── 3. EVACUATION CENTERS ───────────────────────────────────
  const evacuationCenters = [
    {
      id            : 'EVC-001',
      name          : 'Santo Niño Municipal Gymnasium',
      location      : 'Poblacion, Santo Niño (Faire), Cagayan',
      barangay      : 'Poblacion',
      capacity      : 500,
      occupied      : 180,
      contactPerson : 'Ricardo Bautista',
      contactNumber : '09221234572',
      status        : 'Active',
      createdAt     : '2024-01-15T08:00:00.000Z'
    },
    {
      id            : 'EVC-002',
      name          : 'Minanga Elementary School',
      location      : 'Purok 1, Minanga, Santo Niño (Faire), Cagayan',
      barangay      : 'Minanga',
      capacity      : 200,
      occupied      : 60,
      contactPerson : 'Leonora Espinosa',
      contactNumber : '09231234573',
      status        : 'Active',
      createdAt     : '2024-01-15T08:00:00.000Z'
    },
    {
      id            : 'EVC-003',
      name          : 'Lubo Barangay Hall (Emergency Wing)',
      location      : 'Purok 4, Lubo, Santo Niño (Faire), Cagayan',
      barangay      : 'Lubo',
      capacity      : 150,
      occupied      : 0,
      contactPerson : 'Cornelio Manalo',
      contactNumber : '09241234574',
      status        : 'Inactive',
      createdAt     : '2024-01-20T08:00:00.000Z'
    },
    {
      id            : 'EVC-004',
      name          : 'Sto. Niño Multi-Purpose Hall',
      location      : 'Purok 2, Sto. Niño, Santo Niño (Faire), Cagayan',
      barangay      : 'Sto. Niño',
      capacity      : 300,
      occupied      : 120,
      contactPerson : 'Erlinda Pascual',
      contactNumber : '09251234575',
      status        : 'Active',
      createdAt     : '2024-02-01T08:00:00.000Z'
    },
    {
      id            : 'EVC-005',
      name          : 'Santo Niño National High School Covered Court',
      location      : 'Poblacion, Santo Niño (Faire), Cagayan',
      barangay      : 'Poblacion',
      capacity      : 400,
      occupied      : 95,
      contactPerson : 'Principal Felix Navarro',
      contactNumber : '09261234576',
      status        : 'Active',
      createdAt     : '2024-02-10T08:00:00.000Z'
    }
  ];

  // ── 4. RELIEF OPERATIONS ────────────────────────────────────
  const reliefOperations = [
    {
      id            : 'REL-001',
      batchNumber   : 'BATCH-001',
      date          : '2024-10-22',
      barangay      : 'Poblacion',
      reliefType    : 'Food Pack',
      quantity      : 250,
      unit          : 'packs',
      status        : 'Completed',
      distributedBy : 'MDRRMO Team Alpha',
      notes         : 'Post-Typhoon Carina relief. Food packs contain 3-day supply of canned goods and rice.'
    },
    {
      id            : 'REL-002',
      batchNumber   : 'BATCH-002',
      date          : '2024-10-23',
      barangay      : 'Minanga',
      reliefType    : 'Food Pack',
      quantity      : 150,
      unit          : 'packs',
      status        : 'Completed',
      distributedBy : 'MDRRMO Team Beta',
      notes         : 'Post-Typhoon Carina relief distribution in Minanga.'
    },
    {
      id            : 'REL-003',
      batchNumber   : 'BATCH-003',
      date          : '2024-10-24',
      barangay      : 'Lubo',
      reliefType    : 'Non-Food Items (NFI)',
      quantity      : 80,
      unit          : 'family kits',
      status        : 'Completed',
      distributedBy : 'DSWD Santo Niño (Faire)',
      notes         : 'NFI kits include sleeping mats, blankets, and hygiene kits.'
    },
    {
      id            : 'REL-004',
      batchNumber   : 'BATCH-004',
      date          : '2024-11-14',
      barangay      : 'Minanga',
      reliefType    : 'Food Pack',
      quantity      : 90,
      unit          : 'packs',
      status        : 'Completed',
      distributedBy : 'MDRRMO / BFP',
      notes         : 'Relief for flood victims in Minanga riverside area (INC-001).'
    },
    {
      id            : 'REL-005',
      batchNumber   : 'BATCH-005',
      date          : '2024-12-03',
      barangay      : 'Lubo',
      reliefType    : 'Shelter Materials',
      quantity      : 15,
      unit          : 'sets',
      status        : 'Completed',
      distributedBy : 'MDRRMO / LGU',
      notes         : 'Roofing materials (GI sheets, lumber) for fire victims in Purok 3, Lubo (INC-004).'
    },
    {
      id            : 'REL-006',
      batchNumber   : 'BATCH-006',
      date          : '2025-01-09',
      barangay      : 'Sto. Niño',
      reliefType    : 'Food Pack',
      quantity      : 60,
      unit          : 'packs',
      status        : 'In Progress',
      distributedBy : 'MDRRMO Team Alpha',
      notes         : 'Ongoing relief for flood-affected families in Sto. Niño (INC-006).'
    },
    {
      id            : 'REL-007',
      batchNumber   : 'BATCH-007',
      date          : '2025-01-22',
      barangay      : 'Lubo',
      reliefType    : 'Food Pack',
      quantity      : 24,
      unit          : 'packs',
      status        : 'Pending',
      distributedBy : 'MDRRMO',
      notes         : 'Prepared for pre-emptively evacuated families from landslide risk zone (INC-010). Awaiting deployment.'
    },
    {
      id            : 'REL-008',
      batchNumber   : 'BATCH-008',
      date          : '2025-01-23',
      barangay      : 'Minanga',
      reliefType    : 'Non-Food Items (NFI)',
      quantity      : 40,
      unit          : 'family kits',
      status        : 'Pending',
      distributedBy : 'DSWD / MDRRMO',
      notes         : 'Hygiene and NFI kits for tropical storm Domeng-affected families in Minanga and Lubo.'
    }
  ];

  // ── 5. ANNOUNCEMENTS ────────────────────────────────────────
  const announcements = [
    {
      id       : 'ANN-001',
      title    : 'Mandatory Evacuation Order — Lubo Hillside Zone',
      content  : 'The Municipal Disaster Risk Reduction and Management Office (MDRRMO) hereby issues a MANDATORY EVACUATION ORDER for all residents living within the identified landslide risk zone in the hillside area of Barangay Lubo. Affected families must proceed to the Lubo Barangay Hall Emergency Wing immediately. Failure to comply may endanger lives. MDRRMO personnel will assist in evacuation.',
      category : 'Evacuation Order',
      date     : '2025-01-22',
      postedBy : 'MDRRMO Admin'
    },
    {
      id       : 'ANN-002',
      title    : 'Tropical Storm Domeng Advisory — Preparedness Reminder',
      content  : 'Tropical Storm Domeng is expected to bring heavy rains and strong winds to Santo Niño (Faire) and surrounding municipalities from January 15–17, 2025. Residents are advised to: (1) Store adequate food and water supply; (2) Secure loose objects; (3) Stay informed via PAGASA updates; (4) Know your evacuation routes. The MDRRMO is on full alert. For emergencies, call the MDRRMO Hotline: 0917-XXX-XXXX.',
      category : 'Weather Advisory',
      date     : '2025-01-14',
      postedBy : 'MDRRMO Admin'
    },
    {
      id       : 'ANN-003',
      title    : 'Community DRRM Training — Barangay Level (February 2025)',
      content  : 'The Municipal DRRMO, in partnership with the Philippine Red Cross, will conduct Barangay-Level DRRM Training sessions in February 2025. Topics include: Basic Life Support (BLS), Search and Rescue, Community Early Warning Systems, and Evacuation Drills. Schedule: Minanga — Feb 3, Lubo — Feb 5, Sto. Niño — Feb 7, Poblacion — Feb 10. All Barangay Emergency Response Teams (BERTs) are required to attend.',
      category : 'Training & Capacity Building',
      date     : '2025-01-18',
      postedBy : 'MDRRMO Admin'
    },
    {
      id       : 'ANN-004',
      title    : 'Relief Distribution — Sto. Niño Flood Victims',
      content  : 'Food packs and non-food items will be distributed to flood-affected families in Barangay Sto. Niño on January 10, 2025, from 8:00 AM to 5:00 PM at the Sto. Niño Multi-Purpose Hall. Affected families must bring their Barangay Identification and Disaster Assistance Family Access Cards (DAFAC) for verification. Queries may be directed to the MDRRMO office.',
      category : 'Relief Distribution',
      date     : '2025-01-09',
      postedBy : 'MDRRMO Admin'
    },
    {
      id       : 'ANN-005',
      title    : 'Post-Typhoon Carina Damage Assessment Results',
      content  : 'The MDRRMO has completed the damage and needs assessment following Typhoon Carina (October 20, 2024). Summary: Total affected families — 1,247; Total affected individuals — 4,988; Houses totally damaged — 38; Houses partially damaged — 212; Agricultural damage (rice/corn) — 145 hectares; Infrastructure damage — Php 3.2 million estimated. LGU is coordinating with national government agencies for additional assistance.',
      category : 'Damage Assessment Report',
      date     : '2024-10-28',
      postedBy : 'MDRRMO Admin'
    },
    {
      id       : 'ANN-006',
      title    : 'Geohazard Awareness Month — December 2024',
      content  : 'In observance of Geohazard Awareness Month, the MDRRMO encourages all residents to familiarize themselves with the municipal geohazard maps. Know if your area is in a flood-prone, landslide-prone, or storm surge-prone zone. Barangay-level community mapping sessions are scheduled throughout December. Contact your barangay council for the schedule in your area. Stay safe and be prepared!',
      category : 'Awareness Campaign',
      date     : '2024-12-01',
      postedBy : 'MDRRMO Admin'
    }
  ];

  // ── 6. ALERTS ───────────────────────────────────────────────
  const alerts = [
    {
      id           : 'ALT-001',
      type         : 'Flood',
      title        : 'FLOOD ALERT — Sto. Niño Low-Lying Areas',
      description  : 'Continuous rainfall has raised river levels to near-critical. Residents in low-lying areas of Sto. Niño are advised to move to higher ground. Monitor water levels closely.',
      affectedAreas: ['Sto. Niño'],
      severity     : 'High',
      status       : 'Active',
      dateIssued   : '2025-01-08',
      timeIssued   : '05:45'
    },
    {
      id           : 'ALT-002',
      type         : 'Typhoon',
      title        : 'TROPICAL STORM WARNING — Domeng',
      description  : 'Tropical Storm Domeng is approaching. PAGASA has raised Tropical Cyclone Wind Signal No. 1 over Santo Niño (Faire). Expect strong winds and heavy to intense rainfall.',
      affectedAreas: ['Minanga', 'Lubo', 'Sto. Niño', 'Poblacion'],
      severity     : 'Moderate',
      status       : 'Active',
      dateIssued   : '2025-01-14',
      timeIssued   : '06:00'
    },
    {
      id           : 'ALT-003',
      type         : 'Landslide',
      title        : 'CRITICAL LANDSLIDE RISK — Lubo Hillside',
      description  : 'Geohazard assessment reveals critical landslide risk on the Lubo hillside. Mandatory evacuation order issued. All residents in the risk zone must evacuate immediately.',
      affectedAreas: ['Lubo'],
      severity     : 'Critical',
      status       : 'Active',
      dateIssued   : '2025-01-22',
      timeIssued   : '07:00'
    },
    {
      id           : 'ALT-004',
      type         : 'Earthquake',
      title        : 'AFTERSHOCK ADVISORY — M5.8 Earthquake Zone',
      description  : 'Aftershocks from the September 2024 M5.8 earthquake continue to be recorded. Residents in Poblacion and nearby barangays are advised to inspect structures for cracks and avoid damaged buildings.',
      affectedAreas: ['Poblacion'],
      severity     : 'Low',
      status       : 'Active',
      dateIssued   : '2025-01-20',
      timeIssued   : '09:00'
    },
    {
      id           : 'ALT-005',
      type         : 'Flood',
      title        : 'FLOOD WATCH — Minanga River Area',
      description  : 'River levels in Minanga are being monitored closely due to persistent rainfall in the upstream catchment area. Residents near the river are advised to be on standby for possible evacuation.',
      affectedAreas: ['Minanga'],
      severity     : 'Moderate',
      status       : 'Active',
      dateIssued   : '2025-01-15',
      timeIssued   : '10:00'
    },
    {
      id           : 'ALT-006',
      type         : 'Typhoon',
      title        : 'TYPHOON CARINA — All Clear Issued',
      description  : 'Typhoon Carina has moved out of the Philippine Area of Responsibility. All clear is issued. Residents may return to their homes after assessment by barangay officials.',
      affectedAreas: ['Minanga', 'Lubo', 'Sto. Niño', 'Poblacion'],
      severity     : 'Low',
      status       : 'Resolved',
      dateIssued   : '2024-10-22',
      timeIssued   : '14:00'
    }
  ];

  // ── 7. USER REPORTS (empty on init) ─────────────────────────
  const userReports = [];

  // ── Persist to localStorage ─────────────────────────────────
  store(KEYS.USERS,         users);
  store(KEYS.INCIDENTS,     incidents);
  store(KEYS.EVACUATION,    evacuationCenters);
  store(KEYS.RELIEF,        reliefOperations);
  store(KEYS.ANNOUNCEMENTS, announcements);
  store(KEYS.ALERTS,        alerts);
  store(KEYS.USER_REPORTS,  userReports);
  store(KEYS.INITIALIZED,   DATA_VERSION);

  console.log('[ODMIS] Mock data initialized successfully.');
  console.log('[ODMIS] Users loaded     :', users.length);
  console.log('[ODMIS] Incidents loaded :', incidents.length);
  console.log('[ODMIS] Evac Centers     :', evacuationCenters.length);
  console.log('[ODMIS] Relief Ops       :', reliefOperations.length);
  console.log('[ODMIS] Announcements    :', announcements.length);
  console.log('[ODMIS] Alerts           :', alerts.length);
}

// ── Auto-initialize on script load ──────────────────────────
initMockData();
