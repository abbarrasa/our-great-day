<?php

namespace AppBundle\Templating\Helper;

use Symfony\Component\Templating\Helper\Helper;
use Symfony\Component\Translation\TranslatorInterface;

class FlashMessageHelper extends Helper
{
    protected $allowedTypes;
    protected $alertClassNames;
    protected $iconClassNames;

    /** @var TranslatorInterface */
    protected $translator;

    /**
     * FlashMessageHelper constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
        $this->allowedTypes = ['notice', 'success', 'alert', 'error'];
        $this->alertClassNames = array_combine(
            $this->allowedTypes, ['info', 'success', 'warning', 'danger']
        );
        $this->iconClassNames = array_combine(
            $this->allowedTypes, ['info_outline', 'check', 'warning', 'error_outline']
        );
    }

    public function getName()
    {
        return 'app_flash_message_helper';
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

    public function getFlashMessage($type, $title, $message, $parameters = array())
    {
        return [
            'alert'   => $this->getAlertClassName($type),
            'icon'    => $this->getIconClassName($type),
            'title'   => $this->translator->trans($title),
            'message' => $this->translator->trans($message, $parameters)
        ];
    }

    private function isValidType($type)
    {
        return in_array($type, $this->allowedTypes);
    }
}
