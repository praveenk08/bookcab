<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * Get booking report based on filters
     * 
     * @param array $filters Filter parameters
     * @return array Report data
     */
    public function get_booking_report($filters = array()) {
        // Initialize variables
        $data = array();
        $where = "1=1";
        $group_by = "";
        
        // Apply date filters
        if (isset($filters['date_range'])) {
            switch ($filters['date_range']) {
                case 'today':
                    $where .= " AND DATE(b.created_at) = CURDATE()";
                    break;
                case 'yesterday':
                    $where .= " AND DATE(b.created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
                    break;
                case 'this_week':
                    $where .= " AND YEARWEEK(b.created_at, 1) = YEARWEEK(CURDATE(), 1)";
                    break;
                case 'last_week':
                    $where .= " AND YEARWEEK(b.created_at, 1) = YEARWEEK(DATE_SUB(CURDATE(), INTERVAL 1 WEEK), 1)";
                    break;
                case 'this_month':
                    $where .= " AND YEAR(b.created_at) = YEAR(CURDATE()) AND MONTH(b.created_at) = MONTH(CURDATE())";
                    break;
                case 'last_month':
                    $where .= " AND YEAR(b.created_at) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND MONTH(b.created_at) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))";
                    break;
                case 'this_year':
                    $where .= " AND YEAR(b.created_at) = YEAR(CURDATE())";
                    break;
                case 'last_year':
                    $where .= " AND YEAR(b.created_at) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 YEAR))";
                    break;
                case 'custom':
                    if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
                        $where .= " AND DATE(b.created_at) BETWEEN '" . $filters['start_date'] . "' AND '" . $filters['end_date'] . "'";
                    }
                    break;
            }
        }
        
        // Apply booking status filter
        if (isset($filters['booking_status']) && $filters['booking_status'] != 'all') {
            $where .= " AND b.status = '" . $filters['booking_status'] . "'";
        }
        
        // Apply vendor filter
        if (isset($filters['vendor_id']) && $filters['vendor_id'] != 'all') {
            $where .= " AND v.id = " . $this->db->escape($filters['vendor_id']);
        }
        
        // Apply payment method filter
        if (isset($filters['payment_method']) && $filters['payment_method'] != 'all') {
            $where .= " AND p.payment_method = '" . $filters['payment_method'] . "'";
        }
        
        // Apply grouping
        if (isset($filters['group_by'])) {
            switch ($filters['group_by']) {
                case 'day':
                    $group_by = "DATE(b.created_at)";
                    $select = "DATE(b.created_at) AS group_label, COUNT(b.id) AS total_bookings, SUM(b.total_amount) AS total_amount";
                    break;
                case 'week':
                    $group_by = "YEARWEEK(b.created_at, 1)";
                    $select = "CONCAT('Week ', WEEK(b.created_at)) AS group_label, COUNT(b.id) AS total_bookings, SUM(b.total_amount) AS total_amount";
                    break;
                case 'month':
                    $group_by = "YEAR(b.created_at), MONTH(b.created_at)";
                    $select = "DATE_FORMAT(b.created_at, '%M %Y') AS group_label, COUNT(b.id) AS total_bookings, SUM(b.total_amount) AS total_amount";
                    break;
                case 'vendor':
                    $group_by = "v.id";
                    $select = "v.business_name AS group_label, COUNT(b.id) AS total_bookings, SUM(b.total_amount) AS total_amount";
                    break;
                case 'vehicle_type':
                    $group_by = "veh.type";
                    $select = "veh.type AS group_label, COUNT(b.id) AS total_bookings, SUM(b.total_amount) AS total_amount";
                    break;
                case 'status':
                    $group_by = "b.status";
                    $select = "b.status AS group_label, COUNT(b.id) AS total_bookings, SUM(b.total_amount) AS total_amount";
                    break;
                default:
                    $group_by = "DATE(b.created_at)";
                    $select = "DATE(b.created_at) AS group_label, COUNT(b.id) AS total_bookings, SUM(b.total_amount) AS total_amount";
            }
        } else {
            $select = "b.*, u.name AS customer_name, v.business_name AS vendor_name, veh.title AS vehicle_name, p.payment_method, p.status AS payment_status";
        }
        
        // Build query
        $this->db->select($select);
        $this->db->from('bookings b');
        $this->db->join('users u', 'u.id = b.user_id', 'left');
        $this->db->join('booking_items bi', 'bi.booking_id = b.id', 'left');
        $this->db->join('vehicles veh', 'veh.id = bi.vehicle_id', 'left');
        $this->db->join('vendors v', 'v.id = veh.vendor_id', 'left');
        $this->db->join('payments p', 'p.booking_id = b.id', 'left');
        $this->db->where($where);
        
        if (!empty($group_by)) {
            $this->db->group_by($group_by);
            $query = $this->db->get();
            $data['summary'] = $query->result();
            
            // Get totals
            $this->db->select('COUNT(b.id) AS total_bookings, SUM(b.total_amount) AS total_amount');
            $this->db->from('bookings b');
            $this->db->join('users u', 'u.id = b.user_id', 'left');
            $this->db->join('booking_items bi', 'bi.booking_id = b.id', 'left');
            $this->db->join('vehicles veh', 'veh.id = bi.vehicle_id', 'left');
            $this->db->join('vendors v', 'v.id = veh.vendor_id', 'left');
            $this->db->join('payments p', 'p.booking_id = b.id', 'left');
            $this->db->where($where);
            $query = $this->db->get();
            $data['totals'] = $query->row();
        } else {
            // For detailed report
            $query = $this->db->get();
            $data['details'] = $query->result();
            
            // Get totals
            $this->db->select('COUNT(b.id) AS total_bookings, SUM(b.total_amount) AS total_amount');
            $this->db->from('bookings b');
            $this->db->where($where);
            $query = $this->db->get();
            $data['totals'] = $query->row();
        }
        
        return $data;
    }
    
    /**
     * Get revenue report based on filters
     * 
     * @param array $filters Filter parameters
     * @return array Report data
     */
    public function get_revenue_report($filters = array()) {
        // Initialize variables
        $data = array();
        $where = "1=1";
        $group_by = "";
        
        // Apply date filters
        if (isset($filters['date_range'])) {
            switch ($filters['date_range']) {
                case 'today':
                    $where .= " AND DATE(p.created_at) = CURDATE()";
                    break;
                case 'yesterday':
                    $where .= " AND DATE(p.created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
                    break;
                case 'this_week':
                    $where .= " AND YEARWEEK(p.created_at, 1) = YEARWEEK(CURDATE(), 1)";
                    break;
                case 'last_week':
                    $where .= " AND YEARWEEK(p.created_at, 1) = YEARWEEK(DATE_SUB(CURDATE(), INTERVAL 1 WEEK), 1)";
                    break;
                case 'this_month':
                    $where .= " AND YEAR(p.created_at) = YEAR(CURDATE()) AND MONTH(p.created_at) = MONTH(CURDATE())";
                    break;
                case 'last_month':
                    $where .= " AND YEAR(p.created_at) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND MONTH(p.created_at) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))";
                    break;
                case 'this_year':
                    $where .= " AND YEAR(p.created_at) = YEAR(CURDATE())";
                    break;
                case 'last_year':
                    $where .= " AND YEAR(p.created_at) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 YEAR))";
                    break;
                case 'custom':
                    if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
                        $where .= " AND DATE(p.created_at) BETWEEN '" . $filters['start_date'] . "' AND '" . $filters['end_date'] . "'";
                    }
                    break;
            }
        }
        
        // Apply payment status filter
        if (isset($filters['payment_status']) && $filters['payment_status'] != 'all') {
            $where .= " AND p.status = '" . $filters['payment_status'] . "'";
        }
        
        // Apply vendor filter
        if (isset($filters['vendor_id']) && $filters['vendor_id'] != 'all') {
            $where .= " AND v.id = " . $this->db->escape($filters['vendor_id']);
        }
        
        // Apply payment method filter
        if (isset($filters['payment_method']) && $filters['payment_method'] != 'all') {
            $where .= " AND p.payment_method = '" . $filters['payment_method'] . "'";
        }
        
        // Apply grouping
        if (isset($filters['group_by'])) {
            switch ($filters['group_by']) {
                case 'day':
                    $group_by = "DATE(p.created_at)";
                    $select = "DATE(p.created_at) AS group_label, COUNT(p.id) AS total_payments, SUM(p.amount) AS total_amount";
                    break;
                case 'week':
                    $group_by = "YEARWEEK(p.created_at, 1)";
                    $select = "CONCAT('Week ', WEEK(p.created_at)) AS group_label, COUNT(p.id) AS total_payments, SUM(p.amount) AS total_amount";
                    break;
                case 'month':
                    $group_by = "YEAR(p.created_at), MONTH(p.created_at)";
                    $select = "DATE_FORMAT(p.created_at, '%M %Y') AS group_label, COUNT(p.id) AS total_payments, SUM(p.amount) AS total_amount";
                    break;
                case 'vendor':
                    $group_by = "v.id";
                    $select = "v.business_name AS group_label, COUNT(p.id) AS total_payments, SUM(p.amount) AS total_amount";
                    break;
                case 'payment_method':
                    $group_by = "p.payment_method";
                    $select = "p.payment_method AS group_label, COUNT(p.id) AS total_payments, SUM(p.amount) AS total_amount";
                    break;
                default:
                    $group_by = "DATE(p.created_at)";
                    $select = "DATE(p.created_at) AS group_label, COUNT(p.id) AS total_payments, SUM(p.amount) AS total_amount";
            }
        } else {
            $select = "p.*, b.booking_code, u.name AS customer_name, v.business_name AS vendor_name";
        }
        
        // Build query
        $this->db->select($select);
        $this->db->from('payments p');
        $this->db->join('bookings b', 'b.id = p.booking_id', 'left');
        $this->db->join('users u', 'u.id = b.user_id', 'left');
        $this->db->join('booking_items bi', 'bi.booking_id = b.id', 'left');
        $this->db->join('vehicles veh', 'veh.id = bi.vehicle_id', 'left');
        $this->db->join('vendors v', 'v.id = veh.vendor_id', 'left');
        $this->db->where($where);
        
        if (!empty($group_by)) {
            $this->db->group_by($group_by);
            $query = $this->db->get();
            $data['summary'] = $query->result();
            
            // Get totals
            $this->db->select('COUNT(p.id) AS total_payments, SUM(p.amount) AS total_amount');
            $this->db->from('payments p');
            $this->db->join('bookings b', 'b.id = p.booking_id', 'left');
            $this->db->join('users u', 'u.id = b.user_id', 'left');
            $this->db->join('booking_items bi', 'bi.booking_id = b.id', 'left');
            $this->db->join('vehicles veh', 'veh.id = bi.vehicle_id', 'left');
            $this->db->join('vendors v', 'v.id = veh.vendor_id', 'left');
            $this->db->where($where);
            $query = $this->db->get();
            $data['totals'] = $query->row();
        } else {
            // For detailed report
            $query = $this->db->get();
            $data['details'] = $query->result();
            
            // Get totals
            $this->db->select('COUNT(p.id) AS total_payments, SUM(p.amount) AS total_amount');
            $this->db->from('payments p');
            $this->db->where($where);
            $query = $this->db->get();
            $data['totals'] = $query->row();
        }
        
        return $data;
    }
    
    /**
     * Get vendor report based on filters
     * 
     * @param array $filters Filter parameters
     * @return array Report data
     */
    public function get_vendor_report($filters = array()) {
        // Initialize variables
        $data = array();
        $where = "1=1";
        
        // Apply date filters for vendor registration
        if (isset($filters['date_range'])) {
            switch ($filters['date_range']) {
                case 'today':
                    $where .= " AND DATE(v.created_at) = CURDATE()";
                    break;
                case 'yesterday':
                    $where .= " AND DATE(v.created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
                    break;
                case 'this_week':
                    $where .= " AND YEARWEEK(v.created_at, 1) = YEARWEEK(CURDATE(), 1)";
                    break;
                case 'last_week':
                    $where .= " AND YEARWEEK(v.created_at, 1) = YEARWEEK(DATE_SUB(CURDATE(), INTERVAL 1 WEEK), 1)";
                    break;
                case 'this_month':
                    $where .= " AND YEAR(v.created_at) = YEAR(CURDATE()) AND MONTH(v.created_at) = MONTH(CURDATE())";
                    break;
                case 'last_month':
                    $where .= " AND YEAR(v.created_at) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND MONTH(v.created_at) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))";
                    break;
                case 'this_year':
                    $where .= " AND YEAR(v.created_at) = YEAR(CURDATE())";
                    break;
                case 'last_year':
                    $where .= " AND YEAR(v.created_at) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 YEAR))";
                    break;
                case 'all_time':
                    // No additional where clause needed
                    break;
                case 'custom':
                    if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
                        $where .= " AND DATE(v.created_at) BETWEEN '" . $filters['start_date'] . "' AND '" . $filters['end_date'] . "'";
                    }
                    break;
            }
        }
        
        // Apply vendor status filter
        if (isset($filters['vendor_status']) && $filters['vendor_status'] != 'all') {
            $where .= " AND v.status = '" . $filters['vendor_status'] . "'";
        }
        
        // Determine sort field and order
        $sort_field = 'v.created_at';
        $sort_order = 'DESC';
        
        if (isset($filters['sort_by'])) {
            switch ($filters['sort_by']) {
                case 'bookings':
                    $sort_field = 'total_bookings';
                    $sort_order = 'DESC';
                    break;
                case 'revenue':
                    $sort_field = 'total_revenue';
                    $sort_order = 'DESC';
                    break;
                case 'vehicles':
                    $sort_field = 'vehicle_count';
                    $sort_order = 'DESC';
                    break;
                case 'rating':
                    $sort_field = 'avg_rating';
                    $sort_order = 'DESC';
                    break;
                case 'date_joined':
                    $sort_field = 'v.created_at';
                    $sort_order = 'DESC';
                    break;
            }
        }
        
        // Determine limit
        $limit = 10;
        if (isset($filters['limit']) && is_numeric($filters['limit'])) {
            $limit = $filters['limit'];
        }
        
        // Build query
        $this->db->select('v.*, u.name AS owner_name, u.email, u.phone, ' . 
                         '(SELECT COUNT(*) FROM vehicles WHERE vendor_id = v.id) AS vehicle_count, ' . 
                         '(SELECT COUNT(*) FROM bookings b JOIN booking_items bi ON bi.booking_id = b.id ' . 
                         'JOIN vehicles veh ON veh.id = bi.vehicle_id WHERE veh.vendor_id = v.id) AS total_bookings, ' . 
                         '(SELECT SUM(p.amount) FROM payments p JOIN bookings b ON b.id = p.booking_id ' . 
                         'JOIN booking_items bi ON bi.booking_id = b.id JOIN vehicles veh ON veh.id = bi.vehicle_id ' . 
                         'WHERE veh.vendor_id = v.id) AS total_revenue, ' . 
                         '(SELECT AVG(rating) FROM reviews r JOIN bookings b ON b.id = r.booking_id ' . 
                         'JOIN booking_items bi ON bi.booking_id = b.id JOIN vehicles veh ON veh.id = bi.vehicle_id ' . 
                         'WHERE veh.vendor_id = v.id) AS avg_rating');
        $this->db->from('vendors v');
        $this->db->join('users u', 'u.id = v.user_id', 'left');
        $this->db->where($where);
        $this->db->order_by($sort_field, $sort_order);
        
        if ($limit > 0) {
            $this->db->limit($limit);
        }
        
        $query = $this->db->get();
        $data['vendors'] = $query->result();
        
        // Get totals
        $this->db->select('COUNT(v.id) AS total_vendors, ' . 
                         'SUM((SELECT COUNT(*) FROM vehicles WHERE vendor_id = v.id)) AS total_vehicles, ' . 
                         'SUM((SELECT COUNT(*) FROM bookings b JOIN booking_items bi ON bi.booking_id = b.id ' . 
                         'JOIN vehicles veh ON veh.id = bi.vehicle_id WHERE veh.vendor_id = v.id)) AS total_bookings, ' . 
                         'SUM((SELECT SUM(p.amount) FROM payments p JOIN bookings b ON b.id = p.booking_id ' . 
                         'JOIN booking_items bi ON bi.booking_id = b.id JOIN vehicles veh ON veh.id = bi.vehicle_id ' . 
                         'WHERE veh.vendor_id = v.id)) AS total_revenue');
        $this->db->from('vendors v');
        $this->db->where($where);
        $query = $this->db->get();
        $data['totals'] = $query->row();
        
        return $data;
    }
    
    /**
     * Get vehicle report based on filters
     * 
     * @param array $filters Filter parameters
     * @return array Report data
     */
    public function get_vehicle_report($filters = array()) {
        // Initialize variables
        $data = array();
        $where = "1=1";
        
        // Apply date filters for vehicle registration
        if (isset($filters['date_range'])) {
            switch ($filters['date_range']) {
                case 'today':
                    $where .= " AND DATE(veh.created_at) = CURDATE()";
                    break;
                case 'yesterday':
                    $where .= " AND DATE(veh.created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
                    break;
                case 'this_week':
                    $where .= " AND YEARWEEK(veh.created_at, 1) = YEARWEEK(CURDATE(), 1)";
                    break;
                case 'last_week':
                    $where .= " AND YEARWEEK(veh.created_at, 1) = YEARWEEK(DATE_SUB(CURDATE(), INTERVAL 1 WEEK), 1)";
                    break;
                case 'this_month':
                    $where .= " AND YEAR(veh.created_at) = YEAR(CURDATE()) AND MONTH(veh.created_at) = MONTH(CURDATE())";
                    break;
                case 'last_month':
                    $where .= " AND YEAR(veh.created_at) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND MONTH(veh.created_at) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))";
                    break;
                case 'this_year':
                    $where .= " AND YEAR(veh.created_at) = YEAR(CURDATE())";
                    break;
                case 'last_year':
                    $where .= " AND YEAR(veh.created_at) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 YEAR))";
                    break;
                case 'all_time':
                    // No additional where clause needed
                    break;
                case 'custom':
                    if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
                        $where .= " AND DATE(veh.created_at) BETWEEN '" . $filters['start_date'] . "' AND '" . $filters['end_date'] . "'";
                    }
                    break;
            }
        }
        
        // Apply vendor filter
        if (isset($filters['vendor_id']) && $filters['vendor_id'] != 'all') {
            $where .= " AND veh.vendor_id = " . $this->db->escape($filters['vendor_id']);
        }
        
        // Apply vehicle type filter
        if (isset($filters['vehicle_type']) && $filters['vehicle_type'] != 'all') {
            $where .= " AND veh.type = '" . $filters['vehicle_type'] . "'";
        }
        
        // Determine sort field and order
        $sort_field = 'bookings_count';
        $sort_order = 'DESC';
        
        if (isset($filters['sort_by'])) {
            switch ($filters['sort_by']) {
                case 'bookings':
                    $sort_field = 'bookings_count';
                    $sort_order = 'DESC';
                    break;
                case 'revenue':
                    $sort_field = 'revenue';
                    $sort_order = 'DESC';
                    break;
                case 'rating':
                    $sort_field = 'avg_rating';
                    $sort_order = 'DESC';
                    break;
                case 'price':
                    $sort_field = 'veh.price_per_day';
                    $sort_order = 'DESC';
                    break;
                case 'date_added':
                    $sort_field = 'veh.created_at';
                    $sort_order = 'DESC';
                    break;
            }
        }
        
        // Determine limit
        $limit = 10;
        if (isset($filters['limit']) && is_numeric($filters['limit'])) {
            $limit = $filters['limit'];
        } elseif (isset($filters['limit']) && $filters['limit'] == 'all') {
            $limit = 0;
        }
        
        // Build query
        $this->db->select('veh.*, v.business_name AS vendor_name, ' . 
                         '(SELECT COUNT(*) FROM booking_items bi WHERE bi.vehicle_id = veh.id) AS bookings_count, ' . 
                         '(SELECT SUM(b.total_amount) FROM bookings b JOIN booking_items bi ON bi.booking_id = b.id ' . 
                         'WHERE bi.vehicle_id = veh.id) AS revenue, ' . 
                         '(SELECT AVG(rating) FROM reviews r JOIN bookings b ON b.id = r.booking_id ' . 
                         'JOIN booking_items bi ON bi.booking_id = b.id WHERE bi.vehicle_id = veh.id) AS avg_rating');
        $this->db->from('vehicles veh');
        $this->db->join('vendors v', 'v.id = veh.vendor_id', 'left');
        $this->db->where($where);
        $this->db->order_by($sort_field, $sort_order);
        
        if ($limit > 0) {
            $this->db->limit($limit);
        }
        
        $query = $this->db->get();
        $data['vehicles'] = $query->result();
        
        // Get totals
        $this->db->select('COUNT(veh.id) AS total_vehicles, ' . 
                         'SUM((SELECT COUNT(*) FROM booking_items bi WHERE bi.vehicle_id = veh.id)) AS total_bookings, ' . 
                         'SUM((SELECT SUM(b.total_amount) FROM bookings b JOIN booking_items bi ON bi.booking_id = b.id ' . 
                         'WHERE bi.vehicle_id = veh.id)) AS total_revenue');
        $this->db->from('vehicles veh');
        $this->db->where($where);
        $query = $this->db->get();
        $data['totals'] = $query->row();
        
        return $data;
    }
    
    /**
     * Get customer report based on filters
     * 
     * @param array $filters Filter parameters
     * @return array Report data
     */
    public function get_customer_report($filters = array()) {
        // Initialize variables
        $data = array();
        $where = "u.role = 'customer'";
        
        // Apply date filters for user registration
        if (isset($filters['date_range'])) {
            switch ($filters['date_range']) {
                case 'today':
                    $where .= " AND DATE(u.created_at) = CURDATE()";
                    break;
                case 'yesterday':
                    $where .= " AND DATE(u.created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
                    break;
                case 'this_week':
                    $where .= " AND YEARWEEK(u.created_at, 1) = YEARWEEK(CURDATE(), 1)";
                    break;
                case 'last_week':
                    $where .= " AND YEARWEEK(u.created_at, 1) = YEARWEEK(DATE_SUB(CURDATE(), INTERVAL 1 WEEK), 1)";
                    break;
                case 'this_month':
                    $where .= " AND YEAR(u.created_at) = YEAR(CURDATE()) AND MONTH(u.created_at) = MONTH(CURDATE())";
                    break;
                case 'last_month':
                    $where .= " AND YEAR(u.created_at) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND MONTH(u.created_at) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))";
                    break;
                case 'this_year':
                    $where .= " AND YEAR(u.created_at) = YEAR(CURDATE())";
                    break;
                case 'last_year':
                    $where .= " AND YEAR(u.created_at) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 YEAR))";
                    break;
                case 'all_time':
                    // No additional where clause needed
                    break;
                case 'custom':
                    if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
                        $where .= " AND DATE(u.created_at) BETWEEN '" . $filters['start_date'] . "' AND '" . $filters['end_date'] . "'";
                    }
                    break;
            }
        }
        
        // Apply customer status filter
        if (isset($filters['customer_status']) && $filters['customer_status'] != 'all') {
            $where .= " AND u.status = '" . $filters['customer_status'] . "'";
        }
        
        // Determine sort field and order
        $sort_field = 'bookings_count';
        $sort_order = 'DESC';
        
        if (isset($filters['sort_by'])) {
            switch ($filters['sort_by']) {
                case 'bookings':
                    $sort_field = 'bookings_count';
                    $sort_order = 'DESC';
                    break;
                case 'spending':
                    $sort_field = 'total_spending';
                    $sort_order = 'DESC';
                    break;
                case 'date_joined':
                    $sort_field = 'u.created_at';
                    $sort_order = 'DESC';
                    break;
                case 'last_booking':
                    $sort_field = 'last_booking_date';
                    $sort_order = 'DESC';
                    break;
            }
        }
        
        // Determine limit
        $limit = 10;
        if (isset($filters['limit']) && is_numeric($filters['limit'])) {
            $limit = $filters['limit'];
        } elseif (isset($filters['limit']) && $filters['limit'] == 'all') {
            $limit = 0;
        }
        
        // Build query
        $this->db->select('u.id, u.name, u.email, u.phone, u.status, u.created_at, ' . 
                         '(SELECT COUNT(*) FROM bookings WHERE user_id = u.id) AS bookings_count, ' . 
                         '(SELECT SUM(total_amount) FROM bookings WHERE user_id = u.id) AS total_spending, ' . 
                         '(SELECT MAX(created_at) FROM bookings WHERE user_id = u.id) AS last_booking_date');
        $this->db->from('users u');
        $this->db->where($where);
        $this->db->order_by($sort_field, $sort_order);
        
        if ($limit > 0) {
            $this->db->limit($limit);
        }
        
        $query = $this->db->get();
        $data['customers'] = $query->result();
        
        // Get totals
        $this->db->select('COUNT(u.id) AS total_customers, ' . 
                         'SUM((SELECT COUNT(*) FROM bookings WHERE user_id = u.id)) AS total_bookings, ' . 
                         'SUM((SELECT SUM(total_amount) FROM bookings WHERE user_id = u.id)) AS total_spending');
        $this->db->from('users u');
        $this->db->where($where);
        $query = $this->db->get();
        $data['totals'] = $query->row();
        
        return $data;
    }
    
    /**
     * Export report data to Excel
     * 
     * @param array $report_data Report data
     * @param string $report_type Type of report (booking, revenue, vendor, vehicle, customer)
     * @param string $filename Filename for the exported file
     * @return void
     */
    public function export_to_excel($report_data, $report_type, $filename) {
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Car Booking System")
                                     ->setLastModifiedBy("Car Booking System")
                                     ->setTitle(ucfirst($report_type) . " Report")
                                     ->setSubject(ucfirst($report_type) . " Report")
                                     ->setDescription(ucfirst($report_type) . " Report Generated on " . date('Y-m-d H:i:s'))
                                     ->setKeywords("office excel php report")
                                     ->setCategory("Report");
        
        // Add header row
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->setTitle(ucfirst($report_type) . " Report");
        
        // Set headers based on report type
        switch ($report_type) {
            case 'booking':
                $this->generate_booking_excel($sheet, $report_data);
                break;
            case 'revenue':
                $this->generate_revenue_excel($sheet, $report_data);
                break;
            case 'vendor':
                $this->generate_vendor_excel($sheet, $report_data);
                break;
            case 'vehicle':
                $this->generate_vehicle_excel($sheet, $report_data);
                break;
            case 'customer':
                $this->generate_customer_excel($sheet, $report_data);
                break;
        }
        
        // Set column widths
        foreach(range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        
        // Set active sheet index to the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        
        // Redirect output to a client's web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }
    
    /**
     * Generate booking report Excel
     * 
     * @param PHPExcel_Worksheet $sheet Excel worksheet
     * @param array $report_data Report data
     */
    private function generate_booking_excel($sheet, $report_data) {
        // Set headers
        $headers = array('Booking ID', 'Customer', 'Vehicle', 'Vendor', 'Booking Date', 'Status', 'Payment Method', 'Amount');
        $col = 0;
        foreach ($headers as $header) {
            $sheet->setCellValueByColumnAndRow($col, 1, $header);
            $col++;
        }
        
        // Style the header row
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        $sheet->getStyle('A1:H1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CCCCCC');
        
        // Add data rows
        $row = 2;
        if (isset($report_data['details'])) {
            foreach ($report_data['details'] as $booking) {
                $sheet->setCellValueByColumnAndRow(0, $row, $booking->id);
                $sheet->setCellValueByColumnAndRow(1, $row, $booking->customer_name);
                $sheet->setCellValueByColumnAndRow(2, $row, $booking->vehicle_name);
                $sheet->setCellValueByColumnAndRow(3, $row, $booking->vendor_name);
                $sheet->setCellValueByColumnAndRow(4, $row, date('Y-m-d', strtotime($booking->created_at)));
                $sheet->setCellValueByColumnAndRow(5, $row, ucfirst($booking->status));
                $sheet->setCellValueByColumnAndRow(6, $row, ucfirst($booking->payment_method));
                $sheet->setCellValueByColumnAndRow(7, $row, $booking->total_amount);
                $row++;
            }
        } elseif (isset($report_data['summary'])) {
            foreach ($report_data['summary'] as $summary) {
                $sheet->setCellValueByColumnAndRow(0, $row, $summary->group_label);
                $sheet->setCellValueByColumnAndRow(1, $row, $summary->total_bookings);
                $sheet->setCellValueByColumnAndRow(2, $row, $summary->total_amount);
                $row++;
            }
        }
        
        // Add totals row
        $sheet->setCellValueByColumnAndRow(0, $row, 'Total');
        $sheet->setCellValueByColumnAndRow(1, $row, $report_data['totals']->total_bookings);
        $sheet->setCellValueByColumnAndRow(7, $row, $report_data['totals']->total_amount);
        $sheet->getStyle('A'.$row.':H'.$row)->getFont()->setBold(true);
    }
    
    /**
     * Generate revenue report Excel
     * 
     * @param PHPExcel_Worksheet $sheet Excel worksheet
     * @param array $report_data Report data
     */
    private function generate_revenue_excel($sheet, $report_data) {
        // Set headers
        $headers = array('Payment ID', 'Booking ID', 'Customer', 'Payment Date', 'Payment Method', 'Status', 'Amount');
        $col = 0;
        foreach ($headers as $header) {
            $sheet->setCellValueByColumnAndRow($col, 1, $header);
            $col++;
        }
        
        // Style the header row
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        $sheet->getStyle('A1:G1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CCCCCC');
        
        // Add data rows
        $row = 2;
        if (isset($report_data['details'])) {
            foreach ($report_data['details'] as $payment) {
                $sheet->setCellValueByColumnAndRow(0, $row, $payment->id);
                $sheet->setCellValueByColumnAndRow(1, $row, $payment->booking_id);
                $sheet->setCellValueByColumnAndRow(2, $row, $payment->customer_name);
                $sheet->setCellValueByColumnAndRow(3, $row, date('Y-m-d', strtotime($payment->created_at)));
                $sheet->setCellValueByColumnAndRow(4, $row, ucfirst($payment->payment_method));
                $sheet->setCellValueByColumnAndRow(5, $row, ucfirst($payment->status));
                $sheet->setCellValueByColumnAndRow(6, $row, $payment->amount);
                $row++;
            }
        } elseif (isset($report_data['summary'])) {
            foreach ($report_data['summary'] as $summary) {
                $sheet->setCellValueByColumnAndRow(0, $row, $summary->group_label);
                $sheet->setCellValueByColumnAndRow(1, $row, $summary->total_payments);
                $sheet->setCellValueByColumnAndRow(2, $row, $summary->total_amount);
                $row++;
            }
        }
        
        // Add totals row
        $sheet->setCellValueByColumnAndRow(0, $row, 'Total');
        $sheet->setCellValueByColumnAndRow(1, $row, $report_data['totals']->total_payments);
        $sheet->setCellValueByColumnAndRow(6, $row, $report_data['totals']->total_amount);
        $sheet->getStyle('A'.$row.':G'.$row)->getFont()->setBold(true);
    }
    
    /**
     * Generate vendor report Excel
     * 
     * @param PHPExcel_Worksheet $sheet Excel worksheet
     * @param array $report_data Report data
     */
    private function generate_vendor_excel($sheet, $report_data) {
        // Set headers
        $headers = array('Vendor', 'Business Name', 'Email', 'Phone', 'Status', 'Vehicles', 'Bookings', 'Revenue');
        $col = 0;
        foreach ($headers as $header) {
            $sheet->setCellValueByColumnAndRow($col, 1, $header);
            $col++;
        }
        
        // Style the header row
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        $sheet->getStyle('A1:H1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CCCCCC');
        
        // Add data rows
        $row = 2;
        foreach ($report_data['vendors'] as $vendor) {
            $sheet->setCellValueByColumnAndRow(0, $row, $vendor->name);
            $sheet->setCellValueByColumnAndRow(1, $row, $vendor->business_name);
            $sheet->setCellValueByColumnAndRow(2, $row, $vendor->email);
            $sheet->setCellValueByColumnAndRow(3, $row, $vendor->phone);
            $sheet->setCellValueByColumnAndRow(4, $row, ucfirst($vendor->status));
            $sheet->setCellValueByColumnAndRow(5, $row, $vendor->vehicles_count);
            $sheet->setCellValueByColumnAndRow(6, $row, $vendor->bookings_count);
            $sheet->setCellValueByColumnAndRow(7, $row, $vendor->revenue);
            $row++;
        }
        
        // Add totals row
        $sheet->setCellValueByColumnAndRow(0, $row, 'Total');
        $sheet->setCellValueByColumnAndRow(5, $row, $report_data['totals']->total_vehicles);
        $sheet->setCellValueByColumnAndRow(6, $row, $report_data['totals']->total_bookings);
        $sheet->setCellValueByColumnAndRow(7, $row, $report_data['totals']->total_revenue);
        $sheet->getStyle('A'.$row.':H'.$row)->getFont()->setBold(true);
    }
    
    /**
     * Generate vehicle report Excel
     * 
     * @param PHPExcel_Worksheet $sheet Excel worksheet
     * @param array $report_data Report data
     */
    private function generate_vehicle_excel($sheet, $report_data) {
        // Set headers
        $headers = array('Vehicle', 'Model', 'Year', 'Vendor', 'Type', 'Price/Day', 'Bookings', 'Revenue');
        $col = 0;
        foreach ($headers as $header) {
            $sheet->setCellValueByColumnAndRow($col, 1, $header);
            $col++;
        }
        
        // Style the header row
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        $sheet->getStyle('A1:H1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CCCCCC');
        
        // Add data rows
        $row = 2;
        foreach ($report_data['vehicles'] as $vehicle) {
            $sheet->setCellValueByColumnAndRow(0, $row, $vehicle->title);
            $sheet->setCellValueByColumnAndRow(1, $row, $vehicle->model);
            $sheet->setCellValueByColumnAndRow(2, $row, $vehicle->year);
            $sheet->setCellValueByColumnAndRow(3, $row, $vehicle->vendor_name);
            $sheet->setCellValueByColumnAndRow(4, $row, ucfirst($vehicle->type));
            $sheet->setCellValueByColumnAndRow(5, $row, $vehicle->price_per_day);
            $sheet->setCellValueByColumnAndRow(6, $row, $vehicle->bookings_count);
            $sheet->setCellValueByColumnAndRow(7, $row, $vehicle->revenue);
            $row++;
        }
        
        // Add totals row
        $sheet->setCellValueByColumnAndRow(0, $row, 'Total');
        $sheet->setCellValueByColumnAndRow(6, $row, $report_data['totals']->total_bookings);
        $sheet->setCellValueByColumnAndRow(7, $row, $report_data['totals']->total_revenue);
        $sheet->getStyle('A'.$row.':H'.$row)->getFont()->setBold(true);
    }
    
    /**
     * Generate customer report Excel
     * 
     * @param PHPExcel_Worksheet $sheet Excel worksheet
     * @param array $report_data Report data
     */
    private function generate_customer_excel($sheet, $report_data) {
        // Set headers
        $headers = array('Customer', 'Email', 'Phone', 'Joined Date', 'Bookings', 'Last Booking', 'Total Spent');
        $col = 0;
        foreach ($headers as $header) {
            $sheet->setCellValueByColumnAndRow($col, 1, $header);
            $col++;
        }
        
        // Style the header row
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        $sheet->getStyle('A1:G1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CCCCCC');
        
        // Add data rows
        $row = 2;
        foreach ($report_data['customers'] as $customer) {
            $sheet->setCellValueByColumnAndRow(0, $row, $customer->name);
            $sheet->setCellValueByColumnAndRow(1, $row, $customer->email);
            $sheet->setCellValueByColumnAndRow(2, $row, $customer->phone);
            $sheet->setCellValueByColumnAndRow(3, $row, date('Y-m-d', strtotime($customer->created_at)));
            $sheet->setCellValueByColumnAndRow(4, $row, $customer->bookings_count);
            $sheet->setCellValueByColumnAndRow(5, $row, $customer->last_booking_date ? date('Y-m-d', strtotime($customer->last_booking_date)) : 'N/A');
            $sheet->setCellValueByColumnAndRow(6, $row, $customer->total_spending);
            $row++;
        }
        
        // Add totals row
        $sheet->setCellValueByColumnAndRow(0, $row, 'Total');
        $sheet->setCellValueByColumnAndRow(4, $row, $report_data['totals']->total_bookings);
        $sheet->setCellValueByColumnAndRow(6, $row, $report_data['totals']->total_spending);
        $sheet->getStyle('A'.$row.':G'.$row)->getFont()->setBold(true);
    }
    
    /**
     * Export report data to PDF
     * 
     * @param array $report_data Report data
     * @param string $report_type Type of report (booking, revenue, vendor, vehicle, customer)
     * @param string $filename Filename for the exported file
     * @return void
     */
    public function export_to_pdf($report_data, $report_type, $filename) {
        // Load TCPDF library
        $this->load->library('pdf');
        
        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Car Booking System');
        $pdf->SetTitle(ucfirst($report_type) . ' Report');
        $pdf->SetSubject(ucfirst($report_type) . ' Report');
        
        // Set default header data
        $pdf->SetHeaderData('', 0, ucfirst($report_type) . ' Report', 'Generated on: ' . date('Y-m-d H:i:s'));
        
        // Set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        
        // Set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        
        // Set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        
        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        
        // Set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        
        // Add a page
        $pdf->AddPage();
        
        // Generate report content based on type
        switch ($report_type) {
            case 'booking':
                $html = $this->generate_booking_pdf_content($report_data);
                break;
            case 'revenue':
                $html = $this->generate_revenue_pdf_content($report_data);
                break;
            case 'vendor':
                $html = $this->generate_vendor_pdf_content($report_data);
                break;
            case 'vehicle':
                $html = $this->generate_vehicle_pdf_content($report_data);
                break;
            case 'customer':
                $html = $this->generate_customer_pdf_content($report_data);
                break;
        }
        
        // Print content
        $pdf->writeHTML($html, true, false, true, false, '');
        
        // Close and output PDF document
        $pdf->Output($filename . '.pdf', 'D');
        exit;
    }
    
    /**
     * Generate booking report PDF content
     * 
     * @param array $report_data Report data
     * @return string HTML content
     */
    private function generate_booking_pdf_content($report_data) {
        $html = '<h1>Booking Report</h1>';
        
        // Add summary information
        $html .= '<div style="margin-bottom: 20px;">';
        $html .= '<p><strong>Total Bookings:</strong> ' . number_format($report_data['totals']->total_bookings) . '</p>';
        $html .= '<p><strong>Total Amount:</strong> ' . number_format($report_data['totals']->total_amount, 2) . '</p>';
        $html .= '</div>';
        
        // Start table
        $html .= '<table border="1" cellpadding="5">';
        
        if (isset($report_data['details'])) {
            // Detailed report
            $html .= '<tr style="background-color: #f2f2f2;">';
            $html .= '<th>Booking ID</th>';
            $html .= '<th>Customer</th>';
            $html .= '<th>Vehicle</th>';
            $html .= '<th>Vendor</th>';
            $html .= '<th>Booking Date</th>';
            $html .= '<th>Status</th>';
            $html .= '<th>Payment Method</th>';
            $html .= '<th>Amount</th>';
            $html .= '</tr>';
            
            foreach ($report_data['details'] as $booking) {
                $html .= '<tr>';
                $html .= '<td>' . $booking->id . '</td>';
                $html .= '<td>' . $booking->customer_name . '</td>';
                $html .= '<td>' . $booking->vehicle_name . '</td>';
                $html .= '<td>' . $booking->vendor_name . '</td>';
                $html .= '<td>' . date('Y-m-d', strtotime($booking->created_at)) . '</td>';
                $html .= '<td>' . ucfirst($booking->status) . '</td>';
                $html .= '<td>' . ucfirst($booking->payment_method) . '</td>';
                $html .= '<td>' . number_format($booking->total_amount, 2) . '</td>';
                $html .= '</tr>';
            }
        } elseif (isset($report_data['summary'])) {
            // Summary report
            $html .= '<tr style="background-color: #f2f2f2;">';
            $html .= '<th>Group</th>';
            $html .= '<th>Total Bookings</th>';
            $html .= '<th>Total Amount</th>';
            $html .= '</tr>';
            
            foreach ($report_data['summary'] as $summary) {
                $html .= '<tr>';
                $html .= '<td>' . $summary->group_label . '</td>';
                $html .= '<td>' . $summary->total_bookings . '</td>';
                $html .= '<td>' . number_format($summary->total_amount, 2) . '</td>';
                $html .= '</tr>';
            }
        }
        
        // Add totals row
        $html .= '<tr style="background-color: #f2f2f2; font-weight: bold;">';
        $html .= '<td>Total</td>';
        $html .= '<td>' . $report_data['totals']->total_bookings . '</td>';
        $html .= '<td>' . number_format($report_data['totals']->total_amount, 2) . '</td>';
        $html .= '</tr>';
        
        $html .= '</table>';
        
        return $html;
    }
    
    /**
     * Generate revenue report PDF content
     * 
     * @param array $report_data Report data
     * @return string HTML content
     */
    private function generate_revenue_pdf_content($report_data) {
        $html = '<h1>Revenue Report</h1>';
        
        // Add summary information
        $html .= '<div style="margin-bottom: 20px;">';
        $html .= '<p><strong>Total Payments:</strong> ' . number_format($report_data['totals']->total_payments) . '</p>';
        $html .= '<p><strong>Total Amount:</strong> ' . number_format($report_data['totals']->total_amount, 2) . '</p>';
        $html .= '</div>';
        
        // Start table
        $html .= '<table border="1" cellpadding="5">';
        
        if (isset($report_data['details'])) {
            // Detailed report
            $html .= '<tr style="background-color: #f2f2f2;">';
            $html .= '<th>Payment ID</th>';
            $html .= '<th>Booking ID</th>';
            $html .= '<th>Customer</th>';
            $html .= '<th>Payment Date</th>';
            $html .= '<th>Payment Method</th>';
            $html .= '<th>Status</th>';
            $html .= '<th>Amount</th>';
            $html .= '</tr>';
            
            foreach ($report_data['details'] as $payment) {
                $html .= '<tr>';
                $html .= '<td>' . $payment->id . '</td>';
                $html .= '<td>' . $payment->booking_id . '</td>';
                $html .= '<td>' . $payment->customer_name . '</td>';
                $html .= '<td>' . date('Y-m-d', strtotime($payment->created_at)) . '</td>';
                $html .= '<td>' . ucfirst($payment->payment_method) . '</td>';
                $html .= '<td>' . ucfirst($payment->status) . '</td>';
                $html .= '<td>' . number_format($payment->amount, 2) . '</td>';
                $html .= '</tr>';
            }
        } elseif (isset($report_data['summary'])) {
            // Summary report
            $html .= '<tr style="background-color: #f2f2f2;">';
            $html .= '<th>Group</th>';
            $html .= '<th>Total Payments</th>';
            $html .= '<th>Total Amount</th>';
            $html .= '</tr>';
            
            foreach ($report_data['summary'] as $summary) {
                $html .= '<tr>';
                $html .= '<td>' . $summary->group_label . '</td>';
                $html .= '<td>' . $summary->total_payments . '</td>';
                $html .= '<td>' . number_format($summary->total_amount, 2) . '</td>';
                $html .= '</tr>';
            }
        }
        
        // Add totals row
        $html .= '<tr style="background-color: #f2f2f2; font-weight: bold;">';
        $html .= '<td>Total</td>';
        $html .= '<td>' . $report_data['totals']->total_payments . '</td>';
        $html .= '<td>' . number_format($report_data['totals']->total_amount, 2) . '</td>';
        $html .= '</tr>';
        
        $html .= '</table>';
        
        return $html;
    }
    
    /**
     * Generate vendor report PDF content
     * 
     * @param array $report_data Report data
     * @return string HTML content
     */
    private function generate_vendor_pdf_content($report_data) {
        $html = '<h1>Vendor Report</h1>';
        
        // Add summary information
        $html .= '<div style="margin-bottom: 20px;">';
        $html .= '<p><strong>Total Vendors:</strong> ' . count($report_data['vendors']) . '</p>';
        $html .= '<p><strong>Total Vehicles:</strong> ' . number_format($report_data['totals']->total_vehicles) . '</p>';
        $html .= '<p><strong>Total Bookings:</strong> ' . number_format($report_data['totals']->total_bookings) . '</p>';
        $html .= '<p><strong>Total Revenue:</strong> ' . number_format($report_data['totals']->total_revenue, 2) . '</p>';
        $html .= '</div>';
        
        // Start table
        $html .= '<table border="1" cellpadding="5">';
        $html .= '<tr style="background-color: #f2f2f2;">';
        $html .= '<th>Vendor</th>';
        $html .= '<th>Business Name</th>';
        $html .= '<th>Email</th>';
        $html .= '<th>Phone</th>';
        $html .= '<th>Status</th>';
        $html .= '<th>Vehicles</th>';
        $html .= '<th>Bookings</th>';
        $html .= '<th>Revenue</th>';
        $html .= '</tr>';
        
        foreach ($report_data['vendors'] as $vendor) {
            $html .= '<tr>';
            $html .= '<td>' . $vendor->name . '</td>';
            $html .= '<td>' . $vendor->business_name . '</td>';
            $html .= '<td>' . $vendor->email . '</td>';
            $html .= '<td>' . $vendor->phone . '</td>';
            $html .= '<td>' . ucfirst($vendor->status) . '</td>';
            $html .= '<td>' . $vendor->vehicles_count . '</td>';
            $html .= '<td>' . $vendor->bookings_count . '</td>';
            $html .= '<td>' . number_format($vendor->revenue, 2) . '</td>';
            $html .= '</tr>';
        }
        
        // Add totals row
        $html .= '<tr style="background-color: #f2f2f2; font-weight: bold;">';
        $html .= '<td colspan="5">Total</td>';
        $html .= '<td>' . $report_data['totals']->total_vehicles . '</td>';
        $html .= '<td>' . $report_data['totals']->total_bookings . '</td>';
        $html .= '<td>' . number_format($report_data['totals']->total_revenue, 2) . '</td>';
        $html .= '</tr>';
        
        $html .= '</table>';
        
        return $html;
    }
    
    /**
     * Generate vehicle report PDF content
     * 
     * @param array $report_data Report data
     * @return string HTML content
     */
    private function generate_vehicle_pdf_content($report_data) {
        $html = '<h1>Vehicle Report</h1>';
        
        // Add summary information
        $html .= '<div style="margin-bottom: 20px;">';
        $html .= '<p><strong>Total Vehicles:</strong> ' . count($report_data['vehicles']) . '</p>';
        $html .= '<p><strong>Total Bookings:</strong> ' . number_format($report_data['totals']->total_bookings) . '</p>';
        $html .= '<p><strong>Total Revenue:</strong> ' . number_format($report_data['totals']->total_revenue, 2) . '</p>';
        $html .= '</div>';
        
        // Start table
        $html .= '<table border="1" cellpadding="5">';
        $html .= '<tr style="background-color: #f2f2f2;">';
        $html .= '<th>Vehicle</th>';
        $html .= '<th>Model</th>';
        $html .= '<th>Year</th>';
        $html .= '<th>Vendor</th>';
        $html .= '<th>Type</th>';
        $html .= '<th>Price/Day</th>';
        $html .= '<th>Bookings</th>';
        $html .= '<th>Revenue</th>';
        $html .= '</tr>';
        
        foreach ($report_data['vehicles'] as $vehicle) {
            $html .= '<tr>';
            $html .= '<td>' . $vehicle->title . '</td>';
            $html .= '<td>' . $vehicle->model . '</td>';
            $html .= '<td>' . $vehicle->year . '</td>';
            $html .= '<td>' . $vehicle->vendor_name . '</td>';
            $html .= '<td>' . ucfirst($vehicle->type) . '</td>';
            $html .= '<td>' . number_format($vehicle->price_per_day, 2) . '</td>';
            $html .= '<td>' . $vehicle->bookings_count . '</td>';
            $html .= '<td>' . number_format($vehicle->revenue, 2) . '</td>';
            $html .= '</tr>';
        }
        
        // Add totals row
        $html .= '<tr style="background-color: #f2f2f2; font-weight: bold;">';
        $html .= '<td colspan="6">Total</td>';
        $html .= '<td>' . $report_data['totals']->total_bookings . '</td>';
        $html .= '<td>' . number_format($report_data['totals']->total_revenue, 2) . '</td>';
        $html .= '</tr>';
        
        $html .= '</table>';
        
        return $html;
    }
    
    /**
     * Generate customer report PDF content
     * 
     * @param array $report_data Report data
     * @return string HTML content
     */
    private function generate_customer_pdf_content($report_data) {
        $html = '<h1>Customer Report</h1>';
        
        // Add summary information
        $html .= '<div style="margin-bottom: 20px;">';
        $html .= '<p><strong>Total Customers:</strong> ' . count($report_data['customers']) . '</p>';
        $html .= '<p><strong>Total Bookings:</strong> ' . number_format($report_data['totals']->total_bookings) . '</p>';
        $html .= '<p><strong>Total Spending:</strong> ' . number_format($report_data['totals']->total_spending, 2) . '</p>';
        $html .= '</div>';
        
        // Start table
        $html .= '<table border="1" cellpadding="5">';
        $html .= '<tr style="background-color: #f2f2f2;">';
        $html .= '<th>Customer</th>';
        $html .= '<th>Email</th>';
        $html .= '<th>Phone</th>';
        $html .= '<th>Joined Date</th>';
        $html .= '<th>Bookings</th>';
        $html .= '<th>Last Booking</th>';
        $html .= '<th>Total Spent</th>';
        $html .= '</tr>';
        
        foreach ($report_data['customers'] as $customer) {
            $html .= '<tr>';
            $html .= '<td>' . $customer->name . '</td>';
            $html .= '<td>' . $customer->email . '</td>';
            $html .= '<td>' . ($customer->phone ?: 'N/A') . '</td>';
            $html .= '<td>' . date('Y-m-d', strtotime($customer->created_at)) . '</td>';
            $html .= '<td>' . $customer->bookings_count . '</td>';
            $html .= '<td>' . ($customer->last_booking_date ? date('Y-m-d', strtotime($customer->last_booking_date)) : 'N/A') . '</td>';
            $html .= '<td>' . number_format($customer->total_spending, 2) . '</td>';
            $html .= '</tr>';
        }
        
        // Add totals row
        $html .= '<tr style="background-color: #f2f2f2; font-weight: bold;">';
        $html .= '<td colspan="4">Total</td>';
        $html .= '<td>' . $report_data['totals']->total_bookings . '</td>';
        $html .= '<td></td>';
        $html .= '<td>' . number_format($report_data['totals']->total_spending, 2) . '</td>';
        $html .= '</tr>';
        
        $html .= '</table>';
        
        return $html;
    }
    
    /**
     * Export report data to Excel or PDF
     * 
     * @param string $report_type Type of report (booking, revenue, vendor, vehicle, customer)
     * @param array $data Report data
     * @param string $format Export format (excel, pdf)
     * @return mixed File path or boolean
     */
    public function export_report($report_type, $data, $format = 'excel') {
        // Generate filename
        $filename = $report_type . '_report_' . date('Y-m-d');
        
        // Export based on format
        if ($format === 'excel') {
            return $this->export_to_excel($data, $report_type, $filename);
        } else {
            return $this->export_to_pdf($data, $report_type, $filename);
        }
    }
}