<?php 
require 'partial/base-header.php';
?>

<?php if ($session::hasFlash('success')): ?>
    <div class="alert alert-success mt-2">
    <i class="fa-solid fa-circle-check me-2"></i>
        <?= $session::getFlash('success') ?>
    </div>
<?php endif; ?>
<?php if ($session::hasFlash('error')): ?>
    <div class="alert alert-danger mt-2">
    <i class="fas fa-exclamation-triangle me-2"></i>
        <?= $session::getFlash('error') ?>
    </div>
<?php endif; ?>

<?= $content ?>
    
<?php require 'partial/base-footer.php'; ?>