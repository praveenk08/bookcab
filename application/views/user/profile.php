<div class="container mt-4">
    <h1>My Profile</h1>
    
    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?= $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>
    
    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger">
            <?= $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Update Profile</h5>
                </div>
                <div class="card-body">
                    <?= form_open('user/profile'); ?>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= set_value('name', $user->name); ?>" required>
                            <?= form_error('name', '<small class="text-danger">', '</small>'); ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= set_value('email', $user->email); ?>" required>
                            <?= form_error('email', '<small class="text-danger">', '</small>'); ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="<?= set_value('phone', $user->phone); ?>" required>
                            <?= form_error('phone', '<small class="text-danger">', '</small>'); ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3"><?= set_value('address', $user->address); ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Account Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>User ID:</strong> <?= $user->id; ?></p>
                    <p><strong>Role:</strong> <?= ucfirst($user->role); ?></p>
                    <p><strong>Joined:</strong> <?= date('M d, Y', strtotime($user->created_at)); ?></p>
                    <p><strong>Verified:</strong> 
                        <?php if($user->is_verified == 1): ?>
                            <span class="badge bg-success">Verified</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Pending</span>
                        <?php endif; ?>
                    </p><p><strong>Status:</strong> 
                        <?php if($user->status == 'active'): ?>
                            <span class="badge bg-success">Active</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Inactive</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Security</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= base_url('change-password'); ?>" class="btn btn-outline-primary">Change Password</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>