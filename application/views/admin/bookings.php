<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Booking Management</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>">Admin Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Booking Management</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Bookings</h5>
                    <div>
                        <a href="<?php echo base_url('admin/bookings?status=pending'); ?>" class="btn btn-warning btn-sm me-2">
                            <i class="fas fa-clock me-1"></i> Pending
                            <?php if(isset($pending_count) && $pending_count > 0): ?>
                                <span class="badge bg-light text-dark ms-1"><?php echo $pending_count; ?></span>
                            <?php endif; ?>
                        </a>
                        <a href="<?php echo base_url('admin/bookings?status=confirmed'); ?>" class="btn btn-success btn-sm me-2">
                            <i class="fas fa-check-circle me-1"></i> Confirmed
                        </a>
                        <a href="<?php echo base_url('admin/bookings?status=completed'); ?>" class="btn btn-primary btn-sm me-2">
                            <i class="fas fa-flag-checkered me-1"></i> Completed
                        </a>
                        <a href="<?php echo base_url('admin/bookings?status=cancelled'); ?>" class="btn btn-danger btn-sm me-2">
                            <i class="fas fa-times-circle me-1"></i> Cancelled
                        </a>
                        <a href="<?php echo base_url('admin/bookings'); ?>" class="btn btn-light btn-sm">
                            <i class="fas fa-list me-1"></i> All
                        </a>
                    </div>
                </div>
                <div class="card-body">
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

                    <!-- Filter Form -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <?php echo form_open('admin/bookings', ['method' => 'GET', 'class' => 'row g-3']); ?>
                                <div class="col-md-2">
                                    <label for="search" class="form-label">Search</label>
                                    <input type="text" class="form-control" id="search" name="search" placeholder="Booking ID, Name..." value="<?php echo $this->input->get('search'); ?>">
                                </div>
                                <div class="col-md-2">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="" <?php echo $this->input->get('status') == '' ? 'selected' : ''; ?>>All Statuses</option>
                                        <option value="pending" <?php echo $this->input->get('status') == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="confirmed" <?php echo $this->input->get('status') == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                        <option value="completed" <?php echo $this->input->get('status') == 'completed' ? 'selected' : ''; ?>>Completed</option>
                                        <option value="cancelled" <?php echo $this->input->get('status') == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="vendor" class="form-label">Vendor</label>
                                    <select class="form-select" id="vendor" name="vendor_id">
                                        <option value="">All Vendors</option>
                                        <?php foreach ($vendors as $vendor): ?>
                                            <option value="<?php echo $vendor->id; ?>" <?php echo $this->input->get('vendor_id') == $vendor->id ? 'selected' : ''; ?>>
                                                <?php echo $vendor->business_name; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="from_date" class="form-label">From Date</label>
                                    <input type="date" class="form-control" id="from_date" name="from_date" value="<?php echo $this->input->get('from_date'); ?>">
                                </div>
                                <div class="col-md-2">
                                    <label for="to_date" class="form-label">To Date</label>
                                    <input type="date" class="form-control" id="to_date" name="to_date" value="<?php echo $this->input->get('to_date'); ?>">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-filter me-1"></i> Filter
                                    </button>
                                    <a href="<?php echo base_url('admin/bookings'); ?>" class="btn btn-outline-secondary">
                                        <i class="fas fa-redo me-1"></i> Reset
                                    </a>
                                </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>

                    <?php if (empty($bookings)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> No bookings found matching your criteria.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Customer</th>
                                        <th>Vendor(s)</th>
                                        <th>Vehicles</th>
                                        <th>Dates</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Booked On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bookings as $booking): ?>
                                        <tr>
                                            <td><strong>#<?php echo $booking->id; ?></strong></td>
                                            <td>
                                                <?php echo $booking->user_name; ?><br>
                                                <small class="text-muted"><?php echo $booking->user_email; ?></small>
                                            </td>
                                            <td>
                                                <?php if(isset($booking->vendors)): ?>
                                                    <?php foreach($booking->vendors as $vendor): ?>
                                                        <span class="badge bg-info text-dark mb-1"><?php echo $vendor->business_name; ?></span><br>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Unknown</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo $booking->vehicle_count; ?> vehicles</td>
                                            <td>
                                                <?php echo date('M d, Y', strtotime($booking->from_date)); ?> -<br>
                                                <?php echo date('M d, Y', strtotime($booking->to_date)); ?>
                                            </td>
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
                                            <td><?php echo date('M d, Y', strtotime($booking->created_at)); ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo base_url('admin/view_booking/' . $booking->id); ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    <?php if ($booking->status == 'pending'): ?>
                                                        <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#confirmBookingModal<?php echo $booking->id; ?>" title="Confirm Booking">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelBookingModal<?php echo $booking->id; ?>" title="Cancel Booking">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    <?php elseif ($booking->status == 'confirmed'): ?>
                                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#completeBookingModal<?php echo $booking->id; ?>" title="Mark as Completed">
                                                            <i class="fas fa-flag-checkered"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelBookingModal<?php echo $booking->id; ?>" title="Cancel Booking">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <!-- Confirm Modal -->
                                                <div class="modal fade" id="confirmBookingModal<?php echo $booking->id; ?>" tabindex="-1" aria-labelledby="confirmBookingModalLabel<?php echo $booking->id; ?>" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-success text-white">
                                                                <h5 class="modal-title" id="confirmBookingModalLabel<?php echo $booking->id; ?>">Confirm Booking</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <?php echo form_open('admin/change_booking_status/' . $booking->id); ?>
                                                                <input type="hidden" name="status" value="confirmed">
                                                                <div class="modal-body">
                                                                    <p>Are you sure you want to confirm booking <strong>#<?php echo $booking->id; ?></strong>?</p>
                                                                    <div class="mb-3">
                                                                        <label for="admin_note<?php echo $booking->id; ?>" class="form-label">Admin Note (Optional)</label>
                                                                        <textarea class="form-control" id="admin_note<?php echo $booking->id; ?>" name="admin_note" rows="3" placeholder="Add any notes about this confirmation..."></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-success">Confirm Booking</button>
                                                                </div>
                                                            <?php echo form_close(); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Complete Modal -->
                                                <div class="modal fade" id="completeBookingModal<?php echo $booking->id; ?>" tabindex="-1" aria-labelledby="completeBookingModalLabel<?php echo $booking->id; ?>" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-primary text-white">
                                                                <h5 class="modal-title" id="completeBookingModalLabel<?php echo $booking->id; ?>">Mark Booking as Completed</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <?php echo form_open('admin/change_booking_status/' . $booking->id); ?>
                                                                <input type="hidden" name="status" value="completed">
                                                                <div class="modal-body">
                                                                    <p>Are you sure you want to mark booking <strong>#<?php echo $booking->id; ?></strong> as completed?</p>
                                                                    <div class="alert alert-info">
                                                                        <i class="fas fa-info-circle me-2"></i> This will allow the customer to leave reviews for the vendors and vehicles.
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="admin_note<?php echo $booking->id; ?>_complete" class="form-label">Admin Note (Optional)</label>
                                                                        <textarea class="form-control" id="admin_note<?php echo $booking->id; ?>_complete" name="admin_note" rows="3" placeholder="Add any notes about this completion..."></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-primary">Mark as Completed</button>
                                                                </div>
                                                            <?php echo form_close(); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Cancel Modal -->
                                                <div class="modal fade" id="cancelBookingModal<?php echo $booking->id; ?>" tabindex="-1" aria-labelledby="cancelBookingModalLabel<?php echo $booking->id; ?>" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-danger text-white">
                                                                <h5 class="modal-title" id="cancelBookingModalLabel<?php echo $booking->id; ?>">Cancel Booking</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <?php echo form_open('admin/change_booking_status/' . $booking->id); ?>
                                                                <input type="hidden" name="status" value="cancelled">
                                                                <div class="modal-body">
                                                                    <p>Are you sure you want to cancel booking <strong>#<?php echo $booking->id; ?></strong>?</p>
                                                                    <div class="alert alert-warning">
                                                                        <i class="fas fa-exclamation-triangle me-2"></i> This will release all reserved vehicles and notify the customer and vendors.
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="cancellation_reason<?php echo $booking->id; ?>" class="form-label">Cancellation Reason <span class="text-danger">*</span></label>
                                                                        <textarea class="form-control" id="cancellation_reason<?php echo $booking->id; ?>" name="admin_note" rows="3" placeholder="Provide a reason for cancellation..." required></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-danger">Cancel Booking</button>
                                                                </div>
                                                            <?php echo form_close(); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <?php if(isset($pagination)): ?>
                            <div class="d-flex justify-content-center mt-4">
                                <?php echo $pagination; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i> Booking Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="card bg-primary text-white h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Total Bookings</h5>
                                    <h2 class="display-4"><?php echo $stats->total ?? 0; ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-success text-white h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Confirmed</h5>
                                    <h2 class="display-4"><?php echo $stats->confirmed ?? 0; ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-warning text-dark h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Pending</h5>
                                    <h2 class="display-4"><?php echo $stats->pending ?? 0; ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-danger text-white h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Cancelled</h5>
                                    <h2 class="display-4"><?php echo $stats->cancelled ?? 0; ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5 class="border-bottom pb-2">Recent Activity</h5>
                            <?php if(empty($recent_activity)): ?>
                                <p class="text-muted">No recent booking activity.</p>
                            <?php else: ?>
                                <ul class="list-group">
                                    <?php foreach($recent_activity as $activity): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="fw-bold">Booking #<?php echo $activity->booking_id; ?></span>: 
                                                <?php echo $activity->action; ?>
                                                <div class="small text-muted"><?php echo date('M d, Y H:i', strtotime($activity->created_at)); ?></div>
                                            </div>
                                            <a href="<?php echo base_url('admin/view_booking/' . $activity->booking_id); ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <h5 class="border-bottom pb-2">Top Vendors by Bookings</h5>
                            <?php if(empty($top_vendors)): ?>
                                <p class="text-muted">No vendor booking data available.</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Vendor</th>
                                                <th>Bookings</th>
                                                <th>Revenue</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($top_vendors as $vendor): ?>
                                                <tr>
                                                    <td>
                                                        <a href="<?php echo base_url('admin/view_vendor/' . $vendor->vendor_id); ?>">
                                                            <?php echo $vendor->business_name; ?>
                                                        </a>
                                                    </td>
                                                    <td><?php echo $vendor->booking_count; ?></td>
                                                    <td>$<?php echo number_format($vendor->total_revenue, 2); ?></td>
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
        </div>
    </div>
</div>