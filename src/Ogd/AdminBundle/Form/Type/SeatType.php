<?php

namespace AdminBundle\Form\Type;

use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class SeatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', 'text', ['label' => 'Name'])
            ->add('lastname', 'text', ['label' => 'Last name'])
            ->add('guest', ModelAutocompleteType::class, [
                'property' => 'id'
            ])
        ;
    }
}
