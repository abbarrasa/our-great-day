<?php

namespace AdminBundle\Import\Storage;


interface ImportStorage
{
    public function filter(array $headers, array $rows, &$filtered);
    
    public function update(array $row);    
    
}
