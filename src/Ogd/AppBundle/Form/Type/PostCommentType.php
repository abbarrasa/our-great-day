<?php

namespace AppBundle\Form\Type;

use AdminBundle\Entity\PostComment;

class PostCommentType extends CommentAbstractType
{
    /**
     * PostCommentType constructor.
     */
    public function __construct()
    {
        parent::__construct(PostComment::class);
    }
}
