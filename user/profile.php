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
  <title>My Profile — ODMIS User</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="../assets/css/style.css" />
  <style>
    .avatar-wrapper {
      position: relative;
      display: inline-block;
      margin: 0 auto 0.75rem;
    }
    .profile-avatar-lg {
      width: 96px; height: 96px; border-radius: 50%;
      background: var(--color-primary, #0d6efd);
      color: #fff; font-size: 2.5rem; font-weight: 700;
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto;
      overflow: hidden;
      border: 3px solid #fff;
      box-shadow: 0 4px 12px rgba(0,0,0,0.12);
      position: relative;
    }
    .profile-avatar-img {
      width: 100%; height: 100%; object-fit: cover; border-radius: 50%; display: block;
    }
    .avatar-camera-btn {
      position: absolute;
      bottom: 2px;
      right: 2px;
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: var(--color-primary, #0d6efd);
      color: #fff;
      border: 2px solid #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.85rem;
      cursor: pointer;
      box-shadow: 0 2px 6px rgba(0,0,0,0.25);
      transition: all 0.2s ease;
      z-index: 2;
    }
    .avatar-camera-btn:hover {
      background: #0b5ed7;
      transform: scale(1.1);
      color: #fff;
    }
    .info-row { display: flex; align-items: flex-start; padding: .6rem 0; border-bottom: 1px solid #f0f0f0; font-size: .875rem; }
    .info-row:last-child { border-bottom: none; }
    .info-label { width: 160px; flex-shrink: 0; color: #777; font-weight: 600; }
    .info-value { color: #333; word-break: break-word; }
    .toast-container-fixed { position: fixed; top: 1.25rem; right: 1.25rem; z-index: 9999; }
    .password-field { position: relative; }
    .password-toggle { position: absolute; right: .75rem; top: 50%; transform: translateY(-50%); cursor: pointer; color: #777; }
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
      <li class="sidebar-nav-item"><a href="evacuation-centers.php" class="sidebar-nav-link" data-page="evacuation-centers"><i class="fas fa-house-damage nav-icon"></i><span class="nav-label">Evacuation Centers</span></a></li>
      <li class="sidebar-nav-item"><a href="profile.php" class="sidebar-nav-link active" data-page="profile"><i class="fas fa-user nav-icon"></i><span class="nav-label">Profile</span></a></li>
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
  <h1 class="navbar-page-title">My Profile</h1>
  <div class="navbar-right">
    <button class="navbar-icon-btn position-relative" title="Notifications" onclick="App.showNotificationModal()">
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
      <h1 class="mb-1"><i class="fas fa-user me-2" style="color:var(--color-accent)"></i>My Profile</h1>
      <p class="page-subtitle mb-0">Manage your personal information and account settings</p>
    </div>
  </div>

  <div class="row g-4">
    <!-- ── LEFT COL: Profile Card ── -->
    <div class="col-lg-4">
      <div class="card shadow-sm text-center mb-3">
        <div class="card-body py-4">
          <div class="avatar-wrapper">
            <div class="profile-avatar-lg" id="profileAvatarLg">U</div>
            <button type="button" class="avatar-camera-btn" id="avatarCameraBtn" title="Upload new photo">
              <i class="fas fa-camera"></i>
            </button>
          </div>
          <h5 class="fw-bold mb-1" id="profileFullName">—</h5>
          <div class="mb-2">
            <span class="badge bg-secondary me-1" id="profileUsername">username</span>
            <span class="badge bg-primary">Resident</span>
          </div>
          <div class="d-flex justify-content-center gap-2 mb-3">
            <button type="button" class="btn btn-sm btn-outline-primary" id="btnChangePhoto">
              <i class="fas fa-camera me-1"></i>Change Photo
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger" id="btnRemovePhoto" style="display:none;">
              <i class="fas fa-trash-alt me-1"></i>Remove
            </button>
          </div>
          <input type="file" id="avatarFileInput" accept="image/jpeg,image/png,image/webp" style="display:none;" />
          <p class="text-muted small mb-3">
            <i class="fas fa-calendar-alt me-1"></i>Member since <span id="profileMemberSince">—</span>
          </p>
          <button class="btn btn-outline-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#editProfileModal">
            <i class="fas fa-edit me-2"></i>Edit Profile
          </button>
        </div>
      </div>

      <!-- Quick Stats -->
      <div class="card shadow-sm">
        <div class="card-header py-3"><h6 class="mb-0 fw-bold"><i class="fas fa-chart-bar me-2 text-primary"></i>My Activity</h6></div>
        <div class="card-body p-3">
          <div class="info-row"><span class="info-label">Reports Submitted</span><span class="info-value fw-bold text-primary" id="statReports">0</span></div>
          <div class="info-row"><span class="info-label">Active Alerts</span><span class="info-value fw-bold text-danger" id="statAlerts">0</span></div>
        </div>
      </div>
    </div>

    <!-- ── RIGHT COL ── -->
    <div class="col-lg-8">
      <!-- Personal Information -->
      <div class="card shadow-sm mb-3">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
          <h6 class="mb-0 fw-bold"><i class="fas fa-id-card me-2 text-primary"></i>Personal Information</h6>
          <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal">
            <i class="fas fa-edit me-1"></i>Edit
          </button>
        </div>
        <div class="card-body p-3">
          <div class="info-row"><span class="info-label"><i class="fas fa-user me-2 text-muted"></i>Full Name</span><span class="info-value" id="infoFullName">—</span></div>
          <div class="info-row"><span class="info-label"><i class="fas fa-at me-2 text-muted"></i>Username</span><span class="info-value" id="infoUsername">—</span></div>
          <div class="info-row"><span class="info-label"><i class="fas fa-envelope me-2 text-muted"></i>Email</span><span class="info-value" id="infoEmail">—</span></div>
          <div class="info-row"><span class="info-label"><i class="fas fa-phone me-2 text-muted"></i>Contact Number</span><span class="info-value" id="infoContact">—</span></div>
          <div class="info-row"><span class="info-label"><i class="fas fa-birthday-cake me-2 text-muted"></i>Date of Birth</span><span class="info-value" id="infoDob">—</span></div>
        </div>
      </div>

      <!-- Address -->
      <div class="card shadow-sm mb-3">
        <div class="card-header py-3">
          <h6 class="mb-0 fw-bold"><i class="fas fa-map-marker-alt me-2 text-primary"></i>Address</h6>
        </div>
        <div class="card-body p-3">
          <div class="info-row"><span class="info-label"><i class="fas fa-home me-2 text-muted"></i>Full Address</span><span class="info-value" id="infoAddress">—</span></div>
        </div>
      </div>

      <!-- Account Security -->
      <div class="card shadow-sm">
        <div class="card-header py-3">
          <h6 class="mb-0 fw-bold"><i class="fas fa-lock me-2 text-primary"></i>Account Security</h6>
        </div>
        <div class="card-body p-3">
          <h6 class="fw-semibold mb-3 text-muted" style="font-size:.85rem">Change Password</h6>
          <form id="changePasswordForm" novalidate>
            <div class="mb-3">
              <label class="form-label fw-semibold small">Current Password <span class="text-danger">*</span></label>
              <div class="password-field">
                <input type="password" class="form-control" id="currentPassword" placeholder="Enter current password" required />
                <i class="fas fa-eye password-toggle" id="toggleCurrentPwd"></i>
              </div>
              <div class="invalid-feedback" id="currentPwdError">Current password is incorrect.</div>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold small">New Password <span class="text-danger">*</span></label>
              <div class="password-field">
                <input type="password" class="form-control" id="newPassword" placeholder="Minimum 6 characters" required minlength="6" />
                <i class="fas fa-eye password-toggle" id="toggleNewPwd"></i>
              </div>
              <div class="invalid-feedback">Password must be at least 6 characters.</div>
            </div>
            <div class="mb-4">
              <label class="form-label fw-semibold small">Confirm New Password <span class="text-danger">*</span></label>
              <div class="password-field">
                <input type="password" class="form-control" id="confirmPassword" placeholder="Re-enter new password" required />
                <i class="fas fa-eye password-toggle" id="toggleConfirmPwd"></i>
              </div>
              <div class="invalid-feedback" id="confirmPwdError">Passwords do not match.</div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Save Changes</button>
            <button type="reset" class="btn btn-outline-secondary ms-2"><i class="fas fa-undo me-2"></i>Reset</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- ═══════════════════════════════════════
     EDIT PROFILE MODAL
════════════════════════════════════════ -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Profile</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <form id="editProfileForm" novalidate>
          <div class="row g-3">
            <div class="col-12">
              <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 border">
                <div class="profile-avatar-lg shadow-sm" id="modalProfileAvatar" style="width:64px; height:64px; font-size:1.6rem; margin:0; flex-shrink:0;">U</div>
                <div class="flex-grow-1">
                  <h6 class="fw-bold mb-1">Profile Photo</h6>
                  <p class="text-muted small mb-2">Upload JPG, PNG, or WebP (max 5MB).</p>
                  <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary btn-sm py-1 px-3" id="modalBtnChangePhoto">
                      <i class="fas fa-camera me-1"></i>Upload Photo
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm py-1 px-2" id="modalBtnRemovePhoto" style="display:none;">
                      <i class="fas fa-trash-alt me-1"></i>Remove
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Username <span class="text-muted">(cannot be changed)</span></label>
              <input type="text" class="form-control" id="editUsername" readonly />
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="editFullName" required />
              <div class="invalid-feedback">Full name is required.</div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
              <input type="email" class="form-control" id="editEmail" required />
              <div class="invalid-feedback">Valid email is required.</div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Contact Number</label>
              <input type="tel" class="form-control" id="editContact" placeholder="09XXXXXXXXX" maxlength="11" inputmode="numeric" pattern="^09\d{9}$" oninput="this.value = this.value.replace(/\D/g, '').slice(0, 11)" />
              <div class="form-text">Format: 11 digits starting with 09 (e.g., 09171234567)</div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Date of Birth</label>
              <input type="date" class="form-control" id="editDob" />
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Address</label>
              <textarea class="form-control" id="editAddress" rows="2" placeholder="House/Lot, Street, Barangay, Municipality"></textarea>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" id="saveProfileBtn"><i class="fas fa-save me-2"></i>Save Changes</button>
      </div>
    </div>
  </div>
</div>

<!-- ═══════════════════════════════════════
     PREVIEW AVATAR MODAL
════════════════════════════════════════ -->
<div class="modal fade" id="previewAvatarModal" tabindex="-1">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white py-2">
        <h6 class="modal-title mb-0"><i class="fas fa-camera me-2"></i>Profile Picture Preview</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center p-4">
        <div class="mx-auto mb-3 shadow-sm rounded-circle overflow-hidden border border-3 border-light" style="width: 130px; height: 130px;">
          <img id="avatarPreviewImg" src="" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;" />
        </div>
        <p class="small text-muted mb-1 text-truncate" id="avatarPreviewName">—</p>
        <span class="badge bg-light text-secondary border" id="avatarPreviewSize">0 KB</span>
      </div>
      <div class="modal-footer justify-content-between p-2">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary btn-sm" id="saveAvatarBtn">
          <i class="fas fa-check me-1"></i>Save Picture
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ═══════════════════════════════════════
     REMOVE AVATAR MODAL
════════════════════════════════════════ -->
<div class="modal fade" id="removeAvatarModal" tabindex="-1">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white py-2">
        <h6 class="modal-title mb-0"><i class="fas fa-trash-alt me-2"></i>Remove Picture</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center py-4">
        <div class="text-danger mb-2"><i class="fas fa-exclamation-triangle fa-2x"></i></div>
        <p class="mb-0">Are you sure you want to remove your profile picture? Your initials will be displayed instead.</p>
      </div>
      <div class="modal-footer justify-content-between p-2">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger btn-sm" id="confirmRemoveAvatarBtn">
          <i class="fas fa-trash-alt me-1"></i>Remove
        </button>
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

<!-- Toast -->
<div class="toast-container-fixed">
  <div id="appToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body" id="appToastBody">Message</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
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

  let session = Auth.getSession();
  if (!session) return;

  // ── Toast helper ───────────────────────────────────────────
  function showToast(msg, type = 'success') {
    const el = document.getElementById('appToast');
    el.className = `toast align-items-center text-white border-0 bg-${type}`;
    document.getElementById('appToastBody').textContent = msg;
    new bootstrap.Toast(el, { delay: 3500 }).show();
  }

  // ── Populate UI & Avatars ──────────────────────────────────
  let _profileData = {};

  function renderAvatars(picUrl, initial) {
    const hasPic = !!picUrl;
    const resolvedUrl = hasPic ? App.resolveAssetUrl(picUrl) : '';

    const lgEl = document.getElementById('profileAvatarLg');
    if (lgEl) {
      if (hasPic) {
        lgEl.innerHTML = `<img src="${resolvedUrl}" alt="Profile Avatar" class="profile-avatar-img">`;
      } else {
        lgEl.innerHTML = '';
        lgEl.textContent = initial;
      }
    }

    const modalEl = document.getElementById('modalProfileAvatar');
    if (modalEl) {
      if (hasPic) {
        modalEl.innerHTML = `<img src="${resolvedUrl}" alt="Profile Avatar" class="profile-avatar-img">`;
      } else {
        modalEl.innerHTML = '';
        modalEl.textContent = initial;
      }
    }

    ['navAvatar', 'sidebarAvatar'].forEach(id => {
      const el = document.getElementById(id);
      if (el) {
        if (hasPic) {
          el.innerHTML = `<img src="${resolvedUrl}" alt="Avatar" style="width:100%;height:100%;object-fit:cover;border-radius:50%;display:block;">`;
        } else {
          el.innerHTML = '';
          el.textContent = initial;
        }
      }
    });

    const removeBtn = document.getElementById('btnRemovePhoto');
    if (removeBtn) removeBtn.style.display = hasPic ? 'inline-flex' : 'none';

    const modalRemoveBtn = document.getElementById('modalBtnRemovePhoto');
    if (modalRemoveBtn) modalRemoveBtn.style.display = hasPic ? 'inline-flex' : 'none';
  }

  function populateUI(user) {
    if (!user) return;
    const initial = (user.full_name || user.fullName || user.username || 'U')[0].toUpperCase();
    const pic     = user.profile_picture || user.profilePicture || null;

    renderAvatars(pic, initial);

    const els = {
      sidebarName:      user.full_name || user.fullName || user.username,
      navUsername:      user.full_name || user.fullName || user.username,
      profileFullName:  user.full_name || user.fullName || '—',
      profileUsername:  '@' + user.username,
    };
    Object.entries(els).forEach(([id, val]) => {
      const el = document.getElementById(id);
      if (el) el.textContent = val;
    });

    const since = user.created_at || user.createdAt;
    const sinceEl = document.getElementById('profileMemberSince');
    if (sinceEl) sinceEl.textContent = since
      ? new Date(since).toLocaleDateString('en-PH', { year: 'numeric', month: 'long' })
      : '—';

    document.getElementById('infoFullName').textContent = user.full_name || user.fullName || '—';
    document.getElementById('infoUsername').textContent = user.username || '—';
    document.getElementById('infoEmail').textContent    = user.email    || '—';
    document.getElementById('infoContact').textContent  = user.contact_number || user.contactNumber || '—';
    document.getElementById('infoDob').textContent      = (user.date_of_birth || user.dateOfBirth)
      ? new Date((user.date_of_birth || user.dateOfBirth) + 'T00:00:00').toLocaleDateString('en-PH', { year:'numeric', month:'long', day:'numeric' })
      : '—';
    document.getElementById('infoAddress').textContent  = user.address  || '—';
  }

  // ── Load profile from API ──────────────────────────────────
  async function loadProfile() {
    try {
      const [profileRes, reportRes, alertRes] = await Promise.all([
        ApiClient.get('/profile/index.php'),
        ApiClient.get('/user-reports/index.php'),
        ApiClient.get('/alerts/index.php')
      ]);
      _profileData = profileRes.data || {};
      populateUI(_profileData);

      const myReports  = Array.isArray(reportRes.data) ? reportRes.data : [];
      const activeAlerts = (Array.isArray(alertRes.data) ? alertRes.data : []).filter(a => a.status !== 'Resolved');
      document.getElementById('statReports').textContent = myReports.length;
      document.getElementById('statAlerts').textContent  = activeAlerts.length;
      document.getElementById('notifBadge').textContent  = activeAlerts.length;
    } catch (err) {
      console.error('Profile load error:', err.message);
      populateUI(session);
    }
  }

  await loadProfile();

  // ── Profile Photo Upload & Removal Handlers ────────────────
  let _selectedAvatarFile = null;
  const previewModalEl = document.getElementById('previewAvatarModal');
  const previewModal   = previewModalEl ? new bootstrap.Modal(previewModalEl) : null;
  const removeModalEl  = document.getElementById('removeAvatarModal');
  const removeModal    = removeModalEl ? new bootstrap.Modal(removeModalEl) : null;

  function triggerPhotoSelect() {
    const input = document.getElementById('avatarFileInput');
    if (input) {
      input.value = '';
      input.click();
    }
  }

  const cameraBtn = document.getElementById('avatarCameraBtn');
  if (cameraBtn) cameraBtn.addEventListener('click', triggerPhotoSelect);

  const btnChangePhoto = document.getElementById('btnChangePhoto');
  if (btnChangePhoto) btnChangePhoto.addEventListener('click', triggerPhotoSelect);

  const modalBtnChange = document.getElementById('modalBtnChangePhoto');
  if (modalBtnChange) modalBtnChange.addEventListener('click', triggerPhotoSelect);

  const btnRemovePhoto = document.getElementById('btnRemovePhoto');
  if (btnRemovePhoto) btnRemovePhoto.addEventListener('click', () => removeModal && removeModal.show());

  const modalBtnRemove = document.getElementById('modalBtnRemovePhoto');
  if (modalBtnRemove) modalBtnRemove.addEventListener('click', () => removeModal && removeModal.show());

  const fileInput = document.getElementById('avatarFileInput');
  if (fileInput) {
    fileInput.addEventListener('change', function () {
      const file = this.files && this.files[0];
      if (!file) return;

      const allowed = ['image/jpeg', 'image/png', 'image/webp'];
      if (!allowed.includes(file.type)) {
        showToast('Only JPG, PNG, and WebP images are allowed.', 'warning');
        this.value = '';
        return;
      }

      if (file.size > 5 * 1024 * 1024) {
        showToast('Image size exceeds 5MB limit.', 'warning');
        this.value = '';
        return;
      }

      _selectedAvatarFile = file;

      const reader = new FileReader();
      reader.onload = function (e) {
        const previewImg = document.getElementById('avatarPreviewImg');
        if (previewImg) previewImg.src = e.target.result;
        const nameEl = document.getElementById('avatarPreviewName');
        if (nameEl) nameEl.textContent = file.name;
        const sizeEl = document.getElementById('avatarPreviewSize');
        if (sizeEl) sizeEl.textContent = (file.size / 1024).toFixed(1) + ' KB';
        if (previewModal) previewModal.show();
      };
      reader.readAsDataURL(file);
    });
  }

  const saveAvatarBtn = document.getElementById('saveAvatarBtn');
  if (saveAvatarBtn) {
    saveAvatarBtn.addEventListener('click', async function () {
      if (!_selectedAvatarFile) return;

      const btn = this;
      const origHtml = btn.innerHTML;
      btn.disabled = true;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';

      const fd = new FormData();
      fd.append('profile_picture', _selectedAvatarFile);

      try {
        const res = await ApiClient.upload('/profile/upload-picture.php', fd);
        const newPic = res.data && res.data.profile_picture;
        _profileData.profile_picture = newPic;
        if (res.data && res.data.token) {
          ApiClient.setToken(res.data.token);
        }
        const initial = (_profileData.full_name || _profileData.username || 'U')[0].toUpperCase();
        renderAvatars(newPic, initial);
        if (previewModal) previewModal.hide();
        showToast('Profile picture updated successfully!', 'success');
      } catch (err) {
        showToast(err.message || 'Failed to upload profile picture.', 'danger');
      } finally {
        btn.disabled = false;
        btn.innerHTML = origHtml;
        if (fileInput) fileInput.value = '';
        _selectedAvatarFile = null;
      }
    });
  }

  const confirmRemoveAvatarBtn = document.getElementById('confirmRemoveAvatarBtn');
  if (confirmRemoveAvatarBtn) {
    confirmRemoveAvatarBtn.addEventListener('click', async function () {
      const btn = this;
      const origHtml = btn.innerHTML;
      btn.disabled = true;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Removing...';

      try {
        const res = await ApiClient.post('/profile/remove-picture.php', {});
        _profileData.profile_picture = null;
        if (res.data && res.data.token) {
          ApiClient.setToken(res.data.token);
        }
        const initial = (_profileData.full_name || _profileData.username || 'U')[0].toUpperCase();
        renderAvatars(null, initial);
        if (removeModal) removeModal.hide();
        showToast('Profile picture removed successfully.', 'info');
      } catch (err) {
        showToast(err.message || 'Failed to remove profile picture.', 'danger');
      } finally {
        btn.disabled = false;
        btn.innerHTML = origHtml;
      }
    });
  }

  // Sidebar/navbar toggle/logout
  document.getElementById('sidebarToggle').addEventListener('click', () => document.body.classList.toggle('sidebar-collapsed'));
  document.getElementById('confirmLogoutBtn').addEventListener('click', () => Auth.logout());
  document.querySelectorAll('[data-action="logout"]').forEach(el => {
    el.addEventListener('click', e => { e.preventDefault(); new bootstrap.Modal(document.getElementById('logoutModal')).show(); });
  });

  // ── Password toggles ───────────────────────────────────────
  function setupToggle(toggleId, inputId) {
    document.getElementById(toggleId).addEventListener('click', function () {
      const input = document.getElementById(inputId);
      const isText = input.type === 'text';
      input.type = isText ? 'password' : 'text';
      this.classList.toggle('fa-eye', isText);
      this.classList.toggle('fa-eye-slash', !isText);
    });
  }
  setupToggle('toggleCurrentPwd', 'currentPassword');
  setupToggle('toggleNewPwd',     'newPassword');
  setupToggle('toggleConfirmPwd', 'confirmPassword');

  // ── Edit Profile Modal: pre-fill ──────────────────────────
  document.getElementById('editProfileModal').addEventListener('show.bs.modal', function () {
    const u = _profileData;
    document.getElementById('editUsername').value = u.username || session.username || '';
    document.getElementById('editFullName').value = u.full_name || u.fullName || '';
    document.getElementById('editEmail').value    = u.email    || '';
    document.getElementById('editContact').value  = u.contact_number || u.contactNumber || '';
    document.getElementById('editDob').value      = u.date_of_birth  || u.dateOfBirth   || '';
    document.getElementById('editAddress').value  = u.address  || '';
    document.getElementById('editProfileForm').classList.remove('was-validated');
  });

  // ── Save Profile ───────────────────────────────────────────
  document.getElementById('saveProfileBtn').addEventListener('click', async function () {
    const form = document.getElementById('editProfileForm');
    form.classList.add('was-validated');
    if (!form.checkValidity()) return;

    const contactVal = document.getElementById('editContact').value.trim();
    if (contactVal !== '' && !/^09\d{9}$/.test(contactVal)) {
      showToast('Contact number must be an 11-digit number in format 09XXXXXXXXX.', 'warning');
      document.getElementById('editContact').focus();
      return;
    }

    const updates = {
      full_name      : document.getElementById('editFullName').value.trim(),
      email          : document.getElementById('editEmail').value.trim(),
      contact_number : contactVal,
      date_of_birth  : document.getElementById('editDob').value,
      address        : document.getElementById('editAddress').value.trim()
    };

    this.disabled = true;
    try {
      const res = await ApiClient.put('/profile/update.php', updates);
      _profileData = { ..._profileData, ...updates };
      populateUI(_profileData);
      bootstrap.Modal.getInstance(document.getElementById('editProfileModal')).hide();
      showToast('Profile updated successfully!', 'success');
    } catch (err) {
      showToast(err.message || 'Failed to update profile.', 'danger');
    } finally {
      this.disabled = false;
    }
  });

  // ── Change Password ────────────────────────────────────────
  document.getElementById('changePasswordForm').addEventListener('submit', async function (e) {
    e.preventDefault();
    this.classList.add('was-validated');

    const currentPwd    = document.getElementById('currentPassword').value;
    const newPwd        = document.getElementById('newPassword').value;
    const confirmPwd    = document.getElementById('confirmPassword').value;
    const currentPwdInput = document.getElementById('currentPassword');
    const confirmPwdInput = document.getElementById('confirmPassword');

    if (newPwd !== confirmPwd) {
      confirmPwdInput.setCustomValidity('mismatch');
      confirmPwdInput.classList.add('is-invalid');
      document.getElementById('confirmPwdError').textContent = 'Passwords do not match.';
      return;
    } else {
      confirmPwdInput.setCustomValidity('');
      confirmPwdInput.classList.remove('is-invalid');
    }
    if (newPwd.length < 6) return;

    const btn = this.querySelector('[type="submit"]');
    if (btn) btn.disabled = true;
    try {
      await ApiClient.put('/profile/change-password.php', { current_password: currentPwd, new_password: newPwd });
      showToast('Password changed successfully!', 'success');
      this.reset();
      this.classList.remove('was-validated');
    } catch (err) {
      currentPwdInput.setCustomValidity('incorrect');
      currentPwdInput.classList.add('is-invalid');
      document.getElementById('currentPwdError').textContent = err.message || 'Current password is incorrect.';
    } finally {
      if (btn) btn.disabled = false;
    }
  });

  // Clear custom validity on input
  ['currentPassword', 'newPassword', 'confirmPassword'].forEach(id => {
    document.getElementById(id).addEventListener('input', function () {
      this.setCustomValidity('');
      this.classList.remove('is-invalid');
    });
  });
});
</script>
</body>
</html>
