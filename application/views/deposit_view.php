<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h3 class="fw-bold" style="color: var(--dark-sidebar);">Wallet Deposit Requests</h3>
        <p class="text-muted">Moderate deposit requests and verify payment proof receipts.</p>
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
                            <input type="text" name="search" id="searchInput" class="form-control border-start-0" placeholder="Search by member name or email..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="status" id="statusFilter" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="pending" <?php echo ($status === 'pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="approved" <?php echo ($status === 'approved') ? 'selected' : ''; ?>>Approved</option>
                            <option value="rejected" <?php echo ($status === 'rejected') ? 'selected' : ''; ?>>Rejected</option>
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

<!-- Deposit Requests Table -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
            <div id="table-container">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="text-white" style="background-color: var(--dark-sidebar); border-bottom: 3px solid var(--primary-gold);">
                            <tr>
                                <th class="ps-4" style="width: 100px;">Index</th>
                                <th>Member</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Payment Proof</th>
                                <th>User Remark</th>
                                <th>Status</th>
                                <th>Requested At</th>
                                <th class="text-end pe-4" style="width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($requests)): ?>
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-receipt d-block fs-1 mb-3 text-secondary"></i>
                                        No deposit requests found matching the filters.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php 
                                $index_num = ($current_page - 1) * 10 + 1;
                                foreach ($requests as $req): 
                                ?>
                                    <tr>
                                        <td class="ps-4 fw-semibold text-muted">#<?php echo $index_num++; ?></td>
                                        <td>
                                            <div class="fw-semibold text-dark"><?php echo htmlspecialchars($req->user_name); ?></div>
                                            <span class="text-muted small"><?php echo htmlspecialchars($req->user_email); ?></span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success fs-6">₹<?php echo number_format($req->amount, 2); ?></span>
                                        </td>
                                        <td>
                                            <?php if ($req->payment_method === 'online'): ?>
                                                <span class="badge bg-info-subtle text-info px-2.5 py-1 border border-info-subtle" style="font-size: 0.75rem;">Online Transfer</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary-subtle text-secondary px-2.5 py-1 border border-secondary-subtle" style="font-size: 0.75rem;">Cash</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($req->payment_method === 'online' && $req->proof_file): ?>
                                                <?php 
                                                $ext = strtolower(pathinfo($req->proof_file, PATHINFO_EXTENSION));
                                                if ($ext === 'pdf'): 
                                                ?>
                                                    <a href="<?php echo base_url($req->proof_file); ?>" target="_blank" class="btn btn-xs btn-outline-danger px-2 py-1" style="font-size: 0.75rem; border-radius: 6px;">
                                                        <i class="fa-solid fa-file-pdf me-1"></i> Download PDF
                                                    </a>
                                                <?php else: ?>
                                                    <div class="position-relative d-inline-block rounded border shadow-sm cursor-zoom-in" style="width: 50px; height: 50px; overflow: hidden;" onclick="viewReceipt('<?php echo base_url($req->proof_file); ?>')">
                                                        <img src="<?php echo base_url($req->proof_file); ?>" alt="Proof Thumbnail" class="w-100 h-100" style="object-fit: cover;">
                                                    </div>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-muted small italic">N/A (Cash)</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="text-dark small"><?php echo htmlspecialchars($req->remark ?: '-'); ?></span>
                                        </td>
                                        <td>
                                            <?php if ($req->status === 'approved'): ?>
                                                <span class="badge bg-success-subtle text-success px-2.5 py-1 border border-success-subtle" style="border-radius: 4px; font-size: 0.75rem;">Approved</span>
                                            <?php elseif ($req->status === 'rejected'): ?>
                                                <span class="badge bg-danger-subtle text-danger px-2.5 py-1 border border-danger-subtle" style="border-radius: 4px; font-size: 0.75rem;">Rejected</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning-subtle text-warning px-2.5 py-1 border border-warning-subtle" style="border-radius: 4px; font-size: 0.75rem;">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-muted small"><?php echo date('M d, Y H:i', strtotime($req->created_at)); ?></td>
                                        <td class="text-end pe-4">
                                            <?php if ($req->status === 'pending'): ?>
                                                <div class="d-inline-flex gap-2">
                                                    <form action="<?php echo base_url('admin/deposits/approve/' . $req->id); ?>" method="POST" class="approve-form">
                                                        <button type="button" class="btn btn-sm btn-outline-success action-btn px-2.5 py-1.5" data-action="approve" style="border-radius: 6px;" title="Approve Request">
                                                            <i class="fa-solid fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <form action="<?php echo base_url('admin/deposits/reject/' . $req->id); ?>" method="POST" class="reject-form">
                                                        <button type="button" class="btn btn-sm btn-outline-danger action-btn px-2.5 py-1.5" data-action="reject" style="border-radius: 6px;" title="Reject Request">
                                                            <i class="fa-solid fa-xmark"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted small">-</span>
                                            <?php endif; ?>
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
                        <nav aria-label="Deposit Request Page Navigation">
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

<!-- AJAX Swapper and Event Delegation Binds -->
<script>
function viewReceipt(url) {
    Swal.fire({
        imageUrl: url,
        imageAlt: 'Receipt Payment Proof',
        showCloseButton: true,
        showConfirmButton: false,
        width: 'auto',
        maxWidth: '90%'
    });
}

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

        // Handle approve/reject form submissions (Event Delegation)
        const actionBtn = e.target.closest('.action-btn');
        if (actionBtn) {
            e.preventDefault();
            const form = actionBtn.closest('form');
            const action = actionBtn.getAttribute('data-action');

            let titleStr = "Approve Deposit Request?";
            let textStr = "This will credit the requested amount to the member wallet and cannot be undone.";
            let confirmBtnColor = "#198754";

            if (action === 'reject') {
                titleStr = "Reject Deposit Request?";
                textStr = "This request will be marked as rejected. No money will be loaded.";
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
                    form.submit();
                }
            });
        }
    });
});
</script>
