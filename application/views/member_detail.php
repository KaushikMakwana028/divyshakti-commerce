<?php
// Maps a raw wallet-log `source` value to a readable label + a color class.
// Add new source keys here as they're introduced elsewhere in the app;
// anything not listed falls back to a neutral tag with an auto-formatted label.
$mpdSourceMap = [
    'referral_commission' => ['label' => 'Referral Commission', 'class' => 'mpd-tag-commission'],
    'purchase'             => ['label' => 'Purchase',            'class' => 'mpd-tag-purchase'],
    'admin_credit'         => ['label' => 'Admin Credit',        'class' => 'mpd-tag-admin-credit'],
    'admin_debit'          => ['label' => 'Admin Debit',         'class' => 'mpd-tag-admin-debit'],
    'withdrawal'           => ['label' => 'Withdrawal',          'class' => 'mpd-tag-withdrawal'],
    'signup_bonus'         => ['label' => 'Signup Bonus',        'class' => 'mpd-tag-bonus'],
];
?>

<div class="mpd-view">

    <?php if (!isset($is_ajax) || !$is_ajax): ?>
        <div class="mpd-header">
            <div class="mpd-header-text">
                <span class="mpd-eyebrow"><i class="fa-solid fa-id-card-clip"></i> Member Record</span>
                <h1 class="mpd-title">Member Profile Details</h1>
                <p class="mpd-subtitle">Detailed audit view of the selected member's registration properties.</p>
            </div>
            <a href="<?php echo base_url('admin/members'); ?>" class="mpd-back-btn">
                <i class="fa-solid fa-arrow-left"></i> Back to Members
            </a>
        </div>
    <?php endif; ?>

    <div class="mpd-grid mpd-grid-top">

        <!-- Profile summary (Left) -->
        <div class="mpd-col-side">
            <div class="mpd-card mpd-profile-card">

                <?php 
                    $mpd_avatar_url = null;
                    if (!empty($member->profile_image)) {
                        if (filter_var($member->profile_image, FILTER_VALIDATE_URL)) {
                            $mpd_avatar_url = $member->profile_image;
                        } else {
                            $clean_img = ltrim($member->profile_image, '/');
                            if (file_exists(FCPATH . $clean_img)) {
                                $mpd_avatar_url = base_url($clean_img);
                            } else if (file_exists(FCPATH . 'uploads/profile_images/' . basename($clean_img))) {
                                $mpd_avatar_url = base_url('uploads/profile_images/' . basename($clean_img));
                            } else {
                                $mpd_avatar_url = base_url($clean_img);
                            }
                        }
                    }
                ?>
                <?php if (!empty($mpd_avatar_url)): ?>
                    <img src="<?php echo htmlspecialchars($mpd_avatar_url); ?>"
                        class="mpd-avatar" 
                        alt="<?php echo htmlspecialchars($member->name ?? 'Member'); ?>"
                        onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                    <div class="mpd-avatar mpd-avatar-fallback" style="display: none;">
                        <?php echo strtoupper(substr($member->name ?? 'M', 0, 1)); ?>
                    </div>
                <?php else: ?>
                    <div class="mpd-avatar mpd-avatar-fallback">
                        <?php echo strtoupper(substr($member->name ?? 'M', 0, 1)); ?>
                    </div>
                <?php endif; ?>

                <h2 class="mpd-member-name"><?php echo htmlspecialchars($member->name ?? 'Member'); ?></h2>
                <div class="mb-2">
                    <span class="badge bg-dark-subtle text-dark border font-monospace fw-bold px-2.5 py-1" style="font-size: 0.82rem; letter-spacing: 0.5px;">
                        <i class="fa-solid fa-id-badge text-primary me-1"></i>ID: <?php echo htmlspecialchars($member->custom_id ?? '-'); ?>
                    </span>
                </div>
                <?php if (!empty($member->email)): ?>
                    <p class="mpd-member-email"><i class="fa-regular fa-envelope me-1 text-muted"></i><?php echo htmlspecialchars($member->email); ?></p>
                <?php else: ?>
                    <p class="mpd-member-email text-muted fst-italic" style="font-size: 0.84rem;"><i class="fa-regular fa-envelope-open me-1 opacity-50"></i>No email provided</p>
                <?php endif; ?>

                <div class="mpd-balance-panel">
                    <span class="mpd-balance-label">Wallet Balance</span>
                    <span class="mpd-balance-amount">₹<?php echo number_format($member->wallet_balance, 2); ?></span>
                </div>

                <!-- Profile Completion & Admin Activation Status -->
                <div class="mt-3 text-start w-100 p-3 rounded" style="background: rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.06);">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small fw-semibold text-muted">Profile Completion</span>
                        <?php 
                            $detail_pct = (int)($member->profile_completion_percentage ?? 0);
                            if ($detail_pct >= 100) {
                                $badge_cls = 'bg-success text-white';
                                $bar_cls = 'bg-success';
                            } elseif ($detail_pct > 0) {
                                $badge_cls = 'bg-primary text-white';
                                $bar_cls = 'bg-primary';
                            } else {
                                $badge_cls = 'bg-slate text-dark border border-secondary fw-bold';
                                $bar_cls = 'bg-secondary opacity-25';
                            }
                        ?>
                        <span class="badge <?php echo $badge_cls; ?> px-2 py-1" style="font-size: 0.78rem;">
                            <?php echo $detail_pct; ?>% Profile
                        </span>
                    </div>
                    <div class="progress mb-2" style="height: 6px; background-color: #e2e8f0; border-radius: 999px;">
                        <div class="progress-bar <?php echo $bar_cls; ?>"
                             role="progressbar"
                             style="width: <?php echo max(5, $detail_pct); ?>%; border-radius: 999px;"></div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small fw-semibold text-muted">Profile Activation</span>
                        <?php if (!empty($member->is_profile_active)): ?>
                            <span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i> Active</span>
                        <?php else: ?>
                            <span class="badge bg-danger"><i class="fa-solid fa-clock me-1"></i> Pending Activation</span>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                        <span class="small fw-semibold text-muted">Activation Commission</span>
                        <?php if (!empty($member->is_commission_distributed)): ?>
                            <span class="badge bg-primary"><i class="fa-solid fa-check me-1"></i> Distributed</span>
                        <?php else: ?>
                            <span class="badge bg-secondary"><i class="fa-solid fa-hourglass-start me-1"></i> Not Distributed</span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Activate/Deactivate Profile Action -->
                <a href="<?php echo base_url('admin/members/activate_profile/' . $member->id); ?>"
                   class="mpd-btn w-100 mt-2 text-center"
                   style="<?php echo empty($member->is_profile_active) ? 'background-color: #198754; color: #fff; border-color: #198754;' : 'background-color: #fff; color: #dc3545; border: 1px solid #dc3545;'; ?>"
                   data-confirm="<?php echo (!empty($member->is_profile_active)) ? 'Are you sure you want to deactivate this member profile?' : 'Are you sure you want to activate & approve this member profile? MLM referral commissions will be distributed once to eligible upline wallets.'; ?>"
                   data-confirm-title="<?php echo (!empty($member->is_profile_active)) ? 'Deactivate Profile?' : 'Activate Profile?'; ?>"
                   data-confirm-btn="<?php echo (!empty($member->is_profile_active)) ? 'Yes, Deactivate' : 'Yes, Activate & Approve'; ?>"
                   data-confirm-icon="<?php echo (!empty($member->is_profile_active)) ? 'warning' : 'question'; ?>"
                   data-confirm-danger="<?php echo (!empty($member->is_profile_active)) ? 'true' : 'false'; ?>">
                    <i class="fa-solid <?php echo (!empty($member->is_profile_active)) ? 'fa-ban' : 'fa-check-double'; ?> me-1"></i>
                    <?php echo (!empty($member->is_profile_active)) ? 'Deactivate Profile' : 'Activate Profile'; ?>
                </a>

                <!-- Add funds directly inside detail page -->
                <button type="button" class="mpd-btn mpd-btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#loadWalletModal">
                    <i class="fa-solid fa-plus"></i> Load Wallet Funds
                </button>
                <a href="<?php echo base_url('admin/members/edit/' . $member->id); ?>"
                    class="mpd-btn mpd-btn-edit">
                    <i class="fa-solid fa-pen-to-square"></i> Edit Member Details
                </a>
                <a href="<?php echo base_url('admin/members/network?user_id=' . $member->id); ?>"
                    class="mpd-btn mpd-btn-outline">
                    <i class="fa-solid fa-sitemap"></i> View Network Tree
                </a>
            </div>
        </div>

        <!-- Details and Transactions list (Right) -->
        <div class="mpd-col-main">

            <div class="mpd-card mpd-section-card">
                <div class="mpd-section-header">
                    <h5><i class="fa-solid fa-address-card"></i> Account Details</h5>
                </div>

                <div class="mpd-info-grid">
                    <div class="mpd-info-tile">
                        <span class="mpd-info-icon mpd-icon-gold"><i class="fa-solid fa-id-badge"></i></span>
                        <div>
                            <span class="mpd-info-label">Unique User ID</span>
                            <code class="mpd-info-value mpd-info-code font-monospace fw-bold" style="color: #0d6efd;"><?php echo htmlspecialchars($member->custom_id ?? '-'); ?></code>
                        </div>
                    </div>

                    <div class="mpd-info-tile">
                        <span class="mpd-info-icon mpd-icon-pink"><i class="fa-solid fa-tag"></i></span>
                        <div>
                            <span class="mpd-info-label">Referral Code</span>
                            <code class="mpd-info-value mpd-info-code"><?php echo htmlspecialchars($member->referral_code); ?></code>
                        </div>
                    </div>

                    <div class="mpd-info-tile">
                        <span class="mpd-info-icon mpd-icon-gold"><i class="fa-solid fa-users"></i></span>
                        <div>
                            <span class="mpd-info-label">Direct Referrals</span>
                            <span class="mpd-info-value"><?php echo $direct_referrals_count; ?> members</span>
                        </div>
                    </div>

                    <div class="mpd-info-tile">
                        <span class="mpd-info-icon mpd-icon-slate"><i class="fa-solid fa-phone"></i></span>
                        <div>
                            <span class="mpd-info-label">Phone Number</span>
                            <span class="mpd-info-value"><?php echo htmlspecialchars($member->phone ?? '-'); ?></span>
                        </div>
                    </div>

                    <div class="mpd-info-tile">
                        <span class="mpd-info-icon mpd-icon-pink"><i class="fa-solid fa-venus-mars"></i></span>
                        <div>
                            <span class="mpd-info-label">Gender</span>
                            <span class="mpd-info-value"><?php echo !empty($member->gender) ? ucfirst(htmlspecialchars($member->gender)) : '<span class="text-muted">Not specified</span>'; ?></span>
                        </div>
                    </div>

                    <div class="mpd-info-tile">
                        <span class="mpd-info-icon <?php echo ((int)$member->status === 1) ? 'mpd-icon-success' : 'mpd-icon-danger'; ?>">
                            <i class="fa-solid <?php echo ((int)$member->status === 1) ? 'fa-circle-check' : 'fa-circle-xmark'; ?>"></i>
                        </span>
                        <div>
                            <span class="mpd-info-label">Status</span>
                            <?php if ((int)$member->status === 1): ?>
                                <span class="mpd-status mpd-status-active">Active Account</span>
                            <?php else: ?>
                                <span class="mpd-status mpd-status-blocked">Blocked Account</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mpd-info-tile mpd-info-tile-wide">
                        <span class="mpd-info-icon mpd-icon-slate"><i class="fa-solid fa-location-dot"></i></span>
                        <div>
                            <span class="mpd-info-label">Address</span>
                            <span class="mpd-info-value"><?php echo htmlspecialchars($member->address ?: 'No address specified'); ?></span>
                        </div>
                    </div>

                    <div class="mpd-info-tile mpd-info-tile-wide">
                        <span class="mpd-info-icon mpd-icon-gold"><i class="fa-solid fa-handshake"></i></span>
                        <div>
                            <span class="mpd-info-label">Parent Referrer (Sponsor)</span>
                            <?php if ($referrer): ?>
                                <span class="mpd-info-value">
                                    <?php echo htmlspecialchars($referrer->name ?? 'Referrer'); ?>
                                    <span class="mpd-sponsor-meta">#<?php echo $referrer->id; ?> · <?php echo htmlspecialchars($referrer->email ?? $referrer->phone ?? ''); ?></span>
                                </span>
                            <?php else: ?>
                                <span class="mpd-info-value mpd-info-muted">No sponsor (top-level root member)</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KYC Identity Documents Card -->
            <div class="mpd-card mpd-section-card mt-3">
                <div class="mpd-section-header">
                    <h5><i class="fa-solid fa-id-card"></i> KYC & Identity Documents</h5>
                    <?php if (!empty($member->is_profile_completed)): ?>
                        <span class="badge bg-success">100% Completed</span>
                    <?php else: ?>
                        <span class="badge bg-warning text-dark"><?php echo (int)($member->profile_completion_percentage ?? 0); ?>% Completed</span>
                    <?php endif; ?>
                </div>

                <div class="mpd-info-grid">
                    <div class="mpd-info-tile">
                        <span class="mpd-info-icon mpd-icon-pink"><i class="fa-solid fa-fingerprint"></i></span>
                        <div>
                            <span class="mpd-info-label">Aadhar Number</span>
                            <span class="mpd-info-value fw-semibold"><?php echo !empty($member->aadhar_number) ? htmlspecialchars($member->aadhar_number) : '<span class="text-muted">Not provided</span>'; ?></span>
                        </div>
                    </div>

                    <div class="mpd-info-tile">
                        <span class="mpd-info-icon mpd-icon-gold"><i class="fa-solid fa-file-image"></i></span>
                        <div>
                            <span class="mpd-info-label">Aadhar Document / Image</span>
                            <?php if (!empty($member->aadhar_image)): ?>
                                <a href="<?php echo base_url(ltrim($member->aadhar_image, '/')); ?>" target="_blank" class="btn btn-sm btn-outline-primary mt-1">
                                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> View / Download Document
                                </a>
                            <?php else: ?>
                                <span class="mpd-info-value text-muted">No file uploaded</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mpd-info-tile">
                        <span class="mpd-info-icon mpd-icon-slate"><i class="fa-solid fa-id-badge"></i></span>
                        <div>
                            <span class="mpd-info-label">PAN Number</span>
                            <span class="mpd-info-value fw-semibold"><?php echo !empty($member->pan_number) ? htmlspecialchars($member->pan_number) : '<span class="text-muted">Not provided</span>'; ?></span>
                        </div>
                    </div>

                    <div class="mpd-info-tile">
                        <span class="mpd-info-icon mpd-icon-gold"><i class="fa-solid fa-file-image"></i></span>
                        <div>
                            <span class="mpd-info-label">PAN Document / Image</span>
                            <?php if (!empty($member->pan_image)): ?>
                                <a href="<?php echo base_url(ltrim($member->pan_image, '/')); ?>" target="_blank" class="btn btn-sm btn-outline-primary mt-1">
                                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> View / Download Document
                                </a>
                            <?php else: ?>
                                <span class="mpd-info-value text-muted">No file uploaded</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bank Account Details Card -->
            <div class="mpd-card mpd-section-card mt-3">
                <div class="mpd-section-header">
                    <h5><i class="fa-solid fa-building-columns"></i> Bank Account Information</h5>
                </div>

                <div class="mpd-info-grid">
                    <div class="mpd-info-tile">
                        <span class="mpd-info-icon mpd-icon-slate"><i class="fa-solid fa-user-check"></i></span>
                        <div>
                            <span class="mpd-info-label">Account Holder Name</span>
                            <span class="mpd-info-value"><?php echo !empty($member->account_holder_name) ? htmlspecialchars($member->account_holder_name) : '<span class="text-muted">Not provided</span>'; ?></span>
                        </div>
                    </div>

                    <div class="mpd-info-tile">
                        <span class="mpd-info-icon mpd-icon-slate"><i class="fa-solid fa-landmark"></i></span>
                        <div>
                            <span class="mpd-info-label">Bank Name</span>
                            <span class="mpd-info-value"><?php echo !empty($member->bank_name) ? htmlspecialchars($member->bank_name) : '<span class="text-muted">Not provided</span>'; ?></span>
                        </div>
                    </div>

                    <div class="mpd-info-tile">
                        <span class="mpd-info-icon mpd-icon-gold"><i class="fa-solid fa-money-check"></i></span>
                        <div>
                            <span class="mpd-info-label">Account Number</span>
                            <code class="mpd-info-value mpd-info-code"><?php echo !empty($member->account_number) ? htmlspecialchars($member->account_number) : 'Not provided'; ?></code>
                        </div>
                    </div>

                    <div class="mpd-info-tile">
                        <span class="mpd-info-icon mpd-icon-pink"><i class="fa-solid fa-hashtag"></i></span>
                        <div>
                            <span class="mpd-info-label">IFSC Code</span>
                            <code class="mpd-info-value mpd-info-code"><?php echo !empty($member->ifsc_code) ? htmlspecialchars($member->ifsc_code) : 'Not provided'; ?></code>
                        </div>
                    </div>

                    <div class="mpd-info-tile">
                        <span class="mpd-info-icon mpd-icon-slate"><i class="fa-solid fa-wallet"></i></span>
                        <div>
                            <span class="mpd-info-label">Account Type</span>
                            <span class="mpd-info-value"><?php echo !empty($member->account_type) ? htmlspecialchars($member->account_type) : '<span class="text-muted">Not provided</span>'; ?></span>
                        </div>
                    </div>

                    <div class="mpd-info-tile">
                        <span class="mpd-info-icon mpd-icon-slate"><i class="fa-solid fa-map-pin"></i></span>
                        <div>
                            <span class="mpd-info-label">Branch Name</span>
                            <span class="mpd-info-value"><?php echo !empty($member->branch_name) ? htmlspecialchars($member->branch_name) : '<span class="text-muted">Not provided</span>'; ?></span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Recent Wallet Logs — full width, own row -->
    <div class="mpd-card mpd-section-card mpd-txn-card">
        <div class="mpd-section-header">
            <h5><i class="fa-solid fa-clock-rotate-left"></i> Recent Wallet Transactions <span class="mpd-section-sub" id="mpdTxnCountLabel">Loading…</span></h5>
        </div>

        <?php if (empty($transactions)): ?>
            <div class="mpd-empty-txn">
                <i class="fa-solid fa-receipt"></i>
                <p>No transactions logged yet.</p>
            </div>
        <?php else: ?>
            <div class="mpd-table-wrap">
                <table class="mpd-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Source</th>
                            <th>Remark / Action</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $txn_idx = 1;
                        foreach ($transactions as $t):
                            $srcInfo = $mpdSourceMap[$t->source] ?? [
                                'label' => ucwords(str_replace('_', ' ', $t->source)),
                                'class' => 'mpd-tag-default',
                            ];
                        ?>
                            <tr>
                                <td data-label="#" class="mpd-td-idx"><?php echo $txn_idx++; ?></td>
                                <td data-label="Type">
                                    <?php if ($t->type === 'credit'): ?>
                                        <span class="mpd-type mpd-type-credit"><i class="fa-solid fa-arrow-down"></i> CREDIT</span>
                                    <?php else: ?>
                                        <span class="mpd-type mpd-type-debit"><i class="fa-solid fa-arrow-up"></i> DEBIT</span>
                                    <?php endif; ?>
                                </td>
                                <td data-label="Amount" class="mpd-td-amount <?php echo ($t->type === 'credit') ? 'mpd-amount-credit' : 'mpd-amount-debit'; ?>">
                                    <?php echo ($t->type === 'credit') ? '+' : '-'; ?>₹<?php echo number_format($t->amount, 2); ?>
                                </td>
                                <td data-label="Source">
                                    <span class="mpd-tag <?php echo $srcInfo['class']; ?>" title="<?php echo htmlspecialchars($t->source); ?>">
                                        <?php echo htmlspecialchars($srcInfo['label']); ?>
                                    </span>
                                </td>
                                <td data-label="Remark" class="mpd-td-remark"><?php echo htmlspecialchars($t->remark ?: '-'); ?></td>
                                <td data-label="Date" class="mpd-td-date"><?php echo date('M d, Y H:i', strtotime($t->created_at)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <div class="mpd-pagination-footer" id="mpdTxnPagination"></div>
    </div>

    <!-- Load Wallet Modal (reused inside details page) -->
    <div class="modal fade" id="loadWalletModal" tabindex="-1" aria-labelledby="loadWalletModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow mpd-modal-content">
                <div class="modal-header text-white p-4 mpd-modal-header">
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
                        <button type="submit" class="btn fw-semibold text-white px-4 mpd-modal-submit">
                            Credit Wallet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* ---------- Scoped tokens (falls back to the theme's global vars) ---------- */
        .mpd-view {
            --mpd-pink: var(--primary-pink, #E91E8C);
            --mpd-gold: var(--primary-gold, #D4AF37);
            --mpd-dark: var(--dark-sidebar, #1f2937);
            --mpd-slate: #64748B;
            --mpd-teal: #0D9488;
            --mpd-red: #DC2626;
            font-family: 'Poppins', sans-serif;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        /* ---------- Header ---------- */
        .mpd-header {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .mpd-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            width: fit-content;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--mpd-pink);
            background: rgba(233, 30, 140, 0.1);
            border-radius: 999px;
            padding: 0.3rem 0.75rem 0.3rem 0.6rem;
        }

        .mpd-title {
            margin: 0.55rem 0 0.2rem;
            font-weight: 700;
            color: var(--mpd-dark);
            font-size: clamp(1.35rem, 4.5vw, 1.9rem);
        }

        .mpd-title::after {
            content: '';
            display: block;
            width: 46px;
            height: 3px;
            margin-top: 0.55rem;
            border-radius: 3px;
            background: linear-gradient(90deg, var(--mpd-gold), var(--mpd-pink));
        }

        .mpd-subtitle {
            margin: 0;
            color: #7a7a7a;
            font-size: 0.9rem;
            max-width: 46ch;
        }

        .mpd-back-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            align-self: flex-start;
            background: var(--mpd-dark);
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.65rem 1.1rem;
            border-radius: 10px;
            transition: box-shadow 0.15s ease, transform 0.15s ease;
        }

        .mpd-back-btn:hover {
            color: #fff;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.16);
        }

        .mpd-back-btn:active {
            transform: translateY(1px);
        }

        /* ---------- Grid ---------- */
        .mpd-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.25rem;
        }

        .mpd-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 14px rgba(43, 43, 43, 0.06);
        }

        /* ---------- Profile card ---------- */
        .mpd-profile-card {
            border-top: 4px solid var(--mpd-pink);
            padding: 2rem 1.5rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .mpd-avatar {
            width: 104px;
            height: 104px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--mpd-gold);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.1rem;
        }

        .mpd-avatar-fallback {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.4rem;
            font-weight: 700;
            color: #fff;
            background: var(--mpd-pink);
        }

        .mpd-member-name {
            font-weight: 700;
            color: var(--text-black, #2B2B2B);
            margin: 0 0 0.25rem;
            font-size: 1.3rem;
        }

        .mpd-member-email {
            color: #8a8a8a;
            font-size: 0.85rem;
            margin: 0 0 1.4rem;
        }

        .mpd-balance-panel {
            width: 100%;
            background: #F8F7F5;
            border-left: 3px solid var(--mpd-gold);
            border-radius: 10px;
            padding: 1rem 1.1rem;
            margin-bottom: 1.4rem;
            text-align: left;
        }

        .mpd-balance-label {
            display: block;
            color: #8a8a8a;
            font-size: 0.78rem;
            margin-bottom: 0.3rem;
        }

        .mpd-balance-amount {
            display: block;
            font-weight: 700;
            font-size: 1.6rem;
            color: #16A34A;
        }

        .mpd-btn {
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
            font-weight: 600;
            font-size: 0.92rem;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            border: 2px solid transparent;
            text-decoration: none;
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease, color 0.15s ease;
        }

        .mpd-btn:not(:last-child) {
            margin-bottom: 0.6rem;
        }

        .mpd-btn:active {
            transform: translateY(1px);
        }

        .mpd-btn-primary {
            background: linear-gradient(45deg, var(--mpd-pink), var(--mpd-gold));
            color: #fff;
            box-shadow: 0 6px 16px rgba(233, 30, 140, 0.18);
        }

        .mpd-btn-primary:hover {
            color: #fff;
            box-shadow: 0 8px 20px rgba(233, 30, 140, 0.26);
        }

        .mpd-btn-edit {
            background: #fff8e6;
            border-color: var(--mpd-gold);
            color: #8c6b1b;
        }

        .mpd-btn-edit:hover {
            background: var(--mpd-gold);
            border-color: var(--mpd-gold);
            color: #fff;
        }

        .mpd-btn-outline {
            background: transparent;
            border-color: var(--mpd-gold);
            color: var(--mpd-gold);
        }

        .mpd-btn-outline:hover {
            background: var(--mpd-gold);
            color: #fff;
        }

        /* ---------- Section cards ---------- */
        .mpd-section-card {
            overflow: hidden;
        }

        .mpd-section-header {
            background: var(--mpd-dark);
            border-bottom: 2px solid var(--mpd-gold);
            color: #fff;
            padding: 1rem 1.35rem;
        }

        .mpd-section-header h5 {
            margin: 0;
            font-weight: 700;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 0.55rem;
        }

        .mpd-section-sub {
            font-weight: 400;
            font-size: 0.8rem;
            opacity: 0.75;
        }

        /* ---------- Info grid (Account Details) ---------- */
        .mpd-info-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            padding: 1.5rem;
        }

        .mpd-info-tile {
            display: flex;
            align-items: flex-start;
            gap: 0.9rem;
            background: #FBFAF9;
            border: 1px solid #F0EAE5;
            border-radius: 12px;
            padding: 1rem 1.1rem;
        }

        .mpd-info-icon {
            flex: none;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            color: #fff;
        }

        .mpd-icon-pink {
            background: var(--mpd-pink);
        }

        .mpd-icon-gold {
            background: var(--mpd-gold);
        }

        .mpd-icon-slate {
            background: var(--mpd-slate);
        }

        .mpd-icon-success {
            background: #16A34A;
        }

        .mpd-icon-danger {
            background: var(--mpd-red);
        }

        .mpd-info-label {
            display: block;
            color: #9a9a9a;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            margin-bottom: 0.3rem;
        }

        .mpd-info-value {
            display: block;
            color: var(--text-black, #2B2B2B);
            font-weight: 600;
            font-size: 0.95rem;
            word-break: break-word;
        }

        .mpd-info-code {
            color: var(--mpd-pink);
            font-size: 1.05rem;
            font-weight: 700;
            background: none;
        }

        .mpd-info-muted {
            color: #9a9a9a;
            font-weight: 500;
            font-style: italic;
        }

        .mpd-sponsor-meta {
            display: block;
            color: #9a9a9a;
            font-weight: 500;
            font-size: 0.82rem;
            margin-top: 0.15rem;
        }

        .mpd-status {
            display: inline-flex;
            align-items: center;
            font-size: 0.76rem;
            font-weight: 700;
            padding: 0.3rem 0.7rem;
            border-radius: 999px;
        }

        .mpd-status-active {
            background: #DCFCE7;
            color: #15803D;
        }

        .mpd-status-blocked {
            background: #FEE2E2;
            color: #B91C1C;
        }

        /* ---------- Transactions table ---------- */
        .mpd-empty-txn {
            text-align: center;
            color: #b5aca8;
            padding: 3rem 1rem;
        }

        .mpd-empty-txn i {
            font-size: 1.8rem;
            margin-bottom: 0.6rem;
            display: block;
        }

        .mpd-empty-txn p {
            margin: 0;
            font-size: 0.9rem;
        }

        .mpd-txn-card {
            width: 100%;
        }

        .mpd-table-wrap {
            overflow-x: auto;
        }

        .mpd-pagination-footer {
            display: flex;
            justify-content: center;
            padding: 1.1rem;
            border-top: 1px solid #F2EEEB;
        }

        .mpd-pagination-footer .pagination {
            display: flex;
            gap: 0.35rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .mpd-pagination-footer .page-link {
            border: 1.5px solid #e9e2df;
            border-radius: 9px;
            color: var(--text-black, #2B2B2B);
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.5rem 0.85rem;
            text-decoration: none;
            cursor: pointer;
            background: #fff;
            display: inline-block;
        }

        .mpd-pagination-footer .page-item.active .page-link {
            background: var(--mpd-pink);
            border-color: var(--mpd-pink);
            color: #fff;
        }

        .mpd-pagination-footer .page-item.disabled .page-link {
            color: #c7c7c7;
            background: #fafafa;
            cursor: default;
        }

        .mpd-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.88rem;
        }

        .mpd-table thead th {
            background: #FAF8F6;
            color: #7a7a7a;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            text-align: left;
            padding: 0.85rem 1rem;
            white-space: nowrap;
        }

        .mpd-table tbody td {
            padding: 0.95rem 1rem;
            border-top: 1px solid #F2EEEB;
            vertical-align: top;
            color: var(--text-black, #2B2B2B);
        }

        .mpd-table tbody tr:hover {
            background: #FDFBFA;
        }

        .mpd-td-idx {
            color: #b0b0b0;
            font-weight: 700;
        }

        .mpd-td-remark {
            color: #4a4a4a;
            max-width: 280px;
            line-height: 1.45;
            word-break: break-word;
        }

        .mpd-td-date {
            color: #9a9a9a;
            white-space: nowrap;
        }

        .mpd-td-amount {
            font-weight: 700;
            white-space: nowrap;
        }

        .mpd-amount-credit {
            color: #16A34A;
        }

        .mpd-amount-debit {
            color: var(--mpd-red);
        }

        .mpd-type {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            padding: 0.3rem 0.6rem;
            border-radius: 999px;
        }

        .mpd-type-credit {
            background: #DCFCE7;
            color: #15803D;
        }

        .mpd-type-debit {
            background: #FEE2E2;
            color: #B91C1C;
        }

        /* Color-coded source tags — each source gets its own hue so the list scans at a glance */
        .mpd-tag {
            display: inline-block;
            font-size: 0.72rem;
            font-weight: 600;
            padding: 0.32rem 0.65rem;
            border-radius: 8px;
            white-space: nowrap;
        }

        .mpd-tag-commission {
            background: rgba(233, 30, 140, 0.1);
            color: var(--mpd-pink);
        }

        .mpd-tag-purchase {
            background: rgba(100, 116, 139, 0.12);
            color: var(--mpd-slate);
        }

        .mpd-tag-admin-credit {
            background: rgba(212, 175, 55, 0.16);
            color: #92720C;
        }

        .mpd-tag-admin-debit {
            background: rgba(220, 38, 38, 0.1);
            color: var(--mpd-red);
        }

        .mpd-tag-withdrawal {
            background: rgba(220, 38, 38, 0.1);
            color: var(--mpd-red);
        }

        .mpd-tag-bonus {
            background: rgba(13, 148, 136, 0.12);
            color: var(--mpd-teal);
        }

        .mpd-tag-default {
            background: #EEEEEE;
            color: #6b6b6b;
        }

        /* ---------- Modal polish ---------- */
        .mpd-modal-content {
            border-radius: 14px;
            overflow: hidden;
        }

        .mpd-modal-header {
            background: linear-gradient(135deg, var(--mpd-dark) 0%, #1f2937 100%);
            border-bottom: 3px solid var(--mpd-gold);
        }

        .mpd-modal-submit {
            background: linear-gradient(45deg, var(--mpd-pink), var(--mpd-gold));
            border: none;
            border-radius: 6px;
            box-shadow: 0 4px 10px rgba(233, 30, 140, 0.18);
        }

        /* =====================================================
       Tablet and up
    ===================================================== */
        @media (min-width: 576px) {
            .mpd-header {
                flex-direction: row;
                align-items: flex-start;
                justify-content: space-between;
            }

            .mpd-btn {
                width: auto;
            }

            .mpd-profile-card .mpd-btn {
                width: 100%;
            }

            .mpd-info-grid {
                grid-template-columns: 1fr 1fr;
            }

            .mpd-info-tile-wide {
                grid-column: 1 / -1;
            }
        }

        @media (min-width: 992px) {
            .mpd-grid-top {
                grid-template-columns: 340px 1fr;
                align-items: start;
            }
        }

        /* =====================================================
       Mobile — collapse the transactions table into cards
    ===================================================== */
        @media (max-width: 767.98px) {
            .mpd-table thead {
                display: none;
            }

            .mpd-table,
            .mpd-table tbody,
            .mpd-table tr,
            .mpd-table td {
                display: block;
                width: 100%;
            }

            .mpd-table tr {
                border-top: 1px solid #F2EEEB;
                padding: 0.9rem 1rem;
            }

            .mpd-table tr:first-child {
                border-top: none;
            }

            .mpd-table td {
                border: none;
                padding: 0.25rem 0;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 0.75rem;
            }

            .mpd-table td::before {
                content: attr(data-label);
                color: #9a9a9a;
                font-size: 0.72rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.03em;
                flex: none;
            }

            .mpd-td-idx {
                order: -1;
            }

            .mpd-td-idx::before {
                content: 'Transaction #';
            }

            .mpd-td-remark {
                flex-direction: column;
                align-items: flex-start;
                text-align: left;
            }

            .mpd-td-remark::before {
                margin-bottom: 0.3rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const memberId = <?php echo (int) $member->id; ?>;
            const perPage = 5;
            const tbody = document.querySelector('.mpd-table tbody');
            const tableWrap = document.querySelector('.mpd-table-wrap');
            const emptyState = document.querySelector('.mpd-empty-txn');
            const pagination = document.getElementById('mpdTxnPagination');
            const countLabel = document.getElementById('mpdTxnCountLabel');

            const sourceMap = {
                referral_commission: {
                    label: 'Referral Commission',
                    class: 'mpd-tag-commission'
                },
                purchase: {
                    label: 'Purchase',
                    class: 'mpd-tag-purchase'
                },
                admin_credit: {
                    label: 'Admin Credit',
                    class: 'mpd-tag-admin-credit'
                },
                admin_debit: {
                    label: 'Admin Debit',
                    class: 'mpd-tag-admin-debit'
                },
                withdrawal: {
                    label: 'Withdrawal',
                    class: 'mpd-tag-withdrawal'
                },
                signup_bonus: {
                    label: 'Signup Bonus',
                    class: 'mpd-tag-bonus'
                },
            };

            function escapeHtml(str) {
                return String(str ?? '').replace(/[&<>"']/g, m => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;'
                } [m]));
            }

            function renderRows(rows) {
                if (!tbody) return;
                if (!rows.length) {
                    if (tableWrap) tableWrap.style.display = 'none';
                    if (emptyState) emptyState.style.display = 'block';
                    return;
                }
                if (tableWrap) tableWrap.style.display = '';
                if (emptyState) emptyState.style.display = 'none';

                tbody.innerHTML = rows.map((t, i) => {
                    const src = sourceMap[t.source] || {
                        label: (t.source || '').replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase()),
                        class: 'mpd-tag-default'
                    };
                    const isCredit = t.type === 'credit';
                    const dateObj = new Date(t.created_at.replace(' ', 'T'));
                    const dateStr = dateObj.toLocaleDateString('en-US', {
                            month: 'short',
                            day: '2-digit',
                            year: 'numeric'
                        }) +
                        ' ' + dateObj.toLocaleTimeString('en-US', {
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: false
                        });

                    return `
                <tr>
                    <td data-label="#" class="mpd-td-idx">${t.row_no}</td>
                    <td data-label="Type">
                        <span class="mpd-type ${isCredit ? 'mpd-type-credit' : 'mpd-type-debit'}">
                            <i class="fa-solid ${isCredit ? 'fa-arrow-down' : 'fa-arrow-up'}"></i> ${isCredit ? 'CREDIT' : 'DEBIT'}
                        </span>
                    </td>
                    <td data-label="Amount" class="mpd-td-amount ${isCredit ? 'mpd-amount-credit' : 'mpd-amount-debit'}">
                        ${isCredit ? '+' : '-'}₹${Number(t.amount).toFixed(2)}
                    </td>
                    <td data-label="Source">
                        <span class="mpd-tag ${src.class}" title="${escapeHtml(t.source)}">${escapeHtml(src.label)}</span>
                    </td>
                    <td data-label="Remark" class="mpd-td-remark">${escapeHtml(t.remark || '-')}</td>
                    <td data-label="Date" class="mpd-td-date">${dateStr}</td>
                </tr>`;
                }).join('');
            }

            function renderPagination(currentPage, totalPages) {
                if (!pagination) return;
                if (totalPages <= 1) {
                    pagination.innerHTML = '';
                    return;
                }

                let html = '<ul class="pagination">';
                html += `<li class="page-item ${currentPage <= 1 ? 'disabled' : ''}">
                    <a class="page-link" data-page="${currentPage - 1}">&laquo; Prev</a>
                 </li>`;
                for (let i = 1; i <= totalPages; i++) {
                    html += `<li class="page-item ${currentPage === i ? 'active' : ''}">
                        <a class="page-link" data-page="${i}">${i}</a>
                     </li>`;
                }
                html += `<li class="page-item ${currentPage >= totalPages ? 'disabled' : ''}">
                    <a class="page-link" data-page="${currentPage + 1}">Next &raquo;</a>
                 </li>`;
                html += '</ul>';
                pagination.innerHTML = html;
            }

            function loadTransactions(page) {
                fetch(`<?php echo base_url('admin/members/transactions/'); ?>${memberId}?page=${page}&per_page=${perPage}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        renderRows(data.transactions || []);
                        renderPagination(data.current_page || 1, data.total_pages || 1);
                        if (countLabel) {
                            countLabel.textContent = `(${data.total || 0} total logs)`;
                        }
                    })
                    .catch(err => {
                        console.error('Failed to load transactions:', err);
                        if (countLabel) countLabel.textContent = '(failed to load)';
                    });
            }

            if (pagination) {
                pagination.addEventListener('click', function(e) {
                    const link = e.target.closest('.page-link');
                    if (!link) return;
                    e.preventDefault();
                    const li = link.closest('.page-item');
                    if (li.classList.contains('disabled') || li.classList.contains('active')) return;
                    loadTransactions(parseInt(link.getAttribute('data-page'), 10));
                });
            }

            loadTransactions(1);
        });
    </script>