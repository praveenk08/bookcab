<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-0 text-gray-800">Bookings</h1>
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
            <h6 class="m-0 font-weight-bold text-primary">All Bookings</h6>
            <div>
                <form action="<?php echo base_url('admin/list_bookings'); ?>" method="get" class="d-flex">
                    <select name="status" class="form-select me-2">
                        <option value="">All Statuses</option>
                        <option value="pending" <?php echo $this->input->get('status') == 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="confirmed" <?php echo $this->input->get('status') == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                        <option value="completed" <?php echo $this->input->get('status') == 'completed' ? 'selected' : ''; ?>>Completed</option>
                        <option value="cancelled" <?php echo $this->input->get('status') == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
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
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($bookings)): ?>
                            <tr>
                                <td colspan="6" class="text-center">No bookings found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($bookings as $booking): ?>
                                <tr>
                                    <td><?php echo $booking->id; ?></td>
                                    <td><?php echo $booking->user_name; ?></td>
                                    <td><?php echo date('M d, Y', strtotime($booking->created_at)); ?></td>
                                    <td>₹<?php echo number_format($booking->total_amount, 2); ?></td>
                                    <td>
                                        <?php if ($booking->status == 'pending'): ?>
                                            <span class="badge bg-warning">Pending</span>
                                        <?php elseif ($booking->status == 'confirmed'): ?>
                                            <span class="badge bg-primary">Confirmed</span>
                                        <?php elseif ($booking->status == 'completed'): ?>
                                            <span class="badge bg-success">Completed</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Cancelled</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo base_url('admin/view_booking/' . $booking->id); ?>" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
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