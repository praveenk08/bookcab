<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Search Vehicles</h1>
            <p class="lead">Find the perfect vehicles for your wedding or event</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Search Filters</h5>
                </div>
                <div class="card-body">
                    <?php echo form_open('vehicle/search', ['method' => 'post', 'id' => 'search-form']); ?>
                        <div class="mb-3">
                            <label for="date_from" class="form-label">From Date</label>
                            <input type="date" class="form-control datepicker" id="date_from" name="date_from" value="<?php echo set_value('date_from', date('Y-m-d')); ?>" min="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="date_to" class="form-label">To Date</label>
                            <input type="date" class="form-control datepicker" id="date_to" name="date_to" value="<?php echo set_value('date_to', date('Y-m-d', strtotime('+1 day'))); ?>" min="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Vehicle Type</label>
                            <select class="form-select" id="type" name="type">
                                <option value="">All Types</option>
                                <option value="SUV" <?php echo set_select('type', 'SUV'); ?>>SUV</option>
                                <option value="Sedan" <?php echo set_select('type', 'Sedan'); ?>>Sedan</option>
                                <option value="Bus" <?php echo set_select('type', 'Bus'); ?>>Bus</option>
                                <option value="Bike" <?php echo set_select('type', 'Bike'); ?>>Bike</option>
                                <option value="E-Rickshaw" <?php echo set_select('type', 'E-Rickshaw'); ?>>E-Rickshaw</option>
                                <option value="Luxury" <?php echo set_select('type', 'Luxury'); ?>>Luxury</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="capacity" class="form-label">Minimum Capacity</label>
                            <input type="number" class="form-control" id="capacity" name="capacity" value="<?php echo set_value('capacity', ''); ?>" min="1">
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity Needed</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo set_value('quantity', '1'); ?>" min="1" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Search Vehicles</button>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Available Vehicle Types</h5>
                </div>
                <?php $image ='https://www.shutterstock.com/shutterstock/photos/2366856295/display_1500/stock-vector-flat-car-picture-placeholder-symbol-for-the-app-or-website-no-photo-thumbnail-graphic-element-for-2366856295.jpg'; ?>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <img src="<?php echo $image;?>" class="card-img-top" alt="SUV">
                                <div class="card-body text-center">
                                    <h5 class="card-title">SUV</h5>
                                    <p class="card-text">Spacious vehicles perfect for family travel</p>
                                    <button class="btn btn-sm btn-outline-primary" onclick="document.getElementById('type').value='SUV'; document.getElementById('search-form').submit();">Search SUVs</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <img src="<?php echo $image;?>" class="card-img-top" alt="Sedan">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Sedan</h5>
                                    <p class="card-text">Comfortable cars for small groups</p>
                                    <button class="btn btn-sm btn-outline-primary" onclick="document.getElementById('type').value='Sedan'; document.getElementById('search-form').submit();">Search Sedans</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <img src="<?php echo $image;?>" class="card-img-top" alt="Bus">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Bus</h5>
                                    <p class="card-text">Large vehicles for group transportation</p>
                                    <button class="btn btn-sm btn-outline-primary" onclick="document.getElementById('type').value='Bus'; document.getElementById('search-form').submit();">Search Buses</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <img src="<?php echo 'https://media.istockphoto.com/id/1412904827/photo/riding-a-motorcycle.jpg?s=2048x2048&w=is&k=20&c=AP2ReU0CuHQ1Py_KkoH5Er9YubdNVbKngrDp5Cgp14E=';?> class="card-img-top" alt="Bike">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Bike</h5>
                                    <p class="card-text">Two-wheelers for quick transportation</p>
                                    <button class="btn btn-sm btn-outline-primary" onclick="document.getElementById('type').value='Bike'; document.getElementById('search-form').submit();">Search Bikes</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <img src="<?php echo "https://media.istockphoto.com/id/176863150/photo/solar-powered-tuctuc-at-the-beach.jpg?s=2048x2048&w=is&k=20&c=ww3sDTEUuSgbz8fjA6xbJwdwLP2cWAAy2x4d7LnwIPQ=";?>" class="card-img-top" alt="E-Rickshaw">
                                <div class="card-body text-center">
                                    <h5 class="card-title">E-Rickshaw</h5>
                                    <p class="card-text">Eco-friendly transportation option</p>
                                    <button class="btn btn-sm btn-outline-primary" onclick="document.getElementById('type').value='E-Rickshaw'; document.getElementById('search-form').submit();">Search E-Rickshaws</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <img src="<?php echo $image;?>" class="card-img-top" alt="Luxury">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Luxury</h5>
                                    <p class="card-text">Premium vehicles for special occasions</p>
                                    <button class="btn btn-sm btn-outline-primary" onclick="document.getElementById('type').value='Luxury'; document.getElementById('search-form').submit();">Search Luxury Cars</button>
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
    // Validate date range
    document.getElementById('search-form').addEventListener('submit', function(e) {
        var dateFrom = new Date(document.getElementById('date_from').value);
        var dateTo = new Date(document.getElementById('date_to').value);
        
        if (dateTo < dateFrom) {
            e.preventDefault();
            alert('To Date must be after From Date');
        }
    });
</script>