<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
require 'vendor/autoload.php';

class ExcelReader
{
    public $fields;
    /** columns names retrieved after parsing */
    public $separator = ';';
    /** separator used to explode each line */
    public $enclosure = '"';
    /** enclosure used to decorate each field */
    public $max_row_size = 4096;

    public function parse_file($file)
    {
        try {
            $objReader = IOFactory::createReader('Xlsx');
            $objPHPExcel = $objReader->load($file);
            $data = array();

            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                $sheetData = $worksheet->toArray(null, true, true, true);
                foreach ($sheetData as $row => $rowData) {
                    if ($row == 1) {
                        continue; // Skip header row
                    }
                    $class_id = intval($worksheet->getTitle()[0]);
                    $section_id = intval($worksheet->getTitle()[strlen($worksheet->getTitle()) - 1]);

                    $no = isset($rowData['A']) ? $rowData['A'] : '';
                    $dataRow = array(
                        'admission_no' => isset($rowData['B']) ? $rowData['B'] : '',
                        'firstname' => isset($rowData['C']) ? $rowData['C'] : '',
                        'lastname' => isset($rowData['D']) ? $rowData['D'] : '',
                        'gender' => isset($rowData['E']) ? $rowData['E'] : '',
                        'date_of_birth' => isset($rowData['F']) ? $rowData['F'] : '',
                        'city' => isset($rowData['G']) ? $rowData['G'] : '',
                        'class_id' => $class_id,
                        'section_id' => $section_id
                        // Add more fields as needed
                    );
                    if (is_numeric($no)) {
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