<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Vendor Management</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>">Admin Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Vendor Management</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Vendors</h5>
                    <div>
                        <button type="button" class="btn btn-warning btn-sm me-2 filter-btn" data-status="pending">
                            <i class="fas fa-clock me-1"></i> Pending
                            <?php if(isset($pending_count) && $pending_count > 0): ?>
                                <span class="badge bg-light text-dark ms-1"><?php echo $pending_count; ?></span>
                            <?php endif; ?>
                        </button>
                        <button type="button" class="btn btn-success btn-sm me-2 filter-btn" data-status="approved">
                            <i class="fas fa-check-circle me-1"></i> Approved
                        </button>
                        <button type="button" class="btn btn-danger btn-sm me-2 filter-btn" data-status="rejected">
                            <i class="fas fa-times-circle me-1"></i> Rejected
                        </button>
                        <button type="button" class="btn btn-light btn-sm filter-btn" data-status="">
                            <i class="fas fa-list me-1"></i> All
                        </button>
                    </div>
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

                    <!-- Filter Form -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="search" class="form-label">Search</label>
                                    <input type="text" class="form-control" id="search" placeholder="Business name, email...">
                                </div>
                                <div class="col-md-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status">
                                        <option value="">All Statuses</option>
                                        <option value="pending">Pending</option>
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="button" id="filter-button" class="btn btn-primary me-2">
                                        <i class="fas fa-filter me-1"></i> Apply Filters
                                    </button>
                                    <button type="button" id="reset-button" class="btn btn-outline-secondary">
                                        <i class="fas fa-redo me-1"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="vendors-table" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                        <th>Business Name</th>
                                        <th>Owner</th>
                                        <th>Contact</th>
                                        <th>Vehicles</th>
                                        <th>Applied On</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- DataTables will populate this -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Vendor Modal Template -->
<div class="modal fade" id="approveVendorModalTemplate" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Approve Vendor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="approveVendorForm" action="" method="post">
                <div class="modal-body">
                    <p>Are you sure you want to approve <strong id="approve-vendor-name"></strong> as a vendor?</p>
                    <div class="mb-3">
                        <label for="approval_note" class="form-label">Approval Note (Optional)</label>
                        <textarea class="form-control" id="approval_note" name="approval_note" rows="3" placeholder="Add any notes or instructions for the vendor..."></textarea>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="is_verified" name="is_verified" value="1">
                        <label class="form-check-label" for="is_verified">
                            Mark as verified business
                        </label>
                        <div class="form-text">Verified businesses have provided additional documentation to confirm their legitimacy.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Approve Vendor</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Vendor Modal Template -->
<div class="modal fade" id="rejectVendorModalTemplate" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Reject Vendor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rejectVendorForm" action="" method="post">
                <div class="modal-body">
                    <p>Are you sure you want to reject <strong id="reject-vendor-name"></strong> as a vendor?</p>
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" placeholder="Provide a reason for rejection..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Vendor</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var vendorsTable = $('#vendors-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?php echo base_url("admin/vendors"); ?>',
            type: 'GET',
            data: function(d) {
                d.status = $('#status').val();
                d.search.value = $('#search').val() || d.search.value;
            }
        },
        columns: [
            { data: 0 }, // ID
            { data: 1 }, // Business Name
            { data: 2 }, // Owner
            { data: 3 }, // Contact
            { data: 4 }, // Vehicles
            { data: 5 }, // Applied On
            { data: 6 }, // Status
            { data: 7, orderable: false } // Actions
        ],
        order: [[0, 'desc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        dom: '<"d-flex justify-content-between align-items-center mb-3"<"d-flex align-items-center"l><"d-flex"f>>t<"d-flex justify-content-between align-items-center mt-3"<"text-muted"i><"d-flex"p>>',
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search...",
            lengthMenu: "Show _MENU_ entries",
            paginate: {
                first: '<i class="fas fa-angle-double-left"></i>',
                last: '<i class="fas fa-angle-double-right"></i>',
                next: '<i class="fas fa-angle-right"></i>',
                previous: '<i class="fas fa-angle-left"></i>'
            }
        }
    });
    
    // Apply filters when button is clicked
    $('#filter-button').on('click', function() {
        vendorsTable.draw();
    });
    
    // Reset filters
    $('#reset-button').on('click', function() {
        $('#search').val('');
        $('#status').val('');
        vendorsTable.search('').draw();
    });
    
    // Filter buttons at the top
    $('.filter-btn').on('click', function() {
        var status = $(this).data('status');
        $('#status').val(status);
        vendorsTable.draw();
    });
    
    // Search input keyup event
    $('#search').on('keyup', function() {
        vendorsTable.search(this.value).draw();
    });
    
    // Handle approve vendor modal
    $(document).on('click', '[data-bs-target^="#approveVendorModal"]', function() {
        var vendorId = $(this).data('vendor-id');
        var vendorName = $(this).data('vendor-name');
        var isVerified = $(this).data('is-verified');
        
        $('#approve-vendor-name').text(vendorName);
        $('#approveVendorForm').attr('action', '<?php echo base_url("admin/approve_vendor/"); ?>' + vendorId);
        $('#is_verified').prop('checked', isVerified == 1);
        
        $('#approveVendorModalTemplate').modal('show');
    });
    
    // Handle reject vendor modal
    $(document).on('click', '[data-bs-target^="#rejectVendorModal"]', function() {
        var vendorId = $(this).data('vendor-id');
        var vendorName = $(this).data('vendor-name');
        
        $('#reject-vendor-name').text(vendorName);
        $('#rejectVendorForm').attr('action', '<?php echo base_url("admin/reject_vendor/"); ?>' + vendorId);
        
        $('#rejectVendorModalTemplate').modal('show');
    });
    
    // Handle form submissions with AJAX
    $('#approveVendorForm, #rejectVendorForm').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var url = form.attr('action');
        
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            success: function(response) {
                // Close modal
                $('.modal').modal('hide');
                
                // Show success message
                var alertClass = 'alert-success';
                var message = 'Vendor status updated successfully.';
                
                // Add alert to page
                var alertHtml = '<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
                                message +
                                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                                '</div>';
                                
                $('.card-body').prepend(alertHtml);
                
                // Refresh table
                vendorsTable.ajax.reload();
            },
            error: function(xhr, status, error) {
                // Show error message
                var alertClass = 'alert-danger';
                var message = 'An error occurred: ' + error;
                
                // Add alert to page
                var alertHtml = '<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
                                message +
                                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                                '</div>';
                                
                $('.card-body').prepend(alertHtml);
            }
        });
    });
});
</script>