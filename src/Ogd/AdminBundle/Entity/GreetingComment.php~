<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GreetingComment
 *
 * @ORM\Table(name="ogd_greeting_comment")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\GreetingCommentRepository")
 */

class GreetingComment extends CommentAbstract
{
    /**
     * @ORM\ManyToOne(targetEntity="AdminBundle\Entity\Greeting", inversedBy="comments")
     * @ORM\JoinColumn(name="id_greeting", referencedColumnName="id", nullable=false)
     */
    private $greeting;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set content.
     *
     * @param string $content
     *
     * @return GreetingComment
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return GreetingComment
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set user.
     *
     * @param \Application\Sonata\UserBundle\Entity\User|null $user
     *
     * @return GreetingComment
     */
    public function setUser(\Application\Sonata\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \Application\Sonata\UserBundle\Entity\User|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set greeting.
     *
     * @param \AdminBundle\Entity\Greeting $greeting
     *
     * @return GreetingComment
     */
    public function setGreeting(\AdminBundle\Entity\Greeting $greeting)
    {
        $this->greeting = $greeting;

        return $this;
    }

    /**
     * Get greeting.
     *
     * @return \AdminBundle\Entity\Greeting
     */
    public function getGreeting()
    {
        return $this->greeting;
    }

}
