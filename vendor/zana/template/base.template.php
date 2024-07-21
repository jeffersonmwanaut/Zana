<?php 
require 'partial/base-header.php';
?>

<?php if ($session::hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissible mt-2" role="alert">
        <div><i class="fa-solid fa-circle-check me-2"></i><?= $session::getFlash('success') ?></div>
        <a class="btn-close" href="<?= $httpRequest->requestUri() ?>"></a>
    </div>
<?php endif; ?>
<?php if ($session::hasFlash('danger')): ?>
    <div class="alert alert-danger alert-dismissible mt-2" role="alert">
        <div><i class="fas fa-exclamation-triangle me-2"></i><?= $session::getFlash('danger') ?></div>
        <a class="btn-close" href="<?= $httpRequest->requestUri() ?>"></a>
    </div>
<?php endif; ?>

<?= $content ?>
    
<?php require 'partial/base-footer.php'; ?>