<?php
$member_id = $this->session->userdata('member_id');



/* =========================
   LOAD CLAIMS
========================= */
$claims = $this->db
    ->order_by('claim_date', 'DESC')
    ->get_where('claims', ['member_id' => $member_id])
    ->result_array();
?>

<section class="section">
  <div class="row g-4">
    <div class="col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h5 class="mb-1">My Claims</h5>
              <p class="text-muted mb-0">Review your submitted claims</p>
            </div>
            <span class="badge bg-info"><?php echo count($claims); ?> Claims</span>
          </div>
        </div>

        <div class="card-body p-4">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>Beneficiary</th>
                  <th>Amount</th>
                  <th>Claim Date</th>
                  <th>Status</th>
                  <th>Approved Date</th>
                  <th>Payment Date</th>
                </tr>
              </thead>
              <tbody>
              <?php if (!empty($claims)): ?>
                <?php $i = 1; foreach ($claims as $c): ?>

                <?php
                /* =========================
                   STATUS BADGE LOGIC
                ========================== */
                switch ($c['status']) {
                    case 'APPROVED':
                        $badge = 'bg-success';
                        break;
                    case 'PAID':
                        $badge = 'bg-primary';
                        break;
                    case 'REJECTED':
                        $badge = 'bg-danger';
                        break;
                    case 'PENDING':
                    default:
                        $badge = 'bg-warning text-dark';
                        break;
                }

                /* Get Beneficiary Name */
                $beneficiary = $this->db->get_where('beneficiaries', ['id' => $c['beneficiary_id']])->row();
                $beneficiary_name = $beneficiary ? $beneficiary->fullname : 'N/A';
                ?>

                <tr>
                  <td><?php echo $i++; ?></td>
                  <td><?php echo htmlspecialchars($beneficiary_name); ?></td>
                  <td>E <?= number_format($c['amount'], 2); ?></td>
                  <td><?= date('Y-m-d', strtotime($c['claim_date'])); ?></td>
                  <td>
                    <span class="badge <?php echo $badge; ?>">
                      <?php echo strtoupper($c['status']); ?>
                    </span>
                  </td>
                  <td>
                    <?php
                    echo !empty($c['approved_date']) ? date('Y-m-d', strtotime($c['approved_date'])) : '-';
                    ?>
                  </td>
                  <td>
                    <?php
                    echo !empty($c['payment_date']) ? date('Y-m-d', strtotime($c['payment_date'])) : '-';
                    ?>
                  </td>
                </tr>

                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="7" class="text-center text-muted py-4">
                    <i class="bi bi-file-earmark-text fs-3 d-block mb-2"></i>
                    No claims found.
                  </td>
                </tr>
              <?php endif; ?>
              </tbody>
            </table>
          </div>

          <!-- Claims Instructions -->
          <div class="alert alert-light mt-4 mb-3 border-start border-4 border-info">
            <h6 class="mb-2">How to Submit a Claim:</h6>
            <ul class="mb-0 ps-3">
              <li>Claim at SNAT Burial Scheme within 2 months of the event.</li>
              <li>Prepare certified documents: death certificate, national ID, passbook, and beneficiary ID / birth certificate.</li>
              <li>Submit documents via support or at the SNAT office.</li>
              <li>We will validate the claim and update you on payout timelines.</li>
            </ul>
          </div>

          <!-- Notice -->
          <div class="alert alert-info mt-0 mb-0">
            <i class="bi bi-info-circle me-2"></i>
            Online claims are not yet available. This feature will be coming soon.
          </div>

        </div>
      </div>
    </div>
  </div>
</section>
