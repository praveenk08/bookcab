<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>My Bookings</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">My Bookings</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Your Booking History</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($bookings)): ?>
                        <div class="alert alert-info">
                            <p class="mb-0">You don't have any bookings yet. <a href="<?php echo base_url('vehicle/search'); ?>">Start searching for vehicles</a> to make your first booking!</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>Date</th>
                                        <th>Vehicles</th>
                                        <th>Total Price</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bookings as $booking): ?>
                                        <tr>
                                            <td>#<?php echo $booking->id; ?></td>
                                            <td><?php echo date('M d, Y', strtotime($booking->created_at)); ?></td>
                                            <td>
                                                <?php 
                                                    $vehicle_count = count($booking->items);
                                                    echo $vehicle_count . ' ' . ($vehicle_count > 1 ? 'vehicles' : 'vehicle');
                                                    
                                                    // Show first two vehicle names
                                                    $shown_vehicles = array_slice($booking->items, 0, 2);
                                                    echo '<br><small class="text-muted">';
                                                    foreach ($shown_vehicles as $index => $item) {
                                                        echo $item->vehicle_title;
                                                        if ($index < count($shown_vehicles) - 1) {
                                                            echo ', ';
                                                        }
                                                    }
                                                    
                                                    // Show "and X more" if there are more than 2 vehicles
                                                    if ($vehicle_count > 2) {
                                                        echo ' and ' . ($vehicle_count - 2) . ' more';
                                                    }
                                                    echo '</small>';
                                                ?>
                                            </td>
                                            <td>₹<?php echo number_format($booking->total_price); ?></td>
                                            <td>
                                                <span class="badge bg-<?php 
                                                    echo $booking->status === 'PENDING' ? 'warning' : 
                                                        ($booking->status === 'CONFIRMED' ? 'success' : 
                                                            ($booking->status === 'ONGOING' ? 'info' : 
                                                                ($booking->status === 'COMPLETED' ? 'primary' : 'danger'))); 
                                                ?>">
                                                    <?php echo $booking->status; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="<?php echo base_url('booking/view/' . $booking->id); ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    
                                                    <?php if ($booking->status === 'PENDING' || $booking->status === 'CONFIRMED'): ?>
                                                        <a href="<?php echo base_url('booking/cancel/' . $booking->id); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to cancel this booking?')">
                                                            <i class="fas fa-times-circle"></i> Cancel
                                                        </a>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($booking->status === 'COMPLETED' && !$booking->has_review): ?>
                                                        <a href="<?php echo base_url('booking/add_review/' . $booking->id); ?>" class="btn btn-sm btn-outline-success">
                                                            <i class="fas fa-star"></i> Review
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <?php if (isset($pagination)): ?>
                            <div class="d-flex justify-content-center mt-4">
                                <?php echo $pagination; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <div class="mt-4">
                        <a href="<?php echo base_url('vehicle/search'); ?>" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i> Search for Vehicles
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>