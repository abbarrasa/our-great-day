<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppAsserts;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Guest
 *
 * @ORM\Table(name="ogd_guest")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\GuestRepository")
 * @UniqueEntity("email")
 * @UniqueEntity(
 *     fields={"firstname", "lastname"},
 *     errorPath="firstname",
 *     message="guest.name"
 * )
 */
class Guest
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
     * @ORM\Column(name="firstname", type="string", length=255)
     * @Assert\Length(max=255)
     * @Assert\NotBlank()
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     * @Assert\Length(max=255)
     * @Assert\NotBlank()
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true, unique=true)
     * @Assert\Length(max=255)
     * @AppAsserts\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=1, nullable=true)
     */
    private $gender;

    /**
     * @var boolean
     *
     * @ORM\Column(name="attending", type="boolean", nullable=true)
     */
    private $attending;

    /**
     * @var integer
     *
     * @ORM\Column(name="guests", type="integer")
     * @Assert\NotNull()
     */
    private $guests;

    /**
     * @var integer
     *
     * @ORM\Column(name="childs", type="integer", nullable=true)
     */
    private $childs;

    /**
     * @var integer
     *
     * @ORM\Column(name="vegans", type="integer", nullable=true)
     */
    private $vegans;

    /**
     * Guest constructor.
     */
    public function __construct()
    {
        //default values
        $this->setGuests(1);
        $this->setChilds(0);
        $this->setVegans(0);
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
     * Set firstname.
     *
     * @param string $firstname
     *
     * @return Guest
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
     * @return Guest
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
     * Set gender.
     *
     * @param string $gender
     *
     * @return Guest
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender.
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set email.
     *
     * @param string|null $email
     *
     * @return Guest
     */
    public function setEmail($email = null)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set guests.
     *
     * @param int $guests
     *
     * @return Guest
     */
    public function setGuests($guests)
    {
        $this->guests = $guests;

        return $this;
    }

    /**
     * Get guests.
     *
     * @return int
     */
    public function getGuests()
    {
        return $this->guests;
    }

    /**
     * Set childs.
     *
     * @param int|null $childs
     *
     * @return Guest
     */
    public function setChilds($childs = null)
    {
        $this->childs = $childs;

        return $this;
    }

    /**
     * Get childs.
     *
     * @return int|null
     */
    public function getChilds()
    {
        return $this->childs;
    }

    /**
     * Set vegans.
     *
     * @param int|null $vegans
     *
     * @return Guest
     */
    public function setVegans($vegans = null)
    {
        $this->vegans = $vegans;

        return $this;
    }

    /**
     * Get vegans.
     *
     * @return int|null
     */
    public function getVegans()
    {
        return $this->vegans;
    }

    /**
     * Set attending.
     *
     * @param bool|null $attending
     *
     * @return Guest
     */
    public function setAttending($attending = null)
    {
        $this->attending = $attending;

        return $this;
    }

    /**
     * Get attending.
     *
     * @return bool|null
     */
    public function getAttending()
    {
        return $this->attending;
    }

    public function __get($name)
    {
        $func = 'get' . ucfirst(strtolower($name));

        return $this->$func();
    }

    public function __set($name, $value)
    {
        $func = 'set' . ucfirst(strtolower($name));
        $this->$func($value);
    }


}
