<?php

namespace AdminBundle\Import\Storage;


interface ImportStorageInterface
{
    //public function filter($data, array &$errors = null);
    
    public function store($data, array &$errors = null, array $options = array());
    
    //public function updateRow(array $row);
    
}
