<?php

namespace Application\FOS\UserBundle\Form\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AddUserPictureSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'onPreSetData'
        ];
    }

    public function onPreSetData(FormEvent $event)
    {
        $form = $event->getForm();

        $form
            ->add('pictureFile', VichImageType::class, [
                'label' => false,
                'required' => false,
                'allow_delete' => true,
                'download_uri' => false,
                'image_uri' => true,
                'delete_label' => 'form.picture.remove',
                'select_label' => 'form.picture.select_image',
                'change_label' => 'form.picture.change',
                'translation_domain' => 'FOSUserBundle',
            ])
        ;
    }
}
