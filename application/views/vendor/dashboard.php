<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Vendor Dashboard</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Vendor Dashboard</li>
                </ol>
            </nav>
        </div>
    </div>

    <?php if ($vendor->status === 'pending'): ?>
        <div class="alert alert-warning">
            <h4 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i> Pending Approval</h4>
            <p>Your vendor application is currently under review. You'll be able to add vehicles and manage bookings once your application is approved.</p>
            <hr>
            <p class="mb-0">Application submitted on: <?php echo date('M d, Y', strtotime($vendor->created_at)); ?></p>
        </div>
    <?php elseif ($vendor->status === 'rejected'): ?>
        <div class="alert alert-danger">
            <h4 class="alert-heading"><i class="fas fa-times-circle me-2"></i> Application Rejected</h4>
            <p>Unfortunately, your vendor application has been rejected. Please contact our support team for more information.</p>
            <hr>
            <p class="mb-0">If you believe this is an error, please contact <a href="mailto:support@carbooking.com">support@carbooking.com</a></p>
        </div>
    <?php else: ?>
        <!-- Dashboard Stats -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Vehicles</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['total_vehicles']; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-car fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Drivers</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['total_drivers']; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-id-card fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Active Bookings</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['active_bookings']; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Revenue</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">₹<?php echo number_format($stats['total_revenue']); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-rupee-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="<?php echo base_url('vendor/add_vehicle'); ?>" class="btn btn-primary btn-block d-flex align-items-center justify-content-center">
                                    <i class="fas fa-plus-circle me-2"></i> Add New Vehicle
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?php echo base_url('vendor/add_driver'); ?>" class="btn btn-success btn-block d-flex align-items-center justify-content-center">
                                    <i class="fas fa-user-plus me-2"></i> Add New Driver
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?php echo base_url('vendor/manage_vehicles'); ?>" class="btn btn-info btn-block d-flex align-items-center justify-content-center">
                                    <i class="fas fa-car me-2"></i> Manage Vehicles
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?php echo base_url('vendor/manage_drivers'); ?>" class="btn btn-warning btn-block d-flex align-items-center justify-content-center">
                                    <i class="fas fa-id-card me-2"></i> Manage Drivers
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Bookings</h5>
                        <a href="<?php echo base_url('vendor/bookings'); ?>" class="btn btn-sm btn-light">View All</a>
                    </div>
                    <div class="card-body">
                        <?php if (empty($recent_bookings)): ?>
                            <div class="alert alert-info">
                                <p class="mb-0">No bookings found. When customers book your vehicles, they will appear here.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Booking ID</th>
                                            <th>Customer</th>
                                            <th>Vehicles</th>
                                            <th>Dates</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recent_bookings as $booking): ?>
                                            <tr>
                                                <td>#<?php echo $booking->id; ?></td>
                                                <td><?php echo $booking->user_name; ?></td>
                                                <td>
                                                    <?php 
                                                        $vehicle_count = count($booking->items);
                                                        echo $vehicle_count . ' ' . ($vehicle_count > 1 ? 'vehicles' : 'vehicle');
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                        if (!empty($booking->items)) {
                                                            $earliest_date = min(array_column($booking->items, 'date_from'));
                                                            $latest_date = max(array_column($booking->items, 'date_to'));
                                                            echo date('M d', strtotime($earliest_date)) . ' - ' . date('M d, Y', strtotime($latest_date));
                                                        }
                                                    ?>
                                                </td>
                                                <td>₹<?php echo number_format($booking->vendor_amount); ?></td>
                                                <td>
                                                    <span class="badge bg-<?php 
                                                        echo $booking->status === 'PENDING' ? 'warning' : 
                                                            ($booking->status === 'CONFIRMED' ? 'success' : 
                                                                ($booking->status === 'ONGOING' ? 'info' : 
                                                                    ($booking->status === 'COMPLETED' ? 'primary' : 'danger'))); 
                                                    ?>">
                                                        <?php echo $booking->status; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="<?php echo base_url('vendor/view_booking/' . $booking->id); ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vehicle Availability Calendar -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Vehicle Availability Overview</h5>
                    </div>
                    <div class="card-body">
                        <div id="availability-calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
    .border-left-primary {
        border-left: 4px solid #4e73df;
    }
    
    .border-left-success {
        border-left: 4px solid #1cc88a;
    }
    
    .border-left-info {
        border-left: 4px solid #36b9cc;
    }
    
    .border-left-warning {
        border-left: 4px solid #f6c23e;
    }
    
    .btn-block {
        display: block;
        width: 100%;
        height: 100%;
        padding: 15px;
    }
    
    #availability-calendar {
        min-height: 300px;
    }
</style>

<?php if ($vendor->status === 'approved'): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // This is a placeholder for a calendar implementation
        // In a real application, you would use a library like FullCalendar.js
        // and populate it with availability data from your backend
        
        const calendarEl = document.getElementById('availability-calendar');
        calendarEl.innerHTML = '<div class="alert alert-info">Calendar functionality would be implemented here using a library like FullCalendar.js. The calendar would show vehicle availability and bookings across all your vehicles.</div>';
    });
</script>
<?php endif; ?>