<?php

namespace Application\FOS\UserBundle\EventListener;

use AdminBundle\Entity\Guest;
use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
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

    /** @var EntityManager  */
    private $em;

    /**
     * RegistrationListener constructor.
     * @param MailerInterface $mailer
     * @param UrlGeneratorInterface $router
     * @param \Twig_Environment $twig
     * @param EntityManager $em
     */
    public function __construct(MailerInterface $mailer, UrlGeneratorInterface $router, \Twig_Environment $twig, EntityManager $em)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->twig   = $twig;
        $this->em     = $em;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::REGISTRATION_INITIALIZE => 'onRegistrationInitialize',
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
            FOSUserEvents::REGISTRATION_FAILURE => 'onRegistrationFailure',
        ];
    }

    /**
     * @param GetResponseUserEvent $event
     */
    public function onRegistrationInitialize(GetResponseUserEvent $event)
    {
        $request = $event->getRequest();
        if (null !== $request->get('_forwarded') && $request->getSession()->has('ogd.guest.id')) {
            $id    = $request->getSession()->get('ogd.guest.id');
            if (!empty($id) &&
                ($guest = $this->em->getRepository(Guest::class)->find($id)) !== null
            ) {
                $user  = $event->getUser();
                
                $user->setEmail($guest->getEmail());
                $user->setFirstname($guest->getFirstname());
                $user->setLastname($guest->getLastname());
            }
        }         
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

    /**
     * @param FormEvent $event
     */                
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
