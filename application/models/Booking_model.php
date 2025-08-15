<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Create a new booking with transaction support
     * 
     * @param array $booking_data Booking data
     * @param array $booking_items Array of booking items
     * @return int|bool Booking ID on success, FALSE on failure
     */
    public function create_booking($booking_data, $booking_items) {
        // Start transaction
        $this->db->trans_start();
        
        // Insert booking
        $this->db->insert('bookings', $booking_data);
        $booking_id = $this->db->insert_id();
        
        // Insert booking items
        foreach ($booking_items as &$item) {
            $item['booking_id'] = $booking_id;
            $this->db->insert('booking_items', $item);
            
            // Update vehicle availability
            $this->load->model('vehicle_model');
            $this->vehicle_model->update_availability_after_booking(
                $item['vehicle_id'],
                $item['date_from'],
                $item['date_to'],
                $item['qty']
            );
        }
        
        // Complete transaction
        $this->db->trans_complete();
        
        // Return booking ID if transaction successful
        return ($this->db->trans_status() === TRUE) ? $booking_id : FALSE;
    }

    /**
     * Get booking by ID
     * 
     * @param int $id Booking ID
     * @return object|bool Booking object on success, FALSE if not found
     */
    public function get_booking_by_id($id) {
        $this->db->select('bookings.*, users.name as user_name, users.email as user_email, users.phone as user_phone');
        $this->db->from('bookings');
        $this->db->join('users', 'users.id = bookings.user_id');
        $this->db->where('bookings.id', $id);
        $query = $this->db->get();
        
        if ($query->num_rows() === 0) {
            return FALSE;
        }
        
        $booking = $query->row();
        $booking->items = $this->get_booking_items($id);
        
        // Ensure total_amount property exists
        if (!isset($booking->total_amount)) {
            $booking->total_amount = 0;
            // Calculate from items if available
            foreach ($booking->items as $item) {
                if (isset($item->subtotal)) {
                    $booking->total_amount += $item->subtotal;
                }
            }
        }
        
        // Ensure from_date and to_date properties exist
        if (!isset($booking->from_date)) {
            $booking->from_date = date('Y-m-d');
        }
        if (!isset($booking->to_date)) {
            $booking->to_date = date('Y-m-d', strtotime('+1 day'));
        }
        
        return $booking;
    }

    /**
     * Get booking items
     * 
     * @param int $booking_id Booking ID
     * @return array Array of booking item objects
     */
    public function get_booking_items($booking_id) {
        $this->db->select('booking_items.*, vehicles.title as vehicle_title, vehicles.type as vehicle_type, vendors.business_name as vendor_name, drivers.name as driver_name, drivers.license_number');
        $this->db->from('booking_items');
        $this->db->join('vehicles', 'vehicles.id = booking_items.vehicle_id');
        $this->db->join('vendors', 'vendors.id = booking_items.vendor_id');
        $this->db->join('drivers', 'drivers.id = booking_items.driver_id', 'left');
        $this->db->where('booking_items.booking_id', $booking_id);
        $query = $this->db->get();
        
        return $query->result();
    }

    /**
     * Update booking status
     * 
     * @param int $id Booking ID
     * @param string $status New status
     * @return bool TRUE on success, FALSE on failure
     */
    public function update_booking_status($id, $status) {
        $this->db->where('id', $id);
        $this->db->update('bookings', ['status' => $status]);
        
        if ($this->db->affected_rows() > 0) {
            // Log status change in audit_logs
            $this->load->model('audit_model');
            $this->audit_model->log_action(
                $this->session->userdata('user_id'),
                'booking',
                $id,
                'status_change',
                NULL,
                $status
            );
            
            return TRUE;
        }
        
        return FALSE;
    }

    /**
     * Get user bookings
     * 
     * @param int $user_id User ID
     * @param array $filters Optional filters
     * @param int $limit Limit results
     * @param int $offset Offset for pagination
     * @return array Array of booking objects
     */
    public function get_user_bookings($user_id, $filters = [], $limit = NULL, $offset = NULL) {
        $this->db->select('bookings.*');
        $this->db->from('bookings');
        $this->db->where('bookings.user_id', $user_id);
        
        if (!empty($filters)) {
            $this->db->where($filters);
        }
        
        if ($limit !== NULL && $offset !== NULL) {
            $this->db->limit($limit, $offset);
        }
        
        $this->db->order_by('bookings.created_at', 'DESC');
        $query = $this->db->get();
        
        $bookings = $query->result();
        
        // Get booking items for each booking
        foreach ($bookings as $booking) {
            $booking->items = $this->get_booking_items($booking->id);
        }
        
        return $bookings;
    }

    /**
     * Count user bookings
     * 
     * @param int $user_id User ID
     * @param array $filters Optional filters
     * @return int Number of bookings
     */
    public function count_user_bookings($user_id, $filters = []) {
        $this->db->from('bookings');
        $this->db->where('user_id', $user_id);
        
        if (!empty($filters)) {
            $this->db->where($filters);
        }
        
        return $this->db->count_all_results();
    }

    /**
     * Get all bookings with optional filtering
     * 
     * @param array $filters Optional filters
     * @param int $limit Limit results
     * @param int $offset Offset for pagination
     * @return array Array of booking objects
     */
    public function get_bookings($filters = [], $limit = NULL, $offset = NULL) {
        $this->db->select('bookings.*, users.name as user_name, users.email as user_email, users.phone as user_phone');
        $this->db->from('bookings');
        $this->db->join('users', 'users.id = bookings.user_id');
        
        if (!empty($filters)) {
            $this->db->where($filters);
        }
        
        if ($limit !== NULL && $offset !== NULL) {
            $this->db->limit($limit, $offset);
        }
        
        $this->db->order_by('bookings.created_at', 'DESC');
        $query = $this->db->get();
        
        $bookings = $query->result();
        
        // Get booking items for each booking
        foreach ($bookings as $booking) {
            $booking->items = $this->get_booking_items($booking->id);
            
            // Ensure total_amount property exists
            if (!isset($booking->total_amount)) {
                $booking->total_amount = 0;
                // Calculate from items if available
                foreach ($booking->items as $item) {
                    if (isset($item->subtotal)) {
                        $booking->total_amount += $item->subtotal;
                    }
                }
            }
            
            // Ensure from_date and to_date properties exist
            if (!isset($booking->from_date)) {
                $booking->from_date = date('Y-m-d');
            }
            if (!isset($booking->to_date)) {
                $booking->to_date = date('Y-m-d', strtotime('+1 day'));
            }
        }
        
        return $bookings;
    }

    /**
     * Count bookings with optional filtering
     * 
     * @param array $filters Optional filters
     * @return int Number of bookings
     */
    public function count_bookings($filters = []) {
        $this->db->from('bookings');
        
        if (!empty($filters)) {
            $this->db->where($filters);
        }
        
        return $this->db->count_all_results();
    }
    
    /**
     * Get vendor bookings
     * 
     * @param int $vendor_id Vendor ID
     * @param array $filters Optional filters
     * @param int $limit Limit results
     * @param int $offset Offset for pagination
     * @return array Array of booking objects
     */
    public function get_vendor_bookings($vendor_id, $filters = [], $limit = NULL, $offset = NULL) {
        $this->db->select('bookings.*, users.name as user_name, users.email as user_email, users.phone as user_phone');
        $this->db->from('bookings');
        $this->db->join('booking_items', 'booking_items.booking_id = bookings.id');
        $this->db->join('users', 'users.id = bookings.user_id');
        $this->db->where('booking_items.vendor_id', $vendor_id);
        
        if (!empty($filters)) {
            $this->db->where($filters);
        }
        
        $this->db->group_by('bookings.id');
        
        if ($limit !== NULL && $offset !== NULL) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get();
        $bookings = $query->result();
        
        // Get booking items for each booking
        foreach ($bookings as $booking) {
            $booking->items = $this->get_booking_items($booking->id);
            
            // Ensure total_amount property exists
            if (!isset($booking->total_amount)) {
                $booking->total_amount = 0;
                // Calculate from items if available
                foreach ($booking->items as $item) {
                    if (isset($item->subtotal)) {
                        $booking->total_amount += $item->subtotal;
                    }
                }
            }
            
            // Ensure from_date and to_date properties exist
            if (!isset($booking->from_date)) {
                $booking->from_date = date('Y-m-d');
            }
            if (!isset($booking->to_date)) {
                $booking->to_date = date('Y-m-d', strtotime('+1 day'));
            }
        }
        
        return $bookings;
    }

    /**
     * Add payment for booking
     * 
     * @param array $payment_data Payment data
     * @return int|bool Payment ID on success, FALSE on failure
     */
    public function add_payment($payment_data) {
        $this->db->insert('payments', $payment_data);
        return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : FALSE;
    }

    /**
     * Update payment status
     * 
     * @param int $id Payment ID
     * @param string $status New status
     * @return bool TRUE on success, FALSE on failure
     */
    public function update_payment_status($id, $status) {
        $this->db->where('id', $id);
        $this->db->update('payments', ['status' => $status]);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * Get payment by booking ID
     * 
     * @param int $booking_id Booking ID
     * @return object|bool Payment object on success, FALSE if not found
     */
    public function get_payment_by_booking_id($booking_id) {
        $query = $this->db->get_where('payments', ['booking_id' => $booking_id]);
        return ($query->num_rows() > 0) ? $query->row() : FALSE;
    }

    /**
     * Add review for booking
     * 
     * @param array $review_data Review data
     * @return int|bool Review ID on success, FALSE on failure
     */
    public function add_review($review_data) {
        $this->db->insert('reviews', $review_data);
        return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : FALSE;
    }

    /**
     * Get review by booking ID
     * 
     * @param int $booking_id Booking ID
     * @return object|bool Review object on success, FALSE if not found
     */
    public function get_review_by_booking_id($booking_id) {
        $query = $this->db->get_where('reviews', ['booking_id' => $booking_id]);
        return ($query->num_rows() > 0) ? $query->row() : FALSE;
    }
    
    /**
     * Get reviews for a specific vehicle
     * 
     * @param int $vehicle_id Vehicle ID
     * @param int $limit Limit results
     * @param int $offset Offset for pagination
     * @return array Array of review objects
     */
    public function get_vehicle_reviews($vehicle_id, $limit = NULL, $offset = NULL) {
        $this->db->select('reviews.*, users.name as user_name');
        $this->db->from('reviews');
        $this->db->join('booking_items', 'reviews.booking_id = booking_items.booking_id');
        $this->db->join('bookings', 'booking_items.booking_id = bookings.id');
        $this->db->join('users', 'bookings.user_id = users.id');
        $this->db->where('booking_items.vehicle_id', $vehicle_id);
        $this->db->group_by('reviews.id'); // Avoid duplicates if multiple items with same vehicle
        
        if ($limit !== NULL && $offset !== NULL) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get();
        return $query->result();
    }
}