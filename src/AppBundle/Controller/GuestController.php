<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $guest = $em->getRepository(Guest::class)->findOneBy($form->getData());
            if ($guest) {
                return $this->redirectToRoute('guest_confirm', ['id' => $guest->getId()]);
            }
        }

        return $this->render('AppBundle:guest:confirmation.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/guest/confirm/{id}", requirements={"id" = "\d+"}, name="guest_confirm")
     */
    public function confirmAction(Request $request, $id)
    {
        $em = $this->getDoctrine();
        if (($guest = $em->getRepository(Guest::class)->find($id)) === null) {
            throw $this->createNotFoundException(sprintf('No found any guest with ID %d', $id));
        }

        $form = $this->createForm(GuestConfirmationType::class, $guest);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($guest);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('AppBundle:guest:confirmation.html.twig', [
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
        $form = $this->createForm(GuestbookType::class, $guestbook);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($guestbook);
            $em->flush();

            $this->addFlash(
                'success',
                $this->get('translator')->trans('Se ha actualizado correctamente el registro')
            );
        }

        $query      = $em->getRepository(Guestbook::class)->getQueryAllModerate();
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $page /*page number*/,
            15 /*limit per page*/
        );

        // parameters to template
        return $this->render('AcmeMainBundle:Article:list.html.twig', array('pagination' => $pagination));
    }

    /**
     * @Route("/guestbook/like/{id}", requirements={"id" = "\d+"}, name="guestbook_like")
     */
    public function likeAction(Request $request, $id)
    {
        $em = $this->getDoctrine();
        if (($guestbook = $em->getRepository(Guestbook::class)->find($id)) === null) {
            throw $this->createNotFoundException(sprintf('No found any guestbook with ID %d', $id));
        }

        $guestbook->setLikes($guestbook->getLikes() + 1);
        $em->persist($guestbook);
        $em->flush();
    }

    /**
     * @Route("/guestbook/unlike/{id}", requirements={"id" = "\d+"}, name="guestbook_unlike")
     */
    public function unlikeAction(Request $request, $id)
    {
        $em = $this->getDoctrine();
        if (($guestbook = $em->getRepository(Guestbook::class)->find($id)) === null) {
            throw $this->createNotFoundException(sprintf('No found any guestbook with ID %d', $id));
        }

        $guestbook->setUnlikes($guestbook->getUnlikes() + 1);
        $em->persist($guestbook);
        $em->flush();
    }
}