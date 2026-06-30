<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — ODMIS</title>

  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Project stylesheet -->
  <link rel="stylesheet" href="assets/css/style.css">

  <style>
    /* ── Page background ── */
    body {
      min-height: 100vh;
      background: linear-gradient(135deg, #1a2e15 0%, #283F24 45%, #467235 100%);
      display: flex;
      flex-direction: column;
      font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    }

    /* ── Subtle government stripe at very top ── */
    .gov-stripe {
      background: linear-gradient(90deg, #c0392b 33.33%, #283F24 33.33%, #283F24 66.66%, #FFBF00 66.66%);
      height: 5px;
      width: 100%;
      flex-shrink: 0;
    }

    /* ── Decorative background pattern ── */
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background-image:
        radial-gradient(circle at 20% 30%, rgba(255,255,255,.04) 0%, transparent 50%),
        radial-gradient(circle at 80% 70%, rgba(255,255,255,.04) 0%, transparent 50%);
      pointer-events: none;
    }

    /* ── Center wrapper ── */
    .login-wrapper {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1rem;
    }

    /* ── Card ── */
    .login-card {
      width: 100%;
      max-width: 480px;
      background: #fff;
      border-radius: .75rem;
      box-shadow: 0 20px 60px rgba(0,0,0,.35), 0 4px 16px rgba(0,0,0,.2);
      overflow: hidden;
    }

    /* ── Card header ── */
    .login-header {
      background: linear-gradient(135deg, #283F24 0%, #467235 100%);
      padding: 2rem 2rem 1.5rem;
      text-align: center;
      position: relative;
    }
    .login-header::after {
      content: '';
      position: absolute;
      bottom: -1px;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, #FFBF00, #FFF78D);
    }

    .logo-icon {
      width: 70px;
      height: 70px;
      background: rgba(255,255,255,.12);
      border: 2px solid rgba(255,255,255,.25);
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-bottom: .75rem;
    }
    .logo-icon i {
      font-size: 2rem;
      color: #fff;
    }

    .login-header h1 {
      color: #fff;
      font-size: 2rem;
      font-weight: 800;
      letter-spacing: 3px;
      margin: 0 0 .2rem;
    }
    .login-header p.subtitle {
      color: rgba(255,255,255,.75);
      font-size: .78rem;
      letter-spacing: .5px;
      text-transform: uppercase;
      margin: 0 0 .5rem;
    }
    .login-header .gov-label {
      display: inline-block;
      background: rgba(192,57,43,.85);
      color: #fff;
      font-size: .68rem;
      font-weight: 600;
      letter-spacing: 1px;
      text-transform: uppercase;
      padding: .15rem .6rem;
      border-radius: 2px;
    }

    /* ── Card body ── */
    .login-body {
      padding: 1.75rem 2rem 2rem;
    }

    /* ── Tabs ── */
    .nav-tabs {
      border-bottom: 2px solid #dee2e6;
      margin-bottom: 1.5rem;
      gap: .25rem;
    }
    .nav-tabs .nav-link {
      color: #6c757d;
      font-weight: 600;
      font-size: .85rem;
      border: none;
      border-bottom: 3px solid transparent;
      border-radius: 0;
      padding: .6rem 1.25rem;
      transition: color .2s, border-color .2s;
    }
    .nav-tabs .nav-link:hover {
      color: #467235;
      border-bottom-color: rgba(26,58,107,.35);
    }
    .nav-tabs .nav-link.active {
      color: #467235;
      border-bottom-color: #467235;
      background: transparent;
    }
    .nav-tabs .nav-link i {
      margin-right: .35rem;
    }

    /* ── Form controls ── */
    .form-label {
      font-size: .82rem;
      font-weight: 600;
      color: #343a40;
      margin-bottom: .3rem;
    }
    .input-group-text {
      background: #f4f6f9;
      border-right: none;
      color: #6c757d;
    }
    .form-control {
      border-left: none;
      font-size: .875rem;
    }
    .form-control:focus {
      box-shadow: 0 0 0 .2rem rgba(70,114,53,.2);
      border-color: #467235;
    }
    .input-group:focus-within .input-group-text {
      border-color: #467235;
    }
    .btn-toggle-pw {
      background: #f4f6f9;
      border: 1px solid #ced4da;
      border-left: none;
      color: #6c757d;
      padding: .375rem .65rem;
    }
    .btn-toggle-pw:hover { color: #467235; background: #e9ecef; }

    /* ── Login button ── */
    .btn-login {
      background: linear-gradient(135deg, #283F24 0%, #467235 100%);
      border: none;
      color: #fff;
      font-weight: 700;
      font-size: .9rem;
      letter-spacing: .5px;
      padding: .65rem;
      border-radius: .4rem;
      transition: opacity .2s, transform .1s;
      width: 100%;
    }
    .btn-login:hover { opacity: .92; transform: translateY(-1px); color: #fff; }
    .btn-login:active { transform: translateY(0); }

    /* ── Misc links ── */
    .auth-links {
      font-size: .8rem;
      margin-top: .75rem;
      color: #6c757d;
      text-align: center;
    }
    .auth-links a {
      color: #467235;
      text-decoration: none;
      font-weight: 600;
    }
    .auth-links a:hover { text-decoration: underline; }

    /* ── Footer ── */
    .login-footer {
      background: #f4f6f9;
      border-top: 1px solid #dee2e6;
      padding: .75rem 1rem;
      text-align: center;
      font-size: .73rem;
      color: #6c757d;
    }

    /* ── Toast ── */
    .toast-container {
      position: fixed;
      top: 1.25rem;
      right: 1.25rem;
      z-index: 9999;
    }

    /* ── Spinner inside button ── */
    .btn-login .spinner-border { width: 1rem; height: 1rem; border-width: .15em; }
  </style>
</head>
<body>
  <div class="gov-stripe"></div>

  <div class="login-wrapper">
    <div class="login-card">

      <!-- Header -->
      <div class="login-header">
        <div class="logo-icon">
          <i class="fas fa-shield-halved"></i>
        </div>
        <h1>ODMIS</h1>
        <p class="subtitle">Online Disaster Management Information System</p>
        <span class="gov-label"><i class="fas fa-landmark me-1"></i>DRRM Office — Sto. Niño, Cagayan, Region II, 3525</span>
      </div>

      <!-- Body -->
      <div class="login-body">

        <!-- Tabs -->
        <ul class="nav nav-tabs" id="loginTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="admin-tab" data-bs-toggle="tab"
                    data-bs-target="#adminPane" type="button" role="tab"
                    aria-controls="adminPane" aria-selected="true">
              <i class="fas fa-user-shield"></i>Admin Login
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="user-tab" data-bs-toggle="tab"
                    data-bs-target="#userPane" type="button" role="tab"
                    aria-controls="userPane" aria-selected="false">
              <i class="fas fa-user"></i>User Login
            </button>
          </li>
        </ul>

        <!-- Tab content -->
        <div class="tab-content" id="loginTabContent">

          <!-- Admin Pane -->
          <div class="tab-pane fade show active" id="adminPane" role="tabpanel">
            <form id="adminForm" novalidate>
              <div class="mb-3">
                <label for="adminUsername" class="form-label">Username</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                  <input type="text" class="form-control" id="adminUsername"
                         placeholder="Enter admin username" autocomplete="username" required>
                </div>
              </div>
              <div class="mb-3">
                <label for="adminPassword" class="form-label">Password</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-lock"></i></span>
                  <input type="password" class="form-control" id="adminPassword"
                         placeholder="Enter password" autocomplete="current-password" required>
                  <button class="btn-toggle-pw" type="button" onclick="togglePw('adminPassword', this)"
                          title="Show/hide password">
                    <i class="fas fa-eye"></i>
                  </button>
                </div>
              </div>
              <div class="mb-3 text-end">
                <a href="forgot-password.php" class="auth-links" style="font-size:.8rem;">
                  <i class="fas fa-key me-1"></i>Forgot Password?
                </a>
              </div>
              <button type="submit" class="btn-login" id="adminSubmit">
                <i class="fas fa-sign-in-alt me-2"></i>Sign In as Admin
              </button>
            </form>
          </div>

          <!-- User Pane -->
          <div class="tab-pane fade" id="userPane" role="tabpanel">
            <form id="userForm" novalidate>
              <div class="mb-3">
                <label for="userUsername" class="form-label">Username</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-user"></i></span>
                  <input type="text" class="form-control" id="userUsername"
                         placeholder="Enter username" autocomplete="username" required>
                </div>
              </div>
              <div class="mb-3">
                <label for="userPassword" class="form-label">Password</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-lock"></i></span>
                  <input type="password" class="form-control" id="userPassword"
                         placeholder="Enter password" autocomplete="current-password" required>
                  <button class="btn-toggle-pw" type="button" onclick="togglePw('userPassword', this)"
                          title="Show/hide password">
                    <i class="fas fa-eye"></i>
                  </button>
                </div>
              </div>
              <div class="mb-3 text-end">
                <a href="forgot-password.php" class="auth-links" style="font-size:.8rem;">
                  <i class="fas fa-key me-1"></i>Forgot Password?
                </a>
              </div>
              <button type="submit" class="btn-login" id="userSubmit">
                <i class="fas fa-sign-in-alt me-2"></i>Sign In
              </button>
              <div class="auth-links mt-3">
                Don't have an account?
                <a href="register.php"><i class="fas fa-user-plus me-1"></i>Register</a>
              </div>
            </form>
          </div>

        </div>
      </div><!-- /.login-body -->

      <!-- Footer -->
      <div class="login-footer">
        <i class="fas fa-shield-halved me-1"></i>
        &copy; 2026 DRRM Office. All rights reserved.
        &mdash; For emergencies call <strong>911</strong>
      </div>
    </div><!-- /.login-card -->
  </div><!-- /.login-wrapper -->

  <!-- Toast container -->
  <div class="toast-container" aria-live="polite" aria-atomic="true">
    <div id="loginToast" class="toast align-items-center border-0" role="alert">
      <div class="d-flex">
        <div class="toast-body" id="toastMsg"></div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto"
                data-bs-dismiss="toast"></button>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/api.js"></script>
  <script src="assets/js/auth.js"></script>

  <script>
    // ── Redirect if already logged in ────────────────────────────
    (function () {
      const session = Auth.getSession();
      if (session && session.username) {
        window.location.href = session.role === 'admin'
          ? 'admin/dashboard.php'
          : 'user/dashboard.php';
      }
    })();

    // ── Toast helper ─────────────────────────────────────────────
    function showToast(message, type) {
      const toastEl = document.getElementById('loginToast');
      const msgEl   = document.getElementById('toastMsg');
      toastEl.className = 'toast align-items-center border-0 text-white bg-'
                        + (type === 'success' ? 'success' : 'danger');
      msgEl.innerHTML = '<i class="fas fa-' + (type === 'success' ? 'check-circle' : 'exclamation-circle') + ' me-2"></i>' + message;
      bootstrap.Toast.getOrCreateInstance(toastEl, { delay: 4000 }).show();
    }

    // ── Password visibility toggle ───────────────────────────────
    function togglePw(inputId, btn) {
      const input = document.getElementById(inputId);
      const icon  = btn.querySelector('i');
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
      }
    }

    // ── Set button loading state ─────────────────────────────────
    function setLoading(btnId, loading) {
      const btn = document.getElementById(btnId);
      if (loading) {
        btn.dataset.original = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Signing in…';
        btn.disabled = true;
      } else {
        btn.innerHTML = btn.dataset.original || btn.innerHTML;
        btn.disabled = false;
      }
    }

    // ── Handle login ─────────────────────────────────────────────
    async function handleLogin(usernameId, passwordId, btnId, expectedRole) {
      const username = document.getElementById(usernameId).value.trim();
      const password = document.getElementById(passwordId).value;

      if (!username || !password) {
        showToast('Please enter both username and password.', 'error');
        return;
      }

      setLoading(btnId, true);
      try {
        const data = await ApiClient.post('/auth/login.php', { username, password });

        // Enforce role-specific login tab
        if (expectedRole && data.role !== expectedRole) {
          const msg = expectedRole === 'admin'
            ? 'Access denied. Administrator privileges are required.'
            : 'Access denied. This login is for registered users only.';
          showToast(msg, 'error');
          return;
        }

        ApiClient.setToken(data.token);
        showToast('Login successful! Redirecting…', 'success');
        setTimeout(function () {
          window.location.href = data.role === 'admin'
            ? 'admin/dashboard.php'
            : 'user/dashboard.php';
        }, 800);
      } catch (err) {
        showToast(err.message || 'Login failed.', 'error');
      } finally {
        setLoading(btnId, false);
      }
    }

    // ── Form submit listeners ─────────────────────────────────────
    document.getElementById('adminForm').addEventListener('submit', function (e) {
      e.preventDefault();
      handleLogin('adminUsername', 'adminPassword', 'adminSubmit', 'admin');
    });

    document.getElementById('userForm').addEventListener('submit', function (e) {
      e.preventDefault();
      handleLogin('userUsername', 'userPassword', 'userSubmit', 'user');
    });

    // ── Clear error styling on tab switch ────────────────────────
    document.querySelectorAll('#loginTabs button').forEach(function (tab) {
      tab.addEventListener('shown.bs.tab', function () {
        document.querySelectorAll('.form-control').forEach(function (el) {
          el.classList.remove('is-invalid');
        });
      });
    });
  </script>
</body>
</html>
