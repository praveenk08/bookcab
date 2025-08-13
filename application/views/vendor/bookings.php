<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Manage Bookings</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('vendor/dashboard'); ?>">Vendor Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Manage Bookings</li>
                </ol>
            </nav>
        </div>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?php echo $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger">
            <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Your Bookings</h5>
                    <div>
                        <a href="<?php echo base_url('vendor/bookings?status=pending'); ?>" class="btn btn-sm btn-outline-warning me-2">
                            <i class="fas fa-clock me-1"></i> Pending
                        </a>
                        <a href="<?php echo base_url('vendor/bookings?status=confirmed'); ?>" class="btn btn-sm btn-outline-success me-2">
                            <i class="fas fa-check me-1"></i> Confirmed
                        </a>
                        <a href="<?php echo base_url('vendor/bookings?status=completed'); ?>" class="btn btn-sm btn-outline-primary me-2">
                            <i class="fas fa-flag-checkered me-1"></i> Completed
                        </a>
                        <a href="<?php echo base_url('vendor/bookings?status=cancelled'); ?>" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-times me-1"></i> Cancelled
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($bookings)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> No bookings found.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Customer</th>
                                        <th>Date Range</th>
                                        <th>Vehicles</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bookings as $booking): ?>
                                        <tr>
                                            <td>#<?php echo $booking->id; ?></td>
                                            <td>
                                                <?php echo $booking->user_name ?? 'Unknown'; ?>
                                                <div class="small text-muted"><?php echo $booking->user_email ?? ''; ?></div>
                                            </td>
                                            <td>
                                                <?php 
                                                if (isset($booking->from_date) && isset($booking->to_date)) {
                                                    echo date('M d', strtotime($booking->from_date)) . ' - ' . date('M d, Y', strtotime($booking->to_date));
                                                } else {
                                                    echo 'Date not specified';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary"><?php echo isset($booking->items) ? count($booking->items) : 0; ?> vehicles</span>
                                            </td>
                                            <td>
                                                $<?php echo number_format($booking->total_amount ?? 0, 2); ?>
                                            </td>
                                            <td>
                                                <?php 
                                                $status_class = '';
                                                switch ($booking->status) {
                                                    case 'pending':
                                                        $status_class = 'bg-warning';
                                                        break;
                                                    case 'confirmed':
                                                        $status_class = 'bg-success';
                                                        break;
                                                    case 'completed':
                                                        $status_class = 'bg-primary';
                                                        break;
                                                    case 'cancelled':
                                                        $status_class = 'bg-danger';
                                                        break;
                                                    default:
                                                        $status_class = 'bg-secondary';
                                                }
                                                ?>
                                                <span class="badge <?php echo $status_class; ?>">
                                                    <?php echo ucfirst($booking->status); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="<?php echo base_url('vendor/view_booking/' . $booking->id); ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php if ($booking->status === 'pending'): ?>
                                                    <a href="<?php echo base_url('vendor/update_booking_status/' . $booking->id . '/confirmed'); ?>" class="btn btn-sm btn-outline-success">
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                    <?php endif; ?>
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