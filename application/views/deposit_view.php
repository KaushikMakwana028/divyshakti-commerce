<style>
    /* =======================================================
   Wallet Deposit Requests — scoped styles (.dep- prefix)
   ======================================================= */
    .dep-wrap {
        --dep-gold: #c89738;
        --dep-gold-dark: #a97c26;
        --dep-dark: #111827;
        --dep-dark-2: #1f2937;
        --dep-border: #e5e7eb;
        --dep-muted: #6b7280;
        --dep-text: #111827;
        --dep-radius: 16px;
        --dep-radius-sm: 12px;
        --dep-shadow: 0 2px 8px rgba(17, 24, 39, .05);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        color: var(--dep-text);
    }

    .dep-wrap * {
        box-sizing: border-box;
    }

    /* ---------- Header ---------- */
    .dep-header {
        margin-bottom: 22px;
    }

    .dep-header h3 {
        font-size: clamp(1.35rem, 2vw, 1.75rem);
        font-weight: 800;
        color: var(--dep-dark);
        margin: 0 0 6px 0;
        letter-spacing: -.02em;
    }

    .dep-header p {
        color: var(--dep-muted);
        font-size: .9rem;
        margin: 0;
    }

    /* ---------- Filter bar ---------- */
    .dep-filter-card {
        background: #fff;
        border: 1px solid var(--dep-border);
        border-radius: var(--dep-radius);
        box-shadow: var(--dep-shadow);
        padding: 16px 18px;
        margin-bottom: 22px;
    }

    .dep-filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
    }

    .dep-search-wrap {
        flex: 1 1 320px;
        display: flex;
        align-items: center;
        border: 1.5px solid var(--dep-border);
        border-radius: var(--dep-radius-sm);
        background: #fff;
        overflow: hidden;
        transition: border-color .18s ease, box-shadow .18s ease;
    }

    .dep-search-wrap:focus-within {
        border-color: var(--dep-gold);
        box-shadow: 0 0 0 4px rgba(200, 151, 56, .14);
    }

    .dep-search-wrap i {
        padding: 0 14px;
        color: var(--dep-muted);
    }

    .dep-search-wrap input {
        border: none;
        outline: none;
        flex: 1;
        padding: 11px 14px 11px 0;
        font-size: .87rem;
        font-family: inherit;
    }

    .dep-status-select {
        flex: 1 1 200px;
        padding: 11px 34px 11px 14px;
        border: 1.5px solid var(--dep-border);
        border-radius: var(--dep-radius-sm);
        font-size: .87rem;
        font-family: inherit;
        background: #fff;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%236b7280' stroke-width='1.6' fill='none' fill-rule='evenodd'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        transition: border-color .18s ease, box-shadow .18s ease;
    }

    .dep-status-select:focus {
        outline: none;
        border-color: var(--dep-gold);
        box-shadow: 0 0 0 4px rgba(200, 151, 56, .14);
    }

    .dep-reset-btn {
        flex: 0 0 auto;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 20px;
        border-radius: var(--dep-radius-sm);
        background: var(--dep-dark-2);
        color: #fff;
        border: none;
        font-weight: 600;
        font-size: .85rem;
        cursor: pointer;
        transition: all .18s ease;
    }

    .dep-reset-btn:hover {
        background: var(--dep-dark);
        transform: translateY(-1px);
    }

    @media (max-width:575px) {
        .dep-reset-btn {
            width: 100%;
            justify-content: center;
        }
    }

    /* ---------- Table card ---------- */
    .dep-table-card {
        background: #fff;
        border: 1px solid var(--dep-border);
        border-radius: var(--dep-radius);
        box-shadow: var(--dep-shadow);
        overflow: hidden;
    }

    .dep-table-scroll {
        overflow-x: auto;
    }

    .dep-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 980px;
    }

    .dep-table thead th {
        background: var(--dep-dark-2);
        color: #fff;
        font-size: .72rem;
        text-transform: uppercase;
        letter-spacing: .6px;
        font-weight: 700;
        padding: 14px 16px;
        text-align: left;
        white-space: nowrap;
        border-bottom: 3px solid var(--dep-gold);
    }

    .dep-table thead th:first-child {
        padding-left: 22px;
    }

    .dep-table thead th.text-end {
        text-align: right;
        padding-right: 22px;
    }

    .dep-table tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f2f5;
        vertical-align: middle;
    }

    .dep-table tbody td:first-child {
        padding-left: 22px;
    }

    .dep-table tbody td.text-end {
        text-align: right;
        padding-right: 22px;
    }

    .dep-table tbody tr {
        transition: background-color .15s ease;
        cursor: pointer;
    }

    .dep-table tbody tr:hover {
        background: rgba(200, 151, 56, .06);
    }

    .dep-table tbody tr:last-child td {
        border-bottom: none;
    }

    .dep-idx {
        font-weight: 700;
        color: var(--dep-muted);
    }

    .dep-member-name {
        font-weight: 700;
        color: var(--dep-dark);
        font-size: .9rem;
    }

    .dep-member-sub {
        font-size: .78rem;
        color: var(--dep-muted);
        margin-top: 2px;
    }

    .dep-no-info {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: .7rem;
        font-weight: 500;
        color: var(--dep-muted);
        background: #f3f4f6;
        border: 1px solid var(--dep-border);
        padding: 3px 9px;
        border-radius: 999px;
    }

    .dep-amount {
        font-weight: 800;
        color: #059669;
        font-size: .98rem;
    }

    .dep-badge-online {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }

    .dep-badge-cash {
        background: #f3f4f6;
        color: #4b5563;
        border: 1px solid var(--dep-border);
    }

    .dep-method-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 999px;
        font-size: .75rem;
        font-weight: 700;
    }

    .dep-proof-thumb {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 50px;
        border-radius: 10px;
        overflow: hidden;
        border: 2px solid var(--dep-gold);
        box-shadow: var(--dep-shadow);
        cursor: zoom-in;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .dep-proof-thumb:hover {
        transform: scale(1.08);
        box-shadow: 0 4px 12px rgba(0, 0, 0, .15);
    }

    .dep-proof-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .dep-thumb-overlay {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, .5);
        opacity: 0;
        transition: opacity .2s ease;
        color: #fff;
        font-size: .85rem;
    }

    .dep-proof-thumb:hover .dep-thumb-overlay {
        opacity: 1;
    }

    .dep-proof-pdf {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 13px;
        border-radius: 9px;
        border: 1.5px solid #fecaca;
        background: #fef2f2;
        color: #dc2626;
        font-weight: 600;
        font-size: .78rem;
        text-decoration: none;
        transition: all .18s ease;
    }

    .dep-proof-pdf:hover {
        background: #dc2626;
        color: #fff;
        border-color: #dc2626;
    }

    .dep-proof-na {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 13px;
        border-radius: 999px;
        background: #f3f4f6;
        color: var(--dep-muted);
        border: 1px solid var(--dep-border);
        font-size: .74rem;
        font-weight: 500;
    }

    .dep-remark {
        font-size: .83rem;
        color: var(--dep-text);
    }

    .dep-status {
        display: inline-flex;
        align-items: center;
        padding: 5px 12px;
        border-radius: 8px;
        font-size: .75rem;
        font-weight: 700;
    }

    .dep-status-approved {
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    .dep-status-rejected {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    .dep-status-pending {
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fcd34d;
    }

    .dep-date {
        font-size: .82rem;
        color: var(--dep-muted);
        white-space: nowrap;
    }

    .dep-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }

    .dep-btn-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: .85rem;
        border: 1.5px solid transparent;
        cursor: pointer;
        transition: all .18s ease;
        background: none;
    }

    .dep-btn-view {
        background: #eff6ff;
        color: #2563eb;
        border-color: #bfdbfe;
    }

    .dep-btn-view:hover {
        background: #2563eb;
        border-color: #2563eb;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(37, 99, 235, .28);
    }

    .dep-btn-approve {
        background: #f0fdf4;
        color: #059669;
        border-color: #bbf7d0;
    }

    .dep-btn-approve:hover {
        background: #059669;
        border-color: #059669;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(5, 150, 105, .28);
    }

    .dep-btn-reject {
        background: #fef2f2;
        color: #dc2626;
        border-color: #fecaca;
    }

    .dep-btn-reject:hover {
        background: #dc2626;
        border-color: #dc2626;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(220, 38, 38, .28);
    }

    /* empty state */
    .dep-empty {
        text-align: center;
        padding: 60px 20px;
        color: var(--dep-muted);
    }

    .dep-empty i {
        font-size: 3rem;
        color: #d1d5db;
        margin-bottom: 14px;
        display: block;
    }

    /* footer / pagination */
    .dep-footer {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        border-top: 1px solid var(--dep-border);
    }

    .dep-footer-info {
        font-size: .83rem;
        color: var(--dep-muted);
    }

    .dep-pagination {
        display: flex;
        gap: 6px;
        list-style: none;
        margin: 0;
        padding: 0;
        flex-wrap: wrap;
    }

    .dep-page-link {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 7px 13px;
        border-radius: 9px;
        border: 1.5px solid var(--dep-border);
        font-size: .82rem;
        font-weight: 600;
        color: var(--dep-dark-2);
        text-decoration: none;
        transition: all .18s ease;
        cursor: pointer;
        background: #fff;
    }

    .dep-page-link:hover {
        border-color: var(--dep-gold);
        color: var(--dep-gold-dark);
    }

    .dep-page-item.active .dep-page-link {
        background: var(--dep-dark);
        border-color: var(--dep-dark);
        color: #fff;
    }

    .dep-page-item.disabled .dep-page-link {
        opacity: .4;
        pointer-events: none;
    }

    /* ---------- Modal restyle ---------- */
    .dep-modal .modal-content {
        border: none !important;
        border-radius: 18px !important;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(17, 24, 39, .25) !important;
    }

    .dep-modal .modal-header {
        background-color: #111827 !important;
        background-image: linear-gradient(120deg, #111827 0%, #1f2937 60%, #2c3646 100%) !important;
        color: #ffffff !important;
        padding: 20px 24px !important;
        border: none !important;
        border-bottom: none !important;
        position: relative;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
    }

    .dep-modal .modal-header::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--dep-gold);
    }

    .dep-modal .modal-title {
        font-weight: 800 !important;
        font-size: 1.05rem !important;
        display: flex !important;
        align-items: center;
        gap: 8px;
        color: #ffffff !important;
        margin: 0;
    }

    .dep-modal .modal-title i {
        color: #fbbf24 !important;
    }

    .dep-modal-close-btn {
        background: rgba(255, 255, 255, .14) !important;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff !important;
        font-size: .9rem;
        cursor: pointer;
        flex-shrink: 0;
        transition: background .18s ease;
        opacity: 1 !important;
    }

    .dep-modal-close-btn:hover {
        background: rgba(255, 255, 255, .28) !important;
    }

    .dep-modal .modal-body {
        padding: 24px;
    }

    .dep-modal-row {
        display: flex;
        gap: 16px;
        padding: 12px 0;
        border-bottom: 1px solid #f1f2f5;
    }

    .dep-modal-row:last-child {
        border-bottom: none;
    }

    .dep-modal-label {
        flex: 0 0 140px;
        font-weight: 700;
        color: var(--dep-muted);
        font-size: .84rem;
    }

    .dep-modal-value {
        flex: 1;
        font-size: .9rem;
        color: var(--dep-dark);
    }

    .dep-remark-box {
        background: #f8f9fc;
        border-left: 3px solid #ec407a;
        border-radius: 8px;
        padding: 12px 14px;
        font-size: .87rem;
        line-height: 1.5;
        color: var(--dep-dark);
    }

    .dep-modal-footer {
        background-color: #f8f9fc !important;
        padding: 16px 24px !important;
        border: none !important;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        flex-wrap: wrap;
    }

    .dep-modal-btn {
        padding: 9px 22px;
        border-radius: 10px;
        font-weight: 600;
        font-size: .85rem;
        border: none;
        cursor: pointer;
        transition: all .18s ease;
    }

    .dep-modal-btn-close {
        background: #e5e7eb;
        color: #374151;
    }

    .dep-modal-btn-close:hover {
        background: #d1d5db;
    }

    .dep-modal-btn-approve {
        background: #059669;
        color: #fff;
    }

    .dep-modal-btn-approve:hover {
        background: #047857;
    }

    .dep-modal-btn-reject {
        background: #dc2626;
        color: #fff;
    }

    .dep-modal-btn-reject:hover {
        background: #b91c1c;
    }

    /* ============ Mobile: table -> card transform ============ */
    @media (max-width:767px) {
        .dep-table-scroll {
            overflow-x: visible;
        }

        .dep-table {
            min-width: 0;
        }

        .dep-table thead {
            display: none;
        }

        .dep-table tbody tr {
            display: block;
            border: 1px solid var(--dep-border);
            border-radius: 14px;
            margin: 14px;
            box-shadow: 0 2px 6px rgba(17, 24, 39, .05);
        }

        .dep-table tbody tr:hover {
            background: #fff;
        }

        .dep-table tbody td {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 14px;
            border-bottom: 1px dashed #f0f1f5;
            text-align: right;
        }

        .dep-table tbody td:first-child,
        .dep-table tbody td:last-child {
            padding-left: 14px;
        }

        .dep-table tbody td:last-child {
            border-bottom: none;
        }

        .dep-table tbody td::before {
            content: attr(data-label);
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: var(--dep-muted);
            text-align: left;
            flex-shrink: 0;
        }

        .dep-actions {
            justify-content: flex-end;
            width: 100%;
        }
    }
</style>

<div class="dep-wrap">

    <div class="dep-header">
        <h3>Wallet Deposit Requests</h3>
        <p>Moderate deposit requests and verify payment proof receipts.</p>
    </div>

    <!-- Search and Filter Form -->
    <div class="dep-filter-card">
        <form id="filterForm" class="dep-filter-row">
            <div class="dep-search-wrap">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="search" id="searchInput" placeholder="Search by member name or email..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
            </div>
            <select name="status" id="statusFilter" class="dep-status-select">
                <option value="">All Statuses</option>
                <option value="pending" <?php echo ($status === 'pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="approved" <?php echo ($status === 'approved') ? 'selected' : ''; ?>>Approved</option>
                <option value="rejected" <?php echo ($status === 'rejected') ? 'selected' : ''; ?>>Rejected</option>
            </select>
            <button type="button" id="resetBtn" class="dep-reset-btn">
                <i class="fa-solid fa-arrow-rotate-left"></i> Reset
            </button>
        </form>
    </div>

    <!-- Deposit Requests Table -->
    <div class="dep-table-card">
        <div id="table-container">
            <div class="dep-table-scroll">
                <table class="dep-table">
                    <thead>
                        <tr>
                            <th style="width:60px;">#</th>
                            <th>Member</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Payment Proof</th>
                            <th>User Remark</th>
                            <th>Status</th>
                            <th>Requested At</th>
                            <th class="text-end" style="width:130px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($requests)): ?>
                            <tr>
                                <td colspan="9">
                                    <div class="dep-empty">
                                        <i class="fa-solid fa-receipt"></i>
                                        No deposit requests found matching the filters.
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php
                            $index_num = ($current_page - 1) * 10 + 1;
                            foreach ($requests as $req):
                            ?>
                                <tr class="deposit-row-clickable" data-id="<?php echo $req->id; ?>">
                                    <td data-label="#"><span class="dep-idx"><?php echo $index_num++; ?></span></td>
                                    <td data-label="Member">
                                        <div class="dep-member-name"><?php echo htmlspecialchars($req->user_name ?? 'Member'); ?></div>
                                        <?php if (!empty($req->user_email)): ?>
                                            <div class="dep-member-sub"><?php echo htmlspecialchars($req->user_email); ?></div>
                                        <?php else: ?>
                                            <span class="dep-no-info"><i class="fa-regular fa-envelope-open"></i>Not provided</span>
                                        <?php endif; ?>
                                    </td>
                                    <td data-label="Amount"><span class="dep-amount">₹<?php echo number_format($req->amount, 2); ?></span></td>
                                    <td data-label="Method">
                                        <?php if ($req->payment_method === 'online'): ?>
                                            <span class="dep-method-badge dep-badge-online">Online Transfer</span>
                                        <?php else: ?>
                                            <span class="dep-method-badge dep-badge-cash">Cash</span>
                                        <?php endif; ?>
                                    </td>
                                    <td data-label="Payment Proof">
                                        <?php if ($req->payment_method === 'online' && $req->proof_file): ?>
                                            <?php
                                            $ext = strtolower(pathinfo($req->proof_file, PATHINFO_EXTENSION));
                                            if ($ext === 'pdf'):
                                            ?>
                                                <a href="<?php echo base_url($req->proof_file); ?>" target="_blank" class="dep-proof-pdf">
                                                    <i class="fa-solid fa-file-pdf"></i> View PDF
                                                </a>
                                            <?php else: ?>
                                                <div class="dep-proof-thumb" onclick="viewReceipt('<?php echo base_url($req->proof_file); ?>')">
                                                    <img src="<?php echo base_url($req->proof_file); ?>" alt="Proof Thumbnail">
                                                    <div class="dep-thumb-overlay"><i class="fa-solid fa-magnifying-glass-plus"></i></div>
                                                </div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="dep-proof-na"><i class="fa-solid fa-money-bill-transfer"></i> N/A (Cash)</span>
                                        <?php endif; ?>
                                    </td>
                                    <td data-label="User Remark"><span class="dep-remark"><?php echo htmlspecialchars($req->remark ?: '-'); ?></span></td>
                                    <td data-label="Status">
                                        <?php if ($req->status === 'approved'): ?>
                                            <span class="dep-status dep-status-approved">Approved</span>
                                        <?php elseif ($req->status === 'rejected'): ?>
                                            <span class="dep-status dep-status-rejected">Rejected</span>
                                        <?php else: ?>
                                            <span class="dep-status dep-status-pending">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td data-label="Requested At"><span class="dep-date"><?php echo date('M d, Y H:i', strtotime($req->created_at)); ?></span></td>
                                    <td data-label="Actions" class="text-end">
                                        <div class="dep-actions">
                                            <button type="button"
                                                class="dep-btn-icon dep-btn-view view-details-btn"
                                                title="View Details"
                                                data-bs-toggle="modal"
                                                data-bs-target="#depositDetailsModal"
                                                data-id="<?php echo $req->id; ?>"
                                                data-member-name="<?php echo htmlspecialchars($req->user_name ?? 'Member'); ?>"
                                                data-member-email="<?php echo htmlspecialchars($req->user_email ?? 'Not provided'); ?>"
                                                data-member-phone="<?php echo htmlspecialchars($req->user_phone ?? ''); ?>"
                                                data-amount="<?php echo number_format($req->amount, 2); ?>"
                                                data-method="<?php echo htmlspecialchars($req->payment_method ?? ''); ?>"
                                                data-remark="<?php echo htmlspecialchars($req->remark ?: 'No remark provided'); ?>"
                                                data-status="<?php echo htmlspecialchars($req->status ?? 'pending'); ?>"
                                                data-proof-file="<?php echo $req->proof_file ? base_url($req->proof_file) : ''; ?>"
                                                data-requested-at="<?php echo date('M d, Y H:i', strtotime($req->created_at)); ?>">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                            <?php if ($req->status === 'pending'): ?>
                                                <form action="<?php echo base_url('admin/deposits/approve/' . $req->id); ?>" method="POST" class="approve-form d-inline-block m-0">
                                                    <button type="button" class="dep-btn-icon dep-btn-approve action-btn" data-action="approve" title="Approve Request">
                                                        <i class="fa-solid fa-check"></i>
                                                    </button>
                                                </form>
                                                <form action="<?php echo base_url('admin/deposits/reject/' . $req->id); ?>" method="POST" class="reject-form d-inline-block m-0">
                                                    <button type="button" class="dep-btn-icon dep-btn-reject action-btn" data-action="reject" title="Reject Request">
                                                        <i class="fa-solid fa-xmark"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if (!empty($total_rows) && $total_rows > 0):
                $start_record = ($current_page - 1) * 10 + 1;
                $end_record = min($current_page * 10, $total_rows);
            ?>
                <div class="dep-footer">
                    <div class="dep-footer-info">
                        Showing <strong><?php echo $start_record; ?></strong> to <strong><?php echo $end_record; ?></strong> of <strong><?php echo number_format($total_rows); ?></strong> available deposit requests
                    </div>
                    <?php if ($total_pages > 1): ?>
                        <nav aria-label="Deposit Request Page Navigation">
                            <ul class="dep-pagination">
                                <li class="dep-page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                                    <a class="dep-page-link" href="#" data-page="<?php echo $current_page - 1; ?>">&laquo; Prev</a>
                                </li>
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="dep-page-item <?php echo ($current_page == $i) ? 'active' : ''; ?>">
                                        <a class="dep-page-link" href="#" data-page="<?php echo $i; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>
                                <li class="dep-page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                                    <a class="dep-page-link" href="#" data-page="<?php echo $current_page + 1; ?>">Next &raquo;</a>
                                </li>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Deposit Details Modal -->
<div class="modal fade dep-modal" id="depositDetailsModal" tabindex="-1" aria-labelledby="depositDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="depositDetailsModalLabel">
                    <i class="fa-solid fa-receipt"></i> Deposit Request Details
                </h5>
                <button type="button" class="dep-modal-close-btn" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="dep-modal-row">
                    <div class="dep-modal-label">Member</div>
                    <div class="dep-modal-value">
                        <div style="font-weight:700;" id="modalMemberName"></div>
                        <div style="color:var(--dep-muted);font-size:.85rem;" id="modalMemberEmail"></div>
                        <div style="color:var(--dep-muted);font-size:.85rem;" id="modalMemberPhone"></div>
                    </div>
                </div>
                <div class="dep-modal-row">
                    <div class="dep-modal-label">Amount</div>
                    <div class="dep-modal-value"><span class="dep-amount" style="font-size:1.1rem;" id="modalAmount"></span></div>
                </div>
                <div class="dep-modal-row">
                    <div class="dep-modal-label">Payment Method</div>
                    <div class="dep-modal-value" id="modalMethod"></div>
                </div>
                <div class="dep-modal-row">
                    <div class="dep-modal-label">Status</div>
                    <div class="dep-modal-value" id="modalStatus"></div>
                </div>
                <div class="dep-modal-row">
                    <div class="dep-modal-label">Requested At</div>
                    <div class="dep-modal-value" id="modalRequestedAt"></div>
                </div>
                <div class="dep-modal-row">
                    <div class="dep-modal-label">User Remark</div>
                    <div class="dep-modal-value">
                        <div class="dep-remark-box" id="modalRemark"></div>
                    </div>
                </div>
                <div class="dep-modal-row" id="modalProofRow">
                    <div class="dep-modal-label">Payment Proof</div>
                    <div class="dep-modal-value" id="modalProofContainer"></div>
                </div>
            </div>
            <div class="dep-modal-footer" id="modalFooterActions">
                <button type="button" class="dep-modal-btn dep-modal-btn-close" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- AJAX Swapper and Event Delegation Binds -->
<script>
    function viewReceipt(url) {
        Swal.fire({
            title: 'Payment Proof Receipt',
            imageUrl: url,
            imageAlt: 'Receipt Payment Proof',
            showCloseButton: true,
            showConfirmButton: false,
            width: '560px',
            padding: '1.5rem',
            customClass: {
                image: 'rounded-3 border border-secondary shadow-lg img-fluid my-2'
            }
        });
    }

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
            const pageLink = e.target.closest('#table-container .dep-pagination .dep-page-link');
            if (pageLink) {
                e.preventDefault();
                const page = pageLink.getAttribute('data-page');
                if (page) {
                    loadTable(page);
                }
            }

            // Handle table row click to open details modal
            const depositRow = e.target.closest('.deposit-row-clickable');
            if (depositRow && !e.target.closest('.action-btn') && !e.target.closest('a') && !e.target.closest('button') && !e.target.closest('.dep-proof-thumb')) {
                const rowBtn = depositRow.querySelector('.view-details-btn');
                if (rowBtn) {
                    rowBtn.click();
                    return;
                }
            }

            // Handle view details button clicks (Event Delegation)
            const viewBtn = e.target.closest('.view-details-btn');
            if (viewBtn) {
                e.preventDefault();
                const id = viewBtn.getAttribute('data-id');
                const name = viewBtn.getAttribute('data-member-name');
                const email = viewBtn.getAttribute('data-member-email');
                const phone = viewBtn.getAttribute('data-member-phone');
                const amount = viewBtn.getAttribute('data-amount');
                const method = viewBtn.getAttribute('data-method');
                const remark = viewBtn.getAttribute('data-remark');
                const status = viewBtn.getAttribute('data-status');
                const proof = viewBtn.getAttribute('data-proof-file');
                const requestedAt = viewBtn.getAttribute('data-requested-at');

                document.getElementById('modalMemberName').textContent = name;
                document.getElementById('modalMemberEmail').textContent = email;
                const phoneEl = document.getElementById('modalMemberPhone');
                if (phoneEl) {
                    phoneEl.textContent = phone ? ('📞 ' + phone) : '';
                }
                document.getElementById('modalAmount').textContent = '₹' + amount;

                // Method badge
                let methodHtml = '';
                if (method === 'online') {
                    methodHtml = `<span class="dep-method-badge dep-badge-online">Online Transfer</span>`;
                } else {
                    methodHtml = `<span class="dep-proof-na"><i class="fa-solid fa-money-bill-transfer"></i> N/A (Cash)</span>`;
                }
                document.getElementById('modalMethod').innerHTML = methodHtml;

                // Status badge
                let statusHtml = '';
                if (status === 'approved') {
                    statusHtml = `<span class="dep-status dep-status-approved">Approved</span>`;
                } else if (status === 'rejected') {
                    statusHtml = `<span class="dep-status dep-status-rejected">Rejected</span>`;
                } else {
                    statusHtml = `<span class="dep-status dep-status-pending">Pending</span>`;
                }
                document.getElementById('modalStatus').innerHTML = statusHtml;

                document.getElementById('modalRequestedAt').textContent = requestedAt;
                document.getElementById('modalRemark').textContent = remark;

                // Proof handling
                const proofContainer = document.getElementById('modalProofContainer');
                const proofRow = document.getElementById('modalProofRow');
                if (proof) {
                    proofRow.style.display = 'flex';
                    const ext = proof.split('.').pop().toLowerCase();
                    if (ext === 'pdf') {
                        proofContainer.innerHTML = `
                        <a href="${proof}" target="_blank" class="dep-proof-pdf" style="padding:9px 16px;font-size:.85rem;">
                            <i class="fa-solid fa-file-pdf"></i> View PDF Receipt
                        </a>
                    `;
                    } else {
                        proofContainer.innerHTML = `
                        <div class="dep-proof-thumb" style="width:100px;height:100px;" onclick="viewReceipt('${proof}')">
                            <img src="${proof}" alt="Proof Receipt">
                            <div class="dep-thumb-overlay"><i class="fa-solid fa-magnifying-glass-plus" style="font-size:1.1rem;"></i></div>
                        </div>
                    `;
                    }
                } else {
                    proofContainer.innerHTML = `<span class="dep-proof-na"><i class="fa-solid fa-money-bill-transfer"></i> N/A (Cash)</span>`;
                }

                // Footer Actions (Approve / Reject buttons if pending)
                const footerActions = document.getElementById('modalFooterActions');
                let footerHtml = `<button type="button" class="dep-modal-btn dep-modal-btn-close" data-bs-dismiss="modal">Close</button>`;
                if (status === 'pending') {
                    footerHtml = `
                    <button type="button" class="dep-modal-btn dep-modal-btn-close" data-bs-dismiss="modal">Close</button>
                    <form action="<?php echo base_url('admin/deposits/reject/'); ?>${id}" method="POST" class="reject-form d-inline-block m-0">
                        <button type="button" class="dep-modal-btn dep-modal-btn-reject action-btn" data-action="reject">
                            <i class="fa-solid fa-xmark me-1"></i> Reject
                        </button>
                    </form>
                    <form action="<?php echo base_url('admin/deposits/approve/'); ?>${id}" method="POST" class="approve-form d-inline-block m-0">
                        <button type="button" class="dep-modal-btn dep-modal-btn-approve action-btn" data-action="approve">
                            <i class="fa-solid fa-check me-1"></i> Approve
                        </button>
                    </form>
                `;
                }
                footerActions.innerHTML = footerHtml;
            }

            // Handle approve/reject form submissions (Event Delegation)
            const actionBtn = e.target.closest('.action-btn');
            if (actionBtn) {
                e.preventDefault();
                const form = actionBtn.closest('form');
                const action = actionBtn.getAttribute('data-action');

                let titleStr = "Approve Deposit Request?";
                let textStr = "This will credit the requested amount to the member wallet and cannot be undone.";
                let confirmBtnColor = "#198754";

                if (action === 'reject') {
                    titleStr = "Reject Deposit Request?";
                    textStr = "This request will be marked as rejected. No money will be loaded.";
                    confirmBtnColor = "#dc3545";
                }

                // If we are currently showing details modal, hide it before sweetalert
                const modalEl = document.getElementById('depositDetailsModal');
                const bootstrapModal = bootstrap.Modal.getInstance(modalEl);

                dsConfirm({
                    title: titleStr,
                    text: textStr,
                    icon: (action === 'reject') ? 'warning' : 'question',
                    confirmText: (action === 'reject') ? 'Yes, Reject' : 'Yes, Approve',
                    isDangerous: (action === 'reject'),
                    onConfirm: function() {
                        form.submit();
                    },
                    onCancel: function() {
                        if (bootstrapModal) {
                            bootstrapModal.show();
                        }
                    }
                });
            }
        });
    });
</script>