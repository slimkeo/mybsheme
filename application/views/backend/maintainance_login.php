<?php
// login.php
include 'includes/header.php';
?>
<body class="bg-light">
<?php include 'includes/navbar.php'; ?>
<section class="py-4 py-lg-0">
  <div class="container">
    <div class="row shadow-lg rounded-4 overflow-hidden bg-white align-items-lg-center">

      <!-- Left: Branding / Image -->
      <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center bg-gradient">
        <div class="text-center text-white p-5">
          <img src="assets/img/image.png" class="img-fluid mb-4 rounded-3" style="max-height: 280px;">
          <h2 class="fw-bold">SNAT BURIAL SCHEME</h2>
          <p class="opacity-75 mt-3">
            Secure access using One-Time Password (OTP).
            Fast, safe, and reliable authentication.
          </p>
        </div>
      </div>

      <!-- Right: Maintenance Message -->
      <div class="col-lg-6 col-12 p-4 p-md-5">
        <div class="mx-auto text-center" style="max-width: 420px;">

          <!-- Mobile logo -->
          <div class="d-lg-none text-center mb-4">
            <img src="assets/img/image.png" class="img-fluid rounded-3 mb-3" style="max-height: 120px;">
            <h4 class="fw-bold">SNAT Burial Scheme</h4>
          </div>

          <div class="mb-4">
            <i class="fa fa-cogs" style="font-size: 4.5rem; color: #dc3545;"></i>
          </div>

          <h3 class="fw-bold mb-3">We Are Under Maintenance</h3>

          <p class="text-muted mb-4">
            Our system is currently undergoing scheduled maintenance.<br>
            Please check back later. We apologize for any inconvenience.
          </p>

          <div class="alert alert-warning text-center" role="alert">
            <strong>Login is temporarily unavailable</strong>
          </div>

        </div>
      </div>

    </div>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
</body>