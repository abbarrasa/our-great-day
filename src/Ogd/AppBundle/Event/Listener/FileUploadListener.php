<?php

namespace AppBundle\Event\Listener;

use AdminBundle\Entity\Post;
use AdminBundle\Model\FileUploadInterface;
use AppBundle\Service\FileUploader;
use Application\Sonata\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use AppBundle\Service\FileUploaderFactory;

class FileUploadListener
{
    /** @var FileUploaderFactory */
    $fileUploaderFactory;
    
    public function __construct(FileUploaderFactory $fileUploaderFactory)
    {
        $this->fileUploaderFactory = $fileUploderFactory;
    }
    
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        
        // This logic only works for FileUploadInterface entities
        if (!$entity instanceof FileUploadInterface) {
            return;
        }        

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        // Retrieve Form as Entity
        $entity = $args->getEntity();
        
        // This logic only works for FileUploadInterface entities
        if (!$entity instanceof FileUploadInterface) {
            return;
        }

        // Check which fields were changes
        $changes = $args->getEntityChangeSet();
        
        if ($entity instanceof User) {
            if (array_key_exists('picture', $changes)) {
                $previousFileName = $changes['picture'][0];
                $pathPreviousFile = $entity->getUploadRootDir().'/'. $previousFileName;
                
                // Remove it
                if(file_exists($pathPreviousFile)){
                    unlink($pathPreviousFile);
                }
            }
        } else if ($entity instanceof Post) {
            if (array_key_exists('coverPicture', $changes)) {
                $previousFileName = $changes['coverPicture'][0];
                $pathPreviousFile = $entity->getUploadRootDir().'/'. $previousFileName;
                
                // Remove it
                if(file_exists($pathPreviousFile)){
                    unlink($pathPreviousFile);
                }
            }
        }

        // Upload new file
        $this->uploadFile($entity);
    }
    
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // This logic only works for FileUploadInterface entities
        if (!$entity instanceof FileUploadInterface) {
            return;
        }
        
        if ($filePath = $entity->getAbsolutePath()) {
            if (!file_exists($filePath)) {
                thrown new FileNotFoundException(null, 0, null, $filePath);
            }
            
            if ($entity instanceof User) {
                $entity->setPicture(new File($filePath));
            } else if ($entity instanceof Post) {
                $entity->setCoverPicture(new File($filePath));                
            }
        }        
    }    

    private function uploadFile($entity)
    {
        if ($entity instanceof User) {
            $file = $entity->getPicture();
        } else if ($entity instanceof Post) {
            $file = $entity->getCoverPicture();
        }

        if ($file instanceof UploadedFile) {
            $fileName = $this->fileUploaderFactory->create($entity)->upload($file);
            //$uploader = new FileUploader($entity->getUploadRootDir());
            //$fileName = $uploader->upload($file);
            
            if ($entity instanceof User) {
                $entity->setPicture($fileName);
            } else if ($entity instanceof Post) {
                $entity->setCoverPicture($fileName);
            }            
        }
    }
}
