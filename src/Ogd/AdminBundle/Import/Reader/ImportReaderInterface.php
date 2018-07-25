<?php

namespace AdminBundle\Import\Reader;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

interface ImportReaderInterface
{
    public function read(Spreadsheet $spreadsheet, array $options = array());
}