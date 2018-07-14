<?php

namespace Application\FOS\UserBundle\EventListener;

use AdminBundle\Entity\AbstractComment;
use AdminBundle\Entity\Greeting;
use Application\Sonata\UserBundle\Entity\User;
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
        if ($form->has('changeReferences') && (bool)$form->get('changeReferences')->getData() === true) {
            $user = $form->getData();
            $this->changeAllUsernameReferences($user);
        }
        
        $url = $this->router->generate('fos_user_profile_edit');
        $event->setResponse(new RedirectResponse($url));
    }
    
    public function changeAllUsernameReferences(User $user)
    {
        $originalData = $this->em->getUnitOfWork()->getOriginalEntityData($user);
        if ($originalData['username'] !== $user->getUsername()) {
            $greetings = $this->em->getRepository(Greeting::class)->findBy(['user' => $user]);
            foreach($greetings as $greeting) {
                if ($greeting->getName() !== $user->getUsername()) {
                    $greeting->setName($user->getUsername());
                    $this->em->persist($greeting);
                }
            }

            $comments = $this->em->getRepository(AbstractComment::class)->findBy(['user' => $user]);
            foreach($comments as $comment) {
                if ($comment->getName() !== $user->getUsername()) {
                    $comment->setName($user->getUsername());
                    $this->em->persist($comment);
                }
            }

            $this->em->flush();
        }
    }
}