<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class PostAbstractComment
 * @package AdminBundle\Entity
 *
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\PostCommentRepository")
 */
class PostComment extends AbstractComment
{
    /**
     * @ORM\ManyToOne(targetEntity="AdminBundle\Entity\Post", inversedBy="comments")
     * @ORM\JoinColumn(name="id_post", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private $post;

    /**
     * {@inheritdoc}
     */
    static function getDtype()
    {
        return 'greeting';
    }

    /**
     * Set post.
     *
     * @param \AdminBundle\Entity\Post $post
     *
     * @return PostComment
     */
    public function setPost(\AdminBundle\Entity\Post $post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post.
     *
     * @return \AdminBundle\Entity\Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return PostComment
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
