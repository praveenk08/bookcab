<?php
/**
 * Revenue Report Template
 * This template is loaded via AJAX when a revenue report is generated
 */
?>

<?php if (empty($report_data)): ?>
<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i> No revenue data found for the selected criteria.
</div>
<?php else: ?>

<?php if (isset($report_data['summary'])): ?>
<!-- Summary Report -->
<div class="card mb-4">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0">Revenue Summary Report</h5>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total Payments</h6>
                        <h2 class="mb-0"><?php echo number_format($report_data['totals']->total_payments); ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total Revenue</h6>
                        <h2 class="mb-0"><?php echo number_format($report_data['totals']->total_amount, 2); ?></h2>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Chart -->
        <div class="row mb-4">
            <div class="col-md-8 offset-md-2">
                <canvas id="revenueReportChart" height="300"></canvas>
            </div>
        </div>
        
        <!-- Summary Table -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th><?php echo isset($filters['group_by']) ? ucfirst(str_replace('_', ' ', $filters['group_by'])) : 'Date'; ?></th>
                        <th class="text-center">Payments</th>
                        <th class="text-end">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($report_data['summary'] as $item): ?>
                    <tr>
                        <td><?php echo $item->group_label; ?></td>
                        <td class="text-center"><?php echo number_format($item->total_payments); ?></td>
                        <td class="text-end"><?php echo number_format($item->total_amount, 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="table-success">
                        <th>Total</th>
                        <th class="text-center"><?php echo number_format($report_data['totals']->total_payments); ?></th>
                        <th class="text-end"><?php echo number_format($report_data['totals']->total_amount, 2); ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script>
// Initialize chart
var ctx = document.getElementById('revenueReportChart').getContext('2d');
var labels = [];
var revenueData = [];

<?php foreach ($report_data['summary'] as $item): ?>
    labels.push('<?php echo addslashes($item->group_label); ?>');
    revenueData.push(<?php echo $item->total_amount; ?>);
<?php endforeach; ?>

var revenueChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Revenue',
            data: revenueData,
            backgroundColor: 'rgba(40, 167, 69, 0.2)',
            borderColor: 'rgba(40, 167, 69, 1)',
            borderWidth: 2,
            tension: 0.1,
            fill: true
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

<?php else: ?>
<!-- Detailed Report -->
<div class="card mb-4">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0">Revenue Detailed Report</h5>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total Payments</h6>
                        <h2 class="mb-0"><?php echo number_format($report_data['totals']->total_payments); ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total Revenue</h6>
                        <h2 class="mb-0"><?php echo number_format($report_data['totals']->total_amount, 2); ?></h2>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Detailed Table -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>Booking</th>
                        <th>Customer</th>
                        <th>Vendor</th>
                        <th>Date</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th class="text-end">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($report_data['details'] as $payment): ?>
                    <tr>
                        <td><?php echo $payment->id; ?></td>
                        <td><?php echo $payment->booking_code; ?></td>
                        <td><?php echo $payment->customer_name; ?></td>
                        <td><?php echo $payment->vendor_name; ?></td>
                        <td><?php echo date('M d, Y', strtotime($payment->created_at)); ?></td>
                        <td><?php echo ucfirst($payment->payment_method); ?></td>
                        <td>
                            <?php if ($payment->status == 'paid'): ?>
                                <span class="badge bg-success">Paid</span>
                            <?php elseif ($payment->status == 'pending'): ?>
                                <span class="badge bg-warning">Pending</span>
                            <?php elseif ($payment->status == 'failed'): ?>
                                <span class="badge bg-danger">Failed</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end"><?php echo number_format($payment->amount, 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="table-success">
                        <th colspan="7">Total</th>
                        <th class="text-end"><?php echo number_format($report_data['totals']->total_amount, 2); ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<?php endif; ?>