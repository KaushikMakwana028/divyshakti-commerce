<style>
/* =======================================================
   Order List Page - Luxury Modern Dashboard Styles
   ======================================================= */
:root {
    --gold-primary: #d4af37;
    --gold-hover: #b8972e;
    --dark-navy: #111827;
    --dark-card: #1f2937;
}

/* KPI Summary Cards */
.order-kpi-card {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    padding: 1.25rem 1.4rem;
    box-shadow: 0 2px 6px rgba(17, 24, 39, 0.04);
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.order-kpi-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 24px -6px rgba(17, 24, 39, 0.08);
    border-color: #d1d5db;
}

.order-kpi-card .kpi-icon-wrap {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    flex-shrink: 0;
}

.kpi-icon-orders {
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.12) 0%, rgba(99, 102, 241, 0.2) 100%);
    color: #4f46e5;
}
.kpi-icon-revenue {
    background: linear-gradient(135deg, rgba(212, 175, 55, 0.15) 0%, rgba(245, 158, 11, 0.22) 100%);
    color: #b45309;
}
.kpi-icon-delivered {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.12) 0%, rgba(5, 150, 105, 0.2) 100%);
    color: #059669;
}
.kpi-icon-pending {
    background: linear-gradient(135deg, rgba(249, 115, 22, 0.12) 0%, rgba(234, 88, 12, 0.2) 100%);
    color: #ea580c;
}

/* Filter Card */
.filter-card {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    box-shadow: 0 2px 6px rgba(17, 24, 39, 0.04);
}

/* Quick Status Pills Navigation */
.status-pills-bar {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f3f4f6;
    margin-bottom: 14px;
}

.quick-status-btn {
    border: 1px solid #e5e7eb;
    background: #f9fafb;
    color: #4b5563;
    font-size: 0.82rem;
    font-weight: 500;
    padding: 6px 14px;
    border-radius: 50px;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}

.quick-status-btn:hover {
    background: #f3f4f6;
    color: #111827;
    border-color: #d1d5db;
}

.quick-status-btn.active {
    background: #111827;
    color: #ffffff;
    border-color: #111827;
    box-shadow: 0 4px 10px rgba(17, 24, 39, 0.18);
}

.quick-status-btn .count-badge {
    font-size: 0.72rem;
    padding: 2px 7px;
    border-radius: 50px;
    font-weight: 600;
    background: rgba(0, 0, 0, 0.08);
    color: inherit;
}

.quick-status-btn.active .count-badge {
    background: rgba(255, 255, 255, 0.22);
    color: #ffffff;
}

/* Distinct Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 11px;
    border-radius: 50px;
    font-size: 0.76rem;
    font-weight: 600;
    letter-spacing: 0.2px;
    white-space: nowrap;
}

/* 1. Pending / Awaiting Payment -> Warm Amber / Gold */
.status-pending {
    background: #fffbeb !important;
    color: #b45309 !important;
    border: 1px solid #fcd34d !important;
}

/* 2. Placed -> Royal Blue */
.status-placed {
    background: #eff6ff !important;
    color: #1d4ed8 !important;
    border: 1px solid #bfdbfe !important;
}

/* 3. Confirmed -> Deep Indigo */
.status-confirmed {
    background: #eef2ff !important;
    color: #4338ca !important;
    border: 1px solid #c7d2fe !important;
}

/* 4. Packed -> Cyan / Turquoise */
.status-packed {
    background: #ecfeff !important;
    color: #0e7490 !important;
    border: 1px solid #a5f3fc !important;
}

/* 5. Out for Delivery -> Vibrant Orange */
.status-out_for_delivery {
    background: #fff7ed !important;
    color: #c2410c !important;
    border: 1px solid #fed7aa !important;
}

/* 6. Delivered -> Emerald Green */
.status-delivered {
    background: #f0fdf4 !important;
    color: #15803d !important;
    border: 1px solid #bbf7d0 !important;
}

/* 7. Completed -> Rich Teal */
.status-completed {
    background: #f0fdfa !important;
    color: #0f766e !important;
    border: 1px solid #99f6e4 !important;
}

/* 8. Cancelled -> Crimson Red */
.status-cancelled {
    background: #fef2f2 !important;
    color: #b91c1c !important;
    border: 1px solid #fecaca !important;
}

/* Pulsing Clock for Awaiting Payment */
@keyframes pulseAmber {
    0% { opacity: 0.6; transform: scale(0.95); }
    50% { opacity: 1; transform: scale(1.1); }
    100% { opacity: 0.6; transform: scale(0.95); }
}
.pulse-clock {
    animation: pulseAmber 1.8s infinite;
}

/* Customer Avatar */
.customer-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: #ffffff;
    font-weight: 700;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 2px 5px rgba(29, 78, 216, 0.25);
}

/* Order Tag */
.order-id-tag {
    font-family: 'SFMono-Regular', Menlo, Monaco, Consolas, monospace;
    font-weight: 700;
    color: #111827;
    background: #f3f4f6;
    border: 1px solid #e5e7eb;
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 0.82rem;
    display: inline-block;
}

/* Action Buttons */
.action-btn-group {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 6px;
}

.btn-action-view {
    width: 33px;
    height: 33px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #eff6ff;
    color: #2563eb;
    border: 1px solid #bfdbfe;
    border-radius: 8px;
    font-size: 0.85rem;
    transition: all 0.2s ease;
    text-decoration: none;
}
.btn-action-view:hover {
    background: #2563eb;
    color: #ffffff;
    border-color: #2563eb;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(37, 99, 235, 0.28);
}

.btn-action-delete {
    width: 33px;
    height: 33px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
    border-radius: 8px;
    font-size: 0.85rem;
    transition: all 0.2s ease;
    cursor: pointer;
}
.btn-action-delete:hover {
    background: #dc2626;
    color: #ffffff;
    border-color: #dc2626;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(220, 38, 38, 0.28);
}

/* Table Card */
.orders-table-card {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    box-shadow: 0 2px 6px rgba(17, 24, 39, 0.04);
    overflow: hidden;
}

.orders-table thead th {
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    font-weight: 600;
    padding-top: 14px;
    padding-bottom: 14px;
}

.orders-table tbody tr {
    transition: background-color 0.15s ease;
}

.orders-table tbody tr:hover {
    background-color: #f9fafb !important;
}

/* Highlight new/unfulfilled orders subtly */
.new-order-highlight {
    background-color: rgba(254, 243, 199, 0.3) !important;
}
</style>

<!-- Page Header -->
<div class="row mb-4 align-items-center">
    <div class="col-sm-8">
        <div class="d-flex align-items-center gap-3">
            <div>
                <h3 class="fw-bold mb-1" style="color: var(--dark-sidebar);">Order Management</h3>
                <p class="text-muted small mb-0">Track real-time fulfillment, customer payments, and referral commission distribution.</p>
            </div>
        </div>
    </div>
    <div class="col-sm-4 text-sm-end mt-3 mt-sm-0">
        <button type="button" id="refreshBtn" class="btn btn-outline-secondary btn-sm px-3 py-2 shadow-sm rounded-3">
            <i class="fa-solid fa-arrows-rotate me-1.5"></i> Refresh
        </button>
    </div>
</div>

<!-- 4 Executive KPI Metric Cards -->
<div class="row g-3 mb-4">
    <!-- 1. Total Orders -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="order-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">Total Orders</span>
                    <h3 class="fw-bold text-dark mt-1 mb-0"><?php echo number_format($kpi_total_orders ?? 0); ?></h3>
                    <span class="text-muted" style="font-size: 0.75rem;"><i class="fa-solid fa-chart-line text-primary me-1"></i>All system orders</span>
                </div>
                <div class="kpi-icon-wrap kpi-icon-orders">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Total Net Sales Revenue -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="order-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">Paid Order Value</span>
                    <h3 class="fw-bold text-dark mt-1 mb-0">₹<?php echo number_format($kpi_total_revenue ?? 0, 2); ?></h3>
                    <span class="text-muted" style="font-size: 0.75rem;"><i class="fa-solid fa-shield-check text-warning me-1"></i>Active &amp; completed</span>
                </div>
                <div class="kpi-icon-wrap kpi-icon-revenue">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Delivered & Fulfilled -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="order-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">Delivered / Done</span>
                    <h3 class="fw-bold text-dark mt-1 mb-0"><?php echo number_format($kpi_delivered_orders ?? 0); ?></h3>
                    <span class="text-muted" style="font-size: 0.75rem;"><i class="fa-solid fa-circle-check text-success me-1"></i>Fulfilled deliveries</span>
                </div>
                <div class="kpi-icon-wrap kpi-icon-delivered">
                    <i class="fa-solid fa-truck-ramp-box"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Pending / Action Needed -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="order-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">Pending Action</span>
                    <h3 class="fw-bold text-dark mt-1 mb-0"><?php echo number_format($kpi_pending_orders ?? 0); ?></h3>
                    <span class="text-muted" style="font-size: 0.75rem;"><i class="fa-solid fa-clock text-danger me-1"></i>Awaiting pay / fulfillment</span>
                </div>
                <div class="kpi-icon-wrap kpi-icon-pending">
                    <i class="fa-solid fa-bell"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filtering Toolbar -->
<div class="row mb-4">
    <div class="col-12">
        <div class="filter-card p-3">
            <!-- Quick Status Filter Pills (Distinct status colors) -->
            <div class="status-pills-bar">
                <button type="button" class="quick-status-btn <?php echo (empty($status)) ? 'active' : ''; ?>" data-status="">
                    <i class="fa-solid fa-list-check"></i> All Orders
                    <span class="count-badge"><?php echo $status_counts['all'] ?? 0; ?></span>
                </button>

                <button type="button" class="quick-status-btn <?php echo ($status === 'pending') ? 'active' : ''; ?>" data-status="pending" style="<?php echo ($status !== 'pending') ? 'border-color: #fcd34d; color: #b45309;' : ''; ?>">
                    <i class="fa-solid fa-clock"></i> Awaiting Payment
                    <span class="count-badge" style="background: rgba(245, 158, 11, 0.15); color: inherit;"><?php echo $status_counts['pending'] ?? 0; ?></span>
                </button>

                <button type="button" class="quick-status-btn <?php echo ($status === 'placed') ? 'active' : ''; ?>" data-status="placed" style="<?php echo ($status !== 'placed') ? 'border-color: #bfdbfe; color: #1d4ed8;' : ''; ?>">
                    <i class="fa-solid fa-receipt"></i> Placed
                    <span class="count-badge" style="background: rgba(37, 99, 235, 0.15); color: inherit;"><?php echo $status_counts['placed'] ?? 0; ?></span>
                </button>

                <button type="button" class="quick-status-btn <?php echo ($status === 'confirmed') ? 'active' : ''; ?>" data-status="confirmed" style="<?php echo ($status !== 'confirmed') ? 'border-color: #c7d2fe; color: #4338ca;' : ''; ?>">
                    <i class="fa-solid fa-circle-check"></i> Confirmed
                    <span class="count-badge" style="background: rgba(99, 102, 241, 0.15); color: inherit;"><?php echo $status_counts['confirmed'] ?? 0; ?></span>
                </button>

                <button type="button" class="quick-status-btn <?php echo ($status === 'packed') ? 'active' : ''; ?>" data-status="packed" style="<?php echo ($status !== 'packed') ? 'border-color: #a5f3fc; color: #0e7490;' : ''; ?>">
                    <i class="fa-solid fa-box-open"></i> Packed
                    <span class="count-badge" style="background: rgba(6, 182, 212, 0.15); color: inherit;"><?php echo $status_counts['packed'] ?? 0; ?></span>
                </button>

                <button type="button" class="quick-status-btn <?php echo ($status === 'out_for_delivery') ? 'active' : ''; ?>" data-status="out_for_delivery" style="<?php echo ($status !== 'out_for_delivery') ? 'border-color: #fed7aa; color: #c2410c;' : ''; ?>">
                    <i class="fa-solid fa-truck-fast"></i> Out for Delivery
                    <span class="count-badge" style="background: rgba(249, 115, 22, 0.15); color: inherit;"><?php echo $status_counts['out_for_delivery'] ?? 0; ?></span>
                </button>

                <button type="button" class="quick-status-btn <?php echo ($status === 'delivered') ? 'active' : ''; ?>" data-status="delivered" style="<?php echo ($status !== 'delivered') ? 'border-color: #bbf7d0; color: #15803d;' : ''; ?>">
                    <i class="fa-solid fa-circle-check"></i> Delivered
                    <span class="count-badge" style="background: rgba(34, 197, 94, 0.15); color: inherit;"><?php echo $status_counts['delivered'] ?? 0; ?></span>
                </button>

                <button type="button" class="quick-status-btn <?php echo ($status === 'cancelled') ? 'active' : ''; ?>" data-status="cancelled" style="<?php echo ($status !== 'cancelled') ? 'border-color: #fecaca; color: #b91c1c;' : ''; ?>">
                    <i class="fa-solid fa-circle-xmark"></i> Cancelled
                    <span class="count-badge" style="background: rgba(239, 68, 68, 0.15); color: inherit;"><?php echo $status_counts['cancelled'] ?? 0; ?></span>
                </button>
            </div>

            <!-- Inputs Row -->
            <form id="filterForm" class="row g-2 align-items-center" onsubmit="return false;">
                <div class="col-lg-6 col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" name="search" id="searchInput" class="form-control border-start-0 ps-1" placeholder="Search by Order ID (#), customer name, email, or product..." value="<?php echo htmlspecialchars($search ?? ''); ?>" autocomplete="off">
                        <?php if (!empty($search)): ?>
                            <button type="button" id="clearSearchBtn" class="btn btn-outline-secondary border-start-0 border-end-0 bg-white text-muted px-2" title="Clear search">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4">
                    <select name="status" id="statusFilter" class="form-select">
                        <option value="">All Statuses (<?php echo $status_counts['all'] ?? 0; ?>)</option>
                        <option value="pending" <?php echo ($status === 'pending') ? 'selected' : ''; ?>>🟡 Awaiting Payment (Pending)</option>
                        <option value="placed" <?php echo ($status === 'placed') ? 'selected' : ''; ?>>🔵 Placed</option>
                        <option value="confirmed" <?php echo ($status === 'confirmed') ? 'selected' : ''; ?>>🟣 Confirmed</option>
                        <option value="packed" <?php echo ($status === 'packed') ? 'selected' : ''; ?>>🌐 Packed</option>
                        <option value="out_for_delivery" <?php echo ($status === 'out_for_delivery') ? 'selected' : ''; ?>>🟠 Out for Delivery</option>
                        <option value="delivered" <?php echo ($status === 'delivered') ? 'selected' : ''; ?>>🟢 Delivered / Completed</option>
                        <option value="cancelled" <?php echo ($status === 'cancelled') ? 'selected' : ''; ?>>🔴 Cancelled</option>
                    </select>
                </div>

                <div class="col-lg-2 col-md-2">
                    <button type="button" id="resetBtn" class="btn btn-secondary w-100 fw-medium">
                        <i class="fa-solid fa-arrow-rotate-left me-1"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Order Table Container -->
<div class="row">
    <div class="col-12">
        <div class="orders-table-card">
            <!-- Table Header Bar -->
            <div class="d-flex align-items-center justify-content-between p-3 border-bottom bg-white">
                <div class="d-flex align-items-center gap-2">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="fa-solid fa-table-list text-warning me-1.5"></i> Orders Directory
                    </h6>
                    <span class="badge bg-light text-secondary border px-2.5 py-1" style="font-size: 0.75rem; font-weight: 600;">
                        <?php echo number_format($total_rows ?? count($orders)); ?> Records
                    </span>
                </div>
            </div>

            <!-- Dynamic Table Wrapper for AJAX Swapping -->
            <div id="table-container">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 orders-table">
                        <thead class="text-white" style="background-color: var(--dark-sidebar); border-bottom: 3px solid var(--primary-gold);">
                            <tr>
                                <th class="ps-4" style="width: 70px;">#</th>
                                <th style="width: 100px;">Order ID</th>
                                <th>Buyer Account</th>
                                <th>Product Details</th>
                                <th>Quantity</th>
                                <th>Total Amount</th>
                                <th style="width: 170px;">Status</th>
                                <th>Ordered Date</th>
                                <th class="text-end pe-4" style="width: 110px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($orders)): ?>
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <div class="py-4">
                                            <div class="mb-3">
                                                <i class="fa-solid fa-box-open text-muted" style="font-size: 3.2rem; opacity: 0.5;"></i>
                                            </div>
                                            <h6 class="fw-bold text-dark">No orders found</h6>
                                            <p class="text-muted small mb-3">No customer orders matched your active search or filter criteria.</p>
                                            <button type="button" class="btn btn-sm btn-outline-primary px-3 rounded-3" onclick="document.getElementById('resetBtn').click();">
                                                <i class="fa-solid fa-rotate-left me-1"></i> Clear Filters
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php 
                                $index_num = ($current_page - 1) * 10 + 1;
                                foreach ($orders as $order): 
                                    $is_new_placed = in_array($order->status, ['placed', 'pending']);
                                    $initials = strtoupper(substr(trim($order->buyer_name ?? 'C'), 0, 2));
                                ?>
                                    <tr id="order-row-<?php echo $order->id; ?>" class="<?php echo $is_new_placed ? 'new-order-highlight' : ''; ?>">
                                        <!-- Index / Sr. No. -->
                                        <td class="ps-4 fw-semibold text-muted"><?php echo $index_num++; ?></td>

                                        <!-- Order ID -->
                                        <td>
                                            <a href="<?php echo base_url('admin/orders/detail/' . $order->id); ?>" class="order-id-tag text-decoration-none" title="View details for Order #<?php echo $order->id; ?>">
                                                #<?php echo $order->id; ?>
                                            </a>
                                        </td>

                                        <!-- Buyer Account -->
                                        <td>
                                            <div class="d-flex align-items-center gap-2.5">
                                                <div class="customer-avatar">
                                                    <?php echo htmlspecialchars($initials); ?>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark" style="font-size: 0.9rem;">
                                                        <?php echo htmlspecialchars($order->buyer_name ?? 'Customer'); ?>
                                                    </div>
                                                    <?php if (!empty($order->buyer_email)): ?>
                                                        <div class="text-muted small" style="font-size: 0.78rem;">
                                                            <i class="fa-regular fa-envelope me-1 opacity-50"></i><?php echo htmlspecialchars($order->buyer_email); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if (!empty($order->buyer_phone)): ?>
                                                        <div class="text-muted small" style="font-size: 0.74rem;">
                                                            <i class="fa-solid fa-phone me-1 opacity-50"></i><?php echo htmlspecialchars($order->buyer_phone); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Product Details -->
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <?php if (!empty($order->product_image)): ?>
                                                    <img src="<?php echo base_url($order->product_image); ?>" 
                                                         alt="Product" 
                                                         class="rounded border" 
                                                         style="width: 38px; height: 38px; object-fit: cover; border-color: #e5e7eb !important;"
                                                         onerror="this.style.display='none';">
                                                <?php endif; ?>
                                                <div>
                                                    <span class="fw-semibold text-dark" style="font-size: 0.88rem;">
                                                        <?php echo htmlspecialchars($order->product_name ?? 'Product'); ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Quantity -->
                                        <td>
                                            <span class="badge bg-light text-dark border px-2.5 py-1" style="font-size: 0.78rem; font-weight: 600;">
                                                <i class="fa-solid fa-layer-group me-1 text-muted"></i><?php echo (int)($order->quantity ?? 1); ?> units
                                            </span>
                                        </td>

                                        <!-- Total Amount -->
                                        <td>
                                            <div class="fw-bold text-dark" style="font-size: 0.95rem;">
                                                ₹<?php echo number_format($order->amount, 2); ?>
                                            </div>
                                        </td>

                                        <!-- Distinct Status Badges -->
                                        <td>
                                            <?php if ($order->status === 'pending'): ?>
                                                <span class="status-badge status-pending" title="Buyer has not completed payment yet">
                                                    <i class="fa-solid fa-clock pulse-clock"></i> Awaiting Payment
                                                </span>
                                            <?php elseif ($order->status === 'placed'): ?>
                                                <span class="status-badge status-placed" title="Order received and ready for review">
                                                    <i class="fa-solid fa-receipt"></i> Placed
                                                </span>
                                            <?php elseif ($order->status === 'confirmed'): ?>
                                                <span class="status-badge status-confirmed" title="Order has been approved and confirmed">
                                                    <i class="fa-solid fa-circle-check"></i> Confirmed
                                                </span>
                                            <?php elseif ($order->status === 'packed'): ?>
                                                <span class="status-badge status-packed" title="Items packed and ready for dispatch">
                                                    <i class="fa-solid fa-box-open"></i> Packed
                                                </span>
                                            <?php elseif ($order->status === 'out_for_delivery'): ?>
                                                <span class="status-badge status-out_for_delivery" title="Handed to courier for delivery">
                                                    <i class="fa-solid fa-truck-fast"></i> Out for Delivery
                                                </span>
                                            <?php elseif ($order->status === 'delivered'): ?>
                                                <span class="status-badge status-delivered" title="Delivered successfully to customer">
                                                    <i class="fa-solid fa-circle-check"></i> Delivered
                                                </span>
                                            <?php elseif ($order->status === 'completed'): ?>
                                                <span class="status-badge status-completed" title="Order finalized and fully completed">
                                                    <i class="fa-solid fa-award"></i> Completed
                                                </span>
                                            <?php else: ?>
                                                <span class="status-badge status-cancelled" title="Order cancelled">
                                                    <i class="fa-solid fa-circle-xmark"></i> Cancelled
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Ordered Date -->
                                        <td>
                                            <div class="text-dark small fw-medium">
                                                <i class="fa-regular fa-calendar me-1 text-muted"></i><?php echo date('M d, Y', strtotime($order->created_at)); ?>
                                            </div>
                                            <div class="text-muted" style="font-size: 0.74rem;">
                                                <i class="fa-regular fa-clock me-1 opacity-50"></i><?php echo date('h:i A', strtotime($order->created_at)); ?>
                                            </div>
                                        </td>

                                        <!-- Actions: View & Delete -->
                                        <td class="text-end pe-4">
                                            <div class="action-btn-group">
                                                <!-- View Order Details -->
                                                <a href="<?php echo base_url('admin/orders/detail/' . $order->id); ?>" 
                                                   class="btn-action-view" 
                                                   title="View Order Details">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>

                                                <!-- Delete Order Button -->
                                                <button type="button" 
                                                        class="btn-action-delete btn-delete-order" 
                                                        data-order-id="<?php echo $order->id; ?>" 
                                                        data-order-name="Order #<?php echo $order->id; ?>" 
                                                        title="Delete Order #<?php echo $order->id; ?>">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Modern Pagination Controls -->
                <?php if (!empty($total_rows) && $total_rows > 0): 
                    $start_record = ($current_page - 1) * 10 + 1;
                    $end_record = min($current_page * 10, $total_rows);
                ?>
                    <div class="card-footer bg-white border-top p-3 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                        <div class="text-muted small">
                            Showing <strong><?php echo $start_record; ?></strong> to <strong><?php echo $end_record; ?></strong> of <strong><?php echo number_format($total_rows); ?></strong> available orders
                        </div>
                        <?php if (!empty($total_pages) && $total_pages > 1): ?>
                        <nav aria-label="Order Page Navigation">
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                                    <a class="page-link px-3" href="#" data-page="<?php echo $current_page - 1; ?>">
                                        <i class="fa-solid fa-chevron-left me-1"></i> Prev
                                    </a>
                                </li>
                                <?php 
                                $start_page = max(1, $current_page - 2);
                                $end_page = min($total_pages, $current_page + 2);
                                for ($i = $start_page; $i <= $end_page; $i++): 
                                ?>
                                    <li class="page-item <?php echo ($current_page == $i) ? 'active' : ''; ?>">
                                        <a class="page-link" href="#" data-page="<?php echo $i; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>
                                <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                                    <a class="page-link px-3" href="#" data-page="<?php echo $current_page + 1; ?>">
                                        Next <i class="fa-solid fa-chevron-right ms-1"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Interactive Order Management JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const resetBtn = document.getElementById('resetBtn');
    const refreshBtn = document.getElementById('refreshBtn');
    const quickStatusBtns = document.querySelectorAll('.quick-status-btn');
    let debounceTimer;

    // Load table data via AJAX
    function loadTable(page = 1) {
        const search = searchInput ? searchInput.value : '';
        const status = statusFilter ? statusFilter.value : '';
        const url = new URL(window.location.href);

        url.searchParams.set('search', search);
        url.searchParams.set('status', status);
        url.searchParams.set('page', page);

        window.history.pushState({}, '', url.toString());

        // Visual loading indicator
        const container = document.getElementById('table-container');
        if (container) {
            container.style.opacity = '0.5';
            container.style.pointerEvents = 'none';
        }

        fetch(url.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newTable = doc.getElementById('table-container');
            if (newTable && container) {
                container.innerHTML = newTable.innerHTML;
            }
            if (container) {
                container.style.opacity = '1';
                container.style.pointerEvents = 'auto';
            }
            bindDeleteHandlers();
        })
        .catch(err => {
            console.error('Error fetching table data: ', err);
            if (container) {
                container.style.opacity = '1';
                container.style.pointerEvents = 'auto';
            }
        });
    }

    // Sync quick status button active states with status select
    function syncQuickPills(selectedStatus) {
        quickStatusBtns.forEach(btn => {
            if (btn.getAttribute('data-status') === selectedStatus) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
    }

    // Quick Status Pills Click Handler
    quickStatusBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const chosenStatus = this.getAttribute('data-status');
            if (statusFilter) {
                statusFilter.value = chosenStatus;
            }
            syncQuickPills(chosenStatus);
            loadTable(1);
        });
    });

    // Search Input Debounce
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                loadTable(1);
            }, 300);
        });
    }

    // Clear search button if present
    const clearSearchBtn = document.getElementById('clearSearchBtn');
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            loadTable(1);
        });
    }

    // Status Dropdown Change
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            syncQuickPills(this.value);
            loadTable(1);
        });
    }

    // Reset Button
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            if (statusFilter) statusFilter.value = '';
            syncQuickPills('');
            loadTable(1);
        });
    }

    // Refresh Button
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            const spinIcon = this.querySelector('i');
            if (spinIcon) spinIcon.classList.add('fa-spin');
            loadTable(1);
            setTimeout(() => {
                if (spinIcon) spinIcon.classList.remove('fa-spin');
            }, 600);
        });
    }

    // Pagination Click Handler
    document.addEventListener('click', function(e) {
        const pageLink = e.target.closest('#table-container .pagination .page-link');
        if (pageLink) {
            e.preventDefault();
            const page = pageLink.getAttribute('data-page');
            if (page) {
                loadTable(page);
            }
        }
    });

    // Delete Order Handler using dsConfirm & dsToast
    function bindDeleteHandlers() {
        const deleteBtns = document.querySelectorAll('.btn-delete-order');
        deleteBtns.forEach(btn => {
            // Remove existing listener to prevent duplicate triggers
            const newBtn = btn.cloneNode(true);
            btn.parentNode.replaceChild(newBtn, btn);

            newBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const orderId = this.getAttribute('data-order-id');
                const orderName = this.getAttribute('data-order-name') || ('Order #' + orderId);

                dsConfirm({
                    title: 'Delete ' + orderName + '?',
                    text: 'Are you sure you want to permanently delete ' + orderName + '? Associated commissions will be removed and product inventory restored. This action cannot be undone!',
                    icon: 'warning',
                    confirmText: 'Yes, Delete Order',
                    cancelText: 'Cancel',
                    isDangerous: true,
                    onConfirm: function() {
                        const row = document.getElementById('order-row-' + orderId);
                        if (row) {
                            row.style.transition = 'all 0.35s ease';
                            row.style.opacity = '0.4';
                        }

                        fetch('<?php echo base_url("admin/orders/delete/"); ?>' + orderId, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data && data.status) {
                                dsToast({
                                    icon: 'success',
                                    title: data.message || (orderName + ' has been deleted successfully.')
                                });
                                if (row) {
                                    row.style.transform = 'scale(0.9)';
                                    row.style.opacity = '0';
                                    setTimeout(() => {
                                        loadTable(1);
                                    }, 350);
                                } else {
                                    loadTable(1);
                                }
                            } else {
                                if (row) row.style.opacity = '1';
                                dsAlert({
                                    icon: 'error',
                                    title: 'Delete Failed',
                                    text: (data && data.message) ? data.message : 'Could not delete this order.'
                                });
                            }
                        })
                        .catch(err => {
                            if (row) row.style.opacity = '1';
                            dsAlert({
                                icon: 'error',
                                title: 'Network Error',
                                text: 'Failed to communicate with server to delete the order.'
                            });
                        });
                    }
                });
            });
        });
    }

    // Initial binding
    bindDeleteHandlers();
});
</script>
