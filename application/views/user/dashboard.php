<div class="container mt-4">
    <h1>User Dashboard</h1>
    
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
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Bookings</h5>
                    <a href="<?= base_url('bookings'); ?>" class="btn btn-sm btn-primary">View All</a>
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
                                        <th>Dates</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($bookings as $booking): ?>
                                        <tr>
                                            <td>#<?= $booking->id; ?></td>
                                            <td><?= $booking->vehicle_name; ?></td>
                                            <td>
                                                <?= date('M d, Y', strtotime($booking->start_date)); ?> - 
                                                <?= date('M d, Y', strtotime($booking->end_date)); ?>
                                            </td>
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
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= base_url('vehicles'); ?>" class="btn btn-primary">Book a Vehicle</a>
                        <a href="<?= base_url('bookings'); ?>" class="btn btn-outline-primary">View My Bookings</a>
                        <a href="<?= base_url('user/profile'); ?>" class="btn btn-outline-primary">Update Profile</a>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recommended Vehicles</h5>
                </div>
                <div class="card-body">
                    <?php if(empty($recommended_vehicles)): ?>
                        <p class="text-muted">No recommended vehicles available.</p>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach($recommended_vehicles as $vehicle): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <img src="<?= base_url('uploads/vehicles/'.$vehicle->image); ?>" class="card-img-top" alt="<?= $vehicle->name; ?>">
                                        <div class="card-body">
                                            <h6 class="card-title"><?= $vehicle->name; ?></h6>
                                            <p class="card-text text-primary fw-bold">$<?= number_format($vehicle->price_per_day, 2); ?>/day</p>
                                            <a href="<?= base_url('vehicle/details/'.$vehicle->id); ?>" class="btn btn-sm btn-outline-primary">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>