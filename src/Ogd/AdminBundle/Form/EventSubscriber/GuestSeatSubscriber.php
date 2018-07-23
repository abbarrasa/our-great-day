<?php

namespace AdminBundle\Form\EventSubscriber;


use AdminBundle\Entity\Guest;
use Sonata\AdminBundle\Model\ModelManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class GuestSeatSubscriber implements EventSubscriberInterface
{
    /** @var ModelManagerInterface */
    private $modelManager;

    public function __construct(ModelManagerInterface $modelManager)
    {
        $this->modelManager = $modelManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SUBMIT => 'onPreSubmit'
        ];
    }

    public function onPreSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        $firstname = isset($data['firstname']) ? $data['firstname'] : null;
        $lastname  = isset($data['lastname']) ? $data['lastname'] : null;
        if (isset($data['guest'])) {
            $guest = $this->modelManager->find(Guest::class, $data['guest']);
        }

        $form
            ->add('firstname', 'text', [
                'label' => 'Name',
                'data'  => isset($guest) ? $guest->getFirstname() : $firstname
            ])
            ->add('lastname', 'text', [
                'label' => 'Last name',
                'data'  => isset($guest) ? $guest->getLastname() : $lastname
            ])
        ;
    }
}