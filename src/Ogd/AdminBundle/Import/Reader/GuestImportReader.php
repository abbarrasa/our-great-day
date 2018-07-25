<?php

namespace AdminBundle\Import\Reader;

class GuestImportReader implements ImportReaderInterface
{
    public function read($spreadsheet, array $options = array())
    {
        $worksheet   = $spreadsheet->getActiveSheet();
        $rows        = $worksheet->toArray();
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
}