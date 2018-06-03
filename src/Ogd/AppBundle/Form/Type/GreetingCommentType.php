<?php

namespace AppBundle\Form\Type;

use AdminBundle\Entity\GreetingComment;

class GreetingCommentType extends CommentAbstractType
{
    /**
     * GreetingCommentType constructor.
     */
    public function __construct()
    {
        parent::__construct(GreetingComment::class);
    }
}
