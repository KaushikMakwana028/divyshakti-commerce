<?php if ((int)$user->role === 1): ?>
    <!-- ADMIN DASHBOARD LAYOUT -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="fw-bold" style="color: var(--dark-sidebar);">Admin Console Dashboard</h3>
            <p class="text-muted">Welcome back, <?php echo htmlspecialchars($user->name); ?>! Here is your business performance summary.</p>
        </div>
    </div>

    <!-- Admin Statistics Row -->
    <div class="row mb-4">
        <!-- Total Sales Card -->
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid var(--primary-pink) !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total E-Commerce Sales</p>
                            <h3 class="fw-bold m-0" style="color: var(--dark-sidebar);">₹<?php echo number_format($total_sales, 2); ?></h3>
                        </div>
                        <div class="p-3 rounded-circle" style="background-color: rgba(236, 64, 122, 0.1) !important;">
                            <i class="fa-solid fa-chart-line fs-4" style="color: var(--primary-pink);"></i>
                        </div>
                    </div>
                    <span class="text-muted small">Total completed orders</span>
                </div>
            </div>
        </div>

        <!-- Admin Earnings Card -->
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid var(--primary-gold) !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Admin Commission Cut</p>
                            <h3 class="fw-bold m-0" style="color: var(--primary-gold);">₹<?php echo number_format($total_admin_comm, 2); ?></h3>
                        </div>
                        <div class="p-3 rounded-circle" style="background-color: rgba(212, 175, 55, 0.15) !important;">
                            <i class="fa-solid fa-sack-dollar fs-4" style="color: var(--primary-gold);"></i>
                        </div>
                    </div>
                    <span class="text-muted small">Remainder cuts + base percentage</span>
                </div>
            </div>
        </div>

        <!-- Total Orders Card -->
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #0d6efd !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Orders</p>
                            <h3 class="fw-bold m-0 text-dark"><?php echo $total_orders; ?></h3>
                        </div>
                        <div class="p-3 rounded-circle" style="background-color: rgba(13, 110, 253, 0.1) !important;">
                            <i class="fa-solid fa-cart-shopping fs-4 text-primary"></i>
                        </div>
                    </div>
                    <div class="small">
                        <span class="text-warning fw-semibold"><?php echo $pending_orders; ?> Pending</span> | 
                        <span class="text-success fw-semibold"><?php echo $completed_orders; ?> Completed</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Members Card -->
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #198754 !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Registered Users</p>
                            <h3 class="fw-bold m-0 text-dark"><?php echo $total_members; ?></h3>
                        </div>
                        <div class="p-3 rounded-circle" style="background-color: rgba(25, 135, 84, 0.1) !important;">
                            <i class="fa-solid fa-users fs-4 text-success"></i>
                        </div>
                    </div>
                    <span class="text-muted small">Registered network members</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Tables Rows -->
    <div class="row">
        <!-- Recent Orders (Left) -->
        <div class="col-lg-7 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; overflow: hidden;">
                <div class="p-4 text-white d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, var(--dark-sidebar) 0%, #1f2937 100%); border-bottom: 2px solid var(--primary-gold);">
                    <h5 class="fw-bold mb-0">Recent Orders</h5>
                    <a href="<?php echo base_url('admin/orders'); ?>" class="btn btn-sm btn-outline-light" style="font-size: 0.8rem; border-radius: 6px;">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Order ID</th>
                                <th>Buyer</th>
                                <th>Product</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th class="pe-3 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recent_orders)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No orders placed yet.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recent_orders as $ord): ?>
                                    <tr>
                                        <td class="ps-3 fw-bold">#<?php echo $ord->id; ?></td>
                                        <td class="fw-semibold text-dark"><?php echo htmlspecialchars($ord->buyer_name); ?></td>
                                        <td class="text-truncate" style="max-width: 140px;"><?php echo htmlspecialchars($ord->product_name); ?></td>
                                        <td class="fw-semibold">₹<?php echo number_format($ord->amount, 2); ?></td>
                                        <td>
                                            <?php if ($ord->status === 'completed'): ?>
                                                <span class="badge bg-success-subtle text-success px-2 py-1 border border-success-subtle" style="font-size: 0.7rem;">Completed</span>
                                            <?php elseif ($ord->status === 'pending'): ?>
                                                <span class="badge bg-warning-subtle text-warning px-2 py-1 border border-warning-subtle" style="font-size: 0.7rem;">Pending</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger-subtle text-danger px-2 py-1 border border-danger-subtle" style="font-size: 0.7rem;">Cancelled</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="pe-3 text-end">
                                            <a href="<?php echo base_url('admin/orders/detail/' . $ord->id); ?>" class="btn btn-sm btn-outline-dark" style="font-size: 0.75rem;">
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
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; overflow: hidden;">
                <div class="p-4 text-white d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, var(--dark-sidebar) 0%, #1f2937 100%); border-bottom: 2px solid var(--primary-gold);">
                    <h5 class="fw-bold mb-0">New Members</h5>
                    <a href="<?php echo base_url('admin/members'); ?>" class="btn btn-sm btn-outline-light" style="font-size: 0.8rem; border-radius: 6px;">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Name</th>
                                <th>Wallet</th>
                                <th>Status</th>
                                <th class="pe-3 text-end">Action</th>
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
                                        <td class="ps-3">
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
                                        <td class="pe-3 text-end">
                                            <a href="<?php echo base_url('admin/members/view/' . $mem->id); ?>" class="btn btn-sm btn-outline-dark" style="font-size: 0.75rem;">
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

<?php else: ?>
    <!-- MEMBER DASHBOARD LAYOUT -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="fw-bold" style="color: var(--dark-sidebar);">Member Settings Console</h3>
            <p class="text-muted">Welcome back to your settings center, <?php echo htmlspecialchars($user->name); ?>!</p>
        </div>
    </div>

    <!-- Quick Statistics Row -->
    <div class="row mb-4">
        <!-- Wallet Card -->
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid var(--primary-gold) !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.8rem; letter-spacing: 0.5px;">Wallet Balance</p>
                            <h3 class="fw-bold m-0" style="color: var(--dark-sidebar);">
                                ₹<?php echo number_format($user->wallet_balance, 2); ?>
                            </h3>
                        </div>
                        <div class="bg-warning-subtle text-warning p-3 rounded-circle" style="background-color: rgba(212, 175, 55, 0.15) !important;">
                            <i class="fa-solid fa-wallet fs-3" style="color: var(--primary-gold);"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="text-success fw-bold me-2"><i class="fa-solid fa-arrow-up-right me-1"></i> Active</span>
                        <span class="text-muted" style="font-size: 0.85rem;">Ready for transactions</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Referral Code Card -->
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid var(--primary-pink) !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.8rem; letter-spacing: 0.5px;">Your Referral Code</p>
                            <h3 class="fw-bold m-0 text-uppercase" style="color: var(--primary-pink); letter-spacing: 1px;">
                                <?php echo htmlspecialchars($user->referral_code); ?>
                            </h3>
                        </div>
                        <div class="p-3 rounded-circle" style="background-color: rgba(236, 64, 122, 0.1) !important;">
                            <i class="fa-solid fa-gift fs-3" style="color: var(--primary-pink);"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="text-muted" style="font-size: 0.85rem;">Share and earn rewards</span>
                        <button class="btn btn-sm btn-outline-secondary py-1" onclick="copyReferralLink('<?php echo base_url('admin/register?ref=' . $user->referral_code); ?>')">
                            <i class="fa-regular fa-copy"></i> Copy Link
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Referrals Count -->
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #198754 !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.8rem; letter-spacing: 0.5px;">Direct Referrals</p>
                            <h3 class="fw-bold m-0 text-success">
                                <?php echo $direct_referrals_count; ?> users
                            </h3>
                        </div>
                        <div class="p-3 rounded-circle" style="background-color: rgba(25, 135, 84, 0.1) !important;">
                            <i class="fa-solid fa-people-group fs-3 text-success"></i>
                        </div>
                    </div>
                    <span class="text-muted small">Registered via your sponsor link</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Personal profile, purchases and recent transaction tables -->
    <div class="row">
        <!-- My Recent Orders -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; overflow: hidden;">
                <div class="p-4 text-white" style="background: linear-gradient(135deg, var(--dark-sidebar) 0%, #1f2937 100%); border-bottom: 2px solid var(--primary-gold);">
                    <h5 class="fw-bold mb-0">My Recent Orders</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Order ID</th>
                                <th>Product</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th class="pe-3">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($my_recent_orders)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">You haven't placed any orders yet.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($my_recent_orders as $ord): ?>
                                    <tr>
                                        <td class="ps-3 fw-bold">#<?php echo $ord->id; ?></td>
                                        <td class="fw-semibold text-dark"><?php echo htmlspecialchars($ord->product_name); ?></td>
                                        <td class="fw-semibold">₹<?php echo number_format($ord->amount, 2); ?></td>
                                        <td>
                                            <?php if ($ord->status === 'completed'): ?>
                                                <span class="badge bg-success-subtle text-success px-2 py-0.5 border border-success-subtle">Completed</span>
                                            <?php elseif ($ord->status === 'pending'): ?>
                                                <span class="badge bg-warning-subtle text-warning px-2 py-0.5 border border-warning-subtle">Pending</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger-subtle text-danger px-2 py-0.5 border border-danger-subtle">Cancelled</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="pe-3 text-muted small"><?php echo date('M d, Y', strtotime($ord->created_at)); ?></td>
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
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; overflow: hidden;">
                <div class="p-4 text-white" style="background: linear-gradient(135deg, var(--dark-sidebar) 0%, #1f2937 100%); border-bottom: 2px solid var(--primary-gold);">
                    <h5 class="fw-bold mb-0">My Recent Wallet Activity</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Txn ID</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Source</th>
                                <th class="pe-3">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($my_recent_txns)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No transactions logged yet.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($my_recent_txns as $t): ?>
                                    <tr>
                                        <td class="ps-3 fw-bold">#<?php echo $t->id; ?></td>
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
                                        <td class="pe-3 text-muted small"><?php echo date('M d, Y H:i', strtotime($t->created_at)); ?></td>
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
<?php endif; ?>
