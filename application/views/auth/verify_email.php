<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header">
                    <h3 class="text-center font-weight-light my-4">Email Verification</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($success) && $success): ?>
                        <div class="alert alert-success" role="alert">
                            <h4 class="alert-heading">Email Verified!</h4>
                            <p>Your email has been successfully verified. You can now log in to your account.</p>
                            <hr>
                            <p class="mb-0"><a href="<?php echo base_url('auth/login'); ?>" class="btn btn-primary">Login Now</a></p>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-danger" role="alert">
                            <h4 class="alert-heading">Verification Failed!</h4>
                            <p><?php echo isset($message) ? $message : 'Invalid or expired verification link. Please request a new verification email.'; ?></p>
                            <hr>
                            <p class="mb-0"><a href="<?php echo base_url('auth/resend_verification'); ?>" class="btn btn-primary">Resend Verification Email</a></p>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer text-center py-3">
                    <div class="small"><a href="<?php echo base_url(); ?>">Return to Home</a></div>
                </div>
            </div>
        </div>
    </div>
</div>