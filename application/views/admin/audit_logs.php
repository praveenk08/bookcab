<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Audit Logs</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>">Admin Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Audit Logs</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">System Activity Logs</h5>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <?php echo form_open('admin/audit_logs', ['method' => 'GET', 'class' => 'row g-3']); ?>
                                <div class="col-md-3">
                                    <label for="user_id" class="form-label">User</label>
                                    <select class="form-select" id="user_id" name="user_id">
                                        <option value="">All Users</option>
                                        <?php foreach ($users as $u): ?>
                                            <option value="<?php echo $u->id; ?>" <?php echo ($this->input->get('user_id') == $u->id) ? 'selected' : ''; ?>>
                                                <?php echo $u->name; ?> (<?php echo $u->email; ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="action_type" class="form-label">Action Type</label>
                                    <select class="form-select" id="action_type" name="action_type">
                                        <option value="" <?php echo $this->input->get('action_type') == '' ? 'selected' : ''; ?>>All Actions</option>
                                        <option value="login" <?php echo $this->input->get('action_type') == 'login' ? 'selected' : ''; ?>>Login</option>
                                        <option value="logout" <?php echo $this->input->get('action_type') == 'logout' ? 'selected' : ''; ?>>Logout</option>
                                        <option value="register" <?php echo $this->input->get('action_type') == 'register' ? 'selected' : ''; ?>>Registration</option>
                                        <option value="booking" <?php echo $this->input->get('action_type') == 'booking' ? 'selected' : ''; ?>>Booking</option>
                                        <option value="vendor" <?php echo $this->input->get('action_type') == 'vendor' ? 'selected' : ''; ?>>Vendor</option>
                                        <option value="vehicle" <?php echo $this->input->get('action_type') == 'vehicle' ? 'selected' : ''; ?>>Vehicle</option>
                                        <option value="admin" <?php echo $this->input->get('action_type') == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="date_range" class="form-label">Date Range</label>
                                    <input type="text" class="form-control" id="date_range" name="date_range" placeholder="Select date range" value="<?php echo $this->input->get('date_range'); ?>">
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-filter me-1"></i> Apply Filters
                                    </button>
                                    <a href="<?php echo base_url('admin/audit_logs'); ?>" class="btn btn-outline-secondary">
                                        <i class="fas fa-redo me-1"></i> Reset
                                    </a>
                                </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>

                    <?php if (empty($logs)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> No audit logs found matching your criteria.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User</th>
                                        <th>Action</th>
                                        <th>Details</th>
                                        <th>IP Address</th>
                                        <th>Date & Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($logs as $log): ?>
                                        <tr>
                                            <td><?php echo $log->id; ?></td>
                                            <td>
                                                <?php if ($log->user_id): ?>
                                                    <a href="<?php echo base_url('admin/view_user/' . $log->user_id); ?>">
                                                        <?php echo $log->user_name; ?>
                                                    </a>
                                                    <div class="small text-muted"><?php echo $log->user_email; ?></div>
                                                <?php else: ?>
                                                    <span class="text-muted">System</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php 
                                                $badge_class = 'bg-secondary';
                                                if (strpos($log->action, 'login') !== false) {
                                                    $badge_class = 'bg-success';
                                                } elseif (strpos($log->action, 'logout') !== false) {
                                                    $badge_class = 'bg-info';
                                                } elseif (strpos($log->action, 'register') !== false) {
                                                    $badge_class = 'bg-primary';
                                                } elseif (strpos($log->action, 'booking') !== false) {
                                                    $badge_class = 'bg-warning text-dark';
                                                } elseif (strpos($log->action, 'error') !== false) {
                                                    $badge_class = 'bg-danger';
                                                }
                                                ?>
                                                <span class="badge <?php echo $badge_class; ?>"><?php echo $log->action; ?></span>
                                            </td>
                                            <td><?php echo $log->details; ?></td>
                                            <td>
                                                <?php if ($log->ip_address): ?>
                                                    <span class="badge bg-light text-dark"><?php echo $log->ip_address; ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo date('M d, Y g:i A', strtotime($log->created_at)); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            <?php echo $this->pagination->create_links(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize date range picker
    $(document).ready(function() {
        $('#date_range').daterangepicker({
            opens: 'left',
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear',
                format: 'YYYY-MM-DD'
            }
        });
        
        $('#date_range').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
        });
        
        $('#date_range').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
        
        // If there's a value already, initialize the picker with it
        if ($('#date_range').val()) {
            var dates = $('#date_range').val().split(' - ');
            $('#date_range').data('daterangepicker').setStartDate(dates[0]);
            $('#date_range').data('daterangepicker').setEndDate(dates[1]);
        }
    });
</script>