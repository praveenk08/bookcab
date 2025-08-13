<?php
/**
 * Booking Report Template
 * This template is loaded via AJAX when a booking report is generated
 */
?>

<?php if (empty($report_data)): ?>
<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i> No booking data found for the selected criteria.
</div>
<?php else: ?>

<?php if (isset($report_data['summary'])): ?>
<!-- Summary Report -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Booking Summary Report</h5>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total Bookings</h6>
                        <h2 class="mb-0"><?php echo number_format($report_data['totals']->total_bookings); ?></h2>
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
                <canvas id="bookingReportChart" height="300"></canvas>
            </div>
        </div>
        
        <!-- Summary Table -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th><?php echo isset($filters['group_by']) ? ucfirst(str_replace('_', ' ', $filters['group_by'])) : 'Date'; ?></th>
                        <th class="text-center">Bookings</th>
                        <th class="text-end">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($report_data['summary'] as $item): ?>
                    <tr>
                        <td><?php echo $item->group_label; ?></td>
                        <td class="text-center"><?php echo number_format($item->total_bookings); ?></td>
                        <td class="text-end"><?php echo number_format($item->total_amount, 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="table-primary">
                        <th>Total</th>
                        <th class="text-center"><?php echo number_format($report_data['totals']->total_bookings); ?></th>
                        <th class="text-end"><?php echo number_format($report_data['totals']->total_amount, 2); ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script>
// Initialize chart
var ctx = document.getElementById('bookingReportChart').getContext('2d');
var labels = [];
var bookingData = [];
var revenueData = [];

<?php foreach ($report_data['summary'] as $item): ?>
    labels.push('<?php echo addslashes($item->group_label); ?>');
    bookingData.push(<?php echo $item->total_bookings; ?>);
    revenueData.push(<?php echo $item->total_amount; ?>);
<?php endforeach; ?>

var bookingChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Number of Bookings',
            data: bookingData,
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
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
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Booking Detailed Report</h5>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total Bookings</h6>
                        <h2 class="mb-0"><?php echo number_format($report_data['totals']->total_bookings); ?></h2>
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
                        <th>Booking ID</th>
                        <th>Customer</th>
                        <th>Vendor</th>
                        <th>Vehicle</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th class="text-end">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($report_data['details'] as $booking): ?>
                    <tr>
                        <td><?php echo $booking->booking_code; ?></td>
                        <td><?php echo $booking->customer_name; ?></td>
                        <td><?php echo $booking->vendor_name; ?></td>
                        <td><?php echo $booking->vehicle_name; ?></td>
                        <td><?php echo date('M d, Y', strtotime($booking->created_at)); ?></td>
                        <td>
                            <?php if ($booking->status == 'pending'): ?>
                                <span class="badge bg-warning">Pending</span>
                            <?php elseif ($booking->status == 'confirmed'): ?>
                                <span class="badge bg-primary">Confirmed</span>
                            <?php elseif ($booking->status == 'completed'): ?>
                                <span class="badge bg-success">Completed</span>
                            <?php elseif ($booking->status == 'cancelled'): ?>
                                <span class="badge bg-danger">Cancelled</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($booking->payment_status == 'paid'): ?>
                                <span class="badge bg-success">Paid</span>
                            <?php elseif ($booking->payment_status == 'pending'): ?>
                                <span class="badge bg-warning">Pending</span>
                            <?php elseif ($booking->payment_status == 'failed'): ?>
                                <span class="badge bg-danger">Failed</span>
                            <?php endif; ?>
                            <small class="d-block"><?php echo ucfirst($booking->payment_method); ?></small>
                        </td>
                        <td class="text-end"><?php echo number_format($booking->total_amount, 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="table-primary">
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