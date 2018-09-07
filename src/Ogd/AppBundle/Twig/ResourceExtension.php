<?php

namespace AppBundle\Twig;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class ResourceExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_resource';
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('data_uri', [$this, 'dataUriFilter']),
            new \Twig_SimpleFilter('basename', [$this, 'basenameFilter'])
        ];
    }

    public function dataUriFilter($source)
    {
        if (!file_exists($source)) {
            throw new FileNotFoundException(null, 0, null, $source);
        }

        $data    = base64_encode(file_get_contents($source));
        $dataUri = 'data:'.mime_content_type($source).';base64,'.$data;

        return $dataUri;
    }
}
