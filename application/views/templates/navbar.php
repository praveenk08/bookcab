<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="<?php echo base_url(); ?>">Bulk Car Booking</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url(); ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('vehicle/search'); ?>">Search Vehicles</a>
                </li>
                <?php if ($this->session->userdata('logged_in')): ?>
                    <?php if ($this->session->userdata('role') === 'admin'): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Admin
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                                <li><a class="dropdown-item" href="<?php echo base_url('admin/vendors'); ?>">Manage Vendors</a></li>
                                <li><a class="dropdown-item" href="<?php echo base_url('admin/bookings'); ?>">Manage Bookings</a></li>
                                <li><a class="dropdown-item" href="<?php echo base_url('admin/users'); ?>">Manage Users</a></li>
                                <li><a class="dropdown-item" href="<?php echo base_url('admin/audit_logs'); ?>">Audit Logs</a></li>
                            </ul>
                        </li>
                    <?php elseif ($this->session->userdata('role') === 'vendor'): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="vendorDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Vendor
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="vendorDropdown">
                                <li><a class="dropdown-item" href="<?php echo base_url('vendor/dashboard'); ?>">Dashboard</a></li>
                                <li><a class="dropdown-item" href="<?php echo base_url('vendor/vehicles'); ?>">My Vehicles</a></li>
                                <li><a class="dropdown-item" href="<?php echo base_url('vendor/drivers'); ?>">My Drivers</a></li>
                                <li><a class="dropdown-item" href="<?php echo base_url('vendor/bookings'); ?>">My Bookings</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url('booking/user_bookings'); ?>">My Bookings</a>
                    </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <?php if ($this->session->userdata('logged_in')): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url('vehicle/cart'); ?>">
                            <i class="fas fa-shopping-cart"></i> Cart
                            <?php 
                            $cart_items = $this->session->userdata('cart_items') ?: [];
                            $cart_count = count($cart_items);
                            if ($cart_count > 0): 
                            ?>
                            <span class="badge bg-danger"><?php echo $cart_count; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <?php $this->load->view('templates/notification_badge'); ?>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user"></i> <?php echo $this->session->userdata('name'); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="<?php echo base_url('user/profile'); ?>">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo base_url('auth/logout'); ?>">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url('auth/login'); ?>">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url('auth/register'); ?>">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>