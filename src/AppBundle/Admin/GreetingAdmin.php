<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Guest;
use AppBundle\Entity\Greeting;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\UserBundle\Model\User;

class GreetingAdmin extends Admin
{
    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('createdAt')
            ->add('publishedAt')
            ->add('status')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('createdAt')
            ->add('publishedAt')
            ->add('status', 'choice', [
                'editable' => true,
                'choices' => [
                    Greeting::STATUS_PENDING => 'Pending',
                    Greeting::STATUS_APPROVED => 'Approved',
                    Greeting::STATUS_REJECTED => 'Rejected',
                ],
            ])
        ;
    }

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
}