<style>
    /* NOTE: intentionally NOT redefining --primary-pink/--primary-gold/--dark-sidebar on :root here —
       doing so as a self-reference inside --primary-pink itself is an invalid circular reference. */

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

    /* Main image uploader */
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
        margin: 0 auto 14px;
    }

    .ep-main-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform .2s ease;
    }

    .ep-main-image .ep-default-tag {
        position: absolute;
        top: 8px;
        left: 8px;
        background: var(--primary-gold, #d4af37);
        color: #111;
        font-weight: 700;
        font-size: .68rem;
        padding: 3px 9px;
        border-radius: 999px;
        box-shadow: 0 2px 6px rgba(0,0,0,.15);
        z-index: 2;
    }

    .ep-upload-zone {
        border: 1.5px dashed #e8c9d6;
        border-radius: 12px;
        padding: 14px;
        text-align: center;
        background: #fffaf5;
        cursor: pointer;
        transition: .15s;
    }

    .ep-upload-zone:hover {
        border-color: var(--primary-pink, #ec407a);
        background: #fff5f8;
    }

    .ep-upload-zone i {
        color: var(--primary-pink, #ec407a);
        font-size: 1.2rem;
    }

    .ep-upload-zone .ep-upload-text {
        font-size: .82rem;
        font-weight: 600;
        color: #374151;
        margin-top: 4px;
    }

    .ep-upload-zone .ep-upload-hint {
        font-size: .72rem;
        color: #9ca3af;
        margin-top: 2px;
    }

    .ep-upload-zone input[type=file] {
        display: none;
    }

    .ep-file-chip {
        display: none;
        align-items: center;
        gap: 8px;
        margin-top: 10px;
        background: #fdf0f5;
        border: 1px solid #f6d3e2;
        border-radius: 8px;
        padding: 6px 10px;
        font-size: .78rem;
        color: #374151;
    }

    .ep-file-chip i {
        color: var(--primary-pink, #ec407a);
        font-size: .9rem;
    }

    /* Form fields */
    .ep-field label {
        font-weight: 600;
        font-size: .84rem;
        color: #374151;
        margin-bottom: 6px;
    }

    .ep-field .input-group-text {
        background: #f9fafb;
        border-right: 0;
        color: #9ca3af;
    }

    .ep-field .form-control,
    .ep-field .form-select {
        border-left: 0;
    }

    .ep-field .input-group:focus-within .input-group-text {
        border-color: var(--primary-pink, #ec407a);
        color: var(--primary-pink, #ec407a);
        background: #fdf0f5;
    }

    .ep-field .input-group:focus-within .form-control,
    .ep-field .input-group:focus-within .form-select {
        border-color: var(--primary-pink, #ec407a);
        box-shadow: 0 0 0 3px rgba(236, 64, 122, .1);
    }

    textarea.form-control {
        resize: vertical;
    }

    .ep-status-toggle {
        display: flex;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
        background: #fafafa;
    }

    .ep-status-toggle input {
        display: none;
    }

    .ep-status-toggle label {
        flex: 1;
        text-align: center;
        padding: 9px 10px;
        font-size: .85rem;
        font-weight: 600;
        color: #6b7280;
        cursor: pointer;
        margin: 0;
        transition: .15s;
    }

    .ep-status-toggle input:checked+label.on {
        background: #dcfce7;
        color: #15803d;
        box-shadow: inset 0 -2px 0 #22c55e;
    }

    .ep-status-toggle input:checked+label.off {
        background: #fee2e2;
        color: #b91c1c;
        box-shadow: inset 0 -2px 0 #ef4444;
    }

    /* Gallery Dropzone & Multi-upload Box */
    .ep-multi-dropzone {
        border: 2px dashed #d1d5db;
        border-radius: 14px;
        padding: 26px 18px;
        text-align: center;
        background: linear-gradient(180deg, #fbfcfe 0%, #f7f9fb 100%);
        cursor: pointer;
        transition: all .2s ease;
        position: relative;
    }

    .ep-multi-dropzone:hover,
    .ep-multi-dropzone.dragover {
        border-color: var(--primary-pink, #ec407a);
        background: #fff5f8;
        transform: scale(1.005);
    }

    .ep-multi-dropzone i.drop-icon {
        font-size: 2.2rem;
        color: var(--primary-pink, #ec407a);
        margin-bottom: 8px;
        display: inline-block;
        transition: transform .2s ease;
    }

    .ep-multi-dropzone:hover i.drop-icon,
    .ep-multi-dropzone.dragover i.drop-icon {
        transform: translateY(-3px);
    }

    .ep-multi-dropzone h6 {
        font-weight: 700;
        font-size: .96rem;
        color: #1f2937;
        margin-bottom: 4px;
    }

    .ep-multi-dropzone p {
        font-size: .8rem;
        color: #6b7280;
        margin-bottom: 10px;
    }

    .ep-multi-dropzone .badge-info-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #fdf2f8;
        color: var(--primary-pink, #ec407a);
        font-size: .74rem;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 999px;
        border: 1px solid #fbcfe8;
    }

    /* Live Upload Progress */
    .ep-upload-progress-box {
        display: none;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 14px 18px;
        margin-top: 14px;
        box-shadow: 0 2px 8px rgba(0,0,0,.04);
    }

    .ep-progress-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: .82rem;
    }

    .ep-progress-header span.title {
        font-weight: 600;
        color: #1f2937;
    }

    .ep-progress-header span.pct {
        font-weight: 700;
        color: var(--primary-pink, #ec407a);
    }

    .ep-progress-bar-wrap {
        height: 8px;
        border-radius: 999px;
        background: #f3f4f6;
        overflow: hidden;
    }

    .ep-progress-bar-fill {
        height: 100%;
        width: 0%;
        background: linear-gradient(90deg, var(--primary-pink, #ec407a), var(--primary-gold, #d4af37));
        border-radius: 999px;
        transition: width .25s ease;
    }

    .ep-progress-status {
        font-size: .74rem;
        color: #6b7280;
        margin-top: 6px;
        display: flex;
        justify-content: space-between;
    }

    /* Gallery grid */
    .ep-gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 14px;
        margin-top: 18px;
    }

    .ep-gallery-item {
        border: 1.5px solid #eef0f3;
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
        transition: all .2s ease;
        position: relative;
    }

    .ep-gallery-item:hover {
        box-shadow: 0 6px 16px rgba(0,0,0,.08);
        transform: translateY(-2px);
    }

    .ep-gallery-item.is-default {
        border-color: var(--primary-gold, #d4af37);
        box-shadow: 0 0 0 1.5px var(--primary-gold, #d4af37), 0 4px 14px rgba(212, 175, 55, .25);
    }

    .ep-gallery-thumb {
        position: relative;
        aspect-ratio: 1/1;
        background: linear-gradient(135deg, #fdf6e3, #fdf0f5);
        overflow: hidden;
    }

    .ep-gallery-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform .25s ease;
    }

    .ep-gallery-item:hover .ep-gallery-thumb img {
        transform: scale(1.04);
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
        z-index: 2;
        box-shadow: 0 2px 6px rgba(0,0,0,.15);
    }

    .ep-gallery-badge.default {
        background: var(--primary-gold, #d4af37);
        color: #111;
    }

    .ep-gallery-badge.secondary {
        background: rgba(31, 41, 55, .78);
        color: #fff;
    }

    .ep-gallery-view {
        position: absolute;
        top: 7px;
        right: 7px;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .92);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #111;
        font-size: .68rem;
        text-decoration: none;
        z-index: 2;
        transition: all .15s ease;
    }

    .ep-gallery-view:hover {
        background: #fff;
        transform: scale(1.1);
        color: var(--primary-pink, #ec407a);
    }

    .ep-gallery-actions {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 8px;
        border-top: 1px solid #f1f2f4;
        background: #fff;
    }

    .ep-gallery-actions .btn {
        font-size: .72rem;
        padding: 5px 8px;
        border-radius: 7px;
        font-weight: 600;
    }

    .ep-gallery-actions .ep-active-label {
        font-size: .72rem;
        font-weight: 700;
        color: #15803d;
        display: flex;
        align-items: center;
        gap: 4px;
        flex-grow: 1;
        padding: 4px 6px;
        background: #dcfce7;
        border-radius: 6px;
        justify-content: center;
    }

    .ep-empty-gallery {
        text-align: center;
        padding: 34px 10px;
        color: #9ca3af;
        background: linear-gradient(135deg, #fdf6e3, #fdf0f5);
        border-radius: 12px;
        margin-top: 18px;
    }

    .ep-empty-gallery i {
        font-size: 2.2rem;
        margin-bottom: 10px;
        display: block;
        color: var(--primary-pink, #ec407a);
        opacity: .6;
    }

    /* Sticky action bar */
    .ep-action-bar {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
    }

    .ep-btn-save {
        background: linear-gradient(45deg, var(--primary-pink, #ec407a), var(--primary-gold, #d4af37));
        border: none;
        color: #fff;
        font-weight: 600;
        border-radius: 10px;
        padding: 10px 22px;
        box-shadow: 0 4px 10px rgba(236, 64, 122, .18);
        transition: transform .15s ease;
    }

    .ep-btn-save:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 6px 14px rgba(236, 64, 122, .25);
    }

    .ep-btn-cancel {
        border-radius: 10px;
        padding: 10px 22px;
        font-weight: 600;
    }

    @media (max-width: 767px) {
        .ep-action-bar {
            position: sticky;
            bottom: 0;
            background: #fff;
            padding: 12px;
            margin: 20px -12px -12px;
            border-top: 2px solid var(--primary-gold, #d4af37);
            box-shadow: 0 -4px 12px rgba(17, 24, 39, .08);
            z-index: 5;
        }

        .ep-action-bar .btn {
            flex: 1;
        }

        .ep-main-image {
            max-width: 170px;
        }

        .ep-gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            gap: 10px;
        }
    }
</style>

<div class="ep-header">
    <div>
        <h3>Edit Product</h3>
        <p>Modify product properties, manage photos, and upload multiple gallery images at once.</p>
    </div>
    <span class="ep-status-pill <?php echo (int)$product->status === 1 ? 'active' : 'inactive'; ?>">
        <i class="fa-solid <?php echo (int)$product->status === 1 ? 'fa-circle-check' : 'fa-circle-xmark'; ?>"></i>
        <?php echo (int)$product->status === 1 ? 'Active' : 'Inactive'; ?>
    </span>
</div>

<form action="<?php echo base_url('admin/products/edit/' . $product->id); ?>" method="POST" enctype="multipart/form-data" id="editProductForm">

    <div class="row g-3">
        <!-- Image column -->
        <div class="col-lg-4">
            <div class="ep-card h-100">
                <div class="ep-card-head">
                    <div class="d-flex align-items-center gap-2">
                        <span class="ep-icon-badge"><i class="fa-solid fa-image"></i></span>
                        <div>
                            <h6>Main Product Photo</h6>
                            <div class="sub">Currently displayed default image</div>
                        </div>
                    </div>
                </div>
                <div class="ep-card-body">
                    <div class="ep-main-image">
                        <span class="ep-default-tag"><i class="fa-solid fa-star me-1"></i>Main Default</span>
                        <?php $img_src = $product->image ? base_url($product->image) : ''; ?>
                        <img id="productImagePreview" src="<?php echo $img_src ?: 'https://placehold.co/220x220/1f2937/d4af37?text=No+Image'; ?>" alt="Product Preview">
                    </div>

                    <label for="productImageInput" class="ep-upload-zone d-block">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <div class="ep-upload-text">Replace default photo</div>
                        <div class="ep-upload-hint">Auto-compressed · PNG, JPG, WebP</div>
                        <input type="file" name="image" id="productImageInput" accept="image/png, image/jpeg, image/jpg, image/gif, image/webp">
                    </label>
                    <div class="ep-file-chip" id="mainImageChip">
                        <i class="fa-solid fa-file-image"></i>
                        <span id="mainImageChipName" class="text-truncate">file.jpg</span>
                    </div>

                    <div class="p-3 mt-3 rounded-3" style="background:#f8fafc; border:1px dashed #cbd5e1; font-size:.78rem; color:#475569;">
                        <i class="fa-solid fa-circle-info text-primary me-1"></i>
                        <strong>Gallery Uploads:</strong> You can add <strong>15–20+ images at once</strong> directly into the Gallery section below without needing to save and reload each time!
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
                            <h6>Product Details</h6>
                            <div class="sub">Basic information, stock & pricing</div>
                        </div>
                    </div>
                </div>
                <div class="ep-card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3 ep-field">
                            <label for="category_id">Category <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-folder-open"></i></span>
                                <select name="category_id" id="category_id" class="form-select" required>
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat->id; ?>" <?php echo set_select('category_id', $cat->id, (int)$product->category_id === (int)$cat->id); ?>>
                                             <?php echo htmlspecialchars($cat->name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3 ep-field">
                            <label for="name">Product Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-tag"></i></span>
                                <input type="text" name="name" id="name" class="form-control" placeholder="e.g. Protein Powder" value="<?php echo set_value('name', $product->name); ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3 ep-field">
                            <label for="price">Price (₹) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                                <input type="number" step="0.01" name="price" id="price" class="form-control" placeholder="0.00" value="<?php echo set_value('price', $product->price); ?>" required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3 ep-field">
                            <label for="stock">Stock Count <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-cubes"></i></span>
                                <input type="number" name="stock" id="stock" class="form-control" placeholder="0" value="<?php echo set_value('stock', $product->stock); ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 ep-field">
                        <label for="description">Description</label>
                        <div class="input-group">
                            <span class="input-group-text align-items-start pt-2"><i class="fa-solid fa-align-left"></i></span>
                            <textarea name="description" id="description" class="form-control" rows="4" placeholder="Enter product description..."><?php echo set_value('description', $product->description); ?></textarea>
                        </div>
                    </div>

                    <div class="mb-1 ep-field" style="max-width:280px;">
                        <label>Status</label>
                        <div class="ep-status-toggle">
                            <input type="radio" name="status" id="statusActive" value="1" <?php echo set_select('status', '1', (int)$product->status === 1); ?>>
                            <label for="statusActive" class="on"><i class="fa-solid fa-toggle-on me-1"></i> Active</label>

                            <input type="radio" name="status" id="statusInactive" value="0" <?php echo set_select('status', '0', (int)$product->status === 0); ?>>
                            <label for="statusInactive" class="off"><i class="fa-solid fa-toggle-off me-1"></i> Inactive</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ep-action-bar">
                <a href="<?php echo base_url('admin/products'); ?>" class="btn ep-btn-cancel btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn ep-btn-save"><i class="fa-solid fa-floppy-disk me-2"></i>Save Product Details</button>
            </div>
        </div>
    </div>
</form>

<!-- Gallery Management with Instant Multi-Upload -->
<div class="ep-card mt-4" id="gallerySection">
    <div class="ep-card-head">
        <div class="d-flex align-items-center gap-2">
            <span class="ep-icon-badge"><i class="fa-solid fa-images"></i></span>
            <div>
                <h6>Product Gallery (Instant Multi-Image Upload)</h6>
                <div class="sub">Add 15–20+ images at once without reloading. Fast, auto-optimized, and crash-proof.</div>
            </div>
        </div>
        <span class="badge rounded-pill px-3 py-2" id="galleryCountBadge" style="background:linear-gradient(45deg, var(--primary-pink, #ec407a), var(--primary-gold, #d4af37)); color:#fff; font-size:.82rem;">
            <?php echo !empty($gallery) ? count($gallery) : 0; ?> Images
        </span>
    </div>
    <div class="ep-card-body">

        <!-- Multi-image Drag & Drop Zone -->
        <div class="ep-multi-dropzone" id="galleryDropzone">
            <i class="fa-solid fa-cloud-arrow-up drop-icon"></i>
            <h6>Drag & Drop Multiple Images Here, or Click to Browse</h6>
            <p>Upload as many images as you want at once (15–20+ photos). Each image is automatically compressed in your browser so the server never crashes!</p>
            <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap">
                <button type="button" class="btn btn-sm btn-outline-primary px-3 rounded-pill" id="btnBrowseGallery">
                    <i class="fa-solid fa-plus me-1"></i> Select 15–20+ Images
                </button>
                <span class="badge-info-pill"><i class="fa-solid fa-bolt"></i> Auto-resizes & optimises instantly</span>
            </div>
            <input type="file" id="ajaxGalleryInput" multiple accept="image/png, image/jpeg, image/jpg, image/gif, image/webp" style="display:none;">
        </div>

        <!-- Live Upload Progress Bar -->
        <div class="ep-upload-progress-box" id="uploadProgressBox">
            <div class="ep-progress-header">
                <span class="title" id="uploadProgressTitle"><i class="fa-solid fa-spinner fa-spin me-2 text-primary"></i>Uploading images...</span>
                <span class="pct" id="uploadProgressPct">0%</span>
            </div>
            <div class="ep-progress-bar-wrap">
                <div class="ep-progress-bar-fill" id="uploadProgressBar"></div>
            </div>
            <div class="ep-progress-status">
                <span id="uploadProgressDetail">Preparing queue...</span>
                <span id="uploadProgressStats">0 / 0</span>
            </div>
        </div>

        <!-- Empty state placeholder -->
        <div class="ep-empty-gallery" id="galleryEmptyState" style="<?php echo !empty($gallery) ? 'display:none;' : ''; ?>">
            <i class="fa-regular fa-images"></i>
            <p class="mb-1 fw-semibold text-dark">No gallery images uploaded yet</p>
            <p class="small mb-0">Drag & drop 15–20+ photos above to start building the product gallery!</p>
        </div>

        <!-- Gallery Grid -->
        <div class="ep-gallery-grid" id="galleryGrid" style="<?php echo empty($gallery) ? 'display:none;' : ''; ?>">
            <?php if (!empty($gallery)): ?>
                <?php foreach ($gallery as $g): ?>
                    <div class="ep-gallery-item <?php echo ((int)$g->is_default === 1) ? 'is-default' : ''; ?>" id="galleryItem_<?php echo $g->id; ?>">
                        <div class="ep-gallery-thumb">
                            <img src="<?php echo base_url($g->image); ?>" alt="Gallery Image" loading="lazy">
                            <?php if ((int)$g->is_default === 1): ?>
                                <span class="ep-gallery-badge default" id="badge_<?php echo $g->id; ?>"><i class="fa-solid fa-star"></i> Default</span>
                            <?php else: ?>
                                <span class="ep-gallery-badge secondary" id="badge_<?php echo $g->id; ?>"><i class="fa-regular fa-image"></i> Secondary</span>
                            <?php endif; ?>
                            <a href="<?php echo base_url($g->image); ?>" target="_blank" class="ep-gallery-view" title="View Full Image">
                                <i class="fa-solid fa-up-right-from-square"></i>
                            </a>
                        </div>
                        <div class="ep-gallery-actions">
                            <div class="ep-action-slot flex-grow-1" id="actionSlot_<?php echo $g->id; ?>">
                                <?php if ((int)$g->is_default === 0): ?>
                                    <button type="button" class="btn btn-outline-warning text-dark w-100 btn-set-default" data-id="<?php echo $g->id; ?>">
                                        <i class="fa-solid fa-star me-1"></i> Set Default
                                    </button>
                                <?php else: ?>
                                    <span class="ep-active-label"><i class="fa-solid fa-circle-check"></i> Active Main</span>
                                <?php endif; ?>
                            </div>
                            <button type="button" class="btn btn-outline-danger btn-delete-gallery" data-id="<?php echo $g->id; ?>" title="Delete this image">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productId = <?php echo (int)$product->id; ?>;
    const defaultPlaceholder = 'https://placehold.co/220x220/1f2937/d4af37?text=No+Image';

    const imageInput = document.getElementById('productImageInput');
    const imagePreview = document.getElementById('productImagePreview');
    const mainChip = document.getElementById('mainImageChip');
    const mainChipName = document.getElementById('mainImageChipName');

    const galleryDropzone = document.getElementById('galleryDropzone');
    const btnBrowseGallery = document.getElementById('btnBrowseGallery');
    const ajaxGalleryInput = document.getElementById('ajaxGalleryInput');
    const uploadProgressBox = document.getElementById('uploadProgressBox');
    const uploadProgressBar = document.getElementById('uploadProgressBar');
    const uploadProgressTitle = document.getElementById('uploadProgressTitle');
    const uploadProgressPct = document.getElementById('uploadProgressPct');
    const uploadProgressDetail = document.getElementById('uploadProgressDetail');
    const uploadProgressStats = document.getElementById('uploadProgressStats');
    const galleryGrid = document.getElementById('galleryGrid');
    const galleryEmptyState = document.getElementById('galleryEmptyState');
    const galleryCountBadge = document.getElementById('galleryCountBadge');

    function showAlert(title, text, icon = 'warning') {
        if (typeof dsAlert !== 'undefined') {
            dsAlert({ icon, title, text });
        } else if (typeof Swal !== 'undefined') {
            Swal.fire({ icon, title, text, confirmButtonColor: '#ec407a' });
        } else {
            alert(title + ': ' + text);
        }
    }

    function showToast(message, icon = 'success') {
        if (typeof Swal !== 'undefined') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            Toast.fire({ icon, title: message });
        }
    }

    /**
     * Client-side image compressor using HTML5 Canvas.
     * Shrinks 5MB-10MB camera images down to ~150KB (max 1400px, 0.85 quality)
     * in ~50ms inside the browser so requests never exceed PHP limits!
     */
    function compressImageClient(file, maxDim = 1400, quality = 0.85) {
        return new Promise((resolve) => {
            if (!file.type.match(/image\/(jpeg|png|webp)/i)) {
                resolve(file);
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    let w = img.naturalWidth || img.width;
                    let h = img.naturalHeight || img.height;
                    if (!w || !h) {
                        resolve(file);
                        return;
                    }
                    const scale = Math.min(1, maxDim / Math.max(w, h));
                    const targetW = Math.round(w * scale);
                    const targetH = Math.round(h * scale);

                    const canvas = document.createElement('canvas');
                    canvas.width = targetW;
                    canvas.height = targetH;
                    const ctx = canvas.getContext('2d');
                    ctx.imageSmoothingEnabled = true;
                    ctx.imageSmoothingQuality = 'high';
                    ctx.drawImage(img, 0, 0, targetW, targetH);

                    const outputType = (file.type === 'image/png') ? 'image/png' : 'image/jpeg';
                    canvas.toBlob((blob) => {
                        if (blob && (blob.size < file.size || scale < 1)) {
                            const newFileName = file.name.replace(/\.[^/.]+$/, "") + (outputType === 'image/png' ? '.png' : '.jpg');
                            const newFile = new File([blob], newFileName, {
                                type: outputType,
                                lastModified: Date.now()
                            });
                            resolve(newFile);
                        } else {
                            resolve(file);
                        }
                    }, outputType, quality);
                };
                img.onerror = () => resolve(file);
                img.src = e.target.result;
            };
            reader.onerror = () => resolve(file);
            reader.readAsDataURL(file);
        });
    }

    // Quick replacement of default image in left column
    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(evt) {
                imagePreview.src = evt.target.result;
            };
            reader.readAsDataURL(file);

            if (mainChip && mainChipName) {
                mainChipName.textContent = file.name;
                mainChip.style.display = 'flex';
            }
        });
    }

    // Gallery Drag & Drop zone triggers
    if (galleryDropzone && ajaxGalleryInput) {
        galleryDropzone.addEventListener('click', (e) => {
            if (e.target !== btnBrowseGallery && !btnBrowseGallery.contains(e.target)) {
                ajaxGalleryInput.click();
            }
        });

        if (btnBrowseGallery) {
            btnBrowseGallery.addEventListener('click', (e) => {
                e.stopPropagation();
                ajaxGalleryInput.click();
            });
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            galleryDropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                galleryDropzone.classList.add('dragover');
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            galleryDropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                galleryDropzone.classList.remove('dragover');
            });
        });

        galleryDropzone.addEventListener('drop', (e) => {
            const files = Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/'));
            if (files.length) {
                startSequentialUploadQueue(files);
            } else {
                showAlert('No Images Found', 'Please drag and drop valid image files (JPG, PNG, WebP).');
            }
        });

        ajaxGalleryInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files).filter(f => f.type.startsWith('image/'));
            if (files.length) {
                startSequentialUploadQueue(files);
            }
            ajaxGalleryInput.value = '';
        });
    }

    /**
     * Sequential AJAX upload queue.
     * Uploads 15–20+ images one at a time.
     * Never triggers post_max_size or PHP memory exhaustion!
     */
    let isUploading = false;
    async function startSequentialUploadQueue(files) {
        if (isUploading) {
            showAlert('Upload in Progress', 'Please wait until the current batch finishes uploading.');
            return;
        }

        isUploading = true;
        uploadProgressBox.style.display = 'block';
        uploadProgressBar.style.width = '0%';
        uploadProgressBar.className = 'ep-progress-bar-fill progress-bar-striped progress-bar-animated';
        uploadProgressTitle.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2 text-primary"></i>Uploading images...';

        const totalFiles = files.length;
        let successCount = 0;
        let failCount = 0;

        for (let i = 0; i < totalFiles; i++) {
            const file = files[i];
            const currentNum = i + 1;
            const pct = Math.round(((i) / totalFiles) * 100);

            uploadProgressPct.textContent = pct + '%';
            uploadProgressBar.style.width = pct + '%';
            uploadProgressDetail.textContent = `Optimizing & uploading: ${file.name}`;
            uploadProgressStats.textContent = `${currentNum} of ${totalFiles}`;

            try {
                // Client-side compression before sending
                const optimizedFile = await compressImageClient(file, 1400, 0.85);

                const formData = new FormData();
                formData.append('gallery_image', optimizedFile);

                const response = await fetch(`<?php echo base_url('admin/products/ajax_upload_gallery/' . $product->id); ?>`, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: formData
                });

                const data = await response.json();

                if (data.status) {
                    successCount++;
                    appendGalleryItemToDOM(data);
                } else {
                    failCount++;
                    console.error('Upload failed for file:', file.name, data.message);
                }
            } catch (err) {
                failCount++;
                console.error('Network error for file:', file.name, err);
            }

            const completedPct = Math.round((currentNum / totalFiles) * 100);
            uploadProgressPct.textContent = completedPct + '%';
            uploadProgressBar.style.width = completedPct + '%';
        }

        // Completion status
        uploadProgressStats.textContent = `${successCount} uploaded, ${failCount} failed`;
        if (failCount === 0) {
            uploadProgressTitle.innerHTML = '<i class="fa-solid fa-circle-check text-success me-2"></i>All images uploaded!';
            uploadProgressDetail.textContent = `Successfully added ${successCount} image(s) to product gallery.`;
            showToast(`Added ${successCount} new images!`);
        } else {
            uploadProgressTitle.innerHTML = `<i class="fa-solid fa-triangle-exclamation text-warning me-2"></i>Completed with warnings`;
            uploadProgressDetail.textContent = `${successCount} succeeded, ${failCount} failed.`;
            showAlert('Upload Finished', `${successCount} image(s) added successfully, but ${failCount} failed.`);
        }

        isUploading = false;
        setTimeout(() => {
            if (!isUploading) {
                uploadProgressBox.style.display = 'none';
            }
        }, 4000);
    }

    /**
     * Appends newly uploaded gallery image into DOM
     */
    function appendGalleryItemToDOM(data) {
        if (galleryEmptyState) galleryEmptyState.style.display = 'none';
        if (galleryGrid) galleryGrid.style.display = 'grid';

        const item = document.createElement('div');
        item.className = `ep-gallery-item ${data.is_default ? 'is-default' : ''}`;
        item.id = `galleryItem_${data.gallery_id}`;

        item.innerHTML = `
            <div class="ep-gallery-thumb">
                <img src="${data.image_url}" alt="Gallery Image" loading="lazy">
                <span class="ep-gallery-badge ${data.is_default ? 'default' : 'secondary'}" id="badge_${data.gallery_id}">
                    <i class="fa-solid ${data.is_default ? 'fa-star' : 'fa-image'}"></i> ${data.is_default ? 'Default' : 'Secondary'}
                </span>
                <a href="${data.image_url}" target="_blank" class="ep-gallery-view" title="View Full Image">
                    <i class="fa-solid fa-up-right-from-square"></i>
                </a>
            </div>
            <div class="ep-gallery-actions">
                <div class="ep-action-slot flex-grow-1" id="actionSlot_${data.gallery_id}">
                    ${data.is_default 
                        ? '<span class="ep-active-label"><i class="fa-solid fa-circle-check"></i> Active Main</span>'
                        : `<button type="button" class="btn btn-outline-warning text-dark w-100 btn-set-default" data-id="${data.gallery_id}">
                            <i class="fa-solid fa-star me-1"></i> Set Default
                           </button>`
                    }
                </div>
                <button type="button" class="btn btn-outline-danger btn-delete-gallery" data-id="${data.gallery_id}" title="Delete this image">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        `;

        galleryGrid.appendChild(item);

        if (data.total_count && galleryCountBadge) {
            galleryCountBadge.textContent = `${data.total_count} Images`;
        }

        if (data.is_default && imagePreview) {
            imagePreview.src = data.image_url;
        }

        bindItemEvents(item);
    }

    /**
     * Bind click events for Set Default and Delete on a gallery item element
     */
    function bindItemEvents(parentEl) {
        // Set Default
        const setDefBtn = parentEl.querySelector('.btn-set-default');
        if (setDefBtn) {
            setDefBtn.addEventListener('click', handleSetDefault);
        }

        // Delete
        const delBtn = parentEl.querySelector('.btn-delete-gallery');
        if (delBtn) {
            delBtn.addEventListener('click', handleDelete);
        }
    }

    // Set Default Click Handler
    async function handleSetDefault() {
        const galleryId = this.getAttribute('data-id');
        if (!galleryId) return;

        this.disabled = true;
        this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Setting...';

        try {
            const res = await fetch(`<?php echo base_url('admin/products/ajax_set_default/' . $product->id); ?>/${galleryId}`, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();

            if (data.status) {
                // Remove default from all other cards
                document.querySelectorAll('.ep-gallery-item').forEach(card => {
                    card.classList.remove('is-default');
                    const gid = card.id.replace('galleryItem_', '');
                    const badge = card.querySelector('.ep-gallery-badge');
                    if (badge) {
                        badge.className = 'ep-gallery-badge secondary';
                        badge.innerHTML = '<i class="fa-regular fa-image"></i> Secondary';
                    }
                    const slot = card.querySelector('.ep-action-slot');
                    if (slot) {
                        slot.innerHTML = `
                            <button type="button" class="btn btn-outline-warning text-dark w-100 btn-set-default" data-id="${gid}">
                                <i class="fa-solid fa-star me-1"></i> Set Default
                            </button>
                        `;
                        const newBtn = slot.querySelector('.btn-set-default');
                        if (newBtn) newBtn.addEventListener('click', handleSetDefault);
                    }
                });

                // Apply default to this card
                const targetCard = document.getElementById(`galleryItem_${galleryId}`);
                if (targetCard) {
                    targetCard.classList.add('is-default');
                    const badge = targetCard.querySelector('.ep-gallery-badge');
                    if (badge) {
                        badge.className = 'ep-gallery-badge default';
                        badge.innerHTML = '<i class="fa-solid fa-star"></i> Default';
                    }
                    const slot = targetCard.querySelector('.ep-action-slot');
                    if (slot) {
                        slot.innerHTML = '<span class="ep-active-label"><i class="fa-solid fa-circle-check"></i> Active Main</span>';
                    }
                }

                // Update main preview at top
                if (imagePreview && data.image_url) {
                    imagePreview.src = data.image_url;
                }

                showToast('Main product image updated!');
            } else {
                showAlert('Error', data.message || 'Could not set default image.', 'error');
                this.disabled = false;
                this.innerHTML = '<i class="fa-solid fa-star me-1"></i> Set Default';
            }
        } catch (err) {
            console.error(err);
            showAlert('Connection Error', 'Could not communicate with the server.', 'error');
            this.disabled = false;
            this.innerHTML = '<i class="fa-solid fa-star me-1"></i> Set Default';
        }
    }

    // Delete Click Handler
    function handleDelete() {
        const galleryId = this.getAttribute('data-id');
        if (!galleryId) return;

        const performDelete = async () => {
            try {
                const res = await fetch(`<?php echo base_url('admin/products/ajax_delete_gallery/' . $product->id); ?>/${galleryId}`, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();

                if (data.status) {
                    const card = document.getElementById(`galleryItem_${galleryId}`);
                    if (card) {
                        card.style.transition = 'all .3s ease';
                        card.style.opacity = '0';
                        card.style.transform = 'scale(0.85)';
                        setTimeout(() => card.remove(), 300);
                    }

                    if (galleryCountBadge) {
                        galleryCountBadge.textContent = `${data.total_count} Images`;
                    }

                    // If deleted image was default, promote next default
                    if (data.was_default) {
                        if (data.new_default_id) {
                            const newCard = document.getElementById(`galleryItem_${data.new_default_id}`);
                            if (newCard) {
                                newCard.classList.add('is-default');
                                const badge = newCard.querySelector('.ep-gallery-badge');
                                if (badge) {
                                    badge.className = 'ep-gallery-badge default';
                                    badge.innerHTML = '<i class="fa-solid fa-star"></i> Default';
                                }
                                const slot = newCard.querySelector('.ep-action-slot');
                                if (slot) {
                                    slot.innerHTML = '<span class="ep-active-label"><i class="fa-solid fa-circle-check"></i> Active Main</span>';
                                }
                            }
                            if (imagePreview && data.new_default_url) {
                                imagePreview.src = data.new_default_url;
                            }
                        } else {
                            if (imagePreview) imagePreview.src = defaultPlaceholder;
                        }
                    }

                    if (data.total_count === 0) {
                        if (galleryGrid) galleryGrid.style.display = 'none';
                        if (galleryEmptyState) galleryEmptyState.style.display = 'block';
                    }

                    showToast('Gallery image removed!');
                } else {
                    showAlert('Delete Failed', data.message || 'Could not delete image.', 'error');
                }
            } catch (err) {
                console.error(err);
                showAlert('Connection Error', 'Could not communicate with the server.', 'error');
            }
        };

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Delete this image?',
                text: 'Are you sure you want to remove this gallery image? If it is the default image, another image will become default.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) performDelete();
            });
        } else if (confirm('Are you sure you want to remove this gallery image?')) {
            performDelete();
        }
    }

    // Bind existing items rendered by PHP
    document.querySelectorAll('.ep-gallery-item').forEach(item => bindItemEvents(item));
});
</script>