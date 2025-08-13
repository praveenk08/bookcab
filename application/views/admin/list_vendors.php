<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-0 text-gray-800">Vendors</h1>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="<?php echo base_url('admin/dashboard'); ?>" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Back to Dashboard</a>
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

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">All Vendors</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Business Name</th>
                            <th>Owner</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($vendors)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No vendors found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($vendors as $vendor): ?>
                                <tr>
                                    <td><?php echo $vendor->id; ?></td>
                                    <td><?php echo $vendor->business_name; ?></td>
                                    <td><?php echo $vendor->owner_name; ?></td>
                                    <td><?php echo $vendor->email; ?></td>
                                    <td><?php echo $vendor->phone; ?></td>
                                    <td>
                                        <?php if ($vendor->status == 'approved'): ?>
                                            <span class="badge bg-success">Approved</span>
                                        <?php elseif ($vendor->status == 'pending'): ?>
                                            <span class="badge bg-warning">Pending</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Rejected</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo base_url('admin/view_vendor/' . $vendor->id); ?>" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                        <?php if ($vendor->status == 'pending'): ?>
                                            <a href="<?php echo base_url('admin/approve_vendor/' . $vendor->id); ?>" class="btn btn-sm btn-success" onclick="return confirm('Are you sure you want to approve this vendor?');"><i class="fas fa-check"></i></a>
                                            <a href="<?php echo base_url('admin/reject_vendor/' . $vendor->id); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to reject this vendor?');"><i class="fas fa-times"></i></a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                <?php echo $pagination; ?>
            </div>
        </div>
    </div>
</div>