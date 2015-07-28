<?php

namespace SalonSolution\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SalonSolutionAdminBundle:Default:index.html.twig', array('name' => $name));
    }
}
