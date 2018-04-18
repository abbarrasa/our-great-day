<?php

namespace Application\FOS\UserBundle\Form\Type;

use AppBundle\Validator\Constraints\FullName;
use AppBundle\Validator\Constraints\Password;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProfileType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $constraintsOptions = ['message' => 'fos_user.current_password.invalid'];

        if (!empty($options['validation_groups'])) {
            $constraintsOptions['groups'] = array(reset($options['validation_groups']));
        }

        $builder
            ->add('current_password', PasswordType::class, [
                'label' => 'form.current_password',
                'translation_domain' => 'FOSUserBundle',
                'required' => false,
                'mapped' => false,
                'constraints' => array(
                    new NotBlank(),
                    new UserPassword($constraintsOptions),
                    new Password()
                ),
                'attr' => array(
                    'autocomplete' => 'current-password',
                ),
            ])
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
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault(
                'constraints', [
                    new FullName()
                ]
            );
    }

    public function getParent()
    {
        return 'fos_user_profile';
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return $this->getName();
    }

    public function getName()
    {
        return 'app_user_profile';
    }
}
