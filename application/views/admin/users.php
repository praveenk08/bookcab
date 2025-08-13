<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>User Management</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>">Admin Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">User Management</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Users</h5>
                    <div>
                        <a href="<?php echo base_url('admin/users?role=admin'); ?>" class="btn btn-info btn-sm me-2">
                            <i class="fas fa-user-shield me-1"></i> Admins
                        </a>
                        <a href="<?php echo base_url('admin/users?role=vendor'); ?>" class="btn btn-success btn-sm me-2">
                            <i class="fas fa-store me-1"></i> Vendors
                        </a>
                        <a href="<?php echo base_url('admin/users?role=customer'); ?>" class="btn btn-warning btn-sm me-2">
                            <i class="fas fa-users me-1"></i> Customers
                        </a>
                        <a href="<?php echo base_url('admin/users'); ?>" class="btn btn-light btn-sm">
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
                            <?php echo form_open('admin/users', ['method' => 'GET', 'class' => 'row g-3']); ?>
                                <div class="col-md-3">
                                    <label for="search" class="form-label">Search</label>
                                    <input type="text" class="form-control" id="search" name="search" placeholder="Name, email, phone..." value="<?php echo $this->input->get('search'); ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="role" class="form-label">Role</label>
                                    <select class="form-select" id="role" name="role">
                                        <option value="" <?php echo $this->input->get('role') == '' ? 'selected' : ''; ?>>All Roles</option>
                                        <option value="admin" <?php echo $this->input->get('role') == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                        <option value="vendor" <?php echo $this->input->get('role') == 'vendor' ? 'selected' : ''; ?>>Vendor</option>
                                        <option value="customer" <?php echo $this->input->get('role') == 'customer' ? 'selected' : ''; ?>>Customer</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="" <?php echo $this->input->get('status') == '' ? 'selected' : ''; ?>>All Statuses</option>
                                        <option value="active" <?php echo $this->input->get('status') == 'active' ? 'selected' : ''; ?>>Active</option>
                                        <option value="inactive" <?php echo $this->input->get('status') == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-filter me-1"></i> Apply Filters
                                    </button>
                                    <a href="<?php echo base_url('admin/users'); ?>" class="btn btn-outline-secondary">
                                        <i class="fas fa-redo me-1"></i> Reset
                                    </a>
                                </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>

                    <?php if (empty($users)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> No users found matching your criteria.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Role</th>
                                        <th>Registered</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?php echo $user->id; ?></td>
                                            <td>
                                                <strong><?php echo $user->name; ?></strong>
                                            </td>
                                            <td><?php echo $user->email; ?></td>
                                            <td><?php echo $user->phone ?: 'N/A'; ?></td>
                                            <td>
                                                <?php if ($user->role == 'admin'): ?>
                                                    <span class="badge bg-info">Admin</span>
                                                <?php elseif ($user->role == 'vendor'): ?>
                                                    <span class="badge bg-success">Vendor</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning text-dark">Customer</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($user->created_at)); ?></td>
                                            <td>
                                                <?php if ($user->status == 'active'): ?>
                                                    <span class="badge bg-success">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo base_url('admin/view_user/' . $user->id); ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    <?php if ($user->id != $this->session->userdata('user_id')): ?> <!-- Cannot change own status -->
                                                        <?php if ($user->status == 'active'): ?>
                                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deactivateUserModal<?php echo $user->id; ?>" title="Deactivate User">
                                                                <i class="fas fa-ban"></i>
                                                            </button>
                                                        <?php else: ?>
                                                            <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#activateUserModal<?php echo $user->id; ?>" title="Activate User">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <!-- Deactivate Modal -->
                                                <div class="modal fade" id="deactivateUserModal<?php echo $user->id; ?>" tabindex="-1" aria-labelledby="deactivateUserModalLabel<?php echo $user->id; ?>" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-danger text-white">
                                                                <h5 class="modal-title" id="deactivateUserModalLabel<?php echo $user->id; ?>">Deactivate User</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <?php echo form_open('admin/update-user-status/' . $user->id); ?>
                                                                <input type="hidden" name="status" value="inactive">
                                                                <div class="modal-body">
                                                                    <p>Are you sure you want to deactivate <strong><?php echo $user->name; ?></strong>?</p>
                                                                    <div class="alert alert-warning">
                                                                        <i class="fas fa-exclamation-triangle me-2"></i> This user will no longer be able to log in or use the system.
                                                                        <?php if ($user->role == 'vendor'): ?>
                                                                            <br><strong>Note:</strong> This will also deactivate their vendor account and all associated vehicles.
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="deactivation_reason<?php echo $user->id; ?>" class="form-label">Reason for Deactivation</label>
                                                                        <textarea class="form-control" id="deactivation_reason<?php echo $user->id; ?>" name="reason" rows="3" required placeholder="Please provide a reason for deactivation..."></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-danger">Deactivate User</button>
                                                                </div>
                                                            <?php echo form_close(); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Activate Modal -->
                                                <div class="modal fade" id="activateUserModal<?php echo $user->id; ?>" tabindex="-1" aria-labelledby="activateUserModalLabel<?php echo $user->id; ?>" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-success text-white">
                                                                <h5 class="modal-title" id="activateUserModalLabel<?php echo $user->id; ?>">Activate User</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <?php echo form_open('admin/update-user-status/' . $user->id); ?>
                                                                <input type="hidden" name="status" value="active">
                                                                <div class="modal-body">
                                                                    <p>Are you sure you want to activate <strong><?php echo $user->name; ?></strong>?</p>
                                                                    <div class="alert alert-info">
                                                                        <i class="fas fa-info-circle me-2"></i> This user will be able to log in and use the system again.
                                                                        <?php if ($user->role == 'vendor'): ?>
                                                                            <br><strong>Note:</strong> If this user has a vendor account, you may need to also update the vendor status separately.
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-success">Activate User</button>
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
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            <?php echo $this->pagination->create_links(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>