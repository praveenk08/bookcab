<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Edit Driver</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('vendor/dashboard'); ?>">Vendor Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('vendor/manage_drivers'); ?>">Manage Drivers</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Driver</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Edit Driver Details</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php echo form_open_multipart('vendor/edit_driver/'.$driver->id); ?>
                        <div class="mb-3">
                            <label for="name" class="form-label">Driver Name *</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo $driver->name; ?>" required>
                            <?php echo form_error('name', '<div class="text-danger">', '</div>'); ?>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo $driver->phone; ?>" required>
                                <?php echo form_error('phone', '<div class="text-danger">', '</div>'); ?>
                            </div>
                            <div class="col-md-6">
                                <label for="license_no" class="form-label">License Number *</label>
                                <input type="text" class="form-control" id="license_no" name="license_no" value="<?php echo $driver->license_number; ?>" required>
                                <?php echo form_error('license_no', '<div class="text-danger">', '</div>'); ?>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3"><?php echo $driver->address; ?></textarea>
                            <?php echo form_error('address', '<div class="text-danger">', '</div>'); ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="license_document" class="form-label">Update License Document (optional)</label>
                            <input type="file" class="form-control" id="license_document" name="license_document">
                            <div class="form-text">Upload a scanned copy of the driver's license. (PDF, JPG, PNG formats only, max 2MB)</div>
                            <?php if(isset($upload_error)): ?>
                                <div class="text-danger"><?php echo $upload_error; ?></div>
                            <?php endif; ?>
                            
                            <?php if (!empty($driver->license_document)): ?>
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
                            <label for="photo" class="form-label">Update Photo (optional)</label>
                            <input type="file" class="form-control" id="photo" name="photo">
                            <div class="form-text">Upload a photo of the driver. (JPG, PNG formats only, max 2MB)</div>
                            
                            <?php if (!empty($driver->photo)): ?>
                                <div class="mt-2 d-flex align-items-center">
                                    <p class="mb-0 me-3">Current photo:</p>
                                    <img src="<?php echo base_url('uploads/drivers/' . $driver->photo); ?>" alt="Driver Photo" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="experience_years" class="form-label">Experience (Years) *</label>
                            <input type="number" class="form-control" id="experience_years" name="experience_years" min="0" max="50" value="<?php echo $driver->experience_years; ?>" required>
                            <?php echo form_error('experience_years', '<div class="text-danger">', '</div>'); ?>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?php echo base_url('vendor/manage_drivers'); ?>" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Driver</button>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>