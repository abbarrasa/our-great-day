<?php

namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\UserBundle\Model\User;

class GuestAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'guest';
    
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('import');
    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('firstname', 'text', ['label' => 'Firstname'])
            ->add('lastname', 'text', ['label' => 'Lastname'])
            ->add('email', 'email', ['label' => 'Email', 'required' => false])
            ->add('gender', 'choice', [
                'label' => 'Gender',
                'choices' => [
                    'Select an option' => null,
                    'Male' => User::GENDER_MALE,
                    'Female' => User::GENDER_FEMALE
                ],
                'required' => false
            ])
            ->add('attending', 'choice', [
                'label' => 'Confirm Attendance',
                'choices' => [
                    'Yes' => true,
                    'No' => false
                ],
                'expanded' => true,
                'required' => false
            ])
            ->add('guests', 'choice', [
                'label' => 'Number of guests',
                'required' => true,
                'choices' => array_combine(range(1, 10), range(1, 10))
            ])
            ->add('childs', 'choice', [
                'label' => 'Number of childs',
                'choices' => array_combine(range(0, 10), range(0, 10)),
                'required' => false
            ])
            ->add('vegans', 'choice', [
                'label' => 'Number of vegans',
                'required' => false,
                'choices' => array_combine(range(0, 10), range(0, 10))
            ])
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('attending')
            ->add('childs')
            ->add('vegans')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('firstname')
            ->add('lastname')
            ->add('email')
            ->add('attending', 'choice', [
                'editable' => true,
                'choices' => [
                    null => 'Not confirmed',
                    true => 'Yes',
                    false => 'No',
                ],
            ])
            ->add('guests', 'choice', [
                'editable' => true,
                'choices' => array_combine(range(1, 10), range(1, 10))
            ])
            ->add('childs', 'choice', [
                'editable' => true,
                'choices' => array_combine(range(0, 10), range(0, 10)),
            ])
            ->add('vegans', 'choice', [
                'editable' => true,
                'choices' => array_combine(range(0, 10), range(0, 10))
            ])
        ;
    }
    
    public function configureActionButtons($action, $object = null)
    {
        $list = parent::configureActionButtons($action, $object);

        $list['import']['template'] =  '@Admin/partials/import-button.html.twig';

        return $list;
    }

    public function getDashboardActions()
    {
        $actions = parent::getDashboardActions();

        $actions['import'] = [
            'label' => 'admin.import.action',
            'translation_domain' => 'AdminBundle',
            'url' => $this->generateUrl('import'),
            'icon' => 'level-up',
        ];

        return $actions;
    }
}
