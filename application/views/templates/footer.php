        </main>
    </div>

    <!-- Bootstrap 5 Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Custom SweetAlert2 Theme & Helper -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/sweetalert-theme.css'); ?>">
    <script src="<?php echo base_url('assets/js/sweetalert-custom.js'); ?>"></script>

    <!-- Global SweetAlert Flash Handlers -->
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
                    title: 'Oops...',
                    text: '<?php echo addslashes(htmlspecialchars($this->session->flashdata('error'))); ?>'
                });
            <?php endif; ?>

            <?php if ($this->session->flashdata('warning')): ?>
                dsAlert({
                    icon: 'warning',
                    title: 'Notice',
                    text: '<?php echo addslashes(htmlspecialchars($this->session->flashdata('warning'))); ?>'
                });
            <?php endif; ?>

            <?php if ($this->session->flashdata('info')): ?>
                dsAlert({
                    icon: 'info',
                    title: 'Information',
                    text: '<?php echo addslashes(htmlspecialchars($this->session->flashdata('info'))); ?>'
                });
            <?php endif; ?>

            <?php if (function_exists('validation_errors') && validation_errors()): ?>
                dsAlert({
                    icon: 'error',
                    title: 'Validation Error',
                    html: '<?php echo addslashes(str_replace(["\r", "\n"], ' ', validation_errors('<div style="text-align:left; margin-bottom:4px;"><i class="fa-solid fa-circle-exclamation text-danger me-2"></i>', '</div>'))); ?>'
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
        if (
            sidebar.classList.contains('show') &&
            !sidebar.contains(e.target) &&
            !toggleBtn.contains(e.target)   // <-- was: e.target !== toggleBtn
        ) {
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
