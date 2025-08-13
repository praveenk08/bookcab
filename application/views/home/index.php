<div class="container">
    <div class="row">
        <div class="col-md-12 text-center mb-5">
            <h1 class="display-4">Welcome to Bulk Car Booking Platform</h1>
            <p class="lead">Book multiple vehicles for your weddings and events with ease</p>
            <div class="mt-4">
                <a href="<?php echo base_url('vehicle/search'); ?>" class="btn btn-primary btn-lg">Search Vehicles</a>
                <?php if (!$this->session->userdata('logged_in')): ?>
                    <a href="<?php echo base_url('auth/register'); ?>" class="btn btn-outline-primary btn-lg ms-2">Register Now</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="row mb-5">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-car fa-3x mb-3 text-primary"></i>
                    <h3 class="card-title">Multiple Vehicle Types</h3>
                    <p class="card-text">Choose from a wide range of vehicles including SUVs, Sedans, Buses, Bikes, E-Rickshaws, and Luxury cars.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-alt fa-3x mb-3 text-primary"></i>
                    <h3 class="card-title">Bulk Booking</h3>
                    <p class="card-text">Book multiple vehicles in a single booking for your wedding or event needs.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-user-check fa-3x mb-3 text-primary"></i>
                    <h3 class="card-title">Verified Vendors</h3>
                    <p class="card-text">All vendors are verified by our admin team to ensure quality service and reliability.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works Section -->
    <div class="row mb-5">
        <div class="col-md-12 text-center mb-4">
            <h2>How It Works</h2>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex justify-content-center align-items-center mb-3" style="width: 50px; height: 50px;">
                        <span class="h4 mb-0">1</span>
                    </div>
                    <h4 class="card-title">Search</h4>
                    <p class="card-text">Search for vehicles based on your requirements, date, and location.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex justify-content-center align-items-center mb-3" style="width: 50px; height: 50px;">
                        <span class="h4 mb-0">2</span>
                    </div>
                    <h4 class="card-title">Add to Cart</h4>
                    <p class="card-text">Add multiple vehicles to your booking cart as needed.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex justify-content-center align-items-center mb-3" style="width: 50px; height: 50px;">
                        <span class="h4 mb-0">3</span>
                    </div>
                    <h4 class="card-title">Book</h4>
                    <p class="card-text">Complete your booking with a few simple steps.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex justify-content-center align-items-center mb-3" style="width: 50px; height: 50px;">
                        <span class="h4 mb-0">4</span>
                    </div>
                    <h4 class="card-title">Enjoy</h4>
                    <p class="card-text">Sit back and enjoy your event with our reliable service.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- For Vendors Section -->
    <div class="row mb-5">
        <div class="col-md-6">
            <h2>Are You a Vehicle Owner?</h2>
            <p class="lead">Join our platform and start earning by renting out your vehicles for weddings and events.</p>
            <ul class="list-group list-group-flush mb-4">
                <li class="list-group-item"><i class="fas fa-check text-success me-2"></i> List multiple vehicles on our platform</li>
                <li class="list-group-item"><i class="fas fa-check text-success me-2"></i> Manage your drivers and availability</li>
                <li class="list-group-item"><i class="fas fa-check text-success me-2"></i> Get bookings for weddings and events</li>
                <li class="list-group-item"><i class="fas fa-check text-success me-2"></i> Grow your business with our platform</li>
            </ul>
            <a href="<?php echo base_url('vendor/apply'); ?>" class="btn btn-primary">Become a Vendor</a>
        </div>
        <div class="col-md-6">
            <img src="https://via.placeholder.com/600x400" alt="Vendor Image" class="img-fluid rounded">
        </div>
    </div>
</div>