<?php

namespace AdminBundle\Import\Storage;

use AdminBundle\Entity\Guest;
use Sonata\AdminBundle\Admin\AbstractAdmin;

class GuestImportStorage implements ImportStorage
{
    private $admin;
    
    public function __construct(AbstractAdmin $admin)
    {
        $this->admin = $admin;
    }
    
    public function filterData(array $headers, array $rows)
    {
        // TODO: Implement validateData() method.
    }

    public function updateData(array $headers, array $rows)
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
