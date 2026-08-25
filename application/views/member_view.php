<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h3 class="fw-bold" style="color: var(--dark-sidebar);">System Members</h3>
        <p class="text-muted">Manage member registration accounts and wallets.</p>
    </div>
</div>

<!-- Search and Filter Form -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 8px;">
            <div class="card-body p-3">
                <form id="filterForm" class="row g-2 align-items-center">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                            <input type="text" name="search" id="searchInput" class="form-control border-start-0" placeholder="Search by name, email, or phone..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="status" id="statusFilter" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="1" <?php echo ($status === '1') ? 'selected' : ''; ?>>Active</option>
                            <option value="0" <?php echo ($status === '0') ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" id="resetBtn" class="btn btn-secondary w-100">
                            Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Member List Table -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
            <div id="table-container">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="text-white" style="background-color: var(--dark-sidebar); border-bottom: 3px solid var(--primary-gold);">
                            <tr>
                                <th class="ps-4" style="width: 100px;">Index</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Wallet Balance</th>
                                <th>Status</th>
                                <th>Registered At</th>
                                <th class="text-end pe-4" style="width: 120px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($members)): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-users-slash d-block fs-1 mb-3 text-secondary"></i>
                                        No members found.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php 
                                $index_num = ($current_page - 1) * 10 + 1;
                                foreach ($members as $member): 
                                ?>
                                    <tr>
                                        <td class="ps-4 fw-semibold text-muted">#<?php echo $index_num++; ?></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white shadow-sm" style="width: 32px; height: 32px; font-size: 0.85rem; background-color: var(--primary-pink);">
                                                    <?php echo strtoupper(substr($member->name ?? 'M', 0, 1)); ?>
                                                </div>
                                                <span class="fw-semibold text-dark"><?php echo htmlspecialchars($member->name); ?></span>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($member->email); ?></td>
                                        <td><?php echo htmlspecialchars($member->phone); ?></td>
                                        <td>
                                            <span class="fw-bold fs-6 text-success">₹<?php echo number_format($member->wallet_balance, 2); ?></span>
                                        </td>
                                        <td>
                                            <?php if ((int)$member->status === 1): ?>
                                                <span class="badge bg-success-subtle text-success px-2.5 py-1 border border-success-subtle" style="border-radius: 4px; font-size: 0.75rem;">Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger-subtle text-danger px-2.5 py-1 border border-danger-subtle" style="border-radius: 4px; font-size: 0.75rem;">Blocked</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-muted small"><?php echo date('M d, Y', strtotime($member->created_at)); ?></td>
                                        <td class="text-end pe-4">
                                            <div class="d-inline-flex gap-2">
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-success load-wallet-btn px-2.5 py-1.5" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#loadWalletModal" 
                                                        data-id="<?php echo $member->id; ?>" 
                                                        data-name="<?php echo htmlspecialchars($member->name); ?>" 
                                                        data-balance="₹<?php echo number_format($member->wallet_balance, 2); ?>"
                                                        style="border-radius: 6px;"
                                                        title="Load Wallet Funds">
                                                    <i class="fa-solid fa-wallet"></i>
                                                </button>
                                                <a href="<?php echo base_url('admin/members/view/' . $member->id); ?>" class="btn btn-sm btn-outline-primary px-2.5 py-1.5" style="border-radius: 6px;" title="Inspect Detail View">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination footer inside container -->
                <?php if ($total_pages > 1): ?>
                    <div class="card-footer bg-white border-0 p-3">
                        <nav aria-label="Member Page Navigation">
                            <ul class="pagination justify-content-center mb-0">
                                <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="#" data-page="<?php echo $current_page - 1; ?>">&laquo; Prev</a>
                                </li>
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="page-item <?php echo ($current_page == $i) ? 'active' : ''; ?>">
                                        <a class="page-link" href="#" data-page="<?php echo $i; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>
                                <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="#" data-page="<?php echo $current_page + 1; ?>">Next &raquo;</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Load Wallet Modal -->
<div class="modal fade" id="loadWalletModal" tabindex="-1" aria-labelledby="loadWalletModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header text-white p-4" style="background: linear-gradient(135deg, var(--dark-sidebar) 0%, #1f2937 100%); border-bottom: 3px solid var(--primary-gold); justify-content: space-between;">
                <h5 class="modal-title fw-bold" id="loadWalletModalLabel">
                    <i class="fa-solid fa-coins me-2 text-warning"></i> Load Wallet Funds
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="loadWalletForm" method="POST" action="">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold mb-1">Member Account</label>
                        <input type="text" id="walletMemberName" class="form-control bg-light" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold mb-1">Current Wallet Balance</label>
                        <input type="text" id="walletCurrentBalance" class="form-control bg-light text-success fw-bold" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label fw-semibold text-dark">Amount to Add (₹)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                            <input type="number" step="0.01" name="amount" id="amount" class="form-control" placeholder="0.00" min="0.01" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="remark" class="form-label fw-semibold text-dark">Transaction Remark</label>
                        <input type="text" name="remark" id="remark" class="form-control" placeholder="e.g. Approved loading bonus" required>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3 border-0 justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn fw-semibold text-white px-4" style="background: linear-gradient(45deg, var(--primary-pink), var(--primary-gold)); border: none; border-radius: 6px; box-shadow: 0 4px 10px rgba(236, 64, 122, 0.15);">
                        Credit Wallet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- AJAX Swapper and Modal Bind Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const resetBtn = document.getElementById('resetBtn');
    let debounceTimer;

    function loadTable(page = 1) {
        const search = searchInput.value;
        const status = statusFilter.value;
        const url = new URL(window.location.href);

        url.searchParams.set('search', search);
        url.searchParams.set('status', status);
        url.searchParams.set('page', page);

        window.history.pushState({}, '', url.toString());

        fetch(url.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newTable = doc.getElementById('table-container');
            if (newTable) {
                document.getElementById('table-container').innerHTML = newTable.innerHTML;
            }
        })
        .catch(err => console.error('Error fetching table data: ', err));
    }

    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            loadTable(1);
        }, 300);
    });

    statusFilter.addEventListener('change', function() {
        loadTable(1);
    });

    resetBtn.addEventListener('click', function() {
        searchInput.value = '';
        statusFilter.value = '';
        loadTable(1);
    });

    document.addEventListener('click', function(e) {
        // Handle pagination link clicks
        const pageLink = e.target.closest('#table-container .pagination .page-link');
        if (pageLink) {
            e.preventDefault();
            const page = pageLink.getAttribute('data-page');
            if (page) {
                loadTable(page);
            }
        }

        // Handle dynamic wallet load button clicks (Event Delegation)
        const walletBtn = e.target.closest('.load-wallet-btn');
        if (walletBtn) {
            const memberId = walletBtn.getAttribute('data-id');
            const memberName = walletBtn.getAttribute('data-name');
            const memberBalance = walletBtn.getAttribute('data-balance');

            document.getElementById('walletMemberName').value = memberName;
            document.getElementById('walletCurrentBalance').value = memberBalance;
            document.getElementById('loadWalletForm').action = "<?php echo base_url('admin/members/wallet/'); ?>" + memberId;
        }
    });
});
</script>
