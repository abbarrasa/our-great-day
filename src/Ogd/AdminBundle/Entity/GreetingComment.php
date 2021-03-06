<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class GreetingComment
 * @package AdminBundle\Entity
 *
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\GreetingCommentRepository")
 */
class GreetingComment extends AbstractComment
{
    /**
     * @ORM\ManyToOne(targetEntity="AdminBundle\Entity\Greeting", inversedBy="comments")
     * @ORM\JoinColumn(name="id_greeting", referencedColumnName="id", nullable=true)
     */
    private $greeting;

    /**
     * {@inheritdoc}
     */
    static function getDtype()
    {
        return 'post';
    }

    /**
     * Set greeting.
     *
     * @param \AdminBundle\Entity\Greeting $greeting
     *
     * @return GreetingComment
     */
    public function setGreeting(\AdminBundle\Entity\Greeting $greeting)
    {
        $this->greeting = $greeting;

        return $this;
    }

    /**
     * Get greeting.
     *
     * @return \AdminBundle\Entity\Greeting
     */
    public function getGreeting()
    {
        return $this->greeting;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return GreetingComment
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
}
