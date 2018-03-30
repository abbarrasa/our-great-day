<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnquiryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'frontend.enquiry.name',
                'required' => true
            ])
            ->add('email', EmailType::class, [
                'label' => 'frontend.enquiry.email',
                'required' => true
            ])
            ->add('content', TextareaType::class, [
                'label' => 'frontend.enquiry.content',
                'required' => true
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'frontend.enquiry.send'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(array(
                'data_class' => 'AdminBundle\Entity\Enquiry',
            ))
        ;
    }
}
