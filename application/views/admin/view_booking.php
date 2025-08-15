<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo site_url(); ?>"><i class="fas fa-home me-1"></i>Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="fas fa-tachometer-alt me-1"></i>Admin Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo site_url('admin/bookings'); ?>"><i class="fas fa-calendar-check me-1"></i>Booking Management</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Booking #<?php echo $booking->id; ?></li>
                </ol>
            </nav>
            <h1 class="mt-3 mb-3"><i class="fas fa-receipt me-2"></i>Booking Details #<?php echo $booking->id; ?></h1>
        </div>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo $this->session->flashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo $this->session->flashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if ($this->session->flashdata('otp_required')): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-key me-2"></i><?php echo $this->session->flashdata('otp_required'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Booking Information</h5>
                    <div>
                        <a href="<?php echo base_url('admin/bookings'); ?>" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back to Bookings
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="border-bottom pb-2 mb-3">Booking Details</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th style="width: 30%">Booking ID:</th>
                                    <td><strong>#<?php echo $booking->id; ?></strong></td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
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
                                </tr>
                                <tr>
                                    <th>Booking Date:</th>
                                    <td><?php echo date('M d, Y H:i', strtotime($booking->created_at)); ?></td>
                                </tr>
                                <tr>
                                    <th>Rental Period:</th>
                                    <td>
                                        <?php echo date('M d, Y', strtotime($booking->from_date)); ?> to 
                                        <?php echo date('M d, Y', strtotime($booking->to_date)); ?>
                                        (<?php echo $booking->days; ?> days)
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total Vehicles:</th>
                                    <td><?php echo count($booking_items); ?></td>
                                </tr>
                                <tr>
                                    <th>Total Amount:</th>
                                    <td><strong>$<?php echo number_format($booking->total_amount, 2); ?></strong></td>
                                </tr>
                                <tr>
                                    <th>Payment Status:</th>
                                    <td>
                                        <?php if ($booking->payment_status == 'paid'): ?>
                                            <span class="badge bg-success">Paid</span>
                                        <?php elseif ($booking->payment_status == 'pending'): ?>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Unpaid</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php if (!empty($booking->notes)): ?>
                                    <tr>
                                        <th>Customer Notes:</th>
                                        <td><?php echo nl2br($booking->notes); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5 class="border-bottom pb-2 mb-3">Customer Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th style="width: 30%">Name:</th>
                                    <td><?php echo $user->name; ?></td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td><?php echo $user->email; ?></td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td><?php echo $user->phone; ?></td>
                                </tr>
                                <tr>
                                    <th>Address:</th>
                                    <td>
                                        <?php if (!empty($user->address)): ?>
                                            <?php echo $user->address; ?>
                                        <?php else: ?>
                                            <span class="text-muted">Not provided</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Member Since:</th>
                                    <td><?php echo date('M d, Y', strtotime($user->created_at)); ?></td>
                                </tr>
                                <tr>
                                    <th>Total Bookings:</th>
                                    <td>
                                        <?php if (isset($user_booking_count)): ?>
                                            <?php echo $user_booking_count; ?> bookings
                                        <?php else: ?>
                                            <span class="text-muted">Unknown</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                            
                            <div class="d-flex justify-content-end mt-3">
                                <?php if ($booking->status == 'pending'): ?>
                                    <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#otpVerificationModal" data-action="confirm">
                                        <i class="fas fa-check me-1"></i> Confirm
                                    </button>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#otpVerificationModal" data-action="cancel">
                                        <i class="fas fa-times me-1"></i> Cancel
                                    </button>
                                <?php elseif ($booking->status == 'confirmed'): ?>
                                    <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#otpVerificationModal" data-action="complete">
                                        <i class="fas fa-flag-checkered me-1"></i> Mark as Completed
                                    </button>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#otpVerificationModal" data-action="cancel">
                                        <i class="fas fa-times me-1"></i> Cancel
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Booked Vehicles</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Vehicle</th>
                                    <th>Vendor</th>
                                    <th>Type</th>
                                    <th>Quantity</th>
                                    <th>Price/Day</th>
                                    <th>Days</th>
                                    <th>Subtotal</th>
                                    <th>Driver</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($booking_items as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if (!empty($item->vehicle_image)): ?>
                                                    <img src="<?php echo base_url('uploads/vehicles/' . $item->vehicle_image); ?>" alt="<?php echo $item->vehicle_title; ?>" class="img-thumbnail me-2" style="width: 60px; height: 45px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="bg-light d-flex align-items-center justify-content-center me-2" style="width: 60px; height: 45px;">
                                                        <i class="fas fa-car fa-lg text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <a href="<?php echo base_url('vehicle/view/' . $item->vehicle_id); ?>" target="_blank">
                                                        <?php echo $item->vehicle_title; ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="<?php echo base_url('admin/view_vendor/' . $item->vendor_id); ?>">
                                                <?php echo $item->business_name; ?>
                                            </a>
                                        </td>
                                        <td><?php echo $item->vehicle_type; ?></td>
                                        <td><?php echo $item->quantity; ?></td>
                                        <td>$<?php echo number_format($item->price_per_day, 2); ?></td>
                                        <td><?php echo $booking->days; ?></td>
                                        <td>$<?php echo number_format($item->subtotal, 2); ?></td>
                                        <td>
                                            <?php if (!empty($item->driver_id)): ?>
                                                <span class="badge bg-info text-dark"><?php echo $item->driver_name; ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Not Assigned</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6" class="text-end"><strong>Total:</strong></td>
                                    <td colspan="2"><strong>$<?php echo number_format($booking->total_amount, 2); ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Booking History</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($audit_logs)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> No history records found for this booking.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Date & Time</th>
                                        <th>Action</th>
                                        <th>User</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($audit_logs as $log): ?>
                                        <tr>
                                            <td><?php echo date('M d, Y H:i', strtotime($log->created_at)); ?></td>
                                            <td>
                                                <?php if (strpos($log->action, 'created') !== false): ?>
                                                    <span class="badge bg-success"><?php echo $log->action; ?></span>
                                                <?php elseif (strpos($log->action, 'cancelled') !== false): ?>
                                                    <span class="badge bg-danger"><?php echo $log->action; ?></span>
                                                <?php elseif (strpos($log->action, 'confirmed') !== false): ?>
                                                    <span class="badge bg-primary"><?php echo $log->action; ?></span>
                                                <?php elseif (strpos($log->action, 'completed') !== false): ?>
                                                    <span class="badge bg-info text-dark"><?php echo $log->action; ?></span>
                                                <?php elseif (strpos($log->action, 'assigned') !== false): ?>
                                                    <span class="badge bg-warning text-dark"><?php echo $log->action; ?></span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary"><?php echo $log->action; ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($log->user_role == 'admin'): ?>
                                                    <span class="badge bg-danger">Admin</span>
                                                <?php elseif ($log->user_role == 'vendor'): ?>
                                                    <span class="badge bg-primary">Vendor</span>
                                                <?php else: ?>
                                                    <span class="badge bg-info text-dark">Customer</span>
                                                <?php endif; ?>
                                                <?php echo $log->user_name; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($log->notes)): ?>
                                                    <?php echo $log->notes; ?>
                                                <?php else: ?>
                                                    <span class="text-muted">No notes</span>
                                                <?php endif; ?>
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

    <!-- OTP Verification Modal -->
    <div class="modal fade" id="otpVerificationModal" tabindex="-1" aria-labelledby="otpVerificationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="otpVerificationModalLabel"><i class="fas fa-shield-alt me-2"></i>Verification Required</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-key fa-3x text-primary mb-3"></i>
                        <h4>Email Verification</h4>
                        <p class="text-muted">We need to verify your identity before proceeding with this action.</p>
                    </div>
                    
                    <div class="mb-3">
                        <label for="verification-email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" id="verification-email" value="<?php echo $this->session->userdata('email'); ?>" readonly>
                        </div>
                    </div>
                    
                    <button id="send-verification" class="btn btn-primary w-100" data-action="">Send Verification Code</button>
                    
                    <div id="otp-verification-container" class="d-none mt-4">
                        <div class="text-center mb-3">
                            <p>Enter the 6-digit code sent to your email</p>
                            <div class="otp-input-container">
                                <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                                <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                                <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                                <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                                <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                                <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">Code expires in <span id="otp-countdown">10:00</span></small>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button id="verify-otp" class="btn btn-success">Verify Code</button>
                            <button id="resend-otp" class="btn btn-outline-secondary" disabled>Resend Code</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <div id="action-buttons" class="d-none">
                        <!-- Action buttons will be dynamically added here after OTP verification -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Confirm Modal -->
    <div class="modal fade" id="confirmBookingModal" tabindex="-1" aria-labelledby="confirmBookingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="confirmBookingModalLabel"><i class="fas fa-check-circle me-2"></i>Confirm Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <?php echo form_open('admin/change_booking_status/' . $booking->id); ?>
                    <input type="hidden" name="status" value="confirmed">
                    <div class="modal-body">
                        <div class="alert alert-success">
                            <i class="fas fa-shield-alt me-2"></i> Your identity has been verified successfully.
                        </div>
                        <p>Are you sure you want to confirm booking <strong>#<?php echo $booking->id; ?></strong>?</p>
                        <div class="mb-3">
                            <label for="admin_note" class="form-label">Admin Note (Optional)</label>
                            <textarea class="form-control" id="admin_note" name="admin_note" rows="3" placeholder="Add any notes about this confirmation..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success verification-required" disabled>Confirm Booking</button>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
    
    <!-- Complete Modal -->
    <div class="modal fade" id="completeBookingModal" tabindex="-1" aria-labelledby="completeBookingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="completeBookingModalLabel"><i class="fas fa-flag-checkered me-2"></i>Mark Booking as Completed</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <?php echo form_open('admin/change_booking_status/' . $booking->id); ?>
                    <input type="hidden" name="status" value="completed">
                    <div class="modal-body">
                        <div class="alert alert-success">
                            <i class="fas fa-shield-alt me-2"></i> Your identity has been verified successfully.
                        </div>
                        <p>Are you sure you want to mark booking <strong>#<?php echo $booking->id; ?></strong> as completed?</p>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> This will allow the customer to leave reviews for the vendors and vehicles.
                        </div>
                        <div class="mb-3">
                            <label for="admin_note_complete" class="form-label">Admin Note (Optional)</label>
                            <textarea class="form-control" id="admin_note_complete" name="admin_note" rows="3" placeholder="Add any notes about this completion..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary verification-required" disabled>Mark as Completed</button>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
    
    <!-- Cancel Modal -->
    <div class="modal fade" id="cancelBookingModal" tabindex="-1" aria-labelledby="cancelBookingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="cancelBookingModalLabel"><i class="fas fa-times-circle me-2"></i>Cancel Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <?php echo form_open('admin/change_booking_status/' . $booking->id); ?>
                    <input type="hidden" name="status" value="cancelled">
                    <div class="modal-body">
                        <div class="alert alert-success">
                            <i class="fas fa-shield-alt me-2"></i> Your identity has been verified successfully.
                        </div>
                        <p>Are you sure you want to cancel booking <strong>#<?php echo $booking->id; ?></strong>?</p>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i> This will release all reserved vehicles and notify the customer and vendors.
                        </div>
                        <div class="mb-3">
                            <label for="cancellation_reason" class="form-label">Cancellation Reason <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="cancellation_reason" name="admin_note" rows="3" placeholder="Provide a reason for cancellation..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger verification-required" disabled>Cancel Booking</button>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    // Handle OTP verification modal actions
    document.addEventListener('DOMContentLoaded', function() {
        const otpModal = document.getElementById('otpVerificationModal');
        const actionButtons = document.getElementById('action-buttons');
        const sendVerificationBtn = document.getElementById('send-verification');
        
        // Set action when opening the modal
        document.querySelectorAll('[data-bs-target="#otpVerificationModal"]').forEach(button => {
            button.addEventListener('click', function() {
                const action = this.getAttribute('data-action');
                sendVerificationBtn.setAttribute('data-action', action);
                
                // Clear previous verification state
                document.getElementById('otp-verification-container').classList.add('d-none');
                actionButtons.classList.add('d-none');
                actionButtons.innerHTML = '';
            });
        });
        
        // After successful verification, show the appropriate action modal
        document.getElementById('verify-otp').addEventListener('click', function() {
            // This is handled in the otp-verification.js file
            // After verification, we'll add a handler to show the appropriate modal
            const action = sendVerificationBtn.getAttribute('data-action');
            
            // Add action buttons based on the action type
            actionButtons.innerHTML = `
                <button type="button" class="btn btn-${action === 'confirm' ? 'success' : (action === 'complete' ? 'primary' : 'danger')}" 
                        data-bs-toggle="modal" 
                        data-bs-target="#${action}BookingModal">
                    Proceed to ${action.charAt(0).toUpperCase() + action.slice(1)}
                </button>
            `;
            
            // Show action buttons
            actionButtons.classList.remove('d-none');
        });
    });
</script>