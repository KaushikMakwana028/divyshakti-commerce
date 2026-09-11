<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Account & Data Erasure - Divy Shakti</title>
    <meta name="description" content="Request permanent account deletion and data erasure from Divy Shakti e-commerce and affiliate platform.">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --brand-pink: #ec407a;
            --brand-pink-dark: #d81b60;
            --brand-gold: #d4af37;
            --brand-dark: #161c2d;
            --brand-bg: #f8fafc;
            --brand-card: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --danger-red: #ef4444;
            --danger-dark: #b91c1c;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--brand-bg);
            color: var(--text-main);
            line-height: 1.7;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Brand Navbar */
        .public-navbar {
            background-color: #ffffff;
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            padding: 14px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .brand-logo-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .brand-logo-img {
            width: 42px;
            height: 42px;
            object-fit: contain;
        }

        .brand-title {
            font-weight: 800;
            font-size: 1.25rem;
            letter-spacing: 0.5px;
            background: linear-gradient(135deg, var(--brand-pink), var(--brand-gold));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0;
        }

        /* Hero Header */
        .legal-hero {
            background: linear-gradient(135deg, #1e1b2e 0%, #111827 100%);
            color: #ffffff;
            padding: 48px 0 40px;
            position: relative;
            overflow: hidden;
            border-bottom: 4px solid var(--danger-red);
        }

        .legal-hero::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(239, 68, 68, 0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero-badge-danger {
            background: rgba(239, 68, 68, 0.18);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.35);
            font-size: 0.78rem;
            font-weight: 600;
            padding: 5px 14px;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 12px;
        }

        .legal-title {
            font-size: 2.2rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
        }

        .legal-subtitle {
            color: #94a3b8;
            font-size: 0.95rem;
            margin-bottom: 0;
        }

        /* Content Card */
        .legal-card {
            background-color: var(--brand-card);
            border-radius: 16px;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 25px rgba(0,0,0,0.05);
            padding: 38px;
            margin-top: -24px;
            margin-bottom: 50px;
            position: relative;
            z-index: 10;
        }

        /* Step Card Styling */
        .step-badge {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--brand-pink), var(--brand-pink-dark));
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            box-shadow: 0 4px 10px rgba(236, 64, 122, 0.25);
            flex-shrink: 0;
        }

        .step-item {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 20px;
            height: 100%;
            transition: all 0.25s ease;
        }

        .step-item:hover {
            border-color: #cbd5e1;
            box-shadow: 0 6px 16px rgba(0,0,0,0.05);
            transform: translateY(-2px);
        }

        .step-item h5 {
            font-weight: 700;
            font-size: 1.05rem;
            color: var(--brand-dark);
            margin: 0;
        }

        .step-item p {
            color: #64748b;
            font-size: 0.88rem;
            margin: 0;
            line-height: 1.5;
        }

        /* Deletion Action Box */
        .deletion-action-box {
            background: #fff8f8;
            border: 2px dashed #fca5a5;
            border-radius: 16px;
            padding: 30px;
            margin: 30px 0;
        }

        .btn-delete-action {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            border: none;
            color: #ffffff;
            font-weight: 700;
            font-size: 1.05rem;
            padding: 14px 28px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(239, 68, 68, 0.35);
            transition: all 0.2s ease;
        }

        .btn-delete-action:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: #ffffff;
            box-shadow: 0 8px 24px rgba(239, 68, 68, 0.45);
            transform: translateY(-1px);
        }

        /* App Guide Box */
        .app-guide-box {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 14px;
            padding: 24px;
        }

        .app-step-pill {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .app-step-num {
            background: var(--brand-dark);
            color: #ffffff;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        /* Footer */
        .public-footer {
            margin-top: auto;
            background-color: var(--brand-dark);
            color: #94a3b8;
            padding: 32px 0 24px;
            border-top: 1px solid rgba(255,255,255,0.08);
            font-size: 0.88rem;
        }

        .footer-link {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-link:hover {
            color: var(--brand-gold);
        }

        @media (max-width: 768px) {
            .legal-card {
                padding: 24px 18px;
                border-radius: 12px;
            }
            .legal-title {
                font-size: 1.75rem;
            }
            .deletion-action-box {
                padding: 20px 14px;
            }
        }
    </style>
</head>
<body>

    <!-- Public Brand Header -->
    <header class="public-navbar">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="<?php echo base_url(); ?>" class="brand-logo-wrap">
                <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Divy Shakti Logo" class="brand-logo-img">
                <span class="brand-title">DIVY SHAKTI</span>
            </a>
            <div class="d-flex align-items-center gap-2">
                <a href="<?php echo base_url('privacy_policy'); ?>" class="btn btn-sm btn-outline-secondary px-3 py-1.5" style="border-radius: 8px; font-size: 0.82rem;">
                    <i class="fa-solid fa-shield-halved me-1"></i> Privacy
                </a>
                <a href="<?php echo base_url('terms_conditions'); ?>" class="btn btn-sm btn-outline-secondary px-3 py-1.5" style="border-radius: 8px; font-size: 0.82rem;">
                    <i class="fa-solid fa-file-contract me-1"></i> Terms
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Banner -->
    <div class="legal-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-9">
                    <span class="hero-badge-danger">
                        <i class="fa-solid fa-triangle-exclamation"></i> Permanent Data Erasure &amp; Account Termination
                    </span>
                    <h1 class="legal-title">Delete Divy Shakti Account</h1>
                    <p class="legal-subtitle">Complete account deletion steps, data handling transparency, and direct confirmation portal.</p>
                </div>
                <div class="col-lg-3 text-lg-end mt-3 mt-lg-0">
                    <span class="badge bg-white text-danger border px-3 py-2" style="font-size: 0.82rem; border-radius: 8px;">
                        <i class="fa-solid fa-circle-exclamation me-1"></i> Immediate &amp; Irreversible
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Body -->
    <main class="container">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="legal-card">

                    <?php if ($this->session->flashdata('success') || $this->input->get('deleted') == '1'): ?>
                        <div class="alert alert-success border-0 shadow-sm p-4 text-center mb-4" style="border-radius: 12px; background: #ecfdf5; color: #065f46;">
                            <i class="fa-solid fa-circle-check fs-1 text-success mb-2 d-block"></i>
                            <h4 class="fw-bold mb-1">Account Successfully Deleted</h4>
                            <p class="mb-3 text-muted">Your Divy Shakti account and all associated personal data have been completely purged from active production servers.</p>
                            <a href="<?php echo base_url(); ?>" class="btn btn-sm btn-success px-4 py-2" style="border-radius: 8px;">
                                <i class="fa-solid fa-house me-1"></i> Return to Homepage
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger border-0 shadow-sm p-3 mb-4" style="border-radius: 10px;">
                            <i class="fa-solid fa-circle-exclamation me-2"></i>
                            <?php echo $this->session->flashdata('error'); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Section Header -->
                    <div class="text-center mb-4">
                        <h2 class="fw-bold" style="color: var(--brand-dark); font-size: 1.6rem;">3 Simple Steps to Delete Your Account</h2>
                        <p class="text-muted" style="max-width: 680px; margin: 0 auto; font-size: 0.94rem;">
                            In accordance with Google Play, Apple App Store, and Indian Digital Personal Data Protection mandates, you have full authority to permanently erase your account at any time.
                        </p>
                    </div>

                    <!-- 3 Process Steps Cards -->
                    <div class="row g-3 mb-4">
                        <!-- Step 1 -->
                        <div class="col-md-4">
                            <div class="step-item">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="step-badge">1</div>
                                    <h5>Identify Account</h5>
                                </div>
                                <p>Provide your registered mobile number below or remain signed in so we can locate your unique membership records.</p>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="col-md-4">
                            <div class="step-item">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="step-badge" style="background: linear-gradient(135deg, #f59e0b, #d97706);">2</div>
                                    <h5>Review Impact</h5>
                                </div>
                                <p>Ensure you understand that remaining wallet balance will be forfeited and MLM sponsorship chains decoupled permanently.</p>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="col-md-4">
                            <div class="step-item">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="step-badge" style="background: linear-gradient(135deg, #ef4444, #dc2626);">3</div>
                                    <h5>Confirm &amp; Delete</h5>
                                </div>
                                <p>Click the Delete button. An explicit confirmation pop-up will ask for your consent; once confirmed, deletion occurs immediately.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Consequences Warning Box -->
                    <div class="alert alert-warning border-0 p-4 mb-4" style="border-radius: 12px; background: #fffbeb; border-left: 4px solid #f59e0b !important;">
                        <h5 class="fw-bold text-dark mb-2"><i class="fa-solid fa-triangle-exclamation text-warning me-2"></i>What Happens When You Delete Your Account:</h5>
                        <ul class="mb-0 text-dark small" style="line-height: 1.8;">
                            <li><strong>Wallet Balance:</strong> Any remaining digital wallet funds or unwithdrawn commission balances are permanently cancelled and non-refundable.</li>
                            <li><strong>MLM Referral Network:</strong> Your referral code is deactivated. Any direct recruits in your downline will be decoupled from your account.</li>
                            <li><strong>Order History &amp; Addresses:</strong> Your personal addresses and cart items will be purged immediately. Completed invoice archives are retained strictly as required by statutory taxation law.</li>
                            <li><strong>KYC Records:</strong> Your PAN card image, Aadhaar scan, and bank account numbers are scrubbed from active storage.</li>
                        </ul>
                    </div>

                    <?php 
                        $is_admin_user = !empty($logged_user) && ((int)($logged_user->role ?? 0) !== 0 || (int)$logged_user->id === 1);
                    ?>

                    <!-- Direct Account Deletion Action Area -->
                    <div class="deletion-action-box text-center">
                        <?php if ($is_admin_user): ?>
                            <!-- Case A1: Current user is an Administrator (Protected) -->
                            <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle mb-3 shadow" style="width: 60px; height: 60px;">
                                <i class="fa-solid fa-shield-halved fs-3"></i>
                            </div>

                            <h3 class="fw-bold text-dark mb-2">Administrator Account Protected</h3>
                            <p class="text-muted small mb-4" style="max-width: 580px; margin: 0 auto;">
                                Administrative master accounts cannot be deleted through this public portal in order to protect platform governance, MLM genealogy, and business operations.
                            </p>

                            <div class="card border border-primary-subtle mx-auto mb-4 text-start shadow-sm" style="max-width: 500px; border-radius: 12px; background: #ffffff; border-top: 3px solid var(--bs-primary) !important;">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
                                        <span class="text-muted small">Authenticated Profile</span>
                                        <span class="badge bg-primary px-2.5 py-1" style="font-size: 0.72rem;">
                                            <i class="fa-solid fa-lock me-1"></i> Administrator Role
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center fw-bold fs-5" style="width: 46px; height: 46px;">
                                            <?php echo strtoupper(substr($logged_user->name ?? 'A', 0, 1)); ?>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0"><?php echo htmlspecialchars($logged_user->name); ?></h6>
                                            <span class="text-muted small"><i class="fa-solid fa-phone me-1"></i><?php echo htmlspecialchars($logged_user->phone); ?></span>
                                            <?php if (!empty($logged_user->custom_id)): ?>
                                                <span class="badge bg-light text-dark border ms-1 font-monospace" style="font-size: 0.7rem;">#<?php echo htmlspecialchars($logged_user->custom_id); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                                        <span class="text-muted small">Status:</span>
                                        <strong class="text-success"><i class="fa-solid fa-circle-check me-1"></i> Protected Master Admin (Cannot Be Deleted)</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-wrap justify-content-center gap-2">
                                <a href="<?php echo base_url('admin/dashboard'); ?>" class="btn btn-primary px-4 py-2.5 fw-semibold" style="border-radius: 10px;">
                                    <i class="fa-solid fa-gauge me-1"></i> Return to Admin Dashboard
                                </a>
                                <a href="<?php echo base_url('admin/logout'); ?>" class="btn btn-outline-secondary px-3 py-2.5" style="border-radius: 10px;">
                                    <i class="fa-solid fa-right-from-bracket me-1"></i> Sign Out to Test Regular Member Deletion
                                </a>
                            </div>

                        <?php elseif (!empty($logged_user)): ?>
                            <!-- Case A2: Current user is a Regular Member (Can delete) -->
                            <div class="d-inline-flex align-items-center justify-content-center bg-danger text-white rounded-circle mb-3 shadow" style="width: 58px; height: 58px;">
                                <i class="fa-solid fa-trash-can fs-4"></i>
                            </div>

                            <h3 class="fw-bold text-danger mb-2">Account Deletion Confirmation</h3>
                            <p class="text-muted small mb-4" style="max-width: 580px; margin: 0 auto;">
                                Once you click the button below and confirm the warning prompt, your account will be immediately deleted. This action cannot be reversed.
                            </p>

                            <div class="card border border-danger-subtle mx-auto mb-4 text-start shadow-sm" style="max-width: 500px; border-radius: 12px; background: #ffffff;">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
                                        <span class="text-muted small">Authenticated Profile</span>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5" style="font-size: 0.72rem;">Currently Logged In</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center fw-bold fs-5" style="width: 46px; height: 46px;">
                                            <?php echo strtoupper(substr($logged_user->name ?? 'U', 0, 1)); ?>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0"><?php echo htmlspecialchars($logged_user->name); ?></h6>
                                            <span class="text-muted small"><i class="fa-solid fa-phone me-1"></i><?php echo htmlspecialchars($logged_user->phone); ?></span>
                                            <?php if (!empty($logged_user->custom_id)): ?>
                                                <span class="badge bg-light text-dark border ms-1 font-monospace" style="font-size: 0.7rem;">#<?php echo htmlspecialchars($logged_user->custom_id); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                                        <span class="text-muted small">Current Wallet Balance:</span>
                                        <strong class="text-danger fs-6">₹<?php echo number_format($logged_user->wallet_balance ?? 0, 2); ?></strong>
                                    </div>
                                </div>
                            </div>

                            <form id="deleteAccountForm" action="<?php echo base_url('process_delete_account'); ?>" method="POST" class="d-inline-block w-100" style="max-width: 440px;">
                                <input type="hidden" name="action_type" value="session_delete">
                                <button type="button" class="btn btn-delete-action w-100" id="btnTriggerDelete">
                                    <i class="fa-solid fa-trash-can me-2"></i> Delete My Account Permanently
                                </button>
                            </form>

                        <?php else: ?>
                            <!-- Case B: Visitor is NOT signed in -->
                            <div class="d-inline-flex align-items-center justify-content-center bg-danger text-white rounded-circle mb-3 shadow" style="width: 58px; height: 58px;">
                                <i class="fa-solid fa-trash-can fs-4"></i>
                            </div>

                            <h3 class="fw-bold text-danger mb-2">Account Deletion Confirmation</h3>
                            <p class="text-muted small mb-4" style="max-width: 580px; margin: 0 auto;">
                                Once you click the button below and confirm the warning prompt, your account will be immediately deleted. This action cannot be reversed.
                            </p>
                            <form id="deleteAccountForm" action="<?php echo base_url('process_delete_account'); ?>" method="POST" class="mx-auto text-start" style="max-width: 480px;">
                                <div class="mb-3">
                                    <label for="phone" class="form-label fw-semibold text-dark small">Registered Mobile Number <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted fw-semibold">+91</span>
                                        <input type="tel" name="phone" id="phone" class="form-control" placeholder="Enter 10-digit mobile number" maxlength="15" required autocomplete="tel">
                                    </div>
                                    <div class="form-text text-muted extra-small">The mobile phone number registered with your Divy Shakti account.</div>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label fw-semibold text-dark small">Account Password <span class="text-muted fw-normal">(If set)</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-lock"></i></span>
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter password (leave blank if OTP only)">
                                    </div>
                                </div>

                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" id="confirmCheckbox" required>
                                    <label class="form-check-label text-muted small" for="confirmCheckbox">
                                        I confirm that I want to delete my Divy Shakti account, and I understand this action immediately forfeits my wallet balance and is irreversible.
                                    </label>
                                </div>

                                <button type="button" class="btn btn-delete-action w-100" id="btnTriggerDelete">
                                    <i class="fa-solid fa-trash-can me-2"></i> Delete My Account Permanently
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>

                    <!-- In-App Mobile Deletion Walkthrough Card -->
                    <div class="row justify-content-center mt-5">
                        <div class="col-lg-10">
                            <div class="app-guide-box">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="fa-solid fa-mobile-screen-button fs-4 text-primary"></i>
                                    <h4 class="fw-bold mb-0" style="color: var(--brand-dark); font-size: 1.15rem;">How to Delete Your Account Directly Inside the Divy Shakti Mobile App</h4>
                                </div>
                                <p class="text-muted small mb-3">
                                    If you are using the Divy Shakti Android or iOS Mobile Application, you can also delete your account without using this web page by following these 4 quick steps:
                                </p>

                                <div class="app-step-pill">
                                    <span class="app-step-num">1</span>
                                    <span>Open the <strong>Divy Shakti</strong> app on your smartphone and log into your account.</span>
                                </div>
                                <div class="app-step-pill">
                                    <span class="app-step-num">2</span>
                                    <span>Tap the <strong>Profile (Account)</strong> tab in the bottom navigation bar.</span>
                                </div>
                                <div class="app-step-pill">
                                    <span class="app-step-num">3</span>
                                    <span>Select <strong>Privacy &amp; Security Settings</strong> &rarr; scroll to <strong>Delete Account</strong>.</span>
                                </div>
                                <div class="app-step-pill">
                                    <span class="app-step-num">4</span>
                                    <span>Review the consequences on your wallet balance and tap <strong>Confirm &amp; Delete Account</strong>.</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FAQs -->
                    <div class="mt-5 pt-3">
                        <h4 class="fw-bold mb-3" style="color: var(--brand-dark); font-size: 1.15rem;">Frequently Asked Questions</h4>
                        <div class="accordion" id="faqAccordion">
                            <div class="accordion-item border-0 mb-2 shadow-sm" style="border-radius: 10px; overflow: hidden;">
                                <h2 class="accordion-header" id="faqOne">
                                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                        Can I recover my account or wallet balance after deletion?
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="faqOne" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-muted small">
                                        No. Account deletion is instantaneous and permanent. All wallet balances, referral connections, and rewards are forfeited and cannot be restored.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item border-0 mb-2 shadow-sm" style="border-radius: 10px; overflow: hidden;">
                                <h2 class="accordion-header" id="faqTwo">
                                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        What happens to orders that are currently being shipped?
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="faqTwo" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-muted small">
                                        Orders that have already been dispatched will continue to be delivered by the courier partner. Your invoice records are retained in compliance with regulatory tax standards.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item border-0 mb-2 shadow-sm" style="border-radius: 10px; overflow: hidden;">
                                <h2 class="accordion-header" id="faqThree">
                                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        Can I re-register with the same phone number in the future?
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="faqThree" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-muted small">
                                        Yes. Once your account is fully deleted, your mobile number is released and you may register a brand-new account in the future as a new member.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="public-footer">
        <div class="container">
            <div class="row gy-3 align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> <strong>Divy Shakti</strong>. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="d-inline-flex gap-3">
                        <a href="<?php echo base_url('privacy_policy'); ?>" class="footer-link">Privacy Policy</a>
                        <span>&bull;</span>
                        <a href="<?php echo base_url('terms_conditions'); ?>" class="footer-link">Terms &amp; Conditions</a>
                        <span>&bull;</span>
                        <a href="<?php echo base_url('delete_account'); ?>" class="footer-link text-danger">Delete Account</a>
                        <span>&bull;</span>
                        <a href="<?php echo base_url('admin/login'); ?>" class="footer-link">Admin Portal</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS & SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteBtn = document.getElementById('btnTriggerDelete');
        const form = document.getElementById('deleteAccountForm');
        const phoneInput = document.getElementById('phone');
        const confirmCheckbox = document.getElementById('confirmCheckbox');

        if (deleteBtn && form) {
            deleteBtn.addEventListener('click', function(e) {
                e.preventDefault();

                // Basic validation for guest form
                if (phoneInput && !phoneInput.value.trim()) {
                    Swal.fire({
                        title: 'Phone Number Required',
                        text: 'Please enter the registered mobile number associated with the account you wish to delete.',
                        icon: 'warning',
                        confirmButtonColor: '#ec407a'
                    });
                    phoneInput.focus();
                    return;
                }

                if (confirmCheckbox && !confirmCheckbox.checked) {
                    Swal.fire({
                        title: 'Confirmation Check Required',
                        text: 'Please check the box confirming you understand the permanent consequences of account deletion.',
                        icon: 'info',
                        confirmButtonColor: '#ec407a'
                    });
                    return;
                }

                // Perfect Confirmation Dialog
                Swal.fire({
                    title: 'Delete Account Permanently?',
                    html: `
                        <div class="text-center">
                            <p class="text-muted mb-2">Are you absolutely sure you want to proceed?</p>
                            <div class="alert alert-danger p-2 small mb-0 text-start">
                                <i class="fa-solid fa-triangle-exclamation me-1"></i>
                                <strong>Warning:</strong> All your personal data, wallet balance, orders, and MLM affiliate links will be <strong>erased immediately</strong>.
                            </div>
                        </div>
                    `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, Delete My Account Immediately',
                    cancelButtonText: 'No, Keep Account',
                    reverseButtons: true,
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Purging Account Data...',
                            text: 'Please wait while we securely process your deletion request.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Submit form
                        form.submit();
                    }
                });
            });
        }
    });
    </script>
</body>
</html>
