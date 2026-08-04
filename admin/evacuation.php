<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Evacuation Centers — ODMIS Admin</title>

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
    .filter-bar .search-group { flex: 2; min-width: 220px; }

    /* ── Summary stat cards ── */
    .stat-mini-card {
      background: #fff;
      border-radius: var(--card-border-radius);
      box-shadow: var(--card-shadow);
      padding: 1rem 1.25rem;
      display: flex;
      align-items: center;
      gap: 1rem;
    }
    .stat-mini-icon {
      width: 44px; height: 44px;
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.25rem; flex-shrink: 0;
    }
    .stat-mini-label { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; color: var(--color-gray); }
    .stat-mini-value { font-size: 1.5rem; font-weight: 800; color: var(--color-primary); line-height: 1.1; }

    /* ── Table action buttons ── */
    .btn-action {
      width: 30px; height: 30px; padding: 0;
      display: inline-flex; align-items: center; justify-content: center;
      border-radius: 6px; font-size: 0.8rem; border: none; cursor: pointer;
      transition: all 0.15s ease;
    }
    .btn-view   { background: #d1ecf1; color: #0c5460; }
    .btn-view:hover   { background: #2980b9; color: #fff; }
    .btn-edit   { background: #fff3cd; color: #856404; }
    .btn-edit:hover   { background: #f39c12; color: #fff; }
    .btn-delete { background: #f8d7da; color: #721c24; }
    .btn-delete:hover { background: #c0392b; color: #fff; }

    /* ── Record count badge ── */
    .record-count {
      background: var(--color-primary); color: #fff;
      font-size: 0.7rem; font-weight: 700;
      padding: 2px 9px; border-radius: 20px; vertical-align: middle;
    }

    /* ── View modal detail rows ── */
    .view-field-label {
      font-size: var(--font-size-xs); font-weight: 700;
      text-transform: uppercase; letter-spacing: 0.4px;
      color: var(--color-gray); min-width: 150px;
    }
    .view-field-value { font-size: var(--font-size-sm); color: var(--color-dark-gray); }

    /* ── Results bar ── */
    .results-bar {
      display: flex; align-items: center; justify-content: space-between;
      flex-wrap: wrap; gap: 0.5rem; margin-bottom: 0.5rem;
      font-size: var(--font-size-xs); color: var(--color-gray);
    }

    /* ── Center ID chip ── */
    .center-id-chip {
      font-family: monospace; font-weight: 700; color: var(--color-primary);
      font-size: var(--font-size-sm); background: rgba(26,58,107,0.07);
      padding: 2px 8px; border-radius: 4px; border: 1px solid rgba(26,58,107,0.15);
    }

    /* ── Capacity bar in table ── */
    .cap-bar-wrap { min-width: 100px; }
    .cap-bar-label { font-size: 0.65rem; color: var(--color-gray); margin-bottom: 2px; }

    /* ── Pagination ── */
    .odmis-pagination { display: flex; gap: 4px; align-items: center; }
    .odmis-pagination button {
      width: 32px; height: 32px; border: 1px solid var(--color-mid-gray);
      background: #fff; border-radius: 6px; font-size: var(--font-size-xs);
      font-weight: 600; cursor: pointer; color: var(--color-dark-gray); transition: all 0.15s;
    }
    .odmis-pagination button:hover:not(:disabled) { background: var(--color-primary); color: #fff; border-color: var(--color-primary); }
    .odmis-pagination button.active { background: var(--color-primary); color: #fff; border-color: var(--color-primary); }
    .odmis-pagination button:disabled { opacity: 0.4; cursor: default; }
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
        <a href="resident-reports.php" class="sidebar-nav-link" data-page="resident-reports">
          <i class="fas fa-clipboard-list nav-icon"></i>
          <span class="nav-label">Resident Reports</span>
        </a>
      </li>
      <li class="sidebar-nav-item">
        <a href="evacuation.php" class="sidebar-nav-link active" data-page="evacuation">
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

  <h1 class="navbar-page-title">Evacuation Centers Management</h1>

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
        <i class="fas fa-house-damage me-2" style="color:var(--color-accent);"></i>
        Evacuation Centers Management
      </h1>
      <p class="page-subtitle mb-0">Manage evacuation centers, capacities, and occupancy across all barangays.</p>
    </div>
    <button class="btn btn-primary" id="btnAddCenter">
      <i class="fas fa-plus me-2"></i>Add Evacuation Center
    </button>
  </div>

  <!-- ── SUMMARY STATS ── -->
  <div class="row g-3 mb-4" id="summaryStats">
    <div class="col-md-4">
      <div class="stat-mini-card">
        <div class="stat-mini-icon" style="background:rgba(26,58,107,0.1); color:var(--color-primary);">
          <i class="fas fa-building"></i>
        </div>
        <div>
          <div class="stat-mini-label">Total Centers</div>
          <div class="stat-mini-value" id="statTotalCenters">0</div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat-mini-card">
        <div class="stat-mini-icon" style="background:rgba(39,174,96,0.12); color:#27ae60;">
          <i class="fas fa-users"></i>
        </div>
        <div>
          <div class="stat-mini-label">Total Capacity</div>
          <div class="stat-mini-value" id="statTotalCapacity">0</div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat-mini-card">
        <div class="stat-mini-icon" style="background:rgba(231,76,60,0.1); color:#e74c3c;">
          <i class="fas fa-user-check"></i>
        </div>
        <div>
          <div class="stat-mini-label">Total Occupied</div>
          <div class="stat-mini-value" id="statTotalOccupied">0</div>
        </div>
      </div>
    </div>
  </div>

  <!-- ── FILTER BAR ── -->
  <div class="filter-bar">
    <div class="filter-group search-group">
      <span class="filter-label"><i class="fas fa-search me-1"></i>Search</span>
      <div class="search-wrapper">
        <i class="fas fa-search search-icon"></i>
        <input type="text" id="filterSearch" class="form-control form-control-sm"
               placeholder="Search by name, location, contact…" />
      </div>
    </div>

    <div class="filter-group">
      <span class="filter-label"><i class="fas fa-map-pin me-1"></i>Barangay</span>
      <select id="filterBarangay" class="form-select form-select-sm">
        <option value="">All Barangays</option>
        <option value="Minanga">Minanga</option>
        <option value="Lubo">Lubo</option>
        <option value="Sto. Niño">Sto. Niño</option>
        <option value="Poblacion">Poblacion</option>
      </select>
    </div>

    <div class="filter-group">
      <span class="filter-label"><i class="fas fa-toggle-on me-1"></i>Status</span>
      <select id="filterStatus" class="form-select form-select-sm">
        <option value="">All Statuses</option>
        <option value="Open">Open</option>
        <option value="Closed">Closed</option>
      </select>
    </div>

    <div class="filter-group" style="flex:0; min-width:auto;">
      <span class="filter-label">&nbsp;</span>
      <button class="btn btn-outline-secondary btn-sm" id="btnResetFilters" title="Clear all filters">
        <i class="fas fa-times me-1"></i>Clear
      </button>
    </div>
  </div>

  <!-- ── TABLE CARD ── -->
  <div class="content-card">
    <div class="content-card-header">
      <h5 class="mb-0" style="color:var(--color-primary); font-weight:700;">
        <i class="fas fa-list me-2" style="color:var(--color-accent);"></i>
        Evacuation Center Records
        <span class="record-count ms-2" id="recordCount">0</span>
      </h5>
      <div class="d-flex align-items-center gap-2">
        <button class="btn btn-outline-primary btn-sm no-print" onclick="window.print()">
          <i class="fas fa-print me-1"></i>Print
        </button>
      </div>
    </div>

    <div class="content-card-body p-0">
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

      <div class="table-wrapper">
        <table class="table table-hover mb-0" id="evacuationTable">
          <thead>
            <tr>
              <th>Center ID</th>
              <th>Center Name</th>
              <th>Location / Barangay</th>
              <th>Capacity</th>
              <th>Occupied</th>
              <th>Available</th>
              <th style="min-width:130px;">Capacity Bar</th>
              <th>Contact Person</th>
              <th>Contact Number</th>
              <th>Status</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody id="evacuationTableBody">
            <!-- populated by JS -->
          </tbody>
        </table>
      </div>

      <!-- Empty state -->
      <div id="emptyState" class="text-center py-5" style="display:none!important;">
        <i class="fas fa-house-damage fa-3x mb-3" style="color:var(--color-mid-gray);"></i>
        <h6 style="color:var(--color-gray);">No evacuation centers found</h6>
        <p class="text-muted small mb-3">Try adjusting your search or filters.</p>
        <button class="btn btn-primary btn-sm" id="btnAddCenterEmpty">
          <i class="fas fa-plus me-1"></i>Add First Center
        </button>
      </div>

      <!-- Pagination -->
      <div class="d-flex align-items-center justify-content-between px-3 py-3 border-top" id="paginationBar">
        <span id="paginationInfo" style="font-size:var(--font-size-xs); color:var(--color-gray);"></span>
        <div class="odmis-pagination" id="paginationControls"></div>
      </div>
    </div>
  </div>

</main><!-- /mainContent -->


<!-- ══════════════════════════════════════════════════════════
     LOGOUT MODAL
══════════════════════════════════════════════════════════ -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title"><i class="fas fa-sign-out-alt me-2"></i>Confirm Logout</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center py-4">
        <p class="mb-0">Are you sure you want to logout?</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-danger btn-sm" id="confirmLogoutBtn">Logout</button>
      </div>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     ADD / EDIT MODAL  (#evacModal)
══════════════════════════════════════════════════════════ -->
<div class="modal fade" id="evacModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header" style="background:var(--color-primary); color:#fff;">
        <h5 class="modal-title" id="evacModalTitle">
          <i class="fas fa-plus-circle me-2"></i>Add Evacuation Center
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="evacForm" novalidate>
          <input type="hidden" id="evacId" />

          <div class="row g-3">
            <!-- Center Name -->
            <div class="col-12">
              <label class="form-label fw-semibold" for="fName">
                Center Name <span class="text-danger">*</span>
              </label>
              <input type="text" class="form-control" id="fName" placeholder="e.g. Minanga Elementary School" required />
              <div class="invalid-feedback">Center name is required.</div>
            </div>

            <!-- Location -->
            <div class="col-md-8">
              <label class="form-label fw-semibold" for="fLocation">
                Location / Address <span class="text-danger">*</span>
              </label>
              <input type="text" class="form-control" id="fLocation" placeholder="e.g. Brgy. Minanga, Bagabag, Nueva Vizcaya" required />
              <div class="invalid-feedback">Location is required.</div>
            </div>

            <!-- Barangay -->
            <div class="col-md-4">
              <label class="form-label fw-semibold" for="fBarangay">
                Barangay <span class="text-danger">*</span>
              </label>
              <select class="form-select" id="fBarangay" required>
                <option value="">Select Barangay</option>
                <option value="Minanga">Minanga</option>
                <option value="Lubo">Lubo</option>
                <option value="Sto. Niño">Sto. Niño</option>
                <option value="Poblacion">Poblacion</option>
              </select>
              <div class="invalid-feedback">Please select a barangay.</div>
            </div>

            <!-- Capacity -->
            <div class="col-md-4">
              <label class="form-label fw-semibold" for="fCapacity">
                Capacity <span class="text-danger">*</span>
              </label>
              <input type="number" class="form-control" id="fCapacity" min="1" placeholder="e.g. 500" required />
              <div class="invalid-feedback">Capacity must be at least 1.</div>
            </div>

            <!-- Occupied Slots -->
            <div class="col-md-4">
              <label class="form-label fw-semibold" for="fOccupied">
                Occupied Slots <span class="text-danger">*</span>
              </label>
              <input type="number" class="form-control" id="fOccupied" min="0" placeholder="e.g. 230" required />
              <div class="invalid-feedback">Occupied slots is required.</div>
            </div>

            <!-- Status -->
            <div class="col-md-4">
              <label class="form-label fw-semibold" for="fStatus">Status</label>
              <select class="form-select" id="fStatus">
                <option value="Open">Open</option>
                <option value="Closed">Closed</option>
              </select>
            </div>

            <!-- Contact Person -->
            <div class="col-md-6">
              <label class="form-label fw-semibold" for="fContactPerson">Contact Person</label>
              <input type="text" class="form-control" id="fContactPerson" placeholder="e.g. Maria Santos" />
            </div>

            <!-- Contact Number -->
            <div class="col-md-6">
              <label class="form-label fw-semibold" for="fContactNumber">Contact Number</label>
              <input type="text" class="form-control" id="fContactNumber" placeholder="e.g. 09171234567" />
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times me-1"></i>Cancel
        </button>
        <button type="button" class="btn btn-primary" id="btnSaveCenter">
          <i class="fas fa-save me-1"></i>Save Center
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     VIEW MODAL  (#viewEvacModal)
══════════════════════════════════════════════════════════ -->
<div class="modal fade" id="viewEvacModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header" style="background:var(--color-primary); color:#fff;">
        <h5 class="modal-title">
          <i class="fas fa-eye me-2"></i>Evacuation Center Details
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">

        <!-- Header strip -->
        <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded-2" style="background:rgba(26,58,107,0.06);">
          <div style="width:52px;height:52px;border-radius:12px;background:var(--color-primary);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fas fa-house-damage fa-lg text-white"></i>
          </div>
          <div>
            <h5 class="mb-0 fw-bold" id="vName" style="color:var(--color-primary);"></h5>
            <small class="text-muted" id="vId"></small>
          </div>
          <div class="ms-auto">
            <span id="vStatusBadge" class="badge fs-6"></span>
          </div>
        </div>

        <!-- Fields grid -->
        <div class="row g-3">
          <div class="col-md-6">
            <div class="view-field-label">Location / Address</div>
            <div class="view-field-value mt-1" id="vLocation">—</div>
          </div>
          <div class="col-md-6">
            <div class="view-field-label">Barangay</div>
            <div class="view-field-value mt-1" id="vBarangay">—</div>
          </div>
          <div class="col-md-4">
            <div class="view-field-label">Capacity</div>
            <div class="view-field-value mt-1 fw-bold" id="vCapacity">—</div>
          </div>
          <div class="col-md-4">
            <div class="view-field-label">Occupied</div>
            <div class="view-field-value mt-1 fw-bold" id="vOccupied">—</div>
          </div>
          <div class="col-md-4">
            <div class="view-field-label">Available</div>
            <div class="view-field-value mt-1 fw-bold" id="vAvailable">—</div>
          </div>
          <div class="col-12">
            <div class="view-field-label">Occupancy</div>
            <div class="mt-2">
              <div class="d-flex justify-content-between mb-1">
                <small id="vCapPct" class="fw-semibold">0%</small>
                <small class="text-muted" id="vCapFraction"></small>
              </div>
              <div class="progress" style="height:12px; border-radius:8px;">
                <div class="progress-bar" id="vCapBar" role="progressbar" style="width:0%; border-radius:8px; transition:width 0.4s ease;"></div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="view-field-label">Contact Person</div>
            <div class="view-field-value mt-1" id="vContactPerson">—</div>
          </div>
          <div class="col-md-6">
            <div class="view-field-label">Contact Number</div>
            <div class="view-field-value mt-1" id="vContactNumber">—</div>
          </div>
          <div class="col-md-6">
            <div class="view-field-label">Date Added</div>
            <div class="view-field-value mt-1" id="vCreatedAt">—</div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-warning text-dark" id="btnViewToEdit">
          <i class="fas fa-edit me-1"></i>Edit
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     DELETE CONFIRM MODAL  (#deleteEvacModal)
══════════════════════════════════════════════════════════ -->
<div class="modal fade" id="deleteEvacModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title"><i class="fas fa-trash-alt me-2"></i>Delete Center</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center py-4">
        <i class="fas fa-exclamation-circle fa-2x text-danger mb-3"></i>
        <p class="mb-1">Are you sure you want to delete</p>
        <p class="fw-bold mb-0" id="deleteTargetName">this center</p>
        <p class="text-muted small mt-2 mb-0">This action cannot be undone.</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-danger btn-sm" id="confirmDeleteBtn">
          <i class="fas fa-trash-alt me-1"></i>Delete
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     TOAST NOTIFICATION
══════════════════════════════════════════════════════════ -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index:9999;">
  <div id="odmisToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive">
    <div class="d-flex">
      <div class="toast-body" id="toastMessage">Action completed.</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
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
   EVACUATION CENTERS — Page Logic
   LocalStorage key: odmis_evacuation_centers
============================================================ */

// ── State ──────────────────────────────────────────────────
let allCenters    = [];
let filteredCenters = [];
let currentPage   = 1;
let perPage       = 10;
let deleteTargetId = null;
let viewTargetId   = null;

// ── Bootstrap modal instances ─────────────────────────────
const evacModalEl    = document.getElementById('evacModal');
const viewModalEl    = document.getElementById('viewEvacModal');
const deleteModalEl  = document.getElementById('deleteEvacModal');
const logoutModalEl  = document.getElementById('logoutModal');
let evacModalInst, viewModalInst, deleteModalInst, logoutModalInst;

// ── Toast helper ──────────────────────────────────────────
function showToast(message, type = 'success') {
  const el = document.getElementById('odmisToast');
  el.className = 'toast align-items-center text-white border-0';
  const bg = { success:'bg-success', danger:'bg-danger', warning:'bg-warning text-dark', info:'bg-info' }[type] || 'bg-success';
  el.classList.add(...bg.split(' '));
  document.getElementById('toastMessage').textContent = message;
  bootstrap.Toast.getOrCreateInstance(el, { delay: 3000 }).show();
}

// ── Capacity bar helper ────────────────────────────────────
function buildCapBar(capacity, occupied) {
  const pct = capacity > 0 ? Math.min(100, Math.round((occupied / capacity) * 100)) : 0;
  let cls = 'bg-success';
  if (pct > 80) cls = 'bg-danger';
  else if (pct >= 50) cls = 'bg-warning';
  return `
    <div class="cap-bar-wrap">
      <div class="cap-bar-label">${pct}% (${occupied}/${capacity})</div>
      <div class="progress" style="height:8px; border-radius:4px;">
        <div class="progress-bar ${cls}" role="progressbar"
             style="width:${pct}%; border-radius:4px;"
             aria-valuenow="${pct}" aria-valuemin="0" aria-valuemax="100"></div>
      </div>
    </div>`;
}

// ── Status badge ───────────────────────────────────────────
function statusBadge(status) {
  const s = (status || '').toLowerCase();
  if (s === 'open')   return '<span class="badge bg-success">Open</span>';
  if (s === 'closed') return '<span class="badge bg-secondary">Closed</span>';
  return `<span class="badge bg-light text-dark">${status}</span>`;
}

// ── Load & render ─────────────────────────────────────────
async function loadCenters() {
  try {
    const res = await ApiClient.get('/evacuation/index.php');
    allCenters = Array.isArray(res.data) ? res.data : [];
  } catch (err) {
    showToast('Failed to load centers: ' + err.message, 'danger');
    allCenters = [];
  }
  filterCenters();
}

function filterCenters() {
  const search    = (document.getElementById('filterSearch').value || '').toLowerCase().trim();
  const barangay  = document.getElementById('filterBarangay').value;
  const statusVal = document.getElementById('filterStatus').value;

  filteredCenters = allCenters.filter(c => {
    const matchSearch = !search ||
      (c.center_name    && c.center_name.toLowerCase().includes(search)) ||
      (c.location       && c.location.toLowerCase().includes(search)) ||
      (c.contact_person && c.contact_person.toLowerCase().includes(search)) ||
      (c.contact_number && c.contact_number.includes(search)) ||
      (c.center_code    && c.center_code.toLowerCase().includes(search));
    const matchBrgy   = !barangay  || c.barangay === barangay;
    const matchStatus = !statusVal || c.status   === statusVal;
    return matchSearch && matchBrgy && matchStatus;
  });

  currentPage = 1;
  renderTable();
  renderSummary();
}

function renderTable() {
  const tbody   = document.getElementById('evacuationTableBody');
  const empty   = document.getElementById('emptyState');
  const pagBar  = document.getElementById('paginationBar');
  const total   = filteredCenters.length;
  const start   = (currentPage - 1) * perPage;
  const slice   = filteredCenters.slice(start, start + perPage);

  document.getElementById('recordCount').textContent = total;
  document.getElementById('resultsInfo').textContent =
    total === 0 ? 'No records found'
    : `Showing ${start + 1}–${Math.min(start + perPage, total)} of ${total} records`;

  if (total === 0) {
    tbody.innerHTML = '';
    empty.style.removeProperty('display');
    pagBar.style.display = 'none';
    return;
  }

  empty.style.setProperty('display', 'none', 'important');
  pagBar.style.display = 'flex';

  tbody.innerHTML = slice.map(c => {
    const avail = (c.capacity || 0) - (c.occupied_slots || 0);
    return `
      <tr>
        <td><span class="center-id-chip">${escHtml(c.center_code)}</span></td>
        <td class="fw-semibold">${escHtml(c.center_name)}</td>
        <td>
          <div style="max-width:220px; white-space:normal; line-height:1.3;">
            ${escHtml(c.location)}
          </div>
          <small class="text-muted">${escHtml(c.barangay)}</small>
        </td>
        <td class="text-center">${c.capacity}</td>
        <td class="text-center">${c.occupied_slots}</td>
        <td class="text-center">${avail}</td>
        <td>${buildCapBar(c.capacity, c.occupied_slots)}</td>
        <td>${escHtml(c.contact_person || '—')}</td>
        <td>${escHtml(c.contact_number || '—')}</td>
        <td>${statusBadge(c.status)}</td>
        <td class="text-center" style="white-space:nowrap;">
          <button class="btn-action btn-view me-1" title="View" onclick="viewCenter(${c.id})">
            <i class="fas fa-eye"></i>
          </button>
          <button class="btn-action btn-edit me-1" title="Edit" onclick="editCenter(${c.id})">
            <i class="fas fa-edit"></i>
          </button>
          <button class="btn-action btn-delete" title="Delete" onclick="deleteCenter(${c.id})">
            <i class="fas fa-trash-alt"></i>
          </button>
        </td>
      </tr>`;
  }).join('');

  renderPagination(total);
}

function renderPagination(total) {
  const totalPages = Math.ceil(total / perPage);
  const info = document.getElementById('paginationInfo');
  const ctrl = document.getElementById('paginationControls');

  info.textContent = `Page ${currentPage} of ${totalPages}`;

  let html = `<button ${currentPage === 1 ? 'disabled' : ''} onclick="goPage(${currentPage - 1})">
                <i class="fas fa-chevron-left"></i></button>`;
  for (let i = 1; i <= totalPages; i++) {
    if (totalPages > 7 && i > 2 && i < totalPages - 1 && Math.abs(i - currentPage) > 1) {
      if (i === 3 || i === totalPages - 2) html += `<button disabled>…</button>`;
      continue;
    }
    html += `<button class="${i === currentPage ? 'active' : ''}" onclick="goPage(${i})">${i}</button>`;
  }
  html += `<button ${currentPage === totalPages ? 'disabled' : ''} onclick="goPage(${currentPage + 1})">
             <i class="fas fa-chevron-right"></i></button>`;
  ctrl.innerHTML = html;
}

function goPage(p) { currentPage = p; renderTable(); }

function renderSummary() {
  document.getElementById('statTotalCenters').textContent  = allCenters.length;
  document.getElementById('statTotalCapacity').textContent = allCenters.reduce((a, c) => a + (c.capacity || 0), 0);
  document.getElementById('statTotalOccupied').textContent = allCenters.reduce((a, c) => a + (c.occupied_slots || 0), 0);
}

// ── HTML escaping ─────────────────────────────────────────
function escHtml(s) {
  if (!s) return '';
  return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ── Open Add modal ─────────────────────────────────────────
function openAddModal() {
  document.getElementById('evacModalTitle').innerHTML =
    '<i class="fas fa-plus-circle me-2"></i>Add Evacuation Center';
  document.getElementById('evacId').value      = '';
  document.getElementById('evacForm').reset();
  document.getElementById('evacForm').classList.remove('was-validated');
  evacModalInst.show();
}

// ── Edit center ───────────────────────────────────────────
function editCenter(id) {
  const c = allCenters.find(x => x.id === id);
  if (!c) return;
  document.getElementById('evacModalTitle').innerHTML =
    '<i class="fas fa-edit me-2"></i>Edit Evacuation Center';
  document.getElementById('evacId').value          = c.id;
  document.getElementById('fName').value           = c.center_name     || '';
  document.getElementById('fLocation').value       = c.location        || '';
  document.getElementById('fBarangay').value       = c.barangay        || '';
  document.getElementById('fCapacity').value       = c.capacity        || '';
  document.getElementById('fOccupied').value       = c.occupied_slots  || 0;
  document.getElementById('fStatus').value         = c.status          || 'Open';
  document.getElementById('fContactPerson').value  = c.contact_person  || '';
  document.getElementById('fContactNumber').value  = c.contact_number  || '';
  document.getElementById('evacForm').classList.remove('was-validated');
  evacModalInst.show();
}

// ── Save center ───────────────────────────────────────────
async function saveCenter() {
  const form = document.getElementById('evacForm');
  form.classList.add('was-validated');
  if (!form.checkValidity()) return;

  const dbId    = document.getElementById('evacId').value.trim();
  const cap     = parseInt(document.getElementById('fCapacity').value, 10);
  const occ     = parseInt(document.getElementById('fOccupied').value, 10) || 0;

  if (occ > cap) {
    document.getElementById('fOccupied').setCustomValidity('Occupied cannot exceed capacity.');
    document.getElementById('fOccupied').reportValidity();
    return;
  }
  document.getElementById('fOccupied').setCustomValidity('');

  const payload = {
    center_name   : document.getElementById('fName').value.trim(),
    location      : document.getElementById('fLocation').value.trim(),
    barangay      : document.getElementById('fBarangay').value,
    capacity      : cap,
    occupied_slots: occ,
    status        : document.getElementById('fStatus').value,
    contact_person: document.getElementById('fContactPerson').value.trim(),
    contact_number: document.getElementById('fContactNumber').value.trim()
  };

  const btn = document.getElementById('btnSaveCenter');
  btn.disabled = true;
  try {
    if (dbId) {
      await ApiClient.put('/evacuation/update.php?id=' + dbId, payload);
      showToast('Evacuation center updated successfully.', 'success');
    } else {
      await ApiClient.post('/evacuation/store.php', payload);
      showToast('Evacuation center added successfully.', 'success');
    }
    evacModalInst.hide();
    await loadCenters();
  } catch (err) {
    showToast(err.message || 'Save failed.', 'danger');
  } finally {
    btn.disabled = false;
  }
}

// ── View center ────────────────────────────────────────────
function viewCenter(id) {
  const c = allCenters.find(x => x.id === id);
  if (!c) return;
  viewTargetId = id;

  document.getElementById('vName').textContent          = c.center_name    || '—';
  document.getElementById('vId').textContent            = c.center_code    || '—';
  document.getElementById('vLocation').textContent      = c.location       || '—';
  document.getElementById('vBarangay').textContent      = c.barangay       || '—';
  document.getElementById('vCapacity').textContent      = c.capacity       || 0;
  document.getElementById('vOccupied').textContent      = c.occupied_slots || 0;
  document.getElementById('vAvailable').textContent     = (c.capacity || 0) - (c.occupied_slots || 0);
  document.getElementById('vContactPerson').textContent = c.contact_person || '—';
  document.getElementById('vContactNumber').textContent = c.contact_number || '—';
  document.getElementById('vCreatedAt').textContent     = c.created_at     || '—';

  const pct = c.capacity > 0 ? Math.min(100, Math.round((c.occupied_slots / c.capacity) * 100)) : 0;
  let barCls = 'bg-success';
  if (pct > 80) barCls = 'bg-danger';
  else if (pct >= 50) barCls = 'bg-warning';

  const bar = document.getElementById('vCapBar');
  bar.className = `progress-bar ${barCls}`;
  bar.style.width = `${pct}%`;
  document.getElementById('vCapPct').textContent      = `${pct}%`;
  document.getElementById('vCapFraction').textContent = `${c.occupied_slots} / ${c.capacity}`;

  const badge = document.getElementById('vStatusBadge');
  if ((c.status || '').toLowerCase() === 'open') {
    badge.className = 'badge bg-success fs-6'; badge.textContent = 'Open';
  } else {
    badge.className = 'badge bg-secondary fs-6'; badge.textContent = c.status || 'Closed';
  }

  viewModalInst.show();
}

// ── Delete center ─────────────────────────────────────────
function deleteCenter(id) {
  const c = allCenters.find(x => x.id === id);
  if (!c) return;
  deleteTargetId = id;
  document.getElementById('deleteTargetName').textContent = `"${c.center_name}"`;
  deleteModalInst.show();
}

async function confirmDelete() {
  if (!deleteTargetId) return;
  const btn = document.getElementById('confirmDeleteBtn');
  btn.disabled = true;
  try {
    await ApiClient.del('/evacuation/destroy.php?id=' + deleteTargetId);
    deleteTargetId = null;
    deleteModalInst.hide();
    showToast('Evacuation center deleted.', 'danger');
    await loadCenters();
  } catch (err) {
    showToast(err.message || 'Delete failed.', 'danger');
  } finally {
    btn.disabled = false;
  }
}

// ── DOMContentLoaded ──────────────────────────────────────
document.addEventListener('DOMContentLoaded', async () => {
  if (!Auth.requireAdmin()) return;

  const session = Auth.getSession();
  if (session) {
    const initLetter = session.fullName ? session.fullName[0].toUpperCase() : 'A';
    const sidebarAvatar = document.getElementById('sidebarUserAvatar');
    const sidebarName   = document.getElementById('sidebarUserName');
    const navAvatar     = document.getElementById('navbarAvatar');
    const navUsername   = document.getElementById('navbarUsername');
    if (sidebarAvatar) sidebarAvatar.textContent = initLetter;
    if (sidebarName)   sidebarName.textContent   = session.fullName || session.username;
    if (navAvatar)     navAvatar.textContent      = initLetter;
    if (navUsername)   navUsername.textContent    = session.username;
  }

  // Modal instances
  evacModalInst   = new bootstrap.Modal(evacModalEl);
  viewModalInst   = new bootstrap.Modal(viewModalEl);
  deleteModalInst = new bootstrap.Modal(deleteModalEl);
  logoutModalInst = new bootstrap.Modal(logoutModalEl);

  // Sidebar toggle
  document.getElementById('sidebarToggle').addEventListener('click', () => {
    document.body.classList.toggle('sidebar-collapsed');
  });
  document.getElementById('sidebarOverlay')?.addEventListener('click', () => {
    document.body.classList.remove('sidebar-collapsed');
  });

  // Logout
  document.getElementById('confirmLogoutBtn').addEventListener('click', () => {
    if (typeof Auth !== 'undefined') Auth.logout();
  });
  document.querySelectorAll('[data-action="logout"]').forEach(el => {
    el.addEventListener('click', e => { e.preventDefault(); logoutModalInst.show(); });
  });

  // Mobile guard
  function checkMobile() {
    const guard = document.getElementById('mobileGuard');
    if (guard) {
      if (window.innerWidth < 1366) guard.classList.add('show');
      else guard.classList.remove('show');
    }
  }
  checkMobile();
  window.addEventListener('resize', checkMobile);

  // Add Center buttons
  document.getElementById('btnAddCenter').addEventListener('click', openAddModal);
  document.getElementById('btnAddCenterEmpty')?.addEventListener('click', openAddModal);

  // Save
  document.getElementById('btnSaveCenter').addEventListener('click', saveCenter);

  // "Edit" from View modal
  document.getElementById('btnViewToEdit').addEventListener('click', () => {
    viewModalInst.hide();
    viewModalEl.addEventListener('hidden.bs.modal', () => { if (viewTargetId) { editCenter(viewTargetId); viewTargetId = null; } }, { once: true });
  });

  // Delete confirm
  document.getElementById('confirmDeleteBtn').addEventListener('click', confirmDelete);

  // Filters
  document.getElementById('filterSearch').addEventListener('input', filterCenters);
  document.getElementById('filterBarangay').addEventListener('change', filterCenters);
  document.getElementById('filterStatus').addEventListener('change', filterCenters);
  document.getElementById('btnResetFilters').addEventListener('click', () => {
    document.getElementById('filterSearch').value   = '';
    document.getElementById('filterBarangay').value = '';
    document.getElementById('filterStatus').value   = '';
    filterCenters();
  });

  // Per-page
  document.getElementById('perPageSelect').addEventListener('change', e => {
    perPage = parseInt(e.target.value, 10);
    currentPage = 1;
    renderTable();
  });

  // Initial load
  await loadCenters();
});
</script>
</body>
</html>
