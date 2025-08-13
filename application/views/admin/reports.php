<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Reports</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>">Admin Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Reports</li>
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
                <a href="#booking_report" class="list-group-item list-group-item-action active" data-bs-toggle="list">
                    <i class="fas fa-calendar-check me-2"></i> Booking Reports
                </a>
                <a href="#revenue_report" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="fas fa-money-bill-wave me-2"></i> Revenue Reports
                </a>
                <a href="#vendor_report" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="fas fa-store me-2"></i> Vendor Reports
                </a>
                <a href="#vehicle_report" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="fas fa-car me-2"></i> Vehicle Reports
                </a>
                <a href="#customer_report" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="fas fa-users me-2"></i> Customer Reports
                </a>
            </div>
        </div>
        
        <div class="col-md-9 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Booking Reports Tab -->
                        <div class="tab-pane fade show active" id="booking_report">
                            <h4 class="card-title mb-4">Booking Reports</h4>
                            
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Filter Options</h5>
                                </div>
                                <div class="card-body">
                                    <?php echo form_open('admin/generate_report/booking', ['method' => 'get', 'id' => 'booking_report_form']); ?>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="date_range" class="form-label">Date Range</label>
                                                    <select class="form-select" id="date_range" name="date_range">
                                                        <option value="today">Today</option>
                                                        <option value="yesterday">Yesterday</option>
                                                        <option value="this_week">This Week</option>
                                                        <option value="last_week">Last Week</option>
                                                        <option value="this_month" selected>This Month</option>
                                                        <option value="last_month">Last Month</option>
                                                        <option value="this_year">This Year</option>
                                                        <option value="last_year">Last Year</option>
                                                        <option value="custom">Custom Range</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6" id="custom_date_range" style="display: none;">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="start_date" class="form-label">Start Date</label>
                                                            <input type="date" class="form-control" id="start_date" name="start_date">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="end_date" class="form-label">End Date</label>
                                                            <input type="date" class="form-control" id="end_date" name="end_date">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="booking_status" class="form-label">Booking Status</label>
                                                    <select class="form-select" id="booking_status" name="booking_status">
                                                        <option value="all" selected>All Statuses</option>
                                                        <option value="pending">Pending</option>
                                                        <option value="confirmed">Confirmed</option>
                                                        <option value="completed">Completed</option>
                                                        <option value="cancelled">Cancelled</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="vendor_id" class="form-label">Vendor</label>
                                                    <select class="form-select" id="vendor_id" name="vendor_id">
                                                        <option value="all" selected>All Vendors</option>
                                                        <?php foreach ($vendors as $vendor): ?>
                                                            <option value="<?php echo $vendor->id; ?>"><?php echo $vendor->business_name; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="group_by" class="form-label">Group By</label>
                                                    <select class="form-select" id="group_by" name="group_by">
                                                        <option value="day">Day</option>
                                                        <option value="week">Week</option>
                                                        <option value="month" selected>Month</option>
                                                        <option value="vendor">Vendor</option>
                                                        <option value="vehicle_type">Vehicle Type</option>
                                                        <option value="status">Status</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="report_type" class="form-label">Report Type</label>
                                                    <select class="form-select" id="report_type" name="report_type">
                                                        <option value="summary" selected>Summary</option>
                                                        <option value="detailed">Detailed</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-search me-1"></i> Generate Report
                                                </button>
                                                <button type="button" class="btn btn-success ms-2" id="export_booking_report">
                                                    <i class="fas fa-file-excel me-1"></i> Export to Excel
                                                </button>
                                                <button type="button" class="btn btn-danger ms-2" id="export_booking_report_pdf">
                                                    <i class="fas fa-file-pdf me-1"></i> Export to PDF
                                                </button>
                                            </div>
                                        </div>
                                    <?php echo form_close(); ?>
                                </div>
                            </div>
                            
                            <div id="booking_report_results">
                                <!-- Report results will be loaded here via AJAX -->
                                <div class="text-center py-5">
                                    <i class="fas fa-chart-bar fa-4x text-muted mb-3"></i>
                                    <p class="lead">Select filters and generate a report to view results</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Revenue Reports Tab -->
                        <div class="tab-pane fade" id="revenue_report">
                            <h4 class="card-title mb-4">Revenue Reports</h4>
                            
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Filter Options</h5>
                                </div>
                                <div class="card-body">
                                    <?php echo form_open('admin/generate_report/revenue', ['method' => 'get', 'id' => 'revenue_report_form']); ?>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="revenue_date_range" class="form-label">Date Range</label>
                                                    <select class="form-select" id="revenue_date_range" name="date_range">
                                                        <option value="today">Today</option>
                                                        <option value="yesterday">Yesterday</option>
                                                        <option value="this_week">This Week</option>
                                                        <option value="last_week">Last Week</option>
                                                        <option value="this_month" selected>This Month</option>
                                                        <option value="last_month">Last Month</option>
                                                        <option value="this_year">This Year</option>
                                                        <option value="last_year">Last Year</option>
                                                        <option value="custom">Custom Range</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6" id="revenue_custom_date_range" style="display: none;">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="revenue_start_date" class="form-label">Start Date</label>
                                                            <input type="date" class="form-control" id="revenue_start_date" name="start_date">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="revenue_end_date" class="form-label">End Date</label>
                                                            <input type="date" class="form-control" id="revenue_end_date" name="end_date">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="revenue_vendor_id" class="form-label">Vendor</label>
                                                    <select class="form-select" id="revenue_vendor_id" name="vendor_id">
                                                        <option value="all" selected>All Vendors</option>
                                                        <?php foreach ($vendors as $vendor): ?>
                                                            <option value="<?php echo $vendor->id; ?>"><?php echo $vendor->business_name; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="revenue_payment_method" class="form-label">Payment Method</label>
                                                    <select class="form-select" id="revenue_payment_method" name="payment_method">
                                                        <option value="all" selected>All Methods</option>
                                                        <option value="cash">Cash</option>
                                                        <option value="paypal">PayPal</option>
                                                        <option value="stripe">Stripe</option>
                                                        <option value="razorpay">Razorpay</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="revenue_group_by" class="form-label">Group By</label>
                                                    <select class="form-select" id="revenue_group_by" name="group_by">
                                                        <option value="day">Day</option>
                                                        <option value="week">Week</option>
                                                        <option value="month" selected>Month</option>
                                                        <option value="vendor">Vendor</option>
                                                        <option value="payment_method">Payment Method</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="include_tax" class="form-label">Include</label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="include_tax" name="include_tax" value="1" checked>
                                                        <label class="form-check-label" for="include_tax">Include Tax</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="include_service_fee" name="include_service_fee" value="1" checked>
                                                        <label class="form-check-label" for="include_service_fee">Include Service Fee</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-search me-1"></i> Generate Report
                                                </button>
                                                <button type="button" class="btn btn-success ms-2" id="export_revenue_report">
                                                    <i class="fas fa-file-excel me-1"></i> Export to Excel
                                                </button>
                                                <button type="button" class="btn btn-danger ms-2" id="export_revenue_report_pdf">
                                                    <i class="fas fa-file-pdf me-1"></i> Export to PDF
                                                </button>
                                            </div>
                                        </div>
                                    <?php echo form_close(); ?>
                                </div>
                            </div>
                            
                            <div id="revenue_report_results">
                                <!-- Report results will be loaded here via AJAX -->
                                <div class="text-center py-5">
                                    <i class="fas fa-money-bill-wave fa-4x text-muted mb-3"></i>
                                    <p class="lead">Select filters and generate a report to view results</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Vendor Reports Tab -->
                        <div class="tab-pane fade" id="vendor_report">
                            <h4 class="card-title mb-4">Vendor Reports</h4>
                            
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Filter Options</h5>
                                </div>
                                <div class="card-body">
                                    <?php echo form_open('admin/generate_report/vendor', ['method' => 'get', 'id' => 'vendor_report_form']); ?>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="vendor_date_range" class="form-label">Date Range</label>
                                                    <select class="form-select" id="vendor_date_range" name="date_range">
                                                        <option value="this_month" selected>This Month</option>
                                                        <option value="last_month">Last Month</option>
                                                        <option value="this_year">This Year</option>
                                                        <option value="last_year">Last Year</option>
                                                        <option value="all_time">All Time</option>
                                                        <option value="custom">Custom Range</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6" id="vendor_custom_date_range" style="display: none;">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="vendor_start_date" class="form-label">Start Date</label>
                                                            <input type="date" class="form-control" id="vendor_start_date" name="start_date">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="vendor_end_date" class="form-label">End Date</label>
                                                            <input type="date" class="form-control" id="vendor_end_date" name="end_date">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="vendor_status" class="form-label">Vendor Status</label>
                                                    <select class="form-select" id="vendor_status" name="vendor_status">
                                                        <option value="all" selected>All Statuses</option>
                                                        <option value="pending">Pending</option>
                                                        <option value="approved">Approved</option>
                                                        <option value="rejected">Rejected</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="vendor_sort_by" class="form-label">Sort By</label>
                                                    <select class="form-select" id="vendor_sort_by" name="sort_by">
                                                        <option value="bookings" selected>Total Bookings</option>
                                                        <option value="revenue">Total Revenue</option>
                                                        <option value="vehicles">Number of Vehicles</option>
                                                        <option value="rating">Average Rating</option>
                                                        <option value="date_joined">Date Joined</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="vendor_report_type" class="form-label">Report Type</label>
                                                    <select class="form-select" id="vendor_report_type" name="report_type">
                                                        <option value="performance" selected>Performance</option>
                                                        <option value="growth">Growth</option>
                                                        <option value="comparison">Comparison</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="vendor_limit" class="form-label">Limit Results</label>
                                                    <select class="form-select" id="vendor_limit" name="limit">
                                                        <option value="10" selected>Top 10</option>
                                                        <option value="20">Top 20</option>
                                                        <option value="50">Top 50</option>
                                                        <option value="all">All Vendors</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-search me-1"></i> Generate Report
                                                </button>
                                                <button type="button" class="btn btn-success ms-2" id="export_vendor_report">
                                                    <i class="fas fa-file-excel me-1"></i> Export to Excel
                                                </button>
                                                <button type="button" class="btn btn-danger ms-2" id="export_vendor_report_pdf">
                                                    <i class="fas fa-file-pdf me-1"></i> Export to PDF
                                                </button>
                                            </div>
                                        </div>
                                    <?php echo form_close(); ?>
                                </div>
                            </div>
                            
                            <div id="vendor_report_results">
                                <!-- Report results will be loaded here via AJAX -->
                                <div class="text-center py-5">
                                    <i class="fas fa-store fa-4x text-muted mb-3"></i>
                                    <p class="lead">Select filters and generate a report to view results</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Vehicle Reports Tab -->
                        <div class="tab-pane fade" id="vehicle_report">
                            <h4 class="card-title mb-4">Vehicle Reports</h4>
                            
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Filter Options</h5>
                                </div>
                                <div class="card-body">
                                    <?php echo form_open('admin/generate_report/vehicle', ['method' => 'get', 'id' => 'vehicle_report_form']); ?>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="vehicle_date_range" class="form-label">Date Range</label>
                                                    <select class="form-select" id="vehicle_date_range" name="date_range">
                                                        <option value="this_month" selected>This Month</option>
                                                        <option value="last_month">Last Month</option>
                                                        <option value="this_year">This Year</option>
                                                        <option value="last_year">Last Year</option>
                                                        <option value="all_time">All Time</option>
                                                        <option value="custom">Custom Range</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6" id="vehicle_custom_date_range" style="display: none;">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="vehicle_start_date" class="form-label">Start Date</label>
                                                            <input type="date" class="form-control" id="vehicle_start_date" name="start_date">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="vehicle_end_date" class="form-label">End Date</label>
                                                            <input type="date" class="form-control" id="vehicle_end_date" name="end_date">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="vehicle_vendor_id" class="form-label">Vendor</label>
                                                    <select class="form-select" id="vehicle_vendor_id" name="vendor_id">
                                                        <option value="all" selected>All Vendors</option>
                                                        <?php foreach ($vendors as $vendor): ?>
                                                            <option value="<?php echo $vendor->id; ?>"><?php echo $vendor->business_name; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="vehicle_type" class="form-label">Vehicle Type</label>
                                                    <select class="form-select" id="vehicle_type" name="vehicle_type">
                                                        <option value="all" selected>All Types</option>
                                                        <option value="sedan">Sedan</option>
                                                        <option value="suv">SUV</option>
                                                        <option value="luxury">Luxury</option>
                                                        <option value="van">Van</option>
                                                        <option value="bus">Bus</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="vehicle_sort_by" class="form-label">Sort By</label>
                                                    <select class="form-select" id="vehicle_sort_by" name="sort_by">
                                                        <option value="bookings" selected>Total Bookings</option>
                                                        <option value="revenue">Total Revenue</option>
                                                        <option value="availability">Availability</option>
                                                        <option value="rating">Average Rating</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="vehicle_limit" class="form-label">Limit Results</label>
                                                    <select class="form-select" id="vehicle_limit" name="limit">
                                                        <option value="10" selected>Top 10</option>
                                                        <option value="20">Top 20</option>
                                                        <option value="50">Top 50</option>
                                                        <option value="all">All Vehicles</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-search me-1"></i> Generate Report
                                                </button>
                                                <button type="button" class="btn btn-success ms-2" id="export_vehicle_report">
                                                    <i class="fas fa-file-excel me-1"></i> Export to Excel
                                                </button>
                                                <button type="button" class="btn btn-danger ms-2" id="export_vehicle_report_pdf">
                                                    <i class="fas fa-file-pdf me-1"></i> Export to PDF
                                                </button>
                                            </div>
                                        </div>
                                    <?php echo form_close(); ?>
                                </div>
                            </div>
                            
                            <div id="vehicle_report_results">
                                <!-- Report results will be loaded here via AJAX -->
                                <div class="text-center py-5">
                                    <i class="fas fa-car fa-4x text-muted mb-3"></i>
                                    <p class="lead">Select filters and generate a report to view results</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Customer Reports Tab -->
                        <div class="tab-pane fade" id="customer_report">
                            <h4 class="card-title mb-4">Customer Reports</h4>
                            
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Filter Options</h5>
                                </div>
                                <div class="card-body">
                                    <?php echo form_open('admin/generate_report/customer', ['method' => 'get', 'id' => 'customer_report_form']); ?>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="customer_date_range" class="form-label">Date Range</label>
                                                    <select class="form-select" id="customer_date_range" name="date_range">
                                                        <option value="this_month" selected>This Month</option>
                                                        <option value="last_month">Last Month</option>
                                                        <option value="this_year">This Year</option>
                                                        <option value="last_year">Last Year</option>
                                                        <option value="all_time">All Time</option>
                                                        <option value="custom">Custom Range</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6" id="customer_custom_date_range" style="display: none;">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="customer_start_date" class="form-label">Start Date</label>
                                                            <input type="date" class="form-control" id="customer_start_date" name="start_date">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="customer_end_date" class="form-label">End Date</label>
                                                            <input type="date" class="form-control" id="customer_end_date" name="end_date">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="customer_status" class="form-label">Customer Status</label>
                                                    <select class="form-select" id="customer_status" name="customer_status">
                                                        <option value="all" selected>All Statuses</option>
                                                        <option value="active">Active</option>
                                                        <option value="inactive">Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="customer_sort_by" class="form-label">Sort By</label>
                                                    <select class="form-select" id="customer_sort_by" name="sort_by">
                                                        <option value="bookings" selected>Total Bookings</option>
                                                        <option value="spending">Total Spending</option>
                                                        <option value="date_joined">Date Joined</option>
                                                        <option value="last_booking">Last Booking Date</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="customer_report_type" class="form-label">Report Type</label>
                                                    <select class="form-select" id="customer_report_type" name="report_type">
                                                        <option value="activity" selected>Activity</option>
                                                        <option value="demographics">Demographics</option>
                                                        <option value="retention">Retention</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="customer_limit" class="form-label">Limit Results</label>
                                                    <select class="form-select" id="customer_limit" name="limit">
                                                        <option value="10" selected>Top 10</option>
                                                        <option value="20">Top 20</option>
                                                        <option value="50">Top 50</option>
                                                        <option value="all">All Customers</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-search me-1"></i> Generate Report
                                                </button>
                                                <button type="button" class="btn btn-success ms-2" id="export_customer_report">
                                                    <i class="fas fa-file-excel me-1"></i> Export to Excel
                                                </button>
                                                <button type="button" class="btn btn-danger ms-2" id="export_customer_report_pdf">
                                                    <i class="fas fa-file-pdf me-1"></i> Export to PDF
                                                </button>
                                            </div>
                                        </div>
                                    <?php echo form_close(); ?>
                                </div>
                            </div>
                            
                            <div id="customer_report_results">
                                <!-- Report results will be loaded here via AJAX -->
                                <div class="text-center py-5">
                                    <i class="fas fa-users fa-4x text-muted mb-3"></i>
                                    <p class="lead">Select filters and generate a report to view results</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Toggle custom date range fields based on date range selection
        $('#date_range').change(function() {
            if ($(this).val() === 'custom') {
                $('#custom_date_range').show();
            } else {
                $('#custom_date_range').hide();
            }
        });
        
        $('#revenue_date_range').change(function() {
            if ($(this).val() === 'custom') {
                $('#revenue_custom_date_range').show();
            } else {
                $('#revenue_custom_date_range').hide();
            }
        });
        
        $('#vendor_date_range').change(function() {
            if ($(this).val() === 'custom') {
                $('#vendor_custom_date_range').show();
            } else {
                $('#vendor_custom_date_range').hide();
            }
        });
        
        $('#vehicle_date_range').change(function() {
            if ($(this).val() === 'custom') {
                $('#vehicle_custom_date_range').show();
            } else {
                $('#vehicle_custom_date_range').hide();
            }
        });
        
        $('#customer_date_range').change(function() {
            if ($(this).val() === 'custom') {
                $('#customer_custom_date_range').show();
            } else {
                $('#customer_custom_date_range').hide();
            }
        });
        
        // Handle form submissions via AJAX
        $('#booking_report_form').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var results = $('#booking_report_results');
            
            results.html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-3x text-primary mb-3"></i><p class="lead">Generating report...</p></div>');
            
            $.ajax({
                url: form.attr('action'),
                type: 'GET',
                data: form.serialize(),
                success: function(response) {
                    results.html(response);
                    initCharts();
                },
                error: function() {
                    results.html('<div class="alert alert-danger">Error generating report. Please try again.</div>');
                }
            });
        });
        
        $('#revenue_report_form').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var results = $('#revenue_report_results');
            
            results.html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-3x text-primary mb-3"></i><p class="lead">Generating report...</p></div>');
            
            $.ajax({
                url: form.attr('action'),
                type: 'GET',
                data: form.serialize(),
                success: function(response) {
                    results.html(response);
                    initCharts();
                },
                error: function() {
                    results.html('<div class="alert alert-danger">Error generating report. Please try again.</div>');
                }
            });
        });
        
        $('#vendor_report_form').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var results = $('#vendor_report_results');
            
            results.html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-3x text-primary mb-3"></i><p class="lead">Generating report...</p></div>');
            
            $.ajax({
                url: form.attr('action'),
                type: 'GET',
                data: form.serialize(),
                success: function(response) {
                    results.html(response);
                    initCharts();
                },
                error: function() {
                    results.html('<div class="alert alert-danger">Error generating report. Please try again.</div>');
                }
            });
        });
        
        $('#vehicle_report_form').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var results = $('#vehicle_report_results');
            
            results.html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-3x text-primary mb-3"></i><p class="lead">Generating report...</p></div>');
            
            $.ajax({
                url: form.attr('action'),
                type: 'GET',
                data: form.serialize(),
                success: function(response) {
                    results.html(response);
                    initCharts();
                },
                error: function() {
                    results.html('<div class="alert alert-danger">Error generating report. Please try again.</div>');
                }
            });
        });
        
        $('#customer_report_form').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var results = $('#customer_report_results');
            
            results.html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-3x text-primary mb-3"></i><p class="lead">Generating report...</p></div>');
            
            $.ajax({
                url: form.attr('action'),
                type: 'GET',
                data: form.serialize(),
                success: function(response) {
                    results.html(response);
                    initCharts();
                },
                error: function() {
                    results.html('<div class="alert alert-danger">Error generating report. Please try again.</div>');
                }
            });
        });
        
        // Export to Excel functionality
        $('#export_booking_report').click(function() {
            var form = $('#booking_report_form');
            window.location.href = '<?php echo base_url("admin/export_report/booking/excel"); ?>?' + form.serialize();
        });
        
        $('#export_revenue_report').click(function() {
            var form = $('#revenue_report_form');
            window.location.href = '<?php echo base_url("admin/export_report/revenue/excel"); ?>?' + form.serialize();
        });
        
        $('#export_vendor_report').click(function() {
            var form = $('#vendor_report_form');
            window.location.href = '<?php echo base_url("admin/export_report/vendor/excel"); ?>?' + form.serialize();
        });
        
        $('#export_vehicle_report').click(function() {
            var form = $('#vehicle_report_form');
            window.location.href = '<?php echo base_url("admin/export_report/vehicle/excel"); ?>?' + form.serialize();
        });
        
        $('#export_customer_report').click(function() {
            var form = $('#customer_report_form');
            window.location.href = '<?php echo base_url("admin/export_report/customer/excel"); ?>?' + form.serialize();
        });
        
        // Export to PDF functionality
        $('#export_booking_report_pdf').click(function() {
            var form = $('#booking_report_form');
            window.location.href = '<?php echo base_url("admin/export_report/booking/pdf"); ?>?' + form.serialize();
        });
        
        $('#export_revenue_report_pdf').click(function() {
            var form = $('#revenue_report_form');
            window.location.href = '<?php echo base_url("admin/export_report/revenue/pdf"); ?>?' + form.serialize();
        });
        
        $('#export_vendor_report_pdf').click(function() {
            var form = $('#vendor_report_form');
            window.location.href = '<?php echo base_url("admin/export_report/vendor/pdf"); ?>?' + form.serialize();
        });
        
        $('#export_vehicle_report_pdf').click(function() {
            var form = $('#vehicle_report_form');
            window.location.href = '<?php echo base_url("admin/export_report/vehicle/pdf"); ?>?' + form.serialize();
        });
        
        $('#export_customer_report_pdf').click(function() {
            var form = $('#customer_report_form');
            window.location.href = '<?php echo base_url("admin/export_report/customer/pdf"); ?>?' + form.serialize();
        });
        
        // Function to initialize charts after AJAX load
        function initCharts() {
            if (typeof Chart !== 'undefined') {
                // Initialize any charts that might be in the loaded content
                if ($('#bookingChart').length) {
                    // Chart initialization code will be here
                }
                
                if ($('#revenueChart').length) {
                    // Chart initialization code will be here
                }
                
                // Add more chart initializations as needed
            }
        }
    });
</script>