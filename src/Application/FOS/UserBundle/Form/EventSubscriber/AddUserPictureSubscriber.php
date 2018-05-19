<?php

namespace Application\FOS\UserBundle\Form\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints\Image;

class AddUserPictureSubscriber implements EventSubscriberInterface
{
    /** @var string */
    private $directory;

    public function __construct($directory)
    {
        $this->directory = $directory;
    }

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'onPreSetData'
        ];
    }

    public function onPreSetData(FormEvent $event)
    {

        $data    = $event->getData();
        $form    = $event->getForm();

        $form
            ->add('picture', FileType::class, [
                'label'              => 'form.picture',
                'required'           => false,
                'data'               => $data !== null && $data->getPicture() !== null ? new File($this->directory.DIRECTORY_SEPARATOR.$data->getPicture()) : null,
                'translation_domain' => 'FOSUserBundle',
                'constraints'        => [
                    new Image([
                        'maxWidth' => 180,
                        'maxHeight' => 180,
                        'maxSize'   => '800k'
                    ])
                ]
            ])
        ;
    }
}
