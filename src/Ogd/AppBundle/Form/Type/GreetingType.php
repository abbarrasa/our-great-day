<?php

namespace AppBundle\Form\Type;

use AppBundle\Form\EventSubscriber\AddUserNameSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GreetingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('message', TextareaType::class, [
                'label' => 'frontend.guestbook.message',
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
                'data_class' => 'AdminBundle\Entity\Greeting',
                'translation_domain' => 'AppBundle',
            ))
        ;
    }
}
