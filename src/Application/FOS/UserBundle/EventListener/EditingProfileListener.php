<?php

namespace Application\FOS\UserBundle\EventListener;

use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;

class EditingProfileListener implements EventSubscriberInterface
{
    /** @var UrlGeneratorInterface */
    private $router;
    /** @var EntityManagerInterface */
    private $em;
    
    /**
     * ResettingListener constructor.
     *
     * @param UrlGeneratorInterface $router
     * @param EntityManagerInterface $em
     */
    public function __construct(UrlGeneratorInterface $router, EntityManagerInterface $em)
    {
        $this->router = $router;
        $this->em     = $em;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::PROFILE_EDIT_SUCCESS => 'onProfileEditSuccess',
            FOSUserEvents::CHANGE_PASSWORD_SUCCESS => 'onProfileEditSuccess'
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function onProfileEditSuccess(FormEvent $event)
    {
        $form = $event->getForm();
        if ($form->has('changeReferences') && $form->get('changeReferences')->getData() === true) {
            $user = $form->getData();
            $this->changeAllUsernameReferences();
        }
        
        $url = $this->router->generate('fos_user_profile_edit');
        $event->setResponse(new RedirectResponse($url));
    }
    
    public function changeAllUsernameReferences($user)
    {
        $uow     = $this->em->getUnitOfWork();
        $changes = $uow->getEntityChangeSet($user);
        if (array_key_exists('username', $changes)) {
            $comments = $this->em->getRepository(AbstractComment::class)->findBy(['user' => $user]);            
            foreach($comments as $comment) {
                if ($comment->getName() !== $changes['username'][1]) {
                    $comment->setName($changes['username'][1]);
                    $this->em->persist($comment);
                }
            }
        }
    }
}
