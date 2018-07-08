<?php

namespace AppBundle\Controller;

use AdminBundle\Entity\Post;
use AdminBundle\Entity\PostComment;
use AppBundle\Form\Type\PostCommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{
    /**
     * @Route("/posts/{page}", requirements={"page" = "\d+"}, name="post_list")
     * @param Request $request
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request, $page = 1)
    {
        $em         = $this->getDoctrine()->getManager();
        $query      = $em->getRepository(Post::class)->getQueryAllPublished();
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $page /*page number*/,
            5 /*limit per page*/
        );

        // parameters to template
        return $this->render('@App/post/list.html.twig', array(
            'pagination' => $pagination
        ));
    }

    /**
     * @Route("/post/{id}/{page}", requirements={"id" = "\d+", "page" = "\d+"}, name="post")
     * @param Request $request
     * @param int $id
     * @param int $page     
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function postAction(Request $request, $id, $page = 1)
    {
        $em = $this->getDoctrine()->getManager();
        if (($post = $em->getRepository(Post::class)->find($id)) === null) {
            throw $this->createNotFoundException(sprintf('No found any post with ID %d', $id));
        }
        
        //Comments
        $query      = $em->getRepository(PostComment::class)->getQueryAllByPost($post);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $page /*page number*/,
            10 /*limit per page*/
        );        
        
        //AbstractComment form
        $comment = new PostComment();
        $comment->setPost($post);
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $comment->setUser($this->getUser());
        }

        $form = $this->createForm(PostCommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->addComment($comment);
            $em->persist($comment);
            $em->persist($post);
            $em->flush();

            $helper = $this->get('app.helper.flash_message');
            $this->addFlash('success', $helper->getFlashMessage(
                'success', 'frontend.success', 'frontend.post.comment.success', [], 'AppBundle'
            ));

            return $this->redirectToRoute('post', ['id' => $id, 'page' => $page]);
        }

        return $this->render("@App/post/post.html.twig", array(
            'post' => $post,
            'pagination' => $pagination,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/post/{id}/like", requirements={"id" = "\d+"}, name="post_like")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function likeAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if (($post = $em->getRepository(Post::class)->find($id)) === null) {
            throw $this->createNotFoundException(sprintf('No found any post with ID %d', $id));
        }

        $post->setLikes($post->getLikes() + 1);
        $em->persist($post);
        $em->flush();

        $helper = $this->get('app.helper.flash_message');
        $this->addFlash('success', $helper->getFlashMessage(
            'success',
            'frontend.success',
            'frontend.post.likes.success',
            ['%title%' => $post->getTitle()],
            'AppBundle'
        ));

        return $this->redirect($request->headers->get('referer'));
    }
}
