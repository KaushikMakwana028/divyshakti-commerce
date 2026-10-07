<style>
    /* =======================================================
       Payment Settings Page — scoped styles (.pay- prefix)
       ======================================================= */
    .pay-wrap {
        --pay-gold: #c89738;
        --pay-gold-dark: #a97c26;
        --pay-pink: #ec407a;
        --pay-dark: #111827;
        --pay-dark-2: #1f2937;
        --pay-border: #e5e7eb;
        --pay-muted: #6b7280;
        --pay-text: #111827;
        --pay-radius: 18px;
        --pay-radius-sm: 12px;
        --pay-shadow: 0 2px 8px rgba(17, 24, 39, .05);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        color: var(--pay-text);
    }

    .pay-wrap * {
        box-sizing: border-box;
    }

    /* ---------- Header ---------- */
    .pay-header {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 22px;
    }

    .pay-header h3 {
        font-size: clamp(1.35rem, 2vw, 1.75rem);
        font-weight: 800;
        color: var(--pay-dark);
        margin: 0 0 6px 0;
        letter-spacing: -.02em;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .pay-header h3 i {
        color: var(--pay-gold);
        font-size: .9em;
    }

    .pay-header p {
        color: var(--pay-muted);
        font-size: .9rem;
        margin: 0;
        max-width: 640px;
        line-height: 1.5;
    }

    .pay-link-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 999px;
        background: #fff;
        border: 1.5px solid var(--pay-border);
        color: var(--pay-dark-2);
        font-weight: 600;
        font-size: .85rem;
        text-decoration: none;
        box-shadow: var(--pay-shadow);
        transition: all .18s ease;
        white-space: nowrap;
    }

    .pay-link-btn:hover {
        background: var(--pay-dark-2);
        border-color: var(--pay-dark-2);
        color: #fff;
    }

    /* ---------- Shell + banner ---------- */
    .pay-card {
        background: #fff;
        border-radius: var(--pay-radius);
        box-shadow: var(--pay-shadow);
        border: 1px solid var(--pay-border);
        margin-bottom: 24px;
    }

    .pay-banner {
        position: relative;
        padding: 26px 28px;
        background: linear-gradient(120deg, var(--pay-dark) 0%, var(--pay-dark-2) 60%, #2c3646 100%);
        color: #fff;
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        align-items: center;
        justify-content: space-between;
        overflow: hidden;
        border-radius: var(--pay-radius) var(--pay-radius) 0 0;
    }

    .pay-banner::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--pay-gold), var(--pay-pink));
    }

    .pay-banner-glow {
        position: absolute;
        width: 260px;
        height: 260px;
        top: -110px;
        right: -60px;
        background: radial-gradient(circle, rgba(200, 151, 56, .18) 0%, transparent 70%);
        pointer-events: none;
    }

    .pay-banner-text {
        position: relative;
        min-width: 0;
        flex: 1 1 320px;
    }

    .pay-eyebrow {
        display: inline-block;
        background: var(--pay-pink);
        color: #fff;
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .6px;
        text-transform: uppercase;
        padding: 5px 12px;
        border-radius: 999px;
        margin-bottom: 10px;
    }

    .pay-banner-title {
        font-size: clamp(1.15rem, 1.8vw, 1.5rem);
        font-weight: 800;
        margin: 0 0 6px 0;
        color: #fff;
    }

    .pay-banner-subtitle {
        color: #9ca3af;
        font-size: .88rem;
        margin: 0;
        max-width: 580px;
        line-height: 1.55;
    }

    .pay-live {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #059669;
        color: #fff;
        font-weight: 700;
        font-size: .8rem;
        padding: 9px 16px;
        border-radius: 999px;
        box-shadow: 0 4px 12px rgba(5, 150, 105, .3);
        white-space: nowrap;
    }

    /* ---------- Layout ---------- */
    .pay-body {
        padding: 24px;
    }

    .pay-grid {
        display: grid;
        grid-template-columns: minmax(0, 5fr) minmax(0, 7fr);
        gap: 22px;
        align-items: start;
    }

    .pay-col {
        display: flex;
        flex-direction: column;
        gap: 22px;
        min-width: 0;
    }

    .pay-section {
        border: 1px solid var(--pay-border);
        border-radius: 16px;
        background: #fff;
        overflow: hidden;
    }

    .pay-section-header {
        padding: 14px 20px;
        background: #f9fafb;
        border-bottom: 1px solid var(--pay-border);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .pay-section-icon {
        width: 34px;
        height: 34px;
        flex-shrink: 0;
        border-radius: 10px;
        background: rgba(200, 151, 56, .14);
        color: var(--pay-gold-dark);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .95rem;
    }

    .pay-section-header h5 {
        margin: 0;
        font-size: 1rem;
        font-weight: 700;
        color: var(--pay-dark);
        line-height: 1.3;
    }

    .pay-section-header small {
        display: block;
        font-size: .76rem;
        color: var(--pay-muted);
        font-weight: 500;
        margin-top: 2px;
    }

    .pay-section-body {
        padding: 20px;
    }

    /* ---------- Form fields ---------- */
    .pay-fields {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .pay-field {
        min-width: 0;
    }

    .pay-field.full {
        grid-column: 1 / -1;
    }

    .pay-label {
        font-size: .83rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 7px;
        display: flex;
        align-items: center;
        gap: 8px;
        line-height: 1.3;
    }

    .pay-label i {
        width: 14px;
        text-align: center;
        color: #9ca3af;
        flex-shrink: 0;
        font-size: .85rem;
    }

    .pay-input,
    .pay-select,
    .pay-textarea {
        width: 100%;
        padding: 0 14px;
        height: 46px;
        border: 1.5px solid var(--pay-border);
        border-radius: var(--pay-radius-sm);
        font-size: .92rem;
        font-family: inherit;
        color: var(--pay-text);
        background-color: #fff;
        display: block;
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    .pay-input.mono {
        font-family: 'SFMono-Regular', Menlo, Consolas, monospace;
        letter-spacing: .3px;
    }

    .pay-input.upper {
        text-transform: uppercase;
    }

    .pay-input::placeholder,
    .pay-textarea::placeholder {
        color: #b0b6c0;
    }

    .pay-input:focus,
    .pay-select:focus,
    .pay-textarea:focus {
        outline: none;
        border-color: var(--pay-gold);
        box-shadow: 0 0 0 4px rgba(200, 151, 56, .14);
    }

    .pay-select {
        appearance: none;
        -webkit-appearance: none;
        padding-right: 40px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%236b7280' stroke-width='1.6' fill='none' fill-rule='evenodd'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
    }

    .pay-textarea {
        height: auto;
        min-height: 170px;
        padding: 12px 14px;
        resize: vertical;
        line-height: 1.6;
    }

    .pay-input-group {
        position: relative;
    }

    .pay-input-group .pay-prefix {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--pay-muted);
        font-weight: 700;
        border-right: 1.5px solid var(--pay-border);
        pointer-events: none;
    }

    .pay-input-group .pay-input {
        padding-left: 58px;
    }

    .pay-helper {
        font-size: .77rem;
        color: var(--pay-muted);
        margin-top: 6px;
        line-height: 1.45;
    }

    /* File input */
    .pay-file {
        width: 100%;
        border: 1.5px dashed #d6c08a;
        border-radius: var(--pay-radius-sm);
        background: #fffdf8;
        padding: 8px;
        font-size: .86rem;
        font-family: inherit;
        color: var(--pay-muted);
        cursor: pointer;
        transition: border-color .2s ease, background .2s ease;
    }

    .pay-file:hover {
        border-color: var(--pay-gold);
        background: #fff9ec;
    }

    .pay-file::file-selector-button {
        margin-right: 12px;
        padding: 9px 16px;
        border: none;
        border-radius: 9px;
        background: var(--pay-dark-2);
        color: #fff;
        font-weight: 600;
        font-size: .83rem;
        font-family: inherit;
        cursor: pointer;
        transition: background .18s ease;
    }

    .pay-file::file-selector-button:hover {
        background: var(--pay-gold-dark);
    }

    /* ---------- QR ---------- */
    .qr-preview-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 22px 16px;
        background: #fdfaf5;
        border: 2px dashed #e2cb9c;
        border-radius: var(--pay-radius-sm);
        text-align: center;
        margin-bottom: 18px;
        gap: 12px;
    }

    .qr-img {
        width: 190px;
        height: 190px;
        max-width: 100%;
        object-fit: contain;
        background: #fff;
        padding: 8px;
        border-radius: 14px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, .08);
        border: 1px solid #ebd49f;
        display: block;
    }

    .qr-img.d-none {
        display: none !important;
    }

    .qr-placeholder {
        width: 170px;
        height: 170px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;
        background: #fff;
        border-radius: 14px;
        border: 1px solid var(--pay-border);
        color: var(--pay-muted);
        font-size: .82rem;
        font-weight: 600;
    }

    .qr-placeholder.d-none {
        display: none !important;
    }

    .qr-placeholder i {
        font-size: 3rem;
        color: #d1d5db;
    }

    .pay-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 14px;
        border-radius: 999px;
        font-size: .76rem;
        font-weight: 700;
    }

    .pay-pill.ok {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }

    .pay-pill.off {
        background: #f3f4f6;
        color: #6b7280;
        border: 1px solid var(--pay-border);
    }

    .pay-check {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        color: #dc2626;
        font-size: .82rem;
        font-weight: 600;
    }

    .pay-check input {
        width: 17px;
        height: 17px;
        accent-color: #dc2626;
        cursor: pointer;
        margin: 0;
    }

    /* ---------- Action bar ---------- */
    .pay-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        padding: 18px 24px;
        border-top: 1px solid var(--pay-border);
        background: #f9fafb;
        border-radius: 0 0 var(--pay-radius) var(--pay-radius);
    }

    .btn-reset-pay,
    .btn-submit-pay {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-family: inherit;
        font-weight: 700;
        font-size: .92rem;
        padding: 13px 26px;
        border-radius: var(--pay-radius-sm);
        cursor: pointer;
        white-space: nowrap;
        transition: transform .18s ease, box-shadow .18s ease, filter .18s ease, background .18s ease;
    }

    .btn-reset-pay {
        background: #fff;
        color: var(--pay-dark-2);
        border: 1.5px solid #d1d5db;
    }

    .btn-reset-pay:hover {
        background: #f3f4f6;
    }

    .btn-submit-pay {
        background: linear-gradient(135deg, var(--pay-gold) 0%, #b88628 100%);
        color: #fff;
        border: none;
        box-shadow: 0 4px 14px rgba(200, 151, 56, .35);
    }

    .btn-submit-pay:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(200, 151, 56, .45);
        filter: brightness(1.05);
    }

    .btn-submit-pay:active {
        transform: translateY(0);
    }

    /* ============ Tablet ============ */
    @media (max-width: 991px) {
        .pay-grid {
            grid-template-columns: 1fr;
        }
    }

    /* ============ Mobile ============ */
    @media (max-width: 575px) {
        .pay-header {
            margin-bottom: 16px;
        }

        .pay-header>div:first-child {
            flex: 1 1 100%;
        }

        .pay-link-btn {
            width: 100%;
            justify-content: center;
        }

        .pay-banner {
            padding: 20px 18px;
        }

        .pay-live {
            width: 100%;
            justify-content: center;
        }

        .pay-body {
            padding: 14px;
        }

        .pay-grid,
        .pay-col {
            gap: 14px;
        }

        .pay-section-header {
            padding: 12px 14px;
        }

        .pay-section-body {
            padding: 16px 14px;
        }

        .pay-fields {
            grid-template-columns: 1fr;
            gap: 14px;
        }

        .qr-img {
            width: 170px;
            height: 170px;
        }

        .pay-input,
        .pay-select {
            font-size: 16px;
        }

        /* stops iOS zoom on focus */
        .pay-textarea {
            font-size: 16px;
            min-height: 190px;
        }

        .pay-actions {
            flex-direction: column-reverse;
            align-items: stretch;
            padding: 14px;
            gap: 10px;
        }

        .btn-reset-pay,
        .btn-submit-pay {
            width: 100%;
            padding: 14px 20px;
        }
    }
</style>

<div class="content-body pay-wrap">

    <!-- Header -->
    <div class="pay-header">
        <div>
            <h3><i class="fa-solid fa-qrcode"></i> Payment Settings</h3>
            <p>Manage the QR code, UPI ID and bank account details members see when they add money to their wallet.</p>
        </div>
        <a href="<?php echo base_url('admin/deposits'); ?>" class="pay-link-btn">
            <i class="fa-solid fa-money-bill-transfer"></i> View Deposit Requests
        </a>
    </div>

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i><?php echo $this->session->flashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i><?php echo $this->session->flashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Main Card Shell -->
    <div class="pay-card">
        <!-- Banner -->
        <div class="pay-banner">
            <div class="pay-banner-glow"></div>
            <div class="pay-banner-text">
                <span class="pay-eyebrow">Financial Configuration</span>
                <h4 class="pay-banner-title">Payment Gateway &amp; Deposit Details</h4>
                <p class="pay-banner-subtitle">
                    Members in the mobile app will see this QR code, UPI ID and bank account when they request a wallet deposit.
                </p>
            </div>
            <span class="pay-live"><i class="fa-solid fa-shield-halved"></i> Live Deposit Active</span>
        </div>

        <!-- Settings Form -->
        <?php echo form_open_multipart('admin/payment_settings/update', ['id' => 'paymentSettingsForm']); ?>

        <div class="pay-body">
            <div class="pay-grid">

                <!-- LEFT: QR + UPI -->
                <div class="pay-col">

                    <!-- QR Code -->
                    <div class="pay-section">
                        <div class="pay-section-header">
                            <span class="pay-section-icon"><i class="fa-solid fa-qrcode"></i></span>
                            <h5>Official Payment QR Code</h5>
                        </div>
                        <div class="pay-section-body">
                            <?php $has_qr = !empty($settings['payment_qr_code']) && file_exists(FCPATH . $settings['payment_qr_code']); ?>
                            <div class="qr-preview-box">
                                <?php if ($has_qr): ?>
                                    <img src="<?php echo base_url($settings['payment_qr_code']) . '?v=' . time(); ?>" alt="Admin Payment QR Code" class="qr-img" id="qrPreviewImg">
                                    <span class="pay-pill ok" id="qrStatusPill"><i class="fa-solid fa-check"></i> Active QR Code</span>
                                    <label class="pay-check" for="removeQrCheck">
                                        <input type="checkbox" name="remove_qr" value="1" id="removeQrCheck">
                                        <span><i class="fa-solid fa-trash-can"></i> Remove current QR code</span>
                                    </label>
                                <?php else: ?>
                                    <div class="qr-placeholder" id="qrPlaceholder">
                                        <i class="fa-solid fa-qrcode"></i>
                                        <span>No QR code uploaded</span>
                                    </div>
                                    <img src="#" alt="QR Preview" class="qr-img d-none" id="qrPreviewImg">
                                    <span class="pay-pill off" id="qrStatusPill">No active QR</span>
                                <?php endif; ?>
                            </div>

                            <div class="pay-field">
                                <label class="pay-label" for="payment_qr_code">
                                    <i class="fa-solid fa-upload"></i> Upload new QR code image
                                </label>
                                <input type="file" class="pay-file" name="payment_qr_code" id="payment_qr_code" accept="image/jpeg,image/png,image/webp">
                                <div class="pay-helper">PNG, JPG, JPEG or WEBP. Maximum size 5 MB.</div>
                            </div>
                        </div>
                    </div>

                    <!-- UPI -->
                    <div class="pay-section">
                        <div class="pay-section-header">
                            <span class="pay-section-icon"><i class="fa-solid fa-mobile-screen-button"></i></span>
                            <h5>UPI Details (VPA)</h5>
                        </div>
                        <div class="pay-section-body">
                            <div class="pay-fields">
                                <div class="pay-field full">
                                    <label class="pay-label" for="payment_upi_id">
                                        <i class="fa-solid fa-at"></i> UPI ID / VPA address
                                    </label>
                                    <input type="text" class="pay-input mono" name="payment_upi_id" id="payment_upi_id" autocomplete="off" autocapitalize="off" value="<?php echo htmlspecialchars($settings['payment_upi_id']); ?>" placeholder="e.g. divyshakti@okaxis">
                                    <div class="pay-helper">Members can copy this UPI ID with one tap.</div>
                                </div>
                                <div class="pay-field full">
                                    <label class="pay-label" for="payment_upi_name">
                                        <i class="fa-solid fa-user-tag"></i> UPI account name
                                    </label>
                                    <input type="text" class="pay-input" name="payment_upi_name" id="payment_upi_name" value="<?php echo htmlspecialchars($settings['payment_upi_name']); ?>" placeholder="e.g. Divy Shakti">
                                    <div class="pay-helper">Display name linked to this UPI ID.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- RIGHT: Bank + Policy -->
                <div class="pay-col">

                    <!-- Bank -->
                    <div class="pay-section">
                        <div class="pay-section-header">
                            <span class="pay-section-icon"><i class="fa-solid fa-building-columns"></i></span>
                            <div>
                                <h5>Bank Account Information</h5>
                                <small>For NEFT / IMPS / RTGS transfers</small>
                            </div>
                        </div>
                        <div class="pay-section-body">
                            <div class="pay-fields">
                                <div class="pay-field">
                                    <label class="pay-label" for="payment_bank_name">
                                        <i class="fa-solid fa-landmark"></i> Bank name
                                    </label>
                                    <input type="text" class="pay-input" name="payment_bank_name" id="payment_bank_name" value="<?php echo htmlspecialchars($settings['payment_bank_name']); ?>" placeholder="e.g. HDFC Bank">
                                </div>

                                <div class="pay-field">
                                    <label class="pay-label" for="payment_account_holder_name">
                                        <i class="fa-solid fa-user-check"></i> Account holder name
                                    </label>
                                    <input type="text" class="pay-input" name="payment_account_holder_name" id="payment_account_holder_name" value="<?php echo htmlspecialchars($settings['payment_account_holder_name']); ?>" placeholder="e.g. Divy Shakti Pvt. Ltd.">
                                </div>

                                <div class="pay-field">
                                    <label class="pay-label" for="payment_account_number">
                                        <i class="fa-solid fa-money-check"></i> Account number
                                    </label>
                                    <input type="text" inputmode="numeric" class="pay-input mono" name="payment_account_number" id="payment_account_number" autocomplete="off" value="<?php echo htmlspecialchars($settings['payment_account_number']); ?>" placeholder="e.g. 12345678901234">
                                </div>

                                <div class="pay-field">
                                    <label class="pay-label" for="payment_ifsc_code">
                                        <i class="fa-solid fa-code"></i> IFSC code
                                    </label>
                                    <input type="text" class="pay-input mono upper" name="payment_ifsc_code" id="payment_ifsc_code" maxlength="11" autocomplete="off" value="<?php echo htmlspecialchars($settings['payment_ifsc_code']); ?>" placeholder="e.g. SBIN0001234">
                                </div>

                                <div class="pay-field">
                                    <label class="pay-label" for="payment_account_type">
                                        <i class="fa-solid fa-tag"></i> Account type
                                    </label>
                                    <select class="pay-select" name="payment_account_type" id="payment_account_type">
                                        <option value="Current" <?php echo ($settings['payment_account_type'] === 'Current') ? 'selected' : ''; ?>>Current Account</option>
                                        <option value="Savings" <?php echo ($settings['payment_account_type'] === 'Savings') ? 'selected' : ''; ?>>Savings Account</option>
                                    </select>
                                </div>

                                <div class="pay-field">
                                    <label class="pay-label" for="payment_branch_name">
                                        <i class="fa-solid fa-location-dot"></i> Branch name
                                    </label>
                                    <input type="text" class="pay-input" name="payment_branch_name" id="payment_branch_name" value="<?php echo htmlspecialchars($settings['payment_branch_name']); ?>" placeholder="e.g. Rajkot Main Branch">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Policy -->
                    <div class="pay-section">
                        <div class="pay-section-header">
                            <span class="pay-section-icon"><i class="fa-solid fa-circle-info"></i></span>
                            <h5>Deposit Policy &amp; Instructions</h5>
                        </div>
                        <div class="pay-section-body">
                            <div class="pay-fields">
                                <div class="pay-field full">
                                    <label class="pay-label" for="min_deposit_amount">
                                        <i class="fa-solid fa-indian-rupee-sign"></i> Minimum deposit amount
                                    </label>
                                    <div class="pay-input-group">
                                        <span class="pay-prefix">₹</span>
                                        <input type="number" step="0.01" min="1" inputmode="decimal" class="pay-input" name="min_deposit_amount" id="min_deposit_amount" value="<?php echo htmlspecialchars($settings['min_deposit_amount']); ?>" placeholder="10.00" required>
                                    </div>
                                    <div class="pay-helper">The smallest amount a member can deposit in the app.</div>
                                </div>

                                <div class="pay-field full">
                                    <label class="pay-label" for="payment_instructions">
                                        <i class="fa-solid fa-list-check"></i> Payment instructions for users
                                    </label>
                                    <textarea class="pay-textarea" name="payment_instructions" id="payment_instructions" rows="7" placeholder="Enter instructions shown to members on the Deposit screen..."><?php echo htmlspecialchars($settings['payment_instructions']); ?></textarea>
                                    <div class="pay-helper">Step-by-step guidance displayed on the member's Deposit screen.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="pay-actions">
            <button type="reset" class="btn-reset-pay" id="payResetBtn">
                <i class="fa-solid fa-rotate-left"></i> Reset Changes
            </button>
            <button type="submit" class="btn-submit-pay">
                <i class="fa-solid fa-floppy-disk"></i> Save Payment Settings
            </button>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>

<script>
    (function() {
        const fileInput = document.getElementById('payment_qr_code');
        const previewImg = document.getElementById('qrPreviewImg');
        const placeholder = document.getElementById('qrPlaceholder');
        const statusPill = document.getElementById('qrStatusPill');
        const originalSrc = previewImg ? previewImg.getAttribute('src') : '';
        const hadPlaceholder = !!placeholder;

        // Live QR preview on file select
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = function(evt) {
                    previewImg.src = evt.target.result;
                    previewImg.classList.remove('d-none');
                    if (placeholder) placeholder.classList.add('d-none');
                    if (statusPill) {
                        statusPill.className = 'pay-pill ok';
                        statusPill.innerHTML = '<i class="fa-solid fa-check"></i> New QR selected';
                    }
                };
                reader.readAsDataURL(file);
            });
        }

        // Reset restores the original preview
        const resetBtn = document.getElementById('payResetBtn');
        if (resetBtn) {
            resetBtn.addEventListener('click', function() {
                setTimeout(function() {
                    if (!previewImg) return;
                    if (hadPlaceholder) {
                        previewImg.classList.add('d-none');
                        previewImg.setAttribute('src', '#');
                        if (placeholder) placeholder.classList.remove('d-none');
                        if (statusPill) {
                            statusPill.className = 'pay-pill off';
                            statusPill.textContent = 'No active QR';
                        }
                    } else {
                        previewImg.setAttribute('src', originalSrc);
                        if (statusPill) {
                            statusPill.className = 'pay-pill ok';
                            statusPill.innerHTML = '<i class="fa-solid fa-check"></i> Active QR Code';
                        }
                    }
                }, 0);
            });
        }

        // IFSC always upper case
        const ifsc = document.getElementById('payment_ifsc_code');
        if (ifsc) {
            ifsc.addEventListener('input', function() {
                const pos = this.selectionStart;
                this.value = this.value.toUpperCase();
                this.setSelectionRange(pos, pos);
            });
        }
    })();
</script>