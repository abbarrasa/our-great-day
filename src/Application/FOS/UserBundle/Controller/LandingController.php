<?php

namespace Application\FOS\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LandingController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function landingAction(Request $request)
    {
        $loginResponse = $this->forward('user.security.controller:loginAction', array( $request ));
        $registerResponse = $this->forward('user.registration.controller:registerAction', array( $request ));

        return $this->render('@FOSUser/Landing/landing.html.twig', array(
            'login' => $loginResponse->getContent(),
            'register' => $registerResponse->getContent(),
        ));
    }


}
