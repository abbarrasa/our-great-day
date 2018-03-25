<?php

namespace AppBundle\Form\Type;

use AppBundle\Form\EventSubscriber\AddUserNameSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GuestbookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comment', TextareaType::class, [
                'label' => 'frontend.guestbook.comment',
                'required' => true
            ])
            ->add('submit', SubmitType::class, ['label' => 'frontend.guestbook.send'])
            ->addEventSubscriber(new AddUserNameSubscriber())
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(array(
                'data_class' => 'AppBundle\Entity\Guestbook'
            ))
        ;
    }
}
