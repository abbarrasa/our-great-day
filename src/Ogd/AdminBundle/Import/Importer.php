<?php

namespace AppBundle\Import;

use Sonata\AdminBundle\Admin\AbstractAdmin;

class Importer
{
    public static function import(array $headers, array $rows, AbstractAdmin $admin)
    {
        //Filter repeated data
        $rows = array_unique($rows, SORT_REGULAR);
        
        $count = 0;
        foreach($rows as $row) {
            $reflectionClass = new \ReflectionClass($admin->getClass());
            $object          = $reflectionClass->newInstanceArgs();
            foreach($row as $index => $value) {
                $property           = $headers[$index];
                $reflectionProperty = $reflectionClass->getProperty($property);
                $reflectionProperty->setAccessible(true);
                $reflectionProperty->setValue($object, $value);
            }

            $admin->create($object);
            $count++;
        }

        return $count;
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
}
