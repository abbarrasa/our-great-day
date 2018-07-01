<?php

namespace AppBundle\Templating\Helper;

use Vich\UploaderBundle\Templating\Helper\UploaderHelper as BaseHelper;

/**
 * Class UploaderHelper
 * @package AppBundle\Templating\Helper
 */
class UploaderHelper extends BaseHelper
{
    /**
     * Gets the absolute o relative path for the file associated with the object
     * and mapping name.
     *
     * @param object|array $obj       The object
     * @param string       $fieldName The field name
     * @param string       $className The object's class. Mandatory if $obj can't be used to determine it
     * @param bool         $relative  Whether the path should be relative or absolute
     *
     * @return string
     */
    public function path($obj, $fieldName, $className = null, $relative = false)
    {
        return $this->storage->resolvePath($obj, $fieldName, $className, $relative);
    }
}