<style>
    /* =======================================================
   Commission Settings Page — scoped styles (.cms- prefix)
   ======================================================= */
    .cms-wrap {
        --cms-gold: #c89738;
        --cms-gold-dark: #a97c26;
        --cms-pink: #ec407a;
        --cms-dark: #111827;
        --cms-dark-2: #1f2937;
        --cms-border: #e5e7eb;
        --cms-muted: #6b7280;
        --cms-text: #111827;
        --cms-radius: 18px;
        --cms-radius-sm: 12px;
        --cms-shadow: 0 2px 8px rgba(17, 24, 39, .05);
        --cms-shadow-hover: 0 10px 24px -6px rgba(17, 24, 39, .10);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        color: var(--cms-text);
    }

    .cms-wrap * {
        box-sizing: border-box;
    }

    /* ---------- Header ---------- */
    .cms-header {
        margin-bottom: 22px;
    }

    .cms-header h3 {
        font-size: clamp(1.35rem, 2vw, 1.75rem);
        font-weight: 800;
        color: var(--cms-dark);
        margin: 0 0 6px 0;
        letter-spacing: -.02em;
    }

    .cms-header p {
        color: var(--cms-muted);
        font-size: .9rem;
        margin: 0;
    }

    /* ---------- Card shell ---------- */
    .cms-card {
        background: #fff;
        border-radius: var(--cms-radius);
        box-shadow: var(--cms-shadow);
        overflow: hidden;
        border: 1px solid var(--cms-border);
    }

    /* ---------- Banner ---------- */
    .cms-banner {
        position: relative;
        padding: 26px 28px;
        background: linear-gradient(120deg, var(--cms-dark) 0%, var(--cms-dark-2) 60%, #2c3646 100%);
        color: #fff;
        display: flex;
        flex-wrap: wrap;
        gap: 18px;
        align-items: center;
        justify-content: space-between;
        overflow: hidden;
    }

    .cms-banner::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--cms-gold), var(--cms-pink));
    }

    .cms-banner-glow {
        position: absolute;
        width: 260px;
        height: 260px;
        background: radial-gradient(circle, rgba(200, 151, 56, .18) 0%, transparent 70%);
        top: -110px;
        right: -60px;
        pointer-events: none;
    }

    .cms-eyebrow {
        display: inline-block;
        background: var(--cms-pink);
        color: #fff;
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .6px;
        text-transform: uppercase;
        padding: 5px 12px;
        border-radius: 999px;
        margin-bottom: 10px;
    }

    .cms-banner h5 {
        font-size: 1.2rem;
        font-weight: 800;
        margin: 0;
        position: relative;
        z-index: 1;
    }

    .cms-payout-panel {
        background: rgba(255, 255, 255, .08);
        border: 1px solid rgba(255, 255, 255, .18);
        border-radius: var(--cms-radius-sm);
        padding: 14px 20px;
        text-align: right;
        position: relative;
        z-index: 1;
        backdrop-filter: blur(2px);
    }

    .cms-payout-label {
        display: block;
        font-size: .78rem;
        color: rgba(255, 255, 255, .72);
        margin-bottom: 4px;
    }

    .cms-payout-value {
        font-size: 1.5rem;
        font-weight: 800;
        margin: 0;
        color: #fbbf24;
    }

    /* ---------- Body / form ---------- */
    .cms-body {
        padding: 26px;
    }

    .cms-level-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    @media (max-width:991px) {
        .cms-level-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width:767px) {
        .cms-level-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width:420px) {
        .cms-level-grid {
            grid-template-columns: 1fr;
        }
    }

    .cms-level-card {
        background: #f8f9fc;
        border: 1.5px solid var(--cms-border);
        border-radius: var(--cms-radius-sm);
        padding: 16px;
        transition: all .2s ease;
    }

    .cms-level-card:hover {
        border-color: var(--cms-gold);
        box-shadow: var(--cms-shadow-hover);
        transform: translateY(-2px);
        background: #fff;
    }

    .cms-level-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 700;
        font-size: .92rem;
        color: var(--cms-dark);
        margin-bottom: 10px;
    }

    .cms-level-label i {
        color: var(--cms-gold-dark);
        font-size: .85rem;
    }

    .cms-input-group {
        display: flex;
        align-items: stretch;
        border: 1.5px solid var(--cms-border);
        border-radius: 10px;
        overflow: hidden;
        background: #fff;
        transition: border-color .18s ease, box-shadow .18s ease;
    }

    .cms-input-group:focus-within {
        border-color: var(--cms-gold);
        box-shadow: 0 0 0 4px rgba(200, 151, 56, .14);
    }

    .cms-input-currency {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        flex-shrink: 0;
        background: #f0fdf4;
        color: #059669;
        font-weight: 700;
        font-size: .95rem;
        border-right: 1.5px solid var(--cms-border);
    }

    .cms-input-group input {
        border: none;
        outline: none;
        width: 100%;
        padding: 10px 12px;
        font-size: .95rem;
        font-weight: 600;
        font-family: inherit;
        color: var(--cms-dark);
    }

    .cms-input-group input::-webkit-outer-spin-button,
    .cms-input-group input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .cms-input-group input[type=number] {
        -moz-appearance: textfield;
    }

    /* ---------- Info alert ---------- */
    .cms-info-alert {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        background: #ecfeff;
        border: 1px solid #a5f3fc;
        border-left: 4px solid #06b6d4;
        border-radius: var(--cms-radius-sm);
        padding: 16px 18px;
        margin-bottom: 24px;
    }

    .cms-info-icon {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        flex-shrink: 0;
        background: #06b6d4;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .cms-info-title {
        font-weight: 700;
        color: var(--cms-dark);
        font-size: .95rem;
        display: block;
        margin-bottom: 3px;
    }

    .cms-info-title span {
        color: #0e7490;
        font-weight: 800;
    }

    .cms-info-sub {
        color: var(--cms-muted);
        font-size: .83rem;
        line-height: 1.5;
    }

    /* ---------- Save button ---------- */
    .cms-actions {
        display: flex;
        justify-content: flex-end;
    }

    .cms-save-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 13px 32px;
        border: none;
        border-radius: 12px;
        background: linear-gradient(45deg, var(--cms-pink), var(--cms-gold));
        color: #fff;
        font-weight: 700;
        font-size: .92rem;
        cursor: pointer;
        box-shadow: 0 6px 16px rgba(236, 64, 122, .25);
        transition: all .2s ease;
    }

    .cms-save-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 22px rgba(236, 64, 122, .35);
    }

    @media (max-width:575px) {
        .cms-banner {
            padding: 20px;
            flex-direction: column;
            align-items: flex-start;
        }

        .cms-payout-panel {
            width: 100%;
            text-align: left;
        }

        .cms-body {
            padding: 18px;
        }

        .cms-actions {
            width: 100%;
        }

        .cms-save-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="cms-wrap">

    <div class="cms-header">
        <h3>MLM Level Commission Settings</h3>
        <p>Configure the referral payout distribution rules for levels 1 to 12 when an admin activates a member account.</p>
    </div>

    <div class="cms-card">
        <div class="cms-banner">
            <div class="cms-banner-glow"></div>
            <div style="position:relative;z-index:1;">
                <span class="cms-eyebrow">System Settings</span>
                <h5>Commission Settings</h5>
            </div>

            <div class="cms-payout-panel">
                <span class="cms-payout-label">Total Referral Payout on Activation</span>
                <h4 class="cms-payout-value" id="totalPayoutIndicator">₹0.00</h4>
            </div>
        </div>

        <div class="cms-body">
            <form action="<?php echo base_url('admin/commissions/update'); ?>" method="POST" id="commissionForm">
                <div class="cms-level-grid">
                    <?php foreach ($settings as $setting): ?>
                        <div class="cms-level-card">
                            <label for="amt_<?php echo $setting->level; ?>" class="cms-level-label">
                                <i class="fa-solid fa-layer-group"></i> Level <?php echo $setting->level; ?>
                            </label>
                            <div class="cms-input-group">
                                <span class="cms-input-currency">&#8377;</span>
                                <input type="number"
                                    step="0.01"
                                    name="amounts[<?php echo $setting->level; ?>]"
                                    id="amt_<?php echo $setting->level; ?>"
                                    class="amt-input"
                                    placeholder="0.00"
                                    min="0"
                                    value="<?php echo htmlspecialchars($setting->amount ?? '0.00'); ?>"
                                    required>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="cms-info-alert">
                    <div class="cms-info-icon"><i class="fa-solid fa-circle-info"></i></div>
                    <div>
                        <span class="cms-info-title">Fixed Money Referral Commission: <span id="allocatedSum">₹0.00</span></span>
                        <div class="cms-info-sub">When an admin activates a member account, these fixed money amounts (₹) will be directly credited once to each active ancestor's wallet.</div>
                    </div>
                </div>

                <div class="cms-actions">
                    <button type="submit" class="cms-save-btn">
                        <i class="fa-solid fa-floppy-disk"></i> Save Commission Amounts
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const amtInputs = document.querySelectorAll('.amt-input');
        const allocatedSum = document.getElementById('allocatedSum');
        const totalPayoutIndicator = document.getElementById('totalPayoutIndicator');
        const form = document.getElementById('commissionForm');

        function calculateSums() {
            let totalAllocated = 0.00;
            amtInputs.forEach(input => {
                const val = parseFloat(input.value) || 0.00;
                totalAllocated += val;
            });

            allocatedSum.textContent = "₹" + totalAllocated.toFixed(2);
            totalPayoutIndicator.textContent = "₹" + totalAllocated.toFixed(2);
        }

        // Bind real-time update
        amtInputs.forEach(input => {
            input.addEventListener('input', calculateSums);
        });

        // Form submit check: ensures no negative values
        if (form) {
            form.addEventListener('submit', function(e) {
                let hasNegative = false;
                amtInputs.forEach(input => {
                    const val = parseFloat(input.value);
                    if (isNaN(val) || val < 0) {
                        hasNegative = true;
                    }
                });

                if (hasNegative) {
                    e.preventDefault();
                    dsAlert({
                        icon: 'error',
                        title: 'Invalid Commission Amount',
                        text: 'All level commission amounts must be non-negative numbers.'
                    });
                }
            });
        }

        // Initial load
        calculateSums();
    });
</script>