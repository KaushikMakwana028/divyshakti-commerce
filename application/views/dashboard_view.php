<?php if ((int)$user->role === 1): ?>
    <!-- ADMIN DASHBOARD LAYOUT -->
    
    <!-- CSS and Chart.js Include -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        .metric-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            background: #ffffff;
        }
        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }
        .metric-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
        }
        .mc-pink::after { background: var(--primary-pink); }
        .mc-gold::after { background: var(--primary-gold); }
        .mc-teal::after { background: #00bcd4; }
        .mc-blue::after { background: #0d6efd; }
        .mc-green::after { background: #198754; }
        .mc-orange::after { background: #fd7e14; }
        
        .card-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-size: 1.25rem;
            transition: all 0.3s ease;
        }
        .metric-card:hover .card-icon {
            transform: scale(1.1) rotate(5deg);
        }
        
        .chart-container-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            background: #ffffff;
            margin-bottom: 24px;
        }
        .chart-header {
            padding: 20px 24px;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .chart-body {
            padding: 24px;
            position: relative;
        }
        
        .table-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            background: #ffffff;
            overflow: hidden;
        }
    </style>

    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h3 class="fw-bold m-0" style="color: var(--dark-sidebar);">Admin Control Center</h3>
            <p class="text-muted m-0">Welcome back, <?php echo htmlspecialchars($user->name); ?>! Here is your business analytics overview.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <span class="badge bg-dark-subtle text-dark px-3 py-2 border border-secondary-subtle" style="border-radius: 50px;">
                <i class="fa-solid fa-calendar-day me-1"></i> <?php echo date('D, d M Y'); ?>
            </span>
        </div>
    </div>

    <!-- Admin Statistics Cards Grid -->
    <div class="row mb-4">
        <!-- Card 1: E-Commerce Sales -->
        <div class="col-sm-6 col-lg-4 col-xxl-2 mb-3">
            <div class="card h-100 metric-card mc-pink">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">E-Commerce Sales</p>
                            <h3 class="fw-bold m-0" style="color: var(--dark-sidebar);">₹<?php echo number_format($total_sales, 2); ?></h3>
                        </div>
                        <div class="card-icon" style="background-color: rgba(236, 64, 122, 0.1); color: var(--primary-pink);">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                    </div>
                    <span class="text-muted small"><i class="fa-solid fa-check-double text-success"></i> All confirmed sales</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Admin Commission -->
        <div class="col-sm-6 col-lg-4 col-xxl-2 mb-3">
            <div class="card h-100 metric-card mc-gold">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">Admin Share</p>
                            <h3 class="fw-bold m-0" style="color: var(--primary-gold);">₹<?php echo number_format($total_admin_comm, 2); ?></h3>
                        </div>
                        <div class="card-icon" style="background-color: rgba(212, 175, 55, 0.15); color: var(--primary-gold);">
                            <i class="fa-solid fa-sack-dollar"></i>
                        </div>
                    </div>
                    <span class="text-muted small"><i class="fa-solid fa-percent text-warning"></i> Earnings cut</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Referral Commissions Paid -->
        <div class="col-sm-6 col-lg-4 col-xxl-2 mb-3">
            <div class="card h-100 metric-card mc-teal">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">Commissions Paid</p>
                            <h3 class="fw-bold m-0" style="color: #00bcd4;">₹<?php echo number_format($total_ref_comm, 2); ?></h3>
                        </div>
                        <div class="card-icon" style="background-color: rgba(0, 188, 212, 0.1); color: #00bcd4;">
                            <i class="fa-solid fa-hand-holding-dollar"></i>
                        </div>
                    </div>
                    <span class="text-muted small"><i class="fa-solid fa-users-gear text-info"></i> Network payouts</span>
                </div>
            </div>
        </div>

        <!-- Card 4: Total Orders -->
        <div class="col-sm-6 col-lg-4 col-xxl-2 mb-3">
            <div class="card h-100 metric-card mc-blue">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">Total Orders</p>
                            <h3 class="fw-bold m-0" style="color: var(--dark-sidebar);"><?php echo $total_orders; ?></h3>
                        </div>
                        <div class="card-icon" style="background-color: rgba(13, 110, 253, 0.1); color: #0d6efd;">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </div>
                    </div>
                    <div class="small">
                        <span class="text-success fw-semibold"><?php echo $completed_orders; ?> Completed</span> | 
                        <span class="text-warning fw-semibold"><?php echo $pending_orders; ?> Pending</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 5: Total Members -->
        <div class="col-sm-6 col-lg-4 col-xxl-2 mb-3">
            <div class="card h-100 metric-card mc-green">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">Registered Members</p>
                            <h3 class="fw-bold m-0" style="color: var(--dark-sidebar);"><?php echo $total_members; ?></h3>
                        </div>
                        <div class="card-icon" style="background-color: rgba(25, 135, 84, 0.1); color: #198754;">
                            <i class="fa-solid fa-users"></i>
                        </div>
                    </div>
                    <span class="text-muted small"><i class="fa-solid fa-boxes-stacked text-success"></i> <?php echo $total_products; ?> Active products</span>
                </div>
            </div>
        </div>

        <!-- Card 6: Pending Deposits -->
        <div class="col-sm-6 col-lg-4 col-xxl-2 mb-3">
            <div class="card h-100 metric-card mc-orange">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">Pending Deposits</p>
                            <h3 class="fw-bold m-0" style="color: #fd7e14;">₹<?php echo number_format($pending_deposits_amount, 2); ?></h3>
                        </div>
                        <div class="card-icon" style="background-color: rgba(253, 126, 20, 0.1); color: #fd7e14;">
                            <i class="fa-solid fa-money-bill-transfer"></i>
                        </div>
                    </div>
                    <span class="text-danger small fw-semibold"><i class="fa-solid fa-circle-exclamation"></i> <?php echo $pending_deposits_count; ?> pending requests</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Analytics Section -->
    <div class="row">
        <!-- Sales & Registration Trend -->
        <div class="col-lg-8 mb-4">
            <div class="card chart-container-card">
                <div class="chart-header">
                    <h5 class="fw-bold m-0 text-dark"><i class="fa-solid fa-chart-area me-2 text-primary"></i>Sales & Member Signup Performance</h5>
                    <span class="text-muted small">6 Month Trend</span>
                </div>
                <div class="chart-body" style="height: 330px;">
                    <canvas id="salesTrendChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Order Status Doughnut -->
        <div class="col-lg-4 mb-4">
            <div class="card chart-container-card">
                <div class="chart-header">
                    <h5 class="fw-bold m-0 text-dark"><i class="fa-solid fa-chart-pie me-2 text-warning"></i>Order Statuses</h5>
                    <span class="text-muted small">Distribution</span>
                </div>
                <div class="chart-body" style="height: 330px; display: flex; align-items: center; justify-content: center;">
                    <canvas id="orderStatusChart" style="max-height: 280px; max-width: 280px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Top Selling Products Bar Chart -->
        <div class="col-lg-6 mb-4">
            <div class="card chart-container-card">
                <div class="chart-header">
                    <h5 class="fw-bold m-0 text-dark"><i class="fa-solid fa-fire me-2 text-danger"></i>Top Selling Products</h5>
                    <span class="text-muted small">By Total Sales Value</span>
                </div>
                <div class="chart-body" style="height: 330px;">
                    <canvas id="topProductsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Monthly Signups (Detailed line graph) -->
        <div class="col-lg-6 mb-4">
            <div class="card chart-container-card">
                <div class="chart-header">
                    <h5 class="fw-bold m-0 text-dark"><i class="fa-solid fa-user-plus me-2 text-success"></i>Monthly Registrations</h5>
                    <span class="text-muted small">Grow Rate</span>
                </div>
                <div class="chart-body" style="height: 330px;">
                    <canvas id="registrationsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Tables Rows -->
    <div class="row">
        <!-- Recent Orders (Left) -->
        <div class="col-lg-7 mb-4">
            <div class="card table-card h-100">
                <div class="p-4 text-white d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, var(--dark-sidebar) 0%, #1f2937 100%); border-bottom: 2px solid var(--primary-gold);">
                    <h5 class="fw-bold mb-0"><i class="fa-solid fa-receipt me-2" style="color: var(--primary-gold);"></i>Recent Orders</h5>
                    <a href="<?php echo base_url('admin/orders'); ?>" class="btn btn-sm btn-outline-light" style="font-size: 0.8rem; border-radius: 6px;">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">#</th>
                                <th>Buyer</th>
                                <th>Product</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th class="pe-4 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recent_orders)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No orders placed yet.</td>
                                </tr>
                            <?php else: ?>
                                <?php 
                                $ord_idx = 1;
                                foreach ($recent_orders as $ord): 
                                ?>
                                    <tr>
                                        <td class="ps-4 fw-bold"><?php echo $ord_idx++; ?></td>
                                        <td class="fw-semibold text-dark"><?php echo htmlspecialchars($ord->buyer_name); ?></td>
                                        <td class="text-truncate" style="max-width: 140px;"><?php echo htmlspecialchars($ord->product_name); ?></td>
                                        <td class="fw-semibold">₹<?php echo number_format($ord->amount, 2); ?></td>
                                        <td>
                                            <?php if ($ord->status === 'confirmed'): ?>
                                                <span class="badge bg-success-subtle text-success px-2 py-1 border border-success-subtle" style="font-size: 0.7rem;">Confirmed</span>
                                            <?php elseif ($ord->status === 'packed'): ?>
                                                <span class="badge bg-primary-subtle text-primary px-2 py-1 border border-primary-subtle" style="font-size: 0.7rem;">Packed</span>
                                            <?php elseif ($ord->status === 'out_for_delivery'): ?>
                                                <span class="badge bg-info-subtle text-info px-2 py-1 border border-info-subtle" style="font-size: 0.7rem;">Out for Delivery</span>
                                            <?php elseif ($ord->status === 'delivered' || $ord->status === 'completed'): ?>
                                                <span class="badge bg-success text-white px-2 py-1" style="font-size: 0.7rem;"><?php echo ($ord->status === 'completed') ? 'Completed' : 'Delivered'; ?></span>
                                            <?php elseif ($ord->status === 'pending'): ?>
                                                <span class="badge bg-warning-subtle text-warning px-2 py-1 border border-warning-subtle" style="font-size: 0.7rem;">Pending</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger-subtle text-danger px-2 py-1 border border-danger-subtle" style="font-size: 0.7rem;">Cancelled</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="pe-4 text-end">
                                            <a href="<?php echo base_url('admin/orders/detail/' . $ord->id); ?>" class="btn btn-sm btn-outline-dark" style="font-size: 0.75rem; border-radius: 6px;">
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

        <!-- Recent Members (Right) -->
        <div class="col-lg-5 mb-4">
            <div class="card table-card h-100">
                <div class="p-4 text-white d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, var(--dark-sidebar) 0%, #1f2937 100%); border-bottom: 2px solid var(--primary-gold);">
                    <h5 class="fw-bold mb-0"><i class="fa-solid fa-users-plus me-2" style="color: var(--primary-gold);"></i>New Members</h5>
                    <a href="<?php echo base_url('admin/members'); ?>" class="btn btn-sm btn-outline-light" style="font-size: 0.8rem; border-radius: 6px;">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Name</th>
                                <th>Wallet</th>
                                <th>Status</th>
                                <th class="pe-4 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recent_members)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No members registered yet.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recent_members as $mem): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-semibold text-dark"><?php echo htmlspecialchars($mem->name); ?></div>
                                            <span class="text-muted extra-small d-block" style="font-size: 0.7rem;"><?php echo htmlspecialchars($mem->email); ?></span>
                                        </td>
                                        <td class="fw-semibold text-success">₹<?php echo number_format($mem->wallet_balance, 2); ?></td>
                                        <td>
                                            <?php if ((int)$mem->status === 1): ?>
                                                <span class="badge bg-success-subtle text-success px-2 py-0.5" style="font-size: 0.7rem;">Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger-subtle text-danger px-2 py-0.5" style="font-size: 0.7rem;">Blocked</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="pe-4 text-end">
                                            <a href="<?php echo base_url('admin/members/view/' . $mem->id); ?>" class="btn btn-sm btn-outline-dark" style="font-size: 0.75rem; border-radius: 6px;">
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
    $status_labels = ['Pending', 'Confirmed', 'Packed', 'Out for Delivery', 'Delivered/Completed', 'Cancelled'];
    $status_counts = [0, 0, 0, 0, 0, 0];
    if (!empty($status_chart)) {
        foreach ($status_chart as $s) {
            $st = strtolower($s['status']);
            if ($st === 'pending') $status_counts[0] += (int)$s['count'];
            elseif ($st === 'confirmed') $status_counts[1] += (int)$s['count'];
            elseif ($st === 'packed') $status_counts[2] += (int)$s['count'];
            elseif ($st === 'out_for_delivery') $status_counts[3] += (int)$s['count'];
            elseif ($st === 'delivered' || $st === 'completed') $status_counts[4] += (int)$s['count'];
            elseif ($st === 'cancelled') $status_counts[5] += (int)$s['count'];
        }
    }

    // Top selling products formatting
    $top_names = [];
    $top_sales = [];
    if (!empty($top_products_chart)) {
        foreach ($top_products_chart as $p) {
            $top_names[] = $p['product_name'];
            $top_sales[] = (float)$p['amount'];
        }
    } else {
        $top_names = ['None'];
        $top_sales = [0];
    }
    ?>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Sales Trend Chart
        const salesCtx = document.getElementById('salesTrendChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($c_labels); ?>,
                datasets: [{
                    label: 'Sales Revenue (₹)',
                    data: <?php echo json_encode($c_sales); ?>,
                    borderColor: '#ec407a',
                    backgroundColor: 'rgba(236, 64, 122, 0.08)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointBackgroundColor: '#ec407a'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
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

        // Order Status Chart
        const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($status_labels); ?>,
                datasets: [{
                    data: <?php echo json_encode($status_counts); ?>,
                    backgroundColor: [
                        '#fd7e14', // Pending
                        '#0d6efd', // Confirmed
                        '#6f42c1', // Packed
                        '#0dcaf0', // Out for Delivery
                        '#198754', // Delivered/Completed
                        '#dc3545'  // Cancelled
                    ],
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

        // Top Products Chart
        const prodCtx = document.getElementById('topProductsChart').getContext('2d');
        new Chart(prodCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($top_names); ?>,
                datasets: [{
                    label: 'Sales Revenue (₹)',
                    data: <?php echo json_encode($top_sales); ?>,
                    backgroundColor: '#d4af37',
                    borderRadius: 8,
                    maxBarThickness: 32
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { color: '#f3f4f6' },
                        ticks: { color: '#9ca3af' }
                    },
                    y: {
                        grid: { display: false },
                        ticks: { color: '#9ca3af' }
                    }
                }
            }
        });

        // User Registrations Grow Rate Chart
        const regCtx = document.getElementById('registrationsChart').getContext('2d');
        new Chart(regCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($c_labels); ?>,
                datasets: [{
                    label: 'New Registrations',
                    data: <?php echo json_encode($c_regs); ?>,
                    backgroundColor: '#198754',
                    borderRadius: 8,
                    maxBarThickness: 32
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f3f4f6' },
                        ticks: { color: '#9ca3af', stepSize: 1 }
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
            <p class="text-muted m-0">Welcome back to your settings center, <?php echo htmlspecialchars($user->name); ?>!</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <span class="badge bg-white text-dark border border-soft px-3 py-2 rounded-pill">
                <i class="fa-solid fa-wallet text-warning me-1"></i> Balance: <strong>₹<?php echo number_format($user->wallet_balance, 2); ?></strong>
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
                            <h3 class="fw-bold m-0" style="color: var(--dark-sidebar);">₹<?php echo number_format($user->wallet_balance, 2); ?></h3>
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
                            <h3 class="fw-bold m-0 text-uppercase" style="color: var(--primary-pink); letter-spacing: 1px;"><?php echo htmlspecialchars($user->referral_code); ?></h3>
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
                                            <?php elseif ($ord->status === 'pending'): ?>
                                                <span class="badge bg-warning-subtle text-warning px-2 py-0.5 border border-warning-subtle">Pending</span>
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
            Swal.fire({
                icon: 'success',
                title: 'Link Copied!',
                text: 'Your referral registration link was successfully copied.',
                confirmButtonColor: '#ec407a',
                timer: 1500
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
