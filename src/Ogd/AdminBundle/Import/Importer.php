<?php

namespace AdminBundle\Import;

use AdminBundle\Import\Storage\ImportStorage;
use Sonata\AdminBundle\Admin\AbstractAdmin;

class Importer
{
    private $reader;
    private $storage;
    
    public function __construct(ImportReaderInterface $reader, ImportStorageInterface $storage)
    {
        $this->reader  = $reader;
        $this->storage = $storage;
    }
    
    //public function import(AbstractAdmin $admin, array $rows, array $headers = array())
    public function import($fileName)
    {
        $spreadsheet = $this->open($fileName);
        $data        = $this->reader->read($spreadsheet);
        $count       = $this->storage->store($data, $errors);

        /*$storage = self::getStorage($admin);
        $matrix  = $storage->filter(self::buildMatrix($headers, $rows), $errors);
        $count   = 0;
        foreach($matrix as $row) {
            $storage->update($row);
            $count++;
        }*/
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
    
    public function open($fileName)
    {
        /* Identify the type of $fileName  */
        $fileType = IOFactory::identify($fileName);
        /* Create a new Reader of the type that has been identified  */
        $reader = IOFactory::createReader($fileType);
        /* Advise the Reader that we only want to load cell data  */
        $reader->setReadDataOnly(true);
        /* Load $fileName to a Spreadsheet Object  */
        $spreadsheet = $reader->load($fileName);

        return $spreadsheet;
    }    

    /**
     * Get import storage class by an admin.
     * @param AbstractAdmin $admin
     * @return ImportStorage
     */
  /*  protected function getStorage(AbstractAdmin $admin)
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
    }*/
}
