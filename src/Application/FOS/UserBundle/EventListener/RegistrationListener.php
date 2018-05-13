<?php

namespace Application\FOS\UserBundle\EventListener;

use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Mailer\MailerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegistrationListener implements EventSubscriberInterface
{
    /** @var MailerInterface */
    private $mailer;

    /** @var UrlGeneratorInterface */
    private $router;

    /** @var \Twig_Environment */
    private $twig;

    /**
     * RegistrationListener constructor.
     * @param MailerInterface $mailer
     * @param UrlGeneratorInterface $router
     * @param \Twig_Environment $twig
     */
    public function __construct(MailerInterface $mailer, UrlGeneratorInterface $router, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->twig   = $twig;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
            FOSUserEvents::REGISTRATION_FAILURE => 'onRegistrationFailure',
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function onRegistrationSuccess(FormEvent $event)
    {
        $request = $event->getRequest();
        /** @var $user \FOS\UserBundle\Model\UserInterface */
        $user = $event->getForm()->getData();
        $user->setEnabled(true);
        //Send a welcome email to user
        $this->mailer->sendConfirmationEmailMessage($user);

        $url = $request->request->has('_target_path') ?
            $this->router->generate($request->request->get('_target_path')) :
            $this->router->generate('fos_user_profile_edit')
        ;
        $event->setResponse(new RedirectResponse($url));
    }


    public function onRegistrationFailure(FormEvent $event)
    {
        $request = $event->getRequest();
        $route   = $request->get('_route');
        if ($route == 'fos_user_landing') {
            $form	  = $event->getForm();
            $response = new Response();
            $response->setContent($this->twig->render('@FOSUser/Landing/register_content.html.twig', [
                'form' => $form->createView()
            ]));

            $event->setResponse($response);
        }
    }
}
