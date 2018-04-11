<?php

namespace Application\FOS\UserBundle\Form\Type;

use AppBundle\Validator\Constraints\FullName;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label'              => 'form.firstname',
                'required'           => false,
                'translation_domain' => 'FOSUserBundle',
                'constraints'        => new Length(['max' => 64])
            ])
            ->add('lastname', TextType::class, [
                'label'              => 'form.lastname',
                'required'           => false,
                'translation_domain' => 'FOSUserBundle',
                'constraints'        => new Length(['max' => 64])
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('constraints', [
                new FullName()
            ])
        ;
    }

    public function getParent()
    {
        return 'fos_user_registration';
    }

    public function getName()
    {
        return 'app_user_registration';
    }
}