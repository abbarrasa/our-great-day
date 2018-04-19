<?php

namespace Application\FOS\UserBundle\Form\Type;

use AppBundle\Validator\Constraints\FullName;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ProfileType extends AbstractType
{
    /**
     * @var string
     */
    private $class;
    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }
        
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
            ->add('email', EmailType::class, array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
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
            ->setDefaults([
                'data_class' => $this->class,
                'csrf_token_id' => 'profile',                
                'constraints' => [
                    new FullName()
                ]
            ])
        ;
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
