<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1><?php echo isset($driver) ? 'Edit Driver' : 'Add New Driver'; ?></h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('vendor/dashboard'); ?>">Vendor Dashboard</a></li>
                    <?php if (isset($driver)): ?>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('vendor/manage_drivers'); ?>">Manage Drivers</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Driver</li>
                    <?php else: ?>
                        <li class="breadcrumb-item active" aria-current="page">Add New Driver</li>
                    <?php endif; ?>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><?php echo isset($driver) ? 'Edit Driver Details' : 'Driver Details'; ?></h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php echo form_open_multipart(isset($driver) ? 'vendor/edit_driver/'.$driver->id : 'vendor/add_driver'); ?>
                        <div class="mb-3">
                            <label for="name" class="form-label">Driver Name *</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($driver) ? $driver->name : set_value('name'); ?>" required>
                            <?php echo form_error('name', '<div class="text-danger">', '</div>'); ?>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo isset($driver) ? $driver->phone : set_value('phone'); ?>" required>
                                <?php echo form_error('phone', '<div class="text-danger">', '</div>'); ?>
                            </div>
                            <div class="col-md-6">
                                <label for="license_no" class="form-label">License Number *</label>
                                <input type="text" class="form-control" id="license_no" name="license_no" value="<?php echo isset($driver) ? $driver->license_no : set_value('license_no'); ?>" required>
                                <?php echo form_error('license_no', '<div class="text-danger">', '</div>'); ?>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3"><?php echo isset($driver) ? $driver->address : set_value('address'); ?></textarea>
                            <?php echo form_error('address', '<div class="text-danger">', '</div>'); ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="license_document" class="form-label"><?php echo isset($driver) ? 'Update License Document (optional)' : 'License Document *'; ?></label>
                            <input type="file" class="form-control" id="license_document" name="license_document" <?php echo isset($driver) ? '' : 'required'; ?>>
                            <div class="form-text">Upload a scanned copy of the driver's license. (PDF, JPG, PNG formats only, max 2MB)</div>
                            <?php if(isset($upload_error)): ?>
                                <div class="text-danger"><?php echo $upload_error; ?></div>
                            <?php endif; ?>
                            
                            <?php if (isset($driver) && !empty($driver->license_document)): ?>
                                <div class="mt-2">
                                    <p class="mb-1">Current document:</p>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-file-alt me-2 text-primary"></i>
                                        <span><?php echo $driver->license_document; ?></span>
                                        <a href="<?php echo base_url('uploads/drivers/' . $driver->license_document); ?>" class="btn btn-sm btn-outline-primary ms-2" target="_blank">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="photo" class="form-label"><?php echo isset($driver) ? 'Update Photo (optional)' : 'Driver Photo *'; ?></label>
                            <input type="file" class="form-control" id="photo" name="photo" <?php echo isset($driver) ? '' : 'required'; ?>>
                            <div class="form-text">Upload a photo of the driver. (JPG, PNG formats only, max 2MB)</div>
                            
                            <?php if (isset($driver) && !empty($driver->photo)): ?>
                                <div class="mt-2 d-flex align-items-center">
                                    <p class="mb-0 me-3">Current photo:</p>
                                    <img src="<?php echo base_url('uploads/drivers/' . $driver->photo); ?>" alt="Driver Photo" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="experience" class="form-label">Experience (Years)</label>
                            <input type="number" class="form-control" id="experience" name="experience" min="0" max="50" value="<?php echo isset($driver) ? $driver->experience : set_value('experience', '0'); ?>">
                            <?php echo form_error('experience', '<div class="text-danger">', '</div>'); ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Additional Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any additional information about the driver..."><?php echo isset($driver) ? $driver->notes : set_value('notes'); ?></textarea>
                            <?php echo form_error('notes', '<div class="text-danger">', '</div>'); ?>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" <?php echo (isset($driver) && $driver->is_active == 1) ? 'checked' : (set_value('is_active') == 1 ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="is_active">Driver is available for assignments</label>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo isset($driver) ? base_url('vendor/manage_drivers') : base_url('vendor/dashboard'); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> <?php echo isset($driver) ? 'Update Driver' : 'Add Driver'; ?>
                            </button>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>