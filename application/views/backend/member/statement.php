<?php
$member_id = $this->session->userdata('member_id');

$member_id = 1109317;

// Pagination settings
$per_page = 12;
$page = (int) $this->input->get('page');
if ($page < 1) {
    $page = 1;
}
$offset = ($page - 1) * $per_page;

// Get total rows and current page results
$total_rows = $this->Statement_model->count_by_member($member_id);
$statements = $this->Statement_model->get_by_member_paginated($member_id, $per_page, $offset);

$total_pages = $total_rows > 0 ? (int) ceil($total_rows / $per_page) : 1;
?>

<section class="section">
  <div class="container">
    <h2 class="mb-4">My last 12 Subscriptions</h2>

    <?php if(empty($statements)): ?>
      <div class="alert alert-info">You have no transactions yet.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>Date</th>
              <th>Description</th>
              <th>Type</th>
              <th>Amount (E)</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($statements as $s): ?>
              <tr>
                <td><?= date('F d, Y', strtotime($s['date'])); ?></td>
                <td><?= htmlspecialchars($s['description']); ?></td>
                <td class="text-capitalize"><?= $s['type']; ?></td>
                <td class="<?= $s['amount'] >= 0 ? 'text-success' : 'text-danger'; ?>">
                  <?= $s['amount'] >= 0 ? '+' : '-' ?>E <?= number_format(abs($s['amount']),2); ?>
                </td>
                <td>
                  <?php if($s['status'] == 'Paid'): ?>
                    <span class="badge bg-success"><?= $s['status']; ?></span>
                  <?php elseif($s['status'] == 'Pending'): ?>
                    <span class="badge bg-warning text-dark"><?= $s['status']; ?></span>
                  <?php else: ?>
                    <span class="badge bg-danger"><?= $s['status']; ?></span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>


    <?php endif; ?>

    <div class="alert alert-info mt-4">
      Online claims or manual adjustments will appear here once processed.
    </div>
  </div>
</section>
