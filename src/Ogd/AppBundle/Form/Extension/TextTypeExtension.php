<?php

namespace AppBundle\Form\Extension;


use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextTypeExtension extends AbstractTypeExtension
{
    public function getExtendedType()
    {
        return TextType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined(['prepend', 'append'])
            ->setAllowedTypes('prepend', ['string'])
            ->setAllowedTypes('append', ['string'])
        ;
    }
}


//# config/services.yaml
//services:
//# ...
//
//App\Form\Extension\ImageTypeExtension:
//tags:
//- { name: form.type_extension, extended_type: Symfony\Component\Form\Extension\Core\Type\FileType
