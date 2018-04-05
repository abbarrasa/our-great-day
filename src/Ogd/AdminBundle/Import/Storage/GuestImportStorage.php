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
    
    public function filter(array $rows, &$filtered)
    {
        $filtered = array();
        $visited  = array();
        foreach($rows as $number => $row) {
            $email = $row['email'];
            $firstname = $row['firstname'];
            $lastname = $row['lastname'];
            if (empty($firstname) || empty($lastname)) {
                $filtered[$number] = 
            }
        }
        
        
    }

    public function update(array $row)
    {
        $object = $this->getObject($row['email'], $row['firstname'], $row['lastname']);
        
        foreach($row as $index => $value) {
            $property = $headers[$index];
            $object->__set($property, $value);
        }
        
        $this->admin->create($object);
    }
                                           
    private function getObject($firstname, $lastname, $email = null)
    {
        $modelManager = $this->admin->getModelManager();
        if (!empty($email) && ($object = $modelManager->findOneBy(Guest::class, ['email' => $email])) !== null) {
            return $object;
        }
        
        if (($object = $modelManager->findOneBy(Guest::class, ['firstname' => $firstname, 'lastname' => $lastname])) !== null) {
            return $object;            
        }
        
        return new Guest();
    }
}
