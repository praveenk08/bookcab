<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Admin Dashboard</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Admin Dashboard</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- JavaScript for Charts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Booking Status Chart
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('bookingStatusChart').getContext('2d');
        var pendingCount = <?php echo isset($pending_bookings) ? $pending_bookings : 0; ?>;
        var confirmedCount = <?php echo isset($confirmed_bookings) ? $confirmed_bookings : 0; ?>;
        var completedCount = <?php echo isset($completed_bookings) ? $completed_bookings : 0; ?>;
        var cancelledCount = <?php echo isset($cancelled_bookings) ? $cancelled_bookings : 0; ?>;
        
        var bookingStatusChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Confirmed', 'Completed', 'Cancelled'],
                datasets: [{
                    data: [pendingCount, confirmedCount, completedCount, cancelledCount],
                    backgroundColor: ['#f6c23e', '#1cc88a', '#4e73df', '#e74a3b'],
                    hoverBackgroundColor: ['#e0b138', '#17a673', '#2e59d9', '#d52a1a'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 70,
            },
        });
    });
</script>

<?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $this->session->flashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $this->session->flashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Pending Vendors Alert -->
    <?php if (isset($pending_vendors) && $pending_vendors > 0): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Attention!</strong> You have <?php echo $pending_vendors; ?> pending vendor application(s) awaiting review.
            <a href="<?php echo base_url('admin/list_vendors'); ?>" class="alert-link">Review now</a>.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Pending Bookings Alert -->
    <?php if (isset($pending_bookings) && $pending_bookings > 0): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Notification:</strong> You have <?php echo $pending_bookings; ?> pending booking(s) that need confirmation.
            <a href="<?php echo base_url('admin/list_bookings'); ?>" class="alert-link">View bookings</a>.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo isset($total_users) ? $total_users : 0; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="<?php echo base_url('admin/list_users'); ?>" class="small text-primary">View Details <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Vendors</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo isset($total_vendors) ? $total_vendors : 0; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-store fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="<?php echo base_url('admin/list_vendors'); ?>" class="small text-success">View Details <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Bookings</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo isset($total_bookings) ? $total_bookings : 0; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="<?php echo base_url('admin/list_bookings'); ?>" class="small text-info">View Details <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Applications</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo isset($pending_vendors) ? $pending_vendors : 0; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="<?php echo base_url('admin/list_vendors'); ?>" class="small text-warning">View Details <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Bookings -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Bookings</h6>
                    <a href="<?php echo base_url('admin/list_bookings'); ?>" class="btn btn-sm btn-primary">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    <?php if (isset($recent_bookings) && !empty($recent_bookings)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_bookings as $booking): ?>
                                        <tr>
                                            <td>#<?php echo $booking->id; ?></td>
                                            <td><?php echo $booking->user_name; ?></td>
                                            <td><?php echo date('M d, Y', strtotime($booking->created_at)); ?></td>
                                            <td>$<?php echo number_format($booking->total_amount, 2); ?></td>
                                            <td>
                                                <?php if ($booking->status == 'pending'): ?>
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                <?php elseif ($booking->status == 'confirmed'): ?>
                                                    <span class="badge bg-success">Confirmed</span>
                                                <?php elseif ($booking->status == 'cancelled'): ?>
                                                    <span class="badge bg-danger">Cancelled</span>
                                                <?php elseif ($booking->status == 'completed'): ?>
                                                    <span class="badge bg-primary">Completed</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?php echo base_url('admin/view_booking/' . $booking->id); ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="mb-0">No recent bookings found.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Recent Users -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Users</h6>
                    <a href="<?php echo base_url('admin/list_users'); ?>" class="btn btn-sm btn-primary">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    <?php if (isset($recent_users) && !empty($recent_users)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($recent_users as $user): ?>
                                <a href="<?php echo base_url('admin/view_user/' . $user->id); ?>" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1"><?php echo $user->name; ?></h6>
                                            <small class="text-muted"><?php echo $user->email; ?></small>
                                        </div>
                                        <span class="badge bg-<?php echo ($user->role == 'admin') ? 'danger' : (($user->role == 'vendor') ? 'primary' : 'info'); ?>">
                                            <?php echo ucfirst($user->role); ?>
                                        </span>
                                    </div>
                                    <small class="text-muted">Joined: <?php echo date('M d, Y', strtotime($user->created_at)); ?></small>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                            <p class="mb-0">No recent users found.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Booking Status Chart -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Booking Status Overview</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height:300px;">
                        <canvas id="bookingStatusChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="me-2">
                            <i class="fas fa-circle text-warning"></i> Pending
                        </span>
                        <span class="me-2">
                            <i class="fas fa-circle text-success"></i> Confirmed
                        </span>
                        <span class="me-2">
                            <i class="fas fa-circle text-primary"></i> Completed
                        </span>
                        <span class="me-2">
                            <i class="fas fa-circle text-danger"></i> Cancelled
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="<?php echo base_url('admin/list_vendors'); ?>" class="btn btn-primary btn-block w-100 py-3">
                                <i class="fas fa-store fa-fw me-2"></i> Manage Vendors
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="<?php echo base_url('admin/list_bookings'); ?>" class="btn btn-success btn-block w-100 py-3">
                                <i class="fas fa-calendar-check fa-fw me-2"></i> Manage Bookings
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="<?php echo base_url('admin/list_users'); ?>" class="btn btn-info btn-block w-100 py-3">
                                <i class="fas fa-users fa-fw me-2"></i> Manage Users
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="<?php echo base_url('admin/audit_logs'); ?>" class="btn btn-secondary btn-block w-100 py-3">
                                <i class="fas fa-history fa-fw me-2"></i> View Audit Logs
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>