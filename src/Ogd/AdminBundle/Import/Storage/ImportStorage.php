<?php

namespace AdminBundle\Import\Storage;


interface ImportStorage
{
    public function filterData(array $headers, array $rows);
    
    public function updateData(array $data);    
    
}
