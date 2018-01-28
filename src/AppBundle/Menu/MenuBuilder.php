<?php
namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MenuBuilder
{
    /** @var FactoryInterface */
    private $factory;

    /** @var RequestStack */
    private $requestStack;

    /**
     * MenuBuilder constructor.
     * @param FactoryInterface $factory
     * @param RequestStack $requestStack
     */
    public function __construct(FactoryInterface $factory, RequestStack $requestStack)
    {
        $this->factory      = $factory;
        $this->requestStack = $requestStack;
    }

    public function createMainMenu()
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'navbar-nav ml-auto');

        $menu
            ->addChild('Home', ['route' => 'homepage'])
            ->setAttribute('icon', 'home')
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link')
            ->setExtra('translation_domain', 'AppBundle')
        ;

        $menu
            ->addChild('Contact us', ['uri' => $this->requestStack->getCurrentRequest()->getBasePath() . '#contact-us'])
            ->setAttribute('icon', 'contact_mail')
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link')
            ->setExtra('translation_domain', 'AppBundle')
        ;

        $menu
            ->addChild('Confirm attendance', ['route' => 'guest'])
            ->setAttribute('icon', 'assignment_turned_in')
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link')
            ->setExtra('translation_domain', 'AppBundle')
        ;

        $menu
            ->addChild('Guestbook', ['route' => 'guestbook'])
            ->setAttribute('icon', 'import_contacts')
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link')
            ->setExtra('translation_domain', 'AppBundle')
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
