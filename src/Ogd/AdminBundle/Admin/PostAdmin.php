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

    /**
     * Set publishedAt and templateFile values on updates
     * @param object $post
     */
    public function preUpdate($post)
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

//admin.post:
//        class: AdminBundle\Admin\PostAdmin
//        tags:
//            - { name: sonata.admin, manager_type: orm, group: admin.group.settings, label: admin.model.list }
//        arguments:
//            - ~
//            - AdminBundle\Entity\Post
//            - ~
//            calls:
//            - [ setTranslationDomain, [AdminBundle]]
//        public: true


//twig:
//    form_themes:
//        - 'form/fields.html.twig'
//    paths: '%kernel.project_dir%/src/Ogd/AdminBundle/Resources/view': Admin




//public function load(array $configs, ContainerBuilder $container)
//{
//    $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
//    //Config
//    $loader->load('config.yml');
//    //Services for Admin classes
//    $loader->load('admin.yml');
//}



//services:
//
//    AppBundle\Form\Extension\TextTypeExtension:
//        tags:
//            - { name: form.type_extension, extended_type: Symfony\Component\Form\Extension\Core\Type\TextType }


//<div class="col-xs-12 col-md-8 ml-auto">
//    <img src="assets/img/faces/avatar.jpg" alt="Raised Image" class="img-raised rounded img-fluid">
//</div>


//<div class="col-12">
//    <div class="row">
//        {% for post in pagination %}
//            {{ include('@App/post/list/' ~ post.templateFile )}}
//        {% endfor %}
//    </div>
//</div>
//
//<div class="col-8 ml-auto sr">
//    {{ knp_pagination_render(pagination, '@App/partials/knp_pagination.html.twig') }}
//</div>
