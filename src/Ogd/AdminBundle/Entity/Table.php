<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use AdminBundle\Validator\Constraints as AdminAsserts;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Table
 *
 * @ORM\Table(name="ogd_table")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\TableRepository")
 * @UniqueEntity("title")
 * @UniqueEntity("subtitle")
 * @AdminAsserts\TableSeats()
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable 
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
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     */
    private $title;
    
    /**
     * @var string
     *
     * @ORM\Column(name="subtitle", type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     */
    private $subtitle;
    
    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255, nullable=true)
     */
    private $picture;    

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
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="AdminBundle\Entity\Seat", mappedBy="table", cascade={"persist"})
     */
    private $seats;
    
    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="table_picture", fileNameProperty="picture")
     * @Assert\Image(maxWidth=600, maxHeight=600, maxSize="1M")
     */
    private $pictureFile;    

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
     * Set updatedAt.
     *
     * @param \DateTime|null $updatedAt
     *
     * @return Table
     */
    public function setUpdatedAt($updatedAt = null)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
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
     * Get freeSeats.
     *
     * @return int
     */
    public function getFreeSeats()
    {
        return $this->numberSeats - $this->seats->count();
    }
    
    /**
     * Set pictureFile
     *
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|null $file
     */
    public function setPictureFile(File $file = null)
    {
        $this->pictureFile = $file;

        if ($file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * Get coverPictureFile
     *
     * @return null|File
     */
    public function getPictureFile()
    {
        return $this->pictureFile;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Table
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set subtitle.
     *
     * @param string|null $subtitle
     *
     * @return Table
     */
    public function setSubtitle($subtitle = null)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * Get subtitle.
     *
     * @return string|null
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set picture.
     *
     * @param string $picture
     *
     * @return Table
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture.
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set createdAt value before persist
     *
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->setCreatedAt(new \DateTime('now'));
    }

    /**
     * Set updatedAt value before persist
     *
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->setUpdatedAt(new \DateTime('now'));
    }
}
