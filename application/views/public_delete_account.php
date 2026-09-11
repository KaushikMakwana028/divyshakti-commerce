<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Deletion &amp; Data Erasure Guide - Divy Shakti</title>
    <meta name="description" content="Official step-by-step guide on how to delete your Divy Shakti account, data erasure details, and privacy compliance guidelines.">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --brand-pink: #ec407a;
            --brand-pink-dark: #d81b60;
            --brand-gold: #d4af37;
            --brand-gold-dark: #b89428;
            --brand-dark: #161c2d;
            --brand-navy: #0f172a;
            --brand-bg: #f8fafc;
            --brand-card: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --danger-red: #ef4444;
            --danger-dark: #b91c1c;
            --success-green: #10b981;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--brand-bg);
            color: var(--text-main);
            line-height: 1.7;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
            padding: 0;
        }

        /* Top Brand Navbar */
        .public-navbar {
            background-color: #ffffff;
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 2px 12px rgba(0,0,0,0.03);
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

        .nav-btn {
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 500;
            padding: 6px 14px;
            transition: all 0.2s ease;
        }

        /* Hero Header */
        .legal-hero {
            background: linear-gradient(135deg, #111827 0%, #1e1b2e 50%, #1a2234 100%);
            color: #ffffff;
            padding: 56px 0 48px;
            position: relative;
            overflow: hidden;
            border-bottom: 4px solid var(--brand-gold);
        }

        .legal-hero::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 420px;
            height: 420px;
            background: radial-gradient(circle, rgba(236, 64, 122, 0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        .legal-hero::before {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.12) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero-badge {
            background: rgba(212, 175, 55, 0.18);
            color: #fce79a;
            border: 1px solid rgba(212, 175, 55, 0.35);
            font-size: 0.78rem;
            font-weight: 600;
            padding: 6px 16px;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 14px;
        }

        .legal-title {
            font-size: 2.25rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 10px;
        }

        .legal-subtitle {
            color: #cbd5e1;
            font-size: 1rem;
            margin-bottom: 0;
            max-width: 720px;
            line-height: 1.6;
        }

        /* Content Card */
        .legal-card {
            background-color: var(--brand-card);
            border-radius: 20px;
            border: 1px solid var(--border-color);
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
            padding: 42px 40px;
            margin-top: -30px;
            margin-bottom: 60px;
            position: relative;
            z-index: 10;
        }

        /* Overview Highlight Pills */
        .highlights-strip {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 16px 20px;
            margin-bottom: 36px;
        }

        .highlight-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .highlight-icon-wrap {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .highlight-text h6 {
            margin: 0;
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--brand-dark);
        }

        .highlight-text p {
            margin: 0;
            font-size: 0.78rem;
            color: var(--text-muted);
        }

        /* Section Headings */
        .section-header-wrap {
            margin-bottom: 24px;
        }

        .section-eyebrow {
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: var(--brand-pink);
            margin-bottom: 4px;
            display: block;
        }

        .section-heading {
            font-size: 1.45rem;
            font-weight: 800;
            color: var(--brand-dark);
            margin: 0;
        }

        .section-desc {
            color: var(--text-muted);
            font-size: 0.92rem;
            margin-top: 4px;
        }

        /* Step Card Styling */
        .steps-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 36px;
        }

        .step-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 22px 18px;
            position: relative;
            transition: all 0.25s ease;
            display: flex;
            flex-direction: column;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }

        .step-card:hover {
            border-color: #cbd5e1;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        }

        .step-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .step-number-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--brand-pink), var(--brand-pink-dark));
            color: #ffffff;
            font-weight: 800;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(236, 64, 122, 0.25);
        }

        .step-icon-wrap {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: #fdf2f8;
            color: var(--brand-pink);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
        }

        .step-title {
            font-weight: 700;
            font-size: 1rem;
            color: var(--brand-dark);
            margin-bottom: 8px;
        }

        .step-text {
            color: #64748b;
            font-size: 0.85rem;
            line-height: 1.55;
            margin: 0;
            flex-grow: 1;
        }

        /* Alternative Email Request Card */
        .alt-request-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 5px solid #3b82f6;
            border-radius: 14px;
            padding: 24px;
            margin-bottom: 36px;
        }

        .alt-request-badge {
            background: #eff6ff;
            color: #2563eb;
            font-weight: 600;
            font-size: 0.75rem;
            padding: 3px 10px;
            border-radius: 50px;
            display: inline-block;
            margin-bottom: 8px;
        }

        .alt-step-list {
            margin: 14px 0 0;
            padding-left: 20px;
        }

        .alt-step-list li {
            font-size: 0.88rem;
            color: #475569;
            margin-bottom: 8px;
            line-height: 1.55;
        }

        /* Data Policy Columns */
        .data-policy-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 36px;
        }

        .policy-box {
            border-radius: 16px;
            padding: 24px;
            height: 100%;
        }

        .policy-box-deleted {
            background: #fff8f8;
            border: 1px solid #fecaca;
        }

        .policy-box-retained {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
        }

        .policy-box-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid rgba(0,0,0,0.06);
        }

        .policy-box-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .icon-deleted {
            background: #fee2e2;
            color: #dc2626;
        }

        .icon-retained {
            background: #dcfce7;
            color: #15803d;
        }

        .policy-box-title {
            font-size: 1.05rem;
            font-weight: 700;
            margin: 0;
            color: var(--brand-dark);
        }

        .policy-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .policy-list li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 10px;
            font-size: 0.86rem;
            color: #334155;
            line-height: 1.5;
        }

        .policy-list li i {
            margin-top: 3px;
            font-size: 0.82rem;
            flex-shrink: 0;
        }

        /* Warning Callout Box */
        .consequences-card {
            background: #fffbeb;
            border: 1px solid #fef3c7;
            border-left: 5px solid #f59e0b;
            border-radius: 14px;
            padding: 22px 24px;
            margin-bottom: 36px;
        }

        .consequences-card h5 {
            font-size: 1rem;
            font-weight: 700;
            color: #92400e;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .consequences-card ul {
            margin: 0;
            padding-left: 20px;
            font-size: 0.86rem;
            color: #78350f;
            line-height: 1.65;
        }

        /* Support Assistance Box */
        .helpdesk-card {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border-radius: 16px;
            color: #ffffff;
            padding: 28px 30px;
            margin-bottom: 36px;
            position: relative;
            overflow: hidden;
        }

        .helpdesk-card::after {
            content: '';
            position: absolute;
            top: -40%;
            right: -5%;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(236, 64, 122, 0.2) 0%, transparent 70%);
            pointer-events: none;
        }

        .help-contact-btn {
            background: #ffffff;
            color: var(--brand-dark);
            font-weight: 600;
            font-size: 0.86rem;
            padding: 10px 18px;
            border-radius: 10px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .help-contact-btn:hover {
            background: #f1f5f9;
            color: var(--brand-pink);
            transform: translateY(-1px);
        }

        /* Accordion */
        .accordion-item {
            border: 1px solid #e2e8f0;
            border-radius: 12px !important;
            margin-bottom: 12px;
            overflow: hidden;
        }

        .accordion-button {
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--brand-dark);
            background-color: #ffffff;
            padding: 18px 20px;
            box-shadow: none !important;
        }

        .accordion-button:not(.collapsed) {
            background-color: #f8fafc;
            color: var(--brand-pink);
        }

        .accordion-body {
            font-size: 0.88rem;
            color: #64748b;
            line-height: 1.65;
            padding: 16px 20px 20px;
            background-color: #ffffff;
        }

        /* Footer */
        .public-footer {
            margin-top: auto;
            background-color: var(--brand-dark);
            color: #94a3b8;
            padding: 34px 0 24px;
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

        /* Responsive */
        @media (max-width: 992px) {
            .steps-container {
                grid-template-columns: repeat(2, 1fr);
            }
            .data-policy-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .legal-card {
                padding: 26px 18px;
                border-radius: 16px;
                margin-top: -20px;
            }
            .legal-title {
                font-size: 1.7rem;
            }
            .steps-container {
                grid-template-columns: 1fr;
            }
            .highlights-strip .row > div {
                margin-bottom: 12px;
            }
            .highlights-strip .row > div:last-child {
                margin-bottom: 0;
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
                <a href="<?php echo base_url('privacy_policy'); ?>" class="btn btn-outline-secondary nav-btn">
                    <i class="fa-solid fa-shield-halved me-1"></i> Privacy Policy
                </a>
                <a href="<?php echo base_url('terms_conditions'); ?>" class="btn btn-outline-secondary nav-btn">
                    <i class="fa-solid fa-file-contract me-1"></i> Terms of Use
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Banner -->
    <div class="legal-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-10">
                    <span class="hero-badge">
                        <i class="fa-solid fa-shield-check"></i> Google Play &amp; App Store Compliance &bull; Data Erasure Guidelines
                    </span>
                    <h1 class="legal-title">Account Deletion &amp; Data Erasure Guide</h1>
                    <p class="legal-subtitle">
                        Clear, step-by-step instructions on how to permanently delete your Divy Shakti account, understand what data is erased, and review statutory data retention policies.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Container -->
    <main class="container">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="legal-card">

                    <!-- Quick Highlights Strip -->
                    <div class="highlights-strip">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="highlight-item">
                                    <div class="highlight-icon-wrap" style="background: #eef2ff; color: #4f46e5;">
                                        <i class="fa-solid fa-mobile-screen fs-5"></i>
                                    </div>
                                    <div class="highlight-text">
                                        <h6>In-App Deletion Available</h6>
                                        <p>Delete directly inside the mobile app anytime</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="highlight-item">
                                    <div class="highlight-icon-wrap" style="background: #fdf2f8; color: #db2777;">
                                        <i class="fa-solid fa-user-xmark fs-5"></i>
                                    </div>
                                    <div class="highlight-text">
                                        <h6>Complete Profile &amp; KYC Purge</h6>
                                        <p>Personal data removed from production servers</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="highlight-item">
                                    <div class="highlight-icon-wrap" style="background: #ecfdf5; color: #059669;">
                                        <i class="fa-solid fa-bolt fs-5"></i>
                                    </div>
                                    <div class="highlight-text">
                                        <h6>Instant Session Revocation</h6>
                                        <p>Immediate logout and security token termination</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PRIMARY SECTION: Step-by-Step Instructions -->
                    <div class="section-header-wrap">
                        <span class="section-eyebrow">Method 1 &bull; Direct &amp; Instant (Recommended)</span>
                        <h2 class="section-heading">How to Delete Your Account in the Mobile App</h2>
                        <p class="section-desc">Follow these 4 simple steps directly on your Android or iOS device using the Divy Shakti app.</p>
                    </div>

                    <!-- 4 Steps Visual Cards Grid -->
                    <div class="steps-container">
                        <!-- Step 1 -->
                        <div class="step-card">
                            <div class="step-top">
                                <div class="step-number-circle">1</div>
                                <div class="step-icon-wrap">
                                    <i class="fa-solid fa-right-to-bracket"></i>
                                </div>
                            </div>
                            <h4 class="step-title">Open App &amp; Sign In</h4>
                            <p class="step-text">
                                Launch the official <strong>Divy Shakti</strong> mobile application on your smartphone and log in to the account you wish to delete.
                            </p>
                        </div>

                        <!-- Step 2 -->
                        <div class="step-card">
                            <div class="step-top">
                                <div class="step-number-circle">2</div>
                                <div class="step-icon-wrap">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                            </div>
                            <h4 class="step-title">Go to Profile Tab</h4>
                            <p class="step-text">
                                Tap the <strong>Profile (Account)</strong> icon located on the far-right corner of the bottom navigation bar to open your dashboard.
                            </p>
                        </div>

                        <!-- Step 3 -->
                        <div class="step-card">
                            <div class="step-top">
                                <div class="step-number-circle">3</div>
                                <div class="step-icon-wrap">
                                    <i class="fa-solid fa-gear"></i>
                                </div>
                            </div>
                            <h4 class="step-title">Select Delete Account</h4>
                            <p class="step-text">
                                Scroll down through the menu list to find <strong>Delete Account</strong> or <strong>Privacy &amp; Security Settings</strong>.
                            </p>
                        </div>

                        <!-- Step 4 -->
                        <div class="step-card">
                            <div class="step-top">
                                <div class="step-number-circle">4</div>
                                <div class="step-icon-wrap">
                                    <i class="fa-solid fa-circle-check"></i>
                                </div>
                            </div>
                            <h4 class="step-title">Confirm Deletion</h4>
                            <p class="step-text">
                                Read the confirmation prompt regarding wallet balance and order history, then tap <strong>Confirm &amp; Delete Account</strong>.
                            </p>
                        </div>
                    </div>

                    <!-- SECONDARY METHOD: Request via Support -->
                    <div class="alt-request-card">
                        <span class="alt-request-badge">
                            <i class="fa-solid fa-envelope me-1"></i> Method 2 &bull; Alternative Request via Email / Support
                        </span>
                        <h4 class="fw-bold text-dark mb-2" style="font-size: 1.15rem;">Unable to Access the Mobile App?</h4>
                        <p class="text-muted small mb-0">
                            If you have uninstalled the application, changed your device, or cannot log in to your account, you can request account deletion directly by contacting our data protection support team:
                        </p>
                        <ol class="alt-step-list">
                            <li>
                                <strong>Send an Email:</strong> Write to our support desk at <a href="mailto:support@divyshakti.com" class="fw-semibold text-primary text-decoration-none">support@divyshakti.com</a> using your registered email address.
                            </li>
                            <li>
                                <strong>Subject Line:</strong> Use the subject: <code>Account Deletion Request - [Your Registered Mobile Number]</code>.
                            </li>
                            <li>
                                <strong>Required Information:</strong> Include your <strong>Full Name</strong>, <strong>Registered Mobile Number</strong>, and <strong>Member ID</strong> (if available).
                            </li>
                            <li>
                                <strong>Processing Time:</strong> Our privacy team will verify your identity and process the deletion within <strong>24 to 48 business hours</strong>. A final confirmation email will be sent upon completion.
                            </li>
                        </ol>
                    </div>

                    <!-- DATA TRANSPARENCY SECTION -->
                    <div class="section-header-wrap mt-5">
                        <span class="section-eyebrow">Privacy &amp; Compliance Transparency</span>
                        <h2 class="section-heading">What Data Is Erased vs. Retained</h2>
                        <p class="section-desc">In compliance with Google Play, Apple Developer guidelines, and Digital Personal Data Protection laws.</p>
                    </div>

                    <div class="data-policy-grid">
                        <!-- Data Deleted Box -->
                        <div class="policy-box policy-box-deleted">
                            <div class="policy-box-header">
                                <div class="policy-box-icon icon-deleted">
                                    <i class="fa-solid fa-trash-can"></i>
                                </div>
                                <div>
                                    <h4 class="policy-box-title text-danger">Data Permanently Deleted</h4>
                                    <span class="text-muted" style="font-size: 0.78rem;">Purged immediately from active servers</span>
                                </div>
                            </div>
                            <ul class="policy-list">
                                <li>
                                    <i class="fa-solid fa-circle-xmark text-danger"></i>
                                    <span><strong>Personal Profile:</strong> Full name, phone number, email address, physical addresses, and profile photo.</span>
                                </li>
                                <li>
                                    <i class="fa-solid fa-circle-xmark text-danger"></i>
                                    <span><strong>KYC Documents:</strong> Aadhaar number &amp; card images, PAN card images, and bank account records.</span>
                                </li>
                                <li>
                                    <i class="fa-solid fa-circle-xmark text-danger"></i>
                                    <span><strong>Authentication &amp; Device:</strong> Login tokens, passwords, push notification tokens, and device identifiers.</span>
                                </li>
                                <li>
                                    <i class="fa-solid fa-circle-xmark text-danger"></i>
                                    <span><strong>Network &amp; Affiliation:</strong> Your referral code is deactivated and direct affiliate tree links are permanently severed.</span>
                                </li>
                                <li>
                                    <i class="fa-solid fa-circle-xmark text-danger"></i>
                                    <span><strong>Digital Wallet:</strong> Wallet account access is revoked and non-withdrawn balances are permanently closed.</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Data Retained Box -->
                        <div class="policy-box policy-box-retained">
                            <div class="policy-box-header">
                                <div class="policy-box-icon icon-retained">
                                    <i class="fa-solid fa-shield-halved"></i>
                                </div>
                                <div>
                                    <h4 class="policy-box-title text-success">Statutory Data Retained</h4>
                                    <span class="text-muted" style="font-size: 0.78rem;">Retained strictly for legal &amp; tax compliance</span>
                                </div>
                            </div>
                            <ul class="policy-list">
                                <li>
                                    <i class="fa-solid fa-circle-check text-success"></i>
                                    <span><strong>Commercial Invoices:</strong> Records of completed orders and tax invoices must be retained under applicable Indian GST and taxation statutes.</span>
                                </li>
                                <li>
                                    <i class="fa-solid fa-circle-check text-success"></i>
                                    <span><strong>Financial Audit Trail:</strong> Historical transaction logs are held in encrypted, read-only archives for statutory financial auditing.</span>
                                </li>
                                <li>
                                    <i class="fa-solid fa-circle-check text-success"></i>
                                    <span><strong>Security Logs:</strong> Anonymized server logs are retained for a temporary period (30–90 days) for fraud prevention, then permanently overwritten.</span>
                                </li>
                                <li>
                                    <i class="fa-solid fa-circle-check text-success"></i>
                                    <span><strong>No Marketing Use:</strong> Retained records are strictly locked and never used for advertising, communications, or profiling.</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Important Consequences Warning -->
                    <div class="consequences-card">
                        <h5>
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            Important Notice Before Deleting Your Account
                        </h5>
                        <ul>
                            <li><strong>Irreversible Action:</strong> Once confirmed, account deletion cannot be undone. You cannot restore your previous rank, network, or data.</li>
                            <li><strong>Forfeiture of Wallet Balances:</strong> Any remaining digital wallet funds, pending payouts, or unwithdrawn commission balances are non-refundable and will be forfeited.</li>
                            <li><strong>Orders in Transit:</strong> Orders already placed and dispatched prior to deletion will still be completed and delivered to your specified shipping address.</li>
                        </ul>
                    </div>

                    <!-- Support Assistance Card -->
                    <div class="helpdesk-card">
                        <div class="row align-items-center">
                            <div class="col-lg-8 mb-3 mb-lg-0">
                                <h4 class="fw-bold mb-1" style="font-size: 1.2rem;">Have Questions or Need Help?</h4>
                                <p class="mb-0 text-white-50 small">
                                    Our data privacy and member support team is here to assist you Monday to Saturday (9:30 AM – 6:30 PM IST).
                                </p>
                            </div>
                            <div class="col-lg-4 text-lg-end">
                                <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                                    <a href="mailto:support@divyshakti.com" class="help-contact-btn">
                                        <i class="fa-solid fa-envelope text-primary"></i> Email Support
                                    </a>
                                    <a href="tel:+918160348894" class="help-contact-btn">
                                        <i class="fa-solid fa-phone text-success"></i> Call Helpline
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Frequently Asked Questions -->
                    <div class="section-header-wrap">
                        <span class="section-eyebrow">Common Inquiries</span>
                        <h2 class="section-heading">Frequently Asked Questions</h2>
                    </div>

                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    Can I recover my account or wallet balance after deletion?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="faqOne" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    No. Account deletion is permanent and cannot be reversed. Any remaining digital wallet balances, referral bonuses, commission points, and downline affiliations are permanently cancelled and forfeited upon deletion.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    What happens to orders that are currently being shipped?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="faqTwo" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Orders that have already been confirmed or shipped prior to deleting your account will proceed as scheduled. Delivery partners will complete delivery to your shipping address. Invoices for these orders are preserved in read-only archives for statutory taxation compliance.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Can I register again with the same mobile number in the future?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="faqThree" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes. Once your account data has been completely purged, your mobile number is released. You are welcome to create a brand-new account in the future as a new user.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    How long does the complete data erasure process take?
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="faqFour" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    When initiated in the mobile app, your account is deactivated immediately and access credentials are terminated instantaneously. Active production data records are purged within 24 to 48 hours, and rotating server backup cycles are fully cleared within 30 days.
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
                        <a href="<?php echo base_url('delete_account'); ?>" class="footer-link text-warning">Account Deletion Guide</a>
                        <span>&bull;</span>
                        <a href="<?php echo base_url('admin/login'); ?>" class="footer-link">Admin Portal</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>