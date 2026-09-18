<style>
    /* =======================================================
       Wallet Withdrawal Requests — Luxury Scoped Styles (.wd-)
       ======================================================= */
    .wd-wrap {
        --wd-gold: #c89738;
        --wd-gold-dark: #a97c26;
        --wd-pink: #ec407a;
        --wd-dark: #111827;
        --wd-dark-2: #1f2937;
        --wd-border: #e5e7eb;
        --wd-muted: #6b7280;
        --wd-text: #111827;
        --wd-radius: 16px;
        --wd-radius-sm: 12px;
        --wd-shadow: 0 2px 8px rgba(17, 24, 39, .05);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        color: var(--wd-text);
    }

    .wd-wrap * {
        box-sizing: border-box;
    }

    /* ---------- Header ---------- */
    .wd-header {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 22px;
    }

    .wd-header-left h3 {
        font-size: clamp(1.35rem, 2vw, 1.75rem);
        font-weight: 800;
        color: var(--wd-dark);
        margin: 0 0 6px 0;
        letter-spacing: -.02em;
    }

    .wd-header-left p {
        color: var(--wd-muted);
        font-size: .9rem;
        margin: 0;
    }

    .wd-btn-min-amount {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 10px 20px;
        background: linear-gradient(135deg, #c89738, #a97c26);
        color: #fff;
        font-weight: 600;
        font-size: .88rem;
        border-radius: var(--wd-radius-sm);
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(200, 151, 56, .35);
        transition: all .2s ease;
    }

    .wd-btn-min-amount:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(200, 151, 56, .45);
        color: #fff;
    }

    /* ---------- Stats Widgets ---------- */
    .wd-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 22px;
    }

    .wd-stat-card {
        background: #fff;
        border: 1px solid var(--wd-border);
        border-radius: var(--wd-radius);
        padding: 18px 20px;
        box-shadow: var(--wd-shadow);
        display: flex;
        align-items: center;
        gap: 16px;
        position: relative;
        overflow: hidden;
    }

    .wd-stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--wd-gold);
    }

    .wd-stat-card.pending::before { background: #f59e0b; }
    .wd-stat-card.approved::before { background: #10b981; }
    .wd-stat-card.rejected::before { background: #ef4444; }
    .wd-stat-card.min-limit::before { background: #ec407a; }

    .wd-stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .wd-stat-icon.pending { background: #fef3c7; color: #b45309; }
    .wd-stat-icon.approved { background: #d1fae5; color: #047857; }
    .wd-stat-icon.rejected { background: #fee2e2; color: #b91c1c; }
    .wd-stat-icon.min-limit { background: #fce7f3; color: #be185d; }

    .wd-stat-info h5 {
        margin: 0 0 4px 0;
        font-size: .8rem;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: var(--wd-muted);
        font-weight: 600;
    }

    .wd-stat-info .stat-value {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--wd-dark);
        line-height: 1.2;
    }

    .wd-stat-info .stat-sub {
        font-size: .78rem;
        color: var(--wd-muted);
        margin-top: 3px;
    }

    /* ---------- Filter bar ---------- */
    .wd-filter-card {
        background: #fff;
        border: 1px solid var(--wd-border);
        border-radius: var(--wd-radius);
        box-shadow: var(--wd-shadow);
        padding: 16px 18px;
        margin-bottom: 22px;
    }

    .wd-filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
    }

    .wd-search-wrap {
        flex: 1 1 300px;
        display: flex;
        align-items: center;
        border: 1.5px solid var(--wd-border);
        border-radius: var(--wd-radius-sm);
        background: #fff;
        overflow: hidden;
        transition: border-color .18s ease, box-shadow .18s ease;
    }

    .wd-search-wrap:focus-within {
        border-color: var(--wd-gold);
        box-shadow: 0 0 0 4px rgba(200, 151, 56, .14);
    }

    .wd-search-wrap i {
        padding: 0 14px;
        color: var(--wd-muted);
    }

    .wd-search-wrap input {
        border: none;
        outline: none;
        flex: 1;
        padding: 11px 14px 11px 0;
        font-size: .87rem;
        font-family: inherit;
    }

    .wd-status-select {
        flex: 1 1 180px;
        padding: 11px 34px 11px 14px;
        border: 1.5px solid var(--wd-border);
        border-radius: var(--wd-radius-sm);
        font-size: .87rem;
        font-family: inherit;
        background: #fff;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%236b7280' stroke-width='1.6' fill='none' fill-rule='evenodd'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        transition: border-color .18s ease, box-shadow .18s ease;
    }

    .wd-status-select:focus {
        outline: none;
        border-color: var(--wd-gold);
        box-shadow: 0 0 0 4px rgba(200, 151, 56, .14);
    }

    .wd-reset-btn {
        flex: 0 0 auto;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 20px;
        border-radius: var(--wd-radius-sm);
        background: var(--wd-dark-2);
        color: #fff;
        border: none;
        font-weight: 600;
        font-size: .85rem;
        cursor: pointer;
        transition: all .18s ease;
    }

    .wd-reset-btn:hover {
        background: var(--wd-dark);
        transform: translateY(-1px);
    }

    @media (max-width:575px) {
        .wd-reset-btn {
            width: 100%;
            justify-content: center;
        }
    }

    /* ---------- Table card ---------- */
    .wd-table-card {
        background: #fff;
        border: 1px solid var(--wd-border);
        border-radius: var(--wd-radius);
        box-shadow: var(--wd-shadow);
        overflow: hidden;
    }

    .wd-table-scroll {
        overflow-x: auto;
    }

    .wd-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: .88rem;
    }

    .wd-table thead th {
        background: #f9fafb;
        color: #374151;
        font-weight: 700;
        text-transform: uppercase;
        font-size: .73rem;
        letter-spacing: .5px;
        padding: 14px 16px;
        border-bottom: 1.5px solid var(--wd-border);
        white-space: nowrap;
    }

    .wd-table tbody tr {
        border-bottom: 1px solid var(--wd-border);
        transition: background-color .15s ease;
    }

    .wd-table tbody tr:hover {
        background-color: #fafbfc;
    }

    .wd-table tbody td {
        padding: 14px 16px;
        vertical-align: middle;
        color: var(--wd-text);
    }

    .wd-idx {
        font-weight: 700;
        color: var(--wd-muted);
        font-size: .82rem;
    }

    .wd-member-name {
        font-weight: 700;
        color: var(--wd-dark);
        font-size: .92rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .wd-custom-id-pill {
        font-size: .7rem;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 6px;
        background: #f3f4f6;
        color: #4b5563;
        letter-spacing: .3px;
    }

    .wd-member-sub {
        font-size: .78rem;
        color: var(--wd-muted);
        margin-top: 2px;
    }

    .wd-member-bal {
        font-size: .74rem;
        color: #059669;
        font-weight: 600;
        margin-top: 2px;
    }

    .wd-amount {
        font-weight: 800;
        font-size: 1rem;
        color: #b91c1c;
        white-space: nowrap;
    }

    /* Bank info badge */
    .wd-bank-box {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .wd-bank-name {
        font-weight: 700;
        font-size: .86rem;
        color: var(--wd-dark);
    }

    .wd-bank-acc {
        font-family: monospace;
        font-size: .82rem;
        color: #374151;
        letter-spacing: .5px;
    }

    .wd-bank-ifsc {
        font-size: .74rem;
        color: var(--wd-muted);
        font-weight: 600;
    }

    .wd-no-bank {
        font-size: .78rem;
        color: #dc2626;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .wd-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 8px;
        font-size: .76rem;
        font-weight: 700;
        text-transform: capitalize;
    }

    .wd-status-approved {
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    .wd-status-rejected {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    .wd-status-pending {
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fcd34d;
    }

    .wd-date {
        font-size: .82rem;
        color: var(--wd-muted);
        white-space: nowrap;
    }

    .wd-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }

    .wd-btn-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: .88rem;
        border: 1.5px solid transparent;
        cursor: pointer;
        transition: all .18s ease;
        background: none;
    }

    .wd-btn-view {
        background: #eff6ff;
        color: #2563eb;
        border-color: #bfdbfe;
    }

    .wd-btn-view:hover {
        background: #2563eb;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(37, 99, 235, .28);
    }

    .wd-btn-approve {
        background: #f0fdf4;
        color: #059669;
        border-color: #bbf7d0;
    }

    .wd-btn-approve:hover {
        background: #059669;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(5, 150, 105, .28);
    }

    .wd-btn-reject {
        background: #fef2f2;
        color: #dc2626;
        border-color: #fecaca;
    }

    .wd-btn-reject:hover {
        background: #dc2626;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(220, 38, 38, .28);
    }

    /* Empty state */
    .wd-empty {
        text-align: center;
        padding: 60px 20px;
        color: var(--wd-muted);
    }

    .wd-empty i {
        font-size: 3rem;
        color: #d1d5db;
        margin-bottom: 14px;
        display: block;
    }

    /* Pagination footer */
    .wd-footer {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 16px 20px;
        border-top: 1px solid var(--wd-border);
        background: #fafbfc;
    }

    .wd-footer-info {
        font-size: .84rem;
        color: var(--wd-muted);
    }

    .wd-pagination {
        display: flex;
        list-style: none;
        gap: 6px;
        margin: 0;
        padding: 0;
    }

    .wd-page-item.disabled .wd-page-link {
        opacity: .4;
        pointer-events: none;
    }

    .wd-page-link {
        padding: 6px 12px;
        border-radius: 8px;
        border: 1px solid var(--wd-border);
        background: #fff;
        color: var(--wd-text);
        text-decoration: none;
        font-size: .82rem;
        font-weight: 600;
        transition: all .15s ease;
    }

    .wd-page-item.active .wd-page-link {
        background: var(--wd-gold);
        border-color: var(--wd-gold);
        color: #fff;
    }

    .wd-page-link:hover:not(.active) {
        background: #f3f4f6;
    }

    /* ---------- Modal Styles ---------- */
    .wd-modal .modal-content {
        border-radius: 18px;
        border: none;
        box-shadow: 0 20px 40px rgba(0, 0, 0, .2);
        overflow: hidden;
    }

    .wd-modal .modal-header {
        background: linear-gradient(135deg, #111827, #1f2937);
        color: #fff;
        padding: 18px 24px;
        border: none;
    }

    .wd-modal .modal-header.modal-header-gold {
        background: linear-gradient(135deg, #c89738, #a97c26);
    }

    .wd-modal .modal-header.modal-header-danger {
        background: linear-gradient(135deg, #dc2626, #991b1b);
    }

    .wd-modal .modal-header.modal-header-success {
        background: linear-gradient(135deg, #059669, #047857);
    }

    .wd-modal .modal-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .wd-modal .modal-body {
        padding: 24px;
        color: var(--wd-text);
    }

    .wd-detail-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
    }

    @media (max-width:575px) {
        .wd-detail-grid {
            grid-template-columns: 1fr;
        }
    }

    .wd-detail-item {
        background: #f9fafb;
        border: 1px solid var(--wd-border);
        border-radius: 10px;
        padding: 12px 14px;
    }

    .wd-detail-item.full-width {
        grid-column: 1 / -1;
    }

    .wd-detail-label {
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--wd-muted);
        letter-spacing: .4px;
        margin-bottom: 4px;
    }

    .wd-detail-val {
        font-size: .92rem;
        font-weight: 600;
        color: var(--wd-dark);
        word-break: break-word;
    }

    .wd-detail-val.highlight {
        color: #dc2626;
        font-size: 1.15rem;
        font-weight: 800;
    }

    .wd-detail-val.bal-highlight {
        color: #059669;
        font-size: 1.05rem;
        font-weight: 800;
    }

    /* Warning callout */
    .wd-warning-box {
        background: #fffbeb;
        border-left: 4px solid #f59e0b;
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 18px;
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    .wd-warning-box i {
        color: #d97706;
        font-size: 1.2rem;
        margin-top: 2px;
    }

    .wd-warning-box p {
        margin: 0;
        font-size: .85rem;
        color: #92400e;
        line-height: 1.45;
    }

    .wd-info-box {
        background: #eff6ff;
        border-left: 4px solid #3b82f6;
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 18px;
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    .wd-info-box i {
        color: #2563eb;
        font-size: 1.2rem;
        margin-top: 2px;
    }

    .wd-info-box p {
        margin: 0;
        font-size: .85rem;
        color: #1e40af;
        line-height: 1.45;
    }
</style>

<div class="wd-wrap">

    <!-- Header Section -->
    <div class="wd-header">
        <div class="wd-header-left">
            <h3><i class="fa-solid fa-hand-holding-dollar me-2" style="color:var(--wd-gold);"></i>Wallet Withdrawal Requests</h3>
            <p>Review member payout requests, verify bank details, and approve wallet deductions.</p>
        </div>
        <div class="wd-header-right">
            <button type="button" class="wd-btn-min-amount" data-bs-toggle="modal" data-bs-target="#minAmountModal">
                <i class="fa-solid fa-sliders"></i>
                <span>Set Minimum Withdrawal (Min: ₹<?php echo number_format($min_withdraw_amount, 2); ?>)</span>
            </button>
        </div>
    </div>

    <!-- Alert Flash Messages -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>
            <?php echo $this->session->flashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i>
            <?php echo $this->session->flashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Summary Stats Grid -->
    <div class="wd-stats-grid">
        <div class="wd-stat-card pending">
            <div class="wd-stat-icon pending">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div class="wd-stat-info">
                <h5>Pending Requests</h5>
                <div class="stat-value"><?php echo number_format($stats['pending_count']); ?></div>
                <div class="stat-sub">₹<?php echo number_format($stats['pending_amount'], 2); ?> to review</div>
            </div>
        </div>

        <div class="wd-stat-card approved">
            <div class="wd-stat-icon approved">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div class="wd-stat-info">
                <h5>Approved Payouts</h5>
                <div class="stat-value"><?php echo number_format($stats['approved_count']); ?></div>
                <div class="stat-sub">₹<?php echo number_format($stats['approved_amount'], 2); ?> disbursed</div>
            </div>
        </div>

        <div class="wd-stat-card rejected">
            <div class="wd-stat-icon rejected">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
            <div class="wd-stat-info">
                <h5>Rejected Requests</h5>
                <div class="stat-value"><?php echo number_format($stats['rejected_count']); ?></div>
                <div class="stat-sub">Total declined</div>
            </div>
        </div>

        <div class="wd-stat-card min-limit">
            <div class="wd-stat-icon min-limit">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div class="wd-stat-info">
                <h5>Minimum Threshold</h5>
                <div class="stat-value">₹<?php echo number_format($min_withdraw_amount, 2); ?></div>
                <div class="stat-sub">App enforces this limit</div>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="wd-filter-card">
        <form id="filterForm" class="wd-filter-row" method="GET" action="<?php echo base_url('admin/withdrawals'); ?>">
            <div class="wd-search-wrap">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="search" id="searchInput" placeholder="Search by name, ID, phone, account #..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
            </div>
            <select name="status" id="statusFilter" class="wd-status-select" onchange="document.getElementById('filterForm').submit()">
                <option value="">All Statuses</option>
                <option value="pending" <?php echo ($status === 'pending') ? 'selected' : ''; ?>>Pending Only</option>
                <option value="approved" <?php echo ($status === 'approved') ? 'selected' : ''; ?>>Approved Only</option>
                <option value="rejected" <?php echo ($status === 'rejected') ? 'selected' : ''; ?>>Rejected Only</option>
            </select>
            <button type="button" id="resetBtn" class="wd-reset-btn" onclick="window.location.href='<?php echo base_url('admin/withdrawals'); ?>'">
                <i class="fa-solid fa-arrow-rotate-left"></i> Reset
            </button>
        </form>
    </div>

    <!-- Withdrawal Requests Table Card -->
    <div class="wd-table-card">
        <div class="wd-table-scroll">
            <table class="wd-table">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>Member Details</th>
                        <th>Requested Amount</th>
                        <th>Bank Details</th>
                        <th>Member Remark</th>
                        <th>Status</th>
                        <th>Date &amp; Time</th>
                        <th class="text-end" style="width:130px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($requests)): ?>
                        <tr>
                            <td colspan="8">
                                <div class="wd-empty">
                                    <i class="fa-solid fa-hand-holding-dollar"></i>
                                    No withdrawal requests found matching your filters.
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php
                        $index_num = ($current_page - 1) * 10 + 1;
                        foreach ($requests as $req):
                        ?>
                            <tr>
                                <td><span class="wd-idx"><?php echo $index_num++; ?></span></td>
                                <td>
                                    <div class="wd-member-name">
                                        <span><?php echo htmlspecialchars($req->user_name ?? 'Member'); ?></span>
                                        <?php if (!empty($req->user_custom_id)): ?>
                                            <span class="wd-custom-id-pill"><?php echo htmlspecialchars($req->user_custom_id); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="wd-member-sub">
                                        <i class="fa-solid fa-phone fa-xs me-1 text-muted"></i><?php echo htmlspecialchars($req->user_phone ?? '-'); ?>
                                        <?php if (!empty($req->user_email)): ?>
                                            &bull; <?php echo htmlspecialchars($req->user_email); ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="wd-member-bal">
                                        <i class="fa-solid fa-wallet fa-xs me-1"></i>Wallet: ₹<?php echo number_format((float)$req->user_wallet_balance, 2); ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="wd-amount">₹<?php echo number_format($req->amount, 2); ?></span>
                                </td>
                                <td>
                                    <?php if (!empty($req->account_number) || !empty($req->bank_name)): ?>
                                        <div class="wd-bank-box">
                                            <span class="wd-bank-name"><?php echo htmlspecialchars($req->bank_name ?: 'Bank'); ?></span>
                                            <span class="wd-bank-acc"><i class="fa-solid fa-credit-card fa-xs me-1 text-muted"></i>A/C: <?php echo htmlspecialchars($req->account_number); ?></span>
                                            <span class="wd-bank-ifsc">IFSC: <?php echo htmlspecialchars($req->ifsc_code ?: '-'); ?></span>
                                        </div>
                                    <?php else: ?>
                                        <span class="wd-no-bank"><i class="fa-solid fa-circle-exclamation"></i> Not provided</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="text-muted" style="font-size:.84rem;">
                                        <?php echo htmlspecialchars($req->remark ?: '-'); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($req->status === 'approved'): ?>
                                        <span class="wd-status wd-status-approved"><i class="fa-solid fa-circle-check"></i> Approved</span>
                                    <?php elseif ($req->status === 'rejected'): ?>
                                        <span class="wd-status wd-status-rejected"><i class="fa-solid fa-circle-xmark"></i> Rejected</span>
                                    <?php else: ?>
                                        <span class="wd-status wd-status-pending"><i class="fa-solid fa-clock"></i> Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="wd-date"><?php echo date('M d, Y H:i', strtotime($req->created_at)); ?></span>
                                </td>
                                <td class="text-end">
                                    <div class="wd-actions">
                                        <!-- View Details Button -->
                                        <button type="button"
                                            class="wd-btn-icon wd-btn-view view-withdraw-btn"
                                            title="View Full Bank & Request Details"
                                            data-bs-toggle="modal"
                                            data-bs-target="#withdrawDetailsModal"
                                            data-id="<?php echo $req->id; ?>"
                                            data-member-name="<?php echo htmlspecialchars($req->user_name ?? ''); ?>"
                                            data-custom-id="<?php echo htmlspecialchars($req->user_custom_id ?? ''); ?>"
                                            data-email="<?php echo htmlspecialchars($req->user_email ?? ''); ?>"
                                            data-phone="<?php echo htmlspecialchars($req->user_phone ?? ''); ?>"
                                            data-balance="<?php echo number_format((float)$req->user_wallet_balance, 2); ?>"
                                            data-amount="<?php echo number_format((float)$req->amount, 2); ?>"
                                            data-bank-name="<?php echo htmlspecialchars($req->bank_name ?? 'Not specified'); ?>"
                                            data-holder-name="<?php echo htmlspecialchars($req->account_holder_name ?? 'Not specified'); ?>"
                                            data-account-no="<?php echo htmlspecialchars($req->account_number ?? 'Not specified'); ?>"
                                            data-ifsc="<?php echo htmlspecialchars($req->ifsc_code ?? 'Not specified'); ?>"
                                            data-account-type="<?php echo htmlspecialchars($req->account_type ?? 'Savings'); ?>"
                                            data-branch="<?php echo htmlspecialchars($req->branch_name ?? 'Not specified'); ?>"
                                            data-remark="<?php echo htmlspecialchars($req->remark ?: 'None'); ?>"
                                            data-admin-remark="<?php echo htmlspecialchars($req->admin_remark ?: 'None'); ?>"
                                            data-status="<?php echo htmlspecialchars($req->status); ?>"
                                            data-action-by="<?php echo htmlspecialchars($req->action_by_name ?? 'Admin'); ?>"
                                            data-processed-at="<?php echo $req->processed_at ? date('M d, Y H:i', strtotime($req->processed_at)) : '-'; ?>"
                                            data-requested-at="<?php echo date('M d, Y H:i', strtotime($req->created_at)); ?>">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>

                                        <?php if ($req->status === 'pending'): ?>
                                            <!-- Approve Action Button -->
                                            <button type="button"
                                                class="wd-btn-icon wd-btn-approve approve-action-btn"
                                                title="Approve and Cut From Wallet"
                                                data-bs-toggle="modal"
                                                data-bs-target="#approveModal"
                                                data-id="<?php echo $req->id; ?>"
                                                data-member-name="<?php echo htmlspecialchars($req->user_name ?? ''); ?>"
                                                data-amount="<?php echo number_format((float)$req->amount, 2); ?>"
                                                data-raw-amount="<?php echo (float)$req->amount; ?>"
                                                data-balance="<?php echo number_format((float)$req->user_wallet_balance, 2); ?>"
                                                data-raw-balance="<?php echo (float)$req->user_wallet_balance; ?>"
                                                data-bank-name="<?php echo htmlspecialchars($req->bank_name ?? ''); ?>"
                                                data-account-no="<?php echo htmlspecialchars($req->account_number ?? ''); ?>"
                                                data-action-url="<?php echo base_url('admin/withdrawals/approve/' . $req->id); ?>">
                                                <i class="fa-solid fa-check"></i>
                                            </button>

                                            <!-- Reject Action Button -->
                                            <button type="button"
                                                class="wd-btn-icon wd-btn-reject reject-action-btn"
                                                title="Reject Withdrawal Request"
                                                data-bs-toggle="modal"
                                                data-bs-target="#rejectModal"
                                                data-id="<?php echo $req->id; ?>"
                                                data-member-name="<?php echo htmlspecialchars($req->user_name ?? ''); ?>"
                                                data-amount="<?php echo number_format((float)$req->amount, 2); ?>"
                                                data-action-url="<?php echo base_url('admin/withdrawals/reject/' . $req->id); ?>">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <?php if (!empty($total_rows) && $total_rows > 0):
            $start_record = ($current_page - 1) * 10 + 1;
            $end_record = min($current_page * 10, $total_rows);
        ?>
            <div class="wd-footer">
                <div class="wd-footer-info">
                    Showing <strong><?php echo $start_record; ?></strong> to <strong><?php echo $end_record; ?></strong> of <strong><?php echo number_format($total_rows); ?></strong> requests
                </div>
                <?php if ($total_pages > 1): ?>
                    <ul class="wd-pagination">
                        <li class="wd-page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                            <a class="wd-page-link" href="<?php echo base_url('admin/withdrawals?page=' . ($current_page - 1) . '&search=' . urlencode($search ?? '') . '&status=' . urlencode($status ?? '')); ?>">&laquo; Prev</a>
                        </li>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="wd-page-item <?php echo ($current_page == $i) ? 'active' : ''; ?>">
                                <a class="wd-page-link" href="<?php echo base_url('admin/withdrawals?page=' . $i . '&search=' . urlencode($search ?? '') . '&status=' . urlencode($status ?? '')); ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="wd-page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                            <a class="wd-page-link" href="<?php echo base_url('admin/withdrawals?page=' . ($current_page + 1) . '&search=' . urlencode($search ?? '') . '&status=' . urlencode($status ?? '')); ?>">Next &raquo;</a>
                        </li>
                    </ul>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- =======================================================
     MODAL 1: Full Details & Bank Snapshot Modal
     ======================================================= -->
<div class="modal fade wd-modal" id="withdrawDetailsModal" tabindex="-1" aria-labelledby="withdrawDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="withdrawDetailsModalLabel">
                    <i class="fa-solid fa-file-invoice-dollar text-warning"></i> Withdrawal Request Details #<span id="dtlRequestId"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="wd-detail-grid">
                    <!-- Member Details -->
                    <div class="wd-detail-item">
                        <div class="wd-detail-label">Member Name</div>
                        <div class="wd-detail-val" id="dtlMemberName">-</div>
                    </div>
                    <div class="wd-detail-item">
                        <div class="wd-detail-label">Custom Member ID</div>
                        <div class="wd-detail-val" id="dtlCustomId">-</div>
                    </div>
                    <div class="wd-detail-item">
                        <div class="wd-detail-label">Phone &amp; Email</div>
                        <div class="wd-detail-val" id="dtlContact">-</div>
                    </div>
                    <div class="wd-detail-item">
                        <div class="wd-detail-label">Current Wallet Balance</div>
                        <div class="wd-detail-val bal-highlight" id="dtlWalletBalance">₹0.00</div>
                    </div>

                    <!-- Payout Amount & Status -->
                    <div class="wd-detail-item">
                        <div class="wd-detail-label">Requested Withdrawal Amount</div>
                        <div class="wd-detail-val highlight" id="dtlAmount">₹0.00</div>
                    </div>
                    <div class="wd-detail-item">
                        <div class="wd-detail-label">Current Status</div>
                        <div class="wd-detail-val" id="dtlStatus">-</div>
                    </div>

                    <!-- Bank Details Section -->
                    <div class="wd-detail-item full-width bg-light">
                        <h6 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-building-columns text-primary me-2"></i>Bank Account Information (Snapshot)</h6>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="wd-detail-label">Account Holder Name</div>
                                <div class="wd-detail-val" id="dtlHolderName">-</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="wd-detail-label">Bank Name</div>
                                <div class="wd-detail-val" id="dtlBankName">-</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="wd-detail-label">Account Number</div>
                                <div class="wd-detail-val font-monospace fs-6" id="dtlAccountNo">-</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="wd-detail-label">IFSC Code</div>
                                <div class="wd-detail-val font-monospace" id="dtlIfsc">-</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="wd-detail-label">Account Type</div>
                                <div class="wd-detail-val" id="dtlAccountType">-</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="wd-detail-label">Branch Name</div>
                                <div class="wd-detail-val" id="dtlBranch">-</div>
                            </div>
                        </div>
                    </div>

                    <!-- Timestamps & Moderator -->
                    <div class="wd-detail-item">
                        <div class="wd-detail-label">Submitted At</div>
                        <div class="wd-detail-val" id="dtlRequestedAt">-</div>
                    </div>
                    <div class="wd-detail-item">
                        <div class="wd-detail-label">Processed At / By</div>
                        <div class="wd-detail-val" id="dtlProcessedInfo">-</div>
                    </div>

                    <!-- Remarks -->
                    <div class="wd-detail-item full-width">
                        <div class="wd-detail-label">Member Remark</div>
                        <div class="wd-detail-val" id="dtlRemark">-</div>
                    </div>
                    <div class="wd-detail-item full-width">
                        <div class="wd-detail-label">Admin Remark / Audit Note</div>
                        <div class="wd-detail-val" id="dtlAdminRemark">-</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary px-4 py-2 rounded-3" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- =======================================================
     MODAL 2: Approve Withdrawal & Cut Wallet Modal
     ======================================================= -->
<div class="modal fade wd-modal" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="approveForm" method="POST" action="">
                <div class="modal-header modal-header-success">
                    <h5 class="modal-title" id="approveModalLabel">
                        <i class="fa-solid fa-circle-check"></i> Approve Withdrawal Request #<span id="apprReqId"></span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="wd-warning-box">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <p>
                            <strong>Important:</strong> Approving this request will immediately deduct 
                            <strong class="text-danger" id="apprDeductAmount">₹0.00</strong> from 
                            <span id="apprMemberName"></span>'s wallet balance and record a debit entry.
                        </p>
                    </div>

                    <div class="p-3 bg-light rounded-3 mb-3 border">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Current Wallet Balance:</span>
                            <strong class="text-success" id="apprCurBalance">₹0.00</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Withdrawal Deduction:</span>
                            <strong class="text-danger" id="apprDeductPreview">-₹0.00</strong>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold small">Remaining Wallet Balance:</span>
                            <strong class="text-primary" id="apprRemainingBalance">₹0.00</strong>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Payout Destination Snapshot:</label>
                        <div class="p-2 px-3 bg-white rounded border small">
                            <i class="fa-solid fa-building-columns text-secondary me-2"></i>
                            <span id="apprBankDetails">-</span>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label for="approveRemark" class="form-label small fw-bold">Admin Remark (Optional):</label>
                        <input type="text" name="admin_remark" id="approveRemark" class="form-control" placeholder="e.g. Paid via NEFT/IMPS UTR: 1234567890">
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2 rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success px-4 py-2 rounded-3 fw-bold" id="confirmApproveBtn">
                        <i class="fa-solid fa-check me-1"></i> Approve &amp; Deduct Wallet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- =======================================================
     MODAL 3: Reject Withdrawal Request Modal
     ======================================================= -->
<div class="modal fade wd-modal" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="rejectForm" method="POST" action="">
                <div class="modal-header modal-header-danger">
                    <h5 class="modal-title" id="rejectModalLabel">
                        <i class="fa-solid fa-circle-xmark"></i> Reject Withdrawal Request #<span id="rejReqId"></span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="wd-info-box">
                        <i class="fa-solid fa-circle-info"></i>
                        <p>
                            Rejecting this request will mark the status as <strong>Rejected</strong>. 
                            <strong>No amount</strong> will be deducted from <span id="rejMemberName"></span>'s wallet balance.
                        </p>
                    </div>

                    <div class="mb-3">
                        <label for="rejectRemark" class="form-label small fw-bold">Reason for Rejection <span class="text-danger">*</span>:</label>
                        <textarea name="admin_remark" id="rejectRemark" class="form-control" rows="3" required placeholder="e.g. Incorrect bank account number or IFSC code mismatch. Please update your profile bank details."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2 rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger px-4 py-2 rounded-3 fw-bold">
                        <i class="fa-solid fa-xmark me-1"></i> Confirm Rejection
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- =======================================================
     MODAL 4: Set Minimum Withdrawal Amount Modal
     ======================================================= -->
<div class="modal fade wd-modal" id="minAmountModal" tabindex="-1" aria-labelledby="minAmountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="<?php echo base_url('admin/withdrawals/set_min_amount'); ?>">
                <div class="modal-header modal-header-gold">
                    <h5 class="modal-title" id="minAmountModalLabel">
                        <i class="fa-solid fa-sliders"></i> Set Minimum Withdrawal Amount
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">
                        Define the minimum threshold required for members to submit a withdrawal request. 
                        Members will not be allowed to request any amount lower than this threshold.
                    </p>

                    <div class="p-3 bg-light rounded-3 mb-3 border">
                        <span class="text-muted small">Current Minimum Limit:</span>
                        <h4 class="fw-bold text-dark mt-1 mb-0">₹<?php echo number_format($min_withdraw_amount, 2); ?></h4>
                    </div>

                    <div class="mb-3">
                        <label for="min_withdraw_amount" class="form-label small fw-bold">New Minimum Withdrawal Amount (₹) <span class="text-danger">*</span>:</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white fw-bold">₹</span>
                            <input type="number" step="1" min="1" max="1000000" name="min_withdraw_amount" id="min_withdraw_amount" class="form-control form-control-lg" value="<?php echo htmlspecialchars($min_withdraw_amount); ?>" required>
                        </div>
                        <div class="form-text small">Enter amount in Rupees (e.g. 500, 1000). Must be at least ₹1.</div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2 rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 fw-bold" style="background:var(--wd-gold); border-color:var(--wd-gold);">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save Minimum Limit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript Handlers -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. View Details Modal Populator
    const detailButtons = document.querySelectorAll('.view-withdraw-btn');
    detailButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('dtlRequestId').textContent = this.dataset.id;
            document.getElementById('dtlMemberName').textContent = this.dataset.memberName || 'Member';
            document.getElementById('dtlCustomId').textContent = this.dataset.customId || 'N/A';
            document.getElementById('dtlContact').textContent = (this.dataset.phone || '-') + ' / ' + (this.dataset.email || '-');
            document.getElementById('dtlWalletBalance').textContent = '₹' + this.dataset.balance;
            document.getElementById('dtlAmount').textContent = '₹' + this.dataset.amount;
            
            // Status badge
            const status = this.dataset.status;
            let statusBadge = '<span class="badge bg-warning text-dark">Pending</span>';
            if (status === 'approved') {
                statusBadge = '<span class="badge bg-success">Approved</span>';
            } else if (status === 'rejected') {
                statusBadge = '<span class="badge bg-danger">Rejected</span>';
            }
            document.getElementById('dtlStatus').innerHTML = statusBadge;

            // Bank details snapshot
            document.getElementById('dtlHolderName').textContent = this.dataset.holderName || '-';
            document.getElementById('dtlBankName').textContent = this.dataset.bankName || '-';
            document.getElementById('dtlAccountNo').textContent = this.dataset.accountNo || '-';
            document.getElementById('dtlIfsc').textContent = this.dataset.ifsc || '-';
            document.getElementById('dtlAccountType').textContent = this.dataset.accountType || 'Savings';
            document.getElementById('dtlBranch').textContent = this.dataset.branch || '-';

            // Timestamps & remarks
            document.getElementById('dtlRequestedAt').textContent = this.dataset.requestedAt || '-';
            document.getElementById('dtlProcessedInfo').textContent = this.dataset.processedAt !== '-' ? (this.dataset.processedAt + ' (by ' + this.dataset.actionBy + ')') : 'Not processed yet';
            document.getElementById('dtlRemark').textContent = this.dataset.remark || 'None';
            document.getElementById('dtlAdminRemark').textContent = this.dataset.adminRemark || 'None';
        });
    });

    // 2. Approve Modal Populator & Balance Validator
    const approveButtons = document.querySelectorAll('.approve-action-btn');
    approveButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const reqId = this.dataset.id;
            const memberName = this.dataset.memberName;
            const amount = parseFloat(this.dataset.rawAmount || 0);
            const balance = parseFloat(this.dataset.rawBalance || 0);
            const actionUrl = this.dataset.actionUrl;

            document.getElementById('apprReqId').textContent = reqId;
            document.getElementById('apprMemberName').textContent = memberName;
            document.getElementById('apprDeductAmount').textContent = '₹' + this.dataset.amount;
            document.getElementById('apprCurBalance').textContent = '₹' + this.dataset.balance;
            document.getElementById('apprDeductPreview').textContent = '-₹' + this.dataset.amount;
            
            const remaining = balance - amount;
            const remainingEl = document.getElementById('apprRemainingBalance');
            const confirmBtn = document.getElementById('confirmApproveBtn');

            if (remaining < 0) {
                remainingEl.textContent = '₹' + remaining.toFixed(2) + ' (Insufficient Balance!)';
                remainingEl.className = 'text-danger fw-bold';
                confirmBtn.disabled = true;
                confirmBtn.title = 'Cannot approve: Insufficient member balance';
            } else {
                remainingEl.textContent = '₹' + remaining.toFixed(2);
                remainingEl.className = 'text-primary fw-bold';
                confirmBtn.disabled = false;
                confirmBtn.title = '';
            }

            const bankSummary = (this.dataset.bankName || 'Bank') + ' - A/C: ' + (this.dataset.accountNo || 'N/A');
            document.getElementById('apprBankDetails').textContent = bankSummary;

            document.getElementById('approveForm').action = actionUrl;
        });
    });

    // 3. Reject Modal Populator
    const rejectButtons = document.querySelectorAll('.reject-action-btn');
    rejectButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('rejReqId').textContent = this.dataset.id;
            document.getElementById('rejMemberName').textContent = this.dataset.memberName;
            document.getElementById('rejectForm').action = this.dataset.actionUrl;
            document.getElementById('rejectRemark').value = '';
        });
    });
});
</script>
