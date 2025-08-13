<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-0 text-gray-800">Users</h1>
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
            <h6 class="m-0 font-weight-bold text-primary">All Users</h6>
            <div>
                <form action="<?php echo base_url('admin/list_users'); ?>" method="get" class="d-flex">
                    <select name="role" class="form-select me-2">
                        <option value="">All Roles</option>
                        <option value="admin" <?php echo $this->input->get('role') == 'admin' ? 'selected' : ''; ?>>Admin</option>
                        <option value="user" <?php echo $this->input->get('role') == 'user' ? 'selected' : ''; ?>>User</option>
                        <option value="vendor" <?php echo $this->input->get('role') == 'vendor' ? 'selected' : ''; ?>>Vendor</option>
                    </select>
                    <select name="status" class="form-select me-2">
                        <option value="">All Statuses</option>
                        <option value="active" <?php echo $this->input->get('status') == 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo $this->input->get('status') == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No users found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo $user->id; ?></td>
                                    <td><?php echo $user->name; ?></td>
                                    <td><?php echo $user->email; ?></td>
                                    <td><?php echo $user->phone; ?></td>
                                    <td>
                                        <?php if ($user->role == 'admin'): ?>
                                            <span class="badge bg-danger">Admin</span>
                                        <?php elseif ($user->role == 'vendor'): ?>
                                            <span class="badge bg-primary">Vendor</span>
                                        <?php else: ?>
                                            <span class="badge bg-info">User</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($user->status == 'active'): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo base_url('admin/view_user/' . $user->id); ?>" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
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