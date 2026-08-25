<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h3 class="fw-bold" style="color: var(--dark-sidebar);">Customer Orders</h3>
        <p class="text-muted">Monitor e-commerce ordering flows, payments, and MLM referrals.</p>
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
                            <input type="text" name="search" id="searchInput" class="form-control border-start-0" placeholder="Search by customer name or product name..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="status" id="statusFilter" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="pending" <?php echo ($status === 'pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="completed" <?php echo ($status === 'completed') ? 'selected' : ''; ?>>Completed</option>
                            <option value="cancelled" <?php echo ($status === 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
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

<!-- Order Table -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
            <div id="table-container">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="text-white" style="background-color: var(--dark-sidebar); border-bottom: 3px solid var(--primary-gold);">
                            <tr>
                                <th class="ps-4" style="width: 100px;">Index</th>
                                <th>Buyer</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Ordered Date</th>
                                <th class="text-end pe-4" style="width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($orders)): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-cart-flatbed-suitcase d-block fs-1 mb-3 text-secondary"></i>
                                        No orders found.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php 
                                $index_num = ($current_page - 1) * 10 + 1;
                                foreach ($orders as $order): 
                                ?>
                                    <tr>
                                        <td class="ps-4 fw-semibold text-muted">#<?php echo $index_num++; ?></td>
                                        <td>
                                            <div class="fw-semibold text-dark"><?php echo htmlspecialchars($order->buyer_name); ?></div>
                                            <span class="text-muted small"><?php echo htmlspecialchars($order->buyer_email); ?></span>
                                        </td>
                                        <td class="fw-semibold text-dark"><?php echo htmlspecialchars($order->product_name); ?></td>
                                        <td><?php echo htmlspecialchars($order->quantity); ?> units</td>
                                        <td>
                                            <span class="fw-bold text-dark">₹<?php echo number_format($order->amount, 2); ?></span>
                                        </td>
                                        <td>
                                            <?php if ($order->status === 'completed'): ?>
                                                <span class="badge bg-success-subtle text-success px-2.5 py-1 border border-success-subtle" style="border-radius: 4px; font-size: 0.75rem;">
                                                    <i class="fa-solid fa-check me-1"></i> Completed
                                                </span>
                                            <?php elseif ($order->status === 'pending'): ?>
                                                <span class="badge bg-warning-subtle text-warning px-2.5 py-1 border border-warning-subtle" style="border-radius: 4px; font-size: 0.75rem;">
                                                    <i class="fa-solid fa-spinner fa-spin me-1"></i> Pending
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-danger-subtle text-danger px-2.5 py-1 border border-danger-subtle" style="border-radius: 4px; font-size: 0.75rem;">
                                                    <i class="fa-solid fa-xmark me-1"></i> Cancelled
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-muted small"><?php echo date('M d, Y H:i', strtotime($order->created_at)); ?></td>
                                        <td class="text-end pe-4">
                                            <a href="<?php echo base_url('admin/orders/detail/' . $order->id); ?>" class="btn btn-sm btn-outline-primary px-2 py-1" style="border-radius: 6px;" title="View Details">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
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
                        <nav aria-label="Order Page Navigation">
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

<!-- AJAX Swapper Script -->
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
        const pageLink = e.target.closest('#table-container .pagination .page-link');
        if (pageLink) {
            e.preventDefault();
            const page = pageLink.getAttribute('data-page');
            if (page) {
                loadTable(page);
            }
        }
    });
});
</script>
