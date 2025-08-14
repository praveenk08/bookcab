<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_notifications_table extends CI_Migration {

    public function up() {
        // Add notifications table if it doesn't exist
        if (!$this->db->table_exists('notifications')) {
            $this->dbforge->add_field([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ],
                'user_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                ],
                'type' => [
                    'type' => 'ENUM',
                    'constraint' => ["booking", "payment", "review", "system", "vendor"],
                    'default' => 'system',
                ],
                'title' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                ],
                'message' => [
                    'type' => 'TEXT',
                ],
                'reference_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'null' => TRUE,
                ],
                'is_read' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                ],
            ]);
            
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->add_key('user_id');
            $this->dbforge->add_key('is_read');
            
            $this->dbforge->create_table('notifications');
            
            // Add foreign key constraint
            $this->db->query('ALTER TABLE `notifications` ADD CONSTRAINT `fk_notifications_user_id` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE');
        }
    }

    public function down() {
        // Drop table if it exists
        if ($this->db->table_exists('notifications')) {
            // Remove foreign key constraint first
            $this->db->query('ALTER TABLE `notifications` DROP FOREIGN KEY `fk_notifications_user_id`');
            
            // Drop the table
            $this->dbforge->drop_table('notifications');
        }
    }
}