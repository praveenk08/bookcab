<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Vendor Management</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>">Admin Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Vendor Management</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Vendors</h5>
                    <div>
                        <a href="<?php echo base_url('admin/vendors?status=pending'); ?>" class="btn btn-warning btn-sm me-2">
                            <i class="fas fa-clock me-1"></i> Pending
                            <?php if(isset($pending_count) && $pending_count > 0): ?>
                                <span class="badge bg-light text-dark ms-1"><?php echo $pending_count; ?></span>
                            <?php endif; ?>
                        </a>
                        <a href="<?php echo base_url('admin/vendors?status=approved'); ?>" class="btn btn-success btn-sm me-2">
                            <i class="fas fa-check-circle me-1"></i> Approved
                        </a>
                        <a href="<?php echo base_url('admin/vendors?status=rejected'); ?>" class="btn btn-danger btn-sm me-2">
                            <i class="fas fa-times-circle me-1"></i> Rejected
                        </a>
                        <a href="<?php echo base_url('admin/vendors'); ?>" class="btn btn-light btn-sm">
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
                            <?php echo form_open('admin/vendors', ['method' => 'GET', 'class' => 'row g-3']); ?>
                                <div class="col-md-3">
                                    <label for="search" class="form-label">Search</label>
                                    <input type="text" class="form-control" id="search" name="search" placeholder="Business name, email..." value="<?php echo $this->input->get('search'); ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="" <?php echo $this->input->get('status') == '' ? 'selected' : ''; ?>>All Statuses</option>
                                        <option value="pending" <?php echo $this->input->get('status') == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="approved" <?php echo $this->input->get('status') == 'approved' ? 'selected' : ''; ?>>Approved</option>
                                        <option value="rejected" <?php echo $this->input->get('status') == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="sort" class="form-label">Sort By</label>
                                    <select class="form-select" id="sort" name="sort">
                                        <option value="newest" <?php echo ($this->input->get('sort') == 'newest' || $this->input->get('sort') == '') ? 'selected' : ''; ?>>Newest First</option>
                                        <option value="oldest" <?php echo $this->input->get('sort') == 'oldest' ? 'selected' : ''; ?>>Oldest First</option>
                                        <option value="name_asc" <?php echo $this->input->get('sort') == 'name_asc' ? 'selected' : ''; ?>>Business Name (A-Z)</option>
                                        <option value="name_desc" <?php echo $this->input->get('sort') == 'name_desc' ? 'selected' : ''; ?>>Business Name (Z-A)</option>
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-filter me-1"></i> Apply Filters
                                    </button>
                                    <a href="<?php echo base_url('admin/vendors'); ?>" class="btn btn-outline-secondary">
                                        <i class="fas fa-redo me-1"></i> Reset
                                    </a>
                                </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>

                    <?php if (empty($vendors)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> No vendors found matching your criteria.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Business Name</th>
                                        <th>Owner</th>
                                        <th>Contact</th>
                                        <th>Vehicles</th>
                                        <th>Applied On</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($vendors as $vendor): ?>
                                        <tr>
                                            <td><?php echo $vendor->id; ?></td>
                                            <td>
                                                <strong><?php echo $vendor->business_name; ?></strong>
                                                <?php if($vendor->is_verified): ?>
                                                    <span class="badge bg-info ms-1" data-bs-toggle="tooltip" title="Verified Business"><i class="fas fa-check-circle"></i></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php echo $vendor->user_name; ?>
                                                <div class="small text-muted"><?php echo $vendor->user_email; ?></div>
                                            </td>
                                            <td>
                                                <i class="fas fa-phone-alt me-1 text-muted"></i> <?php echo $vendor->phone; ?><br>
                                                <i class="fas fa-map-marker-alt me-1 text-muted"></i> <?php echo $vendor->city; ?>
                                            </td>
                                            <td>
                                                <?php if(isset($vendor->vehicle_count)): ?>
                                                    <span class="badge bg-primary"><?php echo $vendor->vehicle_count; ?> vehicles</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">0 vehicles</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($vendor->created_at)); ?></td>
                                            <td>
                                                <?php if ($vendor->status == 'pending'): ?>
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                <?php elseif ($vendor->status == 'approved'): ?>
                                                    <span class="badge bg-success">Approved</span>
                                                <?php elseif ($vendor->status == 'rejected'): ?>
                                                    <span class="badge bg-danger">Rejected</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo base_url('admin/view_vendor/' . $vendor->id); ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    <?php if ($vendor->status == 'pending'): ?>
                                                        <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#approveVendorModal<?php echo $vendor->id; ?>" title="Approve Vendor">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectVendorModal<?php echo $vendor->id; ?>" title="Reject Vendor">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    <?php elseif ($vendor->status == 'approved'): ?>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectVendorModal<?php echo $vendor->id; ?>" title="Reject Vendor">
                                                            <i class="fas fa-ban"></i>
                                                        </button>
                                                    <?php elseif ($vendor->status == 'rejected'): ?>
                                                        <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#approveVendorModal<?php echo $vendor->id; ?>" title="Approve Vendor">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <!-- Approve Modal -->
                                                <div class="modal fade" id="approveVendorModal<?php echo $vendor->id; ?>" tabindex="-1" aria-labelledby="approveVendorModalLabel<?php echo $vendor->id; ?>" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-success text-white">
                                                                <h5 class="modal-title" id="approveVendorModalLabel<?php echo $vendor->id; ?>">Approve Vendor</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <?php echo form_open('admin/approve_vendor/' . $vendor->id); ?>
                                                                <div class="modal-body">
                                                                    <p>Are you sure you want to approve <strong><?php echo $vendor->business_name; ?></strong> as a vendor?</p>
                                                                    <div class="mb-3">
                                                                        <label for="approval_note<?php echo $vendor->id; ?>" class="form-label">Approval Note (Optional)</label>
                                                                        <textarea class="form-control" id="approval_note<?php echo $vendor->id; ?>" name="approval_note" rows="3" placeholder="Add any notes or instructions for the vendor..."></textarea>
                                                                    </div>
                                                                    <div class="form-check mb-3">
                                                                        <input class="form-check-input" type="checkbox" id="is_verified<?php echo $vendor->id; ?>" name="is_verified" value="1" <?php echo $vendor->is_verified ? 'checked' : ''; ?>>
                                                                        <label class="form-check-label" for="is_verified<?php echo $vendor->id; ?>">
                                                                            Mark as verified business
                                                                        </label>
                                                                        <div class="form-text">Verified businesses have provided additional documentation to confirm their legitimacy.</div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-success">Approve Vendor</button>
                                                                </div>
                                                            <?php echo form_close(); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Reject Modal -->
                                                <div class="modal fade" id="rejectVendorModal<?php echo $vendor->id; ?>" tabindex="-1" aria-labelledby="rejectVendorModalLabel<?php echo $vendor->id; ?>" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-danger text-white">
                                                                <h5 class="modal-title" id="rejectVendorModalLabel<?php echo $vendor->id; ?>">Reject Vendor</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <?php echo form_open('admin/reject_vendor/' . $vendor->id); ?>
                                                                <div class="modal-body">
                                                                    <p>Are you sure you want to reject <strong><?php echo $vendor->business_name; ?></strong> as a vendor?</p>
                                                                    <div class="mb-3">
                                                                        <label for="rejection_reason<?php echo $vendor->id; ?>" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                                                                        <textarea class="form-control" id="rejection_reason<?php echo $vendor->id; ?>" name="rejection_reason" rows="3" placeholder="Provide a reason for rejection..." required></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-danger">Reject Vendor</button>
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
</div>