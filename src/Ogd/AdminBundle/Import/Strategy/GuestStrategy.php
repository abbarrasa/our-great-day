<?php

namespace AdminBundle\Import\Strategy;

class GuestStrategy implements StrategyInterface
{
    private $admin;
    
    public function __construct(AbstractAdmin $admin)
    {
        $this->admin = $admin;
    }
    
    public function validateData(array $headers, array $data)
    {
        // TODO: Implement validateData() method.
    }

    public function import(array $headers, array $data)
    {
        $count = 0;
        foreach($rows as $row) {
            $reflectionClass = new \ReflectionClass(Guest::class);
            $object          = $reflectionClass->newInstanceArgs();
            foreach($row as $index => $value) {
                $property           = $headers[$index];
                $reflectionProperty = $reflectionClass->getProperty($property);
                $reflectionProperty->setAccessible(true);
                $reflectionProperty->setValue($object, $value);
            }
            $this->admin->create($object);
            $count++;
        }
        
        return $count;
    }

}
