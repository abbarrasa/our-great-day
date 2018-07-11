<?php

namespace AdminBundle\Admin;

use AdminBundle\Entity\AbstractComment;
use AdminBundle\Entity\GreetingComment;
use AdminBundle\Entity\PostComment;
use AdminBundle\Entity\Greeting;
use AdminBundle\Entity\Post;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CommentAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'comment';

    /**
     * Fields to be shown on filter forms
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('content')
            ->add('dtype',
                'doctrine_orm_callback',
                [
                    'callback' => function ($queryBuilder, $alias, $field, $value) {
                        if (!is_array($value) || !array_key_exists('value', $value) || empty($value['value'])) {
                            return false;
                        }

                        $queryBuilder->andWhere($alias . ' INSTANCE OF :dtype');
                        $queryBuilder->setParameter('dtype', $value['value']);

                        return true;
                    },
                ],
                'choice',
                [
                    'choices' => [
                        ucfirst(GreetingComment::getDtype()) => GreetingComment::getDtype(),
                        ucfirst(PostComment::getDtype()) => PostComment::getDtype(),
                    ],
                    'translation_domain' => $this->getTranslationDomain(),
                ]
            )
            ->add('createdAt')
        ;
    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', 'text', ['label' => 'Name'])
            ->add('content', 'textarea', ['label' => 'Message'])
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        /** @var AbstractComment $subject */
        $subject = $this->getSubject();
        $showMapper
            ->add('name')
            ->add('content')
        ;
        
        if ($subject::getDtype() === GreetingComment::getDtype()) {
            $showMapper->add('greeting', 'entity', [
                'class' => Greeting::class,
                'associated_property' => 'message',
            ]);
        } else if ($subject::getDtype() === PostComment::getDtype()) {
            $showMapper->add('post', 'entity', [
                'class' => Post::class,
                'associated_property' => 'content',
            ]);
        }
    }

    /**
     * Fields to be shown on lists
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('name')
            ->add('content')
            ->add('createdAt')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'delete' => [],
                ]
            ])
        ;
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