<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Booking #<?php echo $booking->id; ?></h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('booking/user_bookings'); ?>">My Bookings</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Booking #<?php echo $booking->id; ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Booking Details</h5>
                    <span class="badge bg-<?php 
                        echo $booking->status === 'PENDING' ? 'warning' : 
                            ($booking->status === 'CONFIRMED' ? 'success' : 
                                ($booking->status === 'ONGOING' ? 'info' : 
                                    ($booking->status === 'COMPLETED' ? 'primary' : 'danger'))); 
                    ?>">
                        <?php echo $booking->status; ?>
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Booking Information</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><strong>Booking ID:</strong> #<?php echo $booking->id; ?></li>
                                <li class="list-group-item"><strong>Status:</strong> <?php echo $booking->status; ?></li>
                                <li class="list-group-item"><strong>Created:</strong> <?php echo date('M d, Y h:i A', strtotime($booking->created_at)); ?></li>
                                <li class="list-group-item"><strong>Total Price:</strong> ₹<?php echo number_format($booking->total_price); ?></li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Customer Information</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><strong>Name:</strong> <?php echo $user->name; ?></li>
                                <li class="list-group-item"><strong>Email:</strong> <?php echo $user->email; ?></li>
                                <li class="list-group-item"><strong>Phone:</strong> <?php echo $user->phone ?: 'Not provided'; ?></li>
                            </ul>
                        </div>
                    </div>
                    
                    <?php if (!empty($booking->notes)): ?>
                        <div class="mb-4">
                            <h6>Booking Notes</h6>
                            <div class="alert alert-secondary">
                                <?php echo nl2br($booking->notes); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <h6>Booked Vehicles</h6>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Vehicle</th>
                                    <th>Vendor</th>
                                    <th>Dates</th>
                                    <th>Quantity</th>
                                    <th>Driver</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($booking_items as $item): ?>
                                    <tr>
                                        <td><?php echo $item->vehicle_title; ?> (<?php echo $item->vehicle_type; ?>)</td>
                                        <td><?php echo $item->vendor_name; ?></td>
                                        <td>
                                            <?php echo date('M d, Y', strtotime($item->date_from)); ?> to 
                                            <?php echo date('M d, Y', strtotime($item->date_to)); ?>
                                            <br>
                                            <small class="text-muted">
                                                <?php 
                                                    $from_timestamp = strtotime($item->date_from);
                                                    $to_timestamp = strtotime($item->date_to);
                                                    $days = ceil(($to_timestamp - $from_timestamp) / (60 * 60 * 24));
                                                    echo $days; 
                                                ?> day(s)
                                            </small>
                                        </td>
                                        <td><?php echo $item->qty; ?></td>
                                        <td>
                                            <?php if ($item->driver_id): ?>
                                                <?php echo $item->driver_name; ?>
                                                <br>
                                                <small class="text-muted">License: <?php echo $item->driver_license; ?></small>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Not Assigned</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>₹<?php echo number_format($item->price); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-end">Total:</th>
                                    <th>₹<?php echo number_format($booking->total_price); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?php echo base_url('booking/user_bookings'); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Bookings
                        </a>
                        
                        <?php if ($booking->status === 'PENDING' || $booking->status === 'CONFIRMED'): ?>
                            <a href="<?php echo base_url('booking/cancel/' . $booking->id); ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this booking?')">
                                <i class="fas fa-times-circle me-2"></i> Cancel Booking
                            </a>
                        <?php elseif ($booking->status === 'COMPLETED' && $this->session->userdata('user_id') == $booking->user_id): ?>
                            <a href="<?php echo base_url('booking/add_review/' . $booking->id); ?>" class="btn btn-primary">
                                <i class="fas fa-star me-2"></i> Add Review
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <?php if (!empty($audit_logs)): ?>
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Booking History</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <?php foreach ($audit_logs as $log): ?>
                                <div class="timeline-item">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-0"><?php echo $log->action_type; ?></h6>
                                        <p class="mb-0"><?php echo $log->description; ?></p>
                                        <small class="text-muted"><?php echo date('M d, Y h:i A', strtotime($log->created_at)); ?></small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-md-4">
            <?php if ($is_vendor_booking && $booking->status === 'CONFIRMED'): ?>
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Vendor Actions</h5>
                    </div>
                    <div class="card-body">
                        <p>Assign drivers to the vehicles for this booking:</p>
                        <?php echo form_open('vendor/assign_drivers/' . $booking->id); ?>
                            <?php foreach ($booking_items as $item): ?>
                                <?php if ($item->vendor_id === $vendor->id): ?>
                                    <div class="mb-3">
                                        <label class="form-label"><?php echo $item->vehicle_title; ?> (<?php echo $item->qty; ?> units)</label>
                                        <select name="driver_id[<?php echo $item->id; ?>]" class="form-select">
                                            <option value="">Select Driver</option>
                                            <?php foreach ($drivers as $driver): ?>
                                                <option value="<?php echo $driver->id; ?>" <?php echo ($item->driver_id == $driver->id) ? 'selected' : ''; ?>>
                                                    <?php echo $driver->name; ?> (License: <?php echo $driver->license_no; ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Save Driver Assignments
                                </button>
                            </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ($this->session->userdata('role') === 'admin'): ?>
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Admin Actions</h5>
                    </div>
                    <div class="card-body">
                        <?php echo form_open('admin/change_booking_status/' . $booking->id); ?>
                            <div class="mb-3">
                                <label for="status" class="form-label">Change Booking Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="PENDING" <?php echo ($booking->status === 'PENDING') ? 'selected' : ''; ?>>PENDING</option>
                                    <option value="CONFIRMED" <?php echo ($booking->status === 'CONFIRMED') ? 'selected' : ''; ?>>CONFIRMED</option>
                                    <option value="ONGOING" <?php echo ($booking->status === 'ONGOING') ? 'selected' : ''; ?>>ONGOING</option>
                                    <option value="COMPLETED" <?php echo ($booking->status === 'COMPLETED') ? 'selected' : ''; ?>>COMPLETED</option>
                                    <option value="CANCELLED" <?php echo ($booking->status === 'CANCELLED') ? 'selected' : ''; ?>>CANCELLED</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="admin_notes" class="form-label">Admin Notes</label>
                                <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3"></textarea>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Update Status
                                </button>
                            </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Need Help?</h5>
                </div>
                <div class="card-body">
                    <p>If you have any questions or need assistance with your booking, please contact our support team:</p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><i class="fas fa-envelope me-2 text-primary"></i> support@carbooking.com</li>
                        <li class="list-group-item"><i class="fas fa-phone me-2 text-primary"></i> +91 1234567890</li>
                        <li class="list-group-item"><i class="fas fa-comment me-2 text-primary"></i> Live Chat (9 AM - 6 PM)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
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
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: #4e73df;
        border: 2px solid #fff;
    }
    
    .timeline-content {
        padding-bottom: 10px;
    }
</style>