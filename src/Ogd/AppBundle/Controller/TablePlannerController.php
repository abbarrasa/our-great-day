<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AdminBundle\Entity\Table;

class TablePlannerController extends Controller
{
    /**
     * @Route("/tables", name="tables")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
//        $tables = $em->getRepository(Table::class)->findAll();
        $em         = $this->getDoctrine()->getManager();
        $query      = $em->getRepository(Table::class)->createQueryBuilder('t');
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $page /*page number*/,
            3 /*limit per page*/
        );
        
        return $this->render('@App/tables/list.html.twig', [
            'pagination' => $pagination
        ]);
    }
}
