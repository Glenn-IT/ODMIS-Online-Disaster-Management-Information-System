/* ============================================================
   ODMIS — API Client (api.js)
   Centralized fetch wrapper: base URL, JWT injection, error handling.
   Load this BEFORE auth.js and all page scripts.
   ============================================================ */
const ApiClient = (function () {
  'use strict';

  const TOKEN_KEY = 'odmis_jwt';

  function getBase() {
    if (window.API_BASE) return window.API_BASE;
    const p = window.location.pathname;
    const match = p.match(/^(.*\/ODMIS-Online-Disaster-Management-Information-System)/i);
    if (match) return match[1] + '/api';
    if (p.includes('/admin/') || p.includes('/user/')) return '../api';
    return 'api';
  }

  function getToken()   { return localStorage.getItem(TOKEN_KEY); }
  function setToken(t)  { localStorage.setItem(TOKEN_KEY, t); }
  function clearToken() { localStorage.removeItem(TOKEN_KEY); }

  async function request(method, path, body, isFormData) {
    const headers = {};
    const token = getToken();
    if (token) headers['Authorization'] = 'Bearer ' + token;
    if (!isFormData && body) headers['Content-Type'] = 'application/json';

    const opts = { method: method, headers: headers };
    if (body) opts.body = isFormData ? body : JSON.stringify(body);

    const baseUrl = getBase();
    const cleanPath = path.startsWith('/') ? path : '/' + path;

    let res;
    try {
      res = await fetch(baseUrl + cleanPath, opts);
    } catch (_) {
      const e = new Error('Network error — is the server running?');
      e.status = 0;
      throw e;
    }

    let data = {};
    try { data = await res.json(); } catch (_) {}

    if (!res.ok) {
      let msg = data.message || ('Request failed (' + res.status + ')');
      if (data.errors) {
        if (Array.isArray(data.errors) && data.errors.length > 0) {
          const errList = data.errors.join(', ');
          if (!msg.includes(errList)) {
            msg += ' (' + errList + ')';
          }
        } else if (typeof data.errors === 'object' && data.errors !== null) {
          const detailMsgs = Object.values(data.errors).join(', ');
          if (detailMsgs && !msg.includes(detailMsgs)) {
            msg += ': ' + detailMsgs;
          }
        }
      }
      const e = new Error(msg);
      e.status = res.status;
      e.errors = data.errors || null;
      throw e;
    }
    return data;
  }

  // Download a file via fetch (PDF/CSV export) with auth header
  async function download(path) {
    const token = getToken();
    const baseUrl = getBase();
    const cleanPath = path.startsWith('/') ? path : '/' + path;
    const res   = await fetch(baseUrl + cleanPath, {
      headers: token ? { 'Authorization': 'Bearer ' + token } : {}
    });
    if (!res.ok) throw new Error('Download failed (' + res.status + ')');

    const blob = await res.blob();
    const cd   = res.headers.get('Content-Disposition') || '';
    const match = cd.match(/filename="?([^";]+)"?/);
    const name  = match ? match[1] : 'odmis_export_' + Date.now();

    const url = URL.createObjectURL(blob);
    const a   = document.createElement('a');
    a.href     = url;
    a.download = name;
    document.body.appendChild(a);
    a.click();
    a.remove();
    URL.revokeObjectURL(url);
  }

  return {
    BASE      : BASE,
    TOKEN_KEY : TOKEN_KEY,
    getToken  : getToken,
    setToken  : setToken,
    clearToken: clearToken,
    get    : function (path)       { return request('GET',    path); },
    post   : function (path, body) { return request('POST',   path, body); },
    put    : function (path, body) { return request('PUT',    path, body); },
    patch  : function (path, body) { return request('PATCH',  path, body); },
    del    : function (path)       { return request('DELETE', path); },
    upload : function (path, fd)   { return request('POST',   path, fd, true); },
    download: download
  };
})();
