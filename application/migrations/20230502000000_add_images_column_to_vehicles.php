<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_images_column_to_vehicles extends CI_Migration {

    public function up() {
        // Add images column to vehicles table if it doesn't exist
        $fields = [
            'images' => [
                'type' => 'TEXT',
                'null' => TRUE,
                'after' => 'rating'
            ]
        ];

        $this->dbforge->add_column('vehicles', $fields);
    }

    public function down() {
        // Remove images column from vehicles table if it exists
        $this->dbforge->drop_column('vehicles', 'images');
    }
}