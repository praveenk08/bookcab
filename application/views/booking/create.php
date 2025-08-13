<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Create Booking</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('vehicle/cart'); ?>">Cart</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create Booking</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Booking Details</h5>
                </div>
                <div class="card-body">
                    <?php echo form_open('booking/create', ['id' => 'booking-form']); ?>
                        <div class="mb-3">
                            <label for="booking_notes" class="form-label">Booking Notes (Optional)</label>
                            <textarea class="form-control" id="booking_notes" name="booking_notes" rows="3" placeholder="Any special requirements or notes for your booking"><?php echo set_value('booking_notes'); ?></textarea>
                        </div>
                        
                        <h5 class="mt-4 mb-3">Selected Vehicles</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Vehicle</th>
                                        <th>Type</th>
                                        <th>Dates</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cart_items as $item): ?>
                                        <tr>
                                            <td><?php echo $item['name']; ?></td>
                                            <td><?php echo $item['options']['type']; ?></td>
                                            <td>
                                                <?php echo date('M d, Y', strtotime($item['options']['date_from'])); ?> to 
                                                <?php echo date('M d, Y', strtotime($item['options']['date_to'])); ?>
                                                <br>
                                                <small class="text-muted"><?php echo $item['options']['days']; ?> day(s)</small>
                                            </td>
                                            <td><?php echo $item['qty']; ?></td>
                                            <td>
                                                ₹<?php echo number_format($item['price']); ?>
                                                <br>
                                                <small class="text-muted">
                                                    ₹<?php echo number_format($item['options']['fixed_price']); ?> + 
                                                    ₹<?php echo number_format($item['options']['fuel_charge']); ?> × 
                                                    <?php echo $item['options']['days']; ?> × 
                                                    <?php echo $item['qty']; ?>
                                                </small>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-end">Total:</th>
                                        <th>₹<?php echo number_format($total_price); ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                        <h5 class="mt-4 mb-3">Terms and Conditions</h5>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>
                            </label>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?php echo base_url('vehicle/cart'); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Back to Cart
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check-circle me-2"></i> Confirm Booking
                            </button>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Booking Summary</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Total Vehicles:</span>
                            <span><?php echo array_sum(array_column($cart_items, 'qty')); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Total Types:</span>
                            <span><?php echo count(array_unique(array_column(array_column($cart_items, 'options'), 'type'))); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Total Vendors:</span>
                            <span><?php echo count(array_unique(array_column(array_column($cart_items, 'options'), 'vendor_id'))); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Subtotal:</span>
                            <span>₹<?php echo number_format($total_price); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Tax (0%):</span>
                            <span>₹0</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                            <span>Grand Total:</span>
                            <span>₹<?php echo number_format($total_price); ?></span>
                        </li>
                    </ul>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> Your booking will be confirmed after admin approval.
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i> Cancellation policy: Free cancellation up to 24 hours before the booking start time.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms and Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5>1. Booking and Payment</h5>
                <p>All bookings are subject to availability and confirmation. A booking is confirmed once it has been approved by the admin and the vendor. Payment is due at the time of booking confirmation.</p>
                
                <h5>2. Cancellation Policy</h5>
                <p>Free cancellation is available up to 24 hours before the booking start time. Cancellations made within 24 hours of the booking start time may be subject to a cancellation fee.</p>
                
                <h5>3. Vehicle Usage</h5>
                <p>Vehicles must be used only for the purpose stated at the time of booking. Any damage to the vehicle during the rental period is the responsibility of the customer.</p>
                
                <h5>4. Driver Responsibility</h5>
                <p>If a driver is provided, they are responsible for the safe operation of the vehicle. Customers must not ask drivers to violate traffic laws or drive in unsafe conditions.</p>
                
                <h5>5. Liability</h5>
                <p>The platform acts as an intermediary between customers and vendors. While we verify vendors, we are not liable for any issues arising from the service provided by the vendor.</p>
                
                <h5>6. Privacy Policy</h5>
                <p>Customer information is collected and used in accordance with our Privacy Policy. We do not share customer information with third parties except as required to fulfill the booking.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">I Understand</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Validate terms checkbox
    document.getElementById('booking-form').addEventListener('submit', function(e) {
        if (!document.getElementById('terms').checked) {
            e.preventDefault();
            alert('You must agree to the Terms and Conditions to proceed.');
        }
    });
</script>