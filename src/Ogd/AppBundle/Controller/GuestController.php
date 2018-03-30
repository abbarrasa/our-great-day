<?php

namespace AppBundle\Controller;

use AdminBundle\Entity\GreetingComment;
use AppBundle\Form\Type\GreetingCommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AdminBundle\Entity\Guest;
use AdminBundle\Entity\Greeting;
use AppBundle\Form\Type\GreetingType;
use AppBundle\Form\Type\GuestConfirmationType;

class GuestController extends Controller
{
    /**
     * @Route("/guest", name="guest")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
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
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
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
            
            $helper = $this->get('app.helper.flash_message');
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
     * @param Request $request
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function guestbookAction(Request $request, $page = 1)
    {
        $em        = $this->getDoctrine()->getManager();
        $greeting  = new Greeting();
        $form      = $this->createForm(GreetingType::class, $greeting);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($greeting);
            $em->flush();

            //Send notification
            //$this->get('app.manager.mailer')->sendGuestbookNotificationMessage($guestbook);

            //Flash success message
            $helper = $this->get('app.helper.flash_message');
            $this->addFlash('success', $helper->getFlashMessage(
                'success', 'frontend.success', 'frontend.guestbook.success'
            ));

            return $this->redirectToRoute('guestbook', ['page' => $page]);
        }

        $query      = $em->getRepository(Greeting::class)->getQueryAllApproved();
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
     * @Route("/guestbook/greeting/{id}/like/{page}", requirements={"id" = "\d+", "page" = "\d+"}, name="greeting_like")
     * @param Request $request
     * @param $id
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function likeAction(Request $request, $id, $page = 1)
    {
        $em = $this->getDoctrine()->getManager();
        if (($greeting = $em->getRepository(Greeting::class)->find($id)) === null) {
            throw $this->createNotFoundException(sprintf('No found any greeting with ID %d', $id));
        }

        $greeting->setLikes($greeting->getLikes() + 1);
        $em->persist($greeting);
        $em->flush();
        
        $helper = $this->get('app.helper.flash_message');
        $this->addFlash('success', $helper->getFlashMessage(
            'success', 'frontend.success', 'frontend.guestbook.likes.success', ['%author%' => $greeting->getName()]
        ));
        
        return $this->redirectToRoute('guestbook', ['page' => $page]);
    }

    /**
     * @Route("/guestbook/greeting/{id}/comments", requirements={"id" = "\d+"}, name="greeting_comments")
     * @param Request $request
     * @param $id
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function commentsAction(Request $request, $id)
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->redirectToRoute('guestbook');
        }

        $em = $this->getDoctrine()->getManager();
        if (($greeting = $em->getRepository(Greeting::class)->find($id)) === null) {
            throw $this->createNotFoundException(sprintf('No found any greeting with ID %d', $id));
        }

        $status          = 200;
        $greetingComment = new GreetingComment();
        $greetingComment->setGreeting($greeting);
        $form = $this->createForm(GreetingCommentType::class, $greetingComment);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $greeting->addComment($greetingComment);
                $em->persist($greetingComment);
                $em->persist($greeting);
                $em->flush();

                return new JsonResponse([
                    'view' => $this->renderView('guest/comments.html.twig', [
                        'greeting' => $greeting,
                        'form'     => $form->createView()
                    ]),
                    'data_comments' => $greeting->getId(),
                    'comments'      => $greeting->getComments()->count()
                ], $status);
            } else {
                $status = 400;
            }
        }

        return new JsonResponse([
            'view' => $this->renderView('guest/comments.html.twig', [
                'greeting' => $greeting,
                'form'     => $form->createView()
            ])
        ], $status);
    }
}
