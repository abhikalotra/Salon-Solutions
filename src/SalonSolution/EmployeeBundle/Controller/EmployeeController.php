<?php

namespace SalonSolution\EmployeeBundle\Controller;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	
	use Symfony\Component\HttpFoundation\Request;   
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	
	use SalonSolution\EmployeeBundle\Entity\SalonsolutionsUser; 					 //SalonsolutionsUser
	use SalonSolution\EmployeeBundle\Entity\SalonsolutionsPayment;   	      		   //SalonsolutionsPayment
	use SalonSolution\EmployeeBundle\Resources\SalonsolutionsUserType;  			  //SalonsolutionsUserType
	use SalonSolution\EmployeeBundle\Entity\SalonsolutionsAdvertisement;         	   //SalonsolutionsAdvertisement
	use SalonSolution\EmployeeBundle\Entity\SalonsolutionsAdvertisementType;            //SalonsolutionsAdvertisement
	use SalonSolution\EmployeeBundle\Entity\SalonsolutionsEmployeeMessages;            //SalonsolutionsEmployeeMessages	
	use SalonSolution\EmployeeBundle\Entity\SalonsolutionsAppointment;            //SalonsolutionsAppointment	
	use SalonSolution\EmployeeBundle\Entity\SalonsolutionsCms;            				//SalonsolutionsCms	
	use SalonSolution\EmployeeBundle\Entity\SalonsolutionsSalon;         			   //SalonsolutionsSalon
	use SalonSolution\EmployeeBundle\Resources\SalonsolutionsSalonType;  			  //SalonsolutionsSalonType	
			
	use Symfony\Component\HttpKernel\Exception\HttpException;
	use Symfony\Component\HttpFoundation\Session\Session;
	use Symfony\Component\HttpFoundation\RedirectResponse;
	
	
	use Symfony\Component\HttpFoundation\File\UploadedFile;

	use Symfony\Component\HttpFoundation\Cookie;


class EmployeeController extends Controller
{
    public function loginAction(Request $request)
    {

			$session = $this->getRequest()->getSession();			
			$userSession = $this->getLoggedInEmployeeDetailAction();    //function name given below //check :- for enter dashoboard into the -path without  login then it will not show
						
				if($userSession)
					return $this->redirect($this->generateUrl('salon_solution_employee_employeeDashboard'));   // check end
			
			$arrServerName = explode('.salonsolutions.ca', $_SERVER['SERVER_NAME']);
			$salonDomain = 	$arrServerName[0];	
			
			if( $salonDomain == 'tanonline' )
				return $this->redirect( $this->generateUrl('salon_solution_web_index') );
					
			//echo $salonDomain."<PRE>";print_r($_SERVER);die;
			$params = array("domainName" => $salonDomain);
			$salonDetail = $this->getSalonAction($params);
		
			foreach($salonDetail as $salonDetail);
		
			$salonLogo = $salonDetail['logo'];
			$salonId = $salonDetail['id'];
		
			$session->set('salonLogo', $salonLogo);
		
			
		


			
			
			$em = $this->getDoctrine()->getEntityManager();
			$repository = $em->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsUser');
			
				if ($request->getMethod() == 'POST')
				{
					//$session->clear();
					
					$email = $request->get('email');    	
					$remember = $request->get('remember');   
					$password = md5($request->get('password'));							
					 
						$twoDays = 60 * 60 * 24 * 2 + time();
						setcookie('email', $email, $twoDays);
						setcookie('password', $password, $twoDays);
							//echo "<pre>"; print_r($cookieeEmail); die;
					
				
					$employee = $repository->findOneBy(array('email' => $email, 'password' => $password,'type' =>'4','status' =>'1'));
					//	echo "<pre>"; print_r($remember); die;
						
							
					if($employee !='')
					{										 
						 $session->set('employeeId', $employee->getId());    	
						 $setemployeeid = $session->get('employeeId', $employee->getId());   
						 
						 $session->set('salonCityId', $employee->getSalonId());    	
						 $setSalonCityId = $session->get('salonCityId', $employee->getSalonId());   
						   
							
						 $session->set('employeeEmail', $employee->getEmail());    	
						 $setEmail = $session->get('employeeEmail', $employee->getEmail());   
						 
						 $session->set('employeefirstName', $employee->getfirstName());
						 $setname = $session->get('employeefirstName', $employee->getfirstName());   
						 
						 
						
						 
							return $this->redirect($this->generateUrl('salon_solution_employee_employeeDashboard'));
					}
					else
					{	
						$this->get('session')->getFlashBag()->set('error', 'Invalid Email or Password');
						
					}
				}
			
		
        return $this->render('SalonSolutionEmployeeBundle:Home:employee_login.html.twig');
    }
    
				
			/**************************** Begin : Function to get the details of logged-in employee ********************************/
			public function getLoggedInEmployeeDetailAction()
			{
				 $session = $this->getRequest()->getSession();                     //check :- for enter dashoboard into the -
																					// path without  login then it will not show
				if( $session->get('employeeId') && $session->get('employeeId') != '' )
					return true;
				else
					return false;
			}
			/*------------------------------- End : Function to get the details of logged-in Employee ------------------------------*/
			
	
			
		/**************************** END : Function to Login the Employee ********************************/
	
	
	
			
		/**************************** Begin : Function to Logout the Employee  ********************************/
	  
		public function logoutAction()
		{
			
			 $session = $this->getRequest()->getSession();
					$session->clear('foo');
					$session->remove('foo');
					unset($session);
						return $this->redirect($this->generateUrl('salon_solution_employee_login'));
			
			return $this->render('SalonSolutionEmployeeBundle:Home:employee_logout.html.twig');
		}
		/*------------------------------- End : Function to Logout the Employee ------------------------------*/
		
		
	
	
			
		/**************************** Begin : Function to Employee Dashboard  ********************************/
	  
		public function dashboardAction()
		{
		 	$session = $this->getRequest()->getSession();
			
			
			 	$salonCityId = $session->get('salonCityId');	 
			
			if($session->get('employeeId') && $session->get('employeeId') != '')					
				$employeeId = $session->get('employeeId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_employee_login') );	
			
			$arrServerName = explode('.salonsolutions.ca', $_SERVER['SERVER_NAME']);
			$salonDomain = 	$arrServerName[0];	
			
			if( $salonDomain == 'tanonline' )
				return $this->redirect( $this->generateUrl('salon_solution_web_index') );
					
			//echo $salonDomain."<PRE>";print_r($_SERVER);die;
			$params = array("domainName" => $salonDomain);
			$salonDetail = $this->getSalonAction($params);
		
			foreach($salonDetail as $salonDetail);
		
			$salonLogo = $salonDetail['logo'];
			$salonId = $salonDetail['id'];
		
			
			//echo "<PRE>";print_r($salonId);die;
			
			$em = $this->getDoctrine()->getEntityManager();
			$arrRecent = $em->createQueryBuilder()
				->select('SalonsolutionsAppointment', 'SalonsolutionsService.title','SalonsolutionsService.color', 'SalonsolutionsUser.firstName', 'SalonsolutionsUser.lastName', 'SalonsolutionsService.displayName', 'SalonsolutionsSalon.displayName')
				->from('SalonSolutionEmployeeBundle:SalonsolutionsAppointment', 'SalonsolutionsAppointment')
				->leftJoin('SalonSolutionEmployeeBundle:SalonsolutionsService', 'SalonsolutionsService', "WITH", "SalonsolutionsService.id=SalonsolutionsAppointment.serviceId")
				->leftJoin('SalonSolutionEmployeeBundle:SalonsolutionsUser', 'SalonsolutionsUser', "WITH", "SalonsolutionsUser.id=SalonsolutionsAppointment.consumerId")
				->leftJoin('SalonSolutionEmployeeBundle:SalonsolutionsSalon', 'SalonsolutionsSalon', "WITH", "SalonsolutionsSalon.id=SalonsolutionsAppointment.salonId")
				->where('SalonsolutionsAppointment.salonId = :salonId')
				->setParameter('salonId', $salonCityId)
					//->addOrderBy('SalonsolutionsAppointment.id','RAND()')
				->addOrderBy('SalonsolutionsAppointment.id', 'DESC')			
				->setMaxResults(10)						  
				->getQuery()
				->getArrayResult();
				//echo "<pre>"; print_r($arrRecent); die;
			$arrRecentAppointment = array();
			
			foreach($arrRecent as $recent)
			{ 
				$arrRecentAppointment[$recent[0]['id']] = $recent[0]; 
				$arrRecentAppointment[$recent[0]['id']]['recentTitle'] = $recent['title']; 
				$arrRecentAppointment[$recent[0]['id']]['recentFirstName'] = $recent['firstName']; 
				$arrRecentAppointment[$recent[0]['id']]['recentLastName'] = $recent['lastName']; 
				$arrRecentAppointment[$recent[0]['id']]['recentDisplayName'] = $recent['displayName']; 
				$arrRecentAppointment[$recent[0]['id']]['recentColor'] = $recent['color']; 
				
			}
				
			//echo "<pre>"; print_r($arrRecentAppointment); die;
			
			
			$session = $this->getRequest()->getSession(); 	
			$employeeId = $session->get('employeeId');				
			$repository = $this->getDoctrine()->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsUser');
			$salonId = $repository->findBy(array('id' => $employeeId));			
			$getSalonId = $salonId[0]->salonId ;
			
			$em = $this->getDoctrine()->getEntityManager();
			$consumerDetails = $em->createQueryBuilder()->select('User')
				->from('SalonSolutionEmployeeBundle:SalonsolutionsUser', 'User')
				->andwhere('User.type = :type')
				->setParameter('type', 3) 		
				//->andwhere('User.status = :status')
				//->setParameter('status', 1)		
				->andwhere('User.salonId = :salonId')
				->setParameter('salonId', $getSalonId)		
				->getQuery() 
				->getResult();
			//echo "<PRE>";print_r($consumerDetails);die;
		
		
		/////////////////////////////////////////////////////////////////////////////		
			
			/* $repository = $this->getDoctrine()->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsUser');
			$parentId = $repository->findBy(array('id' => $employeeId));			
			
			 $flashMessage = $parentId[0]->parentId ;
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsEmployeeMessages');
			$message = $repository->findBy(array('salonOwnerId' => $flashMessage , 'type' => 'Employee Message'));			
			$showMessage =	$message[0]->message;
			
			$session->set('employeeMessage' , $showMessage);
			$setMessage = $session->get('employeeMessage', $showMessage);  
		
				/* if($session->get('appointmentDate'))
					$appointmentDate = $session->get('appointmentDate');
				else
					$appointmentDate = date("d-m-Y");
				  */
				  
				  /*if( $session->get('appointmentDate') ){
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
			$salonId = $salonDetail['id'];
		
			$session->set('salonLogo', $salonLogo);
		
			
			$userSession = $this->getLoggedInConsumerDetailAction();         //function name given below 					
			$employeeId = $session->get('employeeId');	
			 //	echo "<pre>"; print_r($salon); die;	
			$repository = $this->getDoctrine()->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsUser');
			$consumerProfile = $repository->findBy(array('id' => $employeeId));			
			$employeeId =  $consumerProfile[0]->parentId;
			// echo "<pre>"; print_r($consumerProfile); die;
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsSalon');
			$salon = $repository->findBy(array('ownerId' => $employeeId));			
			$ownerId =  $salon[0]->ownerId;
			
			//Consumer Offering/Flash Message Start 
				$repository = $this->getDoctrine()->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsEmployeeMessages');
				$message = $repository->findBy(array('salonOwnerId' => $ownerId , 'type' => 'Offering Message'));			
				$showMessage =	$message[0]->message;

				$session->set('offeringMessage' , $showMessage);
				$setMessage = $session->get('offeringMessage', $showMessage);   
				//Consumer Offering/Flash Message End 
				
				
		
			 	//echo "<pre>"; print_r($ownerId); die;
			
			$em = $this->getDoctrine()->getEntityManager();
			$allSalons = $em->createQueryBuilder() 
			->select('SalonsolutionsSalon')
			->from('SalonSolutionEmployeeBundle:SalonsolutionsSalon',  'SalonsolutionsSalon')
			->where('SalonsolutionsSalon.ownerId = :ownerId')
			->setParameter('ownerId', $ownerId)
			->getQuery()
			->getArrayResult();	
			
			foreach($allSalons as $salon)
			{
				$arrSalonIds = $salon['id'];
			}
			
			//echo "<pre>"; print_r($arrSalonIds); die;
			
			$em = $this->getDoctrine()->getEntityManager();
			/*$servicesDetail = $em->createQueryBuilder() 
			->select('SalonsolutionsService', 'SalonsolutionsServiceAvailability')
			->from('SalonSolutionEmployeeBundle:SalonsolutionsService',  'SalonsolutionsService')
			->leftJoin('SalonSolutionEmployeeBundle:SalonsolutionsServiceAvailability', 'SalonsolutionsServiceAvailability', "WITH", "SalonsolutionsServiceAvailability.serviceId=SalonsolutionsService.id")
			->where('SalonsolutionsService.salonId in(:salonId)')
			->setParameter('salonId', $arrSalonIds)
			->getQuery()
			->getResult();*/
			
			/*$servicesDetail = $em->createQueryBuilder() 
			->select('SalonsolutionsService')
			->from('SalonSolutionEmployeeBundle:SalonsolutionsService',  'SalonsolutionsService')
			->where('SalonsolutionsService.salonId in(:salonId)')
			->setParameter('salonId', $arrSalonIds)
			->getQuery()
			->getArrayResult();		
			
			$totalServices =	count($servicesDetail);
			$widthDivision	= 81/$totalServices;
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
				->from('SalonSolutionEmployeeBundle:SalonsolutionsAppointment',  'SalonsolutionsAppointment')
				->where('SalonsolutionsAppointment.scheduledDate= :scheduledDate')
				->setParameter('scheduledDate', $finalAppointmentDate)
				->andWhere('SalonsolutionsAppointment.serviceId= :serviceId')
				->setParameter('serviceId', $service['id'])			
				->andWhere('SalonsolutionsAppointment.status= :status')
				->setParameter('status', 1)
				->getQuery()
				->getArrayResult();		
					//echo "<pre>"; print_r($appointmentDetail); die;
				
				if( $serviceAvailabilityMax < $service['availability'] )
				{
					$serviceAvailabilityMax = $service['availability'];
				}
				
				 	//echo $appointmentDate."<pre>"; print_r($appointmentDetail); die;
					
				if( isset($appointmentDetail) && is_array($appointmentDetail) && count($appointmentDetail) > 0 )
				{					
					$arrServiceAllDetails[$service['id']]['booked'] = count($appointmentDetail);				
					
					//abhi start 
					$id =	$appointmentDetail[0]['id'] ;				
					 $status =	$appointmentDetail[0]['status'] ;				// die;
					 $bookingType =	$appointmentDetail[0]['bookingType'] ;				// die; 
					$fetchConsumerName =	$appointmentDetail[0]['consumerId'] ;				
					$repository = $this->getDoctrine()->getRepository('SalonSolutionEmployeeBundle:Salonsolutionsuser');
					$appointmentConsumerName = $repository->findBy(array('id' => $fetchConsumerName));	
					$firstName=	$appointmentConsumerName[0]->firstName; 
					$lastName=	$appointmentConsumerName[0]->lastName;
					//$consumerId=	$appointmentConsumerName[0]->id;
						//echo "<pre>"; print_r($firstName); die;
					
					$arrServiceAllDetails[$service['id']]['firstName'] = $firstName;					
					$arrServiceAllDetails[$service['id']]['lastName'] = $lastName;				
					$arrServiceAllDetails[$service['id']]['appointmentId'] = $id;				
					$arrServiceAllDetails[$service['id']]['status'] = $status;				
					$arrServiceAllDetails[$service['id']]['bookingType'] = $bookingType;				
					//$arrServiceAllDetails[$service['id']]['consumerId'] = $consumerId;				
					 
					//echo "<pre>"; print_r($id); die;
					//abhi end   
				}
				else  
				{
					$arrServiceAllDetails[$service['id']]['booked'] = 0;				
				} 
				$arrServiceAllDetails[$service['id']]['vacant'] = $service['availability'] - $arrServiceAllDetails[$service['id']]['booked'];
				//echo "<pre>";   print_r($arrServiceAllDetails[$service['id']]['vacant']); die;
			}
			
			
			
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
			//echo $salonId;die;
			//$arrTime = $this->getBusinessHours($ownerId, $appointmentDate);
			$arrTime = $this->getBusinessHours($ownerId, $finalAppointmentDate);
			return $this->render('SalonSolutionEmployeeBundle:Home:employee_dashboard.html.twig' , array('servicesDetail' => $arrServiceAllDetails, 'serviceAvailabilityMax' => $serviceAvailabilityMax, 'allSalons' => $allSalons, 'arrTime' => $arrTime, 'widthDivision'=>$widthDivision));
			 */
			
			
			
			
			
			return $this->render('SalonSolutionEmployeeBundle:Home:employee_dashboard.html.twig' , array('arrRecentAppointment' => $arrRecentAppointment));
		}
		/*------------------------------- End : Function to Employee Dashboard------------------------------*/
		
		
		 
		/**************************** Begin : Function to Delete Recent Appointment ********************************/
	  
		public function deleteRecentAppointmentAction($id, Request $request)
		{			
					$em = $this->getDoctrine()->getEntityManager();
					$del = $em->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsAppointment')->find($id);				
			
					if ($del) {
					$em->remove($del);
					$em->flush();
						return $this->redirect($this->generateUrl('salon_solution_employee_employeeDashboard'));  // redirect the page
					} 
			
						
			return $this->render('SalonSolutionEmployeeBundle:Page:employee_delete_recent_appointment.html.twig');

			
		}
		/*------------------------------- End : Function to  Delete Recent Appointment ------------------------------*/
		
		
		
		
	/**************************** Begin : Function to Employee Profile  ********************************/
	  
		public function employeeProfileAction(Request $request)
		{
			
				$session = $this->getRequest()->getSession();
				
				if($session->get('employeeId') && $session->get('employeeId') != '')					
					$employeeId = $session->get('employeeId');	
				else
					return $this->redirect( $this->generateUrl('salon_solution_employee_login') );	
				 
				$repository = $this->getDoctrine()->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsUser');
				$employeeProfile = $repository->findBy(array('id' => $employeeId));			
			
			return $this->render('SalonSolutionEmployeeBundle:Home:employee_profile.html.twig', array('employeeProfile' => $employeeProfile));
		}
		/*------------------------------- End : Function to Employee Profile  ------------------------------*/
			
		
		
		/**************************** Begin : Function to Employee Edit Profile  ********************************/
	  
		public function editProfileAction(Request $request)
		{
			
				$session = $this->getRequest()->getSession();
				
				if($session->get('employeeId') && $session->get('employeeId') != '')					
					$employeeId = $session->get('employeeId');	
				else
					return $this->redirect( $this->generateUrl('salon_solution_employee_login') );
				 
				$repository = $this->getDoctrine()->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsUser');
				$employeeEditProfile = $repository->findBy(array('id' => $employeeId));			
			
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
						$resultEmployee = $em->createQueryBuilder() 
						->select('tblemployee')
						->update('SalonSolutionEmployeeBundle:SalonsolutionsUser',  'tblemployee')
						->set('tblemployee.firstName', ':firstName')
						->setParameter('firstName', $firstName)
						->set('tblemployee.lastName', ':lastName')
						->setParameter('lastName', $lastName)
						->set('tblemployee.email', ':email')
						->setParameter('email', $email)
						->set('tblemployee.username', ':username')
						->setParameter('username', $username)
						->set('tblemployee.address', ':address')
						->setParameter('address', $address)
						->set('tblemployee.country', ':country')
						->setParameter('country', $country)
						->set('tblemployee.state', ':state')
						->setParameter('state', $state)
						->set('tblemployee.city', ':city')
						->setParameter('city', $city)
						->set('tblemployee.zip', ':zip')
						->setParameter('zip', $zip)
						->set('tblemployee.mobile', ':mobile')
						->setParameter('mobile', $mobile)
						->set('tblemployee.landline', ':landline')
						->setParameter('landline', $landline)
						->set('tblemployee.photo', ':photo')
						->setParameter('photo', $ranPhotoUpload.$photo)
						->where('tblemployee.id = :id')
						->setParameter('id', $employeeId)
						->getQuery()
						->getResult();		
						//echo "<pre>";	print_r($resultEmployee);   die();
						
						return $this->redirect($this->generateUrl('salon_solution_employee_employeeProfile'));  // redirect 

					} 			
			
			return $this->render('SalonSolutionEmployeeBundle:Home:employee_edit_profile.html.twig', array('employeeEditProfile' => $employeeEditProfile));
		}
		
		
		/*------------------------------- End : Function to Employee Edit Profile  ------------------------------*/
		
		
			
		/**************************** Begin : Function to Employee Change Password  ********************************/
	  
		public function employeeChangePasswordAction(Request $request)
		{
			
			$session = $this->getRequest()->getSession(); 	
					
	     	if($session->get('employeeId') && $session->get('employeeId') != '')					
				$employeeId = $session->get('employeeId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_employee_login') );	
					     
			$em = $this->getDoctrine()->getEntityManager();
			$employeeCurrentPassword = $em->createQueryBuilder() 
			->select('SalonsolutionsUser')
			->from('SalonSolutionEmployeeBundle:SalonsolutionsUser',  'SalonsolutionsUser')
			->where('SalonsolutionsUser.id = :id')
			->setParameter('id', $employeeId)
			->andwhere('SalonsolutionsUser.type = :type')
			->setParameter('type', 4)
			->andwhere('SalonsolutionsUser.status = :status')
			->setParameter('status', 1)
			->getQuery()
			->getResult();			
			   $currentPassword = $employeeCurrentPassword[0]->password ;
					
				 if ($request->getMethod() == 'POST') 
					 {					
						 $oldPassword = $request->get("oldPassword");  	
						 $newPassword = $request->get("newPassword");
						 $repeatPassword = $request->get("repeatPassword");  //echo "<pre>"; print_r($oldPassword); die;
						
							if( ($currentPassword == md5($oldPassword)) && ($newPassword == $repeatPassword) && ($newPassword != '') && ($repeatPassword != '') )
								{	
									$queryUpdatePassword = $em->createQueryBuilder() 
									->select('SalonsolutionsUser')
									->update('SalonSolutionEmployeeBundle:SalonsolutionsUser',  'SalonsolutionsUser')
									->set('SalonsolutionsUser.password', ':password')
									->setParameter('password', md5($newPassword))
									->where('SalonsolutionsUser.id = :id')
									->setParameter('id', $employeeId)
									->getQuery()
									->getResult();								
									
									$this->get('session')->getFlashBag()->set('success', 'Your Password has been changed Successfully');
									
									
								}	  
								elseif( ($currentPassword == '') && ($newPassword == '') && ($repeatPassword == '') )
								{
									
									$this->get('session')->getFlashBag()->set('error', 'Please Enter Your Password');				
								}
								elseif( ($currentPassword != md5($oldPassword) ) && ($newPassword == '') && ($repeatPassword == '') )
								{
									
									$this->get('session')->getFlashBag()->set('error', 'Please Enter Your Correct Old Password');				
								}
								elseif( ($currentPassword == md5($oldPassword)) && ($newPassword != $repeatPassword) )
								{
									$this->get('session')->getFlashBag()->set('newpasswordmessage', 'New Password Does not Match');	
								}
								else
								{
									$this->get('session')->getFlashBag()->set('error', 'Invalid Password Details');		
								}
								
															
						 //return $this->redirect($this->generateUrl('salon_solution_admin_manageCMS'));  // redirect the page
					}
			
		
			return $this->render('SalonSolutionEmployeeBundle:Home:employee_changePassword.html.twig');
	
		}
		/*------------------------------- End : Function to Employee Change Password  ------------------------------*/

		 
		
			
		/**************************** Begin : Function to Employee appointment ********************************/
	  
		public function appointmentAction(Request $request)
		{ 
			    
			
		
			  
			$session = $this->getRequest()->getSession(); 			
			
		$salonCityId = $session->get('salonCityId');
			
			
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
			$salonId = $salonDetail['id'];
		
			$session->set('salonLogo', $salonLogo);
		
			
			$userSession = $this->getLoggedInConsumerDetailAction();         //function name given below 
			if($session->get('employeeId') && $session->get('employeeId') != '')					
				$employeeId = $session->get('employeeId');	
			
					
			else
				return $this->redirect( $this->generateUrl('salon_solution_employee_login') );
			
			$salonCityId = $session->get('salonCityId');
			
			 //	echo "<pre>"; print_r($salon); die;	
			$repository = $this->getDoctrine()->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsUser');
			$consumerProfile = $repository->findBy(array('id' => $employeeId));			
			$employeeId =  $consumerProfile[0]->parentId;
			// echo "<pre>"; print_r($consumerProfile); die;
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsSalon');
			$salon = $repository->findBy(array('ownerId' => $employeeId));			
			$ownerId =  $salon[0]->ownerId;
			
				
				
			//Consumer Offering/Flash Message Start 
				$repository = $this->getDoctrine()->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsEmployeeMessages');
				$message = $repository->findBy(array('salonOwnerId' => $ownerId , 'type' => 'Offering Message'));			
				$showMessage =	$message[0]->message;

				$session->set('offeringMessage' , $showMessage);
				$setMessage = $session->get('offeringMessage', $showMessage);   
				//Consumer Offering/Flash Message End 
				
				
		
				//echo "<pre>"; print_r($ownerId); die;
			
			$em = $this->getDoctrine()->getEntityManager();
			$allSalons = $em->createQueryBuilder() 
			->select('SalonsolutionsSalon')
			->from('SalonSolutionEmployeeBundle:SalonsolutionsSalon',  'SalonsolutionsSalon')
			->where('SalonsolutionsSalon.ownerId = :ownerId')
			->setParameter('ownerId', $ownerId)
			->getQuery()
			->getArrayResult();	
			
			
			
			foreach($allSalons as $salon)
			{
				$arrSalonIds = $salon['id'];
			}
			
			$arrServiceAllDetails = array();
			$serviceBookingTime = array();
			$serviceBookingDetail = array();
			$arrAppointments = array();
			
			$arrTime = $this->getBusinessHours($ownerId, $finalAppointmentDate);
			//echo "<PRE>";print_r($arrTime);die;
			foreach($arrTime as $time)
			{
				$em = $this->getDoctrine()->getEntityManager();
			
				$arrAppointments[$time]['time'] = $time;
				
				
				
				$servicesDetail = $em->createQueryBuilder() 
				->select('SalonsolutionsService')
				->from('SalonSolutionEmployeeBundle:SalonsolutionsService',  'SalonsolutionsService')
				->where('SalonsolutionsService.salonId in(:salonId)')
				->setParameter('salonId', $salonCityId)
				->getQuery()
				->getArrayResult();		
			
	if($servicesDetail){
				
							//	echo "<pre>";print_r($servicesDetail);	die;
				$totalServices = count($servicesDetail);
				$widthDivision = 81/$totalServices;
				
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
		
			
			return $this->render('SalonSolutionEmployeeBundle:Home:employee_appointment.html.twig' , array('servicesDetail' => $arrServiceAllDetails, 'serviceAvailabilityMax' => $serviceAvailabilityMax, 'allSalons' => $allSalons, 'arrTime' => $arrTime, 'widthDivision'=>$widthDivision, 'serviceBookingDetail'=>$serviceBookingDetail, 'arrAppointments'=>$arrAppointments));
			
			
			//return $this->render('SalonSolutionEmployeeBundle:Home:employee_appointment.html.twig' , array('servicesDetail' => $arrServiceAllDetails, 'serviceAvailabilityMax' => $serviceAvailabilityMax, 'allSalons' => $allSalons, 'arrTime' => $arrTime, 'widthDivision'=>$widthDivision, 'serviceBookingDetail'=>$serviceBookingDetail, 'arrAppointments'=>$arrAppointments));
	
		}
		
		public function getSalonAction($params)
		{
			$em = $this->getDoctrine()->getEntityManager();
			if( array_key_exists('criteria', $params) && $params['criteria'] == 'LIKE' )
			{
				$arrSalon = $em->createQueryBuilder()->select('Salon')
				->from('SalonSolutionEmployeeBundle:SalonsolutionsSalon', 'Salon')
				->where('Salon.name LIKE :salonName')
				->setParameter('salonName', $params['salonSearchKey'].'%')
				->getQuery()
				->getArrayResult();
			}
			else if( array_key_exists('domainName', $params) && $params['domainName'] != '' )
			{
				$arrSalon = $em->createQueryBuilder()->select('Salon')
				->from('SalonSolutionEmployeeBundle:SalonsolutionsSalon', 'Salon')
				->where('Salon.domain=:domainName')
				->setParameter('domainName', $params['domainName'])
				->getQuery()
				->getArrayResult();
			}
			else
			{
				$arrSalon = $em->createQueryBuilder()->select('Salon')
				->from('SalonSolutionEmployeeBundle:SalonsolutionsSalon', 'Salon')
				->where('Salon.name=:salonName')
				->setParameter('salonName', $params['salonSearchKey'])
				->getQuery()
				->getArrayResult();
			}
			//echo "<PRE>";print_r($arrSalon);die;
			return $arrSalon;
		}
		
			public function getLoggedInConsumerDetailAction()
		{
			 $session = $this->getRequest()->getSession();              //check :- for enter dashoboard into the -
																	// path without  login then it will not show
			if( $session->get('consumerId') && $session->get('consumerId') != '' )
				return true;
			else
				return false;
		}
		/*------------------------------- End : Function to Employee appointment  ------------------------------*/

		//abhi start 
		/*********** Begin : Function to Book Consumer Appointment ********************************/
	  
		 public function bookConsumerAppointmentAction(Request $request)
		{
			  
			$session = $this->getRequest()->getSession();
			
		 	$appointmentTime = $_POST['appointmentTime']; 
			$appointmentSalonId = $_POST['appointmentSalonId'];
			$appointmentServiceId = $_POST['appointmentServiceId'];
			 $appointmentConsumerId = $_POST['appointmentConsumerId'];
			 
				/* if($session->get('appointmentDate'))
					$appointmentDate = $session->get('appointmentDate');
				else
					$appointmentDate = date("d-m-Y");
				  */
				  
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
			$bookAppointment->setBookingType('0');    
			$bookAppointment->setStatus('1');
			
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($bookAppointment);
			$em->flush();
			
			return new response('SUCCESS');
		}  
		/*-------------------------- End : Function to Book Consumer Appointment ------------------------------*/	
		
		
		
		
		
		/*************** Begin : Function to Load Consumer's Un-Confirmed Appointments ********************************/
	  
		/* public function consumerUnConfirmedAppointmentsAction()
		{
			$session = $this->getRequest()->getSession(); 			
			$consumerId = $session->get('consumerId');		
			$repository = $this->getDoctrine()->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsAppointment');
			$appointmentDetail = $repository->findBy(array('consumerId' => $consumerId, 'status' => '0'));	
			$salonId=	$appointmentDetail[0]->salonId;
			
			
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsUser');
			$consumerDetail = $repository->findBy(array('id' => $consumerId));			
			$consumerFirstName=	$consumerDetail[0]->firstName;		
			$consumerLastName=	$consumerDetail[0]->lastName;		
				
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsSalon');
			$salonDetail = $repository->findBy(array('id' => $salonId));
			$salonName=	$salonDetail[0]->name;
			$salonCity=	$salonDetail[0]->city;
			
			//echo "<pre>"; print_r($salonDetail); die;
			$em = $this->getDoctrine()->getEntityManager();
			$arrServive = $em->createQueryBuilder()
				->select('SalonsolutionsAppointment', 'SalonsolutionsService.title','SalonsolutionsService.color')
				->from('SalonSolutionEmployeeBundle:SalonsolutionsAppointment', 'SalonsolutionsAppointment')
				->leftJoin('SalonSolutionEmployeeBundle:SalonsolutionsService', 'SalonsolutionsService', "WITH", "SalonsolutionsService.id=SalonsolutionsAppointment.serviceId")
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
			}
			
			//echo "<pre>"; print_r($arrServive); die;
			
			return $this->render('SalonSolutionEmployeeBundle:Home:consumer_unConfirmedAppointments.html.twig', array('appointmentDetail'=>$arrBooking , 'consumerDetail'=>$consumerDetail ,'consumerFirstName'=> $consumerFirstName, 'consumerLastName'=> $consumerLastName , 'salonName' => $salonName , 'salonCity' => $salonCity ));
			
		} */
		/*---------------- End : Function to Load Consumer's Un-Confirmed Appointments ------------------------------*/
		
		/************************* Begin : Function to Confirm Consumer Appointment ********************************/
	  
		/* public function confirmConsumerAppointmentAction()
		{
			$session = $this->getRequest()->getSession();
			
			$appointmentId = $_POST['appointmentId'];
			
			$em = $this->getDoctrine()->getEntityManager();
			$confirmedPayment = $em->createQueryBuilder() 
			->select('SalonsolutionsAppointment')
			->update('SalonSolutionEmployeeBundle:SalonsolutionsAppointment',  'SalonsolutionsAppointment')
			->set('SalonsolutionsAppointment.status', ':status')
			->setParameter('status', '1')
			->where('SalonsolutionsAppointment.id = :id')
			->setParameter('id', $appointmentId)
			->getQuery()
			->getResult();
			
			return new response('SUCCESS');
		} */
		/*---------------- End : Function to Confirm Consumer Appointment ------------------------------*/	
		
		
		//abhi end
			
		/**************************** Begin : Function to Employee appointment ********************************/
	  
		public function bookAppointmentAction(Request $request)
		{
			$em = $this->getDoctrine()->getEntityManager();
			$services = $em->createQueryBuilder() 
			->select('SalonsolutionsService')
			->from('SalonSolutionEmployeeBundle:SalonsolutionsService',  'SalonsolutionsService')
			->getQuery()
			->getResult();			
			 
			//echo "<pre>"; print_r($services); die;
		
			return $this->render('SalonSolutionEmployeeBundle:Home:employee_book_appointment.html.twig' ,array('services' => $services));
	
		}
		
		
		
		public function searchConsumersAction()
		{
			$consumersHtml = '';
			if( isset($_POST['consumerSearchKey']) && $_POST['consumerSearchKey'] != '' )
			{
				
				$consumerSearchKey = $_POST['consumerSearchKey'];
				
				//echo $salonSearchKey; die;
				
				if( array_key_exists('criteria', $_POST) && $_POST['criteria'] != '' )
					$criteria = $_POST['criteria'];
				else
					$criteria = 'LIKE';
					
				$params = array('criteria'=>$criteria, 'consumerSearchKey'=>$consumerSearchKey);
				$arrConsumer = $this->getConsumerAction($params);
				
				//echo "<pre>"; print_r($arrConsumer); die;
				
				if( is_array($arrConsumer) && count($arrConsumer) > 0 )
				{
					foreach($arrConsumer as $consumer)
					{
						$consumersHtml.='<li id="'.$consumer['id'].'" onclick="javascript:selectConsumer('.$consumer['id'].', \''.$consumer['firstName'].'\');">'.$consumer['firstName'].'  '.$consumer['lastName'].'</li>';
					}
				}
				
			}
			return new response($consumersHtml);
		}
		
		
		
		public function getConsumerAction($params)
		{
			$session = $this->getRequest()->getSession(); 	
			$employeeId = $session->get('employeeId');				
			$repository = $this->getDoctrine()->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsUser');
			$salonId = $repository->findBy(array('id' => $employeeId));			
			$getSalonId = $salonId[0]->salonId ;
				 
			
			$em = $this->getDoctrine()->getEntityManager();
			if( array_key_exists('criteria', $params) && $params['criteria'] == 'LIKE' )
			{
				$arrConsumer = $em->createQueryBuilder()->select('User')
				->from('SalonSolutionEmployeeBundle:SalonsolutionsUser', 'User')
				->where('User.firstName LIKE :consumerName')
				->setParameter('consumerName', $params['consumerSearchKey'].'%')
				->andwhere('User.type = :type')
				->setParameter('type', 3) 		
				->andwhere('User.status = :status')
				->setParameter('status', 1)		
				->andwhere('User.salonId = :salonId')
				->setParameter('salonId', $getSalonId)		
				->getQuery() 
				->getArrayResult();
				
					//echo "<PRE>";print_r($arrConsumer);die;
			}
			else if( array_key_exists('consumerName', $params) && $params['consumerName'] != '' )
			{
				$arrConsumer = $em->createQueryBuilder()->select('User')
				->from('SalonSolutionEmployeeBundle:SalonsolutionsUser', 'User')
				->where('Salon.firstName=:consumerName')
				->setParameter('consumerName', $params['consumerName'])
				->andwhere('User.type = :type')
				->setParameter('type', 3)			
				->andwhere('User.status = :status')
				->setParameter('status', 1)	
				->andwhere('User.salonId = :salonId')
				->setParameter('salonId', $getSalonId)								
				->getQuery()
				->getArrayResult();
			}
			else
			{
				$arrConsumer = $em->createQueryBuilder()->select('User')
				->from('SalonSolutionEmployeeBundle:SalonsolutionsUser', 'User')
				->where('User.firstName=:consumerName')
				->setParameter('consumerName', $params['consumerSearchKey'])
				->andwhere('User.type = :type')
				->setParameter('type', 3)			
				->andwhere('User.status = :status')
				->setParameter('status', 1)			
				->andwhere('User.salonId = :salonId')
				->setParameter('salonId', $getSalonId)		
				->getQuery()
				->getArrayResult();
			}
			//echo "<PRE>";print_r($arrConsumer);die;
			return $arrConsumer;
		}
		
		/*------------------------------- End : Function to Employee appointment  ------------------------------*/

		 
		 
		 
		public function changeAppointmentStatusAction(Request $request)
		{
		 	  $appointmentId = $_POST['appointmentId']; 			 
		 	   $appointmentStatus = $_POST['appointmentStatus']; 			 
		 	 //$appointmentStatus = $_POST['appointmentStatus']; 			 
				
				$em = $this->getDoctrine()->getEntityManager();				
				$arrAppointmentId= $em->createQueryBuilder()
				->select('Appointment')
				->update('SalonSolutionEmployeeBundle:SalonsolutionsAppointment',  'Appointment')
				->set('Appointment.status', ':status')
				->setParameter('status', $appointmentStatus)		
				->andwhere('Appointment.id = :id')
				->setParameter('id', $appointmentId)		
				->getQuery()
				->getArrayResult();
						
			 
		
			return new response('SUCCESS');
		}  		
		/*-------------------------- End : Function to Book Consumer Appointment ------------------------------*/	
		
		 
		public function saveAppointmentNotesAction()
		{ 
		 	  $appointmentId = $_POST['appointmentId']; 	
		 	  $notes = $_POST['notes']; 	
			  
				$em = $this->getDoctrine()->getEntityManager();				
				$arrAppointmentId= $em->createQueryBuilder()
				->select('Appointment')
				->update('SalonSolutionEmployeeBundle:SalonsolutionsAppointment',  'Appointment')
				->set('Appointment.notes', ':notes')
				->setParameter('notes', $notes)		
				->andwhere('Appointment.id = :id')
				->setParameter('id', $appointmentId)		
				->getQuery()
				->getArrayResult();
				
		
			return new response('SUCCESS');
		}  
		/*-------------------------- End : Function to BookingType Appointment ------------------------------*/	
		
		 
		 	 
		public function appointmentBookingTypeAction(Request $request)
		{			
			$statusHtml = '';
				
		 	  $appointmentId = $_POST['appointmentId']; 			 
		 	  	
				
				$em = $this->getDoctrine()->getEntityManager();				
				$arrAppointment= $em->createQueryBuilder()
				->select('Appointment')
				->from('SalonSolutionEmployeeBundle:SalonsolutionsAppointment',  'Appointment')		
				->where('Appointment.id = :id')
				->setParameter('id', $appointmentId)		 
				->getQuery()
				->getResult();
				$getBookingType = $arrAppointment[0]->bookingType;
				if($getBookingType == 0)
				{
					$getBookingTypeOFFLINE = 'OFFLINE (Salon Booking)';
					$statusHtml.='<span class="offlineSalonBooking">'.$getBookingTypeOFFLINE.'</span>'; 
				}
				elseif($getBookingType == 1) 
				{
					$getBookingTypeONLINE = 'ONLINE (Web Booking)';
					$statusHtml.='<span class="onlineWebBooking">'.$getBookingTypeONLINE.'</span>'; 
				}	
		
			return new response($statusHtml);
		}  		
		/*-------------------------- End : Function to BookingType Appointment ------------------------------*/	
		
		
			
		/**************************** Begin : Function to Add Client ********************************/
	  
		public function addClientAction(Request $request)
		{
			
			$session = $this->getRequest()->getSession();
			
		 	$firstName = $_POST['firstName'];  
			$lastName = $_POST['lastName'];
			$email = $_POST['email'];
			$mobile = $_POST['mobile'];
			 
			/* if($session->get('appointmentDate'))
					$appointmentDate = $session->get('appointmentDate');
				else
					$appointmentDate = date("d-m-Y");
				  */
				  
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
			 
			 
			$addNewClient = new SalonsolutionsUser();
						 
			$addNewClient->setFirstName($firstName);   
			$addNewClient->setLastName($lastName);
			$addNewClient->setEmail($email);
			$addNewClient->setMobile($mobile);
			$addNewClient->setStatus('1');
			$addNewClient->setType('5');
			
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($addNewClient);
			$em->flush(); 
			 
			 $getLastNewClientId = $addNewClient->getId();
			
			return new response($getLastNewClientId);			
		
			
		}
		/*------------------------------- End : Function to Add Client  ------------------------------*/

		
				/************* Begin : Function to Compare User Booking ***************/
	  
		public function compareUserBookingAction(Request $request)
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
			 
			if( $session->get('appointmentDate') )
			{  
				$arrAppointmentDate = explode('-',$session->get('appointmentDate'));   //echo "<PRE>";print_r($a); die; 
			}
			else
			{ 
				$arrAppointmentDate = explode('-',date('Y-m-d'));
			}
			if( strlen($arrAppointmentDate[1]) < 2 )
				$appointmentDate = $arrAppointmentDate[0].'-0'.$arrAppointmentDate[1].'-'.$arrAppointmentDate[2];  
			else 
				$appointmentDate = $session->get('appointmentDate');
			  
			//echo $a;die; 
			
			$em = $this->getDoctrine()->getEntityManager();
			$userAppointments = $em->createQueryBuilder() 
			->select('SalonsolutionsAppointment')
			->from('SalonSolutionEmployeeBundle:SalonsolutionsAppointment', 'SalonsolutionsAppointment')
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
		
		
		
		
		
			
		/**************************** Begin : Function to Employee appointment ********************************/
	  
		public function bookNewAppointmentAction(Request $request)
		{
			
			
		
			return $this->render('SalonSolutionEmployeeBundle:Home:employee_book_new_appointment.html.twig');
	
		}
		/*------------------------------- End : Function to Employee appointment  ------------------------------*/

		
		
			
		/**************************** Begin : Function to Employee Clients  ********************************/
	  
		public function clientsAction(Request $request)
		{
			
			$session = $this->getRequest()->getSession(); 	
			
			if($session->get('employeeId') && $session->get('employeeId') != '')					
				$employeeId = $session->get('employeeId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_employee_login') );	
			
			$employeeId = $session->get('employeeId');				
			$repository = $this->getDoctrine()->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsUser');
			$salonId = $repository->findBy(array('id' => $employeeId));			
			$getSalonId = $salonId[0]->salonId ;
			
			$em = $this->getDoctrine()->getEntityManager();
			$consumerDetails = $em->createQueryBuilder()->select('User')
				->from('SalonSolutionEmployeeBundle:SalonsolutionsUser', 'User')
				->andwhere('User.type = :type')
				->setParameter('type', 3) 		
				//->andwhere('User.status = :status')
				//->setParameter('status', 1)		
				->andwhere('User.salonId = :salonId')
				->setParameter('salonId', $getSalonId)		
				->getQuery() 
				->getResult();
			//echo "<PRE>";print_r($consumerDetails);die;
			return $this->render('SalonSolutionEmployeeBundle:Home:employee_clients.html.twig' , array('consumerDetails' => $consumerDetails));
	
		}
		
				//--- Begin : Function to  Change Employee Status Clients  ------------------------------*/
					public function changeStatusClientsAction()
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
						
						if( isset($_POST['objectType']) && $_POST['objectType'] == 'Client' )
						{
							$em = $this->getDoctrine()->getEntityManager();
							$confirmedSubscribe = $em->createQueryBuilder() 
							->select('ser')
							->update('SalonSolutionEmployeeBundle:SalonsolutionsUser',  'ser')
							->set('ser.status', ':status')
							->setParameter('status', $status)
							->where('ser.id = :id')
							->setParameter('id', $id)
							->getQuery()
							->getResult();
						}
						$statusHtml.='<a id="status-'.$id.'" class="edit" title="Click to Change" onclick="javascript:changeStatusClients(\'status-'.$id.'\','.$status.');">'.$statusString.'</a>'; 
						
						return new response($statusHtml);				
					} 
						/*------ End : Function to  change Employee Status Clients  -----------*/
			
		/*------------------------------- End : Function to Employee Clients  ------------------------------*/

				
		/**************************** Begin : Function to  View Clients  ********************************/
	  
		public function viewClientAction($id , Request $request)
		{			
			//echo $id; die;
			$repository = $this->getDoctrine()->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsUser');
			$viewClientsDetails = $repository->findBy(array('id' => $id));			
			
			
			//echo "<PRE>";print_r($viewClientsDetails);die;
			return $this->render('SalonSolutionEmployeeBundle:Home:employee_view_client.html.twig' , array('viewClientsDetails' => $viewClientsDetails));
	
		}
		/*------------------------------- End : Function to  View Clients  ------------------------------*/

		/**************************** Begin : Function to  Edit Clients  ********************************/
	  
		public function editClientAction($id , Request $request)
		{		

			$session = $this->getRequest()->getSession(); 	
				if($session->get('employeeId') && $session->get('employeeId') != '')					
				$employeeId = $session->get('employeeId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_employee_login') );	
			
			$employeeId = $session->get('employeeId');	
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsUser');
			$clientEditProfile = $repository->findBy(array('id' => $id));			
			
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
					
						
						$em = $this->getDoctrine()->getEntityManager();
						$resultEmployee = $em->createQueryBuilder() 
						->select('tblemployee')
						->update('SalonSolutionEmployeeBundle:SalonsolutionsUser',  'tblemployee')
						->set('tblemployee.firstName', ':firstName')
						->setParameter('firstName', $firstName)
						->set('tblemployee.lastName', ':lastName')
						->setParameter('lastName', $lastName)
						->set('tblemployee.email', ':email')
						->setParameter('email', $email)
						->set('tblemployee.username', ':username')
						->setParameter('username', $username)
						->set('tblemployee.address', ':address')
						->setParameter('address', $address)
						->set('tblemployee.country', ':country')
						->setParameter('country', $country)
						->set('tblemployee.state', ':state')
						->setParameter('state', $state)
						->set('tblemployee.city', ':city')
						->setParameter('city', $city)
						->set('tblemployee.zip', ':zip')
						->setParameter('zip', $zip)
						->set('tblemployee.mobile', ':mobile')
						->setParameter('mobile', $mobile)					
						->where('tblemployee.id = :id')
						->setParameter('id', $employeeId)
						->getQuery()
						->getResult();	
						return $this->redirect($this->generateUrl('salon_solution_employee_employeeClients'));  
					} 	 
			
			//echo "<PRE>";print_r($clientEditProfile);die;
			return $this->render('SalonSolutionEmployeeBundle:Home:employee_edit_client.html.twig' , array('clientEditProfile' => $clientEditProfile));
	
		}
		/*------------------------------- End : Function to Edit Clients  ------------------------------*/
		
		/**************************** Begin : Function to Delete Clients ********************************/
	  
		public function deleteClientAction($id, Request $request)
		{			
					/*$em = $this->getDoctrine()->getEntityManager();
					$del = $em->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsUser')->find($id);				
			
					if ($del) {
					$em->remove($del);
					$em->flush();
						return $this->redirect($this->generateUrl('salon_solution_employee_employeeClients'));  // redirect the page
					} */
					$em = $this->getDoctrine()->getEntityManager();
					$del = $em->createQueryBuilder() 
					->select('tblUser')
					->update('SalonSolutionEmployeeBundle:SalonsolutionsUser',  'tblUser')
					->set('tblUser.status', ':status')
					->setParameter('status', 2)								
					->where('tblUser.id = :id')
					->setParameter('id', $id)
					->getQuery()
					->getResult();	
					return $this->redirect($this->generateUrl('salon_solution_employee_employeeClients'));  
				
						
			return $this->render('SalonSolutionEmployeeBundle:Page:employee_delete_client.html.twig');

			
		}
		/*------------------------------- End : Function to Delete Clients ------------------------------*/
		
		
		/**************************** Begin : Function to Service Maintenance ********************************/
	  
		public function serviceMaintenanceAction(Request $request)
		{
			$session = $this->getRequest()->getSession();
			
			$salonCityId = $session->get('salonCityId');
				
			$arrServerName = explode('.salonsolutions.ca', $_SERVER['SERVER_NAME']);
			$salonDomain = 	$arrServerName[0];	

			if( $salonDomain == 'tanonline' )
			return $this->redirect( $this->generateUrl('salon_solution_web_index') );

			//echo $salonDomain."<PRE>";print_r($_SERVER);die;
			$params = array("domainName" => $salonDomain);
			$salonDetail = $this->getSalonAction($params);	
		
			foreach($salonDetail as $salonDetail);
			$salonId = $salonDetail['id'];
			
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsService');
			$serviceMaintenance = $repository->findBy(array('salonId' => $salonCityId,'status' => 1 ));			
			//$serviceMaintenance = $repository->findBy(array('salonId' => $salonId,'status' => 1 ));			
			
			
			if ($request->getMethod() == 'POST') 
				{					
					$serviceNameId = $request->get("serviceName");
					$status = $request->get("status");
					 
					$em = $this->getDoctrine()->getEntityManager();
					$resultEmployee = $em->createQueryBuilder() 
					->select('tblService')
					->update('SalonSolutionEmployeeBundle:SalonsolutionsService',  'tblService')
					->set('tblService.status', ':status')
					->setParameter('status', 2)								
					->where('tblService.id = :id')
					->setParameter('id', $serviceNameId)
					->getQuery()
					->getResult();	
					return $this->redirect($this->generateUrl('salon_solution_employee_serviceMaintenance'));  
				} 	 
			
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsService');
			$serviceUnderMaintenance = $repository->findBy(array('salonId' => $salonCityId, 'status' => 2 ));			
			
			
			return $this->render('SalonSolutionEmployeeBundle:Home:employee_service_maintenance.html.twig', array('serviceMaintenance' => $serviceMaintenance, 'serviceUnderMaintenance' => $serviceUnderMaintenance));
	
		}
		/*------------------------------- End : Function to Employee appointment  ------------------------------*/
 
		
		
		/**************************** Begin : Function to change Under Maintenance Status ***********/
		public function changeUnderMaintenanceStatusAction()
		{		
			 $id = $_POST['id'];
			
			$em = $this->getDoctrine()->getEntityManager();
			$confirmedSubscribe = $em->createQueryBuilder() 
			->select('ser')
			->update('SalonSolutionSalonBundle:SalonsolutionsService',  'ser')
			->set('ser.status', ':status')
			->setParameter('status', 1)
			->where('ser.id = :id')
			->setParameter('id', $id)
			->getQuery()
			->getResult();
			
			
			return new response();

		}  		
		/*------------------------- End : Function to change Under Maintenance Status  ---------------------*/

		
		
		
		
		
			
		/**************************** Begin : Function to Arrive Late Status ********************************/
	  
		public function arriveLateStatusAction(Request $request)
		{
			$session = $this->getRequest()->getSession();
				
			$salonCityId = $session->get('salonCityId');
			
			
			$arrServerName = explode('.salonsolutions.ca', $_SERVER['SERVER_NAME']);
			$salonDomain = 	$arrServerName[0];	

			if( $salonDomain == 'tanonline' )
			return $this->redirect( $this->generateUrl('salon_solution_web_index') );

			$params = array("domainName" => $salonDomain);
			$salonDetail = $this->getSalonAction($params);	
		
			foreach($salonDetail as $salonDetail);
			$salonId = $salonDetail['id'];
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsAppointment');
			$arriveLate = $repository->findBy(array('salonId' => $salonId));			
			 
			 
			$em = $this->getDoctrine()->getEntityManager();
			$arryArriveLateStatus = $em->createQueryBuilder()
				->select('SalonsolutionsAppointment','SalonsolutionsUser.firstName','SalonsolutionsUser.lastName', 'SalonsolutionsService.title','SalonsolutionsService.color')
				->from('SalonSolutionEmployeeBundle:SalonsolutionsAppointment', 'SalonsolutionsAppointment')
				->leftJoin('SalonSolutionEmployeeBundle:SalonsolutionsService', 'SalonsolutionsService', "WITH", "SalonsolutionsService.id=SalonsolutionsAppointment.serviceId ")
				->leftJoin('SalonSolutionEmployeeBundle:SalonsolutionsUser', 'SalonsolutionsUser', "WITH", "SalonsolutionsUser.id=SalonsolutionsAppointment.consumerId ")
				->where('SalonsolutionsAppointment.salonId = :salonId')
				->setParameter('salonId', $salonCityId)			
				->getQuery()
				->getArrayResult();
			
		
			
			$arrArriveLate = array();
			
			foreach($arryArriveLateStatus as $arriveLateStatus)
			{
				$arrArriveLate[$arriveLateStatus[0]['id']] = $arriveLateStatus[0]; 
				$arrArriveLate[$arriveLateStatus[0]['id']]['serviceTitle'] = $arriveLateStatus['title']; 
				$arrArriveLate[$arriveLateStatus[0]['id']]['serviceColor'] = $arriveLateStatus['color']; 
				$arrArriveLate[$arriveLateStatus[0]['id']]['serviceFirstName'] = $arriveLateStatus['firstName']; 
				$arrArriveLate[$arriveLateStatus[0]['id']]['serviceLastName'] = $arriveLateStatus['lastName']; 
			}
			
			//echo "<PRE>";print_r($arrArriveLate);die;

			
			
			return $this->render('SalonSolutionEmployeeBundle:Home:employee_arrive_late_status.html.twig' , array('arrArriveLate' => $arrArriveLate));
	
		}
		/*------------------------------- End : Function to Employee  Arrive Late Status  --------------------*/
 
		
			
		/**************************** Begin : Function to Rebook Appointment ********************************/
	  
		public function rebookAppointmentAction($id, $firstName, Request $request)
		{
			$getId = $id; 
			 $getFirstName = $firstName; 
			 
			$session = $this->getRequest()->getSession(); 			
			
				$salonCityId = $session->get('salonCityId');
				
				 
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
			$salonId = $salonDetail['id'];
		
			$session->set('salonLogo', $salonLogo);
		
			
			$userSession = $this->getLoggedInConsumerDetailAction();   //function name given below 					
			$employeeId = $session->get('employeeId');	
			$repository = $this->getDoctrine()->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsUser');
			$consumerProfile = $repository->findBy(array('id' => $employeeId));			
			$employeeId =  $consumerProfile[0]->parentId;	// echo "<pre>"; print_r($consumerProfile); die;
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsSalon');
			$salon = $repository->findBy(array('ownerId' => $employeeId));			
			$ownerId =  $salon[0]->ownerId;
			
			// echo "<pre>"; print_r($consumerProfile); die;
			 
			//Consumer Offering/Flash Message Start 
				$repository = $this->getDoctrine()->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsEmployeeMessages');
				$message = $repository->findBy(array('salonOwnerId' => $ownerId , 'type' => 'Offering Message'));			
				$showMessage =	$message[0]->message;

				$session->set('offeringMessage' , $showMessage);
				$setMessage = $session->get('offeringMessage', $showMessage);   
			
			$em = $this->getDoctrine()->getEntityManager();
			$allSalons = $em->createQueryBuilder() 
			->select('SalonsolutionsSalon')
			->from('SalonSolutionEmployeeBundle:SalonsolutionsSalon',  'SalonsolutionsSalon')
			->where('SalonsolutionsSalon.ownerId = :ownerId')
			->setParameter('ownerId', $ownerId)
			->getQuery()
			->getArrayResult();	
			
			foreach($allSalons as $salon)
			{
				$arrSalonIds = $salon['id'];
			}
			
			$arrServiceAllDetails = array();
			$serviceBookingTime = array();
			$serviceBookingDetail = array();
			$arrAppointments = array();
			
					
			$arrTime = $this->getBusinessHours($ownerId, $finalAppointmentDate);
		
		
							//echo "<PRE>";print_r($arrTime);die;
			foreach($arrTime as $time)
			{
				$em = $this->getDoctrine()->getEntityManager();
			
				$arrAppointments[$time]['time'] = $time;
				
				
				
				$servicesDetail = $em->createQueryBuilder() 
				->select('SalonsolutionsService')
				->from('SalonSolutionEmployeeBundle:SalonsolutionsService',  'SalonsolutionsService')
				->where('SalonsolutionsService.salonId in(:salonId)')
				->setParameter('salonId', $salonCityId)
				->getQuery()
				->getArrayResult();		
			
	if($servicesDetail){
				
							//	echo "<pre>";print_r($servicesDetail);	die;
				$totalServices = count($servicesDetail);
				$widthDivision = 81/$totalServices;
				
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
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsAppointment');
			$appointmentLatestDetils= $repository->findBy(array('id' => $id));
			//echo "<PRE>";print_r($appointmentLatestDetils);die;
			$getUserId = $appointmentLatestDetils[0]->consumerId;
			
				return $this->render('SalonSolutionEmployeeBundle:Home:employee_rebook_appointment.html.twig' , array('servicesDetail' => $arrServiceAllDetails, 'serviceAvailabilityMax' => $serviceAvailabilityMax, 'allSalons' => $allSalons, 'arrTime' => $arrTime, 'widthDivision'=>$widthDivision,  'getFirstName' => $getFirstName, 'getId' => $getId, 'getUserId' => $getUserId, 'serviceBookingDetail'=>$serviceBookingDetail, 'arrAppointments'=>$arrAppointments));
				
				
				
				
		//	return $this->render('SalonSolutionEmployeeBundle:Home:employee_rebook_appointment.html.twig'  , array('servicesDetail' => $arrServiceAllDetails, 'serviceAvailabilityMax' => $serviceAvailabilityMax, 'allSalons' => $allSalons, 'arrTime' => $arrTime, 'widthDivision'=>$widthDivision, 'getFirstName' => $getFirstName, 'getId' => $getId, 'getUserId' => $getUserId));
		
			
			
			
	
		} 
		/*------------------------------- End : Function to Employee  Rebook Appointment  --------------------*/
  
		/*********** Begin : Function to  RE--Book Consumer Appointment ********************************/
	  
		 public function rebookConsumerAppointmentAction(Request $request)
		{
			  
			$session = $this->getRequest()->getSession();
			 
			$appointmentTime = $_POST['appointmentTime']; 
			$appointmentSalonId = $_POST['appointmentSalonId'];
			$appointmentServiceId = $_POST['appointmentServiceId'];
			$appointmentGetFirstName = $_POST['appointmentGetFirstName'];
			$appointmentGetId = $_POST['appointmentGetId'];
			$appointmentGetUserId = $_POST['appointmentGetUserId'];

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
				}
				else{
					$finalAppointmentDate	= date("d-m-Y");				
					}
			
			 
			$rebookAppointment = new SalonsolutionsAppointment();
			
			$rebookAppointment->setConsumerId($appointmentGetUserId);   
			$rebookAppointment->setSalonId($appointmentSalonId);
			$rebookAppointment->setServiceId($appointmentServiceId); 
			$rebookAppointment->setScheduledDate($finalAppointmentDate);
			$rebookAppointment->setScheduledTime($appointmentTime);
			$rebookAppointment->setBookingType('0');    
			$rebookAppointment->setStatus('1');
			
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($rebookAppointment);
			$em->flush();
			
			/* $em = $this->getDoctrine()->getEntityManager();				
			$rebookAppointments= $em->createQueryBuilder()
			->select('Appointment')
			->update('SalonSolutionEmployeeBundle:SalonsolutionsAppointment',  'Appointment')		
			->set('Appointment.salonId', ':salonId')
			->setParameter('salonId', $appointmentSalonId)	
			->set('Appointment.serviceId', ':serviceId')
			->setParameter('serviceId', $appointmentServiceId)
			->set('Appointment.scheduledDate', ':scheduledDate')
			->setParameter('scheduledDate', $finalAppointmentDate)		
			->set('Appointment.scheduledTime', ':scheduledTime')
			->setParameter('scheduledTime', $appointmentTime)			
			->set('Appointment.status', ':status')
			->setParameter('status', 1)			
			->where('Appointment.id = :id')
			->setParameter('id', $appointmentGetId)		
			->getQuery()
			->getArrayResult(); */
			
				
			return new response('SUCCESS');
		}  
		/*--------------- End : Function to  RE--Book Consumer Appointment ------------------*/	
		
		 
		/*********** Begin : Function to view Notes PopUp ********************************/
	  
		 public function viewNotesPopUpAction(Request $request)
		{  
			$showNotesId = $_POST['showNotesId']; 
						
			$repository = $this->getDoctrine()->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsAppointment');
			$viewNotes = $repository->findBy(array('id' => $showNotesId));		
			
			$notes = $viewNotes[0]->notes;	
				
			return new response($notes);
		}  
		/*--------------- End : Function to  RE--Book Consumer Appointment ------------------*/	
		
		 
		
		
		
		
		
		
		
		
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
		
	
		
		/******************* Begin : Function to Set User Selected Date in Session *********************/
	  
		public function setSelectedDateInSessionAction()
		{
			$session = $this->getRequest()->getSession();
			
			$session->set('appointmentDate', $_POST['appointmentDate']);
			
			return new response('SUCCESS');
		}
		/*------------------ End : Function to Set User Selected Date in Session ------------------*/
		
		
		public function getBusinessHours($ownerId, $appointmentDate)
		{
			/*------------------- Start - Calculate Business Hours ----------------*/
			$arrDefaultTime = array('12:00 AM', '12:30 AM','01:00 AM', '01:30 AM','02:00 AM', '02:30 AM','03:00 AM', '03:30 AM','04:00 AM', '04:30 AM','05:00 AM', '05:30 AM','06:00 AM', '06:30 AM','07:00 AM', '07:30 AM', '08:00 AM', '08:30 AM', '09:00 AM', '09:30 AM', '10:00 AM', '10:30 AM', '11:00 AM', '11:30 AM', '12:00 PM', '12:30 PM', '01:00 PM', '01:30 PM', '02:00 PM', '02:30 PM', '03:00 PM', '03:30 PM', '04:00 PM', '04:30 PM', '05:00 PM', '05:30 PM','06:00 PM', '06:30 PM','07:00 PM', '07:30 PM','08:00 PM', '08:30 PM','09:00 PM', '09:30 PM','10:00 PM', '10:30 PM','11:00 PM', '11:30 PM');
		 
			$repository = $this->getDoctrine()->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsSalonHours');
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
		  
		   	$currentDay = date('D', strtotime($appointmentDate));
		   	
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
		   	/*------------------------ End - Calculate Business Hours --------------------------*/
		   	
		   	return $arrTime;
		}
		
		
		
		
		/*********** Begin : Function to Display Consumer Appointment History ***********************/
	  
	/* 	public function appointmentHistoryAction()
		{
			$session = $this->getRequest()->getSession(); 			
			$employeeId = $session->get('employeeId');	
			echo "<pre>"; print_r($employeeId); die;
			
			$consumerId = $session->get('consumerId');		
			
			 $currentDate = date("d-m-Y");
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsAppointment');
			$appointmentDetail = $repository->findBy(array('consumerId' => $consumerId));	
			$salonId=	$appointmentDetail[0]->salonId;
			
			
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsUser');
			$consumerDetail = $repository->findBy(array('id' => $consumerId));			
			$consumerFirstName=	$consumerDetail[0]->firstName;		
			$consumerLastName=	$consumerDetail[0]->lastName;		
				
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionEmployeeBundle:SalonsolutionsSalon');
			$salonDetail = $repository->findBy(array('id' => $salonId));
			$salonName=	$salonDetail[0]->name;
			$salonCity=	$salonDetail[0]->city;
			
			//echo "<pre>"; print_r($salonDetail); die;
			$em = $this->getDoctrine()->getEntityManager();
			$arrServive = $em->createQueryBuilder()
				->select('SalonsolutionsAppointment', 'SalonsolutionsService.title','SalonsolutionsService.color')
				->from('SalonSolutionEmployeeBundle:SalonsolutionsAppointment', 'SalonsolutionsAppointment')
				->leftJoin('SalonSolutionEmployeeBundle:SalonsolutionsService', 'SalonsolutionsService', "WITH", "SalonsolutionsService.id=SalonsolutionsAppointment.serviceId")
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
			
			return $this->render('SalonSolutionEmployeeBundle:Home:consumer_appointmentHistory.html.twig', array('appointmentDetail'=>$arrBooking , 'consumerDetail'=>$consumerDetail ,'consumerFirstName'=> $consumerFirstName, 'consumerLastName'=> $consumerLastName , 'salonName' => $salonName , 'salonCity' => $salonCity ));
		} */
		
		
		/*------------------------------- End : Function to Display Consumer Appointment History ------------------------------*/
		
		
		
	
} 
