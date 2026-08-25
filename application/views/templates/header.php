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
    
    <style>
        :root {
            --primary-pink: #ec407a;
            --primary-gold: #d4af37;
            --primary-gold-hover: #c5a059;
            --dark-sidebar: #111827;
            --light-bg: #f3f4f6;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-bg);
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--dark-sidebar);
            color: #9ca3af;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            z-index: 1000;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
            transition: left 0.3s ease;
        }

        .sidebar-brand {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-brand img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border: 2px solid var(--primary-gold);
        }

        .brand-name {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: #ffffff;
            font-size: 1.15rem;
            letter-spacing: 1px;
            background: linear-gradient(45deg, var(--primary-pink), var(--primary-gold));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .sidebar-menu {
            list-style: none;
            padding: 20px 0;
            margin: 0;
            flex-grow: 1;
        }

        .sidebar-menu li {
            margin-bottom: 5px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 25px;
            color: #9ca3af;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .sidebar-menu a:hover, .sidebar-menu li.active a {
            color: #ffffff;
            background-color: rgba(236, 64, 122, 0.1);
            border-left: 4px solid var(--primary-pink);
        }

        .sidebar-menu a:hover i, .sidebar-menu li.active a i {
            color: var(--primary-gold);
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        .btn-logout-sidebar {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 10px;
            border: 1px solid rgba(236, 64, 122, 0.4);
            border-radius: 6px;
            color: var(--primary-pink);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-logout-sidebar:hover {
            background-color: var(--primary-pink);
            color: #ffffff;
        }

        /* Top Header Styling */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        .top-header {
            height: 70px;
            background-color: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .breadcrumb-container {
            margin: 0;
            padding: 0;
        }

        .breadcrumb-container a {
            text-decoration: none;
            color: #6b7280;
            font-size: 0.9rem;
        }

        .breadcrumb-container span {
            color: var(--primary-pink);
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* User Profile Dropdown */
        .user-profile-dropdown {
            cursor: pointer;
        }

        .user-info-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            background: none;
            border: none;
            padding: 6px 12px;
            border-radius: 50px;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .user-info-btn:hover {
            background-color: #f9fafb;
        }

        .user-info-btn img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid var(--primary-gold);
        }

        .user-info-btn span {
            font-size: 0.9rem;
            font-weight: 600;
            color: #374151;
        }

        .user-dropdown-menu {
            width: 280px;
            border: none;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            padding: 0;
            margin-top: 10px !important;
            overflow: hidden;
        }

        .dropdown-header-custom {
            padding: 20px;
            background: linear-gradient(135deg, var(--dark-sidebar) 0%, #1f2937 100%);
            color: #ffffff;
            text-align: center;
            border-bottom: 2px solid var(--primary-gold);
        }

        .dropdown-header-custom img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
            border: 2px solid var(--primary-gold);
        }

        .dropdown-header-custom h6 {
            margin: 0;
            font-weight: 600;
            color: #ffffff;
        }

        .dropdown-header-custom p {
            margin: 0;
            font-size: 0.8rem;
            color: #9ca3af;
        }

        .dropdown-header-custom .badge-role {
            display: inline-block;
            margin-top: 5px;
            font-size: 0.7rem;
            background-color: rgba(212, 175, 55, 0.2);
            color: var(--primary-gold);
            border: 1px solid var(--primary-gold);
            padding: 2px 8px;
            border-radius: 50px;
            font-weight: 600;
        }

        .dropdown-item-custom {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: #4b5563;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.2s ease;
            border-bottom: 1px solid #f3f4f6;
        }

        .dropdown-item-custom:last-child {
            border-bottom: none;
        }

        .dropdown-item-custom:hover {
            background-color: #f9fafb;
            color: var(--primary-pink);
        }

        .dropdown-item-custom i {
            font-size: 1rem;
            width: 20px;
            color: #9ca3af;
        }

        .dropdown-item-custom:hover i {
            color: var(--primary-gold);
        }

        /* Content Container */
        .content-body {
            padding: 30px;
            flex-grow: 1;
        }
        @media(max-width: 575.98px) {
            .content-body {
                padding: 15px;
            }
        }

        .sidebar-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 998;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        .sidebar-backdrop.show {
            opacity: 1;
            visibility: visible;
        }

        /* Responsive sidebar */
        @media(max-width: 991.98px) {
            .sidebar {
                left: -260px;
                z-index: 1000;
            }
            .sidebar.show {
                left: 0;
            }
            .main-content {
                margin-left: 0 !important;
            }
            .main-content.shifted {
                margin-left: 0 !important;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar Layout -->
    <div class="sidebar" id="sidebar">
        <div>
            <div class="sidebar-brand">
                <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Divy Shakti Logo">
                <span class="brand-name">DIVY SHAKTI</span>
            </div>
            
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
                    <li class="<?php echo ($this->uri->segment(1) == 'admin' && $this->uri->segment(2) == 'members') ? 'active' : ''; ?>">
                        <a href="<?php echo base_url('admin/members'); ?>">
                            <i class="fa-solid fa-users"></i>
                            <span>Members</span>
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
            <div class="breadcrumb-container d-none d-sm-block">
                <a href="<?php echo base_url('admin/dashboard'); ?>">Admin</a> 
                <span class="mx-2">/</span> 
                <span><?php echo ucfirst($this->uri->segment(2) ?: 'Dashboard'); ?></span>
            </div>
            
            <!-- User Dropdown Menu -->
            <div class="dropdown">
                <button class="user-info-btn dropdown-toggle" type="button" id="userProfileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="<?php echo $user->profile_image ? base_url($user->profile_image) : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user->email))) . '?d=mp'; ?>" alt="User Avatar">
                    <span><?php echo htmlspecialchars($user->name); ?></span>
                </button>
                <div class="dropdown-menu dropdown-menu-end user-dropdown-menu" aria-labelledby="userProfileDropdown">
                    <div class="dropdown-header-custom">
                        <img src="<?php echo $user->profile_image ? base_url($user->profile_image) : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user->email))) . '?d=mp'; ?>" alt="User Avatar Large">
                        <h6><?php echo htmlspecialchars($user->name); ?></h6>
                        <p><?php echo htmlspecialchars($user->email); ?></p>
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
