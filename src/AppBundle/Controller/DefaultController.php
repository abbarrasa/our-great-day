<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Enquiry;
use AppBundle\Form\Type\EnquiryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('AppBundle:default:index.html.twig');
    }

    /**
     * @Route("/enquiry", name="enquiry")
     * @Method("POST")
     */
    public function enquiryAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->redirectToRoute('homepage');
        }
        $flashes = null;
        $enquiry = new Enquiry();
        $form    = $this->createForm(EnquiryType::class, $enquiry);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine();
            $em->persist($enquiry);
            $em->flush();

            //Enviamos un correo con la consulta

//            $helper = $this->get('AppBundle\Service\FlashMessageService');
//            $flashes[] = $helper->getFlash('success', 'Tu cuestión ha sido enviada');
        }

        return $this->render('AppBundle:default/partials:enquiry.html.twig', [
            'form' => $form->createView(),
            'flashes' => $flashes
        ]);
    }
}
