<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $defaultTargetDirectory;

    public function __construct($defaultTargetDirectory)
    {
        $this->defaultTargetDirectory = $defaultTargetDirectory;
    }

    public function upload(UploadedFile $file, $targetDirectory = null)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();        
        if (null === $targetDirectory) {
            $targetDirectory = $this->getDefaultTargetDirectory();
        }

        if (!file_exists($targetDirectory)) {
            mkdir($targetDirectory, 0775);
        }

        $file->move($targetDirectory, $fileName);

        return $fileName;
    }

    public function getDefaultTargetDirectory()
    {
        return $this->defaultTargetDirectory;
    }
}
