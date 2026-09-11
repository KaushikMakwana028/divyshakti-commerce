<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page['title'] ?? 'Privacy Policy'); ?> - Divy Shakti</title>
    <meta name="description" content="Official Privacy Policy and Data Protection guidelines for Divy Shakti E-Commerce and Affiliate Network.">
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
            --brand-dark: #161c2d;
            --brand-bg: #f8fafc;
            --brand-card: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
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
            background: linear-gradient(135deg, var(--brand-dark) 0%, #1e293b 100%);
            color: #ffffff;
            padding: 48px 0 40px;
            position: relative;
            overflow: hidden;
            border-bottom: 4px solid var(--brand-gold);
        }

        .legal-hero::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(236,64,122,0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero-badge {
            background: rgba(212, 175, 55, 0.18);
            color: #fce79a;
            border: 1px solid rgba(212, 175, 55, 0.35);
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
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            padding: 40px;
            margin-top: -24px;
            margin-bottom: 60px;
            position: relative;
            z-index: 10;
        }

        .cms-legal-doc h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--brand-dark);
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
            padding-bottom: 6px;
            border-bottom: 2px solid #f1f5f9;
        }

        .cms-legal-doc p, .cms-legal-doc li {
            color: #334155;
            font-size: 0.96rem;
        }

        .cms-legal-doc ul {
            padding-left: 1.25rem;
            margin-bottom: 1rem;
        }

        .cms-legal-doc li {
            margin-bottom: 0.4rem;
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
                <a href="<?php echo base_url('terms_conditions'); ?>" class="btn btn-sm btn-outline-secondary px-3 py-1.5" style="border-radius: 8px; font-size: 0.82rem;">
                    <i class="fa-solid fa-file-contract me-1"></i> Terms
                </a>
                <a href="<?php echo base_url('delete_account'); ?>" class="btn btn-sm btn-outline-danger px-3 py-1.5" style="border-radius: 8px; font-size: 0.82rem;">
                    <i class="fa-solid fa-user-xmark me-1"></i> Delete Account
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Banner -->
    <div class="legal-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="hero-badge">
                        <i class="fa-solid fa-shield-check"></i> Official Privacy &amp; Data Protection Document
                    </span>
                    <h1 class="legal-title"><?php echo htmlspecialchars($page['title'] ?? 'Privacy Policy'); ?></h1>
                    <p class="legal-subtitle">Transparency, data protection, and user privacy governance for Divy Shakti members.</p>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <button class="btn btn-sm btn-light px-3 py-2 text-dark fw-semibold" style="border-radius: 8px;" onclick="window.print()">
                        <i class="fa-solid fa-print me-1"></i> Print / Save PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Body -->
    <main class="container">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="legal-card">
                    <!-- Render Dynamic or Default HTML Content -->
                    <?php echo $page['content']; ?>

                    <!-- Bottom Nav Links -->
                    <div class="mt-5 pt-4 border-top d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <span class="text-muted small">
                            <i class="fa-solid fa-circle-check text-success me-1"></i> Published by Divy Shakti Legal &amp; Compliance Team
                        </span>
                        <div class="d-flex gap-3">
                            <a href="<?php echo base_url('terms_conditions'); ?>" class="text-primary small fw-semibold text-decoration-none">
                                View Terms &amp; Conditions &rarr;
                            </a>
                            <a href="<?php echo base_url('delete_account'); ?>" class="text-danger small fw-semibold text-decoration-none">
                                Account Deletion Request &rarr;
                            </a>
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

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
