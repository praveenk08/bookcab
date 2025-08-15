<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct() {
        parent::__construct();

        // echo "sssssssss";die;
        // Check if user is logged in
        // if (!$this->session->userdata('logged_in')) {
        //     redirect('login');
        // }
        
        // // Check if user role is 'user'
        // if ($this->session->userdata('role') !== 'user') {
        //     redirect('login');
        // }
        
        // Load models
        $this->load->model('booking_model');
        $this->load->model('vehicle_model');
    }

    public function dashboard() {
        $data['title'] = 'User Dashboard';
        $user_id = $this->session->userdata('user_id');
        
        // Get user's bookings
        $data['bookings'] = $this->booking_model->get_user_bookings($user_id, 5); // Limit to 5 recent bookings
        // Get recommended vehicles
        $data['recommended_vehicles'] = $this->vehicle_model->get_recommended_vehicles(4); // Limit to 4 vehicles
        
        // Load views
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('user/dashboard', $data);
        $this->load->view('templates/footer');
    }
    
    public function bookings() {
        $data['title'] = 'My Bookings';
        $user_id = $this->session->userdata('user_id');
        
        // Get all user's bookings
        $data['bookings'] = $this->booking_model->get_user_bookings($user_id);

        // Load views
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('user/bookings', $data);
        $this->load->view('templates/footer');
    }
    
    public function profile() {
        $data['title'] = 'My Profile';
        
        // Load user model
        $this->load->model('user_model');
        $user_id = $this->session->userdata('user_id');
        
        // Get user details
        $data['user'] = $this->user_model->get_user_by_id($user_id);
        
        // Form validation
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('phone', 'Phone', 'required|trim');
        
        if ($this->form_validation->run() === FALSE) {
            // Load views
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);
            $this->load->view('user/profile', $data);
            $this->load->view('templates/footer');
        } else {
            // Update user profile
            $update_data = [
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address')
            ];
            
            $this->user_model->update_user($user_id, $update_data);
            $this->session->set_flashdata('success', 'Profile updated successfully');
            redirect('user/profile');
        }
    }
}