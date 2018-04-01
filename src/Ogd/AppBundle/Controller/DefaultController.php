<?php

namespace AppBundle\Controller;

use AdminBundle\Entity\Enquiry;
use AppBundle\Form\Type\EnquiryType;
use AppBundle\Service\Mailer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('@App/default/index.html.twig');
    }

    /**
     * @Route("/enquiry", name="enquiry")
     * @Method("POST")
     * @param Request $request
     * @param Mailer $mailer
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function enquiryAction(Request $request, Mailer $mailer)
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->redirectToRoute('homepage');
        }
        
        $status  = 200;
        $enquiry = new Enquiry();
        $form    = $this->createForm(EnquiryType::class, $enquiry);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($enquiry);
                $em->flush();

                //Send notification
                $mailer->sendEnquiryNotificationMessage($enquiry);

                return new JsonResponse(null, $status);
            } else {
                $status = 400;
            }
        }
        
        return new JsonResponse([
            'view' => $this->renderView('@App/default/partials/enquiry.html.twig', [
                'form' => $form->createView()
            ])
        ], $status);
    }
}
