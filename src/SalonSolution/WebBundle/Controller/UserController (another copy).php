<?php

	namespace SalonSolution\WebBundle\Controller;
	
	
	use Symfony\Component\HttpFoundation\Request;   
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;

	
	use SalonSolution\WebBundle\Entity\SalonsolutionsUser;            //SalonsolutionsUser
	use SalonSolution\WebBundle\Resources\SalonsolutionsUserType;  			  //SalonsolutionsUserType
	
	use SalonSolution\WebBundle\Entity\SalonsolutionsSalon;            //SalonsolutionsSalon
	//use SalonSolution\WebBundle\Resources\SalonsolutionsSalonType;  			  //SalonsolutionsSalonType
	

	//use SalonSolution\WebBundle\Entity\SalonsolutionsGlobalType;
	//use Test\TestBundle\Resources\SalonsolutionsGlobalTypeType;  

	use Symfony\Component\HttpFoundation\Session\Session;
	use Symfony\Component\HttpFoundation\RedirectResponse;
	
	
	use Symfony\Component\HttpFoundation\File\UploadedFile;

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
	  
		public function registerCustomerAction(Request $request)
		{
			
				if ($request->getMethod() == 'POST') 
					{
						
						$firstName = $request->get("firstName");  	
						$lastName = $request->get("lastName");
						$email = $request->get("email");
						$username = $request->get("username");
						$password = $request->get("password");
						$salonName = $request->get("salonName");
						$domain = $request->get("domain");
						$address = $request->get("address");						
						$city = $request->get("city");
						$state = $request->get("state");
						$country = $request->get("country");
						$zip = $request->get("zip"); 
						$basePath = $this->getBasePathAction();							
						$photo = $_FILES['photo']['name'];  							
							$targetFilePhoto = $basePath."/".$this->container->getParameter('gbl_uploadPath_customers').$photo;
							move_uploaded_file($_FILES['photo']['tmp_name'], $targetFilePhoto);					
					
						$logo = $_FILES['logo']['name'];  							
							$targetFileLogo = $basePath."/".$this->container->getParameter('gbl_uploadPath_salons').$logo;
							move_uploaded_file($_FILES['logo']['tmp_name'], $targetFileLogo);					
					
						$customer = new SalonsolutionsUser();
						
						$customer->setFirstName($firstName);   
						$customer->setLastName($lastName);
						$customer->setEmail($email);
						$customer->setUsername($username);						
						$customer->setPassword(md5($password));
						/*$customer->setAddress($address);
						$customer->setCity($city);
						$customer->setState($state);
						$customer->setCountry($country);
						$customer->setZip($zip);*/
						$customer->setPhoto($photo);
						$customer->setType('2');
						$customer->setStatus('0');	
							
						$em = $this->getDoctrine()->getEntityManager();
						$em->persist($customer);
						$em->flush();
																					// next ---------> table insert
						$customerId = $customer->getId();
																														
						$salon = new SalonsolutionsSalon();
						
						$salon->setName($salonName);   
						$salon->setAddress($address);
						$salon->setDomain($domain);
						$salon->setCity($city);
						$salon->setState($state);
						$salon->setCountry($country);
						$salon->setZip($zip);
						$salon->setLogo($logo);
						$salon->setStatus('0');
						$salon->setOwnerId($customerId);
						
						$em = $this->getDoctrine()->getEntityManager();
						$em->persist($salon);
						$em->flush();
								
								
								
					}

					
					
				/*	if ($request->isMethod('POST')) {		
							
							$forms->bind($request);		     					 //   for entry data code process by this code
							
							$data = $forms->getData(); 	
															
							$password = $data->password;
							$data->password	= md5($password);	
							//echo "<pre>"; print_r($_SERVER);die;
							//echo $this->get('request')->getBaseUrl();die;
							$basePath = $this->getBasePathAction();
							$fileName = $_FILES['SalonsolutionsUser']['name']['photo'];
							$data->photo = $fileName;	
							$targetFile = $basePath."/".$this->container->getParameter('gbl_uploadPath_customers').$fileName;	
								
							move_uploaded_file($_FILES['SalonsolutionsUser']['tmp_name']['photo'], $targetFile);
					
											
																				 
								$em = $this->getDoctrine()->getEntityManager();
								$em->persist($data);
								$em->flush();     
									
								$lastinsertd= $data->getId();	 					// this is get the last inser id symfony method
								  
																						echo "<pre>"; print_r($lastinsertd); 	
									//$id = 	->set('userId', $salonid); 
							
							
							
						//	return $this->redirect($this->generateUrl('salon_solution_web_index'));
							} */
					
			
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
		
		
		public function getBasePathAction()
		{
			$basePath = $_SERVER['DOCUMENT_ROOT'].$this->get('request')->getBaseUrl();
			return $basePath;
		}
		
		public function getBaseUrlAction()
		{
			$baseUrl = "http://".$_SERVER['HTTP_HOST'].$this->get('request')->getBaseUrl();
			return $baseUrl;
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	}
