<?php
/**
 * ODMIS Official Letterhead Header Component
 * Source: docs/HEADER.pdf
 * Used on all print outputs across the system
 */
$docTitle = $docTitle ?? 'Official Disaster Management Report';
$docSubtitle = $docSubtitle ?? 'Municipality of Santo Niño (Faire), Cagayan';
?>
<!-- Official Letterhead Print Header -->
<div class="odmis-print-header" aria-hidden="true">
  <div class="print-header-bg">
    <img src="../img/header_bg.png" alt="" class="print-header-bg-img" />
  </div>
  <div class="print-header-main">
    <div class="print-header-seal">
      <img src="../img/seal_stonino.png" alt="Seal of Santo Niño" />
    </div>
    <div class="print-header-text">
      <div class="p-country">Republic of the Philippines</div>
      <div class="p-province">Province of Cagayan</div>
      <div class="p-municipality">Municipality of Sto. Niño</div>
      <div class="p-office">OFFICE OF THE MUNICIPAL DISASTER RISK REDUCTION AND MANAGEMENT</div>
    </div>
    <div class="print-header-mdrrmo">
      <img src="../img/logo_mdrrmo.jpg" alt="MDRRMO Logo" />
    </div>
  </div>
  <div class="print-header-line"></div>
  <div class="print-header-doc-info">
    <div class="p-doc-title"><?= htmlspecialchars($docTitle) ?></div>
    <div class="p-doc-meta">
      <span class="p-doc-subtitle"><?= htmlspecialchars($docSubtitle) ?></span>
      <span class="p-doc-date">Printed: <?= date('F d, Y  h:i A') ?></span>
    </div>
  </div>
</div>
