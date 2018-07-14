<?php

namespace Application\FOS\UserBundle\Form\Type;

use AppBundle\Validator\Constraints\FullName;
use Application\FOS\UserBundle\Form\EventSubscriber\AddUserPictureSubscriber;
use Application\Sonata\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
        $this->class     = $class;
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
            ->add('gender', ChoiceType::class, [
                'label' => 'form.gender',
                'required' => false,
                'expanded' => true,
                'choices' => [
                    'form.gender.male' => User::GENDER_MALE,
                    'form.gender.female' => User::GENDER_FEMALE
                ],
                'translation_domain' => 'FOSUserBundle',
                'choice_translation_domain' => 'FOSUserBundle'
            ])
            ->add('changeReferences', CheckboxType::class, [
                'label'    => 'form.change_references',
                'mapped'   => false,
                'required' => false,
                'translation_domain' => 'FOSUserBundle',                
            ])
            ->addEventSubscriber(new AddUserPictureSubscriber())
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
