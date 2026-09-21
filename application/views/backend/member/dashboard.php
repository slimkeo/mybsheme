<?php
$member_id = $this->session->userdata('member_id');

/* =========================
   LOAD DATA
========================= */
/* =========================
   LOAD DATA
========================= */
$beneficiaries = $this->Beneficiary_model->get_by_member($member_id);

$summary = $this->Beneficiary_model->get_payable_summary($member_id);

$total_beneficiaries     = $summary['total_beneficiaries'];
$payable_beneficiaries   = $summary['payable_beneficiaries'];
$beneficiary_fee         = $summary['payable_beneficiary_fee'];

$total_monthly           = $this->Beneficiary_model->get_total_monthly_fee($member_id);

$fees = $this->Beneficiary_model->get_fee_settings();

$principal_fee = $fees['principal_fee'];
$member_fee    = $fees['member_fee'];
$spouse_fee    = $fees['spouse_fee'];

// For display breakdown only (members vs spouses among payable beneficiaries)
$non_payable_statuses = [
  'BENEFITTED - REPLACED',
  'DECEASED - REPLACED',
  'DELETED',
  'LATE NOT BENEFITTED',
  'PASSBOOK REPLACEMENT',
  'LATE NOT BENEFITTED - REPLACED'
];

$payable_list = array_filter($beneficiaries, function($b) use ($non_payable_statuses) {
  $status = trim($b['status'] ?? '');
  return !in_array($status, $non_payable_statuses, true);
});

$payable_members_count = count(array_filter($payable_list, function($b) {
  return $b['is_spouse'] == 0;
}));

$payable_spouses_count = count(array_filter($payable_list, function($b) {
  return $b['is_spouse'] == 1;
}));

/* =========================
   RECENT TRANSACTIONS (LAST 7)
========================= */
$recent_transactions = $this->Statement_model->get_by_member_paginated($member_id, 7, 0);

/* =========================
   CLAIMS COUNT FOR CARD
========================= */
$claims_count = $this->db
    ->where('member_id', $member_id)
    ->count_all_results('claims');
?>

<section id="dashboard" class="dashboard section">

  <!-- Welcome Section -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h2 class="mb-2">
                Welcome Back
                <?php
                $member_name = $this->session->userdata('name');
                echo $member_name ? ', ' . htmlspecialchars($member_name) : '!';
                ?>
              </h2>
              <p class="text-muted mb-0">
                Manage your SNAT Burial Scheme membership and contributions
              </p>
            </div>
            <div class="text-end">
              <span class="badge bg-success fs-6 px-3 py-2">Active Member</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Statistics Cards -->
  <div class="row gy-4 mb-4">

    <div class="col-lg-3 col-md-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body p-4">
          <h6 class="text-muted mb-2">Claims</h6>
          <h3 class="mb-0 text-primary"><?= (int) $claims_count; ?></h3>
          <small class="text-muted">Total claims you have submitted</small>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-md-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body p-4">
          <h6 class="text-muted mb-2">Monthly Contribution</h6>
          <h3 class="mb-0 text-info">
            E <?= number_format($total_monthly, 2); ?>
          </h3>
          <small class="text-muted">
            Principal + <?= $payable_beneficiaries; ?> Beneficiaries
          </small>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-md-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body p-4">
          <h6 class="text-muted mb-2">Beneficiaries</h6>
          <h3 class="mb-0 text-success"><?= $total_beneficiaries; ?></h3>
          <small class="text-muted"><?= $payable_beneficiaries; ?> Payable</small>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-md-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body p-4">
          <h6 class="text-muted mb-2">Policy Status</h6>
          <h3 class="mb-0 text-warning">Active</h3>
          <small class="text-success">
            Coverage: E <?= number_format($principal_payout, 2); ?>
          </small>
        </div>
      </div>
    </div>

  </div>

  <!-- Main Content -->
  <div class="row">

    <!-- Left Column -->
    <div class="col-lg-8">

      <!-- Recent Transactions -->
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
              <i class="bi bi-clock-history me-2"></i>Recent Transactions
            </h5>
            <a href="<?= base_url(); ?>index.php?burial/statement" class="btn btn-sm btn-outline-primary">
              View All
            </a>
          </div>
        </div>

        <div class="card-body p-4">
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Description</th>
                  <th>Amount</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($recent_transactions)): ?>
                  <tr>
                    <td colspan="4" class="text-center text-muted">No transactions yet.</td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($recent_transactions as $tx): ?>
                    <tr>
                      <td><?= date('Y-m-d', strtotime($tx['date'])); ?></td>
                      <td><?= htmlspecialchars($tx['description']); ?></td>
                      <td class="<?= $tx['amount'] >= 0 ? 'text-success' : 'text-danger'; ?>">
                        <?= $tx['amount'] >= 0 ? '+' : '-' ?>E <?= number_format(abs($tx['amount']), 2); ?>
                      </td>
                      <td>
                        <?php if ($tx['status'] == 'Paid'): ?>
                          <span class="badge bg-success"><?= $tx['status']; ?></span>
                        <?php elseif ($tx['status'] == 'Pending'): ?>
                          <span class="badge bg-warning text-dark"><?= $tx['status']; ?></span>
                        <?php else: ?>
                          <span class="badge bg-danger"><?= $tx['status']; ?></span>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
          <h5 class="mb-0">
            <i class="bi bi-lightning-charge me-2"></i>Quick Actions
          </h5>
        </div>
        <div class="card-body p-4">
          <div class="row g-3">

            <div class="col-md-6">
              <a href="<?= base_url(); ?>index.php?burial/payments" class="btn btn-primary w-100 p-3 d-flex align-items-center justify-content-center">
                <i class="bi bi-credit-card me-2 fs-5"></i>
                <span>Pay Contribution</span>
              </a>
            </div>

            <div class="col-md-6">
              <a href="<?= base_url(); ?>index.php?burial/beneficiaries" class="btn btn-outline-primary w-100 p-3 d-flex align-items-center justify-content-center">
                <i class="bi bi-people me-2 fs-5"></i>
                <span>Manage Beneficiaries</span>
              </a>
            </div>

            <div class="col-md-6">
              <a href="<?= base_url(); ?>index.php?burial/statement" class="btn btn-outline-info w-100 p-3 d-flex align-items-center justify-content-center">
                <i class="bi bi-file-earmark-text me-2 fs-5"></i>
                <span>View Statement</span>
              </a>
            </div>

            <div class="col-md-6">
              <a href="<?= base_url(); ?>index.php?burial/policy" class="btn btn-outline-success w-100 p-3 d-flex align-items-center justify-content-center">
                <i class="bi bi-file-earmark-medical me-2 fs-5"></i>
                <span>Policy Details</span>
              </a>
            </div>

          </div>
        </div>
      </div>

    </div>

    <!-- Right Column -->
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
          <h5 class="mb-0">Account Information</h5>
        </div>

        <div class="card-body p-4">

          <div class="mb-3">
            <small class="text-muted d-block">Policy Number</small>
            <strong>
              <?php
              $passbook_no = $this->session->userdata('passbook_no');
              echo $member_id ? 'SNAT-' . htmlspecialchars($member_id) : 'N/A';
              ?>
            </strong>
          </div>

          <div class="mb-3">
            <small class="text-muted d-block">Member Since</small>
            <strong>
              <?php
              $member = $this->db->get_where('members', ['id' => $member_id])->row();
              echo ($member && isset($member->createdate))
                  ? date('F Y', strtotime($member->createdate))
                  : 'N/A';
              ?>
            </strong>
          </div>

          <div class="mb-3">
            <small class="text-muted d-block">Coverage Amount</small>
            <strong class="text-success">
              E <?= number_format($principal_payout, 2); ?>
            </strong>
          </div>

          <div>
            <small class="text-muted d-block">Next Payment Due</small>
            <strong><?= date('F d, Y', strtotime('+1 month')); ?></strong>
          </div>

        </div>
      </div>
    </div>

  </div>

</section>
