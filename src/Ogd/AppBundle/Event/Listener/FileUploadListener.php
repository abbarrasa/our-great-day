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

class FileUploadListener
{
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

    private function uploadFile($entity)
    {
        if ($entity instanceof User) {
            $file = $entity->getPicture();
        } else if ($entity instanceof Post) {
            $file = $entity->getCoverPicture();
        }

        if ($file instanceof File) {
            if ($file instanceof UploadedFile) {
                $uploader = new FileUploader($entity->getUploadRootDir());
                $fileName = $uploader->upload($file);
            } else {
                $fileName = $file->getFilename();
            }

            if ($entity instanceof User) {
                $entity->setPicture($fileName);
            } else if ($entity instanceof Post) {
                $entity->setCoverPicture($fileName);
            }
        }
    }
}
