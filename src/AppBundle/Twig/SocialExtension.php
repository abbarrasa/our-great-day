<?php

namespace AppBundle\Twig;

use AppBundle\Templating\Helper\SocialUrlHelper;
use Symfony\Component\Translation\TranslatorInterface;

class SocialExtension extends \Twig_Extension
{
    /**
     * @var SocialUrlHelper
     */
    protected $socialUrlHelper;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * SocialExtension constructor.
     *
     * @param SocialUrlHelper $socialUrlHelper
     * @param TranslatorInterface $translator
     */
    public function __construct(SocialUrlHelper $socialUrlHelper, TranslatorInterface $translator)
    {
        $this->socialUrlHelper = $socialUrlHelper;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_social';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('facebook_social_link', array($this, 'facebookLinkFunction'),
                array('needs_environment' => false, 'pre_escape' => 'html', 'is_safe' => array('html'))),
            new \Twig_SimpleFunction('twitter_social_link', array($this, 'generateVideoCutLink'),
                array('needs_environment' => false, 'pre_escape' => 'html', 'is_safe' => array('html'))),
            new \Twig_SimpleFunction('googleplus_social_link', array($this, 'generateVideoCutLink'),
                array('needs_environment' => false, 'pre_escape' => 'html', 'is_safe' => array('html'))),
        ];
    }

    /**
     * Prints sharer link for Facebook
     *
     * @param string $content
     * @param array $attributes
     * @return string
     */
    public function facebookLinkFunction($content = '', array $attributes = array())
    {
        $defaults   = [
            'title'   => $this->translator->trans('frontend.menu.share', ['%site%' => 'Facebook']),
            'onclick' => "javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;",
            'target' => '_blank'
        ];
        $attributes = array_merge($defaults, $attributes);

        return $this->generateLink($this->socialUrlHelper->generateFacebookUrl(), $content, $attributes);
    }

    /**
     * Prints tweet link for Twitter
     *
     * @param string $content
     * @param array $attributes
     * @return string
     */
    public function twitterLinkFunction($content = '', array $attributes = array())
    {
        $defaults   = [
            'title'   => $this->translator->trans('frontend.menu.share', ['%site%' => 'Twitter']),
            'onclick' => "javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;",
            'target' => '_blank'
        ];
        $attributes = array_merge($defaults, $attributes);

        return $this->generateLink($this->socialUrlHelper->generateTwitterUrl(), $content, $attributes);
    }

    /**
     * Prints sharer link for Google Plus
     *
     * @param string $content
     * @param array $attributes
     * @return string
     */
    public function googleplusLinkFunction($content = '', array $attributes = array())
    {
        $defaults   = [
            'title'   => $this->translator->trans('frontend.menu.share', ['%site%' => 'Google+']),
            'onclick' => "javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=350,width=480');return false;",
            'target' => '_blank'
        ];
        $attributes = array_merge($defaults, $attributes);

        return $this->generateLink($this->socialUrlHelper->generateGoogleplusUrl(), $content, $attributes);
    }
    
    private function generateLink($url, $content = '', array $attributes = array())
    {
        $defaults   = ['href' => $url];
        $attributes = array_merge($defaults, $attributes);

        $htmlAttributes = array_map(function($value, $key) {
            return $key . '="' . $value . '"';
        }, $attributes, array_keys($attributes));

        $html = '<a ' . implode(' ', $htmlAttributes) . '>' . $content . '</a>';

        return $html;
    }
}
