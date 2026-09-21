<?php
$member_id = $this->session->userdata('member_id');
$member_id = 1100001;

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


?>

<section class="section">
  <div class="row g-4">
    <div class="col-12">
      <div class="card border-0 shadow-sm">

        <!-- HEADER -->
        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h5 class="mb-1">Beneficiaries</h5>
              <p class="text-muted mb-0">
                Once added, beneficiaries cannot be edited or deleted.
              </p>
            </div>
            <span class="badge bg-primary">
              <?= $total_beneficiaries ?> Total
            </span>
          </div>
        </div>

        <div class="card-body p-4">

          <!-- TABLE -->
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead>
                <tr>
                  <th>Full Name</th>
                  <th>Gender</th>
                  <th>DOB</th>
                  <th>Status</th>
                  <th>Maturity Status</th>
                </tr>
              </thead>
              <tbody>

              <?php if (empty($beneficiaries)): ?>
                <tr>
                  <td colspan="4" class="text-center text-muted">
                    No beneficiaries added yet
                  </td>
                </tr>
              <?php else: ?>
<?php

$count = 1;
foreach($beneficiaries as $b): 
  // Calculate maturity status
  $submission_date = $b['submission_date'];
  
  // Handle different date formats (dd-mm-yyyy or yyyy-mm-dd)
  $submission_timestamp = false;
  if (strpos($submission_date, '-') !== false) {
    $date_parts = explode('-', $submission_date);
    if (count($date_parts) == 3 && intval($date_parts[0]) > 12) {
      $submission_timestamp = strtotime($submission_date);
    } else {
      $submission_timestamp = strtotime($date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0]);
    }
  } else {
    $submission_timestamp = strtotime($submission_date);
  }
  
  $today = strtotime(date('Y-m-d'));
  $one_year_ago = strtotime('-1 year', $today);
  $is_matured = ($submission_timestamp && $submission_timestamp <= $one_year_ago);
  
  // Determine maturity status text and badge class
  if ($b['status'] == 'BENEFITTED' || $b['status'] == 'BENEFITTED - REPLACED'| $b['status'] == 'DECEASED - REPLACED'| $b['status'] == 'DELETED' | $b['status'] == 'LATE NOT BENEFITTED'| $b['status'] == 'LATE NOT BENEFITTED - REPLACED'| $b['status'] =='PASSBOOK REPLACEMENT') {
    $maturity_status = $b['status'];
    $maturity_badge = 'bg-danger';
  }// elseif ($b['status'] == 'REPLACEE') {
  //	$maturity_status = 'Matured';
  //	$maturity_badge = 'label-success';
  //	$row_class = 'success';
  //} 
  elseif ($is_matured) {
    $maturity_status = 'Matured';
    $badge = 'bg-success';
  } else {
    $maturity_status = 'Waiting';
    $badge = 'bg-warning text-dark';
  }

?>


                  <tr>
                    <td><?= htmlspecialchars($b['fullname']) ?></td>
                    <td><?= htmlspecialchars($b['gender']) ?></td>
                    <td><?= htmlspecialchars($b['dob']) ?></td>
                    <td><?= htmlspecialchars($b['status']) ?></td>
                    <td>
                      <span class="badge <?= $badge ?>">
                        <?= $label ?>
                      </span>
                    </td>
                  </tr>

                <?php endforeach; ?>
              <?php endif; ?>

              </tbody>
            </table>
          </div>

          <!-- POLICY COST SUMMARY -->
          <div class="d-flex justify-content-between align-items-center mt-4 p-3 border rounded bg-light">
            <div>
              <strong>Monthly Policy Breakdown</strong>
              <div class="text-muted small">
                Principal Member: E<?= number_format($principal_fee, 2) ?><br>
                Total Beneficiaries: <?php echo $total_beneficiaries; ?> <br>
                Payable Beneficiaries (<?= $payable_beneficiaries ?> ×
                E<?= number_format($member_fee, 2) ?>):
                E<?= number_format($beneficiary_fee, 2) ?>
              </div>
            </div>
            <div class="text-end">
              <h4 class="mb-0 text-primary">
                E<?= number_format($total_monthly, 2) ?> / month
              </h4>
            </div>
          </div>

          <!-- WARNING -->
          <div class="alert alert-warning mt-3 mb-0">
            <strong>Important:</strong>
            Adding a beneficiary will immediately increase your monthly
            subscription. Once added, beneficiaries cannot be edited or deleted.
          </div>

        </div>
      </div>
    </div>
  </div>
</section>
