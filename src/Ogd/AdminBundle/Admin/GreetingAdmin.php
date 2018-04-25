<?php

namespace AdminBundle\Admin;

use AdminBundle\Entity\Greeting;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

class GreetingAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'greeting';
    
    /**
     * Fields to be shown on filter forms
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('createdAt')
            ->add('publishedAt')
            ->add('status')
        ;
    }
    
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('message', 'textarea', ['label' => 'Message'])
            ->add('status', 'choice', [
                'label' => 'Status',
                'choices' => Greeting::getStatusList(),
            ])
        ;
    }    

    /**
     * Fields to be shown on lists
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('createdAt')
            ->add('publishedAt')
            ->add('status', 'choice', [
                'editable' => true,
                'choices' => Greeting::getStatusList(),
            ])
        ;
    }

    /**
     * Set publishedAt value on updates
     * @param object $greeting
     */
    public function preUpdate($greeting)
    {
        if ($greeting->getStatus() == Greeting::STATUS_APPROVED) {
            if ($greeting->getPublishedAt() === null) {
                $greeting->setPublishedAt(new \DateTime());
            }
        } else {
            $greeting->setPublishedAt(null);
        }
    }

    public function configureActionButtons($action, $object = null)
    {
        $list = parent::configureActionButtons($action, $object);
        unset($list['create']);

        return $list;
    }

    public function getDashboardActions()
    {
        $actions = parent::getDashboardActions();
        unset($actions['create']);

        return $actions;
    }
}
