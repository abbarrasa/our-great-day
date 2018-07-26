<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Seat
 *
 * @ORM\Table(name="ogd_seat")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\SeatRepository")
 * @UniqueEntity("guest")
 * @UniqueEntity(fields={"firstname", "lastname"}) 
 * @ORM\HasLifecycleCallbacks()
 */
class Seat
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
     * @ORM\Column(name="firstname", type="string", length=255)
     * @Assert\Length(min=2, max=255)
     * @Assert\NotBlank()
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     * @Assert\Length(min=2, max=255)
     * @Assert\NotBlank()
     */
    private $lastname;

    /**
     * @ORM\OneToOne(targetEntity="AdminBundle\Entity\Guest", inversedBy="seat")
     * @ORM\JoinColumn(name="id_guest", referencedColumnName="id", nullable=true)
     */
    private $guest;

    /**
     * @ORM\ManyToOne(targetEntity="AdminBundle\Entity\Table", inversedBy="seats")
     * @ORM\JoinColumn(name="id_table", referencedColumnName="id")
     */
    private $table;
    

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
     * Set firstname.
     *
     * @param string $firstname
     *
     * @return Seat
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname.
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname.
     *
     * @param string $lastname
     *
     * @return Seat
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname.
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set guest.
     *
     * @param \AdminBundle\Entity\Guest|null $guest
     *
     * @return Seat
     */
    public function setGuest(\AdminBundle\Entity\Guest $guest = null)
    {
        $this->guest = $guest;

        return $this;
    }

    /**
     * Get guest.
     *
     * @return \AdminBundle\Entity\Guest|null
     */
    public function getGuest()
    {
        return $this->guest;
    }

    /**
     * Set table.
     *
     * @param \AdminBundle\Entity\Table|null $table
     *
     * @return Seat
     */
    public function setTable(\AdminBundle\Entity\Table $table = null)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Get table.
     *
     * @return \AdminBundle\Entity\Table|null
     */
    public function getTable()
    {
        return $this->table;
    }
}
