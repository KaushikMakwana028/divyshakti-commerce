<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?php echo base_url('admin/members'); ?>" class="text-decoration-none">Members</a></li>
                <li class="breadcrumb-item"><a href="<?php echo base_url('admin/members/view/' . $member->id); ?>" class="text-decoration-none"><?php echo htmlspecialchars($member->name); ?></a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Member</li>
            </ol>
        </nav>
        <h3 class="fw-bold m-0" style="color: var(--dark-sidebar);">
            <i class="fa-solid fa-user-pen me-2 text-primary"></i> Edit Member Profile
        </h3>
        <p class="text-muted small m-0 mt-1">Update personal info, banking details, KYC documents, and referral code/sponsor.</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0">
        <a href="<?php echo base_url('admin/members/view/' . $member->id); ?>" class="btn btn-outline-secondary px-3 rounded-3 me-2">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Detail
        </a>
        <a href="<?php echo base_url('admin/members'); ?>" class="btn btn-outline-dark px-3 rounded-3">
            <i class="fa-solid fa-users me-1"></i> Member List
        </a>
    </div>
</div>

<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="fa-solid fa-circle-exclamation fs-5 me-2"></i>
            <div><?php echo $this->session->flashdata('error'); ?></div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="fa-solid fa-circle-check fs-5 me-2"></i>
            <div><?php echo $this->session->flashdata('success'); ?></div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<form action="<?php echo base_url('admin/members/edit/' . $member->id); ?>" method="POST" enctype="multipart/form-data" id="editMemberForm">
    
    <!-- Top Summary Card / Profile Header -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
        <div class="p-4 text-white d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3" 
             style="background: linear-gradient(135deg, var(--dark-sidebar) 0%, #1f2937 100%); border-bottom: 3px solid var(--primary-gold);">
            <div class="d-flex align-items-center gap-3">
                <div class="position-relative" style="width: 64px; height: 64px;">
                    <?php if (!empty($member->profile_image) && file_exists(FCPATH . ltrim($member->profile_image, '/'))): ?>
                        <img src="<?php echo base_url(ltrim($member->profile_image, '/')); ?>" id="headerAvatarPreview" class="w-100 h-100 rounded-circle border border-2 border-white shadow-sm" style="object-fit: cover;">
                    <?php else: ?>
                        <div id="headerAvatarFallback" class="w-100 h-100 rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold fs-4 border border-2 border-white shadow-sm">
                            <?php echo strtoupper(substr($member->name ?? 'M', 0, 1)); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div>
                    <h5 class="fw-bold mb-1 text-white"><?php echo htmlspecialchars($member->name); ?></h5>
                    <div class="d-flex flex-wrap align-items-center gap-2 text-white-50 small">
                        <span><i class="fa-solid fa-id-badge me-1"></i>ID: <strong class="text-white font-monospace"><?php echo htmlspecialchars($member->custom_id ?? '-' ); ?></strong></span>
                        <span>·</span>
                        <span><i class="fa-solid fa-tag me-1"></i>Ref: <strong class="text-white font-monospace"><?php echo htmlspecialchars($member->referral_code); ?></strong></span>
                        <span>·</span>
                        <span><i class="fa-solid fa-wallet me-1"></i>Wallet: <strong class="text-success">₹<?php echo number_format($member->wallet_balance, 2); ?></strong></span>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="submit" class="btn btn-warning px-4 py-2 fw-semibold text-dark rounded-3 shadow-sm" style="background-color: var(--primary-gold); border-color: var(--primary-gold);">
                    <i class="fa-solid fa-floppy-disk me-1.5"></i> Save Changes
                </button>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column: Identifiers, Referrals, and Personal Details -->
        <div class="col-lg-7">

            <!-- Card 1: Referral & Network Hierarchy -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center gap-2">
                    <span class="badge rounded-circle p-2" style="background: rgba(200,151,56,0.15); color: var(--primary-gold);">
                        <i class="fa-solid fa-network-wired fs-6"></i>
                    </span>
                    <h6 class="fw-bold m-0 text-dark">Referral Code & Network Assignment</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <!-- Custom ID -->
                        <div class="col-md-6">
                            <label for="custom_id" class="form-label fw-semibold text-dark small">Member Custom ID <span class="text-muted">(Sequence)</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted font-monospace">#</span>
                                <input type="text" name="custom_id" id="custom_id" class="form-control font-monospace fw-bold" 
                                       placeholder="0002001" value="<?php echo set_value('custom_id', $member->custom_id ?? ''); ?>">
                            </div>
                            <div class="form-text extra-small" style="font-size: 0.75rem;">Sequential Member ID used in app & invoices.</div>
                        </div>

                        <!-- Member's Own Referral Code -->
                        <div class="col-md-6">
                            <label for="referral_code" class="form-label fw-semibold text-dark small">Member's Referral Code <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-gift"></i></span>
                                <input type="text" name="referral_code" id="referral_code" class="form-control font-monospace fw-bold text-uppercase" 
                                       required placeholder="DS123456" value="<?php echo set_value('referral_code', $member->referral_code); ?>">
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="btnGenRefCode" title="Generate New Code">
                                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                                </button>
                            </div>
                            <div class="form-text extra-small" style="font-size: 0.75rem;">The code this member gives to invite new downline members.</div>
                        </div>

                        <!-- Parent Referrer (Sponsor) -->
                        <div class="col-12">
                            <label for="parent_sponsor" class="form-label fw-semibold text-dark small">
                                Parent Sponsor (Upline / Who Referred This Member)
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-handshake"></i></span>
                                <input type="text" name="parent_sponsor" id="parent_sponsor" class="form-control" 
                                       placeholder="Enter sponsor Referral Code, Phone, or Custom ID (leave empty for root)"
                                       value="<?php echo set_value('parent_sponsor', $referrer ? $referrer->referral_code : ''); ?>">
                                <button type="button" class="btn btn-outline-primary btn-sm" id="btnVerifySponsor">
                                    <i class="fa-solid fa-magnifying-glass me-1"></i> Verify Sponsor
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm" id="btnClearSponsor" title="Clear sponsor to make root member">
                                    <i class="fa-solid fa-xmark"></i> Clear
                                </button>
                            </div>

                            <!-- Sponsor Live Feedback Box -->
                            <div id="sponsorFeedback" class="mt-2 p-2.5 rounded-3 border <?php echo $referrer ? 'border-success bg-success-subtle' : 'border-secondary-subtle bg-light'; ?>" style="font-size: 0.8rem;">
                                <?php if ($referrer): ?>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <i class="fa-solid fa-circle-check text-success me-1.5"></i>
                                            Current Sponsor: <strong><?php echo htmlspecialchars($referrer->name); ?></strong> 
                                            (<code><?php echo htmlspecialchars($referrer->referral_code); ?></code>)
                                        </div>
                                        <div class="text-muted small">Phone: <?php echo htmlspecialchars($referrer->phone); ?></div>
                                    </div>
                                <?php else: ?>
                                    <div class="text-muted">
                                        <i class="fa-solid fa-info-circle me-1.5"></i> No sponsor currently assigned (Root Member).
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-text extra-small" style="font-size: 0.75rem;">
                                Admin can add or switch sponsor. Note: circular sponsorships (downline assigned as sponsor) are automatically blocked.
                            </div>
                        </div>

                        <!-- Account Status & KYC Status -->
                        <div class="col-md-6">
                            <label for="status" class="form-label fw-semibold text-dark small">Account Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select">
                                <option value="1" <?php echo set_select('status', '1', (int)$member->status === 1); ?>>Active Account</option>
                                <option value="0" <?php echo set_select('status', '0', (int)$member->status === 0); ?>>Blocked / Suspended</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="is_profile_active" class="form-label fw-semibold text-dark small">KYC Verification Status <span class="text-danger">*</span></label>
                            <select name="is_profile_active" id="is_profile_active" class="form-select">
                                <option value="1" <?php echo set_select('is_profile_active', '1', (int)$member->is_profile_active === 1); ?>>Verified & Approved</option>
                                <option value="0" <?php echo set_select('is_profile_active', '0', (int)$member->is_profile_active === 0); ?>>Pending Admin Review</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Personal Information -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center gap-2">
                    <span class="badge rounded-circle p-2" style="background: rgba(236,64,122,0.15); color: var(--primary-pink);">
                        <i class="fa-solid fa-user fs-6"></i>
                    </span>
                    <h6 class="fw-bold m-0 text-dark">Personal Information</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <!-- Full Name -->
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold text-dark small">Full Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="fa-regular fa-user"></i></span>
                                <input type="text" name="name" id="name" class="form-control" required placeholder="Full Name" 
                                       value="<?php echo set_value('name', $member->name); ?>">
                            </div>
                        </div>

                        <!-- Phone Number -->
                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-semibold text-dark small">Phone Number <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-phone"></i></span>
                                <input type="tel" name="phone" id="phone" class="form-control" required placeholder="10-digit mobile" 
                                       value="<?php echo set_value('phone', $member->phone); ?>">
                            </div>
                        </div>

                        <!-- Email Address -->
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold text-dark small">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="fa-regular fa-envelope"></i></span>
                                <input type="email" name="email" id="email" class="form-control" placeholder="user@example.com" 
                                       value="<?php echo set_value('email', $member->email ?? ''); ?>">
                            </div>
                        </div>

                        <!-- Gender -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark small d-block">Gender</label>
                            <?php $current_gender = strtolower($member->gender ?? ''); ?>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="gender" id="gender_male" value="male" autocomplete="off" <?php echo ($current_gender === 'male') ? 'checked' : ''; ?>>
                                <label class="btn btn-outline-secondary btn-sm py-2" for="gender_male"><i class="fa-solid fa-mars me-1"></i> Male</label>

                                <input type="radio" class="btn-check" name="gender" id="gender_female" value="female" autocomplete="off" <?php echo ($current_gender === 'female') ? 'checked' : ''; ?>>
                                <label class="btn btn-outline-secondary btn-sm py-2" for="gender_female"><i class="fa-solid fa-venus me-1"></i> Female</label>

                                <input type="radio" class="btn-check" name="gender" id="gender_other" value="other" autocomplete="off" <?php echo ($current_gender === 'other') ? 'checked' : ''; ?>>
                                <label class="btn btn-outline-secondary btn-sm py-2" for="gender_other"><i class="fa-solid fa-genderless me-1"></i> Other</label>
                            </div>
                        </div>

                        <!-- Profile Image Upload -->
                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark small">Profile Image</label>
                            <div class="d-flex align-items-center gap-3">
                                <div class="position-relative border rounded shadow-sm overflow-hidden" style="width: 70px; height: 70px; border-color: var(--primary-gold) !important; flex-shrink: 0;">
                                    <?php if (!empty($member->profile_image) && file_exists(FCPATH . ltrim($member->profile_image, '/'))): ?>
                                        <img src="<?php echo base_url(ltrim($member->profile_image, '/')); ?>" id="profileImagePreview" class="w-100 h-100" style="object-fit: cover;">
                                    <?php else: ?>
                                        <img src="https://placehold.co/100x100/1f2937/d4af37?text=Avatar" id="profileImagePreview" class="w-100 h-100" style="object-fit: cover;">
                                    <?php endif; ?>
                                </div>
                                <div class="flex-grow-1">
                                    <input type="file" name="profile_image" id="profileImageInput" class="form-control form-control-sm" accept="image/*">
                                    <div class="form-text extra-small" style="font-size: 0.72rem;">Accepts JPG, PNG, WebP (Max 4MB). Leave empty to retain current avatar.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="col-12">
                            <label for="address" class="form-label fw-semibold text-dark small">Complete Address</label>
                            <textarea name="address" id="address" class="form-control" rows="3" placeholder="Street address, city, state, pincode..."><?php echo set_value('address', $member->address ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Security & Password -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center gap-2">
                    <span class="badge rounded-circle p-2" style="background: rgba(13,110,253,0.15); color: #0d6efd;">
                        <i class="fa-solid fa-lock fs-6"></i>
                    </span>
                    <h6 class="fw-bold m-0 text-dark">Password & Security</h6>
                </div>
                <div class="card-body p-4">
                    <label for="password" class="form-label fw-semibold text-dark small">Reset Member Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-key"></i></span>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Leave blank to keep existing password" autocomplete="new-password">
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('password')">
                            <i class="fa-regular fa-eye" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                    <div class="form-text extra-small" style="font-size: 0.75rem;">Only fill this if the member requested a password reset. Minimum 6 characters.</div>
                </div>
            </div>

        </div>

        <!-- Right Column: KYC Documents & Bank Account -->
        <div class="col-lg-5">

            <!-- Card 4: KYC & Identity Documents -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge rounded-circle p-2" style="background: rgba(16,185,129,0.15); color: #10b981;">
                            <i class="fa-solid fa-id-card fs-6"></i>
                        </span>
                        <h6 class="fw-bold m-0 text-dark">KYC & Identity</h6>
                    </div>
                    <span class="badge bg-light text-secondary border">
                        Profile: <?php echo (int)($member->profile_completion_percentage ?? 0); ?>%
                    </span>
                </div>
                <div class="card-body p-4">
                    <!-- Aadhaar -->
                    <div class="mb-4">
                        <label for="aadhar_number" class="form-label fw-semibold text-dark small">Aadhaar Number</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text bg-light text-muted"><i class="fa-regular fa-id-card"></i></span>
                            <input type="text" name="aadhar_number" id="aadhar_number" class="form-control font-monospace" 
                                   placeholder="12-digit Aadhaar" value="<?php echo set_value('aadhar_number', $member->aadhar_number ?? ''); ?>" maxlength="16">
                        </div>

                        <label class="form-label small text-muted mb-1">Aadhaar Card Document</label>
                        <?php if (!empty($member->aadhar_image)): ?>
                            <?php $is_pdf = strtolower(pathinfo($member->aadhar_image, PATHINFO_EXTENSION)) === 'pdf'; ?>
                            <div class="d-flex align-items-center justify-content-between p-2 rounded-3 border bg-light mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa-solid <?php echo $is_pdf ? 'fa-file-pdf text-danger' : 'fa-image text-primary'; ?> fs-5"></i>
                                    <span class="small text-truncate" style="max-width: 170px;"><?php echo basename($member->aadhar_image); ?></span>
                                </div>
                                <a href="<?php echo base_url(ltrim($member->aadhar_image, '/')); ?>" target="_blank" class="btn btn-xs btn-outline-primary py-1 px-2" style="font-size: 0.72rem;">
                                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> View
                                </a>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="aadhar_image" class="form-control form-control-sm" accept="image/*,.pdf">
                        <div class="form-text extra-small" style="font-size: 0.72rem;">Upload new file to replace (JPG, PNG, PDF max 5MB).</div>
                    </div>

                    <hr class="text-muted opacity-25 my-3">

                    <!-- PAN -->
                    <div>
                        <label for="pan_number" class="form-label fw-semibold text-dark small">PAN Number</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-credit-card"></i></span>
                            <input type="text" name="pan_number" id="pan_number" class="form-control font-monospace text-uppercase" 
                                   placeholder="10-digit PAN" value="<?php echo set_value('pan_number', $member->pan_number ?? ''); ?>" maxlength="10">
                        </div>

                        <label class="form-label small text-muted mb-1">PAN Card Document</label>
                        <?php if (!empty($member->pan_image)): ?>
                            <?php $is_pdf = strtolower(pathinfo($member->pan_image, PATHINFO_EXTENSION)) === 'pdf'; ?>
                            <div class="d-flex align-items-center justify-content-between p-2 rounded-3 border bg-light mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa-solid <?php echo $is_pdf ? 'fa-file-pdf text-danger' : 'fa-image text-danger'; ?> fs-5"></i>
                                    <span class="small text-truncate" style="max-width: 170px;"><?php echo basename($member->pan_image); ?></span>
                                </div>
                                <a href="<?php echo base_url(ltrim($member->pan_image, '/')); ?>" target="_blank" class="btn btn-xs btn-outline-primary py-1 px-2" style="font-size: 0.72rem;">
                                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> View
                                </a>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="pan_image" class="form-control form-control-sm" accept="image/*,.pdf">
                        <div class="form-text extra-small" style="font-size: 0.72rem;">Upload new file to replace (JPG, PNG, PDF max 5MB).</div>
                    </div>
                </div>
            </div>

            <!-- Card 5: Bank Account Details -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center gap-2">
                    <span class="badge rounded-circle p-2" style="background: rgba(14,116,144,0.15); color: #0e7490;">
                        <i class="fa-solid fa-building-columns fs-6"></i>
                    </span>
                    <h6 class="fw-bold m-0 text-dark">Bank Account Details</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="account_holder_name" class="form-label fw-semibold text-dark small">Account Holder Name</label>
                            <input type="text" name="account_holder_name" id="account_holder_name" class="form-control form-control-sm" 
                                   placeholder="Name as per bank records" value="<?php echo set_value('account_holder_name', $member->account_holder_name ?? ''); ?>">
                        </div>

                        <div class="col-12">
                            <label for="bank_name" class="form-label fw-semibold text-dark small">Bank Name</label>
                            <input type="text" name="bank_name" id="bank_name" class="form-control form-control-sm" 
                                   placeholder="e.g. State Bank of India, HDFC" value="<?php echo set_value('bank_name', $member->bank_name ?? ''); ?>">
                        </div>

                        <div class="col-md-7">
                            <label for="account_number" class="form-label fw-semibold text-dark small">Account Number</label>
                            <input type="text" name="account_number" id="account_number" class="form-control form-control-sm font-monospace" 
                                   placeholder="Account Number" value="<?php echo set_value('account_number', $member->account_number ?? ''); ?>">
                        </div>

                        <div class="col-md-5">
                            <label for="ifsc_code" class="form-label fw-semibold text-dark small">IFSC Code</label>
                            <input type="text" name="ifsc_code" id="ifsc_code" class="form-control form-control-sm font-monospace text-uppercase" 
                                   placeholder="e.g. SBIN0001234" value="<?php echo set_value('ifsc_code', $member->ifsc_code ?? ''); ?>">
                        </div>

                        <div class="col-md-6">
                            <label for="account_type" class="form-label fw-semibold text-dark small">Account Type</label>
                            <select name="account_type" id="account_type" class="form-select form-select-sm">
                                <option value="" <?php echo empty($member->account_type) ? 'selected' : ''; ?>>Select Type</option>
                                <option value="Savings" <?php echo ($member->account_type === 'Savings') ? 'selected' : ''; ?>>Savings</option>
                                <option value="Current" <?php echo ($member->account_type === 'Current') ? 'selected' : ''; ?>>Current</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="branch_name" class="form-label fw-semibold text-dark small">Branch Name</label>
                            <input type="text" name="branch_name" id="branch_name" class="form-control form-control-sm" 
                                   placeholder="Branch City/Area" value="<?php echo set_value('branch_name', $member->branch_name ?? ''); ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sticky Save Button -->
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-warning py-2.5 fw-bold shadow-sm" style="background-color: var(--primary-gold); border-color: var(--primary-gold); color: #111827;">
                        <i class="fa-solid fa-circle-check me-1.5"></i> Save & Apply Updates
                    </button>
                    <a href="<?php echo base_url('admin/members/view/' . $member->id); ?>" class="btn btn-outline-secondary py-2">
                        Cancel & Return
                    </a>
                </div>
            </div>

        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const memberId = <?php echo (int)$member->id; ?>;
    const sponsorInput = document.getElementById('parent_sponsor');
    const feedbackBox = document.getElementById('sponsorFeedback');
    const btnVerify = document.getElementById('btnVerifySponsor');
    const btnClear = document.getElementById('btnClearSponsor');
    const btnGenRef = document.getElementById('btnGenRefCode');
    const refCodeInput = document.getElementById('referral_code');

    // Profile Image live preview
    const profileImgInput = document.getElementById('profileImageInput');
    const profilePreview = document.getElementById('profileImagePreview');
    const headerAvatar = document.getElementById('headerAvatarPreview');

    if (profileImgInput) {
        profileImgInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    if (profilePreview) profilePreview.src = evt.target.result;
                    if (headerAvatar) headerAvatar.src = evt.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Generate random referral code helper
    if (btnGenRef && refCodeInput) {
        btnGenRef.addEventListener('click', function() {
            const randomSuffix = Math.floor(100000 + Math.random() * 900000);
            refCodeInput.value = 'DS' + randomSuffix;
        });
    }

    // Clear sponsor helper
    if (btnClear && sponsorInput) {
        btnClear.addEventListener('click', function() {
            sponsorInput.value = '';
            feedbackBox.className = 'mt-2 p-2.5 rounded-3 border border-secondary-subtle bg-light';
            feedbackBox.innerHTML = '<div class="text-muted"><i class="fa-solid fa-info-circle me-1.5"></i> Sponsor cleared. This member will be set as a Root member.</div>';
        });
    }

    // Sponsor verify check
    function verifySponsor() {
        const query = sponsorInput.value.trim();
        if (!query) {
            feedbackBox.className = 'mt-2 p-2.5 rounded-3 border border-secondary-subtle bg-light';
            feedbackBox.innerHTML = '<div class="text-muted"><i class="fa-solid fa-info-circle me-1.5"></i> No sponsor entered (Root member).</div>';
            return;
        }

        feedbackBox.className = 'mt-2 p-2.5 rounded-3 border border-info bg-info-subtle text-info';
        feedbackBox.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1.5"></i> Checking sponsor validity...';

        fetch('<?php echo base_url('admin/members/ajax_check_sponsor'); ?>?query=' + encodeURIComponent(query) + '&member_id=' + memberId)
            .then(res => res.json())
            .then(data => {
                if (data.valid && data.sponsor) {
                    feedbackBox.className = 'mt-2 p-2.5 rounded-3 border border-success bg-success-subtle text-success';
                    feedbackBox.innerHTML = '<div class="d-flex align-items-center justify-content-between">' +
                        '<div><i class="fa-solid fa-circle-check me-1.5"></i> Valid Sponsor: <strong>' + escapeHtml(data.sponsor.name) + '</strong> (' + escapeHtml(data.sponsor.referral_code) + ')</div>' +
                        '<div class="text-muted small">Phone: ' + escapeHtml(data.sponsor.phone) + '</div></div>';
                } else if (data.valid && !data.sponsor) {
                    feedbackBox.className = 'mt-2 p-2.5 rounded-3 border border-secondary-subtle bg-light';
                    feedbackBox.innerHTML = '<div class="text-muted"><i class="fa-solid fa-info-circle me-1.5"></i> ' + escapeHtml(data.message) + '</div>';
                } else {
                    feedbackBox.className = 'mt-2 p-2.5 rounded-3 border border-danger bg-danger-subtle text-danger';
                    feedbackBox.innerHTML = '<i class="fa-solid fa-circle-xmark me-1.5"></i> ' + escapeHtml(data.message);
                }
            })
            .catch(() => {
                feedbackBox.className = 'mt-2 p-2.5 rounded-3 border border-danger bg-danger-subtle text-danger';
                feedbackBox.innerHTML = '<i class="fa-solid fa-triangle-exclamation me-1.5"></i> Error checking sponsor.';
            });
    }

    if (btnVerify) {
        btnVerify.addEventListener('click', verifySponsor);
    }

    let debounceTimer = null;
    sponsorInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(verifySponsor, 600);
    });

    function escapeHtml(text) {
        if (!text) return '';
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return text.replace(/[&<>"']/g, m => map[m]);
    }
});

function togglePasswordVisibility(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById('togglePasswordIcon');
    if (!input) return;
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fa-regular fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fa-regular fa-eye';
    }
}
</script>