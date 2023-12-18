<?php 
use Zana\Router\Router;
?>
<nav class="navbar navbar-expand-lg bg-transparent fixed-top shadow-none">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= URL_ROOT . '/' . Router::generateUrl('_MAIN') ?>">
      <img src="<?= FAVICON['filename'] ?>" alt="JMM Logo">
      JMM Corporation
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarMain">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item dropdown">
          <a class="nav-link" href="#" id="navbarDropdown1" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Who we are
          </a>
          <ul class="dropdown-menu border-0 rounded-0" aria-labelledby="navbarDropdown1">
            <li><a class="dropdown-item" href="<?= URL_ROOT . '/' . Router::generateUrl('ABOUT.OVERVIEW') ?>">At a glance</a></li>
            <li><a class="dropdown-item" href="<?= URL_ROOT . '/' . Router::generateUrl('ABOUT.PURPOSE') ?>">Our Purpose</a></li>
            <li><a class="dropdown-item" href="<?= URL_ROOT . '/' . Router::generateUrl('ABOUT.VALUES') ?>">Our Values</a></li>
            <li><a class="dropdown-item" href="<?= URL_ROOT . '/' . Router::generateUrl('ABOUT.HISTORY') ?>">Our History</a></li>
            <li><a class="dropdown-item" href="<?= URL_ROOT . '/' . Router::generateUrl('ABOUT.TEAM') ?>">Our Team</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link" href="#" id="navbarDropdown2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            What we do
          </a>
          <ul class="dropdown-menu border-0 rounded-0" aria-labelledby="navbarDropdown2">
            <li><a class="dropdown-item" href="<?= URL_ROOT . '/' . Router::generateUrl('SERVICE.CLEANING_AND_LAUNDRY') ?>">Cleaning and Laundry</a></li>
            <li><a class="dropdown-item" href="<?= URL_ROOT . '/' . Router::generateUrl('SERVICE.CIVIL_ENGINEERING_AND_REHABILITATION') ?>">Civil Engineering and Rehabilitation</a></li>
            <li><a class="dropdown-item" href="<?= URL_ROOT . '/' . Router::generateUrl('SERVICE.PLANT_MAINTENANCE') ?>">Plant Maintenance</a></li>
            <li><a class="dropdown-item" href="<?= URL_ROOT . '/' . Router::generateUrl('SERVICE.COMPUTER_AND_TELECOMMUNICATION') ?>">Computer and Telecommunication</a></li>
            <li><a class="dropdown-item" href="<?= URL_ROOT . '/' . Router::generateUrl('SERVICE.EQUIPMENT_SUPPLY') ?>">Equipment Supply</a></li>
            <li><a class="dropdown-item" href="<?= URL_ROOT . '/' . Router::generateUrl('SERVICE.TRAININGS') ?>">Trainings</a></li>
            <li><a class="dropdown-item" href="<?= URL_ROOT . '/' . Router::generateUrl('SERVICE.TRANSPORT') ?>">Transport</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= URL_ROOT . '/' . Router::generateUrl('CONTACT') ?>">Contact us</a>
        </li>
      </ul>
    </div>
  </div>
</nav>