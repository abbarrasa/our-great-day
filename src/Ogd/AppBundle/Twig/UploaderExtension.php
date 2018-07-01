<?php

namespace AppBundle\Twig;

use AppBundle\Templating\Helper\UploaderHelper;

/**
 * Class UploaderExtension
 * @package AppBundle\Twig
 */
class UploaderExtension extends \Twig_Extension
{
    /**
     * @var UploaderHelper
     */
    private $helper;

    /**
     * UploaderExtension constructor.
     * @param UploaderHelper $helper
     */
    public function __construct(UploaderHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_uploader';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('app_uploader_path', [$this, 'pathFunction']),
            new \Twig_SimpleFunction('app_uploader_asset', [$this, 'assetFunction']),
        ];
    }

    /**
     * Gets the absolute or relative path for the file associated with the uploadable object.
     *
     * @param object $obj       The object
     * @param string $fieldName The field name
     * @param string $className The object's class. Mandatory if $obj can't be used to determine it
     * @param bool   $relative  Whether the path should be relative or absolute
     *
     * @return string
     */
    public function pathFunction($obj, $fieldName, $className = null, $relative = false)
    {
        return $this->helper->path($obj, $fieldName, $className, $relative);
    }

    /**
     * Gets the public path for the file associated with the uploadable object.
     *
     * @param object $obj       The object
     * @param string $fieldName The field name
     * @param string $className The object's class. Mandatory if $obj can't be used to determine it
     *
     * @return string|null The public path or null if file not stored
     */
    public function assetFunction($obj, string $fieldName, string $className = null): ?string
    {
        return $this->helper->asset($obj, $fieldName, $className);
    }
}