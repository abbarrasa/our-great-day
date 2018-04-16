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

    /**
     * The RESETTING_REQUEST_USER_INVALID event occurs when the user requested is invalid in resetting process.
     *
     * This event allows you to set the response when the user requested is invalid.
     * The event listener method receives a FOS\UserBundle\Event\GetResponseNullableUserEvent instance.
     *
     * @Event("FOS\UserBundle\Event\GetResponseNullableUserEvent")
     */
    const RESETTING_REQUEST_USERNAME_INVALID = 'resetting.request.username.invalid';

    const RESETTING_ACCOUNT_LOCKED = 'resetting.account.locked';

}