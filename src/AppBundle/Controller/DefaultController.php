<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Enquiry;
use AppBundle\Entity\Joined;
use AppBundle\Form\Type\EnquiryType;
use AppBundle\Form\Type\JoinedType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
        $joined = new Joined();
        $form   = $this->createForm(JoinedType::class, $joined);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $helper = $this->get('AppBundle\Service\FlashMessageHelper');

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($joined);
                $em->flush();

                $this->addFlash('success', $helper->getFlashMessage(
                    'success', 'frontend.success', 'frontend.joined.success'
                ));

                return $this->redirectToRoute('homepage');
            } else {
                $this->addFlash('error', $helper->getFlashMessage(
                    'error', 'frontend.error', 'frontend.form.error'
                ));
            }
        }

        return $this->render('default/index.html.twig', [
            'form' => $form->createView()
        ]);
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

                return new JsonResponse(null, $status);
            } else {
                $status = 400;
            }
        }

        return new JsonResponse([
            'view' => $this->renderView('default/partials/enquiry.html.twig', [
                'form' => $form->createView()
            ])
        ], $status);
    }
}
