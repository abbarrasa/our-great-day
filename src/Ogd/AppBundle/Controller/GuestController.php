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
use Nzo\UrlEncryptorBundle\UrlEncryptor\UrlEncryptor;
use Nzo\UrlEncryptorBundle\Annotations\ParamDecryptor;

class GuestController extends Controller
{
    /**
     * @Route("/guest", name="guest")
     * @param Request $request
     * @param UrlEncryptor $encryptor
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function guestAction(Request $request, UrlEncryptor $encryptor)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED') &&
            ($guest = $this->getUser()->getGuest()) !== null
        ) {
            return $this->redirectToRoute('guest_confirm', ['id' => $encryptor->encrypt($guest->getId())]);            
        }
        
        $form = $this->createForm(GuestConfirmationType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $guests = $em->getRepository(Guest::class)->findByCriteria($form->getData());
                if (count($guests) > 1) {
                    $form->addError(new FormError('guest.multiple_matches.error'));
                } else if (count($guests) == 0) {
                    $form->addError(new FormError('guest.not_found.error'));
                } else {
                    return $this->redirectToRoute('guest_confirm', ['id' => $encryptor->encrypt($guests[0]->getId())]);
                }
            } else {
                $form->addError(new FormError('form.error'));
            }
        }

        return $this->render('@App/guest/confirmation.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/guest/confirm/{id}", name="guest_confirm")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @ParamDecryptor(params={"id"})     
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
                'success', 'frontend.success', 'frontend.guest.success', [], 'AppBundle'
            ));
            $request->getSession()->set('ogd.guest.id', $guest->getId());

            return $this->redirectToRoute('guest_set_user');
        }

        return $this->render('@App/guest/confirmation.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/guest/set-user", name="guest_set_user")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function setUserAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('fos_user_landing');
        }

        $id = $request->getSession()->get('ogd.guest.id');
        $em = $this->getDoctrine()->getManager();
        if (($guest = $em->getRepository(Guest::class)->find($id)) === null) {
            throw $this->createNotFoundException(sprintf('No found any guest with ID %d', $id));
        }

        if (null === $guest->getUser()) {
            $guest->setUser($this->getUser());

            $em->persist($guest);
            $em->flush();

            $helper = $this->get('app.helper.flash_message');
            $this->addFlash('success', $helper->getFlashMessage(
                'success', 'frontend.success', 'frontend.guest.set_user.success', [], 'AppBundle'
            ));
        }

        $request->getSession()->remove('ogd.guest.id');

        return $this->redirectToRoute('homepage');
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
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $greeting->setUser($this->getUser());
        }

        $form      = $this->createForm(GreetingType::class, $greeting);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($greeting);
            $em->flush();

            //Send notification
            //$this->get('app.mailer')->sendGreetingNotificationMessage($greeting);

            //Flash success message
            $helper = $this->get('app.helper.flash_message');
            $this->addFlash('success', $helper->getFlashMessage(
                'success', 'frontend.success', 'frontend.guestbook.success', [], 'AppBundle'
            ));

            return $this->redirectToRoute('guestbook', ['page' => $page]);
        }

        $query      = $em->getRepository(Greeting::class)->getQueryAllApproved();
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $page /*page number*/,
            12 /*limit per page*/
        );

        // parameters to template
        return $this->render('@App/guest/guestbook.html.twig', array(
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
            'success', 'frontend.success', 'frontend.guestbook.likes.success', ['%author%' => $greeting->getName()], 'AppBundle'
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

        $status  = JsonResponse::HTTP_OK;
        $comment = new GreetingComment();
        $comment->setGreeting($greeting);
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $comment->setUser($this->getUser());
        }

        $form = $this->createForm(GreetingCommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $greeting->addComment($comment);
                $em->persist($comment);
                $em->persist($greeting);
                $em->flush();

                return new JsonResponse([
                    'view' => $this->renderView('@App/guest/comments.html.twig', [
                        'greeting' => $greeting,
                        'form'     => $form->createView()
                    ]),
                    'data_comments' => $greeting->getId(),
                    'comments'      => $greeting->getComments()->count()
                ], $status);
            } else {
                $status = JsonResponse::HTTP_BAD_REQUEST;
            }
        }

        return new JsonResponse([
            'view' => $this->renderView('@App/guest/comments.html.twig', [
                'greeting' => $greeting,
                'form'     => $form->createView()
            ])
        ], $status);
    }
}
