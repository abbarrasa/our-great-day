<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;

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

    /** @var TranslatorInterface */
    protected $translator;

    /**
     * MenuBuilder constructor.
     * @param FactoryInterface $factory
     * @param UrlGeneratorInterface $router
     * @param TranslatorInterface $translator
     */
    public function __construct(FactoryInterface $factory, UrlGeneratorInterface $router, TranslatorInterface $translator)
    {
        $this->factory    = $factory;
        $this->router     = $router;
        $this->translator = $translator;
    }

    public function createSocialMenu()
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
                'title' => $this->translator->trans('frontend.menu.share', ['%site%' => 'Facebook']),
                'data-toggle' => 'tooltip',
                'data-placement' => 'bottom'
            ])
        ;

        $menu
            ->addChild('twitter', ['uri' => $this->generateSocialUrl(self::URL_PATTERN_TWITTER)])
            ->setLabel('')
            ->setAttribute('icon', 'fa fa-twitter')
            ->setLinkAttributes([
                'onclick' => "javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;",
                'target' => '_blank',
                'title' => $this->translator->trans('frontend.menu.share', ['%site%' => 'Twitter']),
                'data-toggle' => 'tooltip',
                'data-placement' => 'bottom'
            ])
        ;

        $menu
            ->addChild('googleplus', ['uri' => $this->generateSocialUrl(self::URL_PATTERN_GOOGLEPLUS)])
            ->setLabel('')
            ->setAttribute('icon', 'fa fa-google-plus-square')
            ->setLinkAttributes([
                'onclick' => "javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=350,width=480');return false;",
                'target' => '_blank',
                'title' => $this->translator->trans('frontend.menu.share', ['%site%' => 'Google+']),
                'data-toggle' => 'tooltip',
                'data-placement' => 'bottom'
            ])
        ;

        $menu
            ->addChild('frontend.menu.contact_us', ['uri' => '#'])
            ->setAttribute('icon', 'fa fa-envelope-o')
            ->setLinkAttribute('class', 'contact-us')
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