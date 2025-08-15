<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Edit Vehicle</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('vendor/dashboard'); ?>">Vendor Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('vendor/manage_vehicles'); ?>">Manage Vehicles</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Vehicle</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Edit Vehicle Details</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php echo form_open_multipart('vendor/edit_vehicle/'.$vehicle->id); ?>
                        <div class="mb-3">
                            <label for="title" class="form-label">Vehicle Title *</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo $vehicle->title; ?>" required>
                            <?php echo form_error('title', '<div class="text-danger">', '</div>'); ?>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="type" class="form-label">Vehicle Type *</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="SUV" <?php echo ($vehicle->type === 'SUV') ? 'selected' : ''; ?>>SUV</option>
                                    <option value="Sedan" <?php echo ($vehicle->type === 'Sedan') ? 'selected' : ''; ?>>Sedan</option>
                                    <option value="Bus" <?php echo ($vehicle->type === 'Bus') ? 'selected' : ''; ?>>Bus</option>
                                    <option value="Bike" <?php echo ($vehicle->type === 'Bike') ? 'selected' : ''; ?>>Bike</option>
                                    <option value="E-Rickshaw" <?php echo ($vehicle->type === 'E-Rickshaw') ? 'selected' : ''; ?>>E-Rickshaw</option>
                                    <option value="Luxury" <?php echo ($vehicle->type === 'Luxury') ? 'selected' : ''; ?>>Luxury</option>
                                </select>
                                <?php echo form_error('type', '<div class="text-danger">', '</div>'); ?>
                            </div>
                            <div class="col-md-6">
                                <label for="capacity" class="form-label">Capacity (Persons) *</label>
                                <input type="number" class="form-control" id="capacity" name="capacity" min="1" max="100" value="<?php echo $vehicle->capacity; ?>" required>
                                <?php echo form_error('capacity', '<div class="text-danger">', '</div>'); ?>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="fixed_price" class="form-label">Fixed Price (₹ per day) *</label>
                                <input type="number" class="form-control" id="fixed_price" name="fixed_price" min="0" step="0.01" value="<?php echo $vehicle->fixed_price; ?>" required>
                                <?php echo form_error('fixed_price', '<div class="text-danger">', '</div>'); ?>
                            </div>
                            <div class="col-md-6">
                                <label for="fuel_charge" class="form-label">Fuel Charge (₹ per day)</label>
                                <input type="number" class="form-control" id="fuel_charge" name="fuel_charge" min="0" step="0.01" value="<?php echo $vehicle->fuel_charge; ?>">
                                <?php echo form_error('fuel_charge', '<div class="text-danger">', '</div>'); ?>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Vehicle Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Describe your vehicle, its features, condition, etc."><?php echo $vehicle->description; ?></textarea>
                            <?php echo form_error('description', '<div class="text-danger">', '</div>'); ?>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Current Vehicle Images</label>
                            <?php if (!empty($vehicle->images)): ?>
                                <div class="row mb-3">
                                    <?php 
                                    $images = json_decode($vehicle->images, true);
                                    if(is_array($images)):
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
                                    <?php 
                                    endforeach;
                                    endif;
                                    ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">No images uploaded for this vehicle.</div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="images" class="form-label">Upload New Images</label>
                            <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                            <div class="form-text">You can select multiple images. (JPG, PNG formats only, max 2MB each)</div>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" <?php echo $vehicle->is_active ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="is_active">Vehicle is available for booking</label>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?php echo base_url('vendor/manage_vehicles'); ?>" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Vehicle</button>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>