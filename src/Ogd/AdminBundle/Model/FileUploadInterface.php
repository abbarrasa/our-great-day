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
     * Get uploadable field list of entity
     *
     * @return array|null
     */
    public function getUploadableFields();

    /**
     * Get absolute upload path of uploadable field of entity
     *
     * @return string
     */
    public function getAbsolutePath($field = null);

    /**
     * Get asset file path of uploadable field of entity
     *
     * @return string
     */
    public function getWebPath($field = null);
}
