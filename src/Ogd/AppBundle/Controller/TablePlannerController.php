<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\SearchTableType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AdminBundle\Entity\Table;

class TablePlannerController extends Controller
{
    /**
     * @Route("/tables/{page}", requirements={"page" = "\d+"}, name="tables")
     * @param Request $request
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, $page = 1)
    {
        $name       = null;        
        $form       = $this->createForm(SearchTableType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $data   = $form->getData();
            $name   = $data['name'];
        }
        
        $em         = $this->getDoctrine()->getManager();
        $query      = $em->getRepository(Table::class)->getQueryBySeatName($name);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $page /*page number*/,
            3 /*limit per page*/
        );

        // parameters to template
        return $this->render('@App/tables/index.html.twig', array(
            'pagination' => $pagination,
            'form'       => $form->createView()
        ));
    }
}
