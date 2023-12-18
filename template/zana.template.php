<?php 
require 'partial/base-header.inc.php'; 
use Zana\Router\Router;
?>
<nav class="navbar bg-zana">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= URL_ROOT ?>">
        <img src="<?= IMG ?>/zana-2.png" alt="Logo" width="30" height="30" class="d-inline-block mx-1">
        Zana
        </a>
    </div>
</nav>

<div class="row">
    <div class="col-12 col-md-2">
        <div class="container py-3">
            <nav class="nav flex-column">
                <a class="nav-link text-body active" aria-current="page" href="<?= URL_ROOT . '/' . Router::generateUrl('_DOC') ?>">Introduction</a>
                <a class="nav-link text-body" aria-current="page" href="<?= URL_ROOT . '/' . Router::generateUrl('_DOWNLOAD') ?>">Download</a>
                <a class="nav-link text-body" aria-current="page" href="<?= URL_ROOT . '/' . Router::generateUrl('_STRUCTURE') ?>">Structure</a>
                <a class="nav-link text-body" aria-current="page" href="<?= URL_ROOT . '/' . Router::generateUrl('_EXAMPLE') ?>">Example of a project</a>
            </nav>
        </div>
    </div>
    <div class="col">
        <div class="container py-3">
            <?= $content ?>
        </div>
    </div>
</div>

<?php require 'partial/base-footer.inc.php'; ?>