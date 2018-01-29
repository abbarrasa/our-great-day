<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Enquiry;
use AppBundle\Form\Type\EnquiryType;
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

        $status  = 200;
        $enquiry = new Enquiry();
        $form    = $this->createForm(EnquiryType::class, $enquiry);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($enquiry);
                $em->flush();

                //Enviamos un correo con la consulta

                $helper     = $this->get('AppBundle\Service\FlashMessageHelper');
                $translator = $this->get('translator');
                return new JsonResponse([
                    'flash' => $helper->getFlash('success', $translator->trans('app.frontend.enquiry.success', [], 'AppBundle'))
                ], $status);
            } else {
                $status = 400;
            }
        }

        return new JsonResponse([
            'view' => $this->renderView('AppBundle:default/partials:enquiry.html.twig', [
                'form' => $form->createView()
            ])
        ], $status);
    }
}
