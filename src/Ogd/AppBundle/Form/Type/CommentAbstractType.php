<?php

namespace AppBundle\Form\Type;

use AppBundle\Form\EventSubscriber\AddUserNameSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentAbstractType extends AbstractType
{
    /** @var string */
    private $class;

    /**
     * CommentAbstractType constructor.
     * @param string $class
     */
    public function __construct($class = null)
    {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => 'frontend.guestbook.comments.content',
                'required' => true
            ])
            ->add('submit', SubmitType::class, ['label' => 'frontend.guestbook.comments.post'])
            ->addEventSubscriber(new AddUserNameSubscriber())
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => $this->class,
                'translation_domain' => 'AppBundle'
            ])
        ;
    }
}