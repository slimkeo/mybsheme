<?php
$member_id = $this->session->userdata('member_id');

/* =========================
   LOAD DATA
========================= */
$beneficiaries = $this->Beneficiary_model->get_by_member($member_id);
$summary       = $this->Beneficiary_model->get_payable_summary($member_id);

$payable_beneficiaries = $summary['payable_beneficiaries'];
$total_beneficiaries   = $summary['total_beneficiaries'];

/* =========================
   FEES
========================= */
$principal_fee = (float) $this->db
    ->get_where('settings', ['type' => 'principal_fee'])
    ->row()->description;

$member_fee = (float) $this->db
    ->get_where('settings', ['type' => 'member_fee'])
    ->row()->description;

$beneficiary_fee = $member_fee * $payable_beneficiaries;
$total_monthly   = $principal_fee + $beneficiary_fee;
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
                <?php foreach ($beneficiaries as $b): ?>

<?php
/* =========================
   MATURITY STATUS (FINAL)
========================= */

$status   = strtoupper(trim($b['status']));
$maturity = $this->Beneficiary_model->is_matured($b['id']);

/* =========================
   TERMINAL STATUSES
========================= */
if (
    $status === 'BENEFITTED' ||
    $status === 'BENEFITTED - REPLACED' ||
    $status === 'DECEASED - REPLACED' ||
    $status === 'DELETED'
) {

    $label = $status;
    $badge = 'bg-danger';

/* =========================
   REPLACEE = ALWAYS MATURED
========================= */
} elseif ($status === 'REPLACEE') {

    $label = 'Matured';
    $badge = 'bg-success';

/* =========================
   1 YEAR RULE (MODEL)
========================= */
} elseif ($maturity === 'MATURED') {

    $label = 'Matured';
    $badge = 'bg-success';

/* =========================
   DEFAULT
========================= */
} else {

    $label = 'Waiting';
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
