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
        $resolver
            ->setDefault('translation_domain', 'AppBundle')
        ;

        if ('guest_confirm' === $this->requestStack->getCurrentRequest()->get('_route')) {
            $resolver
                ->setDefault('data_class', 'AdminBundle\Entity\Guest')
            ;
        }
    }

    private function buildGuestForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'frontend.guest.firstname',
                'required' => true,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'frontend.guest.lastname',
                'required' => true,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'frontend.guest.email',
                'required' => false,
                'constraints' => [
                    new Email()
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'frontend.guest.search_me'
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
                'label' => 'frontend.guest.guests',
                'choices' => array_combine(range(1, 10), range(1, 10)),
                'required' => true
            ])
            ->add('childs', ChoiceType::class, [
                'label' => 'frontend.guest.childs',
                'choices' => array_combine(range(0, 10), range(0, 10)),
                'required' => true
            ])
            ->add('vegans', ChoiceType::class, [
                'label' => 'frontend.guest.vegans',
                'choices' => array_combine(range(0, 10), range(0, 10)),
                'required' => true
            ])
            ->add('attending', ChoiceType::class, [
                'label' => "frontend.guest.attending",
                'choices' => ['yes' => true, 'no' => false],
                'required' => true,
                'expanded' => true,
                'choice_label' => function ($value, $key, $index) {
                    return 'frontend.choice.'.$key;
                },                
            ])
            ->add('submit', SubmitType::class, [
                'label' => "frontend.guest.confirm"
            ])
        ;
    }
}
