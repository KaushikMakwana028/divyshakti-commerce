<style>
    :root {
        --dsk-bg: #f2f3f7;
        --dsk-surface: #ffffff;
        --dsk-border: #e8e9ee;
        --dsk-text: #161c2d;
        --dsk-muted: #8890a3;
        --dsk-success: #0f9d58;
        --dsk-success-bg: #e8f8ee;
        --dsk-danger: #e0273d;
        --dsk-danger-bg: #fdecee;
        --dsk-warning: #b8860b;
        --dsk-warning-bg: #fbf2df;
        --dsk-radius: 12px;
    }

    .oda * {
        box-sizing: border-box;
    }

    .oda {
        font-family: inherit;
        color: var(--dsk-text);
    }

    .oda .page-head {
        display: flex;
        flex-wrap: wrap;
        gap: .75rem;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 1.25rem;
    }

    .oda .page-head h1 {
        font-size: 1.4rem;
        font-weight: 800;
        margin: 0 0 .2rem;
        letter-spacing: -.01em;
    }

    .oda .page-head p {
        margin: 0;
        color: var(--dsk-muted);
        font-size: .85rem;
    }

    .oda .back-btn {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .5rem 1rem;
        border-radius: 8px;
        border: 1px solid var(--dsk-border);
        background: var(--dsk-surface);
        color: var(--dsk-text);
        font-weight: 600;
        font-size: .82rem;
        text-decoration: none;
        box-shadow: 0 1px 2px rgba(20, 20, 40, .04);
        transition: .15s;
    }

    .oda .back-btn:hover {
        border-color: var(--dark-sidebar, #161c2d);
        color: var(--dsk-text);
    }

    .oda .order-meta {
        display: flex;
        flex-wrap: wrap;
        gap: .5rem 1.5rem;
        align-items: center;
        background: var(--dsk-surface);
        border: 1px solid var(--dsk-border);
        border-radius: var(--dsk-radius);
        padding: .8rem 1.1rem;
        margin-bottom: 1.25rem;
        font-size: .82rem;
    }

    .oda .order-meta .sep {
        color: var(--dsk-border);
    }

    .oda .status-pill {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .3rem .7rem;
        border-radius: 100px;
        font-weight: 700;
        font-size: .75rem;
    }

    .oda .status-pill.confirmed {
        background: var(--dsk-success-bg);
        color: var(--dsk-success);
    }

    .oda .status-pill.packed {
        background: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
    }

    .oda .status-pill.out-for-delivery {
        background: rgba(13, 202, 240, 0.1);
        color: #0dcaf0;
    }

    .oda .status-pill.delivered {
        background: var(--dsk-success-bg);
        color: var(--dsk-success);
    }

    .oda .status-pill.pending {
        background: var(--dsk-warning-bg);
        color: var(--dsk-warning);
    }

    .oda .status-pill.cancelled {
        background: var(--dsk-danger-bg);
        color: var(--dsk-danger);
    }

    .oda .card {
        background: var(--dsk-surface);
        border-radius: var(--dsk-radius);
        border: 1px solid var(--dsk-border);
        overflow: hidden;
        box-shadow: 0 1px 2px rgba(20, 20, 40, .04);
    }

    .oda .card-head {
        display: flex;
        align-items: center;
        gap: .5rem;
        background: var(--dark-sidebar, #161c2d);
        color: #fff;
        padding: .65rem .9rem;
        border-bottom: 2px solid var(--primary-gold, #d4af37);
    }

    .oda .card-head i {
        color: var(--primary-gold, #d4af37);
        font-size: .85rem;
    }

    .oda .card-head h2 {
        font-size: .82rem;
        font-weight: 700;
        margin: 0;
        letter-spacing: .01em;
    }

    .oda .card-body {
        padding: 1rem;
    }

    .oda .top-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 1rem;
        align-items: start;
    }

    @media (max-width:900px) {
        .oda .top-grid {
            grid-template-columns: 1fr;
        }
    }

    .oda .field {
        margin-bottom: .65rem;
    }

    .oda .field:last-child {
        margin-bottom: 0;
    }

    .oda .field .lbl {
        display: block;
        font-size: .68rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: var(--dsk-muted);
        margin-bottom: .15rem;
        font-weight: 600;
    }

    .oda .field .val {
        font-size: .88rem;
        font-weight: 600;
        color: var(--dsk-text);
    }

    .oda .field .val.sub {
        font-weight: 400;
        color: #4b5266;
    }

    .oda .code-chip {
        display: inline-block;
        font-family: 'SFMono-Regular', Consolas, monospace;
        background: #fdf1f6;
        color: var(--primary-pink, #ec4899);
        font-weight: 700;
        padding: .15rem .5rem;
        border-radius: 6px;
        font-size: .8rem;
    }

    .oda .btn-row {
        display: flex;
        gap: .5rem;
    }

    .oda .abtn {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .4rem;
        border: none;
        border-radius: 8px;
        padding: .55rem .6rem;
        font-weight: 700;
        font-size: .8rem;
        cursor: pointer;
        transition: .15s;
    }

    .oda .abtn.approve {
        background: var(--dsk-success);
        color: #fff;
    }

    .oda .abtn.approve:hover {
        background: #0c8347;
    }

    .oda .abtn.cancel {
        background: transparent;
        color: var(--dsk-danger);
        border: 1px solid var(--dsk-danger-bg);
    }

    .oda .abtn.cancel:hover {
        background: var(--dsk-danger-bg);
    }

    .oda .abtn.danger-solid {
        background: var(--dsk-danger);
        color: #fff;
        width: 100%;
    }

    .oda .abtn.danger-solid:hover {
        background: #c2192d;
    }

    .oda .warn-note {
        display: block;
        margin-top: .6rem;
        font-size: .72rem;
        line-height: 1.4;
        color: var(--dsk-muted);
    }

    .oda .warn-note.critical {
        color: var(--dsk-danger);
    }

    .oda .archived-note {
        display: flex;
        align-items: center;
        gap: .4rem;
        background: var(--dsk-bg);
        border-radius: 8px;
        padding: .6rem .7rem;
        color: var(--dsk-muted);
        font-size: .8rem;
        font-weight: 600;
    }

    .oda .item-row {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .oda .item-thumb {
        width: 58px;
        height: 58px;
        border-radius: 8px;
        border: 1px solid var(--dsk-border);
        overflow: hidden;
        flex-shrink: 0;
        background: var(--dark-sidebar, #161c2d);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .oda .item-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .oda .item-info {
        flex: 1;
        min-width: 160px;
    }

    .oda .item-info h3 {
        margin: 0 0 .15rem;
        font-size: 1rem;
        font-weight: 700;
    }

    .oda .item-info span {
        font-size: .78rem;
        color: var(--dsk-muted);
    }

    .oda .item-qty {
        text-align: right;
    }

    .oda .item-qty .q {
        display: block;
        font-size: .75rem;
        color: var(--dsk-muted);
        margin-bottom: .1rem;
    }

    .oda .item-qty .amt {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--dsk-success);
    }

    @media (max-width:480px) {
        .oda .item-row {
            justify-content: center;
            text-align: center;
        }

        .oda .item-qty {
            text-align: center;
            width: 100%;
        }
    }

    .oda .table-wrap {
        overflow-x: auto;
    }

    .oda table.comm {
        width: 100%;
        border-collapse: collapse;
        font-size: .85rem;
    }

    .oda table.comm th {
        text-align: left;
        background: #fafbfc;
        color: var(--dsk-muted);
        font-size: .68rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        padding: .65rem .9rem;
        border-bottom: 1px solid var(--dsk-border);
        font-weight: 700;
    }

    .oda table.comm td {
        padding: .7rem .9rem;
        border-bottom: 1px solid var(--dsk-border);
        vertical-align: middle;
    }

    .oda table.comm tr:last-child td {
        border-bottom: none;
    }

    .oda .lvl-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 28px;
        height: 22px;
        padding: 0 .4rem;
        border-radius: 6px;
        background: var(--dsk-bg);
        font-weight: 700;
        font-size: .75rem;
        color: var(--dsk-text);
    }

    .oda .lvl-badge.remainder {
        background: var(--dsk-warning-bg);
        color: var(--dsk-warning);
    }

    .oda .recv-name {
        font-weight: 700;
        font-size: .85rem;
    }

    .oda .recv-mail {
        display: block;
        color: var(--dsk-muted);
        font-size: .75rem;
    }

    .oda .amt-pos {
        color: var(--dsk-success);
        font-weight: 700;
    }

    .oda .amt-adm {
        color: var(--dsk-warning);
        font-weight: 700;
    }

    .oda .action-badge {
        display: inline-block;
        padding: .2rem .55rem;
        border-radius: 100px;
        font-size: .7rem;
        font-weight: 700;
    }

    .oda .action-badge.ref {
        background: var(--dsk-success-bg);
        color: var(--dsk-success);
    }

    .oda .action-badge.adm {
        background: var(--dsk-warning-bg);
        color: var(--dsk-warning);
    }

    .oda tr.total-row td {
        background: #fafbfc;
        font-weight: 700;
    }

    .oda .empty-row {
        text-align: center;
        padding: 2rem 1rem;
        color: var(--dsk-muted);
        font-size: .85rem;
    }

    @media (max-width:680px) {
        .oda table.comm thead {
            display: none;
        }

        .oda table.comm,
        .oda table.comm tbody,
        .oda table.comm tr,
        .oda table.comm td {
            display: block;
            width: 100%;
        }

        .oda table.comm tr {
            border-bottom: 1px solid var(--dsk-border);
            padding: .75rem .9rem;
        }

        .oda table.comm tr.total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .oda table.comm td {
            border: none;
            padding: .15rem 0;
        }

        .oda table.comm td[data-label]::before {
            content: attr(data-label);
            display: block;
            font-size: .65rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: var(--dsk-muted);
            font-weight: 700;
            margin-bottom: .1rem;
        }
    }

    .oda .sections {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
</style>

<div class="oda">

    <div class="page-head">
        <div>
            <h1>Order Detail Audit</h1>
            <p>Detailed review of ordering items, buyer details, and MLM commission chains.</p>
        </div>
        <a href="<?php echo base_url('admin/orders'); ?>" class="back-btn">
            <i class="fa-solid fa-arrow-left"></i> Back to Orders
        </a>
    </div>

    <div class="order-meta">
        <span><strong>Order #<?php echo $order->id; ?></strong></span>
        <span class="sep">&bull;</span>
        <?php if ($order->status === 'pending'): ?>
            <span class="status-pill pending"><i class="fa-solid fa-spinner fa-spin"></i> Pending</span>
        <?php elseif ($order->status === 'confirmed'): ?>
            <span class="status-pill confirmed"><i class="fa-solid fa-circle-check"></i> Confirmed</span>
        <?php elseif ($order->status === 'packed'): ?>
            <span class="status-pill packed"><i class="fa-solid fa-box"></i> Packed</span>
        <?php elseif ($order->status === 'out_for_delivery'): ?>
            <span class="status-pill out-for-delivery"><i class="fa-solid fa-truck"></i> Out for Delivery</span>
        <?php elseif ($order->status === 'delivered' || $order->status === 'completed'): ?>
            <span class="status-pill delivered"><i class="fa-solid fa-circle-check"></i> <?php echo ($order->status === 'completed') ? 'Completed' : 'Delivered'; ?></span>
        <?php else: ?>
            <span class="status-pill cancelled"><i class="fa-solid fa-circle-xmark"></i> Cancelled</span>
        <?php endif; ?>
        <span class="sep">&bull;</span>
        <span style="color:var(--dsk-muted)">Placed by <strong style="color:var(--dsk-text)"><?php echo htmlspecialchars($order->buyer_name); ?></strong></span>
    </div>

    <div class="sections">

        <!-- Row 1: Status control / Buyer / Shipping -->
        <div class="top-grid">

            <!-- Status Changer Panel -->
            <div class="card">
                <div class="card-head"><i class="fa-solid fa-circle-info"></i>
                    <h2>Order Status Control</h2>
                </div>
                <div class="card-body">
                    <form action="<?php echo base_url('admin/orders/status/' . $order->id); ?>" method="POST" id="statusChangeForm">
                        <?php if ($order->status === 'pending'): ?>
                            <div class="btn-row">
                                <button type="button" class="abtn approve submit-status-btn" data-status="confirmed">
                                    <i class="fa-solid fa-circle-check"></i> Confirm Order
                                </button>
                                <button type="button" class="abtn cancel submit-status-btn" data-status="cancelled">
                                    <i class="fa-solid fa-xmark"></i> Cancel Order
                                </button>
                                <input type="hidden" name="status" id="statusVal" value="">
                            </div>
                            <span class="warn-note">Confirming this order will deduct user balance and trigger MLM commission payouts.</span>
                        <?php elseif ($order->status === 'confirmed'): ?>
                            <div class="btn-row">
                                <button type="button" class="abtn approve submit-status-btn" style="background-color: var(--primary-gold) !important; color: #fff;" data-status="packed">
                                    <i class="fa-solid fa-box"></i> Pack Order
                                </button>
                                <button type="button" class="abtn cancel submit-status-btn" data-status="cancelled">
                                    <i class="fa-solid fa-triangle-exclamation"></i> Cancel &amp; Refund
                                </button>
                                <input type="hidden" name="status" id="statusVal" value="">
                            </div>
                            <span class="warn-note">Cancel & Refund will restore stock, refund buyer balance, and reverse commissions.</span>
                        <?php elseif ($order->status === 'packed'): ?>
                            <div class="btn-row">
                                <button type="button" class="abtn approve submit-status-btn" style="background-color: #0dcaf0 !important; color: #fff;" data-status="out_for_delivery">
                                    <i class="fa-solid fa-truck"></i> Out for Delivery
                                </button>
                                <button type="button" class="abtn cancel submit-status-btn" data-status="cancelled">
                                    <i class="fa-solid fa-triangle-exclamation"></i> Cancel &amp; Refund
                                </button>
                                <input type="hidden" name="status" id="statusVal" value="">
                            </div>
                            <span class="warn-note">Cancel & Refund will restore stock, refund buyer balance, and reverse commissions.</span>
                        <?php elseif ($order->status === 'out_for_delivery'): ?>
                            <div class="btn-row">
                                <button type="button" class="abtn approve submit-status-btn" style="background-color: #198754 !important; color: #fff;" data-status="delivered">
                                    <i class="fa-solid fa-house-chimney-user"></i> Deliver Order
                                </button>
                                <button type="button" class="abtn cancel submit-status-btn" data-status="cancelled">
                                    <i class="fa-solid fa-triangle-exclamation"></i> Cancel &amp; Refund
                                </button>
                                <input type="hidden" name="status" id="statusVal" value="">
                            </div>
                            <span class="warn-note">Cancel & Refund will restore stock, refund buyer balance, and reverse commissions.</span>
                        <?php elseif ($order->status === 'delivered' || $order->status === 'completed'): ?>
                            <div class="btn-row">
                                <button type="button" class="abtn cancel submit-status-btn" data-status="cancelled">
                                    <i class="fa-solid fa-triangle-exclamation"></i> Cancel &amp; Refund
                                </button>
                                <input type="hidden" name="status" id="statusVal" value="">
                            </div>
                            <span class="warn-note critical"><i class="fa-solid fa-circle-exclamation"></i> Order is <?php echo ($order->status === 'completed') ? 'completed' : 'delivered'; ?>. Reversing will refund buyer, restore stock, and reverse commissions.</span>
                        <?php else: ?>
                            <div class="archived-note">
                                <i class="fa-solid fa-archive"></i> Order is cancelled and archived.
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <!-- Buyer Specs Card -->
            <div class="card">
                <div class="card-head"><i class="fa-solid fa-user"></i>
                    <h2>Buyer Account</h2>
                </div>
                <div class="card-body">
                    <div class="field">
                        <span class="lbl">Buyer Name</span>
                        <span class="val"><?php echo htmlspecialchars($order->buyer_name); ?></span>
                    </div>
                    <div class="field">
                        <span class="lbl">Email Address</span>
                        <span class="val sub"><?php echo htmlspecialchars($order->buyer_email); ?></span>
                    </div>
                    <div class="field">
                        <span class="lbl">Phone Number</span>
                        <span class="val sub"><?php echo htmlspecialchars($order->buyer_phone); ?></span>
                    </div>
                    <div class="field">
                        <span class="lbl">Sponsor Code</span>
                        <span class="code-chip"><?php echo htmlspecialchars($order->buyer_ref); ?></span>
                    </div>
                </div>
            </div>

            <!-- Shipping Address Card -->
            <div class="card">
                <div class="card-head"><i class="fa-solid fa-truck-fast"></i>
                    <h2>Shipping Details</h2>
                </div>
                <div class="card-body">
                    <?php if (empty($shipping_address)): ?>
                        <span class="val sub" style="font-style:italic;">No shipping address recorded for this order.</span>
                    <?php else: ?>
                        <div class="field">
                            <span class="lbl">Contact Person</span>
                            <span class="val"><?php echo htmlspecialchars($shipping_address->full_name); ?></span>
                        </div>
                        <div class="field">
                            <span class="lbl">Phone Number</span>
                            <span class="val sub"><?php echo htmlspecialchars($shipping_address->mobile); ?></span>
                        </div>
                        <div class="field">
                            <span class="lbl">Street Address</span>
                            <span class="val sub">
                                <?php echo htmlspecialchars($shipping_address->address_line1); ?><br>
                                <?php if ($shipping_address->address_line2): ?>
                                    <?php echo htmlspecialchars($shipping_address->address_line2); ?><br>
                                <?php endif; ?>
                                <?php if ($shipping_address->landmark): ?>
                                    Landmark: <?php echo htmlspecialchars($shipping_address->landmark); ?>
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="field">
                            <span class="lbl">Location</span>
                            <span class="val sub">
                                <?php echo htmlspecialchars($shipping_address->city); ?>,
                                <?php echo htmlspecialchars($shipping_address->state); ?> -
                                <strong><?php echo htmlspecialchars($shipping_address->pincode); ?></strong>,
                                <?php echo htmlspecialchars($shipping_address->country); ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>

        <!-- Row 2: Product Ordered details -->
        <div class="card">
            <div class="card-head"><i class="fa-solid fa-basket-shopping"></i>
                <h2>Purchase Items</h2>
            </div>
            <div class="card-body">
                <div class="item-row">
                    <div class="item-thumb">
                        <img src="<?php echo $order->product_image ? base_url($order->product_image) : 'https://placehold.co/80x80/161c2d/d4af37?text=No+Image'; ?>"
                            alt="<?php echo htmlspecialchars($order->product_name); ?>">
                    </div>
                    <div class="item-info">
                        <h3><?php echo htmlspecialchars($order->product_name); ?></h3>
                        <span>Unit Price: &#8377;<?php echo number_format($order->product_price, 2); ?> &nbsp;&bull;&nbsp; Qty: <?php echo $order->quantity; ?> units</span>
                    </div>
                    <div class="item-qty">
                        <span class="q">Total</span>
                        <span class="amt">&#8377;<?php echo number_format($order->amount, 2); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 3: Commission Settings chain walkthrough -->
        <div class="card">
            <div class="card-head"><i class="fa-solid fa-sitemap"></i>
                <h2>MLM Referral Commission Chain Audit</h2>
            </div>
            <div class="table-wrap">
                <table class="comm">
                    <thead>
                        <tr>
                            <th>Level</th>
                            <th>Receiver Member</th>
                            <th>Allocation %</th>
                            <th>Payout Amount</th>
                            <th>Action Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!in_array($order->status, ['completed', 'confirmed', 'packed', 'out_for_delivery', 'delivered'])): ?>
                            <tr>
                                <td colspan="5" class="empty-row">
                                    <i class="fa-solid fa-clock"></i> Payout table is only computed for confirmed/paid orders.
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
                                    <td data-label="Level"><span class="lvl-badge">L<?php echo $comm->level; ?></span></td>
                                    <td data-label="Receiver Member">
                                        <span class="recv-name"><?php echo htmlspecialchars($comm->receiver_name); ?></span>
                                        <span class="recv-mail"><?php echo htmlspecialchars($comm->receiver_email); ?></span>
                                    </td>
                                    <td data-label="Allocation %"><?php echo number_format($level_pct, 2); ?>%</td>
                                    <td data-label="Payout Amount" class="amt-pos">+&#8377;<?php echo number_format($comm->amount, 2); ?></td>
                                    <td data-label="Action Type"><span class="action-badge ref">Referral Commission</span></td>
                                </tr>
                            <?php endforeach; ?>

                            <!-- Admin remainder cut record -->
                            <?php if ($admin_commission): ?>
                                <?php
                                $admin_pct = 100.00 - $total_paid_pct;
                                $total_paid_amt += (float)$admin_commission->amount;
                                ?>
                                <tr>
                                    <td data-label="Level"><span class="lvl-badge remainder">R</span></td>
                                    <td data-label="Receiver Member">
                                        <span class="recv-name">Main Admin Cut (Lowest ID Admin)</span>
                                        <span class="recv-mail">System Base Cut + Skipped Levels</span>
                                    </td>
                                    <td data-label="Allocation %"><?php echo number_format($admin_pct, 2); ?>%</td>
                                    <td data-label="Payout Amount" class="amt-adm">+&#8377;<?php echo number_format($admin_commission->amount, 2); ?></td>
                                    <td data-label="Action Type"><span class="action-badge adm">Admin Commission</span></td>
                                </tr>
                            <?php endif; ?>

                            <!-- Summary Footer row -->
                            <tr class="total-row">
                                <td colspan="2" data-label="">Total Distributed Payouts</td>
                                <td data-label="">100.00%</td>
                                <td data-label="">&#8377;<?php echo number_format($total_paid_amt, 2); ?></td>
                                <td data-label=""></td>
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
                let confirmBtnColor = "#ec4899";

                if (targetStatus === 'cancelled') {
                    const currentStatus = "<?php echo $order->status; ?>";
                    if (currentStatus === 'pending') {
                        titleStr = "Cancel Order?";
                        textStr = "Are you sure you want to cancel this pending order? No funds have been deducted yet.";
                        confirmBtnColor = "#e0273d";
                    } else {
                        titleStr = "Cancel & Refund Order?";
                        textStr = "This will refund the buyer's wallet, restore product stock, and reverse all MLM level commission payouts! This action cannot be undone.";
                        confirmBtnColor = "#e0273d";
                    }
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