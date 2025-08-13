<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1><?php echo isset($vehicle) ? 'Edit Vehicle' : 'Add New Vehicle'; ?></h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('vendor/dashboard'); ?>">Vendor Dashboard</a></li>
                    <?php if (isset($vehicle)): ?>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('vendor/manage_vehicles'); ?>">Manage Vehicles</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Vehicle</li>
                    <?php else: ?>
                        <li class="breadcrumb-item active" aria-current="page">Add New Vehicle</li>
                    <?php endif; ?>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><?php echo isset($vehicle) ? 'Edit Vehicle Details' : 'Vehicle Details'; ?></h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php echo form_open_multipart(isset($vehicle) ? 'vendor/edit_vehicle/'.$vehicle->id : 'vendor/add_vehicle'); ?>
                        <div class="mb-3">
                            <label for="title" class="form-label">Vehicle Title *</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo isset($vehicle) ? $vehicle->title : set_value('title'); ?>" required>
                            <?php echo form_error('title', '<div class="text-danger">', '</div>'); ?>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="type" class="form-label">Vehicle Type *</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="SUV" <?php echo (isset($vehicle) && $vehicle->type === 'SUV') ? 'selected' : (set_value('type') === 'SUV' ? 'selected' : ''); ?>>SUV</option>
                                    <option value="Sedan" <?php echo (isset($vehicle) && $vehicle->type === 'Sedan') ? 'selected' : (set_value('type') === 'Sedan' ? 'selected' : ''); ?>>Sedan</option>
                                    <option value="Bus" <?php echo (isset($vehicle) && $vehicle->type === 'Bus') ? 'selected' : (set_value('type') === 'Bus' ? 'selected' : ''); ?>>Bus</option>
                                    <option value="Bike" <?php echo (isset($vehicle) && $vehicle->type === 'Bike') ? 'selected' : (set_value('type') === 'Bike' ? 'selected' : ''); ?>>Bike</option>
                                    <option value="E-Rickshaw" <?php echo (isset($vehicle) && $vehicle->type === 'E-Rickshaw') ? 'selected' : (set_value('type') === 'E-Rickshaw' ? 'selected' : ''); ?>>E-Rickshaw</option>
                                    <option value="Luxury" <?php echo (isset($vehicle) && $vehicle->type === 'Luxury') ? 'selected' : (set_value('type') === 'Luxury' ? 'selected' : ''); ?>>Luxury</option>
                                </select>
                                <?php echo form_error('type', '<div class="text-danger">', '</div>'); ?>
                            </div>
                            <div class="col-md-6">
                                <label for="capacity" class="form-label">Capacity (Persons) *</label>
                                <input type="number" class="form-control" id="capacity" name="capacity" min="1" max="100" value="<?php echo isset($vehicle) ? $vehicle->capacity : set_value('capacity'); ?>" required>
                                <?php echo form_error('capacity', '<div class="text-danger">', '</div>'); ?>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="fixed_price" class="form-label">Fixed Price (₹ per day) *</label>
                                <input type="number" class="form-control" id="fixed_price" name="fixed_price" min="0" step="0.01" value="<?php echo isset($vehicle) ? $vehicle->fixed_price : set_value('fixed_price'); ?>" required>
                                <?php echo form_error('fixed_price', '<div class="text-danger">', '</div>'); ?>
                            </div>
                            <div class="col-md-6">
                                <label for="fuel_charge" class="form-label">Fuel Charge (₹ per day)</label>
                                <input type="number" class="form-control" id="fuel_charge" name="fuel_charge" min="0" step="0.01" value="<?php echo isset($vehicle) ? $vehicle->fuel_charge : set_value('fuel_charge', '0'); ?>">
                                <?php echo form_error('fuel_charge', '<div class="text-danger">', '</div>'); ?>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Vehicle Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Describe your vehicle, its features, condition, etc."><?php echo isset($vehicle) ? $vehicle->description : set_value('description'); ?></textarea>
                            <?php echo form_error('description', '<div class="text-danger">', '</div>'); ?>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Vehicle Images</label>
                            <?php if (isset($vehicle) && !empty($vehicle->images)): ?>
                                <div class="row mb-3">
                                    <?php 
                                    $images = json_decode($vehicle->images, true);
                                    foreach ($images as $index => $image): 
                                    ?>
                                        <div class="col-md-4 mb-3">
                                            <div class="card">
                                                <img src="<?php echo base_url('uploads/vehicles/' . $image); ?>" class="card-img-top" alt="Vehicle Image">
                                                <div class="card-body p-2 text-center">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="delete_images[]" value="<?php echo $image; ?>" id="delete_image_<?php echo $index; ?>">
                                                        <label class="form-check-label" for="delete_image_<?php echo $index; ?>">
                                                            Delete this image
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            
                            <input type="file" class="form-control" id="vehicle_images" name="vehicle_images[]" multiple <?php echo isset($vehicle) ? '' : 'required'; ?>>
                            <div class="form-text">Upload up to 5 images of your vehicle. (JPG, PNG formats only, max 2MB each)</div>
                            <?php if(isset($upload_error)): ?>
                                <div class="text-danger"><?php echo $upload_error; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Vehicle Availability</label>
                            <div class="alert alert-info">
                                <p class="mb-0">After saving this vehicle, you can manage its availability from the vehicle management page.</p>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Available Quantity *</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" min="1" max="100" value="<?php echo isset($vehicle) ? $vehicle->quantity : set_value('quantity', '1'); ?>" required>
                            <div class="form-text">How many units of this vehicle do you have available for booking?</div>
                            <?php echo form_error('quantity', '<div class="text-danger">', '</div>'); ?>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" <?php echo (isset($vehicle) && $vehicle->is_active == 1) ? 'checked' : (set_value('is_active') == 1 ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="is_active">Make this vehicle available for booking</label>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo isset($vehicle) ? base_url('vendor/manage_vehicles') : base_url('vendor/dashboard'); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> <?php echo isset($vehicle) ? 'Update Vehicle' : 'Add Vehicle'; ?>
                            </button>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>