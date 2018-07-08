<?php

namespace AppBundle\Form\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddUserNameSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'onPreSetData'
        ];
    }

    public function onPreSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        $form
            ->add('name', TextType::class, [
                'label' => 'frontend.guestbook.name',
                'required' => true,
                'data' => $data->getUser() !== null ? $data->getUser()->getUsername() : null
            ])
        ;
    }
}
