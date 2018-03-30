<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppAsserts;


/**
 * Joined
 *
 * @ORM\Table(name="ogd_joined")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\JoinedRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Joined
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
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\Length(max=255)
     * @AppAsserts\Email()
     */
    private $email;

    /**
     * @var boolean
     *
     * @ORM\Column(name="notified", type="boolean", nullable=false)
     */
    private $notified;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="requested_at", type="datetime")
     */
    private $requestedAt;

    /**
     * Joined constructor.
     */
    public function __construct()
    {
        $this->setNotified(false);
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
     * Set email.
     *
     * @param string $email
     *
     * @return Joined
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set notified.
     *
     * @param bool $notified
     *
     * @return Joined
     */
    public function setNotified($notified)
    {
        $this->notified = $notified;

        return $this;
    }

    /**
     * Get notified.
     *
     * @return bool
     */
    public function getNotified()
    {
        return $this->notified;
    }

    /**
     * Set requestedAt.
     *
     * @param \DateTime $requestedAt
     *
     * @return Joined
     */
    public function setRequestedAt($requestedAt)
    {
        $this->requestedAt = $requestedAt;

        return $this;
    }

    /**
     * Get requestedAt.
     *
     * @return \DateTime
     */
    public function getRequestedAt()
    {
        return $this->requestedAt;
    }

    /**
     * Set requestedAt value before persist
     *
     * @ORM\PrePersist
     */
    public function setRequestedAtValue()
    {
        $this->setRequestedAt(new \DateTime());
    }
}
