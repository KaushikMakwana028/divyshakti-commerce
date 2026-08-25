<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h3 class="fw-bold" style="color: var(--dark-sidebar);">Product Details</h3>
        <p class="text-muted">Detailed view of the selected product properties.</p>
    </div>
    <div class="col-sm-6 text-sm-end">
        <a href="<?php echo base_url('admin/products'); ?>" class="btn btn-secondary px-4 py-2" style="border-radius: 8px;">
            <i class="fa-solid fa-arrow-left me-2"></i> Back to Products
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
            <div class="p-4 text-white" style="background: linear-gradient(135deg, var(--dark-sidebar) 0%, #1f2937 100%); border-bottom: 3px solid var(--primary-gold);">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="badge mb-2 text-uppercase" style="background-color: var(--primary-pink); font-size: 0.75rem; font-weight: 600; padding: 5px 10px;">
                            <?php echo htmlspecialchars($product->category_name ?: 'Uncategorized'); ?>
                        </span>
                        <h5 class="fw-bold mb-0"><?php echo htmlspecialchars($product->name); ?></h5>
                    </div>
                    <div>
                        <?php if ((int)$product->status === 1): ?>
                            <span class="badge bg-success px-3 py-2 border border-success-subtle" style="border-radius: 50px; font-size: 0.8rem;">
                                <i class="fa-solid fa-circle-check me-1"></i> Active
                            </span>
                        <?php else: ?>
                            <span class="badge bg-danger px-3 py-2 border border-danger-subtle" style="border-radius: 50px; font-size: 0.8rem;">
                                <i class="fa-solid fa-circle-xmark me-1"></i> Inactive
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="row">
                    <!-- Product Image Display (Left) -->
                    <div class="col-md-4 text-center mb-4 mb-md-0 border-end pe-md-4">
                        <div class="d-inline-block shadow-sm rounded border" style="overflow: hidden; width: 100%; max-width: 220px; aspect-ratio: 1; border: 3px solid var(--primary-gold) !important; border-radius: 16px !important; background-color: #f9fafb;">
                            <img src="<?php echo $product->image ? base_url($product->image) : 'https://placehold.co/220x220/1f2937/d4af37?text=' . urlencode(substr($product->name, 0, 1)); ?>" 
                                 alt="<?php echo htmlspecialchars($product->name); ?>" 
                                 class="img-fluid w-100 h-100" 
                                 style="object-fit: cover;">
                        </div>
                    </div>

                    <!-- Product Details Table / List (Right) -->
                    <div class="col-md-8 ps-md-4">
                        <table class="table table-borderless align-middle mb-4">
                            <tbody>
                                <tr>
                                    <td class="fw-semibold text-muted" style="width: 150px;">Product ID</td>
                                    <td class="fw-bold text-dark">#<?php echo htmlspecialchars($product->id); ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted">URL Slug</td>
                                    <td>
                                        <code style="color: var(--primary-pink); background-color: rgba(236, 64, 122, 0.05); padding: 3px 8px; border-radius: 4px; font-size: 0.9rem;">
                                            <?php echo htmlspecialchars($product->slug); ?>
                                        </code>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted">Pricing</td>
                                    <td>
                                        <span class="fs-4 fw-bold" style="color: var(--dark-sidebar);">₹<?php echo number_format($product->price, 2); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted">Stock Level</td>
                                    <td>
                                        <?php if ((int)$product->stock > 0): ?>
                                            <span class="badge bg-success-subtle text-success px-3 py-1.5 border border-success-subtle" style="font-size: 0.8rem; border-radius: 4px;">
                                                <i class="fa-solid fa-cubes me-1"></i> In Stock (<?php echo htmlspecialchars($product->stock); ?> items)
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger px-3 py-1.5 text-white" style="font-size: 0.8rem; border-radius: 4px;">
                                                <i class="fa-solid fa-triangle-exclamation me-1"></i> Out of Stock
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted" valign="top">Description</td>
                                    <td class="text-dark bg-light p-3 rounded" style="font-size: 0.95rem; line-height: 1.6; border-left: 3px solid var(--primary-pink);">
                                        <?php echo !empty($product->description) ? nl2br(htmlspecialchars($product->description)) : '<span class="text-muted">No description provided for this product.</span>'; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted">System Timestamps</td>
                                    <td class="text-muted small">
                                        <strong>Created:</strong> <?php echo date('M d, Y h:i A', strtotime($product->created_at)); ?>
                                        <br>
                                        <strong>Last Updated:</strong> <?php echo date('M d, Y h:i A', strtotime($product->updated_at)); ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="d-flex align-items-center justify-content-end">
                            <a href="<?php echo base_url('admin/products/edit/' . $product->id); ?>" class="btn px-4 py-2 fw-semibold text-white" style="background: linear-gradient(45deg, var(--primary-pink), var(--primary-gold)); border: none; border-radius: 8px; box-shadow: 0 4px 10px rgba(236, 64, 122, 0.15);">
                                <i class="fa-solid fa-pen-to-square me-2"></i> Edit Product
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
