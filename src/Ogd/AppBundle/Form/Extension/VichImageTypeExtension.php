<?php

namespace AppBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Vich\UploaderBundle\Storage\StorageInterface;

class VichImageTypeExtension extends AbstractTypeExtension
{
    /**
     * @var StorageInterface
     */
    protected $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return VichImageType::class;
    }

    /**
     * {@inheritdoc}
     */    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'delete_label' => 'form.label.delete',
                'select_label' => 'form.label.select',
                'change_label' => 'form.label.change'
        ]);
    }    

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if ($options['image_uri']) {
            $view->vars['delete_label'] = $options['delete_label'];            
            $view->vars['select_label'] = $options['select_label'];
            $view->vars['change_label'] = $options['change_label'];
            if (!$options['imagine_pattern']) {
                $parentData = $form->getParent()->getData();
                $imageUri   = null;

                if (null !== $parentData) {
                    $imageUri = $this->storage->resolvePath($parentData, $form->getName());
                }

                $view->vars['image_uri'] = $imageUri;
            }            
        } 
    }
}
