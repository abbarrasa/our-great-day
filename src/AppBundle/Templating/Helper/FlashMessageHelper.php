<?php

namespace AppBundle\Templating\Helper;

use Symfony\Component\Templating\Helper\Helper;

class FlashMessageHelper extends Helper
{
    protected $allowedTypes;
    protected $alertClassNames;
    protected $iconClassNames;
    protected $viewTemplateName;

    public function __construct()
    {
        $this->allowedTypes = ['notice', 'success', 'alert', 'error'];
        $this->alertClassNames = array_combine($this->allowedTypes, ['info', 'success', 'warning', 'danger']);
        $this->iconClassNames = array_combine($this->allowedTypes, ['info_outline', 'check', 'warning', 'error_outline']);
        $this->viewTemplateName = 'AppBundle:flash:messages.html.twig';
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

    public function getViewTemplateName()
    {
        return $this->viewTemplateName;
    }

    public function getFlash($type, $message)
    {
        return [
            'type'    => $type,
            'class'   => $this->getAlertClassName($type),
            'icon'    => $this->getIconClassName($type),
            'message' => $message
        ];
    }

    private function isValidType($type)
    {
        return in_array($type, $this->allowedTypes);
    }
}