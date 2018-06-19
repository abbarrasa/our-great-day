<?php

namespace AppBundle\Event\Listener;

use AdminBundle\Entity\Post;
use AppBundle\Service\Uploader\FileUploaderInterface;
use Application\Sonata\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class FileUploadListener
{
    /** @var FileUploaderInterface */
    private $uploader;

    public function __construct(FileUploaderInterface $uploader)
    {
        $this->uploader = $uploader;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    private function uploadFile($entity)
    {
        // upload only works for User and Post entities
        if ($entity instanceof User) {
            $file = $entity->getPicture();
            // only upload new files
            if ($file instanceof UploadedFile) {
                $fileName = $this->uploader->upload($file);
                $entity->setPicture($fileName);
            }
        } else if ($entity instanceof Post) {
            $file = $entity->getCoverPicture();
            // only upload new files
            if ($file instanceof UploadedFile) {
                $fileName = $this->uploader->upload($file);
                $entity->setCoverPicture($fileName);
            }
        }

        return;
    }
}