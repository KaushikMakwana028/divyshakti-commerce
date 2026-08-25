<div class="row mb-4 align-items-center">
    <div class="col-12">
        <h3 class="fw-bold" style="color: var(--dark-sidebar);">MLM Level Commission Settings</h3>
        <p class="text-muted">Configure the referral payout distribution rules for levels 1 to 12.</p>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <!-- Display form validation errors locally if any -->
        <?php if (validation_errors()): ?>
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-left: 4px solid #dc3545 !important;">
                <i class="fa-solid fa-circle-exclamation me-2 text-danger"></i>
                <?php echo validation_errors(' ', ' '); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

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
                    <span class="d-block small text-light text-opacity-75">Admin Cut Remainder</span>
                    <h4 class="fw-bold m-0 text-warning" id="adminCutIndicator">25.50%</h4>
                </div>
            </div>
            
            <div class="card-body p-4">
                <form action="<?php echo base_url('admin/commissions/update'); ?>" method="POST" id="commissionForm">
                    <div class="row g-4">
                        <?php foreach ($settings as $setting): ?>
                            <div class="col-md-3 col-sm-6">
                                <div class="p-3 border rounded shadow-sm bg-light">
                                    <label for="pct_<?php echo $setting->level; ?>" class="form-label fw-bold text-dark mb-2">
                                        <i class="fa-solid fa-layer-group text-muted me-1"></i> Level <?php echo $setting->level; ?>
                                    </label>
                                    <div class="input-group">
                                        <input type="number" 
                                               step="0.01" 
                                               name="percentages[<?php echo $setting->level; ?>]" 
                                               id="pct_<?php echo $setting->level; ?>" 
                                               class="form-control pct-input fw-semibold" 
                                               placeholder="0.00" 
                                               min="0" 
                                               max="100" 
                                               value="<?php echo htmlspecialchars($setting->percentage); ?>" 
                                               required>
                                        <span class="input-group-text"><i class="fa-solid fa-percent"></i></span>
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
                                <span class="d-block text-dark fw-semibold">Referral Commission Allocated: <span id="allocatedSum">74.50%</span></span>
                                <span class="text-muted small">Total sum of Level 1 to 12 percentages must be strictly less than 100.00%.</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-end gap-2 mt-4">
                        <button type="submit" class="btn px-5 py-2.5 fw-semibold text-white" style="background: linear-gradient(45deg, var(--primary-pink), var(--primary-gold)); border: none; border-radius: 8px; box-shadow: 0 4px 10px rgba(236, 64, 122, 0.15);">
                            <i class="fa-solid fa-floppy-disk me-2"></i> Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const pctInputs = document.querySelectorAll('.pct-input');
    const allocatedSum = document.getElementById('allocatedSum');
    const adminCutIndicator = document.getElementById('adminCutIndicator');
    const form = document.getElementById('commissionForm');

    function calculateSums() {
        let totalAllocated = 0.00;
        pctInputs.forEach(input => {
            const val = parseFloat(input.value) || 0.00;
            totalAllocated += val;
        });

        const adminCut = 100.00 - totalAllocated;

        allocatedSum.textContent = totalAllocated.toFixed(2) + "%";
        adminCutIndicator.textContent = adminCut.toFixed(2) + "%";

        if (adminCut <= 0) {
            adminCutIndicator.className = "fw-bold m-0 text-danger";
        } else {
            adminCutIndicator.className = "fw-bold m-0 text-warning";
        }
    }

    // Bind real-time update
    pctInputs.forEach(input => {
        input.addEventListener('input', calculateSums);
    });

    // Form submit check
    if (form) {
        form.addEventListener('submit', function(e) {
            let totalAllocated = 0.00;
            pctInputs.forEach(input => {
                const val = parseFloat(input.value) || 0.00;
                totalAllocated += val;
            });

            if (totalAllocated >= 100.00) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Allocation Sum',
                    text: 'Total level commission sum (' + totalAllocated.toFixed(2) + '%) must be strictly less than 100%.',
                    confirmButtonColor: '#ec407a'
                });
            }
        });
    }

    // Initial load
    calculateSums();
});
</script>
