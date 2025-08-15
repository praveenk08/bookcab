<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Manage Drivers</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <?php if ($this->session->userdata('role') === 'vendor'): ?>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('vendor/dashboard'); ?>">Vendor Dashboard</a></li>
                    <?php else: ?>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>">Admin Dashboard</a></li>
                    <?php endif; ?>
                    <li class="breadcrumb-item active" aria-current="page">Manage Drivers</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Drivers</h5>
                    <a href="<?php echo base_url('driver/add'); ?>" class="btn btn-light btn-sm">
                        <i class="fas fa-plus-circle me-1"></i> Add New Driver
                    </a>
                </div>
                <div class="card-body">
                    <!-- Search and Filter Section -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <form action="<?php echo base_url('driver/search'); ?>" method="GET" class="row g-3">
                                <div class="col-md-3">
                                    <input type="text" name="name" class="form-control" placeholder="Driver Name" value="<?php echo isset($_GET['name']) ? $_GET['name'] : ''; ?>">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="license" class="form-control" placeholder="License Number" value="<?php echo isset($_GET['license']) ? $_GET['license'] : ''; ?>">
                                </div>
                                <div class="col-md-2">
                                    <select name="status" class="form-select">
                                        <option value="">All Status</option>
                                        <option value="1" <?php echo (isset($_GET['status']) && $_GET['status'] == '1') ? 'selected' : ''; ?>>Active</option>
                                        <option value="0" <?php echo (isset($_GET['status']) && $_GET['status'] == '0') ? 'selected' : ''; ?>>Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search me-1"></i> Search
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <a href="<?php echo base_url('driver/manage'); ?>" class="btn btn-secondary w-100">
                                        <i class="fas fa-redo me-1"></i> Reset
                                    </a>
                                </div>
                            </form>
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

                    <?php if (empty($drivers)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> No drivers found. 
                            <a href="<?php echo base_url('driver/add'); ?>" class="alert-link">Add your first driver</a> to assign them to bookings.
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
                                        <?php if ($this->session->userdata('role') === 'admin'): ?>
                                            <th>Vendor</th>
                                        <?php endif; ?>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($drivers as $driver): ?>
                                        <tr>
                                            <td>
                                                <?php if (!empty($driver->photo) && file_exists('./uploads/drivers/' . $driver->photo)): ?>
                                                    <img src="<?php echo base_url('uploads/drivers/' . $driver->photo); ?>" alt="<?php echo isset($driver->name) ? $driver->name : 'Driver'; ?>" class="rounded-circle" width="50" height="50" style="object-fit: cover;" onerror="this.onerror=null; this.src='<?php echo base_url('assets/images/no-image.png'); ?>';">
                                                <?php else: ?>
                                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white" style="width: 50px; height: 50px;">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo $driver->name; ?></td>
                                            <td><?php echo $driver->phone; ?></td>
                                            <td><?php echo isset($driver->license_number) ? $driver->license_number : 'N/A'; ?></td>
                                            <td><?php echo isset($driver->experience_years) ? $driver->experience_years : 'N/A'; ?> years</td>
                                            <?php if ($this->session->userdata('role') === 'admin'): ?>
                                                <td>
                                                    <?php 
                                                        $vendor = $this->vendor_model->get_vendor_by_id($driver->vendor_id);
                                                        echo $vendor ? $vendor->business_name : 'N/A';
                                                    ?>
                                                </td>
                                            <?php endif; ?>
                                            <td>
                                                <?php if (isset($driver->is_active) && $driver->is_active): ?>
                                                    <span class="badge bg-success">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo base_url('driver/view/' . $driver->id); ?>" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="View Driver">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="<?php echo base_url('driver/edit/' . $driver->id); ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit Driver">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteDriverModal<?php echo $driver->id; ?>" data-bs-toggle="tooltip" title="Delete Driver">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                    <?php if(isset($driver->license_document) && !empty($driver->license_document)): ?>
                                                    <a href="<?php echo base_url('uploads/drivers/' . $driver->license_document); ?>" class="btn btn-sm btn-outline-secondary" target="_blank" data-bs-toggle="tooltip" title="View License">
                                                        <i class="fas fa-id-card"></i>
                                                    </a>
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
                                                                <p class="text-danger"><i class="fas fa-exclamation-triangle me-2"></i> This action cannot be undone.</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <a href="<?php echo base_url('driver/delete/' . $driver->id); ?>" class="btn btn-danger">Delete</a>
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
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>