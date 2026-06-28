<?php
define('CURRENT_VERSION', 'v1.08');

$loginPath = (
    strpos($_SERVER['PHP_SELF'], '/admin/') !== false ||
    strpos($_SERVER['PHP_SELF'], '/user/')  !== false
) ? '../login.php' : 'login.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Under Construction — ODMIS</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      min-height: 100vh;
      background: linear-gradient(135deg, #1a2e15 0%, #283F24 45%, #467235 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
      margin: 0;
    }
    .uc-card {
      background: #fff;
      border-radius: .75rem;
      box-shadow: 0 20px 60px rgba(0,0,0,.35), 0 4px 16px rgba(0,0,0,.2);
      max-width: 480px;
      width: 100%;
      overflow: hidden;
      text-align: center;
    }
    .uc-header {
      background: linear-gradient(135deg, #283F24 0%, #467235 100%);
      padding: 2rem;
      position: relative;
    }
    .uc-header::after {
      content: '';
      position: absolute;
      bottom: -1px; left: 0; right: 0;
      height: 3px;
      background: linear-gradient(90deg, #FFBF00, #FFF78D);
    }
    .uc-icon {
      width: 80px; height: 80px;
      background: rgba(255,255,255,.12);
      border: 2px solid rgba(255,255,255,.25);
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-bottom: .75rem;
    }
    .uc-icon i { font-size: 2.2rem; color: #FFBF00; }
    .uc-header h2 { color: #fff; font-weight: 800; margin: 0 0 .5rem; font-size: 1.6rem; }
    .uc-header p { color: rgba(255,255,255,.75); font-size: .8rem; margin: 0 0 .75rem; }
    .version-badge {
      display: inline-block;
      background: rgba(255,191,0,.2);
      border: 1px solid rgba(255,191,0,.5);
      color: #FFBF00;
      font-size: .75rem;
      font-weight: 700;
      letter-spacing: 1px;
      padding: .2rem .65rem;
      border-radius: 20px;
    }
    .uc-body { padding: 2rem 2rem 1.5rem; }
    .uc-body p { color: #6c757d; font-size: .9rem; margin-bottom: 1.5rem; line-height: 1.6; }
    .btn-logout {
      background: linear-gradient(135deg, #c0392b 0%, #e74c3c 100%);
      border: none;
      color: #fff;
      font-weight: 700;
      font-size: .9rem;
      padding: .65rem 2rem;
      border-radius: .4rem;
      cursor: pointer;
      transition: opacity .2s, transform .1s;
      width: 100%;
      letter-spacing: .3px;
    }
    .btn-logout:hover { opacity: .9; transform: translateY(-1px); }
    .btn-logout:active { transform: translateY(0); }
    .uc-footer {
      background: #f4f6f9;
      border-top: 1px solid #dee2e6;
      padding: .75rem 1rem;
      font-size: .73rem;
      color: #6c757d;
    }
  </style>
</head>
<body>
  <div class="uc-card">

    <div class="uc-header">
      <div class="uc-icon">
        <i class="fas fa-hard-hat"></i>
      </div>
      <h2>Under Construction</h2>
      <p>Online Disaster Management Information System</p>
      <span class="version-badge">
        <i class="fas fa-code-branch me-1"></i><?= CURRENT_VERSION ?>
      </span>
    </div>

    <div class="uc-body">
      <p>
        This feature is not yet available in the current presentation version.
        It will be unlocked in an upcoming release.
      </p>
      <button class="btn-logout" onclick="odmisLogout()">
        <i class="fas fa-sign-out-alt me-2"></i>Log Out
      </button>
    </div>

    <div class="uc-footer">
      <i class="fas fa-shield-halved me-1"></i>
      &copy; 2025 DRRM Office. All rights reserved.
      &mdash; For emergencies call <strong>911</strong>
    </div>

  </div>

  <script>
    function odmisLogout() {
      localStorage.removeItem('odmis_jwt');
      window.location.href = '<?= htmlspecialchars($loginPath, ENT_QUOTES) ?>';
    }
  </script>
</body>
</html>
<?php exit; ?>
