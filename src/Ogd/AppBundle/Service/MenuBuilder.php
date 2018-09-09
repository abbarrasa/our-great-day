<?php
namespace AppBundle\Service;

use Knp\Menu\FactoryInterface;
use AppBundle\Templating\Helper\SocialUrlHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class MenuBuilder
 * @package AppBundle\Service
 */
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

    public function createMainMenu(
        RequestStack $requestStack,
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $tokenStorage
    ) {
        $request = $requestStack->getCurrentRequest();
        $menu    = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'navbar-nav');

        $menu
            ->addChild('frontend.menu.home', ['route' => 'homepage'])
            ->setLabelAttribute(
                'class', $this->getClassIfRoute('homepage', $request, 'nav-item-label show', 'nav-item-label')
            )		
            ->setAttribute('class', $this->getClassIfRoute('homepage', $request, 'nav-item current', 'nav-item'))
            ->setLinkAttribute('class', 'nav-link')
	        ->setExtra('icon', 'home')
            ->setExtra('translation_domain', 'AppBundle')
        ;

        $menu
            ->addChild('frontend.menu.news', ['route' => 'post_list'])
            ->setLabelAttribute(
                'class', $this->getClassIfRoute(['post_list', 'post'], $request, 'nav-item-label show', 'nav-item-label')
            )				
            ->setAttribute(
                'class', $this->getClassIfRoute(
                    ['post_list', 'post'], $request, 'nav-item current', 'nav-item'
                )
            )
            ->setLinkAttribute('class', 'nav-link')
            ->setExtra('icon', 'event_note')
            ->setExtra('translation_domain', 'AppBundle')
        ;

        $menu
            ->addChild('frontend.menu.confirm_attendance', ['route' => 'guest'])
            ->setLabelAttribute(
                'class', $this->getClassIfRoute(['guest', 'guest_confirm', 'guest_set_user', 'fos_user_landing'], $request, 'nav-item-label show', 'nav-item-label')
            )			
            ->setAttribute(
                'class', $this->getClassIfRoute(
                    ['guest', 'guest_confirm', 'guest_set_user', 'fos_user_landing'], $request, 'nav-item current', 'nav-item'
                )
            )
            ->setLinkAttribute('class', 'nav-link')
	        ->setExtra('icon', 'assignment_turned_in')
            ->setExtra('translation_domain', 'AppBundle')
        ;
	    
        $menu
            ->addChild('Banquete', ['route' => 'tables'])
            ->setLabelAttribute(
                'class', $this->getClassIfRoute('tables', $request, 'nav-item-label show', 'nav-item-label')
            )			
            ->setAttribute(
                'class', $this->getClassIfRoute('tables', $request, 'nav-item current', 'nav-item')
            )
            ->setLinkAttribute('class', 'nav-link')
	    ->setExtra('icon', 'restaurant')
            ->setExtra('translation_domain', 'AppBundle')
        ;
	    
        $menu
            ->addChild('frontend.menu.guestbook', ['route' => 'guestbook'])
            ->setLabelAttribute(
                'class', $this->getClassIfRoute('guestbook', $request, 'nav-item-label show', 'nav-item-label')
            )				
            ->setAttribute('class', $this->getClassIfRoute('guestbook', $request, 'nav-item current', 'nav-item'))
            ->setLinkAttribute('class', 'nav-link')
	        ->setExtra('icon', 'import_contacts')
            ->setExtra('translation_domain', 'AppBundle')
        ;

        if ($authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $username = $tokenStorage->getToken()->getUser()->getUsername();
            $menu->addChild($username)
		->setLabelAttribute(
			'class', $this->getClassIfRoute('fos_user_profile_edit', $request, 'nav-item-label show', 'nav-item-label')
		    )		    
                ->setAttributes(['dropdown' => true, 'class' => $this->getClassIfRoute(
                    'fos_user_profile_edit', $request, 'nav-item current', 'nav-item'
                            )
                ])
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
		    ->setLabelAttribute(
			'class', $this->getClassIfRoute([
                        	'fos_user_security_login', 'fos_user_resetting_request', 'fos_user_resetting_check_email', 'fos_user_resetting_reset'
			], $request, 'nav-item-label show', 'nav-item-label')
		    )		    		    
                ->setAttribute(
                    'class', $this->getClassIfRoute([
                    'fos_user_security_login', 'fos_user_resetting_request', 'fos_user_resetting_check_email', 'fos_user_resetting_reset'],
                    $request,
                    'nav-item current',
                    'nav-item'
                    )
                )
                ->setLinkAttribute('class', 'nav-link')
                ->setExtra('icon', 'fingerprint')
                ->setExtra('translation_domain', 'FOSUserBundle');
            $menu
                ->addChild('layout.register', ['route' => 'fos_user_registration_register'])
		    ->setLabelAttribute(
			'class', $this->getClassIfRoute('fos_user_registration_register', $request, 'nav-item-label show', 'nav-item-label')
		    )		    		    
                ->setAttribute(
                    'class', $this->getClassIfRoute('fos_user_registration_register', $request, 'nav-item current', 'nav-item')
                )
                ->setLinkAttribute('class', 'nav-link')
                ->setExtra('icon', 'person_add')
                ->setExtra('translation_domain', 'FOSUserBundle');
        }

        $menu
            ->addChild('frontend.menu.contact_us', ['uri' => '#'])
            ->setLabelAttribute('class', 'nav-item-label')
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
            ->setLabelAttribute('class', 'nav-item-label')		
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
            ->setLabelAttribute('class', 'nav-item-label')		
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
            ->setLabelAttribute('class', 'nav-item-label')		
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

    private function getClassIfRoute($routeName, Request $request, $class, $defaultClass = '')
    {
        if (is_array($routeName)) {
            if (in_array($request->get('_route'), $routeName)) {
                return $class;
            }

            return $defaultClass;
        }

        if ($routeName == $request->get('_route')) {
            return $class;
        }

        return $defaultClass;
    }
}
