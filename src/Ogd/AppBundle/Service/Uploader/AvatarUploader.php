<?php

namespace AppBundle\Service\Uploader;

use Application\Sonata\UserBundle\Entity\User;

class AvatarUploader extends FileUploader
{
    public function __construct()
    {
        $this->targetDirectory = User::getUploadRootDir();
    }  
}
