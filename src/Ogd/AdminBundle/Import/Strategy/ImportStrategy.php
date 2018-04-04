<?php

namespace AdminBundle\Import\Storage;


interface ImportStrategy
{
    public function filterData(array $headers, array $rows);
    
}
