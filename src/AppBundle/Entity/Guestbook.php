<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Guestbook
 *
 * @ORM\Table(name="ogd_guestbook")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GuestbookRepository")
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
     * @ORM\Column(name="date", type="datetime")
     * @Assert\NotNull()
     */
    private $date;

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
     * @Assert\NotNull()
     */
    private $likes;

    /**
     * @var integer
     *
     * @ORM\Column(name="unlikes", type="integer")
     * @Assert\NotNull()
     */
    private $unlikes;

    /**
     * Guestbook constructor.
     */
    public function __construct()
    {
        $this->setLikes(0);
        $this->setUnlikes(0);
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
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return Guestbook
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
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
}
