<?php

namespace AppBundle\Templating\Helper;

use Symfony\Component\Templating\Helper\Helper;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SocialUrlHelper extends Helper
{
    const URL_PATTERN_FACEBOOK   = 'https://www.facebook.com/sharer.php?u=%url%&t=%text%';
    const URL_PATTERN_TWITTER    = 'https://twitter.com/intent/tweet?url=%url%&text=%text%&hashtags=%hashtags%';
    const URL_PATTERN_GOOGLEPLUS = 'https://plus.google.com/share?url=%url%';
    const TEXT                   = 'Nuestro gran día';
    const HASHTAGS               = ['veroycarlos', 'boda', 'bodorriol', 'nuestrograndía'];

    /** @var UrlGeneratorInterface */
    protected $router;

    /**
     * SocialHelper constructor.
     *
     * @param UrlGeneratorInterface $router
     */
    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public function getName()
    {
        return 'app_social_url_helper';
    }

    /**
     * Generates sharer url for Facebook
     *
     * @param array $parameters
     * @return string
     */
    public function generateFacebookUrl(array $parameters = array())
    {
        $default    = ['text' => self::TEXT, 'hashtags' => implode(', ', self::HASHTAGS)];
        $parameters = array_merge($default, $parameters);

        return $this->generateSocialUrl(self::URL_PATTERN_FACEBOOK, $parameters);
    }

    /**
     * Generates tweet url for Twitter
     *
     * @param array $parameters
     * @return string
     */
    public function generateTwitterUrl(array $parameters = array())
    {
        $default    = ['text' => self::TEXT, 'hashtags' => implode(', ', self::HASHTAGS)];
        $parameters = array_merge($default, $parameters);

        return $this->generateSocialUrl(self::URL_PATTERN_TWITTER, $parameters);
    }

    /**
     * Generates sharer url for Google Plus
     *
     * @param array $parameters
     * @return string
     */
    public function generateGoogleplusUrl(array $parameters = array())
    {
        $default    = ['text' => self::TEXT, 'hashtags' => implode(', ', self::HASHTAGS)];
        $parameters = array_merge($default, $parameters);

        return $this->generateSocialUrl(self::URL_PATTERN_GOOGLEPLUS, $parameters);
    }


    private function generateSocialUrl($pattern, array $parameters)
    {
        return strtr($pattern, [
            '%url%' => $this->router->generate('homepage', [], UrlGeneratorInterface::ABSOLUTE_URL),
            '%text%' => urlencode($parameters['text']),
            '%hashtags%' => urlencode($parameters['hashtags'])
        ]);
    }
}