<?php

namespace Doctolib\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    public function indexAction()
    {
        return $this->render('DoctolibAdminBundle:Admin:index.html.twig');
    }
}
