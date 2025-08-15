<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Assign Driver to Booking</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <?php if ($this->session->userdata('role') === 'vendor'): ?>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('vendor/dashboard'); ?>">Vendor Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('vendor/bookings'); ?>">Manage Bookings</a></li>
                    <?php else: ?>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>">Admin Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/bookings'); ?>">Manage Bookings</a></li>
                    <?php endif; ?>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('booking/view/' . $booking->id); ?>">Booking #<?php echo $booking->id; ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Assign Driver</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Assign Driver to Booking #<?php echo $booking->id; ?></h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $this->session->flashdata('error'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo $this->session->flashdata('success'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <div class="booking-details mb-4">
                        <h5 class="border-bottom pb-2">Booking Details</h5>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <p><strong>Customer:</strong> <?php echo $booking->customer_name; ?></p>
                                <p><strong>Vehicle:</strong> <?php echo $booking->vehicle_name; ?></p>
                                <p><strong>Booking Date:</strong> <?php echo date('d M Y', strtotime($booking->booking_date)); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Pickup Location:</strong> <?php echo $booking->pickup_location; ?></p>
                                <p><strong>Dropoff Location:</strong> <?php echo $booking->dropoff_location; ?></p>
                                <p><strong>Status:</strong> 
                                    <span class="badge bg-<?php 
                                        switch($booking->status) {
                                            case 'pending': echo 'warning'; break;
                                            case 'confirmed': echo 'success'; break;
                                            case 'cancelled': echo 'danger'; break;
                                            case 'completed': echo 'info'; break;
                                            default: echo 'secondary';
                                        }
                                    ?>">
                                        <?php echo ucfirst($booking->status); ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <?php echo form_open('driver/assign/' . $booking->id); ?>
                        <div class="mb-3">
                            <label for="driver_id" class="form-label">Select Driver *</label>
                            <?php if (!empty($drivers)): ?>
                                <select class="form-select" id="driver_id" name="driver_id" required>
                                    <option value="">Select a driver</option>
                                    <?php foreach ($drivers as $driver): ?>
                                        <option value="<?php echo $driver->id; ?>" <?php echo (isset($booking->driver_id) && $booking->driver_id == $driver->id) ? 'selected' : ''; ?>>
                                            <?php echo $driver->name; ?> (<?php echo $driver->phone; ?>) - <?php echo $driver->experience_years; ?> years exp.
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php echo form_error('driver_id', '<div class="text-danger">', '</div>'); ?>
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i> No active drivers available. Please add drivers first.
                                    <div class="mt-2">
                                        <a href="<?php echo base_url('driver/add'); ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-plus"></i> Add New Driver
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Assignment Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"><?php echo isset($booking->driver_notes) ? $booking->driver_notes : ''; ?></textarea>
                            <div class="form-text">Optional notes for the driver about this booking.</div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?php echo base_url('booking/view/' . $booking->id); ?>" class="btn btn-secondary me-md-2">Cancel</a>
                            <?php if (!empty($drivers)): ?>
                                <button type="submit" class="btn btn-primary">Assign Driver</button>
                            <?php endif; ?>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>