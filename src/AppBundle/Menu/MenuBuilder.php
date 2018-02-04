<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MenuBuilder
{
    const TEXT			         = 'Nuestro gran día';
    const HASHTAGS               = ['veroycarlos', 'boda'];
    const URL_PATTERN_FACEBOOK   = 'https://www.facebook.com/sharer.php?u=%url%&t=%text%';
    const URL_PATTERN_TWITTER    = 'https://twitter.com/intent/tweet?url=%url%&text=%text%&hashtags=%hashtags%';
    const URL_PATTERN_GOOGLEPLUS = 'https://plus.google.com/share?url=%url%';

    /** @var FactoryInterface */
    private $factory;

    /** @var UrlGeneratorInterface */
    protected $router;

    /**
     * MenuBuilder constructor.
     * @param FactoryInterface $factory
     * @param UrlGeneratorInterface $router
     */
    public function __construct(FactoryInterface $factory, UrlGeneratorInterface $router)
    {
        $this->factory = $factory;
        $this->router  = $router;
    }

    public function createSocialMenu(array $options)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');

        $menu
            ->addChild('facebook', ['uri' => $this->generateSocialUrl(self::URL_PATTERN_FACEBOOK)])
            ->setLabel('')
            ->setAttribute('icon', 'fa fa-facebook-square')
            ->setLinkAttributes([
                'onclick' => "javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;",
                'target' => '_blank',
                'title' => 'Share on Facebook'
            ])
            ->setExtra('translation_domain', 'AppBundle')
        ;

        $menu
            ->addChild('twitter', ['uri' => $this->generateSocialUrl(self::URL_PATTERN_TWITTER)])
            ->setLabel('')
            ->setAttribute('icon', 'fa fa-twitter')
            ->setLinkAttributes([
                'onclick' => "javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;",
                'target' => '_blank',
                'title' => 'Share on Twitter'
            ])
            ->setExtra('translation_domain', 'AppBundle')
        ;

        $menu
            ->addChild('googleplus', ['uri' => $this->generateSocialUrl(self::URL_PATTERN_GOOGLEPLUS)])
            ->setLabel('')
            ->setAttribute('icon', 'fa fa-google-plus-square')
            ->setLinkAttributes([
                'onclick' => "javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=350,width=480');return false;",
                'target' => '_blank',
                'title' => 'Share on Google+'
            ])
            ->setExtra('translation_domain', 'AppBundle')
        ;

        $menu
            ->addChild('Contact us!', ['uri' => '#'])
            ->setAttribute('icon', 'fa fa-envelope-o')
            ->setExtra('translation_domain', 'AppBundle')
        ;

        return $menu;
    }

    private function generateSocialUrl($pattern)
    {
        return strtr($pattern, [
            '%url%' => $this->router->generate('homepage', [], UrlGeneratorInterface::ABSOLUTE_URL),
            '%text%' => urlencode(self::TEXT),
            '%hashtags%' => urlencode(implode(',', self::HASHTAGS))
        ]);
    }

}