        </main>
    </div>

    <!-- Bootstrap 5 Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Custom styling for SweetAlert2 matching project theme -->
    <style>
        .swal-custom-popup {
            background-color: #111827 !important; /* matches var(--dark-sidebar) */
            border: 2px solid #d4af37 !important; /* matches var(--primary-gold) */
            border-radius: 16px !important;
            font-family: sans-serif;
        }
        .swal-custom-title {
            color: #f3f4f6 !important;
            font-weight: 700 !important;
        }
        .swal-custom-content {
            color: #d1d5db !important;
        }
        .swal-custom-confirm-btn {
            background: linear-gradient(45deg, #ec407a, #d4af37) !important;
            border: none !important;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1) !important;
            padding: 8px 24px !important;
            border-radius: 6px !important;
            font-weight: 600 !important;
        }
        .swal-custom-confirm-btn:hover {
            opacity: 0.9 !important;
        }
    </style>

    <!-- Global SweetAlert Flash Handlers -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($this->session->flashdata('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '<?php echo addslashes(htmlspecialchars($this->session->flashdata('success'))); ?>',
                    background: '#111827',
                    iconColor: '#ec407a',
                    customClass: {
                        popup: 'swal-custom-popup',
                        title: 'swal-custom-title',
                        htmlContainer: 'swal-custom-content',
                        confirmButton: 'swal-custom-confirm-btn'
                    },
                    buttonsStyling: false
                });
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '<?php echo addslashes(htmlspecialchars($this->session->flashdata('error'))); ?>',
                    background: '#111827',
                    iconColor: '#dc3545',
                    customClass: {
                        popup: 'swal-custom-popup',
                        title: 'swal-custom-title',
                        htmlContainer: 'swal-custom-content',
                        confirmButton: 'swal-custom-confirm-btn'
                    },
                    buttonsStyling: false
                });
            <?php endif; ?>
        });
    </script>
    
    <!-- Sidebar Toggle Script for Mobile -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const backdrop = document.getElementById('sidebarBackdrop');
            
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    sidebar.classList.toggle('show');
                    if (backdrop) {
                        backdrop.classList.toggle('show');
                    }
                });
            }
            
            if (backdrop) {
                backdrop.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    backdrop.classList.remove('show');
                });
            }
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(e) {
                if (window.innerWidth < 992) {
                    if (sidebar.classList.contains('show') && !sidebar.contains(e.target) && e.target !== toggleBtn) {
                        sidebar.classList.remove('show');
                        if (backdrop) {
                            backdrop.classList.remove('show');
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
