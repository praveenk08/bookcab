<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Add New Driver</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <?php if ($this->session->userdata('role') === 'vendor'): ?>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('vendor/dashboard'); ?>">Vendor Dashboard</a></li>
                    <?php else: ?>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>">Admin Dashboard</a></li>
                    <?php endif; ?>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('driver/manage'); ?>">Manage Drivers</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add New Driver</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Driver Details</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $this->session->flashdata('error'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php echo form_open_multipart('driver/add'); ?>
                        <?php if ($this->session->userdata('role') === 'admin'): ?>
                            <div class="mb-3">
                                <label for="vendor_id" class="form-label">Vendor *</label>
                                <select class="form-select" id="vendor_id" name="vendor_id" required>
                                    <option value="">Select Vendor</option>
                                    <?php 
                                        $vendors = $this->vendor_model->get_vendors(['status' => 'approved']);
                                        foreach ($vendors as $vendor): 
                                    ?>
                                        <option value="<?php echo $vendor->id; ?>"><?php echo $vendor->business_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php echo form_error('vendor_id', '<div class="text-danger">', '</div>'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Driver Name *</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo set_value('name'); ?>" required>
                            <?php echo form_error('name', '<div class="text-danger">', '</div>'); ?>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo set_value('phone'); ?>" required>
                                <div class="form-text">10-digit number without spaces or dashes</div>
                                <?php echo form_error('phone', '<div class="text-danger">', '</div>'); ?>
                            </div>
                            <div class="col-md-6">
                                <label for="license_no" class="form-label">License Number *</label>
                                <input type="text" class="form-control" id="license_no" name="license_no" value="<?php echo set_value('license_no'); ?>" required>
                                <?php echo form_error('license_no', '<div class="text-danger">', '</div>'); ?>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3"><?php echo set_value('address'); ?></textarea>
                            <?php echo form_error('address', '<div class="text-danger">', '</div>'); ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="license_document" class="form-label">License Document *</label>
                            <input type="file" class="form-control" id="license_document" name="license_document" required>
                            <div class="form-text">Upload a scanned copy of the driver's license. (PDF, JPG, PNG formats only, max 2MB)</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="photo" class="form-label">Driver Photo *</label>
                            <input type="file" class="form-control" id="photo" name="photo" required>
                            <div class="form-text">Upload a photo of the driver. (JPG, PNG formats only, max 2MB)</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="experience" class="form-label">Experience (Years) *</label>
                            <input type="number" class="form-control" id="experience" name="experience" min="0" max="50" value="<?php echo set_value('experience', '0'); ?>" required>
                            <?php echo form_error('experience', '<div class="text-danger">', '</div>'); ?>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?php echo base_url('driver/manage'); ?>" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Add Driver</button>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>