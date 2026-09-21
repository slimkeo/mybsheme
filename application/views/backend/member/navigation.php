
<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

        <a href="dashboard.php" class="logo d-flex align-items-center me-auto">
            <img src="assets/img/logo.png" alt="SNAT Burial Scheme Logo" style="height:40px;">
        </a>

        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="<?php echo base_url(); ?>index.php?burial/dashboard" class="<?= ($page_name == 'dashboard') ? 'active' : '' ?>">Dashboard</a></li>
                <li><a href="<?php echo base_url(); ?>index.php?burial/beneficiaries" class="<?= ($page_name == 'beneficiaries') ? 'active' : '' ?>">Beneficiaries</a></li>
                <li><a href="<?php echo base_url(); ?>index.php?burial/claims" class="<?= ($page_name == 'claims') ? 'active' : '' ?>">Claims</a></li>
                <li><a href="<?php echo base_url(); ?>index.php?burial/statement" class="<?= ($page_name == 'statement') ? 'active' : '' ?>">Subscriptions</a></li>
               <?php /* <li><a href="<?php echo base_url(); ?>index.php?burial/support" class="<?= ($page_name == 'support') ? 'active' : '' ?>">Support</a></li> */ ?>
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        <!-- Logged-in member actions -->
        <div class="d-flex align-items-center gap-2">
            <div class="dropdown">
                <a class="btn-getstarted dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle me-1"></i> <?php echo $this->session->userdata('name') ? htmlspecialchars($this->session->userdata('name')) : 'Member'; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="<?php echo base_url(); ?>index.php?burial/beneficiaries">Beneficiaries</a></li>
                    <li><a class="dropdown-item" href="<?php echo base_url(); ?>index.php?burial/policy">Policy</a></li>
                    <li><a class="dropdown-item" href="<?php echo base_url(); ?>index.php?burial/statement">Subscriptions</a></li>
                   <?php /* <li><a class="dropdown-item" href="<?php echo base_url(); ?>index.php?burial/profile">Profile</a></li> */ ?>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="<?php echo base_url(); ?>index.php?login/logout"><i class="bi bi-box-arrow-right me-1"></i>Logout</a></li>
                </ul>
            </div>
            <a class="btn-getstarted btn-outline-primary" href="<?php echo base_url(); ?>index.php?burial/payments">Pay Subscriptions</a>
        </div>


    </div>
</header>
