<div class="row mb-4">
    <div class="col-12">
        <h3 class="fw-bold" style="color: var(--dark-sidebar);">Add New Category</h3>
        <p class="text-muted">Create a brand new category item.</p>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
            <div class="p-4 text-white" style="background: linear-gradient(135deg, var(--dark-sidebar) 0%, #1f2937 100%); border-bottom: 3px solid var(--primary-gold);">
                <span class="badge mb-2 text-uppercase" style="background-color: var(--primary-pink); font-size: 0.75rem; font-weight: 600; padding: 5px 10px;">
                    Category Properties
                </span>
                <h5 class="fw-bold mb-0">Add New Category</h5>
            </div>
            
            <div class="card-body p-4">
                <form action="<?php echo base_url('admin/categories/add'); ?>" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <!-- Image Upload Block (Left Side inside form) -->
                        <div class="col-md-4 text-center mb-4 mb-md-0 border-end pe-md-4">
                            <label class="form-label fw-semibold d-block text-start text-dark mb-3">Category Image Preview</label>
                            
                            <!-- Static Preview Card Wrapper (No camera icon overlay) -->
                            <div class="mb-3 d-inline-block position-relative shadow-sm rounded border" style="width: 150px; height: 150px; overflow: hidden; border: 2px solid var(--primary-gold) !important; background-color: #f9fafb;">
                                <img id="categoryImagePreview" src="https://placehold.co/150x150/1f2937/d4af37?text=No+Image" 
                                     alt="Category Preview" 
                                     class="img-fluid w-100 h-100" 
                                     style="object-fit: cover;">
                            </div>
                            
                            <!-- Standard file input field -->
                            <div class="text-start px-2">
                                <label for="categoryImageInput" class="form-label small fw-semibold text-dark mb-1">Select Image File</label>
                                <input type="file" name="image" id="categoryImageInput" class="form-control" accept="image/png, image/jpeg, image/jpg, image/gif, image/webp" required>
                                <span class="text-muted extra-small d-block mt-1" style="font-size: 0.75rem;">Allowed formats: PNG, JPG, GIF, WebP (Max 2MB)</span>
                            </div>
                        </div>

                        <!-- Data Fields (Right Side inside form) -->
                        <div class="col-md-8 ps-md-4">
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold text-dark">Category Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-tag"></i></span>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="e.g. Health Drinks" value="<?php echo set_value('name'); ?>" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="status" class="form-label fw-semibold text-dark">Status</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-toggle-on"></i></span>
                                    <select name="status" id="status" class="form-select">
                                        <option value="1" <?php echo set_select('status', '1', true); ?>>Active</option>
                                        <option value="0" <?php echo set_select('status', '0'); ?>>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-end gap-2">
                                <a href="<?php echo base_url('admin/categories'); ?>" class="btn btn-secondary px-4 py-2" style="border-radius: 8px;">
                                    Cancel
                                </a>
                                <button type="submit" class="btn px-4 py-2 fw-semibold text-white" style="background: linear-gradient(45deg, var(--primary-pink), var(--primary-gold)); border: none; border-radius: 8px; box-shadow: 0 4px 10px rgba(236, 64, 122, 0.15);">
                                    <i class="fa-solid fa-floppy-disk me-2"></i> Save Category
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JS Script for Live Image Preview -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('categoryImageInput');
    const imagePreview = document.getElementById('categoryImagePreview');

    // Live preview selected image
    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Size validation (2MB limit)
                if (file.size > 2 * 1024 * 1024) {
                    dsAlert({
                        icon: 'warning',
                        title: 'File Too Large',
                        text: 'Please select an image smaller than 2MB.'
                    });
                    imageInput.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(event) {
                    imagePreview.src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
