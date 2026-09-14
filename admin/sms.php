<?php
// admin/sms.php
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
  <title>SMS Broadcast — ODMIS Admin</title>

  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <!-- ODMIS Styles -->
  <link rel="stylesheet" href="../assets/css/style.css" />

  <style>
    /* Composer card */
    .composer-card {
      background: #fff;
      border-radius: var(--card-border-radius, 8px);
      box-shadow: var(--card-shadow, 0 2px 10px rgba(0,0,0,0.05));
      padding: 1.5rem;
      margin-bottom: 1.5rem;
    }
    .composer-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.25rem;
      padding-bottom: 0.75rem;
      border-bottom: 1px solid #edf2f7;
    }
    .composer-title {
      font-size: 1.05rem;
      font-weight: 700;
      color: var(--color-primary, #467235);
      margin: 0;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    /* Character counter */
    .char-counter {
      font-size: 0.75rem;
      font-weight: 600;
      color: #6c757d;
      text-align: right;
      margin-top: 0.35rem;
    }

    /* Filter bar */
    .filter-bar {
      background: #fff;
      border-radius: var(--card-border-radius, 8px);
      box-shadow: var(--card-shadow, 0 2px 10px rgba(0,0,0,0.05));
      padding: 1.25rem;
      margin-bottom: 1.5rem;
      display: flex;
      flex-wrap: wrap;
      gap: 1.25rem;
      align-items: flex-end;
    }
    .filter-group {
      flex: 1 1 200px;
      min-width: 150px;
    }
    .filter-group.search-group {
      flex: 2 1 300px;
    }
    .filter-label {
      font-size: var(--font-size-xs, 0.75rem);
      font-weight: 700;
      color: var(--color-gray, #6c757d);
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 0.4rem;
      display: block;
    }
    .search-wrapper {
      position: relative;
    }
    .search-wrapper input {
      padding-left: 2.25rem;
    }
    .search-wrapper .search-icon {
      position: absolute;
      left: 0.85rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--color-gray);
      font-size: 0.85rem;
    }

    /* Badge overrides */
    .badge-status {
      padding: 0.28rem 0.65rem;
      font-size: 0.75rem;
      font-weight: 700;
      border-radius: 4px;
    }
    .badge-status-sent {
      background-color: #d4edda;
      color: #155724;
    }
    .badge-status-simulated {
      background-color: #d1ecf1;
      color: #0c5460;
    }
    .badge-status-failed {
      background-color: #f8d7da;
      color: #721c24;
    }

    /* Table action button */
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
      background: #e9ecef;
      color: #495057;
      transition: all 0.15s ease;
    }
    .btn-action:hover {
      background: var(--color-primary, #467235);
      color: #fff;
    }
  </style>
</head>
<body>

<!-- ═══════════════════════════════════════════════════
     SIDEBAR
═══════════════════════════════════════════════════ -->
<div id="sidebar">
  <div class="sidebar-logo-area">
    <img src="../img/odmis_logo.jpg" alt="ODMIS Logo" class="sidebar-logo-img">
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
        <a href="announcements.php" class="sidebar-nav-link" data-page="announcements">
          <i class="fas fa-bullhorn nav-icon"></i>
          <span class="nav-label">Announcements</span>
        </a>
      </li>
      <li class="sidebar-nav-item">
        <a href="sms.php" class="sidebar-nav-link active" data-page="sms">
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

<!-- ═══════════════════════════════════════════════════
     TOP NAVBAR
═══════════════════════════════════════════════════ -->
<nav id="topNavbar">
  <button class="navbar-hamburger" id="sidebarToggle" title="Toggle Sidebar"><i class="fas fa-bars"></i></button>
  <h1 class="navbar-page-title">SMS Broadcast & Management</h1>
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
        <li><a class="dropdown-item" href="settings.php"><i class="fas fa-cog me-2 text-muted"></i>Settings</a></li>
        <li><hr class="dropdown-divider my-1"></li>
        <li><a class="dropdown-item text-danger" href="javascript:void(0)" data-action="logout" onclick="event.preventDefault(); App.handleLogout(event);"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- ═══════════════════════════════════════════════════
     MAIN CONTENT
═══════════════════════════════════════════════════ -->
<main id="mainContent">

  <!-- ── SMS Broadcast Composer ── -->
  <div class="composer-card">
    <div class="composer-header">
      <h2 class="composer-title"><i class="fas fa-bullhorn text-warning"></i> Send Emergency DRRM SMS Broadcast</h2>
      <div class="d-flex align-items-center gap-2">
        <span class="badge bg-light text-dark border px-2 py-1"><i class="fas fa-wallet text-success me-1"></i> Balance: <strong id="balancePill">Loading...</strong></span>
        <button class="btn btn-sm btn-outline-secondary" onclick="loadBalance()" title="Refresh Gateway Balance"><i class="fas fa-sync-alt"></i></button>
      </div>
    </div>

    <form id="smsBroadcastForm" novalidate>
      <div class="row g-3">
        <!-- Target Selector -->
        <div class="col-12 col-md-6" id="targetWrapper">
          <label class="form-label fw-bold small text-muted">TARGET AUDIENCE</label>
          <select class="form-select" id="broadcastTarget" onchange="handleTargetChange()">
            <option value="all" selected>All Registered Residents (MDRRMO All)</option>
            <option value="barangay">Specific Barangay Residents</option>
          </select>
          <div class="form-text small" id="targetHelpText">Broadcasts to all registered residents in Santo Niño.</div>
        </div>

        <!-- Barangay Dropdown (Conditional) -->
        <div class="col-12 col-md-4" id="barangayWrapper" style="display:none;">
          <label class="form-label fw-bold small text-muted">SELECT BARANGAY</label>
          <select class="form-select" id="broadcastBarangay" onchange="updateRecipientPreview()">
            <option value="">-- Choose Barangay --</option>
          </select>
        </div>

        <!-- Template Selector -->
        <div class="col-12 col-md-6" id="templateWrapper">
          <label class="form-label fw-bold small text-muted">QUICK DRRM TEMPLATES</label>
          <select class="form-select" id="templateSelect" onchange="applyTemplate()">
            <option value="">-- Choose Pre-defined Template --</option>
            <option value="typhoon">Typhoon Warning Advisory</option>
            <option value="flood_evac">Flood & Pre-emptive Evacuation Notice</option>
            <option value="earthquake">Earthquake Preparedness Advisory</option>
            <option value="relief">Relief Aid Distribution Notice</option>
            <option value="weather">Heavy Rainfall & River Monitoring</option>
          </select>
        </div>

        <!-- Recipient Counter Badge -->
        <div class="col-12">
          <div class="p-2 px-3 bg-light border rounded-2 d-flex align-items-center justify-content-between">
            <span class="small text-muted"><i class="fas fa-users me-1 text-primary"></i> Target Reach: <strong id="recipientCountPreview" class="text-dark">Calculating...</strong></span>
            <span class="badge bg-success-subtle text-success border border-success-subtle" id="senderIdBadge">Sender ID: PhilSMS</span>
          </div>
        </div>

        <!-- Message Body -->
        <div class="col-12">
          <label class="form-label fw-bold small text-muted">MESSAGE CONTENT</label>
          <textarea class="form-control" id="smsMessage" rows="4" placeholder="Type your emergency advisory or notification here..." oninput="updateCharCount()" required></textarea>
          <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted">SMS standard: 160 characters per message segment.</small>
            <div class="char-counter" id="charCounter">0 / 160 chars (1 SMS segment)</div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="col-12 text-end">
          <button type="button" class="btn btn-light me-2" onclick="resetComposer()"><i class="fas fa-times me-1"></i> Clear</button>
          <button type="submit" class="btn btn-primary px-4 fw-bold" id="btnSendBroadcast">
            <i class="fas fa-paper-plane me-2"></i> Send Broadcast
          </button>
        </div>
      </div>
    </form>
  </div>

  <!-- ── 3. SMS Transmission History ── -->
  <div class="filter-bar">
    <div class="filter-group search-group">
      <label class="filter-label">Search SMS Logs</label>
      <div class="search-wrapper">
        <i class="fas fa-search search-icon"></i>
        <input type="text" class="form-control" id="searchLog" placeholder="Search by recipient phone, name, or message..." oninput="debounceSearch()" />
      </div>
    </div>

    <div class="filter-group">
      <label class="filter-label">Status</label>
      <select class="form-select" id="filterStatus" onchange="loadLogs()">
        <option value="">All Statuses</option>
        <option value="Sent">Sent</option>
        <option value="Simulated">Simulated</option>
        <option value="Failed">Failed</option>
      </select>
    </div>

    <div class="filter-group">
      <label class="filter-label">Barangay</label>
      <select class="form-select" id="filterBarangay" onchange="loadLogs()">
        <option value="">All Barangays</option>
      </select>
    </div>

    <div class="filter-group" style="flex:0 0 auto;">
      <button class="btn btn-outline-secondary" onclick="resetFilters()"><i class="fas fa-undo me-1"></i> Reset</button>
    </div>
  </div>

  <!-- Table Card -->
  <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="smsTable">
          <thead class="table-light">
            <tr>
              <th class="ps-4">Date & Time</th>
              <th>Recipient</th>
              <th>Barangay</th>
              <th>Message</th>
              <th>Status</th>
              <th>Provider / Reference</th>
              <th>Dispatched By</th>
              <th class="text-center pe-4">Action</th>
            </tr>
          </thead>
          <tbody id="smsTableBody">
            <tr>
              <td colspan="8" class="text-center py-5 text-muted">
                <i class="fas fa-spinner fa-spin fa-2x mb-2 text-primary d-block"></i>
                Loading SMS transmission logs...
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <!-- Card Footer / Pagination -->
    <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3 px-4">
      <small class="text-muted" id="tablePaginationInfo">Showing 0 of 0 records</small>
      <nav>
        <ul class="pagination pagination-sm mb-0" id="pagination"></ul>
      </nav>
    </div>
  </div>

</main>

<!-- ═══════════════════════════════════════════════════
     DISPATCH LOADING SCREEN OVERLAY (PAGE FREEZE)
═══════════════════════════════════════════════════ -->
<div id="smsLoadingOverlay" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(15, 23, 42, 0.85); backdrop-filter:blur(6px); -webkit-backdrop-filter:blur(6px); z-index:99999; flex-direction:column; align-items:center; justify-content:center; padding:1.5rem;">
  <div style="background:#fff; color:#1e293b; border-radius:18px; padding:2.5rem 2rem; max-width:480px; width:92%; box-shadow:0 25px 50px -12px rgba(0,0,0,0.45); text-align:center;">
    
    <!-- Animated Transmission Icon -->
    <div style="width:84px; height:84px; border-radius:50%; background:#e8f5e9; color:#2e7d32; display:flex; align-items:center; justify-content:center; margin:0 auto 1.5rem auto; font-size:2.4rem; position:relative;">
      <i class="fas fa-tower-broadcast fa-fade"></i>
      <span class="spinner-grow spinner-grow-sm position-absolute top-0 end-0 text-success" style="width:1.3rem; height:1.3rem;"></span>
    </div>

    <h4 style="font-weight:800; color:#1e293b; margin-bottom:0.4rem;">Dispatching SMS Broadcast</h4>
    <p class="text-muted small mb-3" id="loadingOverlayStatus">Transmitting emergency advisory via PhilSMS Gateway...</p>

    <!-- Animated Progress Bar -->
    <div class="progress mb-3" style="height:7px; background:#e2e8f0; border-radius:4px; overflow:hidden;">
      <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width:100%;"></div>
    </div>

    <!-- Critical Freeze Warning -->
    <div class="alert alert-warning py-2 px-3 mb-0 text-start border-warning" style="font-size:0.82rem; border-radius:8px;">
      <div class="d-flex align-items-start gap-2">
        <i class="fas fa-triangle-exclamation text-warning fs-6 mt-1 flex-shrink-0"></i>
        <div>
          <strong>Please DO NOT refresh, navigate back, or close this page.</strong>
          <div class="text-muted mt-1" style="font-size:0.75rem;">Messages are being delivered to residents' phones. The screen will automatically close when transmission is verified.</div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ═══════════════════════════════════════════════════
     CONFIRMATION BROADCAST MODAL
═══════════════════════════════════════════════════ -->
<div class="modal fade" id="confirmBroadcastModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title fw-bold"><i class="fas fa-exclamation-triangle me-2"></i> Confirm SMS Dispatch</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <p class="mb-3">Are you sure you want to broadcast this SMS message?</p>
        <div class="alert alert-light border small mb-3">
          <div class="mb-1"><strong>Target:</strong> <span id="confirmTargetText">All Residents</span></div>
          <div class="mb-1"><strong>Estimated Recipients:</strong> <span id="confirmCountText">0</span></div>
          <div><strong>Message Preview:</strong></div>
          <div class="p-2 bg-white rounded border mt-1 text-muted fst-italic" id="confirmMessageText"></div>
        </div>
        <div class="small text-danger"><i class="fas fa-info-circle me-1"></i> This action will deduct credits from your PhilSMS balance.</div>
      </div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary fw-bold" id="btnConfirmSend" onclick="executeBroadcast()">
          <i class="fas fa-paper-plane me-1"></i> Yes, Dispatch SMS
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ═══════════════════════════════════════════════════
     VIEW LOG DETAILS MODAL
═══════════════════════════════════════════════════ -->
<div class="modal fade" id="viewLogModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="fas fa-envelope-open-text me-2"></i> SMS Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <div class="mb-3">
          <label class="fw-bold small text-muted">RECIPIENT</label>
          <div id="modalLogRecipient" class="fs-6 fw-semibold text-dark"></div>
        </div>
        <div class="row g-2 mb-3">
          <div class="col-6">
            <label class="fw-bold small text-muted">BARANGAY</label>
            <div id="modalLogBarangay"></div>
          </div>
          <div class="col-6">
            <label class="fw-bold small text-muted">DATE & TIME</label>
            <div id="modalLogDate"></div>
          </div>
        </div>
        <div class="row g-2 mb-3">
          <div class="col-6">
            <label class="fw-bold small text-muted">DELIVERY STATUS</label>
            <div id="modalLogStatus"></div>
          </div>
          <div class="col-6">
            <label class="fw-bold small text-muted">REFERENCE ID</label>
            <div id="modalLogRef" class="font-monospace small"></div>
          </div>
        </div>
        <div class="mb-3">
          <label class="fw-bold small text-muted">FULL MESSAGE</label>
          <div class="p-3 bg-light rounded border text-break" id="modalLogMessage"></div>
        </div>
        <div id="modalLogErrorWrapper" style="display:none;">
          <label class="fw-bold small text-danger">ERROR MESSAGE</label>
          <div class="p-2 bg-danger-subtle text-danger rounded border border-danger-subtle small" id="modalLogError"></div>
        </div>
      </div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

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

<!-- Toast Container -->
<div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/auth.js"></script>
<script src="../assets/js/app.js"></script>

<script>
  'use strict';

  // 31 Official Barangays of Sto. Niño (Faire), Cagayan
  const BARANGAYS = [
    'Abariongan Ruar', 'Abariongan Uneg', 'Balagan', 'Balanni', 'Cabayo',
    'Calapangan', 'Calassitan', 'Campo', 'Centro Norte', 'Centro Sur',
    'Dungao', 'Lattac', 'Lipatan', 'Lubo', 'Mabitbitnong',
    'Masical', 'Matalao', 'Nag-uma', 'Namuccayan', 'Niug Norte',
    'Niug Sur', 'Palusao', 'Poblacion', 'San Manuel', 'San Roque',
    'Santa Felicitas', 'Santa Maria', 'Sidiran', 'Tabang', 'Tamucco', 'Virginia'
  ];

  // DRRM Templates
  const TEMPLATES = {
    typhoon: "[MDRRMO ALERT] Santo Niño is under Tropical Cyclone Wind Signal. Residents in low-lying and flood-prone areas are advised to secure dwellings and take precautionary measures.",
    flood_evac: "[MDRRMO EVACUATION NOTICE] Flood waters are rising rapidly. Residents in {Barangay} are strongly urged to preemptively evacuate to the designated Evacuation Center immediately.",
    earthquake: "[MDRRMO ADVISORY] An earthquake occurred. Check for structural hazards and watch for aftershocks. Duck, Cover, and Hold. Report casualties or damages to MDRRMO hotline.",
    relief: "[MDRRMO RELIEF NOTICE] Relief aid distribution is scheduled today at the Barangay Hall for affected households. Please coordinate with barangay officials and bring a valid ID.",
    weather: "[MDRRMO WEATHER BULLETIN] Heavy to intense rainfall warning is currently in effect. Flashfloods and landslides are possible. Monitor official updates."
  };

  let currentPage = 1;
  const pageLimit = 20;
  let searchTimer = null;
  let cachedLogs = [];
  let confirmModal = null;
  let viewModal = null;

  document.addEventListener('DOMContentLoaded', async () => {
    Auth.requireAdmin();
    App.initNavbar();

    confirmModal = new bootstrap.Modal(document.getElementById('confirmBroadcastModal'));
    viewModal = new bootstrap.Modal(document.getElementById('viewLogModal'));

    populateBarangayDropdowns();
    await loadBalance();
    await updateRecipientPreview();
    await loadLogs();

    document.getElementById('smsBroadcastForm').addEventListener('submit', (e) => {
      e.preventDefault();
      openConfirmModal();
    });
  });

  function populateBarangayDropdowns() {
    const broadcastSelect = document.getElementById('broadcastBarangay');
    const filterSelect = document.getElementById('filterBarangay');

    BARANGAYS.forEach(b => {
      const opt1 = document.createElement('option');
      opt1.value = b;
      opt1.textContent = b;
      broadcastSelect.appendChild(opt1);

      const opt2 = document.createElement('option');
      opt2.value = b;
      opt2.textContent = b;
      filterSelect.appendChild(opt2);
    });
  }

  async function loadBalance() {
    const pill = document.getElementById('balancePill');
    if (pill) pill.textContent = 'Checking...';
    try {
      const res = await ApiClient.get('/sms/balance.php');
      if (res.success && res.data) {
        if (pill) pill.textContent = res.data.remaining_balance || '₱0';
        const senderBadge = document.getElementById('senderIdBadge');
        if (senderBadge) {
          senderBadge.textContent = 'Sender ID: ' + (res.data.sender_id || 'PhilSMS');
        }
      }
    } catch (err) {
      if (pill) pill.textContent = 'Unavailable';
      console.error('Balance error:', err);
    }
  }

  async function updateRecipientPreview() {
    const target = document.getElementById('broadcastTarget').value;
    const barangay = document.getElementById('broadcastBarangay').value;
    const previewEl = document.getElementById('recipientCountPreview');

    try {
      let path = '/sms/broadcast.php?preview_count=1&target=' + encodeURIComponent(target);
      if (target === 'barangay' && barangay) {
        path += '&barangay=' + encodeURIComponent(barangay);
      }
      const res = await ApiClient.get(path);
      const count = res.data ? res.data.count : 0;
      previewEl.textContent = count + ' Resident' + (count === 1 ? '' : 's');
    } catch (err) {
      previewEl.textContent = 'Unknown';
    }
  }

  function handleTargetChange() {
    const target = document.getElementById('broadcastTarget').value;
    const bWrapper = document.getElementById('barangayWrapper');
    const tWrapper = document.getElementById('targetWrapper');
    const tmplWrapper = document.getElementById('templateWrapper');
    const helpText = document.getElementById('targetHelpText');

    if (target === 'all') {
      bWrapper.style.display = 'none';
      if (tWrapper) tWrapper.className = 'col-12 col-md-6';
      if (tmplWrapper) tmplWrapper.className = 'col-12 col-md-6';
      helpText.textContent = 'Broadcasts to all registered residents in Santo Niño.';
    } else if (target === 'barangay') {
      bWrapper.style.display = 'block';
      if (tWrapper) tWrapper.className = 'col-12 col-md-4';
      if (tmplWrapper) tmplWrapper.className = 'col-12 col-md-4';
      helpText.textContent = 'Filter by resident address in the selected barangay.';
    }
    updateRecipientPreview();
  }

  function applyTemplate() {
    const tKey = document.getElementById('templateSelect').value;
    if (!tKey || !TEMPLATES[tKey]) return;

    let text = TEMPLATES[tKey];
    const target = document.getElementById('broadcastTarget').value;
    const bName = document.getElementById('broadcastBarangay').value;

    if (text.includes('{Barangay}')) {
      text = text.replace('{Barangay}', (target === 'barangay' && bName) ? bName : 'your area');
    }

    document.getElementById('smsMessage').value = text;
    updateCharCount();
  }

  function updateCharCount() {
    const msg = document.getElementById('smsMessage').value;
    const len = msg.length;
    const segments = Math.ceil(len / 160) || 1;
    document.getElementById('charCounter').textContent = `${len} / 160 chars (${segments} SMS segment${segments > 1 ? 's' : ''})`;
  }

  function resetComposer() {
    document.getElementById('smsBroadcastForm').reset();
    document.getElementById('templateSelect').value = '';
    handleTargetChange();
    updateCharCount();
  }

  function openConfirmModal() {
    const msg = document.getElementById('smsMessage').value.trim();
    if (!msg) {
      showToast('Please enter a message before broadcasting.', 'error');
      return;
    }

    const target = document.getElementById('broadcastTarget').value;
    const barangay = document.getElementById('broadcastBarangay').value;

    if (target === 'barangay' && !barangay) {
      showToast('Please select a target barangay.', 'error');
      return;
    }

    let targetDesc = 'All Registered Residents';
    if (target === 'barangay') targetDesc = 'Residents in ' + barangay;

    document.getElementById('confirmTargetText').textContent = targetDesc;
    document.getElementById('confirmCountText').textContent = document.getElementById('recipientCountPreview').textContent;
    document.getElementById('confirmMessageText').textContent = msg;

    confirmModal.show();
  }

  function preventUnload(e) {
    e.preventDefault();
    e.returnValue = 'An SMS broadcast is currently in progress. If you leave or reload, transmission may be incomplete.';
    return e.returnValue;
  }

  function showLoadingScreen(statusText) {
    const overlay = document.getElementById('smsLoadingOverlay');
    if (statusText) {
      document.getElementById('loadingOverlayStatus').textContent = statusText;
    }
    overlay.style.display = 'flex';
    window.addEventListener('beforeunload', preventUnload);
  }

  function hideLoadingScreen() {
    const overlay = document.getElementById('smsLoadingOverlay');
    overlay.style.display = 'none';
    window.removeEventListener('beforeunload', preventUnload);
  }

  async function executeBroadcast() {
    const btn = document.getElementById('btnConfirmSend');
    btn.disabled = true;

    const target = document.getElementById('broadcastTarget').value;
    const barangay = document.getElementById('broadcastBarangay').value;
    const message = document.getElementById('smsMessage').value.trim();

    // 1. Hide confirmation modal and display full-screen blocking overlay
    confirmModal.hide();

    let targetDesc = 'all registered residents';
    if (target === 'barangay') targetDesc = 'residents in ' + barangay;

    showLoadingScreen(`Transmitting SMS to ${targetDesc}... Please wait.`);

    try {
      const res = await ApiClient.post('/sms/broadcast.php', {
        target: target,
        barangay: barangay,
        message: message
      });

      showToast(res.message || 'SMS broadcast completed successfully!', 'success');
      resetComposer();
      await loadBalance();
      await loadLogs();
    } catch (err) {
      showToast(err.message || 'Failed to dispatch SMS broadcast.', 'error');
    } finally {
      hideLoadingScreen();
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i> Yes, Dispatch SMS';
    }
  }

  async function loadLogs() {
    const tbody = document.getElementById('smsTableBody');
    const search = document.getElementById('searchLog').value.trim();
    const status = document.getElementById('filterStatus').value;
    const barangay = document.getElementById('filterBarangay').value;

    let path = `/sms/history.php?page=${currentPage}&limit=${pageLimit}`;
    if (search) path += '&search=' + encodeURIComponent(search);
    if (status) path += '&status=' + encodeURIComponent(status);
    if (barangay) path += '&barangay=' + encodeURIComponent(barangay);

    try {
      const res = await ApiClient.get(path);
      const data = res.data;
      cachedLogs = data.logs || [];

      if (cachedLogs.length === 0) {
        tbody.innerHTML = `
          <tr>
            <td colspan="8" class="text-center py-5 text-muted">
              <i class="fas fa-inbox fa-2x mb-2 d-block opacity-50"></i>
              No SMS records found matching current criteria.
            </td>
          </tr>
        `;
        renderPagination(0, 1, pageLimit);
        return;
      }

      tbody.innerHTML = cachedLogs.map((log, index) => {
        let badgeClass = 'badge-status-sent';
        if (log.status === 'Simulated') badgeClass = 'badge-status-simulated';
        if (log.status === 'Failed') badgeClass = 'badge-status-failed';

        const recipientDisplay = log.recipient_name 
          ? `<strong>${escapeHtml(log.recipient_name)}</strong><br><small class="text-muted font-monospace">${escapeHtml(log.recipient_phone)}</small>`
          : `<span class="font-monospace">${escapeHtml(log.recipient_phone)}</span>`;

        const msgSnippet = escapeHtml(log.message.length > 55 ? log.message.substring(0, 55) + '...' : log.message);
        const refId = log.provider_message_id ? escapeHtml(log.provider_message_id) : '-';

        return `
          <tr>
            <td class="ps-4 small text-muted">${formatDate(log.created_at)}</td>
            <td>${recipientDisplay}</td>
            <td><span class="badge bg-light text-dark border">${escapeHtml(log.barangay || 'General')}</span></td>
            <td><span class="small text-dark">${msgSnippet}</span></td>
            <td><span class="badge-status ${badgeClass}">${escapeHtml(log.status)}</span></td>
            <td class="small text-muted font-monospace">${escapeHtml(log.provider)} <small class="text-muted">(${refId})</small></td>
            <td class="small text-muted">${escapeHtml(log.sent_by_name || 'System')}</td>
            <td class="text-center pe-4">
              <button class="btn-action" title="View Full Details" onclick="viewLogDetail(${index})">
                <i class="fas fa-eye"></i>
              </button>
            </td>
          </tr>
        `;
      }).join('');

      renderPagination(data.total, data.page, data.limit);
    } catch (err) {
      tbody.innerHTML = `
        <tr>
          <td colspan="8" class="text-center py-4 text-danger">
            <i class="fas fa-exclamation-circle me-1"></i> Failed to load SMS logs.
          </td>
        </tr>
      `;
    }
  }

  function viewLogDetail(index) {
    const log = cachedLogs[index];
    if (!log) return;

    document.getElementById('modalLogRecipient').textContent = log.recipient_name ? `${log.recipient_name} (${log.recipient_phone})` : log.recipient_phone;
    document.getElementById('modalLogBarangay').textContent = log.barangay || 'N/A';
    document.getElementById('modalLogDate').textContent = formatDate(log.created_at);
    document.getElementById('modalLogStatus').innerHTML = `<span class="badge ${log.status === 'Sent' ? 'bg-success' : (log.status === 'Simulated' ? 'bg-info' : 'bg-danger')}">${log.status}</span>`;
    document.getElementById('modalLogRef').textContent = log.provider_message_id || 'N/A';
    document.getElementById('modalLogMessage').textContent = log.message;

    const errWrap = document.getElementById('modalLogErrorWrapper');
    if (log.error_message) {
      errWrap.style.display = 'block';
      document.getElementById('modalLogError').textContent = log.error_message;
    } else {
      errWrap.style.display = 'none';
    }

    viewModal.show();
  }

  function renderPagination(total, page, limit) {
    const totalPages = Math.ceil(total / limit) || 1;
    document.getElementById('tablePaginationInfo').textContent = `Showing ${(page - 1) * limit + (total ? 1 : 0)} - ${Math.min(page * limit, total)} of ${total} records`;

    const ul = document.getElementById('pagination');
    ul.innerHTML = '';

    if (totalPages <= 1) return;

    const prevLi = document.createElement('li');
    prevLi.className = `page-item ${page === 1 ? 'disabled' : ''}`;
    prevLi.innerHTML = `<a class="page-link" href="#" onclick="changePage(${page - 1}); return false;">&laquo;</a>`;
    ul.appendChild(prevLi);

    for (let i = 1; i <= totalPages; i++) {
      if (i === 1 || i === totalPages || (i >= page - 2 && i <= page + 2)) {
        const li = document.createElement('li');
        li.className = `page-item ${i === page ? 'active' : ''}`;
        li.innerHTML = `<a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>`;
        ul.appendChild(li);
      }
    }

    const nextLi = document.createElement('li');
    nextLi.className = `page-item ${page === totalPages ? 'disabled' : ''}`;
    nextLi.innerHTML = `<a class="page-link" href="#" onclick="changePage(${page + 1}); return false;">&raquo;</a>`;
    ul.appendChild(nextLi);
  }

  function changePage(p) {
    currentPage = p;
    loadLogs();
  }

  function debounceSearch() {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
      currentPage = 1;
      loadLogs();
    }, 350);
  }

  function resetFilters() {
    document.getElementById('searchLog').value = '';
    document.getElementById('filterStatus').value = '';
    document.getElementById('filterBarangay').value = '';
    currentPage = 1;
    loadLogs();
  }

  function formatDate(str) {
    if (!str) return '-';
    const d = new Date(str);
    return d.toLocaleString('en-US', {
      month: 'short', day: 'numeric', year: 'numeric',
      hour: '2-digit', minute: '2-digit'
    });
  }

  function escapeHtml(str) {
    if (!str) return '';
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  function showToast(msg, type = 'info') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type === 'error' ? 'danger' : (type === 'success' ? 'success' : 'primary')} border-0 show mb-2`;
    toast.role = 'alert';
    toast.innerHTML = `
      <div class="d-flex">
        <div class="toast-body small">${escapeHtml(msg)}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    `;
    container.appendChild(toast);
    setTimeout(() => {
      toast.remove();
    }, 4500);
  }
</script>

</body>
</html>
