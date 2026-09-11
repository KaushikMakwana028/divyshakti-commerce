/**
 * Divy Shakti - Custom SweetAlert2 Helper & Event Interceptor
 */

(function(window, document) {
    'use strict';

    /**
     * Helper to show a custom themed Alert dialog
     */
    window.dsAlert = function(options) {
        options = options || {};
        if (typeof options === 'string') {
            options = { text: options };
        }

        const icon = options.icon || 'info';
        const title = options.title || (icon === 'error' ? 'Oops...' : (icon === 'success' ? 'Success!' : 'Notice'));

        return Swal.fire({
            title: title,
            text: options.text || '',
            html: options.html || undefined,
            icon: icon,
            confirmButtonText: options.confirmText || 'OK',
            buttonsStyling: false,
            customClass: {
                confirmButton: 'ds-swal-confirm'
            },
            timer: options.timer || undefined,
            timerProgressBar: Boolean(options.timer)
        });
    };

    /**
     * Helper to show a custom themed Confirmation dialog
     */
    window.dsConfirm = function(options) {
        options = options || {};
        const isDangerous = Boolean(options.isDangerous);
        const icon = options.icon || (isDangerous ? 'warning' : 'question');
        const confirmBtnClass = isDangerous ? 'ds-swal-confirm ds-swal-danger' : 'ds-swal-confirm';

        return Swal.fire({
            title: options.title || 'Are you sure?',
            text: options.text || 'Do you want to proceed with this action?',
            html: options.html || undefined,
            icon: icon,
            showCancelButton: true,
            confirmButtonText: options.confirmText || (isDangerous ? 'Yes, Proceed' : 'Confirm'),
            cancelButtonText: options.cancelText || 'Cancel',
            buttonsStyling: false,
            reverseButtons: true,
            customClass: {
                confirmButton: confirmBtnClass,
                cancelButton: 'ds-swal-cancel'
            }
        }).then(function(result) {
            if (result.isConfirmed) {
                if (typeof options.onConfirm === 'function') {
                    options.onConfirm();
                }
            } else if (result.isDismissed) {
                if (typeof options.onCancel === 'function') {
                    options.onCancel();
                }
            }
            return result;
        });
    };

    /**
     * Helper to show a sleek, unobtrusive Toast notification
     */
    window.dsToast = function(options) {
        options = options || {};
        if (typeof options === 'string') {
            options = { title: options };
        }

        const Toast = Swal.mixin({
            toast: true,
            position: options.position || 'top-end',
            showConfirmButton: false,
            showCloseButton: true,
            timer: options.timer || 3500,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        return Toast.fire({
            icon: options.icon || 'success',
            title: options.title || 'Notification',
            text: options.text || undefined,
            html: options.html || undefined
        });
    };

    /**
     * Global Event Interceptor for [data-confirm] elements
     * Seamlessly upgrades any link, button, or form to SweetAlert confirmation
     */
    document.addEventListener('click', function(e) {
        const trigger = e.target.closest('[data-confirm]');
        if (!trigger) return;

        // Prevent immediate navigation or form submission
        e.preventDefault();
        e.stopPropagation();

        const text = trigger.getAttribute('data-confirm');
        const title = trigger.getAttribute('data-confirm-title') || 'Are you sure?';
        const icon = trigger.getAttribute('data-confirm-icon') || 'warning';
        const confirmText = trigger.getAttribute('data-confirm-btn') || 'Yes, proceed';
        const cancelText = trigger.getAttribute('data-confirm-cancel') || 'Cancel';
        const isDangerous = trigger.getAttribute('data-confirm-danger') === 'true' || 
                            trigger.classList.contains('sm-icon-btn-deactivate') || 
                            trigger.classList.contains('btn-danger') ||
                            trigger.classList.contains('text-danger');

        window.dsConfirm({
            title: title,
            text: text,
            icon: icon,
            confirmText: confirmText,
            cancelText: cancelText,
            isDangerous: isDangerous,
            onConfirm: function() {
                // If it's a link, navigate
                if (trigger.tagName.toLowerCase() === 'a' && trigger.href) {
                    window.location.href = trigger.href;
                } 
                // If it's a button inside a form, submit the form
                else if (trigger.form) {
                    trigger.form.submit();
                }
                // If it's a button with data-href
                else if (trigger.getAttribute('data-href')) {
                    window.location.href = trigger.getAttribute('data-href');
                }
            }
        });
    }, true);

})(window, document);
