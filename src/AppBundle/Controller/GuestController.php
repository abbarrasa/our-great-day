<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Guest;
use AppBundle\Entity\Guestbook;
use AppBundle\Form\Type\GuestbookType;
use AppBundle\Form\Type\GuestConfirmationType;

class GuestController extends Controller
{
    /**
     * @Route("/guest", name="guest")
     */
    public function guestAction(Request $request)
    {
        $form = $this->createForm(GuestConfirmationType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $guests = $em->getRepository(Guest::class)->findByCriteria($form->getData());
                if (count($guests) > 1) {
                    $form->addError(new FormError('frontend.guest.multiple_matches'));
                } else if (count($guests) == 0) {
                    $form->addError(new FormError('frontend.guest.not_found'));
                } else {
                    return $this->redirectToRoute('guest_confirm', ['id' => $guests[0]->getId()]);
                }
            } else {
                $form->addError(new FormError('frontend.form.error'));
            }
        }

        return $this->render('guest/confirmation.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/guest/confirm/{id}", requirements={"id" = "\d+"}, name="guest_confirm")
     */
    public function confirmAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if (($guest = $em->getRepository(Guest::class)->find($id)) === null) {
            throw $this->createNotFoundException(sprintf('No found any guest with ID %d', $id));
        }

        $form = $this->createForm(GuestConfirmationType::class, $guest);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($guest);
            $em->flush();
            
            $helper = $this->get('AppBundle\Service\FlashMessageHelper');
            $this->addFlash('success', $helper->getFlashMessage(
                'success', 'frontend.success', 'frontend.guest.success'
            ));            

            return $this->redirectToRoute('homepage');
        }

        return $this->render('guest/confirmation.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/guestbook/{page}", requirements={"page" = "\d+"}, name="guestbook")
     */
    public function guestbookAction(Request $request, $page = 1)
    {
        $em        = $this->getDoctrine()->getManager();
        $guestbook = new Guestbook();
        $form      = $this->createForm(GuestbookType::class, $guestbook);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($guestbook);
            $em->flush();

            $helper = $this->get('AppBundle\Service\FlashMessageHelper');
            $this->addFlash('success', $helper->getFlashMessage(
                'success', 'frontend.success', 'frontend.guestbook.success'
            ));

            return $this->redirectToRoute('guestbook', ['page' => $page]);
        }

        $query      = $em->getRepository(Guestbook::class)->getQueryAllApproved();
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $page /*page number*/,
            9 /*limit per page*/
        );

        // parameters to template
        return $this->render('guest/guestbook.html.twig', array(
            'pagination' => $pagination,
            'form'       => $form->createView()
        ));
    }

    /**
     * @Route("/guestbook/like/{id}/{page}", requirements={"id" = "\d+", "page" = "\d+"}, name="guestbook_like")
     */
    public function likeAction(Request $request, $id, $page = 1)
    {
        $em = $this->getDoctrine()->getManager();
        if (($guestbook = $em->getRepository(Guestbook::class)->find($id)) === null) {
            throw $this->createNotFoundException(sprintf('No found any guestbook with ID %d', $id));
        }

        $guestbook->setLikes($guestbook->getLikes() + 1);
        $em->persist($guestbook);
        $em->flush();
        
        $helper = $this->get('AppBundle\Service\FlashMessageHelper');
        $this->addFlash('success', $helper->getFlashMessage(
            'success', 'frontend.success', 'frontend.guestbook.likes.success', ['%author%' => $guestbook->getName()]
        ));
        
        return $this->redirectToRoute('guestbook', ['page' => $page]);
    }
}
