<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Driver Details</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <?php if ($this->session->userdata('role') === 'vendor'): ?>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('vendor/dashboard'); ?>">Vendor Dashboard</a></li>
                    <?php else: ?>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>">Admin Dashboard</a></li>
                    <?php endif; ?>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('driver/manage'); ?>">Manage Drivers</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Driver Details</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Driver Information</h5>
                    <div>
                        <a href="<?php echo base_url('driver/edit/'.$driver->id); ?>" class="btn btn-light btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="<?php echo base_url('driver/manage'); ?>" class="btn btn-light btn-sm ms-2">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4 text-center">
                            <?php if (!empty($driver->photo) && file_exists('./uploads/drivers/' . $driver->photo)): ?>
                                <img src="<?php echo base_url('uploads/drivers/' . $driver->photo); ?>" alt="Driver Photo" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;" onerror="this.onerror=null; this.src='<?php echo base_url('assets/images/no-image.png'); ?>';">
                            <?php else: ?>
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 150px; height: 150px; margin: 0 auto;">
                                    <i class="fas fa-user fa-4x text-secondary"></i>
                                </div>
                            <?php endif; ?>
                            <div class="mt-2">
                                <span class="badge <?php echo isset($driver->is_active) && $driver->is_active ? 'bg-success' : 'bg-danger'; ?>">
                                    <?php echo isset($driver->is_active) && $driver->is_active ? 'Active' : 'Inactive'; ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h3 class="mb-3"><?php echo $driver->name; ?></h3>
                            <div class="driver-info">
                                <p><strong><i class="fas fa-phone-alt me-2 text-primary"></i> Phone:</strong> <?php echo $driver->phone; ?></p>
                                <p><strong><i class="fas fa-id-card me-2 text-primary"></i> License Number:</strong> <?php echo $driver->license_number; ?></p>
                                <p><strong><i class="fas fa-star me-2 text-primary"></i> Experience:</strong> <?php echo $driver->experience_years; ?> years</p>
                                <?php if (!empty($driver->address)): ?>
                                    <p><strong><i class="fas fa-map-marker-alt me-2 text-primary"></i> Address:</strong> <?php echo $driver->address; ?></p>
                                <?php endif; ?>
                                <?php if ($this->session->userdata('role') === 'admin'): ?>
                                    <p><strong><i class="fas fa-building me-2 text-primary"></i> Vendor:</strong> 
                                        <?php 
                                            $vendor = $this->vendor_model->get_vendor_by_id($driver->vendor_id);
                                            echo $vendor ? $vendor->business_name : 'N/A'; 
                                        ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($driver->license_document)): ?>
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2">License Document</h5>
                            <div class="mt-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-file-alt me-2 text-primary fa-2x"></i>
                                    <div>
                                        <p class="mb-1"><?php echo $driver->license_document; ?></p>
                                        <a href="<?php echo base_url('uploads/drivers/' . $driver->license_document); ?>" class="btn btn-sm btn-outline-primary" target="_blank">
                                            <i class="fas fa-eye"></i> View Document
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($driver_bookings)): ?>
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2">Recent Bookings</h5>
                            <div class="table-responsive mt-3">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Booking ID</th>
                                            <th>Vehicle</th>
                                            <th>Customer</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($driver_bookings as $booking): ?>
                                        <tr>
                                            <td>#<?php echo $booking->id; ?></td>
                                            <td><?php echo $booking->vehicle_name; ?></td>
                                            <td><?php echo $booking->customer_name; ?></td>
                                            <td><?php echo date('d M Y', strtotime($booking->booking_date)); ?></td>
                                            <td>
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
                                            </td>
                                            <td>
                                                <a href="<?php echo base_url('booking/view/' . $booking->id); ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>