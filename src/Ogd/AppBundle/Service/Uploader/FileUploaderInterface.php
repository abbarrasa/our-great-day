<?php

namespace AppBundle\Service\Uploader;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileUploaderInterface
{
    /**
     * Gets target directory for uploads
     * @return mixed
     */
    public function getTargetDirectory();

    /**
     * Uploads a file
     * @param UploadedFile $file
     * @param bool $basename If true, returns basename of path. Else returns full path.
     * @return mixed
     */
    public function upload(UploadedFile $file, $basename = true);
}