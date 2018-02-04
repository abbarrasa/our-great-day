<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Joined;
use AppBundle\Form\JoinedType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
            $translator = $this->get('translator');
            $helper = $this->get('AppBundle\Service\FlashMessageHelper');

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($joined);
                $em->flush();

                $this->addFlash('success', $helper->getFlashMessage(
                    'success', $translator->trans('Success!'), $translator->trans('Your request has been received successfully.')
                ));
            } else {
                $this->addFlash('error', $helper->getFlashMessage(
                    'error', $translator->trans('Error!'), $translator->trans('There is any error in form.')
                ));
            }
        }

        return $this->render('default/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
