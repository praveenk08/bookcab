<?php
/**
 * Vehicle Report Template
 * This template is loaded via AJAX when a vehicle report is generated
 */
?>

<?php if (empty($report_data) || empty($report_data['vehicles'])): ?>
<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i> No vehicle data found for the selected criteria.
</div>
<?php else: ?>

<!-- Vehicle Report -->
<div class="card mb-4">
    <div class="card-header bg-secondary text-white">
        <h5 class="mb-0">Vehicle Performance Report</h5>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total Vehicles</h6>
                        <h2 class="mb-0"><?php echo number_format($report_data['totals']->total_vehicles); ?></h2>
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
                        <h6 class="text-muted">Total Revenue</h6>
                        <h2 class="mb-0"><?php echo number_format($report_data['totals']->total_revenue, 2); ?></h2>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Chart -->
        <div class="row mb-4">
            <div class="col-md-10 offset-md-1">
                <canvas id="vehicleReportChart" height="300"></canvas>
            </div>
        </div>
        
        <!-- Vehicle Table -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Vehicle</th>
                        <th>Vendor</th>
                        <th>Type</th>
                        <th class="text-center">Price/Day</th>
                        <th class="text-center">Bookings</th>
                        <th class="text-center">Rating</th>
                        <th class="text-end">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($report_data['vehicles'] as $vehicle): ?>
                    <tr>
                        <td>
                            <a href="<?php echo base_url('admin/view_vehicle/' . $vehicle->id); ?>">
                                <?php echo $vehicle->title; ?>
                            </a>
                            <small class="d-block text-muted"><?php echo $vehicle->model . ' (' . $vehicle->year . ')'; ?></small>
                        </td>
                        <td><?php echo $vehicle->vendor_name; ?></td>
                        <td><?php echo ucfirst($vehicle->type); ?></td>
                        <td class="text-center"><?php echo number_format($vehicle->price_per_day, 2); ?></td>
                        <td class="text-center"><?php echo number_format($vehicle->bookings_count); ?></td>
                        <td class="text-center">
                            <?php if ($vehicle->avg_rating): ?>
                                <div class="d-flex align-items-center justify-content-center">
                                    <span class="me-1"><?php echo number_format($vehicle->avg_rating, 1); ?></span>
                                    <i class="fas fa-star text-warning"></i>
                                </div>
                            <?php else: ?>
                                <span class="text-muted">N/A</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end"><?php echo number_format($vehicle->revenue, 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Initialize chart
var ctx = document.getElementById('vehicleReportChart').getContext('2d');
var labels = [];
var bookingsData = [];
var revenueData = [];

<?php 
// Limit to top 10 for chart readability
$chartVehicles = array_slice($report_data['vehicles'], 0, 10);
foreach ($chartVehicles as $vehicle): ?>
    labels.push('<?php echo addslashes($vehicle->title); ?>');
    bookingsData.push(<?php echo $vehicle->bookings_count ?: 0; ?>);
    revenueData.push(<?php echo $vehicle->revenue ?: 0; ?>);
<?php endforeach; ?>

var vehicleChart = new Chart(ctx, {
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
            label: 'Revenue',
            data: revenueData,
            type: 'horizontalBar',
            backgroundColor: 'rgba(40, 167, 69, 0.5)',
            borderColor: 'rgba(40, 167, 69, 1)',
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