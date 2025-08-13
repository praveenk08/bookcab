<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicle extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['vehicle_model', 'vendor_model']);
        $this->load->helper(['form', 'url', 'security']);
        $this->load->library(['form_validation', 'session']);
    }

    public function index() {
        redirect('vehicle/search');
    }

    public function search() {
        // Form validation rules
        $this->form_validation->set_rules('date_from', 'From Date', 'trim');
        $this->form_validation->set_rules('date_to', 'To Date', 'trim');
        $this->form_validation->set_rules('type', 'Vehicle Type', 'trim');
        $this->form_validation->set_rules('capacity', 'Capacity', 'trim|numeric');
        $this->form_validation->set_rules('quantity', 'Quantity', 'trim|numeric');
        
        if ($this->form_validation->run() === FALSE) {
            // Load search form
            $this->load->view('templates/header');
            $this->load->view('vehicle/search');
            $this->load->view('templates/footer');
        } else {
            // Prepare search criteria
            $criteria = [
                'date_from' => $this->input->post('date_from'),
                'date_to' => $this->input->post('date_to'),
                'type' => $this->input->post('type'),
                'capacity' => $this->input->post('capacity'),
                'quantity' => $this->input->post('quantity')
            ];
            
            // Search vehicles
            $data['vehicles'] = $this->vehicle_model->search_vehicles($criteria);
            $data['criteria'] = $criteria;
            
            $this->load->view('templates/header');
            $this->load->view('vehicle/search_results', $data);
            $this->load->view('templates/footer');
        }
    }

    public function view($id) {
        // Get vehicle details
        $data['vehicle'] = $this->vehicle_model->get_vehicle_by_id($id);
        
        if (!$data['vehicle']) {
            $this->session->set_flashdata('error', 'Vehicle not found');
            redirect('vehicle/search');
        }
        
        // Get vendor details
        $vendor_id = $data['vehicle']->vendor_id;
        $data['vendor'] = $this->vendor_model->get_vendor_by_id($vendor_id);
        
        // Get reviews for this vehicle
        $this->load->model('booking_model');
        $data['reviews'] = $this->booking_model->get_vehicle_reviews($id);
        
        // Check if vehicle is from an approved vendor and is active
        if ($data['vendor']->status !== 'approved' || !$data['vehicle']->is_active) {
            $this->session->set_flashdata('error', 'Vehicle is not available.');
            redirect('vehicle/search');
        }
        
        $this->load->view('templates/header');
        $this->load->view('vehicle/details', $data);
        $this->load->view('templates/footer');
    }

    public function check_availability() {
        // Form validation rules
        $this->form_validation->set_rules('vehicle_id', 'Vehicle ID', 'trim|required|numeric');
        $this->form_validation->set_rules('date_from', 'From Date', 'trim|required');
        $this->form_validation->set_rules('date_to', 'To Date', 'trim|required');
        $this->form_validation->set_rules('quantity', 'Quantity', 'trim|required|numeric');
        
        if ($this->form_validation->run() === FALSE) {
            $response = ['status' => 'error', 'message' => validation_errors()];
        } else {
            $vehicle_id = $this->input->post('vehicle_id');
            $date_from = $this->input->post('date_from');
            $date_to = $this->input->post('date_to');
            $quantity = $this->input->post('quantity');
            
            // Check availability
            $is_available = $this->vehicle_model->check_availability($vehicle_id, $date_from, $date_to, $quantity);
            
            if ($is_available) {
                $response = ['status' => 'success', 'message' => 'Vehicle is available for the selected dates.'];
            } else {
                $response = ['status' => 'error', 'message' => 'Vehicle is not available for the selected dates.'];
            }
        }
        
        // Return JSON response
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($response));
    }

    public function add_to_cart() {
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            $response = ['status' => 'error', 'message' => 'Please login to add vehicles to cart.'];
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode($response));
            return;
        }
        
        // Form validation rules
        $this->form_validation->set_rules('vehicle_id', 'Vehicle ID', 'trim|required|numeric');
        $this->form_validation->set_rules('date_from', 'From Date', 'trim|required');
        $this->form_validation->set_rules('date_to', 'To Date', 'trim|required');
        $this->form_validation->set_rules('quantity', 'Quantity', 'trim|required|numeric');
        
        if ($this->form_validation->run() === FALSE) {
            $response = ['status' => 'error', 'message' => validation_errors()];
        } else {
            $vehicle_id = $this->input->post('vehicle_id');
            $date_from = $this->input->post('date_from');
            $date_to = $this->input->post('date_to');
            $quantity = $this->input->post('quantity');
            
            // Check availability
            $is_available = $this->vehicle_model->check_availability($vehicle_id, $date_from, $date_to, $quantity);
            
            if (!$is_available) {
                $response = ['status' => 'error', 'message' => 'Vehicle is not available for the selected dates.'];
                $this->output->set_content_type('application/json');
                $this->output->set_output(json_encode($response));
                return;
            }
            
            // Get vehicle details
            $vehicle = $this->vehicle_model->get_vehicle_by_id($vehicle_id);
            
            if (!$vehicle) {
                $response = ['status' => 'error', 'message' => 'Vehicle not found.'];
                $this->output->set_content_type('application/json');
                $this->output->set_output(json_encode($response));
                return;
            }
            
            // Calculate price
            $from_timestamp = strtotime($date_from);
            $to_timestamp = strtotime($date_to);
            $days = ceil(($to_timestamp - $from_timestamp) / (60 * 60 * 24));
            $price = ($vehicle->fixed_price + $vehicle->fuel_charge) * $days * $quantity;
            
            // Prepare cart item
            $cart_item = [
                'id' => $vehicle_id,
                'qty' => $quantity,
                'price' => $price,
                'name' => $vehicle->title,
                'options' => [
                    'type' => $vehicle->type,
                    'vendor_id' => $vehicle->vendor_id,
                    'date_from' => $date_from,
                    'date_to' => $date_to,
                    'days' => $days,
                    'fixed_price' => $vehicle->fixed_price,
                    'fuel_charge' => $vehicle->fuel_charge
                ]
            ];
            
            // Initialize cart if not already
            if (!$this->session->has_userdata('cart_items')) {
                $this->session->set_userdata('cart_items', []);
            }
            
            // Add to cart
            $cart_items = $this->session->userdata('cart_items');
            $cart_items[] = $cart_item;
            $this->session->set_userdata('cart_items', $cart_items);
            
            $response = ['status' => 'success', 'message' => 'Vehicle added to cart successfully.'];
        }
        
        // Return JSON response
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($response));
    }

    public function cart() {
        $data['cart_items'] = $this->session->userdata('cart_items') ?: [];
        $data['total_price'] = 0;
        
        foreach ($data['cart_items'] as $item) {
            $data['total_price'] += $item['price'];
        }
        
        $this->load->view('templates/header');
        $this->load->view('vehicle/cart', $data);
        $this->load->view('templates/footer');
    }

    public function remove_from_cart($index) {
        $cart_items = $this->session->userdata('cart_items') ?: [];
        
        if (isset($cart_items[$index])) {
            unset($cart_items[$index]);
            $cart_items = array_values($cart_items); // Re-index array
            $this->session->set_userdata('cart_items', $cart_items);
            $this->session->set_flashdata('success', 'Item removed from cart successfully.');
        } else {
            $this->session->set_flashdata('error', 'Item not found in cart.');
        }
        
        redirect('vehicle/cart');
    }

    public function clear_cart() {
        $this->session->unset_userdata('cart_items');
        $this->session->set_flashdata('success', 'Cart cleared successfully.');
        redirect('vehicle/cart');
    }
}