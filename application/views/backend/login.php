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

      <!-- Right: Login Form -->
      <div class="col-lg-6 col-12 p-4 p-md-5">
        <div class="mx-auto" style="max-width: 420px;">

          <!-- Mobile logo -->
          <div class="d-lg-none text-center mb-4">
            <img src="assets/img/image.png" class="img-fluid rounded-3 mb-3" style="max-height: 120px;">
            <h4 class="fw-bold">SNAT Burial Scheme</h4>
          </div>

          <h3 class="fw-bold mb-2">Login with OTP</h3>
          <p class="text-muted mb-4">
            Enter your Passbook, ID, or Cell Number to receive a one-time password.
          </p>

          <form id="otp-login-form" method="post">

            <!-- Identifier -->
            <div class="mb-3">
              <label class="form-label fw-semibold">
                ID Number / Cell Number
              </label>
              <input
                type="text"
                id="login_identifier"
                name="login_identifier"
                class="form-control form-control-lg"
                placeholder="e.g. Passbook, ID, or 76xxxxxx"
                required
              >
            </div>

            <!-- Generate OTP -->
            <div class="d-grid mb-3">
              <button
                type="button"
                id="btn-generate-otp"
                class="btn btn-outline-primary btn-lg fw-semibold"
              >
                Generate OTP
              </button>
            </div>

            <!-- Message -->
            <div id="otp-message" class="alert text-center d-none" role="alert"></div>

            <!-- OTP -->
            <div class="mb-4">
              <label class="form-label fw-semibold">One-Time Password</label>
              <input
                type="text"
                id="otp"
                name="otp"
                class="form-control form-control-lg"
                placeholder="Enter OTP"
                disabled
                required
              >
              <small class="text-muted">
                OTP expires in 5 minutes
              </small>
            </div>

            <!-- Login -->
            <div class="d-grid mb-3">
              <button
                class="btn btn-primary btn-lg fw-bold"
                type="submit"
                id="btn-login-otp"
                disabled
              >
                Login
              </button>
            </div>

          </form>

          <div class="text-center">
            <a href="<?php echo base_url();?>index.php?login/register"
               class="btn btn-link fw-semibold">
              A teacher, not a member yet? Register here
            </a>
          </div>

        </div>
      </div>

    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>

<script>
(function () {
  const btnGenerate = document.getElementById('btn-generate-otp');
  const btnLogin = document.getElementById('btn-login-otp');
  const msg = document.getElementById('otp-message');
  const idInput = document.getElementById('login_identifier');
  const otpInput = document.getElementById('otp');
  const form = document.getElementById('otp-login-form');

  function normalizeIdentifier(value) {
    if (!value) return value;
    const digitsOnly = value.replace(/\D/g, '');
    if (digitsOnly.length === 8 && !digitsOnly.startsWith('268')) {
      return '268' + digitsOnly;
    }
    if (digitsOnly.startsWith('268')) {
      return digitsOnly;
    }
    return value.trim();
  }

  btnGenerate.addEventListener('click', function () {
    const idValue = idInput.value.trim();
    if (!idValue) {
      msg.className = 'alert alert-danger text-center';
      msg.textContent = 'Please enter your Passbook, ID, or Cell Number.';
      msg.classList.remove('d-none');
      return;
    }

    btnGenerate.disabled = true;
    btnGenerate.textContent = 'Sending OTP...';

    const formData = new FormData();
    formData.append('login_identifier', normalizeIdentifier(idValue));

    fetch('<?php echo base_url();?>index.php?login/ajax_generate_otp', {
      method: 'POST',
      body: formData
    })
    .then(r => r.json())
    .then(data => {
      msg.classList.remove('d-none');
      msg.className = 'alert alert-' + (data.status === 'success' ? 'success' : 'danger');
      msg.textContent = data.message;
      if (data.status === 'success') {
        otpInput.disabled = false;
        btnLogin.disabled = false;
      }
      btnGenerate.disabled = false;
      btnGenerate.textContent = 'Generate OTP';
    })
    .catch(() => {
      msg.className = 'alert alert-danger';
      msg.textContent = 'An error occurred. Please try again.';
      msg.classList.remove('d-none');
      btnGenerate.disabled = false;
      btnGenerate.textContent = 'Generate OTP';
    });
  });

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    btnLogin.disabled = true;
    btnLogin.textContent = 'Logging in...';

    const formData = new FormData();
    formData.append('login_identifier', normalizeIdentifier(idInput.value));
    formData.append('otp', otpInput.value);

    fetch('<?php echo base_url();?>index.php?login/ajax_otp_login', {
      method: 'POST',
      body: formData
    })
    .then(r => r.json())
    .then(data => {
      if (data.status === 'success') {
        window.location.href = data.redirect_url ?? '<?php echo base_url();?>index.php?burial/dashboard';
      } else {
        msg.className = 'alert alert-danger';
        msg.textContent = data.message;
        msg.classList.remove('d-none');
        btnLogin.disabled = false;
        btnLogin.textContent = 'Login';
      }
    });
  });
})();
</script>

</body>
