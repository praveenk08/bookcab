<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendor extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['user_model', 'vendor_model', 'vehicle_model', 'driver_model']);
        $this->load->helper(['form', 'url', 'security', 'file']);
        $this->load->library(['form_validation', 'session', 'upload']);
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        
        // Check if user is a vendor
        if ($this->session->userdata('role') !== 'vendor') {
            $this->session->set_flashdata('error', 'Access denied. You must be a vendor to access this page.');
            redirect('auth');
        }
    }

    public function index() {
        redirect('vendor/dashboard');
    }

    public function dashboard() {
        $vendor_id = $this->vendor_model->get_vendor_id_by_user_id($this->session->userdata('user_id'));
        
        if (!$vendor_id) {
            redirect('vendor/apply');
        }
        
        $data['vendor'] = $this->vendor_model->get_vendor_by_id($vendor_id);
        $data['vehicles'] = $this->vehicle_model->get_vehicles(['vendor_id' => $vendor_id]);
        $data['drivers'] = $this->driver_model->get_drivers(['vendor_id' => $vendor_id]);
        $data['bookings'] = $this->vendor_model->get_vendor_bookings($vendor_id);
        
        // Prepare stats for dashboard
        $data['stats'] = [
            'total_vehicles' => count($data['vehicles']),
            'total_drivers' => count($data['drivers']),
            'total_bookings' => count($data['bookings']),
            'active_bookings' => $this->count_active_bookings($data['bookings']),
            'total_revenue' => $this->calculate_vendor_revenue($vendor_id),
            'pending_bookings' => $this->count_pending_bookings($data['bookings'])
        ];
        
        $this->load->view('templates/header');
        $this->load->view('vendor/dashboard', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Calculate total revenue for a vendor
     * 
     * @param int $vendor_id The vendor ID
     * @return float Total revenue
     */
    private function calculate_vendor_revenue($vendor_id) {
        $this->db->select_sum('amount');
        $this->db->from('payments');
        $this->db->join('booking_items', 'booking_items.booking_id = payments.booking_id');
        $this->db->where('booking_items.vendor_id', $vendor_id);
        $this->db->where('payments.status', 'completed');
        $result = $this->db->get()->row();
        
        return $result->amount ?? 0;
    }
    
    /**
     * Count pending bookings from a list of bookings
     * 
     * @param array $bookings Array of booking objects
     * @return int Number of pending bookings
     */
    private function count_pending_bookings($bookings) {
        $count = 0;
        foreach ($bookings as $booking) {
            if ($booking->status === 'pending') {
                $count++;
            }
        }
        return $count;
    }
    
    private function count_active_bookings($bookings) {
        $count = 0;
        foreach ($bookings as $booking) {
            if ($booking->status === 'confirmed') {
                $count++;
            }
        }
        return $count;
    }
    
    public function apply() {
        // Check if already applied
        $vendor_id = $this->vendor_model->get_vendor_id_by_user_id($this->session->userdata('user_id'));
        
        if ($vendor_id) {
            $vendor = $this->vendor_model->get_vendor_by_id($vendor_id);
            
            if ($vendor->status === 'pending') {
                $this->session->set_flashdata('info', 'Your vendor application is pending approval.');
                redirect('vendor/dashboard');
            } elseif ($vendor->status === 'approved') {
                redirect('vendor/dashboard');
            } elseif ($vendor->status === 'rejected') {
                $this->session->set_flashdata('error', 'Your vendor application was rejected. You can apply again.');
            }
        }
        
        // Form validation rules
        $this->form_validation->set_rules('business_name', 'Business Name', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');
        
        if ($this->form_validation->run() === FALSE) {
            // Load application form
            $this->load->view('templates/header');
            $this->load->view('vendor/apply');
            $this->load->view('templates/footer');
        } else {
            // Upload document
            $config['upload_path'] = './uploads/vendor_docs/';
            $config['allowed_types'] = 'pdf|jpg|jpeg|png';
            $config['max_size'] = 2048; // 2MB
            $config['encrypt_name'] = TRUE;
            
            // Create directory if it doesn't exist
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, TRUE);
            }
            
            $this->upload->initialize($config);
            
            if (!$this->upload->do_upload('document')) {
                $error = $this->upload->display_errors();
                $this->session->set_flashdata('error', $error);
                redirect('vendor/apply');
            } else {
                $upload_data = $this->upload->data();
                $doc_path = 'uploads/vendor_docs/' . $upload_data['file_name'];
                
                // Prepare vendor data
                $vendor_data = [
                    'user_id' => $this->session->userdata('user_id'),
                    'business_name' => $this->input->post('business_name'),
                    'address' => $this->input->post('address'),
                    'doc_path' => $doc_path,
                    'status' => 'pending'
                ];
                
                // Save vendor application
                $vendor_id = $this->vendor_model->add_vendor($vendor_data);
                
                if ($vendor_id) {
                    // Update user role to vendor
                    $this->user_model->update_user($this->session->userdata('user_id'), ['role' => 'vendor']);
                    
                    // Update session data
                    $this->session->set_userdata('role', 'vendor');
                    
                    $this->session->set_flashdata('success', 'Your vendor application has been submitted and is pending approval.');
                    redirect('vendor/dashboard');
                } else {
                    $this->session->set_flashdata('error', 'Failed to submit vendor application. Please try again.');
                    redirect('vendor/apply');
                }
            }
        }
    }

    public function add_vehicle() {
        $vendor_id = $this->vendor_model->get_vendor_id_by_user_id($this->session->userdata('user_id'));
        
        if (!$vendor_id) {
            redirect('vendor/apply');
        }
        
        $vendor = $this->vendor_model->get_vendor_by_id($vendor_id);
        
        if ($vendor->status !== 'approved') {
            $this->session->set_flashdata('error', 'Your vendor account must be approved before adding vehicles.');
            redirect('vendor/dashboard');
        }
        
        // Form validation rules
        $this->form_validation->set_rules('title', 'Vehicle Title', 'trim|required');
        $this->form_validation->set_rules('type', 'Vehicle Type', 'trim|required');
        $this->form_validation->set_rules('capacity', 'Capacity', 'trim|required|numeric');
        $this->form_validation->set_rules('fixed_price', 'Fixed Price', 'trim|required|numeric');
        $this->form_validation->set_rules('fuel_charge', 'Fuel Charge', 'trim|required|numeric');

        if ($this->form_validation->run() === FALSE) {
            // Load add vehicle form
            $this->load->view('templates/header');
            $this->load->view('vendor/add_vehicle');
            $this->load->view('templates/footer');
        } else {
            // Upload images
            $config['upload_path'] = './uploads/vehicles/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = 2048; // 2MB
            $config['encrypt_name'] = TRUE;
            
            // Create directory if it doesn't exist
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, TRUE);
            }
            
            $this->upload->initialize($config);
            
            $images = [];
            
            // Handle multiple image uploads
            $files = $_FILES;
            $count = count($_FILES['vehicle_images']['name']);

           
            
            for ($i = 0; $i < $count; $i++) {
                if (!empty($_FILES['vehicle_images']['name'][$i])) {
                    $_FILES['image']['name'] = $files['vehicle_images']['name'][$i];
                    $_FILES['image']['type'] = $files['vehicle_images']['type'][$i];
                    $_FILES['image']['tmp_name'] = $files['vehicle_images']['tmp_name'][$i];
                    $_FILES['image']['error'] = $files['vehicle_images']['error'][$i];
                    $_FILES['image']['size'] = $files['vehicle_images']['size'][$i];
                    
                    if ($this->upload->do_upload('image')) {
                        $upload_data = $this->upload->data();
                        $images[] = 'uploads/vehicles/' . $upload_data['file_name'];
                    }
                }
            }
            
            // Prepare vehicle data
            $vehicle_data = [
                'vendor_id' => $vendor_id,
                'title' => $this->input->post('title'),
                'type' => $this->input->post('type'),
                // 'capacity' => $this->input->post('capacity'),
                'seats' => $this->input->post('capacity'),
                'price_per_day' => $this->input->post('fixed_price'),
                'fuel_charge_per_km' => $this->input->post('fuel_charge'),
                // 'images' => json_encode($images),
                'is_active' => 1
            ];
            // Save vehicle
            // echo "<pre/>"; print_r($images);             print_r($vehicle_data); die;

            $vehicle_id = $this->vehicle_model->add_vehicle($vehicle_data);
            
            if ($vehicle_id) {
                // Add vehicle availability for next 30 days
                $quantity = $this->input->post('quantity') ? $this->input->post('quantity') : 1;
                
                for ($i = 0; $i < 30; $i++) {
                    $date = date('Y-m-d', strtotime("+$i days"));
                    $availability_data = [
                        'vehicle_id' => $vehicle_id,
                        'date' => $date,
                        'quantity' => $quantity
                    ];
                    $this->vehicle_model->add_vehicle_availability($availability_data);
                }
                
                $this->session->set_flashdata('success', 'Vehicle added successfully.');
                redirect('vendor/dashboard');
            } else {
                $this->session->set_flashdata('error', 'Failed to add vehicle. Please try again.');
                redirect('vendor/add_vehicle');
            }
        }
    }

    public function manage_vehicles() {
        $vendor_id = $this->vendor_model->get_vendor_id_by_user_id($this->session->userdata('user_id'));
        
        if (!$vendor_id) {
            redirect('vendor/apply');
        }
        
        $data['vehicles'] = $this->vehicle_model->get_vehicles(['vendor_id' => $vendor_id]);
        // echo "<pre/>";  print_r($data['vehicles']); //die;

        $this->load->view('templates/header');
        $this->load->view('vendor/manage_vehicles', $data);
        $this->load->view('templates/footer');
    }
    
    public function manage_drivers() {
        $vendor_id = $this->vendor_model->get_vendor_id_by_user_id($this->session->userdata('user_id'));
        
        if (!$vendor_id) {
            redirect('vendor/apply');
        }
        
        $data['drivers'] = $this->driver_model->get_drivers(['vendor_id' => $vendor_id]);
        
        $this->load->view('templates/header');
        $this->load->view('vendor/manage_drivers', $data);
        $this->load->view('templates/footer');
    }
    
    public function bookings() {
        $vendor_id = $this->vendor_model->get_vendor_id_by_user_id($this->session->userdata('user_id'));
        
        if (!$vendor_id) {
            redirect('vendor/apply');
        }
        
        // Filter by status if provided
        $status = $this->input->get('status');
        $filters = ['vendor_id' => $vendor_id];
        
        if ($status) {
            $filters['status'] = $status;
        }
        
        $data['bookings'] = $this->vendor_model->get_vendor_bookings($vendor_id, $filters);
        
        $this->load->view('templates/header');
        $this->load->view('vendor/bookings', $data);
        $this->load->view('templates/footer');
    }
    
    public function view_booking($id) {
        $vendor_id = $this->vendor_model->get_vendor_id_by_user_id($this->session->userdata('user_id'));
        
        if (!$vendor_id) {
            redirect('vendor/apply');
        }
        
        // Get booking details
        $data['booking'] = $this->booking_model->get_booking_by_id($id);
        
        // Check if booking exists and belongs to this vendor
        $belongs_to_vendor = false;
        if ($data['booking'] && isset($data['booking']->items)) {
            foreach ($data['booking']->items as $item) {
                if ($item->vendor_id == $vendor_id) {
                    $belongs_to_vendor = true;
                    break;
                }
            }
        }
        
        if (!$data['booking'] || !$belongs_to_vendor) {
            $this->session->set_flashdata('error', 'Booking not found or you do not have permission to view it.');
            redirect('vendor/bookings');
        }
        
        $this->load->view('templates/header');
        $this->load->view('vendor/view_booking', $data);
        $this->load->view('templates/footer');
    }
    
    public function update_booking_status($id, $status) {
        $vendor_id = $this->vendor_model->get_vendor_id_by_user_id($this->session->userdata('user_id'));
        
        if (!$vendor_id) {
            redirect('vendor/apply');
        }
        
        // Get booking details
        $booking = $this->booking_model->get_booking_by_id($id);
        
        // Check if booking exists and belongs to this vendor
        $belongs_to_vendor = false;
        if ($booking && isset($booking->items)) {
            foreach ($booking->items as $item) {
                if ($item->vendor_id == $vendor_id) {
                    $belongs_to_vendor = true;
                    break;
                }
            }
        }
        
        if (!$booking || !$belongs_to_vendor) {
            $this->session->set_flashdata('error', 'Booking not found or you do not have permission to update it.');
            redirect('vendor/bookings');
        }
        
        // Validate status
        $valid_statuses = ['pending', 'confirmed', 'completed', 'cancelled'];
        if (!in_array($status, $valid_statuses)) {
            $this->session->set_flashdata('error', 'Invalid booking status.');
            redirect('vendor/view_booking/' . $id);
        }
        
        // Update booking status
        $this->db->where('id', $id);
        $this->db->update('bookings', ['status' => $status]);
        
        $this->session->set_flashdata('success', 'Booking status updated successfully.');
        redirect('vendor/view_booking/' . $id);
    }
    
    public function edit_vehicle($id) {
        $vendor_id = $this->vendor_model->get_vendor_id_by_user_id($this->session->userdata('user_id'));
        
        if (!$vendor_id) {
            redirect('vendor/apply');
        }
        
        // Check if vehicle belongs to vendor
        $vehicle = $this->vehicle_model->get_vehicle_by_id($id);
        
        if (!$vehicle || $vehicle->vendor_id != $vendor_id) {
            $this->session->set_flashdata('error', 'Vehicle not found or you do not have permission to edit it.');
            redirect('vendor/dashboard');
        }
        
        // Form validation rules
        $this->form_validation->set_rules('title', 'Vehicle Title', 'trim|required');
        $this->form_validation->set_rules('type', 'Vehicle Type', 'trim|required');
        $this->form_validation->set_rules('capacity', 'Capacity', 'trim|required|numeric');
        $this->form_validation->set_rules('fixed_price', 'Fixed Price', 'trim|required|numeric');
        $this->form_validation->set_rules('fuel_charge', 'Fuel Charge', 'trim|required|numeric');
        
        if ($this->form_validation->run() === FALSE) {
            // Load edit vehicle form
            $data['vehicle'] = $vehicle;
            $this->load->view('templates/header');
            $this->load->view('vendor/edit_vehicle', $data);
            $this->load->view('templates/footer');
        } else {
            // Prepare vehicle data
            $vehicle_data = [
                'title' => $this->input->post('title'),
                'type' => $this->input->post('type'),
                'capacity' => $this->input->post('capacity'),
                'fixed_price' => $this->input->post('fixed_price'),
                'fuel_charge' => $this->input->post('fuel_charge'),
                'is_active' => $this->input->post('is_active') ? 1 : 0
            ];
            
            // Handle image uploads if any
            if (!empty($_FILES['images']['name'][0])) {
                $config['upload_path'] = './uploads/vehicles/';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['max_size'] = 2048; // 2MB
                $config['encrypt_name'] = TRUE;
                
                // Create directory if it doesn't exist
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, TRUE);
                }
                
                $this->upload->initialize($config);
                
                $images = json_decode($vehicle->images, TRUE) ?: [];
                
                // Handle multiple image uploads
                $files = $_FILES;
                $count = count($_FILES['images']['name']);
                
                for ($i = 0; $i < $count; $i++) {
                    if (!empty($_FILES['images']['name'][$i])) {
                        $_FILES['image']['name'] = $files['images']['name'][$i];
                        $_FILES['image']['type'] = $files['images']['type'][$i];
                        $_FILES['image']['tmp_name'] = $files['images']['tmp_name'][$i];
                        $_FILES['image']['error'] = $files['images']['error'][$i];
                        $_FILES['image']['size'] = $files['images']['size'][$i];
                        
                        if ($this->upload->do_upload('image')) {
                            $upload_data = $this->upload->data();
                            $images[] = 'uploads/vehicles/' . $upload_data['file_name'];
                        }
                    }
                }
                
                $vehicle_data['images'] = json_encode($images);
            }
            
            // Update vehicle
            $result = $this->vehicle_model->update_vehicle($id, $vehicle_data);
            
            if ($result) {
                // Update vehicle availability if quantity changed
                if ($this->input->post('quantity')) {
                    $quantity = $this->input->post('quantity');
                    $this->vehicle_model->update_vehicle_availability($id, $quantity);
                }
                
                $this->session->set_flashdata('success', 'Vehicle updated successfully.');
                redirect('vendor/dashboard');
            } else {
                $this->session->set_flashdata('error', 'Failed to update vehicle. Please try again.');
                redirect('vendor/edit_vehicle/' . $id);
            }
        }
    }

    public function add_driver() {
        $vendor_id = $this->vendor_model->get_vendor_id_by_user_id($this->session->userdata('user_id'));
        
        if (!$vendor_id) {
            redirect('vendor/apply');
        }
        
        $vendor = $this->vendor_model->get_vendor_by_id($vendor_id);
        
        if ($vendor->status !== 'approved') {
            $this->session->set_flashdata('error', 'Your vendor account must be approved before adding drivers.');
            redirect('vendor/dashboard');
        }
        
        // Form validation rules
        $this->form_validation->set_rules('name', 'Driver Name', 'trim|required');
        $this->form_validation->set_rules('license_no', 'License Number', 'trim|required');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|regex_match[/^[0-9]{10}$/]');
        $this->form_validation->set_rules('experience', 'Experience Years', 'trim|required|numeric');
                // echo "<pre/>"; print_r($this->input->post()); die;

        if ($this->form_validation->run() === FALSE) {
            // Load add driver form
            $this->load->view('templates/header');
            $this->load->view('vendor/add_driver');
            $this->load->view('templates/footer');
        } else {
            // Prepare driver data
            $driver_data = [
                'vendor_id' => $vendor_id,
                'name' => $this->input->post('name'),
                'license_number' => $this->input->post('license_no'),
                'phone' => $this->input->post('phone'),
                'experience_years' => $this->input->post('experience')
            ];
            
            // Save driver
            $driver_id = $this->driver_model->add_driver($driver_data);
            
            if ($driver_id) {
                $this->session->set_flashdata('success', 'Driver added successfully.');
                redirect('vendor/dashboard');
            } else {
                $this->session->set_flashdata('error', 'Failed to add driver. Please try again.');
                redirect('vendor/add_driver');
            }
        }
    }

    public function edit_driver($id) {
        $vendor_id = $this->vendor_model->get_vendor_id_by_user_id($this->session->userdata('user_id'));
        
        if (!$vendor_id) {
            redirect('vendor/apply');
        }
        
        // Check if driver belongs to vendor
        $driver = $this->driver_model->get_driver_by_id($id);
        
        if (!$driver || $driver->vendor_id != $vendor_id) {
            $this->session->set_flashdata('error', 'Driver not found or you do not have permission to edit it.');
            redirect('vendor/dashboard');
        }
        
        // Form validation rules
        $this->form_validation->set_rules('name', 'Driver Name', 'trim|required');
        $this->form_validation->set_rules('license_no', 'License Number', 'trim|required');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|regex_match[/^[0-9]{10}$/]');
        $this->form_validation->set_rules('experience_years', 'Experience Years', 'trim|required|numeric');
        
        if ($this->form_validation->run() === FALSE) {
            // Load edit driver form
            $data['driver'] = $driver;
            $this->load->view('templates/header');
            $this->load->view('vendor/edit_driver', $data);
            $this->load->view('templates/footer');
        } else {
            // Prepare driver data
            $driver_data = [
                'name' => $this->input->post('name'),
                'license_no' => $this->input->post('license_no'),
                'phone' => $this->input->post('phone'),
                'experience_years' => $this->input->post('experience_years')
            ];
            
            // Update driver
            $result = $this->driver_model->update_driver($id, $driver_data);
            
            if ($result) {
                $this->session->set_flashdata('success', 'Driver updated successfully.');
                redirect('vendor/dashboard');
            } else {
                $this->session->set_flashdata('error', 'Failed to update driver. Please try again.');
                redirect('vendor/edit_driver/' . $id);
            }
        }
    }

    public function delete_driver($id) {
        $vendor_id = $this->vendor_model->get_vendor_id_by_user_id($this->session->userdata('user_id'));
        
        if (!$vendor_id) {
            redirect('vendor/apply');
        }
        
        // Check if driver belongs to vendor
        $driver = $this->driver_model->get_driver_by_id($id);
        
        if (!$driver || $driver->vendor_id != $vendor_id) {
            $this->session->set_flashdata('error', 'Driver not found or you do not have permission to delete it.');
            redirect('vendor/dashboard');
        }
        
        // Delete driver
        $result = $this->driver_model->delete_driver($id);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Driver deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete driver. Please try again.');
        }
        
        redirect('vendor/dashboard');
    }

    public function delete_vehicle($id) {
        $vendor_id = $this->vendor_model->get_vendor_id_by_user_id($this->session->userdata('user_id'));
        
        if (!$vendor_id) {
            redirect('vendor/apply');
        }
        
        // Check if vehicle belongs to vendor
        $vehicle = $this->vehicle_model->get_vehicle_by_id($id);
        
        if (!$vehicle || $vehicle->vendor_id != $vendor_id) {
            $this->session->set_flashdata('error', 'Vehicle not found or you do not have permission to delete it.');
            redirect('vendor/dashboard');
        }
        
        // Delete vehicle
        $result = $this->vehicle_model->delete_vehicle($id);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Vehicle deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete vehicle. Please try again.');
        }
        
        redirect('vendor/dashboard');
    }
}