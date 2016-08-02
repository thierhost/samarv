<?php

namespace Doctolib\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DoctolibAdminBundle:Default:index.html.twig');
    }
}
