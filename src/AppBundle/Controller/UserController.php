<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Route("/user/profile", name="user_profile")
     */
    public function indexAction(Request $request)
    {
        return $this->render('AppBundle:user:profile.html.twig');
    }

//<?php
//if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
//throw $this->createAccessDeniedException('No puedes acceder a esta pagina');
//}
//
//$user = $this->getUser();
//
//$form = $this->createForm(ProfileUserType::class, $user);
//$form->handleRequest($request);
//
//if ($request->isXmlHttpRequest()) {
//    return $this->render('FrontendMainBundle:usuario/common:formProfile.html.twig', array(
//        'form' => $form->createView(),
//    ));
//}
//
//if ($form->isSubmitted()) {
//    if ($form->isValid()) {
//        $userManager = $this->get('manager.user');
//        $plainPassword = $form->get('current_password')->getData();
//        $userManager->updatePassword($user, $plainPassword);
//        $changes = $userManager->getChangedUserFields();
//        $userManager->updateUser($user);
//        if (count($changes) > 0) {
//            /** @var $notesManager NotesManager */
//            $notesManager = $this->get('manager.notes');
//            $notesManager->insertChangesNote(
//                'El usuario ha editado sus datos. Estos son los anteriores:',
//                $changes['old'],
//                $user
//            );
//        }
//        // lanzo el evento que llamará al servicio de STR y hará control de errores
//        /* @var $dispatcher EventDispatcherInterface */
//        $dispatcher = $this->get('event_dispatcher');
//        $dispatcher->dispatch(Events::USER_UPDATE, new UserEvent($user, $request));
//
//        $this->addFlash(
//            'notice',
//            $this->get('translator')->trans('Se han actualizado sus datos correctamente')
//        );
//    } else {
//        $this->addFlash(
//            'error',
//            $this->get('translator')->trans('Existen errores en los datos')
//        );
//    }
//}
//
//return $this->render('FrontendMainBundle:usuario:misDatos.html.twig', array(
//    'form' => $form->createView(),
//));


}
