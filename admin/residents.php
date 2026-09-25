<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Residents Management — ODMIS Admin</title>

  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../assets/vendor/fontawesome/css/all.min.css" />
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
      min-width: 220px;
    }

    /* ── Info note ── */
    .info-note {
      background: #e8f4fd;
      border: 1px solid #bee3f8;
      border-radius: 8px;
      padding: 0.75rem 1rem;
      margin-bottom: 1.25rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      font-size: var(--font-size-sm);
      color: #2c5f87;
    }
    .info-note i {
      font-size: 1rem;
      flex-shrink: 0;
    }

    /* ── Table action buttons ── */
    .btn-action {
      width: 34px;
      height: 34px;
      padding: 0;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 6px;
      font-size: 0.85rem;
      border: none;
      cursor: pointer;
      transition: all 0.15s ease;
    }
    .btn-view {
      background: #d1ecf1;
      color: #0c5460;
    }
    .btn-view:hover {
      background: #2980b9;
      color: #fff;
    }
    .btn-activate {
      background: #d4edda;
      color: #155724;
    }
    .btn-activate:hover {
      background: #27ae60;
      color: #fff;
    }
    .btn-deactivate {
      background: #fff3cd;
      color: #856404;
    }
    .btn-deactivate:hover {
      background: #f39c12;
      color: #fff;
    }

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
    }
    .view-field-value {
      font-size: var(--font-size-sm);
      color: var(--color-dark-gray);
      word-break: break-word;
    }

    /* ── Results bar ── */
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

    /* ── User avatar chip ── */
    .user-avatar-chip {
      width: 34px;
      height: 34px;
      border-radius: 50%;
      background: var(--color-primary);
      color: #fff;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 0.85rem;
      font-weight: 700;
      flex-shrink: 0;
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
    .odmis-pagination button:hover:not(:disabled) {
      background: var(--color-primary);
      color: #fff;
      border-color: var(--color-primary);
    }
    .odmis-pagination button.active {
      background: var(--color-primary);
      color: #fff;
      border-color: var(--color-primary);
    }
    .odmis-pagination button:disabled {
      opacity: 0.4;
      cursor: default;
    }

    /* ── View modal avatar ── */
    .view-user-avatar {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      background: var(--color-primary);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      font-weight: 700;
      flex-shrink: 0;
      overflow: hidden;
    }

    /* ══════════════════════════════════════════════════════════
       MOBILE & RESPONSIVE REFINEMENTS
       ══════════════════════════════════════════════════════════ */
    .table-wrapper {
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
    }

    @media (max-width: 991.98px) {
      #sidebar {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        bottom: 0 !important;
        height: 100vh !important;
        width: 260px !important;
        max-width: 82vw !important;
        z-index: 1060 !important;
        transform: translateX(-100%) !important;
        transition: transform 0.25s ease-in-out !important;
        box-shadow: 4px 0 20px rgba(0,0,0,0.3) !important;
        display: flex !important;
        flex-direction: column !important;
      }

      #sidebar.mobile-open {
        transform: translateX(0) !important;
        visibility: visible !important;
      }

      /* Force labels, logo brand text, and user info to be fully visible and readable on mobile */
      #sidebar.mobile-open .nav-label,
      #sidebar .nav-label,
      body.sidebar-collapsed #sidebar.mobile-open .nav-label {
        opacity: 1 !important;
        width: auto !important;
        overflow: visible !important;
        white-space: normal !important;
        pointer-events: auto !important;
        display: inline-block !important;
      }

      #sidebar.mobile-open .sidebar-brand-text,
      #sidebar .sidebar-brand-text,
      body.sidebar-collapsed #sidebar.mobile-open .sidebar-brand-text {
        opacity: 1 !important;
        width: auto !important;
        overflow: visible !important;
        white-space: normal !important;
        pointer-events: auto !important;
        display: flex !important;
      }

      #sidebar.mobile-open .sidebar-user-info,
      #sidebar .sidebar-user-info,
      body.sidebar-collapsed #sidebar.mobile-open .sidebar-user-info {
        opacity: 1 !important;
        width: auto !important;
        overflow: visible !important;
        white-space: normal !important;
        pointer-events: auto !important;
        display: flex !important;
      }

      #sidebar.mobile-open .sidebar-nav-link,
      body.sidebar-collapsed #sidebar.mobile-open .sidebar-nav-link {
        justify-content: flex-start !important;
        padding: 0.75rem 1.25rem !important;
      }

      #sidebar.mobile-open .sidebar-nav-link .nav-icon,
      body.sidebar-collapsed #sidebar.mobile-open .sidebar-nav-link .nav-icon {
        margin-right: 0.85rem !important;
      }

      #sidebarOverlay {
        position: fixed !important;
        inset: 0 !important;
        background: rgba(0, 0, 0, 0.5) !important;
        z-index: 1055 !important;
        display: none;
      }

      #sidebarOverlay.show {
        display: block !important;
      }

      #topNavbar {
        left: 0 !important;
        z-index: 1040 !important;
      }

      .navbar-hamburger {
        cursor: pointer !important;
        z-index: 1045 !important;
        display: inline-flex !important;
        padding: 0.45rem 0.6rem !important;
      }

      #mainContent {
        margin-left: 0 !important;
        padding: 1rem 0.85rem;
      }
    }

    @media (max-width: 767.98px) {
      .filter-bar {
        padding: 0.85rem;
        gap: 0.65rem;
      }
      .filter-bar .search-group {
        min-width: 100%;
        flex: 1 1 100%;
      }
      .filter-bar .filter-group:not(.search-group) {
        min-width: calc(50% - 0.35rem);
        flex: 1 1 calc(50% - 0.35rem);
      }
      .filter-bar .filter-actions {
        min-width: 100%;
        flex: 1 1 100%;
      }
      .filter-bar .filter-actions button {
        width: 100%;
      }
    }

    @media (max-width: 575.98px) {
      .navbar-user-name {
        display: none !important;
      }
      .navbar-page-title {
        font-size: 1rem;
      }
      .page-header h1 {
        font-size: 1.35rem;
      }
      .page-header .page-subtitle {
        font-size: 0.8rem;
      }
      .results-bar {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 0.4rem;
      }
      #paginationBar {
        flex-direction: column;
        gap: 0.75rem;
        align-items: center !important;
        text-align: center;
        padding: 0.85rem 1rem !important;
      }
      .odmis-pagination {
        flex-wrap: wrap;
        justify-content: center;
      }
      .view-user-header {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 0.75rem !important;
      }
      .view-user-header .ms-auto {
        margin-left: 0 !important;
      }
    }
  </style>
</head>
<body>

<!-- ══════════════════════════════════════════════════════════
     SIDEBAR
══════════════════════════════════════════════════════════ -->
<div id="sidebar">
  <div class="sidebar-logo-area">
    <img src="../img/odmis_logo.jpg" alt="ODMIS Logo" class="sidebar-logo-img">
    <div class="sidebar-brand-text">
      <span class="brand-title">ODMIS</span>
      <span class="brand-subtitle">Disaster Management</span>
    </div>
    <button type="button" class="sidebar-close-btn d-lg-none" id="sidebarClose" title="Close Menu">
      <i class="fas fa-times"></i>
    </button>
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
        <a href="evacuation.php" class="sidebar-nav-link" data-page="evacuation">
          <i class="fas fa-house-damage nav-icon"></i>
          <span class="nav-label">Evacuation Centers</span>
        </a>
      </li>
      <li class="sidebar-nav-item">
        <a href="residents.php" class="sidebar-nav-link active" data-page="residents">
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
        <a href="announcements.php" class="sidebar-nav-link" data-page="announcements">
          <i class="fas fa-bullhorn nav-icon"></i>
          <span class="nav-label">Announcements</span>
        </a>
      </li>
      <li class="sidebar-nav-item">
        <a href="sms.php" class="sidebar-nav-link" data-page="sms">
          <i class="fas fa-sms nav-icon"></i>
          <span class="nav-label">SMS Broadcast</span>
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
  <button class="navbar-hamburger" id="sidebarToggle" title="Toggle Sidebar" type="button" aria-label="Toggle Sidebar Navigation">
    <i class="fas fa-bars"></i>
  </button>

  <h1 class="navbar-page-title">Residents Management</h1>

  <div class="navbar-right">
    <button class="navbar-icon-btn position-relative" title="Notifications" onclick="App.showNotificationModal()">
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
          <a class="dropdown-item text-danger" href="javascript:void(0)" data-action="logout" onclick="event.preventDefault(); App.handleLogout(event);">
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
        <i class="fas fa-users me-2" style="color:var(--color-accent);"></i>
        Residents Management
      </h1>
      <p class="page-subtitle mb-0">View and manage registered resident accounts in the system.</p>
    </div>
  </div>

  <!-- ── INFO NOTE ── -->
  <div class="info-note">
    <i class="fas fa-info-circle"></i>
    <span>Residents are registered system users. This list shows all accounts with the <strong>user</strong> role. Use the Status filter and toggle actions to activate or deactivate accounts.</span>
  </div>

  <!-- ── FILTER BAR ── -->
  <div class="filter-bar">
    <div class="filter-group search-group">
      <span class="filter-label"><i class="fas fa-search me-1"></i>Search</span>
      <div class="search-wrapper">
        <i class="fas fa-search search-icon"></i>
        <input type="text" id="filterSearch" class="form-control form-control-sm"
               placeholder="Search by name, username, email, contact…" />
      </div>
    </div>

    <div class="filter-group">
      <span class="filter-label"><i class="fas fa-toggle-on me-1"></i>Status</span>
      <select id="filterStatus" class="form-select form-select-sm">
        <option value="">All Statuses</option>
        <option value="Active">Active</option>
        <option value="Inactive">Inactive</option>
      </select>
    </div>

    <div class="filter-group filter-actions" style="flex:0; min-width:auto;">
      <span class="filter-label d-none d-md-inline">&nbsp;</span>
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
        Resident Records
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

      <!-- Mobile swipe hint -->
      <div class="d-md-none px-3 py-2 text-muted" style="font-size:0.75rem; background:#f8fafc; border-top:1px solid #edf2f7; border-bottom:1px solid #edf2f7;">
        <i class="fas fa-arrows-left-right me-1 text-primary"></i> Swipe horizontally to view full table details
      </div>

      <div class="table-wrapper">
        <table class="table table-hover mb-0" id="residentsTable">
          <thead>
            <tr>
              <th style="width:50px;" class="text-nowrap">#</th>
              <th class="text-nowrap">Full Name</th>
              <th class="text-nowrap">Username</th>
              <th class="text-nowrap">Email</th>
              <th class="text-nowrap">Contact Number</th>
              <th class="text-nowrap">Date of Birth</th>
              <th class="text-nowrap">Address</th>
              <th class="text-nowrap">Status</th>
              <th class="text-center text-nowrap">Actions</th>
            </tr>
          </thead>
          <tbody id="residentsTableBody">
            <!-- populated by JS -->
          </tbody>
        </table>
      </div>

      <!-- Empty state -->
      <div id="emptyState" class="text-center py-5" style="display:none!important;">
        <i class="fas fa-users fa-3x mb-3" style="color:var(--color-mid-gray);"></i>
        <h6 style="color:var(--color-gray);">No residents found</h6>
        <p class="text-muted small mb-0">Try adjusting your search or filters.</p>
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
     VIEW RESIDENT MODAL  (#viewResidentModal)
══════════════════════════════════════════════════════════ -->
<div class="modal fade" id="viewResidentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header" style="background:var(--color-primary); color:#fff;">
        <h5 class="modal-title">
          <i class="fas fa-user me-2"></i>Resident Details
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">

        <!-- Header strip -->
        <div class="view-user-header d-flex align-items-center gap-3 mb-4 p-3 rounded-2 flex-wrap" style="background:rgba(26,58,107,0.06);">
          <div class="view-user-avatar" id="vUserAvatar">A</div>
          <div>
            <h5 class="mb-0 fw-bold" id="vFullName" style="color:var(--color-primary);"></h5>
            <small class="text-muted">@<span id="vUsername"></span></small>
          </div>
          <div class="ms-auto">
            <span id="vStatusBadge" class="badge fs-6"></span>
          </div>
        </div>

        <!-- Fields grid -->
        <div class="row g-3">
          <div class="col-12 col-sm-6">
            <div class="view-field-label">Email Address</div>
            <div class="view-field-value mt-1" id="vEmail">—</div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="view-field-label">Contact Number</div>
            <div class="view-field-value mt-1" id="vContact">—</div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="view-field-label">Date of Birth</div>
            <div class="view-field-value mt-1" id="vDob">—</div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="view-field-label">Role</div>
            <div class="view-field-value mt-1">
              <span class="badge bg-primary" id="vRole">User</span>
            </div>
          </div>
          <div class="col-12">
            <div class="view-field-label">Home Address</div>
            <div class="view-field-value mt-1" id="vAddress">—</div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="view-field-label">Account Created</div>
            <div class="view-field-value mt-1" id="vCreatedAt">—</div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="view-field-label">Account Status</div>
            <div class="view-field-value mt-1" id="vStatusText">—</div>
          </div>
        </div>
      </div>
      <div class="modal-footer d-flex flex-wrap gap-2 justify-content-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-warning text-dark" id="btnViewToggle">
          <i class="fas fa-toggle-off me-1"></i>Toggle Status
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     CONFIRM TOGGLE STATUS MODAL  (#toggleStatusModal)
══════════════════════════════════════════════════════════ -->
<div class="modal fade" id="toggleStatusModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header" id="toggleModalHeader">
        <h5 class="modal-title" id="toggleModalTitle">
          <i class="fas fa-toggle-on me-2"></i>Confirm Status Change
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center py-4">
        <i class="fas fa-user-circle fa-2x mb-3" id="toggleModalIcon"></i>
        <p class="mb-1" id="toggleModalMessage">Are you sure you want to change this resident's status?</p>
        <p class="fw-bold mb-0" id="toggleTargetName"></p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-sm" id="confirmToggleBtn">Confirm</button>
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
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/auth.js"></script>
<script src="../assets/js/app.js"></script>

<script>
/* ============================================================
   RESIDENTS — Page Logic
============================================================ */

// ── State ──────────────────────────────────────────────────
let allResidents      = [];
let filteredResidents = [];
let currentPage       = 1;
let perPage           = 10;
let viewTargetId      = null;
let toggleTargetId    = null;

// ── Bootstrap modal instances ─────────────────────────────
let viewModalInst, toggleModalInst, logoutModalInst;

// ── Toast helper ──────────────────────────────────────────
function showToast(message, type = 'success') {
  const el = document.getElementById('odmisToast');
  el.className = 'toast align-items-center text-white border-0';
  const bg = { success:'bg-success', danger:'bg-danger', warning:'bg-warning text-dark', info:'bg-info' }[type] || 'bg-success';
  el.classList.add(...bg.split(' '));
  document.getElementById('toastMessage').textContent = message;
  bootstrap.Toast.getOrCreateInstance(el, { delay: 3000 }).show();
}

// ── HTML escaping ─────────────────────────────────────────
function escHtml(s) {
  if (!s) return '';
  return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ── Status badge ───────────────────────────────────────────
function statusBadge(status) {
  const s = (status || '').toLowerCase();
  if (s === 'active')   return '<span class="badge bg-success">Active</span>';
  if (s === 'inactive') return '<span class="badge bg-danger">Inactive</span>';
  return `<span class="badge bg-secondary">${escHtml(status)}</span>`;
}

// ── Load & render ─────────────────────────────────────────
async function loadResidents() {
  try {
    const res = await ApiClient.get('/residents/index.php');
    allResidents = Array.isArray(res.data) ? res.data : [];
  } catch (err) {
    showToast('Failed to load residents: ' + err.message, 'danger');
    allResidents = [];
  }
  filterResidents();
}

function filterResidents() {
  const search    = (document.getElementById('filterSearch').value || '').toLowerCase().trim();
  const statusVal = document.getElementById('filterStatus').value;

  filteredResidents = allResidents.filter(u => {
    const matchSearch = !search ||
      (u.full_name      && u.full_name.toLowerCase().includes(search)) ||
      (u.username       && u.username.toLowerCase().includes(search)) ||
      (u.email          && u.email.toLowerCase().includes(search)) ||
      (u.contact_number && u.contact_number.includes(search)) ||
      (u.address        && u.address.toLowerCase().includes(search));
    const matchStatus = !statusVal || (u.status || 'Active') === statusVal;
    return matchSearch && matchStatus;
  });

  currentPage = 1;
  renderTable();
}

function renderTable() {
  const tbody  = document.getElementById('residentsTableBody');
  const empty  = document.getElementById('emptyState');
  const pagBar = document.getElementById('paginationBar');
  const total  = filteredResidents.length;
  const start  = (currentPage - 1) * perPage;
  const slice  = filteredResidents.slice(start, start + perPage);

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

  tbody.innerHTML = slice.map((u, idx) => {
    const initLetter = u.full_name ? u.full_name[0].toUpperCase() : (u.username ? u.username[0].toUpperCase() : 'U');
    const userStatus = u.status || 'Active';
    const isActive   = userStatus.toLowerCase() === 'active';

    return `
      <tr>
        <td class="text-nowrap">${start + idx + 1}</td>
        <td>
          <div class="d-flex align-items-center gap-2">
            ${u.profile_picture ?
              `<img src="../${escHtml(u.profile_picture)}" alt="${escHtml(u.full_name || 'Resident')}" class="user-avatar-chip" style="object-fit:cover;padding:0;">` :
              `<div class="user-avatar-chip">${escHtml(initLetter)}</div>`
            }
            <span class="fw-semibold text-nowrap">${escHtml(u.full_name || '—')}</span>
          </div>
        </td>
        <td class="text-nowrap"><span class="font-monospace text-primary fw-semibold">@${escHtml(u.username)}</span></td>
        <td class="text-nowrap">${escHtml(u.email || '—')}</td>
        <td class="text-nowrap">${escHtml(u.contact_number || '—')}</td>
        <td class="text-nowrap">${escHtml(u.date_of_birth || '—')}</td>
        <td>
          <div style="min-width:140px; max-width:220px; white-space:normal; line-height:1.3; font-size:var(--font-size-xs);">
            ${escHtml(u.address || '—')}
          </div>
        </td>
        <td class="text-nowrap">${statusBadge(userStatus)}</td>
        <td class="text-center text-nowrap" style="white-space:nowrap;">
          <button class="btn-action btn-view me-1" title="View Details" onclick="viewResident(${u.id})">
            <i class="fas fa-eye"></i>
          </button>
          <button class="btn-action ${isActive ? 'btn-deactivate' : 'btn-activate'}"
                  title="${isActive ? 'Deactivate' : 'Activate'}"
                  onclick="promptToggleStatus(${u.id})">
            <i class="fas ${isActive ? 'fa-user-slash' : 'fa-user-check'}"></i>
          </button>
        </td>
      </tr>`;
  }).join('');

  renderPagination(total);
}

function renderPagination(total) {
  const totalPages = Math.ceil(total / perPage) || 1;
  document.getElementById('paginationInfo').textContent = `Page ${currentPage} of ${totalPages}`;
  const ctrl = document.getElementById('paginationControls');

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

// ── View resident ─────────────────────────────────────────
function viewResident(id) {
  const u = allResidents.find(r => Number(r.id) === Number(id));
  if (!u) return;
  viewTargetId = id;

  const initLetter = u.full_name ? u.full_name[0].toUpperCase() : (u.username ? u.username[0].toUpperCase() : 'U');
  const userStatus = u.status || 'Active';
  const isActive   = userStatus.toLowerCase() === 'active';

  const vAvatar = document.getElementById('vUserAvatar');
  if (u.profile_picture) {
    vAvatar.innerHTML = `<img src="../${escHtml(u.profile_picture)}" alt="${escHtml(u.full_name || 'Resident')}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;display:block;">`;
  } else {
    vAvatar.innerHTML = '';
    vAvatar.textContent = initLetter;
  }
  document.getElementById('vFullName').textContent     = u.full_name     || '—';
  document.getElementById('vUsername').textContent     = u.username      || '—';
  document.getElementById('vEmail').textContent        = u.email         || '—';
  document.getElementById('vContact').textContent      = u.contact_number || '—';
  document.getElementById('vDob').textContent          = u.date_of_birth || '—';
  document.getElementById('vAddress').textContent      = u.address       || '—';
  document.getElementById('vCreatedAt').textContent    = u.created_at    || '—';
  document.getElementById('vRole').textContent         = u.role ? (u.role.charAt(0).toUpperCase() + u.role.slice(1)) : 'User';
  document.getElementById('vStatusText').textContent   = userStatus;

  const badge = document.getElementById('vStatusBadge');
  badge.className  = `badge fs-6 ${isActive ? 'bg-success' : 'bg-danger'}`;
  badge.textContent = userStatus;

  const toggleBtn = document.getElementById('btnViewToggle');
  if (isActive) {
    toggleBtn.className = 'btn btn-warning text-dark';
    toggleBtn.innerHTML = '<i class="fas fa-user-slash me-1"></i>Deactivate';
  } else {
    toggleBtn.className = 'btn btn-success';
    toggleBtn.innerHTML = '<i class="fas fa-user-check me-1"></i>Activate';
  }

  viewModalInst.show();
}

// ── Toggle status ─────────────────────────────────────────
function promptToggleStatus(id) {
  const u = allResidents.find(r => Number(r.id) === Number(id));
  if (!u) return;
  toggleTargetId   = id;
  const userStatus = u.status || 'Active';
  const isActive   = userStatus.toLowerCase() === 'active';
  const action     = isActive ? 'Deactivate' : 'Activate';

  document.getElementById('toggleModalTitle').innerHTML =
    `<i class="fas fa-toggle-${isActive ? 'off' : 'on'} me-2"></i>Confirm ${action}`;
  document.getElementById('toggleModalHeader').className =
    `modal-header ${isActive ? 'bg-warning text-dark' : 'bg-success text-white'}`;
  document.getElementById('toggleModalIcon').className =
    `fas fa-user-${isActive ? 'slash' : 'check'} fa-2x mb-3 ${isActive ? 'text-warning' : 'text-success'}`;
  document.getElementById('toggleModalMessage').textContent =
    `Are you sure you want to ${action.toLowerCase()} this resident?`;
  document.getElementById('toggleTargetName').textContent = u.full_name || u.username;

  const confirmBtn = document.getElementById('confirmToggleBtn');
  confirmBtn.className = `btn btn-sm ${isActive ? 'btn-warning text-dark' : 'btn-success'}`;
  confirmBtn.textContent = action;

  toggleModalInst.show();
}

async function toggleStatus() {
  if (!toggleTargetId) return;
  const btn = document.getElementById('confirmToggleBtn');
  btn.disabled = true;
  try {
    await ApiClient.patch('/residents/toggle-status.php?id=' + toggleTargetId);
    toggleTargetId = null;
    toggleModalInst.hide();
    showToast('Resident status updated successfully.', 'success');
    await loadResidents();
  } catch (err) {
    showToast(err.message || 'Status update failed.', 'danger');
  } finally {
    btn.disabled = false;
  }
}

// ── DOMContentLoaded ──────────────────────────────────────
document.addEventListener('DOMContentLoaded', async () => {
  if (!Auth.requireAdmin()) return;

  // Initialize ODMIS application core (responsive mobile sidebar, top navbar, notifications, auth guard)
  if (typeof App !== 'undefined') {
    App.initPage({ page: 'residents', adminPage: true, requireAdmin: true });
  }

  // Modal instances
  viewModalInst   = new bootstrap.Modal(document.getElementById('viewResidentModal'));
  toggleModalInst = new bootstrap.Modal(document.getElementById('toggleStatusModal'));
  logoutModalInst = new bootstrap.Modal(document.getElementById('logoutModal'));

  // Confirm toggle status
  document.getElementById('confirmToggleBtn').addEventListener('click', toggleStatus);

  // "Toggle Status" from view modal
  document.getElementById('btnViewToggle').addEventListener('click', () => {
    const id = viewTargetId;
    viewModalInst.hide();
    document.getElementById('viewResidentModal').addEventListener('hidden.bs.modal', () => {
      if (id) promptToggleStatus(id);
    }, { once: true });
  });

  // Filters
  document.getElementById('filterSearch').addEventListener('input', filterResidents);
  document.getElementById('filterStatus').addEventListener('change', filterResidents);
  document.getElementById('btnResetFilters').addEventListener('click', () => {
    document.getElementById('filterSearch').value = '';
    document.getElementById('filterStatus').value = '';
    filterResidents();
  });

  // Per-page
  document.getElementById('perPageSelect').addEventListener('change', e => {
    perPage = parseInt(e.target.value, 10);
    currentPage = 1;
    renderTable();
  });

  // Expose handlers globally for HTML onclick attributes
  window.viewResident       = viewResident;
  window.promptToggleStatus = promptToggleStatus;
  window.toggleStatus       = toggleStatus;
  window.goPage             = goPage;

  // Initial load
  await loadResidents();
});
</script>
</body>
</html>
