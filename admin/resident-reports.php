<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Resident Reports — ODMIS Admin</title>

  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <!-- ODMIS Styles -->
  <link rel="stylesheet" href="../assets/css/style.css" />

  <style>
    /* ── Filter bar ── */
    .filter-bar {
      background: #fff;
      border-radius: var(--card-border-radius);
      box-shadow: var(--card-shadow);
      padding: 1rem 1.25rem;
      margin-bottom: 1.25rem;
      display: flex;
      flex-wrap: wrap;
      gap: 0.75rem;
      align-items: flex-end;
    }
    .filter-bar .filter-group {
      display: flex;
      flex-direction: column;
      gap: 0.25rem;
      min-width: 140px;
      flex: 1;
    }
    .filter-bar .filter-label {
      font-size: 0.7rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.4px;
      color: var(--color-gray);
    }
    .filter-bar .search-group {
      flex: 2;
      min-width: 200px;
    }

    /* ── Table action buttons ── */
    .btn-action {
      width: 30px;
      height: 30px;
      padding: 0;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 6px;
      font-size: 0.8rem;
      border: none;
      cursor: pointer;
      transition: all 0.15s ease;
    }
    .btn-view   { background: #d1ecf1; color: #0c5460; }
    .btn-view:hover   { background: #2980b9; color: #fff; }

    /* ── Report status badges ── */
    .badge-report-pending  { background: #fff3cd; color: #856404; padding: 3px 10px; border-radius: 20px; font-size: var(--font-size-xs); font-weight: 700; }
    .badge-report-review   { background: #d1ecf1; color: #0c5460; padding: 3px 10px; border-radius: 20px; font-size: var(--font-size-xs); font-weight: 700; }
    .badge-report-resolved { background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 20px; font-size: var(--font-size-xs); font-weight: 700; }

    /* ── Record count badge ── */
    .record-count {
      background: var(--color-primary);
      color: #fff;
      font-size: 0.7rem;
      font-weight: 700;
      padding: 2px 9px;
      border-radius: 20px;
      vertical-align: middle;
    }

    /* ── View modal detail rows ── */
    .view-field-label {
      font-size: var(--font-size-xs);
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.4px;
      color: var(--color-gray);
      min-width: 140px;
    }
    .view-field-value {
      font-size: var(--font-size-sm);
      color: var(--color-dark-gray);
    }

    /* ── Results summary bar ── */
    .results-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 0.5rem;
      margin-bottom: 0.5rem;
      font-size: var(--font-size-xs);
      color: var(--color-gray);
    }

    /* ── Report ID chip ── */
    .report-id-chip {
      font-family: monospace;
      font-weight: 700;
      color: var(--color-primary);
      font-size: var(--font-size-sm);
      background: rgba(26,58,107,0.07);
      padding: 2px 8px;
      border-radius: 4px;
      border: 1px solid rgba(26,58,107,0.15);
    }

    /* ── Pagination ── */
    .odmis-pagination {
      display: flex;
      gap: 4px;
      align-items: center;
    }
    .odmis-pagination button {
      width: 32px;
      height: 32px;
      border: 1px solid var(--color-mid-gray);
      background: #fff;
      border-radius: 6px;
      font-size: var(--font-size-xs);
      font-weight: 600;
      cursor: pointer;
      color: var(--color-dark-gray);
      transition: all 0.15s;
    }
    .odmis-pagination button:hover:not(:disabled) { background: var(--color-primary); color: #fff; border-color: var(--color-primary); }
    .odmis-pagination button.active { background: var(--color-primary); color: #fff; border-color: var(--color-primary); }
    .odmis-pagination button:disabled { opacity: 0.4; cursor: default; }

    .report-photo-thumb {
      max-width: 100%;
      max-height: 320px;
      border-radius: 8px;
      border: 1px solid var(--color-mid-gray);
    }
  </style>
</head>
<body>

<!-- ══════════════════════════════════════════════════════════
     MOBILE GUARD
══════════════════════════════════════════════════════════ -->
<div id="mobileGuard">
  <div class="text-center text-white p-4">
    <i class="fas fa-desktop mobile-guard-icon"></i>
    <h4 class="mobile-guard-title">Desktop Required</h4>
    <p class="mobile-guard-subtitle">This system is optimized for desktop devices (minimum 1366 px width). Please switch to a desktop or laptop computer.</p>
    <span class="mobile-guard-badge"><i class="fas fa-expand-arrows-alt me-1"></i>Min. 1366 px</span>
  </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     SIDEBAR
══════════════════════════════════════════════════════════ -->
<div id="sidebar">
  <div class="sidebar-logo-area">
    <div class="sidebar-logo-placeholder">
      <i class="fas fa-shield-alt"></i>
    </div>
    <div class="sidebar-brand-text">
      <span class="brand-title">ODMIS</span>
      <span class="brand-subtitle">Disaster Management</span>
    </div>
  </div>

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
        <a href="resident-reports.php" class="sidebar-nav-link active" data-page="resident-reports">
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

  <div class="sidebar-footer">
    <div class="sidebar-user-avatar" id="sidebarUserAvatar">A</div>
    <div class="sidebar-user-info">
      <span class="sidebar-user-name" id="sidebarUserName">Admin</span>
      <span class="sidebar-user-role" id="sidebarUserRole">Administrator</span>
    </div>
  </div>
</div>

<div id="sidebarOverlay"></div>

<!-- ══════════════════════════════════════════════════════════
     TOP NAVBAR
══════════════════════════════════════════════════════════ -->
<nav id="topNavbar">
  <button class="navbar-hamburger" id="sidebarToggle" title="Toggle Sidebar">
    <i class="fas fa-bars"></i>
  </button>

  <h1 class="navbar-page-title">Resident Reports</h1>

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
        <li>
          <a class="dropdown-item" href="settings.php">
            <i class="fas fa-cog me-2 text-muted"></i>Settings
          </a>
        </li>
        <li><hr class="dropdown-divider my-1"></li>
        <li>
          <a class="dropdown-item text-danger" href="#" data-action="logout">
            <i class="fas fa-sign-out-alt me-2"></i>Logout
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- ══════════════════════════════════════════════════════════
     MAIN CONTENT
══════════════════════════════════════════════════════════ -->
<main id="mainContent">

  <!-- Page Header -->
  <div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
      <h1 class="mb-1">
        <i class="fas fa-clipboard-list me-2" style="color:var(--color-accent);"></i>
        Resident Reports
      </h1>
      <p class="page-subtitle mb-0">Review incident reports submitted by residents.</p>
    </div>
  </div>

  <!-- ── FILTER BAR ── -->
  <div class="filter-bar">
    <!-- Search -->
    <div class="filter-group search-group">
      <span class="filter-label"><i class="fas fa-search me-1"></i>Search</span>
      <div class="search-wrapper">
        <i class="fas fa-search search-icon"></i>
        <input type="text" id="filterSearch" class="form-control form-control-sm"
               placeholder="Search by title, location, resident…" />
      </div>
    </div>

    <!-- Incident Type -->
    <div class="filter-group">
      <span class="filter-label"><i class="fas fa-bolt me-1"></i>Incident Type</span>
      <select id="filterType" class="form-select form-select-sm">
        <option value="">All Types</option>
        <option value="Flood">Flood</option>
        <option value="Typhoon">Typhoon</option>
        <option value="Earthquake">Earthquake</option>
        <option value="Fire">Fire</option>
        <option value="Landslide">Landslide</option>
        <option value="Other">Other</option>
      </select>
    </div>

    <!-- Status -->
    <div class="filter-group">
      <span class="filter-label"><i class="fas fa-toggle-on me-1"></i>Status</span>
      <select id="filterStatus" class="form-select form-select-sm">
        <option value="">All Statuses</option>
        <option value="Pending">Pending</option>
        <option value="Reviewed">Reviewed</option>
        <option value="Resolved">Resolved</option>
      </select>
    </div>

    <!-- Reset button -->
    <div class="filter-group" style="flex: 0; min-width: auto;">
      <span class="filter-label">&nbsp;</span>
      <button class="btn btn-outline-secondary btn-sm" id="btnResetFilters" title="Clear all filters">
        <i class="fas fa-times me-1"></i>Clear
      </button>
    </div>
  </div>

  <!-- ── REPORTS TABLE CARD ── -->
  <div class="content-card">
    <div class="content-card-header">
      <h5 class="mb-0" style="color:var(--color-primary); font-weight:700;">
        <i class="fas fa-list me-2" style="color:var(--color-accent);"></i>
        Submitted Reports
        <span class="record-count ms-2" id="recordCount">0</span>
      </h5>
    </div>

    <div class="content-card-body p-0">
      <!-- Results bar -->
      <div class="px-3 pt-3 pb-1 results-bar">
        <span id="resultsInfo">Showing all records</span>
        <div class="d-flex align-items-center gap-2">
          <label class="mb-0" style="font-size:0.75rem; color:var(--color-gray);">Per page:</label>
          <select id="perPageSelect" class="form-select form-select-sm" style="width:70px;">
            <option value="10" selected>10</option>
            <option value="25">25</option>
            <option value="50">50</option>
          </select>
        </div>
      </div>

      <!-- Table -->
      <div class="table-wrapper">
        <table class="table table-hover mb-0" id="reportsTable">
          <thead>
            <tr>
              <th>Report ID</th>
              <th>Type</th>
              <th>Title</th>
              <th>Barangay</th>
              <th>Submitted By</th>
              <th>Date</th>
              <th>Status</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody id="reportsTableBody">
            <!-- populated by JS -->
          </tbody>
        </table>
      </div>

      <!-- Empty state -->
      <div id="reportsEmptyState" class="empty-state d-none">
        <i class="fas fa-inbox empty-state-icon"></i>
        <p class="empty-state-title">No Reports Found</p>
        <p class="empty-state-text">No resident reports match your current filters, or none have been submitted yet.</p>
      </div>

      <!-- Pagination -->
      <div class="d-flex align-items-center justify-content-between px-3 py-2 border-top" id="paginationWrapper">
        <span class="text-muted" id="paginationInfo" style="font-size:var(--font-size-xs);"></span>
        <div class="odmis-pagination" id="paginationBtns"></div>
      </div>
    </div>
  </div>

</main>

<!-- ══════════════════════════════════════════════════════════
     MODAL 1 — VIEW REPORT (read-only)
══════════════════════════════════════════════════════════ -->
<div class="modal fade" id="viewReportModal" tabindex="-1" aria-labelledby="viewReportModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header" style="background:var(--color-primary-dark);">
        <h5 class="modal-title" id="viewReportModalLabel">
          <i class="fas fa-eye me-2"></i>Report Details
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body modal-body-scroll">

        <!-- ID + type header strip -->
        <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded"
             style="background:rgba(26,58,107,0.05); border:1px solid rgba(26,58,107,0.1);">
          <div>
            <div style="font-size:0.7rem; font-weight:700; text-transform:uppercase; color:var(--color-gray); letter-spacing:0.5px;">Report ID</div>
            <div class="report-id-chip mt-1" id="viewId">—</div>
          </div>
          <div class="vr mx-1"></div>
          <div>
            <div style="font-size:0.7rem; font-weight:700; text-transform:uppercase; color:var(--color-gray); letter-spacing:0.5px;">Type</div>
            <div class="mt-1" id="viewTypeBadge">—</div>
          </div>
          <div class="vr mx-1"></div>
          <div>
            <div style="font-size:0.7rem; font-weight:700; text-transform:uppercase; color:var(--color-gray); letter-spacing:0.5px;">Status</div>
            <div class="mt-1" id="viewStatusBadge">—</div>
          </div>
        </div>

        <div class="section-title">Report Information</div>
        <div class="info-row">
          <span class="info-label view-field-label">Title</span>
          <span class="info-value view-field-value fw-600" id="viewTitle">—</span>
        </div>
        <div class="info-row">
          <span class="info-label view-field-label">Description</span>
          <span class="info-value view-field-value" id="viewDescription">—</span>
        </div>

        <div class="section-title mt-4">Location Details</div>
        <div class="info-row">
          <span class="info-label view-field-label">Location / Address</span>
          <span class="info-value view-field-value" id="viewLocation">—</span>
        </div>
        <div class="info-row">
          <span class="info-label view-field-label">Barangay</span>
          <span class="info-value view-field-value" id="viewBarangay">—</span>
        </div>
        <div class="info-row">
          <span class="info-label view-field-label">Municipality</span>
          <span class="info-value view-field-value" id="viewMunicipality">—</span>
        </div>

        <div class="section-title mt-4">Date &amp; Submission</div>
        <div class="info-row">
          <span class="info-label view-field-label">Incident Date</span>
          <span class="info-value view-field-value" id="viewDate">—</span>
        </div>
        <div class="info-row">
          <span class="info-label view-field-label">Time</span>
          <span class="info-value view-field-value" id="viewTime">—</span>
        </div>
        <div class="info-row">
          <span class="info-label view-field-label">Submitted By</span>
          <span class="info-value view-field-value" id="viewSubmittedBy">—</span>
        </div>
        <div class="info-row">
          <span class="info-label view-field-label">Submitted At</span>
          <span class="info-value view-field-value" id="viewSubmittedAt">—</span>
        </div>

        <div id="viewPhotoWrap" class="mt-3 d-none">
          <div class="section-title">Attached Photo</div>
          <img id="viewPhoto" class="report-photo-thumb" src="" alt="Report photo" />
        </div>
      </div>
      <div class="modal-footer flex-wrap gap-2">
        <div class="me-auto" id="viewStatusActions"></div>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times me-1"></i>Close
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     SCRIPTS
══════════════════════════════════════════════════════════ -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/auth.js"></script>
<script src="../assets/js/app.js"></script>

<script>
/* ============================================================
   resident-reports.php — page-specific logic
   ============================================================ */
(function () {
  'use strict';

  // ── State ────────────────────────────────────────────────
  var _allReports  = [];   // full unfiltered dataset
  var _filtered     = [];
  var _currentPage  = 1;
  var _perPage      = 10;
  var _viewingId    = null;

  // ── Bootstrap modal instances ────────────────────────────
  var _viewModal;

  // ── Helpers ──────────────────────────────────────────────
  function esc(str) {
    return String(str || '')
      .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
      .replace(/"/g,'&quot;').replace(/'/g,'&#39;');
  }

  function formatDateDisplay(dateStr) {
    if (!dateStr) return '—';
    try {
      var d = new Date(dateStr + 'T00:00:00');
      if (isNaN(d.getTime())) return dateStr;
      var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
      return months[d.getMonth()] + ' ' + d.getDate() + ', ' + d.getFullYear();
    } catch(e) { return dateStr; }
  }

  function formatTimeDisplay(t) {
    if (!t) return '—';
    var parts = t.split(':');
    if (parts.length < 2) return t;
    var h    = parseInt(parts[0], 10);
    var m    = parts[1];
    var ampm = h >= 12 ? 'PM' : 'AM';
    h = h % 12 || 12;
    return h + ':' + m + ' ' + ampm;
  }

  function typeBadge(type) {
    return '<span class="badge-type">' + esc(type || '—') + '</span>';
  }

  function statusBadge(st) {
    var cls = { 'Pending':'badge-report-pending', 'Reviewed':'badge-report-review', 'Resolved':'badge-report-resolved' }[st] || 'badge-report-pending';
    return '<span class="' + cls + '">' + esc(st || 'Pending') + '</span>';
  }

  // ── API ───────────────────────────────────────────────────
  async function fetchAllReports() {
    var res = await ApiClient.get('/user-reports/index.php');
    return Array.isArray(res.data) ? res.data : [];
  }

  // ── LOAD & RENDER ─────────────────────────────────────────
  async function loadReports() {
    try {
      _allReports = await fetchAllReports();
    } catch (err) {
      showToast('Failed to load reports: ' + err.message, 'error');
      _allReports = [];
    }
    filterReports();
  }

  function filterReports() {
    var search = (document.getElementById('filterSearch').value || '').toLowerCase().trim();
    var type   = document.getElementById('filterType').value   || '';
    var status = document.getElementById('filterStatus').value || '';

    _filtered = _allReports.filter(function(r) {
      if (search) {
        var hay = [r.report_code, r.title, r.location, r.barangay, r.incident_type, r.submitted_by, r.description]
                    .join(' ').toLowerCase();
        if (!hay.includes(search)) return false;
      }
      if (type   && r.incident_type !== type)   return false;
      if (status && (r.status || 'Pending') !== status) return false;
      return true;
    });

    _filtered.sort(function(a, b) {
      return new Date(b.created_at || 0) - new Date(a.created_at || 0);
    });

    _currentPage = 1;
    renderTable();
  }

  function renderTable() {
    var tbody       = document.getElementById('reportsTableBody');
    var emptyState  = document.getElementById('reportsEmptyState');
    var countBadge  = document.getElementById('recordCount');
    var resultsInfo = document.getElementById('resultsInfo');
    var pWrapper    = document.getElementById('paginationWrapper');

    countBadge.textContent = _allReports.length;

    if (!_filtered.length) {
      tbody.innerHTML = '';
      emptyState.classList.remove('d-none');
      pWrapper.classList.add('d-none');
      resultsInfo.textContent = 'No records found';
      return;
    }

    emptyState.classList.add('d-none');
    pWrapper.classList.remove('d-none');

    var total = _filtered.length;
    var pages = Math.ceil(total / _perPage);
    _currentPage = Math.min(_currentPage, pages);
    var start = (_currentPage - 1) * _perPage;
    var slice = _filtered.slice(start, start + _perPage);

    resultsInfo.textContent = 'Showing ' + (start + 1) + '–' + Math.min(start + _perPage, total) + ' of ' + total + ' records';

    var rows = slice.map(function(r) {
      var rowId = r.id;

      return '<tr>' +
        '<td><span class="report-id-chip">' + esc(r.report_code || ('RPT-' + r.id)) + '</span></td>' +
        '<td>' + typeBadge(r.incident_type) + '</td>' +
        '<td style="max-width:220px;"><span class="fw-600" title="' + esc(r.title) + '">' + esc(r.title || '—') + '</span></td>' +
        '<td class="text-muted"><i class="fas fa-map-marker-alt me-1" style="color:var(--color-accent);font-size:0.75rem;"></i>' + esc(r.barangay || '—') + '</td>' +
        '<td class="text-muted">' + esc(r.submitted_by || '—') + '</td>' +
        '<td class="text-muted">' + formatDateDisplay(r.report_date) + '</td>' +
        '<td>' + statusBadge(r.status) + '</td>' +
        '<td class="text-center">' +
          '<div class="d-flex align-items-center justify-content-center gap-1">' +
            '<button class="btn-action btn-view" onclick="ReportsPage.viewReport(' + rowId + ')" title="View"><i class="fas fa-eye"></i></button>' +
          '</div>' +
        '</td>' +
      '</tr>';
    });
    tbody.innerHTML = rows.join('');

    renderPagination(pages);
  }

  function renderPagination(pages) {
    var container = document.getElementById('paginationBtns');
    var info      = document.getElementById('paginationInfo');

    info.textContent = 'Page ' + _currentPage + ' of ' + (pages || 1);

    var html = '';
    html += '<button ' + (_currentPage <= 1 ? 'disabled' : '') + ' onclick="ReportsPage.goPage(' + (_currentPage - 1) + ')" title="Previous"><i class="fas fa-chevron-left"></i></button>';

    var start = Math.max(1, _currentPage - 2);
    var end   = Math.min(pages, start + 4);
    if (end - start < 4) start = Math.max(1, end - 4);

    for (var p = start; p <= end; p++) {
      html += '<button class="' + (p === _currentPage ? 'active' : '') + '" onclick="ReportsPage.goPage(' + p + ')">' + p + '</button>';
    }

    html += '<button ' + (_currentPage >= pages ? 'disabled' : '') + ' onclick="ReportsPage.goPage(' + (_currentPage + 1) + ')" title="Next"><i class="fas fa-chevron-right"></i></button>';

    container.innerHTML = html;
  }

  // ── VIEW MODAL ────────────────────────────────────────────
  function viewReport(id) {
    var r = _allReports.find(function(x){ return x.id === id; });
    if (!r) { showToast('Report not found.', 'error'); return; }
    _viewingId = id;

    document.getElementById('viewId').textContent           = r.report_code || ('RPT-' + r.id);
    document.getElementById('viewTypeBadge').innerHTML       = typeBadge(r.incident_type);
    document.getElementById('viewStatusBadge').innerHTML     = statusBadge(r.status);
    document.getElementById('viewTitle').textContent         = r.title       || '—';
    document.getElementById('viewDescription').textContent   = r.description || '—';
    document.getElementById('viewLocation').textContent      = r.location    || '—';
    document.getElementById('viewBarangay').textContent      = r.barangay    || '—';
    document.getElementById('viewMunicipality').textContent  = r.municipality|| '—';
    document.getElementById('viewDate').textContent          = formatDateDisplay(r.report_date);
    document.getElementById('viewTime').textContent          = formatTimeDisplay(r.incident_time);
    document.getElementById('viewSubmittedBy').textContent   = r.submitted_by || '—';
    document.getElementById('viewSubmittedAt').textContent   = r.created_at ? new Date(r.created_at).toLocaleString('en-PH') : '—';

    var photoWrap = document.getElementById('viewPhotoWrap');
    var photoEl   = document.getElementById('viewPhoto');
    if (r.photo_path) {
      photoEl.src = '../' + r.photo_path;
      photoWrap.classList.remove('d-none');
    } else {
      photoWrap.classList.add('d-none');
    }

    renderStatusActions(r);

    _viewModal.show();
  }

  function renderStatusActions(r) {
    var wrap = document.getElementById('viewStatusActions');
    var status = r.status || 'Pending';
    var buttons = [];
    if (status !== 'Reviewed') {
      buttons.push('<button type="button" class="btn btn-outline-primary btn-sm" onclick="ReportsPage.setStatus(' + r.id + ',\'Reviewed\')"><i class="fas fa-check me-1"></i>Mark Reviewed</button>');
    }
    if (status !== 'Resolved') {
      buttons.push('<button type="button" class="btn btn-outline-success btn-sm" onclick="ReportsPage.setStatus(' + r.id + ',\'Resolved\')"><i class="fas fa-check-double me-1"></i>Mark Resolved</button>');
    }
    wrap.innerHTML = buttons.join(' ');
  }

  async function setStatus(id, status) {
    try {
      await ApiClient.patch('/user-reports/update-status.php?id=' + id, { status: status });
      showToast('Report marked as ' + status + '.', 'success');
      await loadReports();
      var r = _allReports.find(function(x){ return x.id === id; });
      if (r && _viewingId === id) { viewReport(id); }
    } catch (err) {
      showToast(err.message || 'Status update failed.', 'error');
    }
  }

  // ── PAGINATION ────────────────────────────────────────────
  function goPage(p) {
    var pages = Math.ceil(_filtered.length / _perPage);
    if (p < 1 || p > pages) return;
    _currentPage = p;
    renderTable();
    var card = document.querySelector('.content-card');
    if (card) card.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  // ── TOAST ─────────────────────────────────────────────────
  function showToast(message, type) {
    if (typeof App !== 'undefined' && typeof App.showToast === 'function') {
      App.showToast(message, type || 'success');
    }
  }

  // ── RESET FILTERS ─────────────────────────────────────────
  function resetFilters() {
    document.getElementById('filterSearch').value = '';
    document.getElementById('filterType').value   = '';
    document.getElementById('filterStatus').value = '';
    filterReports();
  }

  // ── PUBLIC API (called from inline HTML onclick) ──────────
  window.ReportsPage = {
    viewReport    : viewReport,
    setStatus     : setStatus,
    goPage        : goPage
  };

  // ── BOOT ─────────────────────────────────────────────────
  document.addEventListener('DOMContentLoaded', async function () {
    if (!Auth.requireAdmin()) return;

    if (typeof App !== 'undefined') {
      App.initPage({ page: 'resident-reports', adminPage: true, requireAdmin: true });
    }

    _viewModal = new bootstrap.Modal(document.getElementById('viewReportModal'));

    await loadReports();

    ['filterSearch','filterType','filterStatus'].forEach(function(id) {
      var el = document.getElementById(id);
      if (!el) return;
      el.addEventListener('input',  filterReports);
      el.addEventListener('change', filterReports);
    });

    document.getElementById('btnResetFilters').addEventListener('click', resetFilters);

    document.getElementById('perPageSelect').addEventListener('change', function() {
      _perPage = parseInt(this.value, 10) || 10;
      _currentPage = 1;
      renderTable();
    });
  });

})();
</script>
</body>
</html>
