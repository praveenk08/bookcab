<?php
/**
 * Vendor Report Template
 * This template is loaded via AJAX when a vendor report is generated
 */
?>

<?php if (empty($report_data) || empty($report_data['vendors'])): ?>
<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i> No vendor data found for the selected criteria.
</div>
<?php else: ?>

<!-- Vendor Performance Report -->
<div class="card mb-4">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">Vendor <?php echo ucfirst($filters['report_type']); ?> Report</h5>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total Vendors</h6>
                        <h2 class="mb-0"><?php echo number_format($report_data['totals']->total_vendors); ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total Vehicles</h6>
                        <h2 class="mb-0"><?php echo number_format($report_data['totals']->total_vehicles); ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total Bookings</h6>
                        <h2 class="mb-0"><?php echo number_format($report_data['totals']->total_bookings); ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total Revenue</h6>
                        <h2 class="mb-0"><?php echo number_format($report_data['totals']->total_revenue, 2); ?></h2>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if ($filters['report_type'] == 'performance' || $filters['report_type'] == 'comparison'): ?>
        <!-- Chart -->
        <div class="row mb-4">
            <div class="col-md-10 offset-md-1">
                <canvas id="vendorReportChart" height="300"></canvas>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Vendor Table -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Vendor</th>
                        <th>Owner</th>
                        <th>Contact</th>
                        <th class="text-center">Vehicles</th>
                        <th class="text-center">Bookings</th>
                        <th class="text-center">Rating</th>
                        <th class="text-end">Revenue</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($report_data['vendors'] as $vendor): ?>
                    <tr>
                        <td>
                            <a href="<?php echo base_url('admin/view_vendor/' . $vendor->id); ?>">
                                <?php echo $vendor->business_name; ?>
                            </a>
                        </td>
                        <td><?php echo $vendor->owner_name; ?></td>
                        <td>
                            <?php echo $vendor->email; ?><br>
                            <small><?php echo $vendor->phone; ?></small>
                        </td>
                        <td class="text-center"><?php echo number_format($vendor->vehicle_count); ?></td>
                        <td class="text-center"><?php echo number_format($vendor->total_bookings); ?></td>
                        <td class="text-center">
                            <?php if ($vendor->avg_rating): ?>
                                <div class="d-flex align-items-center justify-content-center">
                                    <span class="me-1"><?php echo number_format($vendor->avg_rating, 1); ?></span>
                                    <i class="fas fa-star text-warning"></i>
                                </div>
                            <?php else: ?>
                                <span class="text-muted">N/A</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end"><?php echo number_format($vendor->total_revenue, 2); ?></td>
                        <td>
                            <?php if ($vendor->status == 'pending'): ?>
                                <span class="badge bg-warning">Pending</span>
                            <?php elseif ($vendor->status == 'approved'): ?>
                                <span class="badge bg-success">Approved</span>
                            <?php elseif ($vendor->status == 'rejected'): ?>
                                <span class="badge bg-danger">Rejected</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if ($filters['report_type'] == 'performance' || $filters['report_type'] == 'comparison'): ?>
<script>
// Initialize chart
var ctx = document.getElementById('vendorReportChart').getContext('2d');
var labels = [];
var bookingsData = [];
var revenueData = [];

<?php foreach ($report_data['vendors'] as $vendor): ?>
    labels.push('<?php echo addslashes($vendor->business_name); ?>');
    bookingsData.push(<?php echo $vendor->total_bookings ?: 0; ?>);
    revenueData.push(<?php echo $vendor->total_revenue ?: 0; ?>);
<?php endforeach; ?>

var vendorChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Bookings',
            data: bookingsData,
            backgroundColor: 'rgba(23, 162, 184, 0.5)',
            borderColor: 'rgba(23, 162, 184, 1)',
            borderWidth: 1,
            yAxisID: 'y'
        }, {
            label: 'Revenue',
            data: revenueData,
            type: 'line',
            fill: false,
            backgroundColor: 'rgba(220, 53, 69, 0.5)',
            borderColor: 'rgba(220, 53, 69, 1)',
            borderWidth: 2,
            yAxisID: 'y1'
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                position: 'left',
                title: {
                    display: true,
                    text: 'Bookings'
                }
            },
            y1: {
                beginAtZero: true,
                position: 'right',
                title: {
                    display: true,
                    text: 'Revenue'
                },
                grid: {
                    drawOnChartArea: false
                }
            }
        }
    }
});
</script>
<?php endif; ?>

<?php if ($filters['report_type'] == 'growth'): ?>
<!-- Growth Analysis -->
<div class="card mb-4">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">Vendor Growth Analysis</h5>
    </div>
    <div class="card-body">
        <p class="lead">This report shows the growth trends for vendors over the selected time period.</p>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> Growth analysis requires historical data. For more detailed growth analysis, please select a longer date range.
        </div>
    </div>
</div>
<?php endif; ?>

<?php endif; ?>