<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
require 'vendor/autoload.php';

/*
* Import student data from custom stylished xlsx file
*/
class ExcelReader
{
    protected $CI;
    
    public $fields;
    /** columns names retrieved after parsing */
    public $separator = ';';
    /** separator used to explode each line */
    public $enclosure = '"';
    /** enclosure used to decorate each field */
    public $max_row_size = 4096;

    public function __construct()
    {
        $this->CI =& get_instance();

        $this->CI->load->model('Section_model');
        $this->CI->load->model('Class_model');
        $this->CI->load->model('Classsection_model');
    }
    public function parse_file($file)
    {
        try {
            $objReader = IOFactory::createReader('Xlsx');
            $objPHPExcel = $objReader->load($file);
            $data = array();

            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                $sheetData = $worksheet->toArray(null, true, true, true);

                $class_name = isset($sheetData[7]['C']) ? $sheetData[7]['C'] : null;
                $section_name = isset($sheetData[8]['C']) ? $sheetData[8]['C'] : null;

                // add class if not exist
                if ($class_name != null) {
                    if (!$this->CI->class_model->check_data_exists($class_name)) {
                        $this->CI->class_model->addByName($class_name);
                        log_message('error', 'name_ok_class ' . $class_name);
                    }
                    $class_id = $this->CI->class_model->getByName($class_name);
                }
                // add section if not exist
                if ($section_name != null) {
                    if (!$this->CI->section_model->checkByName($section_name)) {
                        $this->CI->section_model->addByName($section_name);
                        log_message('error', 'name_ok_section ' . $section_name);
                    }
                    $section_id = $this->CI->section_model->getByName($section_name);
                }
                // add class and section matching table if not exist
                if ($section_id != null && $class_id != null) {
                    if (!$this->CI->classsection_model->check_data_exists(array('class_id' => $class_id, 'section_id' => $section_id))) {
                        $this->CI->classsection_model->addClassSection($class_id, $section_id);
                        log_message('error', 'name_ok_section_class ' . $class_id . $section_id);
                    }
                }


                foreach ($sheetData as $row => $rowData) {
                    if ($row == 1) {
                        continue; // Skip header row
                    }

                    $no = isset($rowData['A']) ? $rowData['A'] : '';

                    // if (strpos($no, ": المستوى") === true) {
                    //     $class_name = isset($rowData['B']) ? $rowData['B'] : '';
                    // }

                    if (is_numeric($no)) {
                        $adimssion_no = isset($rowData['B']) ? $rowData['B'] : '';
                        if ($adimssion_no !== '') {
                            $dataRow = array(
                                'admission_no' => $adimssion_no,
                                'firstname' => isset($rowData['C']) ? $rowData['C'] : '',
                                'lastname' => isset($rowData['D']) ? $rowData['D'] : '',
                                'gender' => isset($rowData['E']) ? $rowData['E'] : '',
                                'dob' => isset($rowData['F']) ? $rowData['F'] : '',
                                'city' => isset($rowData['G']) ? $rowData['G'] : '',
                                'email' => $adimssion_no . '@taalim.ma',
                                'class_id' => $class_id,
                                'section_id' => $section_id
                                // Add more fields as needed
                            );
                        }
                        $data[] = $dataRow;
                    }
                }
            }
            return $data;
        } catch (Exception $e) {
            log_message('error', 'Error parsing Excel file: ' . $e->getMessage());
            return array();
        }
    }

    private function getFileType($file)
    {
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        log_message('error', 'doc_file_extension: ' . $extension);

        switch ($extension) {
            case 'xlsx':
                return 'Xlsx';
            case 'xls':
                return 'Xls';
            default:
                throw new Exception('Invalid file type');
        }
    }
}