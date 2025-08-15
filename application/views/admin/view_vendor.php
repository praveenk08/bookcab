<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Vendor Details</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>">Admin Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin/vendors'); ?>">Vendor Management</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Vendor Details</li>
                </ol>
            </nav>
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

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Vendor Information</h5>
                    <div>
                        <a href="<?php echo base_url('admin/vendors'); ?>" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back to Vendors
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="border-bottom pb-2 mb-3">Business Details</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th style="width: 30%">Business Name:</th>
                                    <td>
                                        <?php echo $vendor->business_name; ?>
                                        <?php if(isset($vendor->is_verified) && $vendor->is_verified): ?>
                                            <span class="badge bg-info ms-1" data-bs-toggle="tooltip" title="Verified Business"><i class="fas fa-check-circle"></i> Verified</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Registration No:</th>
                                    <td><?php echo $vendor->registration_no; ?></td>
                                </tr>
                                <tr>
                                    <th>Business Type:</th>
                                    <td><?php echo $vendor->business_type; ?></td>
                                </tr>
                                <tr>
                                    <th>Address:</th>
                                    <td><?php echo $vendor->address; ?></td>
                                </tr>
                                <tr>
                                    <th>City:</th>
                                    <td><?php echo $vendor->city; ?></td>
                                </tr>
                                <tr>
                                    <th>State:</th>
                                    <td><?php echo $vendor->state; ?></td>
                                </tr>
                                <tr>
                                    <th>Postal Code:</th>
                                    <td><?php echo $vendor->postal_code; ?></td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td><?php echo $vendor->phone; ?></td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td><?php echo $vendor->email; ?></td>
                                </tr>
                                <tr>
                                    <th>Website:</th>
                                    <td>
                                        <?php if(!empty($vendor->website)): ?>
                                            <a href="<?php echo $vendor->website; ?>" target="_blank"><?php echo $vendor->website; ?></a>
                                        <?php else: ?>
                                            <span class="text-muted">Not provided</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5 class="border-bottom pb-2 mb-3">Owner Details</h5>
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
                                    <th>Joined:</th>
                                    <td><?php echo date('M d, Y', strtotime($user->created_at)); ?></td>
                                </tr>
                            </table>
                            
                            <h5 class="border-bottom pb-2 mb-3 mt-4">Application Status</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th style="width: 30%">Status:</th>
                                    <td>
                                        <?php if ($vendor->status == 'pending'): ?>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        <?php elseif ($vendor->status == 'approved'): ?>
                                            <span class="badge bg-success">Approved</span>
                                        <?php elseif ($vendor->status == 'rejected'): ?>
                                            <span class="badge bg-danger">Rejected</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Applied On:</th>
                                    <td><?php echo date('M d, Y', strtotime($vendor->created_at)); ?></td>
                                </tr>
                                <?php if ($vendor->status == 'approved' && !empty($vendor->approved_at)): ?>
                                    <tr>
                                        <th>Approved On:</th>
                                        <td><?php echo date('M d, Y', strtotime($vendor->approved_at)); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Approval Note:</th>
                                        <td>
                                            <?php if(!empty($vendor->approval_note)): ?>
                                                <?php echo $vendor->approval_note; ?>
                                            <?php else: ?>
                                                <span class="text-muted">No notes provided</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <?php if ($vendor->status == 'rejected' && !empty($vendor->rejected_at)): ?>
                                    <tr>
                                        <th>Rejected On:</th>
                                        <td><?php echo date('M d, Y', strtotime($vendor->rejected_at)); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Rejection Reason:</th>
                                        <td>
                                            <?php if(!empty($vendor->rejection_reason)): ?>
                                                <?php echo $vendor->rejection_reason; ?>
                                            <?php else: ?>
                                                <span class="text-muted">No reason provided</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </table>
                            
                            <div class="d-flex justify-content-end mt-3">
                                <?php if ($vendor->status == 'pending'): ?>
                                    <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#approveVendorModal">
                                        <i class="fas fa-check me-1"></i> Approve
                                    </button>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectVendorModal">
                                        <i class="fas fa-times me-1"></i> Reject
                                    </button>
                                <?php elseif ($vendor->status == 'approved'): ?>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectVendorModal">
                                        <i class="fas fa-ban me-1"></i> Revoke Approval
                                    </button>
                                <?php elseif ($vendor->status == 'rejected'): ?>
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveVendorModal">
                                        <i class="fas fa-check me-1"></i> Approve
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
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Business Description</h5>
                </div>
                <div class="card-body">
                    <?php if(!empty($vendor->description)): ?>
                        <p><?php echo nl2br($vendor->description); ?></p>
                    <?php else: ?>
                        <p class="text-muted">No business description provided.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Documents</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Business Registration</h6>
                                </div>
                                <div class="card-body text-center">
                                    <?php if(!empty($vendor->registration_document)): ?>
                                        <div class="mb-3">
                                            <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                        </div>
                                        <a href="<?php echo base_url('uploads/vendors/' . $vendor->registration_document); ?>" class="btn btn-sm btn-primary" target="_blank">
                                            <i class="fas fa-eye me-1"></i> View Document
                                        </a>
                                    <?php else: ?>
                                        <div class="mb-3">
                                            <i class="fas fa-file-excel fa-3x text-muted"></i>
                                        </div>
                                        <p class="text-muted">No document uploaded</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">ID Proof</h6>
                                </div>
                                <div class="card-body text-center">
                                    <?php if(!empty($vendor->id_proof)): ?>
                                        <div class="mb-3">
                                            <i class="fas fa-id-card fa-3x text-primary"></i>
                                        </div>
                                        <a href="<?php echo base_url('uploads/vendors/' . $vendor->id_proof); ?>" class="btn btn-sm btn-primary" target="_blank">
                                            <i class="fas fa-eye me-1"></i> View Document
                                        </a>
                                    <?php else: ?>
                                        <div class="mb-3">
                                            <i class="fas fa-id-card fa-3x text-muted"></i>
                                        </div>
                                        <p class="text-muted">No document uploaded</p>
                                    <?php endif; ?>
                                </div>
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
                    <h5 class="mb-0">Vehicles (<?php echo count($vehicles); ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($vehicles)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> This vendor has not added any vehicles yet.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Capacity</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Status</th>
                                        <th>Added On</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($vehicles as $vehicle): ?>
                                        <tr>
                                            <td>
                                                <?php if (!empty($vehicle->images)): 
                                                    $images = json_decode($vehicle->images, true);
                                                    if (!empty($images[0])): ?>
                                                        <img src="<?php echo base_url('uploads/vehicles/' . $images[0]); ?>" alt="<?php echo $vehicle->title; ?>" class="img-thumbnail" style="width: 80px; height: 60px; object-fit: cover;">
                                                    <?php else: ?>
                                                        <div class="bg-light d-flex align-items-center justify-content-center" style="width: 80px; height: 60px;">
                                                            <i class="fas fa-car fa-2x text-muted"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <div class="bg-light d-flex align-items-center justify-content-center" style="width: 80px; height: 60px;">
                                                        <i class="fas fa-car fa-2x text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?php echo base_url('vehicle/view/' . $vehicle->id); ?>" target="_blank">
                                                    <?php echo $vehicle->title; ?>
                                                </a>
                                            </td>
                                            <td><?php echo $vehicle->type; ?></td>
                                            <td><?php echo $vehicle->capacity; ?> persons</td>
                                            <td>$<?php echo number_format($vehicle->price_per_day, 2); ?>/day</td>
                                            <td><?php echo $vehicle->quantity; ?></td>
                                            <td>
                                                <?php if ($vehicle->is_active): ?>
                                                    <span class="badge bg-success">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($vehicle->created_at)); ?></td>
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

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Drivers (<?php echo count($drivers); ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($drivers)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> This vendor has not added any drivers yet.
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
                                        <th>Added On</th>
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
                                            <td><?php echo $driver->license_no; ?></td>
                                            <td><?php echo $driver->experience; ?> years</td>
                                            <td>
                                                <?php if ($driver->is_active): ?>
                                                    <span class="badge bg-success">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($driver->created_at)); ?></td>
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

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Recent Bookings (<?php echo count($bookings); ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($bookings)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> This vendor has no bookings yet.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>Customer</th>
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
                                                <a href="<?php echo base_url('admin/view_booking/' . $booking->id); ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
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

    <!-- Approve Modal -->
    <div class="modal fade" id="approveVendorModal" tabindex="-1" aria-labelledby="approveVendorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="approveVendorModalLabel">Approve Vendor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <?php echo form_open('admin/approve_vendor/' . $vendor->id); ?>
                    <div class="modal-body">
                        <p>Are you sure you want to approve <strong><?php echo $vendor->business_name; ?></strong> as a vendor?</p>
                        <div class="mb-3">
                            <label for="approval_note" class="form-label">Approval Note (Optional)</label>
                            <textarea class="form-control" id="approval_note" name="approval_note" rows="3" placeholder="Add any notes or instructions for the vendor..."></textarea>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="is_verified" name="is_verified" value="1" <?php echo $vendor->is_verified ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="is_verified">
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
    <div class="modal fade" id="rejectVendorModal" tabindex="-1" aria-labelledby="rejectVendorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="rejectVendorModalLabel">Reject Vendor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <?php echo form_open('admin/reject_vendor/' . $vendor->id); ?>
                    <div class="modal-body">
                        <p>Are you sure you want to reject <strong><?php echo $vendor->business_name; ?></strong> as a vendor?</p>
                        <div class="mb-3">
                            <label for="rejection_reason" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" placeholder="Provide a reason for rejection..." required></textarea>
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
</div>