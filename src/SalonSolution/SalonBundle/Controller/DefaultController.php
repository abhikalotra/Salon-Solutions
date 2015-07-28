<?php

namespace SalonSolution\SalonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SalonSolutionSalonBundle:Default:index.html.twig', array('name' => $name));
    }
}
