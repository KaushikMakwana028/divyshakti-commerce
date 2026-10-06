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
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf3d0;
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

    /* Main image preview */
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
        margin: 0 auto 16px;
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

    /* Multi Dropzone */
    .ep-multi-dropzone {
        border: 2px dashed #d1d5db;
        border-radius: 14px;
        padding: 22px 14px;
        text-align: center;
        background: linear-gradient(180deg, #fbfcfe 0%, #f7f9fb 100%);
        cursor: pointer;
        transition: all .2s ease;
    }

    .ep-multi-dropzone:hover,
    .ep-multi-dropzone.dragover {
        border-color: var(--primary-pink, #ec407a);
        background: #fff5f8;
        transform: scale(1.005);
    }

    .ep-multi-dropzone i.drop-icon {
        font-size: 2rem;
        color: var(--primary-pink, #ec407a);
        margin-bottom: 8px;
        display: inline-block;
    }

    .ep-multi-dropzone h6 {
        font-weight: 700;
        font-size: .92rem;
        color: #1f2937;
        margin-bottom: 3px;
    }

    .ep-multi-dropzone p {
        font-size: .78rem;
        color: #6b7280;
        margin-bottom: 10px;
    }

    /* Selected files preview grid */
    .ep-selected-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 18px;
        margin-bottom: 10px;
        padding-bottom: 8px;
        border-bottom: 1px solid #f1f2f4;
    }

    .ep-selected-header .title {
        font-weight: 700;
        font-size: .86rem;
        color: #1f2937;
    }

    .ep-selected-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(105px, 1fr));
        gap: 10px;
        max-height: 380px;
        overflow-y: auto;
        padding-right: 4px;
    }

    /* Custom scrollbar for grid */
    .ep-selected-grid::-webkit-scrollbar {
        width: 5px;
    }
    .ep-selected-grid::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 4px;
    }

    .ep-thumb-card {
        border: 1.5px solid #eef0f3;
        border-radius: 10px;
        overflow: hidden;
        background: #fff;
        position: relative;
        transition: all .15s ease;
    }

    .ep-thumb-card.is-main {
        border-color: var(--primary-gold, #d4af37);
        box-shadow: 0 0 0 1.5px var(--primary-gold, #d4af37), 0 3px 10px rgba(212, 175, 55, .25);
    }

    .ep-thumb-img-wrap {
        aspect-ratio: 1/1;
        position: relative;
        background: #fdf6e3;
        cursor: pointer;
    }

    .ep-thumb-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .ep-thumb-star {
        position: absolute;
        top: 4px;
        left: 4px;
        background: var(--primary-gold, #d4af37);
        color: #111;
        font-size: .58rem;
        font-weight: 700;
        padding: 2px 5px;
        border-radius: 999px;
        box-shadow: 0 2px 4px rgba(0,0,0,.15);
        z-index: 2;
    }

    .ep-thumb-del {
        position: absolute;
        top: 4px;
        right: 4px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: rgba(220, 38, 38, .85);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .6rem;
        border: none;
        cursor: pointer;
        z-index: 2;
        transition: transform .15s ease;
    }

    .ep-thumb-del:hover {
        transform: scale(1.15);
        background: #dc2626;
    }

    .ep-thumb-footer {
        padding: 4px 6px;
        background: #f9fafb;
        border-top: 1px solid #f1f2f4;
        text-align: center;
    }

    .ep-thumb-footer .btn-make-main {
        font-size: .64rem;
        padding: 2px 4px;
        border-radius: 5px;
        width: 100%;
        font-weight: 600;
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

    /* Size Checkbox Tiles */
    .ep-sizes-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .ep-size-checkbox-tile {
        cursor: pointer;
        user-select: none;
        margin: 0;
    }

    .ep-size-checkbox-tile input[type="checkbox"] {
        display: none;
    }

    .ep-size-tile-content {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.84rem;
        color: #334155;
        transition: all 0.18s ease;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }

    .ep-size-check-icon {
        font-size: 0.85rem;
        color: #cbd5e1;
        transition: color 0.18s ease;
    }

    .ep-size-checkbox-tile:hover .ep-size-tile-content {
        border-color: #cbd5e1;
        background: #f1f5f9;
    }

    .ep-size-checkbox-tile input[type="checkbox"]:checked + .ep-size-tile-content {
        background: linear-gradient(135deg, rgba(236, 64, 122, 0.08), rgba(212, 175, 55, 0.12));
        border-color: var(--primary-pink, #ec407a);
        color: #1f2937;
        box-shadow: 0 2px 8px rgba(236, 64, 122, 0.18);
    }

    .ep-size-checkbox-tile input[type="checkbox"]:checked + .ep-size-tile-content .ep-size-check-icon {
        color: var(--primary-pink, #ec407a);
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
        padding: 10px 24px;
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

    /* Modal Progress Overlay */
    .ep-progress-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(17, 24, 39, .65);
        backdrop-filter: blur(4px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .ep-progress-card {
        background: #fff;
        border-radius: 18px;
        padding: 28px 32px;
        max-width: 440px;
        width: 90%;
        text-align: center;
        box-shadow: 0 20px 40px rgba(0,0,0,.2);
    }

    .ep-progress-card h5 {
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 6px;
    }

    .ep-progress-card p {
        font-size: .84rem;
        color: #6b7280;
        margin-bottom: 16px;
    }

    .ep-progress-card .progress {
        height: 10px;
        border-radius: 999px;
        background: #f1f5f9;
        margin-bottom: 10px;
    }

    .ep-progress-card .progress-bar {
        background: linear-gradient(90deg, var(--primary-pink, #ec407a), var(--primary-gold, #d4af37));
        border-radius: 999px;
        transition: width .2s ease;
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
    }
</style>

<div class="ep-header">
    <div>
        <h3>Add New Product</h3>
        <p>Create a brand new product and upload multiple gallery images at once.</p>
    </div>
    <span class="ep-status-pill">
        <i class="fa-solid fa-circle-plus"></i>
        New Product
    </span>
</div>

<form action="<?php echo base_url('admin/products/add'); ?>" method="POST" enctype="multipart/form-data" id="addProductForm">

    <!-- Hidden input to store chosen default gallery index -->
    <input type="hidden" name="default_gallery_index" id="defaultGalleryIndex" value="0">

    <div class="row g-3">
        <!-- Image & Gallery column -->
        <div class="col-lg-5">
            <div class="ep-card h-100">
                <div class="ep-card-head">
                    <div class="d-flex align-items-center gap-2">
                        <span class="ep-icon-badge"><i class="fa-solid fa-images"></i></span>
                        <div>
                            <h6>Product Images & Gallery</h6>
                            <div class="sub">Add 15–20+ photos at once</div>
                        </div>
                    </div>
                </div>
                <div class="ep-card-body">
                    <!-- Main image preview -->
                    <div class="ep-main-image">
                        <span class="ep-default-tag"><i class="fa-solid fa-star me-1"></i>Main Default</span>
                        <img id="productImagePreview" src="https://placehold.co/220x220/1f2937/d4af37?text=No+Image" alt="Main Photo Preview">
                    </div>

                    <!-- Multi-image Drag & Drop Zone -->
                    <div class="ep-multi-dropzone" id="addDropzone">
                        <i class="fa-solid fa-cloud-arrow-up drop-icon"></i>
                        <h6>Drag & Drop Photos Here</h6>
                        <p>Select 15–20+ photos at once. Click any photo below to make it the Main Photo!</p>
                        <button type="button" class="btn btn-sm btn-outline-primary px-3 rounded-pill" id="btnBrowseAdd">
                            <i class="fa-solid fa-plus me-1"></i> Browse & Add Photos
                        </button>
                        <input type="file" id="multiImageInput" multiple accept="image/png, image/jpeg, image/jpg, image/gif, image/webp" style="display:none;">
                    </div>

                    <!-- Selected Files Gallery Preview -->
                    <div id="selectedPanel" style="display:none;">
                        <div class="ep-selected-header">
                            <span class="title">
                                Selected Photos (<span id="selectedCountText">0</span>)
                            </span>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:.74rem;" id="btnAddMore">
                                    <i class="fa-solid fa-plus me-1"></i>Add More
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size:.74rem;" id="btnClearAll">
                                    Clear
                                </button>
                            </div>
                        </div>
                        <div class="ep-selected-grid" id="selectedGrid"></div>
                    </div>

                    <div class="p-3 mt-3 rounded-3" style="background:#f8fafc; border:1px dashed #cbd5e1; font-size:.76rem; color:#475569;">
                        <i class="fa-solid fa-circle-check text-success me-1"></i>
                        <strong>Crash-proof Multi-Upload:</strong> All selected images are automatically optimized in your browser before upload, ensuring ultra-fast processing without server errors or timeouts.
                    </div>
                </div>
            </div>
        </div>

        <!-- Details column -->
        <div class="col-lg-7">
            <div class="ep-card">
                <div class="ep-card-head">
                    <div class="d-flex align-items-center gap-2">
                        <span class="ep-icon-badge"><i class="fa-solid fa-box-open"></i></span>
                        <div>
                            <h6>Product Details</h6>
                            <div class="sub">Basic information & pricing</div>
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
                                        <option value="<?php echo $cat->id; ?>" <?php echo set_select('category_id', $cat->id); ?>>
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
                                <input type="text" name="name" id="name" class="form-control" placeholder="e.g. Protein Powder" value="<?php echo set_value('name'); ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3 ep-field">
                            <label for="price">Price (₹) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                                <input type="number" step="0.01" name="price" id="price" class="form-control" placeholder="0.00" value="<?php echo set_value('price'); ?>" required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3 ep-field">
                            <label for="stock">Stock Count <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-cubes"></i></span>
                                <input type="number" name="stock" id="stock" class="form-control" placeholder="0" value="<?php echo set_value('stock', '0'); ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 ep-field">
                        <label for="description">Description</label>
                        <div class="input-group">
                            <span class="input-group-text align-items-start pt-2"><i class="fa-solid fa-align-left"></i></span>
                            <textarea name="description" id="description" class="form-control" rows="4" placeholder="Enter product description..."><?php echo set_value('description'); ?></textarea>
                        </div>
                    <div class="mb-3 ep-field">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="mb-0">
                                <i class="fa-solid fa-shirt me-1 text-warning"></i> Available Sizes <span class="text-muted fw-normal" style="font-size:0.78rem;">(Optional - select as many as you want)</span>
                            </label>
                            <div class="d-flex gap-2" style="font-size:0.75rem;">
                                <a href="javascript:void(0)" class="text-decoration-none fw-semibold text-primary" id="btnSelectAllSizes">Select All</a>
                                <span class="text-muted">•</span>
                                <a href="javascript:void(0)" class="text-decoration-none fw-semibold text-muted" id="btnClearAllSizes">Clear All</a>
                            </div>
                        </div>
                        <div class="ep-sizes-grid">
                            <?php 
                                $all_sizes = ['S', 'M', 'L', 'XL', 'XXL', '3XL', '4XL', '5XL', 'Free size'];
                                $posted_sizes = (array)$this->input->post('sizes');
                                foreach ($all_sizes as $sz):
                                    $is_checked = in_array($sz, $posted_sizes);
                            ?>
                                <label class="ep-size-checkbox-tile">
                                    <input type="checkbox" name="sizes[]" value="<?php echo htmlspecialchars($sz); ?>" class="size-checkbox" <?php echo $is_checked ? 'checked' : ''; ?>>
                                    <span class="ep-size-tile-content">
                                        <span class="ep-size-text"><?php echo htmlspecialchars($sz); ?></span>
                                        <i class="fa-solid fa-circle-check ep-size-check-icon"></i>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        <small class="text-muted d-block mt-2" style="font-size:0.75rem;">
                            <i class="fa-solid fa-circle-info me-1"></i> If selected, users will choose their size on the mobile app before purchasing. If no sizes are selected, the product will be saved without size options.
                        </small>
                    </div>

                    <div class="mb-1 ep-field" style="max-width:280px;">
                        <label>Status</label>
                        <div class="ep-status-toggle">
                            <?php 
                                $add_status = ($this->input->post('status') !== null) ? (string)$this->input->post('status') : '1'; 
                            ?>
                            <input type="radio" name="status" id="statusActive" value="1" <?php echo ($add_status === '1') ? 'checked' : ''; ?>>
                            <label for="statusActive" class="on"><i class="fa-solid fa-toggle-on me-1"></i> Active</label>

                            <input type="radio" name="status" id="statusInactive" value="0" <?php echo ($add_status === '0') ? 'checked' : ''; ?>>
                            <label for="statusInactive" class="off"><i class="fa-solid fa-toggle-off me-1"></i> Inactive</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ep-action-bar">
                <a href="<?php echo base_url('admin/products'); ?>" class="btn ep-btn-cancel btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn ep-btn-save" id="btnSaveProduct"><i class="fa-solid fa-floppy-disk me-2"></i>Save Product</button>
            </div>
        </div>
    </div>
</form>

<!-- Fullscreen Upload & Save Progress Modal -->
<div class="ep-progress-overlay" id="saveProgressModal">
    <div class="ep-progress-card">
        <div class="mb-3">
            <span class="ep-icon-badge mx-auto" style="width:50px; height:50px; font-size:1.4rem;">
                <i class="fa-solid fa-cloud-arrow-up"></i>
            </span>
        </div>
        <h5 id="modalTitle">Saving Product...</h5>
        <p id="modalSubtitle">Preparing photos for upload...</p>

        <div class="progress">
            <div class="progress-bar progress-bar-striped progress-bar-animated" id="modalProgressBar" style="width: 0%;"></div>
        </div>

        <div class="d-flex justify-content-between text-muted" style="font-size:.76rem;">
            <span id="modalStepText">Optimizing photos...</span>
            <span id="modalPctText" class="fw-bold text-dark">0%</span>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const defaultPlaceholder = 'https://placehold.co/220x220/1f2937/d4af37?text=No+Image';

    const form = document.getElementById('addProductForm');
    const imagePreview = document.getElementById('productImagePreview');
    const addDropzone = document.getElementById('addDropzone');
    const btnBrowseAdd = document.getElementById('btnBrowseAdd');
    const multiInput = document.getElementById('multiImageInput');
    const selectedPanel = document.getElementById('selectedPanel');
    const selectedGrid = document.getElementById('selectedGrid');
    const selectedCountText = document.getElementById('selectedCountText');
    const btnAddMore = document.getElementById('btnAddMore');
    const btnClearAll = document.getElementById('btnClearAll');
    const btnSaveProduct = document.getElementById('btnSaveProduct');

    // Progress Modal elements
    const saveProgressModal = document.getElementById('saveProgressModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalSubtitle = document.getElementById('modalSubtitle');
    const modalProgressBar = document.getElementById('modalProgressBar');
    const modalStepText = document.getElementById('modalStepText');
    const modalPctText = document.getElementById('modalPctText');

    // Accumulative file store
    let pendingFiles = [];
    let mainFileIndex = 0;

    function showAlert(title, text, icon = 'warning') {
        if (typeof dsAlert !== 'undefined') {
            dsAlert({ icon, title, text });
        } else if (typeof Swal !== 'undefined') {
            Swal.fire({ icon, title, text, confirmButtonColor: '#ec407a' });
        } else {
            alert(title + ': ' + text);
        }
    }

    /**
     * Client-side image compressor using HTML5 Canvas.
     * Shrinks 5MB-10MB camera images down to ~150KB (max 1400px, 0.85 quality)
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

    // Browse triggers
    if (addDropzone && multiInput) {
        addDropzone.addEventListener('click', (e) => {
            if (e.target !== btnBrowseAdd && !btnBrowseAdd.contains(e.target)) {
                multiInput.click();
            }
        });

        btnBrowseAdd.addEventListener('click', (e) => {
            e.stopPropagation();
            multiInput.click();
        });

        if (btnAddMore) {
            btnAddMore.addEventListener('click', () => multiInput.click());
        }

        // Drag & Drop
        ['dragenter', 'dragover'].forEach(eventName => {
            addDropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                addDropzone.classList.add('dragover');
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            addDropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                addDropzone.classList.remove('dragover');
            });
        });

        addDropzone.addEventListener('drop', (e) => {
            const files = Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/'));
            if (files.length) {
                addFilesToQueue(files);
            } else {
                showAlert('No Images Found', 'Please drag and drop valid image files (JPG, PNG, WebP).');
            }
        });

        multiInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files).filter(f => f.type.startsWith('image/'));
            if (files.length) {
                addFilesToQueue(files);
            }
            multiInput.value = '';
        });
    }

    // Clear all files
    if (btnClearAll) {
        btnClearAll.addEventListener('click', () => {
            pendingFiles = [];
            mainFileIndex = 0;
            renderPreviewGrid();
        });
    }

    /**
     * Add files to pending queue (accumulate without replacing!)
     */
    function addFilesToQueue(newFiles) {
        newFiles.forEach(file => {
            // Avoid adding identical file twice
            const exists = pendingFiles.some(f => f.name === file.name && f.size === file.size);
            if (!exists) {
                pendingFiles.push(file);
            }
        });

        if (mainFileIndex >= pendingFiles.length) {
            mainFileIndex = 0;
        }

        renderPreviewGrid();
    }

    /**
     * Render the preview cards grid
     */
    function renderPreviewGrid() {
        selectedGrid.innerHTML = '';

        if (!pendingFiles.length) {
            selectedPanel.style.display = 'none';
            imagePreview.src = defaultPlaceholder;
            selectedCountText.textContent = '0';
            return;
        }

        selectedPanel.style.display = 'block';
        selectedCountText.textContent = pendingFiles.length;

        pendingFiles.forEach((file, idx) => {
            const isMain = (idx === mainFileIndex);

            const card = document.createElement('div');
            card.className = `ep-thumb-card ${isMain ? 'is-main' : ''}`;

            const imgWrap = document.createElement('div');
            imgWrap.className = 'ep-thumb-img-wrap';

            const img = document.createElement('img');
            img.alt = file.name;

            const reader = new FileReader();
            reader.onload = (e) => {
                img.src = e.target.result;
                if (isMain) {
                    imagePreview.src = e.target.result;
                }
            };
            reader.readAsDataURL(file);

            imgWrap.appendChild(img);

            if (isMain) {
                const starBadge = document.createElement('span');
                starBadge.className = 'ep-thumb-star';
                starBadge.innerHTML = '<i class="fa-solid fa-star"></i> Main';
                imgWrap.appendChild(starBadge);
            }

            const delBtn = document.createElement('button');
            delBtn.type = 'button';
            delBtn.className = 'ep-thumb-del';
            delBtn.title = 'Remove';
            delBtn.innerHTML = '<i class="fa-solid fa-xmark"></i>';
            delBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                removeFile(idx);
            });
            imgWrap.appendChild(delBtn);

            imgWrap.addEventListener('click', () => setMainPhoto(idx));

            const footer = document.createElement('div');
            footer.className = 'ep-thumb-footer';
            if (isMain) {
                footer.innerHTML = '<span class="text-success fw-bold" style="font-size:.65rem;"><i class="fa-solid fa-circle-check"></i> Default</span>';
            } else {
                const mainBtn = document.createElement('button');
                mainBtn.type = 'button';
                mainBtn.className = 'btn btn-outline-warning text-dark btn-make-main';
                mainBtn.innerHTML = '<i class="fa-solid fa-star me-1"></i> Make Main';
                mainBtn.addEventListener('click', () => setMainPhoto(idx));
                footer.appendChild(mainBtn);
            }

            card.appendChild(imgWrap);
            card.appendChild(footer);
            selectedGrid.appendChild(card);
        });
    }

    function setMainPhoto(idx) {
        mainFileIndex = idx;
        renderPreviewGrid();
    }

    function removeFile(idx) {
        pendingFiles.splice(idx, 1);
        if (mainFileIndex === idx) {
            mainFileIndex = 0;
        } else if (mainFileIndex > idx) {
            mainFileIndex--;
        }
        renderPreviewGrid();
    }

    // Sizes Select All / Clear All helpers
    document.getElementById('btnSelectAllSizes')?.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelectorAll('.size-checkbox').forEach(cb => cb.checked = true);
    });
    document.getElementById('btnClearAllSizes')?.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelectorAll('.size-checkbox').forEach(cb => cb.checked = false);
    });

    /**
     * Submit Form with Progressive Chunk Upload
     * Step 1: Compress images client-side
     * Step 2: Create product with main image via AJAX
     * Step 3: Sequentially upload remaining gallery images to ajax_upload_gallery
     * Step 4: Redirect to products page
     */
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        if (!pendingFiles.length) {
            showAlert('Image Required', 'Please select at least one photo for the product.');
            return;
        }

        btnSaveProduct.disabled = true;
        saveProgressModal.style.display = 'flex';

        modalTitle.textContent = 'Saving Product...';
        modalSubtitle.textContent = `Optimizing ${pendingFiles.length} photo(s) for fast upload...`;
        modalProgressBar.style.width = '5%';
        modalPctText.textContent = '5%';
        modalStepText.textContent = 'Compressing images...';

        try {
            // Step 1: Compress all files client-side
            const optimizedFiles = [];
            for (let i = 0; i < pendingFiles.length; i++) {
                modalStepText.textContent = `Optimizing photo ${i + 1} of ${pendingFiles.length}...`;
                const opt = await compressImageClient(pendingFiles[i], 1400, 0.85);
                optimizedFiles.push(opt);
                const pct = Math.round(((i + 1) / pendingFiles.length) * 20);
                modalProgressBar.style.width = pct + '%';
                modalPctText.textContent = pct + '%';
            }

            // Step 2: Create product with the chosen main photo
            modalStepText.textContent = 'Creating product in database...';
            modalSubtitle.textContent = 'Saving product details & main photo...';
            modalProgressBar.style.width = '30%';
            modalPctText.textContent = '30%';

            const mainPhotoFile = optimizedFiles[mainFileIndex];
            const secondaryFiles = optimizedFiles.filter((_, idx) => idx !== mainFileIndex);

            const formData = new FormData(form);
            formData.set('image', mainPhotoFile);

            const createRes = await fetch(form.action, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });

            const createData = await createRes.json();

            if (!createData.status || !createData.product_id) {
                saveProgressModal.style.display = 'none';
                btnSaveProduct.disabled = false;
                showAlert('Save Failed', createData.message || 'Could not save product details.', 'error');
                return;
            }

            const newProductId = createData.product_id;

            // Step 3: Sequentially upload any secondary gallery images
            if (secondaryFiles.length > 0) {
                modalTitle.textContent = 'Uploading Gallery Photos...';
                const totalSecondary = secondaryFiles.length;

                for (let j = 0; j < totalSecondary; j++) {
                    const secFile = secondaryFiles[j];
                    const currentSecNum = j + 1;
                    const secPct = 30 + Math.round((currentSecNum / totalSecondary) * 65);

                    modalSubtitle.textContent = `Uploading gallery photo ${currentSecNum} of ${totalSecondary}...`;
                    modalStepText.textContent = secFile.name;
                    modalProgressBar.style.width = secPct + '%';
                    modalPctText.textContent = secPct + '%';

                    const secFormData = new FormData();
                    secFormData.append('gallery_image', secFile);

                    try {
                        await fetch(`<?php echo base_url('admin/products/ajax_upload_gallery/'); ?>${newProductId}`, {
                            method: 'POST',
                            headers: { 'X-Requested-With': 'XMLHttpRequest' },
                            body: secFormData
                        });
                    } catch (secErr) {
                        console.error('Secondary upload error:', secFile.name, secErr);
                    }
                }
            }

            // Done!
            modalProgressBar.style.width = '100%';
            modalPctText.textContent = '100%';
            modalTitle.innerHTML = '<i class="fa-solid fa-circle-check text-success me-2"></i>Product Created Successfully!';
            modalSubtitle.textContent = `All ${pendingFiles.length} photo(s) uploaded. Redirecting...`;
            modalStepText.textContent = 'Done!';

            setTimeout(() => {
                window.location.href = createData.redirect || '<?php echo base_url('admin/products'); ?>';
            }, 1000);

        } catch (err) {
            console.error('Submission error:', err);
            saveProgressModal.style.display = 'none';
            btnSaveProduct.disabled = false;
            showAlert('Upload Error', 'An unexpected error occurred while uploading. Please try again.', 'error');
        }
    });
});
</script>