<div class="row mb-4">
    <div class="col-12">
        <h3 class="fw-bold" style="color: var(--dark-sidebar);">Profile Settings</h3>
        <p class="text-muted">Manage your personal information and account security.</p>
    </div>
</div>

<div class="row">
    <!-- Profile Card (Left Column) -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm text-center p-4" style="border-radius: 12px; border-top: 4px solid var(--primary-pink) !important;">
            <div class="card-body">
                <!-- Avatar with Camera Overlay (Clickable) -->
                <div class="position-relative d-inline-block mb-3 avatar-container" id="avatarClickTarget" style="cursor: pointer;">
                    <img id="profileImagePreview" src="<?php echo $user->profile_image ? base_url($user->profile_image) : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user->email ?? ''))) . '?d=mp'; ?>" 
                         alt="User Avatar" 
                         class="img-fluid rounded-circle border" 
                         style="width: 140px; height: 140px; object-fit: cover; border: 3px solid var(--primary-gold) !important; transition: all 0.3s ease;">
                    <div class="camera-overlay position-absolute bottom-0 end-0 bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 38px; height: 38px; border: 2px solid var(--primary-gold); transition: all 0.2s ease;">
                        <i class="fa-solid fa-camera" style="color: var(--primary-pink); font-size: 1.1rem;"></i>
                    </div>
                </div>
                
                <h5 class="fw-bold m-0" style="color: var(--dark-sidebar);"><?php echo htmlspecialchars($user->name ?? 'User'); ?></h5>
                <p class="text-muted small mb-2"><?php echo !empty($user->email) ? htmlspecialchars($user->email) : '<span class="fst-italic text-muted">No email provided</span>'; ?></p>
                <span class="badge" style="background-color: rgba(212, 175, 55, 0.15); color: var(--primary-gold); border: 1px solid var(--primary-gold); padding: 4px 10px; font-size: 0.75rem; border-radius: 50px;">
                    <?php echo ((int)$user->role === 1) ? 'Admin Account' : 'User Account'; ?>
                </span>
                
                <hr class="my-4">
                
                <div class="text-start">
                    <p class="mb-3 d-flex align-items-center">
                        <strong style="color: var(--dark-sidebar); min-width: 90px;"><i class="fa-solid fa-phone me-2 text-muted"></i> Phone:</strong> 
                        <span class="text-muted"><?php echo htmlspecialchars($user->phone ?? '-'); ?></span>
                    </p>
                    <p class="mb-3 d-flex align-items-center">
                        <strong style="color: var(--dark-sidebar); min-width: 90px;"><i class="fa-solid fa-gift me-2 text-muted"></i> Referral:</strong> 
                        <span class="badge ms-1 text-uppercase" style="background-color: rgba(212, 175, 55, 0.12); color: var(--primary-gold); border: 1px dashed var(--primary-gold); padding: 6px 12px; font-size: 0.85rem; font-weight: bold; border-radius: 6px; letter-spacing: 0.5px;">
                            <?php echo htmlspecialchars($user->referral_code ?? '-'); ?>
                        </span>
                    </p>
                    <p class="mb-0">
                        <strong style="color: var(--dark-sidebar);"><i class="fa-solid fa-location-dot me-2 text-muted"></i> Address:</strong> 
                        <br>
                        <span class="text-muted d-inline-block mt-2 ps-4"><?php echo htmlspecialchars($user->address ?: 'No address specified'); ?></span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Forms (Right Column) -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header bg-white p-0">
                <!-- Navigation Tabs -->
                <ul class="nav nav-tabs border-bottom-0" id="profileTabs" role="tablist">
                    <li class="nav-item" role="presentation" style="flex: 1;">
                        <button class="nav-link w-100 py-3 active border-0 rounded-0 fw-semibold text-center" id="edit-profile-tab" data-bs-toggle="tab" data-bs-target="#edit-profile" type="button" role="tab" aria-controls="edit-profile" aria-selected="true" style="color: var(--dark-sidebar); border-bottom: 3px solid transparent !important;">
                            <i class="fa-solid fa-user-pen me-2"></i> Edit Profile
                        </button>
                    </li>
                    <li class="nav-item" role="presentation" style="flex: 1;">
                        <button class="nav-link w-100 py-3 border-0 rounded-0 fw-semibold text-center" id="change-password-tab" data-bs-toggle="tab" data-bs-target="#change-password" type="button" role="tab" aria-controls="change-password" aria-selected="false" style="color: var(--dark-sidebar); border-bottom: 3px solid transparent !important;">
                            <i class="fa-solid fa-shield-halved me-2"></i> Password & Security
                        </button>
                    </li>
                </ul>
            </div>
            
            <div class="card-body p-4">
                <div class="tab-content" id="profileTabsContent">
                    <!-- Edit Profile Tab -->
                    <div class="tab-pane fade show active" id="edit-profile" role="tabpanel" aria-labelledby="edit-profile-tab">
                        <form action="<?php echo base_url('admin/profile'); ?>" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="update_profile">
                            
                            <!-- Hidden File Input for Profile Image (submitted here) -->
                            <input type="file" name="profile_image" id="profileImageInput" style="display: none;" accept="image/png, image/jpeg, image/jpg">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label fw-semibold text-dark">Full Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                        <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($user->name ?? ''); ?>" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label fw-semibold text-dark">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                                        <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($user->email ?? ''); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label fw-semibold text-dark">Phone Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                                        <input type="text" name="phone" id="phone" class="form-control" value="<?php echo htmlspecialchars($user->phone ?? ''); ?>" required>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="gender" class="form-label fw-semibold text-dark">Gender</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-venus-mars"></i></span>
                                        <select name="gender" id="gender" class="form-select">
                                            <option value="">Select Gender</option>
                                            <option value="male" <?php echo (isset($user->gender) && strtolower($user->gender) === 'male') ? 'selected' : ''; ?>>Male</option>
                                            <option value="female" <?php echo (isset($user->gender) && strtolower($user->gender) === 'female') ? 'selected' : ''; ?>>Female</option>
                                            <option value="other" <?php echo (isset($user->gender) && strtolower($user->gender) === 'other') ? 'selected' : ''; ?>>Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="address" class="form-label fw-semibold text-dark">Street Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-location-dot"></i></span>
                                    <textarea name="address" id="address" class="form-control" rows="3" placeholder="Your street address"><?php echo htmlspecialchars($user->address ?: ''); ?></textarea>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn px-4 py-2 fw-semibold text-white" style="background: linear-gradient(45deg, var(--primary-pink), var(--primary-gold)); border: none; border-radius: 8px;">
                                    <i class="fa-solid fa-floppy-disk me-2"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Change Password Tab -->
                    <div class="tab-pane fade" id="change-password" role="tabpanel" aria-labelledby="change-password-tab">
                        <form action="<?php echo base_url('admin/profile'); ?>" method="POST">
                            <input type="hidden" name="action" value="change_password">
                            
                            <div class="mb-3">
                                <label for="current_password" class="form-label fw-semibold text-dark">Current Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                    <input type="password" name="current_password" id="current_password" class="form-control" placeholder="Enter current password" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="new_password" class="form-label fw-semibold text-dark">New Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                                        <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Min 6 characters" required>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="confirm_new_password" class="form-label fw-semibold text-dark">Confirm New Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                                        <input type="password" name="confirm_new_password" id="confirm_new_password" class="form-control" placeholder="Re-enter new password" required>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn px-4 py-2 fw-semibold text-white" style="background: linear-gradient(45deg, var(--primary-pink), var(--primary-gold)); border: none; border-radius: 8px;">
                                    <i class="fa-solid fa-lock me-2"></i> Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS styles for tab and hover animation of camera overlay -->
<style>
    #profileTabs .nav-link.active {
        border-bottom: 3px solid var(--primary-pink) !important;
        color: var(--primary-pink) !important;
        background-color: transparent !important;
    }
    #profileTabs .nav-link:hover {
        color: var(--primary-pink) !important;
    }
    .avatar-container:hover #profileImagePreview {
        filter: brightness(0.85);
    }
    .avatar-container:hover .camera-overlay {
        transform: scale(1.15);
        background-color: #f3f4f6 !important;
    }
</style>

<!-- JS for camera click and image selection live preview -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // If the URL has a hash matching a tab id, switch to it
    const hash = window.location.hash;
    if (hash === '#password') {
        const tabTrigger = new bootstrap.Tab(document.querySelector('#change-password-tab'));
        tabTrigger.show();
    }

    // Connect Click Event of Avatar preview and Camera Overlay to Hidden File Input
    const avatarContainer = document.getElementById('avatarClickTarget');
    const fileInput = document.getElementById('profileImageInput');
    const imagePreview = document.getElementById('profileImagePreview');

    if (avatarContainer && fileInput) {
        avatarContainer.addEventListener('click', function(e) {
            fileInput.click();
        });
    }

    // Live Preview Selected Image
    if (fileInput && imagePreview) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Verify file size is within limits (optional warning)
                if (file.size > 2 * 1024 * 1024) {
                    dsAlert({
                        icon: 'warning',
                        title: 'File Too Large',
                        text: 'Please select an image smaller than 2MB.'
                    });
                    fileInput.value = ''; // clear input
                    return;
                }

                // Verify file is an image
                if (!file.type.match('image.*')) {
                    dsAlert({
                        icon: 'warning',
                        title: 'Invalid File Type',
                        text: 'Please select an image file (PNG, JPG, JPEG).'
                    });
                    fileInput.value = '';
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
