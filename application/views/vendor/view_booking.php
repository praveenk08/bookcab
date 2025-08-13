<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Booking Details</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('vendor/dashboard'); ?>">Vendor Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('vendor/bookings'); ?>">Manage Bookings</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Booking #<?php echo $booking->id; ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?php echo $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger">
            <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Booking Summary -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Booking Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div>Booking ID:</div>
                        <div class="fw-bold">#<?php echo $booking->id; ?></div>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <div>Status:</div>
                        <div>
                            <?php 
                            $status_class = '';
                            switch ($booking->status) {
                                case 'pending':
                                    $status_class = 'bg-warning';
                                    break;
                                case 'confirmed':
                                    $status_class = 'bg-success';
                                    break;
                                case 'completed':
                                    $status_class = 'bg-primary';
                                    break;
                                case 'cancelled':
                                    $status_class = 'bg-danger';
                                    break;
                                default:
                                    $status_class = 'bg-secondary';
                            }
                            ?>
                            <span class="badge <?php echo $status_class; ?>">
                                <?php echo ucfirst($booking->status); ?>
                            </span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <div>Booking Date:</div>
                        <div><?php echo date('M d, Y', strtotime($booking->created_at)); ?></div>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <div>Total Amount:</div>
                        <div class="fw-bold">$<?php echo number_format($booking->total_amount, 2); ?></div>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <div>Payment Status:</div>
                        <div>
                            <?php if (isset($booking->payment_status) && $booking->payment_status == 'completed'): ?>
                                <span class="badge bg-success">Paid</span>
                            <?php else: ?>
                                <span class="badge bg-warning">Pending</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php if ($booking->status == 'pending'): ?>
                <div class="card-footer">
                    <div class="d-grid gap-2">
                        <a href="<?php echo base_url('vendor/update_booking_status/' . $booking->id . '/confirmed'); ?>" class="btn btn-success">
                            <i class="fas fa-check me-2"></i> Confirm Booking
                        </a>
                    </div>
                </div>
                <?php elseif ($booking->status == 'confirmed'): ?>
                <div class="card-footer">
                    <div class="d-grid gap-2">
                        <a href="<?php echo base_url('vendor/update_booking_status/' . $booking->id . '/completed'); ?>" class="btn btn-primary">
                            <i class="fas fa-flag-checkered me-2"></i> Mark as Completed
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name</label>
                            <div class="form-control bg-light"><?php echo $booking->user_name; ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <div class="form-control bg-light"><?php echo $booking->user_email; ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone</label>
                            <div class="form-control bg-light"><?php echo $booking->user_phone ?? 'Not provided'; ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pickup Date & Time</label>
                            <div class="form-control bg-light">
                                <?php 
                                if (isset($booking->pickup_date) && isset($booking->pickup_time)) {
                                    echo date('M d, Y', strtotime($booking->pickup_date)) . ' at ' . date('g:i A', strtotime($booking->pickup_time));
                                } else {
                                    echo 'Not specified';
                                }
                                ?>
                            </div>
                        </div>
                        <?php if (isset($booking->pickup_location)): ?>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Pickup Location</label>
                            <div class="form-control bg-light"><?php echo $booking->pickup_location; ?></div>
                        </div>
                        <?php endif; ?>
                        <?php if (isset($booking->drop_location)): ?>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Drop-off Location</label>
                            <div class="form-control bg-light"><?php echo $booking->drop_location; ?></div>
                        </div>
                        <?php endif; ?>
                        <?php if (isset($booking->special_instructions) && !empty($booking->special_instructions)): ?>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Special Instructions</label>
                            <div class="form-control bg-light" style="min-height: 80px;"><?php echo $booking->special_instructions; ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Items -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Booked Vehicles</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Vehicle</th>
                                    <th>Type</th>
                                    <th>Date Range</th>
                                    <th>Driver</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($booking->items as $item): ?>
                                    <?php if ($item->vendor_id == $this->vendor_model->get_vendor_id_by_user_id($this->session->userdata('user_id'))): ?>
                                    <tr>
                                        <td><?php echo $item->vehicle_title; ?></td>
                                        <td><?php echo $item->vehicle_type; ?></td>
                                        <td>
                                            <?php 
                                            if (isset($item->date_from) && isset($item->date_to)) {
                                                echo date('M d', strtotime($item->date_from)) . ' - ' . date('M d, Y', strtotime($item->date_to));
                                            } else {
                                                echo 'Not specified';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php if (isset($item->driver_name) && !empty($item->driver_name)): ?>
                                                <?php echo $item->driver_name; ?>
                                            <?php else: ?>
                                                <span class="text-muted">No driver</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo isset($item->qty) ? $item->qty : 1; ?></td>
                                        <td>$<?php echo isset($item->price) ? number_format($item->price, 2) : '0.00'; ?></td>
                                        <td>$<?php echo isset($item->subtotal) ? number_format($item->subtotal, 2) : '0.00'; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>