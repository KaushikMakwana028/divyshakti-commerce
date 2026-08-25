<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h3 class="fw-bold" style="color: var(--dark-sidebar);">Member Profile Details</h3>
        <p class="text-muted">Detailed audit view of the selected member registration properties.</p>
    </div>
    <div class="col-sm-6 text-sm-end">
        <a href="<?php echo base_url('admin/members'); ?>" class="btn btn-secondary px-4" style="border-radius: 8px;">
            <i class="fa-solid fa-arrow-left me-2"></i> Back to Members
        </a>
    </div>
</div>

<div class="row">
    <!-- Profile summary (Left) -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm text-center p-4" style="border-radius: 12px; border-top: 4px solid var(--primary-pink) !important;">
            <div class="card-body">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold text-white shadow-sm mb-3" style="width: 100px; height: 100px; font-size: 2.5rem; background-color: var(--primary-pink);">
                    <?php echo strtoupper(substr($member->name ?? 'M', 0, 1)); ?>
                </div>

                <h4 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($member->name); ?></h4>
                <p class="text-muted small mb-3"><?php echo htmlspecialchars($member->email); ?></p>

                <div class="bg-light p-3 rounded mb-4" style="border-left: 3px solid var(--primary-gold);">
                    <span class="text-muted small d-block mb-1">Wallet Balance</span>
                    <h3 class="fw-bold text-success m-0">₹<?php echo number_format($member->wallet_balance, 2); ?></h3>
                </div>

                <!-- Add funds directly inside detail page -->
                <button type="button" class="btn fw-semibold text-white w-100 py-2" 
                        data-bs-toggle="modal" 
                        data-bs-target="#loadWalletModal"
                        style="background: linear-gradient(45deg, var(--primary-pink), var(--primary-gold)); border: none; border-radius: 6px;">
                    <i class="fa-solid fa-plus me-2"></i> Load Wallet Funds
                </button>
            </div>
        </div>
    </div>

    <!-- Details and Transactions list (Right) -->
    <div class="col-lg-8 ps-lg-4">
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
            <div class="p-3 text-white" style="background-color: var(--dark-sidebar); border-bottom: 2px solid var(--primary-gold);">
                <h5 class="fw-bold mb-0">Account Details</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-sm-6 border-end">
                        <span class="text-muted small d-block">Referral Code</span>
                        <code class="fs-5 fw-bold text-pink" style="color: var(--primary-pink);"><?php echo htmlspecialchars($member->referral_code); ?></code>
                    </div>
                    <div class="col-sm-6">
                        <span class="text-muted small d-block">Direct Referrals Count</span>
                        <span class="fs-5 fw-bold text-dark"><i class="fa-solid fa-users text-muted me-1"></i> <?php echo $direct_referrals_count; ?> users</span>
                    </div>
                    <hr>
                    <div class="col-sm-6 border-end">
                        <span class="text-muted small d-block">Phone Number</span>
                        <span class="fw-semibold text-dark"><?php echo htmlspecialchars($member->phone); ?></span>
                    </div>
                    <div class="col-sm-6">
                        <span class="text-muted small d-block">Status</span>
                        <?php if ((int)$member->status === 1): ?>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1" style="font-size: 0.75rem;">Active Account</span>
                        <?php else: ?>
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1" style="font-size: 0.75rem;">Blocked Account</span>
                        <?php endif; ?>
                    </div>
                    <hr>
                    <div class="col-12">
                        <span class="text-muted small d-block">Address</span>
                        <span class="text-dark"><?php echo htmlspecialchars($member->address ?: 'No address specified'); ?></span>
                    </div>
                    <hr>
                    <div class="col-12">
                        <span class="text-muted small d-block">Parent Referrer (Sponsor)</span>
                        <?php if ($referrer): ?>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                <span class="fw-semibold text-dark"><?php echo htmlspecialchars($referrer->name); ?></span>
                                <span class="text-muted">(ID: #<?php echo $referrer->id; ?> - <?php echo htmlspecialchars($referrer->email); ?>)</span>
                            </div>
                        <?php else: ?>
                            <span class="text-muted italic">No sponsor (Top level root member)</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Wallet Logs -->
        <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
            <div class="p-3 text-white" style="background-color: var(--dark-sidebar); border-bottom: 2px solid var(--primary-gold);">
                <h5 class="fw-bold mb-0">Recent Wallet Transactions (Last 10 Logs)</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Txn ID</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Source</th>
                            <th>Remark / Action</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($transactions)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No transactions logged yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($transactions as $t): ?>
                                <tr>
                                    <td class="ps-3 fw-bold">#<?php echo $t->id; ?></td>
                                    <td>
                                        <?php if ($t->type === 'credit'): ?>
                                            <span class="badge bg-success-subtle text-success px-2 py-1" style="font-size: 0.75rem;">CREDIT</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger-subtle text-danger px-2 py-1" style="font-size: 0.75rem;">DEBIT</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="fw-bold <?php echo ($t->type === 'credit') ? 'text-success' : 'text-danger'; ?>">
                                        <?php echo ($t->type === 'credit') ? '+' : '-'; ?>₹<?php echo number_format($t->amount, 2); ?>
                                    </td>
                                    <td>
                                        <code style="font-size: 0.8rem; background-color: #f3f4f6; color: #374151; padding: 2px 6px; border-radius: 4px;">
                                            <?php echo htmlspecialchars($t->source); ?>
                                        </code>
                                    </td>
                                    <td class="text-dark"><?php echo htmlspecialchars($t->remark ?: '-'); ?></td>
                                    <td class="text-muted small"><?php echo date('M d, Y H:i', strtotime($t->created_at)); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Load Wallet Modal (reused inside details page) -->
<div class="modal fade" id="loadWalletModal" tabindex="-1" aria-labelledby="loadWalletModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header text-white p-4" style="background: linear-gradient(135deg, var(--dark-sidebar) 0%, #1f2937 100%); border-bottom: 3px solid var(--primary-gold);">
                <h5 class="modal-title fw-bold" id="loadWalletModalLabel">
                    <i class="fa-solid fa-coins me-2 text-warning"></i> Load Wallet Funds
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?php echo base_url('admin/members/wallet/' . $member->id); ?>">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold mb-1">Member Account</label>
                        <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($member->name); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold mb-1">Current Wallet Balance</label>
                        <input type="text" class="form-control bg-light text-success fw-bold" value="₹<?php echo number_format($member->wallet_balance, 2); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label fw-semibold text-dark">Amount to Add (₹)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                            <input type="number" step="0.01" name="amount" class="form-control" placeholder="0.00" min="0.01" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="remark" class="form-label fw-semibold text-dark">Transaction Remark</label>
                        <input type="text" name="remark" class="form-control" placeholder="e.g. Approved loading bonus" required>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3 border-0 justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn fw-semibold text-white px-4" style="background: linear-gradient(45deg, var(--primary-pink), var(--primary-gold)); border: none; border-radius: 6px; box-shadow: 0 4px 10px rgba(236, 64, 122, 0.15);">
                        Credit Wallet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
