<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Guest;
use AppBundle\Entity\Guestbook;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\UserBundle\Model\User;

class GuestbookAdmin extends Admin
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
                    Guestbook::STATUS_PENDING => 'Pending',
                    Guestbook::STATUS_APPROVED => 'Approved',
                    Guestbook::STATUS_REJECTED => 'Rejected',
                ],
            ])
        ;
    }

    public function preUpdate($guestbook)
    {
        if ($guestbook->getStatus() == Guestbook::STATUS_APPROVED) {
            if ($guestbook->getPublishedAt() === null) {
                $guestbook->setPublishedAt(new \DateTime());
            }
        } else {
            $guestbook->setPublishedAt(null);
        }
    }
}