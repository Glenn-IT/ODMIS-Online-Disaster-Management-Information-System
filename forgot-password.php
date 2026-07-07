<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password — ODMIS</title>

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
        radial-gradient(circle at 20% 30%, rgba(255,255,255,.04) 0%, transparent 50%),
        radial-gradient(circle at 80% 70%, rgba(255,255,255,.04) 0%, transparent 50%);
      pointer-events: none;
    }

    .fp-wrapper {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1rem;
    }

    .fp-card {
      width: 100%;
      max-width: 480px;
      background: #fff;
      border-radius: .75rem;
      box-shadow: 0 20px 60px rgba(0,0,0,.35), 0 4px 16px rgba(0,0,0,.2);
      overflow: hidden;
    }

    /* Header */
    .fp-header {
      background: linear-gradient(135deg, #283F24 0%, #467235 100%);
      padding: 1.75rem 2rem 1.5rem;
      text-align: center;
      position: relative;
    }
    .fp-header::after {
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
    .fp-header h1 { color: #fff; font-size: 1.6rem; font-weight: 800; letter-spacing: 3px; margin: 0 0 .15rem; }
    .fp-header p.subtitle { color: rgba(255,255,255,.75); font-size: .74rem; letter-spacing: .4px; text-transform: uppercase; margin: 0 0 .4rem; }
    .fp-header .gov-label {
      display: inline-block;
      background: rgba(192,57,43,.85);
      color: #fff; font-size: .66rem; font-weight: 600;
      letter-spacing: 1px; text-transform: uppercase;
      padding: .12rem .55rem; border-radius: 2px;
    }

    /* Body */
    .fp-body { padding: 1.75rem 2rem 2rem; }

    /* ── Step indicator ── */
    .step-indicator {
      display: flex;
      align-items: center;
      margin-bottom: 1.75rem;
    }
    .step-dot {
      width: 36px; height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: .85rem;
      border: 2px solid #dee2e6;
      color: #adb5bd;
      background: #f8f9fa;
      position: relative;
      z-index: 1;
      transition: background .3s, border-color .3s, color .3s;
      flex-shrink: 0;
    }
    .step-dot.active {
      background: #467235;
      border-color: #467235;
      color: #fff;
    }
    .step-dot.done {
      background: #27ae60;
      border-color: #27ae60;
      color: #fff;
    }
    .step-connector {
      flex: 1;
      height: 2px;
      background: #dee2e6;
      transition: background .3s;
    }
    .step-connector.done { background: #27ae60; }
    .step-label-wrap {
      display: flex;
      justify-content: space-between;
      margin-top: .4rem;
      margin-bottom: 1.5rem;
      font-size: .7rem;
      color: #6c757d;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: .3px;
    }
    .step-label-wrap span { flex: 1; text-align: center; }
    .step-label-wrap span:first-child { text-align: left; }
    .step-label-wrap span:last-child  { text-align: right; }

    /* Step panes */
    .step-pane { display: none; }
    .step-pane.active { display: block; }

    /* Step heading */
    .step-heading {
      font-size: .95rem;
      font-weight: 700;
      color: #467235;
      margin-bottom: .25rem;
    }
    .step-desc {
      font-size: .8rem;
      color: #6c757d;
      margin-bottom: 1.25rem;
    }

    /* Form controls */
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
    .strength-bar-wrap { height: 5px; background: #e9ecef; border-radius: 3px; overflow: hidden; margin-top: .4rem; }
    .strength-bar { height: 100%; width: 0; border-radius: 3px; transition: width .3s, background .3s; }
    .strength-label { font-size: .72rem; margin-top: .2rem; font-weight: 600; }
    .strength-weak   { background: #e74c3c; }
    .strength-fair   { background: #f39c12; }
    .strength-good   { background: #2ecc71; }
    .strength-strong { background: #27ae60; }
    .text-weak   { color: #e74c3c; }
    .text-fair   { color: #f39c12; }
    .text-good   { color: #2ecc71; }
    .text-strong { color: #27ae60; }

    /* Buttons */
    .btn-primary-custom {
      background: linear-gradient(135deg, #467235 0%, #5a9246 100%);
      border: none; color: #fff;
      font-weight: 700; font-size: .9rem; letter-spacing: .5px;
      padding: .65rem; border-radius: .4rem;
      transition: opacity .2s, transform .1s; width: 100%;
    }
    .btn-primary-custom:hover { opacity: .92; transform: translateY(-1px); color: #fff; }
    .btn-primary-custom:active { transform: translateY(0); }
    .btn-primary-custom .spinner-border { width: 1rem; height: 1rem; border-width: .15em; }

    .btn-back {
      background: transparent;
      border: 1px solid #ced4da;
      color: #6c757d;
      font-size: .85rem;
      padding: .5rem 1rem;
      border-radius: .4rem;
      transition: background .2s;
    }
    .btn-back:hover { background: #f4f6f9; }

    .auth-links { font-size: .8rem; margin-top: .75rem; color: #6c757d; text-align: center; }
    .auth-links a { color: #467235; text-decoration: none; font-weight: 600; }
    .auth-links a:hover { text-decoration: underline; }

    .fp-footer {
      background: #f4f6f9;
      border-top: 1px solid #dee2e6;
      padding: .75rem 1rem;
      text-align: center;
      font-size: .73rem;
      color: #6c757d;
    }

    /* Alert boxes */
    .alert-info-custom {
      background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460;
      border-radius: .4rem; padding: .65rem .85rem; font-size: .82rem; margin-bottom: 1rem;
    }
    .alert-success-custom {
      background: #d4edda; border: 1px solid #c3e6cb; color: #155724;
      border-radius: .4rem; padding: .75rem .85rem; font-size: .85rem; text-align: center;
    }

    /* Toast */
    .toast-container { position: fixed; top: 1.25rem; right: 1.25rem; z-index: 9999; }

    .invalid-feedback { font-size: .75rem; }
    .form-control.is-invalid { border-left: none; }
  </style>
</head>
<body>
  <div class="gov-stripe"></div>

  <div class="fp-wrapper">
    <div class="fp-card">

      <!-- Header -->
      <div class="fp-header">
        <div class="logo-icon"><i class="fas fa-shield-halved"></i></div>
        <h1>ODMIS</h1>
        <p class="subtitle">Online Disaster Management Information System</p>
        <span class="gov-label"><i class="fas fa-key me-1"></i>Password Recovery</span>
      </div>

      <!-- Body -->
      <div class="fp-body">

        <!-- Step indicator -->
        <div class="step-indicator">
          <div class="step-dot active" id="dot1">1</div>
          <div class="step-connector" id="conn1"></div>
          <div class="step-dot" id="dot2">2</div>
          <div class="step-connector" id="conn2"></div>
          <div class="step-dot" id="dot3">3</div>
        </div>
        <div class="step-label-wrap">
          <span>Verify Username</span>
          <span style="text-align:center;">Security Check</span>
          <span>New Password</span>
        </div>

        <!-- ── STEP 1: Enter Username ── -->
        <div class="step-pane active" id="step1">
          <p class="step-heading"><i class="fas fa-user me-2 text-primary"></i>Verify Your Username</p>
          <p class="step-desc">Enter the username associated with your ODMIS account.</p>

          <div class="alert-info-custom">
            <i class="fas fa-info-circle me-1"></i>
            Please enter your registered username exactly as it was created.
          </div>

          <div class="mb-4">
            <label for="fpUsername" class="form-label">Username <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-user"></i></span>
              <input type="text" class="form-control" id="fpUsername"
                     placeholder="Enter your username" autocomplete="username">
            </div>
            <div class="invalid-feedback" id="errFpUsername" style="display:none;"></div>
          </div>

          <button class="btn-primary-custom" id="btnStep1" onclick="handleStep1()">
            <i class="fas fa-arrow-right me-2"></i>Continue
          </button>

          <div class="auth-links mt-3">
            Remember your password?
            <a href="login.php"><i class="fas fa-sign-in-alt me-1"></i>Back to Login</a>
          </div>
        </div>

        <!-- ── STEP 2: Security Question ── -->
        <div class="step-pane" id="step2">
          <p class="step-heading"><i class="fas fa-shield-alt me-2 text-primary"></i>Security Verification</p>
          <p class="step-desc">Answer your security question to verify your identity.</p>

          <div class="mb-3">
            <label class="form-label">Security Question</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-question-circle"></i></span>
              <input type="text" class="form-control" id="fpSecQuestion" readonly
                     style="background:#f8f9fa; color:#495057;">
            </div>
          </div>

          <div class="mb-4">
            <label for="fpSecAnswer" class="form-label">Your Answer <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-pen"></i></span>
              <input type="text" class="form-control" id="fpSecAnswer"
                     placeholder="Enter your answer">
            </div>
            <div class="invalid-feedback" id="errFpSecAnswer" style="display:none;"></div>
            <div class="form-text"><i class="fas fa-info-circle me-1"></i>Answer is not case-sensitive.</div>
          </div>

          <div class="d-flex gap-2">
            <button class="btn-back" onclick="goToStep(1)">
              <i class="fas fa-arrow-left me-1"></i>Back
            </button>
            <button class="btn-primary-custom" id="btnStep2" onclick="handleStep2()" style="flex:1;">
              <i class="fas fa-check me-2"></i>Verify Answer
            </button>
          </div>
        </div>

        <!-- ── STEP 3: New Password ── -->
        <div class="step-pane" id="step3">
          <p class="step-heading"><i class="fas fa-lock me-2 text-primary"></i>Set New Password</p>
          <p class="step-desc">Choose a strong new password for your account.</p>

          <div class="mb-3">
            <label for="fpNewPassword" class="form-label">New Password <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-lock"></i></span>
              <input type="password" class="form-control" id="fpNewPassword"
                     placeholder="Minimum 6 characters" oninput="checkStrength(this.value)">
              <button class="btn-toggle-pw" type="button" onclick="togglePw('fpNewPassword', this)">
                <i class="fas fa-eye"></i>
              </button>
            </div>
            <div class="strength-bar-wrap mt-2">
              <div class="strength-bar" id="strengthBar"></div>
            </div>
            <div class="strength-label" id="strengthLabel"></div>
            <div class="invalid-feedback" id="errFpNewPw" style="display:none;"></div>
          </div>

          <div class="mb-4">
            <label for="fpConfirmPassword" class="form-label">Confirm New Password <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-lock"></i></span>
              <input type="password" class="form-control" id="fpConfirmPassword"
                     placeholder="Re-enter new password">
              <button class="btn-toggle-pw" type="button" onclick="togglePw('fpConfirmPassword', this)">
                <i class="fas fa-eye"></i>
              </button>
            </div>
            <div class="invalid-feedback" id="errFpConfirmPw" style="display:none;"></div>
          </div>

          <div class="d-flex gap-2">
            <button class="btn-back" onclick="goToStep(2)">
              <i class="fas fa-arrow-left me-1"></i>Back
            </button>
            <button class="btn-primary-custom" id="btnStep3" onclick="handleStep3()" style="flex:1;">
              <i class="fas fa-save me-2"></i>Save New Password
            </button>
          </div>
        </div>

        <!-- ── SUCCESS panel (shown after step 3) ── -->
        <div class="step-pane" id="stepSuccess">
          <div class="text-center py-2">
            <div style="width:70px;height:70px;background:#d4edda;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;margin-bottom:1rem;">
              <i class="fas fa-check-circle text-success" style="font-size:2rem;"></i>
            </div>
            <h5 class="fw-bold text-success mb-2">Password Updated!</h5>
            <p class="text-muted" style="font-size:.85rem;">
              Your password has been reset successfully.<br>
              You will be redirected to the login page shortly.
            </p>
            <div class="alert-success-custom mt-3">
              <i class="fas fa-info-circle me-1"></i>
              Redirecting to login in <span id="countdown">3</span> second(s)…
            </div>
            <a href="login.php" class="btn-primary-custom d-inline-block mt-3" style="text-decoration:none;padding:.55rem 1.5rem;width:auto;">
              <i class="fas fa-sign-in-alt me-2"></i>Go to Login Now
            </a>
          </div>
        </div>

      </div><!-- /.fp-body -->

      <!-- Footer -->
      <div class="fp-footer">
        <i class="fas fa-shield-halved me-1"></i>
        &copy; 2026 DRRM Office. All rights reserved.
      </div>
    </div>
  </div>

  <!-- Toast -->
  <div class="toast-container" aria-live="polite" aria-atomic="true">
    <div id="fpToast" class="toast align-items-center border-0" role="alert">
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
    // ── State ─────────────────────────────────────────────────────
    var _resolvedUsername = '';
    var _resolvedUserId = null;

    // ── Toast helper ─────────────────────────────────────────────
    function showToast(message, type) {
      var toastEl = document.getElementById('fpToast');
      var msgEl   = document.getElementById('toastMsg');
      toastEl.className = 'toast align-items-center border-0 text-white bg-'
                        + (type === 'success' ? 'success' : 'danger');
      msgEl.innerHTML = '<i class="fas fa-' + (type === 'success' ? 'check-circle' : 'exclamation-circle') + ' me-2"></i>' + message;
      bootstrap.Toast.getOrCreateInstance(toastEl, { delay: 4500 }).show();
    }

    // ── Password visibility toggle ───────────────────────────────
    function togglePw(inputId, btn) {
      var input = document.getElementById(inputId);
      var icon  = btn.querySelector('i');
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
      var bar   = document.getElementById('strengthBar');
      var label = document.getElementById('strengthLabel');
      if (!pw) { bar.style.width = '0'; bar.className = 'strength-bar'; label.textContent = ''; return; }
      var score = 0;
      if (pw.length >= 6)  score++;
      if (pw.length >= 10) score++;
      if (/[A-Z]/.test(pw)) score++;
      if (/[0-9]/.test(pw)) score++;
      if (/[^A-Za-z0-9]/.test(pw)) score++;
      var levels = [
        { pct: '20%', cls: 'strength-weak',   txt: 'strength-label text-weak',   lbl: 'Weak' },
        { pct: '40%', cls: 'strength-weak',   txt: 'strength-label text-weak',   lbl: 'Weak' },
        { pct: '60%', cls: 'strength-fair',   txt: 'strength-label text-fair',   lbl: 'Fair' },
        { pct: '80%', cls: 'strength-good',   txt: 'strength-label text-good',   lbl: 'Good' },
        { pct: '100%',cls: 'strength-strong', txt: 'strength-label text-strong', lbl: 'Strong' }
      ];
      var lvl = levels[Math.min(score, 4)];
      bar.style.width = lvl.pct;
      bar.className   = 'strength-bar ' + lvl.cls;
      label.className = lvl.txt;
      label.textContent = 'Strength: ' + lvl.lbl;
    }

    // ── Step navigation ───────────────────────────────────────────
    function goToStep(step) {
      // Hide all panes
      document.querySelectorAll('.step-pane').forEach(function (p) { p.classList.remove('active'); });
      if (step === 'success') {
        document.getElementById('stepSuccess').classList.add('active');
        updateStepIndicator(4); // all done
        return;
      }
      document.getElementById('step' + step).classList.add('active');
      updateStepIndicator(step);
    }

    function updateStepIndicator(current) {
      for (var i = 1; i <= 3; i++) {
        var dot  = document.getElementById('dot'  + i);
        var conn = document.getElementById('conn' + i);
        dot.classList.remove('active', 'done');
        if (conn) conn.classList.remove('done');

        if (i < current) {
          dot.classList.add('done');
          dot.innerHTML = '<i class="fas fa-check" style="font-size:.7rem;"></i>';
          if (conn) conn.classList.add('done');
        } else if (i === current) {
          dot.classList.add('active');
          dot.textContent = i;
        } else {
          dot.textContent = i;
        }
      }
    }

    // ── Field error helpers ──────────────────────────────────────
    function setFieldError(inputId, errId, message) {
      var input = document.getElementById(inputId);
      var err   = document.getElementById(errId);
      input.classList.add('is-invalid');
      if (err) { err.textContent = message; err.style.display = 'block'; }
    }
    function clearFieldError(inputId, errId) {
      var input = document.getElementById(inputId);
      var err   = document.getElementById(errId);
      if (input) input.classList.remove('is-invalid');
      if (err)   err.style.display = 'none';
    }

    // ── Spinner helper ────────────────────────────────────────────
    function setBtnLoading(btnId, loading, originalHTML) {
      var btn = document.getElementById(btnId);
      if (loading) {
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Checking…';
        btn.disabled = true;
      } else {
        btn.innerHTML = originalHTML;
        btn.disabled = false;
      }
    }

    // ── STEP 1 handler ────────────────────────────────────────────
    async function handleStep1() {
      clearFieldError('fpUsername', 'errFpUsername');
      var username = document.getElementById('fpUsername').value.trim();
      if (!username) {
        setFieldError('fpUsername', 'errFpUsername', 'Please enter your username.');
        return;
      }
      setBtnLoading('btnStep1', true, '<i class="fas fa-arrow-right me-2"></i>Continue');
      try {
        var res = await ApiClient.post('/auth/forgot-password.php', { step: 1, username: username });
        _resolvedUsername = username;
        document.getElementById('fpSecQuestion').value = res.data.security_question;
        document.getElementById('fpSecAnswer').value   = '';
        clearFieldError('fpSecAnswer', 'errFpSecAnswer');
        goToStep(2);
      } catch (err) {
        setFieldError('fpUsername', 'errFpUsername', err.message || 'Username not found.');
        showToast(err.message || 'No account found with that username.', 'error');
      } finally {
        setBtnLoading('btnStep1', false, '<i class="fas fa-arrow-right me-2"></i>Continue');
      }
    }

    // ── STEP 2 handler ────────────────────────────────────────────
    async function handleStep2() {
      clearFieldError('fpSecAnswer', 'errFpSecAnswer');
      var answer = document.getElementById('fpSecAnswer').value.trim();
      if (!answer) {
        setFieldError('fpSecAnswer', 'errFpSecAnswer', 'Please enter your security answer.');
        return;
      }
      setBtnLoading('btnStep2', true, '<i class="fas fa-check me-2"></i>Verify Answer');
      try {
        var res = await ApiClient.post('/auth/forgot-password.php', {
          step: 2, username: _resolvedUsername, security_answer: answer
        });
        _resolvedUserId = res.data.user_id;
        document.getElementById('fpNewPassword').value     = '';
        document.getElementById('fpConfirmPassword').value = '';
        checkStrength('');
        clearFieldError('fpNewPassword',     'errFpNewPw');
        clearFieldError('fpConfirmPassword', 'errFpConfirmPw');
        goToStep(3);
      } catch (err) {
        setFieldError('fpSecAnswer', 'errFpSecAnswer', 'Incorrect answer. Please try again.');
        showToast(err.message || 'Security answer does not match our records.', 'error');
      } finally {
        setBtnLoading('btnStep2', false, '<i class="fas fa-check me-2"></i>Verify Answer');
      }
    }

    // ── STEP 3 handler ────────────────────────────────────────────
    async function handleStep3() {
      clearFieldError('fpNewPassword',     'errFpNewPw');
      clearFieldError('fpConfirmPassword', 'errFpConfirmPw');
      var newPw     = document.getElementById('fpNewPassword').value;
      var confirmPw = document.getElementById('fpConfirmPassword').value;
      var valid     = true;
      if (!newPw) { setFieldError('fpNewPassword','errFpNewPw','New password is required.'); valid = false; }
      else if (newPw.length < 6) { setFieldError('fpNewPassword','errFpNewPw','Password must be at least 6 characters.'); valid = false; }
      if (!confirmPw) { setFieldError('fpConfirmPassword','errFpConfirmPw','Please confirm your new password.'); valid = false; }
      else if (newPw && confirmPw !== newPw) { setFieldError('fpConfirmPassword','errFpConfirmPw','Passwords do not match.'); valid = false; }
      if (!valid) return;

      setBtnLoading('btnStep3', true, '<i class="fas fa-save me-2"></i>Save New Password');
      try {
        await ApiClient.post('/auth/forgot-password.php', {
          step: 3, user_id: _resolvedUserId, new_password: newPw
        });
        goToStep('success');
        var secs    = 3;
        var counter = document.getElementById('countdown');
        var timer   = setInterval(function () {
          secs--;
          if (counter) counter.textContent = secs;
          if (secs <= 0) { clearInterval(timer); window.location.href = 'login.php?reset=1'; }
        }, 1000);
      } catch (err) {
        showToast(err.message || 'Password reset failed. Please try again.', 'error');
      } finally {
        setBtnLoading('btnStep3', false, '<i class="fas fa-save me-2"></i>Save New Password');
      }
    }

    // ── Allow Enter key on step inputs ───────────────────────────
    document.getElementById('fpUsername').addEventListener('keydown', function (e) {
      if (e.key === 'Enter') handleStep1();
    });
    document.getElementById('fpSecAnswer').addEventListener('keydown', function (e) {
      if (e.key === 'Enter') handleStep2();
    });
    document.getElementById('fpConfirmPassword').addEventListener('keydown', function (e) {
      if (e.key === 'Enter') handleStep3();
    });
  </script>
</body>
</html>
