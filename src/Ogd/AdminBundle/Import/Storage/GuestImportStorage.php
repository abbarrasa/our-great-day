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
    
    public function filterData(array $headers, array $rows, &$filtered)
    {
        $filtered = array();
        
        // TODO: Implement validateData() method.
    }

    public function updateData(array $headers, array $rows)
    {       
        $count        = 0;
        $emailKey     = array_search('email', $headers);
        $firstnameKey = array_search('firstname', $headers);
        $lastnameKey  = array_search('lastname', $headers);        
        foreach($rows as $row) {
            $object = $this->getObject($row[$lastKey], $row[$firstnameKey], $row[$emailKey]);
            foreach($row as $index => $value) {
                $property = $headers[$index];
                $object->__set($property, $value);
            }
            $this->admin->create($object);
            $count++;
        }
        
        return $count;
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
