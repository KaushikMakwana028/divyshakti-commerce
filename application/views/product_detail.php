<style>
    /* NOTE: intentionally NOT redefining --primary-pink/--primary-gold/--dark-sidebar on :root here —
       doing so as a self-reference (var(--primary-pink, ...) inside --primary-pink itself) is an
       invalid circular reference and silently kills the variable everywhere on the page.
       We just reference them with a fallback wherever they're used instead. */

    .ep-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
    }

    .ep-header h3 {
        color: var(--dark-sidebar, #1f2937);
        font-weight: 700;
        margin-bottom: 2px;
    }

    .ep-header p {
        color: #6b7280;
        margin-bottom: 0;
        font-size: .92rem;
    }

    .ep-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 999px;
        font-size: .78rem;
        font-weight: 600;
    }

    .ep-status-pill.active {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf3d0;
    }

    .ep-status-pill.inactive {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    .ep-card {
        position: relative;
        background: #fff;
        border: 1px solid #eef0f3;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(17, 24, 39, .04);
        overflow: hidden;
    }

    .ep-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--primary-pink, #ec407a), var(--primary-gold, #d4af37));
    }

    .ep-card-head {
        padding: 18px 22px;
        border-bottom: 1px solid #f1f2f4;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        background: linear-gradient(135deg, rgba(236, 64, 122, .05), rgba(212, 175, 55, .06));
    }

    .ep-card-head h6 {
        margin: 0;
        font-weight: 700;
        color: var(--dark-sidebar, #1f2937);
        font-size: .98rem;
    }

    .ep-card-head .sub {
        font-size: .78rem;
        color: #9ca3af;
        margin-top: 2px;
    }

    .ep-card-body {
        padding: 22px;
    }

    .ep-icon-badge {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary-pink, #ec407a), var(--primary-gold, #d4af37));
        color: #fff;
        font-size: .85rem;
        flex-shrink: 0;
    }

    .ep-category-tag {
        display: inline-block;
        background: var(--primary-pink, #ec407a);
        color: #fff;
        text-transform: uppercase;
        font-size: .68rem;
        font-weight: 700;
        letter-spacing: .02em;
        padding: 4px 10px;
        border-radius: 999px;
        margin-bottom: 6px;
    }

    /* Main image */
    .ep-main-image {
        position: relative;
        width: 100%;
        aspect-ratio: 1/1;
        max-width: 220px;
        border-radius: 14px;
        overflow: hidden;
        background: linear-gradient(135deg, #fdf6e3, #fdf0f5);
        border: 2px solid var(--primary-gold, #d4af37);
        box-shadow: 0 4px 14px rgba(212, 175, 55, .18);
        margin: 0 auto;
    }

    .ep-main-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    /* Info rows */
    .ep-info-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .ep-info-row {
        display: flex;
        gap: 14px;
        align-items: flex-start;
        padding-bottom: 14px;
        border-bottom: 1px solid #f1f2f4;
    }

    .ep-info-row:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .ep-info-label {
        width: 140px;
        flex-shrink: 0;
        font-weight: 600;
        font-size: .82rem;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: .02em;
        padding-top: 2px;
    }

    .ep-info-value {
        flex: 1;
        color: #1f2937;
        font-size: .95rem;
    }

    .ep-price-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--dark-sidebar, #1f2937);
    }

    .ep-badge-stock {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 999px;
        font-size: .78rem;
        font-weight: 700;
    }

    .ep-badge-stock.in {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf3d0;
    }

    .ep-badge-stock.out {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    .ep-description-box {
        background: #fffaf5;
        border-left: 3px solid var(--primary-pink, #ec407a);
        border-radius: 10px;
        padding: 14px 16px;
        font-size: .92rem;
        line-height: 1.6;
        color: #374151;
    }

    .ep-description-box.empty {
        color: #9ca3af;
        font-style: italic;
    }

    .ep-timestamp {
        font-size: .82rem;
        color: #6b7280;
        line-height: 1.6;
    }

    .ep-timestamp strong {
        color: #374151;
    }

    /* Gallery grid */
    .ep-gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
        gap: 14px;
    }

    .ep-gallery-item {
        border: 1px solid #eef0f3;
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
    }

    .ep-gallery-item.is-default {
        border-color: var(--primary-gold, #d4af37);
        box-shadow: 0 0 0 1px var(--primary-gold, #d4af37), 0 4px 12px rgba(212, 175, 55, .15);
    }

    .ep-gallery-thumb {
        position: relative;
        aspect-ratio: 1/1;
        background: linear-gradient(135deg, #fdf6e3, #fdf0f5);
    }

    .ep-gallery-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .ep-gallery-badge {
        position: absolute;
        top: 7px;
        left: 7px;
        font-size: .64rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 999px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .ep-gallery-badge.default {
        background: var(--primary-gold, #d4af37);
        color: #111;
    }

    .ep-gallery-badge.secondary {
        background: rgba(236, 64, 122, .82);
        color: #fff;
    }

    .ep-gallery-view {
        position: absolute;
        bottom: 7px;
        right: 7px;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .9);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #111;
        font-size: .68rem;
        text-decoration: none;
    }

    .ep-empty-gallery {
        text-align: center;
        padding: 28px 10px;
        color: #9ca3af;
        background: linear-gradient(135deg, #fdf6e3, #fdf0f5);
        border-radius: 12px;
        font-size: .88rem;
    }

    .ep-btn-edit {
        background: linear-gradient(45deg, var(--primary-pink, #ec407a), var(--primary-gold, #d4af37));
        border: none;
        color: #fff;
        font-weight: 600;
        border-radius: 10px;
        padding: 10px 22px;
        box-shadow: 0 4px 10px rgba(236, 64, 122, .18);
    }

    .ep-btn-back {
        border-radius: 10px;
        padding: 9px 18px;
        font-weight: 600;
    }

    .ep-btn-manage {
        border-radius: 8px;
        font-size: .78rem;
        padding: 6px 12px;
        font-weight: 600;
    }

    @media (max-width: 767px) {
        .ep-info-row {
            flex-direction: column;
            gap: 4px;
        }

        .ep-info-label {
            width: auto;
        }
    }
</style>

<div class="ep-header">
    <div>
        <h3>Product Details</h3>
        <p>Detailed view of the selected product properties.</p>
    </div>
    <a href="<?php echo base_url('admin/products'); ?>" class="btn ep-btn-back btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-2"></i> Back to Products
    </a>
</div>

<div class="row g-3">
    <!-- Image column -->
    <div class="col-lg-4">
        <div class="ep-card h-100">
            <div class="ep-card-head">
                <div class="d-flex align-items-center gap-2">
                    <span class="ep-icon-badge"><i class="fa-solid fa-image"></i></span>
                    <div>
                        <h6>Default Image</h6>
                        <div class="sub">Main product photo</div>
                    </div>
                </div>
                <span class="ep-status-pill <?php echo (int)$product->status === 1 ? 'active' : 'inactive'; ?>">
                    <i class="fa-solid <?php echo (int)$product->status === 1 ? 'fa-circle-check' : 'fa-circle-xmark'; ?>"></i>
                    <?php echo (int)$product->status === 1 ? 'Active' : 'Inactive'; ?>
                </span>
            </div>
            <div class="ep-card-body text-center">
                <div class="ep-main-image">
                    <img src="<?php echo $product->image ? base_url($product->image) : 'https://placehold.co/220x220/1f2937/d4af37?text=' . urlencode(substr($product->name, 0, 1)); ?>" alt="<?php echo htmlspecialchars($product->name); ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Details column -->
    <div class="col-lg-8">
        <div class="ep-card">
            <div class="ep-card-head">
                <div class="d-flex align-items-center gap-2">
                    <span class="ep-icon-badge"><i class="fa-solid fa-box-open"></i></span>
                    <div>
                        <h6><?php echo htmlspecialchars($product->name); ?></h6>
                        <div class="sub">Product #<?php echo htmlspecialchars($product->id); ?></div>
                    </div>
                </div>
                <span class="ep-category-tag"><?php echo htmlspecialchars($product->category_name ?: 'Uncategorized'); ?></span>
            </div>
            <div class="ep-card-body">
                <div class="ep-info-list">
                    <div class="ep-info-row">
                        <div class="ep-info-label">Pricing</div>
                        <div class="ep-info-value">
                            <span class="ep-price-value">₹<?php echo number_format($product->price, 2); ?></span>
                        </div>
                    </div>

                    <div class="ep-info-row">
                        <div class="ep-info-label">Stock Level</div>
                        <div class="ep-info-value">
                            <?php if ((int)$product->stock > 0): ?>
                                <span class="ep-badge-stock in">
                                    <i class="fa-solid fa-cubes"></i> In Stock (<?php echo htmlspecialchars($product->stock); ?> items)
                                </span>
                            <?php else: ?>
                                <span class="ep-badge-stock out">
                                    <i class="fa-solid fa-triangle-exclamation"></i> Out of Stock
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="ep-info-row">
                        <div class="ep-info-label">Description</div>
                        <div class="ep-info-value">
                            <?php if (!empty($product->description)): ?>
                                <div class="ep-description-box"><?php echo nl2br(htmlspecialchars($product->description)); ?></div>
                            <?php else: ?>
                                <div class="ep-description-box empty">No description provided for this product.</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="ep-info-row">
                        <div class="ep-info-label">System Timestamps</div>
                        <div class="ep-info-value ep-timestamp">
                            <strong>Created:</strong> <?php echo date('M d, Y h:i A', strtotime($product->created_at)); ?>
                            <br>
                            <strong>Last Updated:</strong> <?php echo date('M d, Y h:i A', strtotime($product->updated_at)); ?>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-end mt-3">
                    <a href="<?php echo base_url('admin/products/edit/' . $product->id); ?>" class="btn ep-btn-edit">
                        <i class="fa-solid fa-pen-to-square me-2"></i> Edit Product
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Gallery -->
<div class="ep-card mt-3">
    <div class="ep-card-head">
        <div class="d-flex align-items-center gap-2">
            <span class="ep-icon-badge"><i class="fa-solid fa-photo-film"></i></span>
            <div>
                <h6>Product Gallery</h6>
                <div class="sub">Secondary photos for this product</div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge rounded-pill px-3 py-2" style="background:linear-gradient(45deg, var(--primary-pink, #ec407a), var(--primary-gold, #d4af37)); color:#fff;"><?php echo !empty($gallery) ? count($gallery) : 0; ?> Photos</span>
            <a href="<?php echo base_url('admin/products/edit/' . $product->id); ?>" class="btn btn-outline-primary ep-btn-manage">
                <i class="fa-solid fa-plus me-1"></i> Manage Gallery
            </a>
        </div>
    </div>
    <div class="ep-card-body">
        <?php if (empty($gallery)): ?>
            <div class="ep-empty-gallery">
                No additional gallery images uploaded for this product.
            </div>
        <?php else: ?>
            <div class="ep-gallery-grid">
                <?php foreach ($gallery as $g): ?>
                    <div class="ep-gallery-item <?php echo ((int)$g->is_default === 1) ? 'is-default' : ''; ?>">
                        <div class="ep-gallery-thumb">
                            <img src="<?php echo base_url($g->image); ?>" alt="Product Gallery">
                            <?php if ((int)$g->is_default === 1): ?>
                                <span class="ep-gallery-badge default"><i class="fa-solid fa-star"></i> Default</span>
                            <?php else: ?>
                                <span class="ep-gallery-badge secondary"><i class="fa-regular fa-image"></i> Secondary</span>
                            <?php endif; ?>
                            <a href="<?php echo base_url($g->image); ?>" target="_blank" class="ep-gallery-view" title="View Fullscreen">
                                <i class="fa-solid fa-magnifying-glass-plus"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>