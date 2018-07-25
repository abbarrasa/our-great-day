<?php

namespace AdminBundle\Import;


interface ImportStorageInterface
{
    public function filter($data, array &$errors = null);
    
    public function store($data, array &$errors = null);
    
    public function updateRow(array $row);
    
}
