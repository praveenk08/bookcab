<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Manage Vehicles</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('vendor/dashboard'); ?>">Vendor Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Manage Vehicles</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Your Vehicles</h5>
                    <a href="<?php echo base_url('vendor/add_vehicle'); ?>" class="btn btn-light btn-sm">
                        <i class="fas fa-plus-circle me-2"></i> Add New Vehicle
                    </a>
                </div>
                <div class="card-body">
                    <?php if (empty($vehicles)): ?>
                        <div class="alert alert-info">
                            <p class="mb-0">You haven't added any vehicles yet. <a href="<?php echo base_url('vendor/add_vehicle'); ?>">Add your first vehicle</a> to start receiving bookings!</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Vehicle</th>
                                        <th>Type</th>
                                        <th>Capacity</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($vehicles as $vehicle): ?>
                                        <tr>
                                            <td>
                                                <?php 
                                                    $images = json_decode($vehicle->images ?? '[]', true) ?? [];
                                                    if (!empty($images)) {
                                                        echo '<img src="' . base_url('uploads/vehicles/' . $images[0]) . '" alt="' . $vehicle->title . '" class="img-thumbnail" style="width: 80px; height: 60px; object-fit: cover;">';
                                                    } else {
                                                        echo '<div class="bg-light text-center" style="width: 80px; height: 60px; line-height: 60px;"><i class="fas fa-car"></i></div>';
                                                    }
                                                ?>
                                            </td>
                                            <td><?php echo $vehicle->title; ?></td>
                                            <td><?php echo $vehicle->type; ?></td>
                                            <td><?php echo $vehicle->capacity ?? 'N/A'; ?> persons</td>
                                            <td>
                                                ₹<?php echo number_format($vehicle->price_per_day ?? 0); ?>/day
                                                <?php if ($vehicle->fuel_charge_per_km > 0): ?>
                                                    <br>
                                                    <small class="text-muted">+₹<?php echo number_format($vehicle->fuel_charge_per_km ?? 0); ?> fuel</small>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo $vehicle->seats ?? 'N/A'; ?> units</td>
                                            <td>
                                                <?php if ($vehicle->is_active): ?>
                                                    <span class="badge bg-success">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="<?php echo base_url('vendor/edit_vehicle/' . $vehicle->id); ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a href="<?php echo base_url('vendor/manage_availability/' . $vehicle->id); ?>" class="btn btn-sm btn-outline-success">
                                                        <i class="fas fa-calendar-alt"></i> Availability
                                                    </a>
                                                    <a href="<?php echo base_url('vendor/delete_vehicle/' . $vehicle->id); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this vehicle? This action cannot be undone.')">
                                                        <i class="fas fa-trash-alt"></i> Delete
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <?php if (isset($pagination)): ?>
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
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Vehicle Management Tips</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-camera text-primary me-2"></i> Quality Photos</h5>
                                    <p class="card-text">Upload clear, high-quality images of your vehicles from multiple angles. Good photos increase booking chances by 70%.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-calendar-check text-primary me-2"></i> Keep Availability Updated</h5>
                                    <p class="card-text">Regularly update your vehicle availability to avoid double bookings and ensure a smooth experience for customers.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-rupee-sign text-primary me-2"></i> Competitive Pricing</h5>
                                    <p class="card-text">Research market rates and set competitive prices. Consider offering discounts for multi-day bookings to attract more customers.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>