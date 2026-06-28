<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register — ODMIS</title>

  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Project stylesheet -->
  <link rel="stylesheet" href="assets/css/style.css">

  <style>
    body {
      min-height: 100vh;
      background: linear-gradient(135deg, #1a2e15 0%, #283F24 45%, #467235 100%);
      display: flex;
      flex-direction: column;
      font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    }

    .gov-stripe {
      background: linear-gradient(90deg, #c0392b 33.33%, #283F24 33.33%, #283F24 66.66%, #FFBF00 66.66%);
      height: 5px;
      flex-shrink: 0;
    }

    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background-image:
        radial-gradient(circle at 15% 40%, rgba(255,255,255,.04) 0%, transparent 50%),
        radial-gradient(circle at 85% 60%, rgba(255,255,255,.04) 0%, transparent 50%);
      pointer-events: none;
    }

    .register-wrapper {
      flex: 1;
      display: flex;
      align-items: flex-start;
      justify-content: center;
      padding: 2rem 1rem 3rem;
    }

    .register-card {
      width: 100%;
      max-width: 520px;
      background: #fff;
      border-radius: .75rem;
      box-shadow: 0 20px 60px rgba(0,0,0,.35), 0 4px 16px rgba(0,0,0,.2);
      overflow: hidden;
    }

    /* Header */
    .reg-header {
      background: linear-gradient(135deg, #283F24 0%, #467235 100%);
      padding: 1.75rem 2rem 1.5rem;
      text-align: center;
      position: relative;
    }
    .reg-header::after {
      content: '';
      position: absolute;
      bottom: -1px; left: 0; right: 0;
      height: 3px;
      background: linear-gradient(90deg, #FFBF00, #FFF78D);
    }
    .logo-icon {
      width: 60px; height: 60px;
      background: rgba(255,255,255,.12);
      border: 2px solid rgba(255,255,255,.25);
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-bottom: .6rem;
    }
    .logo-icon i { font-size: 1.6rem; color: #fff; }
    .reg-header h1 { color: #fff; font-size: 1.6rem; font-weight: 800; letter-spacing: 3px; margin: 0 0 .15rem; }
    .reg-header p.subtitle { color: rgba(255,255,255,.75); font-size: .74rem; letter-spacing: .4px; text-transform: uppercase; margin: 0 0 .4rem; }
    .reg-header .gov-label {
      display: inline-block;
      background: rgba(192,57,43,.85);
      color: #fff; font-size: .66rem; font-weight: 600;
      letter-spacing: 1px; text-transform: uppercase;
      padding: .12rem .55rem; border-radius: 2px;
    }

    /* Body */
    .reg-body { padding: 1.75rem 2rem 2rem; }

    .section-divider {
      display: flex;
      align-items: center;
      gap: .75rem;
      margin: 1.25rem 0 1rem;
      color: #6c757d;
      font-size: .75rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: .5px;
    }
    .section-divider::before,
    .section-divider::after {
      content: ''; flex: 1;
      height: 1px; background: #dee2e6;
    }

    .form-label { font-size: .82rem; font-weight: 600; color: #343a40; margin-bottom: .3rem; }
    .input-group-text { background: #f4f6f9; border-right: none; color: #6c757d; font-size: .85rem; }
    .form-control { border-left: none; font-size: .875rem; }
    .form-control:focus { box-shadow: 0 0 0 .2rem rgba(70,114,53,.2); border-color: #467235; }
    .input-group:focus-within .input-group-text { border-color: #467235; }
    .btn-toggle-pw {
      background: #f4f6f9; border: 1px solid #ced4da; border-left: none;
      color: #6c757d; padding: .375rem .65rem;
    }
    .btn-toggle-pw:hover { color: #467235; background: #e9ecef; }

    /* Password strength */
    .strength-bar-wrap {
      height: 5px;
      background: #e9ecef;
      border-radius: 3px;
      overflow: hidden;
      margin-top: .4rem;
    }
    .strength-bar {
      height: 100%;
      width: 0;
      border-radius: 3px;
      transition: width .3s ease, background .3s ease;
    }
    .strength-label {
      font-size: .72rem;
      margin-top: .2rem;
      font-weight: 600;
    }
    .strength-weak   { background: #e74c3c; }
    .strength-fair   { background: #f39c12; }
    .strength-good   { background: #2ecc71; }
    .strength-strong { background: #27ae60; }
    .text-weak   { color: #e74c3c; }
    .text-fair   { color: #f39c12; }
    .text-good   { color: #2ecc71; }
    .text-strong { color: #27ae60; }

    /* Security question select */
    .form-select { font-size: .875rem; }
    .form-select:focus { box-shadow: 0 0 0 .2rem rgba(70,114,53,.2); border-color: #467235; }

    /* Submit button */
    .btn-register {
      background: linear-gradient(135deg, #467235 0%, #5a9246 100%);
      border: none; color: #fff;
      font-weight: 700; font-size: .9rem; letter-spacing: .5px;
      padding: .65rem; border-radius: .4rem;
      transition: opacity .2s, transform .1s; width: 100%;
    }
    .btn-register:hover { opacity: .92; transform: translateY(-1px); color: #fff; }
    .btn-register:active { transform: translateY(0); }
    .btn-register .spinner-border { width: 1rem; height: 1rem; border-width: .15em; }

    .auth-links { font-size: .8rem; margin-top: .75rem; color: #6c757d; text-align: center; }
    .auth-links a { color: #467235; text-decoration: none; font-weight: 600; }
    .auth-links a:hover { text-decoration: underline; }

    .reg-footer {
      background: #f4f6f9;
      border-top: 1px solid #dee2e6;
      padding: .75rem 1rem;
      text-align: center;
      font-size: .73rem;
      color: #6c757d;
    }

    /* Toast */
    .toast-container { position: fixed; top: 1.25rem; right: 1.25rem; z-index: 9999; }

    .form-control.is-invalid { border-left: none; }
    .invalid-feedback { font-size: .75rem; }
  </style>
</head>
<body>
  <div class="gov-stripe"></div>

  <div class="register-wrapper">
    <div class="register-card">

      <!-- Header -->
      <div class="reg-header">
        <div class="logo-icon"><i class="fas fa-shield-halved"></i></div>
        <h1>ODMIS</h1>
        <p class="subtitle">Online Disaster Management Information System</p>
        <span class="gov-label"><i class="fas fa-user-plus me-1"></i>Create New Account</span>
      </div>

      <!-- Body -->
      <div class="reg-body">
        <form id="registerForm" novalidate>

          <!-- Personal Information -->
          <div class="section-divider"><i class="fas fa-id-card me-1"></i>Personal Information</div>

          <div class="mb-3">
            <label for="regFullName" class="form-label">Full Name <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-id-badge"></i></span>
              <input type="text" class="form-control" id="regFullName"
                     placeholder="e.g. Juan Dela Cruz" required>
            </div>
            <div class="invalid-feedback" id="errFullName"></div>
          </div>

          <div class="row g-2 mb-3">
            <div class="col-md-6">
              <label for="regUsername" class="form-label">Username <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-at"></i></span>
                <input type="text" class="form-control" id="regUsername"
                       placeholder="Choose a username" required>
              </div>
              <div class="invalid-feedback" id="errUsername"></div>
            </div>
            <div class="col-md-6">
              <label for="regEmail" class="form-label">Email Address <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" class="form-control" id="regEmail"
                       placeholder="email@example.com" required>
              </div>
              <div class="invalid-feedback" id="errEmail"></div>
            </div>
          </div>

          <div class="row g-2 mb-3">
            <div class="col-md-6">
              <label for="regContact" class="form-label">Contact Number <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                <input type="tel" class="form-control" id="regContact"
                       placeholder="09XXXXXXXXX" maxlength="11" required>
              </div>
              <div class="invalid-feedback" id="errContact"></div>
            </div>
            <div class="col-md-6">
              <label for="regDob" class="form-label">Date of Birth <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                <input type="date" class="form-control" id="regDob" required>
              </div>
              <div class="invalid-feedback" id="errDob"></div>
            </div>
          </div>

          <div class="mb-3">
            <label for="regAddress" class="form-label">Address <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
              <input type="text" class="form-control" id="regAddress"
                     placeholder="Purok, Barangay, Municipality" required>
            </div>
            <div class="invalid-feedback" id="errAddress"></div>
          </div>

          <!-- Password -->
          <div class="section-divider"><i class="fas fa-lock me-1"></i>Password</div>

          <div class="mb-3">
            <label for="regPassword" class="form-label">Password <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-lock"></i></span>
              <input type="password" class="form-control" id="regPassword"
                     placeholder="Minimum 6 characters" required oninput="checkStrength(this.value)">
              <button class="btn-toggle-pw" type="button" onclick="togglePw('regPassword', this)">
                <i class="fas fa-eye"></i>
              </button>
            </div>
            <!-- Password strength indicator -->
            <div class="strength-bar-wrap mt-2">
              <div class="strength-bar" id="strengthBar"></div>
            </div>
            <div class="strength-label" id="strengthLabel"></div>
            <div class="invalid-feedback" id="errPassword"></div>
          </div>

          <div class="mb-3">
            <label for="regConfirmPassword" class="form-label">Confirm Password <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-lock"></i></span>
              <input type="password" class="form-control" id="regConfirmPassword"
                     placeholder="Re-enter password" required>
              <button class="btn-toggle-pw" type="button" onclick="togglePw('regConfirmPassword', this)">
                <i class="fas fa-eye"></i>
              </button>
            </div>
            <div class="invalid-feedback" id="errConfirmPassword"></div>
          </div>

          <!-- Security -->
          <div class="section-divider"><i class="fas fa-shield-alt me-1"></i>Security Question</div>

          <div class="mb-3">
            <label for="regSecQuestion" class="form-label">Security Question <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-question-circle"></i></span>
              <select class="form-select" id="regSecQuestion" required style="border-left:none;">
                <option value="" disabled selected>Select a security question</option>
                <option>What is your mother's maiden name?</option>
                <option>What is the name of your first pet?</option>
                <option>What city were you born in?</option>
                <option>What is the name of your elementary school?</option>
                <option>What was the name of your childhood best friend?</option>
                <option>What is your favorite book?</option>
              </select>
            </div>
            <div class="invalid-feedback" id="errSecQuestion"></div>
          </div>

          <div class="mb-4">
            <label for="regSecAnswer" class="form-label">Security Answer <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-key"></i></span>
              <input type="text" class="form-control" id="regSecAnswer"
                     placeholder="Your answer (case-insensitive)" required>
            </div>
            <div class="invalid-feedback" id="errSecAnswer"></div>
          </div>

          <button type="submit" class="btn-register" id="regSubmit">
            <i class="fas fa-user-plus me-2"></i>Create Account
          </button>

          <div class="auth-links mt-3">
            Already have an account?
            <a href="login.php"><i class="fas fa-sign-in-alt me-1"></i>Sign In</a>
          </div>
        </form>
      </div>

      <!-- Footer -->
      <div class="reg-footer">
        <i class="fas fa-shield-halved me-1"></i>
        &copy; 2026 DRRM Office. All rights reserved.
      </div>
    </div>
  </div>

  <!-- Toast -->
  <div class="toast-container" aria-live="polite" aria-atomic="true">
    <div id="regToast" class="toast align-items-center border-0" role="alert">
      <div class="d-flex">
        <div class="toast-body" id="toastMsg"></div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
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
      const toastEl = document.getElementById('regToast');
      const msgEl   = document.getElementById('toastMsg');
      toastEl.className = 'toast align-items-center border-0 text-white bg-'
                        + (type === 'success' ? 'success' : 'danger');
      msgEl.innerHTML = '<i class="fas fa-' + (type === 'success' ? 'check-circle' : 'exclamation-circle') + ' me-2"></i>' + message;
      bootstrap.Toast.getOrCreateInstance(toastEl, { delay: 5000 }).show();
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

    // ── Password strength ─────────────────────────────────────────
    function checkStrength(pw) {
      const bar   = document.getElementById('strengthBar');
      const label = document.getElementById('strengthLabel');
      if (!pw) { bar.style.width = '0'; bar.className = 'strength-bar'; label.textContent = ''; return; }
      let score = 0;
      if (pw.length >= 6)  score++;
      if (pw.length >= 10) score++;
      if (/[A-Z]/.test(pw)) score++;
      if (/[0-9]/.test(pw)) score++;
      if (/[^A-Za-z0-9]/.test(pw)) score++;
      const levels = [
        { pct: '20%', cls: 'strength-weak',   txt: 'strength-label text-weak',   label: 'Weak' },
        { pct: '40%', cls: 'strength-weak',   txt: 'strength-label text-weak',   label: 'Weak' },
        { pct: '60%', cls: 'strength-fair',   txt: 'strength-label text-fair',   label: 'Fair' },
        { pct: '80%', cls: 'strength-good',   txt: 'strength-label text-good',   label: 'Good' },
        { pct: '100%',cls: 'strength-strong', txt: 'strength-label text-strong', label: 'Strong' }
      ];
      const lvl = levels[Math.min(score, 4)];
      bar.style.width = lvl.pct; bar.className = 'strength-bar ' + lvl.cls;
      label.className = lvl.txt; label.textContent = 'Strength: ' + lvl.label;
    }

    // ── Field error helpers ──────────────────────────────────────
    function setError(inputId, errId, message) {
      const input = document.getElementById(inputId);
      const err   = document.getElementById(errId);
      if (input) input.classList.add('is-invalid');
      if (err)   { err.textContent = message; err.style.display = 'block'; }
    }
    function clearAllErrors() {
      ['regFullName','regUsername','regEmail','regContact','regDob','regAddress',
       'regPassword','regConfirmPassword','regSecQuestion','regSecAnswer'].forEach(function (id) {
        const el = document.getElementById(id);
        if (el) el.classList.remove('is-invalid');
      });
      document.querySelectorAll('.invalid-feedback').forEach(function (el) { el.style.display = 'none'; });
    }

    // ── Validate ──────────────────────────────────────────────────
    function validate() {
      clearAllErrors();
      let valid    = true;
      const fullName  = document.getElementById('regFullName').value.trim();
      const username  = document.getElementById('regUsername').value.trim();
      const email     = document.getElementById('regEmail').value.trim();
      const contact   = document.getElementById('regContact').value.trim();
      const dob       = document.getElementById('regDob').value;
      const address   = document.getElementById('regAddress').value.trim();
      const password  = document.getElementById('regPassword').value;
      const confirmPw = document.getElementById('regConfirmPassword').value;
      const secQ      = document.getElementById('regSecQuestion').value;
      const secA      = document.getElementById('regSecAnswer').value.trim();

      if (!fullName) { setError('regFullName','errFullName','Full name is required.'); valid = false; }
      if (!username) { setError('regUsername','errUsername','Username is required.'); valid = false; }
      else if (username.length < 3) { setError('regUsername','errUsername','Username must be at least 3 characters.'); valid = false; }
      if (!email) { setError('regEmail','errEmail','Email address is required.'); valid = false; }
      else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { setError('regEmail','errEmail','Please enter a valid email address.'); valid = false; }
      if (!contact) { setError('regContact','errContact','Contact number is required.'); valid = false; }
      else if (!/^09\d{9}$/.test(contact)) { setError('regContact','errContact','Enter a valid PH mobile number (09XXXXXXXXX).'); valid = false; }
      if (!dob) { setError('regDob','errDob','Date of birth is required.'); valid = false; }
      if (!address) { setError('regAddress','errAddress','Address is required.'); valid = false; }
      if (!password) { setError('regPassword','errPassword','Password is required.'); valid = false; }
      else if (password.length < 6) { setError('regPassword','errPassword','Password must be at least 6 characters.'); valid = false; }
      if (!confirmPw) { setError('regConfirmPassword','errConfirmPassword','Please confirm your password.'); valid = false; }
      else if (password && confirmPw !== password) { setError('regConfirmPassword','errConfirmPassword','Passwords do not match.'); valid = false; }
      if (!secQ) { setError('regSecQuestion','errSecQuestion','Please select a security question.'); valid = false; }
      if (!secA) { setError('regSecAnswer','errSecAnswer','Security answer is required.'); valid = false; }
      return valid;
    }

    // ── Submit handler ────────────────────────────────────────────
    document.getElementById('registerForm').addEventListener('submit', async function (e) {
      e.preventDefault();
      if (!validate()) return;

      const btn = document.getElementById('regSubmit');
      btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating account…';
      btn.disabled  = true;

      try {
        await ApiClient.post('/auth/register.php', {
          full_name        : document.getElementById('regFullName').value.trim(),
          username         : document.getElementById('regUsername').value.trim(),
          email            : document.getElementById('regEmail').value.trim(),
          password         : document.getElementById('regPassword').value,
          contact_number   : document.getElementById('regContact').value.trim(),
          date_of_birth    : document.getElementById('regDob').value,
          address          : document.getElementById('regAddress').value.trim(),
          security_question: document.getElementById('regSecQuestion').value,
          security_answer  : document.getElementById('regSecAnswer').value.trim()
        });

        showToast('Account created successfully! Redirecting to login…', 'success');
        setTimeout(function () { window.location.href = 'login.php'; }, 2000);
      } catch (err) {
        // Surface server-side field errors if present
        if (err.errors) {
          Object.entries(err.errors).forEach(function([field, msg]) {
            const map = { username: 'errUsername', email: 'errEmail' };
            if (map[field]) setError('reg' + field.charAt(0).toUpperCase() + field.slice(1), map[field], msg);
          });
        }
        showToast(err.message || 'Registration failed.', 'error');
      } finally {
        btn.innerHTML = '<i class="fas fa-user-plus me-2"></i>Create Account';
        btn.disabled  = false;
      }
    });
  </script>
</body>
</html>
