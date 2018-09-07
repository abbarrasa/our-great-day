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
        if (isset($data['guest'])) {
            if (!isset($data['name']) && ($guest = $this->modelManager->find(Guest::class, $data['guest'])) !== null) {
                $data['name'] = $guest->getFirstname() . ' ' . $guest->getLastname();
            }
        }

        $event->setData($data);
    }
}
