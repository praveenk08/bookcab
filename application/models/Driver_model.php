<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Driver_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Add a new driver
     * 
     * @param array $data Driver data
     * @return int|bool Driver ID on success, FALSE on failure
     */
    public function add_driver($data) {
        $this->db->insert('drivers', $data);
        return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : FALSE;
    }

    /**
     * Get driver by ID
     * 
     * @param int $id Driver ID
     * @return object|bool Driver object on success, FALSE if not found
     */
    public function get_driver_by_id($id) {
        $query = $this->db->get_where('drivers', ['id' => $id]);
        return ($query->num_rows() > 0) ? $query->row() : FALSE;
    }

    /**
     * Update driver
     * 
     * @param int $id Driver ID
     * @param array $data Driver data to update
     * @return bool TRUE on success, FALSE on failure
     */
    public function update_driver($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('drivers', $data);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * Delete driver
     * 
     * @param int $id Driver ID
     * @return bool TRUE on success, FALSE on failure
     */
    public function delete_driver($id) {
        $this->db->where('id', $id);
        $this->db->delete('drivers');
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * Get all drivers with optional filtering
     * 
     * @param array $filters Optional filters
     * @param int $limit Limit results
     * @param int $offset Offset for pagination
     * @return array Array of driver objects
     */
    public function get_drivers($filters = [], $limit = NULL, $offset = NULL) {
        if (!empty($filters)) {
            $this->db->where($filters);
        }
        
        if ($limit !== NULL && $offset !== NULL) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get('drivers');
        return $query->result();
    }

    /**
     * Count drivers with optional filtering
     * 
     * @param array $filters Optional filters
     * @return int Number of drivers
     */
    public function count_drivers($filters = []) {
        if (!empty($filters)) {
            $this->db->where($filters);
        }
        
        return $this->db->count_all_results('drivers');
    }
}