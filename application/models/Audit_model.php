<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Log an action in the audit log
     * 
     * This method supports two calling patterns:
     * 1. log_action($user_id, $entity_type, $entity_id, $action, $old_value, $new_value)
     * 2. log_action($entity_type, $entity_id, $action, $description, $user_id)
     * 
     * @param mixed $param1 Either user_id (int) or entity_type (string)
     * @param mixed $param2 Either entity_type (string) or entity_id (int)
     * @param mixed $param3 Either entity_id (int) or action (string)
     * @param mixed $param4 Either action (string) or description (string)
     * @param mixed $param5 Either old_value (mixed) or user_id (int)
     * @param mixed $param6 New value (mixed) - only used in first pattern
     * @return int|bool Audit log ID on success, FALSE on failure
     */
    public function log_action($param1, $param2, $param3, $param4, $param5 = NULL, $param6 = NULL) {
        // Determine which calling pattern is being used
        if (is_string($param1) && is_numeric($param2)) {
            // Pattern 2: log_action($entity_type, $entity_id, $action, $description, $user_id)
            $data = [
                'entity_type' => $param1,
                'entity_id' => $param2,
                'action' => $param3,
                'old_value' => $param4,
                'user_id' => $param5,
                'created_at' => date('Y-m-d H:i:s')
            ];
        } else {
            // Pattern 1: log_action($user_id, $entity_type, $entity_id, $action, $old_value, $new_value)
            $data = [
                'user_id' => $param1,
                'entity_type' => $param2,
                'entity_id' => $param3,
                'action' => $param4,
                'old_value' => $param5 ? json_encode($param5) : NULL,
                'new_value' => $param6 ? json_encode($param6) : NULL,
                'created_at' => date('Y-m-d H:i:s')
            ];
        }
        
        $this->db->insert('audit_logs', $data);
        return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : FALSE;
    }

    /**
     * Get audit logs for an entity
     * 
     * @param string $entity_type Entity type
     * @param int $entity_id Entity ID
     * @param int $limit Limit results
     * @param int $offset Offset for pagination
     * @return array Array of audit log objects
     */
    public function get_entity_logs($entity_type, $entity_id, $limit = NULL, $offset = NULL) {
        $this->db->select('audit_logs.*, users.name as user_name');
        $this->db->from('audit_logs');
        $this->db->join('users', 'users.id = audit_logs.user_id', 'left');
        $this->db->where('entity_type', $entity_type);
        $this->db->where('entity_id', $entity_id);
        $this->db->order_by('created_at', 'DESC');
        
        if ($limit !== NULL && $offset !== NULL) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get all audit logs with optional filtering
     * 
     * @param array $filters Optional filters
     * @param int $limit Limit results
     * @param int $offset Offset for pagination
     * @return array Array of audit log objects
     */
    public function get_logs($filters = [], $limit = NULL, $offset = NULL) {
        $this->db->select('audit_logs.*, users.name as user_name');
        $this->db->from('audit_logs');
        $this->db->join('users', 'users.id = audit_logs.user_id', 'left');
        
        if (!empty($filters)) {
            $this->db->where($filters);
        }
        
        $this->db->order_by('created_at', 'DESC');
        
        if ($limit !== NULL && $offset !== NULL) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Count audit logs with optional filtering
     * 
     * @param array $filters Optional filters
     * @return int Number of audit logs
     */
    public function count_logs($filters = []) {
        $this->db->from('audit_logs');
        
        if (!empty($filters)) {
            $this->db->where($filters);
        }
        
        return $this->db->count_all_results();
    }
}