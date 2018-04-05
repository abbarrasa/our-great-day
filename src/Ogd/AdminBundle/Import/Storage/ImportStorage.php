<?php

namespace AdminBundle\Import\Storage;


interface ImportStorage
{
    public function filter(array $rows, &$errors = array());
    
    public function update(array $row);    
    
}
