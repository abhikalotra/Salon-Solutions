<?php

namespace SalonSolution\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SalonSolutionWebBundle:Default:index.html.twig', array('name' => $name));
    }
}
