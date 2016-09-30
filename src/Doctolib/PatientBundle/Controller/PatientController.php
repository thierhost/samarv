<?php

namespace Doctolib\PatientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PatientController extends Controller
{
    public function indexAction(Request $request)
    {
        // on recuppere le user connecte ensuite on met dans la session toutes les infos conncernant le âtient
        $user = $this->getUser();
        if($user===null){
            throw new NotFoundHttpException('Utilisateur Inexistant');
        }else{
            $em = $this->getDoctrine()->getManager();
            $repo = $em->getRepository('DoctolibPatientBundle:Patient');
            $patient = $repo->findOneBy(array(
                'user' =>$user,
            ));
            $session = $request->getSession();
            $session->set('patient',$patient);

            return $this->render('DoctolibPatientBundle:Patient:index.html.twig');
        }
        

    }
}
