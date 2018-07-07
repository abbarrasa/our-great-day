<?php

namespace AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PostAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'post';

    /**
     * Fields to be shown on filter forms
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('published')
            ->add('createdAt')
            ->add('publishedAt')
            ->add('likes')
        ;
    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {

        $formMapper
            ->add('title', 'text', ['label' => 'Title'])
            ->add('coverPictureFile', VichImageType::class, [
                'label' => 'Cover picture',
                'required' => $this->isCurrentRoute('create'),
                'allow_delete' => false,
                'download_uri' => false,
                'image_uri' => true
            ])
            ->add('content', 'textarea', [
                'label' => 'Text',
                'required' => false
            ])
            ->add('published', null, ['label' => 'Is published?'])
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
            ->add('coverPictureFile', null, ['template' => '@Admin/partials/list_image.html.twig'])
            ->add('published', 'choice', [
                'editable' => true,
                'choices' => [
                    false => 'No',
                    true => 'Yes'
                ],
            ])
            ->add('publishedAt')            
            ->add('createdAt')
        ;
    }

    public function prePersist($post)
    {
        $this->updatePublishedAt($post);
    }

    /**
     * Set publishedAt and templateFile values on updates
     * @param object $post
     */
    public function preUpdate($post)
    {
        $this->updatePublishedAt($post);
    }

    private function updatePublishedAt($post)
    {
        if ($post->isPublished()) {
            if ($post->getPublishedAt() === null) {
                $post->setPublishedAt(new \DateTime());
            }
        } else {
            $post->setPublishedAt(null);
        }
    }
}