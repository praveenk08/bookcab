<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Resend Verification Email</h4>
                </div>
                <div class="card-body">
                    <?php if($this->session->flashdata('success')): ?>
                        <div class="alert alert-success">
                            <?php echo $this->session->flashdata('success'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?php echo $this->session->flashdata('error'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php echo form_open('auth/resend_verification'); ?>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" name="email" class="form-control" required>
                            <?php echo form_error('email', '<div class="text-danger">', '</div>'); ?>
                        </div>
                        
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary btn-block">Resend Verification Email</button>
                        </div>
                    <?php echo form_close(); ?>
                    
                    <div class="mt-3 text-center">
                        <p>Remember your password? <a href="<?php echo base_url('auth/login'); ?>">Login</a></p>
                        <p>Don't have an account? <a href="<?php echo base_url('auth/register'); ?>">Register</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>