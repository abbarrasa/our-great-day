<?php

namespace AppBundle\Service\Uploader;

use AdminBundle\Entity\Post;

class CoverPicturePostUploader extends FileUploader
{
    public function __construct()
    {
        $this->targetDirectory = Post::getUploadRootDir();
    }  
}
