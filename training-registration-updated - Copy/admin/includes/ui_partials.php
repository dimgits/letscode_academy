<?php
$__flash = function_exists('flash_get') ? flash_get() : null;
?>
<!-- Toast container (JS pushes toast elements in here) -->
<div class="toast-container" id="toastContainer"></div>

<?php if ($__flash): ?>
<script>
  window.__pendingToast = {
    type: <?= json_encode($__flash['type']) ?>,
    message: <?= json_encode($__flash['message']) ?>
  };
</script>
<?php endif; ?>

<!-- Confirmation modal (reused for delete confirmations, etc.) -->
<div class="modal-overlay" id="confirmModal">
  <div class="modal-box">
    <h3 id="confirmModalTitle">Are you sure?</h3>
    <p id="confirmModalMessage">This action cannot be undone.</p>
    <div class="modal-actions">
      <button type="button" class="modal-btn modal-btn-cancel" id="confirmModalCancel">Cancel</button>
      <button type="button" class="modal-btn modal-btn-confirm" id="confirmModalConfirm">Confirm</button>
    </div>
  </div>
</div>

<!-- Loading spinner overlay shown while navigating between pages -->
<div class="loading-overlay" id="loadingOverlay">
  <div class="spinner"></div>
</div>
