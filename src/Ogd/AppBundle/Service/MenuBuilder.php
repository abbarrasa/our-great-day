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
    /** @var TranslatorInterface */
    private $translator;	
	
    /**
     * MenuBuilder constructor.
     *
     * @param FactoryInterface $factory
     * @param TranslatorInterface $translator
     */
    public function __construct(FactoryInterface $factory, TranslatorInterface $translator)
    {
        $this->factory    = $factory;
        $this->translator = $translator;
    }

    public function createMainMenu(AuthorizationCheckerInterface $authorizationChecker, TokenStorageInterface $tokenStorage)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'navbar-nav');

        $menu
            ->addChild('frontend.menu.home', ['route' => 'homepage'])
            ->setLabelAttribute('class', 'd-lg-none d-xl-none')
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link')
	    ->setExtra('icon', 'home')
            ->setExtra('translation_domain', 'AppBundle')
        ;

        $menu
            ->addChild('frontend.menu.news', ['route' => 'post_list'])
            ->setLabelAttribute('class', 'd-lg-none d-xl-none')
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link')
            ->setExtra('icon', 'event_note')
            ->setExtra('translation_domain', 'AppBundle')
        ;

        $menu
            ->addChild('frontend.menu.confirm_attendance', ['route' => 'guest'])
            ->setLabelAttribute('class', 'd-lg-none d-xl-none')
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link')
	        ->setExtra('icon', 'assignment_turned_in')
            ->setExtra('translation_domain', 'AppBundle')
        ;

        $menu
            ->addChild('frontend.menu.guestbook', ['route' => 'guestbook'])
            ->setLabelAttribute('class', 'd-lg-none d-xl-none')
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link')
	    ->setExtra('icon', 'import_contacts')
            ->setExtra('translation_domain', 'AppBundle')
        ;

        if ($authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $username = $tokenStorage->getToken()->getUser()->getUsername();
            $menu->addChild($username)
                ->setLabelAttribute('class', 'd-lg-none d-xl-none')
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
                ->setLabelAttribute('class', 'd-lg-none d-xl-none')
                ->setAttribute('class', 'nav-item')
                ->setLinkAttribute('class', 'nav-link')
                ->setExtra('icon', 'fingerprint')
                ->setExtra('translation_domain', 'FOSUserBundle');
            $menu
                ->addChild('layout.register', ['route' => 'fos_user_registration_register'])
                ->setLabelAttribute('class', 'd-lg-none d-xl-none')
                ->setAttribute('class', 'nav-item')
                ->setLinkAttribute('class', 'nav-link')
                ->setExtra('icon', 'person_add')
                ->setExtra('translation_domain', 'FOSUserBundle');
        }

        $menu
            ->addChild('frontend.menu.contact_us', ['uri' => '#'])
            ->setLabelAttribute('class', 'd-lg-none d-xl-none')
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link contact-us')
            ->setExtra('icon', 'email')
            ->setExtra('translation_domain', 'AppBundle')
        ;

        return $menu;
    }

    public function createSocialMenu(SocialUrlHelper $socialUrlHelper)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'navbar-nav');
        $menu
            ->addChild('facebook', ['uri' => $socialUrlHelper->generateFacebookUrl()])
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
            ->addChild('twitter', ['uri' => $socialUrlHelper->generateTwitterUrl()])
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
            ->addChild('googleplus', ['uri' => $socialUrlHelper->generateGoogleplusUrl()])
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
