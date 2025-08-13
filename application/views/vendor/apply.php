<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Become a Vendor</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Vendor Application</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Vendor Application Form</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="alert alert-info">
                        <h5 class="alert-heading"><i class="fas fa-info-circle me-2"></i> Become a Vendor on Our Platform</h5>
                        <p>Join our network of vehicle providers and start earning by renting out your vehicles for weddings and events.</p>
                        <hr>
                        <p class="mb-0">Please fill out the form below with your business details. Our team will review your application and get back to you within 2-3 business days.</p>
                    </div>
                    
                    <?php echo form_open_multipart('vendor/apply'); ?>
                        <div class="mb-3">
                            <label for="business_name" class="form-label">Business Name *</label>
                        <input type="text" class="form-control" id="business_name" name="business_name" value="<?php echo set_value('business_name'); ?>" required>
                        <?php echo form_error('business_name', '<div class="text-danger">', '</div>'); ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Business Address *</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required><?php echo set_value('address'); ?></textarea>
                            <?php echo form_error('address', '<div class="text-danger">', '</div>'); ?>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="city" class="form-label">City *</label>
                                <input type="text" class="form-control" id="city" name="city" value="<?php echo set_value('city'); ?>" required>
                                <?php echo form_error('city', '<div class="text-danger">', '</div>'); ?>
                            </div>
                            <div class="col-md-6">
                                <label for="pincode" class="form-label">PIN Code *</label>
                                <input type="text" class="form-control" id="pincode" name="pincode" value="<?php echo set_value('pincode'); ?>" required>
                                <?php echo form_error('pincode', '<div class="text-danger">', '</div>'); ?>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="business_phone" class="form-label">Business Phone *</label>
                            <input type="tel" class="form-control" id="business_phone" name="business_phone" value="<?php echo set_value('business_phone'); ?>" required>
                            <?php echo form_error('business_phone', '<div class="text-danger">', '</div>'); ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="gst_number" class="form-label">GST Number (if applicable)</label>
                            <input type="text" class="form-control" id="gst_number" name="gst_number" value="<?php echo set_value('gst_number'); ?>">
                            <?php echo form_error('gst_number', '<div class="text-danger">', '</div>'); ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="business_document" class="form-label">Business Registration Document *</label>
                            <input type="file" class="form-control" id="business_document" name="business_document" required>
                            <div class="form-text">Upload a scanned copy of your business registration certificate, shop license, or any other official document. (PDF, JPG, PNG formats only, max 2MB)</div>
                            <?php if(isset($upload_error)): ?>
                                <div class="text-danger"><?php echo $upload_error; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="id_proof" class="form-label">ID Proof *</label>
                            <input type="file" class="form-control" id="id_proof" name="id_proof" required>
                            <div class="form-text">Upload a scanned copy of your Aadhaar Card, PAN Card, or Driving License. (PDF, JPG, PNG formats only, max 2MB)</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Business Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Tell us about your business, types of vehicles you offer, and your experience in the industry..."><?php echo set_value('description'); ?></textarea>
                            <?php echo form_error('description', '<div class="text-danger">', '</div>'); ?>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a> for vendors *</label>
                            <?php echo form_error('terms', '<div class="text-danger">', '</div>'); ?>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i> Submit Application
                            </button>
                        </div>
                    <?php echo form_close(); ?>
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
                <h5 class="modal-title" id="termsModalLabel">Vendor Terms and Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>1. Vendor Registration and Approval</h6>
                <p>All vendors must complete the registration process and provide accurate business information. Our admin team will review your application and may request additional documentation. Approval is at the discretion of the platform administrators.</p>
                
                <h6>2. Vehicle Listings</h6>
                <p>Vendors are responsible for providing accurate information about their vehicles, including type, capacity, pricing, and availability. All vehicles must be legally registered, insured, and maintained in good condition.</p>
                
                <h6>3. Drivers</h6>
                <p>If providing drivers, vendors must ensure all drivers have valid licenses, proper training, and clean records. Vendors are responsible for driver behavior and performance.</p>
                
                <h6>4. Booking Fulfillment</h6>
                <p>Vendors must honor all confirmed bookings. Cancellations by vendors may result in penalties, including account suspension. Vehicles must be provided as described and on time.</p>
                
                <h6>5. Pricing and Payments</h6>
                <p>Vendors set their own pricing but must honor the prices listed at the time of booking. The platform charges a service fee of 10% on each successful booking. Payments will be processed according to the platform's payment schedule.</p>
                
                <h6>6. Reviews and Ratings</h6>
                <p>Customers can leave reviews and ratings. Vendors with consistently poor ratings may be removed from the platform.</p>
                
                <h6>7. Dispute Resolution</h6>
                <p>The platform administrators will mediate disputes between vendors and customers. Vendors agree to abide by the final decision of the platform administrators.</p>
                
                <h6>8. Account Termination</h6>
                <p>The platform reserves the right to terminate vendor accounts for violations of these terms, fraudulent activity, or consistently poor service.</p>
                
                <h6>9. Changes to Terms</h6>
                <p>These terms may be updated periodically. Vendors will be notified of significant changes and continued use of the platform constitutes acceptance of the updated terms.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">I Understand</button>
            </div>
        </div>
    </div>
</div>