<?php

namespace Application\FOS\UserBundle\Event;

final class Events
{
    /**
     * The USER_AUTOLOGIN event occurs after validating the user token login of a autologin link.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("FOS\UserBundle\Event\FilterUserResponseEvent")
     */
    const USER_AUTOLOGIN = 'user.autologin';
}