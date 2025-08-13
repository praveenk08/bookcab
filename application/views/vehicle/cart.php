<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Your Booking Cart</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('vehicle/search'); ?>">Search</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Cart</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Selected Vehicles</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($cart_items)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> Your cart is empty. <a href="<?php echo base_url('vehicle/search'); ?>">Search for vehicles</a> to add to your cart.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Vehicle</th>
                                        <th>Type</th>
                                        <th>Dates</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cart_items as $index => $item): ?>
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
                                            <td>
                                                <a href="<?php echo base_url('vehicle/remove_from_cart/' . $index); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to remove this item?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-end">Total:</th>
                                        <th>₹<?php echo number_format($total_price); ?></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <a href="<?php echo base_url('vehicle/clear_cart'); ?>" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to clear your cart?')">
                                <i class="fas fa-trash"></i> Clear Cart
                            </a>
                            <a href="<?php echo base_url('vehicle/search'); ?>" class="btn btn-outline-primary">
                                <i class="fas fa-search"></i> Add More Vehicles
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Booking Summary</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($cart_items)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> Add vehicles to your cart to see the booking summary.
                        </div>
                    <?php else: ?>
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
                        
                        <?php if ($this->session->userdata('logged_in')): ?>
                            <div class="d-grid">
                                <a href="<?php echo base_url('booking/create'); ?>" class="btn btn-success">
                                    <i class="fas fa-check-circle me-2"></i> Proceed to Booking
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i> Please <a href="<?php echo base_url('auth/login'); ?>">login</a> to proceed with your booking.
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>