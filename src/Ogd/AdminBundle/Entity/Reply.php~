<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Reply
 *
 * @ORM\Table(name="ogd_reply")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\ReplyRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Reply
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AdminBundle\Entity\Enquiry", inversedBy="replies")
     * @ORM\JoinColumn(name="id_enquiry", referencedColumnName="id", nullable=false)
     */
    private $greeting;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="replies")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id", nullable=false)
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
     */
    private $createdAt;

    /**
     * Set createdAt value before persist
     *
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
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
     * @return Reply
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
     * @return Reply
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
     * Set greeting.
     *
     * @param \AdminBundle\Entity\Enquiry $greeting
     *
     * @return Reply
     */
    public function setGreeting(\AdminBundle\Entity\Enquiry $greeting)
    {
        $this->greeting = $greeting;

        return $this;
    }

    /**
     * Get greeting.
     *
     * @return \AdminBundle\Entity\Enquiry
     */
    public function getGreeting()
    {
        return $this->greeting;
    }

    /**
     * Set user.
     *
     * @param \Application\Sonata\UserBundle\Entity\User $user
     *
     * @return Reply
     */
    public function setUser(\Application\Sonata\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
