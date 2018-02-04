<?php

namespace AppBundle\Templating\Helper;

use Symfony\Component\Templating\Helper\Helper;

class FlashMessageHelper extends Helper
{
    protected $allowedTypes;
    protected $alertClassNames;
    protected $iconClassNames;

    public function __construct($template = null)
    {
        $this->allowedTypes = ['notice', 'success', 'alert', 'error'];
        $this->alertClassNames = array_combine(
            $this->allowedTypes, ['info', 'success', 'warning', 'danger']
        );
        $this->iconClassNames = array_combine($this->allowedTypes, ['fa fa-exclamation-circle', 'fa fa-check-circle', 'fa fa-exlamation-triangle', 'fa fa-times-circle']);
    }

    public function getName()
    {
        return 'app_flash_message';
    }

    public function getAlertClassName($type)
    {
        if (!$this->isValidType($type)) {
            throw new \InvalidArgumentException(sprintf("'%s' is not a valid flash type", $type));
        }

        return $this->alertClassNames[$type];
    }

    public function getIconClassName($type)
    {
        if (!$this->isValidType($type)) {
            throw new \InvalidArgumentException(sprintf("'%s' is not a valid flash type", $type));
        }

        return $this->iconClassNames[$type];
    }

    public function getFlashMessage($type, $title, $message)
    {
        return [
            'alert'   => $this->getAlertClassName($type),
            'icon'    => $this->getIconClassName($type),
            'title'   => $title,
            'message' => $message
        ];
    }

    private function isValidType($type)
    {
        return in_array($type, $this->allowedTypes);
    }
}