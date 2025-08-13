<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Add Review</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('booking/user_bookings'); ?>">My Bookings</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('booking/view/' . $booking->id); ?>">Booking #<?php echo $booking->id; ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Review</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Review Your Experience</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mb-4">
                        <h6>Booking Summary</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><strong>Booking ID:</strong> #<?php echo $booking->id; ?></li>
                                    <li class="list-group-item"><strong>Date:</strong> <?php echo date('M d, Y', strtotime($booking->created_at)); ?></li>
                                    <li class="list-group-item"><strong>Total Price:</strong> ₹<?php echo number_format($booking->total_price); ?></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <strong>Vehicles:</strong> 
                                        <?php 
                                            $vehicle_count = count($booking_items);
                                            echo $vehicle_count . ' ' . ($vehicle_count > 1 ? 'vehicles' : 'vehicle');
                                        ?>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Vendors:</strong>
                                        <?php 
                                            $vendors = array_unique(array_column($booking_items, 'vendor_name'));
                                            echo count($vendors) . ' ' . (count($vendors) > 1 ? 'vendors' : 'vendor');
                                        ?>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Status:</strong>
                                        <span class="badge bg-primary">COMPLETED</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <?php echo form_open('booking/add_review/' . $booking->id); ?>
                        <?php foreach ($vendors as $vendor_id => $vendor_name): ?>
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Review for <?php echo $vendor_name; ?></h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Vehicles from this vendor:</label>
                                        <ul class="list-group">
                                            <?php foreach ($booking_items as $item): ?>
                                                <?php if ($item->vendor_id == $vendor_id): ?>
                                                    <li class="list-group-item">
                                                        <?php echo $item->vehicle_title; ?> (<?php echo $item->vehicle_type; ?>)
                                                        <br>
                                                        <small class="text-muted">
                                                            <?php echo date('M d, Y', strtotime($item->date_from)); ?> to 
                                                            <?php echo date('M d, Y', strtotime($item->date_to)); ?>
                                                            (<?php echo $item->qty; ?> units)
                                                        </small>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="rating_<?php echo $vendor_id; ?>" class="form-label">Rating</label>
                                        <div class="rating-stars">
                                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rating[<?php echo $vendor_id; ?>]" id="rating_<?php echo $vendor_id; ?>_<?php echo $i; ?>" value="<?php echo $i; ?>" <?php echo ($i == 5) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="rating_<?php echo $vendor_id; ?>_<?php echo $i; ?>">
                                                        <?php for ($j = 1; $j <= 5; $j++): ?>
                                                            <i class="fas fa-star <?php echo ($j <= $i) ? 'text-warning' : 'text-muted'; ?>"></i>
                                                        <?php endfor; ?>
                                                    </label>
                                                </div>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="comment_<?php echo $vendor_id; ?>" class="form-label">Your Review</label>
                                        <textarea class="form-control" id="comment_<?php echo $vendor_id; ?>" name="comment[<?php echo $vendor_id; ?>]" rows="3" placeholder="Share your experience with this vendor and their vehicles..." required></textarea>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo base_url('booking/view/' . $booking->id); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Back to Booking
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i> Submit Reviews
                            </button>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .rating-stars .form-check-input {
        display: none;
    }
    
    .rating-stars .form-check-label {
        cursor: pointer;
        padding: 5px 10px;
        border-radius: 4px;
        transition: background-color 0.2s;
    }
    
    .rating-stars .form-check-label:hover {
        background-color: #f8f9fa;
    }
    
    .rating-stars .form-check-input:checked + .form-check-label {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Update star colors when rating changes
        const ratingInputs = document.querySelectorAll('.rating-stars input[type="radio"]');
        
        ratingInputs.forEach(input => {
            input.addEventListener('change', function() {
                const vendorId = this.name.match(/\[(\d+)\]/)[1];
                const rating = parseInt(this.value);
                
                // Update all labels for this vendor
                for (let i = 1; i <= 5; i++) {
                    const label = document.querySelector(`label[for="rating_${vendorId}_${i}"]`);
                    const stars = label.querySelectorAll('i.fas.fa-star');
                    
                    stars.forEach((star, index) => {
                        if (index < i) {
                            star.className = 'fas fa-star ' + (i <= rating ? 'text-warning' : 'text-muted');
                        } else {
                            star.className = 'fas fa-star text-muted';
                        }
                    });
                }
            });
        });
    });
</script>