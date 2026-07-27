<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reports — ODMIS Admin</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="../assets/css/style.css" />

  <style>
    /* ── Report type tab buttons ── */
    .report-type-group .btn {
      border-radius: 6px;
      font-weight: 600;
      font-size: 0.82rem;
      padding: 0.45rem 1.1rem;
      transition: all 0.18s;
    }
    .report-type-group .btn.active {
      background: var(--color-primary, #467235);
      color: #fff;
      border-color: var(--color-primary, #467235);
    }
    .report-type-group .btn:not(.active) {
      background: #fff;
      color: var(--color-primary, #467235);
      border-color: #c8d3e0;
    }
    .report-type-group .btn:not(.active):hover {
      background: #eef2f7;
    }

    /* ── Filter card ── */
    .filter-card {
      background: #fff;
      border-radius: var(--card-border-radius, 10px);
      box-shadow: var(--card-shadow, 0 2px 10px rgba(0,0,0,.08));
      padding: 1.25rem 1.5rem 1rem;
      margin-bottom: 1.25rem;
    }
    .filter-label {
      font-size: 0.75rem;
      font-weight: 700;
      color: #5a6a7a;
      text-transform: uppercase;
      letter-spacing: .04em;
      margin-bottom: 0.3rem;
    }

    /* ── Report output card ── */
    .report-output-card {
      background: #fff;
      border-radius: var(--card-border-radius, 10px);
      box-shadow: var(--card-shadow, 0 2px 10px rgba(0,0,0,.08));
      padding: 1.5rem;
    }
    .report-header-block {
      border-bottom: 2px solid var(--color-primary, #467235);
      padding-bottom: 0.85rem;
      margin-bottom: 1.1rem;
    }
    .report-sys-name {
      font-size: 1.05rem;
      font-weight: 800;
      color: var(--color-primary, #467235);
      margin-bottom: 0.1rem;
    }
    .report-office {
      font-size: 0.82rem;
      color: #5a6a7a;
      margin-bottom: 0.55rem;
    }
    .report-title-text {
      font-size: 1rem;
      font-weight: 700;
      color: #222;
      margin-bottom: 0.15rem;
    }
    .report-meta {
      font-size: 0.78rem;
      color: #7a8a9a;
    }
    .report-table th {
      background: var(--color-primary, #467235);
      color: #fff;
      font-size: 0.78rem;
      font-weight: 700;
      white-space: nowrap;
    }
    .report-table td {
      font-size: 0.82rem;
      vertical-align: middle;
    }
    .report-summary {
      font-size: 0.82rem;
      font-weight: 600;
      color: #5a6a7a;
      margin-top: 0.75rem;
    }
    .empty-report {
      text-align: center;
      padding: 3rem 0;
      color: #aab;
    }
    .empty-report i { font-size: 3rem; margin-bottom: 0.75rem; }

    /* ── Severity / Status badges ── */
    .badge-severity-critical { background: #dc3545; color:#fff; }
    .badge-severity-high     { background: #fd7e14; color:#fff; }
    .badge-severity-medium   { background: #ffc107; color:#000; }
    .badge-severity-low      { background: #198754; color:#fff; }

    /* ── Charts area ── */
    .chart-row-card {
      background: #fff;
      border-radius: var(--card-border-radius, 10px);
      box-shadow: var(--card-shadow, 0 2px 10px rgba(0,0,0,.08));
      padding: 1.25rem;
      height: 100%;
    }
    .chart-card-title {
      font-size: 0.82rem;
      font-weight: 700;
      color: var(--color-primary, #467235);
      margin-bottom: 0.85rem;
      display: flex;
      align-items: center;
      gap: 0.4rem;
    }
    .chart-card-title i { color: var(--color-accent, #FFBF00); }

    /* ── Toast ── */
    #toastContainer {
      position: fixed;
      top: 1.25rem;
      right: 1.25rem;
      z-index: 9999;
      min-width: 280px;
    }

    /* ── PRINT STYLES ── */
    @media print {
      .sidebar, #sidebar, .top-navbar, #topNavbar,
      .filter-card, .report-actions, .mobile-guard,
      #logoutModal, #toastContainer, .chart-section {
        display: none !important;
      }
      body, .main-content, .content-wrapper {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
      }
      .report-output-card {
        box-shadow: none !important;
        border-radius: 0 !important;
        padding: 0 !important;
      }
      .report-table th {
        background: #467235 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
    }
  </style>
</head>
<body>

<!-- ═══════════════════════════════════════════════════
     SIDEBAR
═══════════════════════════════════════════════════ -->
<div id="sidebar">
  <!-- Brand -->
  <div class="sidebar-logo-area">
    <div class="sidebar-logo-placeholder">
      <i class="fas fa-shield-alt"></i>
    </div>
    <div class="sidebar-brand-text">
      <span class="brand-title">ODMIS</span>
      <span class="brand-subtitle">Disaster Management</span>
    </div>
  </div>

  <!-- Nav -->
  <nav class="sidebar-nav">
    <ul class="list-unstyled mb-0">
      <li class="sidebar-nav-item">
        <a href="dashboard.php" class="sidebar-nav-link" data-page="dashboard">
          <i class="fas fa-tachometer-alt nav-icon"></i>
          <span class="nav-label">Dashboard</span>
        </a>
      </li>
      <li class="sidebar-nav-item">
        <a href="incidents.php" class="sidebar-nav-link" data-page="incidents">
          <i class="fas fa-exclamation-triangle nav-icon"></i>
          <span class="nav-label">Incidents</span>
        </a>
      </li>
      <li class="sidebar-nav-item">
        <a href="resident-reports.php" class="sidebar-nav-link" data-page="resident-reports">
          <i class="fas fa-clipboard-list nav-icon"></i>
          <span class="nav-label">Resident Reports</span>
        </a>
      </li>
      <li class="sidebar-nav-item">
        <a href="evacuation.php" class="sidebar-nav-link" data-page="evacuation">
          <i class="fas fa-house-damage nav-icon"></i>
          <span class="nav-label">Evacuation Centers</span>
        </a>
      </li>
      <li class="sidebar-nav-item">
        <a href="residents.php" class="sidebar-nav-link" data-page="residents">
          <i class="fas fa-users nav-icon"></i>
          <span class="nav-label">Residents</span>
        </a>
      </li>
      <li class="sidebar-nav-item">
        <a href="relief.php" class="sidebar-nav-link" data-page="relief">
          <i class="fas fa-box-open nav-icon"></i>
          <span class="nav-label">Relief Operations</span>
        </a>
      </li>
      <li class="sidebar-nav-item">
        <a href="reports.php" class="sidebar-nav-link" data-page="reports">
          <i class="fas fa-chart-bar nav-icon"></i>
          <span class="nav-label">Reports</span>
        </a>
      </li>
      <li class="sidebar-nav-item">
        <a href="settings.php" class="sidebar-nav-link" data-page="settings">
          <i class="fas fa-cog nav-icon"></i>
          <span class="nav-label">Settings</span>
        </a>
      </li>
    </ul>
  </nav>

  <!-- Footer user info -->
  <div class="sidebar-footer">
    <div class="sidebar-user-avatar" id="sidebarUserAvatar">A</div>
    <div class="sidebar-user-info">
      <span class="sidebar-user-name" id="sidebarUserName">Admin</span>
      <span class="sidebar-user-role" id="sidebarUserRole">Administrator</span>
    </div>
  </div>
</div>

<!-- Sidebar overlay (mobile) -->
<div id="sidebarOverlay"></div>

<!-- ═══════════════════════════════════════════════════
     TOP NAVBAR
═══════════════════════════════════════════════════ -->
<nav id="topNavbar">
  <button class="navbar-hamburger" id="sidebarToggle" title="Toggle Sidebar"><i class="fas fa-bars"></i></button>
  <h1 class="navbar-page-title">Reports</h1>
  <div class="navbar-right">
    <button class="navbar-icon-btn position-relative" title="Notifications">
      <i class="fas fa-bell"></i>
      <span class="notification-count" id="notificationCount">0</span>
    </button>
    <div class="navbar-divider"></div>
    <div class="dropdown">
      <div class="navbar-user dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">
        <div class="navbar-avatar" id="navbarAvatar">A</div>
        <span class="navbar-user-name" id="navbarUsername">Admin</span>
        <i class="fas fa-chevron-down ms-1" style="font-size:0.65rem; color:var(--color-gray);"></i>
      </div>
      <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="min-width:180px; font-size:var(--font-size-sm);">
        <li><a class="dropdown-item" href="settings.php"><i class="fas fa-cog me-2 text-muted"></i>Settings</a></li>
        <li><hr class="dropdown-divider my-1"></li>
        <li><a class="dropdown-item text-danger" href="#" data-action="logout"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- ═══════════════════════════════════════════════════
     MAIN CONTENT
═══════════════════════════════════════════════════ -->
<main id="mainContent">

    <!-- ══ SECTION 1: FILTER CARD ══ -->
    <div class="filter-card">

      <!-- Report type selector -->
      <div class="d-flex align-items-center gap-3 mb-3 flex-wrap">
        <span class="filter-label mb-0">Report Type:</span>
        <div class="report-type-group d-flex gap-2 flex-wrap" id="reportTypeGroup">
          <button class="btn active" data-type="incidents" onclick="switchReportType('incidents')">
            <i class="fas fa-exclamation-triangle me-1"></i>Disaster Incidents
          </button>
          <button class="btn" data-type="residents" onclick="switchReportType('residents')">
            <i class="fas fa-users me-1"></i>Residents
          </button>
          <button class="btn" data-type="relief" onclick="switchReportType('relief')">
            <i class="fas fa-box-open me-1"></i>Relief Operations
          </button>
          <button class="btn" data-type="evacuation" onclick="switchReportType('evacuation')">
            <i class="fas fa-house-damage me-1"></i>Evacuation Centers
          </button>
        </div>
      </div>

      <hr class="my-2">

      <!-- Filters row -->
      <div class="row g-2 align-items-end mt-1" id="filtersRow">

        <!-- Date From -->
        <div class="col-auto">
          <label class="filter-label">Date From</label>
          <input type="date" class="form-control form-control-sm" id="filterDateFrom" style="min-width:140px;">
        </div>

        <!-- Date To -->
        <div class="col-auto">
          <label class="filter-label">Date To</label>
          <input type="date" class="form-control form-control-sm" id="filterDateTo" style="min-width:140px;">
        </div>

        <!-- Disaster Type (incidents only) -->
        <div class="col-auto" id="filterTypeWrap">
          <label class="filter-label">Disaster Type</label>
          <select class="form-select form-select-sm" id="filterDisasterType" style="min-width:150px;">
            <option value="">All Types</option>
            <option value="Flood">Flood</option>
            <option value="Typhoon">Typhoon</option>
            <option value="Earthquake">Earthquake</option>
            <option value="Fire">Fire</option>
            <option value="Landslide">Landslide</option>
          </select>
        </div>

        <!-- Barangay -->
        <div class="col-auto">
          <label class="filter-label">Barangay</label>
          <select class="form-select form-select-sm" id="filterBarangay" style="min-width:150px;">
            <option value="">All Barangays</option>
            <option value="Minanga">Minanga</option>
            <option value="Lubo">Lubo</option>
            <option value="Sto. Niño">Sto. Niño</option>
            <option value="Poblacion">Poblacion</option>
          </select>
        </div>

        <!-- Status -->
        <div class="col-auto">
          <label class="filter-label">Status</label>
          <select class="form-select form-select-sm" id="filterStatus" style="min-width:140px;">
            <option value="">All Statuses</option>
          </select>
        </div>

        <!-- Action buttons -->
        <div class="col-auto ms-auto report-actions">
          <div class="d-flex gap-2">
            <button class="btn btn-primary btn-sm px-3" onclick="generateReport()">
              <i class="fas fa-file-alt me-1"></i>Generate Report
            </button>
            <button class="btn btn-outline-secondary btn-sm px-3" onclick="printReport()">
              <i class="fas fa-print me-1"></i>Print
            </button>
            <button class="btn btn-outline-danger btn-sm px-3" onclick="exportPDF()">
              <i class="fas fa-file-pdf me-1"></i>Export PDF
            </button>
          </div>
        </div>

      </div><!-- /filtersRow -->
    </div><!-- /filter-card -->

    <!-- ══ SECTION 2: REPORT OUTPUT ══ -->
    <div class="report-output-card" id="reportOutputCard">

      <!-- Empty state (default) -->
      <div class="empty-report" id="emptyReportState">
        <i class="fas fa-chart-bar d-block"></i>
        <p class="mb-0 mt-2">Select a report type and click <strong>Generate Report</strong> to view results.</p>
      </div>

      <!-- Report header (hidden until generated) -->
      <div id="reportHeaderBlock" class="report-header-block" style="display:none;">
        <div class="report-sys-name">ODMIS - Online Disaster Management Information System</div>
        <div class="report-office">MDRRMO &mdash; Municipality of Santo Niño (Faire), Cagayan, Region II</div>
        <div class="report-title-text" id="reportTitleText">Report</div>
        <div class="report-meta" id="reportMeta"></div>
      </div>

      <!-- Table wrapper -->
      <div id="reportTableWrapper" style="display:none;">
        <div class="table-responsive">
          <table class="table table-bordered table-hover report-table align-middle mb-0" id="reportTable">
            <thead id="reportThead"></thead>
            <tbody id="reportTbody"></tbody>
          </table>
        </div>
        <div class="report-summary" id="reportSummary"></div>
      </div>

      <!-- Charts (incidents only) -->
      <div class="chart-section row g-3 mt-2" id="reportCharts" style="display:none;">
        <div class="col-md-4">
          <div class="chart-row-card">
            <div class="chart-card-title"><i class="fas fa-chart-pie"></i>Incidents by Type</div>
            <canvas id="chartByType" height="220"></canvas>
          </div>
        </div>
        <div class="col-md-8">
          <div class="chart-row-card">
            <div class="chart-card-title"><i class="fas fa-chart-bar"></i>Incidents by Barangay</div>
            <canvas id="chartByBarangay" height="220"></canvas>
          </div>
        </div>
      </div>

    </div><!-- /report-output-card -->

</main><!-- /mainContent -->

<!-- ═══════════════════════════════════════════════════
     MOBILE GUARD
═══════════════════════════════════════════════════ -->
<div id="mobileGuard">
  <div class="text-center text-white p-4">
    <i class="fas fa-desktop mobile-guard-icon"></i>
    <h4 class="mobile-guard-title">Desktop Required</h4>
    <p class="mobile-guard-subtitle">This system is optimized for desktop devices (minimum 1366 px width). Please switch to a desktop or laptop computer.</p>
    <span class="mobile-guard-badge"><i class="fas fa-expand-arrows-alt me-1"></i>Min. 1366 px</span>
  </div>
</div>

<!-- ═══════════════════════════════════════════════════
     LOGOUT MODAL
═══════════════════════════════════════════════════ -->
<div class="modal fade" id="logoutModal" tabindex="-1">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title"><i class="fas fa-sign-out-alt me-2"></i>Confirm Logout</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center py-4"><p class="mb-0">Are you sure you want to logout?</p></div>
      <div class="modal-footer">
        <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-danger btn-sm" id="confirmLogoutBtn">Logout</button>
      </div>
    </div>
  </div>
</div>

<!-- ═══════════════════════════════════════════════════
     TOAST CONTAINER
═══════════════════════════════════════════════════ -->
<div id="toastContainer"></div>

<!-- ═══════════════════════════════════════════════════
     SCRIPTS
═══════════════════════════════════════════════════ -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/auth.js"></script>
<script src="../assets/js/app.js"></script>

<script>
/* ════════════════════════════════════════════════════
   REPORTS PAGE — MAIN SCRIPT
════════════════════════════════════════════════════ */

let currentReportType = 'incidents';
let chartByTypeInstance = null;
let chartByBarangayInstance = null;

/* ── Status options per report type ── */
const STATUS_OPTIONS = {
  incidents:  ['Active', 'Resolved', 'Monitoring', 'Closed'],
  residents:  ['Active', 'Inactive'],
  relief:     ['Pending', 'Distributed', 'Cancelled'],
  evacuation: ['Open', 'Closed', 'Full']
};

/* ── Table column definitions ── */
const TABLE_HEADERS = {
  incidents: ['ID', 'Type', 'Title', 'Location', 'Barangay', 'Date', 'Severity', 'Status'],
  residents: ['#', 'Full Name', 'Username', 'Email', 'Contact', 'Address', 'Status', 'Registered Date'],
  relief:    ['Batch #', 'Date', 'Barangay', 'Type', 'Quantity', 'Unit', 'Status'],
  evacuation:['Center Name', 'Barangay', 'Capacity', 'Occupied', 'Available', 'Contact Person', 'Status']
};

/* ────────────────────────────────────────────────────
   DOM READY / INIT
──────────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', function () {
  if (!Auth.requireAdmin()) return;

  const session = Auth.getSession();
  if (session) {
    const initial = (session.fullName || session.username || 'A')[0].toUpperCase();
    const sa = document.getElementById('sidebarUserAvatar');
    const sn = document.getElementById('sidebarUserName');
    const na = document.getElementById('navbarAvatar');
    const nu = document.getElementById('navbarUsername');
    if (sa) sa.textContent = initial;
    if (sn) sn.textContent = session.fullName || session.username;
    if (na) na.textContent = initial;
    if (nu) nu.textContent = session.username;
  }

  document.getElementById('sidebarToggle').addEventListener('click', () => {
    document.body.classList.toggle('sidebar-collapsed');
  });

  document.getElementById('confirmLogoutBtn').addEventListener('click', () => Auth.logout());
  document.querySelectorAll('[data-action="logout"]').forEach(el => {
    el.addEventListener('click', (e) => {
      e.preventDefault();
      new bootstrap.Modal(document.getElementById('logoutModal')).show();
    });
  });

  function checkMobile() {
    const guard = document.getElementById('mobileGuard');
    if (window.innerWidth < 1366) guard.classList.add('show');
    else guard.classList.remove('show');
  }
  checkMobile();
  window.addEventListener('resize', checkMobile);

  // Set default date range to cover all mock data
  const today = new Date();
  document.getElementById('filterDateTo').value = formatDateInput(today);
  document.getElementById('filterDateFrom').value = '2024-01-01';

  // Populate status dropdown for initial type
  populateStatusOptions('incidents');
});

/* ────────────────────────────────────────────────────
   HELPERS
──────────────────────────────────────────────────── */
function formatDateInput(d) {
  return d.toISOString().split('T')[0];
}

function formatDateDisplay(str) {
  if (!str) return '—';
  const d = new Date(str);
  if (isNaN(d)) return str;
  return d.toLocaleDateString('en-PH', { year: 'numeric', month: 'short', day: 'numeric' });
}

function showToast(message, type = 'info') {
  const colors = { success: '#198754', danger: '#dc3545', info: '#0dcaf0', warning: '#ffc107' };
  const icons  = { success: 'fa-check-circle', danger: 'fa-times-circle', info: 'fa-info-circle', warning: 'fa-exclamation-circle' };
  const id = 'toast_' + Date.now();
  const html = `
    <div id="${id}" class="toast align-items-center text-white border-0 show mb-2"
         style="background:${colors[type]||colors.info};border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,.18);">
      <div class="d-flex">
        <div class="toast-body d-flex align-items-center gap-2">
          <i class="fas ${icons[type]||icons.info}"></i> ${message}
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" onclick="document.getElementById('${id}').remove()"></button>
      </div>
    </div>`;
  document.getElementById('toastContainer').insertAdjacentHTML('beforeend', html);
  setTimeout(() => { const el = document.getElementById(id); if (el) el.remove(); }, 4000);
}

function populateStatusOptions(type) {
  const sel = document.getElementById('filterStatus');
  const opts = STATUS_OPTIONS[type] || [];
  sel.innerHTML = '<option value="">All Statuses</option>' +
    opts.map(s => `<option value="${s}">${s}</option>`).join('');
}

function severityBadge(severity) {
  const map = {
    critical: 'badge-severity-critical',
    high:     'badge-severity-high',
    medium:   'badge-severity-medium',
    low:      'badge-severity-low'
  };
  const key = (severity || '').toLowerCase();
  return `<span class="badge ${map[key] || 'bg-secondary'}">${severity || '—'}</span>`;
}

function statusBadge(status) {
  const map = {
    active:      'bg-success',
    open:        'bg-success',
    resolved:    'bg-primary',
    monitoring:  'bg-info text-dark',
    closed:      'bg-secondary',
    inactive:    'bg-secondary',
    pending:     'bg-warning text-dark',
    distributed: 'bg-primary',
    cancelled:   'bg-danger',
    full:        'bg-warning text-dark'
  };
  const key = (status || '').toLowerCase();
  return `<span class="badge ${map[key] || 'bg-secondary'}">${status || '—'}</span>`;
}

/* ────────────────────────────────────────────────────
   SWITCH REPORT TYPE
──────────────────────────────────────────────────── */
function switchReportType(type) {
  currentReportType = type;

  // Update tab active state
  document.querySelectorAll('#reportTypeGroup .btn').forEach(btn => {
    btn.classList.toggle('active', btn.dataset.type === type);
  });

  // Show/hide disaster type filter
  document.getElementById('filterTypeWrap').style.display =
    (type === 'incidents') ? '' : 'none';

  // Update status options
  populateStatusOptions(type);

  // Reset output
  resetReportOutput();
}

function resetReportOutput() {
  document.getElementById('emptyReportState').style.display = '';
  document.getElementById('reportHeaderBlock').style.display = 'none';
  document.getElementById('reportTableWrapper').style.display = 'none';
  document.getElementById('reportCharts').style.display = 'none';
}

/* ────────────────────────────────────────────────────
   GENERATE REPORT
──────────────────────────────────────────────────── */
async function generateReport() {
  const dateFrom = document.getElementById('filterDateFrom').value;
  const dateTo   = document.getElementById('filterDateTo').value;
  const barangay = document.getElementById('filterBarangay').value;
  const status   = document.getElementById('filterStatus').value;
  const disType  = document.getElementById('filterDisasterType').value;

  const btn = document.querySelector('[onclick="generateReport()"]');
  if (btn) { btn.disabled = true; btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Loading…'; }

  try {
    let data = [];
    switch (currentReportType) {
      case 'incidents':  data = await loadIncidentsData(dateFrom, dateTo, barangay, status, disType); break;
      case 'residents':  data = await loadResidentsData(barangay, status);                            break;
      case 'relief':     data = await loadReliefData(dateFrom, dateTo, barangay, status);             break;
      case 'evacuation': data = await loadEvacuationData(barangay, status);                           break;
    }
    renderReportHeader(dateFrom, dateTo, barangay, status, disType, data.length);
    renderReportTable(data);
    if (currentReportType === 'incidents') renderIncidentCharts(data);
    else document.getElementById('reportCharts').style.display = 'none';
  } catch (err) {
    showToast('Failed to generate report: ' + err.message, 'danger');
  } finally {
    if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fas fa-chart-bar me-1"></i>Generate Report'; }
  }
}

/* ────────────────────────────────────────────────────
   DATA LOADERS (API)
──────────────────────────────────────────────────── */
async function loadIncidentsData(dateFrom, dateTo, barangay, status, disType) {
  let qs = '';
  if (dateFrom) qs += '&date_from=' + encodeURIComponent(dateFrom);
  if (dateTo)   qs += '&date_to='   + encodeURIComponent(dateTo);
  if (barangay) qs += '&barangay='  + encodeURIComponent(barangay);
  if (status)   qs += '&status='    + encodeURIComponent(status);
  if (disType)  qs += '&disaster_type=' + encodeURIComponent(disType);
  const res = await ApiClient.get('/reports/incidents.php?' + qs.replace(/^&/, ''));
  return Array.isArray(res.data) ? res.data : [];
}

async function loadResidentsData(barangay, status) {
  let qs = '';
  if (barangay) qs += '&barangay=' + encodeURIComponent(barangay);
  if (status)   qs += '&status='   + encodeURIComponent(status);
  const res = await ApiClient.get('/reports/residents.php?' + qs.replace(/^&/, ''));
  return Array.isArray(res.data) ? res.data : [];
}

async function loadReliefData(dateFrom, dateTo, barangay, status) {
  let qs = '';
  if (dateFrom) qs += '&date_from=' + encodeURIComponent(dateFrom);
  if (dateTo)   qs += '&date_to='   + encodeURIComponent(dateTo);
  if (barangay) qs += '&barangay='  + encodeURIComponent(barangay);
  if (status)   qs += '&status='    + encodeURIComponent(status);
  const res = await ApiClient.get('/reports/relief.php?' + qs.replace(/^&/, ''));
  return Array.isArray(res.data) ? res.data : [];
}

async function loadEvacuationData(barangay, status) {
  let qs = '';
  if (barangay) qs += '&barangay=' + encodeURIComponent(barangay);
  if (status)   qs += '&status='   + encodeURIComponent(status);
  const res = await ApiClient.get('/reports/evacuation.php?' + qs.replace(/^&/, ''));
  return Array.isArray(res.data) ? res.data : [];
}

/* ────────────────────────────────────────────────────
   RENDER REPORT HEADER
──────────────────────────────────────────────────── */
function renderReportHeader(dateFrom, dateTo, barangay, status, disType, count) {
  const titles = {
    incidents:  'Disaster Incidents Report',
    residents:  'Residents Report',
    relief:     'Relief Operations Report',
    evacuation: 'Evacuation Centers Report'
  };

  const filtersApplied = [];
  if (dateFrom || dateTo) filtersApplied.push(`Date: ${dateFrom || 'start'} to ${dateTo || 'today'}`);
  if (disType && currentReportType === 'incidents') filtersApplied.push(`Type: ${disType}`);
  if (barangay) filtersApplied.push(`Barangay: ${barangay}`);
  if (status) filtersApplied.push(`Status: ${status}`);
  if (!filtersApplied.length) filtersApplied.push('All Records');

  document.getElementById('reportTitleText').textContent = titles[currentReportType];
  document.getElementById('reportMeta').innerHTML =
    `Generated: ${new Date().toLocaleString('en-PH')} &nbsp;|&nbsp; Filters: ${filtersApplied.join(', ')} &nbsp;|&nbsp; Total Records: ${count}`;

  document.getElementById('emptyReportState').style.display = 'none';
  document.getElementById('reportHeaderBlock').style.display = '';
}

/* ────────────────────────────────────────────────────
   RENDER TABLE
──────────────────────────────────────────────────── */
function renderReportTable(data) {
  const thead = document.getElementById('reportThead');
  const tbody = document.getElementById('reportTbody');
  const headers = TABLE_HEADERS[currentReportType];

  thead.innerHTML = '<tr>' + headers.map(h => `<th>${h}</th>`).join('') + '</tr>';

  if (!data.length) {
    tbody.innerHTML = `<tr><td colspan="${headers.length}" class="text-center text-muted py-4">No records found for the selected filters.</td></tr>`;
  } else {
    tbody.innerHTML = data.map((row, i) => buildRow(row, i)).join('');
  }

  document.getElementById('reportSummary').textContent =
    `Showing ${data.length} record${data.length !== 1 ? 's' : ''}.`;

  document.getElementById('reportTableWrapper').style.display = '';
}

function buildRow(row, index) {
  switch (currentReportType) {
    case 'incidents':
      return `<tr>
        <td><code>${row.incident_code || row.id || ('INC-' + (index + 1))}</code></td>
        <td>${row.disaster_type || '—'}</td>
        <td>${row.title || '—'}</td>
        <td>${row.location || '—'}</td>
        <td>${row.barangay || '—'}</td>
        <td>${formatDateDisplay(row.incident_date || row.created_at)}</td>
        <td>${severityBadge(row.severity)}</td>
        <td>${statusBadge(row.status)}</td>
      </tr>`;

    case 'residents':
      return `<tr>
        <td>${index + 1}</td>
        <td>${row.full_name || '—'}</td>
        <td>${row.username || '—'}</td>
        <td>${row.email || '—'}</td>
        <td>${row.contact_number || '—'}</td>
        <td>${row.address || '—'}</td>
        <td>${statusBadge(row.status || 'Active')}</td>
        <td>${formatDateDisplay(row.created_at)}</td>
      </tr>`;

    case 'relief':
      return `<tr>
        <td><code>${row.batch_number || ('BATCH-' + (index + 1))}</code></td>
        <td>${formatDateDisplay(row.operation_date)}</td>
        <td>${row.barangay || '—'}</td>
        <td>${row.relief_type || '—'}</td>
        <td>${row.quantity || '—'}</td>
        <td>${row.unit || '—'}</td>
        <td>${statusBadge(row.status)}</td>
      </tr>`;

    case 'evacuation': {
      const available = (parseInt(row.capacity) || 0) - (parseInt(row.occupied_slots) || 0);
      return `<tr>
        <td>${row.center_name || '—'}</td>
        <td>${row.barangay || '—'}</td>
        <td>${row.capacity || '—'}</td>
        <td>${row.occupied_slots || 0}</td>
        <td>${Math.max(0, available)}</td>
        <td>${row.contact_person || '—'}</td>
        <td>${statusBadge(row.status)}</td>
      </tr>`;
    }

    default: return '';
  }
}

/* ────────────────────────────────────────────────────
   INCIDENT CHARTS
──────────────────────────────────────────────────── */
function renderIncidentCharts(data) {
  const chartsEl = document.getElementById('reportCharts');
  chartsEl.style.display = '';

  // Destroy old chart instances
  if (chartByTypeInstance)     { chartByTypeInstance.destroy();     chartByTypeInstance = null; }
  if (chartByBarangayInstance) { chartByBarangayInstance.destroy(); chartByBarangayInstance = null; }

  // Aggregate by type
  const byType = {};
  data.forEach(r => {
    const t = r.disaster_type || 'Unknown';
    byType[t] = (byType[t] || 0) + 1;
  });

  // Aggregate by barangay
  const byBarangay = {};
  data.forEach(r => {
    const b = r.barangay || 'Unknown';
    byBarangay[b] = (byBarangay[b] || 0) + 1;
  });

  const PALETTE = ['#467235','#FFBF00','#2ecc71','#e74c3c','#9b59b6','#3498db','#FFF78D','#1abc9c'];

  // Pie chart - by type
  chartByTypeInstance = new Chart(document.getElementById('chartByType').getContext('2d'), {
    type: 'doughnut',
    data: {
      labels: Object.keys(byType),
      datasets: [{
        data: Object.values(byType),
        backgroundColor: PALETTE,
        borderWidth: 2,
        borderColor: '#fff'
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { position: 'bottom', labels: { font: { size: 11 }, padding: 10 } }
      }
    }
  });

  // Bar chart - by barangay
  chartByBarangayInstance = new Chart(document.getElementById('chartByBarangay').getContext('2d'), {
    type: 'bar',
    data: {
      labels: Object.keys(byBarangay),
      datasets: [{
        label: 'Incidents',
        data: Object.values(byBarangay),
        backgroundColor: '#467235',
        borderRadius: 5
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: {
        y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 11 } } },
        x: { ticks: { font: { size: 11 } } }
      }
    }
  });
}

/* ────────────────────────────────────────────────────
   PRINT & EXPORT
──────────────────────────────────────────────────── */
function printReport() {
  if (document.getElementById('reportHeaderBlock').style.display === 'none') {
    showToast('Please generate a report first before printing.', 'warning');
    return;
  }
  window.print();
}

async function exportPDF() {
  const type     = currentReportType;
  const dateFrom = document.getElementById('filterDateFrom').value;
  const dateTo   = document.getElementById('filterDateTo').value;
  const barangay = document.getElementById('filterBarangay').value;
  const status   = document.getElementById('filterStatus').value;
  const disType  = document.getElementById('filterDisasterType').value;

  let qs = '?report=' + type;
  if (dateFrom) qs += '&date_from=' + encodeURIComponent(dateFrom);
  if (dateTo)   qs += '&date_to='   + encodeURIComponent(dateTo);
  if (barangay) qs += '&barangay='  + encodeURIComponent(barangay);
  if (status)   qs += '&status='    + encodeURIComponent(status);
  if (disType && type === 'incidents') qs += '&disaster_type=' + encodeURIComponent(disType);

  try {
    showToast('Generating PDF…', 'info');
    await ApiClient.download('/reports/export-pdf.php' + qs);
  } catch (err) {
    showToast('PDF export failed: ' + err.message, 'danger');
  }
}

async function exportCSV() {
  const type     = currentReportType;
  const dateFrom = document.getElementById('filterDateFrom').value;
  const dateTo   = document.getElementById('filterDateTo').value;
  const barangay = document.getElementById('filterBarangay').value;
  const status   = document.getElementById('filterStatus').value;
  const disType  = document.getElementById('filterDisasterType').value;

  let qs = '?report=' + type;
  if (dateFrom) qs += '&date_from=' + encodeURIComponent(dateFrom);
  if (dateTo)   qs += '&date_to='   + encodeURIComponent(dateTo);
  if (barangay) qs += '&barangay='  + encodeURIComponent(barangay);
  if (status)   qs += '&status='    + encodeURIComponent(status);
  if (disType && type === 'incidents') qs += '&disaster_type=' + encodeURIComponent(disType);

  try {
    showToast('Generating CSV…', 'info');
    await ApiClient.download('/reports/export-csv.php' + qs);
  } catch (err) {
    showToast('CSV export failed: ' + err.message, 'danger');
  }
}
</script>
</body>
</html>
