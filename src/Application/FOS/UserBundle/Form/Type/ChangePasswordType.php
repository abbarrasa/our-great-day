<?php

namespace Application\FOS\UserBundle\Form\Type;

use AppBundle\Validator\Constraints\Password;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $constraintsOptions = array(
            'message' => 'fos_user.current_password.invalid',
        );

        if (!empty($options['validation_groups'])) {
            $constraintsOptions['groups'] = array(reset($options['validation_groups']));
        }

        $builder
            ->add('current_password', PasswordType::class, array(
                'label' => 'form.current_password',
                'translation_domain' => 'FOSUserBundle',
                'mapped' => false,
                'constraints' => array(
                    new NotBlank(),
                    new UserPassword($constraintsOptions),
                ),
                'attr' => array(
                    'autocomplete' => 'current-password',
                ),
            ))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'options' => array(
                    'translation_domain' => 'FOSUserBundle',
                    'attr' => array(
                        'autocomplete' => 'new-password',
                    ),
                ),
                'first_options' => array('label' => 'form.new_password'),
                'second_options' => array('label' => 'form.new_password_confirmation'),
                'invalid_message' => 'fos_user.password.mismatch',
                'constraints' => array(
                    new Password()
                )
            ))
        ;
    }

    public function getParent()
    {
        return 'fos_user_change_password';
    }

    public function getName()
    {
        return 'user_change_password';
    }

    public function getBlockPrefix()
    {
        return $this->getName();
    }
}
