</div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p>&copy; <?php echo date('Y'); ?> Bulk Car Booking Platform. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item"><a href="#">Terms of Service</a></li>
                        <li class="list-inline-item"><a href="#">Privacy Policy</a></li>
                        <li class="list-inline-item"><a href="#">Contact Us</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (required for some Bootstrap components) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Base URL for JavaScript -->
    <script>
        var base_url = '<?php echo base_url(); ?>';
    </script>
    
    <!-- Notification JavaScript -->
    <script src="<?php echo base_url('assets/js/notifications.js'); ?>"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
        
        // Initialize date pickers if any
        if (typeof flatpickr !== 'undefined') {
            flatpickr(".datepicker", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                minDate: "today"
            });
        }
        
        // Auto-dismiss alerts after 5 seconds
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function() {
                $(this).remove();
            });
        }, 5000);
    </script>
</body>
</html>