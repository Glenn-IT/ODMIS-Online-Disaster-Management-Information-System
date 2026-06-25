/* ============================================================
   ODMIS - Online Disaster Management Information System
   Authentication Module (auth.js)
   Handles login, logout, session management, and access control
   ============================================================ */

const Auth = (function () {
  'use strict';

  // ── Constants ────────────────────────────────────────────────
  const SESSION_KEY = 'odmis_session';
  const USERS_KEY   = 'odmis_users';

  // ── Private helpers ──────────────────────────────────────────

  /**
   * Detect how many levels deep the current page is relative to root.
   * Root pages (login.html) → depth 0
   * admin/*, user/* pages   → depth 1
   * admin/sub/*              → depth 2
   * Returns the relative path prefix needed to reach root.
   */
  function _getRootPrefix() {
    const path  = window.location.pathname;
    // Count directory separators after the first slash
    const parts = path.replace(/\\/g, '/').split('/').filter(Boolean);
    // parts example: ['ODMIS-Online-Disaster-Management-Information-System','admin','dashboard.html']
    // We want to know how many directories are between the file and the web root.
    // The last part is always the filename; everything before it is directories.
    const depth = parts.length - 1; // number of directory levels from server root
    // Determine if there is a project sub-folder (e.g. ODMIS-Online-…) at position 0
    // The project root is one level below the server root when hosted under htdocs/ODMIS-…
    // Within the project:
    //   login.html              → parts.length from project root = 0 dirs  → prefix = ''
    //   admin/dashboard.html    → parts.length from project root = 1 dir   → prefix = '../'
    // We resolve relative to the page's own directory using a link element trick.
    const link = document.createElement('a');
    link.href  = 'odmis-root-marker.txt';          // arbitrary relative URL
    const pageDir = link.href.replace(/\/[^/]*$/, '');  // directory of current page

    // Build prefix by counting slashes from project root marker in the URL
    // Simpler and more reliable approach: detect sub-directory by searching for known dirs
    const lowerPath = path.toLowerCase();
    if (lowerPath.includes('/admin/') || lowerPath.includes('\\admin\\')) return '../';
    if (lowerPath.includes('/user/')  || lowerPath.includes('\\user\\'))  return '../';
    return '';  // root-level page
  }

  /** Safely read JSON from localStorage. Returns null on failure. */
  function _read(key) {
    try {
      const raw = localStorage.getItem(key);
      return raw ? JSON.parse(raw) : null;
    } catch (e) {
      return null;
    }
  }

  /** Safely write JSON to localStorage. */
  function _write(key, value) {
    try {
      localStorage.setItem(key, JSON.stringify(value));
      return true;
    } catch (e) {
      console.error('[Auth] Failed to write key:', key, e);
      return false;
    }
  }

  /** Navigate relative to the project root. */
  function _goTo(relativePath) {
    const prefix = _getRootPrefix();
    window.location.href = prefix + relativePath;
  }

  // ── Public API ───────────────────────────────────────────────

  /**
   * Attempt to log in a user.
   * @param {string} username
   * @param {string} password
   * @param {string} role   - 'admin' | 'user' (optional filter; '' means any)
   * @returns {{ success: boolean, user: object|null, message: string }}
   */
  function login(username, password, role) {
    if (!username || !password) {
      return { success: false, user: null, message: 'Username and password are required.' };
    }

    const users = _read(USERS_KEY) || [];
    const trimUser = username.trim().toLowerCase();
    const user = users.find(u => u.username.toLowerCase() === trimUser);

    if (!user) {
      return { success: false, user: null, message: 'Invalid username or password.' };
    }

    if (user.password !== password) {
      return { success: false, user: null, message: 'Invalid username or password.' };
    }

    if (user.status !== 'active') {
      return { success: false, user: null, message: 'Your account has been deactivated. Please contact the administrator.' };
    }

    // Role filter: if a specific role is required, enforce it
    if (role && role !== '' && user.role !== role) {
      return {
        success : false,
        user    : null,
        message : role === 'admin'
          ? 'Access denied. Administrator privileges are required.'
          : 'Access denied. This login is for registered users only.'
      };
    }

    // Build a safe session object (exclude password)
    const session = {
      id            : user.id,
      username      : user.username,
      email         : user.email,
      role          : user.role,
      fullName      : user.fullName,
      contactNumber : user.contactNumber,
      address       : user.address,
      loginTime     : new Date().toISOString()
    };

    _write(SESSION_KEY, session);
    return { success: true, user: session, message: 'Login successful.' };
  }

  /**
   * Log out the current user and redirect to the login page.
   */
  function logout() {
    localStorage.removeItem(SESSION_KEY);
    // Determine correct path from any page depth
    _goTo('login.html');
  }

  /**
   * Get the current session object, or null if not logged in.
   * @returns {object|null}
   */
  function getSession() {
    return _read(SESSION_KEY);
  }

  /**
   * Returns true if a valid session exists.
   * @returns {boolean}
   */
  function isAuthenticated() {
    const session = getSession();
    return session !== null && typeof session === 'object' && !!session.username;
  }

  /**
   * Returns true if the current user is an admin.
   * @returns {boolean}
   */
  function isAdmin() {
    const session = getSession();
    return isAuthenticated() && session.role === 'admin';
  }

  /**
   * Returns true if the current user is a regular user.
   * @returns {boolean}
   */
  function isUser() {
    const session = getSession();
    return isAuthenticated() && session.role === 'user';
  }

  /**
   * Redirect to login if not authenticated.
   * Call this at the top of any protected page.
   */
  function requireAuth() {
    if (!isAuthenticated()) {
      _goTo('login.html');
      return false;
    }
    return true;
  }

  /**
   * Redirect if the current user is not an admin.
   * Call this at the top of admin pages.
   */
  function requireAdmin() {
    if (!isAuthenticated()) {
      _goTo('login.html');
      return false;
    }
    if (!isAdmin()) {
      // Redirect to user dashboard instead
      _goTo('user/dashboard.html');
      return false;
    }
    return true;
  }

  /**
   * Redirect if the current user is not a regular user.
   * Call this at the top of user pages.
   */
  function requireUser() {
    if (!isAuthenticated()) {
      _goTo('login.html');
      return false;
    }
    if (!isUser()) {
      // Admins trying to access user pages → send to admin dashboard
      if (isAdmin()) {
        _goTo('admin/dashboard.html');
      } else {
        _goTo('login.html');
      }
      return false;
    }
    return true;
  }

  /**
   * Register a new user.
   * @param {object} userData - { username, email, password, fullName, contactNumber, dateOfBirth, address, securityQuestion, securityAnswer }
   * @returns {{ success: boolean, message: string, user: object|null }}
   */
  function register(userData) {
    if (!userData || !userData.username || !userData.password || !userData.email) {
      return { success: false, message: 'Username, email, and password are required.', user: null };
    }

    const users    = _read(USERS_KEY) || [];
    const trimUser = userData.username.trim().toLowerCase();
    const trimEmail= userData.email.trim().toLowerCase();

    // Check for duplicate username
    if (users.some(u => u.username.toLowerCase() === trimUser)) {
      return { success: false, message: 'Username is already taken. Please choose another.', user: null };
    }

    // Check for duplicate email
    if (users.some(u => u.email.toLowerCase() === trimEmail)) {
      return { success: false, message: 'An account with this email already exists.', user: null };
    }

    // Validate password length
    if (userData.password.length < 6) {
      return { success: false, message: 'Password must be at least 6 characters long.', user: null };
    }

    // Build new user record
    const newUser = {
      id              : 'USR-' + String(users.length + 1).padStart(3, '0'),
      username        : userData.username.trim(),
      email           : userData.email.trim(),
      password        : userData.password,
      role            : 'user',                  // all registrations default to 'user'
      fullName        : (userData.fullName   || '').trim(),
      contactNumber   : (userData.contactNumber || '').trim(),
      dateOfBirth     : userData.dateOfBirth || '',
      address         : (userData.address   || '').trim(),
      status          : 'active',
      securityQuestion: (userData.securityQuestion || '').trim(),
      securityAnswer  : (userData.securityAnswer   || '').trim(),
      createdAt       : new Date().toISOString()
    };

    users.push(newUser);
    _write(USERS_KEY, users);

    // Return a safe copy without the password
    const safeUser = Object.assign({}, newUser);
    delete safeUser.password;
    delete safeUser.securityAnswer;

    return { success: true, message: 'Registration successful. You may now log in.', user: safeUser };
  }

  /**
   * Look up a user by their username.
   * @param {string} username
   * @returns {object|null} user record or null
   */
  function getUserByUsername(username) {
    if (!username) return null;
    const users = _read(USERS_KEY) || [];
    const found = users.find(u => u.username.toLowerCase() === username.trim().toLowerCase());
    return found || null;
  }

  /**
   * Update a user's password (used by forgot-password flow).
   * @param {string} username
   * @param {string} newPassword
   * @returns {{ success: boolean, message: string }}
   */
  function updatePassword(username, newPassword) {
    if (!username || !newPassword) {
      return { success: false, message: 'Username and new password are required.' };
    }

    if (newPassword.length < 6) {
      return { success: false, message: 'Password must be at least 6 characters long.' };
    }

    const users    = _read(USERS_KEY) || [];
    const trimUser = username.trim().toLowerCase();
    const index    = users.findIndex(u => u.username.toLowerCase() === trimUser);

    if (index === -1) {
      return { success: false, message: 'User not found.' };
    }

    users[index].password = newPassword;
    _write(USERS_KEY, users);
    return { success: true, message: 'Password updated successfully.' };
  }

  /**
   * Update the current session data (e.g., after profile changes).
   * @param {object} updates - partial session fields to merge
   */
  function updateSession(updates) {
    const session = getSession();
    if (!session) return;
    const merged = Object.assign({}, session, updates);
    _write(SESSION_KEY, merged);
  }

  /**
   * Update a user's profile information.
   * @param {string} username
   * @param {object} profileData - fields to update (excluding password and role)
   * @returns {{ success: boolean, message: string }}
   */
  function updateProfile(username, profileData) {
    if (!username) return { success: false, message: 'Username is required.' };

    const users    = _read(USERS_KEY) || [];
    const trimUser = username.trim().toLowerCase();
    const index    = users.findIndex(u => u.username.toLowerCase() === trimUser);

    if (index === -1) return { success: false, message: 'User not found.' };

    // Allowed fields to update (cannot change username, role, id, password via this method)
    const allowed = ['fullName', 'email', 'contactNumber', 'dateOfBirth', 'address', 'securityQuestion', 'securityAnswer', 'status'];
    allowed.forEach(field => {
      if (profileData.hasOwnProperty(field)) {
        users[index][field] = profileData[field];
      }
    });

    _write(USERS_KEY, users);

    // Refresh session if the update is for the currently logged-in user
    const session = getSession();
    if (session && session.username.toLowerCase() === trimUser) {
      updateSession({
        fullName      : users[index].fullName,
        email         : users[index].email,
        contactNumber : users[index].contactNumber,
        address       : users[index].address
      });
    }

    return { success: true, message: 'Profile updated successfully.' };
  }

  /**
   * Verify a user's security answer (for forgot-password flow).
   * @param {string} username
   * @param {string} answer
   * @returns {{ success: boolean, message: string }}
   */
  function verifySecurityAnswer(username, answer) {
    const user = getUserByUsername(username);
    if (!user) return { success: false, message: 'User not found.' };
    if (!user.securityAnswer) return { success: false, message: 'No security answer set for this account.' };

    const match = user.securityAnswer.trim().toLowerCase() === (answer || '').trim().toLowerCase();
    return match
      ? { success: true, message: 'Security answer verified.' }
      : { success: false, message: 'Security answer does not match.' };
  }

  // ── Expose public interface ──────────────────────────────────
  return {
    SESSION_KEY          : SESSION_KEY,
    USERS_KEY            : USERS_KEY,
    login                : login,
    logout               : logout,
    getSession           : getSession,
    isAuthenticated      : isAuthenticated,
    isAdmin              : isAdmin,
    isUser               : isUser,
    requireAuth          : requireAuth,
    requireAdmin         : requireAdmin,
    requireUser          : requireUser,
    register             : register,
    getUserByUsername    : getUserByUsername,
    updatePassword       : updatePassword,
    updateSession        : updateSession,
    updateProfile        : updateProfile,
    verifySecurityAnswer : verifySecurityAnswer
  };
})();
