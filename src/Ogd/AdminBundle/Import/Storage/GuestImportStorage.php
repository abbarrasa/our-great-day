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

    public function store($data, array &$errors = null, array $options = array())
    {
        $count = 0;
        $data  = $this->filter($data, $errors);
        foreach($data as $row) {
            $this->updateRow($row);
            $count++;
        }
        
        return $count;
    }
        
    protected function filter(array $rows, array &$errors = null)
    {
        $visited = array();
        foreach($rows as $number => $row) {
            $email     = $row['email'];
            $firstname = $row['firstname'];
            $lastname  = $row['lastname'];
            if (empty($firstname) || empty($lastname)) {
                $errors[$number] = sprintf("Required columns are empty: [%s, %s]", $firstname, $lastname);
                continue;
            }
            
            if (!empty($email) && in_array($email, array_column($visited, 'email'))) {
                $errors[$number] = sprintf("Value of %s column is repeated", $email);
                continue;                
            }
                
            if (in_array($firstname, array_column($visited, 'firstname')) &&
                in_array($lastname, array_column($visited, 'lastname'))
            ) {
                $errors[$number] = sprintf("Value of %s and %s column are repeated", $firstname, $lastname);
                continue;                
            }
            
            $visited[] = $row;
        }
        
        return $visited;        
    }
    
    protected function updatRow($row)
    {
        $object = $this->getObject($row['firstname'], $row['lastname'], $row['email']);
        foreach($row as $property => $value) {
            if (!empty($value)) {
                if (in_array($property, ['guests', 'vegans', 'childs'])) {
                    $object->__set($property, (int)$value);
                } else {
                    $object->__set($property, (string)$value);
                }
            }
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
