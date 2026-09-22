<footer id="footer" class="footer">
  <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="index.html" class="d-flex align-items-center">
            <span class="sitename">SNAT Burial Home</span>
          </a>
          <div class="mt-2 p-2 bg-light rounded small text-muted">
            <i class="bi bi-person-check me-1"></i> Signed in as <strong><?php echo $this->session->userdata('name') ? htmlspecialchars($this->session->userdata('name')) : 'Member'; ?></strong>
          </div>
          <div class="footer-contact pt-3">
            <p>SNAT Burial Scheme</p>
            <p>P O Box 2128 Manzini</p>
            <p>Eswatini</p>
            <p class="mt-3"><strong>Phone:</strong> <span>+268 76298630</span></p>
            <p><strong>Email:</strong> <span>info@snatburialscheme.com</span></p>
          </div>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Member Links</h4>
          <ul>
            <li><i class="bi bi-chevron-right"></i> <a href="dashboard.php">Dashboard</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="beneficiaries.php">Beneficiaries</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="policy.php">Policy</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="statement.php">Statements</a></li>
          </ul>
        </div>

        <div class="col-lg-4 col-md-12 footer-links">
          <h4>Account</h4>
          <ul>
           <?php /* <li><i class="bi bi-chevron-right"></i> <a href="profile.php">Profile</a></li> */ ?>
              <li><i class="bi bi-chevron-right"></i> <a href="payments.php">Pay Contribution</a></li>
             <?php /* <li><i class="bi bi-chevron-right"></i> <a href="support.php">Support</a></li> */ ?>
            <li><i class="bi bi-box-arrow-right"></i> <a href="logout.php" class="text-danger">Logout</a></li>
          </ul>
        </div>

      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">SNAT Burial Scheme</strong> <span>All Rights Reserved</span></p>
      <div class="credits">
        Powered by SNAT Burial Scheme
      </div>
    </div>

  </footer>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  
  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>
  
  <!-- Initialize AOS -->
  <script>
    AOS.init({
      duration: 1000,
      easing: 'ease-in-out',
      once: true
    });
  </script>