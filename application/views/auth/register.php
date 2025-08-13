<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Register</h4>
                </div>
                <div class="card-body">
                    <?php if($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= $this->session->flashdata('error'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?= form_open('auth/register'); ?>
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= set_value('name'); ?>" required>
                            <?= form_error('name', '<small class="text-danger">', '</small>'); ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= set_value('email'); ?>" required>
                            <?= form_error('email', '<small class="text-danger">', '</small>'); ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="<?= set_value('phone'); ?>" required>
                            <?= form_error('phone', '<small class="text-danger">', '</small>'); ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <?= form_error('password', '<small class="text-danger">', '</small>'); ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            <?= form_error('confirm_password', '<small class="text-danger">', '</small>'); ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3"><?= set_value('address'); ?></textarea>
                            <?= form_error('address', '<small class="text-danger">', '</small>'); ?>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Register</button>
                        </div>
                    <?= form_close(); ?>
                    
                    <div class="mt-3 text-center">
                        <p>Already have an account? <a href="<?= base_url('login'); ?>">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>