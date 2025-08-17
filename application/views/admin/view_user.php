<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>User Details</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>">Admin Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin/users'); ?>">User Management</a></li>
                    <li class="breadcrumb-item active" aria-current="page">User Details</li>
                </ol>
            </nav>
        </div>
    </div>

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

    <div class="row">
        <!-- User Information Card -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">User Information</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="avatar-circle mx-auto mb-3">
                            <span class="avatar-initials"><?php echo substr($user->name, 0, 1); ?></span>
                        </div>
                        <h4><?php echo $user->name; ?></h4>
                        <p>
                            <?php if ($user->role == 'admin'): ?>
                                <span class="badge bg-info">Admin</span>
                            <?php elseif ($user->role == 'vendor'): ?>
                                <span class="badge bg-success">Vendor</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">Customer</span>
                            <?php endif; ?>
                            
                            <?php if ($user->status == 'active'): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactive</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-envelope me-2 text-muted"></i> Email</span>
                            <span><?php echo $user->email; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-phone me-2 text-muted"></i> Phone</span>
                            <span><?php echo $user->phone ?: 'Not provided'; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-calendar-alt me-2 text-muted"></i> Registered</span>
                            <span><?php echo date('M d, Y', strtotime($user->created_at)); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-clock me-2 text-muted"></i> Last Login</span>
                            <span><?php echo $user->last_login ? date('M d, Y H:i', strtotime($user->last_login)) : 'Never'; ?></span>
                        </li>
                    </ul>
                </div>
                <div class="card-footer">
                    <?php if ($user->id != $this->session->userdata('user_id')): ?> <!-- Cannot change own status -->
                        <?php if ($user->status == 'active'): ?>
                            <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#deactivateUserModal">
                                <i class="fas fa-ban me-1"></i> Deactivate User
                            </button>
                        <?php else: ?>
                            <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#activateUserModal">
                                <i class="fas fa-check me-1"></i> Activate User
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Tabs for different sections -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header bg-light">
                    <ul class="nav nav-tabs card-header-tabs" id="userDetailsTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="bookings-tab" data-bs-toggle="tab" data-bs-target="#bookings" type="button" role="tab" aria-controls="bookings" aria-selected="true">
                                <i class="fas fa-calendar-check me-1"></i> Bookings
                            </button>
                        </li>
                        <?php if ($user->role == 'vendor' && isset($vendor)): ?>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="vendor-tab" data-bs-toggle="tab" data-bs-target="#vendor" type="button" role="tab" aria-controls="vendor" aria-selected="false">
                                <i class="fas fa-store me-1"></i> Vendor Details
                            </button>
                        </li>
                        <?php endif; ?>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity" type="button" role="tab" aria-controls="activity" aria-selected="false">
                                <i class="fas fa-history me-1"></i> Activity Log
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="userDetailsTabsContent">
                        <!-- Bookings Tab -->
                        <div class="tab-pane fade show active" id="bookings" role="tabpanel" aria-labelledby="bookings-tab">
                            <h5 class="card-title">Booking History</h5>
                            <?php if (empty($bookings)): ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i> No bookings found for this user.
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Date</th>
                                                <th>Vehicles</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($bookings as $booking): ?>
                                                <tr>
                                                    <td>#<?php echo $booking->id; ?></td>
                                                    <td>
                                                        <?php echo empty($booking->pickup_date) ? 'Not provided' : date('M d, Y', strtotime($booking->pickup_date)); ?>
                                                        <div class="small text-muted"><?php echo empty($booking->pickup_time) ? 'Not provided' : date('g:i A', strtotime($booking->pickup_time)); ?></div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-primary"><?php echo $booking->vehicle_count; ?> vehicles</span>
                                                    </td>
                                                    <td><?php echo $this->config->item('currency_symbol') . number_format($booking->total_amount, 2); ?></td>
                                                    <td>
                                                        <?php if ($booking->status == 'pending'): ?>
                                                            <span class="badge bg-warning text-dark">Pending</span>
                                                        <?php elseif ($booking->status == 'confirmed'): ?>
                                                            <span class="badge bg-info">Confirmed</span>
                                                        <?php elseif ($booking->status == 'completed'): ?>
                                                            <span class="badge bg-success">Completed</span>
                                                        <?php elseif ($booking->status == 'cancelled'): ?>
                                                            <span class="badge bg-danger">Cancelled</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo base_url('admin/view_booking/' . $booking->id); ?>" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php if (count($bookings) >= 5): ?>
                                    <div class="text-center mt-3">
                                        <a href="<?php echo base_url('admin/bookings?user_id=' . $user->id); ?>" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-list me-1"></i> View All Bookings
                                        </a>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Vendor Details Tab -->
                        <?php if ($user->role == 'vendor' && isset($vendor)): ?>
                        <div class="tab-pane fade" id="vendor" role="tabpanel" aria-labelledby="vendor-tab">
                            <h5 class="card-title">Vendor Information</h5>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h6 class="card-subtitle mb-2 text-muted">Business Details</h6>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>Business Name</span>
                                                    <span class="fw-bold"><?php echo $vendor->business_name; ?></span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>Status</span>
                                                    <?php if ($vendor->status == 'pending'): ?>
                                                        <span class="badge bg-warning text-dark">Pending</span>
                                                    <?php elseif ($vendor->status == 'approved'): ?>
                                                        <span class="badge bg-success">Approved</span>
                                                    <?php elseif ($vendor->status == 'rejected'): ?>
                                                        <span class="badge bg-danger">Rejected</span>
                                                    <?php endif; ?>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>Verified</span>
                                                    <?php if ($vendor->is_verified): ?>
                                                        <span class="badge bg-info"><i class="fas fa-check-circle me-1"></i> Yes</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">No</span>
                                                    <?php endif; ?>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>Applied On</span>
                                                    <span><?php echo date('M d, Y', strtotime($vendor->created_at)); ?></span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="card-footer">
                                            <a href="<?php echo base_url('admin/view_vendor/' . $vendor->id); ?>" class="btn btn-primary btn-sm w-100">
                                                <i class="fas fa-external-link-alt me-1"></i> View Full Vendor Profile
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h6 class="card-subtitle mb-2 text-muted">Statistics</h6>
                                            <div class="row text-center">
                                                <div class="col-6 mb-3">
                                                    <div class="p-3 border rounded bg-light">
                                                        <h3 class="text-primary mb-0"><?php echo $vendor->vehicle_count ?: 0; ?></h3>
                                                        <small class="text-muted">Vehicles</small>
                                                    </div>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <div class="p-3 border rounded bg-light">
                                                        <h3 class="text-primary mb-0"><?php echo $vendor->driver_count ?: 0; ?></h3>
                                                        <small class="text-muted">Drivers</small>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="p-3 border rounded bg-light">
                                                        <h3 class="text-primary mb-0"><?php echo $vendor->booking_count ?: 0; ?></h3>
                                                        <small class="text-muted">Bookings</small>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="p-3 border rounded bg-light">
                                                        <h3 class="text-primary mb-0"><?php echo $vendor->avg_rating ? number_format($vendor->avg_rating, 1) : 'N/A'; ?></h3>
                                                        <small class="text-muted">Avg. Rating</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Activity Log Tab -->
                        <div class="tab-pane fade" id="activity" role="tabpanel" aria-labelledby="activity-tab">
                            <h5 class="card-title">Activity Log</h5>
                            <?php if (empty($activity_logs)): ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i> No activity logs found for this user.
                                </div>
                            <?php else: ?>
                                <div class="timeline">
                                    <?php foreach ($activity_logs as $log): ?>
                                        <div class="timeline-item">
                                            <div class="timeline-marker"></div>
                                            <div class="timeline-content">
                                                <h6 class="timeline-title"><?php echo $log->action; ?></h6>
                                                <p class="timeline-text"><?php echo $log->details; ?></p>
                                                <p class="timeline-date text-muted small">
                                                    <i class="fas fa-clock me-1"></i> <?php echo date('M d, Y g:i A', strtotime($log->created_at)); ?>
                                                    <?php if ($log->ip_address): ?>
                                                        <span class="ms-2"><i class="fas fa-globe me-1"></i> <?php echo $log->ip_address; ?></span>
                                                    <?php endif; ?>
                                                </p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php if (count($activity_logs) >= 10): ?>
                                    <div class="text-center mt-3">
                                        <a href="<?php echo base_url('admin/audit_logs?user_id=' . $user->id); ?>" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-history me-1"></i> View Full Activity Log
                                        </a>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Deactivate Modal -->
<div class="modal fade" id="deactivateUserModal" tabindex="-1" aria-labelledby="deactivateUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deactivateUserModalLabel">Deactivate User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?php echo form_open('admin/update-user-status/' . $user->id); ?>
                <input type="hidden" name="status" value="inactive">
                <div class="modal-body">
                    <p>Are you sure you want to deactivate <strong><?php echo $user->name; ?></strong>?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i> This user will no longer be able to log in or use the system.
                        <?php if ($user->role == 'vendor'): ?>
                            <br><strong>Note:</strong> This will also deactivate their vendor account and all associated vehicles.
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="deactivation_reason" class="form-label">Reason for Deactivation</label>
                        <textarea class="form-control" id="deactivation_reason" name="reason" rows="3" required placeholder="Please provide a reason for deactivation..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Deactivate User</button>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!-- Activate Modal -->
<div class="modal fade" id="activateUserModal" tabindex="-1" aria-labelledby="activateUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="activateUserModalLabel">Activate User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?php echo form_open('admin/update-user-status/' . $user->id); ?>
                <input type="hidden" name="status" value="active">
                <div class="modal-body">
                    <p>Are you sure you want to activate <strong><?php echo $user->name; ?></strong>?</p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> This user will be able to log in and use the system again.
                        <?php if ($user->role == 'vendor'): ?>
                            <br><strong>Note:</strong> If this user has a vendor account, you may need to also update the vendor status separately.
                        <?php endif; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Activate User</button>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<style>
    .avatar-circle {
        width: 80px;
        height: 80px;
        background-color: #007bff;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .avatar-initials {
        color: white;
        font-size: 40px;
        font-weight: bold;
        text-transform: uppercase;
    }
    
    /* Timeline styling */
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline:before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        height: 100%;
        width: 2px;
        background-color: #e9ecef;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    
    .timeline-marker {
        position: absolute;
        left: -30px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: #007bff;
        border: 2px solid #fff;
    }
    
    .timeline-content {
        padding-bottom: 10px;
        border-bottom: 1px solid #e9ecef;
    }
    
    .timeline-title {
        margin-bottom: 5px;
    }
    
    .timeline-date {
        margin-top: 5px;
    }
</style>