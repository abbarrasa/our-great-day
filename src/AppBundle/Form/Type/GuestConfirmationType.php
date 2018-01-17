<?php

namespace AppBundle\Form\Type;

use AppBundle\Validator\Constraints\Email;
use AppBundle\Validator\Constraints\GuestExists;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class GuestConfirmationType extends AbstractType
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $route = $this->requestStack->getCurrentRequest()->get('_route');
        if ($route === 'guest') {
            $this->buildGuestForm($builder, $options);
        } else if ($route === 'guest_confirm') {
            $this->buildGuestConfirmForm($builder, $options);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        if ('guest' === $this->requestStack->getCurrentRequest()->get('_route')) {
            $resolver->setDefaults([
                'constraints' => [
                    new GuestExists()
                ],
            ]);
        }

        if ('guest_confirm' === $this->requestStack->getCurrentRequest()->get('_route')) {
            $resolver
                ->setDefaults(array(
                    'data_class' => 'AppBundle\Entity\Guest',
                ))
            ;
        }
    }

    private function buildGuestForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'First name',
                'required' => true,
                'label_attr' => ['class' => 'control-label'],
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Last name',
                'required' => true,
                'label_attr' => ['class' => 'control-label'],
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email (optional)',
                'required' => false,
                'label_attr' => ['class' => 'control-label'],
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Email()
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Search me',
                'attr' => ['class' => 'btn btn-primary']
            ])
        ;

        $builder
            ->get('email')
            ->addModelTransformer(new CallbackTransformer(
                // set transformation
                function ($email) {
                    if ($email === null) {
                        return null;
                    }

                    return mb_strtolower($email);
                },
                // submit transformation
                function ($email) {
                    if ($email === null) {
                        return null;
                    }

                    return mb_strtolower($email);
                }
            ))
        ;
    }

    private function buildGuestConfirmForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildGuestForm($builder, $options);
        $builder
            ->add('guests', ChoiceType::class, [
                'label' => 'Number of guests',
                'choices' => array_combine(range(1, 10), range(1, 10)),
                'required' => true,
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'class' => 'selectpicker',
                    'data-style' => 'select-with-transition',
                    'data-size' => '7'
                ]
            ])
            ->add('childs', ChoiceType::class, [
                'label' => 'Number of childs',
                'choices' => array_combine(range(0, 10), range(0, 10)),
                'required' => true,
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'class' => 'selectpicker',
                    'data-style' => 'select-with-transition',
                    'data-size' => '7'
                ]
            ])
            ->add('vegans', ChoiceType::class, [
                'label' => 'Number of vegans',
                'choices' => array_combine(range(0, 10), range(0, 10)),
                'required' => true,
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'class' => 'selectpicker',
                    'data-style' => 'select-with-transition',
                    'data-size' => '7'
                ]
            ])
            ->add('attending', ChoiceType::class, [
                'label' => "I'm attending",
                'choices' => ['Yes' => true, 'No' => false],
                'required' => true,
                'expanded' => true,
                'label_attr' => ['class' => 'control-label'],
                'attr' => ['class' => 'form-control']
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Confirm",
                'attr' => ['class' => 'btn btn-primary']
            ])
        ;
    }
}