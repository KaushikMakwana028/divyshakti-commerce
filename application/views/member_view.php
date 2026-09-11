<div class="sm-view">

    <div class="sm-header">
        <span class="sm-eyebrow"><i class="fa-solid fa-users"></i> Member Directory</span>
        <h1 class="sm-title">System Members</h1>
        <p class="sm-subtitle">Manage member registration accounts and wallets.</p>
    </div>

    <!-- Search and Filter Form -->
    <div class="sm-card sm-filter-card">
        <form id="filterForm" class="sm-filter-form">
            <div class="sm-filter-search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="search" id="searchInput" placeholder="Search by User ID, name, email, or phone…" value="<?php echo htmlspecialchars($search ?? ''); ?>">
            </div>
            <select name="status" id="statusFilter" class="sm-filter-select">
                <option value="">All Statuses</option>
                <option value="1" <?php echo ($status === '1') ? 'selected' : ''; ?>>Active (Verified)</option>
                <option value="incomplete" <?php echo ($status === 'incomplete') ? 'selected' : ''; ?>>Incomplete / Pending</option>
                <option value="0" <?php echo ($status === '0') ? 'selected' : ''; ?>>Blocked</option>
            </select>
            <button type="button" id="resetBtn" class="sm-reset-btn">
                <i class="fa-solid fa-arrow-rotate-left"></i> Reset
            </button>
        </form>
    </div>

    <!-- Member List Table -->
    <div class="sm-card sm-table-card">
        <div id="table-container">
            <div class="sm-table-wrap">
                <table class="sm-table">
                    <thead>
                        <tr>
                            <th class="sm-th-idx">#</th>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Wallet Balance</th>
                            <th>Status</th>
                            <th>Registered At</th>
                            <th class="sm-th-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($members)): ?>
                            <tr>
                                <td colspan="9">
                                    <div class="sm-empty">
                                        <i class="fa-solid fa-users-slash"></i>
                                        <p>No members found.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php
                            $index_num = ($current_page - 1) * 10 + 1;
                            foreach ($members as $member):
                            ?>
                                <tr>
                                    <td data-label="#" class="sm-td-idx"><?php echo $index_num++; ?></td>
                                    <td data-label="User ID">
                                        <span class="badge bg-dark-subtle text-dark border font-monospace fw-bold px-2 py-1" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                                            <i class="fa-solid fa-id-badge text-primary me-1"></i><?php echo htmlspecialchars($member->custom_id ?? '-'); ?>
                                        </span>
                                    </td>
                                    <td data-label="Name">
                                        <div class="sm-name-cell">
                                            <?php if (!empty($member->profile_image) && file_exists(FCPATH . ltrim($member->profile_image, '/'))): ?>
                                                <img src="<?php echo base_url(ltrim($member->profile_image, '/')); ?>" class="sm-avatar" alt="<?php echo htmlspecialchars($member->name ?? 'Member'); ?>">
                                            <?php else: ?>
                                                <div class="sm-avatar sm-avatar-fallback"><?php echo strtoupper(substr($member->name ?? 'M', 0, 1)); ?></div>
                                            <?php endif; ?>
                                            <span class="sm-name-text"><?php echo htmlspecialchars($member->name ?? 'Unknown'); ?></span>
                                        </div>
                                    </td>
                                    <td data-label="Email" class="sm-td-muted">
                                        <?php if (!empty($member->email)): ?>
                                            <span class="text-dark d-inline-flex align-items-center gap-1" title="<?php echo htmlspecialchars($member->email); ?>">
                                                <i class="fa-regular fa-envelope text-muted me-1"></i><?php echo htmlspecialchars($member->email); ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-light text-muted border px-2 py-1" style="font-size: 0.72rem; font-weight: 500;">
                                                <i class="fa-regular fa-envelope-open me-1 opacity-50"></i>Not provided
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td data-label="Phone" class="sm-td-muted">
                                        <?php if (!empty($member->phone)): ?>
                                            <span class="text-dark fw-medium d-inline-flex align-items-center gap-1">
                                                <i class="fa-solid fa-phone text-muted me-1" style="font-size: 0.72rem;"></i><?php echo htmlspecialchars($member->phone); ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-light text-muted border px-2 py-1" style="font-size: 0.72rem; font-weight: 500;">
                                                <i class="fa-solid fa-phone-slash me-1 opacity-50"></i>-
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td data-label="Wallet Balance">
                                        <span class="sm-balance">₹<?php echo number_format($member->wallet_balance, 2); ?></span>
                                    </td>
                                    <td data-label="Status" class="sm-td-status">
                                        <div class="sm-status-group">
                                            <?php 
                                                $pct = (int)($member->profile_completion_percentage ?? 0);
                                                $is_completed = !empty($member->is_profile_completed);
                                                $is_active = !empty($member->is_profile_active);
                                            ?>
                                            <!-- Top Status Pill -->
                                            <?php if ((int)$member->status === 0): ?>
                                                <span class="sm-status sm-status-blocked" title="Account is Blocked">
                                                    <i class="fa-solid fa-ban"></i> Blocked
                                                </span>
                                            <?php elseif ($is_active && $is_completed): ?>
                                                <span class="sm-status sm-status-active" title="Account is Active & Verified">
                                                    <i class="fa-solid fa-circle-check"></i> Active
                                                </span>
                                            <?php elseif ($is_completed): ?>
                                                <span class="sm-status sm-status-review" title="Profile 100% Complete - Awaiting Admin Review">
                                                    <i class="fa-solid fa-clock"></i> Under Review
                                                </span>
                                            <?php else: ?>
                                                <span class="sm-status sm-status-incomplete" title="Profile is Incomplete (<?php echo $pct; ?>%)">
                                                    <i class="fa-solid fa-triangle-exclamation"></i> Incomplete
                                                </span>
                                            <?php endif; ?>

                                            <!-- Profile Completion Details -->
                                            <div class="sm-profile-meta">
                                                <div class="sm-profile-progress-wrap" title="Profile completion: <?php echo $pct; ?>%">
                                                    <div class="sm-profile-progress-track">
                                                        <div class="sm-profile-progress-bar <?php 
                                                            if ($pct >= 100) echo 'sm-progress-100';
                                                            elseif ($pct >= 50) echo 'sm-progress-mid';
                                                            elseif ($pct > 0) echo 'sm-progress-low';
                                                            else echo 'sm-progress-zero';
                                                        ?>" style="width: <?php echo max(6, $pct); ?>%;"></div>
                                                    </div>
                                                </div>

                                                <div class="sm-badges-inline">
                                                    <?php if ($pct >= 100): ?>
                                                        <span class="sm-pbadge sm-pbadge-100" title="Profile 100% Complete">
                                                            <i class="fa-solid fa-circle-check"></i> 100% Profile
                                                        </span>
                                                    <?php elseif ($pct > 0): ?>
                                                        <span class="sm-pbadge sm-pbadge-mid" title="Profile <?php echo $pct; ?>% Complete">
                                                            <i class="fa-solid fa-chart-pie"></i> <?php echo $pct; ?>% Profile
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="sm-pbadge sm-pbadge-zero" title="Profile 0% Complete (KYC Not Submitted)">
                                                            <i class="fa-solid fa-circle-xmark"></i> 0% Profile
                                                        </span>
                                                    <?php endif; ?>

                                                    <?php if ($is_active): ?>
                                                        <span class="sm-kyc-tag sm-kyc-tag-verified" title="KYC Approved by Admin">
                                                            <i class="fa-solid fa-shield-check"></i> Verified
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="sm-kyc-tag sm-kyc-tag-pending" title="KYC Pending Admin Approval">
                                                            <i class="fa-solid fa-shield-halved"></i> Pending
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Registered" class="sm-td-muted"><?php echo date('M d, Y', strtotime($member->created_at)); ?></td>
                                    <td data-label="Actions" class="sm-td-actions">
                                        <div class="sm-actions">
                                            <?php if (!empty($member->is_profile_active)): ?>
                                                <a href="<?php echo base_url('admin/members/activate_profile/' . $member->id); ?>"
                                                    class="sm-icon-btn sm-icon-btn-deactivate"
                                                    title="Deactivate Profile"
                                                    data-confirm="Are you sure you want to deactivate <?php echo htmlspecialchars($member->name ?? 'this member'); ?>'s profile?"
                                                    data-confirm-title="Deactivate Profile?"
                                                    data-confirm-btn="Yes, Deactivate"
                                                    data-confirm-danger="true">
                                                    <i class="fa-solid fa-ban"></i>
                                                </a>
                                            <?php else: ?>
                                                <?php if ($pct < 100): ?>
                                                    <a href="<?php echo base_url('admin/members/activate_profile/' . $member->id); ?>"
                                                        class="sm-icon-btn sm-icon-btn-activate"
                                                        title="Activate Profile (Warning: Profile is only <?php echo $pct; ?>% complete)"
                                                        data-confirm="<?php echo htmlspecialchars($member->name ?? 'This member'); ?> has only completed <?php echo $pct; ?>% of their profile. Are you sure you want to activate anyway?"
                                                        data-confirm-title="Activate Incomplete Profile?"
                                                        data-confirm-btn="Yes, Activate Anyway"
                                                        data-confirm-icon="warning">
                                                        <i class="fa-solid fa-check-double"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <a href="<?php echo base_url('admin/members/activate_profile/' . $member->id); ?>"
                                                        class="sm-icon-btn sm-icon-btn-activate"
                                                        title="Activate & Approve Profile"
                                                        data-confirm="Are you sure you want to activate & approve <?php echo htmlspecialchars($member->name ?? 'this member'); ?>'s 100% complete profile?"
                                                        data-confirm-title="Activate Profile?"
                                                        data-confirm-btn="Yes, Activate & Approve"
                                                        data-confirm-icon="question">
                                                        <i class="fa-solid fa-check-double"></i>
                                                    </a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <button type="button"
                                                class="sm-icon-btn sm-icon-btn-wallet load-wallet-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#loadWalletModal"
                                                data-id="<?php echo $member->id; ?>"
                                                data-name="<?php echo htmlspecialchars($member->name ?? ''); ?>"
                                                data-balance="₹<?php echo number_format($member->wallet_balance, 2); ?>"
                                                title="Load Wallet Funds">
                                                <i class="fa-solid fa-wallet"></i>
                                            </button>
                                            <a href="<?php echo base_url('admin/members/edit/' . $member->id); ?>" class="sm-icon-btn sm-icon-btn-edit" title="Edit Member Details">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a href="<?php echo base_url('admin/members/view/' . $member->id); ?>" class="sm-icon-btn sm-icon-btn-view" title="Inspect Detail View">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination footer inside container -->
            <?php if (!empty($total_rows) && $total_rows > 0): 
                $start_record = ($current_page - 1) * 10 + 1;
                $end_record = min($current_page * 10, $total_rows);
            ?>
                <div class="sm-pagination-footer d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                    <div class="text-muted small">
                        Showing <strong><?php echo $start_record; ?></strong> to <strong><?php echo $end_record; ?></strong> of <strong><?php echo number_format($total_rows); ?></strong> available members
                    </div>
                    <?php if ($total_pages > 1): ?>
                    <nav aria-label="Member Page Navigation">
                        <ul class="pagination justify-content-center mb-0">
                            <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="#" data-page="<?php echo $current_page - 1; ?>">&laquo; Prev</a>
                            </li>
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo ($current_page == $i) ? 'active' : ''; ?>">
                                    <a class="page-link" href="#" data-page="<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="#" data-page="<?php echo $current_page + 1; ?>">Next &raquo;</a>
                            </li>
                        </ul>
                    </nav>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Load Wallet Modal -->
<div class="modal fade" id="loadWalletModal" tabindex="-1" aria-labelledby="loadWalletModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow sm-modal-content">
            <div class="modal-header text-white p-4 sm-modal-header">
                <h5 class="modal-title fw-bold" id="loadWalletModalLabel">
                    <i class="fa-solid fa-coins me-2 text-warning"></i> Load Wallet Funds
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="loadWalletForm" method="POST" action="">
                <div class="modal-body p-4">
                    <div class="sm-modal-summary">
                        <div class="sm-modal-summary-row">
                            <span class="sm-modal-summary-label"><i class="fa-solid fa-user"></i> Member Account</span>
                            <input type="text" id="walletMemberName" class="sm-modal-summary-value" readonly>
                        </div>
                        <div class="sm-modal-summary-divider"></div>
                        <div class="sm-modal-summary-row">
                            <span class="sm-modal-summary-label"><i class="fa-solid fa-wallet"></i> Current Balance</span>
                            <input type="text" id="walletCurrentBalance" class="sm-modal-summary-value sm-modal-summary-balance" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="amount" class="form-label fw-semibold text-dark">Amount to Add (₹)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                            <input type="number" step="0.01" name="amount" id="amount" class="form-control" placeholder="0.00" min="0.01" required>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label for="remark" class="form-label fw-semibold text-dark">Transaction Remark</label>
                        <input type="text" name="remark" id="remark" class="form-control" placeholder="e.g. Approved loading bonus" required>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3 border-0 justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn fw-semibold text-white px-4 sm-modal-submit">
                        Credit Wallet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .sm-view {
        --sm-pink: var(--primary-pink, #E91E8C);
        --sm-gold: var(--primary-gold, #D4AF37);
        --sm-dark: var(--dark-sidebar, #1f2937);
        --sm-slate: #64748B;
        font-family: 'Poppins', sans-serif;
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    /* ---------- Header ---------- */
    .sm-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        width: fit-content;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--sm-pink);
        background: rgba(233, 30, 140, 0.1);
        border-radius: 999px;
        padding: 0.3rem 0.75rem 0.3rem 0.6rem;
    }

    .sm-title {
        margin: 0.55rem 0 0.2rem;
        font-weight: 700;
        color: var(--sm-dark);
        font-size: clamp(1.35rem, 4.5vw, 1.9rem);
    }

    .sm-title::after {
        content: '';
        display: block;
        width: 46px;
        height: 3px;
        margin-top: 0.55rem;
        border-radius: 3px;
        background: linear-gradient(90deg, var(--sm-gold), var(--sm-pink));
    }

    .sm-subtitle {
        margin: 0;
        color: #7a7a7a;
        font-size: 0.9rem;
    }

    /* ---------- Cards ---------- */
    .sm-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 2px 14px rgba(43, 43, 43, 0.06);
    }

    /* ---------- Filter form ---------- */
    .sm-filter-card {
        padding: 1rem;
    }

    .sm-filter-form {
        display: flex;
        flex-direction: column;
        gap: 0.7rem;
    }

    .sm-filter-search {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        border: 1.5px solid #e9e2df;
        border-radius: 11px;
        background: #FBFAF9;
        padding: 0.1rem 0.9rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .sm-filter-search:focus-within {
        border-color: var(--sm-pink);
        box-shadow: 0 0 0 3px rgba(233, 30, 140, 0.12);
    }

    .sm-filter-search i {
        color: #9c9c9c;
    }

    .sm-filter-search input {
        flex: 1;
        min-width: 0;
        border: none;
        outline: none;
        background: transparent;
        padding: 0.72rem 0;
        font-size: 0.9rem;
        font-family: inherit;
    }

    .sm-filter-select {
        border: 1.5px solid #e9e2df;
        border-radius: 11px;
        background: #FBFAF9;
        padding: 0.72rem 0.9rem;
        font-size: 0.9rem;
        font-family: inherit;
        color: var(--text-black, #2B2B2B);
        outline: none;
    }

    .sm-filter-select:focus {
        border-color: var(--sm-pink);
    }

    .sm-reset-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        border: none;
        background: var(--sm-dark);
        color: #fff;
        font-weight: 600;
        font-size: 0.88rem;
        padding: 0.72rem 1rem;
        border-radius: 11px;
        cursor: pointer;
        transition: box-shadow 0.15s ease, transform 0.15s ease;
    }

    .sm-reset-btn:hover {
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.16);
    }

    .sm-reset-btn:active {
        transform: translateY(1px);
    }

    /* ---------- Table ---------- */
    .sm-table-card {
        overflow: hidden;
    }

    .sm-table-wrap {
        overflow-x: auto;
    }

    .sm-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

    .sm-table thead th {
        background: var(--sm-dark);
        border-bottom: 3px solid var(--sm-gold);
        color: #fff;
        font-weight: 600;
        font-size: 0.82rem;
        text-align: left;
        padding: 1rem 1.1rem;
        white-space: nowrap;
    }

    .sm-th-idx {
        width: 70px;
    }

    .sm-th-actions {
        text-align: right;
        width: 120px;
    }

    .sm-table tbody td {
        padding: 0.9rem 1.1rem;
        border-top: 1px solid #F2EEEB;
        vertical-align: middle;
        color: var(--text-black, #2B2B2B);
    }

    .sm-table tbody tr:hover {
        background: #FDFBFA;
    }

    .sm-td-idx {
        color: #b0b0b0;
        font-weight: 600;
    }

    .sm-td-muted {
        color: #6b6b6b;
    }

    .sm-td-actions {
        text-align: right;
    }

    .sm-name-cell {
        display: flex;
        align-items: center;
        gap: 0.65rem;
    }

    .sm-avatar {
        flex: none;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--sm-gold);
    }

    .sm-avatar-fallback {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        font-weight: 700;
        color: #fff;
        background: var(--sm-pink);
        border: none;
    }

    .sm-name-text {
        font-weight: 600;
        color: var(--text-black, #2B2B2B);
    }

    .sm-balance {
        font-weight: 700;
        color: #16A34A;
    }

    .sm-td-status {
        min-width: 170px;
    }

    .sm-status-group {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .sm-status {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.74rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        padding: 0.28rem 0.7rem;
        border-radius: 999px;
        width: fit-content;
        line-height: 1.2;
    }

    .sm-status-active {
        background: #ECFDF5;
        color: #047857;
        border: 1px solid #A7F3D0;
    }

    .sm-status-review {
        background: #FFFBEB;
        color: #B45309;
        border: 1px solid #FDE68A;
    }

    .sm-status-incomplete {
        background: #FFF7ED;
        color: #C2410C;
        border: 1px solid #FFEDD5;
    }

    .sm-status-blocked {
        background: #FEF2F2;
        color: #B91C1C;
        border: 1px solid #FECACA;
    }

    /* Profile Progress and Badges */
    .sm-profile-meta {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }

    .sm-profile-progress-wrap {
        width: 100%;
        max-width: 130px;
    }

    .sm-profile-progress-track {
        height: 5px;
        background: #E2E8F0;
        border-radius: 999px;
        overflow: hidden;
    }

    .sm-profile-progress-bar {
        height: 100%;
        border-radius: 999px;
        transition: width 0.3s ease;
    }

    .sm-progress-100 {
        background: linear-gradient(90deg, #10B981, #059669);
    }

    .sm-progress-mid {
        background: linear-gradient(90deg, #3B82F6, #2563EB);
    }

    .sm-progress-low {
        background: linear-gradient(90deg, #F59E0B, #D97706);
    }

    .sm-progress-zero {
        background: #CBD5E1;
    }

    .sm-badges-inline {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.35rem;
    }

    /* Profile Completion Badges - Sharp, High-Contrast & Beautiful */
    .sm-pbadge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 0.22rem 0.55rem;
        border-radius: 6px;
        line-height: 1.25;
        white-space: nowrap;
    }

    .sm-pbadge-100 {
        background: #DCFCE7;
        color: #15803D;
        border: 1px solid #86EFAC;
    }

    .sm-pbadge-mid {
        background: #EFF6FF;
        color: #1D4ED8;
        border: 1px solid #BFDBFE;
    }

    /* 0% Profile Badge - Ultra High Contrast & Clearly Visible */
    .sm-pbadge-zero {
        background: #F1F5F9;
        color: #1E293B;
        border: 1.5px solid #94A3B8;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        font-weight: 700;
    }

    .sm-pbadge-zero i {
        color: #EF4444;
        font-size: 0.72rem;
    }

    /* KYC Verification Tag */
    .sm-kyc-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.28rem;
        font-size: 0.70rem;
        font-weight: 600;
        padding: 0.2rem 0.5rem;
        border-radius: 6px;
        line-height: 1.25;
        white-space: nowrap;
    }

    .sm-kyc-tag-verified {
        background: #EEF2FF;
        color: #4338CA;
        border: 1px solid #C7D2FE;
    }

    .sm-kyc-tag-pending {
        background: #FEF3C7;
        color: #92400E;
        border: 1px solid #FCD34D;
    }

    .sm-actions {
        display: inline-flex;
        gap: 0.5rem;
    }

    .sm-icon-btn {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        border: 1.5px solid transparent;
        text-decoration: none;
        cursor: pointer;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .sm-icon-btn:active {
        transform: translateY(1px);
    }

    .sm-icon-btn-wallet {
        background: #ECFDF5;
        border-color: #A7F3D0;
        color: #16A34A;
    }

    .sm-icon-btn-wallet:hover {
        background: #16A34A;
        border-color: #16A34A;
        color: #fff;
    }

    .sm-icon-btn-edit {
        background: #FFFBEB;
        border-color: #FDE68A;
        color: #D97706;
    }

    .sm-icon-btn-edit:hover {
        background: #D97706;
        border-color: #D97706;
        color: #fff;
    }

    .sm-icon-btn-view {
        background: #EFF6FF;
        border-color: #BFDBFE;
        color: #2563EB;
    }

    .sm-icon-btn-view:hover {
        background: #2563EB;
        border-color: #2563EB;
        color: #fff;
    }

    .sm-icon-btn-activate {
        background: #ECFDF5;
        border-color: #A7F3D0;
        color: #16A34A;
    }

    .sm-icon-btn-activate:hover {
        background: #16A34A;
        border-color: #16A34A;
        color: #fff;
    }

    .sm-icon-btn-deactivate {
        background: #FEF2F2;
        border-color: #FECACA;
        color: #DC2626;
    }

    .sm-icon-btn-deactivate:hover {
        background: #DC2626;
        border-color: #DC2626;
        color: #fff;
    }

    .sm-empty {
        text-align: center;
        color: #b5aca8;
        padding: 3.5rem 1rem;
    }

    .sm-empty i {
        font-size: 1.9rem;
        margin-bottom: 0.7rem;
        display: block;
        color: #d8d2ce;
    }

    .sm-empty p {
        margin: 0;
        font-size: 0.92rem;
    }

    /* ---------- Pagination ---------- */
    .sm-pagination-footer {
        padding: 1rem;
        border-top: 1px solid #F2EEEB;
    }

    .sm-view .pagination {
        gap: 0.35rem;
    }

    .sm-view .page-link {
        border: 1.5px solid #e9e2df;
        border-radius: 9px !important;
        color: var(--text-black, #2B2B2B);
        font-weight: 600;
        font-size: 0.85rem;
        padding: 0.5rem 0.85rem;
    }

    .sm-view .page-item.active .page-link {
        background: var(--sm-pink);
        border-color: var(--sm-pink);
        color: #fff;
    }

    .sm-view .page-item.disabled .page-link {
        color: #c7c7c7;
        background: #fafafa;
    }

    .sm-view .page-link:hover:not(.sm-view .page-item.active .page-link) {
        border-color: var(--sm-pink);
    }

    /* ---------- Wallet modal ----------
       !important + a fuller selector path here because this modal is appended
       near <body> by Bootstrap and can end up after the page's own stylesheet
       in the cascade, letting global .modal-header / .btn defaults win. */
    .sm-modal-content {
        border-radius: 14px;
        overflow: hidden;
    }

    #loadWalletModal .modal-header.sm-modal-header {
        background: linear-gradient(135deg, var(--dark-sidebar, #1f2937) 0%, #1f2937 100%) !important;
        border-bottom: 3px solid var(--primary-gold, #D4AF37) !important;
        justify-content: space-between;
    }

    #loadWalletModal .modal-header.sm-modal-header,
    #loadWalletModal .modal-header.sm-modal-header .modal-title {
        color: #fff !important;
    }

    #loadWalletModal .modal-header.sm-modal-header .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.85;
    }

    #loadWalletModal button.sm-modal-submit {
        background: linear-gradient(45deg, var(--primary-pink, #E91E8C), var(--primary-gold, #D4AF37)) !important;
        border: none !important;
        border-radius: 6px;
        color: #fff !important;
        opacity: 1 !important;
        box-shadow: 0 4px 10px rgba(233, 30, 140, 0.18);
    }

    .sm-modal-summary {
        background: #FBFAF9;
        border: 1px solid #F0EAE5;
        border-radius: 12px;
        padding: 1rem 1.1rem;
        margin-bottom: 1.25rem;
    }

    .sm-modal-summary-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
    }

    .sm-modal-summary-label {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #8a8a8a;
        font-size: 0.82rem;
        font-weight: 600;
    }

    .sm-modal-summary-value {
        border: none;
        background: transparent;
        text-align: right;
        font-weight: 700;
        color: var(--text-black, #2B2B2B);
        font-size: 0.92rem;
        outline: none;
        max-width: 55%;
    }

    .sm-modal-summary-balance {
        color: #16A34A;
        font-size: 1rem;
    }

    .sm-modal-summary-divider {
        height: 1px;
        background: #F0EAE5;
        margin: 0.7rem 0;
    }

    /* ---------- Tablet and up ---------- */
    @media (min-width: 768px) {
        .sm-filter-form {
            flex-direction: row;
            align-items: center;
        }

        .sm-filter-search {
            flex: 1 1 320px;
        }

        .sm-filter-select {
            flex: 0 0 200px;
        }

        .sm-reset-btn {
            flex: 0 0 auto;
        }
    }

    /* ---------- Mobile: table becomes cards ---------- */
    @media (max-width: 767.98px) {
        .sm-table thead {
            display: none;
        }

        .sm-table,
        .sm-table tbody,
        .sm-table tr,
        .sm-table td {
            display: block;
            width: 100%;
        }

        .sm-table tr {
            border-top: 1px solid #F2EEEB;
            padding: 0.9rem 1.1rem;
        }

        .sm-table tr:first-child {
            border-top: none;
        }

        .sm-table td {
            border: none;
            padding: 0.3rem 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }

        .sm-table td::before {
            content: attr(data-label);
            color: #9a9a9a;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            flex: none;
        }

        .sm-td-idx {
            order: -1;
        }

        .sm-td-idx::before {
            content: 'Member #';
        }

        .sm-td-actions {
            justify-content: flex-end;
        }

        .sm-td-actions::before {
            content: none;
        }
    }
</style>

<!-- AJAX Swapper and Modal Bind Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const resetBtn = document.getElementById('resetBtn');
        let debounceTimer;

        function loadTable(page = 1) {
            const search = searchInput.value;
            const status = statusFilter.value;
            const url = new URL(window.location.href);

            url.searchParams.set('search', search);
            url.searchParams.set('status', status);
            url.searchParams.set('page', page);

            window.history.pushState({}, '', url.toString());

            fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTable = doc.getElementById('table-container');
                    if (newTable) {
                        document.getElementById('table-container').innerHTML = newTable.innerHTML;
                    }
                })
                .catch(err => console.error('Error fetching table data: ', err));
        }

        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                loadTable(1);
            }, 300);
        });

        statusFilter.addEventListener('change', function() {
            loadTable(1);
        });

        resetBtn.addEventListener('click', function() {
            searchInput.value = '';
            statusFilter.value = '';
            loadTable(1);
        });

        document.addEventListener('click', function(e) {
            // Handle pagination link clicks
            const pageLink = e.target.closest('#table-container .pagination .page-link');
            if (pageLink) {
                e.preventDefault();
                const page = pageLink.getAttribute('data-page');
                if (page) {
                    loadTable(page);
                }
            }

            // Handle dynamic wallet load button clicks (Event Delegation)
            const walletBtn = e.target.closest('.load-wallet-btn');
            if (walletBtn) {
                const memberId = walletBtn.getAttribute('data-id');
                const memberName = walletBtn.getAttribute('data-name');
                const memberBalance = walletBtn.getAttribute('data-balance');

                document.getElementById('walletMemberName').value = memberName;
                document.getElementById('walletCurrentBalance').value = memberBalance;
                document.getElementById('loadWalletForm').action = "<?php echo base_url('admin/members/wallet/'); ?>" + memberId;
            }
        });
    });
</script>