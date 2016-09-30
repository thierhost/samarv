<?php

namespace Doctolib\MedecinBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MedecinController extends Controller
{
    public function indexAction(Request $request)
    {
        // on recuppere le user connecte ensuite on met dans la session toutes les infos conncernant le medecin
        $user = $this->getUser();
        if($user===null){
            throw new NotFoundHttpException('Utilisateur Inexistant');
        }else{
            $em = $this->getDoctrine()->getManager();
            $repo = $em->getRepository('DoctolibMedecinBundle:Medecin');
            $medecin = $repo->findOneBy(array(
                'user' =>$user,
            ));
            $specialite =$medecin->getSpecialite()->getNom();
            $session = $request->getSession();
            $session->set('medecin',$medecin);
            $session->set('specialite',$specialite);
            return $this->render('DoctolibMedecinBundle:Medecin:index.html.twig');
        }

    }
    
    public function parametreAction(Request $request){
        return $this->render('DoctolibMedecinBundle:Medecin:parametre.html.twig');
    }
    
    public function editAction(Request $request){
        $user = $this->getUser();
        if($user===null){
            throw new NotFoundHttpException('Utilisateur Inexistant');
        }else {
            $em = $this->getDoctrine()->getManager();
            $repo = $em->getRepository('DoctolibMedecinBundle:Medecin');
            $medecin = $repo->findOneBy(array(
                'user' => $user,
            ));
            $form = $this->createForm('Doctolib\MedecinBundle\Form\MedecinType', $medecin);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $medecin->getUser()->setSalt('');
                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($medecin->getUser());
                $password_encoder = $encoder->encodePassword($medecin->getUser()->getPassword(), $medecin->getUser()->getSalt());
                $medecin->getUser()->setPassword($password_encoder);
                $medecin->getUser()->setRoles(array('ROLE_MEDECIN'));
                $em->flush();
                // creation d'un flash bag pour une notification flash
                $request->getSession()->getFlashBag()->add('Notice', 'Profile Modifié avec succés');
                // redirection
                $url = $this->generateUrl('medecin_parametre');
                // redirection permanente avec le status http 301 ::)))))
                return $this->redirect($url,301);

            }else{
                return $this->render('DoctolibMedecinBundle:Medecin:editProfile.html.twig', array(
                    'form' => $form->createView()
                ));
            }


        }
        
    }

    public function bioEditAction(Request $request){
        $user = $this->getUser();
        if($user===null){
            throw new NotFoundHttpException('Utilisateur Inexistant');
        }else {
            $em = $this->getDoctrine()->getManager();
            $repo = $em->getRepository('DoctolibMedecinBundle:Medecin');
            $medecin = $repo->findOneBy(array(
                'user' => $user,
            ));
            $form = $this->createForm('Doctolib\MedecinBundle\Form\MedecinBioEditType', $medecin);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $em->flush();
                // creation d'un flash bag pour une notification flash
                $request->getSession()->getFlashBag()->add('Notice', 'Bio Modifié avec succés');
                // redirection
                $url = $this->generateUrl('medecin_parametre');
                // redirection permanente avec le status http 301 ::)))))
                return $this->redirect($url,301);

            }else{
                return $this->render('DoctolibMedecinBundle:Medecin:bioedit.html.twig', array(
                    'form' => $form->createView()
                ));
            }


        }

    }
    public function cvEditAction(Request $request){
        $user = $this->getUser();
        if($user===null){
            throw new NotFoundHttpException('Utilisateur Inexistant');
        }else {
            $em = $this->getDoctrine()->getManager();
            $repo = $em->getRepository('DoctolibMedecinBundle:Medecin');
            $medecin = $repo->findOneBy(array(
                'user' => $user,
            ));
            $form = $this->createForm('Doctolib\MedecinBundle\Form\MedecinCvEditType', $medecin);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $em->flush();
                // creation d'un flash bag pour une notification flash
                $request->getSession()->getFlashBag()->add('Notice', 'Cv Modifié avec succés');
                // redirection
                $url = $this->generateUrl('medecin_parametre');
                // redirection permanente avec le status http 301 ::)))))
                return $this->redirect($url,301);

            }else{
                return $this->render('DoctolibMedecinBundle:Medecin:cvedit.html.twig', array(
                    'form' => $form->createView()
                ));
            }


        }

    }
}
