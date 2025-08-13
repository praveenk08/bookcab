<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Manage Vehicle Availability</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('vendor/dashboard'); ?>">Vendor Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('vendor/manage_vehicles'); ?>">Manage Vehicles</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Manage Availability</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4 mb-md-0">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Vehicle Details</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <?php 
                            $images = json_decode($vehicle->images, true);
                            if (!empty($images)) {
                                echo '<img src="' . base_url('uploads/vehicles/' . $images[0]) . '" alt="' . $vehicle->title . '" class="img-fluid rounded" style="max-height: 200px;">';
                            } else {
                                echo '<div class="bg-light text-center p-5"><i class="fas fa-car fa-3x text-muted"></i></div>';
                            }
                        ?>
                    </div>
                    
                    <h5 class="card-title"><?php echo $vehicle->title; ?></h5>
                    <p class="card-text">
                        <span class="badge bg-info"><?php echo $vehicle->type; ?></span>
                        <span class="badge bg-secondary"><?php echo $vehicle->capacity; ?> persons</span>
                    </p>
                    
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Price per day
                            <span>₹<?php echo number_format($vehicle->fixed_price); ?></span>
                        </li>
                        <?php if ($vehicle->fuel_charge > 0): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Fuel charge
                                <span>₹<?php echo number_format($vehicle->fuel_charge); ?></span>
                            </li>
                        <?php endif; ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total units
                            <span><?php echo $vehicle->quantity; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Status
                            <?php if ($vehicle->is_active): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactive</span>
                            <?php endif; ?>
                        </li>
                    </ul>
                    
                    <div class="d-grid gap-2">
                        <a href="<?php echo base_url('vendor/edit_vehicle/' . $vehicle->id); ?>" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-2"></i> Edit Vehicle
                        </a>
                        <a href="<?php echo base_url('vendor/manage_vehicles'); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Vehicles
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Set Availability</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <p class="mb-0"><i class="fas fa-info-circle me-2"></i> Set the number of units available for each date. By default, all units are available unless specified otherwise.</p>
                    </div>
                    
                    <?php echo form_open('vendor/update_availability/' . $vehicle->id); ?>
                        <div class="row mb-3">
                            <div class="col-md-5">
                                <label for="date_from" class="form-label">From Date</label>
                                <input type="date" class="form-control" id="date_from" name="date_from" required min="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-md-5">
                                <label for="date_to" class="form-label">To Date</label>
                                <input type="date" class="form-control" id="date_to" name="date_to" required min="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-md-2">
                                <label for="available_quantity" class="form-label">Units</label>
                                <input type="number" class="form-control" id="available_quantity" name="available_quantity" min="0" max="<?php echo $vehicle->quantity; ?>" value="<?php echo $vehicle->quantity; ?>" required>
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Update Availability
                            </button>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Availability Calendar</h5>
                    <button type="button" class="btn btn-sm btn-light" id="refreshCalendar">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
                <div class="card-body">
                    <div id="availability-calendar"></div>
                    
                    <div class="mt-3">
                        <h6>Legend:</h6>
                        <div class="d-flex flex-wrap">
                            <div class="me-3 mb-2">
                                <span class="badge bg-success">&nbsp;</span> All units available
                            </div>
                            <div class="me-3 mb-2">
                                <span class="badge bg-warning">&nbsp;</span> Some units available
                            </div>
                            <div class="me-3 mb-2">
                                <span class="badge bg-danger">&nbsp;</span> No units available
                            </div>
                            <div class="me-3 mb-2">
                                <span class="badge bg-info">&nbsp;</span> Bookings exist
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Custom Availability Settings</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Available Units</th>
                                    <th>Booked Units</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($availability)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No custom availability settings found. By default, all units are available.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($availability as $avail): ?>
                                        <tr>
                                            <td><?php echo date('M d, Y', strtotime($avail->date)); ?></td>
                                            <td>
                                                <?php echo $avail->quantity; ?> of <?php echo $vehicle->quantity; ?> units
                                                <?php 
                                                    $percentage = ($avail->quantity / $vehicle->quantity) * 100;
                                                    $bg_class = $percentage == 0 ? 'bg-danger' : ($percentage < 50 ? 'bg-warning' : 'bg-success');
                                                ?>
                                                <div class="progress mt-1" style="height: 5px;">
                                                    <div class="progress-bar <?php echo $bg_class; ?>" role="progressbar" style="width: <?php echo $percentage; ?>%" aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php echo $avail->booked_units ?: 0; ?> units
                                                <?php if ($avail->booked_units > 0): ?>
                                                    <span class="badge bg-info ms-1">Bookings exist</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary edit-availability" 
                                                            data-date="<?php echo $avail->date; ?>" 
                                                            data-quantity="<?php echo $avail->quantity; ?>">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                    <?php if ($avail->booked_units == 0): ?>
                                                        <a href="<?php echo base_url('vendor/delete_availability/' . $vehicle->id . '/' . $avail->date); ?>" 
                                                           class="btn btn-sm btn-outline-danger" 
                                                           onclick="return confirm('Are you sure you want to delete this availability setting?')">
                                                            <i class="fas fa-trash-alt"></i> Delete
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php if (isset($pagination)): ?>
                        <div class="d-flex justify-content-center mt-4">
                            <?php echo $pagination; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Availability Modal -->
<div class="modal fade" id="editAvailabilityModal" tabindex="-1" aria-labelledby="editAvailabilityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAvailabilityModalLabel">Edit Availability</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?php echo form_open('vendor/update_single_availability/' . $vehicle->id); ?>
                <div class="modal-body">
                    <input type="hidden" name="date" id="edit_date">
                    
                    <div class="mb-3">
                        <label for="edit_date_display" class="form-label">Date</label>
                        <input type="text" class="form-control" id="edit_date_display" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_quantity" class="form-label">Available Units</label>
                        <input type="number" class="form-control" id="edit_quantity" name="quantity" min="0" max="<?php echo $vehicle->quantity; ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<style>
    #availability-calendar {
        min-height: 400px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize date inputs with flatpickr if available
        if (typeof flatpickr !== 'undefined') {
            flatpickr('#date_from', {
                minDate: 'today',
                onChange: function(selectedDates, dateStr, instance) {
                    document.querySelector('#date_to')._flatpickr.set('minDate', dateStr);
                }
            });
            
            flatpickr('#date_to', {
                minDate: 'today'
            });
        }
        
        // Edit availability modal functionality
        const editButtons = document.querySelectorAll('.edit-availability');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const date = this.getAttribute('data-date');
                const quantity = this.getAttribute('data-quantity');
                
                document.getElementById('edit_date').value = date;
                document.getElementById('edit_date_display').value = new Date(date).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                document.getElementById('edit_quantity').value = quantity;
                
                const modal = new bootstrap.Modal(document.getElementById('editAvailabilityModal'));
                modal.show();
            });
        });
        
        // This is a placeholder for a calendar implementation
        // In a real application, you would use a library like FullCalendar.js
        // and populate it with availability data from your backend
        const calendarEl = document.getElementById('availability-calendar');
        calendarEl.innerHTML = '<div class="alert alert-info">Calendar functionality would be implemented here using a library like FullCalendar.js. The calendar would show vehicle availability for each date with color coding based on available units.</div>';
        
        // Refresh calendar button (placeholder)
        document.getElementById('refreshCalendar').addEventListener('click', function() {
            // In a real implementation, this would refresh the calendar data
            alert('Calendar refreshed!');
        });
    });
</script>