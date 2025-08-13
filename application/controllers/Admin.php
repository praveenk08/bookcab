<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['user_model', 'vendor_model', 'vehicle_model', 'booking_model', 'audit_model']);
        $this->load->helper(['form', 'url', 'security']);
        $this->load->library(['form_validation', 'session']);
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        
        // Check if user is an admin
        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Access denied. You must be an admin to access this page.');
            redirect('auth');
        }
    }

    public function index() {
        redirect('admin/dashboard');
    }

    public function dashboard() {
        $data['total_users'] = $this->user_model->count_users();
        $data['total_vendors'] = $this->vendor_model->count_vendors();
        $data['pending_vendors'] = $this->vendor_model->count_vendors(['status' => 'pending']);
        $data['total_bookings'] = $this->booking_model->count_bookings();
        $data['pending_bookings'] = $this->booking_model->count_bookings(['status' => 'pending']);
        
        $this->load->view('templates/header');
        $this->load->view('admin/dashboard', $data);
        $this->load->view('templates/footer');
    }

    public function list_vendors() {
        // Pagination config
        $config['base_url'] = site_url('admin/list_vendors');
        $config['total_rows'] = $this->vendor_model->count_vendors();
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $config['use_page_numbers'] = TRUE;
        
        // Initialize pagination
        $this->load->library('pagination');
        $this->pagination->initialize($config);
        
        // Get current page
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
        $offset = ($page - 1) * $config['per_page'];
        
        // Get vendors
        $data['vendors'] = $this->vendor_model->get_vendors([], $config['per_page'], $offset);
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('templates/header');
        $this->load->view('admin/list_vendors', $data);
        $this->load->view('templates/footer');
    }

    public function approve_vendor($id) {
        $vendor = $this->vendor_model->get_vendor_by_id($id);
        
        if (!$vendor) {
            $this->session->set_flashdata('error', 'Vendor not found.');
            redirect('admin/list_vendors');
        }
        
        if ($vendor->status === 'approved') {
            $this->session->set_flashdata('info', 'Vendor is already approved.');
            redirect('admin/list_vendors');
        }
        
        // Update vendor status
        $result = $this->vendor_model->update_vendor($id, ['status' => 'approved']);
        
        if ($result) {
            // Log action
            $this->audit_model->log_action(
                $this->session->userdata('user_id'),
                'vendor',
                $id,
                'status_change',
                $vendor->status,
                'approved'
            );
            
            $this->session->set_flashdata('success', 'Vendor approved successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to approve vendor. Please try again.');
        }
        
        redirect('admin/list_vendors');
    }

    public function reject_vendor($id) {
        $vendor = $this->vendor_model->get_vendor_by_id($id);
        
        if (!$vendor) {
            $this->session->set_flashdata('error', 'Vendor not found.');
            redirect('admin/list_vendors');
        }
        
        if ($vendor->status === 'rejected') {
            $this->session->set_flashdata('info', 'Vendor is already rejected.');
            redirect('admin/list_vendors');
        }
        
        // Update vendor status
        $result = $this->vendor_model->update_vendor($id, ['status' => 'rejected']);
        
        if ($result) {
            // Log action
            $this->audit_model->log_action(
                $this->session->userdata('user_id'),
                'vendor',
                $id,
                'status_change',
                $vendor->status,
                'rejected'
            );
            
            $this->session->set_flashdata('success', 'Vendor rejected successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to reject vendor. Please try again.');
        }
        
        redirect('admin/list_vendors');
    }

    public function list_bookings() {
        // Pagination config
        $config['base_url'] = site_url('admin/list_bookings');
        $config['total_rows'] = $this->booking_model->count_bookings();
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $config['use_page_numbers'] = TRUE;
        
        // Initialize pagination
        $this->load->library('pagination');
        $this->pagination->initialize($config);
        
        // Get current page
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
        $offset = ($page - 1) * $config['per_page'];
        
        // Get bookings
        $data['bookings'] = $this->booking_model->get_bookings([], $config['per_page'], $offset);
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('templates/header');
        $this->load->view('admin/list_bookings', $data);
        $this->load->view('templates/footer');
    }

    public function view_booking($id) {
        $data['booking'] = $this->booking_model->get_booking_by_id($id);
        
        if (!$data['booking']) {
            $this->session->set_flashdata('error', 'Booking not found.');
            redirect('admin/list_bookings');
        }
        
        $data['audit_logs'] = $this->audit_model->get_entity_logs('booking', $id);
        
        $this->load->view('templates/header');
        $this->load->view('admin/view_booking', $data);
        $this->load->view('templates/footer');
    }

    public function change_booking_status($id) {
        $booking = $this->booking_model->get_booking_by_id($id);
        
        if (!$booking) {
            $this->session->set_flashdata('error', 'Booking not found.');
            redirect('admin/list_bookings');
        }
        
        // Form validation rules
        $this->form_validation->set_rules('status', 'Status', 'trim|required');
        
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', 'Invalid status.');
            redirect('admin/view_booking/' . $id);
        } else {
            $new_status = $this->input->post('status');
            
            // Update booking status
            $result = $this->booking_model->update_booking_status($id, $new_status);
            
            if ($result) {
                $this->session->set_flashdata('success', 'Booking status updated successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to update booking status. Please try again.');
            }
            
            redirect('admin/view_booking/' . $id);
        }
    }

    public function list_users() {
        // Pagination config
        $config['base_url'] = site_url('admin/list_users');
        $config['total_rows'] = $this->user_model->count_users();
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $config['use_page_numbers'] = TRUE;
        
        // Initialize pagination
        $this->load->library('pagination');
        $this->pagination->initialize($config);
        
        // Get current page
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
        $offset = ($page - 1) * $config['per_page'];
        
        // Get users
        $data['users'] = $this->user_model->get_users([], $config['per_page'], $offset);
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('templates/header');
        $this->load->view('admin/list_users', $data);
        $this->load->view('templates/footer');
    }

    public function view_user($id) {
        $data['user'] = $this->user_model->get_user_by_id($id);
        
        if (!$data['user']) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect('admin/list_users');
        }
        
        // If user is a vendor, get vendor details
        if ($data['user']->role === 'vendor') {
            $vendor_id = $this->vendor_model->get_vendor_id_by_user_id($id);
            if ($vendor_id) {
                $data['vendor'] = $this->vendor_model->get_vendor_by_id($vendor_id);
                $data['vehicles'] = $this->vehicle_model->get_vehicles(['vendor_id' => $vendor_id]);
                
                // Add vendor statistics
                $data['vendor']->booking_count = $this->vendor_model->count_vendor_bookings($vendor_id);
                $data['vendor']->vehicle_count = count($data['vehicles']);
                $data['vendor']->driver_count = $this->db->where('vendor_id', $vendor_id)->count_all_results('drivers');
                $data['vendor']->avg_rating = $this->db->select_avg('rating')->where('vendor_id', $vendor_id)->get('reviews')->row()->rating ?? 0;
                $data['vendor']->is_verified = ($data['vendor']->status === 'approved');
            }
        }
        
        // Get user bookings
        $data['bookings'] = $this->booking_model->get_user_bookings($id);
        
        // Add booking statistics for each booking
        foreach ($data['bookings'] as $booking) {
            $booking->vehicle_count = count($booking->items);
        }
        
        $this->load->view('templates/header');
        $this->load->view('admin/view_user', $data);
        $this->load->view('templates/footer');
    }
    
    public function update_user_status($id) {
        $user = $this->user_model->get_user_by_id($id);
        
        if (!$user) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect('admin/list_users');
        }
        
        // Cannot change own status
        if ($id == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'You cannot change your own status.');
            redirect('admin/view_user/' . $id);
        }
        
        // Form validation rules
        $this->form_validation->set_rules('status', 'Status', 'trim|required|in_list[active,inactive]');
        
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', 'Invalid status.');
            redirect('admin/view_user/' . $id);
        } else {
            $new_status = $this->input->post('status');
            
            // Update user status
            $result = $this->user_model->update_user($id, ['status' => $new_status]);
            
            if ($result) {
                // Log action
                $this->audit_model->log_action(
                    $this->session->userdata('user_id'),
                    'user',
                    $id,
                    'status_change',
                    $user->status,
                    $new_status
                );
                
                $this->session->set_flashdata('success', 'User status updated successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to update user status. Please try again.');
            }
            
            redirect('admin/view_user/' . $id);
        }
    }

    public function audit_logs() {
        // Pagination config
        $config['base_url'] = site_url('admin/audit_logs');
        $config['total_rows'] = $this->audit_model->count_logs();
        $config['per_page'] = 20;
        $config['uri_segment'] = 3;
        $config['use_page_numbers'] = TRUE;
        
        // Initialize pagination
        $this->load->library('pagination');
        $this->pagination->initialize($config);
        
        // Get current page
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
        $offset = ($page - 1) * $config['per_page'];
        
        // Get audit logs
        $data['logs'] = $this->audit_model->get_logs([], $config['per_page'], $offset);
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('templates/header');
        $this->load->view('admin/audit_logs', $data);
        $this->load->view('templates/footer');
    }
    
    public function settings() {
        // Load settings model if not already loaded
        $this->load->model('settings_model');
        
        // If form is submitted
        if ($this->input->post()) {
            // Validate form
            $this->form_validation->set_rules('site_name', 'Site Name', 'required');
            $this->form_validation->set_rules('site_email', 'Site Email', 'required|valid_email');
            
            if ($this->form_validation->run() === TRUE) {
                // Process settings update
                $settings = $this->input->post();
                
                // Handle file uploads (logo, favicon)
                $config['upload_path'] = './assets/uploads/';
                $config['allowed_types'] = 'gif|jpg|png|ico';
                $config['max_size'] = 2048;
                $this->load->library('upload', $config);
                
                // Upload logo if provided
                if ($_FILES['site_logo']['name']) {
                    if ($this->upload->do_upload('site_logo')) {
                        $upload_data = $this->upload->data();
                        $settings['site_logo'] = 'assets/uploads/' . $upload_data['file_name'];
                    }
                }
                
                // Upload favicon if provided
                if ($_FILES['site_favicon']['name']) {
                    if ($this->upload->do_upload('site_favicon')) {
                        $upload_data = $this->upload->data();
                        $settings['site_favicon'] = 'assets/uploads/' . $upload_data['file_name'];
                    }
                }
                
                // Save settings
                $result = $this->settings_model->update_settings($settings);
                
                if ($result) {
                    $this->session->set_flashdata('success', 'Settings updated successfully.');
                } else {
                    $this->session->set_flashdata('error', 'Failed to update settings.');
                }
                
                redirect('admin/settings');
            }
        }
        
        // Get current settings
        $data['settings'] = $this->settings_model->get_all_settings();
        
        $this->load->view('templates/header');
        $this->load->view('admin/settings', $data);
        $this->load->view('templates/footer');
    }
    
    public function reports() {
        // Load required models
        $this->load->model('reports_model');
        
        // Default report type
        $data['report_type'] = $this->input->get('type') ? $this->input->get('type') : 'booking';
        
        // Process report generation
        if ($this->input->post('generate_report')) {
            $report_type = $this->input->post('report_type');
            $filters = $this->input->post();
            
            switch ($report_type) {
                case 'booking':
                    $data['report_data'] = $this->reports_model->get_booking_report($filters);
                    break;
                case 'revenue':
                    $data['report_data'] = $this->reports_model->get_revenue_report($filters);
                    break;
                case 'vendor':
                    $data['report_data'] = $this->reports_model->get_vendor_report($filters);
                    break;
                case 'vehicle':
                    $data['report_data'] = $this->reports_model->get_vehicle_report($filters);
                    break;
                case 'customer':
                    $data['report_data'] = $this->reports_model->get_customer_report($filters);
                    break;
            }
            
            $data['report_type'] = $report_type;
            $data['filters'] = $filters;
        }
        
        // Get vendors for filter dropdown
        $data['vendors'] = $this->vendor_model->get_vendors(['status' => 'approved']);
        
        $this->load->view('templates/header');
        $this->load->view('admin/reports', $data);
        $this->load->view('templates/footer');
    }
    
    public function export_report() {
        // Load required libraries
        $this->load->library('excel');
        $this->load->model('reports_model');
        
        $report_type = $this->input->get('type');
        $filters = $this->input->get();
        
        // Generate report data
        switch ($report_type) {
            case 'booking':
                $report_data = $this->reports_model->get_booking_report($filters);
                $filename = 'booking_report_' . date('Y-m-d');
                break;
            case 'revenue':
                $report_data = $this->reports_model->get_revenue_report($filters);
                $filename = 'revenue_report_' . date('Y-m-d');
                break;
            case 'vendor':
                $report_data = $this->reports_model->get_vendor_report($filters);
                $filename = 'vendor_report_' . date('Y-m-d');
                break;
            case 'vehicle':
                $report_data = $this->reports_model->get_vehicle_report($filters);
                $filename = 'vehicle_report_' . date('Y-m-d');
                break;
            case 'customer':
                $report_data = $this->reports_model->get_customer_report($filters);
                $filename = 'customer_report_' . date('Y-m-d');
                break;
            default:
                $this->session->set_flashdata('error', 'Invalid report type.');
                redirect('admin/reports');
        }
        
        // Export to Excel or PDF based on format parameter
        $format = $this->input->get('format') ? $this->input->get('format') : 'excel';
        
        if ($format === 'excel') {
            $this->reports_model->export_to_excel($report_data, $report_type, $filename);
        } else {
            $this->reports_model->export_to_pdf($report_data, $report_type, $filename);
        }
    }
    
    public function vendors() {
        // Pagination config
        $config['base_url'] = site_url('admin/vendors');
        $config['total_rows'] = $this->vendor_model->count_vendors();
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $config['use_page_numbers'] = TRUE;
        
        // Initialize pagination
        $this->load->library('pagination');
        $this->pagination->initialize($config);
        
        // Get current page
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
        $offset = ($page - 1) * $config['per_page'];
        
        // Get vendors
        $data['vendors'] = $this->vendor_model->get_vendors([], $config['per_page'], $offset);
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('templates/header');
        $this->load->view('admin/vendors', $data);
        $this->load->view('templates/footer');
    }
    
    public function view_vendor($id) {
        $data['vendor'] = $this->vendor_model->get_vendor_by_id($id);
        
        if (!$data['vendor']) {
            $this->session->set_flashdata('error', 'Vendor not found.');
            redirect('admin/vendors');
        }
        
        // Get vendor user
        $data['user'] = $this->user_model->get_user_by_id($data['vendor']->user_id);
        
        // Get vendor vehicles
        $data['vehicles'] = $this->vehicle_model->get_vehicles(['vendor_id' => $id]);
        
        // Get vendor bookings
        $data['bookings'] = $this->booking_model->get_vendor_bookings($id);
        
        // Get vendor drivers
        $this->load->model('driver_model');
        $data['drivers'] = $this->driver_model->get_drivers(['vendor_id' => $id]);
        
        $this->load->view('templates/header');
        $this->load->view('admin/view_vendor', $data);
        $this->load->view('templates/footer');
    }
    
    public function bookings() {
        redirect('admin/list_bookings');
    }
    
    public function users() {
        redirect('admin/list_users');
    }
}