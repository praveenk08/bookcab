<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Manage Drivers</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('vendor/dashboard'); ?>">Vendor Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Manage Drivers</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Your Drivers</h5>
                    <a href="<?php echo base_url('vendor/add_driver'); ?>" class="btn btn-light btn-sm">
                        <i class="fas fa-plus-circle me-1"></i> Add New Driver
                    </a>
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

                    <?php if (empty($drivers)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> You haven't added any drivers yet. 
                            <a href="<?php echo base_url('vendor/add_driver'); ?>" class="alert-link">Add your first driver</a> to assign them to bookings.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>License No.</th>
                                        <th>Experience</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($drivers as $driver): ?>
                                        <tr>
                                            <td>
                                                <?php if (!empty($driver->photo)): ?>
                                                    <img src="<?php echo base_url('uploads/drivers/' . $driver->photo); ?>" alt="<?php echo $driver->name; ?>" class="rounded-circle" width="50" height="50" style="object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white" style="width: 50px; height: 50px;">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo $driver->name; ?></td>
                                            <td><?php echo $driver->phone; ?></td>
                                            <td><?php echo isset($driver->license_no) ? $driver->license_no : 'N/A'; ?></td>
                                            <td><?php echo isset($driver->experience_years) ? $driver->experience_years : 'N/A'; ?> years</td>
                                            <td>
                                                <?php if ($driver->is_active): ?>
                                                    <span class="badge bg-success">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo base_url('vendor/edit_driver/' . $driver->id); ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit Driver">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteDriverModal<?php echo $driver->id; ?>" data-bs-toggle="tooltip" title="Delete Driver">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                    <?php if(isset($driver->license_document) && !empty($driver->license_document)): ?>
                                                    <a href="<?php echo base_url('uploads/drivers/' . $driver->license_document); ?>" class="btn btn-sm btn-outline-info" target="_blank" data-bs-toggle="tooltip" title="View License">
                                                        <i class="fas fa-id-card"></i>
                                                    </a>
                                                    <?php else: ?>
                                                    <button class="btn btn-sm btn-outline-secondary" disabled data-bs-toggle="tooltip" title="No License Document">
                                                        <i class="fas fa-id-card"></i>
                                                    </button>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="deleteDriverModal<?php echo $driver->id; ?>" tabindex="-1" aria-labelledby="deleteDriverModalLabel<?php echo $driver->id; ?>" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-danger text-white">
                                                                <h5 class="modal-title" id="deleteDriverModalLabel<?php echo $driver->id; ?>">Confirm Delete</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Are you sure you want to delete driver <strong><?php echo $driver->name; ?></strong>?</p>
                                                                <div class="alert alert-warning">
                                                                    <i class="fas fa-exclamation-triangle me-2"></i> This action cannot be undone. If this driver is assigned to any active bookings, they will be unassigned.
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <a href="<?php echo base_url('vendor/delete_driver/' . $driver->id); ?>" class="btn btn-danger">Delete Driver</a>
                                                            </div>
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
                    <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i> Driver Management Tips</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-check-circle text-success me-2"></i> Best Practices</h6>
                            <ul class="list-unstyled ps-3">
                                <li><i class="fas fa-angle-right me-2"></i> Keep driver documents up to date</li>
                                <li><i class="fas fa-angle-right me-2"></i> Verify driver's license validity regularly</li>
                                <li><i class="fas fa-angle-right me-2"></i> Maintain accurate contact information</li>
                                <li><i class="fas fa-angle-right me-2"></i> Set driver status to inactive when unavailable</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-exclamation-triangle text-warning me-2"></i> Important Notes</h6>
                            <ul class="list-unstyled ps-3">
                                <li><i class="fas fa-angle-right me-2"></i> Drivers can be assigned to multiple bookings if timing allows</li>
                                <li><i class="fas fa-angle-right me-2"></i> You'll receive notifications when drivers are assigned to bookings</li>
                                <li><i class="fas fa-angle-right me-2"></i> Customers can rate drivers after completed bookings</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>