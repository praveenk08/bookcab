<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Driver extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['user_model', 'vendor_model', 'vehicle_model', 'driver_model']);
        $this->load->helper(['form', 'url', 'security', 'file']);
        $this->load->library(['form_validation', 'session', 'upload']);
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        
        // Check if user is a vendor or admin
        if (!in_array($this->session->userdata('role'), ['vendor', 'admin'])) {
            $this->session->set_flashdata('error', 'Access denied. You must be a vendor or admin to access this page.');
            redirect('auth');
        }
    }

    public function index() {
        redirect('driver/manage');
    }

    public function manage() {
        // Check if user is vendor or admin
        if ($this->session->userdata('role') === 'vendor') {
            $vendor_id = $this->vendor_model->get_vendor_id_by_user_id($this->session->userdata('user_id'));
            
            if (!$vendor_id) {
                redirect('vendor/apply');
            }
            
            $data['drivers'] = $this->driver_model->get_drivers(['vendor_id' => $vendor_id]);
        } else {
            // Admin can see all drivers
            $data['drivers'] = $this->driver_model->get_drivers();
        }
        
        $this->load->view('templates/header');
        $this->load->view('driver/manage', $data);
        $this->load->view('templates/footer');
    }
    
    public function search() {
        // Get search parameters
        $name = $this->input->get('name');
        $license = $this->input->get('license');
        $status = $this->input->get('status');
        
        // Build filters array
        $filters = [];
        
        // Check if user is vendor or admin
        if ($this->session->userdata('role') === 'vendor') {
            $vendor_id = $this->vendor_model->get_vendor_id_by_user_id($this->session->userdata('user_id'));
            
            if (!$vendor_id) {
                redirect('vendor/apply');
            }
            
            $filters['vendor_id'] = $vendor_id;
        }
        
        // Add search filters
        if (!empty($name)) {
            $this->db->like('name', $name);
        }
        
        if (!empty($license)) {
            $this->db->like('license_number', $license);
        }
        
        if ($status !== '' && $status !== null) {
            $filters['is_active'] = $status;
        }
        
        // Get filtered drivers
        $data['drivers'] = $this->driver_model->get_drivers($filters);
        
        $this->load->view('templates/header');
        $this->load->view('driver/manage', $data);
        $this->load->view('templates/footer');
    }
    
    public function add() {
        // Check if user is vendor
        if ($this->session->userdata('role') === 'vendor') {
            $vendor_id = $this->vendor_model->get_vendor_id_by_user_id($this->session->userdata('user_id'));
            
            if (!$vendor_id) {
                redirect('vendor/apply');
            }
            
            $vendor = $this->vendor_model->get_vendor_by_id($vendor_id);
            
            if ($vendor->status !== 'approved') {
                $this->session->set_flashdata('error', 'Your vendor account must be approved before adding drivers.');
                redirect('vendor/dashboard');
            }
        }
        
        // Form validation rules
        $this->form_validation->set_rules('name', 'Driver Name', 'trim|required');
        $this->form_validation->set_rules('license_no', 'License Number', 'trim|required');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|regex_match[/^[0-9]{10}$/]');
        $this->form_validation->set_rules('experience', 'Experience Years', 'trim|required|numeric');
        $this->form_validation->set_rules('address', 'Address', 'trim');

        if ($this->form_validation->run() === FALSE) {
            // Load add driver form
            $this->load->view('templates/header');
            $this->load->view('driver/add');
            $this->load->view('templates/footer');
        } else {
            // Upload license document
            $license_document = '';
            if (!empty($_FILES['license_document']['name'])) {
                $config['upload_path'] = './uploads/drivers/';
                $config['allowed_types'] = 'pdf|jpg|jpeg|png';
                $config['max_size'] = 2048; // 2MB
                $config['encrypt_name'] = TRUE;
                
                // Create directory if it doesn't exist
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, TRUE);
                }
                
                $this->upload->initialize($config);
                
                if (!$this->upload->do_upload('license_document')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect('driver/add');
                } else {
                    $upload_data = $this->upload->data();
                    $license_document = $upload_data['file_name'];
                }
            }
            
            // Upload driver photo
            $photo = '';
            if (!empty($_FILES['photo']['name'])) {
                $config['upload_path'] = './uploads/drivers/';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['max_size'] = 2048; // 2MB
                $config['encrypt_name'] = TRUE;
                
                $this->upload->initialize($config);
                
                if (!$this->upload->do_upload('photo')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect('driver/add');
                } else {
                    $upload_data = $this->upload->data();
                    $photo = $upload_data['file_name'];
                }
            }
            
            // Prepare driver data
            $driver_data = [
                'vendor_id' => ($this->session->userdata('role') === 'vendor') ? 
                    $this->vendor_model->get_vendor_id_by_user_id($this->session->userdata('user_id')) : 
                    $this->input->post('vendor_id'),
                'name' => $this->input->post('name'),
                'license_number' => $this->input->post('license_no'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address'),
                'experience_years' => $this->input->post('experience'),
                'license_document' => $license_document,
                'photo' => $photo,
                'is_active' => 1
            ];
            
            // Save driver
            $driver_id = $this->driver_model->add_driver($driver_data);
            
            if ($driver_id) {
                $this->session->set_flashdata('success', 'Driver added successfully.');
                redirect('driver/manage');
            } else {
                $this->session->set_flashdata('error', 'Failed to add driver. Please try again.');
                redirect('driver/add');
            }
        }
    }

    public function edit($id) {
        // Check if user is vendor
        if ($this->session->userdata('role') === 'vendor') {
            $vendor_id = $this->vendor_model->get_vendor_id_by_user_id($this->session->userdata('user_id'));
            
            if (!$vendor_id) {
                redirect('vendor/apply');
            }
            
            // Check if driver belongs to vendor
            $driver = $this->driver_model->get_driver_by_id($id);
            
            if (!$driver || $driver->vendor_id != $vendor_id) {
                $this->session->set_flashdata('error', 'Driver not found or you do not have permission to edit it.');
                redirect('driver/manage');
            }
        } else {
            // Admin can edit any driver
            $driver = $this->driver_model->get_driver_by_id($id);
            
            if (!$driver) {
                $this->session->set_flashdata('error', 'Driver not found.');
                redirect('driver/manage');
            }
        }
        
        // Form validation rules
        $this->form_validation->set_rules('name', 'Driver Name', 'trim|required');
        $this->form_validation->set_rules('license_no', 'License Number', 'trim|required');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|regex_match[/^[0-9]{10}$/]');
        $this->form_validation->set_rules('experience', 'Experience Years', 'trim|required|numeric');
        $this->form_validation->set_rules('address', 'Address', 'trim');
        
        if ($this->form_validation->run() === FALSE) {
            // Load edit driver form
            $data['driver'] = $driver;
            $this->load->view('templates/header');
            $this->load->view('driver/edit', $data);
            $this->load->view('templates/footer');
        } else {
            // Prepare driver data
            $driver_data = [
                'name' => $this->input->post('name'),
                'license_number' => $this->input->post('license_no'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address'),
                'experience_years' => $this->input->post('experience'),
                'is_active' => $this->input->post('is_active') ? 1 : 0
            ];
            
            // Upload license document if provided
            if (!empty($_FILES['license_document']['name'])) {
                $config['upload_path'] = './uploads/drivers/';
                $config['allowed_types'] = 'pdf|jpg|jpeg|png';
                $config['max_size'] = 2048; // 2MB
                $config['encrypt_name'] = TRUE;
                
                // Create directory if it doesn't exist
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, TRUE);
                }
                
                $this->upload->initialize($config);
                
                if ($this->upload->do_upload('license_document')) {
                    $upload_data = $this->upload->data();
                    $driver_data['license_document'] = $upload_data['file_name'];
                    
                    // Delete old file if exists
                    if (!empty($driver->license_document)) {
                        $old_file = './uploads/drivers/' . $driver->license_document;
                        if (file_exists($old_file)) {
                            unlink($old_file);
                        }
                    }
                }
            }
            
            // Upload driver photo if provided
            if (!empty($_FILES['photo']['name'])) {
                $config['upload_path'] = './uploads/drivers/';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['max_size'] = 2048; // 2MB
                $config['encrypt_name'] = TRUE;
                
                $this->upload->initialize($config);
                
                if ($this->upload->do_upload('photo')) {
                    $upload_data = $this->upload->data();
                    $driver_data['photo'] = $upload_data['file_name'];
                    
                    // Delete old file if exists
                    if (!empty($driver->photo)) {
                        $old_file = './uploads/drivers/' . $driver->photo;
                        if (file_exists($old_file)) {
                            unlink($old_file);
                        }
                    }
                }
            }
            
            // Update driver
            $result = $this->driver_model->update_driver($id, $driver_data);
            
            if ($result) {
                $this->session->set_flashdata('success', 'Driver updated successfully.');
                redirect('driver/manage');
            } else {
                $this->session->set_flashdata('error', 'Failed to update driver. Please try again.');
                redirect('driver/edit/' . $id);
            }
        }
    }

    public function delete($id) {
        // Check if user is vendor
        if ($this->session->userdata('role') === 'vendor') {
            $vendor_id = $this->vendor_model->get_vendor_id_by_user_id($this->session->userdata('user_id'));
            
            if (!$vendor_id) {
                redirect('vendor/apply');
            }
            
            // Check if driver belongs to vendor
            $driver = $this->driver_model->get_driver_by_id($id);
            
            if (!$driver || $driver->vendor_id != $vendor_id) {
                $this->session->set_flashdata('error', 'Driver not found or you do not have permission to delete it.');
                redirect('driver/manage');
            }
        } else {
            // Admin can delete any driver
            $driver = $this->driver_model->get_driver_by_id($id);
            
            if (!$driver) {
                $this->session->set_flashdata('error', 'Driver not found.');
                redirect('driver/manage');
            }
        }
        
        // Delete driver
        $result = $this->driver_model->delete_driver($id);
        
        if ($result) {
            // Delete driver files
            if (!empty($driver->license_document)) {
                $license_file = './uploads/drivers/' . $driver->license_document;
                if (file_exists($license_file)) {
                    unlink($license_file);
                }
            }
            
            if (!empty($driver->photo)) {
                $photo_file = './uploads/drivers/' . $driver->photo;
                if (file_exists($photo_file)) {
                    unlink($photo_file);
                }
            }
            
            $this->session->set_flashdata('success', 'Driver deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete driver. Please try again.');
        }
        
        redirect('driver/manage');
    }

    public function view($id) {
        // Check if user is vendor
        if ($this->session->userdata('role') === 'vendor') {
            $vendor_id = $this->vendor_model->get_vendor_id_by_user_id($this->session->userdata('user_id'));
            
            if (!$vendor_id) {
                redirect('vendor/apply');
            }
            
            // Check if driver belongs to vendor
            $driver = $this->driver_model->get_driver_by_id($id);
            
            if (!$driver || $driver->vendor_id != $vendor_id) {
                $this->session->set_flashdata('error', 'Driver not found or you do not have permission to view it.');
                redirect('driver/manage');
            }
        } else {
            // Admin can view any driver
            $driver = $this->driver_model->get_driver_by_id($id);
            
            if (!$driver) {
                $this->session->set_flashdata('error', 'Driver not found.');
                redirect('driver/manage');
            }
        }
        
        $data['driver'] = $driver;
        
        // Get vendor details
        $data['vendor'] = $this->vendor_model->get_vendor_by_id($driver->vendor_id);
        
        $this->load->view('templates/header');
        $this->load->view('driver/view', $data);
        $this->load->view('templates/footer');
    }

    public function assign($booking_id = null) {
        // Check if booking ID is provided
        if (!$booking_id) {
            $this->session->set_flashdata('error', 'Invalid booking ID.');
            redirect('vendor/bookings');
        }
        
        // Check if user is vendor
        if ($this->session->userdata('role') === 'vendor') {
            $vendor_id = $this->vendor_model->get_vendor_id_by_user_id($this->session->userdata('user_id'));
            
            if (!$vendor_id) {
                redirect('vendor/apply');
            }
            
            // Get booking details
            $booking = $this->booking_model->get_booking_by_id($booking_id);
            
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
                $this->session->set_flashdata('error', 'Booking not found or you do not have permission to assign drivers.');
                redirect('vendor/bookings');
            }
            
            // Get available drivers for this vendor
            $data['drivers'] = $this->driver_model->get_drivers(['vendor_id' => $vendor_id, 'is_active' => 1]);
        } else {
            // Admin can assign drivers to any booking
            $booking = $this->booking_model->get_booking_by_id($booking_id);
            
            if (!$booking) {
                $this->session->set_flashdata('error', 'Booking not found.');
                redirect('admin/bookings');
            }
            
            // Get all active drivers
            $data['drivers'] = $this->driver_model->get_drivers(['is_active' => 1]);
        }
        
        $data['booking'] = $booking;
        
        // Form validation rules
        $this->form_validation->set_rules('driver_id', 'Driver', 'required');
        $this->form_validation->set_rules('vehicle_id', 'Vehicle', 'required');
        
        if ($this->form_validation->run() === FALSE) {
            // Load assign driver form
            $this->load->view('templates/header');
            $this->load->view('driver/assign', $data);
            $this->load->view('templates/footer');
        } else {
            // Assign driver to booking
            $assign_data = [
                'booking_id' => $booking_id,
                'vehicle_id' => $this->input->post('vehicle_id'),
                'driver_id' => $this->input->post('driver_id'),
                'assigned_by' => $this->session->userdata('user_id'),
                'assigned_at' => date('Y-m-d H:i:s')
            ];
            
            $result = $this->booking_model->assign_driver($assign_data);
            
            if ($result) {
                // Add to booking history
                $history_data = [
                    'booking_id' => $booking_id,
                    'action' => 'driver_assigned',
                    'user_id' => $this->session->userdata('user_id'),
                    'notes' => 'Driver assigned to booking'
                ];
                $this->booking_model->add_booking_history($history_data);
                
                $this->session->set_flashdata('success', 'Driver assigned successfully.');
                
                if ($this->session->userdata('role') === 'vendor') {
                    redirect('vendor/view_booking/' . $booking_id);
                } else {
                    redirect('admin/view_booking/' . $booking_id);
                }
            } else {
                $this->session->set_flashdata('error', 'Failed to assign driver. Please try again.');
                redirect('driver/assign/' . $booking_id);
            }
        }
    }
}