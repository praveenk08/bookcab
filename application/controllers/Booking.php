<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['booking_model', 'vehicle_model', 'vendor_model', 'user_model', 'audit_model']);
        $this->load->helper(['form', 'url', 'security']);
        $this->load->library(['form_validation', 'session']);
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    public function index() {
        redirect('booking/user_bookings');
    }

    public function create() {
        // Check if cart is empty
        $cart_items = $this->session->userdata('cart_items') ?: [];
        if (empty($cart_items)) {
            $this->session->set_flashdata('error', 'Your cart is empty. Please add vehicles to cart first.');
            redirect('vehicle/search');
        }
        
        // Form validation rules
        $this->form_validation->set_rules('booking_notes', 'Booking Notes', 'trim');
        
        if ($this->form_validation->run() === FALSE) {
            $data['cart_items'] = $cart_items;
            $data['total_price'] = 0;
            
            foreach ($data['cart_items'] as $item) {
                $data['total_price'] += $item['price'];
            }
            
            $this->load->view('templates/header');
            $this->load->view('booking/create', $data);
            $this->load->view('templates/footer');
        } else {
            $user_id = $this->session->userdata('user_id');
            $booking_notes = $this->input->post('booking_notes');
            $total_price = 0;
            
            foreach ($cart_items as $item) {
                $total_price += $item['price'];
            }
            
            // Prepare booking data
            $booking_data = [
                'user_id' => $user_id,
                'total_price' => $total_price,
                'status' => 'PENDING',
                'notes' => $booking_notes,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            // Prepare booking items data
            $booking_items = [];
            foreach ($cart_items as $item) {
                $booking_items[] = [
                    'vehicle_id' => $item['id'],
                    'vendor_id' => $item['options']['vendor_id'],
                    'driver_id' => null, // Will be assigned by vendor later
                    'qty' => $item['qty'],
                    'date_from' => $item['options']['date_from'],
                    'date_to' => $item['options']['date_to'],
                    'price' => $item['price']
                ];
            }
            
            // Create booking with transaction
            $booking_id = $this->booking_model->create_booking($booking_data, $booking_items);
            
            if ($booking_id) {
                // Log the action
                $this->audit_model->log_action('booking', $booking_id, 'create', 'Booking created', $user_id);
                
                // Create notification for user
                create_notification(
                    $user_id,
                    'booking',
                    'Booking Created',
                    'Your booking #' . $booking_id . ' has been created successfully and is pending approval.',
                    $booking_id
                );
                
                // Create notifications for vendors
                $vendor_ids = [];
                foreach ($booking_items as $item) {
                    if (!in_array($item['vendor_id'], $vendor_ids)) {
                        $vendor_ids[] = $item['vendor_id'];
                        
                        // Get vendor user ID
                        $vendor = $this->vendor_model->get_vendor_by_id($item['vendor_id']);
                        if ($vendor && $vendor->user_id) {
                            create_notification(
                                $vendor->user_id,
                                'booking',
                                'New Booking Received',
                                'You have received a new booking #' . $booking_id . '. Please review and confirm.',
                                $booking_id
                            );
                        }
                    }
                }
                
                // Create notification for admin
                $admins = $this->user_model->get_users_by_role('admin');
                if ($admins) {
                    $admin_ids = array_column($admins, 'id');
                    create_multiple_notifications(
                        $admin_ids,
                        'booking',
                        'New Booking Created',
                        'A new booking #' . $booking_id . ' has been created by a user.',
                        $booking_id
                    );
                }
                
                // Clear cart
                $this->session->unset_userdata('cart_items');
                
                $this->session->set_flashdata('success', 'Booking created successfully. Your booking ID is ' . $booking_id);
                redirect('booking/view/' . $booking_id);
            } else {
                $this->session->set_flashdata('error', 'Failed to create booking. Please try again.');
                redirect('booking/create');
            }
        }
    }

    public function view($id) {
        $user_id = $this->session->userdata('user_id');
        $user_role = $this->session->userdata('role');
        
        // Get booking details
        $data['booking'] = $this->booking_model->get_booking_by_id($id);
        
        if (!$data['booking']) {
            $this->session->set_flashdata('error', 'Booking not found.');
            redirect('booking/user_bookings');
        }
        
        // Check if user has permission to view this booking
        if ($user_role !== 'admin' && $user_role !== 'vendor' && $data['booking']->user_id != $user_id) {
            $this->session->set_flashdata('error', 'You do not have permission to view this booking.');
            redirect('booking/user_bookings');
        }
        
        // Get booking items
        $data['booking_items'] = $this->booking_model->get_booking_items($id);
        
        // Get user details
        $data['user'] = $this->user_model->get_user_by_id($data['booking']->user_id);
        
        // Get audit logs
        $data['audit_logs'] = $this->audit_model->get_entity_logs('booking', $id);
        
        // Check if vendor viewing their own booking
        $data['is_vendor_booking'] = false;
        if ($user_role === 'vendor') {
            $vendor = $this->vendor_model->get_vendor_by_user_id($user_id);
            if ($vendor) {
                foreach ($data['booking_items'] as $item) {
                    if ($item->vendor_id === $vendor->id) {
                        $data['is_vendor_booking'] = true;
                        break;
                    }
                }
            }
            
            // If not vendor's booking, redirect
            if (!$data['is_vendor_booking']) {
                $this->session->set_flashdata('error', 'You do not have permission to view this booking.');
                redirect('vendor/dashboard');
            }
        }
        
        $this->load->view('templates/header');
        $this->load->view('booking/view', $data);
        $this->load->view('templates/footer');
    }

    public function user_bookings() {
        $user_id = $this->session->userdata('user_id');
        
        // Get user bookings
        $data['bookings'] = $this->booking_model->get_user_bookings($user_id);
                // echo "<pre>"; print_r($data); die;

        $this->load->view('templates/header');
        $this->load->view('booking/user_bookings', $data);
        $this->load->view('templates/footer');
    }

    public function cancel($id) {
        $user_id = $this->session->userdata('user_id');
        $user_role = $this->session->userdata('role');
        
        // Get booking details
        $booking = $this->booking_model->get_booking_by_id($id);
        
        if (!$booking) {
            $this->session->set_flashdata('error', 'Booking not found.');
            redirect('booking/user_bookings');
        }
        
        // Check if user has permission to cancel this booking
        if ($user_role !== 'admin' && $booking->user_id != $user_id) {
            $this->session->set_flashdata('error', 'You do not have permission to cancel this booking.');
            redirect('booking/user_bookings');
        }
        
        // Check if booking can be cancelled
        if ($booking->status !== 'PENDING' && $booking->status !== 'CONFIRMED') {
            $this->session->set_flashdata('error', 'This booking cannot be cancelled.');
            redirect('booking/view/' . $id);
        }
        
        // Update booking status
        $update_data = [
            'status' => 'CANCELLED',
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $updated = $this->booking_model->update_booking($id, $update_data);
        
        if ($updated) {
            // Log the action
            $this->audit_model->log_action('booking', $id, 'status_change', 'Booking cancelled', $user_id);
            
            // Release vehicle availability
            $booking_items = $this->booking_model->get_booking_items($id);
            foreach ($booking_items as $item) {
                $this->vehicle_model->release_availability($item->vehicle_id, $item->date_from, $item->date_to, $item->qty);
            }
            
            $this->session->set_flashdata('success', 'Booking cancelled successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to cancel booking. Please try again.');
        }
        
        redirect('booking/view/' . $id);
    }

    public function add_review($id) {
        $user_id = $this->session->userdata('user_id');
        
        // Get booking details
        $booking = $this->booking_model->get_booking_by_id($id);
        
        if (!$booking) {
            $this->session->set_flashdata('error', 'Booking not found.');
            redirect('booking/user_bookings');
        }
        
        // Check if user has permission to review this booking
        if ($booking->user_id != $user_id) {
            $this->session->set_flashdata('error', 'You do not have permission to review this booking.');
            redirect('booking/user_bookings');
        }
        
        // Check if booking is completed
        if ($booking->status !== 'COMPLETED') {
            $this->session->set_flashdata('error', 'You can only review completed bookings.');
            redirect('booking/view/' . $id);
        }
        
        // Form validation rules
        $this->form_validation->set_rules('vendor_id', 'Vendor', 'trim|required|numeric');
        $this->form_validation->set_rules('rating', 'Rating', 'trim|required|numeric|greater_than[0]|less_than[6]');
        $this->form_validation->set_rules('comment', 'Comment', 'trim|required');
        
        if ($this->form_validation->run() === FALSE) {
            // Get booking items to show vendors
            $data['booking'] = $booking;
            $data['booking_items'] = $this->booking_model->get_booking_items($id);
            
            // Get unique vendors from booking items
            $vendors = [];
            foreach ($data['booking_items'] as $item) {
                if (!isset($vendors[$item->vendor_id])) {
                    $vendor = $this->vendor_model->get_vendor_by_id($item->vendor_id);
                    if ($vendor) {
                        $vendors[$item->vendor_id] = $vendor;
                    }
                }
            }
            $data['vendors'] = $vendors;
            
            $this->load->view('templates/header');
            $this->load->view('booking/add_review', $data);
            $this->load->view('templates/footer');
        } else {
            $vendor_id = $this->input->post('vendor_id');
            $rating = $this->input->post('rating');
            $comment = $this->input->post('comment');
            
            // Check if vendor exists in booking items
            $booking_items = $this->booking_model->get_booking_items($id);
            $vendor_exists = false;
            foreach ($booking_items as $item) {
                if ($item->vendor_id == $vendor_id) {
                    $vendor_exists = true;
                    break;
                }
            }
            
            if (!$vendor_exists) {
                $this->session->set_flashdata('error', 'Invalid vendor selected.');
                redirect('booking/add_review/' . $id);
            }
            
            // Prepare review data
            $review_data = [
                'booking_id' => $id,
                'user_id' => $user_id,
                'vendor_id' => $vendor_id,
                'rating' => $rating,
                'comment' => $comment,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            // Add review
            $review_id = $this->booking_model->add_review($review_data);
            
            if ($review_id) {
                // Log the action
                $this->audit_model->log_action('review', $review_id, 'create', 'Review added for booking #' . $id, $user_id);
                
                $this->session->set_flashdata('success', 'Review added successfully.');
                redirect('booking/view/' . $id);
            } else {
                $this->session->set_flashdata('error', 'Failed to add review. Please try again.');
                redirect('booking/add_review/' . $id);
            }
        }
    }
}