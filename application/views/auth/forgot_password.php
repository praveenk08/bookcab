<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header">
                    <h3 class="text-center font-weight-light my-4">Forgot Password</h3>
                </div>
                <div class="card-body">
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
                    
                    <div class="small mb-3 text-muted">Enter your email address and we will send you a link to reset your password.</div>
                    
                    <?php echo form_open('auth/forgot_password'); ?>
                        <div class="form-floating mb-3">
                            <input class="form-control" id="email" name="email" type="email" placeholder="name@example.com" required />
                            <label for="email">Email address</label>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                            <a class="small" href="<?php echo base_url('auth/login'); ?>">Return to login</a>
                            <button type="submit" class="btn btn-primary">Reset Password</button>
                        </div>
                    <?php echo form_close(); ?>
                </div>
                <div class="card-footer text-center py-3">
                    <div class="small"><a href="<?php echo base_url('auth/register'); ?>">Need an account? Sign up!</a></div>
                </div>
            </div>
        </div>
    </div>
</div>