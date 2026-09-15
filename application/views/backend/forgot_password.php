<?php
// login.php
include 'includes/header.php';
?>

<body>
<?php include 'includes/navbar.php'; ?>

<!-- Login Section with Side Image -->
<section class="vh-100 mt-3" style="background-color: #f8f9fa;">
  <div class="container-fluid h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      
      <!-- Left Side: Image (visible on lg and above) -->
      <div class="col-lg-6 d-none d-lg-block p-0">
        <img src="assets/img/image.jpg" 
             alt="SNAT Burial Scheme" 
             class="img-fluid rounded-4">
      </div>
      
      <!-- Right Side: Login Form -->
      <div class="col-lg-6 col-12">
        <div class="d-flex justify-content-center align-items-center h-100">
          <div class="card shadow-lg border-0 rounded-4" style="max-width: 450px; width: 100%;">
            <div class="card-body p-5 text-center">

              <div class="mb-5">
                <h3 class="fw-bold mb-3">Create or Forgot Password</h3>
                <p class="text-muted mb-4">Enter your Passbook, ID, or Cell Number to create or reset your password</p>
              </div>

              <form action="process_login.php" method="post"> <!-- Adjust to your login handler -->

                <div class="form-outline mb-4 text-start">
                  <label class="form-label fw-semibold" for="login_identifier">
                    Passbook Number / ID Number / Cell Number
                  </label>
                  <input
                    type="text"
                    id="login_identifier"
                    name="login_identifier"
                    class="form-control form-control-lg"
                    placeholder="Enter your Passbook, ID, or Cell Number"
                    required
                  />
                </div>

                <div class="form-outline mb-4 text-start">
                  <label class="form-label fw-semibold" for="password">New Password</label>
                  <input type="password" id="password" name="password" class="form-control form-control-lg" 
                         placeholder="Enter your password" required />
                </div>

                <div class="d-grid mb-4">
                  <button class="btn btn-primary btn-lg fw-bold" type="submit">
                    Login
                  </button>
                </div>

                <div class="text-center small text-muted">
                  <span>Forgot your password or never set one?</span>
                  <a href="<?php echo base_url();?>index.php?login/forgot_password" class="text-decoration-none fw-semibold">
                    Click here to get/restore your password
                  </a>
                </div>

                <hr class="my-4">

                <div class="d-grid">
                  <a href="<?php echo base_url();?>index.php?login/register" class="btn btn-outline-primary btn-lg fw-bold">
                    A teacher, not a member yet? Register here
                  </a>
                </div>

              </form>

            </div>
          </div>
        </div>
      </div>
      
    </div>
  </div>
</section>

<!-- On mobile: Optional smaller image above the form -->
<div class="d-lg-none bg-light text-center py-4">
  <img src="assets/img/image.jpg" 
       alt="SNAT Burial Scheme" 
       class="img-fluid rounded-4">
</div>

<?php
include 'includes/footer.php';
?>
</body>