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
  <title>Relief Operations — ODMIS Resident</title>
  <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../assets/vendor/fontawesome/css/all.min.css" />
  <link rel="stylesheet" href="../assets/css/style.css" />
  <style>
    .relief-card {
      border: 1px solid rgba(0,0,0,0.08);
      border-radius: var(--card-border-radius);
      box-shadow: var(--card-shadow);
      background: #fff;
      transition: transform var(--transition-fast), box-shadow var(--transition-fast);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      height: 100%;
    }
    .relief-card:hover {
      transform: translateY(-3px);
      box-shadow: var(--card-shadow-hover);
    }
    .relief-card-header {
      padding: 0.9rem 1.2rem;
      border-bottom: 1px solid #f0f0f0;
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #fafbfa;
    }
    .relief-card-body {
      padding: 1.2rem;
      flex: 1;
    }
    .relief-card-footer {
      padding: 0.8rem 1.2rem;
      background: #fbfcfb;
      border-top: 1px solid #f0f0f0;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .info-tag {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      font-size: 0.8rem;
      color: #555;
      margin-bottom: 0.4rem;
    }
    .barangay-notice {
      background: linear-gradient(135deg, rgba(70,114,53,0.08) 0%, rgba(255,191,0,0.08) 100%);
      border: 1px solid rgba(70,114,53,0.2);
      border-radius: var(--card-border-radius);
      padding: 0.9rem 1.25rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 0.75rem;
    }
    .stat-mini-card {
      background: #fff;
      border-radius: var(--card-border-radius);
      box-shadow: var(--card-shadow);
      padding: 1rem 1.25rem;
      display: flex;
      align-items: center;
      gap: 1rem;
      border-left: 4px solid var(--color-primary);
    }
    .stat-mini-icon {
      width: 44px;
      height: 44px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
      flex-shrink: 0;
    }
    .filter-tabs .nav-link {
      font-size: 0.82rem;
      padding: 0.4rem 0.85rem;
      cursor: pointer;
    }
  </style>
</head>
<body>

<!-- ═══════════════════════════════════════
     SIDEBAR
════════════════════════════════════════ -->
<div id="sidebar" class="sidebar">
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
          <i class="fas fa-tachometer-alt nav-icon"></i><span class="nav-label">Dashboard</span>
        </a>
      </li>
      <li class="sidebar-nav-item">
        <a href="report-incident.php" class="sidebar-nav-link" data-page="report-incident">
          <i class="fas fa-plus-circle nav-icon"></i><span class="nav-label">Report Incident</span>
        </a>
      </li>
      <li class="sidebar-nav-item">
        <a href="alerts.php" class="sidebar-nav-link" data-page="alerts">
          <i class="fas fa-bell nav-icon"></i><span class="nav-label">Disaster Alerts</span>
        </a>
      </li>
      <li class="sidebar-nav-item">
        <a href="evacuation-centers.php" class="sidebar-nav-link" data-page="evacuation-centers">
          <i class="fas fa-house-damage nav-icon"></i><span class="nav-label">Evacuation Centers</span>
        </a>
      </li>
      <li class="sidebar-nav-item">
        <a href="relief.php" class="sidebar-nav-link active" data-page="relief">
          <i class="fas fa-box-open nav-icon"></i><span class="nav-label">Relief Operations</span>
        </a>
      </li>
      <li class="sidebar-nav-item">
        <a href="profile.php" class="sidebar-nav-link" data-page="profile">
          <i class="fas fa-user nav-icon"></i><span class="nav-label">Profile</span>
        </a>
      </li>
    </ul>
  </nav>

  <div class="sidebar-footer">
    <div class="sidebar-user-avatar" id="sidebarAvatar">U</div>
    <div class="sidebar-user-info">
      <span class="sidebar-user-name" id="sidebarName">User</span>
      <span class="sidebar-user-role">Resident</span>
    </div>
  </div>
</div>

<div id="sidebarOverlay"></div>

<!-- ═══════════════════════════════════════
     TOP NAVBAR
════════════════════════════════════════ -->
<nav id="topNavbar">
  <button class="navbar-hamburger" id="sidebarToggle" title="Toggle Sidebar">
    <i class="fas fa-bars"></i>
  </button>
  <h1 class="navbar-page-title">Relief Operations</h1>
  <div class="navbar-right">
    <button class="navbar-icon-btn position-relative" title="Notifications" onclick="App.showNotificationModal()">
      <i class="fas fa-bell"></i>
      <span class="notification-count" id="notifBadge">0</span>
    </button>
    <div class="navbar-divider"></div>
    <div class="dropdown">
      <div class="navbar-user dropdown-toggle" data-bs-toggle="dropdown" role="button">
        <div class="navbar-avatar" id="navAvatar">U</div>
        <span class="navbar-user-name" id="navUsername">User</span>
        <i class="fas fa-chevron-down ms-1" style="font-size:.65rem;color:var(--color-gray)"></i>
      </div>
      <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="min-width:180px;font-size:var(--font-size-sm)">
        <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2 text-muted"></i>Profile</a></li>
        <li><hr class="dropdown-divider my-1"></li>
        <li><a class="dropdown-item text-danger" href="javascript:void(0)" data-action="logout" onclick="event.preventDefault(); App.handleLogout(event);"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- ═══════════════════════════════════════
     MAIN CONTENT
════════════════════════════════════════ -->
<main id="mainContent">
  <div class="page-header">
    <div>
      <h1 class="mb-1"><i class="fas fa-box-open me-2" style="color:var(--color-accent)"></i>Relief Operations</h1>
      <p class="page-subtitle mb-0">Official relief assistance distribution batches and schedules in your community</p>
    </div>
  </div>

  <!-- Barangay Location Notice -->
  <div class="barangay-notice mb-4">
    <div class="d-flex align-items-center gap-2">
      <div class="text-success fs-5"><i class="fas fa-map-marker-alt"></i></div>
      <div>
        <span class="text-muted small d-block">Your Registered Community / Barangay:</span>
        <strong class="text-dark" id="residentBarangayDisplay">Checking profile…</strong>
      </div>
    </div>
    <div class="small text-muted">
      <i class="fas fa-shield-alt text-primary me-1"></i>Showing operations dispatched exclusively to your barangay
    </div>
  </div>

  <!-- Summary Cards -->
  <div class="row g-3 mb-4">
    <div class="col-sm-4">
      <div class="stat-mini-card" style="border-left-color: var(--color-primary);">
        <div class="stat-mini-icon" style="background: rgba(70,114,53,0.1); color: var(--color-primary);">
          <i class="fas fa-boxes"></i>
        </div>
        <div>
          <div class="text-muted small">Total Batches</div>
          <div class="fs-4 fw-bold text-dark" id="statTotal">0</div>
        </div>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="stat-mini-card" style="border-left-color: #0d6efd;">
        <div class="stat-mini-icon" style="background: rgba(13,110,253,0.1); color: #0d6efd;">
          <i class="fas fa-truck-moving"></i>
        </div>
        <div>
          <div class="text-muted small">In Progress / Ongoing</div>
          <div class="fs-4 fw-bold text-dark" id="statInProgress">0</div>
        </div>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="stat-mini-card" style="border-left-color: #198754;">
        <div class="stat-mini-icon" style="background: rgba(25,135,84,0.1); color: #198754;">
          <i class="fas fa-check-double"></i>
        </div>
        <div>
          <div class="text-muted small">Completed Deliveries</div>
          <div class="fs-4 fw-bold text-dark" id="statCompleted">0</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Filter & Search Bar -->
  <div class="card shadow-sm mb-4">
    <div class="card-body py-3">
      <div class="row g-3 align-items-center">
        <div class="col-md-7">
          <ul class="nav nav-pills filter-tabs flex-wrap gap-1" id="statusFilter">
            <li class="nav-item"><a class="nav-link active" href="#" data-status="all">All Batches</a></li>
            <li class="nav-item"><a class="nav-link" href="#" data-status="In Progress"><i class="fas fa-spinner me-1"></i>In Progress</a></li>
            <li class="nav-item"><a class="nav-link" href="#" data-status="Pending"><i class="fas fa-clock me-1"></i>Pending</a></li>
            <li class="nav-item"><a class="nav-link" href="#" data-status="Completed"><i class="fas fa-check me-1"></i>Completed</a></li>
          </ul>
        </div>
        <div class="col-md-5">
          <div class="input-group input-group-sm">
            <span class="input-group-text"><i class="fas fa-search text-muted"></i></span>
            <input type="text" class="form-control" id="searchRelief" placeholder="Search by batch, relief type, distributor..." />
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Relief Operations Cards Grid -->
  <div class="row g-3 mb-4" id="reliefGrid">
    <div class="col-12 text-center py-5 text-muted">
      <span class="spinner-border spinner-border-sm me-2 text-primary"></span>Loading relief operations for your community…
    </div>
  </div>
</main>

<!-- Details Modal -->
<div class="modal fade" id="viewReliefModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-light">
        <h5 class="modal-title fw-bold" id="modalBatchTitle"><i class="fas fa-box-open text-primary me-2"></i>Relief Batch Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="modalReliefBody">
        <!-- Rendered via JS -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Confirm Logout</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body text-center py-4"><p class="mb-0">Are you sure you want to logout?</p></div>
      <div class="modal-footer">
        <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-danger btn-sm" id="confirmLogoutBtn">Logout</button>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/auth.js"></script>
<script src="../assets/js/app.js"></script>
<script>
document.addEventListener('DOMContentLoaded', async function () {
  if (!Auth.requireUser()) return;

  const session = Auth.getSession();
  if (session) {
    const initial = (session.fullName || session.username || 'U')[0].toUpperCase();
    const sa = document.getElementById('sidebarAvatar');
    const sn = document.getElementById('sidebarName');
    const na = document.getElementById('navAvatar');
    const nu = document.getElementById('navUsername');
    if (sa) sa.textContent = initial;
    if (sn) sn.textContent = session.fullName || session.username;
    if (na) na.textContent = initial;
    if (nu) nu.textContent = session.fullName || session.username;
  }

  App.initSidebar();
  document.getElementById('confirmLogoutBtn').addEventListener('click', () => Auth.logout());
  document.querySelectorAll('[data-action="logout"]').forEach(el => {
    el.addEventListener('click', e => { e.preventDefault(); new bootstrap.Modal(document.getElementById('logoutModal')).show(); });
  });

  const officialBarangays = [
    'Abariongan Ruar', 'Abariongan Uneg', 'Balagan', 'Balanni', 'Cabayo',
    'Calapangan', 'Calassitan', 'Campo', 'Centro Norte', 'Centro Sur',
    'Dungao', 'Lattac', 'Lipatan', 'Lubo', 'Mabitbitnong',
    'Masical', 'Matalao', 'Nag-uma', 'Namuccayan', 'Niug Norte',
    'Niug Sur', 'Palusao', 'Poblacion', 'San Manuel', 'San Roque',
    'Santa Felicitas', 'Santa Maria', 'Sidiran', 'Tabang', 'Tamucco', 'Virginia'
  ];

  let residentBarangay = '';
  let allRelief = [];
  let currentStatus = 'all';
  let currentSearch = '';

  // 1. Resolve resident barangay from profile
  try {
    const meRes = await ApiClient.get('/auth/me.php');
    if (meRes.success && meRes.data) {
      const addr = meRes.data.address || '';
      for (const b of officialBarangays) {
        if (addr.toLowerCase().includes(b.toLowerCase())) {
          residentBarangay = b;
          break;
        }
      }
    }
  } catch (err) {
    console.warn('Could not fetch user profile for barangay name:', err);
  }

  const barangayDisplay = document.getElementById('residentBarangayDisplay');
  if (residentBarangay) {
    barangayDisplay.innerHTML = `<span class="badge bg-success-subtle text-success fs-6 border border-success-subtle"><i class="fas fa-check-circle me-1"></i>Barangay ${residentBarangay}, Santo Niño</span>`;
  } else {
    barangayDisplay.innerHTML = `<span class="badge bg-warning-subtle text-warning-emphasis fs-6 border border-warning-subtle"><i class="fas fa-exclamation-triangle me-1"></i>Not specified in profile. <a href="profile.php" class="text-decoration-underline text-warning-emphasis ms-1">Update Address</a></span>`;
  }

  // 2. Fetch relief operations (API automatically filters to resident's barangay)
  try {
    const [rRes, aRes] = await Promise.all([
      ApiClient.get('/relief/index.php'),
      ApiClient.get('/alerts/index.php')
    ]);
    allRelief = Array.isArray(rRes.data) ? rRes.data : [];
    const activeAlerts = (Array.isArray(aRes.data) ? aRes.data : []).filter(a => a.status !== 'Resolved');
    document.getElementById('notifBadge').textContent = activeAlerts.length;
  } catch (err) {
    console.error('Relief load error:', err.message);
    document.getElementById('reliefGrid').innerHTML = `
      <div class="col-12 text-center py-5 text-danger">
        <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
        <p>Failed to load relief operations. Please refresh the page.</p>
      </div>`;
    return;
  }

  // 3. Update summary metrics
  const inProgressCount = allRelief.filter(r => r.status === 'In Progress').length;
  const completedCount  = allRelief.filter(r => r.status === 'Completed').length;
  document.getElementById('statTotal').textContent      = allRelief.length;
  document.getElementById('statInProgress').textContent = inProgressCount;
  document.getElementById('statCompleted').textContent  = completedCount;

  // 4. Status helpers
  function statusBadge(status) {
    const s = (status || '').toLowerCase();
    if (s === 'completed') return '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Completed</span>';
    if (s === 'in progress') return '<span class="badge bg-primary"><i class="fas fa-spinner fa-spin me-1"></i>In Progress</span>';
    return '<span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Pending</span>';
  }

  function reliefIcon(type) {
    const t = (type || '').toLowerCase();
    if (t.includes('food')) return 'fa-utensils';
    if (t.includes('medical') || t.includes('medicine')) return 'fa-medkit';
    if (t.includes('hygiene') || t.includes('kit')) return 'fa-soap';
    if (t.includes('cash') || t.includes('financial')) return 'fa-hand-holding-usd';
    if (t.includes('water')) return 'fa-tint';
    return 'fa-box-open';
  }

  // 5. Render operations cards
  function renderRelief() {
    let filtered = allRelief.filter(r => {
      if (currentStatus !== 'all' && (r.status || '') !== currentStatus) return false;
      if (currentSearch) {
        const q = currentSearch.toLowerCase();
        const batch = (r.batch_number || '').toLowerCase();
        const type  = (r.relief_type || '').toLowerCase();
        const dist  = (r.distributed_by || '').toLowerCase();
        const notes = (r.notes || '').toLowerCase();
        if (!batch.includes(q) && !type.includes(q) && !dist.includes(q) && !notes.includes(q)) return false;
      }
      return true;
    });

    const grid = document.getElementById('reliefGrid');
    if (filtered.length === 0) {
      grid.innerHTML = `
        <div class="col-12 text-center py-5">
          <div class="text-muted mb-2"><i class="fas fa-box-open fa-3x" style="opacity:0.35;"></i></div>
          <h6 class="text-muted fw-bold">No Relief Operations Found</h6>
          <p class="text-muted small mb-0">There are currently no relief distribution batches matching your criteria in ${residentBarangay ? 'Barangay ' + residentBarangay : 'your community'}.</p>
        </div>`;
      return;
    }

    grid.innerHTML = filtered.map(r => `
      <div class="col-lg-4 col-md-6">
        <div class="relief-card">
          <div class="relief-card-header">
            <div>
              <span class="badge bg-dark-subtle text-dark border px-2 py-1">${r.batch_number || 'BATCH'}</span>
            </div>
            <div>${statusBadge(r.status)}</div>
          </div>
          <div class="relief-card-body">
            <h5 class="fw-bold mb-2 text-dark d-flex align-items-center gap-2">
              <i class="fas ${reliefIcon(r.relief_type)} text-primary"></i>
              <span>${r.relief_type || 'Relief Goods'}</span>
            </h5>
            <div class="info-tag text-muted mb-2">
              <i class="fas fa-calendar-alt text-secondary"></i>
              <span>Schedule: <strong>${r.operation_date || 'TBA'}</strong></span>
            </div>
            <div class="info-tag text-muted mb-2">
              <i class="fas fa-cube text-secondary"></i>
              <span>Quantity: <strong>${r.quantity || 0} ${r.unit || 'units'}</strong></span>
            </div>
            <div class="info-tag text-muted mb-3">
              <i class="fas fa-user-shield text-secondary"></i>
              <span>In-charge: <strong>${r.distributed_by || 'LGU / DRRMO'}</strong></span>
            </div>
            ${r.notes ? `<p class="small text-muted mb-0 bg-light p-2 rounded"><i class="fas fa-info-circle me-1 text-primary"></i>${r.notes.substring(0, 100)}${r.notes.length > 100 ? '…' : ''}</p>` : ''}
          </div>
          <div class="relief-card-footer">
            <span class="small text-muted"><i class="fas fa-map-marker-alt text-danger me-1"></i>${r.barangay}</span>
            <button class="btn btn-sm btn-outline-primary" onclick="viewDetails(${r.id})">
              <i class="fas fa-eye me-1"></i>View Details
            </button>
          </div>
        </div>
      </div>
    `).join('');
  }

  // 6. View Details Handler
  window.viewDetails = function(id) {
    const r = allRelief.find(item => item.id == id);
    if (!r) return;

    document.getElementById('modalBatchTitle').innerHTML = `<i class="fas fa-box-open text-primary me-2"></i>Batch ${r.batch_number}`;
    document.getElementById('modalReliefBody').innerHTML = `
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-primary">${r.relief_type || 'Relief Goods'}</h5>
        ${statusBadge(r.status)}
      </div>
      <table class="table table-sm table-bordered">
        <tbody>
          <tr><th class="bg-light" style="width:40%;">Batch Number</th><td><code>${r.batch_number}</code></td></tr>
          <tr><th class="bg-light">Target Barangay</th><td><i class="fas fa-map-marker-alt text-danger me-1"></i>${r.barangay}</td></tr>
          <tr><th class="bg-light">Distribution Date</th><td><i class="fas fa-calendar-alt text-primary me-1"></i>${r.operation_date || '—'}</td></tr>
          <tr><th class="bg-light">Allocation / Quantity</th><td><strong>${r.quantity || 0} ${r.unit || 'units'}</strong></td></tr>
          <tr><th class="bg-light">Dispatched By</th><td>${r.distributed_by || 'LGU Sto. Niño / DRRMO'}</td></tr>
          <tr><th class="bg-light">Instructions / Remarks</th><td>${r.notes ? r.notes : '<em class="text-muted">None specified. Please coordinate with your Barangay Council.</em>'}</td></tr>
        </tbody>
      </table>
      <div class="alert alert-secondary small mb-0 py-2">
        <i class="fas fa-info-circle me-1 text-primary"></i><strong>Resident Notice:</strong> For questions about claiming this batch, please proceed to your Barangay Hall or present your Resident ID during scheduled hours.
      </div>
    `;
    new bootstrap.Modal(document.getElementById('viewReliefModal')).show();
  };

  // 7. Event listeners
  document.getElementById('statusFilter').addEventListener('click', function(e) {
    const link = e.target.closest('a[data-status]');
    if (!link) return;
    e.preventDefault();
    document.querySelectorAll('#statusFilter .nav-link').forEach(l => l.classList.remove('active'));
    link.classList.add('active');
    currentStatus = link.dataset.status;
    renderRelief();
  });

  document.getElementById('searchRelief').addEventListener('input', function(e) {
    currentSearch = e.target.value.trim();
    renderRelief();
  });

  renderRelief();
});
</script>
</body>
</html>
