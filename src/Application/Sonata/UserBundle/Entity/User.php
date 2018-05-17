<?php

namespace Application\Sonata\UserBundle\Entity;

use Sonata\UserBundle\Entity\BaseUser as BaseUser;

/**
 * This file has been generated by the SonataEasyExtendsBundle.
 *
 * @link https://sonata-project.org/easy-extends
 *
 * References:
 * @link http://www.doctrine-project.org/projects/orm/2.0/docs/reference/working-with-objects/en
 */
class User extends BaseUser
{
    /**
     * @var int $id
     */
    protected $id;
    
    protected $guest;

    protected $greetings;

    protected $greetingComments;

    protected $picture;

    /**
     * Get id.
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set guest.
     *
     * @param $guest
     */
    public function setGuest($guest)
    {
        $this->guest = $guest;
    }

    /**
     * Get guest.
     *
     * @return mixed
     */
    public function getGuest()
    {
        return $this->guest;
    }

    /**
     * Set greetings.
     *
     * @param $greetings
     */
    public function setGreetings($greetings)
    {
        $this->greetings = $greetings;
    }

    /**
     * Get greetings.
     *
     * @return mixed
     */
    public function getGreetings()
    {
        return $this->greetings;
    }

    /**
     * Set greetingComments.
     *
     * @param $greetingComments
     */
    public function setGreetingComments($greetingComments)
    {
        $this->greetingComments = $greetingComments;
    }

    /**
     * Get greetingComments.
     *
     * @return mixed
     */
    public function getGreetingComments()
    {
        return $this->greetingComments;
    }

    /**
     * Set picture.
     *
     * @param $picture
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    /**
     * Get picture.
     *
     * @return mixed
     */
    public function getPicture()
    {
        return $this->picture;
    }
}
