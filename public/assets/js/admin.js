/**
 * Admin Panel JavaScript
 */

(function() {
    'use strict';

    // Sidebar Toggle
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggle = document.getElementById('sidebarCollapse');
        const sidebarToggleMobile = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const wrapper = document.querySelector('.wrapper');

        function toggleSidebar() {
            if (sidebar && wrapper) {
                sidebar.classList.toggle('active');
                wrapper.classList.toggle('active');
            }
        }

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function(e) {
                e.preventDefault();
                toggleSidebar();
            });
        }

        if (sidebarToggleMobile) {
            sidebarToggleMobile.addEventListener('click', function(e) {
                e.preventDefault();
                toggleSidebar();
            });
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const isClickInsideSidebar = sidebar && sidebar.contains(event.target);
            const isClickOnToggle = (sidebarToggle && sidebarToggle.contains(event.target)) || 
                                   (sidebarToggleMobile && sidebarToggleMobile.contains(event.target));
            
            if (window.innerWidth <= 768 && !isClickInsideSidebar && !isClickOnToggle && sidebar && sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
                if (wrapper) {
                    wrapper.classList.remove('active');
                }
            }
        });
    });

    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

})();
