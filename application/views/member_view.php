<!-- ============================================================== -->
<!-- Members / Member Directory View — Redesigned                  -->
<!-- ============================================================== -->

<div class="mm-view">

    <!-- ============================================================== -->
    <!-- 1. Header -->
    <!-- ============================================================== -->
    <div class="mm-page-head">
        <div>
            <div class="mm-eyebrow"><i class="fa-solid fa-users"></i> Member Directory</div>
            <h3 class="mm-title">System Members</h3>
            <p class="mm-subtitle">Manage member registration accounts and wallets.</p>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- 2. Search & Filter Bar -->
    <!-- ============================================================== -->
    <div class="mm-toolbar">
        <form id="filterForm" class="mm-filter-form" onsubmit="return false;">
            <div class="mm-search-field">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="search" id="searchInput" placeholder="Search by User ID, name, or phone…" value="<?php echo htmlspecialchars($search ?? ''); ?>" autocomplete="off">
            </div>
            <select name="status" id="statusFilter" class="mm-select">
                <option value="">All Statuses</option>
                <option value="1" <?php echo ($status === '1') ? 'selected' : ''; ?>>Active (Verified)</option>
                <option value="incomplete" <?php echo ($status === 'incomplete') ? 'selected' : ''; ?>>Incomplete / Pending</option>
                <option value="0" <?php echo ($status === '0') ? 'selected' : ''; ?>>Blocked</option>
            </select>
            <button type="button" id="resetBtn" class="mm-reset-btn">
                <i class="fa-solid fa-arrow-rotate-left"></i> Reset
            </button>
        </form>
    </div>

    <!-- ============================================================== -->
    <!-- 3. Member List (Desktop Table + Mobile Cards) -->
    <!-- ============================================================== -->
    <div class="mm-table-wrapper" id="table-container">

        <!-- Desktop Table View -->
        <div class="d-none d-md-block mm-table-scroll">
            <table class="mm-table">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th style="width:120px;">User ID</th>
                        <th>Name</th>
                        <th style="width:150px;">Phone</th>
                        <th style="width:130px;">Wallet</th>
                        <th style="width:190px;">Status</th>
                        <th style="width:120px;">Registered</th>
                        <th class="text-end" style="width:150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($members)): ?>
                        <tr>
                            <td colspan="8">
                                <div class="mm-empty">
                                    <div class="mm-empty-icon"><i class="fa-solid fa-users-slash"></i></div>
                                    <p>No members found.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php
                        $index_num = ($current_page - 1) * 10 + 1;
                        foreach ($members as $member):
                            $pct = (int)($member->profile_completion_percentage ?? 0);
                            $is_completed = !empty($member->is_profile_completed);
                            $is_active = !empty($member->is_profile_active);
                        ?>
                            <tr>
                                <td class="mm-muted fw-semibold"><?php echo $index_num++; ?></td>
                                <td>
                                    <span class="mm-uid-badge">
                                        <i class="fa-solid fa-id-badge"></i><?php echo htmlspecialchars($member->custom_id ?? '-'); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="mm-name"><?php echo htmlspecialchars($member->name ?? 'Unknown'); ?></span>
                                </td>
                                <td class="mm-muted">
                                    <?php if (!empty($member->phone)): ?>
                                        <span class="mm-phone"><i class="fa-solid fa-phone"></i><?php echo htmlspecialchars($member->phone); ?></span>
                                    <?php else: ?>
                                        <span class="mm-phone mm-phone-none"><i class="fa-solid fa-phone-slash"></i>-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="mm-balance">₹<?php echo number_format($member->wallet_balance, 2); ?></span>
                                </td>
                                <td>
                                    <?php
                                    if ((int)$member->status === 0) {
                                        echo '<span class="mm-status-pill mm-status-blocked"><i class="fa-solid fa-ban"></i> Blocked</span>';
                                    } elseif ($is_active && $is_completed) {
                                        echo '<span class="mm-status-pill mm-status-active"><i class="fa-solid fa-circle-check"></i> Active</span>';
                                    } elseif ($is_completed) {
                                        echo '<span class="mm-status-pill mm-status-review"><i class="fa-solid fa-clock"></i> Under Review</span>';
                                    } else {
                                        echo '<span class="mm-status-pill mm-status-incomplete"><i class="fa-solid fa-triangle-exclamation"></i> Incomplete</span>';
                                    }
                                    ?>
                                    <div class="mm-progress-row">
                                        <div class="mm-progress-track">
                                            <div class="mm-progress-bar <?php
                                                                        if ($pct >= 100) echo 'mm-progress-100';
                                                                        elseif ($pct >= 50) echo 'mm-progress-mid';
                                                                        elseif ($pct > 0) echo 'mm-progress-low';
                                                                        else echo 'mm-progress-zero';
                                                                        ?>" style="width: <?php echo max(6, $pct); ?>%;"></div>
                                        </div>
                                        <span class="mm-progress-pct"><?php echo $pct; ?>%</span>
                                    </div>
                                    <span class="mm-kyc-note <?php echo $is_active ? 'mm-kyc-verified' : 'mm-kyc-pending'; ?>">
                                        <i class="fa-solid <?php echo $is_active ? 'fa-shield-check' : 'fa-shield-halved'; ?>"></i>
                                        <?php echo $is_active ? 'KYC Verified' : 'KYC Pending'; ?>
                                    </span>
                                </td>
                                <td class="mm-muted"><?php echo date('M d, Y', strtotime($member->created_at)); ?></td>
                                <td class="text-end">
                                    <div class="mm-actions">
                                        <?php if ($is_active): ?>
                                            <a href="<?php echo base_url('admin/members/activate_profile/' . $member->id); ?>"
                                                class="mm-icon-btn mm-icon-deactivate"
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
                                                    class="mm-icon-btn mm-icon-activate"
                                                    title="Activate Profile (only <?php echo $pct; ?>% complete)"
                                                    data-confirm="<?php echo htmlspecialchars($member->name ?? 'This member'); ?> has only completed <?php echo $pct; ?>% of their profile. Are you sure you want to activate anyway?"
                                                    data-confirm-title="Activate Incomplete Profile?"
                                                    data-confirm-btn="Yes, Activate Anyway"
                                                    data-confirm-icon="warning">
                                                    <i class="fa-solid fa-check-double"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="<?php echo base_url('admin/members/activate_profile/' . $member->id); ?>"
                                                    class="mm-icon-btn mm-icon-activate"
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
                                            class="mm-icon-btn mm-icon-wallet load-wallet-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#loadWalletModal"
                                            data-id="<?php echo $member->id; ?>"
                                            data-name="<?php echo htmlspecialchars($member->name ?? ''); ?>"
                                            data-balance="₹<?php echo number_format($member->wallet_balance, 2); ?>"
                                            title="Load Wallet Funds">
                                            <i class="fa-solid fa-wallet"></i>
                                        </button>
                                        <a href="<?php echo base_url('admin/members/edit/' . $member->id); ?>" class="mm-icon-btn mm-icon-edit" title="Edit Member Details">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <a href="<?php echo base_url('admin/members/view/' . $member->id); ?>" class="mm-icon-btn mm-icon-view" title="Inspect Detail View">
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

        <!-- Mobile Cards View -->
        <div class="d-md-none mm-mobile-list">
            <?php if (empty($members)): ?>
                <div class="mm-empty mm-empty-card">
                    <div class="mm-empty-icon"><i class="fa-solid fa-users-slash"></i></div>
                    <p>No members found.</p>
                </div>
            <?php else: ?>
                <?php
                $index_num_m = ($current_page - 1) * 10 + 1;
                foreach ($members as $member):
                    $pct = (int)($member->profile_completion_percentage ?? 0);
                    $is_completed = !empty($member->is_profile_completed);
                    $is_active = !empty($member->is_profile_active);
                ?>
                    <div class="mm-mobile-card">
                        <div class="mm-mobile-top">
                            <div>
                                <span class="mm-uid-badge">
                                    <i class="fa-solid fa-id-badge"></i><?php echo htmlspecialchars($member->custom_id ?? '-'); ?>
                                </span>
                                <div class="mm-name mt-1"><?php echo htmlspecialchars($member->name ?? 'Unknown'); ?></div>
                            </div>
                            <span class="mm-balance"><?php echo '₹' . number_format($member->wallet_balance, 2); ?></span>
                        </div>

                        <div class="mm-mobile-meta">
                            <?php if (!empty($member->phone)): ?>
                                <span class="mm-phone"><i class="fa-solid fa-phone"></i><?php echo htmlspecialchars($member->phone); ?></span>
                            <?php else: ?>
                                <span class="mm-phone mm-phone-none"><i class="fa-solid fa-phone-slash"></i>-</span>
                            <?php endif; ?>
                            <span><i class="fa-regular fa-calendar"></i> <?php echo date('M d, Y', strtotime($member->created_at)); ?></span>
                        </div>

                        <div class="mm-mobile-status">
                            <?php
                            if ((int)$member->status === 0) {
                                echo '<span class="mm-status-pill mm-status-blocked"><i class="fa-solid fa-ban"></i> Blocked</span>';
                            } elseif ($is_active && $is_completed) {
                                echo '<span class="mm-status-pill mm-status-active"><i class="fa-solid fa-circle-check"></i> Active</span>';
                            } elseif ($is_completed) {
                                echo '<span class="mm-status-pill mm-status-review"><i class="fa-solid fa-clock"></i> Under Review</span>';
                            } else {
                                echo '<span class="mm-status-pill mm-status-incomplete"><i class="fa-solid fa-triangle-exclamation"></i> Incomplete</span>';
                            }
                            ?>
                            <div class="mm-progress-row">
                                <div class="mm-progress-track">
                                    <div class="mm-progress-bar <?php
                                                                if ($pct >= 100) echo 'mm-progress-100';
                                                                elseif ($pct >= 50) echo 'mm-progress-mid';
                                                                elseif ($pct > 0) echo 'mm-progress-low';
                                                                else echo 'mm-progress-zero';
                                                                ?>" style="width: <?php echo max(6, $pct); ?>%;"></div>
                                </div>
                                <span class="mm-progress-pct"><?php echo $pct; ?>%</span>
                            </div>
                            <span class="mm-kyc-note <?php echo $is_active ? 'mm-kyc-verified' : 'mm-kyc-pending'; ?>">
                                <i class="fa-solid <?php echo $is_active ? 'fa-shield-check' : 'fa-shield-halved'; ?>"></i>
                                <?php echo $is_active ? 'KYC Verified' : 'KYC Pending'; ?>
                            </span>
                        </div>

                        <div class="mm-mobile-actions">
                            <?php if ($is_active): ?>
                                <a href="<?php echo base_url('admin/members/activate_profile/' . $member->id); ?>"
                                    class="mm-icon-btn mm-icon-deactivate"
                                    title="Deactivate Profile"
                                    data-confirm="Are you sure you want to deactivate <?php echo htmlspecialchars($member->name ?? 'this member'); ?>'s profile?"
                                    data-confirm-title="Deactivate Profile?"
                                    data-confirm-btn="Yes, Deactivate"
                                    data-confirm-danger="true">
                                    <i class="fa-solid fa-ban"></i>
                                </a>
                            <?php else: ?>
                                <a href="<?php echo base_url('admin/members/activate_profile/' . $member->id); ?>"
                                    class="mm-icon-btn mm-icon-activate"
                                    title="Activate Profile"
                                    data-confirm="<?php echo $pct < 100
                                                        ? htmlspecialchars($member->name ?? 'This member') . ' has only completed ' . $pct . '% of their profile. Are you sure you want to activate anyway?'
                                                        : 'Are you sure you want to activate & approve ' . htmlspecialchars($member->name ?? 'this member') . '\'s 100% complete profile?'; ?>"
                                    data-confirm-title="<?php echo $pct < 100 ? 'Activate Incomplete Profile?' : 'Activate Profile?'; ?>"
                                    data-confirm-btn="<?php echo $pct < 100 ? 'Yes, Activate Anyway' : 'Yes, Activate & Approve'; ?>"
                                    data-confirm-icon="<?php echo $pct < 100 ? 'warning' : 'question'; ?>">
                                    <i class="fa-solid fa-check-double"></i>
                                </a>
                            <?php endif; ?>
                            <button type="button"
                                class="mm-icon-btn mm-icon-wallet load-wallet-btn flex-grow-1"
                                data-bs-toggle="modal"
                                data-bs-target="#loadWalletModal"
                                data-id="<?php echo $member->id; ?>"
                                data-name="<?php echo htmlspecialchars($member->name ?? ''); ?>"
                                data-balance="₹<?php echo number_format($member->wallet_balance, 2); ?>"
                                title="Load Wallet Funds">
                                <i class="fa-solid fa-wallet"></i> Wallet
                            </button>
                            <a href="<?php echo base_url('admin/members/edit/' . $member->id); ?>" class="mm-icon-btn mm-icon-edit" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a href="<?php echo base_url('admin/members/view/' . $member->id); ?>" class="mm-icon-btn mm-icon-view" title="View">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Pagination Footer -->
        <?php if (!empty($total_rows) && $total_rows > 0):
            $start_record = ($current_page - 1) * 10 + 1;
            $end_record = min($current_page * 10, $total_rows);
        ?>
            <div class="mm-footer">
                <div class="mm-footer-info">
                    Showing <strong><?php echo $start_record; ?></strong> to <strong><?php echo $end_record; ?></strong> of <strong><?php echo number_format($total_rows); ?></strong> available members
                </div>
                <?php if ($total_pages > 1): ?>
                    <nav aria-label="Member Page Navigation">
                        <ul class="mm-pagination pagination-sm">
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

<!-- ============================================================== -->
<!-- 4. LOAD WALLET MODAL -->
<!-- ============================================================== -->
<div class="modal fade mm-modal" id="loadWalletModal" tabindex="-1" aria-labelledby="loadWalletModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="mm-modal-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <span class="mm-modal-icon-badge"><i class="fa-solid fa-coins"></i></span>
                    <div>
                        <h5 class="mm-modal-title" id="loadWalletModalLabel">Load Wallet Funds</h5>
                        <div class="mm-modal-subtitle">Credit balance to a member account</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="loadWalletForm" method="POST" action="">
                <div class="mm-modal-body">
                    <div class="mm-summary-card">
                        <div class="mm-summary-row">
                            <span class="mm-summary-label"><i class="fa-solid fa-user"></i> Member</span>
                            <input type="text" id="walletMemberName" class="mm-summary-value" readonly>
                        </div>
                        <div class="mm-summary-divider"></div>
                        <div class="mm-summary-row">
                            <span class="mm-summary-label"><i class="fa-solid fa-wallet"></i> Current Balance</span>
                            <input type="text" id="walletCurrentBalance" class="mm-summary-value mm-summary-balance" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="amount" class="mm-field-label">Amount to Add (₹)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                            <input type="number" step="0.01" name="amount" id="amount" class="form-control" placeholder="0.00" min="0.01" required>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label for="remark" class="mm-field-label">Transaction Remark</label>
                        <input type="text" name="remark" id="remark" class="form-control" placeholder="e.g. Approved loading bonus" required>
                    </div>
                </div>

                <div class="mm-modal-footer">
                    <button type="button" class="mm-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="mm-btn-submit">
                        <i class="fa-solid fa-check"></i> Credit Wallet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* ================= Design Tokens ================= */
    /* Replace the top of your <style> block */
    :root {
        --mm-gold: #d4af37;
        --mm-gold-dark: #b8942a;
        --mm-gold-light: #f3e2ab;
        --mm-pink: #ec407a;
        --mm-ink: #14181f;
        --mm-ink-soft: #2a3040;
        --mm-muted: #6b7280;
        --mm-border: #e8e9ee;
        --mm-surface: #ffffff;
        --mm-bg: #f7f7fb;
        --mm-success: #10b981;
        --mm-danger: #ef4444;
        --mm-radius-lg: 18px;
        --mm-radius-md: 14px;
        --mm-shadow-sm: 0 1px 2px rgba(20, 24, 31, 0.04), 0 1px 1px rgba(20, 24, 31, 0.03);
        --mm-shadow-md: 0 8px 24px -8px rgba(20, 24, 31, 0.12);
        --mm-shadow-lg: 0 24px 48px -12px rgba(20, 24, 31, 0.22);
    }

    .mm-view {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    /* ---------- Header ---------- */
    .mm-eyebrow {
        text-transform: uppercase;
        letter-spacing: 1.2px;
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--mm-gold-dark);
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .mm-eyebrow::before {
        content: "";
        width: 18px;
        height: 2px;
        background: var(--mm-gold);
        display: inline-block;
        border-radius: 2px;
    }

    .mm-title {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        color: var(--mm-ink);
        letter-spacing: -0.5px;
        font-size: 1.9rem;
        margin: 0;
    }

    .mm-subtitle {
        color: var(--mm-muted);
        font-size: 0.92rem;
        margin-top: 6px;
    }

    /* ---------- Toolbar ---------- */
    .mm-toolbar {
        border-radius: var(--mm-radius-md);
        border: 1px solid var(--mm-border);
        background: var(--mm-surface);
        padding: 12px 14px;
        box-shadow: var(--mm-shadow-sm);
    }

    .mm-filter-form {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .mm-search-field {
        display: flex;
        align-items: center;
        gap: 10px;
        border: 1px solid var(--mm-border);
        border-radius: 11px;
        background: #fbfbfd;
        padding: 0 14px;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .mm-search-field:focus-within {
        border-color: var(--mm-gold);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.14);
        background: #fff;
    }

    .mm-search-field i {
        color: var(--mm-muted);
        font-size: 0.9rem;
    }

    .mm-search-field input {
        flex: 1;
        min-width: 0;
        border: none;
        outline: none;
        background: transparent;
        padding: 11px 0;
        font-size: 0.9rem;
    }

    .mm-select {
        border: 1px solid var(--mm-border);
        border-radius: 11px;
        background: #fbfbfd;
        padding: 11px 12px;
        font-size: 0.9rem;
        color: var(--mm-ink-soft);
        outline: none;
    }

    .mm-select:focus {
        border-color: var(--mm-gold);
    }

    .mm-reset-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border: 1px solid var(--mm-border);
        background: #fff;
        color: var(--mm-ink-soft);
        font-weight: 600;
        font-size: 0.86rem;
        padding: 11px 14px;
        border-radius: 11px;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .mm-reset-btn:hover {
        background: #f4f4f7;
        border-color: #d8d9e0;
    }

    @media (min-width: 768px) {
        .mm-filter-form {
            flex-direction: row;
            align-items: center;
        }

        .mm-search-field {
            flex: 1 1 320px;
        }

        .mm-select {
            flex: 0 0 200px;
        }

        .mm-reset-btn {
            flex: 0 0 auto;
        }
    }

    /* ---------- Table ---------- */
    .mm-table-wrapper {
        border-radius: var(--mm-radius-lg);
        border: 1px solid var(--mm-border);
        background: var(--mm-surface);
        overflow: hidden;
        box-shadow: var(--mm-shadow-sm);
    }

    .mm-table-scroll {
        overflow-x: auto;
    }

    .mm-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.88rem;
    }

    .mm-table thead th {
        background: var(--mm-ink);
        color: #fff;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        font-weight: 700;
        text-align: left;
        padding: 13px 14px;
        white-space: nowrap;
        box-shadow: inset 0 -3px 0 var(--mm-gold);
        border: none;
    }

    .mm-table tbody tr {
        border-bottom: 1px solid var(--mm-border);
        transition: background 0.15s ease;
    }

    .mm-table tbody tr:last-child {
        border-bottom: none;
    }

    .mm-table tbody tr:hover {
        background: #fbfaf6;
    }

    .mm-table td {
        padding: 13px 14px;
        vertical-align: middle;
    }

    .mm-muted {
        color: var(--mm-muted);
    }

    .mm-uid-badge {
        font-family: 'SFMono-Regular', Consolas, monospace;
        font-size: 0.76rem;
        font-weight: 700;
        background: #f1f2f6;
        color: var(--mm-ink-soft);
        padding: 4px 10px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        letter-spacing: 0.3px;
    }

    .mm-uid-badge i {
        color: var(--mm-gold-dark);
    }

    .mm-name {
        font-weight: 700;
        color: var(--mm-ink);
        font-size: 0.92rem;
    }

    .mm-phone {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--mm-ink-soft);
        font-weight: 500;
        font-size: 0.86rem;
    }

    .mm-phone i {
        font-size: 0.72rem;
        color: var(--mm-muted);
    }

    .mm-phone-none {
        color: var(--mm-muted);
    }

    .mm-balance {
        font-weight: 700;
        color: #16a34a;
        font-size: 0.92rem;
    }

    /* Status cell */
    .mm-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.74rem;
        font-weight: 700;
        padding: 5px 11px;
        border-radius: 30px;
        width: fit-content;
        line-height: 1.2;
        border: 1px solid transparent;
    }

    .mm-status-active {
        background: #ecfdf5;
        color: #047857;
        border-color: #a7f3d0;
    }

    .mm-status-review {
        background: #fffbeb;
        color: #b45309;
        border-color: #fde68a;
    }

    .mm-status-incomplete {
        background: #fff7ed;
        color: #c2410c;
        border-color: #ffedd5;
    }

    .mm-status-blocked {
        background: #fef2f2;
        color: #b91c1c;
        border-color: #fecaca;
    }

    .mm-progress-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 7px;
    }

    .mm-progress-track {
        flex: 1;
        max-width: 110px;
        height: 5px;
        background: #e5e7eb;
        border-radius: 999px;
        overflow: hidden;
    }

    .mm-progress-bar {
        height: 100%;
        border-radius: 999px;
        transition: width 0.3s ease;
    }

    .mm-progress-100 {
        background: linear-gradient(90deg, #10b981, #059669);
    }

    .mm-progress-mid {
        background: linear-gradient(90deg, #3b82f6, #2563eb);
    }

    .mm-progress-low {
        background: linear-gradient(90deg, #f59e0b, #d97706);
    }

    .mm-progress-zero {
        background: #cbd5e1;
    }

    .mm-progress-pct {
        font-size: 0.72rem;
        font-weight: 700;
        color: var(--mm-muted);
        min-width: 30px;
    }

    .mm-kyc-note {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 0.72rem;
        font-weight: 600;
        margin-top: 4px;
    }

    .mm-kyc-verified {
        color: #4338ca;
    }

    .mm-kyc-pending {
        color: #b45309;
    }

    .mm-kyc-note i {
        font-size: 0.7rem;
    }

    /* Actions */
    .mm-actions {
        display: flex;
        gap: 6px;
        justify-content: flex-end;
    }

    .mm-icon-btn {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        border: 1px solid transparent;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.15s ease;
        flex-shrink: 0;
    }

    .mm-icon-btn:active {
        transform: translateY(1px);
    }

    .mm-icon-wallet {
        background: #ecfdf5;
        border-color: #a7f3d0;
        color: #16a34a;
    }

    .mm-icon-wallet:hover {
        background: #16a34a;
        border-color: #16a34a;
        color: #fff;
    }

    .mm-icon-edit {
        background: #fffbeb;
        border-color: #fde68a;
        color: #d97706;
    }

    .mm-icon-edit:hover {
        background: #d97706;
        border-color: #d97706;
        color: #fff;
    }

    .mm-icon-view {
        background: #eff6ff;
        border-color: #bfdbfe;
        color: #2563eb;
    }

    .mm-icon-view:hover {
        background: #2563eb;
        border-color: #2563eb;
        color: #fff;
    }

    .mm-icon-activate {
        background: #ecfdf5;
        border-color: #a7f3d0;
        color: #16a34a;
    }

    .mm-icon-activate:hover {
        background: #16a34a;
        border-color: #16a34a;
        color: #fff;
    }

    .mm-icon-deactivate {
        background: #fef2f2;
        border-color: #fecaca;
        color: #dc2626;
    }

    .mm-icon-deactivate:hover {
        background: #dc2626;
        border-color: #dc2626;
        color: #fff;
    }

    /* Empty state */
    .mm-empty {
        text-align: center;
        padding: 56px 20px;
        color: var(--mm-muted);
    }

    .mm-empty-card {
        background: #fff;
        border-radius: var(--mm-radius-md);
        border: 1px solid var(--mm-border);
    }

    .mm-empty-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: #f4f4f8;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 14px auto;
        font-size: 1.5rem;
        color: var(--mm-muted);
    }

    .mm-empty p {
        margin: 0;
        font-size: 0.9rem;
    }

    /* ---------- Mobile Cards ---------- */
    .mm-mobile-list {
        padding: 12px;
        background: var(--mm-bg);
    }

    .mm-mobile-card {
        border-radius: var(--mm-radius-md);
        border: 1px solid var(--mm-border);
        background: var(--mm-surface);
        padding: 14px;
        margin-bottom: 12px;
        box-shadow: var(--mm-shadow-sm);
    }

    .mm-mobile-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
    }

    .mm-mobile-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px dashed var(--mm-border);
        font-size: 0.82rem;
        color: var(--mm-muted);
    }

    .mm-mobile-status {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px dashed var(--mm-border);
    }

    .mm-mobile-actions {
        display: flex;
        gap: 8px;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid var(--mm-border);
    }

    .mm-mobile-actions .mm-icon-btn.flex-grow-1 {
        width: auto;
        flex: 1;
        gap: 6px;
        font-size: 0.82rem;
        font-weight: 700;
    }

    /* ---------- Pagination Footer ---------- */
    .mm-footer {
        background: var(--mm-surface);
        border-top: 1px solid var(--mm-border);
        padding: 14px 18px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
        justify-content: space-between;
    }

    .mm-footer-info {
        color: var(--mm-muted);
        font-size: 0.84rem;
    }

    .mm-pagination {
        display: flex;
        gap: 4px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .mm-pagination .page-link {
        border: 1px solid var(--mm-border);
        border-radius: 8px;
        color: var(--mm-ink-soft);
        font-weight: 600;
        font-size: 0.83rem;
        padding: 6px 11px;
        transition: all 0.15s ease;
    }

    .mm-pagination .page-item.active .page-link {
        background: linear-gradient(135deg, var(--mm-pink) 0%, var(--mm-gold) 100%);
        border-color: transparent;
        color: #fff;
    }

    .mm-pagination .page-item.disabled .page-link {
        opacity: 0.45;
    }

    .mm-pagination .page-link:hover:not(.disabled) {
        border-color: var(--mm-gold);
        background: #fffaf0;
    }

    /* ---------- Wallet Modal (matches compact style) ---------- */
    .mm-modal .modal-dialog {
        max-width: 440px;
    }

    .mm-modal .modal-content {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: var(--mm-shadow-lg);
    }

    .mm-modal-header {
        background: linear-gradient(135deg, var(--mm-ink) 0%, var(--mm-ink-soft) 100%);
        position: relative;
        color: #fff;
        padding: 16px 20px;
    }

    .mm-modal-header::after {
        content: "";
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--mm-pink), var(--mm-gold));
    }

    .mm-modal-icon-badge {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.12);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--mm-gold-light);
        font-size: 0.92rem;
        margin-right: 10px;
        flex-shrink: 0;
    }

    .mm-modal-title {
        font-weight: 700;
        font-size: 1rem;
        margin: 0;
        color: #fff;
    }

    .mm-modal-subtitle {
        font-size: 0.74rem;
        color: rgba(255, 255, 255, 0.6);
        margin-top: 1px;
    }

    .mm-modal .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
        opacity: 0.7;
        font-size: 0.78rem;
    }

    .mm-modal .btn-close:hover {
        opacity: 1;
    }

    .mm-modal-body {
        padding: 20px;
        background: #fdfdfd;
    }

    .mm-summary-card {
        background: #fbfaf9;
        border: 1px solid #f0eae5;
        border-radius: 12px;
        padding: 12px 14px;
        margin-bottom: 16px;
    }

    .mm-summary-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .mm-summary-label {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--mm-muted);
        font-size: 0.8rem;
        font-weight: 600;
    }

    .mm-summary-value {
        border: none;
        background: transparent;
        text-align: right;
        font-weight: 700;
        color: var(--mm-ink);
        font-size: 0.88rem;
        outline: none;
        max-width: 55%;
    }

    .mm-summary-balance {
        color: #16a34a;
    }

    .mm-summary-divider {
        height: 1px;
        background: #f0eae5;
        margin: 8px 0;
    }

    .mm-field-label {
        font-weight: 700;
        color: var(--mm-ink);
        font-size: 0.82rem;
        margin-bottom: 7px;
        display: block;
    }

    .mm-modal-body .input-group-text {
        background: #fff;
        border-color: var(--mm-border);
        color: var(--mm-gold-dark);
    }

    .mm-modal-body .form-control {
        border-color: var(--mm-border);
        padding: 9px 12px;
        font-size: 0.9rem;
    }

    .mm-modal-body .form-control:focus {
        border-color: var(--mm-gold);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.15);
    }

    .mm-modal-footer {
        background: #fdfdfd;
        border-top: 1px solid var(--mm-border);
        padding: 14px 20px;
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }

    .mm-btn-cancel {
        border: 1px solid var(--mm-border);
        background: #fff;
        color: var(--mm-ink-soft);
        border-radius: 10px;
        font-weight: 600;
        padding: 9px 16px;
        font-size: 0.86rem;
    }

    .mm-btn-cancel:hover {
        background: #f4f4f7;
    }

    .mm-btn-submit {
        background: linear-gradient(135deg, var(--mm-pink) 0%, var(--mm-gold) 100%);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        padding: 9px 18px;
        font-size: 0.86rem;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        box-shadow: 0 8px 18px -6px rgba(236, 64, 122, 0.45);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .mm-btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 22px -6px rgba(236, 64, 122, 0.5);
    }

    @media (max-width: 575px) {
        .mm-title {
            font-size: 1.5rem;
        }

        .mm-modal-body {
            padding: 16px;
        }

        .mm-modal-header {
            padding: 14px 16px;
        }

        .mm-modal-footer {
            padding: 12px 16px;
        }
    }
</style>

<!-- ============================================================== -->
<!-- 5. JavaScript (unchanged behaviour) -->
<!-- ============================================================== -->
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
            const pageLink = e.target.closest('#table-container .pagination .page-link, #table-container .mm-pagination .page-link');
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