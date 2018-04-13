<?php
namespace AppBundle\Service;

use Knp\Menu\FactoryInterface;
use AppBundle\Templating\Helper\SocialUrlHelper;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MenuBuilder
{
    /** @var FactoryInterface */
    private $factory;
    /** @var SocialUrlHelper */
    private $socialUrlHelper;
    /** @var TranslatorInterface */
    private $translator;
    /** @var AuthorizationCheckerInterface */
    private $authorizationChecker;
    /** @var TokenStorageInterface */	
    private $tokenStorage;

    /**
     * MenuBuilder constructor.
     *
     * @param FactoryInterface $factory
     * @param SocialUrlHelper $socialUrlHelper
     * @param TranslatorInterface $translator
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
	    FactoryInterface $factory,
	    SocialUrlHelper $socialUrlHelper,
	    TranslatorInterface $translator,
	    AuthorizationCheckerInterface $authorizationChecker,
	    TokenStorageInterface $tokenStorage
    ) {
        $this->factory    	    = $factory;
        $this->socialUrlHelper 	    = $socialUrlHelper;
        $this->translator 	    = $translator;
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage 	    = $tokenStorage;	    
    }

    public function createMainMenu()
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'navbar-nav');

        $menu
            ->addChild('frontend.menu.home', ['route' => 'homepage'])
            ->setAttributes(['icon' => 'home', 'class' => 'nav-item'])
            ->setLinkAttribute('class', 'nav-link')
            ->setExtra('translation_domain', 'AppBundle')
        ;

        $menu
            ->addChild('frontend.menu.confirm_attendance', ['route' => 'guest'])
            ->setAttributes(['icon' => 'assignment_turned_in', 'class' => 'nav-item'])
            ->setLinkAttribute('class', 'nav-link')
            ->setExtra('translation_domain', 'AppBundle')
        ;

        $menu
            ->addChild('frontend.menu.guestbook', ['route' => 'guestbook'])
            ->setAttributes(['icon' => 'import_contacts', 'class' => 'nav-item'])
            ->setLinkAttribute('class', 'nav-link')
            ->setExtra('translation_domain', 'AppBundle')
        ;

        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $username = $this->tokenStorage->getToken()->getUser()->getUsername();
            $menu->addChild($username)
                ->setAttributes(['dropdown' => true, 'class' => 'nav-item', 'icon' => 'account_circle'])
                ->setLinkAttributes([
                    'class' =>'nav-link',
                    'data-toggle' => 'dropdown',
                    'aria-expanded' => 'false'
                ])
//              ->setExtra('translation_domain', 'AppBundle')
        ;
//        $menu[$username]
//            ->addChild('Profile', array('route' => 'fos_user_profile_edit'))
//            ->setAttribute('divider_append', true)
//            ->setLinkAttribute('class', 'dropdown-item')
////            ->setExtra('translation_domain', 'AppBundle')
//        ;
            $menu[$username]
                ->addChild('Logout', array('route' => 'fos_user_security_logout'))
                ->setLinkAttribute('class', 'dropdown-item')
//              ->setExtra('translation_domain', 'AppBundle')
            ;
        } else {
            $menu
                ->addChild('security.login.submit', ['route' => 'fos_user_security_login'])
                ->setAttributes(['icon' => 'fingerprint', 'class' => 'nav-item'])
                ->setLinkAttribute('class', 'nav-link')
                ->setExtra('translation_domain', 'FOSUserBundle')
            ;
            $menu
                ->addChild('registration.submit', ['route' => 'fos_user_registration_register'])
                ->setAttributes(['icon' => 'person_add', 'class' => 'nav-item'])
                ->setLinkAttribute('class', 'nav-link')
                ->setExtra('translation_domain', 'FOSUserBundle')
            ;
		
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
	    }
	    
        $menu
            ->addChild('frontend.menu.contact_us', ['uri' => '#'])
            ->setAttributes(['icon' => 'email', 'class' => 'nav-item'])
            ->setLinkAttribute('class', 'nav-link contact-us')
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

    public function createSocialMenu()
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'navbar-nav');
        $menu
            ->addChild('facebook', ['uri' => $this->socialUrlHelper->generateFacebookUrl()])
            ->setLabel('Facebook')
            ->setLabelAttribute('class', 'd-lg-none d-xl-none')
            ->setAttributes(['icon' => 'fa fa-facebook-square', 'class' => 'nav-item'])
            ->setLinkAttributes([
                'class' => 'nav-link',
                'onclick' => "javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;",
                'target' => '_blank',
                'title' => $this->translator->trans('frontend.menu.share', ['%site%' => 'Facebook'], 'AppBundle'),
                'data-toggle' => 'tooltip'
            ])
            ->setExtra('translation_domain', 'AppBundle')
        ;
        $menu
            ->addChild('twitter', ['uri' => $this->socialUrlHelper->generateTwitterUrl()])
            ->setLabel('Twitter')
            ->setLabelAttribute('class', 'd-lg-none d-xl-none')
            ->setAttributes(['icon' => 'fa fa-twitter', 'class' => 'nav-item'])
            ->setLinkAttributes([
                'class' => 'nav-link',
                'onclick' => "javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;",
                'target' => '_blank',
                'title' => $this->translator->trans('frontend.menu.share', ['%site%' => 'Twitter'], 'AppBundle'),
                'data-toggle' => 'tooltip'
            ])
            ->setExtra('translation_domain', 'AppBundle')
        ;
        $menu
            ->addChild('googleplus', ['uri' => $this->socialUrlHelper->generateGoogleplusUrl()])
            ->setLabel('Google+')
            ->setLabelAttribute('class', 'd-lg-none d-xl-none')
            ->setAttributes(['icon' => 'fa fa-google-plus-square', 'class' => 'nav-item'])
            ->setLinkAttributes([
                'class' => 'nav-link',
                'onclick' => "javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=350,width=480');return false;",
                'target' => '_blank',
                'title' => $this->translator->trans('frontend.menu.share', ['%site%' => 'Google+'], 'AppBundle'),
                'data-toggle' => 'tooltip'
            ])
            ->setExtra('translation_domain', 'AppBundle')
        ;

        return $menu;
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
