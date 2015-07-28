<?php

	namespace SalonSolution\WebBundle\Controller;
	
	
	use Symfony\Component\HttpFoundation\Request;   
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;

	
	use SalonSolution\WebBundle\Entity\SalonsolutionsUser;            //SalonsolutionsUser
	use SalonSolution\WebBundle\Entity\SalonsolutionsPayment;            //SalonsolutionsPayment
	use SalonSolution\WebBundle\Resources\SalonsolutionsUserType;  			  //SalonsolutionsUserType
	use SalonSolution\WebBundle\Entity\SalonsolutionsEmployeeMessages;            //SalonsolutionsEmployeeMessages
	
	use SalonSolution\WebBundle\Entity\SalonsolutionsSalon;            //SalonsolutionsSalon
	use SalonSolution\WebBundle\Resources\SalonsolutionsSalonType;  			  //SalonsolutionsSalonType
	
	use SalonSolution\WebBundle\Entity\SalonsolutionsAppointment;
	
	use Stripe;
			
			
		use Symfony\Component\HttpKernel\Exception\HttpException;
	//use SalonSolution\WebBundle\Entity\SalonsolutionsGlobalType;
	//use Test\TestBundle\Resources\SalonsolutionsGlobalTypeType;  

	use Symfony\Component\HttpFoundation\Session\Session;
	use Symfony\Component\HttpFoundation\RedirectResponse;
	
	
	use Symfony\Component\HttpFoundation\File\UploadedFile;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;

	class WebController extends Controller 
	{
		/**************************** Begin : Function to display home page ********************************/
		public function indexAction()
		{
			return $this->render('SalonSolutionWebBundle:Home:index.html.twig');
		}
		/*------------------------------- End : Function to display home page ------------------------------*/
				
	
		/**************************** Begin : Function to display home page ********************************/
		public function stripeAction(Request $request)
		{
			 
			
			$em = $this->getDoctrine()->getEntityManager();
			$registerDetail = $em->createQueryBuilder() 
			->select('SalonsolutionsUser')
			->from('SalonSolutionWebBundle:SalonsolutionsUser',  'SalonsolutionsUser')
			->addOrderBy('SalonsolutionsUser.id', 'DESC')
			->setMaxResults(1)
			->getQuery()
			->getResult();	
			
			$userId = $registerDetail[0]->id;
			$firstName = $registerDetail[0]->firstName;
			$email = $registerDetail[0]->email;
			
			$em = $this->getDoctrine()->getEntityManager();
			$salonDetail = $em->createQueryBuilder() 
			->select('SalonsolutionsSalon')
			->from('SalonSolutionWebBundle:SalonsolutionsSalon',  'SalonsolutionsSalon')
			->addOrderBy('SalonsolutionsSalon.id', 'DESC')
			->setMaxResults(1)
			->getQuery()
			->getResult();	
				$salonId = $salonDetail[0]->id;
			
		 $request = $this->container->get('request');
        $message = '';
        if($request->get('test'))
        {
           Stripe::setApiKey('sk_test_ZPmfNFOUBUAY3YyiSSzUPMA8');

            $token = $request->get('stripeToken');

            $customer = \Stripe_Customer::create(array(
                  'email' => $email,
                  'card'  => $token
            ));

            $charge = \Stripe_Charge::create(array(
                  'customer' => $customer->id,
                  'amount'   => 1000,
                  'currency' => 'usd',
                  'description' => $email
            ));
				
				$customerId =	$customer->id;
				$customeremail =	$customer->email;
				
				$times = date_create();
				$time = date_format($times, 'H:i:s');
				
				$mydate = getdate(date("U"));
				$date = $mydate['weekday'].','. $mydate['month'].','. $mydate['mday'].','. $mydate['year'];


		        $message = 'Successfully Paid $10.00  !';
		        
		        
		        $em = $this->getDoctrine()->getEntityManager();
				$confirmedPayment = $em->createQueryBuilder() 
				->select('SalonsolutionsUser')
				->update('SalonSolutionWebBundle:SalonsolutionsUser',  'SalonsolutionsUser')
				->set('SalonsolutionsUser.status', ':status')
				->setParameter('status', '1')
				->where('SalonsolutionsUser.email = :email')
				->setParameter('email', $email)
				->getQuery()
				->getResult();
			
			
			
				$Payment = new SalonsolutionsPayment();
				$Payment->setUserId($userId);   
				$Payment->setSalonId($salonId);   
				$Payment->setTransactionId($customerId);   
				$Payment->setTransactionDate($date);   
				$Payment->setTransactionTime($time);   
				$Payment->setTransactionAmount('10');
				$Payment->setPaymentMethodId('1');
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($Payment);
				$em->flush();
				
				
					$employeeMessage = new SalonsolutionsEmployeeMessages();
				$employeeMessage->setSalonOwnerId($userId);   
				$employeeMessage->setType("Employee Message");   
				$employeeMessage->setMessage("This is a message to be displayed on Employee Dashboard ");   
				$employeeMessage->setStatus('0');
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($employeeMessage);
				$em->flush();
				
				$halfMessage = new SalonsolutionsEmployeeMessages();
				$halfMessage->setSalonOwnerId($userId);   
				$halfMessage->setMessage("Offering Message");   
				$halfMessage->setMessage("HALF PRICE SPRAY TANS EVERY FRIDAY !!! ");   
				$halfMessage->setStatus('0');
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($halfMessage);
				$em->flush();
						
        } 

			return $this->render('SalonSolutionWebBundle:Home:stripe.html.twig', array('message' => $message, 'firstName' => $firstName));
		
		}
		/*------------------------------- End : Function to display home page ------------------------------*/
				
	
		/**************************** Begin : Function to register the customers ********************************/
	  
		public function registerCustomerAction(Request $request)
		{
			
			/* $request = $this->container->get('request');
				
				$message = '';
				if($request->get('test'))
				{
					require_once('charge.php');
					
					$result = runStripe();
					echo $result;die;
				}				
				*/
						
				if ($request->getMethod() == 'POST') 
					{
						
						$firstName = $request->get("firstName");  	
						$lastName = $request->get("lastName");
						$email = $request->get("email");
						$username = $request->get("username");
						$password = $request->get("password");
						$salonName = $request->get("salonName");
						$domain = $request->get("domain");
						$displayName = $request->get("displayName");
						$address = $request->get("address");						
						$city = $request->get("city");
						$country = $request->get("country");
						$state = $request->get("state");
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
						$salon->setDisplayName($displayName);
						$salon->setDescription('Enter Your description');
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
						
						return $this->redirect($this->generateUrl('salon_solution_web_stripe'));
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
			//echo "dsff";
			
			return $this->render('SalonSolutionWebBundle:Home:login.html.twig');
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
		
		
		
		
		
		
		
		
		public function searchSalonAction()
		{
			
			$em = $this->getDoctrine()->getEntityManager();
			
			if( isset($_POST) && is_array($_POST) && count($_POST) > 0 )
			{
				if( isset($_POST['serviceName']) && $_POST['serviceName'] != '' )
				{
					$serviceName = $_POST['serviceName'];
				}
				if( isset($_POST['cityZip']) && $_POST['cityZip'] != '' )
				{
					$cityZip = $_POST['cityZip'];
					$arrCityZip = explode(', ', $cityZip);
					$cityName = $arrCityZip[0];
					$zipCode = $arrCityZip[1];
				}
				
				if( isset($serviceName) && isset($cityName) )
				{
					$arrSalon = $em->createQueryBuilder()
					->select('SalonsolutionsSalon', 'SalonsolutionsService.title')
					->from('SalonSolutionWebBundle:SalonsolutionsSalon', 'SalonsolutionsSalon')
					->leftJoin('SalonSolutionWebBundle:SalonsolutionsService', 'SalonsolutionsService', "WITH", "SalonsolutionsService.salonId=SalonsolutionsSalon.id")
					->where('SalonsolutionsSalon.city=:cityName')
					->setParameter('cityName', $cityName)
					->orWhere('SalonsolutionsSalon.zip=:zipCode')
					->setParameter('zipCode', $zipCode)
					->andWhere('SalonsolutionsService.title=:serviceName')
					->setParameter('serviceName', $serviceName)
					->setMaxResults(5)
					->getQuery()
					->getArrayResult();
				}
				else
				{
					if( isset($serviceName) )
					{
						$arrSalon = $em->createQueryBuilder()
						->select('SalonsolutionsSalon', 'SalonsolutionsService.title')
						->from('SalonSolutionWebBundle:SalonsolutionsSalon', 'SalonsolutionsSalon')
						->leftJoin('SalonSolutionWebBundle:SalonsolutionsService', 'SalonsolutionsService', "WITH", "SalonsolutionsService.salonId=SalonsolutionsSalon.id")
						->where('SalonsolutionsService.title=:serviceName')
						->setParameter('serviceName', $serviceName)
						->setMaxResults(5)
						->getQuery()
						//->getSql();
						->getArrayResult();
					}
					else
					{
						$arrSalon = $em->createQueryBuilder()
						->select('SalonsolutionsSalon', 'SalonsolutionsService.title')
						->from('SalonSolutionWebBundle:SalonsolutionsSalon', 'SalonsolutionsSalon')
						->leftJoin('SalonSolutionWebBundle:SalonsolutionsService', 'SalonsolutionsService', "WITH", "SalonsolutionsService.salonId=SalonsolutionsSalon.id")
						->where('SalonsolutionsSalon.city=:cityName')
						->setParameter('cityName', $cityName)
						->orWhere('SalonsolutionsSalon.zip=:zipCode')
						->setParameter('zipCode', $zipCode)
						->setMaxResults(5)
						->getQuery()
						->getArrayResult();
					}
				}
			//	echo "<pre>";
			//	print_r($arrSalon);   
			//	die;
				
				/*$userUpdate = $em->createQueryBuilder()
				->select('a')
				->update('InCitySearchBundle:Service', 'a')
				->set('a.status', ':status')
				->setParameter('status', $status)
				->where('a.addedby=:userid')
				->setParameter('userid', $id)
				->getQuery()
				->getResult();*/
				
				return $this->render('SalonSolutionWebBundle:Home:search_salons.html.twig', array('arrSalon' => $arrSalon));
			}
			
			//return $this->render('SalonSolutionWebBundle:Home:search_salons.html.twig');
			
		}
		
		public function searchServiceAction()
		{
			$servicesHtml = '';
			if( isset($_POST['serviceSearchKey']) && $_POST['serviceSearchKey'] != '' )
			{
				$serviceSearchKey = $_POST['serviceSearchKey'];
				$params = array('criteria'=>'LIKE', 'serviceSearchKey'=>$serviceSearchKey);
				$arrService = $this->getServiceAction($params);
				
				foreach($arrService as $service)
				{
					$servicesHtml.='<li onclick="javascript:selectService(\''.$service['title'].'\');">'.$service['title'].'</li>';
				}
				
			}
			return new response($servicesHtml);
		}
		
		public function searchZipCityAction()
		{
			$zipCityHtml = '';
			if( isset($_POST['zipCitySearchKey']) && $_POST['zipCitySearchKey'] != '' )
			{
				$zipCitySearchKey = $_POST['zipCitySearchKey'];
				$params = array('criteria'=>'LIKE', 'zipCitySearchKey'=>$zipCitySearchKey);
				$arrZipCity = $this->getZipCityAction($params);
				
				foreach($arrZipCity as $zipCity)
				{
					$zipCityHtml.='<li onclick="javascript:selectCityZip(\''.$zipCity['city'].', '.$zipCity['zip'].'\');">'.$zipCity['city'].', '.$zipCity['zip'].'</li>';
				}
			}
			return new response($zipCityHtml);
		}
		
		public function searchSalonsAction()
		{
			$salonsHtml = '';
			if( isset($_POST['salonSearchKey']) && $_POST['salonSearchKey'] != '' )
			{
				$salonSearchKey = $_POST['salonSearchKey'];
				
				if( array_key_exists('criteria', $_POST) && $_POST['criteria'] != '' )
					$criteria = $_POST['criteria'];
				else
					$criteria = 'LIKE';
					
				$params = array('criteria'=>$criteria, 'salonSearchKey'=>$salonSearchKey);
				$arrSalon = $this->getSalonAction($params);
				
				if( is_array($arrSalon) && count($arrSalon) > 0 )
				{
					foreach($arrSalon as $salon)
					{
						$salonsHtml.='<li onclick="javascript:selectSalon(\''.$salon['name'].'\');">'.$salon['name'].'</li>';
					}
				}
			}
			return new response($salonsHtml);
		}
		
		public function getSalonDomainAction()
		{
			$salonsDomain = '';
			if( isset($_POST['salonName']) && $_POST['salonName'] != '' )
			{
				$salonName = $_POST['salonName'];
				$params = array('criteria'=>'EQUAL', 'salonSearchKey'=>$salonName);
				$arrSalon = $this->getSalonAction($params);
				
				if( is_array($arrSalon) && count($arrSalon) > 0 )
				{
					foreach($arrSalon as $salon);
				
					$salonsDomain.= $salon['domain'].'.salonsolutions.ca/consumerLogin';
				}
				
			}
			return new response($salonsDomain);
		}
		
		public function getSalonAction($params)
		{
			$em = $this->getDoctrine()->getEntityManager();
			if( array_key_exists('criteria', $params) && $params['criteria'] == 'LIKE' )
			{
				$arrSalon = $em->createQueryBuilder()->select('Salon')
				->from('SalonSolutionWebBundle:SalonsolutionsSalon', 'Salon')
				->where('Salon.name LIKE :salonName')
				->setParameter('salonName', $params['salonSearchKey'].'%')
				//->limit(1)
				->addOrderBy('Salon.id','asc')
				->getQuery()
				->getArrayResult();
			}
			else if( array_key_exists('domainName', $params) && $params['domainName'] != '' )
			{
				$arrSalon = $em->createQueryBuilder()->select('Salon')
				->from('SalonSolutionWebBundle:SalonsolutionsSalon', 'Salon')
				->where('Salon.domain=:domainName')
				->setParameter('domainName', $params['domainName'])
				->addOrderBy('Salon.id','asc')
				->getQuery()
				->getArrayResult();
			}
			else
			{
				$arrSalon = $em->createQueryBuilder()->select('Salon')
				->from('SalonSolutionWebBundle:SalonsolutionsSalon', 'Salon')
				->where('Salon.name=:salonName')
				->setParameter('salonName', $params['salonSearchKey'])
				->getQuery()
				->getArrayResult();
			}
			//echo "<PRE>";print_r($arrSalon);die;
			return $arrSalon;
		}
		
		public function getServiceAction($params)
		{
			$em = $this->getDoctrine()->getEntityManager();
			if( array_key_exists('criteria', $params) && $params['criteria'] == 'LIKE' )
			{
				$arrService = $em->createQueryBuilder()->select('Service')
				->from('SalonSolutionWebBundle:SalonsolutionsService', 'Service')
				->where('Service.title LIKE :serviceName')
				->setParameter('serviceName', $params['serviceSearchKey'].'%')
				->orWhere('Service.description LIKE :serviceDescription')
				->setParameter('serviceDescription', $params['serviceSearchKey'].'%')
				->getQuery()
				->getArrayResult();
			}
			else
			{
				$arrService = $em->createQueryBuilder()->select('Service')
				->from('SalonSolutionWebBundle:SalonsolutionsService', 'Service')
				->where('Service.title=:serviceName')
				->setParameter('serviceName', $params['serviceSearchKey'].'%')
				->getQuery()
				->getArrayResult();
			}
			return $arrService;
		}
		
		public function getZipCityAction($params)
		{
			$em = $this->getDoctrine()->getEntityManager();
			if( array_key_exists('criteria', $params) && $params['criteria'] == 'LIKE' )
			{
				$arrZipCity = $em->createQueryBuilder()->select('Zipcode')
				->from('SalonSolutionWebBundle:SalonsolutionsZipcode', 'Zipcode')
				->where('Zipcode.zip LIKE :zipcode')
				->setParameter('zipcode', $params['zipCitySearchKey'].'%')
				->orWhere('Zipcode.city LIKE :cityName')
				->setParameter('cityName', $params['zipCitySearchKey'].'%')
				->getQuery()
				->getArrayResult();
			}
			else
			{
				$arrZipCity = $em->createQueryBuilder()->select('Zipcode')
				->from('SalonSolutionWebBundle:SalonsolutionsZipcode', 'Zipcode')
				->where('Zipcode.zip=:zipcode')
				->setParameter('zipcode', $params['zipCitySearchKey'].'%')
				->orWhere('Zipcode.city=:cityName')
				->setParameter('cityName', $params['zipCitySearchKey'].'%')
				->getQuery()
				->getArrayResult();
			}
			
			return $arrZipCity;
		}
		
		
		
		
		
		
		
		/**************************** Begin : Function to register the consumers ********************************/
	  
		public function consumerRegistrationAction(Request $request)
		{
			$repository = $this->getDoctrine()->getRepository('SalonSolutionWebBundle:SalonsolutionsSalon');
			  $salonOwners = $repository->findAll();			
			
				//echo "<pre>"; print_r($Salons) ; die;
			
			//$advertisements = $this->getAdvertisementsAction($salonId);
			
				if ($request->getMethod() == 'POST') 
					{
						
						$firstName = $request->get("firstName");  	
						$lastName = $request->get("lastName");
						$email = $request->get("email");
						$username = $request->get("username");
						$password = $request->get("password");	
						$country = $request->get("country");
						$state = $request->get("state");				
						$city = $request->get("city");
						$address = $request->get("address");		
						$zip = $request->get("zip"); 
						$mobile = $request->get("mobile"); 
						$landline = $request->get("landline"); 
						$basePath = $this->getBasePathAction();	   						
						$photo = $_FILES['photo']['name'];  	
						$ranPhotoUpload = rand(1,10000);  		
						$targetFilePhoto = $basePath."/".$this->container->getParameter('gbl_uploadPath_consumers').$ranPhotoUpload.$photo;
						move_uploaded_file($_FILES['photo']['tmp_name'], $targetFilePhoto);					
						//$salonId = $request->get("ownerId");
						
						$arrServerName = explode('.salonsolutions.ca', $_SERVER['SERVER_NAME']);
						$salonDomain = 	$arrServerName[0];	
				//echo "<pre>"; print_r($salonDomain) ; die;
			
						if( $salonDomain == 'tanonline' )
							return $this->redirect( $this->generateUrl('salon_solution_web_index') );
				
						//echo $salonDomain."<PRE>";print_r($_SERVER);die;
						$params = array("domainName" => $salonDomain);
						$salonDetail = $this->getSalonAction($params);
							
							
		
		
						foreach($salonDetail as $salonDetail);
		
						$salonId = $salonDetail['id'];
						$salonOwnerId = $salonDetail['ownerId'];
						//echo "<pre>"; print_r($salonOwnerId) ; die;
						
						$consumer = new SalonsolutionsUser();
						
						$consumer->setFirstName($firstName);   
						$consumer->setLastName($lastName);
						$consumer->setEmail($email);
						$consumer->setUsername($username);						
						$consumer->setPassword(md5($password));
						$consumer->setAddress($address);
						$consumer->setCountry($country);
						$consumer->setState($state);						
						$consumer->setCity($city);
						$consumer->setZip($zip);									
						$consumer->setMobile($mobile);									
						$consumer->setLandline($landline);									
						$consumer->setPhoto($ranPhotoUpload.$photo);										
						$consumer->setParentId($salonId);	
						$consumer->setSalonId($salonId);	
						$consumer->setSalonOwnerId($salonOwnerId);	
						$consumer->setType('3');
						$consumer->setStatus('1');	
							 
						$em = $this->getDoctrine()->getEntityManager();
						$em->persist($consumer);
						$em->flush();
																					// next ---------> table insert
						
					}
							
						
			
			return $this->render('SalonSolutionWebBundle:Home:consumer_register.html.twig',array('salonOwners'=> $salonOwners));
		}
		/*------------------------------- End : Function to register the consumers ------------------------------*/
		
		
		
		
		
		
		
		
		/**************************** Begin : Function to Consumer Login  ********************************/
	  
		public function consumerLoginAction(Request $request)
		{
				/*$session = $this->getRequest()->getSession();
			
				$userSession = $this->getLoggedInConsumerDetailAction();    //function name given below 
																		 //check :- for enter dashoboard into the -path without  login then it will not show
				if($userSession)
					return $this->redirect($this->generateUrl('salon_solution_web_consumerDashboard'));   // check end
				
			
			$em = $this->getDoctrine()->getEntityManager();
			$repository = $em->getRepository('SalonSolutionWebBundle:SalonsolutionsUser');
			
				if ($request->getMethod() == 'POST')
				{
					//$session->clear();
					
					$email = $request->get('email');        // echo "<pre>"; print_r($email); die;  
															//$password = $request->get('password');   //echo $password;   die;   				
					$password = md5($request->get('password'));   //echo $password;    				
				
					$consumer = $repository->findOneBy(array('email' => $email, 'password' => $password,'type' =>'3','status' =>'1'));
						
					if($consumer !='')
					{										 
						 $session->set('consumerId', $consumer->getId());    	
						 $setconsumerid = $session->get('consumerId', $consumer->getId());   
						   
							
						 $session->set('consumerEmail', $consumer->getEmail());    	
						 $setEmail = $session->get('consumerEmail', $consumer->getEmail());   
							 // echo "<pre>"; print_r($setEmail); die;  
						 $session->set('consumerfirstName', $consumer->getfirstName());
						 $setname = $session->get('consumerfirstName', $consumer->getfirstName());   
						 
							return $this->redirect($this->generateUrl('salon_solution_web_consumerDashboard'));
					}
					else
					{	
						$this->get('session')->getFlashBag()->set('error', 'Invalid Email or Password');
							
					}	
			
				}
						
			
			return $this->render('SalonSolutionWebBundle:Home:consumer_login.html.twig');
			
			*/
				
				
				$session = $this->getRequest()->getSession();
				
				$arrServerName = explode('.salonsolutions.ca', $_SERVER['SERVER_NAME']);
				$salonDomain = 	$arrServerName[0];	
			
				if( $salonDomain == 'tanonline' )
					return $this->redirect( $this->generateUrl('salon_solution_web_index') );
					
					//echo $salonDomain."<PRE>";print_r($_SERVER);die;
				$params = array("domainName" => $salonDomain);
				$salonDetail = $this->getSalonAction($params);
				//echo $salonDomain."<PRE>";print_r($salonDetail);die;
				//foreach($salonDetail as $salonDetail);
			
				$salonId = $salonDetail[0]['id'];
				$salonName = $salonDetail[0]['name'];
				$salonLogo = $salonDetail[0]['logo'];
			
				$session->set('salonLogo', $salonLogo);
				
				$session->set('salonName', $salonName);
				
				$advertisements = $this->getAdvertisementsAction($salonId);
				$session->set('advertisements', $advertisements);
				
				//echo "<PRE>";print_r($advertisements);die;
				$userSession = $this->getLoggedInConsumerDetailAction();   
																		 					 
				if($userSession)
				{
					if($session->get('salonUrl'))
						return $this->redirect( $session->get('salonUrl') ); 
					else
						return $this->redirect($this->generateUrl('salon_solution_web_consumerDashboard'));  
				}
				
				$this->salonAdsAction();
				
				$em = $this->getDoctrine()->getEntityManager();
				$repository = $em->getRepository('SalonSolutionWebBundle:SalonsolutionsUser');
			
				if ($request->getMethod() == 'POST')
				{
					//$session->clear();
					
					$email = $request->get('email');        // echo "<pre>"; print_r($email); die;  
															//$password = $request->get('password');   //echo $password;   die;   				
					$password = md5($request->get('password'));   //echo $password;    				
				
					$consumer = $repository->findOneBy(array('email' => $email, 'password' => $password, 'parentId' => $salonId,'type' =>'3','status' =>'1'));
					
					//echo "<pre>"; print_r($consumer); die;
					
					if($consumer !='')
					{										 
						 $session->set('consumerId', $consumer->getId());    	
						 $setconsumerid = $session->get('consumerId', $consumer->getId());   
						   
							
						 $session->set('consumerEmail', $consumer->getEmail());    	
						 $setEmail = $session->get('consumerEmail', $consumer->getEmail());   
							 // echo "<pre>"; print_r($setEmail); die;  
						 $session->set('consumerfirstName', $consumer->getfirstName());
						 $setname = $session->get('consumerfirstName', $consumer->getfirstName());   
						 
							return $this->redirect($this->generateUrl('salon_solution_web_consumerDashboard'));
					}
					else
					{	
						$this->get('session')->getFlashBag()->set('error', 'Invalid Email or Password');
							
					}	
			
				}
				
					
			//return $this->render('SalonSolutionWebBundle:Home:consumer_login.html.twig');
			return $this->render('SalonSolutionWebBundle:Home:consumer_login.html.twig', array('salonId' => $salonId, 'salonName' => $salonName, 'salonLogo' => $salonLogo)); 
			
			
			
		}
		/*------------------------------- End : Function to Consumer Login  ------------------------------*/
			
			
		/**************************** Begin : Function to get the details of logged-in consumer ********************************/
		public function getLoggedInConsumerDetailAction()
		{
			 $session = $this->getRequest()->getSession();                     //check :- for enter dashoboard into the -
																				// path without  login then it will not show
			if( $session->get('consumerId') && $session->get('consumerId') != '' )
				return true;
			else
				return false;
		}
		/*------------------------------- End : Function to get the details of logged-in consumer ------------------------------*/
		
	
			
		/**************************** Begin : Function to Login the consumer ********************************/
	  
		public function consumerlogoutAction()
		{
			
			 $session = $this->getRequest()->getSession();
					$session->clear('foo');
					$session->remove('foo');
					unset($session);
						return $this->redirect($this->generateUrl('salon_solution_web_consumerLogin'));
			
			return $this->render('SalonSolutionWebBundle:Home:consumer_logout.html.twig');
		}
		/*------------------------------- End : Function to Login the consumer ------------------------------*/
		
		
		
		/**************************** Begin : Function to Consumer Dashboard  ********************************/
	  
		public function consumerDashboardAction(Request $request)
		{
			$session = $this->getRequest()->getSession(); 
			 
			
			
			 
		/* if( $session->get('appointmentDate') )
				$appointmentDate = $session->get('appointmentDate');	
			else
				$appointmentDate = date("d-m-Y"); */
			
			if( $session->get('appointmentDate') ){
			 $appointmentDate = $session->get('appointmentDate');	
					$pieces = explode("-", $appointmentDate);
					 $dayPiece=$pieces[0]; 
					 $monthPiece=$pieces[1]+1; 
					 $YearPiece=$pieces[2]; 					 
					if(strlen($monthPiece) == 1){
						$monthPiece = '0'.$monthPiece;
					}
				$finalAppointmentDate	= $dayPiece.'-'.$monthPiece.'-'.$YearPiece ;
			}else{
				$finalAppointmentDate	= date("d-m-Y");				
			}
			 
			$arrServerName = explode('.salonsolutions.ca', $_SERVER['SERVER_NAME']);
			$salonDomain = 	$arrServerName[0];	
			
			if( $salonDomain == 'tanonline' )
				return $this->redirect( $this->generateUrl('salon_solution_web_index') );
					
			//echo $salonDomain."<PRE>";print_r($_SERVER);die;
			$params = array("domainName" => $salonDomain);
			$salonDetail = $this->getSalonAction($params);
		
			foreach($salonDetail as $salonDetail);
		
			$salonLogo = $salonDetail['logo'];
		
			$session->set('salonLogo', $salonLogo);
		
			
			$userSession = $this->getLoggedInConsumerDetailAction();         //function name given below 	

			if($session->get('consumerId') && $session->get('consumerId') != '')					
				$consumerId = $session->get('consumerId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_web_consumerLogin') );	
						
			$consumerId = $session->get('consumerId');	
			 //	echo "<pre>"; print_r($salon); die;	
			$repository = $this->getDoctrine()->getRepository('SalonSolutionWebBundle:SalonsolutionsUser');
			$consumerProfile = $repository->findBy(array('id' => $consumerId));			
			$salonId =  $consumerProfile[0]->parentId;
			// echo "<pre>"; print_r($consumerProfile); die;
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionWebBundle:SalonsolutionsSalon');
			$salon = $repository->findBy(array('id' => $salonId));			
			$ownerId =  $salon[0]->ownerId;
				//echo "<pre>"; print_r($ownerId); die;
			
			//Consumer Offering/Flash Message Start 
				$repository = $this->getDoctrine()->getRepository('SalonSolutionWebBundle:SalonsolutionsEmployeeMessages');
				$message = $repository->findBy(array('salonOwnerId' => $ownerId , 'type' => 'Offering Message'));			
				$showMessage =	$message[0]->message;

				$session->set('offeringMessage' , $showMessage);
				$setMessage = $session->get('offeringMessage', $showMessage);   
				//Consumer Offering/Flash Message End 
				
				
		
			 	//echo "<pre>"; print_r($ownerId); die;
			
			$em = $this->getDoctrine()->getEntityManager();
			$allSalons = $em->createQueryBuilder() 
			->select('SalonsolutionsSalon')
			->from('SalonSolutionWebBundle:SalonsolutionsSalon',  'SalonsolutionsSalon')
			->where('SalonsolutionsSalon.ownerId = :ownerId')
			->setParameter('ownerId', $ownerId)
				
			->getQuery()
			->getArrayResult();	

			//echo "<pre>"; print_r($allSalons); die;
			
			foreach($allSalons as $salon)
			{
				$arrSalonIds = $salon['id'];
			}
			
			//echo "<pre>"; print_r($salonAvilable); die;
			$arrServiceAllDetails = array();
			$serviceBookingTime = array();
			$serviceBookingDetail = array();
			$arrAppointments = array();
			
		
			//echo $appointmentDate."<pre>"; print_r($arrServiceAllDetails); die;
			
			if($session->get('salonId'))
			{
				$salonId = $session->get('salonId');
				$salonLocation = $session->get('salonLocation');
			}
			else 
			{
				$salonId = $allSalons[0]['id'];
				$salonLocation = $allSalons[0]['city'];
				
				$session->set('salonId', $salonId); 
				$session->set('salonLocation', $salonLocation);
			}
			
			/*------------------------ Start - Calculate Business Hours -------------------------------*/
			$arrDefaultTime = array('12:00 AM', '12:30 AM','01:00 AM', '01:30 AM','02:00 AM', '02:30 AM','03:00 AM', '03:30 AM','04:00 AM', '04:30 AM','05:00 AM', '05:30 AM','06:00 AM', '06:30 AM','07:00 AM', '07:30 AM', '08:00 AM', '08:30 AM', '09:00 AM', '09:30 AM', '10:00 AM', '10:30 AM', '11:00 AM', '11:30 AM', '12:00 PM', '12:30 PM', '01:00 PM', '01:30 PM', '02:00 PM', '02:30 PM', '03:00 PM', '03:30 PM', '04:00 PM', '04:30 PM', '05:00 PM', '05:30 PM','06:00 PM', '06:30 PM','07:00 PM', '07:30 PM','08:00 PM', '08:30 PM','09:00 PM', '09:30 PM','10:00 PM', '10:30 PM','11:00 PM', '11:30 PM');
		 
			$repository = $this->getDoctrine()->getRepository('SalonSolutionWebBundle:SalonsolutionsSalonHours');
			$salonHours = $repository->findBy(array('salonId' => $ownerId));	
			
			
			$monFhalfStart = $salonHours[0]->monFhalfStart;
			$monFhalfEnd = $salonHours[0]->monFhalfEnd;
			$monShalfStart = $salonHours[0]->monShalfStart;
			$monShalfEnd = $salonHours[0]->monShalfEnd;
			$tuesFhalfStart = $salonHours[0]->tuesFhalfStart;
			$tuesFhalfEnd = $salonHours[0]->tuesFhalfEnd;
			$tuesShalfStart = $salonHours[0]->tuesShalfStart;
			$tuesShalfEnd = $salonHours[0]->tuesShalfEnd;
			$wedFhalfStart = $salonHours[0]->wedFhalfStart;
			$wedFhalfEnd = $salonHours[0]->wedFhalfEnd;
			$wedShalfStart = $salonHours[0]->wedShalfStart;
			$wedShalfEnd = $salonHours[0]->wedShalfEnd;
			$thuFhalfStart = $salonHours[0]->thuFhalfStart;
			$thuFhalfEnd = $salonHours[0]->thuFhalfEnd;
			$thuShalfStart = $salonHours[0]->thuShalfStart;
			$thuShalfEnd = $salonHours[0]->thuShalfEnd;
			$friFhalfStart = $salonHours[0]->friFhalfStart;
			$friFhalfEnd = $salonHours[0]->friFhalfEnd;
			$friShalfStart = $salonHours[0]->friShalfStart;
			$friShalfEnd = $salonHours[0]->friShalfEnd;
			$satFhalfStart = $salonHours[0]->satFhalfStart;
			$satFhalfEnd = $salonHours[0]->satFhalfEnd;
			$satShalfStart = $salonHours[0]->satShalfStart; 
			$satShalfEnd = $salonHours[0]->satShalfEnd;
			$sunFhalfStart = $salonHours[0]->sunFhalfStart;
			$sunFhalfEnd = $salonHours[0]->sunFhalfEnd;
			$sunShalfStart = $salonHours[0]->sunShalfStart;
			$sunShalfEnd = $salonHours[0]->sunShalfEnd;
		  
		   	//$currentDay = date('D', strtotime($appointmentDate));
		   	$currentDay = date('D', strtotime($finalAppointmentDate));
		   	
		   	if( $currentDay == 'Mon' )
		   	{
		   		$salonOpeningTime =  $monFhalfStart;
			   	$salonClosingTime =  $monShalfEnd;
			   
			   	$salonBreakStartTime =  $monFhalfEnd;
			   	$salonBreakEndTime =  $monShalfStart;
			}
		   	else if( $currentDay == 'Tue' )
		   	{
		   		$salonOpeningTime =  $tuesFhalfStart;
			   	$salonClosingTime =  $tuesShalfEnd;
			   
			   	$salonBreakStartTime =  $tuesFhalfEnd;
			   	$salonBreakEndTime =  $tuesShalfStart;
		   	}
		   	else if( $currentDay == 'Wed' )
		   	{
				$salonOpeningTime =  $wedFhalfStart;
			   	$salonClosingTime =  $wedShalfEnd;
			   
			   	$salonBreakStartTime =  $wedFhalfEnd;
			   	$salonBreakEndTime =  $wedShalfStart;   
		   	}
		   	else if( $currentDay == 'Thu' )
		   	{
		 		$salonOpeningTime =  $thuFhalfStart;
			   	$salonClosingTime =  $thuShalfEnd;
			   
			   	$salonBreakStartTime =  $thuFhalfEnd;
			   	$salonBreakEndTime =  $thuShalfStart;  
		  	}
		   	else if( $currentDay == 'Fri' )
		   	{
		 		$salonOpeningTime =  $friFhalfStart;
			   	$salonClosingTime =  $friShalfEnd;
			   
			   	$salonBreakStartTime =  $friFhalfEnd;
			   	$salonBreakEndTime =  $friShalfStart;  
		   	}
		   	else if( $currentDay == 'Sat' )
		   	{
		 		$salonOpeningTime =  $satFhalfStart;
			   	$salonClosingTime =  $satShalfEnd;
			   
			   	$salonBreakStartTime =  $satFhalfEnd;
			   	$salonBreakEndTime =  $satShalfStart;  
		   	}
		   	else if( $currentDay == 'Sun' )
		   	{
		 		$salonOpeningTime =  $sunFhalfStart;
			   	$salonClosingTime =  $sunShalfEnd;
			   
			   	$salonBreakStartTime =  $sunFhalfEnd;
			   	$salonBreakEndTime =  $sunShalfStart;  
		   	}
		   	else
		   	{
		 		$salonOpeningTime =  '09:00 AM';
			   	$salonClosingTime =  '01:00 PM';
			   
			   	$salonBreakStartTime =  '02:00 PM';
			   	$salonBreakEndTime =  '07:00 PM';  
		   	}
		   
	   		$i = 0;
	   		
	   		$arrTime = array();
		   	foreach($arrDefaultTime as $defaultTime)
		   	{
	   			if( $defaultTime == $salonOpeningTime )
		   		{
		   			$i = 1;
		   		}
		   		
		   		if( $defaultTime == $salonClosingTime )
		   		{
		   			$i = 0;
		   		}
		   		
		   		if( $i == 1 )
		   		{
		   			$arrTime[] = $defaultTime;
		   		}
		   		else
		   		{
		   			continue;
		   		}
				
				
				
		   	}
			
			
			//echo "<pre>"; print_r($arrTime); die;
			//die;
			
			
		   	/*------------------------ End - Calculate Business Hours -------------------------------*/
	foreach($arrTime as $time)
			{
				$em = $this->getDoctrine()->getEntityManager();
			
				$arrAppointments[$time]['time'] = $time;
				
				
				
				$servicesDetail = $em->createQueryBuilder() 
				->select('SalonsolutionsService')
				->from('SalonSolutionEmployeeBundle:SalonsolutionsService',  'SalonsolutionsService')
				->where('SalonsolutionsService.salonId in(:salonId)')
				->setParameter('salonId', $salonId)
				->getQuery()
				->getArrayResult();		
			
	if($servicesDetail){
				
							//	echo "<pre>";print_r($servicesDetail);	die;
				$totalServices = count($servicesDetail);
				$widthDivision = 80/$totalServices;
				
				$serviceAvailabilityMax = 0;
			
				foreach($servicesDetail as $service)
				{
					$arrServiceAllDetails[$service['id']] = $service;
					
					$arrAppointments[$time]['services'][$service['id']] = $service;
					
				//	 echo "<pre>";print_r($arrServiceAllDetails[$service['id']]);	die;
					 
					$appointmentDetail = array();
					$appointmentDetail = $em->createQueryBuilder() 
					->select('SalonsolutionsAppointment')
					->from('SalonSolutionEmployeeBundle:SalonsolutionsAppointment',  'SalonsolutionsAppointment')
					->where('SalonsolutionsAppointment.scheduledDate= :scheduledDate')
					->setParameter('scheduledDate', $finalAppointmentDate)
					->andWhere('SalonsolutionsAppointment.scheduledTime= :scheduledTime')
					->setParameter('scheduledTime', $time)
					->andWhere('SalonsolutionsAppointment.serviceId= :serviceId')
					->setParameter('serviceId', $service['id'])
					//->setParameter('serviceId', $service['id'])
					->andWhere('SalonsolutionsAppointment.status= :status')
					->setParameter('status', 1)
					->getQuery()
					->getArrayResult();		
						
				
				
					if( $serviceAvailabilityMax < $service['availability'] )
					{
						$serviceAvailabilityMax = $service['availability'];
					}
				
					$arrServiceAllDetails[$service['id']]['serviceAvailabilityMax'] = $serviceAvailabilityMax;
					
					$arrAppointments[$time]['services'][$service['id']]['serviceAvailabilityMax'] = $serviceAvailabilityMax;
				
					 
					
					if( isset($appointmentDetail) && is_array($appointmentDetail) && count($appointmentDetail) > 0 )
					{
						$arrServiceAllDetails[$service['id']]['booked'] = count($appointmentDetail);
						
						$arrAppointments[$time]['services'][$service['id']]['booked'] = count($appointmentDetail);
										
						foreach($appointmentDetail as $serviceBooking)
						{				
							//abhi start 
							$id =	$serviceBooking['id'] ;				
						 	$status =	$serviceBooking['status'] ;				
						 	$bookingType =	$serviceBooking['bookingType'] ;	
					 		$bookingDate =	$serviceBooking['scheduledDate'] ;
					 		$bookingTime =	$serviceBooking['scheduledTime'] ;
							$fetchConsumerName =	$serviceBooking['consumerId'] ;	
								
							$repository = $this->getDoctrine()->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsUser');
							$appointmentConsumerName = $repository->findBy(array('id' => $fetchConsumerName));	
							$firstName=	$appointmentConsumerName[0]->firstName; 
							$lastName=	$appointmentConsumerName[0]->lastName;
						
							$serviceBookingDetail[$service['id']]['firstName'] = $firstName;					
							$serviceBookingDetail[$service['id']]['lastName'] = $lastName;				
							$serviceBookingDetail[$service['id']]['appointmentId'] = $id;				
							$serviceBookingDetail[$service['id']]['status'] = $status;				
							$serviceBookingDetail[$service['id']]['bookingType'] = $bookingType;
							$serviceBookingDetail[$service['id']]['bookingDate'] = $bookingDate;
							$serviceBookingDetail[$service['id']]['bookingTime'] = $bookingTime;				
						
							$serviceBookingTime[$service['id']] = $bookingTime;
							//abhi end   
							
							$arrAppointments[$time]['services'][$service['id']]['appointments'][$id]['firstName'] = $firstName;
							$arrAppointments[$time]['services'][$service['id']]['appointments'][$id]['lastName'] = $lastName;
							$arrAppointments[$time]['services'][$service['id']]['appointments'][$id]['appointmentId'] = $id;
							$arrAppointments[$time]['services'][$service['id']]['appointments'][$id]['status'] = $status;
							$arrAppointments[$time]['services'][$service['id']]['appointments'][$id]['bookingType'] = $bookingType;
							$arrAppointments[$time]['services'][$service['id']]['appointments'][$id]['bookingDate'] = $bookingDate;
							$arrAppointments[$time]['services'][$service['id']]['appointments'][$id]['bookingTime'] = $bookingTime;
							
						}
					}
					else  
					{
						$arrServiceAllDetails[$service['id']]['booked'] = 0;
						$arrAppointments[$time]['services'][$service['id']]['booked'] = 0;				
					} 
					$arrServiceAllDetails[$service['id']]['vacant'] = $arrServiceAllDetails[$service['id']]['serviceAvailabilityMax'] - $arrServiceAllDetails[$service['id']]['booked'];
					
					$arrAppointments[$time]['services'][$service['id']]['vacant'] = $arrAppointments[$time]['services'][$service['id']]['serviceAvailabilityMax'] - $arrAppointments[$time]['services'][$service['id']]['booked'];
				
				}
				
	}else{
			$arrServiceAllDetails = '';
			$serviceBookingTime = '';
			$serviceBookingDetail = '';
			$arrAppointments = '';
			$serviceAvailabilityMax = '';
			$widthDivision = "100px";
			$this->get('session')->getFlashBag()->set('noServicesFound', 'No services Found');    	
			
	}	

			
			}
			 
			 
			
			//echo "<pre>";print_r($arrAppointments);
			//die;
				
			
			//return $this->render('SalonSolutionWebBundle:Home:consumer_dashboard.html.twig', array('servicesDetail' => $arrServiceAllDetails, 'serviceAvailabilityMax' => $serviceAvailabilityMax, 'allSalons' => $allSalons, 'arrTime' => $arrTime, 'widthDivision'=>$widthDivision));
			
			return $this->render('SalonSolutionWebBundle:Home:consumer_dashboard.html.twig', array('servicesDetail' => $arrServiceAllDetails, 'serviceAvailabilityMax' => $serviceAvailabilityMax, 'allSalons' => $allSalons, 'arrTime' => $arrTime, 'widthDivision'=>$widthDivision, 'serviceBookingDetail'=>$serviceBookingDetail, 'arrAppointments'=>$arrAppointments));
			
		
		}
		/*------------------------------- End : Function to Consumer Dashboard  ------------------------------*/
		
		
		  
		
	/**************************** Begin : Function to Consumer Book Friend ********************************/
	  
		public function consumerBookFriendAction(Request $request)
		{
			$session = $this->getRequest()->getSession(); 	
		
		/* if( $session->get('appointmentDate') )
				$appointmentDate = $session->get('appointmentDate');	
			else
				$appointmentDate = date("d-m-Y"); */
			
			if( $session->get('appointmentDate') ){
			 $appointmentDate = $session->get('appointmentDate');	
					$pieces = explode("-", $appointmentDate);
					 $dayPiece=$pieces[0]; 
					 $monthPiece=$pieces[1]+1; 
					 $YearPiece=$pieces[2]; 					 
					if(strlen($monthPiece) == 1){
						$monthPiece = '0'.$monthPiece;
					}
				$finalAppointmentDate	= $dayPiece.'-'.$monthPiece.'-'.$YearPiece ;
			}else{
				$finalAppointmentDate	= date("d-m-Y");				
			}
			
			$userSession = $this->getLoggedInConsumerDetailAction();         //function name given 
		
			if($session->get('consumerId') && $session->get('consumerId') != '')					
				$consumerId = $session->get('consumerId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_web_consumerLogin') );	
			
			$consumerId = $session->get('consumerId');	
			 //	echo "<pre>"; print_r($salon); die;	
			$repository = $this->getDoctrine()->getRepository('SalonSolutionWebBundle:SalonsolutionsUser');
			$consumerProfile = $repository->findBy(array('id' => $consumerId));			
			$salonId =  $consumerProfile[0]->parentId;
			 //echo "<pre>"; print_r($salonId); die;
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionWebBundle:SalonsolutionsSalon');
			$salon = $repository->findBy(array('id' => $salonId));			
			$ownerId =  $salon[0]->ownerId;
			    
			 	
			//Consumer Offering/Flash Message Start 
			$repository = $this->getDoctrine()->getRepository('SalonSolutionWebBundle:SalonsolutionsEmployeeMessages');
			$message = $repository->findBy(array('salonOwnerId' => $ownerId , 'type' => 'Offering Message'));			
			$showMessage =	$message[0]->message;

			$session->set('offeringMessage' , $showMessage);
			$setMessage = $session->get('offeringMessage', $showMessage);   
			//Consumer Offering/Flash Message End 
			
				//echo "<pre>"; print_r($salons); die;
			
			$em = $this->getDoctrine()->getEntityManager();
			$allSalons = $em->createQueryBuilder() 
			->select('SalonsolutionsSalon')
			->from('SalonSolutionWebBundle:SalonsolutionsSalon',  'SalonsolutionsSalon')
			->where('SalonsolutionsSalon.ownerId = :ownerId')
			->setParameter('ownerId', $ownerId)
			->getQuery()
			->getArrayResult();	
			
			foreach($allSalons as $salon)
			{
				$arrSalonIds = $salon['id'];
			}
			
			
			//echo "<pre>"; print_r($salonAvilable); die;
			
			$arrServiceAllDetails = array();
			$serviceBookingTime = array();
			$serviceBookingDetail = array();
			$arrAppointments = array();
			
			
			/*  // abhi start old 
			$em = $this->getDoctrine()->getEntityManager();
			/*$servicesDetail = $em->createQueryBuilder() 
			->select('SalonsolutionsService', 'SalonsolutionsServiceAvailability')
			->from('SalonSolutionWebBundle:SalonsolutionsService',  'SalonsolutionsService')
			->leftJoin('SalonSolutionWebBundle:SalonsolutionsServiceAvailability', 'SalonsolutionsServiceAvailability', "WITH", "SalonsolutionsServiceAvailability.serviceId=SalonsolutionsService.id")
			->where('SalonsolutionsService.salonId in(:salonId)')
			->setParameter('salonId', $arrSalonIds)
			->getQuery()
			->getResult();*/
			
			/*$servicesDetail = $em->createQueryBuilder() 
			->select('SalonsolutionsService')
			->from('SalonSolutionWebBundle:SalonsolutionsService',  'SalonsolutionsService')
			->where('SalonsolutionsService.salonId in(:salonId)')
			->setParameter('salonId', $arrSalonIds)
			->getQuery()
			->getArrayResult();		
			
			$totalServices =	count($servicesDetail);
			$widthDivision	= 80/$totalServices;
			//echo "<pre>"; print_r($widthDivision); die;
			//echo "<pre>"; print_r($servicesDetail); die; 
			
			$arrServiceAllDetails = array();
			$serviceAvailabilityMax = 0;
			
			foreach($servicesDetail as $service)
			{
				$arrServiceAllDetails[$service['id']] = $service;
				$appointmentDetail = array();
				$appointmentDetail = $em->createQueryBuilder() 
				->select('SalonsolutionsAppointment')
				->from('SalonSolutionWebBundle:SalonsolutionsAppointment',  'SalonsolutionsAppointment')
				->where('SalonsolutionsAppointment.scheduledDate= :scheduledDate')
				->setParameter('scheduledDate', $finalAppointmentDate)
				->andWhere('SalonsolutionsAppointment.serviceId= :serviceId')
				->setParameter('serviceId', $service['id'])
				->andWhere('SalonsolutionsAppointment.status= :status')
				->setParameter('status', 1)
				->getQuery()
				->getArrayResult();		
				
				if( $serviceAvailabilityMax < $service['availability'] )
				{
					$serviceAvailabilityMax = $service['availability'];
				}
				
				//echo $appointmentDate."<pre>"; print_r($appointmentDetail); die;
				$arrServiceAllDetails[$service['id']]['booked'] = count($appointmentDetail);
				$arrServiceAllDetails[$service['id']]['vacant'] = $service['availability'] - $arrServiceAllDetails[$service['id']]['booked'];
			}
			*/
			//echo $appointmentDate."<pre>"; print_r($arrServiceAllDetails); die;
			
			if($session->get('salonId'))
			{
				$salonId = $session->get('salonId');
				$salonLocation = $session->get('salonLocation');
			}
			else 
			{
				$salonId = $allSalons[0]['id'];
				$salonLocation = $allSalons[0]['city'];
				
				$session->set('salonId', $salonId); 
				$session->set('salonLocation', $salonLocation);
			}
		
		
			/*------------------------ Start - Calculate Business Hours -------------------------------*/
			$arrDefaultTime = array('12:00 AM', '12:30 AM','01:00 AM', '01:30 AM','02:00 AM', '02:30 AM','03:00 AM', '03:30 AM','04:00 AM', '04:30 AM','05:00 AM', '05:30 AM','06:00 AM', '06:30 AM','07:00 AM', '07:30 AM', '08:00 AM', '08:30 AM', '09:00 AM', '09:30 AM', '10:00 AM', '10:30 AM', '11:00 AM', '11:30 AM', '12:00 PM', '12:30 PM', '01:00 PM', '01:30 PM', '02:00 PM', '02:30 PM', '03:00 PM', '03:30 PM', '04:00 PM', '04:30 PM', '05:00 PM', '05:30 PM','06:00 PM', '06:30 PM','07:00 PM', '07:30 PM','08:00 PM', '08:30 PM','09:00 PM', '09:30 PM','10:00 PM', '10:30 PM','11:00 PM', '11:30 PM');
		 
			$repository = $this->getDoctrine()->getRepository('SalonSolutionWebBundle:SalonsolutionsSalonHours');
			$salonHours = $repository->findBy(array('salonId' => $ownerId));	
			
			
			$monFhalfStart = $salonHours[0]->monFhalfStart;
			$monFhalfEnd = $salonHours[0]->monFhalfEnd;
			$monShalfStart = $salonHours[0]->monShalfStart;
			$monShalfEnd = $salonHours[0]->monShalfEnd;
			$tuesFhalfStart = $salonHours[0]->tuesFhalfStart;
			$tuesFhalfEnd = $salonHours[0]->tuesFhalfEnd;
			$tuesShalfStart = $salonHours[0]->tuesShalfStart;
			$tuesShalfEnd = $salonHours[0]->tuesShalfEnd;
			$wedFhalfStart = $salonHours[0]->wedFhalfStart;
			$wedFhalfEnd = $salonHours[0]->wedFhalfEnd;
			$wedShalfStart = $salonHours[0]->wedShalfStart;
			$wedShalfEnd = $salonHours[0]->wedShalfEnd;
			$thuFhalfStart = $salonHours[0]->thuFhalfStart;
			$thuFhalfEnd = $salonHours[0]->thuFhalfEnd;
			$thuShalfStart = $salonHours[0]->thuShalfStart;
			$thuShalfEnd = $salonHours[0]->thuShalfEnd;
			$friFhalfStart = $salonHours[0]->friFhalfStart;
			$friFhalfEnd = $salonHours[0]->friFhalfEnd;
			$friShalfStart = $salonHours[0]->friShalfStart;
			$friShalfEnd = $salonHours[0]->friShalfEnd;
			$satFhalfStart = $salonHours[0]->satFhalfStart;
			$satFhalfEnd = $salonHours[0]->satFhalfEnd;
			$satShalfStart = $salonHours[0]->satShalfStart; 
			$satShalfEnd = $salonHours[0]->satShalfEnd;
			$sunFhalfStart = $salonHours[0]->sunFhalfStart;
			$sunFhalfEnd = $salonHours[0]->sunFhalfEnd;
			$sunShalfStart = $salonHours[0]->sunShalfStart;
			$sunShalfEnd = $salonHours[0]->sunShalfEnd;
		  
		   	$currentDay = date('D', strtotime($finalAppointmentDate));
		  // 	$currentDay = date('D', strtotime($appointmentDate));
		   	
		   	if( $currentDay == 'Mon' )
		   	{
		   		$salonOpeningTime =  $monFhalfStart;
			   	$salonClosingTime =  $monShalfEnd;
			   
			   	$salonBreakStartTime =  $monFhalfEnd;
			   	$salonBreakEndTime =  $monShalfStart;
			}
		   	else if( $currentDay == 'Tue' )
		   	{
		   		$salonOpeningTime =  $tuesFhalfStart;
			   	$salonClosingTime =  $tuesShalfEnd;
			   
			   	$salonBreakStartTime =  $tuesFhalfEnd;
			   	$salonBreakEndTime =  $tuesShalfStart;
		   	}
		   	else if( $currentDay == 'Wed' )
		   	{
				$salonOpeningTime =  $wedFhalfStart;
			   	$salonClosingTime =  $wedShalfEnd;
			   
			   	$salonBreakStartTime =  $wedFhalfEnd;
			   	$salonBreakEndTime =  $wedShalfStart;   
		   	}
		   	else if( $currentDay == 'Thu' )
		   	{
		 		$salonOpeningTime =  $thuFhalfStart;
			   	$salonClosingTime =  $thuShalfEnd;
			   
			   	$salonBreakStartTime =  $thuFhalfEnd;
			   	$salonBreakEndTime =  $thuShalfStart;  
		  	}
		   	else if( $currentDay == 'Fri' )
		   	{
		 		$salonOpeningTime =  $friFhalfStart;
			   	$salonClosingTime =  $friShalfEnd;
			   
			   	$salonBreakStartTime =  $friFhalfEnd;
			   	$salonBreakEndTime =  $friShalfStart;  
		   	}
		   	else if( $currentDay == 'Sat' )
		   	{
		 		$salonOpeningTime =  $satFhalfStart;
			   	$salonClosingTime =  $satShalfEnd;
			   
			   	$salonBreakStartTime =  $satFhalfEnd;
			   	$salonBreakEndTime =  $satShalfStart;  
		   	}
		   	else if( $currentDay == 'Sun' )
		   	{
		 		$salonOpeningTime =  $sunFhalfStart;
			   	$salonClosingTime =  $sunShalfEnd;
			   
			   	$salonBreakStartTime =  $sunFhalfEnd;
			   	$salonBreakEndTime =  $sunShalfStart;  
		   	}
		   	else
		   	{
		 		$salonOpeningTime =  '09:00 AM';
			   	$salonClosingTime =  '01:00 PM';
			   
			   	$salonBreakStartTime =  '02:00 PM';
			   	$salonBreakEndTime =  '07:00 PM';  
		   	}
		   
	   		$i = 0;
	   		
	   		$arrTime = array();
		   	foreach($arrDefaultTime as $defaultTime)
		   	{
	   			if( $defaultTime == $salonOpeningTime )
		   		{
		   			$i = 1;
		   		}
		   		
		   		if( $defaultTime == $salonClosingTime )
		   		{
		   			$i = 0;
		   		}
		   		
		   		if( $i == 1 )
		   		{
		   			$arrTime[] = $defaultTime;
		   		}
		   		else
		   		{
		   			continue;
		   		}
		   	}
		   	/*------------------------ End - Calculate Business Hours -------------------------------*/
				
			foreach($arrTime as $time)
			{
				$em = $this->getDoctrine()->getEntityManager();
			
				$arrAppointments[$time]['time'] = $time;
				
				
				
				$servicesDetail = $em->createQueryBuilder() 
				->select('SalonsolutionsService')
				->from('SalonSolutionEmployeeBundle:SalonsolutionsService',  'SalonsolutionsService')
				->where('SalonsolutionsService.salonId in(:salonId)')
				->setParameter('salonId', $salonId)
				->getQuery()
				->getArrayResult();		
			
	if($servicesDetail){
				
							//	echo "<pre>";print_r($servicesDetail);	die;
				$totalServices = count($servicesDetail);
				$widthDivision = 80/$totalServices;
				
				$serviceAvailabilityMax = 0;
			
				foreach($servicesDetail as $service)
				{
					$arrServiceAllDetails[$service['id']] = $service;
					
					$arrAppointments[$time]['services'][$service['id']] = $service;
					
				//	 echo "<pre>";print_r($arrServiceAllDetails[$service['id']]);	die;
					 
					$appointmentDetail = array();
					$appointmentDetail = $em->createQueryBuilder() 
					->select('SalonsolutionsAppointment')
					->from('SalonSolutionEmployeeBundle:SalonsolutionsAppointment',  'SalonsolutionsAppointment')
					->where('SalonsolutionsAppointment.scheduledDate= :scheduledDate')
					->setParameter('scheduledDate', $finalAppointmentDate)
					->andWhere('SalonsolutionsAppointment.scheduledTime= :scheduledTime')
					->setParameter('scheduledTime', $time)
					->andWhere('SalonsolutionsAppointment.serviceId= :serviceId')
					->setParameter('serviceId', $service['id'])
					//->setParameter('serviceId', $service['id'])
					->andWhere('SalonsolutionsAppointment.status= :status')
					->setParameter('status', 1)
					->getQuery()
					->getArrayResult();		
						
				
				
					if( $serviceAvailabilityMax < $service['availability'] )
					{
						$serviceAvailabilityMax = $service['availability'];
					}
				
					$arrServiceAllDetails[$service['id']]['serviceAvailabilityMax'] = $serviceAvailabilityMax;
					
					$arrAppointments[$time]['services'][$service['id']]['serviceAvailabilityMax'] = $serviceAvailabilityMax;
				
					 
					
					if( isset($appointmentDetail) && is_array($appointmentDetail) && count($appointmentDetail) > 0 )
					{
						$arrServiceAllDetails[$service['id']]['booked'] = count($appointmentDetail);
						
						$arrAppointments[$time]['services'][$service['id']]['booked'] = count($appointmentDetail);
										
						foreach($appointmentDetail as $serviceBooking)
						{				
							//abhi start 
							$id =	$serviceBooking['id'] ;				
						 	$status =	$serviceBooking['status'] ;				
						 	$bookingType =	$serviceBooking['bookingType'] ;	
					 		$bookingDate =	$serviceBooking['scheduledDate'] ;
					 		$bookingTime =	$serviceBooking['scheduledTime'] ;
							$fetchConsumerName =	$serviceBooking['consumerId'] ;	
								
							$repository = $this->getDoctrine()->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsUser');
							$appointmentConsumerName = $repository->findBy(array('id' => $fetchConsumerName));	
							$firstName=	$appointmentConsumerName[0]->firstName; 
							$lastName=	$appointmentConsumerName[0]->lastName;
						
							$serviceBookingDetail[$service['id']]['firstName'] = $firstName;					
							$serviceBookingDetail[$service['id']]['lastName'] = $lastName;				
							$serviceBookingDetail[$service['id']]['appointmentId'] = $id;				
							$serviceBookingDetail[$service['id']]['status'] = $status;				
							$serviceBookingDetail[$service['id']]['bookingType'] = $bookingType;
							$serviceBookingDetail[$service['id']]['bookingDate'] = $bookingDate;
							$serviceBookingDetail[$service['id']]['bookingTime'] = $bookingTime;				
						
							$serviceBookingTime[$service['id']] = $bookingTime;
							//abhi end   
							
							$arrAppointments[$time]['services'][$service['id']]['appointments'][$id]['firstName'] = $firstName;
							$arrAppointments[$time]['services'][$service['id']]['appointments'][$id]['lastName'] = $lastName;
							$arrAppointments[$time]['services'][$service['id']]['appointments'][$id]['appointmentId'] = $id;
							$arrAppointments[$time]['services'][$service['id']]['appointments'][$id]['status'] = $status;
							$arrAppointments[$time]['services'][$service['id']]['appointments'][$id]['bookingType'] = $bookingType;
							$arrAppointments[$time]['services'][$service['id']]['appointments'][$id]['bookingDate'] = $bookingDate;
							$arrAppointments[$time]['services'][$service['id']]['appointments'][$id]['bookingTime'] = $bookingTime;
							
						}
					} 
					else  
					{
						$arrServiceAllDetails[$service['id']]['booked'] = 0;
						$arrAppointments[$time]['services'][$service['id']]['booked'] = 0;				
					} 
					$arrServiceAllDetails[$service['id']]['vacant'] = $arrServiceAllDetails[$service['id']]['serviceAvailabilityMax'] - $arrServiceAllDetails[$service['id']]['booked'];
					
					$arrAppointments[$time]['services'][$service['id']]['vacant'] = $arrAppointments[$time]['services'][$service['id']]['serviceAvailabilityMax'] - $arrAppointments[$time]['services'][$service['id']]['booked'];
				
				}
				
	}else{
			$arrServiceAllDetails = '';
			$serviceBookingTime = '';
			$serviceBookingDetail = '';
			$arrAppointments = '';
			$serviceAvailabilityMax = '';
			$widthDivision = "100px";
			$this->get('session')->getFlashBag()->set('noServicesFound', 'No services Found');    	
			
	}	

			
			}
				
			
			//return $this->render('SalonSolutionWebBundle:Home:consumer_book_friend.html.twig', array('servicesDetail' => $arrServiceAllDetails, 'serviceAvailabilityMax' => $serviceAvailabilityMax, 'allSalons' => $allSalons, 'arrTime' => $arrTime , 'widthDivision'=>$widthDivision));
			
			
			return $this->render('SalonSolutionWebBundle:Home:consumer_book_friend.html.twig', array('servicesDetail' => $arrServiceAllDetails, 'serviceAvailabilityMax' => $serviceAvailabilityMax, 'allSalons' => $allSalons, 'arrTime' => $arrTime , 'widthDivision'=>$widthDivision, 'serviceBookingDetail'=>$serviceBookingDetail, 'arrAppointments'=>$arrAppointments));
		
			
			
			
		}
		/*------------------------------- End : Function to Consumer Book Friend  ------------------------------*/
		
		
		
		
		
		/**************************** Begin : Function to Consumer Profile  ********************************/
	  
		public function consumerProfileAction(Request $request)
		{
			
				$session = $this->getRequest()->getSession();
				if($session->get('consumerId') && $session->get('consumerId') != '')					
					$consumerId = $session->get('consumerId');	
				else
					return $this->redirect( $this->generateUrl('salon_solution_web_consumerLogin') );	
				 
				$repository = $this->getDoctrine()->getRepository('SalonSolutionWebBundle:SalonsolutionsUser');
				$consumerProfile = $repository->findBy(array('id' => $consumerId));			
			
				
			
			
			return $this->render('SalonSolutionWebBundle:Home:consumer_profile.html.twig', array('consumerProfile' => $consumerProfile));
		}
		/*------------------------------- End : Function to Consumer Profile  ------------------------------*/
		
		
		
		
		/**************************** Begin : Function to Consumer Edit Profile  ********************************/
	  
		public function consumerEditProfileAction(Request $request)
		{
			
				$session = $this->getRequest()->getSession();
			if($session->get('consumerId') && $session->get('consumerId') != '')					
				$consumerId = $session->get('consumerId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_web_consumerLogin') );	
				 
				$repository = $this->getDoctrine()->getRepository('SalonSolutionWebBundle:SalonsolutionsUser');
				$consumerEditProfile = $repository->findBy(array('id' => $consumerId));			
			
				if ($request->getMethod() == 'POST') 
					{					
						$firstName = $request->get("firstName");  	
						$lastName = $request->get("lastName");
						$email = $request->get("email");
						$username = $request->get("username");	
						$country = $request->get("country");						
						$state = $request->get("state");
						$address = $request->get("address");						
						$city = $request->get("city");						
						$zip = $request->get("zip"); 
						$mobile = $request->get("mobile"); 
						$landline = $request->get("landline"); 
						$basePath = $this->getBasePathAction();	  
						$photo = $_FILES['photo']['name'];  	
							$ranPhotoUpload = rand(1,10000);  		
							$targetFilePhoto = $basePath."/".$this->container->getParameter('gbl_uploadPath_consumers').$ranPhotoUpload.$photo;            //getBasePathAction() defined into upper 
							move_uploaded_file($_FILES['photo']['tmp_name'], $targetFilePhoto);					
					
						
						$em = $this->getDoctrine()->getEntityManager();
						$resultConsumer = $em->createQueryBuilder() 
						->select('tblconsumer')
						->update('SalonSolutionWebBundle:SalonsolutionsUser',  'tblconsumer')
						->set('tblconsumer.firstName', ':firstName')
						->setParameter('firstName', $firstName)
						->set('tblconsumer.lastName', ':lastName')
						->setParameter('lastName', $lastName)
						->set('tblconsumer.email', ':email')
						->setParameter('email', $email)
						->set('tblconsumer.username', ':username')
						->setParameter('username', $username)
						->set('tblconsumer.address', ':address')
						->setParameter('address', $address)
						->set('tblconsumer.country', ':country')
						->setParameter('country', $country)
						->set('tblconsumer.state', ':state')
						->setParameter('state', $state)
						->set('tblconsumer.city', ':city')
						->setParameter('city', $city)
						->set('tblconsumer.zip', ':zip')
						->setParameter('zip', $zip)
						->set('tblconsumer.mobile', ':mobile')
						->setParameter('mobile', $mobile)
						->set('tblconsumer.landline', ':landline')
						->setParameter('landline', $landline)
						->set('tblconsumer.photo', ':photo')
						->setParameter('photo', $ranPhotoUpload.$photo)
						->where('tblconsumer.id = :id')
						->setParameter('id', $consumerId)
						->getQuery()
						->getResult();		
						//echo "<pre>";	print_r($resultConsumer);   die();
																					// next ---------> table insert
					//	$customerId = $customer->getId();
						
					//	return $this->redirect($this->generateUrl('salon_solution_salon_manageConsumers'));  // redirect the page
			
					} 			
			
			return $this->render('SalonSolutionWebBundle:Home:consumer_edit_profile.html.twig', array('consumerEditProfile' => $consumerEditProfile));
		}
		
		
		/*------------------------------- End : Function to Consumer Edit Profile  ------------------------------*/
		
		
		
		
		
		
		/**************************** Begin : Function to Consumer Recover Password  ********************************/
	  
		public function consumerRecoverPasswordAction(Request $request)
		{
			
		$email=$this->get('request')->request->get('email');
		$em = $this->getDoctrine()->getEntityManager();
    		$repository = $em->getRepository('SalonSolutionWebBundle:SalonsolutionsUser');
    		
    		if ($request->getMethod() == 'POST') 
        	{
           		 $user = $repository->findOneBy(array('email' => $email));
           		
           		//echo "<pre>"; print_r($email); die;
            		if ($user) 
            		{   
					
						//	$newPassword=rand(100000,'999999');
						$newPassword = $this->generateRandomString();   
						echo $newPassword;
						$encPass=md5($newPassword);
						$realtors = $em->createQueryBuilder()
						->select('SalonsolutionsUser')
						->update('SalonSolutionWebBundle:SalonsolutionsUser',  'SalonsolutionsUser')
						->set('SalonsolutionsUser.password', ':password')
						->setParameter('password', $encPass)
						->where('SalonsolutionsUser.email=:email')
						->setParameter('email', $email)
						->getQuery()
						->getResult();
								
													
						$newPassword=  $this->generateRandomString();
					
						//password is encrypted into md5 
						$encPass=md5($newPassword); 
						$to = $email;
						$subject = "Password Reset";
						$txt=   "Hello <br><br>Your password has been reset on <br><br>Your new Password is: <b>".$newPassword."</b>";
						//$headers = "From: webmaster@example.com" . "\r\n" ;
						$headers = "MIME-Version: 1.0" . "\r\n";
						$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
						$headers .= "From: <support@salonSolution.com>" . "\r\n";
						 $retval = mail($to,$subject,$txt,$headers); //send mail      					 
						   if( $retval == true )
						   {
							  // return $this->render('SalonSolutionWebBundle:Home:consumer_login.html.twig');
							
							  echo "Message sent successfully...";
						   }
						   else
						   {
							   //return $this->render('SalonSolutionWebBundle:Home:consumer_forgotPassword.html.twig', array('name1' => 'Invalid Email'));
							  echo "Message could not be sent...";
						   }		  
											
					} 
				
		
			}
			return $this->render('SalonSolutionWebBundle:Home:consumer_forgotPassword.html.twig');
		}
		/*------------------------------- End : Function to Consumer RecoverPassword  ------------------------------*/

		function generateRandomString($length = 10) {
			return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
		}
		
		
		
		/**************************** Begin : Function to Consumer Change Password  ********************************/
	  
		public function consumerChangePasswordAction(Request $request)
		{
			
			$session = $this->getRequest()->getSession(); 	
			if($session->get('consumerId') && $session->get('consumerId') != '')					
				$consumerId = $session->get('consumerId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_web_consumerLogin') );				
		    $consumerId = $session->get('consumerId');				    
			$em = $this->getDoctrine()->getEntityManager();
			$consumerCurrentPassword = $em->createQueryBuilder() 
			->select('SalonsolutionsUser')
			->from('SalonSolutionWebBundle:SalonsolutionsUser',  'SalonsolutionsUser')
			->where('SalonsolutionsUser.id = :id')
			->setParameter('id', $consumerId)
			->andwhere('SalonsolutionsUser.type = :type')
			->setParameter('type', 3)
			->andwhere('SalonsolutionsUser.status = :status')
			->setParameter('status', 1)
			->getQuery()
			->getResult();			
			   $currentPassword = $consumerCurrentPassword[0]->password ;
					
				 if ($request->getMethod() == 'POST') 
					 {						
						 $oldPassword = $request->get("oldPassword");  	
						 $newPassword = $request->get("newPassword");
						 $repeatPassword = $request->get("repeatPassword");  //echo "<pre>"; print_r($oldPassword); die;
						 
							if( ($currentPassword == md5($oldPassword)) && ($newPassword == $repeatPassword) )
								{	
									$queryUpdatePassword = $em->createQueryBuilder() 
									->select('SalonsolutionsUser')
									->update('SalonSolutionWebBundle:SalonsolutionsUser',  'SalonsolutionsUser')
									->set('SalonsolutionsUser.password', ':password')
									->setParameter('password', md5($newPassword))
									->where('SalonsolutionsUser.id = :id')
									->setParameter('id', $consumerId)
									->getQuery()
									->getResult();								
									
									$this->get('session')->getFlashBag()->set('sucess', 'Your Password has been changed Sucessfully');
									
								}	
								elseif( ($currentPassword == '') && ($newPassword == '') && ($repeatPassword == '') )
								{
									
									$this->get('session')->getFlashBag()->set('error', 'Please enter your password');				
								}
								elseif( ($currentPassword != md5($oldPassword) ) && ($newPassword == '') && ($repeatPassword == '') )
								{
									
									$this->get('session')->getFlashBag()->set('error', 'Please enter your correct Old Password');				
								}
								elseif( ($currentPassword == md5($oldPassword)) && ($newPassword != $repeatPassword) )
								{
									$this->get('session')->getFlashBag()->set('newpasswordmessage', 'New Password Does not Match');	
								}
								else
								{
									$this->get('session')->getFlashBag()->set('error', 'Invalid Password Details');		
								}
								
															
							}
			
				
			
		
			return $this->render('SalonSolutionWebBundle:Home:consumer_changePassword.html.twig');
		}
		/*------------------------------- End : Function to Consumer Change Password  ------------------------------*/

		
		
		
		
		
		/**************************** Begin : Function to Display Consumer Appointments ********************************/
	  
		public function consumerAppointmentsAction()
		{
			
			
			
			$session = $this->getRequest()->getSession();
			if($session->get('consumerId') && $session->get('consumerId') != '')					
				$consumerId = $session->get('consumerId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_web_consumerLogin') );	 			
			$consumerId = $session->get('consumerId');		
			$repository = $this->getDoctrine()->getRepository('SalonSolutionWebBundle:SalonsolutionsAppointment');
			$appointmentDetail = $repository->findBy(array('consumerId' => $consumerId));	
			$salonId=	$appointmentDetail[0]->salonId;
			
			
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionWebBundle:SalonsolutionsUser');
			$consumerDetail = $repository->findBy(array('id' => $consumerId));			
			$consumerFirstName=	$consumerDetail[0]->firstName;		
			$consumerLastName=	$consumerDetail[0]->lastName;		
				
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionWebBundle:SalonsolutionsSalon');
			$salonDetail = $repository->findBy(array('id' => $salonId));
			$salonName=	$salonDetail[0]->name;
			$salonCity=	$salonDetail[0]->city;
			
			//echo "<pre>"; print_r($salonDetail); die;
			$em = $this->getDoctrine()->getEntityManager();
			$arrServive = $em->createQueryBuilder()
				->select('SalonsolutionsAppointment', 'SalonsolutionsService.title','SalonsolutionsService.color')
				->from('SalonSolutionWebBundle:SalonsolutionsAppointment', 'SalonsolutionsAppointment')
				->leftJoin('SalonSolutionWebBundle:SalonsolutionsService', 'SalonsolutionsService', "WITH", "SalonsolutionsService.id=SalonsolutionsAppointment.serviceId")
				->where('SalonsolutionsAppointment.status = :status')
				->setParameter('status', 1)
				->getQuery()
				->getArrayResult();
			
			$arrBooking = array();
			
			foreach($arrServive as $service)
			{
				$arrBooking[$service[0]['id']] = $service[0]; 
				$arrBooking[$service[0]['id']]['serviceTitle'] = $service['title']; 
				$arrBooking[$service[0]['id']]['serviceColor'] = $service['color']; 
			}
			
			//echo "<pre>"; print_r($arrServive); die;
			
			return $this->render('SalonSolutionWebBundle:Home:consumer_appointment.html.twig', array('appointmentDetail'=>$arrBooking , 'consumerDetail'=>$consumerDetail ,'consumerFirstName'=> $consumerFirstName, 'consumerLastName'=> $consumerLastName , 'salonName' => $salonName , 'salonCity' => $salonCity ));
		}
		/*------------------------------- End : Function to Display Consumer Appointments ------------------------------*/
		
		
		/**************************** Begin : Function to Delete Consumer Appointments ********************************/
	  
		public function deleteConsumerAppointmentAction($id )
		{
		
		$em = $this->getDoctrine()->getEntityManager();
			$del = $em->getRepository('SalonSolutionWebBundle:SalonsolutionsAppointment')->find($id);				
				
			if ($del) {
			$em->remove($del);
			$em->flush();
				return $this->redirect($this->generateUrl('salon_solution_web_consumerAppointments'));  // redirect the page
			}
		/* 	 $em = $this->getDoctrine()->getEntityManager();
				$confirmedPayment = $em->createQueryBuilder() 
				->select('SalonsolutionsAppointment')
				->update('SalonSolutionWebBundle:SalonsolutionsAppointment',  'SalonsolutionsAppointment')
				->set('SalonsolutionsAppointment.status', ':status')
				->setParameter('status', '5')
				->where('SalonsolutionsAppointment.id = :id')
				->setParameter('id', $id)
				->getQuery()
				->getResult();
				return $this->redirect($this->generateUrl('salon_solution_web_consumerAppointments')); 
			  */
			
			return $this->render('SalonSolutionWebBundle:Home:delete_consumer_appointment.html.twig');
		}
		/*------------------------------- End : Function to Delete Consumer Appointments ------------------------------*/
		
		
		/*********** Begin : Function to Display Consumer Appointment History ***********************/
	  
		public function consumerAppointmentHistoryAction()
		{
			$session = $this->getRequest()->getSession(); 
			if($session->get('consumerId') && $session->get('consumerId') != '')					
				$consumerId = $session->get('consumerId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_web_consumerLogin') );				
			$consumerId = $session->get('consumerId');		
			
			 $currentDate = date("d-m-Y");
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionWebBundle:SalonsolutionsAppointment');
			$appointmentDetail = $repository->findBy(array('consumerId' => $consumerId));	
			$salonId=	$appointmentDetail[0]->salonId;
			
			
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionWebBundle:SalonsolutionsUser');
			$consumerDetail = $repository->findBy(array('id' => $consumerId));			
			$consumerFirstName=	$consumerDetail[0]->firstName;		
			$consumerLastName=	$consumerDetail[0]->lastName;		
				
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionWebBundle:SalonsolutionsSalon');
			$salonDetail = $repository->findBy(array('id' => $salonId));
			$salonName=	$salonDetail[0]->name;
			$salonCity=	$salonDetail[0]->city;
			
			//echo "<pre>"; print_r($salonDetail); die;
			$em = $this->getDoctrine()->getEntityManager();
			$arrServive = $em->createQueryBuilder()
				->select('SalonsolutionsAppointment', 'SalonsolutionsService.title','SalonsolutionsService.color')
				->from('SalonSolutionWebBundle:SalonsolutionsAppointment', 'SalonsolutionsAppointment')
				->leftJoin('SalonSolutionWebBundle:SalonsolutionsService', 'SalonsolutionsService', "WITH", "SalonsolutionsService.id=SalonsolutionsAppointment.serviceId")
				->where('SalonsolutionsAppointment.status = :status')
				->setParameter('status', 1)
				->andWhere('SalonsolutionsAppointment.scheduledDate < :currentDate')
				->setParameter('currentDate', $currentDate)
				->getQuery()
				->getArrayResult();
			//echo "<pre>"; print_r($arrServive); die;
			
			$arrBooking = array();
			
			foreach($arrServive as $service)
			{
				$arrBooking[$service[0]['id']] = $service[0]; 
				$arrBooking[$service[0]['id']]['serviceTitle'] = $service['title']; 
				$arrBooking[$service[0]['id']]['serviceColor'] = $service['color']; 
			}
			
			//echo "<pre>"; print_r($arrBooking); die;
			
			return $this->render('SalonSolutionWebBundle:Home:consumer_appointmentHistory.html.twig', array('appointmentDetail'=>$arrBooking , 'consumerDetail'=>$consumerDetail ,'consumerFirstName'=> $consumerFirstName, 'consumerLastName'=> $consumerLastName , 'salonName' => $salonName , 'salonCity' => $salonCity ));
		}
		/*------------------------------- End : Function to Display Consumer Appointment History ------------------------------*/
		
		
		/**************************** Begin : Function to display Advertisement ********************************/
	  
		public function getAdvertisementsAction($salonId)
		
		{
					
			$em = $this->getDoctrine()->getEntityManager();
			$arrAdvertisements = $em->createQueryBuilder()
			->select('adver, salon.name, salon.advertisementDisplay')
			->from('SalonSolutionWebBundle:SalonsolutionsAdvertisement',  'adver')
			->leftJoin('SalonSolutionWebBundle:SalonsolutionsSalon', 'salon', "WITH", "salon.id=adver.salonId")
			->where('salon.id = :id')
			->setParameter('id', $salonId)				
			->getQuery()
			->getArrayResult();
			
			//echo "<pre>";	print_r($arrAdvertisements);   die();
			
			$Advertisements = array();
			//$Advertisements = $arrAdvertisements[0];
			
			foreach($arrAdvertisements as $advertisement)
			{
				$Advertisements[$advertisement[0]['id']] = $advertisement[0];
				$Advertisements[$advertisement[0]['id']]['name'] = $advertisement['name'];
				$Advertisements[$advertisement[0]['id']]['advertisementDisplay'] = $advertisement['advertisementDisplay'];
				
			} 
			
				//echo "<pre>";	print_r($Advertisements);   die();
								
			return $Advertisements;
		}
			/*------------------------------- End : Function to display Advertisement ------------------------------*/
		
		
		
		/**************************** Begin : Function to Salon Search customers ********************************/
	  
		public function searchSalonDataAction()
		{
			
			
			return $this->render('SalonSolutionWebBundle:Home:search_salons.html.twig');
		}
		/*------------------------------- End : Function to Salon Search customers ------------------------------*/
		
		
		
			
		/**************************** Begin : Function to About Us ********************************/
	  
		public function aboutUsAction()
		{
			echo "dsfsdf";
			
			return $this->render('SalonSolutionWebBundle:Home:about_us.html.twig');
		}
		/*------------------------------- End : Function to About Us ------------------------------*/
		 
		
		
		/**************************** Begin : Function to Set User Selected Date in Session ********************************/
	  
		public function setSelectedDateInSessionAction()
		{
			$session = $this->getRequest()->getSession();
			
			  $session->set('appointmentDate', $_POST['appointmentDate']); 
			
			return new response('SUCCESS');
		}
		/*------------------------------- End : Function to Set User Selected Date in Session ------------------------------*/
		
		
		
		/**************************** Begin : Function to Book Consumer Appointment ********************************/
	  
		public function bookConsumerAppointmentAction()
		{
			
			$session = $this->getRequest()->getSession();
			
			$appointmentTime = $_POST['appointmentTime'];
			$appointmentSalonId = $_POST['appointmentSalonId'];
			$appointmentServiceId = $_POST['appointmentServiceId'];
			$appointmentConsumerId = $_POST['appointmentConsumerId'];
			//$appointmentStatus = $_POST['appointmentStatus'];
			
		/* if( $session->get('appointmentDate') )
				$appointmentDate = $session->get('appointmentDate');	
			else
				$appointmentDate = date("d-m-Y"); */
			
			if( $session->get('appointmentDate') ){
			 $appointmentDate = $session->get('appointmentDate');	
					$pieces = explode("-", $appointmentDate);
					 $dayPiece=$pieces[0]; 
					 $monthPiece=$pieces[1]+1; 
					 $YearPiece=$pieces[2]; 					 
					if(strlen($monthPiece) == 1){
						$monthPiece = '0'.$monthPiece;
					}
				$finalAppointmentDate	= $dayPiece.'-'.$monthPiece.'-'.$YearPiece ;
			}else{
				$finalAppointmentDate	= date("d-m-Y");				
			}
					
			$bookAppointment = new SalonsolutionsAppointment();
						
			$bookAppointment->setConsumerId($appointmentConsumerId);   
			$bookAppointment->setSalonId($appointmentSalonId);
			$bookAppointment->setServiceId($appointmentServiceId);
			$bookAppointment->setScheduledDate($finalAppointmentDate);
			$bookAppointment->setScheduledTime($appointmentTime);
			 $bookAppointment->setStatus('0');   
			
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($bookAppointment);
			$em->flush();
			
			return new response('SUCCESS');
		
		}
		/*------------------------------- End : Function to Book Consumer Appointment ------------------------------*/	

		
		/**************************** Begin : Function to Load Consumer's Un-Confirmed Appointments ********************************/
	  
		public function consumerUnConfirmedAppointmentsAction( Request $request)
		{

			$session = $this->getRequest()->getSession();
			if($session->get('consumerId') && $session->get('consumerId') != '')					
				$consumerId = $session->get('consumerId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_web_consumerLogin') );				
			$consumerId = $session->get('consumerId');		
			$repository = $this->getDoctrine()->getRepository('SalonSolutionWebBundle:SalonsolutionsAppointment');
			$appointmentDetail = $repository->findBy(array('consumerId' => $consumerId, 'status' => 0));	
			
			//$friendName = 	$appointmentDetail[0]->firstName.' '.$appointmentDetail[0]->lastName;
			$friendName = 	$appointmentDetail[0]->firstName.' '.$appointmentDetail[0]->lastName;
			
			
			$salonId=	$appointmentDetail[0]->salonId;
			
			
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionWebBundle:SalonsolutionsUser');
			$consumerDetail = $repository->findBy(array('id' => $consumerId));			
			$consumerFirstName=	$consumerDetail[0]->firstName;		
			$consumerLastName=	$consumerDetail[0]->lastName;	 	
				
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionWebBundle:SalonsolutionsSalon');
			$salonDetail = $repository->findBy(array('id' => $salonId));
			$salonName=	$salonDetail[0]->name;
			$salonCity=	$salonDetail[0]->city;
			
			//echo "<pre>"; print_r($salonDetail); die;
			$em = $this->getDoctrine()->getEntityManager();
			$arrServive = $em->createQueryBuilder()
				->select('SalonsolutionsAppointment', 'SalonsolutionsService.title','SalonsolutionsService.color')
				->from('SalonSolutionWebBundle:SalonsolutionsAppointment', 'SalonsolutionsAppointment')
				->leftJoin('SalonSolutionWebBundle:SalonsolutionsService', 'SalonsolutionsService', "WITH", "SalonsolutionsService.id=SalonsolutionsAppointment.serviceId")
				->where('SalonsolutionsAppointment.status = :status')
				->setParameter('status', 0)
				->getQuery()
				->getArrayResult();
			
			$arrBooking = array();
			
			foreach($arrServive as $service)
			{
				$arrBooking[$service[0]['id']] = $service[0]; 
				$arrBooking[$service[0]['id']]['serviceTitle'] = $service['title']; 
				$arrBooking[$service[0]['id']]['serviceColor'] = $service['color']; 
				$arrBooking[$service[0]['id']]['friendName'] = ($friendName != NULL && $friendName != '') ? $friendName : ''; 
			}
			
			//echo "<pre>"; print_r($arrBooking); die;
			
			return $this->render('SalonSolutionWebBundle:Home:consumer_unConfirmedAppointments.html.twig', array('appointmentDetail'=>$arrBooking , 'consumerDetail'=>$consumerDetail ,'consumerFirstName'=> $consumerFirstName, 'consumerLastName'=> $consumerLastName , 'salonName' => $salonName , 'salonCity' => $salonCity ));
			
		}
		/*--------------- End : Function to Load Consumer's Un-Confirmed Appointments -----------------------*/
		
		/**************** Begin : Function to Confirm Consumer Appointment ********************************/
	  
		public function confirmConsumerAppointmentAction()
		{ 
			$session = $this->getRequest()->getSession();
			
			$appointmentId = $_POST['appointmentId'];
			
			$em = $this->getDoctrine()->getEntityManager();
			$confirmedPayment = $em->createQueryBuilder() 
			->select('SalonsolutionsAppointment')
			->update('SalonSolutionWebBundle:SalonsolutionsAppointment',  'SalonsolutionsAppointment')
			->set('SalonsolutionsAppointment.bookingType', ':bookingType')
			->setParameter('bookingType', '1')               //online booking by consumer
			->set('SalonsolutionsAppointment.status', ':status')
			->setParameter('status', '1')
			->where('SalonsolutionsAppointment.id = :id')
			->setParameter('id', $appointmentId)
			->getQuery()
			->getResult();
			
			return new response('SUCCESS');
		}
		/*------------------------------- End : Function to Confirm Consumer Appointment ------------------------------*/	
		
		
		
		/* abhi Book Friend Appointment  Start*/
		
		
		
			/**************************** Begin : Function to Book Consumer Appointment ********************************/
	  
		public function bookFriendAppointmentsAction( Request $request)
		{
			$session = $this->getRequest()->getSession();
			
			$appointmentTime = $_POST['appointmentTime'];
			$appointmentSalonId = $_POST['appointmentSalonId'];
			$appointmentServiceId = $_POST['appointmentServiceId'];
			$appointmentConsumerId = $_POST['appointmentConsumerId'];
			
			/* if($session->get('appointmentDate'))
				$appointmentDate = $session->get('appointmentDate');
			else
				$appointmentDate = date("d-m-Y"); */
			


			 if($session->get('appointmentDate')){
				$appointmentDate = $session->get('appointmentDate');	
					$pieces = explode("-", $appointmentDate);
					 $dayPiece=$pieces[0]; 
					 $monthPiece=$pieces[1]+1; 
					 $YearPiece=$pieces[2]; 					 
					if(strlen($monthPiece) == 1){
						$monthPiece = '0'.$monthPiece;
					}
				$finalAppointmentDate	= $dayPiece.'-'.$monthPiece.'-'.$YearPiece ;
			 }
			else{
					$finalAppointmentDate = date("d-m-Y"); 
			}
	
			
			
			if ($request->getMethod() == 'POST') 
			{
				 
				$friendFN = $request->get("friendFN");  
				$friendLN = $request->get("friendLN");
				$friendEmail = $request->get("friendEmail");
				$friendPhone = $request->get("friendPhone");							
				
				$bookAppointment = new SalonsolutionsAppointment();
				
				
				$bookAppointment->setConsumerId($appointmentConsumerId);   
				$bookAppointment->setSalonId($appointmentSalonId);
				$bookAppointment->setServiceId($appointmentServiceId);
				$bookAppointment->setScheduledDate($finalAppointmentDate);
				$bookAppointment->setScheduledTime($appointmentTime);
				$bookAppointment->setStatus('0');
				 
				$bookAppointment->setFirstName($friendFN);   
				$bookAppointment->setLastName($friendLN);
				$bookAppointment->setEmail($friendEmail);				
				$bookAppointment->setMobile($friendPhone);
					
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($bookAppointment);
				$em->flush();
				
			}
				
			return new response('SUCCESS');
			
				}
		/*------------------- End : Function to Book Consumer Appointment ---------------*/	
			
			/************* Begin : Function to Advertisement (ADS) ***************/
	  
		public function salonAdsAction()
		{
					
			$session = $this->getRequest()->getSession();
				
			$arrServerName = explode('.salonsolutions.ca', $_SERVER['SERVER_NAME']);
			$salonDomain = 	$arrServerName[0];	

			if( $salonDomain == 'tanonline' )
			return $this->redirect( $this->generateUrl('salon_solution_web_index') );

			//echo $salonDomain."<PRE>";print_r($_SERVER);die;
			$params = array("domainName" => $salonDomain);
			$salonDetail = $this->getSalonAction($params);
			//echo $salonDomain."<PRE>";print_r($salonDetail);die;
			//foreach($salonDetail as $salonDetail);

			$salonId = $salonDetail[0]['id'];
				
				//echo $salonDomain."<PRE>";print_r($salonId);die;
				
			//$salonId=	$salonDetail['id'];
			//$repository = $this->getDoctrine()->getRepository('SalonSolutionWebBundle:SalonsolutionsAdvertisement');
			//$salonAdvertisement = $repository->findBy(array('salonId' => $salonId));			
			
			$em = $this->getDoctrine()->getEntityManager();
			$salonAdvertisement = $em->createQueryBuilder() 
			->select('SalonsolutionsAdvertisement', 'SalonsolutionsSalon.advertisementDisplay')
			->from('SalonSolutionWebBundle:SalonsolutionsAdvertisement', 'SalonsolutionsAdvertisement')
			->leftJoin('SalonSolutionWebBundle:SalonsolutionsSalon', 'SalonsolutionsSalon', "WITH", "SalonsolutionsSalon.id=SalonsolutionsAdvertisement.salonId")
			->where('SalonsolutionsAdvertisement.salonId = :salonId')
			->setParameter('salonId', $salonId)
			//->addOrderBy('SalonsolutionsAdvertisement.id','RAND()')
			//->addOrderBy('SalonsolutionsAdvertisement.id', 'DESC')			
			//->setMaxResults(3)						  
			->getQuery()
			->getArrayResult();
			 
			$salonAdvertisement = $this->getAdvertisementsAction($salonId);
			
			//echo "<PRE>";print_r($salonAdvertisement);  die;    //abhi adver
			$advertisementDisplay = 2;
			foreach($salonAdvertisement as $ad);
			$advertisementDisplay = $ad['advertisementDisplay'];
			
			/*if( array_key_exists(0, $salonAdvertisement) && array_key_exists('advertisementDisplay', $salonAdvertisement[0]) )		
				$session->set('advertisementDisplay' , $salonAdvertisement[0]['advertisementDisplay']);
			else 
			{
				if( array_key_exists('advertisementDisplay', $salonAdvertisement) )	
				{
					$session->set('advertisementDisplay' , $salonAdvertisement['advertisementDisplay']);
				}
				else
					$session->set('advertisementDisplay' , 3);
			}*/
				
			$session->set('advertisementDisplay', $advertisementDisplay);
			
			$arrAdvertisement = array();

			foreach($salonAdvertisement as $ads)
			{ 
				$arrAdvertisement[$ads['id']] = $ads; 
			}
			
			$totalAdvertisements = count($arrAdvertisement);
			
	 		if($totalAdvertisements < 3)
			{ 
				$session->set('leftRandomAdvertisements' , $arrAdvertisement);
				$session->set('rightRandomAdvertisements' , $arrAdvertisement);
			}
			else
			{
				$totalAdvertisements = 3;
				
				// Left Ads Section
				$leftRandomAds = array_rand($arrAdvertisement, $totalAdvertisements);
				foreach($leftRandomAds as $leftRandomAd)
				{
					$leftRandomAdvertisements[] = $arrAdvertisement[$leftRandomAd]; 	
				}
				$session->set('leftRandomAdvertisements' , $leftRandomAdvertisements);	
				
				// Right Ads Section
				$rightRandomAds = array_rand($arrAdvertisement, $totalAdvertisements);
				foreach($rightRandomAds as $rightRandomAd)
				{
					$rightRandomAdvertisements[] = $arrAdvertisement[$rightRandomAd]; 	
				}
				$session->set('rightRandomAdvertisements' , $rightRandomAdvertisements);
			}
			//echo "<PRE>";print_r( $leftRandomAdvertisements);die;
			return true;
		}  
			
		/*--------- End : Function to Advertisement (ADS) -----------*/	
		
		
		
				/************* Begin : Function to Compare User Booking ***************/
	  
		public function compareUserBookingAction()
		{
					
		 	//Consumer Advertisment Start 
			$session = $this->getRequest()->getSession();

			$arrServerName = explode('.salonsolutions.ca', $_SERVER['SERVER_NAME']);
			$salonDomain = 	$arrServerName[0];	

			if( $salonDomain == 'tanonline' )
			return $this->redirect( $this->generateUrl('salon_solution_web_index') );

			//echo $salonDomain."<PRE>";print_r($_SERVER);die;
			$params = array("domainName" => $salonDomain);
			$salonDetail = $this->getSalonAction($params);
			//echo "<PRE>";print_r($salonDetail);die; 
			foreach($salonDetail as $salonDetail);

			$salonId = $salonDetail['id'];
					
			$salonMaxBookingsCustom = $salonDetail['maxBookingsCustom'];
			$salonMaxBookingsDefault = $salonDetail['maxBookingsDefault'];
			
			if( $salonMaxBookingsCustom > 0 )
			{
					$maxAvailableBookings = $salonMaxBookingsCustom;
			}
			else
			{
				$maxAvailableBookings = $salonMaxBookingsDefault;
			}
			//echo $session->get('appointmentDate');die;
			
			$a = explode('-',$session->get('appointmentDate'));
			if( strlen($a[1]) < 2 )
				$appointmentDate = $a[0].'-0'.$a[1].'-'.$a[2];  
			else
				$appointmentDate = $session->get('appointmentDate');
			
			//echo $appointmentDate;die;
			
			$em = $this->getDoctrine()->getEntityManager();
			$userAppointments = $em->createQueryBuilder() 
			->select('SalonsolutionsAppointment')
			->from('SalonSolutionWebBundle:SalonsolutionsAppointment', 'SalonsolutionsAppointment')
			->where('SalonsolutionsAppointment.salonId = :salonId')
			->setParameter('salonId', $salonId)
			->andWhere('SalonsolutionsAppointment.consumerId = :consumerId')
			->setParameter('consumerId', $session->get('consumerId'))
			->andWhere('SalonsolutionsAppointment.scheduledDate = :scheduledDate')
			->setParameter('scheduledDate', $appointmentDate)
			->getQuery()
			->getArrayResult();
			
			//echo "<PRE>";print_r($userAppointments);die;
			
			if( $userAppointments && is_array($userAppointments) && count($userAppointments) > 0 )
			{
				$appointmentsBookedByUser = count($userAppointments[0]);
				
				if( $appointmentsBookedByUser >= $maxAvailableBookings )
				{
					return new response('MAX_BOOKED');
				}
				else
				{
					return new response('AVAILABLE');
				} 
			}
			else
			{
				return new response('AVAILABLE');
			} 
		}  
			
		/*--------- End : Function to  Compare User Booking  -----------*/	
		
			


			/************* Begin : Function to  Set Salon In Session  ***************/
	  
		public function setSalonInSessionAction()
		{ 
					
			$session = $this->getRequest()->getSession();
			
			$salonId = $_POST['salonId'];
			
			$session->set('salonId', $salonId);
			return new response('SUCCESS');
			
		}  
			
		/*--------- End : Function to  Compare User Booking  -----------*/	
		
		

	
		
	}
