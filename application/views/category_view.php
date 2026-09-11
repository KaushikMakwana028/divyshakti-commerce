<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h3 class="fw-bold" style="color: var(--dark-sidebar);">Categories</h3>
        <p class="text-muted">Manage product and item categories for your system.</p>
    </div>
    <div class="col-sm-6 text-sm-end">
        <a href="<?php echo base_url('admin/categories/add'); ?>" class="btn fw-semibold text-white px-4 py-2" style="background: linear-gradient(45deg, var(--primary-pink), var(--primary-gold)); border: none; border-radius: 8px; box-shadow: 0 4px 10px rgba(236, 64, 122, 0.15);">
            <i class="fa-solid fa-plus me-2"></i> Add New Category
        </a>
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
                            <input type="text" name="search" id="searchInput" class="form-control border-start-0" placeholder="Search categories by name..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
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

<!-- Dynamic Table and Pagination Container -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
            <div id="table-container">
                <div class="table-responsive">
                    <table class="table align-middle mb-0" style="min-width: 800px;">
                        <thead class="table-dark" style="background-color: var(--dark-sidebar); border-bottom: 3px solid var(--primary-gold);">
                            <tr>
                                <th class="py-3 ps-4" style="width: 100px;">#</th>
                                <th class="py-3" style="width: 100px;">Image</th>
                                <th class="py-3">Category Name</th>
                                <th class="py-3" style="width: 150px;">Status</th>
                                <th class="py-3" style="width: 180px;">Created At</th>
                                <th class="py-3 text-center" style="width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($categories)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fa-regular fa-folder-open fs-2 mb-3 d-block text-muted"></i>
                                        No categories found.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php 
                                $index_num = ($current_page - 1) * 10 + 1;
                                foreach ($categories as $cat): 
                                ?>
                                    <tr class="border-bottom">
                                        <td class="ps-4 fw-semibold text-muted"><?php echo $index_num++; ?></td>
                                        <td>
                                            <img src="<?php echo $cat->image ? base_url($cat->image) : 'https://placehold.co/80x80/1f2937/d4af37?text=' . urlencode(substr($cat->name, 0, 1)); ?>" 
                                                 alt="<?php echo htmlspecialchars($cat->name); ?>" 
                                                 class="rounded border" 
                                                 style="width: 50px; height: 50px; object-fit: cover; border: 2px solid var(--primary-gold) !important;">
                                        </td>
                                        <td>
                                            <h6 class="fw-bold m-0" style="color: var(--dark-sidebar);"><?php echo htmlspecialchars($cat->name); ?></h6>
                                        </td>
                                        <td>
                                            <?php if ((int)$cat->status === 1): ?>
                                                <span class="badge bg-success-subtle text-success px-3 py-2 border border-success-subtle" style="border-radius: 50px; font-size: 0.75rem;">
                                                    <i class="fa-solid fa-circle-check me-1"></i> Active
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-danger-subtle text-danger px-3 py-2 border border-danger-subtle" style="border-radius: 50px; font-size: 0.75rem;">
                                                    <i class="fa-solid fa-circle-xmark me-1"></i> Inactive
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-muted small">
                                            <?php echo date('M d, Y h:i A', strtotime($cat->created_at)); ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?php echo base_url('admin/categories/edit/' . $cat->id); ?>" class="btn btn-sm btn-outline-warning px-2 py-1 me-1" title="Edit Category" style="border-radius: 6px; border-color: var(--primary-gold); color: var(--primary-gold);">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a href="<?php echo base_url('admin/categories/delete/' . $cat->id); ?>" class="btn btn-sm btn-outline-danger px-2 py-1 btn-delete-category" title="Delete Category" style="border-radius: 6px;">
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
                <?php if (!empty($total_rows) && $total_rows > 0): 
                    $start_record = ($current_page - 1) * 10 + 1;
                    $end_record = min($current_page * 10, $total_rows);
                ?>
                    <div class="card-footer bg-white border-top p-3 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                        <div class="text-muted small">
                            Showing <strong><?php echo $start_record; ?></strong> to <strong><?php echo $end_record; ?></strong> of <strong><?php echo number_format($total_rows); ?></strong> available categories
                        </div>
                        <?php if ($total_pages > 1): ?>
                        <nav aria-label="Category Page Navigation">
                            <ul class="pagination pagination-sm justify-content-center mb-0">
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
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- AJAX and Action Listeners -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const resetBtn = document.getElementById('resetBtn');
    let debounceTimer;

    // Fetch dynamic category list
    function loadTable(page = 1) {
        const search = searchInput.value;
        const status = statusFilter.value;
        const url = new URL(window.location.href);

        url.searchParams.set('search', search);
        url.searchParams.set('status', status);
        url.searchParams.set('page', page);

        // Update address bar
        window.history.pushState({}, '', url.toString());

        // Perform request
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
                bindActionListeners(); // Rebind SweetAlert triggers
            }
        })
        .catch(err => console.error('Error fetching table data: ', err));
    }

    // Debounce search
    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            loadTable(1);
        }, 300);
    });

    // Dropdown change
    statusFilter.addEventListener('change', function() {
        loadTable(1);
    });

    // Reset button
    resetBtn.addEventListener('click', function() {
        searchInput.value = '';
        statusFilter.value = '';
        loadTable(1);
    });

    // Handle pagination click events (via event delegation)
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

    // SweetAlert prompt bindings
    function bindActionListeners() {
        const deleteButtons = document.querySelectorAll('.btn-delete-category');
        deleteButtons.forEach(button => {
            // Remove old clones to prevent duplicates
            const newBtn = button.cloneNode(true);
            button.parentNode.replaceChild(newBtn, button);

            newBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const deleteUrl = this.getAttribute('href');
                
                dsConfirm({
                    title: 'Delete Category?',
                    text: 'All items linked to this category might be affected. This action cannot be undone!',
                    icon: 'warning',
                    confirmText: 'Yes, Delete',
                    cancelText: 'Cancel',
                    isDangerous: true,
                    onConfirm: function() {
                        window.location.href = deleteUrl;
                    }
                });
            });
        });
    }

    // Run binds initially
    bindActionListeners();
});
</script>
