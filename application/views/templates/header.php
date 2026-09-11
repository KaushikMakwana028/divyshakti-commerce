<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Divy Shakti - Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Custom SweetAlert2 Theme -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/sweetalert-theme.css'); ?>">

    <style>
        :root {
            --primary-pink: #ec407a;
            --primary-pink-soft: rgba(236, 64, 122, .1);
            --primary-gold: #d4af37;
            --primary-gold-hover: #c5a059;
            --dark-sidebar: #111827;
            --dark-sidebar-alt: #1a2233;
            --light-bg: #f3f4f6;
            --border-soft: #e5e7eb;
            --text-muted: #6b7280;
            --radius: 12px;
            --radius-sm: 8px;
            --shadow-sm: 0 1px 2px rgba(17,24,39,.05);
            --shadow-md: 0 10px 15px -3px rgba(17,24,39,.08), 0 4px 6px -2px rgba(17,24,39,.04);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-bg);
            overflow-x: hidden;
        }

        /* Scrollbar polish */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-thumb { background: rgba(0,0,0,.15); border-radius: 10px; }
        ::-webkit-scrollbar-track { background: transparent; }

        /* Focus visibility for accessibility */
        a:focus-visible, button:focus-visible {
            outline: 2px solid var(--primary-gold);
            outline-offset: 2px;
        }

        /* ================= Sidebar ================= */
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: linear-gradient(180deg, var(--dark-sidebar) 0%, var(--dark-sidebar-alt) 100%);
            color: #9ca3af;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            z-index: 1000;
            box-shadow: 4px 0 20px rgba(0,0,0,.15);
            transition: left .28s ease;
        }

        .sidebar-brand {
            padding: 22px 20px;
            border-bottom: 1px solid rgba(255,255,255,.06);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-brand img {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 2px solid var(--primary-gold);
            flex-shrink: 0;
            object-fit: cover;
        }

        .brand-name {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: #ffffff;
            font-size: 1.1rem;
            letter-spacing: .5px;
            line-height: 1.15;
            background: linear-gradient(45deg, var(--primary-pink), var(--primary-gold));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .sidebar-scroll {
            flex-grow: 1;
            overflow-y: auto;
            padding: 18px 0 10px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0 14px;
            margin: 0;
        }

        .sidebar-menu li { margin-bottom: 3px; }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 11px 14px;
            color: #9ca3af;
            text-decoration: none;
            font-size: .9rem;
            font-weight: 500;
            border-radius: var(--radius-sm);
            transition: all .2s ease;
            position: relative;
        }

        .sidebar-menu a i {
            width: 18px;
            text-align: center;
            font-size: .95rem;
            color: #6b7280;
            transition: color .2s ease;
        }

        .sidebar-menu a:hover {
            color: #ffffff;
            background-color: rgba(255,255,255,.05);
        }

        .sidebar-menu a:hover i { color: var(--primary-gold); }

        .sidebar-menu li.active a {
            color: #ffffff;
            background: linear-gradient(90deg, rgba(236,64,122,.18), rgba(212,175,55,.06));
        }

        .sidebar-menu li.active a i { color: var(--primary-gold); }

        .sidebar-menu li.active a::before {
            content: '';
            position: absolute;
            left: -14px;
            top: 8px;
            bottom: 8px;
            width: 3px;
            border-radius: 0 4px 4px 0;
            background: linear-gradient(180deg, var(--primary-pink), var(--primary-gold));
        }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid rgba(255,255,255,.06);
        }

        .btn-logout-sidebar {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 10px;
            border: 1px solid rgba(236,64,122,.35);
            border-radius: var(--radius-sm);
            color: var(--primary-pink);
            text-decoration: none;
            font-weight: 600;
            font-size: .88rem;
            transition: all .2s ease;
        }

        .btn-logout-sidebar:hover {
            background-color: var(--primary-pink);
            border-color: var(--primary-pink);
            color: #ffffff;
        }

        /* ================= Main content / header ================= */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left .28s ease;
        }

        .top-header {
            height: 68px;
            background-color: #ffffff;
            border-bottom: 1px solid var(--border-soft);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 0 24px;
            position: sticky;
            top: 0;
            z-index: 900;
            box-shadow: var(--shadow-sm);
        }

        #sidebarToggle {
            font-size: 1.2rem;
            color: var(--dark-sidebar);
            padding: 6px 10px;
            border-radius: var(--radius-sm);
            text-decoration: none;
        }
        #sidebarToggle:hover { background-color: var(--light-bg); }

        .breadcrumb-container {
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .breadcrumb-container a {
            text-decoration: none;
            color: var(--text-muted);
            font-size: .85rem;
            font-weight: 500;
            transition: color .2s ease;
        }
        .breadcrumb-container a:hover { color: var(--primary-pink); }

        .breadcrumb-container .divider {
            color: #d1d5db;
            font-size: .8rem;
        }

        .breadcrumb-container span.current {
            color: var(--dark-sidebar);
            font-size: .85rem;
            font-weight: 700;
        }

        /* User dropdown trigger */
        .user-info-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            background: none;
            padding: 5px 14px 5px 6px;
            border-radius: 50px;
            border: 1px solid var(--border-soft);
            transition: all .2s ease;
        }

        .user-info-btn:hover {
            background-color: var(--light-bg);
            border-color: #d1d5db;
        }

        .user-info-btn img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 1.5px solid var(--primary-gold);
        }

        .user-info-btn span {
            font-size: .87rem;
            font-weight: 600;
            color: #374151;
        }

        .user-dropdown-menu {
            width: 280px;
            border: none;
            box-shadow: var(--shadow-md);
            border-radius: var(--radius);
            padding: 0;
            margin-top: 12px !important;
            overflow: hidden;
        }

        .dropdown-header-custom {
            padding: 22px 20px;
            background: linear-gradient(135deg, var(--dark-sidebar) 0%, var(--dark-sidebar-alt) 100%);
            color: #ffffff;
            text-align: center;
            border-bottom: 2px solid var(--primary-gold);
        }

        .dropdown-header-custom img {
            width: 58px;
            height: 58px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
            border: 2px solid var(--primary-gold);
        }

        .dropdown-header-custom h6 {
            margin: 0;
            font-weight: 700;
            color: #ffffff;
            font-size: .95rem;
        }

        .dropdown-header-custom p {
            margin: 2px 0 0;
            font-size: .78rem;
            color: #9ca3af;
        }

        .dropdown-header-custom .badge-role {
            display: inline-block;
            margin-top: 8px;
            font-size: .68rem;
            background-color: rgba(212,175,55,.15);
            color: var(--primary-gold);
            border: 1px solid rgba(212,175,55,.5);
            padding: 3px 10px;
            border-radius: 50px;
            font-weight: 700;
            letter-spacing: .03em;
        }

        .dropdown-item-custom {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: #4b5563;
            font-size: .87rem;
            font-weight: 500;
            text-decoration: none;
            transition: all .2s ease;
            border-bottom: 1px solid #f3f4f6;
        }

        .dropdown-item-custom:last-child { border-bottom: none; }

        .dropdown-item-custom:hover {
            background-color: #fafafa;
            color: var(--primary-pink);
        }

        .dropdown-item-custom i {
            font-size: .95rem;
            width: 18px;
            text-align: center;
            color: #9ca3af;
        }

        .dropdown-item-custom:hover i { color: var(--primary-gold); }

        /* ================= Content container ================= */
        .content-body {
            padding: 28px;
            flex-grow: 1;
        }
        @media(max-width: 575.98px) {
            .content-body { padding: 14px; }
            .top-header { padding: 0 14px; }
        }

        .sidebar-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0,0,0,.5);
            backdrop-filter: blur(1px);
            z-index: 998;
            opacity: 0;
            visibility: hidden;
            transition: opacity .28s ease, visibility .28s ease;
        }
        .sidebar-backdrop.show {
            opacity: 1;
            visibility: visible;
        }

        /* ================= Responsive sidebar ================= */
        @media(max-width: 991.98px) {
            .sidebar {
                left: -260px;
                z-index: 1000;
            }
            .sidebar.show {
                left: 0;
                box-shadow: 4px 0 30px rgba(0,0,0,.35);
            }
            .main-content,
            .main-content.shifted {
                margin-left: 0 !important;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar Layout -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Divy Shakti Logo">
            <span class="brand-name">DIVY SHAKTI</span>
        </div>

        <div class="sidebar-scroll">
            <ul class="sidebar-menu">
                <li class="<?php echo ($this->uri->segment(1) == 'admin' && $this->uri->segment(2) == 'dashboard') ? 'active' : ''; ?>">
                    <a href="<?php echo base_url('admin/dashboard'); ?>">
                        <i class="fa-solid fa-gauge"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <?php if ((int)$user->role === 1): ?>
                    <li class="<?php echo ($this->uri->segment(1) == 'admin' && $this->uri->segment(2) == 'categories') ? 'active' : ''; ?>">
                        <a href="<?php echo base_url('admin/categories'); ?>">
                            <i class="fa-solid fa-folder-tree"></i>
                            <span>Categories</span>
                        </a>
                    </li>
                    <li class="<?php echo ($this->uri->segment(1) == 'admin' && $this->uri->segment(2) == 'products') ? 'active' : ''; ?>">
                        <a href="<?php echo base_url('admin/products'); ?>">
                            <i class="fa-solid fa-boxes-stacked"></i>
                            <span>Products</span>
                        </a>
                    </li>
                    <li class="<?php echo ($this->uri->segment(1) == 'admin' && $this->uri->segment(2) == 'members' && $this->uri->segment(3) != 'network') ? 'active' : ''; ?>">
                        <a href="<?php echo base_url('admin/members'); ?>">
                            <i class="fa-solid fa-users"></i>
                            <span>Members</span>
                        </a>
                    </li>
                    <li class="<?php echo ($this->uri->segment(1) == 'admin' && $this->uri->segment(2) == 'members' && $this->uri->segment(3) == 'network') ? 'active' : ''; ?>">
                        <a href="<?php echo base_url('admin/members/network'); ?>">
                            <i class="fa-solid fa-sitemap"></i>
                            <span>Referral Network</span>
                        </a>
                    </li>
                    <li class="<?php echo ($this->uri->segment(1) == 'admin' && $this->uri->segment(2) == 'orders') ? 'active' : ''; ?>">
                        <a href="<?php echo base_url('admin/orders'); ?>">
                            <i class="fa-solid fa-cart-shopping"></i>
                            <span>Orders</span>
                        </a>
                    </li>
                    <li class="<?php echo ($this->uri->segment(1) == 'admin' && $this->uri->segment(2) == 'commissions') ? 'active' : ''; ?>">
                        <a href="<?php echo base_url('admin/commissions'); ?>">
                            <i class="fa-solid fa-percent"></i>
                            <span>Commissions</span>
                        </a>
                    </li>
                    <li class="<?php echo ($this->uri->segment(1) == 'admin' && $this->uri->segment(2) == 'deposits') ? 'active' : ''; ?>">
                        <a href="<?php echo base_url('admin/deposits'); ?>">
                            <i class="fa-solid fa-money-bill-transfer"></i>
                            <span>Deposits</span>
                        </a>
                    </li>
                    <li class="<?php echo ($this->uri->segment(1) == 'admin' && $this->uri->segment(2) == 'cms') ? 'active' : ''; ?>">
                        <a href="<?php echo base_url('admin/cms'); ?>">
                            <i class="fa-solid fa-file-contract"></i>
                            <span>Policy &amp; Terms CMS</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="sidebar-footer">
            <a href="<?php echo base_url('admin/logout'); ?>" class="btn-logout-sidebar">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Sign Out</span>
            </a>
        </div>
    </div>

    <!-- Sidebar Backdrop for Mobile Overlay -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- Main Content Wrapper -->
    <div class="main-content" id="main-content">
        <!-- Top Navigation Header -->
        <header class="top-header">
            <!-- Sidebar toggle button for mobile -->
            <button class="btn btn-link text-dark d-lg-none" id="sidebarToggle">
                <i class="fa-solid fa-bars fs-4"></i>
            </button>

            <!-- Breadcrumbs -->
            <div class="breadcrumb-container d-none d-sm-flex">
                <a href="<?php echo base_url('admin/dashboard'); ?>">Admin</a>
                <span class="divider">/</span>
                <span class="current"><?php echo ucfirst($this->uri->segment(2) ?: 'Dashboard'); ?></span>
            </div>

            <!-- User Dropdown Menu -->
            <div class="dropdown">
                <button class="user-info-btn dropdown-toggle" type="button" id="userProfileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="<?php echo $user->profile_image ? base_url($user->profile_image) : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user->email ?? ''))) . '?d=mp'; ?>" alt="User Avatar">
                    <span><?php echo htmlspecialchars($user->name ?? 'User'); ?></span>
                </button>
                <div class="dropdown-menu dropdown-menu-end user-dropdown-menu" aria-labelledby="userProfileDropdown">
                    <div class="dropdown-header-custom">
                        <img src="<?php echo $user->profile_image ? base_url($user->profile_image) : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user->email ?? ''))) . '?d=mp'; ?>" alt="User Avatar Large">
                        <h6><?php echo htmlspecialchars($user->name ?? 'User'); ?></h6>
                        <p><?php echo htmlspecialchars($user->email ?? ($user->phone ?? '')); ?></p>
                        <span class="badge-role">
                            <?php echo ((int)$user->role === 1) ? 'Admin Role' : 'User Role'; ?>
                        </span>
                    </div>

                    <a href="<?php echo base_url('admin/profile'); ?>" class="dropdown-item-custom">
                        <i class="fa-solid fa-user-gear"></i>
                        <span>Profile Settings</span>
                    </a>
                    <a href="<?php echo base_url('admin/profile#password'); ?>" class="dropdown-item-custom">
                        <i class="fa-solid fa-key"></i>
                        <span>Change Password</span>
                    </a>
                    <a href="<?php echo base_url('admin/logout'); ?>" class="dropdown-item-custom text-danger">
                        <i class="fa-solid fa-right-from-bracket text-danger"></i>
                        <span>Sign Out</span>
                    </a>
                </div>
            </div>
        </header>
        <!-- Content Container -->
        <main class="content-body">