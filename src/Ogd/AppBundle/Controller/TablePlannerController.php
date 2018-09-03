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
//        $em     = $this->getDoctrine()->getManager();
//        $tables = $em->getRepository(Table::class)->findAll();
        $tables = [];

        return $this->render('@App/tables/list.html.twig', [
            'tables' => $tables]
        );
    }
}
