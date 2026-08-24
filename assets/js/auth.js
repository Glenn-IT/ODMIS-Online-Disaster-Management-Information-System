/* ============================================================
   ODMIS — Authentication Module (auth.js)
   JWT-based session management. Depends on api.js.
   ============================================================ */
const Auth = (function () {
  'use strict';

  // ── Root-relative path prefix ────────────────────────────
  function _prefix() {
    const p = window.location.pathname.toLowerCase();
    if (p.includes('/admin/') || p.includes('/user/')) return '../';
    return '';
  }

  function _go(rel) { window.location.href = _prefix() + rel; }
  function _replace(rel) { window.location.replace(_prefix() + rel); }

  // ── Decode JWT payload (no sig verify — server verifies) ─
  function _decode(token) {
    try {
      const b64 = token.split('.')[1].replace(/-/g, '+').replace(/_/g, '/');
      const payload = JSON.parse(atob(b64));
      if (payload.exp && payload.exp < Math.floor(Date.now() / 1000)) return null;
      return payload;
    } catch (_) { return null; }
  }

  // ── Session object ────────────────────────────────────────
  // Returns a plain object compatible with the old session shape
  // that app.js and page scripts already expect.
  function getSession() {
    const token = ApiClient.getToken();
    if (!token) return null;
    const p = _decode(token);
    if (!p) { ApiClient.clearToken(); return null; }
    return {
      id           : p.sub,
      username     : p.username     || '',
      email        : p.email        || '',
      role         : p.role         || '',
      fullName     : p.full_name    || p.username || '',
      full_name    : p.full_name    || '',
      contactNumber: p.contact_number || '',
      address      : p.address      || ''
    };
  }

  function isAuthenticated() { return getSession() !== null; }
  function isAdmin()  { const s = getSession(); return !!s && s.role === 'admin'; }
  function isUser()   { const s = getSession(); return !!s && s.role === 'user';  }

  function requireAuth() {
    if (!isAuthenticated()) { _replace('login.php'); return false; }
    return true;
  }

  function requireAdmin() {
    if (!isAuthenticated()) { _replace('login.php'); return false; }
    if (!isAdmin())         { _replace('user/dashboard.php'); return false; }
    return true;
  }

  function requireUser() {
    if (!isAuthenticated()) { _replace('login.php'); return false; }
    if (!isUser()) {
      _replace(isAdmin() ? 'admin/dashboard.php' : 'login.php');
      return false;
    }
    return true;
  }

  function logout() {
    ApiClient.clearToken();
    try {
      localStorage.removeItem('odmis_token');
      localStorage.removeItem('odmis_session');
      sessionStorage.removeItem('odmis_token');
      sessionStorage.removeItem('odmis_session');
    } catch (_) {}
    _replace('login.php');
  }

  // Handle browser Back/Forward navigation & bfcache restoration
  window.addEventListener('pageshow', function (event) {
    const p = window.location.pathname.toLowerCase();
    if (p.includes('/admin/')) {
      if (!isAuthenticated()) {
        _replace('login.php');
      } else if (!isAdmin()) {
        _replace('user/dashboard.php');
      }
    } else if (p.includes('/user/')) {
      if (!isAuthenticated()) {
        _replace('login.php');
      } else if (!isUser()) {
        _replace('admin/dashboard.php');
      }
    } else if (p.includes('login.php')) {
      if (isAuthenticated()) {
        _replace(isAdmin() ? 'admin/dashboard.php' : 'user/dashboard.php');
      }
    }
  });

  // No-op: session comes from JWT; cannot be patched client-side
  function updateSession() {}

  return {
    getSession     : getSession,
    isAuthenticated: isAuthenticated,
    isAdmin        : isAdmin,
    isUser         : isUser,
    requireAuth    : requireAuth,
    requireAdmin   : requireAdmin,
    requireUser    : requireUser,
    logout         : logout,
    updateSession  : updateSession
  };
})();
