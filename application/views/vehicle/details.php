<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><?php echo $vehicle->title; ?></h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?php if (!empty($vehicle->images)): ?>
                                <div id="vehicleCarousel" class="carousel slide" data-ride="carousel">
                                    <div class="carousel-inner">
                                        <?php foreach(explode(',', $vehicle->images) as $index => $image): ?>
                                            <div class="carousel-item <?php echo ($index === 0) ? 'active' : ''; ?>">
                                                <img src="<?php echo base_url('assets/images/vehicles/' . $image); ?>" class="d-block w-100" alt="<?php echo $vehicle->title; ?>">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <a class="carousel-control-prev" href="#vehicleCarousel" role="button" data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#vehicleCarousel" role="button" data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                            <?php else: ?>
                                <img src="<?php echo base_url('assets/images/vehicles/no-image.jpg'); ?>" class="img-fluid" alt="No Image Available">
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <h5>Vehicle Details</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Type</th>
                                    <td><?php echo $vehicle->type; ?></td>
                                </tr>
                                <tr>
                                    <th>Capacity</th>
                                    <td><?php echo $vehicle->capacity; ?> persons</td>
                                </tr>
                                <tr>
                                    <th>Fuel Type</th>
                                    <td><?php echo $vehicle->fuel_type; ?></td>
                                </tr>
                                <tr>
                                    <th>Transmission</th>
                                    <td><?php echo $vehicle->transmission; ?></td>
                                </tr>
                                <tr>
                                    <th>Price Per Day</th>
                                    <td>₹<?php echo number_format($vehicle->price_per_day, 2); ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <?php if ($vehicle->status == 'available'): ?>
                                            <span class="badge badge-success">Available</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Not Available</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h5>Description</h5>
                        <p><?php echo $vehicle->description; ?></p>
                    </div>
                    
                    <div class="mt-4">
                        <h5>Features</h5>
                        <ul class="list-group list-group-flush">
                            <?php foreach(explode(',', $vehicle->features) as $feature): ?>
                                <li class="list-group-item"><?php echo trim($feature); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Reviews Section -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Customer Reviews</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($reviews)): ?>
                        <?php foreach($reviews as $review): ?>
                            <div class="media mb-3 pb-3 border-bottom">
                                <div class="media-body">
                                    <h5 class="mt-0"><?php echo $review->user_name; ?></h5>
                                    <div class="mb-2">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <?php if($i <= $review->rating): ?>
                                                <i class="fas fa-star text-warning"></i>
                                            <?php else: ?>
                                                <i class="far fa-star text-warning"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                        <small class="text-muted ml-2"><?php echo date('M d, Y', strtotime($review->created_at)); ?></small>
                                    </div>
                                    <p><?php echo $review->comment; ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center">No reviews yet. Be the first to review this vehicle!</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Booking Form -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Book This Vehicle</h5>
                </div>
                <div class="card-body">
                    <?php echo form_open('booking/add_to_cart'); ?>
                        <input type="hidden" name="vehicle_id" value="<?php echo $vehicle->id; ?>">
                        
                        <div class="form-group">
                            <label for="date_from">From Date</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="date_to">To Date</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="qty">Quantity</label>
                            <input type="number" class="form-control" id="qty" name="qty" value="1" min="1" max="<?php echo $vehicle->available_qty; ?>">
                            <small class="text-muted">Available: <?php echo $vehicle->available_qty; ?></small>
                        </div>
                        
                        <div class="form-group">
                            <label for="with_driver">With Driver?</label>
                            <select class="form-control" id="with_driver" name="with_driver">
                                <option value="0">No</option>
                                <option value="1">Yes (Additional ₹<?php echo number_format($vehicle->driver_price_per_day, 2); ?> per day)</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
            
            <!-- Vendor Info -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Vendor Information</h5>
                </div>
                <div class="card-body">
                    <h5><?php echo $vendor->shop_name; ?></h5>
                    <p><i class="fas fa-map-marker-alt"></i> <?php echo $vendor->address; ?></p>
                    <p><i class="fas fa-phone"></i> <?php echo $vendor->phone; ?></p>
                    <p><i class="fas fa-envelope"></i> <?php echo $vendor->email; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Date validation
    $('#date_from').change(function() {
        var fromDate = $(this).val();
        $('#date_to').attr('min', fromDate);
        
        // If to date is before from date, reset it
        if ($('#date_to').val() < fromDate) {
            $('#date_to').val('');
        }
    });
    
    // Calculate total price
    function calculateTotal() {
        var fromDate = new Date($('#date_from').val());
        var toDate = new Date($('#date_to').val());
        var qty = parseInt($('#qty').val());
        var withDriver = $('#with_driver').val() == '1';
        
        if (fromDate && toDate && !isNaN(qty)) {
            var days = Math.ceil((toDate - fromDate) / (1000 * 60 * 60 * 24));
            if (days > 0) {
                var pricePerDay = <?php echo $vehicle->price_per_day; ?>;
                var driverPricePerDay = <?php echo $vehicle->driver_price_per_day; ?>;
                
                var totalPrice = pricePerDay * days * qty;
                if (withDriver) {
                    totalPrice += driverPricePerDay * days * qty;
                }
                
                $('#total_price').text('₹' + totalPrice.toFixed(2));
            }
        }
    }
    
    $('#date_from, #date_to, #qty, #with_driver').change(calculateTotal);
});
</script>