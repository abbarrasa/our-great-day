<?php

namespace AdminBundle\Import;

use AdminBundle\Import\Storage\ImportStorage;
use Sonata\AdminBundle\Admin\AbstractAdmin;

class Importer
{
    public static function import(AbstractAdmin $admin, array $rows, array $headers = array())
    {
        $storage = self::getStorage($admin);
        $matrix  = $storage->filter(self::buildMatrix($headers, $rows), $errors);
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
        
        return new ImportResult($count, $errors);
    }

    /**
     * Get import storage class by an admin.
     * @param AbstractAdmin $admin
     * @return ImportStorage
     */
    protected function getStorage(AbstractAdmin $admin)
    {
        $namespace = __NAMESPACE__ . '\\Storage';
        $className  = $namespace . '\\' . self::classToTableName($admin->getClass()) . 'ImportStorage';

        return (new \ReflectionClass($className))->newInstance($admin);
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
        foreach($rows as $number => $row) {
            $matrix[$number] = array_combine($headers, $row);
        }

        return $matrix;
    }
}
