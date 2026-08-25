<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h3 class="fw-bold" style="color: var(--dark-sidebar);">Products</h3>
        <p class="text-muted">Manage items, stock counts, and prices for your system.</p>
    </div>
    <div class="col-sm-6 text-sm-end">
        <a href="<?php echo base_url('admin/products/add'); ?>" class="btn fw-semibold text-white px-4 py-2" style="background: linear-gradient(45deg, var(--primary-pink), var(--primary-gold)); border: none; border-radius: 8px; box-shadow: 0 4px 10px rgba(236, 64, 122, 0.15);">
            <i class="fa-solid fa-plus me-2"></i> Add New Product
        </a>
    </div>
</div>

<!-- Search and Filter Form -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 8px;">
            <div class="card-body p-3">
                <form id="filterForm" class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                            <input type="text" name="search" id="searchInput" class="form-control border-start-0" placeholder="Search by name..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="category_id" id="categoryFilter" class="form-select">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat->id; ?>" <?php echo ($category_id == $cat->id) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
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

<!-- Dynamic Table Container -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
            <div id="table-container">
                <div class="table-responsive">
                    <table class="table align-middle mb-0" style="min-width: 900px;">
                        <thead class="table-dark" style="background-color: var(--dark-sidebar); border-bottom: 3px solid var(--primary-gold);">
                            <tr>
                                <th class="py-3 ps-4" style="width: 100px;">Index</th>
                                <th class="py-3" style="width: 100px;">Image</th>
                                <th class="py-3">Product Name</th>
                                <th class="py-3">Category</th>
                                <th class="py-3">Price</th>
                                <th class="py-3">Stock</th>
                                <th class="py-3" style="width: 130px;">Status</th>
                                <th class="py-3 text-center" style="width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($products)): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-boxes-stacked fs-2 mb-3 d-block text-muted"></i>
                                        No products found.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php 
                                $index_num = ($current_page - 1) * 10 + 1;
                                foreach ($products as $prod): 
                                ?>
                                    <tr class="border-bottom">
                                        <td class="ps-4 fw-semibold text-muted">#<?php echo $index_num++; ?></td>
                                        <td>
                                            <img src="<?php echo $prod->image ? base_url($prod->image) : 'https://placehold.co/80x80/1f2937/d4af37?text=' . urlencode(substr($prod->name, 0, 1)); ?>" 
                                                 alt="<?php echo htmlspecialchars($prod->name); ?>" 
                                                 class="rounded border" 
                                                 style="width: 50px; height: 50px; object-fit: cover; border: 2px solid var(--primary-gold) !important;">
                                        </td>
                                        <td>
                                            <h6 class="fw-bold m-0" style="color: var(--dark-sidebar);"><?php echo htmlspecialchars($prod->name); ?></h6>
                                            <span class="text-muted small"><?php echo htmlspecialchars($prod->slug); ?></span>
                                        </td>
                                        <td>
                                            <span class="badge" style="background-color: rgba(236, 64, 122, 0.1); color: var(--primary-pink); font-size: 0.75rem;">
                                                <?php echo htmlspecialchars($prod->category_name ?: 'Uncategorized'); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-bold" style="color: var(--dark-sidebar);">₹<?php echo number_format($prod->price, 2); ?></span>
                                        </td>
                                        <td>
                                            <?php if ((int)$prod->stock > 0): ?>
                                                <span class="fw-bold text-dark"><?php echo htmlspecialchars($prod->stock); ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-danger px-2.5 py-1 text-white" style="font-size: 0.7rem; font-weight: 600;">Out of Stock</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ((int)$prod->status === 1): ?>
                                                <span class="badge bg-success-subtle text-success px-3 py-2 border border-success-subtle" style="border-radius: 50px; font-size: 0.75rem;">
                                                    <i class="fa-solid fa-circle-check me-1"></i> Active
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-danger-subtle text-danger px-3 py-2 border border-danger-subtle" style="border-radius: 50px; font-size: 0.75rem;">
                                                    <i class="fa-solid fa-circle-xmark me-1"></i> Inactive
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?php echo base_url('admin/products/detail/' . $prod->id); ?>" class="btn btn-sm btn-outline-info px-2 py-1 me-1" title="View Details" style="border-radius: 6px;">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="<?php echo base_url('admin/products/edit/' . $prod->id); ?>" class="btn btn-sm btn-outline-warning px-2 py-1 me-1" title="Edit Product" style="border-radius: 6px; border-color: var(--primary-gold); color: var(--primary-gold);">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a href="<?php echo base_url('admin/products/delete/' . $prod->id); ?>" class="btn btn-sm btn-outline-danger px-2 py-1 btn-delete-product" title="Delete Product" style="border-radius: 6px;">
                                                <i class="fa-solid fa-trash"></i>
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
                        <nav aria-label="Product Page Navigation">
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

<!-- AJAX Swapper and Action Binds -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const statusFilter = document.getElementById('statusFilter');
    const resetBtn = document.getElementById('resetBtn');
    let debounceTimer;

    function loadTable(page = 1) {
        const search = searchInput.value;
        const category_id = categoryFilter.value;
        const status = statusFilter.value;
        const url = new URL(window.location.href);

        url.searchParams.set('search', search);
        url.searchParams.set('category_id', category_id);
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
                bindActionListeners();
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

    categoryFilter.addEventListener('change', function() {
        loadTable(1);
    });

    statusFilter.addEventListener('change', function() {
        loadTable(1);
    });

    resetBtn.addEventListener('click', function() {
        searchInput.value = '';
        categoryFilter.value = '';
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

    function bindActionListeners() {
        const deleteButtons = document.querySelectorAll('.btn-delete-product');
        deleteButtons.forEach(button => {
            const newBtn = button.cloneNode(true);
            button.parentNode.replaceChild(newBtn, button);

            newBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const deleteUrl = this.getAttribute('href');
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to delete this product catalog item!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ec407a',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = deleteUrl;
                    }
                });
            });
        });
    }

    bindActionListeners();
});
</script>
