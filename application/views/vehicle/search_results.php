<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Search Results</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('vehicle/search'); ?>">Search</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Results</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Search Filters</h5>
                </div>
                <div class="card-body">
                    <?php echo form_open('vehicle/search', ['method' => 'post', 'id' => 'search-form']); ?>
                        <div class="mb-3">
                            <label for="date_from" class="form-label">From Date</label>
                            <input type="date" class="form-control datepicker" id="date_from" name="date_from" value="<?php echo set_value('date_from', $criteria['date_from']); ?>" min="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="date_to" class="form-label">To Date</label>
                            <input type="date" class="form-control datepicker" id="date_to" name="date_to" value="<?php echo set_value('date_to', $criteria['date_to']); ?>" min="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Vehicle Type</label>
                            <select class="form-select" id="type" name="type">
                                <option value="">All Types</option>
                                <option value="SUV" <?php echo set_select('type', 'SUV', ($criteria['type'] === 'SUV')); ?>>SUV</option>
                                <option value="Sedan" <?php echo set_select('type', 'Sedan', ($criteria['type'] === 'Sedan')); ?>>Sedan</option>
                                <option value="Bus" <?php echo set_select('type', 'Bus', ($criteria['type'] === 'Bus')); ?>>Bus</option>
                                <option value="Bike" <?php echo set_select('type', 'Bike', ($criteria['type'] === 'Bike')); ?>>Bike</option>
                                <option value="E-Rickshaw" <?php echo set_select('type', 'E-Rickshaw', ($criteria['type'] === 'E-Rickshaw')); ?>>E-Rickshaw</option>
                                <option value="Luxury" <?php echo set_select('type', 'Luxury', ($criteria['type'] === 'Luxury')); ?>>Luxury</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="capacity" class="form-label">Minimum Capacity</label>
                            <input type="number" class="form-control" id="capacity" name="capacity" value="<?php echo set_value('capacity', $criteria['capacity']); ?>" min="1">
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity Needed</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo set_value('quantity', $criteria['quantity']); ?>" min="1" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update Search</button>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Available Vehicles</h5>
                    <span class="badge bg-light text-dark"><?php echo count($vehicles); ?> results</span>
                </div>
                <div class="card-body">
                    <?php if (empty($vehicles)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> No vehicles found matching your criteria. Please try different search parameters.
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($vehicles as $vehicle): ?>
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 vehicle-card">
                                        <img src="<?php echo !empty($vehicle->images) ? json_decode($vehicle->images)[0] : 'https://via.placeholder.com/300x200?text=No+Image'; ?>" class="card-img-top" alt="<?php echo $vehicle->title; ?>">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo $vehicle->title; ?></h5>
                                            <p class="card-text mb-1"><i class="fas fa-car me-2 text-primary"></i> <?php echo $vehicle->type; ?></p>
                                            <p class="card-text mb-1"><i class="fas fa-users me-2 text-primary"></i> Capacity: <?php echo $vehicle->capacity; ?> persons</p>
                                            <p class="card-text mb-1"><i class="fas fa-tag me-2 text-primary"></i> Price: ₹<?php echo number_format($vehicle->fixed_price); ?> + ₹<?php echo number_format($vehicle->fuel_charge); ?> (fuel)</p>
                                            <p class="card-text mb-3"><i class="fas fa-store me-2 text-primary"></i> Vendor: <?php echo $vehicle->shop_name; ?></p>
                                            
                                            <div class="d-grid gap-2">
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#vehicleModal<?php echo $vehicle->id; ?>">
                                                    View Details
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Vehicle Modal -->
                                <div class="modal fade" id="vehicleModal<?php echo $vehicle->id; ?>" tabindex="-1" aria-labelledby="vehicleModalLabel<?php echo $vehicle->id; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="vehicleModalLabel<?php echo $vehicle->id; ?>"><?php echo $vehicle->title; ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <img src="<?php echo !empty($vehicle->images) ? json_decode($vehicle->images)[0] : 'https://via.placeholder.com/300x200?text=No+Image'; ?>" class="img-fluid rounded mb-3" alt="<?php echo $vehicle->title; ?>">
                                                        <?php if (!empty($vehicle->images)): ?>
                                                            <div class="row">
                                                                <?php foreach (array_slice(json_decode($vehicle->images), 1, 3) as $image): ?>
                                                                    <div class="col-4">
                                                                        <img src="<?php echo $image; ?>" class="img-fluid rounded" alt="<?php echo $vehicle->title; ?>">
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h5>Vehicle Details</h5>
                                                        <ul class="list-group list-group-flush mb-3">
                                                            <li class="list-group-item"><i class="fas fa-car me-2 text-primary"></i> Type: <?php echo $vehicle->type; ?></li>
                                                            <li class="list-group-item"><i class="fas fa-users me-2 text-primary"></i> Capacity: <?php echo $vehicle->capacity; ?> persons</li>
                                                            <li class="list-group-item"><i class="fas fa-tag me-2 text-primary"></i> Fixed Price: ₹<?php echo number_format($vehicle->fixed_price); ?> per day</li>
                                                            <li class="list-group-item"><i class="fas fa-gas-pump me-2 text-primary"></i> Fuel Charge: ₹<?php echo number_format($vehicle->fuel_charge); ?> per day</li>
                                                            <li class="list-group-item"><i class="fas fa-store me-2 text-primary"></i> Vendor: <?php echo $vehicle->shop_name; ?></li>
                                                        </ul>
                                                        
                                                        <h5>Booking Details</h5>
                                                        <?php echo form_open('vehicle/add_to_cart', ['id' => 'booking-form-' . $vehicle->id, 'class' => 'booking-form']); ?>
                                                            <input type="hidden" name="vehicle_id" value="<?php echo $vehicle->id; ?>">
                                                            <div class="mb-3">
                                                                <label for="date_from_<?php echo $vehicle->id; ?>" class="form-label">From Date</label>
                                                                <input type="date" class="form-control" id="date_from_<?php echo $vehicle->id; ?>" name="date_from" value="<?php echo $criteria['date_from']; ?>" min="<?php echo date('Y-m-d'); ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="date_to_<?php echo $vehicle->id; ?>" class="form-label">To Date</label>
                                                                <input type="date" class="form-control" id="date_to_<?php echo $vehicle->id; ?>" name="date_to" value="<?php echo $criteria['date_to']; ?>" min="<?php echo date('Y-m-d'); ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="quantity_<?php echo $vehicle->id; ?>" class="form-label">Quantity</label>
                                                                <input type="number" class="form-control" id="quantity_<?php echo $vehicle->id; ?>" name="quantity" value="<?php echo $criteria['quantity'] ?: 1; ?>" min="1" max="<?php echo $vehicle->available_quantity; ?>" required>
                                                                <small class="text-muted">Available: <?php echo $vehicle->available_quantity; ?></small>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Total Price</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text">₹</span>
                                                                    <input type="text" class="form-control" id="total_price_<?php echo $vehicle->id; ?>" readonly>
                                                                </div>
                                                                <small class="text-muted">Price calculation: (Fixed Price + Fuel Charge) × Days × Quantity</small>
                                                            </div>
                                                            <div class="d-grid">
                                                                <button type="submit" class="btn btn-primary">Add to Cart</button>
                                                            </div>
                                                        <?php echo form_close(); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <script>
                                    // Calculate total price for this vehicle
                                    function calculatePrice<?php echo $vehicle->id; ?>() {
                                        var dateFrom = new Date(document.getElementById('date_from_<?php echo $vehicle->id; ?>').value);
                                        var dateTo = new Date(document.getElementById('date_to_<?php echo $vehicle->id; ?>').value);
                                        var quantity = parseInt(document.getElementById('quantity_<?php echo $vehicle->id; ?>').value);
                                        
                                        if (dateFrom && dateTo && !isNaN(quantity)) {
                                            var days = Math.ceil((dateTo - dateFrom) / (1000 * 60 * 60 * 24));
                                            if (days < 1) days = 1;
                                            
                                            var fixedPrice = <?php echo $vehicle->fixed_price; ?>;
                                            var fuelCharge = <?php echo $vehicle->fuel_charge; ?>;
                                            var totalPrice = (fixedPrice + fuelCharge) * days * quantity;
                                            
                                            document.getElementById('total_price_<?php echo $vehicle->id; ?>').value = totalPrice.toLocaleString('en-IN');
                                        }
                                    }
                                    
                                    // Initialize calculation
                                    document.addEventListener('DOMContentLoaded', function() {
                                        calculatePrice<?php echo $vehicle->id; ?>();
                                        
                                        // Add event listeners
                                        document.getElementById('date_from_<?php echo $vehicle->id; ?>').addEventListener('change', calculatePrice<?php echo $vehicle->id; ?>);
                                        document.getElementById('date_to_<?php echo $vehicle->id; ?>').addEventListener('change', calculatePrice<?php echo $vehicle->id; ?>);
                                        document.getElementById('quantity_<?php echo $vehicle->id; ?>').addEventListener('change', calculatePrice<?php echo $vehicle->id; ?>);
                                        
                                        // Validate date range
                                        document.getElementById('booking-form-<?php echo $vehicle->id; ?>').addEventListener('submit', function(e) {
                                            var dateFrom = new Date(document.getElementById('date_from_<?php echo $vehicle->id; ?>').value);
                                            var dateTo = new Date(document.getElementById('date_to_<?php echo $vehicle->id; ?>').value);
                                            
                                            if (dateTo < dateFrom) {
                                                e.preventDefault();
                                                alert('To Date must be after From Date');
                                            }
                                        });
                                    });
                                </script>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>