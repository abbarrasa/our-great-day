<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Guestbook
 *
 * @ORM\Table(name="ogd_guestbook")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GuestbookRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Guestbook
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var text
     *
     * @ORM\Column(name="comment", type="string", length=1024)
     * @Assert\NotBlank()
     */
    private $comment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Assert\NotNull()
     */
    private $createdAt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="moderated", type="boolean", nullable=true)
     */
    private $moderated;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="guestbook")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id", nullable=true)
     */
    private $user;

    /**
     * @var integer
     *
     * @ORM\Column(name="likes", type="integer")
     * @Assert\NotBlank()
     */
    private $likes;

    /**
     * Guestbook constructor.
     */
    public function __construct()
    {
        $this->setLikes(0);
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
     * Set name.
     *
     * @param string $name
     *
     * @return Guestbook
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

    /**
     * Set comment.
     *
     * @param string $comment
     *
     * @return Guestbook
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment.
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $date
     *
     * @return Guestbook
     */
    public function setCreatedAt($date)
    {
        $this->createdAt = $date;

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
     * Set moderated.
     *
     * @param bool|null $moderated
     *
     * @return Guestbook
     */
    public function setModerated($moderated = null)
    {
        $this->moderated = $moderated;

        return $this;
    }

    /**
     * Get moderated.
     *
     * @return bool|null
     */
    public function getModerated()
    {
        return $this->moderated;
    }

    /**
     * Set user.
     *
     * @param \Application\Sonata\UserBundle\Entity\User|null $user
     *
     * @return Guestbook
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
     * Set likes.
     *
     * @param integer $likes
     *
     * @return Guestbook
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;

        return $this;
    }

    /**
     * Get likes.
     *
     * @return integer
     */
    public function getLikes()
    {
        return $this->likes;
    }    
    
    /**
     * Set createdAt value before persist 
     *
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->setCreatedAt(new \DateTime());
    }    
}
