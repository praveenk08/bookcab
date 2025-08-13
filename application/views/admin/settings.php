<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>System Settings</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>">Admin Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">System Settings</li>
                </ol>
            </nav>
        </div>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $this->session->flashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $this->session->flashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="list-group">
                <a href="#general" class="list-group-item list-group-item-action active" data-bs-toggle="list">
                    <i class="fas fa-cog me-2"></i> General Settings
                </a>
                <a href="#booking" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="fas fa-calendar-check me-2"></i> Booking Settings
                </a>
                <a href="#payment" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="fas fa-credit-card me-2"></i> Payment Settings
                </a>
                <a href="#email" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="fas fa-envelope me-2"></i> Email Settings
                </a>
                <a href="#social" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="fas fa-share-alt me-2"></i> Social Media
                </a>
                <a href="#terms" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="fas fa-file-contract me-2"></i> Terms & Policies
                </a>
            </div>
        </div>
        
        <div class="col-md-9 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="tab-content">
                        <!-- General Settings Tab -->
                        <div class="tab-pane fade show active" id="general">
                            <h4 class="card-title mb-4">General Settings</h4>
                            <?php echo form_open_multipart('admin/update_settings/general'); ?>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="site_name" class="form-label">Site Name</label>
                                            <input type="text" class="form-control" id="site_name" name="site_name" value="<?php echo $settings['site_name'] ?? 'Car Booking System'; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="site_tagline" class="form-label">Site Tagline</label>
                                            <input type="text" class="form-control" id="site_tagline" name="site_tagline" value="<?php echo $settings['site_tagline'] ?? 'Book your ride with ease'; ?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="admin_email" class="form-label">Admin Email</label>
                                            <input type="email" class="form-control" id="admin_email" name="admin_email" value="<?php echo $settings['admin_email'] ?? 'admin@example.com'; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="contact_phone" class="form-label">Contact Phone</label>
                                            <input type="text" class="form-control" id="contact_phone" name="contact_phone" value="<?php echo $settings['contact_phone'] ?? '+1234567890'; ?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="site_description" class="form-label">Site Description</label>
                                    <textarea class="form-control" id="site_description" name="site_description" rows="3"><?php echo $settings['site_description'] ?? 'Car Booking System - Book your ride with ease'; ?></textarea>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="logo" class="form-label">Site Logo</label>
                                            <input type="file" class="form-control" id="logo" name="logo">
                                            <div class="form-text">Recommended size: 200x50 pixels</div>
                                            <?php if (!empty($settings['logo'])): ?>
                                                <div class="mt-2">
                                                    <img src="<?php echo base_url('uploads/settings/' . $settings['logo']); ?>" alt="Site Logo" class="img-thumbnail" style="max-height: 50px;">
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="favicon" class="form-label">Favicon</label>
                                            <input type="file" class="form-control" id="favicon" name="favicon">
                                            <div class="form-text">Recommended size: 32x32 pixels</div>
                                            <?php if (!empty($settings['favicon'])): ?>
                                                <div class="mt-2">
                                                    <img src="<?php echo base_url('uploads/settings/' . $settings['favicon']); ?>" alt="Favicon" class="img-thumbnail" style="max-height: 32px;">
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="currency" class="form-label">Currency</label>
                                            <select class="form-select" id="currency" name="currency">
                                                <option value="USD" <?php echo ($settings['currency'] ?? 'USD') == 'USD' ? 'selected' : ''; ?>>USD ($)</option>
                                                <option value="EUR" <?php echo ($settings['currency'] ?? '') == 'EUR' ? 'selected' : ''; ?>>EUR (€)</option>
                                                <option value="GBP" <?php echo ($settings['currency'] ?? '') == 'GBP' ? 'selected' : ''; ?>>GBP (£)</option>
                                                <option value="INR" <?php echo ($settings['currency'] ?? '') == 'INR' ? 'selected' : ''; ?>>INR (₹)</option>
                                                <option value="AUD" <?php echo ($settings['currency'] ?? '') == 'AUD' ? 'selected' : ''; ?>>AUD (A$)</option>
                                                <option value="CAD" <?php echo ($settings['currency'] ?? '') == 'CAD' ? 'selected' : ''; ?>>CAD (C$)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="timezone" class="form-label">Timezone</label>
                                            <select class="form-select" id="timezone" name="timezone">
                                                <?php foreach ($timezones as $tz): ?>
                                                    <option value="<?php echo $tz; ?>" <?php echo ($settings['timezone'] ?? 'UTC') == $tz ? 'selected' : ''; ?>><?php echo $tz; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="google_analytics" class="form-label">Google Analytics Tracking ID</label>
                                    <input type="text" class="form-control" id="google_analytics" name="google_analytics" value="<?php echo $settings['google_analytics'] ?? ''; ?>" placeholder="UA-XXXXXXXXX-X or G-XXXXXXXXXX">
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="maintenance_mode" name="maintenance_mode" value="1" <?php echo ($settings['maintenance_mode'] ?? '0') == '1' ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="maintenance_mode">Maintenance Mode</label>
                                    </div>
                                    <div class="form-text">When enabled, only administrators can access the site.</div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Save General Settings
                                </button>
                            <?php echo form_close(); ?>
                        </div>
                        
                        <!-- Booking Settings Tab -->
                        <div class="tab-pane fade" id="booking">
                            <h4 class="card-title mb-4">Booking Settings</h4>
                            <?php echo form_open('admin/update_settings/booking'); ?>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="booking_advance_days" class="form-label">Advance Booking Days</label>
                                            <input type="number" class="form-control" id="booking_advance_days" name="booking_advance_days" value="<?php echo $settings['booking_advance_days'] ?? '30'; ?>" min="1" max="365">
                                            <div class="form-text">Maximum number of days in advance a booking can be made.</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="min_booking_hours" class="form-label">Minimum Booking Hours</label>
                                            <input type="number" class="form-control" id="min_booking_hours" name="min_booking_hours" value="<?php echo $settings['min_booking_hours'] ?? '4'; ?>" min="1" max="24">
                                            <div class="form-text">Minimum number of hours required for a booking.</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="cancellation_hours" class="form-label">Cancellation Policy (Hours)</label>
                                            <input type="number" class="form-control" id="cancellation_hours" name="cancellation_hours" value="<?php echo $settings['cancellation_hours'] ?? '24'; ?>" min="0" max="72">
                                            <div class="form-text">Hours before pickup time when cancellation is allowed.</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="cancellation_fee_percent" class="form-label">Cancellation Fee (%)</label>
                                            <input type="number" class="form-control" id="cancellation_fee_percent" name="cancellation_fee_percent" value="<?php echo $settings['cancellation_fee_percent'] ?? '10'; ?>" min="0" max="100">
                                            <div class="form-text">Percentage of booking amount charged as cancellation fee.</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="tax_percent" class="form-label">Tax Percentage (%)</label>
                                            <input type="number" class="form-control" id="tax_percent" name="tax_percent" value="<?php echo $settings['tax_percent'] ?? '5'; ?>" min="0" max="100" step="0.01">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="service_fee_percent" class="form-label">Service Fee (%)</label>
                                            <input type="number" class="form-control" id="service_fee_percent" name="service_fee_percent" value="<?php echo $settings['service_fee_percent'] ?? '2'; ?>" min="0" max="100" step="0.01">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="allow_guest_booking" name="allow_guest_booking" value="1" <?php echo ($settings['allow_guest_booking'] ?? '0') == '1' ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="allow_guest_booking">Allow Guest Bookings</label>
                                    </div>
                                    <div class="form-text">Allow users to book without creating an account.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="auto_approve_bookings" name="auto_approve_bookings" value="1" <?php echo ($settings['auto_approve_bookings'] ?? '0') == '1' ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="auto_approve_bookings">Auto-Approve Bookings</label>
                                    </div>
                                    <div class="form-text">Automatically approve bookings without admin review.</div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Save Booking Settings
                                </button>
                            <?php echo form_close(); ?>
                        </div>
                        
                        <!-- Payment Settings Tab -->
                        <div class="tab-pane fade" id="payment">
                            <h4 class="card-title mb-4">Payment Settings</h4>
                            <?php echo form_open('admin/update_settings/payment'); ?>
                                <div class="mb-3">
                                    <label class="form-label">Enabled Payment Methods</label>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="payment_cash" name="payment_methods[]" value="cash" <?php echo in_array('cash', $settings['payment_methods'] ?? ['cash']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="payment_cash">Cash on Delivery</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="payment_paypal" name="payment_methods[]" value="paypal" <?php echo in_array('paypal', $settings['payment_methods'] ?? []) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="payment_paypal">PayPal</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="payment_stripe" name="payment_methods[]" value="stripe" <?php echo in_array('stripe', $settings['payment_methods'] ?? []) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="payment_stripe">Stripe</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="payment_razorpay" name="payment_methods[]" value="razorpay" <?php echo in_array('razorpay', $settings['payment_methods'] ?? []) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="payment_razorpay">Razorpay</label>
                                    </div>
                                </div>
                                
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="mb-0">PayPal Settings</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox" id="paypal_sandbox" name="paypal_sandbox" value="1" <?php echo ($settings['paypal_sandbox'] ?? '1') == '1' ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="paypal_sandbox">Sandbox Mode</label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="paypal_client_id" class="form-label">Client ID</label>
                                            <input type="text" class="form-control" id="paypal_client_id" name="paypal_client_id" value="<?php echo $settings['paypal_client_id'] ?? ''; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="paypal_secret" class="form-label">Secret</label>
                                            <input type="password" class="form-control" id="paypal_secret" name="paypal_secret" value="<?php echo $settings['paypal_secret'] ?? ''; ?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="mb-0">Stripe Settings</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox" id="stripe_test_mode" name="stripe_test_mode" value="1" <?php echo ($settings['stripe_test_mode'] ?? '1') == '1' ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="stripe_test_mode">Test Mode</label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="stripe_publishable_key" class="form-label">Publishable Key</label>
                                            <input type="text" class="form-control" id="stripe_publishable_key" name="stripe_publishable_key" value="<?php echo $settings['stripe_publishable_key'] ?? ''; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="stripe_secret_key" class="form-label">Secret Key</label>
                                            <input type="password" class="form-control" id="stripe_secret_key" name="stripe_secret_key" value="<?php echo $settings['stripe_secret_key'] ?? ''; ?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="mb-0">Razorpay Settings</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="razorpay_key_id" class="form-label">Key ID</label>
                                            <input type="text" class="form-control" id="razorpay_key_id" name="razorpay_key_id" value="<?php echo $settings['razorpay_key_id'] ?? ''; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="razorpay_key_secret" class="form-label">Key Secret</label>
                                            <input type="password" class="form-control" id="razorpay_key_secret" name="razorpay_key_secret" value="<?php echo $settings['razorpay_key_secret'] ?? ''; ?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Save Payment Settings
                                </button>
                            <?php echo form_close(); ?>
                        </div>
                        
                        <!-- Email Settings Tab -->
                        <div class="tab-pane fade" id="email">
                            <h4 class="card-title mb-4">Email Settings</h4>
                            <?php echo form_open('admin/update_settings/email'); ?>
                                <div class="mb-3">
                                    <label for="mail_protocol" class="form-label">Mail Protocol</label>
                                    <select class="form-select" id="mail_protocol" name="mail_protocol">
                                        <option value="smtp" <?php echo ($settings['mail_protocol'] ?? 'smtp') == 'smtp' ? 'selected' : ''; ?>>SMTP</option>
                                        <option value="mail" <?php echo ($settings['mail_protocol'] ?? '') == 'mail' ? 'selected' : ''; ?>>PHP Mail</option>
                                        <option value="sendmail" <?php echo ($settings['mail_protocol'] ?? '') == 'sendmail' ? 'selected' : ''; ?>>Sendmail</option>
                                    </select>
                                </div>
                                
                                <div id="smtp_settings">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="smtp_host" class="form-label">SMTP Host</label>
                                                <input type="text" class="form-control" id="smtp_host" name="smtp_host" value="<?php echo $settings['smtp_host'] ?? ''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="smtp_port" class="form-label">SMTP Port</label>
                                                <input type="text" class="form-control" id="smtp_port" name="smtp_port" value="<?php echo $settings['smtp_port'] ?? '587'; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="smtp_user" class="form-label">SMTP Username</label>
                                                <input type="text" class="form-control" id="smtp_user" name="smtp_user" value="<?php echo $settings['smtp_user'] ?? ''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="smtp_pass" class="form-label">SMTP Password</label>
                                                <input type="password" class="form-control" id="smtp_pass" name="smtp_pass" value="<?php echo $settings['smtp_pass'] ?? ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="smtp_crypto" name="smtp_crypto" value="tls" <?php echo ($settings['smtp_crypto'] ?? 'tls') == 'tls' ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="smtp_crypto">Use TLS</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email_from_address" class="form-label">From Email</label>
                                            <input type="email" class="form-control" id="email_from_address" name="email_from_address" value="<?php echo $settings['email_from_address'] ?? ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email_from_name" class="form-label">From Name</label>
                                            <input type="text" class="form-control" id="email_from_name" name="email_from_name" value="<?php echo $settings['email_from_name'] ?? ''; ?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <button type="button" class="btn btn-info" id="test_email_btn">
                                        <i class="fas fa-paper-plane me-1"></i> Send Test Email
                                    </button>
                                </div>
                                
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="mb-0">Email Templates</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="email_template_booking" class="form-label">Booking Confirmation</label>
                                            <textarea class="form-control" id="email_template_booking" name="email_template_booking" rows="5"><?php echo $settings['email_template_booking'] ?? ''; ?></textarea>
                                            <div class="form-text">Available variables: {customer_name}, {booking_id}, {booking_date}, {total_amount}, {vehicle_details}</div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="email_template_cancellation" class="form-label">Booking Cancellation</label>
                                            <textarea class="form-control" id="email_template_cancellation" name="email_template_cancellation" rows="5"><?php echo $settings['email_template_cancellation'] ?? ''; ?></textarea>
                                            <div class="form-text">Available variables: {customer_name}, {booking_id}, {booking_date}, {cancellation_reason}</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Save Email Settings
                                </button>
                            <?php echo form_close(); ?>
                        </div>
                        
                        <!-- Social Media Tab -->
                        <div class="tab-pane fade" id="social">
                            <h4 class="card-title mb-4">Social Media Settings</h4>
                            <?php echo form_open('admin/update_settings/social'); ?>
                                <div class="mb-3">
                                    <label for="facebook_url" class="form-label">Facebook URL</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fab fa-facebook"></i></span>
                                        <input type="url" class="form-control" id="facebook_url" name="facebook_url" value="<?php echo $settings['facebook_url'] ?? ''; ?>">
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="twitter_url" class="form-label">Twitter URL</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                                        <input type="url" class="form-control" id="twitter_url" name="twitter_url" value="<?php echo $settings['twitter_url'] ?? ''; ?>">
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="instagram_url" class="form-label">Instagram URL</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                                        <input type="url" class="form-control" id="instagram_url" name="instagram_url" value="<?php echo $settings['instagram_url'] ?? ''; ?>">
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="linkedin_url" class="form-label">LinkedIn URL</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fab fa-linkedin"></i></span>
                                        <input type="url" class="form-control" id="linkedin_url" name="linkedin_url" value="<?php echo $settings['linkedin_url'] ?? ''; ?>">
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="youtube_url" class="form-label">YouTube URL</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fab fa-youtube"></i></span>
                                        <input type="url" class="form-control" id="youtube_url" name="youtube_url" value="<?php echo $settings['youtube_url'] ?? ''; ?>">
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Save Social Media Settings
                                </button>
                            <?php echo form_close(); ?>
                        </div>
                        
                        <!-- Terms & Policies Tab -->
                        <div class="tab-pane fade" id="terms">
                            <h4 class="card-title mb-4">Terms & Policies</h4>
                            <?php echo form_open('admin/update_settings/terms'); ?>
                                <div class="mb-3">
                                    <label for="terms_conditions" class="form-label">Terms & Conditions</label>
                                    <textarea class="form-control editor" id="terms_conditions" name="terms_conditions" rows="10"><?php echo $settings['terms_conditions'] ?? ''; ?></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="privacy_policy" class="form-label">Privacy Policy</label>
                                    <textarea class="form-control editor" id="privacy_policy" name="privacy_policy" rows="10"><?php echo $settings['privacy_policy'] ?? ''; ?></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="cancellation_policy" class="form-label">Cancellation Policy</label>
                                    <textarea class="form-control editor" id="cancellation_policy" name="cancellation_policy" rows="10"><?php echo $settings['cancellation_policy'] ?? ''; ?></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="vendor_terms" class="form-label">Vendor Terms</label>
                                    <textarea class="form-control editor" id="vendor_terms" name="vendor_terms" rows="10"><?php echo $settings['vendor_terms'] ?? ''; ?></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Save Terms & Policies
                                </button>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Test Email Modal -->
<div class="modal fade" id="testEmailModal" tabindex="-1" aria-labelledby="testEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="testEmailModalLabel">Send Test Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?php echo form_open('admin/send_test_email', ['id' => 'test_email_form']); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="test_email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="test_email" name="test_email" required>
                        <div class="form-text">A test email will be sent to this address.</div>
                    </div>
                    <div id="test_email_result"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info" id="send_test_email_btn">
                        <i class="fas fa-paper-plane me-1"></i> Send Test Email
                    </button>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize rich text editor for terms & policies
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: '.editor',
                height: 300,
                menubar: false,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table paste code help wordcount'
                ],
                toolbar: 'undo redo | formatselect | bold italic backcolor | \
                    alignleft aligncenter alignright alignjustify | \
                    bullist numlist outdent indent | removeformat | help'
            });
        }
        
        // Toggle SMTP settings based on mail protocol
        $('#mail_protocol').change(function() {
            if ($(this).val() === 'smtp') {
                $('#smtp_settings').show();
            } else {
                $('#smtp_settings').hide();
            }
        }).trigger('change');
        
        // Test email functionality
        $('#test_email_btn').click(function() {
            $('#testEmailModal').modal('show');
        });
        
        $('#test_email_form').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var btn = $('#send_test_email_btn');
            var result = $('#test_email_result');
            
            btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Sending...').attr('disabled', true);
            result.html('');
            
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        result.html('<div class="alert alert-success mt-3"><i class="fas fa-check-circle me-1"></i> ' + response.message + '</div>');
                    } else {
                        result.html('<div class="alert alert-danger mt-3"><i class="fas fa-exclamation-circle me-1"></i> ' + response.message + '</div>');
                    }
                },
                error: function() {
                    result.html('<div class="alert alert-danger mt-3"><i class="fas fa-exclamation-circle me-1"></i> An error occurred while sending the test email.</div>');
                },
                complete: function() {
                    btn.html('<i class="fas fa-paper-plane me-1"></i> Send Test Email').attr('disabled', false);
                }
            });
        });
    });
</script>