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
  <title>Dashboard — ODMIS User</title>
  <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../assets/vendor/fontawesome/css/all.min.css" />
  <link rel="stylesheet" href="../assets/css/style.css" />
  <style>
    .alert-border-card { border-left: 5px solid #ccc; border-radius: 6px; background: #fff; padding: 1rem; margin-bottom: 0.75rem; box-shadow: 0 1px 4px rgba(0,0,0,.07); }
    .alert-border-critical { border-left-color: #dc3545; }
    .alert-border-high     { border-left-color: #fd7e14; }
    .alert-border-moderate,.alert-border-warning { border-left-color: #ffc107; }
    .alert-border-low,.alert-border-safe { border-left-color: #198754; }
    .emergency-contact-list li { display: flex; justify-content: space-between; align-items: center; padding: .45rem 0; border-bottom: 1px solid #f0f0f0; font-size: .875rem; }
    .emergency-contact-list li:last-child { border-bottom: none; }
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
        <a href="dashboard.php" class="sidebar-nav-link active" data-page="dashboard">
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
        <a href="relief.php" class="sidebar-nav-link" data-page="relief">
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
  <h1 class="navbar-page-title">Dashboard</h1>
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
      <h1 class="mb-1"><i class="fas fa-tachometer-alt me-2" style="color:var(--color-accent)"></i>Dashboard</h1>
      <p class="page-subtitle mb-0"><i class="fas fa-calendar-alt me-1"></i>Welcome back, <span id="welcomeName">User</span></p>
    </div>
  </div>

  <!-- ROW 1 — Stat Cards -->
  <div class="row g-3 mb-4">
    <div class="col-lg-3 col-sm-6">
      <div class="stat-card stat-primary">
        <div class="stat-card-icon"><i class="fas fa-file-alt"></i></div>
        <div class="stat-card-body">
          <div class="stat-card-value" id="statMyReports">0</div>
          <div class="stat-card-label">My Reports</div>
          <div class="stat-card-badge flat"><i class="fas fa-user me-1"></i>Submitted by you</div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-sm-6">
      <div class="stat-card stat-danger">
        <div class="stat-card-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="stat-card-body">
          <div class="stat-card-value" id="statActiveAlerts">0</div>
          <div class="stat-card-label">Active Alerts</div>
          <div class="stat-card-badge up"><i class="fas fa-circle-notch fa-spin me-1"></i>Live</div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-sm-6">
      <div class="stat-card stat-success">
        <div class="stat-card-icon"><i class="fas fa-house-damage"></i></div>
        <div class="stat-card-body">
          <div class="stat-card-value" id="statEvacCenters">0</div>
          <div class="stat-card-label">Evacuation Centers</div>
          <div class="stat-card-badge flat"><i class="fas fa-map-marker-alt me-1"></i>Available</div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-sm-6">
      <div class="stat-card stat-warning">
        <div class="stat-card-icon"><i class="fas fa-box-open"></i></div>
        <div class="stat-card-body">
          <div class="stat-card-value" id="statReliefOps">0</div>
          <div class="stat-card-label">Relief Operations</div>
          <div class="stat-card-badge flat"><i class="fas fa-map-marker-alt me-1"></i>In your barangay</div>
        </div>
      </div>
    </div>
  </div>

  <!-- ROW 2 — Alerts + Emergency Contacts -->
  <div class="row g-3 mb-4">
    <!-- Active Disaster Alerts -->
    <div class="col-lg-8">
      <div class="card h-100 shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between py-3">
          <h6 class="mb-0 fw-bold"><i class="fas fa-exclamation-triangle text-danger me-2"></i>Active Disaster Alerts</h6>
          <a href="alerts.php" class="btn btn-outline-primary btn-sm">View All Alerts</a>
        </div>
        <div class="card-body" id="alertsList">
          <div class="text-center text-muted py-3"><i class="fas fa-spinner fa-spin me-2"></i>Loading alerts...</div>
        </div>
      </div>
    </div>

    <!-- Emergency Contacts -->
    <div class="col-lg-4">
      <div class="card h-100 shadow-sm">
        <div class="card-header py-3">
          <h6 class="mb-0 fw-bold"><i class="fas fa-phone-alt text-success me-2"></i>Emergency Contacts</h6>
        </div>
        <div class="card-body p-3">
          <ul class="list-unstyled mb-0 emergency-contact-list">
            <li><span><i class="fas fa-shield-alt text-primary me-2"></i>NDRRMC Hotline</span><strong>911</strong></li>
            <li><span><i class="fas fa-fire text-danger me-2"></i>BFP Emergency</span><strong>160</strong></li>
            <li><span><i class="fas fa-user-shield text-info me-2"></i>PNP</span><strong>117</strong></li>
            <li><span><i class="fas fa-plus-square text-danger me-2"></i>Red Cross</span><strong>(02) 527-0000</strong></li>
            <li><span><i class="fas fa-building text-secondary me-2"></i>Local DRRMO</span><strong>(078) 888-0000</strong></li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- ROW 3 — Official DRRM Announcements & Public Advisories -->
  <div class="row g-3 mb-4" id="announcementsSection">
    <div class="col-12">
      <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between py-3 flex-wrap gap-2">
          <div class="d-flex align-items-center gap-2">
            <h6 class="mb-0 fw-bold"><i class="fas fa-bullhorn me-2" style="color:var(--color-accent);"></i>Public Advisories & Announcements</h6>
            <span class="badge" style="background:var(--color-primary); color:#fff;" id="announcementBadgeCount">0</span>
          </div>
          <small class="text-muted"><i class="fas fa-shield-alt me-1" style="color:var(--color-primary);"></i>Official MDRRMO Santo Niño Advisories</small>
        </div>
        <div class="card-body p-3">
          <div id="announcementsList">
            <div class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin me-2" style="color:var(--color-primary);"></i>Loading public advisories...</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- ═══════════════════════════════════════
     ANNOUNCEMENT DETAILS MODAL
════════════════════════════════════════ -->
<div class="modal fade" id="viewAnnouncementModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background:var(--color-primary); color:#fff;">
        <h5 class="modal-title fw-bold" id="modalAnnTitle" style="color:#fff;"><i class="fas fa-bullhorn me-2" style="color:var(--color-accent);"></i>Announcement Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4" id="modalAnnBody">
        <!-- Rendered via JS -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
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
    const wn = document.getElementById('welcomeName');
    if (sa) sa.textContent = initial;
    if (sn) sn.textContent = session.fullName || session.username;
    if (na) na.textContent = initial;
    if (nu) nu.textContent = session.fullName || session.username;
    if (wn) wn.textContent = session.fullName || session.username;
  }

  App.initSidebar();
  document.getElementById('confirmLogoutBtn').addEventListener('click', () => Auth.logout());
  document.querySelectorAll('[data-action="logout"]').forEach(el => {
    el.addEventListener('click', e => { e.preventDefault(); new bootstrap.Modal(document.getElementById('logoutModal')).show(); });
  });

  // ── Load data ──────────────────────────────────────────────
  let alerts = [], evacuationCtrs = [], myReports = [], reliefOps = [], announcements = [];
  try {
    const [aRes, evacRes, repRes, relRes, annRes] = await Promise.all([
      ApiClient.get('/alerts/index.php'),
      ApiClient.get('/evacuation/index.php'),
      ApiClient.get('/user-reports/index.php'),
      ApiClient.get('/relief/index.php'),
      ApiClient.get('/announcements/index.php')
    ]);
    alerts        = Array.isArray(aRes.data)   ? aRes.data   : [];
    evacuationCtrs= Array.isArray(evacRes.data)? evacRes.data: [];
    myReports     = Array.isArray(repRes.data) ? repRes.data : [];
    reliefOps     = Array.isArray(relRes.data) ? relRes.data : [];
    announcements = Array.isArray(annRes.data) ? annRes.data : [];
  } catch (err) { console.error('Dashboard load error:', err.message); }

  // ── Stat Cards ─────────────────────────────────────────────
  const activeAlerts = alerts.filter(a => (a.severity || '').toLowerCase() !== 'safe' && a.status !== 'Resolved');
  document.getElementById('statMyReports').textContent    = myReports.length;
  document.getElementById('statActiveAlerts').textContent = activeAlerts.length;
  document.getElementById('statEvacCenters').textContent  = evacuationCtrs.length;
  document.getElementById('statReliefOps').textContent    = reliefOps.length;
  document.getElementById('notifBadge').textContent       = activeAlerts.length;

  // ── Severity helpers ───────────────────────────────────────
  function severityBorderClass(sev) {
    const s = (sev || '').toLowerCase();
    if (s === 'critical') return 'alert-border-critical';
    if (s === 'high')     return 'alert-border-high';
    if (s === 'moderate' || s === 'warning') return 'alert-border-moderate';
    return 'alert-border-safe';
  }
  function severityBadgeClass(sev) {
    const s = (sev || '').toLowerCase();
    if (s === 'critical') return 'bg-danger';
    if (s === 'high' || s === 'moderate') return 'bg-warning text-dark';
    if (s === 'low') return 'bg-secondary';
    return 'bg-success';
  }
  function alertTypeIcon(type) {
    const t = (type || '').toLowerCase();
    if (t === 'typhoon')    return 'fa-wind';
    if (t === 'flood')      return 'fa-water';
    if (t === 'earthquake') return 'fa-mountain';
    if (t === 'fire')       return 'fa-fire';
    if (t === 'landslide')  return 'fa-mountain';
    return 'fa-exclamation-triangle';
  }

  // ── Alerts section ─────────────────────────────────────────
  const alertsEl = document.getElementById('alertsList');
  const displayAlerts = alerts.filter(a => a.status !== 'Resolved').slice(0, 4);
  if (displayAlerts.length === 0) {
    alertsEl.innerHTML = '<div class="text-center text-success py-3"><i class="fas fa-check-circle me-2"></i>No active alerts at this time.</div>';
  } else {
    alertsEl.innerHTML = displayAlerts.map(a => `
      <div class="alert-border-card ${severityBorderClass(a.severity)}">
        <div class="d-flex align-items-start justify-content-between gap-2">
          <div class="d-flex align-items-center gap-2 mb-1">
            <i class="fas ${alertTypeIcon(a.alert_type)} text-secondary"></i>
            <strong style="font-size:.9rem">${a.title || 'Alert'}</strong>
          </div>
          <span class="badge ${severityBadgeClass(a.severity)} flex-shrink-0">${a.severity || ''}</span>
        </div>
        <p class="mb-1 text-muted" style="font-size:.82rem">${(a.description || '').substring(0, 120)}${(a.description || '').length > 120 ? '...' : ''}</p>
        <div class="d-flex flex-wrap gap-3" style="font-size:.78rem;color:#666">
          <span><i class="fas fa-map-marker-alt me-1"></i>${a.affected_areas || '—'}</span>
          <span><i class="fas fa-clock me-1"></i>${a.issued_at || a.created_at || ''}</span>
        </div>
      </div>`).join('');
  }

  // ── Announcements section ──────────────────────────────────
  const annEl = document.getElementById('announcementsList');
  const annBadge = document.getElementById('announcementBadgeCount');
  if (annBadge) annBadge.textContent = announcements.length;

  function annCategoryBadge(cat) {
    const c = (cat || '').toLowerCase();
    if (c.includes('evacuat')) return `<span class="badge" style="background:var(--color-danger); color:#fff;"><i class="fas fa-running me-1"></i>${cat}</span>`;
    if (c.includes('weather')) return `<span class="badge" style="background:#0284c7; color:#fff;"><i class="fas fa-cloud-sun me-1"></i>${cat}</span>`;
    if (c.includes('advisory')) return `<span class="badge" style="background:rgba(255,191,0,0.18); color:var(--color-accent-dark); border:1px solid rgba(255,191,0,0.3);"><i class="fas fa-exclamation-triangle me-1"></i>${cat}</span>`;
    if (c.includes('prepared')) return `<span class="badge" style="background:rgba(70,114,53,0.12); color:var(--color-primary); border:1px solid rgba(70,114,53,0.25);"><i class="fas fa-shield-alt me-1"></i>${cat}</span>`;
    return `<span class="badge bg-light text-dark border"><i class="fas fa-bullhorn me-1 text-muted"></i>${cat || 'General'}</span>`;
  }

  function escHtml(str) {
    if (!str) return '';
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }

  if (announcements.length === 0) {
    annEl.innerHTML = `
      <div class="text-center text-muted py-4">
        <i class="fas fa-bullhorn fa-2x mb-2" style="opacity:0.35;"></i>
        <p class="mb-0 small">No public advisories or announcements at this time.</p>
      </div>`;
  } else {
    annEl.innerHTML = `
      <div class="row g-3">
        ${announcements.map(a => `
          <div class="col-md-6 col-xl-4">
            <div class="card h-100 border shadow-none" style="background:#fafbfa; transition:all 0.2s ease;">
              <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div>
                  <div class="d-flex align-items-center justify-content-between mb-2">
                    ${annCategoryBadge(a.category)}
                    <small class="text-muted" style="font-size:0.75rem;"><i class="fas fa-calendar-alt me-1"></i>${a.published_at || ''}</small>
                  </div>
                  <h6 class="fw-bold mb-2 text-dark" style="font-size:0.95rem; line-height:1.35;">${escHtml(a.title)}</h6>
                  <p class="text-muted mb-3" style="font-size:0.82rem; line-height:1.5;">${escHtml(a.body).substring(0, 110)}${a.body.length > 110 ? '…' : ''}</p>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                  <small class="text-muted" style="font-size:0.75rem;"><i class="fas fa-user-shield me-1" style="color:var(--color-primary);"></i>${escHtml(a.published_by_name || 'MDRRMO')}</small>
                  <button class="btn btn-sm btn-outline-primary" style="font-size:0.75rem; padding:0.2rem 0.55rem;" onclick="viewAnnouncement(${a.id})">
                    Read Advisory <i class="fas fa-chevron-right ms-1"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        `).join('')}
      </div>`;
  }

  // ── View Announcement Modal Handler ─────────────────────────
  window.viewAnnouncement = function (id) {
    const a = announcements.find(item => Number(item.id) === Number(id));
    if (!a) return;

    document.getElementById('modalAnnTitle').innerHTML = `<i class="fas fa-bullhorn me-2" style="color:var(--color-accent);"></i>${escHtml(a.title)}`;
    document.getElementById('modalAnnBody').innerHTML = `
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3 pb-3 border-bottom">
        <div>${annCategoryBadge(a.category)}</div>
        <div class="text-muted small">
          <span class="me-3"><i class="fas fa-calendar-alt me-1"></i>Published: <strong>${a.published_at || '—'}</strong></span>
          <span><i class="fas fa-user-shield me-1" style="color:var(--color-primary);"></i>By: <strong>${escHtml(a.published_by_name || 'MDRRMO')}</strong></span>
        </div>
      </div>
      <div class="announcement-modal-body mb-4" style="font-size:0.92rem; line-height:1.7; color:#2d3748; white-space:pre-wrap;">${escHtml(a.body)}</div>
      <div class="alert alert-secondary small mb-0 py-2">
        <i class="fas fa-info-circle me-1" style="color:var(--color-primary);"></i><strong>Official DRRM Advisory:</strong> This announcement was broadcasted by the Municipal Disaster Risk Reduction and Management Office (MDRRMO) of Santo Niño, Cagayan.
      </div>
    `;
    new bootstrap.Modal(document.getElementById('viewAnnouncementModal')).show();
  };
});
</script>
</body>
</html>
