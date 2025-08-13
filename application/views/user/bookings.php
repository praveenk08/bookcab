<div class="container mt-4">
    <h1>My Bookings</h1>
    
    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?= $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>
    
    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger">
            <?= $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">All Bookings</h5>
        </div>
        <div class="card-body">
            <?php if(empty($bookings)): ?>
                <p class="text-muted">You don't have any bookings yet.</p>
                <a href="<?= base_url('vehicles'); ?>" class="btn btn-primary">Book a Vehicle</a>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Vehicle</th>
                                <th>Vendor</th>
                                <th>Dates</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($bookings as $booking): ?>
                                <tr>
                                    <td>#<?= $booking->id; ?></td>
                                    <td><?= $booking->vehicle_name; ?></td>
                                    <td><?= $booking->vendor_name; ?></td>
                                    <td>
                                        <?= date('M d, Y', strtotime($booking->start_date)); ?> - 
                                        <?= date('M d, Y', strtotime($booking->end_date)); ?>
                                    </td>
                                    <td>$<?= number_format($booking->total_amount, 2); ?></td>
                                    <td>
                                        <?php if($booking->status == 'pending'): ?>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        <?php elseif($booking->status == 'confirmed'): ?>
                                            <span class="badge bg-success">Confirmed</span>
                                        <?php elseif($booking->status == 'cancelled'): ?>
                                            <span class="badge bg-danger">Cancelled</span>
                                        <?php elseif($booking->status == 'completed'): ?>
                                            <span class="badge bg-info">Completed</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('booking/view/'.$booking->id); ?>" class="btn btn-sm btn-info">View</a>
                                        <?php if($booking->status == 'pending'): ?>
                                            <a href="<?= base_url('booking/cancel/'.$booking->id); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to cancel this booking?');">Cancel</a>
                                        <?php endif; ?>
                                        <?php if($booking->status == 'confirmed' && !$booking->payment_status): ?>
                                            <a href="<?= base_url('booking/payment/'.$booking->id); ?>" class="btn btn-sm btn-success">Pay Now</a>
                                        <?php endif; ?>
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