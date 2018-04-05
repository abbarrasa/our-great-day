<?php

namespace AppBundle\Import;

use Sonata\AdminBundle\Admin\AbstractAdmin;

class Importer
{
    public static function import(AbstractAdmin $admin, array $rows, array $headers = array())
    {
        $storage = $this->getStorage($admin);
        $matrix  = $storage->filter($this->buildMatrix($headers, $rows), $errors);
        $count   = 0;        
        foreach($matrix as $row) {
            $storage->update($row);
            $count++;
        }
//        foreach($rows as $row) {
//            $reflectionClass = new \ReflectionClass($admin->getClass());
//            $object          = $reflectionClass->newInstanceArgs();
//            foreach($row as $index => $value) {
//                $property           = $headers[$index];
//                $reflectionProperty = $reflectionClass->getProperty($property);
//                $reflectionProperty->setAccessible(true);
//                $reflectionProperty->setValue($object, $value);
//            }

//            $admin->create($object);
//        }
        
        $result = new ImportResult();
        $result->setCount($count);
        $result->setErrors($errors);
        
        return $result;
    }

    protected function getStorage(AbstractAdmin $admin)
    {
        $name  = $this->classToTableName($admin->getClass()) . 'ImportStorage';

        return (new \ReflectionClass($name))->newInstance($admin);
    }
    
    protected function classToTableName($className)
    {
        if (strpos($className, '\\') !== false) {
            return substr($className, strrpos($className, '\\') + 1);
        }

        return $className;
    }
    
    private function buildMatrix($headers, $rows)
    {
        if (empty($headers)) {
            return $rows;
        }
        
        $matrix = array();
        foreach($rows as $row) {
            $matrix[] = array_combine($headers, $row);
        }
        
        return $matrix;
    }
}
