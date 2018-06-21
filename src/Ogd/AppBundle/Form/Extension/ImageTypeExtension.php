<?php

namespace AppBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageTypeExtension extends AbstractTypeExtension
{
    public function getExtendedType()
    {
        return FileType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // makes it legal for FileType fields to have an image_property option
        $resolver->setDefined(array('image_path_method'));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (isset($options['image_path_method'])) {
            // this will be whatever class/entity is bound to your form (e.g. Media)
            $parentData = $form->getParent()->getData();

            $imageUrl = null;
            if (null !== $parentData) {
                $method   = $options['image_path_method'];
                $imageUrl = $parentData->$method();
            }

            // sets an "image_url" variable that will be available when rendering this field
            $view->vars['image_url'] = $imageUrl;
        }
    }
}