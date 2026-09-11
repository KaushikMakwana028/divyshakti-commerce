<?php if ((int)$user->role === 1): ?>
    <!-- ADMIN DASHBOARD LAYOUT -->
    
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        /* ====================================================
           Divy Shakti Admin Dashboard - Luxury Design System
           ==================================================== */

        /* Modern Clean Luxury Welcome Card */
        .dash-welcome-card {
            background: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 18px;
            padding: 1.35rem 1.75rem;
            box-shadow: 0 4px 20px rgba(17, 24, 39, 0.04);
            position: relative;
            overflow: hidden;
            margin-bottom: 1.75rem;
        }
        .dash-welcome-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ec407a 0%, #d4af37 50%, #10b981 100%);
        }
        .welcome-avatar {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: linear-gradient(135deg, #ec407a 0%, #d4af37 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.35rem;
            font-weight: 700;
            box-shadow: 0 4px 14px rgba(236, 64, 122, 0.25);
            flex-shrink: 0;
        }
        .welcome-title {
            font-family: inherit;
            font-size: 1.38rem;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.3px;
        }
        .welcome-status-pill {
            background: rgba(16, 185, 129, 0.12);
            color: #059669;
            border: 1px solid rgba(16, 185, 129, 0.25);
            font-size: 0.72rem;
            font-weight: 600;
            padding: 4px 9px;
            border-radius: 50px;
        }
        .welcome-sub {
            font-size: 0.86rem;
            color: #64748b;
            margin-top: 2px;
        }
        .welcome-actions-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: nowrap;
        }
        .btn-welcome {
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 0.82rem;
            font-weight: 600;
            letter-spacing: 0.2px;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
        }
        .btn-welcome-primary {
            background: linear-gradient(135deg, #ec407a 0%, #d4af37 100%);
            color: #ffffff;
            border: none;
            box-shadow: 0 3px 10px rgba(236, 64, 122, 0.22);
        }
        .btn-welcome-primary:hover {
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.35);
        }
        .btn-welcome-outline {
            background: #ffffff;
            color: #334155;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }
        .btn-welcome-outline:hover {
            background: #f8fafc;
            color: #0f172a;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }

        /* Stat KPI Cards */
        .dash-kpi-card {
            background: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 18px;
            padding: 1.5rem 1.65rem;
            box-shadow: 0 4px 20px rgba(17, 24, 39, 0.04);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }
        .dash-kpi-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(17, 24, 39, 0.1);
            border-color: rgba(212, 175, 55, 0.35);
        }
        .dash-kpi-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }
        .kpi-pink::before { background: linear-gradient(90deg, #ec407a, #d4af37); }
        .kpi-gold::before { background: linear-gradient(90deg, #d4af37, #c5a059); }
        .kpi-teal::before { background: linear-gradient(90deg, #0d9488, #14b8a6); }
        .kpi-blue::before { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
        .kpi-green::before { background: linear-gradient(90deg, #10b981, #34d399); }
        .kpi-orange::before { background: linear-gradient(90deg, #f59e0b, #fbbf24); }

        .dash-kpi-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.75rem;
        }
        .dash-kpi-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.75px;
            color: #64748b;
            margin: 0;
        }
        .dash-kpi-val {
            font-family: 'Poppins', sans-serif;
            font-size: 1.9rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
            margin: 0.25rem 0 0.5rem 0;
            letter-spacing: -0.5px;
            word-break: break-word;
        }
        .dash-kpi-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }
        .dash-kpi-card:hover .dash-kpi-icon {
            transform: scale(1.1) rotate(5deg);
        }
        .dash-kpi-footer {
            border-top: 1px solid #f1f5f9;
            padding-top: 0.75rem;
            margin-top: 0.5rem;
            font-size: 0.82rem;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Panels (Charts & Tables) */
        .dash-panel {
            background: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 18px;
            box-shadow: 0 4px 20px rgba(17, 24, 39, 0.04);
            margin-bottom: 1.75rem;
            overflow: hidden;
        }
        .dash-panel-header {
            padding: 1.15rem 1.5rem;
            background: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .dash-panel-header.dark-header {
            background: linear-gradient(135deg, #111827 0%, #1f2a3f 100%);
            border-bottom: 2px solid #d4af37;
            color: #ffffff;
        }
        .dash-panel-title {
            font-weight: 700;
            font-size: 1rem;
            margin: 0;
            display: flex;
            align-items: center;
        }
        .dash-panel-body {
            padding: 1.5rem;
            position: relative;
        }

        /* Donut Chart Inner Center Badge */
        .donut-center-badge {
            position: absolute;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            pointer-events: none;
        }
        .donut-center-num {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 2rem;
            color: #0f172a;
            line-height: 1;
        }
        .donut-center-lbl {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            font-weight: 600;
            display: block;
            margin-top: 2px;
        }

        /* Product Leaderboard List */
        .prod-lead-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 14px;
            border-radius: 12px;
            transition: background-color 0.2s ease;
            margin-bottom: 8px;
            background-color: #f8fafc;
            border: 1px solid #f1f5f9;
        }
        .prod-lead-item:hover {
            background-color: #f1f5f9;
        }
        .prod-rank {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.8rem;
            margin-right: 12px;
            flex-shrink: 0;
        }
        .prod-rank-1 { background: rgba(212, 175, 55, 0.2); color: #b45309; }
        .prod-rank-2 { background: rgba(148, 163, 184, 0.2); color: #475569; }
        .prod-rank-3 { background: rgba(180, 83, 9, 0.15); color: #78350f; }

        /* Tables Styling */
        .dash-table th {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            font-weight: 600;
            color: #64748b;
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 16px;
        }
        .dash-table td {
            padding: 14px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.88rem;
        }
        .dash-table tr:hover td {
            background-color: #f8fafc;
        }
        .user-initial-avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            color: #ffffff;
            margin-right: 12px;
            flex-shrink: 0;
        }

        /* ====================================================
           Responsive Media Queries for Mobile & Tablet Devices
           ==================================================== */
        @media (max-width: 991.98px) {
            .dash-welcome-card {
                padding: 1.25rem 1.2rem;
                margin-bottom: 1.25rem;
            }
            .welcome-actions-wrapper {
                margin-top: 0.5rem;
                flex-wrap: wrap;
            }
            .welcome-actions-wrapper .btn-welcome {
                flex-grow: 1;
            }
        }

        @media (max-width: 767.98px) {
            .dash-welcome-card {
                padding: 1.15rem 1rem;
                border-radius: 16px;
                margin-bottom: 1.25rem;
            }
            .welcome-avatar {
                width: 42px;
                height: 42px;
                font-size: 1.15rem;
                border-radius: 12px;
            }
            .welcome-title {
                font-size: 1.22rem;
                line-height: 1.25;
            }
            .welcome-sub {
                font-size: 0.8rem;
                line-height: 1.35;
            }
            .welcome-status-pill {
                font-size: 0.68rem !important;
                padding: 3px 8px !important;
            }
            .welcome-actions-wrapper {
                display: grid !important;
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 8px !important;
                width: 100% !important;
                margin-top: 0.85rem !important;
            }
            .btn-welcome {
                width: 100%;
                padding: 9px 6px;
                font-size: 0.8rem;
                justify-content: center;
            }
            .dash-kpi-card {
                padding: 1.15rem 1.2rem;
                border-radius: 14px;
            }
            .dash-kpi-val {
                font-size: 1.45rem;
            }
            .dash-panel {
                border-radius: 14px;
            }
        }
    </style>

    <!-- 1. Modern Welcome & Quick Actions Card -->
    <div class="dash-welcome-card">
        <div class="row align-items-center g-3">
            <div class="col-lg-7 col-xl-7">
                <div class="d-flex align-items-center">
                    <div class="welcome-avatar me-3">
                        <?php echo strtoupper(substr($user->name ?? 'A', 0, 1)); ?>
                    </div>
                    <div class="min-w-0">
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                            <h2 class="welcome-title mb-0">Welcome back, <?php echo htmlspecialchars($user->name ?? 'Admin'); ?>! 👋</h2>
                            <span class="badge welcome-status-pill">
                                <i class="fa-solid fa-circle text-success me-1" style="font-size: 0.45rem;"></i> Live
                            </span>
                            <span class="badge bg-light text-secondary border px-2.5 py-1" style="font-size: 0.72rem; border-radius: 50px;">
                                <i class="fa-regular fa-calendar-days me-1 text-muted"></i> <?php echo date('D, d M Y'); ?>
                            </span>
                        </div>
                        <p class="welcome-sub mb-0">Enterprise business control center & MLM affiliate analytics overview.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-xl-5 text-lg-end">
                <div class="welcome-actions-wrapper justify-content-lg-end">
                    <a href="<?php echo base_url('admin/products/add'); ?>" class="btn-welcome btn-welcome-primary">
                        <i class="fa-solid fa-plus me-1.5"></i>  Add Product
                    </a>
                    <a href="<?php echo base_url('admin/orders'); ?>" class="btn-welcome btn-welcome-outline">
                        <i class="fa-solid fa-receipt me-1.5 text-primary"></i>  Orders
                    </a>
                    <a href="<?php echo base_url('admin/deposit'); ?>" class="btn-welcome btn-welcome-outline position-relative">
                        <i class="fa-solid fa-wallet me-1.5 text-warning"></i>  Deposits
                        <?php if ($pending_deposits_count > 0): ?>
                            <span class="badge rounded-pill bg-danger ms-1" style="font-size: 0.65rem; padding: 2px 6px;">
                                <?php echo $pending_deposits_count; ?>
                            </span>
                        <?php endif; ?>
                    </a>
                    <a href="<?php echo base_url('admin/members'); ?>" class="btn-welcome btn-welcome-outline">
                        <i class="fa-solid fa-users me-1.5 text-info"></i>  Members
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Primary KPI Metric Cards (2 Balanced Rows of 3 Cards) -->
    <div class="row g-4 mb-4">
        <!-- Card 1: Gross E-Commerce Sales -->
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="dash-kpi-card kpi-pink">
                <div>
                    <div class="dash-kpi-top">
                        <p class="dash-kpi-label">Gross Sales Revenue</p>
                        <div class="dash-kpi-icon" style="background-color: rgba(236, 64, 122, 0.12); color: #ec407a;">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                    </div>
                    <div class="dash-kpi-val">₹<?php echo number_format($total_sales, 2); ?></div>
                </div>
                <div class="dash-kpi-footer">
                    <span><i class="fa-solid fa-circle-check text-success me-1"></i> Confirmed order volume</span>
                    <span class="badge bg-success-subtle text-success fw-semibold" style="font-size: 0.72rem;">Verified</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Admin Revenue Share -->
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="dash-kpi-card kpi-gold">
                <div>
                    <div class="dash-kpi-top">
                        <p class="dash-kpi-label">Admin Revenue Cut</p>
                        <div class="dash-kpi-icon" style="background-color: rgba(212, 175, 55, 0.15); color: #d4af37;">
                            <i class="fa-solid fa-sack-dollar"></i>
                        </div>
                    </div>
                    <div class="dash-kpi-val" style="color: #b45309;">₹<?php echo number_format($total_admin_comm, 2); ?></div>
                </div>
                <div class="dash-kpi-footer">
                    <span><i class="fa-solid fa-percent text-warning me-1"></i> Platform retained profit</span>
                    <span class="badge bg-warning-subtle text-warning fw-semibold" style="font-size: 0.72rem;">Earnings</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Network Commissions Paid -->
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="dash-kpi-card kpi-teal">
                <div>
                    <div class="dash-kpi-top">
                        <p class="dash-kpi-label">Affiliate Commissions</p>
                        <div class="dash-kpi-icon" style="background-color: rgba(13, 148, 136, 0.12); color: #0d9488;">
                            <i class="fa-solid fa-hand-holding-dollar"></i>
                        </div>
                    </div>
                    <div class="dash-kpi-val" style="color: #0d9488;">₹<?php echo number_format($total_ref_comm, 2); ?></div>
                </div>
                <div class="dash-kpi-footer">
                    <span><i class="fa-solid fa-sitemap text-info me-1"></i> 12-level network payouts</span>
                    <span class="badge bg-info-subtle text-info fw-semibold" style="font-size: 0.72rem;">Distributed</span>
                </div>
            </div>
        </div>

        <!-- Card 4: Total Orders & Activity -->
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="dash-kpi-card kpi-blue">
                <div>
                    <div class="dash-kpi-top">
                        <p class="dash-kpi-label">Total Orders Placed</p>
                        <div class="dash-kpi-icon" style="background-color: rgba(59, 130, 246, 0.12); color: #3b82f6;">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </div>
                    </div>
                    <div class="dash-kpi-val"><?php echo $total_orders; ?> <span class="fs-6 text-muted fw-normal">Orders</span></div>
                </div>
                <div class="dash-kpi-footer">
                    <div>
                        <span class="badge bg-success-subtle text-success me-1 px-2 py-1"><?php echo $completed_orders; ?> Completed</span>
                        <span class="badge bg-warning-subtle text-warning px-2 py-1"><?php echo $pending_orders; ?> Pending</span>
                    </div>
                    <a href="<?php echo base_url('admin/orders'); ?>" class="text-primary text-decoration-none extra-small fw-semibold">View All &rarr;</a>
                </div>
            </div>
        </div>

        <!-- Card 5: Registered Members & Catalog -->
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="dash-kpi-card kpi-green">
                <div>
                    <div class="dash-kpi-top">
                        <p class="dash-kpi-label">Network Members</p>
                        <div class="dash-kpi-icon" style="background-color: rgba(16, 185, 129, 0.12); color: #10b981;">
                            <i class="fa-solid fa-users"></i>
                        </div>
                    </div>
                    <div class="dash-kpi-val"><?php echo $total_members; ?> <span class="fs-6 text-muted fw-normal">Active Users</span></div>
                </div>
                <div class="dash-kpi-footer">
                    <span><i class="fa-solid fa-box text-success me-1"></i> <?php echo $total_products; ?> Products in catalog</span>
                    <a href="<?php echo base_url('admin/members'); ?>" class="text-success text-decoration-none extra-small fw-semibold">Directory &rarr;</a>
                </div>
            </div>
        </div>

        <!-- Card 6: Wallet Deposit Requests -->
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="dash-kpi-card kpi-orange">
                <div>
                    <div class="dash-kpi-top">
                        <p class="dash-kpi-label">Pending Deposits</p>
                        <div class="dash-kpi-icon" style="background-color: rgba(245, 158, 11, 0.12); color: #f59e0b;">
                            <i class="fa-solid fa-money-bill-transfer"></i>
                        </div>
                    </div>
                    <div class="dash-kpi-val" style="color: #d97706;">₹<?php echo number_format($pending_deposits_amount, 2); ?></div>
                </div>
                <div class="dash-kpi-footer">
                    <?php if ($pending_deposits_count > 0): ?>
                        <span class="badge bg-danger text-white px-2 py-1"><i class="fa-solid fa-bell me-1"></i> <?php echo $pending_deposits_count; ?> require action</span>
                    <?php else: ?>
                        <span class="text-success"><i class="fa-solid fa-circle-check text-success me-1"></i> All requests approved</span>
                    <?php endif; ?>
                    <a href="<?php echo base_url('admin/deposit'); ?>" class="text-warning text-decoration-none extra-small fw-semibold">Review &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Analytics & Distribution Section -->
    <div class="row g-4 mb-4">
        <!-- Sales & Revenue Growth Trend (8 Cols) -->
        <div class="col-lg-8">
            <div class="dash-panel h-100">
                <div class="dash-panel-header">
                    <h5 class="dash-panel-title">
                        <i class="fa-solid fa-chart-area me-2 text-primary"></i> Sales & Revenue Performance
                    </h5>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-light text-dark border px-2.5 py-1.5" style="font-size: 0.72rem; border-radius: 6px;">
                            6 Month Trend
                        </span>
                        <span class="badge" style="background: rgba(236, 64, 122, 0.12); color: #ec407a; font-size: 0.72rem; padding: 6px 10px; border-radius: 6px;">
                            ₹<?php echo number_format($total_sales, 2); ?> Total
                        </span>
                    </div>
                </div>
                <div class="dash-panel-body">
                    <div style="height: 320px; position: relative;">
                        <canvas id="salesTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Status Distribution Donut (4 Cols) -->
        <div class="col-lg-4">
            <div class="dash-panel h-100">
                <div class="dash-panel-header">
                    <h5 class="dash-panel-title">
                        <i class="fa-solid fa-chart-pie me-2 text-warning"></i> Order Statuses
                    </h5>
                    <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 0.72rem; border-radius: 6px;">
                        Distribution
                    </span>
                </div>
                <div class="dash-panel-body">
                    <div class="position-relative d-flex align-items-center justify-content-center" style="height: 230px;">
                        <canvas id="orderStatusChart"></canvas>
                        <div class="donut-center-badge">
                            <div class="donut-center-num"><?php echo $total_orders; ?></div>
                            <span class="donut-center-lbl">Orders</span>
                        </div>
                    </div>

                    <!-- Clean Structured Status Pills (All 6 Lifecycle Stages) -->
                    <div class="mt-3 pt-2 border-top">
                        <div class="row g-2 text-center" style="font-size: 0.78rem;">
                            <div class="col-4">
                                <div class="p-1.5 rounded" style="background: #f8fafc; border: 1px solid #f1f5f9;">
                                    <span class="d-block text-muted extra-small">Placed</span>
                                    <strong class="text-warning"><?php echo $placed_orders ?? $pending_orders ?? 0; ?></strong>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-1.5 rounded" style="background: #f8fafc; border: 1px solid #f1f5f9;">
                                    <span class="d-block text-muted extra-small">Confirmed</span>
                                    <strong class="text-primary"><?php echo $confirmed_orders ?? 0; ?></strong>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-1.5 rounded" style="background: #f8fafc; border: 1px solid #f1f5f9;">
                                    <span class="d-block text-muted extra-small">Packed</span>
                                    <strong style="color: #6366f1;"><?php echo $packed_orders ?? 0; ?></strong>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-1.5 rounded" style="background: #f8fafc; border: 1px solid #f1f5f9;">
                                    <span class="d-block text-muted extra-small">Out for Del.</span>
                                    <strong style="color: #06b6d4;"><?php echo $out_for_delivery_orders ?? 0; ?></strong>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-1.5 rounded" style="background: #f8fafc; border: 1px solid #f1f5f9;">
                                    <span class="d-block text-muted extra-small">Delivered</span>
                                    <strong class="text-success"><?php echo $delivered_orders ?? 0; ?></strong>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-1.5 rounded" style="background: #f8fafc; border: 1px solid #f1f5f9;">
                                    <span class="d-block text-muted extra-small">Cancelled</span>
                                    <strong class="text-danger"><?php echo $cancelled_orders ?? 0; ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Top Performing Products & Monthly Registrations Row -->
    <div class="row g-4 mb-4">
        <!-- Top Products Leaderboard (7 Cols) -->
        <div class="col-lg-7">
            <div class="dash-panel h-100">
                <div class="dash-panel-header">
                    <h5 class="dash-panel-title">
                        <i class="fa-solid fa-fire me-2 text-danger"></i> Top Performing Products
                    </h5>
                    <a href="<?php echo base_url('admin/products'); ?>" class="btn btn-sm btn-outline-secondary py-1 px-2.5" style="font-size: 0.78rem; border-radius: 6px;">
                        Manage Products
                    </a>
                </div>
                <div class="dash-panel-body">
                    <?php if (empty($top_products_chart)): ?>
                        <div class="text-center py-5 text-muted">
                            <i class="fa-solid fa-box-open fs-1 mb-2 opacity-50"></i>
                            <p class="mb-0">No product sales recorded yet.</p>
                        </div>
                    <?php else: ?>
                        <?php 
                        $rank = 1;
                        foreach ($top_products_chart as $prod): 
                            $pct = ($total_sales > 0) ? round(($prod['amount'] / $total_sales) * 100, 1) : 0;
                            $rankClass = ($rank === 1) ? 'prod-rank-1' : (($rank === 2) ? 'prod-rank-2' : 'prod-rank-3');
                        ?>
                            <div class="prod-lead-item">
                                <div class="d-flex align-items-center flex-grow-1 me-3">
                                    <div class="prod-rank <?php echo $rankClass; ?>">#<?php echo $rank++; ?></div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="fw-semibold text-dark" style="font-size: 0.92rem;"><?php echo htmlspecialchars($prod['product_name']); ?></span>
                                            <span class="fw-bold text-dark" style="font-size: 0.92rem;">₹<?php echo number_format($prod['amount'], 2); ?></span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="progress flex-grow-1 me-3" style="height: 6px; background-color: #e2e8f0; border-radius: 10px;">
                                                <div class="progress-bar" role="progressbar" style="width: <?php echo max($pct, 8); ?>%; background: linear-gradient(90deg, #ec407a, #d4af37); border-radius: 10px;"></div>
                                            </div>
                                            <span class="badge bg-white text-muted border px-2 py-0.5" style="font-size: 0.72rem; flex-shrink: 0;">
                                                <?php echo $prod['qty']; ?> units sold (<?php echo $pct; ?>%)
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Monthly Member Signups Chart (5 Cols) -->
        <div class="col-lg-5">
            <div class="dash-panel h-100">
                <div class="dash-panel-header">
                    <h5 class="dash-panel-title">
                        <i class="fa-solid fa-user-plus me-2 text-success"></i> Monthly Registrations
                    </h5>
                    <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: #10b981; font-size: 0.72rem; padding: 6px 10px; border-radius: 6px;">
                        <?php echo $total_members; ?> Total Members
                    </span>
                </div>
                <div class="dash-panel-body">
                    <div style="height: 260px; position: relative;">
                        <canvas id="registrationsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. Recent Activity Tables Section -->
    <div class="row g-4">
        <!-- Recent Orders (7 Cols) -->
        <div class="col-lg-7 mb-4">
            <div class="dash-panel h-100">
                <div class="dash-panel-header dark-header">
                    <h5 class="dash-panel-title text-white">
                        <i class="fa-solid fa-receipt me-2" style="color: #d4af37;"></i> Recent Orders
                    </h5>
                    <a href="<?php echo base_url('admin/orders'); ?>" class="btn btn-sm btn-outline-light py-1 px-2.5" style="font-size: 0.78rem; border-radius: 6px;">
                        View All
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table dash-table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Buyer</th>
                                <th>Product</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th class="pe-3 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recent_orders)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-inbox fs-2 mb-2 d-block opacity-50"></i>
                                        No recent orders found.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recent_orders as $ord): ?>
                                    <tr>
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center">
                                                <div class="user-initial-avatar" style="background: linear-gradient(135deg, #1e293b, #334155);">
                                                    <?php echo strtoupper(substr($ord->buyer_name ?? 'C', 0, 1)); ?>
                                                </div>
                                                <div>
                                                    <span class="fw-semibold text-dark d-block"><?php echo htmlspecialchars($ord->buyer_name ?? 'Customer'); ?></span>
                                                    <span class="text-muted extra-small" style="font-size: 0.72rem;">Order #<?php echo $ord->id; ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-dark fw-medium text-truncate d-block" style="max-width: 140px;" title="<?php echo htmlspecialchars($ord->product_name ?? 'Product'); ?>">
                                                <?php echo htmlspecialchars($ord->product_name ?? 'Product'); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <strong class="text-dark">₹<?php echo number_format($ord->amount, 2); ?></strong>
                                        </td>
                                        <td>
                                            <?php if ($ord->status === 'confirmed'): ?>
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1" style="font-size: 0.72rem; border-radius: 6px;">
                                                    <i class="fa-solid fa-check me-1"></i> Confirmed
                                                </span>
                                            <?php elseif ($ord->status === 'packed'): ?>
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1" style="font-size: 0.72rem; border-radius: 6px;">
                                                    <i class="fa-solid fa-box me-1"></i> Packed
                                                </span>
                                            <?php elseif ($ord->status === 'out_for_delivery'): ?>
                                                <span class="badge bg-info-subtle text-info border border-info-subtle px-2.5 py-1" style="font-size: 0.72rem; border-radius: 6px;">
                                                    <i class="fa-solid fa-truck me-1"></i> Out for Delivery
                                                </span>
                                            <?php elseif ($ord->status === 'delivered' || $ord->status === 'completed'): ?>
                                                <span class="badge text-white px-2.5 py-1" style="background-color: #10b981; font-size: 0.72rem; border-radius: 6px;">
                                                    <i class="fa-solid fa-circle-check me-1"></i> <?php echo ($ord->status === 'completed') ? 'Completed' : 'Delivered'; ?>
                                                </span>
                                            <?php elseif ($ord->status === 'placed' || $ord->status === 'pending'): ?>
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1" style="font-size: 0.72rem; border-radius: 6px;">
                                                    <i class="fa-solid fa-bell me-1"></i> Placed
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1" style="font-size: 0.72rem; border-radius: 6px;">
                                                    Cancelled
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="pe-3 text-end">
                                            <a href="<?php echo base_url('admin/orders/detail/' . $ord->id); ?>" class="btn btn-sm btn-outline-dark py-1 px-2.5" style="font-size: 0.75rem; border-radius: 6px;">
                                                Details
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Registered Members (5 Cols) -->
        <div class="col-lg-5 mb-4">
            <div class="dash-panel h-100">
                <div class="dash-panel-header dark-header">
                    <h5 class="dash-panel-title text-white">
                        <i class="fa-solid fa-users me-2" style="color: #d4af37;"></i> New Members
                    </h5>
                    <a href="<?php echo base_url('admin/members'); ?>" class="btn btn-sm btn-outline-light py-1 px-2.5" style="font-size: 0.78rem; border-radius: 6px;">
                        View All
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table dash-table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Member</th>
                                <th>Wallet</th>
                                <th>Status</th>
                                <th class="pe-3 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recent_members)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-users-slash fs-2 mb-2 d-block opacity-50"></i>
                                        No registered members yet.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php 
                                $avatarColors = [
                                    'linear-gradient(135deg, #ec407a, #d4af37)',
                                    'linear-gradient(135deg, #3b82f6, #06b6d4)',
                                    'linear-gradient(135deg, #10b981, #059669)',
                                    'linear-gradient(135deg, #8b5cf6, #ec4899)'
                                ];
                                $c_idx = 0;
                                foreach ($recent_members as $mem): 
                                    $bg = $avatarColors[$c_idx % count($avatarColors)];
                                    $c_idx++;
                                ?>
                                    <tr>
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center">
                                                <div class="user-initial-avatar" style="background: <?php echo $bg; ?>;">
                                                    <?php echo strtoupper(substr($mem->name ?? 'M', 0, 1)); ?>
                                                </div>
                                                <div>
                                                    <div class="d-flex align-items-center gap-1">
                                                        <span class="fw-semibold text-dark"><?php echo htmlspecialchars($mem->name ?? 'Member'); ?></span>
                                                        <?php if (!empty($mem->custom_id)): ?>
                                                            <span class="badge bg-dark-subtle text-dark border font-monospace" style="font-size: 0.65rem; padding: 1px 5px;">#<?php echo htmlspecialchars($mem->custom_id); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <?php if (!empty($mem->email)): ?>
                                                        <span class="text-muted extra-small d-block text-truncate" style="max-width: 140px; font-size: 0.72rem;" title="<?php echo htmlspecialchars($mem->email); ?>"><?php echo htmlspecialchars($mem->email); ?></span>
                                                    <?php elseif (!empty($mem->phone)): ?>
                                                        <span class="text-muted extra-small d-block text-truncate" style="max-width: 140px; font-size: 0.72rem;"><i class="fa-solid fa-phone me-1 opacity-50" style="font-size: 0.65rem;"></i><?php echo htmlspecialchars($mem->phone); ?></span>
                                                    <?php else: ?>
                                                        <span class="badge bg-light text-muted border px-1.5 py-0.5" style="font-size: 0.65rem; font-weight: 500;"><i class="fa-regular fa-envelope-open me-1 opacity-50"></i>Not provided</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success">₹<?php echo number_format($mem->wallet_balance, 2); ?></span>
                                        </td>
                                        <td>
                                            <?php if ((int)$mem->status === 1): ?>
                                                <span class="badge bg-success-subtle text-success px-2 py-0.5" style="font-size: 0.7rem; border-radius: 4px;">Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger-subtle text-danger px-2 py-0.5" style="font-size: 0.7rem; border-radius: 4px;">Blocked</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="pe-3 text-end">
                                            <a href="<?php echo base_url('admin/members/view/' . $mem->id); ?>" class="btn btn-sm btn-outline-dark py-1 px-2.5" style="font-size: 0.75rem; border-radius: 6px;">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Generation & Charts Binding -->
    <?php
    // Prepare Sales & Registrations trends for Chart.js
    $months_range = [];
    for ($i = 5; $i >= 0; $i--) {
        $m_key = date('Y-m', strtotime("-$i months"));
        $months_range[$m_key] = [
            'label' => date('M Y', strtotime("-$i months")),
            'sales' => 0.0,
            'regs' => 0
        ];
    }

    if (!empty($sales_chart)) {
        foreach ($sales_chart as $s) {
            if (isset($months_range[$s['month_val']])) {
                $months_range[$s['month_val']]['sales'] = (float)$s['amount'];
            }
        }
    }
    if (!empty($reg_chart)) {
        foreach ($reg_chart as $r) {
            if (isset($months_range[$r['month_val']])) {
                $months_range[$r['month_val']]['regs'] = (int)$r['count'];
            }
        }
    }

    $c_labels = [];
    $c_sales = [];
    $c_regs = [];
    foreach ($months_range as $val) {
        $c_labels[] = $val['label'];
        $c_sales[] = $val['sales'];
        $c_regs[] = $val['regs'];
    }

    // Prepare status count details
    $status_labels = ['Placed', 'Confirmed', 'Packed', 'Out for Delivery', 'Delivered', 'Cancelled'];
    $status_counts = [
        (int)($placed_orders ?? $pending_orders ?? 0),
        (int)($confirmed_orders ?? 0),
        (int)($packed_orders ?? 0),
        (int)($out_for_delivery_orders ?? 0),
        (int)($delivered_orders ?? 0),
        (int)($cancelled_orders ?? 0)
    ];
    ?>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // 1. Sales Trend Chart
        const salesCanvas = document.getElementById('salesTrendChart');
        if (salesCanvas) {
            const salesCtx = salesCanvas.getContext('2d');
            
            // Create luxury gradient background
            const gradientSales = salesCtx.createLinearGradient(0, 0, 0, 300);
            gradientSales.addColorStop(0, 'rgba(236, 64, 122, 0.28)');
            gradientSales.addColorStop(1, 'rgba(212, 175, 55, 0.01)');

            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($c_labels); ?>,
                    datasets: [{
                        label: 'Sales Revenue (₹)',
                        data: <?php echo json_encode($c_sales); ?>,
                        borderColor: '#ec407a',
                        backgroundColor: gradientSales,
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 5,
                        pointBackgroundColor: '#d4af37',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleColor: '#fef08a',
                            bodyColor: '#ffffff',
                            padding: 12,
                            cornerRadius: 10,
                            callbacks: {
                                label: function(context) {
                                    return ' Revenue: ₹' + Number(context.raw).toLocaleString('en-IN', {minimumFractionDigits: 2});
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: {
                                color: '#64748b',
                                callback: function(value) {
                                    return '₹' + Number(value).toLocaleString('en-IN');
                                }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#64748b' }
                        }
                    }
                }
            });
        }

        // 2. Order Status Doughnut Chart
        const statusCanvas = document.getElementById('orderStatusChart');
        if (statusCanvas) {
            const statusCtx = statusCanvas.getContext('2d');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: <?php echo json_encode($status_labels); ?>,
                    datasets: [{
                        data: <?php echo json_encode($status_counts); ?>,
                        backgroundColor: [
                            '#f59e0b', // Pending
                            '#3b82f6', // Confirmed
                            '#6366f1', // Packed
                            '#06b6d4', // Out for Delivery
                            '#10b981', // Completed
                            '#ef4444'  // Cancelled
                        ],
                        borderWidth: 3,
                        borderColor: '#ffffff',
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleColor: '#fef08a',
                            padding: 10,
                            cornerRadius: 8
                        }
                    },
                    cutout: '72%'
                }
            });
        }

        // 3. User Registrations Bar Chart
        const regCanvas = document.getElementById('registrationsChart');
        if (regCanvas) {
            const regCtx = regCanvas.getContext('2d');
            new Chart(regCtx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($c_labels); ?>,
                    datasets: [{
                        label: 'New Registrations',
                        data: <?php echo json_encode($c_regs); ?>,
                        backgroundColor: '#10b981',
                        borderRadius: 8,
                        maxBarThickness: 32
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleColor: '#fef08a',
                            padding: 10,
                            cornerRadius: 8
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: { color: '#64748b', stepSize: 1 }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#64748b' }
                        }
                    }
                }
            });
        }
    });
    </script>

<?php else: ?>
    <!-- MEMBER DASHBOARD LAYOUT -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .member-metric-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            background: #ffffff;
        }
        .member-metric-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        .member-metric-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
        }
        .mm-gold::after { background: var(--primary-gold); }
        .mm-pink::after { background: var(--primary-pink); }
        .mm-green::after { background: #198754; }
        .mm-blue::after { background: #0d6efd; }
        .mm-purple::after { background: #6f42c1; }
        .mm-orange::after { background: #fd7e14; }

        .member-icon {
            width: 46px;
            height: 46px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-size: 1.15rem;
            transition: all 0.3s ease;
        }
        .member-metric-card:hover .member-icon {
            transform: scale(1.1);
        }

        .chart-box {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            background: #ffffff;
            margin-bottom: 24px;
            overflow: hidden;
        }
    </style>

    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h3 class="fw-bold m-0" style="color: var(--dark-sidebar);">Member Console</h3>
            <p class="text-muted m-0">Welcome back to your settings center, <?php echo htmlspecialchars($user->name ?? 'User'); ?>!</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <span class="badge bg-white text-dark border border-soft px-3 py-2 rounded-pill">
                <i class="fa-solid fa-wallet text-warning me-1"></i> Balance: <strong>₹<?php echo number_format($user->wallet_balance ?? 0, 2); ?></strong>
            </span>
        </div>
    </div>

    <!-- Member Statistics Grid -->
    <div class="row mb-4">
        <!-- 1. Wallet Balance -->
        <div class="col-sm-6 col-lg-4 mb-3">
            <div class="card h-100 member-metric-card mm-gold">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Wallet Balance</p>
                            <h3 class="fw-bold m-0" style="color: var(--dark-sidebar);">₹<?php echo number_format($user->wallet_balance ?? 0, 2); ?></h3>
                        </div>
                        <div class="member-icon" style="background-color: rgba(212, 175, 55, 0.15); color: var(--primary-gold);">
                            <i class="fa-solid fa-wallet"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="text-success fw-bold me-2"><i class="fa-solid fa-circle-check"></i> Active</span>
                        <span class="text-muted extra-small" style="font-size: 0.75rem;">Ready for transactions</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Referral Code -->
        <div class="col-sm-6 col-lg-4 mb-3">
            <div class="card h-100 member-metric-card mm-pink">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Referral Sponsor Code</p>
                            <h3 class="fw-bold m-0 text-uppercase" style="color: var(--primary-pink); letter-spacing: 1px;"><?php echo htmlspecialchars($user->referral_code ?? ''); ?></h3>
                        </div>
                        <div class="member-icon" style="background-color: rgba(236, 64, 122, 0.1); color: var(--primary-pink);">
                            <i class="fa-solid fa-gift"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="text-muted extra-small" style="font-size: 0.75rem;">Invite members to earn</span>
                        <button class="btn btn-sm btn-outline-secondary py-0.5 px-2 text-nowrap" style="font-size: 0.75rem; border-radius: 6px;" onclick="copyReferralLink('<?php echo base_url('admin/register?ref=' . $user->referral_code); ?>')">
                            <i class="fa-regular fa-copy me-1"></i> Copy Link
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Direct Referrals Count -->
        <div class="col-sm-6 col-lg-4 mb-3">
            <div class="card h-100 member-metric-card mm-green">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Direct Referrals</p>
                            <h3 class="fw-bold m-0 text-success"><?php echo $direct_referrals_count; ?> Users</h3>
                        </div>
                        <div class="member-icon" style="background-color: rgba(25, 135, 84, 0.1); color: #198754;">
                            <i class="fa-solid fa-people-group"></i>
                        </div>
                    </div>
                    <span class="text-muted extra-small" style="font-size: 0.75rem;"><i class="fa-solid fa-sitemap"></i> Network registration scale</span>
                </div>
            </div>
        </div>

        <!-- 4. Total Purchases Spent -->
        <div class="col-sm-6 col-lg-4 mb-3">
            <div class="card h-100 member-metric-card mm-blue">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total E-Shop Spent</p>
                            <h3 class="fw-bold m-0" style="color: var(--dark-sidebar);">₹<?php echo number_format($my_total_spent, 2); ?></h3>
                        </div>
                        <div class="member-icon" style="background-color: rgba(13, 110, 253, 0.1); color: #0d6efd;">
                            <i class="fa-solid fa-bag-shopping"></i>
                        </div>
                    </div>
                    <div class="small">
                        <span class="text-success fw-semibold"><?php echo $my_completed_orders; ?> Completed</span> | 
                        <span class="text-warning fw-semibold"><?php echo $my_pending_orders; ?> Pending</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. Total Referral Earnings -->
        <div class="col-sm-6 col-lg-4 mb-3">
            <div class="card h-100 member-metric-card mm-purple">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Referral Earned</p>
                            <h3 class="fw-bold m-0" style="color: #6f42c1;">₹<?php echo number_format($my_total_referral_earnings, 2); ?></h3>
                        </div>
                        <div class="member-icon" style="background-color: rgba(111, 66, 193, 0.1); color: #6f42c1;">
                            <i class="fa-solid fa-coins"></i>
                        </div>
                    </div>
                    <span class="text-muted extra-small" style="font-size: 0.75rem;"><i class="fa-solid fa-arrow-trend-up"></i> Accumulated passive profit</span>
                </div>
            </div>
        </div>

        <!-- 6. My Pending Deposits -->
        <div class="col-sm-6 col-lg-4 mb-3">
            <div class="card h-100 member-metric-card mm-orange">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Pending Deposits</p>
                            <h3 class="fw-bold m-0" style="color: #fd7e14;">₹<?php echo number_format($my_pending_deposits_amount, 2); ?></h3>
                        </div>
                        <div class="member-icon" style="background-color: rgba(253, 126, 20, 0.1); color: #fd7e14;">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                    </div>
                    <span class="text-muted extra-small" style="font-size: 0.75rem;"><i class="fa-solid fa-spinner fa-spin"></i> <?php echo $my_pending_deposits_count; ?> Deposit requests pending</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Analytics Rows -->
    <div class="row">
        <!-- Spent vs Earned Doughnut -->
        <div class="col-lg-5 mb-4">
            <div class="card chart-box h-100">
                <div class="p-4 bg-white border-bottom border-soft d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold m-0 text-dark"><i class="fa-solid fa-chart-pie me-2 text-pink"></i>Financial Overview</h5>
                    <span class="text-muted small">Debits vs Credits</span>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center" style="height: 310px;">
                    <canvas id="memberOverviewChart" style="max-height: 250px; max-width: 250px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Monthly Wallet Trend (Credits vs Debits) Bar Chart -->
        <div class="col-lg-7 mb-4">
            <div class="card chart-box h-100">
                <div class="p-4 bg-white border-bottom border-soft d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold m-0 text-dark"><i class="fa-solid fa-chart-column me-2 text-warning"></i>Monthly Account Activity</h5>
                    <span class="text-muted small">Last 6 Months</span>
                </div>
                <div class="card-body" style="height: 310px;">
                    <canvas id="memberActivityChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Personal profile, purchases and recent transaction tables -->
    <div class="row">
        <!-- My Recent Orders -->
        <div class="col-lg-6 mb-4">
            <div class="card table-card h-100">
                <div class="p-4 text-white d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, var(--dark-sidebar) 0%, #1f2937 100%); border-bottom: 2px solid var(--primary-gold);">
                    <h5 class="fw-bold mb-0"><i class="fa-solid fa-cart-shopping me-2" style="color: var(--primary-gold);"></i>My Recent Orders</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">#</th>
                                <th>Product</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th class="pe-4">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($my_recent_orders)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">You haven't placed any orders yet.</td>
                                </tr>
                            <?php else: ?>
                                <?php 
                                $ord_idx = 1;
                                foreach ($my_recent_orders as $ord): 
                                ?>
                                    <tr>
                                        <td class="ps-4 fw-bold"><?php echo $ord_idx++; ?></td>
                                        <td class="fw-semibold text-dark"><?php echo htmlspecialchars($ord->product_name); ?></td>
                                        <td class="fw-semibold">₹<?php echo number_format($ord->amount, 2); ?></td>
                                        <td>
                                            <?php if ($ord->status === 'confirmed'): ?>
                                                <span class="badge bg-success-subtle text-success px-2 py-0.5 border border-success-subtle">Confirmed</span>
                                            <?php elseif ($ord->status === 'packed'): ?>
                                                <span class="badge bg-primary-subtle text-primary px-2 py-0.5 border border-primary-subtle">Packed</span>
                                            <?php elseif ($ord->status === 'out_for_delivery'): ?>
                                                <span class="badge bg-info-subtle text-info px-2 py-0.5 border border-info-subtle">Out for Delivery</span>
                                            <?php elseif ($ord->status === 'delivered' || $ord->status === 'completed'): ?>
                                                <span class="badge bg-success text-white px-2 py-0.5"><?php echo ($ord->status === 'completed') ? 'Completed' : 'Delivered'; ?></span>
                                            <?php elseif ($ord->status === 'placed' || $ord->status === 'pending'): ?>
                                                <span class="badge bg-warning-subtle text-warning px-2 py-0.5 border border-warning-subtle">Placed</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger-subtle text-danger px-2 py-0.5 border border-danger-subtle">Cancelled</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="pe-4 text-muted small"><?php echo date('M d, Y', strtotime($ord->created_at)); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- My Recent Wallet Transactions -->
        <div class="col-lg-6 mb-4">
            <div class="card table-card h-100">
                <div class="p-4 text-white d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, var(--dark-sidebar) 0%, #1f2937 100%); border-bottom: 2px solid var(--primary-gold);">
                    <h5 class="fw-bold mb-0"><i class="fa-solid fa-money-bill-transfer me-2" style="color: var(--primary-gold);"></i>My Wallet Activity Logs</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">#</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Source</th>
                                <th class="pe-4">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($my_recent_txns)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No transactions logged yet.</td>
                                </tr>
                            <?php else: ?>
                                <?php 
                                $txn_idx = 1;
                                foreach ($my_recent_txns as $t): 
                                ?>
                                    <tr>
                                        <td class="ps-4 fw-bold"><?php echo $txn_idx++; ?></td>
                                        <td>
                                            <?php if ($t->type === 'credit'): ?>
                                                <span class="badge bg-success-subtle text-success px-2 py-0.5">Credit</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger-subtle text-danger px-2 py-0.5">Debit</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="fw-bold <?php echo ($t->type === 'credit') ? 'text-success' : 'text-danger'; ?>">
                                            <?php echo ($t->type === 'credit') ? '+' : '-'; ?>₹<?php echo number_format($t->amount, 2); ?>
                                        </td>
                                        <td><code><?php echo htmlspecialchars($t->source); ?></code></td>
                                        <td class="pe-4 text-muted small"><?php echo date('M d, Y H:i', strtotime($t->created_at)); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Copy Success Toast / Dialog -->
    <script>
    function copyReferralLink(link) {
        navigator.clipboard.writeText(link).then(function() {
            dsToast({
                icon: 'success',
                title: 'Referral link copied to clipboard!'
            });
        }, function(err) {
            console.error('Could not copy text: ', err);
        });
    }
    </script>

    <!-- Prepare Member Chart.js Datasets -->
    <?php
    $my_months_range = [];
    for ($i = 5; $i >= 0; $i--) {
        $m_key = date('Y-m', strtotime("-$i months"));
        $my_months_range[$m_key] = [
            'label' => date('M Y', strtotime("-$i months")),
            'credit' => 0.0,
            'debit' => 0.0
        ];
    }

    if (!empty($my_monthly_activity_chart)) {
        foreach ($my_monthly_activity_chart as $a) {
            if (isset($my_months_range[$a['month_val']])) {
                $my_months_range[$a['month_val']]['credit'] = (float)$a['credit'];
                $my_months_range[$a['month_val']]['debit'] = (float)$a['debit'];
            }
        }
    }

    $m_labels = [];
    $m_credits = [];
    $m_debits = [];
    foreach ($my_months_range as $val) {
        $m_labels[] = $val['label'];
        $m_credits[] = $val['credit'];
        $m_debits[] = $val['debit'];
    }

    // Pie chart values
    $pie_vals = [
        $my_pie_chart['spent'],
        $my_pie_chart['referral_earnings'],
        $my_pie_chart['other_credits']
    ];
    ?>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Member Doughnut Chart (spent vs earned)
        const overviewCtx = document.getElementById('memberOverviewChart').getContext('2d');
        new Chart(overviewCtx, {
            type: 'doughnut',
            data: {
                labels: ['Total Spent (Debits)', 'Referral Earnings', 'Other Credits'],
                datasets: [{
                    data: <?php echo json_encode($pie_vals); ?>,
                    backgroundColor: ['#dc3545', '#198754', '#d4af37'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            font: { size: 10 },
                            color: '#4b5563'
                        }
                    }
                },
                cutout: '65%'
            }
        });

        // Member Activity Bar Chart
        const activityCtx = document.getElementById('memberActivityChart').getContext('2d');
        new Chart(activityCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($m_labels); ?>,
                datasets: [
                    {
                        label: 'Total Earned/Credited (₹)',
                        data: <?php echo json_encode($m_credits); ?>,
                        backgroundColor: '#198754',
                        borderRadius: 6
                    },
                    {
                        label: 'Total Spent/Debited (₹)',
                        data: <?php echo json_encode($m_debits); ?>,
                        backgroundColor: '#dc3545',
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { boxWidth: 12, font: { size: 10 } }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f3f4f6' },
                        ticks: { color: '#9ca3af' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#9ca3af' }
                    }
                }
            }
        });
    });
    </script>
<?php endif; ?>
