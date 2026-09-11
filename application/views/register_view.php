<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Divy Shakti - Register</title>
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
            padding: 30px 20px;
        }

        .register-card {
            width: 100%;
            max-width: 550px;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            border: 2px solid var(--primary-gold);
            overflow: hidden;
        }

        .register-header {
            padding: 25px 20px 15px 20px;
            text-align: center;
            background-color: #ffffff;
            border-bottom: 1px solid #f3f4f6;
        }

        .register-header img {
            width: 75px;
            height: 75px;
            border-radius: 50%;
            border: 2px solid var(--primary-gold);
            margin-bottom: 10px;
        }

        .register-header h2 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            margin-bottom: 5px;
            background: linear-gradient(45deg, var(--primary-pink), var(--primary-gold));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .register-header p {
            color: #6b7280;
            font-size: 0.85rem;
            margin: 0;
        }

        .register-body {
            padding: 25px 30px;
        }

        .form-label {
            font-weight: 500;
            color: #374151;
            font-size: 0.85rem;
            margin-bottom: 6px;
        }

        .input-group-text {
            background-color: #f9fafb;
            border-color: #d1d5db;
            color: #9ca3af;
            font-size: 0.9rem;
        }

        .form-control {
            border-color: #d1d5db;
            font-size: 0.9rem;
        }

        .form-control:focus {
            border-color: var(--primary-pink);
            box-shadow: 0 0 0 0.2rem rgba(236, 64, 122, 0.15);
        }

        .btn-register {
            background: linear-gradient(45deg, var(--primary-pink), var(--primary-gold));
            border: none;
            color: #ffffff;
            font-weight: 600;
            padding: 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
            font-size: 0.95rem;
        }

        .btn-register:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .register-footer {
            padding: 18px;
            text-align: center;
            background-color: #f9fafb;
            border-top: 1px solid #f3f4f6;
            font-size: 0.9rem;
        }

        .register-footer a {
            color: var(--primary-pink);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .register-footer a:hover {
            color: var(--primary-gold);
        }
    </style>
</head>
<body>

    <div class="register-card">
        <div class="register-header">
            <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Divy Shakti Logo">
            <h2>DIVY SHAKTI</h2>
            <p>Create a new customer account</p>
        </div>
        
        <div class="register-body">
            <form action="<?php echo base_url('admin/register'); ?>" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                            <input type="text" name="name" id="name" class="form-control" placeholder="John Doe" value="<?php echo set_value('name'); ?>" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                            <input type="email" name="email" id="email" class="form-control" placeholder="john@example.com" value="<?php echo set_value('email'); ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                            <input type="text" name="phone" id="phone" class="form-control" placeholder="9876543210" value="<?php echo set_value('phone'); ?>" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="referral_code" class="form-label">Referral Code (Optional)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-users"></i></span>
                            <input type="text" name="referral_code" id="referral_code" class="form-control" placeholder="Referral code if any" value="<?php echo set_value('referral_code'); ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Min 6 chars" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-4">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm password" required>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-register w-100 mb-2">
                    <i class="fa-solid fa-user-plus me-2"></i> Register Account
                </button>
            </form>
        </div>
        
        <div class="register-footer">
            Already have an account? <a href="<?php echo base_url('admin/login'); ?>">Sign In here</a>
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
                    title: 'Registration Failed',
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
