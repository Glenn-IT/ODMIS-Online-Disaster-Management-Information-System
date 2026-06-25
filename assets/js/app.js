/* ============================================================
   ODMIS - Online Disaster Management Information System
   Shared Application Utilities (app.js)
   ============================================================ */

const App = (function () {
  'use strict';

  // ── Storage Keys ─────────────────────────────────────────────
  const KEYS = {
    USERS         : 'odmis_users',
    INCIDENTS     : 'odmis_incidents',
    EVACUATION    : 'odmis_evacuation_centers',
    RELIEF        : 'odmis_relief_operations',
    ANNOUNCEMENTS : 'odmis_announcements',
    ALERTS        : 'odmis_alerts',
    USER_REPORTS  : 'odmis_user_reports'
  };

  // ── Toast Container ──────────────────────────────────────────
  let _toastContainer = null;

  function _getToastContainer() {
    if (_toastContainer && document.body.contains(_toastContainer)) {
      return _toastContainer;
    }
    _toastContainer = document.getElementById('toastContainer');
    if (!_toastContainer) {
      _toastContainer = document.createElement('div');
      _toastContainer.id        = 'toastContainer';
      _toastContainer.className = 'toast-container';
      document.body.appendChild(_toastContainer);
    }
    return _toastContainer;
  }

  // ── Toast icon map ───────────────────────────────────────────
  const _toastIcons = {
    success : 'bi bi-check-circle-fill',
    error   : 'bi bi-x-circle-fill',
    warning : 'bi bi-exclamation-triangle-fill',
    info    : 'bi bi-info-circle-fill'
  };

  /**
   * Display a toast notification.
   * @param {string} message - The message to display.
   * @param {string} type    - 'success' | 'error' | 'warning' | 'info'
   * @param {number} duration - Auto-dismiss in ms (default 4000). 0 = no auto-dismiss.
   */
  function showToast(message, type, duration) {
    type     = type     || 'success';
    duration = (duration === undefined || duration === null) ? 4000 : duration;

    const container = _getToastContainer();
    const icon      = _toastIcons[type] || _toastIcons.info;

    const toast = document.createElement('div');
    toast.className = 'odmis-toast toast-' + type;
    toast.setAttribute('role', 'alert');
    toast.innerHTML =
      '<i class="' + icon + ' toast-icon"></i>' +
      '<div class="toast-body">' +
        '<div class="toast-message">' + _escapeHtml(message) + '</div>' +
      '</div>' +
      '<button class="toast-close" type="button" title="Dismiss">' +
        '<i class="bi bi-x"></i>' +
      '</button>' +
      (duration > 0 ? '<div class="toast-progress" style="animation-duration:' + duration + 'ms"></div>' : '');

    container.appendChild(toast);

    // Close button handler
    const closeBtn = toast.querySelector('.toast-close');
    if (closeBtn) {
      closeBtn.addEventListener('click', function () { _dismissToast(toast); });
    }

    // Auto-dismiss
    if (duration > 0) {
      setTimeout(function () { _dismissToast(toast); }, duration);
    }

    return toast;
  }

  function _dismissToast(toast) {
    if (!toast || !toast.parentNode) return;
    toast.classList.add('hiding');
    setTimeout(function () {
      if (toast.parentNode) toast.parentNode.removeChild(toast);
    }, 320);
  }

  // ── HTML Escape ──────────────────────────────────────────────
  function _escapeHtml(str) {
    if (str === null || str === undefined) return '';
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  // ── Date Formatting ──────────────────────────────────────────
  const _MONTHS = ['January','February','March','April','May','June',
                   'July','August','September','October','November','December'];

  /**
   * Format a date string into a readable form.
   * @param {string|Date} dateStr
   * @param {string} style - 'long' (default) | 'short' | 'time' | 'datetime'
   * @returns {string}
   */
  function formatDate(dateStr, style) {
    if (!dateStr) return '—';
    style = style || 'long';
    try {
      const d = new Date(dateStr);
      if (isNaN(d.getTime())) return String(dateStr);

      const day   = d.getDate();
      const month = d.getMonth();
      const year  = d.getFullYear();
      const hh    = String(d.getHours()).padStart(2, '0');
      const mm    = String(d.getMinutes()).padStart(2, '0');

      switch (style) {
        case 'short':
          return (month + 1) + '/' + day + '/' + year;
        case 'medium':
          return _MONTHS[month].substring(0,3) + ' ' + day + ', ' + year;
        case 'time':
          return hh + ':' + mm;
        case 'datetime':
          return _MONTHS[month].substring(0,3) + ' ' + day + ', ' + year + ' ' + hh + ':' + mm;
        case 'long':
        default:
          return _MONTHS[month] + ' ' + day + ', ' + year;
      }
    } catch (e) {
      return String(dateStr);
    }
  }

  /**
   * Format a time string (HH:MM) to 12-hour format.
   * @param {string} timeStr - 'HH:MM'
   * @returns {string}
   */
  function formatTime(timeStr) {
    if (!timeStr) return '—';
    const parts = timeStr.split(':');
    if (parts.length < 2) return timeStr;
    let h   = parseInt(parts[0], 10);
    const m = parts[1];
    const ampm = h >= 12 ? 'PM' : 'AM';
    h = h % 12 || 12;
    return h + ':' + m + ' ' + ampm;
  }

  // ── ID Generation ────────────────────────────────────────────
  /**
   * Generate a sequential ID based on existing records.
   * @param {string} prefix - e.g. 'INC', 'REL', 'ANN'
   * @param {Array}  existingItems - existing records with .id field
   * @returns {string} e.g. 'INC-011'
   */
  function generateId(prefix, existingItems) {
    existingItems = existingItems || [];
    let maxNum = existingItems.reduce(function (max, item) {
      if (!item.id) return max;
      const parts = String(item.id).split('-');
      const num   = parseInt(parts[parts.length - 1], 10);
      return isNaN(num) ? max : Math.max(max, num);
    }, 0);
    return prefix + '-' + String(maxNum + 1).padStart(3, '0');
  }

  // ── LocalStorage CRUD ─────────────────────────────────────────

  /**
   * Read an array from localStorage.
   * @param {string} key
   * @returns {Array}
   */
  function getData(key) {
    try {
      const raw = localStorage.getItem(key);
      if (!raw) return [];
      const parsed = JSON.parse(raw);
      return Array.isArray(parsed) ? parsed : [];
    } catch (e) {
      console.warn('[App] getData failed for key:', key, e);
      return [];
    }
  }

  /**
   * Write data to localStorage.
   * @param {string} key
   * @param {*}      value
   * @returns {boolean}
   */
  function setData(key, value) {
    try {
      localStorage.setItem(key, JSON.stringify(value));
      return true;
    } catch (e) {
      console.error('[App] setData failed for key:', key, e);
      return false;
    }
  }

  // ── Sidebar ──────────────────────────────────────────────────
  /**
   * Initialize sidebar toggle behavior.
   * Wires up the hamburger button, overlay, and collapse state.
   */
  function initSidebar() {
    const hamburger = document.getElementById('sidebarToggle');
    const sidebar   = document.getElementById('sidebar');
    const overlay   = document.getElementById('sidebarOverlay');

    if (!hamburger || !sidebar) return;

    // Restore collapsed state from session storage
    const isCollapsed = sessionStorage.getItem('odmis_sidebar_collapsed') === 'true';
    if (isCollapsed) {
      document.body.classList.add('sidebar-collapsed');
    }

    hamburger.addEventListener('click', function () {
      const isMobile = window.innerWidth < 992;

      if (isMobile) {
        // On mobile: slide sidebar in/out with overlay
        sidebar.classList.toggle('mobile-open');
        if (overlay) overlay.classList.toggle('show');
      } else {
        // On desktop: collapse/expand icon-only mode
        document.body.classList.toggle('sidebar-collapsed');
        const collapsed = document.body.classList.contains('sidebar-collapsed');
        sessionStorage.setItem('odmis_sidebar_collapsed', String(collapsed));
      }
    });

    // Close sidebar on overlay click (mobile)
    if (overlay) {
      overlay.addEventListener('click', function () {
        sidebar.classList.remove('mobile-open');
        overlay.classList.remove('show');
      });
    }

    // Close mobile sidebar on nav link click
    const navLinks = sidebar.querySelectorAll('.sidebar-nav-link');
    navLinks.forEach(function (link) {
      link.addEventListener('click', function () {
        if (window.innerWidth < 992) {
          sidebar.classList.remove('mobile-open');
          if (overlay) overlay.classList.remove('show');
        }
      });
    });
  }

  // ── Mobile Guard ─────────────────────────────────────────────
  /**
   * Show a full-screen overlay when the viewport is too narrow for admin pages.
   * @param {boolean} adminPage - Show guard only when true (admin pages).
   * @param {number}  minWidth  - Minimum required width in px (default 1366).
   */
  function initMobileGuard(adminPage, minWidth) {
    adminPage = (adminPage === undefined) ? true : adminPage;
    minWidth  = minWidth || 1366;

    if (!adminPage) return;

    const guard = document.getElementById('mobileGuard');
    if (!guard) return;

    function checkWidth() {
      if (window.innerWidth < minWidth) {
        guard.classList.add('show');
      } else {
        guard.classList.remove('show');
      }
    }

    checkWidth();
    window.addEventListener('resize', _debounce(checkWidth, 150));
  }

  // ── Navbar ───────────────────────────────────────────────────
  /**
   * Populate navbar with the current user's name and notification count.
   */
  function initNavbar() {
    // Populate user display name
    const session = (typeof Auth !== 'undefined') ? Auth.getSession() : null;
    if (!session) return;

    const displayName  = session.fullName || session.username || 'User';
    const initials     = _getInitials(displayName);

    // Navbar avatar
    const navAvatar = document.getElementById('navbarAvatar');
    if (navAvatar) navAvatar.textContent = initials;

    // Navbar username label
    const navUsername = document.getElementById('navbarUsername');
    if (navUsername) navUsername.textContent = displayName;

    // Sidebar user info
    const sidebarAvatar = document.getElementById('sidebarUserAvatar');
    if (sidebarAvatar) sidebarAvatar.textContent = initials;

    const sidebarName = document.getElementById('sidebarUserName');
    if (sidebarName) sidebarName.textContent = displayName;

    const sidebarRole = document.getElementById('sidebarUserRole');
    if (sidebarRole) {
      sidebarRole.textContent = session.role === 'admin' ? 'Administrator' : 'Registered User';
    }

    // Notification badge (count active incidents + active alerts)
    _updateNotificationCount();
  }

  function _updateNotificationCount() {
    const badge = document.getElementById('notificationCount');
    if (!badge) return;

    const incidents = getData(KEYS.INCIDENTS);
    const alerts    = getData(KEYS.ALERTS);

    const activeIncidents = incidents.filter(function (i) { return i.status === 'Active'; }).length;
    const activeAlerts    = alerts.filter(function (a) { return a.status === 'Active'; }).length;
    const total           = activeIncidents + activeAlerts;

    if (total > 0) {
      badge.textContent = total > 99 ? '99+' : String(total);
      badge.style.display = '';
    } else {
      badge.style.display = 'none';
    }
  }

  function _getInitials(name) {
    if (!name) return '?';
    const parts = name.trim().split(/\s+/);
    if (parts.length === 1) return parts[0].charAt(0).toUpperCase();
    return (parts[0].charAt(0) + parts[parts.length - 1].charAt(0)).toUpperCase();
  }

  // ── Confirm Modal ────────────────────────────────────────────
  /**
   * Show a generic confirmation modal.
   * @param {string}   message   - Confirmation message body.
   * @param {Function} onConfirm - Callback invoked when the user clicks "Confirm".
   * @param {object}   options   - Optional: { title, confirmLabel, danger }
   */
  function showConfirm(message, onConfirm, options) {
    options = options || {};
    const title        = options.title        || 'Confirm Action';
    const confirmLabel = options.confirmLabel || 'Confirm';
    const isDanger     = options.danger !== false; // default true

    let modal = document.getElementById('confirmModal');

    // Create modal if it doesn't exist in the DOM
    if (!modal) {
      modal = document.createElement('div');
      modal.id        = 'confirmModal';
      modal.className = 'modal fade';
      modal.setAttribute('tabindex', '-1');
      modal.setAttribute('aria-modal', 'true');
      modal.setAttribute('role', 'dialog');
      modal.innerHTML =
        '<div class="modal-dialog modal-dialog-centered">' +
          '<div class="modal-content">' +
            '<div class="modal-header modal-danger">' +
              '<h5 class="modal-title" id="confirmModalTitle"></h5>' +
              '<button type="button" class="btn-close" data-bs-dismiss="modal"></button>' +
            '</div>' +
            '<div class="modal-body">' +
              '<p class="confirm-message" id="confirmModalMessage"></p>' +
            '</div>' +
            '<div class="modal-footer">' +
              '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>' +
              '<button type="button" class="btn btn-danger" id="confirmModalBtn">Confirm</button>' +
            '</div>' +
          '</div>' +
        '</div>';
      document.body.appendChild(modal);
    }

    // Update content
    const titleEl   = modal.querySelector('#confirmModalTitle');
    const msgEl     = modal.querySelector('#confirmModalMessage');
    const confirmBtn= modal.querySelector('#confirmModalBtn');
    const headerEl  = modal.querySelector('.modal-header');

    if (titleEl)   titleEl.textContent   = title;
    if (msgEl)     msgEl.textContent     = message;
    if (confirmBtn) confirmBtn.textContent = confirmLabel;

    // Style header
    if (headerEl) {
      headerEl.className = 'modal-header ' + (isDanger ? 'modal-danger' : '');
    }

    // Remove old listener and add fresh one
    const newBtn = confirmBtn.cloneNode(true);
    confirmBtn.parentNode.replaceChild(newBtn, confirmBtn);
    newBtn.textContent = confirmLabel;
    newBtn.className   = 'btn ' + (isDanger ? 'btn-danger' : 'btn-primary');

    if (typeof bootstrap !== 'undefined') {
      const bsModal = new bootstrap.Modal(modal);

      newBtn.addEventListener('click', function () {
        bsModal.hide();
        if (typeof onConfirm === 'function') onConfirm();
      });

      bsModal.show();
    } else {
      // Fallback if Bootstrap JS is not loaded
      if (typeof onConfirm === 'function' && window.confirm(message)) {
        onConfirm();
      }
    }
  }

  // ── Active Nav Highlight ─────────────────────────────────────
  /**
   * Mark the correct sidebar nav link as active.
   * @param {string} page - Identifier matching the data-page attribute on nav links,
   *                        OR a filename substring (e.g. 'dashboard', 'incidents').
   */
  function setActiveNav(page) {
    const links = document.querySelectorAll('.sidebar-nav-link[data-page]');
    links.forEach(function (link) {
      link.classList.remove('active');
      if (link.getAttribute('data-page') === page) {
        link.classList.add('active');
      }
    });

    // Fallback: try to match by href
    if (!links.length) {
      const allLinks = document.querySelectorAll('.sidebar-nav-link');
      allLinks.forEach(function (link) {
        link.classList.remove('active');
        const href = (link.getAttribute('href') || '').toLowerCase();
        if (href && href.includes(page.toLowerCase())) {
          link.classList.add('active');
        }
      });
    }
  }

  /**
   * Auto-detect and set the active nav link based on the current URL.
   */
  function autoSetActiveNav() {
    const path = window.location.pathname.replace(/\\/g, '/').toLowerCase();

    const links = document.querySelectorAll('.sidebar-nav-link');
    links.forEach(function (link) {
      link.classList.remove('active');
      const href = (link.getAttribute('href') || '').replace(/\\/g, '/').toLowerCase();
      if (!href || href === '#') return;

      // Extract the filename from the href
      const hrefFile = href.split('/').pop().replace('.html', '');
      if (hrefFile && path.includes(hrefFile)) {
        link.classList.add('active');
      }
    });
  }

  // ── Logout Modal ─────────────────────────────────────────────
  /**
   * Wire up logout buttons/links to show a confirmation before logging out.
   */
  function initLogout() {
    const logoutTriggers = document.querySelectorAll('[data-action="logout"], #logoutBtn, #logoutLink, .logout-trigger');

    logoutTriggers.forEach(function (el) {
      el.addEventListener('click', function (e) {
        e.preventDefault();
        showConfirm(
          'Are you sure you want to log out of ODMIS?',
          function () {
            if (typeof Auth !== 'undefined') {
              Auth.logout();
            } else {
              localStorage.removeItem('odmis_session');
              window.location.href = '../login.html';
            }
          },
          {
            title        : 'Confirm Logout',
            confirmLabel : 'Yes, Logout',
            danger       : true
          }
        );
      });
    });
  }

  // ── Severity Helpers ─────────────────────────────────────────
  /**
   * Return a severity badge HTML string.
   * @param {string} severity - 'Low' | 'Moderate' | 'High' | 'Critical'
   * @returns {string} HTML
   */
  function severityBadge(severity) {
    const cls = {
      'Low'      : 'badge-severity-low',
      'Moderate' : 'badge-severity-moderate',
      'High'     : 'badge-severity-high',
      'Critical' : 'badge-severity-critical'
    };
    const icon = {
      'Low'      : 'bi-arrow-down-circle',
      'Moderate' : 'bi-dash-circle',
      'High'     : 'bi-exclamation-circle',
      'Critical' : 'bi-exclamation-octagon-fill'
    };
    const c = cls[severity]  || 'badge-severity-low';
    const i = icon[severity] || 'bi-circle';
    return '<span class="badge-severity ' + c + '"><i class="bi ' + i + '"></i>' + _escapeHtml(severity || '—') + '</span>';
  }

  /**
   * Return an incident/relief status badge HTML string.
   * @param {string} status
   * @returns {string} HTML
   */
  function statusBadge(status) {
    const cls = {
      'Active'      : 'badge-status-active',
      'Resolved'    : 'badge-status-resolved',
      'Pending'     : 'badge-status-pending',
      'In Progress' : 'badge-status-in-progress',
      'Completed'   : 'badge-status-completed',
      'Inactive'    : 'badge-status-inactive'
    };
    const c = cls[status] || 'badge-status-resolved';
    return '<span class="badge-status ' + c + '">' + _escapeHtml(status || '—') + '</span>';
  }

  /**
   * Return a disaster type badge HTML string.
   * @param {string} type
   * @returns {string} HTML
   */
  function typeBadge(type) {
    const icons = {
      'Flood'     : 'bi-water',
      'Typhoon'   : 'bi-wind',
      'Earthquake': 'bi-activity',
      'Fire'      : 'bi-fire',
      'Landslide' : 'bi-layers'
    };
    const icon = icons[type] || 'bi-exclamation-triangle';
    return '<span class="badge-type"><i class="bi ' + icon + ' me-1"></i>' + _escapeHtml(type || '—') + '</span>';
  }

  // ── Capacity Color Helper ─────────────────────────────────────
  /**
   * Return CSS class for a capacity bar fill based on percentage.
   * @param {number} occupied
   * @param {number} capacity
   * @returns {string} CSS class name
   */
  function capacityClass(occupied, capacity) {
    if (!capacity || capacity === 0) return 'low';
    const pct = (occupied / capacity) * 100;
    if (pct >= 95) return 'full';
    if (pct >= 75) return 'high';
    if (pct >= 50) return 'medium';
    return 'low';
  }

  // ── Pagination Helper ─────────────────────────────────────────
  /**
   * Paginate an array.
   * @param {Array}  items    - Full dataset
   * @param {number} page     - Current page (1-based)
   * @param {number} perPage  - Items per page
   * @returns {{ items: Array, total: number, pages: number, current: number }}
   */
  function paginate(items, page, perPage) {
    page    = Math.max(1, page    || 1);
    perPage = Math.max(1, perPage || 10);
    const total = items.length;
    const pages = Math.ceil(total / perPage);
    const start = (page - 1) * perPage;
    return {
      items   : items.slice(start, start + perPage),
      total   : total,
      pages   : pages,
      current : Math.min(page, pages || 1)
    };
  }

  // ── Filter / Search Helper ────────────────────────────────────
  /**
   * Filter an array of objects by a search string across specified fields.
   * @param {Array}    items   - Dataset
   * @param {string}   query   - Search string
   * @param {string[]} fields  - Fields to search in
   * @returns {Array}
   */
  function filterBySearch(items, query, fields) {
    if (!query || !query.trim()) return items;
    const q = query.trim().toLowerCase();
    return items.filter(function (item) {
      return fields.some(function (field) {
        const val = item[field];
        return val && String(val).toLowerCase().includes(q);
      });
    });
  }

  // ── Debounce ──────────────────────────────────────────────────
  function _debounce(fn, delay) {
    let timer;
    return function () {
      const ctx  = this;
      const args = arguments;
      clearTimeout(timer);
      timer = setTimeout(function () { fn.apply(ctx, args); }, delay);
    };
  }

  // ── Form Helpers ─────────────────────────────────────────────
  /**
   * Read all named form inputs into a plain object.
   * @param {HTMLFormElement} form
   * @returns {object}
   */
  function getFormData(form) {
    const data = {};
    const elements = form.querySelectorAll('input, select, textarea');
    elements.forEach(function (el) {
      if (!el.name) return;
      if (el.type === 'checkbox') {
        data[el.name] = el.checked;
      } else if (el.type === 'radio') {
        if (el.checked) data[el.name] = el.value;
      } else {
        data[el.name] = el.value;
      }
    });
    return data;
  }

  /**
   * Show or clear Bootstrap validation state on a field.
   * @param {HTMLElement} field
   * @param {string|null} error - error message or null to clear
   */
  function setFieldError(field, error) {
    if (!field) return;
    const feedback = field.parentNode.querySelector('.invalid-feedback');
    if (error) {
      field.classList.add('is-invalid');
      field.classList.remove('is-valid');
      if (feedback) feedback.textContent = error;
    } else {
      field.classList.remove('is-invalid');
      field.classList.add('is-valid');
      if (feedback) feedback.textContent = '';
    }
  }

  /**
   * Clear all validation states on a form.
   * @param {HTMLFormElement} form
   */
  function clearFormErrors(form) {
    if (!form) return;
    form.querySelectorAll('.is-invalid, .is-valid').forEach(function (el) {
      el.classList.remove('is-invalid', 'is-valid');
    });
    form.querySelectorAll('.invalid-feedback').forEach(function (el) {
      el.textContent = '';
    });
  }

  // ── DataTable Init Helper ─────────────────────────────────────
  /**
   * Initialize a jQuery DataTable with ODMIS standard options.
   * @param {string} selector - CSS selector for the <table>
   * @param {object} options  - Additional DataTables options to merge
   * @returns {object} DataTable instance (or null if unavailable)
   */
  function initDataTable(selector, options) {
    if (typeof $ === 'undefined' || !$.fn || !$.fn.DataTable) return null;
    const el = $(selector);
    if (!el.length) return null;

    // Destroy existing instance to avoid re-init errors
    if ($.fn.DataTable.isDataTable(selector)) {
      el.DataTable().destroy();
    }

    const defaults = {
      pageLength    : 10,
      lengthMenu    : [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'All']],
      language      : {
        search       : '',
        searchPlaceholder: 'Search...',
        lengthMenu   : 'Show _MENU_ entries',
        info         : 'Showing _START_ to _END_ of _TOTAL_ records',
        emptyTable   : 'No records found.',
        zeroRecords  : 'No matching records found.'
      },
      responsive    : true,
      dom           : "<'row align-items-center mb-2'<'col-sm-6'l><'col-sm-6 text-end'f>>" +
                      "<'row'<'col-12'tr>>" +
                      "<'row align-items-center mt-2'<'col-sm-6'i><'col-sm-6 text-end'p>>"
    };

    return el.DataTable(Object.assign({}, defaults, options || {}));
  }

  // ── Print Helper ──────────────────────────────────────────────
  /**
   * Trigger browser print dialog.
   * @param {string} title - Injected into .print-title element if present.
   */
  function printPage(title) {
    if (title) {
      const titleEl = document.querySelector('.print-title');
      if (titleEl) titleEl.textContent = title;
    }
    // Set current date in print header
    const dateEl = document.querySelector('.print-date');
    if (dateEl) dateEl.textContent = 'Printed: ' + formatDate(new Date().toISOString(), 'datetime');

    window.print();
  }

  // ── Full-page init ────────────────────────────────────────────
  /**
   * Run all standard page initializations.
   * Call this on DOMContentLoaded for pages that use the full layout.
   * @param {object} config
   * @param {string}  config.page       - Page identifier for active nav highlighting
   * @param {boolean} config.adminPage  - Whether this is an admin page (enables mobile guard)
   * @param {boolean} config.requireAdmin - Enforce admin role check
   * @param {boolean} config.requireUser  - Enforce user role check
   * @param {number}  config.minWidth   - Mobile guard breakpoint (default 1366)
   */
  function initPage(config) {
    config = config || {};

    // Auth checks
    if (config.requireAdmin && typeof Auth !== 'undefined') {
      if (!Auth.requireAdmin()) return;
    } else if (config.requireUser && typeof Auth !== 'undefined') {
      if (!Auth.requireUser()) return;
    } else if (typeof Auth !== 'undefined') {
      if (!Auth.requireAuth()) return;
    }

    initSidebar();
    initNavbar();
    initLogout();

    if (config.adminPage !== false) {
      initMobileGuard(true, config.minWidth);
    }

    if (config.page) {
      setActiveNav(config.page);
    } else {
      autoSetActiveNav();
    }
  }

  // ── Expose public API ─────────────────────────────────────────
  return {
    KEYS            : KEYS,
    showToast       : showToast,
    formatDate      : formatDate,
    formatTime      : formatTime,
    generateId      : generateId,
    getData         : getData,
    setData         : setData,
    initSidebar     : initSidebar,
    initMobileGuard : initMobileGuard,
    initNavbar      : initNavbar,
    initLogout      : initLogout,
    initPage        : initPage,
    showConfirm     : showConfirm,
    setActiveNav    : setActiveNav,
    autoSetActiveNav: autoSetActiveNav,
    severityBadge   : severityBadge,
    statusBadge     : statusBadge,
    typeBadge       : typeBadge,
    capacityClass   : capacityClass,
    paginate        : paginate,
    filterBySearch  : filterBySearch,
    getFormData     : getFormData,
    setFieldError   : setFieldError,
    clearFormErrors : clearFormErrors,
    initDataTable   : initDataTable,
    printPage       : printPage,
    escapeHtml      : _escapeHtml
  };
})();
