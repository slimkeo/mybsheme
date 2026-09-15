<section class="section">
  <div class="row g-4">
    <div class="col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h5 class="mb-1">Pay Contribution</h5>
              <p class="text-muted mb-0">Use the banking details below to make your monthly contribution.</p>
            </div>
            <span class="badge bg-info">Placeholder</span>
          </div>
        </div>
        <div class="card-body p-4">
          <div class="row g-4">
            <div class="col-md-6">
              <div class="border rounded-3 p-3 h-100">
                <h6 class="mb-3">Bank Transfer</h6>
                <ul class="list-unstyled mb-0">
                  <li class="mb-2"><strong>Bank:</strong> Standard Bank</li>
                  <li class="mb-2"><strong>Account Name:</strong> SNAT Burial Scheme</li>
                  <li class="mb-2"><strong>Account Number:</strong> 9110003362291</li>
                  <li class="mb-2"><strong>Reference:</strong> <?php echo htmlspecialchars($this->session->userdata('passbook_no')); ?></li>
                </ul>
              </div>
            </div>
            <div class="col-md-6">
              <div class="border rounded-3 p-3 h-100">
                <h6 class="mb-3">Mobile Money (Coming Soon)</h6>
                <p class="mb-2 text-muted">Pay via MoMo using your passbook number as reference.</p>
                <div class="alert alert-info mb-0">
                  Mobile money integration is a placeholder and will be enabled after provider setup.
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
