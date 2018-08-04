<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use AdminBundle\Validator\Constraints as AdminAsserts;

/**
 * Table
 *
 * @ORM\Table(name="ogd_table")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\TableRepository")
 * @UniqueEntity("name")
 * @AdminAsserts\TableSeats()
 * @ORM\HasLifecycleCallbacks()
 */
class Table
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
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     */
    private $name;

    /**
     * @var integer
     * @ORM\Column(name="number_seats", type="integer")
     * @Assert\NotNull()
     */
    private $numberSeats;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="AdminBundle\Entity\Seat", mappedBy="table")
     */
    private $seats;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->numberSeats = 1;
        $this->seats = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Table
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
     * Set numberSeats.
     *
     * @param int $numberSeats
     *
     * @return Table
     */
    public function setNumberSeats($numberSeats)
    {
        $this->numberSeats = $numberSeats;

        return $this;
    }

    /**
     * Get numberSeats.
     *
     * @return int
     */
    public function getNumberSeats()
    {
        return $this->numberSeats;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Table
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
     * Add seat.
     *
     * @param \AdminBundle\Entity\Seat $seat
     *
     * @return Table
     */
    public function addSeat(\AdminBundle\Entity\Seat $seat)
    {
        $this->seats[] = $seat;

        return $this;
    }

    /**
     * Remove seat.
     *
     * @param \AdminBundle\Entity\Seat $seat
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeSeat(\AdminBundle\Entity\Seat $seat)
    {
        return $this->seats->removeElement($seat);
    }

    /**
     * Get seats.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeats()
    {
        return $this->seats;
    }
    
    /**
     * Set createdAt and freeSeats values before persist
     *
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->setCreatedAt(new \DateTime());
    }

    /**
     * Get freeSeats.
     *
     * @return int
     */
    public function getFreeSeats()
    {
        return $this->numberSeats - $this->seats->count();
    }
}
