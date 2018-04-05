<?php

namespace AdminBundle\Import\Storage;


interface ImportStorage
{
    public function filter(array $rows, &$errors);
    
    public function update(array $row);    
    
}
