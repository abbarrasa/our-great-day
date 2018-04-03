<?php

namespace AdminBundle\Import\Strategy;

use AdminBundle\Entity\Guest;
use Sonata\AdminBundle\Admin\AbstractAdmin;

class GuestStrategy implements ImportStrategy
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

    public function import(array $headers, array $rows)
    {
        $count = 0;
        foreach($rows as $row) {
            $object = new Guest();
            foreach($row as $index => $value) {
                $property = $headers[$index];
                $object->__set($property, $value);
            }
            $this->admin->create($object);
            $count++;
        }
        
        return $count;
    }

}
