<?php
/**
 * PHPExcel
 *
 * This is a simplified version of the PHPExcel class for the car booking system.
 * In a real application, you would download the full PHPExcel library.
 */

class PHPExcel {
    /**
     * Document properties
     */
    private $properties;
    
    /**
     * Active sheet index
     */
    private $activeSheetIndex = 0;
    
    /**
     * Collection of worksheet objects
     */
    private $workSheetCollection = array();
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->properties = new PHPExcel_DocumentProperties();
        $this->workSheetCollection[] = new PHPExcel_Worksheet($this);
    }
    
    /**
     * Get properties
     */
    public function getProperties() {
        return $this->properties;
    }
    
    /**
     * Set active sheet index
     */
    public function setActiveSheetIndex($index) {
        $this->activeSheetIndex = $index;
        return $this->getActiveSheet();
    }
    
    /**
     * Get active sheet
     */
    public function getActiveSheet() {
        return $this->workSheetCollection[$this->activeSheetIndex];
    }
}

/**
 * PHPExcel_DocumentProperties
 */
class PHPExcel_DocumentProperties {
    private $creator = 'Unknown Creator';
    private $lastModifiedBy = 'Unknown';
    private $title = 'Untitled Spreadsheet';
    private $subject = '';
    private $description = '';
    private $keywords = '';
    private $category = '';
    
    public function setCreator($creator) {
        $this->creator = $creator;
        return $this;
    }
    
    public function setLastModifiedBy($lastModifiedBy) {
        $this->lastModifiedBy = $lastModifiedBy;
        return $this;
    }
    
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }
    
    public function setSubject($subject) {
        $this->subject = $subject;
        return $this;
    }
    
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }
    
    public function setKeywords($keywords) {
        $this->keywords = $keywords;
        return $this;
    }
    
    public function setCategory($category) {
        $this->category = $category;
        return $this;
    }
}

/**
 * PHPExcel_Worksheet
 */
class PHPExcel_Worksheet {
    private $parent;
    private $title = 'Worksheet';
    private $cells = array();
    private $columnDimensions = array();
    
    public function __construct($parent) {
        $this->parent = $parent;
    }
    
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }
    
    public function setCellValue($coordinate, $value) {
        $this->cells[$coordinate] = $value;
        return $this;
    }
    
    public function setCellValueByColumnAndRow($column, $row, $value) {
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($column);
        return $this->setCellValue($columnLetter . $row, $value);
    }
    
    public function getColumnDimension($column) {
        if (!isset($this->columnDimensions[$column])) {
            $this->columnDimensions[$column] = new PHPExcel_Worksheet_ColumnDimension();
        }
        return $this->columnDimensions[$column];
    }
    
    public function getStyle($coordinate) {
        return new PHPExcel_Style();
    }
    
    public function mergeCells($range) {
        return $this;
    }
}

/**
 * PHPExcel_Worksheet_ColumnDimension
 */
class PHPExcel_Worksheet_ColumnDimension {
    private $autoSize = false;
    
    public function setAutoSize($autoSize) {
        $this->autoSize = $autoSize;
        return $this;
    }
}

/**
 * PHPExcel_Style
 */
class PHPExcel_Style {
    private $font;
    private $alignment;
    private $borders;
    private $fill;
    
    public function __construct() {
        $this->font = new PHPExcel_Style_Font();
        $this->alignment = new PHPExcel_Style_Alignment();
        $this->borders = new PHPExcel_Style_Borders();
        $this->fill = new PHPExcel_Style_Fill();
    }
    
    public function getFont() {
        return $this->font;
    }
    
    public function getAlignment() {
        return $this->alignment;
    }
    
    public function getBorders() {
        return $this->borders;
    }
    
    public function getFill() {
        return $this->fill;
    }
}

/**
 * PHPExcel_Style_Font
 */
class PHPExcel_Style_Font {
    private $bold = false;
    private $size = 11;
    
    public function setBold($bold) {
        $this->bold = $bold;
        return $this;
    }
    
    public function setSize($size) {
        $this->size = $size;
        return $this;
    }
}

/**
 * PHPExcel_Style_Alignment
 */
class PHPExcel_Style_Alignment {
    const HORIZONTAL_LEFT = 'left';
    const HORIZONTAL_CENTER = 'center';
    const HORIZONTAL_RIGHT = 'right';
    
    private $horizontal = self::HORIZONTAL_LEFT;
    
    public function setHorizontal($horizontal) {
        $this->horizontal = $horizontal;
        return $this;
    }
}

/**
 * PHPExcel_Style_Borders
 */
class PHPExcel_Style_Borders {
    private $allBorders;
    
    public function __construct() {
        $this->allBorders = new PHPExcel_Style_Border();
    }
    
    public function getAllBorders() {
        return $this->allBorders;
    }
}

/**
 * PHPExcel_Style_Border
 */
class PHPExcel_Style_Border {
    const BORDER_THIN = 'thin';
    
    private $borderStyle = self::BORDER_THIN;
    
    public function setBorderStyle($borderStyle) {
        $this->borderStyle = $borderStyle;
        return $this;
    }
}

/**
 * PHPExcel_Style_Fill
 */
class PHPExcel_Style_Fill {
    const FILL_SOLID = 'solid';
    
    private $fillType = self::FILL_SOLID;
    private $startColor;
    
    public function __construct() {
        $this->startColor = new PHPExcel_Style_Color();
    }
    
    public function setFillType($fillType) {
        $this->fillType = $fillType;
        return $this;
    }
    
    public function getStartColor() {
        return $this->startColor;
    }
}

/**
 * PHPExcel_Style_Color
 */
class PHPExcel_Style_Color {
    private $argb = 'FFFFFFFF';
    
    public function setARGB($argb) {
        $this->argb = $argb;
        return $this;
    }
}

/**
 * PHPExcel_Cell
 */
class PHPExcel_Cell {
    public static function stringFromColumnIndex($columnIndex) {
        $columnString = '';
        
        while ($columnIndex >= 0) {
            $columnString = chr(65 + ($columnIndex % 26)) . $columnString;
            $columnIndex = floor($columnIndex / 26) - 1;
        }
        
        return $columnString;
    }
}

/**
 * PHPExcel_IOFactory
 */
class PHPExcel_IOFactory {
    public static function createWriter($phpExcel, $writerType) {
        return new PHPExcel_Writer_Excel2007($phpExcel);
    }
}

/**
 * PHPExcel_Writer_Excel2007
 */
class PHPExcel_Writer_Excel2007 {
    private $phpExcel;
    
    public function __construct($phpExcel) {
        $this->phpExcel = $phpExcel;
    }
    
    public function save($filename) {
        // In a real implementation, this would create an Excel file
        // For this simplified version, we'll just output some text
        if ($filename === 'php://output') {
            echo "Excel file would be generated here.\n";
        }
    }
}