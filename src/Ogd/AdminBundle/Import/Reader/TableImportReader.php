<?php

namespace AdminBundle\Import\Reader;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

class TableImportReader implements ImportReaderInterface
{
    static private $_TABLE_SHEET_NAMES = ['MESAS', 'TABLES'];
    static private $_SEAT_SHEET_NAMES = ['ASIENTOS', 'INVITADOS', 'SEATS', 'GUESTS'];
    
    public function read(Spreadsheet $spreadsheet, array $options = array())
    {
        $tablesSheet = $this->getSheetByName(self::$_TABLE_SHEET_NAMES, $spreadsheet);
        $seatsSheet  = $this->arrayGroupBy(
            $this->getSheetByName(self::$_SEAT_SHEET_NAMES, $spreadsheet), function($i){  return $i['table']; }
        );
        
        return [
            $tablesSheet !== null ? $this->readSheet($tablesSheet) : [],
            $seatsSheet !== null ? $this->readSheet($seatsSheet) : []
        ];
    }
    
    private function getSheetByName(array $needle, Spreadsheet $spreadsheet)
    {
        $sheetNames = array_map(function($name) { return strtoupper($name); }, $spreadsheet->getSheetNames());
        foreach($needle as $name) {
            if (($index = array_search($name, $sheetNames)) !== false) {
                return $spreadsheet->getSheet($index);
            }
        }
        
        return null;
    }
    
    private function readSheet($sheet)
    {
        $rows        = $sheet->toArray();
        $headers     = reset($rows);
        $rows        = array_slice($rows, 1, null, true);
        //Filter repeated data
        $rows        = array_unique($rows, SORT_REGULAR);

        return $this->buildMatrix($headers, $rows);        
    }

    private function buildMatrix($headers, $rows)
    {
        if (empty($headers)) {
            return $rows;
        }

        $matrix = array();
        foreach($rows as $number => $row) {
            $matrix[$number] = array_combine($headers, $row);
        }

        return $matrix;
    }

    function arrayGroupBy(array $arr, callable $keySelector)
    {
        $result = array();
        foreach ($arr as $i) {
            $key = call_user_func($keySelector, $i);
            $result[$key][] = $i;
        }

        return $result;
    }
}