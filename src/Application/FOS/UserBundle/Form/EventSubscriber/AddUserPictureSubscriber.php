<?php

namespace Application\FOS\UserBundle\Form\EventSubscriber;

//use Application\Sonata\UserBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
//use Symfony\Component\Form\Extension\Core\Type\FileType;
//use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
//use Symfony\Component\HttpFoundation\File\File;
//use Symfony\Component\Validator\Constraints\Image;
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
        /** @var User $data */
        //$data    = $event->getData();
        $form    = $event->getForm();

        $form
            ->add('pictureFile', VichImageType::class, [
                'label' => false,
                'required' => false,
                'allow_delete' => true,
                'download_uri' => false,
                'image_uri' => true,
                'translation_domain' => 'FOSUserBundle',
            ])
//
//
//            ->add('picture', FileType::class, [
//                'label'              => 'form.picture',
//                'required'           => false,
//                //'data'               => $data !== null && $data->getPicture() !== null ? new File($data->getAbsolutePath()) : null,
//                'image_path_method'  => 'getAbsolutePath'
//                'translation_domain' => 'FOSUserBundle',
//                'constraints'        => [
//                    new Image([
//                        'maxWidth' => 180,
//                        'maxHeight' => 180,
//                        'maxSize'   => '800k'
//                    ])
//                ]
//            ])
        ;
    }
}
