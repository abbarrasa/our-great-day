<?php

namespace AdminBundle\Admin;

use AdminBundle\Form\EventSubscriber\GuestSeatSubscriber;
use Doctrine\DBAL\Query\QueryBuilder;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;

class SeatAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'seat';

    /**
     * Fields to be shown on filter forms
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('guest', 'doctrine_orm_model_autocomplete', array(), null, array(
                // in related CategoryAdmin there must be datagrid filter on `title` field to make the autocompletion work
                'property' => ['firstname', 'lastname'],
                'callback' => function ($admin, $property, $value) {
                    /** @var QueryBuilder $qb */
                    $qb    = $admin->getDatagrid()->getQuery();
                    $alias = $qb->getRootAlias();
                    $qb
                        ->andWhere(
                            $qb->expr()->orX(
                                $qb->expr()->like($alias . '.firstname', $qb->expr()->literal('%' . $value . '%')),
                                $qb->expr()->like($alias . '.lastname', $qb->expr()->literal('%' . $value . '%'))
                            )
                        )
                        ->andWhere($alias . '.attending=1')
                    ;
                },
                'to_string_callback' => function($entity, $property) {
                    return $entity->getLastname() . ', ' . $entity->getFirstname();
                },                
            ))
            ->add('table', 'doctrine_orm_model_autocomplete', array(), null, array(
                // in related CategoryAdmin there must be datagrid filter on `title` field to make the autocompletion work
                'property' => ['title', 'subtitle'],
                'callback' => function ($admin, $property, $value) {
                    /** @var QueryBuilder $qb */
                    $qb    = $admin->getDatagrid()->getQuery();
                    $alias = $qb->getRootAlias();
                    $qb
                        ->andWhere(
                            $qb->expr()->orX(
                                $qb->expr()->like($alias . '.title', $qb->expr()->literal('%' . $value . '%')),
                                $qb->expr()->like($alias . '.subtitle', $qb->expr()->literal('%' . $value . '%'))
                            )
                        )
                    ;
                },
                'to_string_callback' => function($entity, $property) {
                    return $entity->getTitle . ($entity->getSubtitle !== null ? ' (' . $entity->getSubtitle() . ')' : '');
                },                
            ))            
            //->add('guest')
            //->add('table')
        ;
    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('guest', ModelAutocompleteType::class, [
                'property' => ['firstname', 'lastname'],
                'callback' => function ($admin, $property, $value) {
                    /** @var QueryBuilder $qb */
                    $qb    = $admin->getDatagrid()->getQuery();
                    $alias = $qb->getRootAlias();
                    $qb
                        ->andWhere(
                            $qb->expr()->orX(
                                $qb->expr()->like($alias . '.firstname', $qb->expr()->literal('%' . $value . '%')),
                                $qb->expr()->like($alias . '.lastname', $qb->expr()->literal('%' . $value . '%'))
                            )
                        )
                        ->andWhere($alias . '.attending=1')
                    ;
                },
                'to_string_callback' => function($entity, $property) {
                    return $entity->getLastname() . ', ' . $entity->getFirstname();
                },
                'required' => false
            ], [
                'help' => 'Seleccione un invitado o introduzca su nombre y apellidos'
            ])
            ->add('name', 'text', ['label' => 'Name', 'required' => false])
            ->getFormBuilder()->addEventSubscriber(new GuestSeatSubscriber($this->modelManager))
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
            ->add('table', null, [
                'associated_property' => 'title'
            ])            
            //->add('table')
            ->add('createdAt')
        ;
    }

//    public function getTemplate($name)
//    {
//        if ($name == 'edit') {
//            return "@Admin/seat/edit.html.twig";
//        }
//
//        return $this->getTemplateRegistry()->getTemplate($name);
//    }


//    public function prePersist($table)
//    {
//        foreach ($table->getSeats() as $seat) {
//            $seat->setTable($table);
//        }
//    }
//
//    public function preUpdate($table)
//    {
//        foreach ($table->getSeats() as $seat) {
//            $seat->setTable($table);
//        }
//    }
}
