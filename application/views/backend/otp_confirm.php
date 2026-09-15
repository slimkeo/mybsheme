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
      
      <!-- Right Side: OTP Confirm Form -->
      <div class="col-lg-6 col-12">
        <div class="d-flex justify-content-center align-items-center h-100">
          <div class="card shadow-lg border-0 rounded-4" style="max-width: 450px; width: 100%;">
            <div class="card-body p-5 text-center">

              <div class="mb-5">
                <h3 class="fw-bold mb-3">Login with OTP</h3>
                <p class="text-muted mb-4">
                  Enter your Passbook, ID, or Cell Number and click **Generate OTP to Login**.
                  The OTP will be sent to your registered cell number and will expire in 5 minutes.
                </p>
              </div>

              <form action="process_otp_confirm.php" method="post"> <!-- Adjust to your OTP handler -->

                <!-- Identifier: passbook / ID / cell -->
                <div class="form-outline mb-3 text-start">
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

                <!-- Button to generate OTP -->
                <div class="d-grid mb-2">
                  <button
                    type="button"
                    id="btn-generate-otp"
                    class="btn btn-outline-primary btn-lg fw-bold"
                  >
                    Generate OTP to Login
                  </button>
                </div>

                <!-- Message area: OTP info or user-not-found -->
                <div id="otp-message" class="small text-muted mb-4 text-center" style="display:none;">
                  <!-- Message will be shown here after clicking the button -->
                </div>

                <!-- OTP input -->
                <div class="form-outline mb-4 text-start">
                  <label class="form-label fw-semibold" for="otp">One-Time Password (OTP)</label>
                  <input
                    type="text"
                    id="otp"
                    name="otp"
                    class="form-control form-control-lg"
                    placeholder="Enter the OTP you received"
                    required
                  />
                </div>

                <!-- Submit OTP to login -->
                <div class="d-grid mb-2">
                  <button class="btn btn-primary btn-lg fw-bold" type="submit">
                    Login with OTP
                  </button>
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

<script>
  (function() {
    const btn = document.getElementById('btn-generate-otp');
    const msg = document.getElementById('otp-message');
    const idInput = document.getElementById('login_identifier');

    if (btn && msg && idInput) {
      btn.addEventListener('click', function () {
        const idValue = (idInput.value || '').trim();
        if (!idValue) {
          msg.style.display = 'block';
          msg.classList.remove('text-success');
          msg.classList.add('text-danger');
          msg.textContent = 'Please enter your Passbook, ID, or Cell Number first.';
          return;
        }

        // TODO: Call your backend via AJAX to actually send the OTP.
        // For now we just show the standard message.
        msg.style.display = 'block';
        msg.classList.remove('text-danger');
        msg.classList.add('text-success');
        msg.textContent = 'If the user exists, an OTP has been sent and will expire in 5 minutes. '
                        + 'If you do not receive an OTP, the user does not exist or the number is incorrect.';
      });
    }
  })();
</script>

<?php
include 'includes/footer.php';
?>
</body>