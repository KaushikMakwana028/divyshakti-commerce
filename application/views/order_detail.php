<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h3 class="fw-bold" style="color: var(--dark-sidebar);">Order Detail Audit</h3>
        <p class="text-muted">Detailed review of ordering items, buyer details, and MLM commission chains.</p>
    </div>
    <div class="col-sm-6 text-sm-end">
        <a href="<?php echo base_url('admin/orders'); ?>" class="btn btn-outline-primary px-4" style="border-radius: 8px;">
            <i class="fa-solid fa-arrow-left me-2"></i> Back to Orders
        </a>
    </div>
</div>

<div class="row">
    <!-- Order Summary Cards (Left) -->
    <div class="col-lg-4 mb-4">
        <!-- Status Changer Panel -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
            <div class="p-3 text-white" style="background-color: var(--dark-sidebar); border-bottom: 2px solid var(--primary-gold);">
                <h6 class="fw-bold mb-0"><i class="fa-solid fa-circle-info me-2 text-warning"></i>Order Status Control</h6>
            </div>
            <div class="card-body">
                <span class="text-muted small d-block mb-1">Current Status</span>
                <div class="mb-3">
                    <?php if ($order->status === 'completed'): ?>
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 fs-6 w-100" style="border-radius: 6px;">
                            <i class="fa-solid fa-circle-check me-1"></i> Completed
                        </span>
                    <?php elseif ($order->status === 'pending'): ?>
                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 fs-6 w-100" style="border-radius: 6px;">
                            <i class="fa-solid fa-spinner fa-spin me-1"></i> Pending Payment
                        </span>
                    <?php else: ?>
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 fs-6 w-100" style="border-radius: 6px;">
                            <i class="fa-solid fa-circle-xmark me-1"></i> Cancelled
                        </span>
                    <?php endif; ?>
                </div>

                <hr class="my-3">

                <!-- Action Form to Change Status -->
                <form action="<?php echo base_url('admin/orders/status/' . $order->id); ?>" method="POST" id="statusChangeForm">
                    <label for="status" class="form-label fw-bold text-dark mb-2">Update Order Status</label>
                    
                    <?php if ($order->status === 'pending'): ?>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-success py-2 fw-semibold submit-status-btn" data-status="completed" style="border-radius: 6px;">
                                <i class="fa-solid fa-check-double me-2"></i> Approve & Complete
                            </button>
                            <button type="button" class="btn btn-outline-danger py-2 fw-semibold submit-status-btn" data-status="cancelled" style="border-radius: 6px;">
                                <i class="fa-solid fa-xmark me-2"></i> Cancel Order
                            </button>
                            <input type="hidden" name="status" id="statusVal" value="">
                        </div>
                        <span class="text-muted extra-small d-block mt-2 text-center" style="font-size: 0.75rem;">Completing this order will deduct user balance and trigger MLM commission payouts.</span>
                    <?php elseif ($order->status === 'completed'): ?>
                        <div class="d-grid">
                            <button type="button" class="btn btn-danger py-2 fw-semibold submit-status-btn" data-status="cancelled" style="border-radius: 6px;">
                                <i class="fa-solid fa-triangle-exclamation me-2"></i> Cancel & Refund Order
                            </button>
                            <input type="hidden" name="status" id="statusVal" value="">
                        </div>
                        <span class="text-muted extra-small d-block mt-2 text-center" style="font-size: 0.75rem; color: #dc3545 !important;">WARNING: This will refund the buyer, restore product stock, and reverse all referral commission payouts!</span>
                    <?php else: ?>
                        <div class="alert alert-secondary border-0 mb-0 py-2.5 text-center small text-muted" style="border-radius: 6px;">
                            <i class="fa-solid fa-archive me-1"></i> Order is cancelled and archived.
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <!-- Buyer Specs Card -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
            <div class="p-3 text-white" style="background-color: var(--dark-sidebar); border-bottom: 2px solid var(--primary-gold);">
                <h6 class="fw-bold mb-0"><i class="fa-solid fa-user me-2 text-warning"></i>Buyer Account</h6>
            </div>
            <div class="card-body p-3">
                <div class="mb-3">
                    <span class="text-muted small d-block">Buyer Name</span>
                    <span class="fw-semibold text-dark"><?php echo htmlspecialchars($order->buyer_name); ?></span>
                </div>
                <div class="mb-3">
                    <span class="text-muted small d-block">Email Address</span>
                    <span class="text-dark"><?php echo htmlspecialchars($order->buyer_email); ?></span>
                </div>
                <div class="mb-3">
                    <span class="text-muted small d-block">Phone Number</span>
                    <span class="text-dark"><?php echo htmlspecialchars($order->buyer_phone); ?></span>
                </div>
                <div>
                    <span class="text-muted small d-block">Sponsor Code</span>
                    <code class="text-pink fw-bold" style="color: var(--primary-pink);"><?php echo htmlspecialchars($order->buyer_ref); ?></code>
                </div>
            </div>
        </div>

        <!-- Shipping Address Card -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
            <div class="p-3 text-white" style="background-color: var(--dark-sidebar); border-bottom: 2px solid var(--primary-gold);">
                <h6 class="fw-bold mb-0"><i class="fa-solid fa-truck-fast me-2 text-warning"></i>Shipping Details</h6>
            </div>
            <div class="card-body p-3">
                <?php if (empty($shipping_address)): ?>
                    <span class="text-muted small italic">No shipping address recorded for this order.</span>
                <?php else: ?>
                    <div class="mb-2">
                        <span class="text-muted small d-block">Contact Person</span>
                        <span class="fw-bold text-dark"><?php echo htmlspecialchars($shipping_address->full_name); ?></span>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted small d-block">Phone Number</span>
                        <span class="text-dark fw-semibold"><?php echo htmlspecialchars($shipping_address->mobile); ?></span>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted small d-block">Street Address</span>
                        <span class="text-dark small" style="line-height: 1.4;">
                            <?php echo htmlspecialchars($shipping_address->address_line1); ?><br>
                            <?php if ($shipping_address->address_line2): ?>
                                <?php echo htmlspecialchars($shipping_address->address_line2); ?><br>
                            <?php endif; ?>
                            <?php if ($shipping_address->landmark): ?>
                                <span class="text-muted">Landmark: </span><?php echo htmlspecialchars($shipping_address->landmark); ?><br>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted small d-block">Location</span>
                        <span class="text-dark small">
                            <?php echo htmlspecialchars($shipping_address->city); ?>, 
                            <?php echo htmlspecialchars($shipping_address->state); ?> - 
                            <strong><?php echo htmlspecialchars($shipping_address->pincode); ?></strong>
                        </span>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Country</span>
                        <span class="text-dark small"><?php echo htmlspecialchars($shipping_address->country); ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Product and Commission details (Right) -->
    <div class="col-lg-8 ps-lg-4">
        <!-- Product Ordered details -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
            <div class="p-3 text-white" style="background-color: var(--dark-sidebar); border-bottom: 2px solid var(--primary-gold);">
                <h6 class="fw-bold mb-0"><i class="fa-solid fa-basket-shopping me-2 text-warning"></i>Purchase Items</h6>
            </div>
            <div class="card-body p-3 p-sm-4">
                <div class="d-flex flex-column flex-sm-row align-items-center gap-3 gap-sm-4 text-center text-sm-start">
                    <div class="border rounded p-1" style="width: 80px; height: 80px; overflow: hidden; flex-shrink: 0;">
                        <img src="<?php echo $order->product_image ? base_url($order->product_image) : 'https://placehold.co/80x80/1f2937/d4af37?text=No+Image'; ?>" 
                             alt="<?php echo htmlspecialchars($order->product_name); ?>" 
                             class="w-100 h-100" 
                             style="object-fit: cover; border-radius: 4px;">
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($order->product_name); ?></h5>
                        <span class="text-muted small">Unit Price: ₹<?php echo number_format($order->product_price, 2); ?></span>
                    </div>
                    <div class="text-sm-end">
                        <span class="text-muted small d-block">Quantity: <strong><?php echo $order->quantity; ?> units</strong></span>
                        <h4 class="fw-bold text-success mt-1 mb-0">₹<?php echo number_format($order->amount, 2); ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Commission Settings chain walkthrough -->
        <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
            <div class="p-3 text-white" style="background-color: var(--dark-sidebar); border-bottom: 2px solid var(--primary-gold);">
                <h6 class="fw-bold mb-0"><i class="fa-solid fa-sitemap me-2 text-warning"></i>MLM Referral Commission Chain Audit</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Level</th>
                            <th>Receiver Member</th>
                            <th>Allocation %</th>
                            <th>Payout Amount</th>
                            <th>Action Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($order->status !== 'completed'): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fa-solid fa-clock me-2"></i> Payout table is only computed for completed/paid orders.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php 
                            $total_paid_pct = 0.00;
                            $total_paid_amt = 0.00;
                            ?>
                            <?php foreach ($commissions as $comm): ?>
                                <?php 
                                // Fetch percentage config dynamically for this level
                                $settings_item = $this->db->get_where('commission_settings', ['level' => $comm->level])->row();
                                $level_pct = $settings_item ? (float)$settings_item->percentage : 0.00;
                                $total_paid_pct += $level_pct;
                                $total_paid_amt += (float)$comm->amount;
                                ?>
                                <tr>
                                    <td class="ps-3 fw-bold">Level <?php echo $comm->level; ?></td>
                                    <td>
                                        <div class="fw-semibold text-dark"><?php echo htmlspecialchars($comm->receiver_name); ?></div>
                                        <span class="text-muted small"><?php echo htmlspecialchars($comm->receiver_email); ?></span>
                                    </td>
                                    <td><?php echo number_format($level_pct, 2); ?>%</td>
                                    <td class="fw-bold text-success">+₹<?php echo number_format($comm->amount, 2); ?></td>
                                    <td><span class="badge bg-success-subtle text-success" style="font-size: 0.75rem;">Referral Commission</span></td>
                                </tr>
                            <?php endforeach; ?>

                            <!-- Admin remainder cut record -->
                            <?php if ($admin_commission): ?>
                                <?php 
                                $admin_pct = 100.00 - $total_paid_pct;
                                $total_paid_amt += (float)$admin_commission->amount;
                                ?>
                                <tr>
                                    <td class="ps-3 fw-bold text-secondary">Remainder</td>
                                    <td>
                                        <div class="fw-semibold text-secondary">Main Admin Cut (Lowest ID Admin)</div>
                                        <span class="text-muted small">System Base Cut + Skipped Levels</span>
                                    </td>
                                    <td><?php echo number_format($admin_pct, 2); ?>%</td>
                                    <td class="fw-bold text-warning">+₹<?php echo number_format($admin_commission->amount, 2); ?></td>
                                    <td><span class="badge bg-warning-subtle text-warning" style="font-size: 0.75rem;">Admin Commission</span></td>
                                </tr>
                            <?php endif; ?>

                            <!-- Summary Footer row -->
                            <tr class="table-light">
                                <td colspan="2" class="ps-3 fw-bold text-dark text-end">Total Distributed Payouts:</td>
                                <td class="fw-bold text-dark">100.00%</td>
                                <td class="fw-bold text-dark">₹<?php echo number_format($total_paid_amt, 2); ?></td>
                                <td></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const submitBtns = document.querySelectorAll('.submit-status-btn');
    const statusValInput = document.getElementById('statusVal');
    const statusChangeForm = document.getElementById('statusChangeForm');

    submitBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const targetStatus = this.getAttribute('data-status');

            let titleStr = "Change Order Status?";
            let textStr = "Are you sure you want to mark this order as " + targetStatus.toUpperCase() + "?";
            let confirmBtnColor = "#ec407a";

            if (targetStatus === 'cancelled') {
                titleStr = "Cancel & Refund Order?";
                textStr = "This will refund the buyer's wallet, restore product stock, and reverse all MLM level commission payouts! This action cannot be undone.";
                confirmBtnColor = "#dc3545";
            }

            Swal.fire({
                title: titleStr,
                text: textStr,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: confirmBtnColor,
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, proceed!'
            }).then((result) => {
                if (result.isConfirmed) {
                    statusValInput.value = targetStatus;
                    statusChangeForm.submit();
                }
            });
        });
    });
});
</script>
