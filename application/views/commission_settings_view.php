<div class="row mb-4 align-items-center">
    <div class="col-12">
        <h3 class="fw-bold" style="color: var(--dark-sidebar);">MLM Level Commission Settings</h3>
        <p class="text-muted">Configure the referral payout distribution rules for levels 1 to 12.</p>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
            <div class="p-4 text-white d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, var(--dark-sidebar) 0%, #1f2937 100%); border-bottom: 3px solid var(--primary-gold);">
                <div>
                    <span class="badge mb-2 text-uppercase" style="background-color: var(--primary-pink); font-size: 0.75rem; font-weight: 600; padding: 5px 10px;">
                        System Settings
                    </span>
                    <h5 class="fw-bold mb-0">Commission Settings</h5>
                </div>
                
                <!-- Real-time calculator panel -->
                <div class="bg-dark bg-opacity-25 px-3 py-2 rounded text-end shadow-inner" style="border: 1px solid rgba(255,255,255,0.15);">
                    <span class="d-block small text-light text-opacity-75">Total MLM Payout per Product</span>
                    <h4 class="fw-bold m-0 text-warning" id="totalPayoutIndicator">₹0.00</h4>
                </div>
            </div>
            
            <div class="card-body p-4">
                <form action="<?php echo base_url('admin/commissions/update'); ?>" method="POST" id="commissionForm">
                    <div class="row g-4">
                        <?php foreach ($settings as $setting): ?>
                            <div class="col-md-3 col-sm-6">
                                <div class="p-3 border rounded shadow-sm bg-light">
                                    <label for="amt_<?php echo $setting->level; ?>" class="form-label fw-bold text-dark mb-2">
                                        <i class="fa-solid fa-layer-group text-muted me-1"></i> Level <?php echo $setting->level; ?>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text fw-bold text-success">&#8377;</span>
                                        <input type="number" 
                                               step="0.01" 
                                               name="amounts[<?php echo $setting->level; ?>]" 
                                               id="amt_<?php echo $setting->level; ?>" 
                                               class="form-control amt-input fw-semibold" 
                                               placeholder="0.00" 
                                               min="0" 
                                               value="<?php echo htmlspecialchars($setting->amount ?? '0.00'); ?>" 
                                               required>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Dynamic Sum Bar info -->
                    <div class="alert alert-info border-0 shadow-sm mt-4 d-flex align-items-center justify-content-between p-3" style="border-left: 4px solid #0dcaf0 !important;">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-circle-info fs-4 me-3 text-info"></i>
                            <div>
                                <span class="d-block text-dark fw-semibold">Fixed Money Referral Commission: <span id="allocatedSum">₹0.00</span></span>
                                <span class="text-muted small">When a customer purchases a product, these fixed money amounts (₹) will be directly credited to each active ancestor's wallet.</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-end gap-2 mt-4">
                        <button type="submit" class="btn px-5 py-2.5 fw-semibold text-white" style="background: linear-gradient(45deg, var(--primary-pink), var(--primary-gold)); border: none; border-radius: 8px; box-shadow: 0 4px 10px rgba(236, 64, 122, 0.15);">
                            <i class="fa-solid fa-floppy-disk me-2"></i> Save Commission Amounts
                        </button>
                    </div>
                </form>
            </div>
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
