<?php

namespace AppBundle\Twig;

use AppBundle\Templating\Helper\FlashMessageHelper;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class FlashMessageExtension extends \Twig_Extension
{
    /**
     * @var SessionInterface
     */
    private $session;

    /** @var FlashMessageHelper */
    private $helper;

    /**
     * FlashMessageExtension constructor.
     * @param SessionInterface $session
     * @param FlashMessageHelper $helper
     */
    public function __construct(SessionInterface $session, FlashMessageHelper $helper)
    {
        $this->session = $session;
        $this->helper  = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_flash_message';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('flash_messages', [$this, 'renderMessages'], [
                'is_safe' => ['html'],
                'needs_environment' => true
            ])
        );
    }

    /**
     * @param \Twig_Environment $environment
     * @param string  $type Message type. If null returns all types messages,
     * otherwise returns messages with given types.
     *
     * @return string
     */
    public function renderMessages(\Twig_Environment $environment, $type = null)
    {
        if ($type === null) {
            $flashBag = $this->session->getFlashBag()->all();
        } else {
            $flashBag[$type] = $this->session->getFlashBag()->get($type);
        }

        foreach($flashBag as $type => $message) {
            $flashes[] = $this->helper->getFlash($type, $message);
        }

        return $environment->render($this->helper->getViewTemplateName(), [
            'flashes' => $flashes
        ]);
    }
}