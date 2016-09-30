<?php
/**
 * Created by PhpStorm.
 * User: Thierno Thiam
 * Date: 31/07/2016
 * Time: 02:46
 */
namespace Doctolib\UserBundle\Controller;

use Doctolib\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Tests\Encoder;

class SecurityController extends Controller
{
    public function loginAction(Request $request){
        $user = $this->getUser();
        if(null === $user){
            $authenticationUtils = $this->get('security.authentication_utils');
            return $this->render('DoctolibUserBundle:Security:index.html.twig', array(
                'last_username' => $authenticationUtils->getLastUsername(),
                'error'         => $authenticationUtils->getLastAuthenticationError(),
            ));
        }
        else{

            if($user->getRoles() == array('ROLE_PATIENT')){
                $url = $this->generateUrl('patient_homepage');
                return $this->redirect($url,301);
            }
            elseif($user->getRoles() == array('ROLE_ADMIN')){
                return $this->redirect($this->generateUrl('doctolib_admin_homepage',301));
            }
              //  return new Response("medecin");
            /*
            elseif($user->getRoles() == array('ROLE_MODERATEUR'))
                return $this->redirectToRoute('map_moderateur');*/
        }


    }

    /**
     * @param Request $request
     * @return Response
     */
    public function inscriptionPatientAction(Request $request){

        if($request->isMethod('POST')){
            // creation de l'objet user
            $user = new User();
            $user->setSalt('');
            // ici on sait que le formulaire à ete submit
            $username = $request->request->get("_username");
            $password = $request->request->get("_password");
            // encodons le mot de passe du user
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $password_encoder = $encoder->encodePassword($password, $user->getSalt());
            $user->setPassword($password_encoder);
            $user->setUsername($username);
            $user->setRoles(array('ROLE_PATIENT'));
            // recuperation de L'entity manager pour persiter notre objet
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            // creation d'un flash bag pour une notification flash
            $request->getSession()->getFlashBag()->add('Notice', 'Utilisateur bien enregistré!');
            // redirection maintenant vers la page de connexion
            $url = $this->generateUrl('login');
            // redirection permanente avec le status http 301 ::)))))
            return $this->redirect($url,301);
        }else{
            // ici on lui renvoi le formulaire d'inscription
            return $this->render("DoctolibUserBundle:Security:inscriptionpatient.html.twig");
        }
    }
}
