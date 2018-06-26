<?php

namespace AppBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class NameToFileTransformer implements DataTransformerInterface
{
    private $directory;

    public function __construct($directory)
    {
        $this->directory = $directory;
    }

    public function transform($value)
    {
        if ($value === null) {
            return null;
        }

        $path = $this->directory.'/'.basename($value);

        if (!file_exists($path)) {
            throw new TransformationFailedException(sprintf(
                'File "%s" does not exist',
                $path
            ));
        }

        return new File($path);
    }

    public function reverseTransform($value)
    {
        if ($value instanceof UploadedFile) {
            return $value;
        }

        if ($value instanceof File) {
            return $value->getFilename();
        }

        return $value;
    }
}