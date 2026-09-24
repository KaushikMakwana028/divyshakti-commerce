<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page['title'] ?? 'Terms and Conditions'); ?> - Divy Shakti</title>
    <meta name="description" content="Official Terms and Conditions, Affiliate Guidelines, and E-Commerce User Agreement for Divy Shakti.">
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
            --brand-dark: #0f172a;
            --brand-bg: #f8fafc;
            --brand-card: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--brand-bg);
            color: var(--text-main);
            line-height: 1.75;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
            padding: 0;
        }

        /* Top Brand Navbar */
        .public-navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
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

        .legal-portal-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #f1f5f9;
            color: #475569;
            font-size: 0.78rem;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 50px;
            letter-spacing: 0.3px;
            border: 1px solid #e2e8f0;
        }

        .legal-portal-badge i {
            color: var(--brand-pink);
            font-size: 0.85rem;
        }

        /* Hero Header */
        .legal-hero {
            background: linear-gradient(135deg, #0b1120 0%, #161c2d 50%, #1e1b2e 100%);
            color: #ffffff;
            padding: 56px 0 68px;
            position: relative;
            overflow: hidden;
            text-align: center;
            border-bottom: 4px solid var(--brand-gold);
        }

        .legal-hero::after {
            content: '';
            position: absolute;
            top: -40%;
            right: -10%;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(236, 64, 122, 0.16) 0%, transparent 70%);
            pointer-events: none;
        }

        .legal-hero::before {
            content: '';
            position: absolute;
            bottom: -40%;
            left: -10%;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.14) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero-content-wrap {
            max-width: 760px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .hero-badge {
            background: rgba(236, 64, 122, 0.16);
            color: #fbcfe8;
            border: 1px solid rgba(236, 64, 122, 0.35);
            font-size: 0.78rem;
            font-weight: 600;
            padding: 6px 16px;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
            letter-spacing: 0.3px;
        }

        .legal-title {
            font-size: 2.35rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 12px;
            color: #ffffff;
        }

        .legal-subtitle {
            color: #94a3b8;
            font-size: 0.98rem;
            line-height: 1.6;
            margin-bottom: 18px;
        }

        .hero-meta {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 50px;
            padding: 5px 16px;
            font-size: 0.78rem;
            color: #cbd5e1;
        }

        .meta-dot {
            color: #64748b;
        }

        /* Content Card */
        .legal-card {
            background-color: var(--brand-card);
            border-radius: 20px;
            border: 1px solid rgba(226, 232, 240, 0.9);
            box-shadow: 0 12px 35px -8px rgba(15, 23, 42, 0.06), 0 4px 12px -2px rgba(15, 23, 42, 0.03);
            padding: 48px 52px;
            margin-top: -36px;
            margin-bottom: 70px;
            position: relative;
            z-index: 10;
        }

        /* Typography & Content Styling */
        .legal-content h2 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
            margin-top: 2.2rem;
            margin-bottom: 0.85rem;
            padding-bottom: 8px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
        }

        .legal-content h2::before {
            content: '';
            display: inline-block;
            width: 4px;
            height: 1.15rem;
            background: linear-gradient(135deg, var(--brand-pink), var(--brand-gold));
            border-radius: 4px;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .legal-content h2:first-of-type {
            margin-top: 0;
        }

        .legal-content h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
            margin-top: 1.5rem;
            margin-bottom: 0.65rem;
        }

        .legal-content p {
            color: #475569;
            font-size: 0.96rem;
            line-height: 1.8;
            margin-bottom: 1.1rem;
        }

        .legal-content strong {
            color: #0f172a;
            font-weight: 600;
        }

        .legal-content ul, .legal-content ol {
            margin-bottom: 1.4rem;
            padding-left: 1.4rem;
        }

        .legal-content li {
            color: #475569;
            font-size: 0.95rem;
            line-height: 1.75;
            margin-bottom: 0.55rem;
        }

        .legal-content li strong {
            color: #1e293b;
        }

        /* Card Bottom Verification Badge */
        .legal-card-footer {
            margin-top: 3.5rem;
            padding-top: 1.75rem;
            border-top: 1px dashed #e2e8f0;
            text-align: center;
        }

        .footer-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 7px 18px;
            border-radius: 50px;
            font-size: 0.84rem;
            font-weight: 500;
            color: #334155;
            margin-bottom: 6px;
        }

        .footer-note {
            font-size: 0.8rem;
            color: #94a3b8;
            margin: 0;
        }

        @media (max-width: 992px) {
            .legal-card {
                padding: 36px 30px;
            }
        }

        @media (max-width: 768px) {
            .legal-card {
                padding: 26px 20px;
                border-radius: 16px;
                margin-top: -24px;
                margin-bottom: 40px;
            }
            .legal-title {
                font-size: 1.8rem;
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
            <div class="d-none d-sm-flex align-items-center">
                <span class="legal-portal-badge">
                    <i class="fa-solid fa-file-contract"></i>
                    <span>Official Agreement</span>
                </span>
            </div>
        </div>
    </header>

    <!-- Hero Banner -->
    <div class="legal-hero">
        <div class="container">
            <div class="hero-content-wrap">
                <span class="hero-badge">
                    <i class="fa-solid fa-scale-balanced"></i> User Agreement &amp; Operating Guidelines
                </span>
                <h1 class="legal-title"><?php echo htmlspecialchars($page['title'] ?? 'Terms and Conditions'); ?></h1>
                <p class="legal-subtitle">Rules, affiliate protocols, wallet terms, and user agreements for Divy Shakti members.</p>
                <div class="hero-meta">
                    <span><i class="fa-regular fa-clock me-1"></i> Effective Date: <?php echo date('F Y'); ?></span>
                    <span class="meta-dot">&bull;</span>
                    <span><i class="fa-solid fa-building-shield me-1"></i> Divy Shakti Commerce</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Body -->
    <main class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">
                <div class="legal-card">
                    <!-- Render Dynamic or Default HTML Content -->
                    <div class="legal-content">
                        <?php echo $page['content']; ?>
                    </div>

                    <!-- Verified Official Badge (No buttons, no redirection links) -->
                    <div class="legal-card-footer">
                        <div class="footer-badge">
                            <i class="fa-solid fa-circle-check text-success"></i>
                            <span>Official Regulatory Agreement &bull; <strong>Divy Shakti Commerce</strong></span>
                        </div>
                        <p class="footer-note">Published by Divy Shakti Legal &amp; Compliance Team &bull; All rights reserved</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
