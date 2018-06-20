<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    protected $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * Uploads a file
     * @param UploadedFile $file
     * @param bool $basename If true, returns basename of path. Else returns full path.
     * @return mixed
     */ 
    public function upload(UploadedFile $file, $basename = true)
    {
        $targetDirectory = $this->getTargetDirectory();        
        $fileName        = $this->generateFilename() . '.' . $file->guessExtension();
        
        if (!file_exists($targetDirectory)) {
            mkdir($targetDirectory, 0775);
        }

        $file->move($targetDirectory, $fileName);
        
        if (!$basename) {
            return $targetDirectory . DIRECTORY_SEPARATOR . $fileName;
        }

        return $fileName;
    }

    /**
     * Gets target directory for uploads
     * @return mixed
     */    
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
    
    protected function generateFilename()
    {
        return md5(uniqid());
    }
}
