<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Evacuation Centers — ODMIS User</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="../assets/css/style.css" />
  <style>
    .evac-card { border: none; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,.09); transition: transform .15s; height: 100%; }
    .evac-card:hover { transform: translateY(-3px); box-shadow: 0 5px 18px rgba(0,0,0,.14); }
    .evac-card .card-header { border-radius: 10px 10px 0 0; padding: 1rem 1.25rem .85rem; }
    .usage-low    { background-color: #198754; }
    .usage-medium { background-color: #ffc107; }
    .usage-high   { background-color: #dc3545; }
    .summary-pill { display: inline-flex; flex-direction: column; align-items: center; background: #fff; border-radius: 8px; box-shadow: 0 1px 6px rgba(0,0,0,.1); padding: .75rem 1.5rem; min-width: 120px; }
    .summary-pill .pill-value { font-size: 1.5rem; font-weight: 700; line-height: 1; }
    .summary-pill .pill-label { font-size: .75rem; color: #666; margin-top: 3px; }
  </style>
</head>
<body>

<!-- ═══════════════════════════════════════
     SIDEBAR
════════════════════════════════════════ -->
<div id="sidebar" class="sidebar">
  <div class="sidebar-logo-area">
    <div class="sidebar-logo-placeholder"><i class="fas fa-shield-alt"></i></div>
    <div class="sidebar-brand-text"><span class="brand-title">ODMIS</span><span class="brand-subtitle">Disaster Management</span></div>
  </div>
  <nav class="sidebar-nav">
    <ul class="list-unstyled mb-0">
      <li class="sidebar-nav-item"><a href="dashboard.php" class="sidebar-nav-link" data-page="dashboard"><i class="fas fa-tachometer-alt nav-icon"></i><span class="nav-label">Dashboard</span></a></li>
      <li class="sidebar-nav-item"><a href="report-incident.php" class="sidebar-nav-link" data-page="report-incident"><i class="fas fa-plus-circle nav-icon"></i><span class="nav-label">Report Incident</span></a></li>
      <li class="sidebar-nav-item"><a href="alerts.php" class="sidebar-nav-link" data-page="alerts"><i class="fas fa-bell nav-icon"></i><span class="nav-label">Disaster Alerts</span></a></li>
      <li class="sidebar-nav-item"><a href="evacuation-centers.php" class="sidebar-nav-link active" data-page="evacuation-centers"><i class="fas fa-house-damage nav-icon"></i><span class="nav-label">Evacuation Centers</span></a></li>
      <li class="sidebar-nav-item"><a href="announcements.php" class="sidebar-nav-link" data-page="announcements"><i class="fas fa-bullhorn nav-icon"></i><span class="nav-label">Announcements</span></a></li>
      <li class="sidebar-nav-item"><a href="profile.php" class="sidebar-nav-link" data-page="profile"><i class="fas fa-user nav-icon"></i><span class="nav-label">Profile</span></a></li>
    </ul>
  </nav>
  <div class="sidebar-footer">
    <div class="sidebar-user-avatar" id="sidebarAvatar">U</div>
    <div class="sidebar-user-info"><span class="sidebar-user-name" id="sidebarName">User</span><span class="sidebar-user-role">Resident</span></div>
  </div>
</div>
<div id="sidebarOverlay"></div>

<!-- ═══════════════════════════════════════
     TOP NAVBAR
════════════════════════════════════════ -->
<nav id="topNavbar">
  <button class="navbar-hamburger" id="sidebarToggle"><i class="fas fa-bars"></i></button>
  <h1 class="navbar-page-title">Evacuation Centers</h1>
  <div class="navbar-right">
    <button class="navbar-icon-btn position-relative" title="Notifications">
      <i class="fas fa-bell"></i><span class="notification-count" id="notifBadge">0</span>
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
        <li><a class="dropdown-item text-danger" href="#" data-action="logout"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
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
      <h1 class="mb-1"><i class="fas fa-house-damage me-2" style="color:var(--color-accent)"></i>Evacuation Centers</h1>
      <p class="page-subtitle mb-0">Find the nearest evacuation center and check available capacity</p>
    </div>
  </div>

  <!-- Summary -->
  <div class="d-flex flex-wrap gap-3 mb-4">
    <div class="summary-pill">
      <span class="pill-value text-primary" id="sumTotal">0</span>
      <span class="pill-label">Total Centers</span>
    </div>
    <div class="summary-pill">
      <span class="pill-value text-info" id="sumCapacity">0</span>
      <span class="pill-label">Total Capacity</span>
    </div>
    <div class="summary-pill">
      <span class="pill-value text-success" id="sumAvailable">0</span>
      <span class="pill-label">Available Slots</span>
    </div>
  </div>

  <!-- Search -->
  <div class="card shadow-sm mb-4">
    <div class="card-body py-3">
      <div class="input-group">
        <span class="input-group-text"><i class="fas fa-search"></i></span>
        <input type="text" class="form-control" id="searchInput" placeholder="Search by name, location, or barangay..." />
        <button class="btn btn-outline-secondary" id="clearSearch"><i class="fas fa-times"></i></button>
      </div>
    </div>
  </div>

  <!-- Cards Grid -->
  <div class="row g-3" id="evacGrid">
    <div class="col-12 text-center py-5 text-muted"><i class="fas fa-spinner fa-spin me-2"></i>Loading evacuation centers...</div>
  </div>
</main>

<!-- ═══════════════════════════════════════
     VIEW DETAILS MODAL
════════════════════════════════════════ -->
<div class="modal fade" id="viewCenterModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="fas fa-house-damage me-2"></i>Evacuation Center Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4" id="viewCenterBody"></div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- ═══════════════════════════════════════
     LOGOUT MODAL
════════════════════════════════════════ -->
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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
    if (nu) nu.textContent = session.username;
  }

  document.getElementById('sidebarToggle').addEventListener('click', () => document.body.classList.toggle('sidebar-collapsed'));
  document.getElementById('confirmLogoutBtn').addEventListener('click', () => Auth.logout());
  document.querySelectorAll('[data-action="logout"]').forEach(el => {
    el.addEventListener('click', e => { e.preventDefault(); new bootstrap.Modal(document.getElementById('logoutModal')).show(); });
  });

  let allCenters = [];
  try {
    const [evacRes, alertRes] = await Promise.all([
      ApiClient.get('/evacuation/index.php'),
      ApiClient.get('/alerts/index.php')
    ]);
    allCenters = Array.isArray(evacRes.data) ? evacRes.data : [];
    const activeAlerts = (Array.isArray(alertRes.data) ? alertRes.data : []).filter(a => a.status !== 'Resolved');
    document.getElementById('notifBadge').textContent = activeAlerts.length;
  } catch (err) { console.error('Evacuation centers load error:', err.message); }

  // Summary
  const totalCap = allCenters.reduce((s, c) => s + (c.capacity || 0), 0);
  const totalOcc = allCenters.reduce((s, c) => s + (c.occupied_slots || 0), 0);
  document.getElementById('sumTotal').textContent     = allCenters.length;
  document.getElementById('sumCapacity').textContent  = totalCap;
  document.getElementById('sumAvailable').textContent = Math.max(0, totalCap - totalOcc);

  // ── Helpers ────────────────────────────────────────────────
  function usageBarClass(pct) {
    if (pct >= 85) return 'usage-high';
    if (pct >= 50) return 'usage-medium';
    return 'usage-low';
  }
  function statusBadge(status) {
    return (status || '').toLowerCase() === 'open'
      ? '<span class="badge bg-success">Open</span>'
      : '<span class="badge bg-secondary">Closed</span>';
  }

  // ── Render ─────────────────────────────────────────────────
  function renderCenters(centers) {
    const grid = document.getElementById('evacGrid');
    if (centers.length === 0) {
      grid.innerHTML = '<div class="col-12 text-center py-5 text-muted"><i class="fas fa-search me-2"></i>No evacuation centers found.</div>';
      return;
    }
    grid.innerHTML = centers.map(c => {
      const pct       = c.capacity > 0 ? Math.round((c.occupied_slots / c.capacity) * 100) : 0;
      const available = Math.max(0, (c.capacity || 0) - (c.occupied_slots || 0));
      return `
        <div class="col-xl-4 col-md-6">
          <div class="card evac-card">
            <div class="card-header bg-primary text-white">
              <h6 class="mb-0 fw-bold"><i class="fas fa-building me-2"></i>${c.center_name}</h6>
              <small class="opacity-75">${c.barangay || ''}</small>
            </div>
            <div class="card-body p-3">
              <div class="mb-2 d-flex align-items-center gap-2 flex-wrap">
                ${statusBadge(c.status)}
                <span class="badge bg-info text-dark">Capacity: ${c.capacity}</span>
              </div>
              <p class="mb-2 text-muted" style="font-size:.82rem"><i class="fas fa-map-marker-alt me-1"></i>${c.location || ''}</p>
              <div class="mb-1 d-flex justify-content-between" style="font-size:.8rem">
                <span>Occupancy</span>
                <span>${c.occupied_slots} / ${c.capacity} (${pct}%)</span>
              </div>
              <div class="progress mb-3" style="height:8px">
                <div class="progress-bar ${usageBarClass(pct)}" style="width:${pct}%"></div>
              </div>
              <div class="d-flex justify-content-between align-items-center">
                <span class="fw-semibold text-${available > 0 ? 'success' : 'danger'}" style="font-size:.875rem">
                  <i class="fas fa-${available > 0 ? 'check-circle' : 'times-circle'} me-1"></i>${available} slots available
                </span>
              </div>
              <hr class="my-2">
              <div style="font-size:.8rem;color:#555">
                <div><i class="fas fa-user me-1 text-muted"></i>${c.contact_person || '—'}</div>
                <div><i class="fas fa-phone me-1 text-muted"></i>${c.contact_number || '—'}</div>
              </div>
            </div>
            <div class="card-footer bg-transparent text-end py-2 px-3">
              <button class="btn btn-primary btn-sm" onclick="viewCenter(${c.id})"><i class="fas fa-eye me-1"></i>View Details</button>
            </div>
          </div>
        </div>`;
    }).join('');
  }

  // ── View Center Modal ──────────────────────────────────────
  window.viewCenter = function (id) {
    const c = allCenters.find(x => x.id === id);
    if (!c) return;
    const pct       = c.capacity > 0 ? Math.round((c.occupied_slots / c.capacity) * 100) : 0;
    const available = Math.max(0, (c.capacity || 0) - (c.occupied_slots || 0));
    document.getElementById('viewCenterBody').innerHTML = `
      <div class="row g-3">
        <div class="col-12"><h5 class="fw-bold">${c.center_name}</h5></div>
        <div class="col-md-6"><label class="fw-semibold text-muted small">Status</label><div>${statusBadge(c.status)}</div></div>
        <div class="col-md-6"><label class="fw-semibold text-muted small">Barangay</label><div>${c.barangay}</div></div>
        <div class="col-12"><label class="fw-semibold text-muted small">Full Address</label><div>${c.location}</div></div>
        <div class="col-md-4"><label class="fw-semibold text-muted small">Total Capacity</label><div class="fw-bold fs-5 text-primary">${c.capacity}</div></div>
        <div class="col-md-4"><label class="fw-semibold text-muted small">Currently Occupied</label><div class="fw-bold fs-5 text-warning">${c.occupied_slots}</div></div>
        <div class="col-md-4"><label class="fw-semibold text-muted small">Available Slots</label><div class="fw-bold fs-5 ${available > 0 ? 'text-success' : 'text-danger'}">${available}</div></div>
        <div class="col-12">
          <label class="fw-semibold text-muted small">Occupancy (${pct}%)</label>
          <div class="progress mt-1" style="height:12px">
            <div class="progress-bar ${usageBarClass(pct)}" style="width:${pct}%"></div>
          </div>
        </div>
        <div class="col-md-6"><label class="fw-semibold text-muted small">Contact Person</label><div>${c.contact_person || '—'}</div></div>
        <div class="col-md-6"><label class="fw-semibold text-muted small">Contact Number</label><div>${c.contact_number || '—'}</div></div>
        <div class="col-md-6"><label class="fw-semibold text-muted small">Center Code</label><div><span class="badge bg-light text-dark">${c.center_code}</span></div></div>
      </div>`;
    new bootstrap.Modal(document.getElementById('viewCenterModal')).show();
  };

  // ── Search ─────────────────────────────────────────────────
  function doSearch() {
    const q = document.getElementById('searchInput').value.trim().toLowerCase();
    const filtered = q
      ? allCenters.filter(c =>
          (c.center_name || '').toLowerCase().includes(q) ||
          (c.location    || '').toLowerCase().includes(q) ||
          (c.barangay    || '').toLowerCase().includes(q))
      : allCenters;
    renderCenters(filtered);
  }

  document.getElementById('searchInput').addEventListener('input', doSearch);
  document.getElementById('clearSearch').addEventListener('click', () => {
    document.getElementById('searchInput').value = '';
    renderCenters(allCenters);
  });

  renderCenters(allCenters);
});
</script>
</body>
</html>
