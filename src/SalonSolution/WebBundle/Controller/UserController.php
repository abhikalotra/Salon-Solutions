<?php

	namespace SalonSolution\WebBundle\Controller;

	use Symfony\Component\HttpFoundation\Request;   
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;

	use SalonSolution\WebBundle\Entity\SalonsolutionsGlobalPaymentMethod;
	use SalonSolution\WebBundle\SalonsolutionsGlobalPaymentMethodType;  

	use SalonSolution\WebBundle\Entity\SalonsolutionsGlobalStatus;
	use SalonSolution\WebBundle\SalonsolutionsGlobalStatusType;  

	use SalonSolution\WebBundle\Entity\SalonsolutionsGlobalType;
	use SalonSolution\WebBundle\SalonsolutionsGlobalTypeType;  

	use SalonSolution\WebBundle\Entity\SalonsolutionsUser;            //SalonsolutionsUser
	use SalonSolution\WebBundle\SalonsolutionsUserType;  			  //SalonsolutionsUserType


	use Symfony\Component\HttpFoundation\Session\Session;


	use Symfony\Component\HttpFoundation\RedirectResponse;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;

	class UserController extends Controller 
	{
		/**************************** Begin : Function to display home page ********************************/
		public function indexAction()
		{
			return $this->render('SalonSolutionWebBundle:Home:index.html.twig');
		}
		/*------------------------------- End : Function to display home page ------------------------------*/
				
	
		/**************************** Begin : Function to register the customers ********************************/
	  
		public function registerCustomerAction()
		{
			
			return $this->render('SalonSolutionWebBundle:Home:user_register.html.twig');
		}
		/*------------------------------- End : Function to register the customers ------------------------------*/
		
		
		/**************************** Begin : Function to Login the customers ********************************/
	  
		public function loginAction()
		{
			echo "dsff";
			
			return $this->render('SalonSolutionSalonBundle:Page:login.html.twig');
		}
		/*------------------------------- End : Function to Login the customers ------------------------------*/
	
	}
