<?php

namespace SalonSolution\EmployeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SalonSolutionEmployeeBundle:Default:index.html.twig', array('name' => $name));
    }
}
