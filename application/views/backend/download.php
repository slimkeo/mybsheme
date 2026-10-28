<?php
// login.php
include 'includes/header.php';
?>

<body class="bg-light">

<?php include 'includes/navbar.php'; ?>
<section class="section py-5">
  <div class="container">

    <!-- Page Title -->
    <div class="text-center mb-5">
      <h2 class="fw-bold">Download the SNAT Burial Scheme App</h2>
      <p class="text-muted">
        Manage your membership, beneficiaries, claims and subscriptions on the go.
      </p>
    </div>

    <!-- Download Cards -->
    <div class="row justify-content-center g-4">

      <!-- Google Play -->
      <div class="col-12 col-sm-6 col-md-5 col-lg-4">
        <div class="card border-0 shadow-sm h-100 text-center p-4">
          <div class="card-body d-flex flex-column align-items-center justify-content-center">
            <h5 class="fw-semibold mb-3">Android</h5>
            <p class="text-muted small mb-4">Get it on Google Play</p>
            
            <a href="https://play.google.com/store/apps/details?id=YOUR_PACKAGE_NAME" 
               target="_blank" 
               rel="noopener noreferrer"
               class="d-inline-block">
              <img src="<?php echo base_url(); ?>uploads/googleplay.png" 
                   alt="Get it on Google Play" 
                   class="img-fluid"
                   style="max-height: 60px; width: auto;">
            </a>
          </div>
        </div>
      </div>

      <!-- App Store -->
      <div class="col-12 col-sm-6 col-md-5 col-lg-4">
        <div class="card border-0 shadow-sm h-100 text-center p-4">
          <div class="card-body d-flex flex-column align-items-center justify-content-center">
            <h5 class="fw-semibold mb-3">iOS</h5>
            <p class="text-muted small mb-4">Download on the App Store</p>
            
            <a href="https://apps.apple.com/app/idYOUR_APP_ID" 
               target="_blank" 
               rel="noopener noreferrer"
               class="d-inline-block">
              <img src="<?php echo base_url(); ?>uploads/appstore.png" 
                   alt="Download on the App Store" 
                   class="img-fluid"
                   style="max-height: 60px; width: auto;">
            </a>
          </div>
        </div>
      </div>

    </div>

  </div>
</section>
<?php include 'includes/footer.php'; ?>


</body>