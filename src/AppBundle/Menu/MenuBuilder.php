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

    public function createMainMenu()
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'navbar-nav ml-auto');

        $menu
            ->addChild('Home', ['route' => 'homepage'])
            ->setAttributes(['icon' => 'home', 'class' => 'nav-item'])
            ->setLinkAttribute('class', 'nav-link')
        ;

        $menu
            ->addChild('Confirm attendance', ['route' => 'guest'])
            ->setAttributes(['icon' => 'assignment_turned_in', 'class' => 'nav-item'])
            ->setLinkAttribute('class', 'nav-link')
        ;

        $menu
            ->addChild('Guestbook', ['route' => 'guestbook'])
            ->setAttributes(['icon' => 'import_contacts', 'class' => 'nav-item')
            ->setLinkAttribute('class', 'nav-link')
        ;

//        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
//            $menu
//                ->addChild('Profile', array('route' => 'user_profile'))
//                ->setAttributes(array(
//                    'rel' => 'tooltip',
//                    'title' => '<b>Material Kit</b> was Designed & Coded with care by the staff from <b>Creative Tim</b>',
//                    'data-placement' => 'bottom',
//                    'data-html' => 'true',
//                    'icon' => 'face'
//                ))
//                ->setExtra('translation_domain', 'AppBundle')
//		    ;
//		    $menu
//			    ->addChild('Logout', array('route' => 'homepage'))
//			    ->setAttribute('icon', 'power settings new')
//			    ->setExtra('translation_domain', 'AppBundle')
//		    ;
//	    } else {
//		    $menu
//                ->addChild('Login', array('route' => 'homepage'))
//			    ->setAttribute('icon', 'exit to app')
//			    ->setExtra('translation_domain', 'AppBundle')
//		    ;
//		    $menu
//                ->addChild('Singup', array('route' => 'homepage'))
//			    ->setAttribute('icon', 'vpn key')
//			    ->setExtra('translation_domain', 'AppBundle')
//		    ;
//	    }
	
        return $menu;

	    //{{ knp_menu_render('main', {'template': ':menu:knp_menu.html.twig'})) }}
    }

    public function createSocialMenu()
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-left');
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

/*
public_backup function createQuery($context = 'list')
{
    $query = parent::createQuery($context);    
    $query->andWhere(
	$query->expr()->orX(
		$query->expr()->isNull($query->getRootAliases()[0] . '.my_field'),
	        $query->expr()->eq($query->getRootAliases()[0] . '.my_field', ':my_param')
	)
    );
    $query->setParameter('my_param', 'my_value');
    return $query;
}
*/


/*
 app.menu_builder:
        class: AppBundle\Menu\MenuBuilder
        arguments: ["@knp_menu.factory", "@security.authorization_checker"]
        tags:
            - { name: knp_menu.menu_builder, method: createMainMenu, alias: main } # The alias is what is used to retrieve the menu
*/
