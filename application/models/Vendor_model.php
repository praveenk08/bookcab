<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendor_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Add a new vendor
     * 
     * @param array $data Vendor data
     * @return int|bool Vendor ID on success, FALSE on failure
     */
    public function add_vendor($data) {
        $this->db->insert('vendors', $data);
        return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : FALSE;
    }

    /**
     * Get vendor by ID
     * 
     * @param int $id Vendor ID
     * @return object|bool Vendor object on success, FALSE if not found
     */
    public function get_vendor_by_id($id) {
        $this->db->select('vendors.*, users.name, users.email, users.phone');
        $this->db->from('vendors');
        $this->db->join('users', 'users.id = vendors.user_id');
        $this->db->where('vendors.id', $id);
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->row() : FALSE;
    }

    /**
     * Get vendor ID by user ID
     * 
     * @param int $user_id User ID
     * @return int|bool Vendor ID on success, FALSE if not found
     */
    public function get_vendor_id_by_user_id($user_id) {
        $query = $this->db->get_where('vendors', ['user_id' => $user_id]);
        return ($query->num_rows() > 0) ? $query->row()->id : FALSE;
    }

    /**
     * Update vendor
     * 
     * @param int $id Vendor ID
     * @param array $data Vendor data to update
     * @return bool TRUE on success, FALSE on failure
     */
    public function update_vendor($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('vendors', $data);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * Get all vendors with optional filtering
     * 
     * @param array $filters Optional filters
     * @param int $limit Limit results
     * @param int $offset Offset for pagination
     * @return array Array of vendor objects
     */
    public function get_vendors($filters = [], $limit = NULL, $offset = NULL) {
        $this->db->select('vendors.*, users.name, users.email, users.phone');
        $this->db->from('vendors');
        $this->db->join('users', 'users.id = vendors.user_id');
        
        if (!empty($filters)) {
            $this->db->where($filters);
        }
        
        if ($limit !== NULL && $offset !== NULL) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get();
        $vendors = $query->result();
        
        // Ensure business_name and owner_name properties exist
        foreach ($vendors as $vendor) {
            if (!isset($vendor->business_name)) {
                $vendor->business_name = 'Unknown Business';
            }
            if (!isset($vendor->owner_name)) {
                $vendor->owner_name = $vendor->name ?? 'Unknown Owner';
            }
        }
        
        return $vendors;
    }

    /**
     * Count vendors with optional filtering
     * 
     * @param array $filters Optional filters
     * @return int Number of vendors
     */
    public function count_vendors($filters = []) {
        $this->db->from('vendors');
        $this->db->join('users', 'users.id = vendors.user_id', 'left');
        
        if (!empty($filters)) {
            // Handle search filter separately
            if (isset($filters['search']) && !empty($filters['search'])) {
                $search = $filters['search'];
                unset($filters['search']);
                
                $this->db->group_start();
                $this->db->like('vendors.business_name', $search);
                $this->db->or_like('users.name', $search);
                $this->db->or_like('users.email', $search);
                $this->db->or_like('vendors.phone', $search);
                $this->db->or_like('vendors.city', $search);
                $this->db->group_end();
            }
            
            // Apply other filters with table prefixes to avoid ambiguity
            if (!empty($filters)) {
                // Fix for ambiguous status column
                if (isset($filters['status'])) {
                    $status = $filters['status'];
                    unset($filters['status']);
                    $this->db->where('vendors.status', $status);
                }
                
                if (!empty($filters)) {
                    $this->db->where($filters);
                }
            }
        }
        
        return $this->db->count_all_results();
    }
    
    /**
     * Get vendors for DataTables with filtering, sorting and pagination
     * 
     * @param array $filters Optional filters including search
     * @param int $limit Limit results
     * @param int $offset Offset for pagination
     * @param string $order_by Column to order by
     * @param string $order_dir Order direction (asc/desc)
     * @return array Array of vendor objects
     */
    public function get_vendors_datatable($filters = [], $limit = NULL, $offset = NULL, $order_by = 'id', $order_dir = 'desc') {
        // Select vendor data and join with users table
        $this->db->select('vendors.*, users.name as user_name, users.email as user_email, users.phone as user_phone');
        $this->db->from('vendors');
        $this->db->join('users', 'users.id = vendors.user_id', 'left');
        
        // Get vehicle count for each vendor
        $this->db->select('(SELECT COUNT(*) FROM vehicles WHERE vehicles.vendor_id = vendors.id) as vehicle_count');
        
        if (!empty($filters)) {
            // Handle search filter separately
            if (isset($filters['search']) && !empty($filters['search'])) {
                $search = $filters['search'];
                unset($filters['search']);
                
                $this->db->group_start();
                $this->db->like('vendors.business_name', $search);
                $this->db->or_like('users.name', $search);
                $this->db->or_like('users.email', $search);
                $this->db->or_like('vendors.phone', $search);
                $this->db->or_like('vendors.city', $search);
                $this->db->group_end();
            }
            
            // Apply other filters with table prefixes to avoid ambiguity
            if (!empty($filters)) {
                // Fix for ambiguous status column
                if (isset($filters['status'])) {
                    $status = $filters['status'];
                    unset($filters['status']);
                    $this->db->where('vendors.status', $status);
                }
                
                if (!empty($filters)) {
                    $this->db->where($filters);
                }
            }
        }
        
        // Apply ordering
        if ($order_by == 'name') {
            $this->db->order_by('users.name', $order_dir);
        } else if ($order_by == 'vehicle_count') {
            $this->db->order_by('vehicle_count', $order_dir);
        } else {
            $this->db->order_by('vendors.' . $order_by, $order_dir);
        }
        
        // Apply limit and offset
        if ($limit !== NULL && $offset !== NULL) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get();
        $vendors = $query->result();
        
        // Ensure business_name and owner_name properties exist
        foreach ($vendors as $vendor) {
            if (!isset($vendor->business_name)) {
                $vendor->business_name = 'Unknown Business';
            }
            if (!isset($vendor->owner_name)) {
                $vendor->owner_name = $vendor->user_name ?? 'Unknown Owner';
            }
        }
        
        return $vendors;
    }

    /**
     * Get bookings for a specific vendor
     * 
     * @param int $vendor_id Vendor ID
     * @param array $filters Optional filters (status, date_from, date_to)
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
        
        // Apply filters
        if (isset($filters['status']) && !empty($filters['status'])) {
            $this->db->where('bookings.status', $filters['status']);
        }
        
        if (isset($filters['date_from']) && !empty($filters['date_from'])) {
            $this->db->where('bookings.pickup_date >=', $filters['date_from']);
        }
        
        if (isset($filters['date_to']) && !empty($filters['date_to'])) {
            $this->db->where('bookings.pickup_date <=', $filters['date_to']);
        }
        
        if (!empty($filters) && !isset($filters['status']) && !isset($filters['date_from']) && !isset($filters['date_to'])) {
            $this->db->where($filters);
        }
        
        $this->db->group_by('bookings.id');
        $this->db->order_by('bookings.created_at', 'DESC');
        
        if ($limit !== NULL && $offset !== NULL) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get();
        $bookings = $query->result();
        
        // Ensure properties exist for each booking
        foreach ($bookings as $booking) {
            // Ensure total_amount property exists
            if (!isset($booking->total_amount)) {
                $booking->total_amount = 0;
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
     * Count vendor bookings
     * 
     * @param int $vendor_id Vendor ID
     * @param array $filters Optional filters
     * @return int Number of bookings
     */
    public function count_vendor_bookings($vendor_id, $filters = []) {
        $this->db->from('bookings');
        $this->db->join('booking_items', 'booking_items.booking_id = bookings.id');
        $this->db->where('booking_items.vendor_id', $vendor_id);
        
        if (!empty($filters)) {
            $this->db->where($filters);
        }
        
        $this->db->group_by('bookings.id');
        
        return $this->db->count_all_results();
    }
}