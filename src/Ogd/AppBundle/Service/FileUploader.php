<?php

namespace AppBundle\Service\Uploader;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    protected $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file)
    {
        $targetDirectory = $this->getTargetDirectory();        
        $fileName        = md5(uniqid()).'.'.$file->guessExtension();
        
        if (!file_exists($targetDirectory)) {
            mkdir($targetDirectory, 0775);
        }

        $file->move($targetDirectory, $fileName);

        return $fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}
