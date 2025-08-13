<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Include the TCPDF library
require_once APPPATH . 'third_party/tcpdf/tcpdf.php';

/**
 * PDF Library for CodeIgniter
 * 
 * This library provides a wrapper for TCPDF to make it easier to use within CodeIgniter
 */
class Pdf extends TCPDF {
    
    /**
     * Constructor
     * 
     * @param string $orientation Page orientation (P=portrait, L=landscape)
     * @param string $unit Unit of measure (pt, mm, cm, in)
     * @param string $format Paper format (A4, Letter, etc)
     * @param boolean $unicode Unicode support
     * @param string $encoding Charset encoding
     * @param boolean $diskcache Use disk caching
     */
    public function __construct($orientation = 'P', $unit = 'mm', $format = 'A4', $unicode = true, $encoding = 'UTF-8', $diskcache = false) {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache);
        
        // Set default header/footer
        $this->setPrintHeader(true);
        $this->setPrintFooter(true);
        
        // Set default font
        $this->SetFont('helvetica', '', 10);
        
        // Set margins
        $this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->SetFooterMargin(PDF_MARGIN_FOOTER);
        
        // Set auto page breaks
        $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        
        // Set image scale factor
        $this->setImageScale(PDF_IMAGE_SCALE_RATIO);
    }
}