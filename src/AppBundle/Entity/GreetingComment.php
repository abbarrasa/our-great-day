<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * GreetingComment
 *
 * @ORM\Table(name="ogd_greeting_comment")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GreetingCommentRepository")
 */

class GreetingComment
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Greeting", inversedBy="comments")
     * @ORM\JoinColumn(name="id_greeting", referencedColumnName="id", nullable=false)
     */
    private $greeting;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="greetingComments")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id", nullable=true)
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * @Assert\NotBlank()
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Assert\NotNull()
     */
    private $createdAt;

    /**
     * GreetingComment constructor.
     */
    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
    }

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
     * @param \AppBundle\Entity\Greeting $greeting
     *
     * @return GreetingComment
     */
    public function setGreeting(\AppBundle\Entity\Greeting $greeting)
    {
        $this->greeting = $greeting;

        return $this;
    }

    /**
     * Get greeting.
     *
     * @return \AppBundle\Entity\Greeting
     */
    public function getGreeting()
    {
        return $this->greeting;
    }
}
