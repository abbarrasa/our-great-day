<?php

namespace Application\FOS\UserBundle\Form\Type;

use AppBundle\Validator\Constraints\FullName;
use AppBundle\Validator\Constraints\Password;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
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
                'constraints'        => [
                    new Length(['max' => 64])
                ]
            ])
            ->add('lastname', TextType::class, [
                'label'              => 'form.lastname',
                'required'           => false,
                'translation_domain' => 'FOSUserBundle',
                'constraints'        => [
                    new Length(['max' => 64])
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'options' => [
                    'translation_domain' => 'FOSUserBundle',
                    'attr' => ['autocomplete' => 'new-password'],
                ],
                'first_options' => ['label' => 'form.password'],
                'second_options' => ['label' => 'form.password_confirmation'],
                'invalid_message' => 'fos_user.password.mismatch',
                'constraints' => [
                    new Password()
                ]
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