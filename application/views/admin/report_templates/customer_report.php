<?php
/**
 * Customer Report Template
 * This template is loaded via AJAX when a customer report is generated
 */
?>

<?php if (empty($report_data) || empty($report_data['customers'])): ?>
<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i> No customer data found for the selected criteria.
</div>
<?php else: ?>

<!-- Customer Report -->
<div class="card mb-4">
    <div class="card-header bg-secondary text-white">
        <h5 class="mb-0">Customer Analysis Report</h5>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total Customers</h6>
                        <h2 class="mb-0"><?php echo number_format($report_data['totals']->total_customers); ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total Bookings</h6>
                        <h2 class="mb-0"><?php echo number_format($report_data['totals']->total_bookings); ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total Spending</h6>
                        <h2 class="mb-0"><?php echo number_format($report_data['totals']->total_spending, 2); ?></h2>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Chart -->
        <div class="row mb-4">
            <div class="col-md-10 offset-md-1">
                <canvas id="customerReportChart" height="300"></canvas>
            </div>
        </div>
        
        <!-- Customer Table -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th class="text-center">Joined</th>
                        <th class="text-center">Bookings</th>
                        <th class="text-center">Last Booking</th>
                        <th class="text-end">Total Spent</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($report_data['customers'] as $customer): ?>
                    <tr>
                        <td>
                            <a href="<?php echo base_url('admin/view_user/' . $customer->id); ?>">
                                <?php echo $customer->name; ?>
                            </a>
                        </td>
                        <td><?php echo $customer->email; ?></td>
                        <td><?php echo $customer->phone ?: 'N/A'; ?></td>
                        <td class="text-center"><?php echo date('M d, Y', strtotime($customer->created_at)); ?></td>
                        <td class="text-center"><?php echo number_format($customer->bookings_count); ?></td>
                        <td class="text-center">
                            <?php if ($customer->last_booking): ?>
                                <?php echo date('M d, Y', strtotime($customer->last_booking)); ?>
                            <?php else: ?>
                                <span class="text-muted">N/A</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end"><?php echo number_format($customer->total_spent, 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Customer Segments -->
        <?php if (count($report_data['customers']) > 5): ?>
        <div class="mt-4">
            <h5>Customer Segments</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Top Spenders</div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <?php 
                                $topSpenders = array_slice($report_data['customers'], 0, 5);
                                foreach ($topSpenders as $customer): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><?php echo $customer->name; ?></span>
                                    <span class="badge bg-success rounded-pill">
                                        <?php echo number_format($customer->total_spent, 2); ?>
                                    </span>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Most Frequent Bookers</div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <?php 
                                // Sort by bookings count
                                $frequentBookers = $report_data['customers'];
                                usort($frequentBookers, function($a, $b) {
                                    return $b->bookings_count - $a->bookings_count;
                                });
                                $frequentBookers = array_slice($frequentBookers, 0, 5);
                                
                                foreach ($frequentBookers as $customer): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><?php echo $customer->name; ?></span>
                                    <span class="badge bg-primary rounded-pill">
                                        <?php echo number_format($customer->bookings_count); ?> bookings
                                    </span>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Initialize chart
var ctx = document.getElementById('customerReportChart').getContext('2d');
var labels = [];
var bookingsData = [];
var spendingData = [];

<?php 
// Limit to top 10 for chart readability
$chartCustomers = array_slice($report_data['customers'], 0, 10);
foreach ($chartCustomers as $customer): ?>
    labels.push('<?php echo addslashes($customer->name); ?>');
    bookingsData.push(<?php echo $customer->bookings_count ?: 0; ?>);
    spendingData.push(<?php echo $customer->total_spent ?: 0; ?>);
<?php endforeach; ?>

var customerChart = new Chart(ctx, {
    type: 'horizontalBar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Bookings',
            data: bookingsData,
            backgroundColor: 'rgba(108, 117, 125, 0.5)',
            borderColor: 'rgba(108, 117, 125, 1)',
            borderWidth: 1,
            xAxisID: 'x'
        }, {
            label: 'Spending',
            data: spendingData,
            type: 'horizontalBar',
            backgroundColor: 'rgba(0, 123, 255, 0.5)',
            borderColor: 'rgba(0, 123, 255, 1)',
            borderWidth: 1,
            xAxisID: 'x1'
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        scales: {
            x: {
                beginAtZero: true,
                position: 'bottom',
                title: {
                    display: true,
                    text: 'Bookings'
                }
            },
            x1: {
                beginAtZero: true,
                position: 'top',
                title: {
                    display: true,
                    text: 'Spending'
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