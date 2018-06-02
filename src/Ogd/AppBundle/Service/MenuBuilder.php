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
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link')
	        ->setExtra('icon', 'home')
            ->setExtra('translation_domain', 'AppBundle')
        ;

        $menu
            ->addChild('frontend.menu.confirm_attendance', ['route' => 'guest'])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link')
	        ->setExtra('icon', 'assignment_turned_in')
            ->setExtra('translation_domain', 'AppBundle')
        ;

        $menu
            ->addChild('frontend.menu.guestbook', ['route' => 'guestbook'])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link')
	        ->setExtra('icon', 'import_contacts')
            ->setExtra('translation_domain', 'AppBundle')
        ;

        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $username = $this->tokenStorage->getToken()->getUser()->getUsername();
            $menu->addChild($username)
                ->setAttributes(['dropdown' => true, 'class' => 'nav-item'])
                ->setLinkAttributes([
                    'class' =>'nav-link',
                    'data-toggle' => 'dropdown',
                    'aria-expanded' => 'false',
		            'aria-haspopup' => 'true'
                ])
	    	    ->setExtra('icon', 'account_circle')
            ;
            $menu[$username]
                ->addChild('layout.profile', array('route' => 'fos_user_profile_edit'))
                ->setAttribute('divider_append', true)
                ->setLinkAttribute('class', 'dropdown-item')
                ->setExtra('translation_domain', 'FOSUserBundle')
            ;
            $menu[$username]
                ->addChild('layout.logout', array('route' => 'fos_user_security_logout'))
                ->setLinkAttribute('class', 'dropdown-item')
                ->setExtra('translation_domain', 'FOSUserBundle')
            ;
        } else {
            $menu
                ->addChild('layout.login', ['route' => 'fos_user_security_login'])
                ->setAttribute('class', 'nav-item')
                ->setLinkAttribute('class', 'nav-link')
                ->setExtra('icon', 'fingerprint')
                ->setExtra('translation_domain', 'FOSUserBundle');
            $menu
                ->addChild('layout.register', ['route' => 'fos_user_registration_register'])
                ->setAttribute('class', 'nav-item')
                ->setLinkAttribute('class', 'nav-link')
                ->setExtra('icon', 'person_add')
                ->setExtra('translation_domain', 'FOSUserBundle');
            $menu
                ->addChild('frontend.menu.contact_us', ['uri' => '#'])
                ->setAttribute('class', 'nav-item')
                ->setLinkAttribute('class', 'nav-link contact-us')
                ->setExtra('icon', 'email')
                ->setExtra('translation_domain', 'AppBundle')
            ;
        }
	
        return $menu;
    }

    public function createSocialMenu()
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'navbar-nav');
        $menu
            ->addChild('facebook', ['uri' => $this->socialUrlHelper->generateFacebookUrl()])
            ->setLabel('Facebook')
            ->setLabelAttribute('class', 'd-lg-none d-xl-none')
            ->setAttribute('class', 'nav-item')
            ->setLinkAttributes([
                'class' => 'nav-link',
                'onclick' => "javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;",
                'target' => '_blank',
                'title' => $this->translator->trans('frontend.menu.share', ['%site%' => 'Facebook'], 'AppBundle'),
                'data-toggle' => 'tooltip'
            ])
	    ->setExtra('icon', 'fa fa-facebook-square')		
            ->setExtra('translation_domain', 'AppBundle')
        ;
        $menu
            ->addChild('twitter', ['uri' => $this->socialUrlHelper->generateTwitterUrl()])
            ->setLabel('Twitter')
            ->setLabelAttribute('class', 'd-lg-none d-xl-none')
            ->setAttribute('class', 'nav-item')
            ->setLinkAttributes([
                'class' => 'nav-link',
                'onclick' => "javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;",
                'target' => '_blank',
                'title' => $this->translator->trans('frontend.menu.share', ['%site%' => 'Twitter'], 'AppBundle'),
                'data-toggle' => 'tooltip'
            ])
	    ->setExtra('icon', 'fa fa-twitter-square')		
            ->setExtra('translation_domain', 'AppBundle')
        ;
        $menu
            ->addChild('googleplus', ['uri' => $this->socialUrlHelper->generateGoogleplusUrl()])
            ->setLabel('Google+')
            ->setLabelAttribute('class', 'd-lg-none d-xl-none')
            ->setAttribute('class', 'nav-item')
            ->setLinkAttributes([
                'class' => 'nav-link',
                'onclick' => "javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=350,width=480');return false;",
                'target' => '_blank',
                'title' => $this->translator->trans('frontend.menu.share', ['%site%' => 'Google+'], 'AppBundle'),
                'data-toggle' => 'tooltip'
            ])
	    ->setExtra('icon', 'fa fa-google-plus-official')		
            ->setExtra('translation_domain', 'AppBundle')
        ;

        return $menu;
    }
}
