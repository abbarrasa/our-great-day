<?php

namespace AdminBundle\Admin;

use AdminBundle\Form\Type\SeatType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Form\Type\CollectionType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class TableAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'table';

    /**
     * Fields to be shown on filter forms
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('subtitle')
            ->add('numberSeats')
//            ->add('seats')
            ->add('createdAt')
        ;
    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Table')
            ->add('title', 'text', ['label' => 'Title'])
            ->add('subtitle', 'text', ['label' => 'Subtitle'])
            ->add('pictureFile', VichImageType::class, [
                'label' => 'Picture',
                'required' => false,
                'allow_delete' => true,
                'download_uri' => false,
                'image_uri' => true
            ])
            ->add('numberSeats', 'choice', [
                'label' => 'Number of seats',
                'choices' => array_combine(range(0, 15), range(0, 15))
            ])
            ->end()
            ->with('Guests')
            ->add('seats', CollectionType::class, [
                'label' => false,
                'type_options' => [
                    // Prevents the "Delete" option from being displayed
                    'delete' => true,
//                    'delete_options' => [
//                        // You may otherwise choose to put the field but hide it
//                        'type'         => HiddenType::class,
//                        // In that case, you need to fill in the options as well
//                        'type_options' => [
//                            'mapped'   => false,
//                            'required' => false,
//                        ]
//                    ]
                ]
            ], [
//                'by_reference' => false, // Use this because of reasons
//                'allow_add' => true, // True if you want allow adding new entries to the collection
//                'allow_delete' => true, // True if you want to allow deleting entries
//                'prototype' => true, // True if you want to use a custom form type
//                'entry_type' => ScoreType::class, // Form type for the Entity that is being attached to the object

                'edit' => 'inline',
                'inline' => 'table',
                'sortable' => 'position'
            ])
            ->end()
        ;
    }

    /**
     * Fields to be shown on lists
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->addIdentifier('subtitle')
            ->add('pictureFile', null, ['template' => '@Admin/partials/list_image.html.twig'])
            ->add('numberSeats')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }
    
    public function prePersist($table)
    {
        $this->setSeatRelationships($table);
    }

    public function preUpdate($table)
    {
        $this->setSeatRelationships($table);
    }

    private function setSeatRelationships($table)
    {
        foreach ($table->getSeats() as $seat) {
            $seat->setTable($table);
        }
    }
}
