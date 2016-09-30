<?php

namespace Doctolib\AdminBundle\Controller;

use Doctolib\AdminBundle\Entity\Specialite;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SpecialiteController extends Controller
{
    public function listAction()
    {
        // liste des specialites
        $em = $this->getDoctrine()->getManager();
        $repos = $em->getRepository("DoctolibAdminBundle:Specialite");
        $specialites = $repos->findAll();
        if(empty($specialites)){
            return $this->render('DoctolibAdminBundle:Specialite:list.html.twig');
        }else{
            return $this->render('DoctolibAdminBundle:Specialite:list.html.twig',array(
                'specialites' =>$specialites
            ));
        }
    }

    public function addAction(Request $request){
        $specialite = new Specialite();
        $form = $this->createForm('Doctolib\AdminBundle\Form\SpecialiteType', $specialite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($specialite);
            $em->flush();
            // ajout dun flasbag
            $session = $request->getSession();
            $session->getFlashBag()->add('info', 'Spécialité ajoutée avec succés');
            // redirection
            $url = $this->generateUrl("doctolib_admin_list_specialite");
            return $this->redirect($url,301);
        }

        return $this->render('DoctolibAdminBundle:Specialite:add.html.twig', array(
            'form' => $form->createView(),
        ));

    }
    
    public function editAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $specialite = $em->getRepository('DoctolibAdminBundle:Specialite')->find($id);

        if (null === $specialite) {
            throw new NotFoundHttpException("Specialité inconnue");
        }

        $form = $this->createForm('Doctolib\AdminBundle\Form\SpecialiteType', $specialite);

        if ($form->handleRequest($request)->isValid()) {
            $em->flush();
            $request->getSession()->getFlashBag()->add('info', 'Spécialité modifiée avec succés');

            return $this->redirect($this->generateUrl('doctolib_admin_list_specialite'),301);
        }

        return $this->render('DoctolibAdminBundle:Specialite:edit.html.twig', array(
            'form'   => $form->createView(),
            'specialite' =>$specialite
        ));
        
    }

    public  function removeAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $specialite = $em->getRepository('DoctolibAdminBundle:Specialite')->find($id);

        if (null === $specialite) {
            throw new NotFoundHttpException("Specialité inconnue");
        }
        $em->remove($specialite);
        $em->flush();
        $request->getSession()->getFlashBag()->add('info', 'Spécialité supprimée avec succés');
        return $this->redirect($this->generateUrl('doctolib_admin_list_specialite'),301);

    }

    
}
