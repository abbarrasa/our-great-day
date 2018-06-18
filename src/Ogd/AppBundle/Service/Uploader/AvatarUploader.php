<?php

namespace AppBundle\Service\Uploader;

class AvatarUploader extends FileUploader
{
    public function __construct()
    {
        $this->targetDirectory = User::PICTURE_WEB_DIR;
    }  
}
