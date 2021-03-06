<?php

namespace AdminBundle\Admin;

use AppBundle\Form\Type\GoogleMapType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class LocationAdmin extends AbstractAdmin
{
    /**
     * Fields to be shown on create/edit forms
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', 'text', array('label' => 'Title'))
            ->add('description', 'textarea', array('label' => 'Description'))
            ->add('order', 'text', array('label' => 'Order'))
            ->add('location', GoogleMapType::class, array(
                'label' => 'Location',
                'addr_name' => 'Address'
            ))
        ;
    }

    /**
     * Fields to be shown on filter forms
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
//        $datagridMapper
//            ->add('name')
//            ->add('surname')
//        ;
    }

    /**
     * Fields to be shown on lists
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('order')
            ->add('title')
            ->add('description')
        ;
    }

    public function getFormTheme()
    {
        // app/Resources/views/form/fields.html.twig
        return array_merge(
            parent::getFormTheme(),
            array(':form:fields.html.twig')
        );
    }
}