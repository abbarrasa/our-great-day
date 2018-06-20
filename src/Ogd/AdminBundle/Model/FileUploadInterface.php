<?php

namespace AdminBundle\Model;

interface FileUploadInterface
{
    /**
     * Get absolute directory path for uploaded files
     *
     * @return string
     */
    public function getUploadRootDir();

    /**
     * Get absolute upload path
     *
     * @return string
     */
    public function getAbsolutePath();

    /**
     * Get asset file path
     *
     * @return string
     */
    public function getWebPath();
}