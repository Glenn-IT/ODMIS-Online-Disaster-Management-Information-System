<?php
// admin/announcements.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Announcements — ODMIS Admin</title>

  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <!-- ODMIS Styles -->
  <link rel="stylesheet" href="../assets/css/style.css" />

  <style>
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
      padding: 0.25rem 0.6rem;
      font-size: var(--font-size-xs, 0.75rem);
      font-weight: 600;
      border-radius: 4px;
    }
    .badge-status-active {
      background-color: var(--color-success-light, #d4edda);
      color: var(--color-success, #155724);
    }
    .badge-status-inactive {
      background-color: var(--color-danger-light, #f8d7da);
      color: var(--color-danger, #721c24);
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
    .btn-view   { background: #d4edda; color: #155724; } /* Green theme toggle */
    .btn-view:hover   { background: #27ae60; color: #fff; }
    .btn-edit   { background: #fff3cd; color: #856404; }
    .btn-edit:hover   { background: #f39c12; color: #fff; }
    .btn-delete { background: #f8d7da; color: #721c24; }
    .btn-delete:hover { background: #c0392b; color: #fff; }
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
        <a href="announcements.php" class="sidebar-nav-link active" data-page="announcements">
          <i class="fas fa-bullhorn nav-icon"></i>
          <span class="nav-label">Announcements</span>
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

  <h1 class="navbar-page-title">Announcements</h1>

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
        <i class="fas fa-bullhorn me-2" style="color:var(--color-accent);"></i>
        Announcements Management
      </h1>
      <p class="page-subtitle mb-0">Post and manage official announcements on the resident dashboards.</p>
    </div>
    <button class="btn btn-primary" id="btnPostAnnouncement">
      <i class="fas fa-plus me-2"></i>New Announcement
    </button>
  </div>

  <!-- Filters -->
  <div class="filter-bar">
    <div class="filter-group search-group">
      <span class="filter-label"><i class="fas fa-search me-1"></i>Search</span>
      <div class="search-wrapper">
        <i class="fas fa-search search-icon"></i>
        <input type="text" id="annSearch" class="form-control form-control-sm" placeholder="Search by title, category, body..." />
      </div>
    </div>
    <div class="filter-group">
      <span class="filter-label"><i class="fas fa-tags me-1"></i>Category</span>
      <select id="annCategory" class="form-select form-select-sm">
        <option value="">All Categories</option>
        <option value="Evacuation Order">Evacuation Order</option>
        <option value="Weather Advisory">Weather Advisory</option>
        <option value="Training & Capacity Building">Training & Capacity Building</option>
        <option value="Relief Distribution">Relief Distribution</option>
        <option value="Damage Assessment Report">Damage Assessment Report</option>
        <option value="Awareness Campaign">Awareness Campaign</option>
      </select>
    </div>
    <div class="filter-group" style="flex: 0; min-width: auto;">
      <button class="btn btn-outline-secondary btn-sm" id="btnResetFilters" title="Clear filters">
        <i class="fas fa-undo"></i>
      </button>
    </div>
  </div>

  <!-- Announcements Table Card -->
  <div class="card card-table shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center py-3">
      <h6 class="mb-0 fw-bold"><i class="fas fa-list me-2"></i>Announcements List</h6>
      <span class="badge bg-primary rounded-pill" id="recordCount">0</span>
    </div>
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th>Title</th>
            <th>Category</th>
            <th>Publisher</th>
            <th>Date Posted</th>
            <th class="text-center">Status</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody id="annTableBody">
          <!-- JS Injected rows -->
        </tbody>
      </table>
    </div>
    
    <!-- Empty State -->
    <div id="emptyState" class="empty-state d-none py-5 text-center text-muted">
      <i class="fas fa-bullhorn fa-3x mb-3 text-light-gray"></i>
      <p class="empty-state-text">No announcements matched your filters.</p>
    </div>
  </div>

</main>

<!-- ══════════════════════════════════════════════════════════
     ANNOUNCEMENT FORM MODAL
══════════════════════════════════════════════════════════ -->
<div class="modal fade" id="announcementModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0">
      <div class="modal-header bg-primary text-white py-3">
        <h5 class="modal-title fw-bold" id="annModalTitle"><i class="fas fa-bullhorn me-2"></i>New Announcement</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <form id="annForm" novalidate>
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label" for="annTitle">Title <span class="text-danger">*</span></label>
              <input type="text" id="annTitle" class="form-control" placeholder="Enter announcement title..." required />
              <div class="invalid-feedback">Title is required.</div>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="annCategorySelect">Category <span class="text-danger">*</span></label>
              <select id="annCategorySelect" class="form-select" required>
                <option value="">— Select Category —</option>
                <option value="Evacuation Order">Evacuation Order</option>
                <option value="Weather Advisory">Weather Advisory</option>
                <option value="Training & Capacity Building">Training & Capacity Building</option>
                <option value="Relief Distribution">Relief Distribution</option>
                <option value="Damage Assessment Report">Damage Assessment Report</option>
                <option value="Awareness Campaign">Awareness Campaign</option>
              </select>
              <div class="invalid-feedback">Category is required.</div>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="annDate">Date Posted</label>
              <input type="date" id="annDate" class="form-control" />
            </div>
            <div class="col-12">
              <label class="form-label" for="annBody">Content Body <span class="text-danger">*</span></label>
              <textarea id="annBody" class="form-control" rows="5" placeholder="Write announcement details..." required></textarea>
              <div class="invalid-feedback">Content body is required.</div>
            </div>
            <div class="col-12" id="annActiveWrapper">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="annActive" checked />
                <label class="form-check-label fw-600" for="annActive">Publish (Make Active)</label>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer bg-light border-top-0 px-4 py-3">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary btn-sm" id="btnSaveAnn">Save Announcement</button>
      </div>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     DELETE CONFIRMATION MODAL
══════════════════════════════════════════════════════════ -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white py-2">
        <h6 class="modal-title fw-bold"><i class="fas fa-trash me-2"></i>Confirm Delete</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center py-4">
        <p class="mb-0">Are you sure you want to delete this announcement?</p>
        <strong id="deleteLabel" class="d-block mt-2 text-danger"></strong>
      </div>
      <div class="modal-footer border-top-0 justify-content-center pt-0">
        <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-danger btn-sm" id="btnConfirmDelete">Delete</button>
      </div>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     LOGOUT CONFIRMATION MODAL
══════════════════════════════════════════════════════════ -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white py-2">
        <h6 class="modal-title fw-bold"><i class="fas fa-sign-out-alt me-2"></i>Confirm Logout</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center py-4">
        <p class="mb-0">Are you sure you want to logout?</p>
      </div>
      <div class="modal-footer border-top-0 justify-content-center pt-0">
        <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-danger btn-sm" id="confirmLogoutBtn">Logout</button>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/auth.js"></script>
<script src="../assets/js/app.js"></script>

<script>
const ManagePage = (function() {
  'use strict';

  let _allAnnouncements = [];
  let _filteredAnn = [];

  let _annModal, _deleteModal, _logoutModal;
  
  let _editingAnnId = null;
  let _deleteTarget = null; // { id: number, name: string }

  // ── HELPERS & UTILITIES ──────────────────────────────────
  function esc(s) {
    if (!s) return '—';
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
  }

  function formatDate(dStr) {
    if (!dStr) return '—';
    try {
      const d = new Date(dStr);
      if (isNaN(d.getTime())) return dStr;
      return d.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    } catch (_) { return dStr; }
  }

  function showToast(message, type) {
    if (typeof App !== 'undefined' && App.showToast) {
      App.showToast(message, type);
    } else {
      let container = document.getElementById('toastContainer');
      if (!container) {
        container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'toast-container';
        document.body.appendChild(container);
      }
      const t = document.createElement('div');
      const icons = { success: 'fa-check-circle', error: 'fa-times-circle', warning: 'fa-exclamation-triangle', info: 'fa-info-circle' };
      t.className = 'odmis-toast toast-' + (type || 'success');
      t.innerHTML = '<i class="fas ' + (icons[type] || 'fa-info-circle') + ' toast-icon"></i>' +
                    '<div class="toast-body"><div class="toast-message">' + esc(message) + '</div></div>' +
                    '<button class="toast-close" onclick="this.parentNode.remove()"><i class="fas fa-times"></i></button>' +
                    '<div class="toast-progress"></div>';
      container.appendChild(t);
      setTimeout(() => { if (t.parentNode) t.remove(); }, 4000);
    }
  }

  // ── DATA ACCESS & DATA LOADERS ───────────────────────────
  async function loadAnnouncements() {
    try {
      const res = await ApiClient.get('/announcements/index.php?all=1');
      _allAnnouncements = Array.isArray(res.data) ? res.data : [];
    } catch (err) {
      showToast('Failed to load announcements: ' + err.message, 'error');
      _allAnnouncements = [];
    }
    filterAnnouncements();
  }

  // ── FILTERING & SORTING ──────────────────────────────────
  function filterAnnouncements() {
    const q = (document.getElementById('annSearch').value || '').toLowerCase().trim();
    const cat = document.getElementById('annCategory').value || '';

    _filteredAnn = _allAnnouncements.filter(a => {
      if (cat && a.category !== cat) return false;
      if (q) {
        const text = [a.title, a.body, a.category, a.published_by_name].join(' ').toLowerCase();
        if (!text.includes(q)) return false;
      }
      return true;
    });

    _filteredAnn.sort((a, b) => new Date(b.published_at) - new Date(a.published_at));
    renderAnnTable();
  }

  // ── RENDERING FUNCTIONS ──────────────────────────────────
  function renderAnnTable() {
    const tbody = document.getElementById('annTableBody');
    const empty = document.getElementById('emptyState');
    document.getElementById('recordCount').textContent = _filteredAnn.length;

    if (_filteredAnn.length === 0) {
      tbody.innerHTML = '';
      empty.classList.remove('d-none');
      return;
    }
    empty.classList.add('d-none');

    tbody.innerHTML = _filteredAnn.map(a => {
      const statusBadge = (parseInt(a.is_active) === 1)
        ? '<span class="badge-status badge-status-active">Active</span>'
        : '<span class="badge-status badge-status-inactive">Inactive</span>';

      const toggleActionTitle = (parseInt(a.is_active) === 1) ? 'Deactivate' : 'Activate';
      const toggleActionIcon = (parseInt(a.is_active) === 1) ? 'fa-toggle-on' : 'fa-toggle-off';

      return `
        <tr>
          <td style="max-width:280px;"><strong class="text-primary d-block" title="${esc(a.title)}">${esc(a.title)}</strong><small class="text-muted text-truncate d-block" style="font-size:0.75rem;">${esc(a.body)}</small></td>
          <td><span class="badge bg-light text-dark border">${esc(a.category || 'General')}</span></td>
          <td class="text-muted"><i class="fas fa-user-circle me-1"></i>${esc(a.published_by_name || 'Admin')}</td>
          <td class="text-muted">${formatDate(a.published_at)}</td>
          <td class="text-center">${statusBadge}</td>
          <td class="text-center">
            <div class="d-flex justify-content-center gap-1">
              <button class="btn-action btn-view" onclick="ManagePage.toggleAnnStatus(${a.id}, ${a.is_active})" title="${toggleActionTitle}"><i class="fas ${toggleActionIcon}"></i></button>
              <button class="btn-action btn-edit" onclick="ManagePage.openEditAnnModal(${a.id})" title="Edit"><i class="fas fa-pencil-alt"></i></button>
              <button class="btn-action btn-delete" onclick="ManagePage.confirmDelete(${a.id}, '${esc(a.title)}')" title="Delete"><i class="fas fa-trash"></i></button>
            </div>
          </td>
        </tr>
      `;
    }).join('');
  }

  // ── ANNOUNCEMENTS WRITE OPERATIONS ───────────────────────
  function openAddAnnModal() {
    _editingAnnId = null;
    document.getElementById('annModalTitle').innerHTML = '<i class="fas fa-bullhorn me-2"></i>New Announcement';
    document.getElementById('annForm').reset();
    document.getElementById('annForm').classList.remove('was-validated');
    document.getElementById('annDate').value = new Date().toISOString().split('T')[0];
    document.getElementById('annActiveWrapper').classList.add('d-none'); // default is active
    _annModal.show();
  }

  async function openEditAnnModal(id) {
    const ann = _allAnnouncements.find(a => a.id === id);
    if (!ann) return;
    _editingAnnId = id;
    document.getElementById('annModalTitle').innerHTML = '<i class="fas fa-pencil-alt me-2"></i>Edit Announcement';
    document.getElementById('annForm').classList.remove('was-validated');

    document.getElementById('annTitle').value = ann.title;
    document.getElementById('annCategorySelect').value = ann.category;
    document.getElementById('annDate').value = ann.published_at ? ann.published_at.split(' ')[0] : '';
    document.getElementById('annBody').value = ann.body;
    document.getElementById('annActive').checked = parseInt(ann.is_active) === 1;
    document.getElementById('annActiveWrapper').classList.remove('d-none');
    _annModal.show();
  }

  async function saveAnnouncement() {
    const form = document.getElementById('annForm');
    if (!form.checkValidity()) {
      form.classList.add('was-validated');
      return;
    }

    const payload = {
      title: document.getElementById('annTitle').value.trim(),
      category: document.getElementById('annCategorySelect').value,
      published_at: document.getElementById('annDate').value,
      body: document.getElementById('annBody').value.trim()
    };

    if (_editingAnnId) {
      payload.is_active = document.getElementById('annActive').checked ? 1 : 0;
    }

    const btn = document.getElementById('btnSaveAnn');
    btn.disabled = true;

    try {
      if (_editingAnnId) {
        await ApiClient.put('/announcements/update.php?id=' + _editingAnnId, payload);
        showToast('Announcement updated successfully.', 'success');
      } else {
        await ApiClient.post('/announcements/store.php', payload);
        showToast('Announcement posted successfully.', 'success');
      }
      _annModal.hide();
      await loadAnnouncements();
    } catch (err) {
      showToast(err.message || 'Saving failed.', 'error');
    } finally {
      btn.disabled = false;
    }
  }

  async function toggleAnnStatus(id, currentStatus) {
    try {
      const payload = { is_active: parseInt(currentStatus) === 1 ? 0 : 1 };
      await ApiClient.put('/announcements/update.php?id=' + id, payload);
      showToast('Announcement status updated.', 'success');
      await loadAnnouncements();
    } catch (err) {
      showToast(err.message || 'Status change failed.', 'error');
    }
  }

  // ── DELETION LOGIC ───────────────────────────────────────
  function confirmDelete(id, name) {
    _deleteTarget = { id, name };
    document.getElementById('deleteLabel').textContent = name;
    _deleteModal.show();
  }

  async function executeDelete() {
    if (!_deleteTarget) return;
    const { id } = _deleteTarget;
    const btn = document.getElementById('btnConfirmDelete');
    btn.disabled = true;

    try {
      await ApiClient.del('/announcements/destroy.php?id=' + id);
      showToast('Announcement deleted successfully.', 'success');
      await loadAnnouncements();
      _deleteModal.hide();
      _deleteTarget = null;
    } catch (err) {
      showToast(err.message || 'Deletion failed.', 'error');
    } finally {
      btn.disabled = false;
    }
  }

  // ── BOOT/INITIALIZATION ──────────────────────────────────
  document.addEventListener('DOMContentLoaded', async function() {
    if (!Auth.requireAdmin()) return;

    if (typeof App !== 'undefined') {
      App.initPage({ page: 'announcements', adminPage: true, requireAdmin: true });
    }

    _annModal = new bootstrap.Modal(document.getElementById('announcementModal'));
    _deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    _logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'));

    // Load initial datasets
    await loadAnnouncements();

    // Top Right profile actions
    document.getElementById('sidebarToggle').addEventListener('click', () => {
      document.body.classList.toggle('sidebar-collapsed');
    });


    // Write click triggers
    document.getElementById('btnPostAnnouncement').addEventListener('click', openAddAnnModal);
    document.getElementById('btnSaveAnn').addEventListener('click', saveAnnouncement);
    document.getElementById('btnConfirmDelete').addEventListener('click', executeDelete);

    // Filters for Announcements
    document.getElementById('annSearch').addEventListener('input', filterAnnouncements);
    document.getElementById('annCategory').addEventListener('change', filterAnnouncements);
    document.getElementById('btnResetFilters').addEventListener('click', () => {
      document.getElementById('annSearch').value = '';
      document.getElementById('annCategory').value = '';
      filterAnnouncements();
    });
  });

  // Export functions to window namespace for inline button onclick triggers
  return {
    toggleAnnStatus,
    openEditAnnModal,
    confirmDelete
  };
})();

window.ManagePage = ManagePage;
</script>

</body>
</html>
