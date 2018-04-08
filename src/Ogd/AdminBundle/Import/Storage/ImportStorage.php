<?php

namespace AdminBundle\Import\Storage;


interface ImportStorage
{
    public function filter(array $rows, array &$errors = null);
    
    public function update(array $row);    
    
}
