<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicle_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Add a new vehicle
     * 
     * @param array $data Vehicle data
     * @return int|bool Vehicle ID on success, FALSE on failure
     */
    public function add_vehicle($data) {
        $this->db->insert('vehicles', $data);
        return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : FALSE;
    }

    /**
     * Get vehicle by ID
     * 
     * @param int $id Vehicle ID
     * @return object|bool Vehicle object on success, FALSE if not found
     */
    public function get_vehicle_by_id($id) {
        $query = $this->db->get_where('vehicles', ['id' => $id]);
        return ($query->num_rows() > 0) ? $query->row() : FALSE;
    }

    /**
     * Update vehicle
     * 
     * @param int $id Vehicle ID
     * @param array $data Vehicle data to update
     * @return bool TRUE on success, FALSE on failure
     */
    public function update_vehicle($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('vehicles', $data);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * Delete vehicle
     * 
     * @param int $id Vehicle ID
     * @return bool TRUE on success, FALSE on failure
     */
    public function delete_vehicle($id) {
        $this->db->where('id', $id);
        $this->db->delete('vehicles');
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * Get all vehicles with optional filtering
     * 
     * @param array $filters Optional filters
     * @param int $limit Limit results
     * @param int $offset Offset for pagination
     * @return array Array of vehicle objects
     */
    public function get_vehicles($filters = [], $limit = NULL, $offset = NULL) {
        if (!empty($filters)) {
            $this->db->where($filters);
        }
        
        if ($limit !== NULL && $offset !== NULL) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get('vehicles');
        return $query->result();
    }

    /**
     * Count vehicles with optional filtering
     * 
     * @param array $filters Optional filters
     * @return int Number of vehicles
     */
    public function count_vehicles($filters = []) {
        if (!empty($filters)) {
            $this->db->where($filters);
        }
        
        return $this->db->count_all_results('vehicles');
    }

    /**
     * Add vehicle availability
     * 
     * @param array $data Availability data
     * @return int|bool Availability ID on success, FALSE on failure
     */
    public function add_vehicle_availability($data) {
        $this->db->insert('vehicle_availability', $data);
        return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : FALSE;
    }

    /**
     * Update vehicle availability
     * 
     * @param int $vehicle_id Vehicle ID
     * @param int $quantity New quantity
     * @return bool TRUE on success, FALSE on failure
     */
    public function update_vehicle_availability($vehicle_id, $quantity) {
        $this->db->where('vehicle_id', $vehicle_id);
        $this->db->update('vehicle_availability', ['quantity' => $quantity]);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * Check vehicle availability for a specific date range
     * 
     * @param int $vehicle_id Vehicle ID
     * @param string $date_from Start date (Y-m-d)
     * @param string $date_to End date (Y-m-d)
     * @param int $quantity Required quantity
     * @return bool TRUE if available, FALSE if not
     */
    public function check_availability($vehicle_id, $date_from, $date_to, $quantity = 1) {
        // Convert dates to proper format
        $start_date = date('Y-m-d', strtotime($date_from));
        $end_date = date('Y-m-d', strtotime($date_to));
        
        // Get all dates between start and end date
        $dates = [];
        $current_date = $start_date;
        
        while (strtotime($current_date) <= strtotime($end_date)) {
            $dates[] = $current_date;
            $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
        }
        
        // Check availability for each date
        foreach ($dates as $date) {
            $this->db->select('quantity');
            $this->db->from('vehicle_availability');
            $this->db->where('vehicle_id', $vehicle_id);
            $this->db->where('date', $date);
            $query = $this->db->get();
            
            if ($query->num_rows() === 0 || $query->row()->quantity < $quantity) {
                return FALSE; // Not available for this date
            }
        }
        
        return TRUE; // Available for all dates
    }

    /**
     * Search vehicles by criteria
     * 
     * @param array $criteria Search criteria
     * @param int $limit Limit results
     * @param int $offset Offset for pagination
     * @return array Array of vehicle objects
     */
    public function search_vehicles($criteria, $limit = NULL, $offset = NULL) {
        $this->db->select('vehicles.*, vendors.business_name as shop_name');
        $this->db->from('vehicles');
        $this->db->join('vendors', 'vendors.id = vehicles.vendor_id');
        $this->db->where('vehicles.is_active', 1);
        $this->db->where('vendors.status', 'approved');
        
        // Filter by type if specified
        if (!empty($criteria['type'])) {
            $this->db->where('vehicles.type', $criteria['type']);
        }
        
        // Filter by capacity if specified
        if (!empty($criteria['capacity'])) {
            $this->db->where('vehicles.capacity >=', $criteria['capacity']);
        }
        
        // Filter by date availability if specified
        if (!empty($criteria['date_from']) && !empty($criteria['date_to'])) {
            $start_date = date('Y-m-d', strtotime($criteria['date_from']));
            $end_date = date('Y-m-d', strtotime($criteria['date_to']));
            
            // Get all dates between start and end date
            $dates = [];
            $current_date = $start_date;
            
            while (strtotime($current_date) <= strtotime($end_date)) {
                $dates[] = $current_date;
                $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
            }
            
            // Join with vehicle_availability for each date
            foreach ($dates as $index => $date) {
                $this->db->join("vehicle_availability as va{$index}", "va{$index}.vehicle_id = vehicles.id");
                $this->db->where("va{$index}.date", $date);
                
                if (!empty($criteria['quantity'])) {
                    $this->db->where("va{$index}.quantity >=", $criteria['quantity']);
                } else {
                    $this->db->where("va{$index}.quantity >=", 1);
                }
            }
        }
        
        if ($limit !== NULL && $offset !== NULL) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Count search results
     * 
     * @param array $criteria Search criteria
     * @return int Number of vehicles matching criteria
     */
    public function count_search_results($criteria) {
        $this->db->from('vehicles');
        $this->db->join('vendors', 'vendors.id = vehicles.vendor_id');
        $this->db->where('vehicles.is_active', 1);
        $this->db->where('vendors.status', 'approved');
        
        // Filter by type if specified
        if (!empty($criteria['type'])) {
            $this->db->where('vehicles.type', $criteria['type']);
        }
        
        // Filter by capacity if specified
        if (!empty($criteria['capacity'])) {
            $this->db->where('vehicles.capacity >=', $criteria['capacity']);
        }
        
        // Filter by date availability if specified
        if (!empty($criteria['date_from']) && !empty($criteria['date_to'])) {
            $start_date = date('Y-m-d', strtotime($criteria['date_from']));
            $end_date = date('Y-m-d', strtotime($criteria['date_to']));
            
            // Get all dates between start and end date
            $dates = [];
            $current_date = $start_date;
            
            while (strtotime($current_date) <= strtotime($end_date)) {
                $dates[] = $current_date;
                $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
            }
            
            // Join with vehicle_availability for each date
            foreach ($dates as $index => $date) {
                $this->db->join("vehicle_availability as va{$index}", "va{$index}.vehicle_id = vehicles.id");
                $this->db->where("va{$index}.date", $date);
                
                if (!empty($criteria['quantity'])) {
                    $this->db->where("va{$index}.quantity >=", $criteria['quantity']);
                } else {
                    $this->db->where("va{$index}.quantity >=", 1);
                }
            }
        }
        
        return $this->db->count_all_results();
    }

    /**
     * Update vehicle availability after booking
     * 
     * @param int $vehicle_id Vehicle ID
     * @param string $date_from Start date (Y-m-d)
     * @param string $date_to End date (Y-m-d)
     * @param int $quantity Quantity to reduce
     * @return bool TRUE on success, FALSE on failure
     */
    public function update_availability_after_booking($vehicle_id, $date_from, $date_to, $quantity = 1) {
        // Convert dates to proper format
        $start_date = date('Y-m-d', strtotime($date_from));
        $end_date = date('Y-m-d', strtotime($date_to));
        
        // Get all dates between start and end date
        $dates = [];
        $current_date = $start_date;
        
        while (strtotime($current_date) <= strtotime($end_date)) {
            $dates[] = $current_date;
            $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
        }
        
        // Update availability for each date
        foreach ($dates as $date) {
            $this->db->set('quantity', 'quantity - ' . $quantity, FALSE);
            $this->db->where('vehicle_id', $vehicle_id);
            $this->db->where('date', $date);
            $this->db->update('vehicle_availability');
        }
        
        return TRUE;
    }
    
    /**
     * Get recommended vehicles for user dashboard
     * 
     * @param int $limit Number of vehicles to return
     * @return array Array of vehicle objects
     */
    public function get_recommended_vehicles($limit = 4) {
        // Get vehicles with highest ratings or most bookings
        $this->db->select('vehicles.*');
        $this->db->from('vehicles');
        $this->db->where('vehicles.status', 'active');
        
        // Order by rating if available, otherwise by newest
        $this->db->order_by('vehicles.rating', 'DESC');
        $this->db->order_by('vehicles.created_at', 'DESC');
        
        $this->db->limit($limit);
        
        $query = $this->db->get();
        // echo $this->db->last_query(); die;
        return $query->result();
    }
}