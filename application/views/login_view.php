<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Divy Shakti - Sign In</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- SweetAlert2 CDN & Custom Theme -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/sweetalert-theme.css'); ?>">
    <script src="<?php echo base_url('assets/js/sweetalert-custom.js'); ?>"></script>
    
    <style>
        :root {
            --primary-pink: #ec407a;
            --primary-gold: #d4af37;
            --primary-gold-hover: #c5a059;
            --dark-bg: #111827;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--dark-bg) 0%, #1f2937 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            width: 100%;
            max-width: 450px;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            border: 2px solid var(--primary-gold);
            overflow: hidden;
        }

        .login-header {
            padding: 30px 20px 20px 20px;
            text-align: center;
            background-color: #ffffff;
            border-bottom: 1px solid #f3f4f6;
        }

        .login-header img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 3px solid var(--primary-gold);
            margin-bottom: 15px;
        }

        .login-header h2 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            margin-bottom: 5px;
            background: linear-gradient(45deg, var(--primary-pink), var(--primary-gold));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .login-header p {
            color: #6b7280;
            font-size: 0.9rem;
            margin: 0;
        }

        .login-body {
            padding: 30px;
        }

        .form-label {
            font-weight: 500;
            color: #374151;
            font-size: 0.9rem;
        }

        .input-group-text {
            background-color: #f9fafb;
            border-color: #d1d5db;
            color: #9ca3af;
        }

        .form-control {
            border-color: #d1d5db;
            font-size: 0.95rem;
        }

        .form-control:focus {
            border-color: var(--primary-pink);
            box-shadow: 0 0 0 0.2rem rgba(236, 64, 122, 0.15);
        }

        .btn-login {
            background: linear-gradient(45deg, var(--primary-pink), var(--primary-gold));
            border: none;
            color: #ffffff;
            font-weight: 600;
            padding: 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
        }

        .btn-login:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .login-footer {
            padding: 20px;
            text-align: center;
            background-color: #f9fafb;
            border-top: 1px solid #f3f4f6;
            font-size: 0.9rem;
        }

        .login-footer a {
            color: var(--primary-pink);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .login-footer a:hover {
            color: var(--primary-gold);
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-header">
            <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Divy Shakti Logo">
            <h2>DIVY SHAKTI</h2>
            <p>Sign in to your account</p>
        </div>
        
        <div class="login-body">
            <form action="<?php echo base_url('admin/login'); ?>" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                        <input type="email" name="email" id="email" class="form-control" placeholder="name@example.com" value="<?php echo set_value('email'); ?>" required>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-login w-100 mb-3">
                    <i class="fa-solid fa-right-to-bracket me-2"></i> Sign In
                </button>
            </form>
        </div>
        
        <div class="login-footer">
            Don't have an account? <a href="<?php echo base_url('admin/register'); ?>">Register here</a>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert Flash & Validation Handlers -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($this->session->flashdata('success')): ?>
                dsAlert({
                    icon: 'success',
                    title: 'Success!',
                    text: '<?php echo addslashes(htmlspecialchars($this->session->flashdata('success'))); ?>'
                });
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                dsAlert({
                    icon: 'error',
                    title: 'Sign In Failed',
                    text: '<?php echo addslashes(htmlspecialchars($this->session->flashdata('error'))); ?>'
                });
            <?php endif; ?>

            <?php if (validation_errors()): ?>
                dsAlert({
                    icon: 'error',
                    title: 'Validation Error',
                    html: '<?php echo addslashes(str_replace(["\r", "\n"], ' ', validation_errors('<div style="text-align:left; margin-bottom:4px;"><i class="fa-solid fa-circle-exclamation text-danger me-2"></i>', '</div>'))); ?>'
                });
            <?php endif; ?>
        });
    </script>
</body>
</html>
