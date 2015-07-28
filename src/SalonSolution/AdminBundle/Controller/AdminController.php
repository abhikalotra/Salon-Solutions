<?php

	namespace SalonSolution\AdminBundle\Controller;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use SalonSolution\AdminBundle\Entity\SalonsolutionsUser;            //SalonsolutionsUser
	use SalonSolution\AdminBundle\Resources\SalonsolutionsUserType;  			  //SalonsolutionsUserType	
	use SalonSolution\AdminBundle\Entity\SalonsolutionsSalon;            //SalonsolutionsUser
	use SalonSolution\AdminBundle\Resources\SalonsolutionsSalonType;  			  //SalonsolutionsUserType	
	use SalonSolution\AdminBundle\Entity\SalonsolutionsCms;            //SalonsolutionsUser
	use SalonSolution\AdminBundle\Resources\SalonsolutionsCmsType;  			  //SalonsolutionsUserType
	use SalonSolution\AdminBundle\Resources\SalonsolutionsAppointment;  			  //SalonsolutionsAppointment
	use SalonSolution\AdminBundle\Entity\SalonsolutionsAdvertisement;  	        //SalonsolutionsAdvertisement	
	use SalonSolution\AdminBundle\Entity\SalonsolutionsService;  	
	use SalonSolution\AdminBundle\Entity\SalonsolutionsGlobalPaymentMethod;  
	
	
	use Symfony\Component\HttpFoundation\Request;   
	use Symfony\Component\HttpFoundation\Response;
	
	use Symfony\Component\HttpFoundation\Session\Session;
	use Symfony\Component\HttpFoundation\RedirectResponse;

	
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	
	use Symfony\Component\HttpFoundation\File\UploadedFile;



class AdminController extends Controller
{
	
    
    /**************************** Begin : Function to display login page ********************************/
		public function loginAction(Request $request)
		{
			$session = $this->getRequest()->getSession();
			
				$userSession = $this->getLoggedInUserDetailAction();    //function name given below 
																		 //check :- for enter dashoboard into the -path without  login then it will not show
				if($userSession)
					return $this->redirect($this->generateUrl('salon_solution_admin_dashboard'));   // check end
				
				     
			$em = $this->getDoctrine()->getEntityManager();
			$repository = $em->getRepository('SalonSolutionAdminBundle:SalonsolutionsUser');
			
					
				if ($request->getMethod() == 'POST')
				{
					
					$session->clear();
					$username = $request->get('username'); //echo $username;           // echo "<pre>"; print_r($fetch_data);
					$password = md5($request->get('password'));    						//	echo $password;  
				
					//find email, password type and status of admin
					$user = $repository->findOneBy(array('username' => $username, 'password' => $password,'type' =>'1','status' =>'1'));
					
					//$fUsername = $user->username;
				//	$fPassword = $user->password; 
					  // echo "<pre>"; print_r($fUsername); die;
					
					//if(($username == $fUsername) && ($password == $fPassword))
					if($user != '' )
					
					{										 
						 $session->set('userId', $user->getId());    	
						 $setid = $session->get('userId', $user->getId());   
							
						 $session->set('username', $user->getUsername());
						 $setname = $session->get('username');  
						 
						  $session->set('photo', $user->getPhoto());
						 $setphoto = $session->get('photo');  
						 
							return $this->redirect($this->generateUrl('salon_solution_admin_dashboard'));
					}	
					
						
					else
					{	
										
					$this->get('session')->getFlashBag()->set('error', 'Invalid Login Details');	
					 
					}
							
				} 
				
				
							
			
			return $this->render('SalonSolutionAdminBundle:Page:login.html.twig');
		}
		
		
		/*------------------------------- End : Function to display login page ------------------------------*/
			
		
			
		/**************************** Begin : Function to Login the customers ********************************/
	  
		public function logoutAction()
		{
			
			 $session = $this->getRequest()->getSession();
					$session->clear('foo');
					$session->remove('foo');
					unset($session);
						return $this->redirect($this->generateUrl('salon_solution_admin_login'));
			
			return $this->render('SalonSolutionAdminBundle:Page:logout.html.twig');
		}
		/*------------------------------- End : Function to Login the customers ------------------------------*/
		
		
	/*************************************************forgot password ****************************/
	  
		public function recoverPasswordAction( Request $request)
		{
			
		
			 $email=$this->get('request')->request->get('email');
			$em = $this->getDoctrine()->getEntityManager();
    		$repository = $em->getRepository('SalonSolutionAdminBundle:SalonsolutionsUser');
    		
    		if ($request->getMethod() == 'POST') 
        	{
           		 $user = $repository->findOneBy(array('email' => $email));
           		
           		//echo "<pre>"; print_r($email); die;
            		if ($user) 
            		{   				
					
						$newPassword = $this->generateRandomString();   
						//echo $newPassword;
						$encPass=md5($newPassword);
						$realtors = $em->createQueryBuilder()
						->select('SalonsolutionsUser')
						->update('SalonSolutionAdminBundle:SalonsolutionsUser',  'SalonsolutionsUser')
						->set('SalonsolutionsUser.password', ':password')
						->setParameter('password', $encPass)
						->where('SalonsolutionsUser.email=:email')
						->setParameter('email', $email)
						->getQuery()
						->getResult();
								
						//password is encrypted into md5 
						$encPass=md5($newPassword); 
						$to = $email;
						$subject = "Password Reset";
						$txt=   "Hello <br><br>Your password has been reset  <br><br>Your new Password is: <b>".$newPassword."</b>";
						//$headers = "From: webmaster@example.com" . "\r\n" ;
						$headers = "MIME-Version: 1.0" . "\r\n";
						$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
						$headers .= "From: <adminSupport@salonSolution.com>" . "\r\n";
						 $retval = mail($to,$subject,$txt,$headers); //send mail      					 
						   if( $retval == true )
						   {
							   
								$this->get('session')->getFlashBag()->set('mailSent', 'Your Password has been Sent into your Email : Please check your Inbox / Span Box');	 
								
							 	return $this->redirect($this->generateUrl('salon_solution_admin_login'));
							  
						   }
						   else
						   {
								return $this->redirect($this->generateUrl('salon_solution_admin_login'));
							  echo "Message could not be sent...";
						   }		   
											 
					} 
				
		
			}
			return $this->render('SalonSolutionAdminBundle:Page:login.html.twig');	
			
		
		}
			function generateRandomString($length = 10) {
				return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
			}
		/*------------------------------- End : Function to Recovery  the customers ------------------------------*/
		
		
		/**************************** Begin : Function to display home page ********************************/
		public function dashboardAction()
		{
			$session = $this->getRequest()->getSession(); 	
			
			/* ****** BELL Notification  start****  last  consumer enter show BELL */
			
			//$repository = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsUser');
			//$consumers = $repository->findBy(array('type' => '3'));
			//$totalConsumers = count($consumers);
				/* ****** BELL Notification  End ****  last  consumer enter show BELL */
			
			
			
			$userSession = $this->getLoggedInUserDetailAction();         //function name given below 
			$session = $this->getRequest()->getSession(); 	
			
			if($session->get('userId') && $session->get('userId') != '')					
				$userId = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_admin_login') );

			
			$userId = $session->get('userId');											 //check :- for enter dashoboard into the -path without  login then it will not show
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsUser');
			$customers = $repository->findBy(array('type' => '2'));		
			$totalCustomers = count($customers);
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsUser');
			$consumers = $repository->findBy(array('type' => '3'));
			$totalConsumers = count($consumers);
			
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsSalon');
			$salons = $repository->findAll();
			$totalSalons = count($salons);
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsService');
			$Services = $repository->findAll();
			$totalServices = count($Services);

			$repository = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsAppointment');
			$Appointments = $repository->findAll();
			$totalAppointments = count($Appointments);
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsPayment');
			$payments = $repository->findAll();
			$totalPayments = count($payments);
								
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsAdvertisement');
			$Advertisements = $repository->findAll();
			$totalAdvertisements = count($Advertisements);
		
			if($userSession)
			{
				return $this->render('SalonSolutionAdminBundle:Page:dashboard.html.twig',
					array(
						'totalCustomers'=> $totalCustomers,
						'totalConsumers'=> $totalConsumers,
						'totalSalons'=> $totalSalons, 
						'totalServices'=> $totalServices,
						'totalAppointments'=> $totalAppointments,
						'totalPayments'=> $totalPayments, 
						'totalAdvertisements'=> $totalAdvertisements
					)
				);
			}
			else
				return $this->redirect($this->generateUrl('salon_solution_admin_login'));   // check end
		}
		/*------------------------------- End : Function to display home page ------------------------------*/
		
		
		/**************************** Begin : Function to get the details of logged-in user ********************************/
		public function getLoggedInUserDetailAction()
		{
			 $session = $this->getRequest()->getSession();                     //check :- for enter dashoboard into the -
																				// path without  login then it will not show
			if( $session->get('userId') && $session->get('userId') != '' )
				return true;
			else
				return false;
		}
		/*------------------------------- End : Function to get the details of logged-in user ------------------------------*/
		
		
		/**************************** Begin : Function to display manage user ********************************/
		public function manageCustomersAction()
		{
			
			//$customers  = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsUser')->findAll();
				$repository = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsUser');
			  $customers = $repository->findBy(array('type' => '2'));
			  
			  	// echo "<pre>"; print_r($customers); die;
			
			return $this->render('SalonSolutionAdminBundle:Page:manage_customer.html.twig',array('customers'=> $customers));
		}
		/*------------------------------- End : Function to display manage user ------------------------------*/
		
		/**************************** Begin : Function to View Customer ********************************/
	  
		public function viewCustomerAction($id, Request $request)
		{
		
			$repository = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsUser');
			  $customerDetails = $repository->findBy(array('id' =>  $id));			
				
				//echo "<pre>"; print_r($customerDetails); die;
			
			return $this->render('SalonSolutionAdminBundle:Page:view_customer.html.twig',array('customerDetails'=> $customerDetails));

			
		}
		/*------------------------------- End : Function to View Customer ------------------------------*/
		
		
		/**************************** Begin : Function to Add Customers ********************************/
		public function addCustomersAction(Request $request)
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
						$mobile = $request->get("mobile"); 
						$landline = $request->get("landline"); 
						$basePath = $this->getBasePathAction();	  
						$photo = $_FILES['photo']['name'];  	
							$ranPhotoUpload = rand(1,10000);  		
							$targetFilePhoto = $basePath."/".$this->container->getParameter('gbl_uploadPath_customers').$ranPhotoUpload.$photo;
							move_uploaded_file($_FILES['photo']['tmp_name'], $targetFilePhoto);					
					
						 $logo = $_FILES['logo']['name'];  	
						 $ranPhotoLogo = rand(1,10000);  							
						$targetFileLogo = $basePath."/".$this->container->getParameter('gbl_uploadPath_salons').$ranPhotoLogo.$logo;
						move_uploaded_file($_FILES['logo']['tmp_name'], $targetFileLogo);					
					
			
						$customer = new SalonsolutionsUser();
						
						$customer->setFirstName($firstName);   
						$customer->setLastName($lastName);
						$customer->setEmail($email);
						$customer->setUsername($username);						
						$customer->setPassword(md5($password));	
						$customer->setAddress($address);
						$customer->setCity($city);
						$customer->setState($state);
						$customer->setCountry($country);
						$customer->setZip($zip);						
						$customer->setMobile($mobile);						
						$customer->setLandline($landline);							
						$customer->setPhoto($ranPhotoUpload.$photo);
						$customer->setType('2');
						$customer->setStatus('1');	
							
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
						$salon->setMobile($mobile);						
						$salon->setLandline($landline);		
						$salon->setLogo($ranPhotoLogo.$logo);
						$salon->setStatus('1');
						$salon->setOwnerId($customerId);
												
						$em = $this->getDoctrine()->getEntityManager();
						$em->persist($salon);
						$em->flush(); 
						return $this->redirect($this->generateUrl('salon_solution_admin_manageCustomers'));  // redirect the page
			
					} 
			  	// echo "<pre>"; print_r($customers); die;
			
			return $this->render('SalonSolutionAdminBundle:Page:add_customer.html.twig');
		}			
																				// base path define
		
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
		/*------------------------------- End : Function to Add Customers ------------------------------*/
		
		
		
	
		
		/**************************** Begin : Function to edit manage user ********************************/
		public function editManageCustomersAction($id, Request $request)
		{
			
			
					$em = $this->getDoctrine()->getEntityManager();
				$testimonial = $em->getRepository('SalonSolutionAdminBundle:SalonsolutionsUser')->find($id);
			
				$form = $this->createForm(new SalonsolutionsUserType(), $testimonial);						
				$request = $this->get('request');
					
					if ($request->getMethod() == 'POST') {
						$form->bind($request);

						$testimonial->getFirstName();					    // go to entity name of set firstname , put it setname	
						$em->persist($testimonial);
						$em->flush();					
							return $this->redirect($this->generateUrl('salon_solution_admin_manageCustomers'));
						
					}

																				 //echo "<pre>"; print_r($deleteManageCoustomer); die;
			
			return $this->render('SalonSolutionAdminBundle:Page:edit_manage_customer.html.twig', array('form' => $form->createView()));
		}
		/*------------------------------- End : Function to edit manage user ------------------------------*/
		
	
		
		/**************************** Begin : Function to delete manage user ********************************/
		public function deleteManageCustomersAction($id)
		{
			
				$em = $this->getDoctrine()->getEntityManager();
					$del = $em->getRepository('SalonSolutionAdminBundle:SalonsolutionsUser')->find($id);				
			
				if ($del) {
						$em->remove($del);
						$em->flush();
							return $this->redirect($this->generateUrl('salon_solution_admin_manageCustomers'));  // redirect the page
						}
																				 //echo "<pre>"; print_r($deleteManageCoustomer); die;
			
			return $this->render('SalonSolutionAdminBundle:Page:delete_manage_coustumer.html.twig');
		}
		/*------------------------------- End : Function to delete manage user ------------------------------*/
		
	
		
		/**************************** Begin : Function to change Status User ********************************/
		public function changeStatusAction()
		{
			$statusHtml = '';
			$em = $this->getDoctrine()->getEntityManager();
			
			if( isset($_POST['currentStatus']) && $_POST['currentStatus'] == 0 )
			{
				$status = 1;	
				$statusString = 'Active';
			}
			else
			{
				$status = 0;
				$statusString = 'Inactive';
			}
				
			$id = $_POST['id'];
			
			if( isset($_POST['objectType']) && $_POST['objectType'] == 'User' )
			{
				$em = $this->getDoctrine()->getEntityManager();
				$confirmedSubscribe = $em->createQueryBuilder() 
				->select('reg')
				->update('SalonSolutionAdminBundle:SalonsolutionsUser',  'reg')
				->set('reg.status', ':status')
				->setParameter('status', $status)
				->where('reg.id = :id')
				->setParameter('id', $id)
				->getQuery()
				->getResult();
			}
			$statusHtml.='<a id="status-'.$id.'" class="edit" title="Click to Change" onclick="javascript:changeStatus(\'status-'.$id.'\','.$status.');">'.$statusString.'</a>'; 
			
			return new response($statusHtml);				
		}
		/*------------------------------- End : Function to change Status User ------------------------------*/
		
		
		/**************************** Begin : Function to display manage Consumer ********************************/
		public function manageConsumersAction()
		{
			
				$repository = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsUser');
			  $Consumers = $repository->findBy(array('type' => '3'));			
			return $this->render('SalonSolutionAdminBundle:Page:manage_consumer.html.twig',array('Consumers'=> $Consumers));
			
		}
		/*------------------------------- End : Function to display manage Coustumer ------------------------------*/
		
		
		/**************************** Begin : Function to View Consumer ********************************/
	  
		public function viewConsumerAction($id, Request $request)
		{
		
			$repository = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsUser');
			  $consumerDetails = $repository->findBy(array('id' =>  $id));			
				
				//echo "<pre>"; print_r($customerDetails); die;
			
			return $this->render('SalonSolutionAdminBundle:Page:view_consumer.html.twig',array('consumerDetails'=> $consumerDetails));

			
		}
		/*------------------------------- End : Function to View Consumer ------------------------------*/
		
		
		
		
			/**************************** Begin : Function to Add Customers ********************************/
		public function addConsumerAction(Request $request)
		{
			
					
		/*	$em = $this->getDoctrine()->getEntityManager();
			$salonName = $em->createQueryBuilder() 
			->select('SalonsolutionsUser')
			->from('SalonSolutionAdminBundle:SalonsolutionsUser',  'SalonsolutionsUser')
			->getQuery()
			->getResult();
		*/
		
			$em = $this->getDoctrine()->getEntityManager();
			$addConsumer = $em->createQueryBuilder()
				->select('SalonsolutionsUser','SalonsolutionsSalon.id','SalonsolutionsSalon.name')
				->from('SalonSolutionAdminBundle:SalonsolutionsUser', 'SalonsolutionsUser')
				->leftJoin('SalonSolutionAdminBundle:SalonsolutionsSalon', 'SalonsolutionsSalon', "WITH", "SalonsolutionsSalon.ownerId=SalonsolutionsUser.id")
				->where('SalonsolutionsUser.type = :type')
				->setParameter('type', 2)											
				->andWhere('SalonsolutionsUser.status = :status')
				->setParameter('status', 1)											
				->getQuery()
				->getArrayResult();
			
			// echo "<pre>"; print_r($addConsumer); die;
			
			$salonName = array();

				foreach($addConsumer as $consumer)
				{
				$salonName[$consumer[0]['id']] = $consumer[0]; 
				$salonName[$consumer[0]['id']]['ownerId'] = $consumer['id']; 
				$salonName[$consumer[0]['id']]['name'] = $consumer['name']; 
				}

				//echo "<pre>"; print_r($salonName); die;

			if ($request->getMethod() == 'POST') 
					{						
						$firstName = $request->get("firstName");  	 	//echo "<pre>"; print_r($firstName); die; 
						$lastName = $request->get("lastName");
						$email = $request->get("email");
						$username = $request->get("username");
						$password = $request->get("password");
						$address = $request->get("address");						
						$city = $request->get("city");
						$state = $request->get("state");
						$country = $request->get("country");
						$zip = $request->get("zip"); 
						$mobile = $request->get("mobile"); 
						$landline = $request->get("landline"); 
					 	$parent_id = $request->get("parent_id");  
						$basePath = $this->getBasePathAction();	  
						$photo = $_FILES['photo']['name'];  	
							$ranPhotoUpload = rand(1,10000);  		
							$targetFilePhoto = $basePath."/".$this->container->getParameter('gbl_uploadPath_consumers').$ranPhotoUpload.$photo;            //getBasePathAction() defined into upper 
							move_uploaded_file($_FILES['photo']['tmp_name'], $targetFilePhoto);					
					
			 
					 $repository = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsSalon');
					 $getSalonId = $repository->findBy(array('id' =>  $parent_id));
				   	$getOwnerId =  $getSalonId[0]->ownerId; 
					 
						$customer = new SalonsolutionsUser();
						
						$customer->setFirstName($firstName);   
						$customer->setLastName($lastName);
						$customer->setEmail($email);
						$customer->setUsername($username);						
						$customer->setPassword(md5($password));						
						$customer->setPhoto($ranPhotoUpload.$photo);
						$customer->setAddress($address);
						$customer->setCity($city);
						$customer->setState($state);
						$customer->setCountry($country);
						$customer->setZip($zip);
						$customer->setMobile($mobile);						
						$customer->setLandline($landline);	
						$customer->setParentId($parent_id);
						$customer->setSalonId($parent_id);
						$customer->setSalonOwnerId($getOwnerId);
						$customer->setType('3');
						$customer->setStatus('1');	
							
						$em = $this->getDoctrine()->getEntityManager();
					   $em->persist($customer);
						$em->flush();
						
						return $this->redirect($this->generateUrl('salon_solution_admin_manageConsumers'));  //
																					// next ---------> table insert
					
					/*	$customerId = $customer->getId();																				
						$salon = new SalonsolutionsSalon();						
						$salon->setName($salonName);   
						$salon->setAddress($address);
						$salon->setDomain($domain);
						$salon->setCity($city);
						$salon->setState($state);
						$salon->setCountry($country);
						$salon->setZip($zip);
						$salon->setLogo($ranPhotoLogo.$logo);
						$salon->setStatus('0');
						$salon->setOwnerId($customerId);
												
						$em = $this->getDoctrine()->getEntityManager();
						$em->persist($salon);
						$em->flush(); 
					
						return $this->redirect($this->generateUrl('salon_solution_admin_manageConsumers'));  // redirect the page
			*/
					} 
		
		
			  	// echo "<pre>"; print_r($customers); die;
			
			return $this->render('SalonSolutionAdminBundle:Page:add_consumer.html.twig', array('salonName' => $salonName));
		}			
		
		
		
		
	
		
		/**************************** Begin : Function to  Edit Consumer ********************************/
		public function editManageConsumerAction($id, Request $request)
		{	
			/*  												//abhi18june 
			$em = $this->getDoctrine()->getEntityManager();
			$salonName = $em->createQueryBuilder() 
			->select('SalonsolutionsSalon')
			->from('SalonSolutionAdminBundle:SalonsolutionsSalon',  'SalonsolutionsSalon')
			->getQuery() 
			->getResult();
			* 
			*/
			//echo "<pre>"; print_r($id); die;
			
			$em = $this->getDoctrine()->getEntityManager();
			$editConsumer = $em->createQueryBuilder()
				->select('SalonsolutionsUser','SalonsolutionsSalon.id','SalonsolutionsSalon.name')
				->from('SalonSolutionAdminBundle:SalonsolutionsUser', 'SalonsolutionsUser')
				->leftJoin('SalonSolutionAdminBundle:SalonsolutionsSalon', 'SalonsolutionsSalon', "WITH", "SalonsolutionsSalon.ownerId=SalonsolutionsUser.id")
				->where('SalonsolutionsUser.type = :type')
				->setParameter('type', 2)											
				->andWhere('SalonsolutionsUser.status = :status')
				->setParameter('status', 1)											
				->getQuery()
				->getArrayResult();
			
				
			$salonName = array();

				foreach($editConsumer as $consumer)
				{
				$salonName[$consumer[0]['id']] = $consumer[0]; 
				$salonName[$consumer[0]['id']]['ownerId'] = $consumer['id']; 
				$salonName[$consumer[0]['id']]['name'] = $consumer['name']; 
				}
			
				//echo "<pre>"; print_r($salonName); die;
			
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsUser');
			 $updateConsumerInfo = $repository->findBy(array('id' =>  $id));			
				//	echo "<pre>"; print_r($updateConsumerInfo); die;
			
			if($request->getMethod() == 'POST')
			{				
				$firstName = $request->get("firstName");  	 	//echo "<pre>"; print_r($firstName); die; 
				$lastName = $request->get("lastName");
				$email = $request->get("email");
				$username = $request->get("username");
				$address = $request->get("address");
				$country = $request->get("country");
				$state = $request->get("state");
				$city = $request->get("city");
				$zip = $request->get("zip");
				$parentId = $request->get("parentId");
				
		
				$em = $this->getDoctrine()->getEntityManager();
				$confirmedSubscribe = $em->createQueryBuilder() 
				->select('tblConsumer')
				->update('SalonSolutionAdminBundle:SalonsolutionsUser',  'tblConsumer')
				->set('tblConsumer.firstName', ':firstName')
				->setParameter('firstName', $firstName)
				->set('tblConsumer.lastName', ':lastName')
				->setParameter('lastName', $lastName)
				->set('tblConsumer.username', ':username')
				->setParameter('username', $username)				
				->set('tblConsumer.address', ':address')
				->setParameter('address', $address)
				->set('tblConsumer.country', ':country')
				->setParameter('country', $country)
				->set('tblConsumer.state', ':state')
				->setParameter('state', $state)
				->set('tblConsumer.city', ':city')
				->setParameter('city', $city)
				->set('tblConsumer.zip', ':zip')
				->setParameter('zip', $zip)
				->set('tblConsumer.parentId', ':parentId')
				->setParameter('parentId', $parentId)
				->where('tblConsumer	.id = :id')
				->setParameter('id', $id)
				->getQuery()
				->getResult();
								
				return $this->redirect($this->generateUrl('salon_solution_admin_manageConsumers'));
				
			}					
			/*	$em = $this->getDoctrine()->getEntityManager();
				$testimonialConsumer = $em->getRepository('SalonSolutionAdminBundle:SalonsolutionsUser')->find($id);
			
				$formConsumer = $this->createForm(new SalonsolutionsUserType(), $testimonialConsumer);						
				$request = $this->get('request');
					
					if ($request->getMethod() == 'POST') {
						$formConsumer->bind($request);

						$testimonialConsumer->getFirstName();					    // go to entity name of set firstname , put it setname	
						$em->persist($testimonialConsumer);
						$em->flush();					
							return $this->redirect($this->generateUrl('salon_solution_admin_manageConsumer'));
					}
					*/
					
				return $this->render('SalonSolutionAdminBundle:Page:edit_manage_consumer.html.twig', array('updateConsumerInfo' => $updateConsumerInfo, 'salonName' => $salonName));
		
		}
		/*------------------------------- End : Function to  Edit Consumer ------------------------------*/
		
		
		/**************************** Begin : Function to  Delete Consumer ********************************/
		public function deleteManageConsumerAction($id)
		{		
			
			$em = $this->getDoctrine()->getEntityManager();
			$delConsumer = $em->getRepository('SalonSolutionAdminBundle:SalonsolutionsUser')->find($id);				
			
			if ($delConsumer)
			{
				$em->remove($delConsumer);
				$em->flush();
					return $this->redirect($this->generateUrl('salon_solution_admin_manageConsumers'));  // redirect the page
			}
																			 //echo "<pre>"; print_r($deleteManageCoustomer); die;
			
			return $this->render('SalonSolutionAdminBundle:Page:delete_manage_consumer.html.twig');
	
		}
		/*------------------------------- End : Function to  Edit Consumer ------------------------------*/
		
		
		
		
		
		/**************************** Begin : Function to display manage Salon ********************************/
		public function manageSalonsAction()
		{
		
			$repository = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsSalon');
			  $Salons = $repository->findAll();			
		
			return $this->render('SalonSolutionAdminBundle:Page:manage_salon.html.twig',array('Salons'=> $Salons));
			
		}
								//--- Begin : Function to  Change Status Salon  ------------------------------*/
					public function changeStatusSalonsAction()
					{
						$statusHtml = '';
						$em = $this->getDoctrine()->getEntityManager();
						
						if( isset($_POST['currentStatus']) && $_POST['currentStatus'] == 0 )
						{
							$status = 1;	
							$statusString = 'Active';
						}
						else
						{
							$status = 0;
							$statusString = 'Inactive';
						}
							
						 $id = $_POST['id'];
						
						if( isset($_POST['objectType']) && $_POST['objectType'] == 'Salon' )
						{
							$em = $this->getDoctrine()->getEntityManager();
							$confirmedSubscribe = $em->createQueryBuilder() 
							->select('ser')
							->update('SalonSolutionAdminBundle:SalonsolutionsSalon',  'ser')
							->set('ser.status', ':status')
							->setParameter('status', $status)
							->where('ser.id = :id')
							->setParameter('id', $id)
							->getQuery()
							->getResult();
						}
						$statusHtml.='<a id="status-'.$id.'" class="edit" title="Click to Change" onclick="javascript:changeStatusSalons(\'status-'.$id.'\','.$status.');">'.$statusString.'</a>'; 
						
						return new response($statusHtml);				
					}
					//--- end : Function to  Change Status Salon  ------------------------------*/
					
		/*------------------------------- End : Function to display manage Salon ------------------------------*/
			
		/**************************** Begin : Function to View Salon ********************************/
	  
		public function viewSalonAction($id, Request $request)
		{
		
			$repository = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsSalon');
			  $salonDetails = $repository->findBy(array('id' =>  $id));			
				
				//echo "<pre>"; print_r($salonDetails); die;
			
			return $this->render('SalonSolutionAdminBundle:Page:view_salon.html.twig',array('salonDetails'=> $salonDetails));

			
		}
		/*------------------------------- End : Function to View Salon ------------------------------*/
		
		
		
		
		
		/**************************** Begin : Function to Add Salon ********************************/
			
		
		public function addSalonAction(Request $request)
		{
			$em = $this->getDoctrine()->getEntityManager();
			$salonOwners = $em->createQueryBuilder() 
			->select('SalonsolutionsUser')
			->from('SalonSolutionAdminBundle:SalonsolutionsUser',  'SalonsolutionsUser')
			->where('SalonsolutionsUser.type = :type')
			->setParameter('type', 2)
			->getQuery()
			->getResult();
			// echo "<pre>"; print_r($salonOwners); die;

					
			if ($request->getMethod() == 'POST') 
					{						
						$name = $request->get("name");  	
						$domain = $request->get("domain");
						$displayName = $request->get("displayName");
						$description = $request->get("description");	
						$address = $request->get("address");						
						$city = $request->get("city");
						$state = $request->get("state");
						$country = $request->get("country");
						$zip = $request->get("zip");  
						$mobile = $request->get("mobile"); 
						$landline = $request->get("landline"); 
						$owner_id = $request->get("owner_id"); 
						$basePath = $this->getBasePathAction();	 
					
						$logo = $_FILES['logo']['name'];  	
							$ranPhotoLogo = rand(1,10000);  							
							$targetFileLogo = $basePath."/".$this->container->getParameter('gbl_uploadPath_salons').$ranPhotoLogo.$logo;
							move_uploaded_file($_FILES['logo']['tmp_name'], $targetFileLogo);					
					
							
			
						$customer = new SalonsolutionsSalon();
						
						$customer->setName($name);   
						$customer->setDomain($domain);
						$customer->setDisplayName($displayName);
						$customer->setDescription($description);
						$customer->setAddress($address);							
						$customer->setCity($city);							
						$customer->setState($state);							
						$customer->setCountry($country);							
						$customer->setZip($zip);							
						$customer->setMobile($mobile);							
						$customer->setLandline($landline);							
						$customer->setOwnerId($owner_id);							
						$customer->setLogo($ranPhotoLogo.$logo);
						
						$customer->setStatus('1');	
							
						$em = $this->getDoctrine()->getEntityManager();
						$em->persist($customer);
						$em->flush();
																					// next ---------> table insert
					
					
						return $this->redirect($this->generateUrl('salon_solution_admin_manageSalons'));  // redirect the page
				
			
					} 
				
		
			  	// echo "<pre>"; print_r($customers); die;
			
			return $this->render('SalonSolutionAdminBundle:Page:add_salon.html.twig', array('salonOwners' => $salonOwners));
		}			
		
		/*------------------------------- End : Function to Add Salon ------------------------------*/
		
		
		
		
		/**************************** Begin : Function to  Edit Salon ********************************/
		public function editManageSalonAction($id, Request $request)
		{			
			
			$em = $this->getDoctrine()->getEntityManager();
			$salonOwners = $em->createQueryBuilder() 
			->select('SalonsolutionsUser')
			->from('SalonSolutionAdminBundle:SalonsolutionsUser',  'SalonsolutionsUser')
			->where('SalonsolutionsUser.type = :type')
			->setParameter('type', 2)
			->getQuery()
			->getResult();
			// echo "<pre>"; print_r($salonOwners); die;

			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsSalon');
			 $updateSalonInfo = $repository->findBy(array('id' =>  $id));			
					//echo "<pre>"; print_r($updateSalonInfo); die;
			
			if($request->getMethod() == 'POST')
			{				
				$name = $request->get("name");  	
				$domain = $request->get("domain");
				$displayName = $request->get("displayName");
				$description = $request->get("description");	
				$address = $request->get("address");						
				$city = $request->get("city");
				$state = $request->get("state");
				$country = $request->get("country");
				$zip = $request->get("zip");  
				$mobile = $request->get("mobile"); 
				$landline = $request->get("landline"); 
				$ownerId = $request->get("ownerId"); 
				$basePath = $this->getBasePathAction();	 			
					$logo = $_FILES['logo']['name'];  	
					$ranPhotoLogo = rand(1,10000);  							
					$targetFileLogo = $basePath."/".$this->container->getParameter('gbl_uploadPath_salons').$ranPhotoLogo.$logo;
					move_uploaded_file($_FILES['logo']['tmp_name'], $targetFileLogo);					

		
				$em = $this->getDoctrine()->getEntityManager();
				$confirmedSubscribe = $em->createQueryBuilder() 
				->select('tblSalon')
				->update('SalonSolutionAdminBundle:SalonsolutionsSalon',  'tblSalon')
				->set('tblSalon.name', ':name')
				->setParameter('name', $name)
				->set('tblSalon.domain', ':domain')
				->setParameter('domain', $domain)
				->set('tblSalon.displayName', ':displayName')
				->setParameter('displayName', $displayName)
				->set('tblSalon.description', ':description')
				->setParameter('description', $description)				
				->set('tblSalon.address', ':address')
				->setParameter('address', $address)
				->set('tblSalon.country', ':country')
				->setParameter('country', $country)
				->set('tblSalon.state', ':state')
				->setParameter('state', $state)
				->set('tblSalon.city', ':city')
				->setParameter('city', $city)
				->set('tblSalon.zip', ':zip')
				->setParameter('zip', $zip)
				->set('tblSalon.mobile', ':mobile')
				->setParameter('mobile', $mobile)
				->set('tblSalon.landline', ':landline')
				->setParameter('landline', $landline)
				->set('tblSalon.ownerId', ':ownerId')
				->setParameter('ownerId', $ownerId)
				->where('tblSalon	.id = :id')
				->setParameter('id', $id)
				->getQuery()
				->getResult();
				
				
				
								
				return $this->redirect($this->generateUrl('salon_solution_admin_manageSalons'));
				
			}
			
			
			/*
				$em = $this->getDoctrine()->getEntityManager();
				$testimonialSalon = $em->getRepository('SalonSolutionAdminBundle:SalonsolutionsSalon')->find($id);
			
				$formSalon = $this->createForm(new SalonsolutionsSalonType(), $testimonialSalon);						
				$request = $this->get('request');
					
					if ($request->getMethod() == 'POST') {
						$formSalon->bind($request);

						$testimonialSalon->getName();					    // go to entity name of set firstname , put it setname	
						$em->persist($testimonialSalon);
						$em->flush();					
							return $this->redirect($this->generateUrl('salon_solution_admin_manageSalon'));
					}
				*/
				return $this->render('SalonSolutionAdminBundle:Page:edit_manage_salon.html.twig', array('salonOwners' => $salonOwners,'updateSalonInfo' => $updateSalonInfo ));
		
		}
		/*------------------------------- End : Function to  Edit Salon ------------------------------*/
		
	
		/**************************** Begin : Function to  Delete Salon ********************************/
		public function deleteManageSalonAction($id)
		{		
			
			$em = $this->getDoctrine()->getEntityManager();
			$delSalon = $em->getRepository('SalonSolutionAdminBundle:SalonsolutionsSalon')->find($id);				
			
			if ($delSalon)
			{
				$em->remove($delSalon);
				$em->flush();
					return $this->redirect($this->generateUrl('salon_solution_admin_manageSalons'));  // redirect the page
			}
																			 //echo "<pre>"; print_r($deleteManageCoustomer); die;
			
			return $this->render('SalonSolutionAdminBundle:Page:delete_manage_salon.html.twig');
	
		}
		/*------------------------------- End : Function to  Edit Salon ------------------------------*/
		
		
		
		/**************************** Begin : Function to Manage CMS ********************************/
		public function manageCMSAction(Request $request)
		{	
			$repository = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsCms');
			  $fetchCMS = $repository->findAll();		
			  	
			return $this->render('SalonSolutionAdminBundle:Page:manage_cms.html.twig',array('fetchCMS'=> $fetchCMS));
			
		}
		/*------------------------------- End : Function to  CMS ------------------------------*/
		
			/**************************** Begin : Function to View Advertisements ********************************/
	  
		public function viewCMSAction($id, Request $request)
		{
			
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsCms');
			  $cmsDetails = $repository->findBy(array('id' =>  $id));			
				
				//echo "<pre>"; print_r($cmsDetails); die;
			
			return $this->render('SalonSolutionAdminBundle:Page:view_cms.html.twig',array('cmsDetails'=> $cmsDetails));

			
		}
		/*------------------------------- End : Function to View Advertisements ------------------------------*/
		
	
		/**************************** Begin : Function to Add Manage CMS ********************************/
		public function addManageCMSAction(Request $request)
		{	
			
			
		if ($request->getMethod() == 'POST') 
					{						
						$title = $request->get("title");  	
						$description = $request->get("description");
						$url = $request->get("url");
			
						$customer = new SalonsolutionsCms();
						
						$customer->setTitle($title);   
						$customer->setDescription($description);
						$customer->setUrl($url);
						
						$em = $this->getDoctrine()->getEntityManager();
						$em->persist($customer);
						$em->flush();
						return $this->redirect($this->generateUrl('salon_solution_admin_manageCMS'));  // redirect the page
			
					} 
					
			return $this->render('SalonSolutionAdminBundle:Page:add_cms.html.twig');
			
		}
		/*------------------------------- End : Function to Add Manage CMS  ------------------------------*/
		
		
		
		
	
		/**************************** Begin : Function to CMS ********************************/
		public function editCMSAction($id, Request $request)
		{	
			$repository = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsCms');
			 $cmsDetails = $repository->findBy(array('id' =>  $id));			
			
			
			if($request->getMethod() == 'POST')
			{
				$title = $request->get("title");  	
				$description = $request->get("description");
				$url = $request->get("url");
		
				$em = $this->getDoctrine()->getEntityManager();
				$confirmedSubscribe = $em->createQueryBuilder() 
				->select('reg')
				->update('SalonSolutionAdminBundle:SalonsolutionsCms',  'reg')
				->set('reg.title', ':title')
				->setParameter('title', $title)
				->set('reg.description', ':description')
				->setParameter('description', $description)
				->set('reg.url', ':url')
				->setParameter('url', $url)
				->where('reg.id = :id')
				->setParameter('id', $id)
				->getQuery()
				->getResult();
				return $this->redirect($this->generateUrl('salon_solution_admin_manageCMS'));
				
				
			}
			
			return $this->render('SalonSolutionAdminBundle:Page:edit_cms.html.twig', array('cmsDetails'=>  $cmsDetails));
			
		}
		/*------------------------------- End : Function to  CMS ------------------------------*/
		
		
	
		/**************************** Begin : Function to Delete CMS ********************************/
		public function deleteCMSAction($id, Request $request)
		{	
			
			
			$em = $this->getDoctrine()->getEntityManager();
			$delCMS = $em->getRepository('SalonSolutionAdminBundle:SalonsolutionsCms')->find($id);				
			
			if ($delCMS)
			{
				$em->remove($delCMS);
				$em->flush();
					return $this->redirect($this->generateUrl('salon_solution_admin_manageCMS'));  
			}
		
			
			return $this->render('SalonSolutionAdminBundle:Page:delete_manage_cms.html.twig');
			
		}
		/*------------------------------- End : Function to Delete CMS ------------------------------*/
		
		
		
			
		
		
	/**************************** Begin : Function to Manage Services ********************************/
	  
		public function manageServicesAction(Request $request)
		{
			$em = $this->getDoctrine()->getEntityManager();
			$Services = $em->createQueryBuilder()
			->select('service, salon.city')
			->from('SalonSolutionAdminBundle:SalonsolutionsService',  'service')
			->leftJoin('SalonSolutionAdminBundle:SalonsolutionsSalon', 'salon', "WITH", "salon.id=service.salonId")
			->getQuery()
			->getArrayResult();
	
			foreach($Services as $sevice)
			{
				$arrService[$sevice[0]['id']] = $sevice[0];
				$arrService[$sevice[0]['id']]['city'] = $sevice['city'];
				
			}
			//echo "<PRE>";print_r($arrService);die;
			return $this->render('SalonSolutionAdminBundle:Page:manage_services.html.twig',array('Services'=> $arrService));
		}
										//--- Begin : Function to  Change Status Services  ------------------------------*/
					public function changeStatusServicesAction()
					{
						$statusHtml = '';
						$em = $this->getDoctrine()->getEntityManager();
						
						if( isset($_POST['currentStatus']) && $_POST['currentStatus'] == 0 )
						{
							$status = 1;	
							$statusString = 'Active';
						}
						else
						{
							$status = 0;
							$statusString = 'Inactive';
						}
							
						 $id = $_POST['id'];
						
						if( isset($_POST['objectType']) && $_POST['objectType'] == 'Services' )
						{
							$em = $this->getDoctrine()->getEntityManager();
							$confirmedSubscribe = $em->createQueryBuilder() 
							->select('ser')
							->update('SalonSolutionAdminBundle:SalonsolutionsService',  'ser')
							->set('ser.status', ':status')
							->setParameter('status', $status)
							->where('ser.id = :id')
							->setParameter('id', $id)
							->getQuery()
							->getResult();
						}
						$statusHtml.='<a id="status-'.$id.'" class="edit" title="Click to Change" onclick="javascript:changeStatusServices(\'status-'.$id.'\','.$status.');">'.$statusString.'</a>'; 
						
						return new response($statusHtml);				
					}
					//--- end : Function to  Change Status Services  ------------------------------*/
					
	/*------------------------------- End : Function to Change Status Services Services ------------------------------*/
		
		
		
		/**************************** Begin : Function to Manage Services ********************************/
	  
		public function addServiceAction(Request $request)
		{
					
			$em = $this->getDoctrine()->getEntityManager();
			$salons = $em->getRepository('SalonSolutionAdminBundle:SalonsolutionsSalon')->findAll();						
				//echo "<pre>";	print_r($salons);  	 die();
				$arrTmpSalon = array();
				$arrSalon = array();
			foreach($salons as $salon)
			{
					if( !in_array($salon->name, $arrTmpSalon) )
					{
						$arrSalon[$salon->id] = $salon->name;
					}
					$arrTmpSalon[] = $salon->name;
			}
			
				//echo "<pre>";	print_r($arrSalon);  	 die();
				
				if ($request->getMethod() == 'POST') 
					{					
						$title = $request->get("title");  	
						$description = $request->get("description");  	
						$color = $request->get("color");  	
						$price = $request->get("price");  	 
						$salonId = $request->get("salonLocation");   //salonLocation  get by6 salon id -: this process given below
						$availability = $request->get("availability");    //	 echo "<pre>";	print_r($salonId);   die();
						$service = new SalonsolutionsService();
						
						$service->setTitle($title);  						 
						$service->setDescription($description);  						 
						$service->setColor($color);  						 
						$service->setPrice($price);  						 
						$service->setSalonId($salonId);  						 
						$service->setAvailability($availability);  	
						$service->setStatus('1');	
							
							 //echo "<pre>";	print_r($availability);   die();
					
							
						$em = $this->getDoctrine()->getEntityManager();
						$em->persist($service);
						$em->flush();
						return $this->redirect($this->generateUrl('salon_solution_admin_manageServices'));  // redirect the page
			
					} 
				
			//	echo "<pre>";	print_r($Services);   die();
		
			return $this->render('SalonSolutionAdminBundle:Page:add_services.html.twig',array('arrSalon'=> $arrSalon));

			
		}
	
		function getSalonLocationAction( Request $request){
			
			//$myArray = $request->get('salonId');
			$salonId = $_POST['salonId']; // echo $salonId; die;
			$html='';
			$em = $this->getDoctrine()->getEntityManager();
			$salonOwner = $em->createQueryBuilder()
			->select('SalonsolutionsSalon')
			->from('SalonSolutionAdminBundle:SalonsolutionsSalon',  'SalonsolutionsSalon')
			->where('SalonsolutionsSalon.id =:id')
			->setParameter('id', $salonId)
			->getQuery()
			->getArrayResult(); 
			
			$salons = $em->createQueryBuilder()
			->select('SalonsolutionsSalon')
			->from('SalonSolutionAdminBundle:SalonsolutionsSalon',  'SalonsolutionsSalon')
			->where('SalonsolutionsSalon.ownerId =:ownerId')
			->setParameter('ownerId', $salonOwner[0]['ownerId'])
			->getQuery()
			->getArrayResult(); 
			
				//echo "<pre>";	print_r($salons);   die();
			
			foreach($salons as $salon)
			{
				if( $salon['id'] == $salonId )
				{
					$html.='<option value="'.$salon['id'].'" id="'.$salon['id'].'" class="ajx_li" onclick="javascript:updateCityValue(this.id);" selected>'.$salon['city'].'</option>';
				}
				else
				{
					$html.='<option value="'.$salon['id'].'" id="'.$salon['id'].'" class="ajx_li" onclick="javascript:updateCityValue(this.id);">'.$salon['city'].'</option>';
				}
			}
			
			return new response($html);	
		}	
				
	/*------------------------------- End : Function to Manage Services ------------------------------*/
	
	
	
		/**************************** Begin : Function to Manage Services ********************************/
	  
		public function editServiceAction($id, Request $request)
		{
			$em = $this->getDoctrine()->getEntityManager();
			$salons = $em->getRepository('SalonSolutionAdminBundle:SalonsolutionsSalon')->findAll();						
				//echo "<pre>";	print_r($salons);  	 die();
				$arrTmpSalon = array();
				$arrSalon = array();
			foreach($salons as $salon)
			{
					if( !in_array($salon->name, $arrTmpSalon) )
					{
						$arrSalon[$salon->id] = $salon->name;
					}
					$arrTmpSalon[] = $salon->name;
			}
			
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsService');
			 $updateServiceInformation = $repository->findBy(array('id' =>  $id));			
				
					//echo "<pre>";	print_r($updateServiceInformation);   die();
					
			if($request->getMethod() == 'POST')
			{				
				$title = $request->get("title");  	
				$description = $request->get("description");  	
				$color = $request->get("color");  	
				$price = $request->get("price");  	
				$salonId = $request->get("salonLocation");  	
				$availability = $request->get("availability");    	
			
		
				$em = $this->getDoctrine()->getEntityManager();
				$confirmedSubscribe = $em->createQueryBuilder() 
				->select('tblService')
				->update('SalonSolutionAdminBundle:SalonsolutionsService',  'tblService')
				->set('tblService.title', ':title')
				->setParameter('title', $title)
				->set('tblService.description', ':description')
				->setParameter('description', $description)
				->set('tblService.color', ':color')
				->setParameter('color', $color)
				->set('tblService.price', ':price')
				->setParameter('price', $price)
				->set('tblService.salonId', ':salonId')
				->setParameter('salonId', $salonId)
				->set('tblService.availability', ':availability')
				->setParameter('availability', $availability)
				->where('tblService.id = :id')
				->setParameter('id', $id)
				->getQuery()
				->getResult();
								
				return $this->redirect($this->generateUrl('salon_solution_admin_manageServices'));
				
			}	
			return $this->render('SalonSolutionAdminBundle:Page:edit_service.html.twig',array('arrSalon'=> $arrSalon,'updateServiceInformation'=> $updateServiceInformation));

			
		}
		/*------------------------------- End : Function to Manage Services ------------------------------*/
		
		
		
		/**************************** Begin : Function to Manage Services ********************************/
	  
		public function deleteServiceAction($id, Request $request)
		{			
					$em = $this->getDoctrine()->getEntityManager();
					$del = $em->getRepository('SalonSolutionAdminBundle:SalonsolutionsService')->find($id);				
			
					if ($del) {
					$em->remove($del);
					$em->flush();
						return $this->redirect($this->generateUrl('salon_solution_admin_manageServices'));  // redirect the page
					}
						
			return $this->render('SalonSolutionAdminBundle:Page:delete_services.html.twig');

			
		}
		/*------------------------------- End : Function to Manage Services ------------------------------*/
		
		
		/**************************** Begin : Function to Manage Services ********************************/
	  
		public function viewServiceAction($id, Request $request)
		{				
					
			$repository = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsService');
			$viewService = $repository->findBy(array('id' =>  $id));			
			$salonId = $viewService[0]->salonId; 
			
			$em = $this->getDoctrine()->getEntityManager();
			$salonLocation= $em->getRepository('SalonSolutionAdminBundle:SalonsolutionsSalon')->findBy(array('id' =>  $salonId));	
			$getSalonLocation= $salonLocation[0]->city;
			
			return $this->render('SalonSolutionAdminBundle:Page:view_service.html.twig', array('viewService'=> $viewService ,'getSalonLocation'=>$getSalonLocation));

		}
		/*------------------------------- End : Function to Manage Services ------------------------------*/
		
		
		
		
		
		
		
		
				
		
		/**************************** Begin : Function to Manage Appointments ********************************/
	  
		public function manageAppointmentsAction(Request $request)
		{
			
																				//4leftjoins			
			
			$em = $this->getDoctrine()->getEntityManager();
			$arrServive = $em->createQueryBuilder()
				->select('SalonsolutionsAppointment', 'SalonsolutionsSalon.city', 'SalonsolutionsSalon.name', 'SalonsolutionsUser.firstName', 'SalonsolutionsService.title','SalonsolutionsService.color')
				->from('SalonSolutionAdminBundle:SalonsolutionsAppointment', 'SalonsolutionsAppointment')
				->leftJoin('SalonSolutionAdminBundle:SalonsolutionsService', 'SalonsolutionsService', "WITH", "SalonsolutionsService.id=SalonsolutionsAppointment.serviceId ")
				->leftJoin('SalonSolutionAdminBundle:SalonsolutionsUser', 'SalonsolutionsUser', "WITH", "SalonsolutionsUser.id=SalonsolutionsAppointment.consumerId ")
				->leftJoin('SalonSolutionAdminBundle:SalonsolutionsSalon', 'SalonsolutionsSalon', "WITH", "SalonsolutionsSalon.id=SalonsolutionsAppointment.salonId ")
				//->where('SalonsolutionsAppointment.salonId = :salonId')
				//->setParameter('salonId', $salonId)			
				->getQuery()
				->getArrayResult();
			
				//echo "<pre>"; print_r($arrServive); die;
			
			
			$arrAppointment = array();
			
			foreach($arrServive as $service)
			{
				$arrAppointment[$service[0]['id']] = $service[0]; 
				$arrAppointment[$service[0]['id']]['serviceTitle'] = $service['title']; 
				$arrAppointment[$service[0]['id']]['serviceColor'] = $service['color']; 
				$arrAppointment[$service[0]['id']]['serviceFirstName'] = $service['firstName']; 
				$arrAppointment[$service[0]['id']]['serviceCity'] = $service['city']; 
				$arrAppointment[$service[0]['id']]['serviceSalomName'] = $service['name']; 
			}
		
		//echo "<pre>"; print_r($arrBooking); die;
		
			return $this->render('SalonSolutionAdminBundle:Page:manage_appointments.html.twig', array('arrAppointment'=>$arrAppointment));

			
		}
		/*------------------------------- End : Function to Manage Appointments ------------------------------*/
	
	
	
			/**************************** Begin : Function to View Appointment ********************************/
	  
		public function viewAppointmentAction($id, Request $request)
		{
			
			// 3leftjoins
			$em = $this->getDoctrine()->getEntityManager();
			$arrServive = $em->createQueryBuilder()
				->select('SalonsolutionsAppointment', 'SalonsolutionsSalon.city', 'SalonsolutionsSalon.name', 'SalonsolutionsUser.firstName', 'SalonsolutionsService.title','SalonsolutionsService.color')
				->from('SalonSolutionAdminBundle:SalonsolutionsAppointment', 'SalonsolutionsAppointment')
				->leftJoin('SalonSolutionAdminBundle:SalonsolutionsService', 'SalonsolutionsService', "WITH", "SalonsolutionsService.id=SalonsolutionsAppointment.serviceId ")
				->leftJoin('SalonSolutionAdminBundle:SalonsolutionsUser', 'SalonsolutionsUser', "WITH", "SalonsolutionsUser.id=SalonsolutionsAppointment.consumerId ")
				->leftJoin('SalonSolutionAdminBundle:SalonsolutionsSalon', 'SalonsolutionsSalon', "WITH", "SalonsolutionsSalon.id=SalonsolutionsAppointment.salonId ")
				->where('SalonsolutionsAppointment.id = :id')
				->setParameter('id', $id)			
				->getQuery()
				->getArrayResult();
			
				//echo "<pre>"; print_r($arrServive); die;
			
			$viewAppointment = array();
			
			foreach($arrServive as $service)
			{
				$viewAppointment[$service[0]['id']] = $service[0]; 
				$viewAppointment[$service[0]['id']]['serviceTitle'] = $service['title']; 
				$viewAppointment[$service[0]['id']]['serviceColor'] = $service['color']; 
				$viewAppointment[$service[0]['id']]['serviceFirstName'] = $service['firstName']; 
				$viewAppointment[$service[0]['id']]['serviceCity'] = $service['city']; 
				$viewAppointment[$service[0]['id']]['serviceSalomName'] = $service['name']; 
			}
			
			return $this->render('SalonSolutionAdminBundle:Page:view_appointment.html.twig' , array('viewAppointment'=>$viewAppointment));

			
			
		}
		/*------------------------------- End : Function to View Appointment ------------------------------*/
		
	
			/**************************** Begin : Function to Delete Appointment ********************************/
	  
		public function deleteAppointmentAction($id, Request $request)
		{
			
			$em = $this->getDoctrine()->getEntityManager();
			$del = $em->getRepository('SalonSolutionAdminBundle:SalonsolutionsAppointment')->find($id);				
	
			if ($del) {
			$em->remove($del);
			$em->flush();
				return $this->redirect($this->generateUrl('salon_solution_admin_manageAppointments'));  // redirect the page
			}
			
			return $this->render('SalonSolutionAdminBundle:Page:delete_appointment.html.twig');

			
			
		}
		/*------------------------------- End : Function to Delete Appointment ------------------------------*/
		
		
		
		
		/**************************** Begin : Function to Manage Advertisements ********************************/
	  
		public function manageAdvertisementsAction(Request $request)
		{
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsAdvertisement');
			$Advertisements = $repository->findAll();
			
			$em = $this->getDoctrine()->getEntityManager();
			$Advertisements = $em->createQueryBuilder()
			->select('adver, salon.name')
			->from('SalonSolutionAdminBundle:SalonsolutionsAdvertisement',  'adver')
			->leftJoin('SalonSolutionAdminBundle:SalonsolutionsSalon', 'salon', "WITH", "salon.id=adver.salonId")
			->getQuery()
			->getArrayResult();
				//echo "<pre>";	print_r($Advertisements);   die();
			
			$arrAdvertisements =array();
			
			foreach($Advertisements as $advertisement)
			{
				$arrAdvertisements[$advertisement[0]['id']] = $advertisement[0];
				$arrAdvertisements[$advertisement[0]['id']]['name'] = $advertisement['name'];
				
			}
				//echo "<pre>";	print_r($arrAdvertisements);   die();
		
			
			return $this->render('SalonSolutionAdminBundle:Page:manage_advertisements.html.twig', array('Advertisements'=> $arrAdvertisements));

			
		}
		/*------------------------------- End : Function to Manage Advertisements ------------------------------*/
		
		
		
		
		/**************************** Begin : Function to ADD Advertisements ********************************/
	  
		public function addAdvertisementAction(Request $request)
		{
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsSalon');
			$salonDetails = $repository->findBy(array('status' => '1'));
				
				//echo "<pre>";	print_r($salonDetails);   die();
		
		if($request->getMethod() == 'POST')
			{				
				$title = $request->get("title");  				
				$description = $request->get("description");	
				$url = $request->get("url");	
				$salonId = $request->get("salonId");	
		
				$addAdvertisement = new SalonsolutionsAdvertisement();
						
				$addAdvertisement->setTitle($title);  						 
				$addAdvertisement->setDescription($description);  	
				$addAdvertisement->setUrl($url);  	
				$addAdvertisement->setSalonId($salonId);  	
				$addAdvertisement->setStatus('1');	
				
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($addAdvertisement);
				$em->flush();
				
				return $this->redirect($this->generateUrl('salon_solution_admin_manageAdvertisements'));  // redirect the page
				
			}
		
		
		
			return $this->render('SalonSolutionAdminBundle:Page:add_advertisement.html.twig', array('salonDetails'=> $salonDetails));


			
		}
		/*------------------------------- End : Function to ADD Advertisements ------------------------------*/
		
		
		
		/**************************** Begin : Function to Delete Advertisement ********************************/
	  
		public function deleteAdvertisementAction($id, Request $request)
		{
				$em = $this->getDoctrine()->getEntityManager();
					$del = $em->getRepository('SalonSolutionAdminBundle:SalonsolutionsAdvertisement')->find($id);				
			
					if ($del) {
					$em->remove($del);
					$em->flush();
						return $this->redirect($this->generateUrl('salon_solution_admin_manageAdvertisements'));  // redirect the page
					}
			
			return $this->render('SalonSolutionAdminBundle:Page:delete_advertisement.html.twig');

		}
		/*------------------------------- End : Function to Delete Advertisement ------------------------------*/
		
		
		/**************************** Begin : Function to Edit Advertisement ********************************/
	  
		public function editAdvertisementAction($id, Request $request)
		{
			$repository = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsAdvertisement');
			 $editAdvertisement = $repository->findBy(array('id' =>  $id));			
				$salonId=  $editAdvertisement[0]->salonId;
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsSalon');
			 $salonName = $repository->findBy(array('status' => '1'));			
			//echo "<pre>";	print_r($userName);   die();
			
			if($request->getMethod() == 'POST')
			{				
				$title = $request->get("title");  	
				$description = $request->get("description");  	
				$url = $request->get("url");  	
				$salonId = $request->get("salonId");  	
						
		
				$em = $this->getDoctrine()->getEntityManager();
				$confirmedSubscribe = $em->createQueryBuilder() 
				->select('tblAdvertisement')
				->update('SalonSolutionAdminBundle:SalonsolutionsAdvertisement',  'tblAdvertisement')
				->set('tblAdvertisement.title', ':title')
				->setParameter('title', $title)
				->set('tblAdvertisement.description', ':description')
				->setParameter('description', $description)	
				->set('tblAdvertisement.url', ':url')
				->setParameter('url', $url)	
				->set('tblAdvertisement.salonId', ':salonId')
				->setParameter('salonId', $salonId)	
				->where('tblAdvertisement.id = :id')
				->setParameter('id', $id)
				->getQuery()
				->getResult();
								
				return $this->redirect($this->generateUrl('salon_solution_admin_manageAdvertisements'));
				
			}	
			
			return $this->render('SalonSolutionAdminBundle:Page:edit_advertisement.html.twig', array('editAdvertisement'=> $editAdvertisement, 'salonName'=> $salonName));

					
		}
		/*------------------------------- End : Function to Edit Advertisement ------------------------------*/
		
		
		/**************************** Begin : Function to View Advertisement ********************************/
	  
		public function viewAdvertisementAction($id, Request $request)
		{
			$repository = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsAdvertisement');
			 $viewAdvertisement = $repository->findBy(array('id' =>  $id));			
			
			return $this->render('SalonSolutionAdminBundle:Page:view_advertisement.html.twig', array('viewAdvertisement'=> $viewAdvertisement));

					
		}
		/*------------------------------- End : Function to View Advertisement ------------------------------*/
		
		
		
		/**************************** Begin : Function to Manage Payment ********************************/
	  
		public function managePaymentsAction(Request $request)
		{
			$repository = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsPayment');
			  $payment = $repository->findAll();		
			
			
			$em = $this->getDoctrine()->getEntityManager();
			$paymentDetails = $em->createQueryBuilder()
			->select('payment, salon.name, user.firstName')
			->from('SalonSolutionAdminBundle:SalonsolutionsPayment',  'payment')
			->leftJoin('SalonSolutionAdminBundle:SalonsolutionsSalon', 'salon', "WITH", "salon.id=payment.salonId")					
			->leftJoin('SalonSolutionAdminBundle:SalonsolutionsUser', 'user', "WITH", "user.id=payment.userId")
			->getQuery()
			->getArrayResult();
			
			$arrPayments =array();
			
			foreach($paymentDetails as $payments)
			{
				$arrPayments[$payments[0]['userId']] = $payments[0];
				$arrPayments[$payments[0]['userId']]['firstName'] = $payments['firstName'];
				$arrPayments[$payments[0]['userId']]['name'] = $payments['name'];
				
			}
				//echo "<pre>" ;  	print_r($arrPayments); die;
				//echo "<pre>" ;  	print_r($arrPayments); die;
			
			return $this->render('SalonSolutionAdminBundle:Page:manage_payments.html.twig' , array('paymentDetails'=>$arrPayments));

			
		}
		
		/*------------------------------- End : Function to Manage Advertisements ------------------------------*/
		
		
		
		/**************************** Begin : Function to Manage Payment GateWay ********************************/
	  
		public function managePaymentGatewaysAction(Request $request)
		{
			$em = $this->getDoctrine()->getEntityManager();
			$globalPaymentMethod = $em->getRepository('SalonSolutionAdminBundle:SalonsolutionsGlobalPaymentMethod')->findAll();		//	echo "<pre>"print_r($globalPaymentMethod);	 die;			
			
			return $this->render('SalonSolutionAdminBundle:Page:manage_payment_gateway.html.twig', array('globalPaymentMethod'=> $globalPaymentMethod));

			
		}
		
									//--- Begin : Function to  Status  Manage Payment Gateway   ------------------------------*/
		public function changeStatusGlobalPaymentAction()
		{
			$statusHtml = '';
			$em = $this->getDoctrine()->getEntityManager();
			
			if( isset($_POST['currentStatus']) && $_POST['currentStatus'] == 0 )
			{
				$status = 1;	
				$statusString = 'Active';
			}
			else
			{
				$status = 0;
				$statusString = 'Inactive';
			}
				
			 $id = $_POST['id'];
			
			if( isset($_POST['objectType']) && $_POST['objectType'] == 'Users' )
			{
				$em = $this->getDoctrine()->getEntityManager();
				$confirmedSubscribe = $em->createQueryBuilder() 
				->select('reg')
				->update('SalonSolutionAdminBundle:SalonsolutionsGlobalPaymentMethod',  'reg')
				->set('reg.status', ':status')
				->setParameter('status', $status)
				->where('reg.id = :id')
				->setParameter('id', $id)
				->getQuery()
				->getResult();
			}
			$statusHtml.='<a id="status-'.$id.'" class="edit" title="Click to Change" onclick="javascript:changeStatusGlobalPayment(\'status-'.$id.'\','.$status.');">'.$statusString.'</a>'; 
			
			return new response($statusHtml);				
		}
										//--- End : Function to change Status   Manage Payment Gateway  ----------*/
		
		/*------------------------------- End : Function to Manage Payment GateWay ------------------------------*/
		
		
		/**************************** Begin : Function to ADD Payment GateWay ********************************/
	  
		public function addGlobalPaymentMethodAction(Request $request)
		{
			
			if ($request->getMethod() == 'POST') 
					{					
						$name = $request->get("name");  	
						$description = $request->get("description");  	
						
						$globalPaymentMethod = new SalonsolutionsGlobalPaymentMethod();
						
						$globalPaymentMethod->setName($name);  						 
						$globalPaymentMethod->setDescription($description);  	
						$globalPaymentMethod->setStatus('0');	
						
						$em = $this->getDoctrine()->getEntityManager();
						$em->persist($globalPaymentMethod);
						$em->flush();
						return $this->redirect($this->generateUrl('salon_solution_admin_managePaymentGateways'));  // redirect the page
			
					} 
			return $this->render('SalonSolutionAdminBundle:Page:add_global_Payment_method.html.twig');

			
		}
		/*------------------------------- End : Function to ADD Payment GateWay ------------------------------*/
		
		
		
		
		/**************************** Begin : Function to View Payment GateWay ********************************/
	  
		public function viewGlobalPaymentMethodAction($id, Request $request)
		{
			$repository = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsGlobalPaymentMethod');
			 $viewGlobalPaymentMethod = $repository->findBy(array('id' =>  $id));			
			
		
			return $this->render('SalonSolutionAdminBundle:Page:view_global_Payment_method.html.twig', array('viewGlobalPaymentMethod'=> $viewGlobalPaymentMethod));

			
		}
		/*------------------------------- End : Function to View Payment GateWay ------------------------------*/
		
		
		
		/**************************** Begin : Function to edit Payment GateWay ********************************/
	  
		public function editGlobalPaymentMethodAction($id, Request $request)
		{
			$repository = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsGlobalPaymentMethod');
			 $updateGlobalPaymentMethod = $repository->findBy(array('id' =>  $id));			
			
			
			if($request->getMethod() == 'POST')
			{				
				$name = $request->get("name");  	
				$description = $request->get("description");  	
						
		
				$em = $this->getDoctrine()->getEntityManager();
				$confirmedSubscribe = $em->createQueryBuilder() 
				->select('tblPaymentMethod')
				->update('SalonSolutionAdminBundle:SalonsolutionsGlobalPaymentMethod',  'tblPaymentMethod')
				->set('tblPaymentMethod.name', ':name')
				->setParameter('name', $name)
				->set('tblPaymentMethod.description', ':description')
				->setParameter('description', $description)	
				->where('tblPaymentMethod.id = :id')
				->setParameter('id', $id)
				->getQuery()
				->getResult();
								
				return $this->redirect($this->generateUrl('salon_solution_admin_managePaymentGateways'));
				
			}	
			
			return $this->render('SalonSolutionAdminBundle:Page:edit_global_Payment_method.html.twig', array('updateGlobalPaymentMethod'=> $updateGlobalPaymentMethod));

			
		}
		/*------------------------------- End : Function to edit Payment GateWay ------------------------------*/
		
		/**************************** Begin : Function to delete PaymentMethod ********************************/
	  
		public function deleteGlobalPaymentMethodAction($id, Request $request)
		{			
					$em = $this->getDoctrine()->getEntityManager();
					$del = $em->getRepository('SalonSolutionAdminBundle:SalonsolutionsGlobalPaymentMethod')->find($id);				
			
					if ($del) {
					$em->remove($del);
					$em->flush();
						return $this->redirect($this->generateUrl('salon_solution_admin_managePaymentGateways'));  // redirect the page
					}
						
			return $this->render('SalonSolutionAdminBundle:Page:delete_global_Payment_method.html.twig');

			
		}
		/*------------------------------- End : Function to Delete PaymentMethod ------------------------------*/
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		/**************************** Begin : Function to Manage  profile  ********************************/
	  
		public function profileAction(Request $request)
		{
			
			$session = $this->getRequest()->getSession(); 

			if($session->get('userId') && $session->get('userId') != '')					
				$setid = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_admin_login') );

			
		    $setid = $session->get('userId');		
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsUser');
			$profilename = $repository->findOneBy(array('id' => $setid));		
			return $this->render('SalonSolutionAdminBundle:Page:profile.html.twig', array('profilename'=> $profilename));

			
		}
		/*------------------------------- End : Function to Manage  profile  ------------------------------*/
		
		/**************************** Begin : Function to Profile ********************************/
	  
		public function profileAccountAction(Request $request)
		{
			$session = $this->getRequest()->getSession(); 	

			if($session->get('userId') && $session->get('userId') != '')					
				$setid = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_admin_login') );

		    $setid = $session->get('userId');		
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsUser');
			$profileDetail = $repository->findOneBy(array('id' => $setid));	
				
				
			return $this->render('SalonSolutionAdminBundle:Page:profile_account.html.twig', array('profileDetail'=> $profileDetail));

			
			
		}
		/*------------------------------- End : Function to Profile ------------------------------*/
		
		
		/**************************** Begin : Function to Profile ********************************/
	  
		public function changeProfileImageAction(Request $request)
		{
			$session = $this->getRequest()->getSession();
			
			if($session->get('userId') && $session->get('userId') != '')					
				$userId = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_admin_login') );

			
			
			$userId = $session->get('userId'); 
			  
			$basePath = $this->getBasePathAction();	  
			$photo = $_FILES['file']['name'];  	
			$ranPhotoUpload = rand(1,10000);  		
			$targetFilePhoto = $basePath."/".$this->container->getParameter('gbl_uploadPath_customers').$ranPhotoUpload.$photo;
			move_uploaded_file($_FILES['file']['tmp_name'], $targetFilePhoto);					
			
			  
			  
			$em = $this->getDoctrine()->getEntityManager();
			$confirmedSubscribe = $em->createQueryBuilder() 
			
			->select('User')
			->update('SalonSolutionAdminBundle:SalonsolutionsUser',  'User')
			->set('User.photo', ':photo')
			->setParameter('photo', $ranPhotoUpload.$photo)
			->where('User.id = :id')
			->setParameter('id', $userId)
			->getQuery()
			->getResult();
			
			$session->set('photo', $ranPhotoUpload.$photo); 
			
			return new response('SUCCESS');        
				
			
			
		}
					

		/*------------------------------- End : Function to Profile ------------------------------*/
		
		
		/**************************** Begin : Function to Profile ********************************/
	  
		public function updateProfileAction()
		{
				
			$session = $this->getRequest()->getSession(); 	
			
			if($session->get('userId') && $session->get('userId') != '')					
				$userId = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_admin_login') );

			
		    $userId = $session->get('userId');		
			//print_r($_POST['firstName']);die('yes');
			$repository = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsUser');
			$profileDetail = $repository->findOneBy(array('id' => $userId));	
			
			$firstName = $_POST["firstName"];     	
			$lastName  = $_POST["lastName"];
			$username  = $_POST["username"];
			$email  =    $_POST["email"];
			$address  =  $_POST["address"];
			$state  = $_POST["state"];
			$city  = $_POST["city"];
			$zip  = $_POST["zip"];
			$mobile  = $_POST["mobile"];
			$landline  = $_POST["landline"];        			// echo "<pre>" ;  	print_r($mobile); die;
		
			$em = $this->getDoctrine()->getEntityManager();
			$profileDetail = $em->createQueryBuilder() 
			->select('profile')
			->update('SalonSolutionAdminBundle:SalonsolutionsUser',  'profile')
			->set('profile.firstName', ':firstName')
			->setParameter('firstName', $firstName)
			->set('profile.lastName', ':lastName')
			->setParameter('lastName', $lastName)
			->set('profile.username', ':username')
			->setParameter('username', $username)
			->set('profile.email', ':email')
			->setParameter('email', $email)
			->set('profile.address', ':address')
			->setParameter('address', $address)
			->set('profile.state', ':state')
			->setParameter('state', $state)
			->set('profile.city', ':city')
			->setParameter('city', $city)
			->set('profile.zip', ':zip')
			->setParameter('zip', $zip)
			->set('profile.mobile', ':mobile')
			->setParameter('mobile', $mobile)
			->set('profile.landline', ':landline')
			->setParameter('landline', $landline)
			->where('profile.id = :id')
			->setParameter('id', $userId)
			->getQuery()
			->getResult();
					
			return new response("SUCCESS");
		}
		/*------------------------------- End : Function to Profile ------------------------------*/
		
		
		/**************************** Begin : Function to Change Password ********************************/
	  
		public function changePasswordAction()
		{
			$session = $this->getRequest()->getSession(); 	
			
			if($session->get('userId') && $session->get('userId') != '')					
				$userId = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_admin_login') );

		    $userId = $session->get('userId');		
			//echo $userId."<PRE>";print_r($_POST);die;
			$em = $this->getDoctrine()->getEntityManager();
			$salonCurrentPassword = $em->createQueryBuilder() 
			->select('SalonsolutionsUser')
			->from('SalonSolutionAdminBundle:SalonsolutionsUser',  'SalonsolutionsUser')
			->where('SalonsolutionsUser.id = :id')
			->setParameter('id', $userId)
			->andwhere('SalonsolutionsUser.type = :type')
			->setParameter('type', 1)
			->andwhere('SalonsolutionsUser.status = :status')
			->setParameter('status', 1)
			->getQuery()
			->getResult();
			
			$currentPassword = $salonCurrentPassword[0]->password ;  //  echo "<PRE>";print_r($currentPassword);die;
			$oldPassword = $_POST["oldPassword"];     
			$newPassword = $_POST["newPassword"];     
			$repeatPassword = $_POST["repeatPassword"];     
			//echo $newPassword."----".$repeatPassword;die;
			$em = $this->getDoctrine()->getEntityManager();
			
			if( ($currentPassword == md5($oldPassword)) && ($newPassword == $repeatPassword) )
			{
				$queryUpdatePassword = $em->createQueryBuilder() 
				->select('SalonsolutionsUser')
				->update('SalonSolutionAdminBundle:SalonsolutionsUser',  'SalonsolutionsUser')
				->set('SalonsolutionsUser.password', ':password')
				->setParameter('password', md5($newPassword))
				->where('SalonsolutionsUser.id = :id')
				->setParameter('id', $userId)
				->getQuery()
				->getResult();
				
				// echo "<PRE>";print_r($newPasswords);die;
				return new response("SUCCESS");
			}
			else
			{
				if( $currentPassword != md5($oldPassword) )
				{
					return new response("OLD_MISMATCH");
				}
				else
				{
					return new response("NEW_MISMATCH");
				}
			}
		}
		/*------------------------------- End : Function to Change Password  ------------------------------*/
		
		
		/**************************** Begin : Function to Change Password ********************************/
	  
		public function changeImageAction()
		{
			$session = $this->getRequest()->getSession(); 			
		    
			if($session->get('userId') && $session->get('userId') != '')					
				$userId = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_admin_login') );

			$userId = $session->get('userId');		
			//print_r($_POST['firstName']);die('yes');
			$repository = $this->getDoctrine()->getRepository('SalonSolutionAdminBundle:SalonsolutionsUser');
			$profileDetail = $repository->findOneBy(array('id' => $userId));	
			
			 
			
		}
		/*------------------------------- End : Function to Change Password  ------------------------------*/
	
	
	/**************************** Begin : Function to Change Password ********************************/
	  
		public function emailAction()
		{
				 
				/* $txta = 'hello';			
				$filename = $txta.'.doc';
			 
				$email = 'abhinandank@ocodewire.com';
				$to = $email;
				$subject = "Registrar Admin Password Reset";
				
				$txt=   '<table style="width:100%; border-collapse:collapse; border:1px solid #CCC;">
							<tr style="width:100%;" >
								<th style="background-color:#b9c9fe;" colspan="3">Appointment Detail:</th>
							</tr>
						
														

							<tr style="width:100%;">
								<td style="background-color:#D2E4FC; padding:5px; border:1px solid #CCC; border-width:1px 0;">Favorite Language:</td>
								<td style="background-color:White; padding:5px; border:1px solid #CCC; border-width:1px 0;">About Yourself:</td>
								<td style="background-color:#D2E4FC; padding:5px; border:1px solid #CCC; border-width:1px 0;">Time:</td>
								
							</tr>
							<tr style="width:100%;">
								<td  style = "background-color:#D2E4FC;">Hindi</td>
								<td style = "background-color:White;"> My Self Abhinandan</td>
								
								<td style = "background-color:#D2E4FC;">9:30</td>
							</tr>
							</table>
						';
				 
				 
				 
				
				
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
				$headers .= 'From: <support@rdrp.com>' . "\r\n";
				$headers .= "Content-Disposition: attachment; filename=\"" . $filename . "\"\r\n\r\n";

				
				mail($to,$subject,$txt,$headers);	 */
								 
					//echo $pdfoutput;die;
			
		}
		/*------------------------------- End : Function to Change Password  ------------------------------*/
	
	
}
