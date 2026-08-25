<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Divy Shakti - Clothing for Every You</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-pink: #ec407a;
            --primary-gold: #d4af37;
            --primary-gold-hover: #c5a059;
            --dark-bg: #111827;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: #374151;
        }

        /* Navbar Styling */
        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 15px 0;
            border-bottom: 2px solid var(--primary-gold);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border: 2px solid var(--primary-gold);
        }

        .navbar-brand-text {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.3rem;
            letter-spacing: 1px;
            background: linear-gradient(45deg, var(--primary-pink), var(--primary-gold));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-link {
            font-weight: 500;
            color: #4b5563 !important;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-pink) !important;
        }

        .btn-nav-login {
            border: 1px solid var(--primary-pink);
            color: var(--primary-pink) !important;
            border-radius: 50px;
            padding: 8px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-nav-login:hover {
            background-color: var(--primary-pink);
            color: #ffffff !important;
        }

        .btn-nav-register {
            background: linear-gradient(45deg, var(--primary-pink), var(--primary-gold));
            color: #ffffff !important;
            border-radius: 50px;
            padding: 8px 25px;
            font-weight: 500;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-nav-register:hover {
            opacity: 0.95;
            transform: translateY(-1px);
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(17, 24, 39, 0.75), rgba(17, 24, 39, 0.75)), url('https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&w=1920&q=80') no-repeat center center;
            background-size: cover;
            min-height: 80vh;
            display: flex;
            align-items: center;
            color: #ffffff;
            text-align: center;
            border-bottom: 5px solid var(--primary-gold);
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 20px;
            background: linear-gradient(45deg, #ffffff, var(--primary-gold));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            font-weight: 300;
            letter-spacing: 2px;
            color: #e5e7eb;
            margin-bottom: 35px;
            text-transform: uppercase;
        }

        .btn-hero {
            background: linear-gradient(45deg, var(--primary-pink), var(--primary-gold));
            color: #ffffff;
            border: none;
            padding: 15px 40px;
            font-weight: 600;
            border-radius: 50px;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(236, 64, 122, 0.4);
            transition: all 0.3s ease;
        }

        .btn-hero:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(236, 64, 122, 0.6);
            color: #ffffff;
        }

        /* Feature/Category Cards */
        .category-section {
            padding: 80px 0;
            background-color: #fafafa;
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-header h2 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: var(--dark-bg);
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
        }

        .section-header h2::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 2px;
            background-color: var(--primary-gold);
        }

        .category-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            background-color: #ffffff;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .category-img-wrapper {
            height: 350px;
            overflow: hidden;
            position: relative;
        }

        .category-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .category-card:hover .category-img-wrapper img {
            transform: scale(1.05);
        }

        .category-info {
            padding: 25px;
            text-align: center;
            border-top: 2px solid var(--primary-gold);
        }

        .category-info h4 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: var(--dark-bg);
            margin-bottom: 10px;
        }

        /* Footer */
        footer {
            background-color: var(--dark-bg);
            color: #9ca3af;
            padding: 40px 0;
            border-top: 3px solid var(--primary-gold);
            text-align: center;
        }

        footer h5 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 20px;
        }

        footer .social-icons a {
            color: #ffffff;
            font-size: 1.25rem;
            margin: 0 10px;
            transition: color 0.3s ease;
        }

        footer .social-icons a:hover {
            color: var(--primary-pink);
        }
    </style>
</head>
<body>

    <!-- Navigation Header -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="<?php echo base_url(); ?>">
                <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Divy Shakti Logo">
                <span class="navbar-brand-text">DIVY SHAKTI</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <!-- Nav links placeholder -->
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Banner -->
    <section class="hero-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h1 class="hero-title">DIVY SHAKTI</h1>
                    <p class="hero-subtitle">Clothing for Every You</p>
                    <a href="#collections" class="btn btn-hero">Explore Collection <i class="fa-solid fa-arrow-down ms-2"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Categories Section -->
    <section id="collections" class="category-section">
        <div class="container">
            <div class="section-header">
                <h2>Our Collections</h2>
                <p class="text-muted">Discover exquisite fabrics and designs curated especially for you.</p>
            </div>
            
            <div class="row g-4">
                <!-- Ethnic Wear -->
                <div class="col-md-4">
                    <div class="category-card">
                        <div class="category-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&w=600&q=80" alt="Ethnic Collection">
                        </div>
                        <div class="category-info">
                            <h4>Ethnic Wear</h4>
                            <p class="text-muted">Traditional designs infusing heritage with style</p>
                        </div>
                    </div>
                </div>
                
                <!-- Designer Sarees -->
                <div class="col-md-4">
                    <div class="category-card">
                        <div class="category-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?auto=format&fit=crop&w=600&q=80" alt="Designer Sarees">
                        </div>
                        <div class="category-info">
                            <h4>Designer Sarees</h4>
                            <p class="text-muted">Elegant drapes for festive & special occasions</p>
                        </div>
                    </div>
                </div>
                
                <!-- Bridal Lehenga -->
                <div class="col-md-4">
                    <div class="category-card">
                        <div class="category-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1595777457583-95e059d581b8?auto=format&fit=crop&w=600&q=80" alt="Bridal Couture">
                        </div>
                        <div class="category-info">
                            <h4>Bridal Couture</h4>
                            <p class="text-muted">Masterpiece creations for your magical moments</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <h5>DIVY SHAKTI</h5>
            <p class="mb-3">CLOTHING FOR EVERY YOU</p>
            <div class="social-icons mb-4">
                <a href="#"><i class="fa-brands fa-facebook"></i></a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-brands fa-pinterest"></i></a>
            </div>
            <p class="small text-muted mb-0">&copy; <?php echo date('Y'); ?> Divy Shakti. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
