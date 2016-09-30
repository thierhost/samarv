<?php

namespace Doctolib\FrontBundle\Controller;

use Doctolib\MedecinBundle\Entity\Medecin;
use Doctolib\PatientBundle\Entity\Patient;
use Doctolib\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Tests\Encoder;

class FrontController extends Controller
{
    public function indexAction()
    {
        if ($this->get('security.context')->isGranted('ROLE_PATIENT')) {
            return $this->redirect($this->generateUrl('patient_homepage'),301);
        }
        if($this->get('security.context')->isGranted('ROLE_ADMIN')){
            return $this->redirect($this->generateUrl('doctolib_admin_homepage'),301);
        }
        if($this->get('security.context')->isGranted('ROLE_MEDECIN')){
            return $this->redirect($this->generateUrl('medecin_homepage'),301);
        }
        else{
            return $this->render('DoctolibFrontBundle:Front:index.html.twig');
        }

    }
    public function inscriptionMedecinAction(Request $request){
        $medecin = new Medecin();
        $form = $this->createForm('Doctolib\MedecinBundle\Form\MedecinType', $medecin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $medecin->getUser()->setSalt('');
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($medecin->getUser());
            $password_encoder = $encoder->encodePassword($medecin->getUser()->getPassword(), $medecin->getUser()->getSalt());
            $medecin->getUser()->setPassword($password_encoder);
            $medecin->getUser()->setRoles(array('ROLE_MEDECIN'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($medecin);
            $em->flush();
            // creation d'un flash bag pour une notification flash
            $request->getSession()->getFlashBag()->add('Notice', 'Inscription Réussie. Veuillez vous conneter maintenant !!!');
            // redirection maintenant vers la page de connexion
            $url = $this->generateUrl('login');
            // redirection permanente avec le status http 301 ::)))))
            return $this->redirect($url,301);

        }

        return $this->render('DoctolibFrontBundle:Front:inscriptionMedecin.html.twig', array(
            'form' => $form->createView(),
        ));
        

    }
    public function inscriptionAdminAction(Request $request){
        if($request->isMethod("POST")){
            // on enregsitre un new medecin
            $prenom = $request->request->get('prenom');
            $nom = $request->request->get('nom');
            $email = $request->request->get('_username');
            $password = $request->request->get('_password');
            $numtel = $request->request->get('numtel');
            $adresse = $request->request->get('adresse');
            // Objet User
            $user = new User();
            $user->setSalt('');
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $password_encoder = $encoder->encodePassword($password, $user->getSalt());
            $user->setPassword($password_encoder);
            $user->setUsername($email);
            $user->setRoles(array('ROLE_ADMIN'));
            // utilisation des setters
            $user->setNom($nom);
            $user->setPrenom($prenom);
            $user->setNumTel($numtel);
            $user->setAdresse($adresse);
            // recuperation de Entity manager
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            // creation d'un flash bag pour une notification flash
            $request->getSession()->getFlashBag()->add('Notice', 'Inscription Réussie. Veuillez vous conneter maintenant !!!');
            // redirection maintenant vers la page de connexion
            $url = $this->generateUrl('login');
            // redirection permanente avec le status http 301 ::)))))
            return $this->redirect($url,301);


        }else{
            // on lui renvoie le formulaire
            return $this->render('DoctolibFrontBundle:Front:inscriptionAdmin.html.twig');
        }
        
    }
    public function inscriptionPatientAction(Request $request){
        if($request->isMethod("POST")){
            // on enregsitre un new patient
            // recuperation des parametres du formaulaire
            $prenom = $request->request->get('prenom');
            $nom = $request->request->get('nom');
            $sexe = $request->request->get('sexe');
            $email = $request->request->get('_username');
            $password = $request->request->get('_password');
            $numtel = $request->request->get('numtel');
            $adresse = $request->request->get('adresse');
            // objet patient
            $patient = new Patient();
            // Objet User
            $user = new User();
            $user->setSalt('');
            // encodons le mot de passe du user
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $password_encoder = $encoder->encodePassword($password, $user->getSalt());
            $user->setPassword($password_encoder);
            $user->setUsername($email);
            $user->setRoles(array('ROLE_PATIENT'));
            // utilisation des setters
            $user->setNom($nom);
            $user->setPrenom($prenom);
            $user->setNumTel($numtel);
            $user->setAdresse($adresse);
            $patient->setSexe($sexe);
            $patient->setUser($user);
            // recuperation de Entity manager
            $em = $this->getDoctrine()->getManager();
            $em->persist($patient);
            $em->flush();
            // creation d'un flash bag pour une notification flash
            $request->getSession()->getFlashBag()->add('Notice', 'Inscription Réussie. Veuillez vous conneter maintenant !!!');
            // redirection maintenant vers la page de connexion
            $url = $this->generateUrl('login');
            // redirection permanente avec le status http 301 ::)))))
            return $this->redirect($url,301);


        }else{
            // on lui renvoie le formulaire
            return $this->render('DoctolibFrontBundle:Front:inscriptionPatient.html.twig');
        }

    }
}
