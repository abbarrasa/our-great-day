<?php

//namespace AdminBundle\Entity;
//
//use Doctrine\ORM\Mapping as ORM;
//use Symfony\Component\Validator\Constraints as Assert;
//use AppBundle\Validator\Constraints as AppAsserts;
//
///**
// *
// * @ORM\Table(name="ogd_location")
// * @ORM\Entity(repositoryClass="AdminBundle\Repository\LocationRepository")
// */
//class Location
//{
//    /**
//     * @var int
//     *
//     * @ORM\Column(name="id", type="integer")
//     * @ORM\Id
//     * @ORM\GeneratedValue(strategy="AUTO")
//     */
//    private $id;
//
//    /**
//     * @var string
//     *
//     * @ORM\Column(name="title", type="string", length=255)
//     */
//    private $title;
//
//    /**
//     * @var string
//     *
//     * @ORM\Column(name="description", type="string", length=255)
//     */
//    private $description;
//
//    /**
//     * @var integer
//     *
//     * @ORM\Column(name="order", type="integer")
//     */
//    private $order;
//
//    /**
//     * @var float
//     *
//     * @ORM\Column(name="latitude", type="decimal", nullable=true)
//     * @AppAsserts\Latitude()
//     */
//    private $latitude;
//
//    /**
//     * @var float
//     *
//     * @ORM\Column(name="longitude", type="decimal", nullable=true)
//     * @AppAsserts\Longitude()
//     */
//    private $longitude;
//
//    /**
//     * @var string
//     *
//     * @ORM\Column(name="address", type="string", length=512)
//     */
//    private $address;
//
//    /**
//     * Get id.
//     *
//     * @return int
//     */
//    public function getId()
//    {
//        return $this->id;
//    }
//
//    /**
//     * Set title.
//     *
//     * @param string $title
//     *
//     * @return Location
//     */
//    public function setTitle($title)
//    {
//        $this->title = $title;
//
//        return $this;
//    }
//
//    /**
//     * Get title.
//     *
//     * @return string
//     */
//    public function getTitle()
//    {
//        return $this->title;
//    }
//
//    /**
//     * Set description.
//     *
//     * @param string $description
//     *
//     * @return Location
//     */
//    public function setDescription($description)
//    {
//        $this->description = $description;
//
//        return $this;
//    }
//
//    /**
//     * Get description.
//     *
//     * @return string
//     */
//    public function getDescription()
//    {
//        return $this->description;
//    }
//
//    /**
//     * Set order.
//     *
//     * @param int $order
//     *
//     * @return Location
//     */
//    public function setOrder($order)
//    {
//        $this->order = $order;
//
//        return $this;
//    }
//
//    /**
//     * Get order.
//     *
//     * @return int
//     */
//    public function getOrder()
//    {
//        return $this->order;
//    }
//
//    /**
//     * Set latitude.
//     *
//     * @param string|null $latitude
//     *
//     * @return Location
//     */
//    public function setLatitude($latitude = null)
//    {
//        $this->latitude = $latitude;
//
//        return $this;
//    }
//
//    /**
//     * Get latitude.
//     *
//     * @return string|null
//     */
//    public function getLatitude()
//    {
//        return $this->latitude;
//    }
//
//    /**
//     * Set longitude.
//     *
//     * @param string|null $longitude
//     *
//     * @return Location
//     */
//    public function setLongitude($longitude = null)
//    {
//        $this->longitude = $longitude;
//
//        return $this;
//    }
//
//    /**
//     * Get longitude.
//     *
//     * @return string|null
//     */
//    public function getLongitude()
//    {
//        return $this->longitude;
//    }
//
//    /**
//     * Set address.
//     *
//     * @param string $address
//     *
//     * @return Location
//     */
//    public function setAddress($address)
//    {
//        $this->address = $address;
//
//        return $this;
//    }
//
//    /**
//     * Get address.
//     *
//     * @return string
//     */
//    public function getAddress()
//    {
//        return $this->address;
//    }
//
//    /**
//     * Set location data from GoogleMapType.
//     *
//     * @param array $location
//     * @return $this
//     */
//    public function setLocation(array $location)
//    {
//        $this->setLatitude($location['lat']);
//        $this->setLongitude($location['lng']);
//        $this->setAddress(isset($location['addr']) ? $location['addr'] : null);
//
//        return $this;
//    }
//
//    /**
//     * Get location data for GoogleMapType.
//     *
//     * @return array
//     * @Assert\NotBlank()
//     */
//    public function getLocation()
//    {
//        return array(
//            'lat'  => $this->getLatitude(),
//            'lng'  => $this->getLongitude(),
//            'addr' => $this->getAddress()
//        );
//    }
//}
