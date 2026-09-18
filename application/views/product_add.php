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
    }
</style>

<div class="ep-header">
    <div>
        <h3>Add New Product</h3>
        <p>Create a brand new product item.</p>
    </div>
    <span class="ep-status-pill">
        <i class="fa-solid fa-circle-plus"></i>
        New Product
    </span>
</div>

<form action="<?php echo base_url('admin/products/add'); ?>" method="POST" enctype="multipart/form-data" id="addProductForm">

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
                </div>
                <div class="ep-card-body">
                    <div class="ep-main-image">
                        <span class="ep-default-tag"><i class="fa-solid fa-star me-1"></i>Default</span>
                        <img id="productImagePreview" src="https://placehold.co/220x220/1f2937/d4af37?text=No+Image" alt="Product Preview">
                    </div>

                    <label for="productImageInput" class="ep-upload-zone d-block">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <div class="ep-upload-text">Select main image</div>
                        <div class="ep-upload-hint">PNG, JPG, GIF, WebP · Max 2MB</div>
                        <input type="file" name="image" id="productImageInput" accept="image/png, image/jpeg, image/jpg, image/gif, image/webp" required>
                    </label>
                    <div class="ep-file-chip" id="mainImageChip">
                        <i class="fa-solid fa-file-image"></i>
                        <span id="mainImageChipName">file.jpg</span>
                    </div>

                    <hr class="my-3">

                    <label for="galleryImagesInput" class="ep-upload-zone d-block">
                        <i class="fa-solid fa-images"></i>
                        <div class="ep-upload-text">Add secondary images</div>
                        <div class="ep-upload-hint">Upload multiple · Max 2MB each · Optional</div>
                        <input type="file" name="gallery_images[]" id="galleryImagesInput" accept="image/png, image/jpeg, image/jpg, image/gif, image/webp" multiple>
                    </label>
                    <div id="galleryPreviewContainer" class="row g-2 mt-2" style="display:none;"></div>
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
                            <div class="sub">Basic information & pricing</div>
                        </div>
                    </div>
                </div>
                <div class="ep-card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3 ep-field">
                            <label for="category_id">Category</label>
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
                            <label for="name">Product Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-tag"></i></span>
                                <input type="text" name="name" id="name" class="form-control" placeholder="e.g. Protein Powder" value="<?php echo set_value('name'); ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3 ep-field">
                            <label for="price">Price (₹)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                                <input type="number" step="0.01" name="price" id="price" class="form-control" placeholder="0.00" value="<?php echo set_value('price'); ?>" required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3 ep-field">
                            <label for="stock">Stock Count</label>
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
                    </div>

                    <div class="mb-1 ep-field" style="max-width:280px;">
                        <label>Status</label>
                        <div class="ep-status-toggle">
                            <input type="radio" name="status" id="statusActive" value="1" <?php echo set_select('status', '1', true); ?>>
                            <label for="statusActive" class="on"><i class="fa-solid fa-toggle-on me-1"></i> Active</label>

                            <input type="radio" name="status" id="statusInactive" value="0" <?php echo set_select('status', '0'); ?>>
                            <label for="statusInactive" class="off"><i class="fa-solid fa-toggle-off me-1"></i> Inactive</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ep-action-bar">
                <a href="<?php echo base_url('admin/products'); ?>" class="btn ep-btn-cancel btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn ep-btn-save"><i class="fa-solid fa-floppy-disk me-2"></i>Save Product</button>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('productImageInput');
        const imagePreview = document.getElementById('productImagePreview');
        const mainChip = document.getElementById('mainImageChip');
        const mainChipName = document.getElementById('mainImageChipName');
        const galleryInput = document.getElementById('galleryImagesInput');
        const galleryContainer = document.getElementById('galleryPreviewContainer');
        const defaultImgSrc = 'https://placehold.co/220x220/1f2937/d4af37?text=No+Image';

        function showAlert(title, text) {
            if (typeof dsAlert !== 'undefined') {
                dsAlert({
                    icon: 'warning',
                    title,
                    text
                });
            } else {
                alert(title + ': ' + text);
            }
        }

        // Live preview: default image
        if (imageInput && imagePreview) {
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                if (file.size > 2 * 1024 * 1024) {
                    showAlert('File Too Large', 'Default product image must be smaller than 2MB.');
                    imageInput.value = '';
                    imagePreview.src = defaultImgSrc;
                    mainChip.style.display = 'none';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(event) {
                    imagePreview.src = event.target.result;
                };
                reader.readAsDataURL(file);

                mainChipName.textContent = file.name;
                mainChip.style.display = 'flex';
            });
        }

        // Live preview: secondary gallery images
        if (galleryInput && galleryContainer) {
            galleryInput.addEventListener('change', function(e) {
                galleryContainer.innerHTML = '';
                const files = Array.from(e.target.files);
                if (!files.length) {
                    galleryContainer.style.display = 'none';
                    return;
                }

                let hasOversized = false;
                const validFiles = [];
                files.forEach(f => f.size > 2 * 1024 * 1024 ? hasOversized = true : validFiles.push(f));

                if (hasOversized) {
                    showAlert('Some Images Too Large', 'One or more secondary images exceeded the 2MB limit. Please re-select images under 2MB.');
                    galleryInput.value = '';
                    galleryContainer.style.display = 'none';
                    return;
                }

                galleryContainer.style.display = 'flex';
                files.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(evt) {
                        const col = document.createElement('div');
                        col.className = 'col-4 col-sm-3';
                        col.innerHTML = `
                        <div class="border rounded-3 p-1 shadow-sm bg-white text-center">
                            <img src="${evt.target.result}" alt="Secondary preview" class="img-fluid rounded-2" style="width:100%; height:60px; object-fit:cover;">
                            <span class="badge bg-secondary mt-1 d-block text-truncate" style="font-size:.6rem;">Sec #${index + 1}</span>
                        </div>`;
                        galleryContainer.appendChild(col);
                    };
                    reader.readAsDataURL(file);
                });
            });
        }
    });
</script>