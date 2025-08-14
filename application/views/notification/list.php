<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Notifications</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Notifications</li>
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
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Your Notifications</h5>
                    <div>
                        <?php if (!empty($notifications)): ?>
                            <a href="<?php echo base_url('notification/mark_all_as_read'); ?>" class="btn btn-light btn-sm me-2">
                                <i class="fas fa-check-double me-1"></i> Mark All as Read
                            </a>
                            <a href="<?php echo base_url('notification/delete_all'); ?>" class="btn btn-light btn-sm" onclick="return confirm('Are you sure you want to delete all notifications?');">
                                <i class="fas fa-trash me-1"></i> Delete All
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($notifications)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-bell fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">No Notifications</h4>
                            <p class="text-muted">You don't have any notifications at the moment.</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group notification-list">
                            <?php foreach ($notifications as $notification): ?>
                                <div class="list-group-item list-group-item-action <?php echo ($notification->is_read == 0) ? 'unread' : ''; ?>">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <h5 class="mb-1">
                                            <?php if ($notification->is_read == 0): ?>
                                                <span class="badge bg-primary me-2">New</span>
                                            <?php endif; ?>
                                            <?php echo $notification->title; ?>
                                        </h5>
                                        <small class="text-muted"><?php echo date('M d, Y h:i A', strtotime($notification->created_at)); ?></small>
                                    </div>
                                    <p class="mb-1"><?php echo $notification->message; ?></p>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-muted">
                                            <?php 
                                            $badge_class = '';
                                            switch($notification->type) {
                                                case 'booking':
                                                    $badge_class = 'bg-info';
                                                    break;
                                                case 'payment':
                                                    $badge_class = 'bg-success';
                                                    break;
                                                case 'review':
                                                    $badge_class = 'bg-warning text-dark';
                                                    break;
                                                case 'system':
                                                    $badge_class = 'bg-secondary';
                                                    break;
                                                case 'vendor':
                                                    $badge_class = 'bg-primary';
                                                    break;
                                            }
                                            ?>
                                            <span class="badge <?php echo $badge_class; ?>"><?php echo ucfirst($notification->type); ?></span>
                                        </small>
                                        <div class="btn-group">
                                            <?php if ($notification->is_read == 0): ?>
                                                <a href="<?php echo base_url('notification/mark_as_read/' . $notification->id); ?>" class="btn btn-sm btn-outline-primary mark-notification-read" data-notification-id="<?php echo $notification->id; ?>">
                                                    <i class="fas fa-check me-1"></i> Mark as Read
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($notification->reference_id): ?>
                                                <?php 
                                                $reference_url = '#';
                                                switch($notification->type) {
                                                    case 'booking':
                                                        $reference_url = base_url('booking/view/' . $notification->reference_id);
                                                        break;
                                                    case 'payment':
                                                        $reference_url = base_url('booking/view/' . $notification->reference_id);
                                                        break;
                                                    case 'vendor':
                                                        $reference_url = base_url('vendor/view/' . $notification->reference_id);
                                                        break;
                                                }
                                                ?>
                                                <a href="<?php echo $reference_url; ?>" class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-eye me-1"></i> View Details
                                                </a>
                                            <?php endif; ?>
                                            <a href="<?php echo base_url('notification/delete/' . $notification->id); ?>" class="btn btn-sm btn-outline-danger delete-notification" data-notification-id="<?php echo $notification->id; ?>">
                                                <i class="fas fa-trash me-1"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .notification-list .unread {
        background-color: rgba(13, 110, 253, 0.05);
        border-left: 3px solid #0d6efd;
    }
</style>