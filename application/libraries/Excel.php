<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'third_party/PHPExcel/PHPExcel.php';

/**
 * Excel Library for CodeIgniter
 * 
 * This library provides a wrapper for PHPExcel to make it easier to use within CodeIgniter
 */
class Excel extends PHPExcel {
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Save the excel file
     * 
     * @param string $filename The filename to save as
     * @param string $format The format (Excel5, Excel2007, PDF, etc)
     * @param string $output Where to output the file (browser, file, string)
     * @return mixed
     */
    public function save($filename = 'excel', $format = 'Excel2007', $output = 'browser') {
        // Create writer
        $writer = PHPExcel_IOFactory::createWriter($this, $format);
        
        // Output to browser
        if ($output == 'browser') {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
            exit;
        }
        
        // Output to file
        if ($output == 'file') {
            $writer->save($filename);
            return TRUE;
        }
        
        // Output to string
        if ($output == 'string') {
            ob_start();
            $writer->save('php://output');
            return ob_get_clean();
        }
        
        return FALSE;
    }
}