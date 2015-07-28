<?php

	namespace SalonSolution\SalonBundle\Controller;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use SalonSolution\SalonBundle\Entity\SalonsolutionsUser;            //SalonsolutionsUser
	use SalonSolution\SalonBundle\Resources\SalonsolutionsUserType;  			  //SalonsolutionsUserType
	
	use SalonSolution\SalonBundle\Entity\SalonsolutionsSalon;            //SalonsolutionsUser
	use SalonSolution\SalonBundle\Resources\SalonsolutionsSalonType;  			  //SalonsolutionsUserType
	
	use SalonSolution\SalonBundle\Entity\SalonsolutionsService;            //SalonsolutionsService
	use SalonSolution\SalonBundle\Entity\SalonsolutionsAdvertisement;            //SalonsolutionsAdvertisement	
	use SalonSolution\SalonBundle\Entity\SalonsolutionsAdvertisementType;            //SalonsolutionsAdvertisement
	use SalonSolution\SalonBundle\Entity\SalonsolutionsEmployeeMessages;            //SalonsolutionsEmployeeMessages
	use SalonSolution\SalonBundle\Entity\SalonsolutionsSalonHours;           	 //SalonsolutionsSalonHours
	use SalonSolution\SalonBundle\Entity\SalonsolutionsBusinessClosedHours;            //SalonsolutionsBusinessClosedHours

	use SalonSolution\SalonBundle\Entity\SalonsolutionsAppointment;            //SalonsolutionsAppointment
	
	use SalonSolution\SalonBundle\Entity\SalonsolutionsCms;            //SalonsolutionsUser
																			//SalonsolutionsUser
	use SalonSolution\SalonBundle\Resources\SalonsolutionsCmsType;  			  //SalonsolutionsUserType
	
	use SalonSolution\SalonBundle\Entity\SalonsolutionsSalonImage;   

	use Symfony\Component\HttpFoundation\Request;   
	use Symfony\Component\HttpFoundation\Response;
	
	use Symfony\Component\HttpFoundation\Session\Session;
	use Symfony\Component\HttpFoundation\RedirectResponse;

	
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	
	use Symfony\Component\HttpFoundation\File\UploadedFile;



class SalonController extends Controller
{
	
    
    /**************************** Begin : Function to display login page ********************************/
	
		public function loginAction(Request $request)
        {
            $session = $this->getRequest()->getSession();
			
			$arrServerName = explode('.salonsolutions.ca', $_SERVER['SERVER_NAME']);
			$salonDomain = 	$arrServerName[0];	
			
			if( $salonDomain == 'tanonline' )
					return $this->redirect( $this->generateUrl('salon_solution_web_index') );
					
			//echo "<PRE>";print_r($arrServerName);die;
			//echo $salonDomain."<PRE>";print_r($_SERVER);die;
			$params = array("domainName" => $salonDomain);
			$salonDetail = $this->getSalonAction($params);
			
			foreach($salonDetail as $salonDetail);
			
			$salonLogo = $salonDetail['logo'];
			
			$session->set('salonLogo', $salonLogo);
			
			$userSession = $this->getLoggedInUserDetailAction();   
																	 					 
			if($userSession)
			{
				if($session->get('salonUrl'))
					return $this->redirect( $session->get('salonUrl') ); 
				else
					return $this->redirect($this->generateUrl('salon_solution_salon_dashboard'));  
			} 
				
				     
			$em = $this->getDoctrine()->getEntityManager();
			$repository = $em->getRepository('SalonSolutionSalonBundle:SalonsolutionsUser');
			
			if ($request->getMethod() == 'POST')
			{
				$session->clear();
				$username = $request->get('username');        // echo "<pre>"; print_r($fetch_data);
				//$password = $request->get('password');   //echo $password;   die;   				
				$password = md5($request->get('password'));   //echo $password;   die;   				
			
				$user = $repository->findOneBy(array('username' => $username, 'password' => $password,'type' =>'2','status' =>'1'));
					
				//	echo "<pre>"; print_r($user); die;
					
				if($user !='')
				{
									 
					 $session->set('userId', $user->getId());    	
					 $setid = $session->get('userId', $user->getId());   
						
					 $session->set('username', $user->getUsername());
					 $setname = $session->get('username');  
					 
					 $session->set('photo', $user->getPhoto());
					 $setphoto = $session->get('photo');  
					 
					 $em = $this->getDoctrine()->getEntityManager();
					 $repository = $em->getRepository('SalonSolutionSalonBundle:SalonsolutionsSalon');
					 $salon = $repository->findOneBy(array( 'ownerId' => $user->getId() ));
				     //echo "<pre>"; print_r($salon); die;
				     
				     $domain = $salon->getDomain();
				     $salonName = $salon->getName();
				     
				     $session->set('salonUrl', 'http://'.$domain.'.salonSolutions.ca/salon/dashboard');
				     $session->set('salonName', $salonName);
					
					 return $this->redirect('http://'.$domain.'.salonSolutions.ca/salon/dashboard');
				}
				else
				{	
					$this->get('session')->getFlashBag()->set('error', 'Invalid UserName or Password');

					//$this->session->getFlashBag()->add('notice', 'You have been successfully been logged out.');
						
				// echo "Please Enter your Correct name  OR Password"; 
				 
				}
						
			} 
				
					
							
			
			return $this->render('SalonSolutionSalonBundle:Page:login.html.twig', array('salonLogo' => $salonLogo));
		}
		
		public function getSalonAction($params)
		{
			$em = $this->getDoctrine()->getEntityManager();
			if( array_key_exists('criteria', $params) && $params['criteria'] == 'LIKE' )
			{
				$arrSalon = $em->createQueryBuilder()->select('Salon')
				->from('SalonSolutionSalonBundle:SalonsolutionsSalon', 'Salon')
				->where('Salon.name LIKE :salonName')
				->setParameter('salonName', $params['salonSearchKey'].'%')
				->getQuery()
				->getArrayResult();
			}
			else if( array_key_exists('domainName', $params) && $params['domainName'] != '' )
			{
				$arrSalon = $em->createQueryBuilder()->select('Salon')
				->from('SalonSolutionSalonBundle:SalonsolutionsSalon', 'Salon')
				->where('Salon.domain=:domainName')
				->setParameter('domainName', $params['domainName'])
				->getQuery()
				->getArrayResult();
			}
			else
			{
				$arrSalon = $em->createQueryBuilder()->select('Salon')
				->from('SalonSolutionSalonBundle:SalonsolutionsSalon', 'Salon')
				->where('Salon.name=:salonName')
				->setParameter('salonName', $params['salonSearchKey'])
				->getQuery()
				->getArrayResult();
			}
			return $arrSalon;
		}
		
			
		
		/*------------------------------- End : Function to display login page ------------------------------*/
			
		
			
		/**************************** Begin : Function to Login the customers ********************************/
	  
		public function logoutAction()
		{
			
			 $session = $this->getRequest()->getSession();
					$session->clear('foo');
					$session->remove('foo');
					unset($session);
						return $this->redirect($this->generateUrl('salon_solution_salon_login'));
			
			return $this->render('SalonSolutionSalonBundle:Page:logout.html.twig');
		}
		/*------------------------------- End : Function to Login the customers ------------------------------*/
		
		
		/**************************** Begin : Function to recover Password ********************************/
	  
		public function recoverPasswordAction(Request $request)
		{
				
			/*************************************************forgot password ****************************/
		
			$email=$this->get('request')->request->get('email');
			$em = $this->getDoctrine()->getEntityManager();
    		$repository = $em->getRepository('SalonSolutionSalonBundle:SalonsolutionsUser');
    		
    		if ($request->getMethod() == 'POST') 
        	{
           		 $user = $repository->findOneBy(array('email' => $email));
					$firstName = $user->firstName;
					$lastName = $user->lastName;
					
					$photo = $user->photo;
					$basePath = $this->getBaseUrlAction();	
					$profileimage = $basePath."/".$this->container->getParameter('gbl_uploadPath_customers').$photo ;
					
           		//echo "<pre>"; print_r($_SERVER); die;
            		if ($user) 
            		{   
						$newPassword = $this->generateRandomString();   
						//echo $newPassword; 
						$encPass=md5($newPassword);
						$realtors = $em->createQueryBuilder()
						->select('SalonsolutionsUser')
						->update('SalonSolutionSalonBundle:SalonsolutionsUser',  'SalonsolutionsUser')
						->set('SalonsolutionsUser.password', ':password')
						->setParameter('password', $encPass)
						->where('SalonsolutionsUser.email=:email')
						->setParameter('email', $email)
						->getQuery()
						->getResult();
								
													
						//$newPassword=  $this->generateRandomString();
					
						//password is encrypted into md5 
						$encPass=md5($newPassword); 
						$to = $email;
						$subject = "Password Reset";
						$txt=   '<div style="margin:0;padding:0;background:#fff">
	<table cellpadding="0" cellspacing="0" border="0" width="100%" style="background:#ddd">
		<tbody>
		<tr>
		<td>
		<table align="center" cellpadding="0" cellspacing="0" border="0" width="670" style="background:#fff;border:0;border-left:1px solid #ccc;border-right:1px solid #ccc">
		<tbody>
		<tr>
		<td>
		<a href="http://tanonline.salonsolutions.ca/theme/frontend/images/logo.png" style="border:none;color:#0084b4;text-decoration:none" ><span></span></a>
		<table cellpadding="0" cellspacing="0" border="0" width="670" style="background:#f2f2f2;table-layout:fixed">
		<tbody> 
		<tr>
		<td style="width:19px;height:77px"> &nbsp; </td>
		<td height="94" width="46" valign="top" rowspan="2" style="background:#fff;line-height:100%"><a href="" ><img src="http://tanonline.salonsolutions.ca/theme/frontend/images/logo.png" width="46" height="94" style="border:0;line-height:100%;border:0" class="CToWUd"></a></td>
		<td width="9"> &nbsp; </td>
		<td width="10" height="77"> &nbsp; </td>
		<td width="458" height="77" style="font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;color:#333">
		<table border="0" cellpadding="0" cellspacing="0">
		<tbody>
		<tr>
		<td style="font-size:14px;font-weight:bold;color:#000"> <span dir="ltr">'.$firstName.' &nbsp;&nbsp;'.$lastName.',</span> </td>
		</tr>
		<tr>
		<td style="font-size:14px;color:#666"> Congratulations!!   . </td>
		</tr>
		</tbody>
		</table>
		</td>
		<td width="10" height="77"> &nbsp; </td>
		<td width="32" style="text-align:right"> </td>
		<td style="width:85px"></td>
		</tr>
		<tr>
		<td style="background:#fff;border-top:1px solid #ddd;font-family:"Helvetica Neue",Helvetica,Arial,sans-serif>&nbsp;</td>
		<td style="background:#fff;border-top:1px solid #ddd;font-family:"Helvetica Neue",Helvetica,Arial,sans-serif>&nbsp;</td>
		<td height="17" style="background:#fff;border-top:1px solid #ddd;font-family:"Helvetica Neue",Helvetica,Arial,sans-serif"><img width="1" height="1" style="display:block;border:0" src="" class="CToWUd"></td>
		<td height="17" colspan="4" style="background:#fff;border-top:1px solid #ddd;font-family:"Helvetica Neue",Helvetica,Arial,sans-serif">&nbsp;</td>
		</tr>
		</tbody>
		</table>
		</td>
		<td rowspan="3"></td>
		</tr>
		<tr>
		<td style="background:#fff">
		<table width="670" border="0" cellpadding="0" cellspacing="0" style="background:#fff;font-family:"Helvetica Neue",Helvetica,Arial,sans-serif">
		<tbody>
		<tr>
		<td style="width:85px;width:114px!important"></td>
		<td colspan="2">
		<table cellpadding="0" cellspacing="0" border="0">
		<tbody>
		<tr>
		<td height="5px;"></td>
		</tr>
		<tr>
		<td style="padding:10px;font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;color:#333;border-top:1px solid #e8e8e8;padding-top:15px;padding-bottom:15px;border-top:none">
			<table cellpadding="0" cellspacing="0" border="0" width="520" style="table-layout:fixed">
			<tbody>
			<tr>
			<td rowspan="4" width="138" valign="top" style="padding-right:10px;line-height:0"><a href="" style="border:none;color:#0084b4;text-decoration:none"><img src="'.$profileimage.'" width="128" height="128" style="border:0;background-color:#f2f2f2;border-radius:5px" class="CToWUd"></a></td>
			<td valign="top" style="line-height:12px"> <a href="" style="border:none;color:#0084b4;text-decoration:none" ><span style="color:#333333;font-size:14px;font-weight:bold;line-height:100%">Your Password Has Been Sucessfully Genrated :) </span></a> </td>
			</tr>
			<tr>
			<td style="padding-top:2px;padding-bottom:2px;font-size:14px;line-height:18px;font-style:italic;font-family:"Georgia","Helvetica Neue",Helvetica,Arial,sans-serif;color:#777"><a href="" style="border:none;color:#0084b4;text-decoration:none;color:#777" >Copy the is Given Below with in Green Letters</a></td>
			</tr>
			<tr>
			<td valign="top" style="font-size:12px;line-height:18px;color:#333333;font-family:"Helvetica Neue",Helvetica,Arial,sans-serif">  <a href="" style="border:none;color:#0084b4;text-decoration:none" >Followed / Copy This Password</a>.<br> 

			<span style="font-size:19px;line-height:18px;border:none;color: #1BB400;font-weight: bold;text-decoration:none"> <img width="29" height="29" src="http://tanonline.salonsolutions.ca/images/icons/1password_logo.png" style="border:0" class="CToWUd"> '.$newPassword.' </span> </td>
			</tr>
			<tr>
			<td valign="bottom" style="padding-top:10px">
			<table border="0" cellpadding="0" cellspacing="0">
			<tbody>
			<tr>
			<td>
			<table border="0" cellpadding="0" cellspacing="0" background="" style="background-color:#eeeeee;border-radius:5px;border:1px solid #cccccc;padding:5px 0 5px 0;text-align:center;height:28px;width:1px">

			</table>
			</td>
			</tr>
			</tbody>
			</table>
			</td>
			</tr>
			</tbody>
			</table>
			</td>
		</tr>
		<tr>
		<td height="2"></td>
		</tr>
		<tr>
		<td width="520" style="border-radius:5px;background-color:#ededed;padding:10px;font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;color:#333333">
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tbody>
		<tr>
		
		<td align="right">
		<table border="0" cellpadding="0" cellspacing="0">
		<tbody>
		<tr>
		<td>
		<table cellpadding="0" cellspacing="0" border="0" style="background-image:url("http://bwcmultimedia.com/PS/SalonSolutions/images/icons/1password_logo.png");background-color:#33a9e5;border-radius:5px;border:1px;height:28px;border:1px solid #28c;word-wrap:break-word;text-align:center">
		<tbody>
		<tr>
		<td align="center" style="padding-left:10px;padding-right:10px">
		<table cellpadding="0" cellspacing="0" border="0">
		<tbody>
		
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		<tr>
		<td height="22"></td>
		</tr>
		</tbody>
		</table>
		</td>
		<td style="width:85px"></td>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
	</table>

</div>'; 
						//$headers = "From: webmaster@example.com" . "\r\n" ;
						$headers = "MIME-Version: 1.0" . "\r\n";
						$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
						$headers .= "From: <salonSolution@help.com>" . "\r\n";
						 $retval = mail($to,$subject,$txt,$headers); //send mail      					 
						   if( $retval == true )
						   {
							 $this->get('session')->getFlashBag()->set('mailSent', 'Your Password has been Sent into your Email : Please check your Inbox / Span Box');	
							 
							 	return $this->redirect($this->generateUrl('salon_solution_salon_login'));
								
						   }
						   else
						   {
							  return $this->redirect($this->generateUrl('salon_solution_salon_login'));
							   echo "Message could not be sent...";
						   }		  
											
					} 
				
		
			}	
				//return $this->render('SalonSolutionSalonBundle:Page:logout.html.twig');
		}
		function generateRandomString($length = 10) {
				return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
			}
		/*------------------------------- End : Function to recover Password ------------------------------*/
		
	
		/**************************** Begin : Function to display home page ********************************/
		public function dashboardAction()
		{
			
			$session = $this->getRequest()->getSession(); 	
			
			$arrServerName = explode('.salonsolutions.ca', $_SERVER['SERVER_NAME']);
			$salonDomain = 	$arrServerName[0];	
			
			$params = array("domainName" => $salonDomain);
			$salonDetail = $this->getSalonAction($params);
			
			foreach($salonDetail as $salonDetail);
			
			$salonLogo = $salonDetail['logo'];
			
			$session->set('salonLogo', $salonLogo);
			
			$userSession = $this->getLoggedInUserDetailAction();         //function name given below 

			if($session->get('userId') && $session->get('userId') != '')					
				$userId = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_salon_login') );
			
		    $userId = $session->get('userId');
			
				
			
		    
			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsSalon');
			$salons = $repository->findBy(array('ownerId' => $userId));
			$totalSalons = count($salons);
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsSalon');
			$alons = $repository->findBy(array('ownerId' => $userId));			
			$alonsId =	$alons[0]->id;			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsUser');
			$Consumers = $repository->findBy(array('type' => '3','status' => '1', 'parentId' => $alonsId));		
			$totalConsumers = count($Consumers);
			
			$em = $this->getDoctrine()->getEntityManager();
			$Services = $em->createQueryBuilder()
				->select('SalonsolutionsService', 'SalonsolutionsSalon.city')
				->from('SalonSolutionSalonBundle:SalonsolutionsService', 'SalonsolutionsService')
				->leftJoin('SalonSolutionSalonBundle:SalonsolutionsSalon', 'SalonsolutionsSalon', "WITH", "SalonsolutionsSalon.id=SalonsolutionsService.salonId ")
				->where('SalonsolutionsSalon.ownerId = :ownerId')
				->setParameter('ownerId', $userId)			
				->getQuery()
				->getArrayResult();
			$totalServices = count($Services);
			
			$em = $this->getDoctrine()->getEntityManager();
			$Appointments = $em->createQueryBuilder()
				->select('SalonsolutionsAppointment', 'SalonsolutionsSalon.name', 'SalonsolutionsSalon.city', 'SalonsolutionsUser.firstName', 'SalonsolutionsService.title','SalonsolutionsService.color')
				->from('SalonSolutionSalonBundle:SalonsolutionsAppointment', 'SalonsolutionsAppointment')
				->leftJoin('SalonSolutionSalonBundle:SalonsolutionsService', 'SalonsolutionsService', "WITH", "SalonsolutionsService.id=SalonsolutionsAppointment.serviceId ")
				->leftJoin('SalonSolutionSalonBundle:SalonsolutionsUser', 'SalonsolutionsUser', "WITH", "SalonsolutionsUser.id=SalonsolutionsAppointment.consumerId ")
				->leftJoin('SalonSolutionSalonBundle:SalonsolutionsSalon', 'SalonsolutionsSalon', "WITH", "SalonsolutionsSalon.id=SalonsolutionsAppointment.salonId ")
				->where('SalonsolutionsSalon.ownerId = :ownerId')
				->setParameter('ownerId', $userId)			
				->getQuery()
				->getArrayResult();
				$totalAppointments = count($Appointments);
		
			
			$em = $this->getDoctrine()->getEntityManager();
			$Advertisements = $em->createQueryBuilder()
				->select('SalonsolutionsAdvertisement', 'SalonsolutionsSalon.name')
				->from('SalonSolutionSalonBundle:SalonsolutionsAdvertisement', 'SalonsolutionsAdvertisement')
				->leftJoin('SalonSolutionSalonBundle:SalonsolutionsSalon', 'SalonsolutionsSalon', "WITH", "SalonsolutionsSalon.id=SalonsolutionsAdvertisement.salonId ")
				->where('SalonsolutionsSalon.ownerId = :ownerId')
				->setParameter('ownerId', $userId)			
				->getQuery()
				->getArrayResult();
			$totalAdvertisements = count($Advertisements);
				//echo "<pre>"; print_r($totalAdvertisements); die;
					
			if($userSession)
			{
				return $this->render('SalonSolutionSalonBundle:Page:dashboard.html.twig', 
					array(
						'totalSalons'=> $totalSalons, 
						'totalConsumers'=> $totalConsumers,
						'totalAppointments'=> $totalAppointments,
						'totalAdvertisements'=> $totalAdvertisements,
						'totalServices'=> $totalServices
					)
				);
			}
			else
				return $this->redirect($this->generateUrl('salon_solution_salon_login')); 
				
				
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
		
	
		/**************************** Begin : Function to Profile ********************************/
	  
		public function profileAction(Request $request)
		{
				
				
				
			$session = $this->getRequest()->getSession(); 	
			if($session->get('userId') && $session->get('userId') != '')					
				$setid = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_salon_login') );
						
		    $setid = $session->get('userId');		
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsUser');
			$profilename = $repository->findOneBy(array('id' => $setid));		
			return $this->render('SalonSolutionSalonBundle:Page:profile.html.twig', array('profilename'=> $profilename));

			
		}
		/*------------------------------- End : Function to Profile ------------------------------*/
		
		/**************************** Begin : Function to Profile ********************************/
	  
		public function changeProfileImageAction(Request $request)
		{
			$session = $this->getRequest()->getSession();
			
			if($session->get('userId') && $session->get('userId') != '')					
				$userId = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_salon_login') );
						
			$userId = $session->get('userId');
			
			//$sourcePath = $file = $_FILES['file']['name'];
						
			 //$file1  = $_FILES['file']['tmp_name'];  
			//move_uploaded_file($_FILES["file"]["tmp_name"], "uploads/user/" . $_FILES["file"]["name"]);
			  
			$basePath = $this->getBasePathAction();	  
			$photo = $_FILES['file']['name'];  	
			$ranPhotoUpload = rand(1,10000);  		
			$targetFilePhoto = $basePath."/".$this->container->getParameter('gbl_uploadPath_customers').$ranPhotoUpload.$photo;
			move_uploaded_file($_FILES['file']['tmp_name'], $targetFilePhoto);		
			
			$em = $this->getDoctrine()->getEntityManager();
			$confirmedSubscribe = $em->createQueryBuilder() 
			
			->select('User')
			->update('SalonSolutionSalonBundle:SalonsolutionsUser',  'User')
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
	  
		public function profileAccountAction(Request $request)
		{
			$session = $this->getRequest()->getSession();
			
			if($session->get('userId') && $session->get('userId') != '')					
				$userId = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_salon_login') );
				
				
		    $setid = $session->get('userId');		
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsUser');
			$profileDetail = $repository->findOneBy(array('id' => $setid));	
				
				
			return $this->render('SalonSolutionSalonBundle:Page:profile_account.html.twig', array('profileDetail'=> $profileDetail));

			
			
		}
		/*------------------------------- End : Function to Profile ------------------------------*/
		
		
		/**************************** Begin : Function to Profile ********************************/
	  
		public function updateProfileAction()
		{
				
			$session = $this->getRequest()->getSession(); 
			
			if($session->get('userId') && $session->get('userId') != '')					
				$userId = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_salon_login') );
				
				
		    $userId = $session->get('userId');		
			//print_r($_POST['firstName']);die('yes');
			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsUser');
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
			->update('SalonSolutionSalonBundle:SalonsolutionsUser',  'profile')
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
				return $this->redirect( $this->generateUrl('salon_solution_salon_login') );
				
				
		    $userId = $session->get('userId');		
			//echo $userId."<PRE>";print_r($_POST);die;
			$em = $this->getDoctrine()->getEntityManager();
			$salonCurrentPassword = $em->createQueryBuilder() 
			->select('SalonsolutionsUser')
			->from('SalonSolutionSalonBundle:SalonsolutionsUser',  'SalonsolutionsUser')
			->where('SalonsolutionsUser.id = :id')
			->setParameter('id', $userId)
			->andwhere('SalonsolutionsUser.type = :type')
			->setParameter('type', 2)
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
				->update('SalonSolutionSalonBundle:SalonsolutionsUser',  'SalonsolutionsUser')
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
		    $userId = $session->get('userId');		
			//print_r($_POST['firstName']);die('yes');
			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsUser');
			$profileDetail = $repository->findOneBy(array('id' => $userId));	
			
			
			
		}
		/*------------------------------- End : Function to Change Password  ------------------------------*/
	
		
		/**************************** Begin : Function to Manage Salons ********************************/
	  
		public function manageSalonsAction(Request $request)
		{
			$session = $this->getRequest()->getSession(); 		

			if($session->get('userId') && $session->get('userId') != '')					
				$userId = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_salon_login') );
				
				
		     $userId = $session->get('userId');		
		    //die;
			
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsSalon');
			  $Salons = $repository->findBy(array('ownerId' =>  $userId));			
		
			
			return $this->render('SalonSolutionSalonBundle:Page:manage_salon.html.twig',array('Salons'=> $Salons));

				
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
							->update('SalonSolutionSalonBundle:SalonsolutionsSalon',  'ser')
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
		/*------------------------------- End : Function to Manage Salons ------------------------------*/
		
		
		/**************************** Begin : Function to Images ********************************/
	  
		public function imagesAction($id, Request $request)
		{
				$salonId	= $id ;
			
				$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsSalonImage');
				$salonImages = $repository->findBy(array('salonId' =>  $salonId));				
			
				//echo "<pre>"; print_r($salonImages)  ; die;
			
			if ($request->getMethod() == 'POST') 
					{	
																
						//$salonId = $request->get("salonId");    // echo  $salonId ; die;
						$caption = $request->get("caption");    // echo  $salonId ; die;
						$basePath = $this->getBasePathAction();	 					
						$photo = $_FILES['photo']['name'];  	
							$ranPhotoUpload = rand(1,10000);  							
							$targetFileLogo = $basePath."/".$this->container->getParameter('gbl_uploadPath_salons').$ranPhotoUpload.$photo;
							move_uploaded_file($_FILES['photo']['tmp_name'], $targetFileLogo);					
					
						$salonImages = new SalonsolutionsSalonImage();
						
						$salonImages->setCaption($caption);   
						$salonImages->setSalonId($salonId);   
						$salonImages->setImage($ranPhotoUpload.$photo);
						
						$em = $this->getDoctrine()->getEntityManager();
						$em->persist($salonImages);
						$em->flush();
						
							return $this->redirect($this->generateUrl('salon_solution_salon_images',array('id'=> $salonId)));
						
					}
						
			
			return $this->render('SalonSolutionSalonBundle:Page:images.html.twig',array('salonImages'=> $salonImages));

			
		}
		/*------------------------------- End : Function to Images ------------------------------*/
		
		
		/**************************** Begin : Function to View Salons ********************************/
	  
		public function deleteSalonImageAction()
		{
			$em = $this->getDoctrine()->getEntityManager();
		
			$deleteSalonImage = $em->getRepository('SalonSolutionSalonBundle:SalonsolutionsSalonImage')->find($_POST['imageId']);				
		
			if ($deleteSalonImage)
			{	
				$em->remove($deleteSalonImage);
				$em->flush();
				return new response('SUCCESS');   
				}
			
			return $this->render('SalonSolutionSalonBundle:Page:delete_image_gallery.html.twig');

		}
		/*------------------------------- End : Function to View Salons ------------------------------*/
		
		
		/**************************** Begin : Function to View Salons ********************************/
	  
		public function viewSalonAction($id, Request $request)
		{
		
			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsSalon');
			  $salonDetails = $repository->findBy(array('id' =>  $id));			
				
			//	echo "<pre>"; print_r($salonDetails); die;
			
			return $this->render('SalonSolutionSalonBundle:Page:view_salon.html.twig',array('salonDetails'=> $salonDetails));

			
		}
		/*------------------------------- End : Function to View Salons ------------------------------*/
		
		
			
			
		/**************************** Begin : Function to Add Salon ********************************/
			
		
		public function addSalonAction(Request $request)
		{
			$session = $this->getRequest()->getSession(); 	
			
			if($session->get('userId') && $session->get('userId') != '')					
				$userId = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_salon_login') );
				
				
		     $userId = $session->get('userId');		
		    //die;
			
			
			$em = $this->getDoctrine()->getEntityManager();
			$salonName = $em->createQueryBuilder() 
			->select('SalonsolutionsSalon')
			->from('SalonSolutionSalonBundle:SalonsolutionsSalon',  'SalonsolutionsSalon')
			->where('SalonsolutionsSalon.ownerId = :ownerId')
			->setParameter('ownerId', $userId)
			->getQuery()
			->getResult();
			$name = $salonName[0]->name ;
			$domain = $salonName[0]->domain ;
			$description = $salonName[0]->description ;
			$logo = $salonName[0]->logo ;
			$displayName = $salonName[0]->displayName ;
			
			// echo "<pre>"; print_r($salonName); die;

			$em = $this->getDoctrine()->getEntityManager();
			$salonOwners = $em->createQueryBuilder() 
			->select('SalonsolutionsUser')
			->from('SalonSolutionSalonBundle:SalonsolutionsUser',  'SalonsolutionsUser')
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
					/*	$owner_id = $request->get("owner_id"); 
						$basePath = $this->getBasePathAction();	 
					
						$logo = $_FILES['logo']['name'];  	
							$ranPhotoLogo = rand(1,10000);  							
							$targetFileLogo = $basePath."/".$this->container->getParameter('gbl_uploadPath_salons').$ranPhotoLogo.$logo;
							move_uploaded_file($_FILES['logo']['tmp_name'], $targetFileLogo);					
								*/
			
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
						$customer->setOwnerId($userId);							
						$customer->setLogo($logo);
						
						$customer->setStatus('1');	
							
						$em = $this->getDoctrine()->getEntityManager();
						$em->persist($customer);
						$em->flush();
																					// next ---------> table insert
					
					
						return $this->redirect($this->generateUrl('salon_solution_salon_manageSalons'));  // redirect the page
				
			
					} 
				
		
			  	// echo "<pre>"; print_r($customers); die;
			
			return $this->render('SalonSolutionSalonBundle:Page:add_salon.html.twig', array('salonOwners' => $salonOwners,'name'=> $name,'domain'=> $domain,'description'=> $description,'logo'=> $logo,'displayName'=> $displayName,));
		}			
		
		/*------------------------------- End : Function to Add Salon ------------------------------*/
		
		
		
		/**************************** Begin : Function to Manage Edit Salons ********************************/
	  
		public function editSalonAction($id, Request $request)
		{
			$session = $this->getRequest()->getSession();
			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsSalon');
			$updateSalonInformation = $repository->findBy(array('id' =>  $id));			
		
			if($request->getMethod() == 'POST')
			{				
				$name = $request->get("name");  	
				$domain = $request->get("domain");
				$displayName = $request->get("Display Name");
				$description = $request->get("description");
				$address = $request->get("address");
				$country = $request->get("country");
				$state = $request->get("state");
				$city = $request->get("city");
				$zip = $request->get("zip");
				$mobile = $request->get("mobile");
				$landline = $request->get("landline");
				$basePath = $this->getBasePathAction();	
				$logo = $_FILES['logo']['name'];  	
					$ranPhotoLogo = rand(1,10000);  							
					$targetFileLogo = $basePath."/".$this->container->getParameter('gbl_uploadPath_salons').$ranPhotoLogo.$logo;
					move_uploaded_file($_FILES['logo']['tmp_name'], $targetFileLogo);					
			
		
				$em = $this->getDoctrine()->getEntityManager();
				$confirmedSubscribe = $em->createQueryBuilder() 
				->select('tblSalon')
				->update('SalonSolutionSalonBundle:SalonsolutionsSalon',  'tblSalon')
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
				->set('tblSalon.logo', ':logo')
				->setParameter('logo', $ranPhotoLogo.$logo)
				->where('tblSalon.id = :id')
				->setParameter('id', $id)
				->getQuery()
				->getResult();
				
				$session->set('salonLogo', $ranPhotoLogo.$logo);
								
				return $this->redirect($this->generateUrl('salon_solution_salon_manageSalons'));
				
			}			
			
			return $this->render('SalonSolutionSalonBundle:Page:edit_salon.html.twig',array('updateSalonInformation'=> $updateSalonInformation));

			
		}
		
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
		/*------------------------------- End : Function to Manage Edit Salons ------------------------------*/
		
		
		
		/**************************** Begin : Function to delete Salon ********************************/
		public function deleteSalonAction($id)
		{
			
			
				$em = $this->getDoctrine()->getEntityManager();
				$del = $em->getRepository('SalonSolutionSalonBundle:SalonsolutionsSalon')->find($id);				
							
				if ($del) {
						$em->remove($del);
						$em->flush();
							return $this->redirect($this->generateUrl('salon_solution_salon_manageSalons'));  // redirect the page
						}
																				 //echo "<pre>"; print_r($deleteManageCoustomer); die;
			
			return $this->render('SalonSolutionSalonBundle:Page:manage_salon.html.twig');
		}
		/*------------------------------- End : Function to delete Salon ------------------------------*/
		
		
		
		
		/**************************** Begin : Function to Manage Consumers ********************************/
	  
		public function manageConsumersAction(Request $request)
		{
				$session = $this->getRequest()->getSession();
				
			if($session->get('userId') && $session->get('userId') != '')					
				$userId = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_salon_login') );
				
				
				 $userId = $session->get('userId');		

				$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsSalon');
			    $alons = $repository->findBy(array('ownerId' => $userId));			
				$alonsId =	$alons[0]->id;
				
				$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsUser');
			    $Consumers = $repository->findBy(array('type' => '3','status' => '1', 'parentId' => $alonsId));		
			
		
			//echo "<pre>";	print_r($Consumers);   die();
			
			return $this->render('SalonSolutionSalonBundle:Page:manage_consumer.html.twig',array('Consumers'=> $Consumers));
			
			
		}
			//--- Begin : Function to  Change Status Consumers  ------------------------------*/
					public function changeStatusConsumersAction()
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
						
						if( isset($_POST['objectType']) && $_POST['objectType'] == 'Consumer' )
						{
							$em = $this->getDoctrine()->getEntityManager();
							$confirmedSubscribe = $em->createQueryBuilder() 
							->select('ser')
							->update('SalonSolutionSalonBundle:SalonsolutionsUser',  'ser')
							->set('ser.status', ':status')
							->setParameter('status', $status)
							->where('ser.id = :id')
							->setParameter('id', $id)
							->getQuery()
							->getResult();
						}
						$statusHtml.='<a id="status-'.$id.'" class="edit" title="Click to Change" onclick="javascript:changeStatusConsumers(\'status-'.$id.'\','.$status.');">'.$statusString.'</a>'; 
						
						return new response($statusHtml);				
					} 
		/*------------------------------- End : Function to Manage Consumer ------------------------------*/
			
			
		/**************************** Begin : Function to View Consumer ********************************/
	  
		public function viewConsumerAction($id, Request $request)
		{
		
			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsUser');
			  $consumerDetails = $repository->findBy(array('id' =>  $id));			
				
			
			return $this->render('SalonSolutionSalonBundle:Page:view_consumer.html.twig',array('consumerDetails'=> $consumerDetails));

			
		}
		/*------------------------------- End : Function to View Consumer ------------------------------*/
		
		
		
		
			
		/**************************** Begin : Function to Add Consumer ********************************/
			
		
		public function addConsumerAction(Request $request)
		{
			$session = $this->getRequest()->getSession(); 	

			if($session->get('userId') && $session->get('userId') != '')					
				$userId = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_salon_login') );


		     $userId = $session->get('userId');		
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsSalon');
			$alons = $repository->findBy(array('ownerId' => $userId));			
			$salonId =	$alons[0]->id;
			
			
					
			if ($request->getMethod() == 'POST') 
					{					
						$firstName = $request->get("firstName");  	
						$lastName = $request->get("lastName");
						$email = $request->get("email");
						$username = $request->get("username");	
						$password = md5($request->get("password"));	
						$country = $request->get("country");						
						$state = $request->get("state");
						$address = $request->get("address");						
						$city = $request->get("city");						
						$zip = $request->get("zip"); 
						$mobile = $request->get("mobile"); 
						$landline = $request->get("landline"); 
						$parentId = $request->get("parentId"); 
						$basePath = $this->getBasePathAction();	  
						$photo = $_FILES['photo']['name'];  	
							$ranPhotoUpload = rand(1,10000);  		
							$targetFilePhoto = $basePath."/".$this->container->getParameter('gbl_uploadPath_consumers').$ranPhotoUpload.$photo;            //getBasePathAction() defined into upper 
							move_uploaded_file($_FILES['photo']['tmp_name'], $targetFilePhoto);					
					
						
						$consumer = new SalonsolutionsUser();
						
						$consumer->setFirstName($firstName);   
						$consumer->setLastName($lastName);
						$consumer->setUserName($username);
						$consumer->setPassword($password);
						$consumer->setEmail($email);
						$consumer->setCountry($country);								
						$consumer->setState($state);								
						$consumer->setAddress($address);								
						$consumer->setCity($city);								
						$consumer->setZip($zip);									
						$consumer->setMobile($mobile);									
						$consumer->setLandline($landline);									
						$consumer->setParentId($parentId);									
						$consumer->setPhoto($ranPhotoUpload.$photo);
						$consumer->setType('3');
						$consumer->setParentId($salonId);
						$consumer->setSalonId($salonId);
						$consumer->setSalonOwnerId($userId);
						$consumer->setStatus('1');	
							
						$em = $this->getDoctrine()->getEntityManager();
						$em->persist($consumer);
						$em->flush();
																					// next ---------> table insert
					//	$customerId = $customer->getId();
						
						return $this->redirect($this->generateUrl('salon_solution_salon_manageConsumers'));  // redirect the page
			
					} 
				
				
		
			  	// echo "<pre>"; print_r($customers); die;
			
			return $this->render('SalonSolutionSalonBundle:Page:add_consumer.html.twig');
		}			
		
		/*------------------------------- End : Function to Add Consumers ------------------------------*/
		
		
			
		
		/**************************** Begin : Function to Edit Consumers ********************************/
	  
		public function editConsumerAction($id, Request $request)
		{
			
			
				$session = $this->getRequest()->getSession(); 		

				if($session->get('userId') && $session->get('userId') != '')					
				$userId = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_salon_login') );

		    $userId = $session->get('userId');		
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsUser');
			 $updateConsumerInformation = $repository->findBy(array('id' =>  $id));			
		
			
				//$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsUser');
			 // $Consumers = $repository->findBy(array('type' => '3','parentId' => $userId));			
			if($request->getMethod() == 'POST')
			{				
				$firstName = $request->get("firstName");  	
				$lastName = $request->get("lastName");
				$email = $request->get("email");
				$username = $request->get("username");
				$address = $request->get("address");
				$country = $request->get("country");
				$state = $request->get("state");
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
				->update('SalonSolutionSalonBundle:SalonsolutionsUser',  'tblconsumer')
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
				->setParameter('id', $id)
				->getQuery()
				->getResult();		
				//echo "<pre>";	print_r($resultConsumer);   die();
				return $this->redirect($this->generateUrl('salon_solution_salon_manageConsumers'));
			}				
			
			return $this->render('SalonSolutionSalonBundle:Page:edit_consumer.html.twig',array('updateConsumerInformation'=> $updateConsumerInformation));
			
			
		}		

		/*------------------------------- End : Function to Edit Consumers ------------------------------*/
		
		
		
		/**************************** Begin : Function to Edit Consumers ********************************/
	  
		public function deleteConsumerAction($id, Request $request)
		{
						
				/* $em = $this->getDoctrine()->getEntityManager();
				$del = $em->getRepository('SalonSolutionSalonBundle:SalonsolutionsUser')->find($id);				
					if ($del)
					{
					$em->remove($del);
					$em->flush();
					return $this->redirect($this->generateUrl('salon_solution_salon_manageConsumers'));  // 
					} */ 
					
					$em = $this->getDoctrine()->getEntityManager();
					$em = $this->getDoctrine()->getEntityManager();
					$del = $em->createQueryBuilder() 
					->select('tblUser')
					->update('SalonSolutionSalonBundle:SalonsolutionsUser',  'tblUser')
					->set('tblUser.status', ':status')
					->setParameter('status', 2)								
					->where('tblUser.id = :id')
					->setParameter('id', $id)
					->getQuery()
					->getResult();	
					return $this->redirect($this->generateUrl('salon_solution_salon_manageConsumers'));  
				
							
			
			return $this->render('SalonSolutionSalonBundle:Page:delete_consumer.html.twig');
			
			
		}		

		/*------------------------------- End : Function to Edit Consumers ------------------------------*/
		
		
		
		
		
		/**************************** Begin : Function to Manage Services ********************************/
	  
		public function manageServicesAction(Request $request)
		{
			$session = $this->getRequest()->getSession();
			
		if($session->get('userId') && $session->get('userId') != '')					
				$userId = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_salon_login') );

			
			$userId = $session->get('userId');	 ;

			$em = $this->getDoctrine()->getEntityManager();
			$arrServices = $em->createQueryBuilder()
				->select('SalonsolutionsService', 'SalonsolutionsSalon.city')
				->from('SalonSolutionSalonBundle:SalonsolutionsService', 'SalonsolutionsService')
				->leftJoin('SalonSolutionSalonBundle:SalonsolutionsSalon', 'SalonsolutionsSalon', "WITH", "SalonsolutionsSalon.id=SalonsolutionsService.salonId ")
				->where('SalonsolutionsSalon.ownerId = :ownerId')
				->setParameter('ownerId', $userId)			
				->getQuery()
				->getArrayResult();

			$Services = array();

				foreach($arrServices as $arrSer)
				{
				$Services[$arrSer[0]['id']] = $arrSer[0]; 
				$Services[$arrSer[0]['id']]['salonName'] = $arrSer['city']; 
				}

					//echo "<pre>";	print_r($Services);   die();
		
			
				//$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsService');
				//$Services = $repository->findAll();			
				
				//echo "<pre>";	print_r($Services);   die();
		
			return $this->render('SalonSolutionSalonBundle:Page:manage_services.html.twig',array('Services'=> $Services));

			
		}
		//--- Begin : Function to  Change Status change Status Services  ------------------------------*/
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
						
						if( isset($_POST['objectType']) && $_POST['objectType'] == 'Service' )
						{
							$em = $this->getDoctrine()->getEntityManager();
							$confirmedSubscribe = $em->createQueryBuilder() 
							->select('ser')
							->update('SalonSolutionSalonBundle:SalonsolutionsService',  'ser')
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
		/*------------------------------- End : Function to  change Status Services  ------------------------------*/
			
		/*------------------------------- End : Function to Manage Services ------------------------------*/
		
		
		
		/**************************** Begin : Function to Manage Services ********************************/
	  
		public function addServiceAction(Request $request)
		{
			$session = $this->getRequest()->getSession(); 	
			
			if($session->get('userId') && $session->get('userId') != '')					
				$userId = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_salon_login') );

			
			$userId = $session->get('userId');		
		
					
			$em = $this->getDoctrine()->getEntityManager();
			$Salonowner = $em->getRepository('SalonSolutionSalonBundle:SalonsolutionsSalon')->findBy(array('ownerId' =>  $userId));						
				//echo "<pre>";	print_r($Salonowner);  	 die();	
				
				if ($request->getMethod() == 'POST') 
					{					
						$title = $request->get("title");  	
						$displayName = $request->get("displayName");  	
						$description = $request->get("description");  	
						$color = $request->get("color");  	
						$price = $request->get("price");  	
						$salonId = $request->get("salonId");  	
						$availability = $request->get("availability");    	
					
						$service = new SalonsolutionsService();
						
						$service->setTitle($title);  						 
						$service->setDisplayName($displayName);  						 
						$service->setDescription($description);  						 
						$service->setColor($color);  						 
						$service->setPrice($price);  						 
						$service->setSalonId($salonId);  						 
						$service->setAvailability($availability);  	
						$service->setStatus('1');	
							
						$em = $this->getDoctrine()->getEntityManager();
						$em->persist($service);
						$em->flush();
					return $this->redirect($this->generateUrl('salon_solution_salon_manageServices'));  // redirect the page
			
					} 
					
				//update service start
					$em = $this->getDoctrine()->getEntityManager();
					$countServices = $em->createQueryBuilder()
					->select('SalonsolutionsService', 'SalonsolutionsSalon.city')
					->from('SalonSolutionSalonBundle:SalonsolutionsService', 'SalonsolutionsService')
					->leftJoin('SalonSolutionSalonBundle:SalonsolutionsSalon', 'SalonsolutionsSalon', "WITH", "SalonsolutionsSalon.id=SalonsolutionsService.salonId ")
					->where('SalonsolutionsSalon.ownerId = :ownerId')
					->setParameter('ownerId', $userId)			
					->getQuery()
					->getArrayResult();

					$totalCountServices = count($countServices);
					$noOfCountServices = $totalCountServices + 1;
					
					//echo "<pre>";	print_r($noOfCountServices);  	 die();	

					$em = $this->getDoctrine()->getEntityManager();
					$confirmedSubscribe = $em->createQueryBuilder() 
					->select('tblSalon')
					->update('SalonSolutionSalonBundle:SalonsolutionsSalon',  'tblSalon')
					->set('tblSalon.maxBookingsDefault', ':maxBookingsDefault')
					->setParameter('maxBookingsDefault', $noOfCountServices)
					->where('tblSalon.ownerId = :ownerId')
					->setParameter('ownerId', $userId)
					->getQuery()
					->getResult();
				//update service end
				
				
			//	echo "<pre>";	print_r($Services);   die();
		
			return $this->render('SalonSolutionSalonBundle:Page:add_services.html.twig',array('Salonowner'=> $Salonowner));

			
		}
		/*------------------------------- End : Function to Manage Services ------------------------------*/
		
		
		
		/**************************** Begin : Function to Edit Services ********************************/
	  
		public function editServiceAction($id, Request $request)
		{
			$session = $this->getRequest()->getSession(); 	

			if($session->get('userId') && $session->get('userId') != '')					
				$userId = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_salon_login') );

			$userId = $session->get('userId');		
		
					
			$em = $this->getDoctrine()->getEntityManager();
			$Salonowner = $em->getRepository('SalonSolutionSalonBundle:SalonsolutionsSalon')->findBy(array('ownerId' =>  $userId));	
			
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsService');
			 $updateServiceInformation = $repository->findBy(array('id' =>  $id));			
				
					//echo "<pre>";	print_r($updateServiceInformation);   die();
					
			if($request->getMethod() == 'POST')
			{				
				$title = $request->get("title");  	
				$displayName = $request->get("displayName");  	
				$description = $request->get("description");  	
				$color = $request->get("color");  	
				$price = $request->get("price");  	
				$salonId = $request->get("salonId");  	
				$availability = $request->get("availability");    	
			
		
				$em = $this->getDoctrine()->getEntityManager();
				$confirmedSubscribe = $em->createQueryBuilder() 
				->select('tblService')
				->update('SalonSolutionSalonBundle:SalonsolutionsService',  'tblService')
				->set('tblService.title', ':title')
				->setParameter('title', $title)
				->set('tblService.displayName', ':displayName')
				->setParameter('displayName', $displayName)	
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
								
				return $this->redirect($this->generateUrl('salon_solution_salon_manageServices'));
				
			}	
			return $this->render('SalonSolutionSalonBundle:Page:edit_service.html.twig',array('Salonowner'=> $Salonowner,'updateServiceInformation'=> $updateServiceInformation));

			
		}
		/*------------------------------- End : Function to Edit Services ------------------------------*/
		
		
		
		/**************************** Begin : Function to delete Services ********************************/
	  
		public function deleteServiceAction($id, Request $request)
		{			
					$em = $this->getDoctrine()->getEntityManager();
					$del = $em->getRepository('SalonSolutionSalonBundle:SalonsolutionsService')->find($id);				
			
					if ($del) {
					$em->remove($del);
					$em->flush();
						return $this->redirect($this->generateUrl('salon_solution_salon_manageServices'));  // redirect the page
					}
						
			return $this->render('SalonSolutionSalonBundle:Page:delete_services.html.twig');

			
		}
		/*------------------------------- End : Function to delete Services ------------------------------*/
		
		
		/**************************** Begin : Function to View Services ********************************/
	  
		public function viewServiceAction($id, Request $request)
		{			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsService');
			$viewService = $repository->findBy(array('id' =>  $id));			
			$salonId = $viewService[0]->salonId; 
				//echo "<pre>";	print_r($viewService);   die();
			$em = $this->getDoctrine()->getEntityManager();
			$salonLocation= $em->getRepository('SalonSolutionSalonBundle:SalonsolutionsSalon')->findBy(array('id' =>  $salonId));	
			$getSalonLocation= $salonLocation[0]->city;
			
					
			return $this->render('SalonSolutionSalonBundle:Page:view_service.html.twig', array('viewService'=> $viewService ,'getSalonLocation'=>$getSalonLocation));

			
		}
		/*------------------------------- End : Function to View Services ------------------------------*/
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	/***************** Begin : Function to Manage Appointments ********************************/
	  
		public function manageAppointmentsAction(Request $request)
		{
			$session = $this->getRequest()->getSession(); 			
			$customerId = $session->get('userId');
			
			if($session->get('userId') && $session->get('userId') != '')					
				$customerId = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_salon_login') );

			
			
					
		/*		$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsUser');
			$userDetail = $repository->findBy(array('id' => $customerId));	
			$userId=	$userDetail[0]->id;
			
				//echo "<pre>"; print_r($customerId); die;
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsSalon');
			$salonDetail = $repository->findBy(array('ownerId' => $userId));	
			$salonId=	$salonDetail[0]->id;
			$salonName=	$salonDetail[0]->name;
			$salonCity=	$salonDetail[0]->city; */
			
			//echo "<pre>"; print_r($salonCity); die;
			
			$em = $this->getDoctrine()->getEntityManager();
			$arrServive = $em->createQueryBuilder()
				->select('SalonsolutionsAppointment', 'SalonsolutionsSalon.name', 'SalonsolutionsSalon.city', 'SalonsolutionsUser.firstName', 'SalonsolutionsService.title','SalonsolutionsService.color')
				->from('SalonSolutionSalonBundle:SalonsolutionsAppointment', 'SalonsolutionsAppointment')
				->leftJoin('SalonSolutionSalonBundle:SalonsolutionsService', 'SalonsolutionsService', "WITH", "SalonsolutionsService.id=SalonsolutionsAppointment.serviceId ")
				->leftJoin('SalonSolutionSalonBundle:SalonsolutionsUser', 'SalonsolutionsUser', "WITH", "SalonsolutionsUser.id=SalonsolutionsAppointment.consumerId ")
				->leftJoin('SalonSolutionSalonBundle:SalonsolutionsSalon', 'SalonsolutionsSalon', "WITH", "SalonsolutionsSalon.id=SalonsolutionsAppointment.salonId ")
				->where('SalonsolutionsSalon.ownerId = :ownerId')
				->setParameter('ownerId', $customerId)			
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
				$arrAppointment[$service[0]['id']]['serviceSalonName'] = $service['name']; 
				$arrAppointment[$service[0]['id']]['serviceSalonCity'] = $service['city']; 
			}
			
			//echo "<pre>"; print_r($arrBooking); die;
			
			return $this->render('SalonSolutionSalonBundle:Page:manage_appointments.html.twig', array('arrAppointment'=>$arrAppointment));

			
		}
		
		//--- Begin : Function to  Change Status Appointment  ------------------------------*/
					public function changeStatusAppointmentAction()
					{
						$statusHtml = '';
						$em = $this->getDoctrine()->getEntityManager();
						
						if( isset($_POST['currentStatus']) && $_POST['currentStatus'] == 0 )
						{
							$status = 1;	
							$statusString = 'Confirm';
						}
						else
						{
							$status = 0;
							$statusString = 'Not Confirm';
						}
							
						 $id = $_POST['id'];
						
						if( isset($_POST['objectType']) && $_POST['objectType'] == 'Appointment' )
						{
							$em = $this->getDoctrine()->getEntityManager();
							$confirmedSubscribe = $em->createQueryBuilder() 
							->select('ser')
							->update('SalonSolutionSalonBundle:SalonsolutionsAppointment',  'ser')
							->set('ser.status', ':status')
							->setParameter('status', $status)
							->where('ser.id = :id')
							->setParameter('id', $id)
							->getQuery()
							->getResult();
						}
						$statusHtml.='<a id="status-'.$id.'" class="edit" title="Click to Change" onclick="javascript:changeStatusAppointment(\'status-'.$id.'\','.$status.');">'.$statusString.'</a>'; 
						
						return new response($statusHtml);				
					} 
			
		/*------------------------------- End : Function to Manage Appointments ------------------------------*/
		
		
			/**************************** Begin : Function to ADD Appointment ********************************/
	  
		public function addAppointmentAction(Request $request)
		{
			$session = $this->getRequest()->getSession(); 		
			
			if($session->get('userId') && $session->get('userId') != '')					
				$userId = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_salon_login') );

			
			$userId = $session->get('userId');	
			
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsSalon');
			$salonDetail = $repository->findBy(array('ownerId' => $userId));	
				
				
						
			if ($request->getMethod() == 'POST') 
					{					
						$consumerId = $request->get("consumerId");  	
						$salonId = $request->get("salonId");  	
						$serviceId = $request->get("serviceId");  	
						$scheduledDate	 = $request->get("scheduledDate");  	
						$scheduledTime = $request->get("scheduledTime");  	
					
						$appointment = new SalonsolutionsAppointment();
						
						$appointment->setConsumerId($consumerId);  						 
						$appointment->setSalonId($salonId);  						 
						$appointment->setServiceId($serviceId);  						 
						$appointment->setScheduledDate($scheduledDate);  						 
						$appointment->setScheduledTime($scheduledTime);  		 	
						$appointment->setStatus('1');	
							
						$em = $this->getDoctrine()->getEntityManager();
						$em->persist($appointment);
						$em->flush();
						return $this->redirect($this->generateUrl('salon_solution_salon_manageAppointments'));  // redirect the page
			
					} 
			
			
			//echo "<pre>"; print_r($salonDetail); die;
			
			return $this->render('SalonSolutionSalonBundle:Page:add_appointment.html.twig', array('salonDetail'=>$salonDetail));

		}
		
		function getServicesAction( Request $request){
			
			//$myArray = $request->get('salonId');
			$salonId = $_POST['salonId']; // echo $salonId;   die;
			$html='';
			$em = $this->getDoctrine()->getEntityManager();
			$salonService = $em->createQueryBuilder()
			->select('SalonsolutionsService')
			->from('SalonSolutionSalonBundle:SalonsolutionsService',  'SalonsolutionsService')
			->where('SalonsolutionsService.salonId =:salonId')
			->setParameter('salonId', $salonId)
			->getQuery()
			->getArrayResult(); 

			
			//echo "<pre>"; print_r($consumerName); die; 
			
				foreach($salonService as $service)
			{
				//echo  $salon['id']; die
				
					$html.='<option value="'.$service['id'].'" id="'.$service['id'].'" class="ajx_li" onclick="javascript:updateServiceValue(this.id);" selected>'.$service['title'].'</option>';
			
			} 
			 
			
			return new response($html);	
		}	
				
		
		function getConsumersAction( Request $request){
			
			//$myArray = $request->get('salonId');
			$salonId = $_POST['salonId'];  
			$html='';
			$em = $this->getDoctrine()->getEntityManager();
			$consumerName = $em->createQueryBuilder()
			->select('SalonsolutionsUser')
			->from('SalonSolutionSalonBundle:SalonsolutionsUser',  'SalonsolutionsUser')
			->where('SalonsolutionsUser.parentId =:parentId')
			->setParameter('parentId', $salonId)
			->getQuery()
			->getArrayResult(); 

			//echo "<pre>"; print_r($consumerName); die; 
			
				foreach($consumerName as $name)
			{
				//echo  $salon['id']; die
				
					$html.='<option value="'.$name['id'].'" id="'.$name['id'].'" class="ajx_li" onclick="javascript:updateServiceValue(this.id);" selected>'.$name['firstName'].'</option>';
			
			} 
			 
			
			return new response($html);	
		}	
				
	/*------------------------------- End : Function to Manage Services ------------------------------*/
	
		/*------------------------------- End : Function to ADD Appointment ------------------------------*/
		
		
		
		
			/**************************** Begin : Function to View Appointment ********************************/
	  
		public function viewAppointmentAction($id, Request $request)
		{
			$session = $this->getRequest()->getSession();
			
			if($session->get('userId') && $session->get('userId') != '')					
				$userId = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_salon_login') );


			
			$customerId = $session->get('userId');					
			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsUser');
			$userDetail = $repository->findBy(array('id' => $customerId));	
			$userId=	$userDetail[0]->id;
			
				//echo "<pre>"; print_r($userDetail); die;
				
			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsSalon');
			$salonDetail = $repository->findBy(array('ownerId' => $userId));	
			$salonId=	$salonDetail[0]->id;
			$salonName=	$salonDetail[0]->name;
			$salonCity=	$salonDetail[0]->city;
			
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsAppointment');
			$viewAppointment = $repository->findBy(array('id' =>  $id));			
			
			// 3leftjoins
			$em = $this->getDoctrine()->getEntityManager();
			$arrServive = $em->createQueryBuilder()
				->select('SalonsolutionsAppointment', 'SalonsolutionsUser.firstName', 'SalonsolutionsService.title','SalonsolutionsService.color')
				->from('SalonSolutionSalonBundle:SalonsolutionsAppointment', 'SalonsolutionsAppointment')
				->leftJoin('SalonSolutionSalonBundle:SalonsolutionsService', 'SalonsolutionsService', "WITH", "SalonsolutionsService.id=SalonsolutionsAppointment.serviceId ")
				->leftJoin('SalonSolutionSalonBundle:SalonsolutionsUser', 'SalonsolutionsUser', "WITH", "SalonsolutionsUser.id=SalonsolutionsAppointment.consumerId ")
				->where('SalonsolutionsAppointment.id = :id')
				->setParameter('id', $id)			
				->getQuery()
				->getArrayResult();
			
			//	echo "<pre>"; print_r($arrServive); die;
			
			$arrBooking = array();
			
			foreach($arrServive as $service)
			{
				$arrBooking[$service[0]['id']] = $service[0]; 
				$arrBooking[$service[0]['id']]['serviceTitle'] = $service['title']; 
				$arrBooking[$service[0]['id']]['serviceColor'] = $service['color']; 
				$arrBooking[$service[0]['id']]['serviceFirstName'] = $service['firstName']; 
			}
		
				//echo "<pre>"; print_r($viewAppointment); die;
		
			return $this->render('SalonSolutionSalonBundle:Page:view_appointment.html.twig', array('viewAppointment'=>$arrBooking,'salonName'=>$salonName,'salonCity'=>$salonCity));

			
		}
		/*------------------------------- End : Function to View Appointment ------------------------------*/
		
		
		
			/**************************** Begin : Function to Delete Appointment ********************************/
	  
		public function deleteAppointmentAction($id, Request $request)
		{
			
			$em = $this->getDoctrine()->getEntityManager();
			$del = $em->getRepository('SalonSolutionSalonBundle:SalonsolutionsAppointment')->find($id);				
	
			if ($del) {
			$em->remove($del);
			$em->flush();
				return $this->redirect($this->generateUrl('salon_solution_salon_manageAppointments'));  // redirect the page
			}
		
			return $this->render('SalonSolutionSalonBundle:Page:delete_appointment.html.twig');

		}
		/*------------------------------- End : Function to Delete Appointment ------------------------------*/
		
		
			
		
		
		/**************************** Begin : Function to Manage Advertisements ********************************/
	  
		public function manageAdvertisementsAction(Request $request)
		{
			$session = $this->getRequest()->getSession(); 
			
			if($session->get('userId') && $session->get('userId') != '')					
				$userId = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_salon_login') );
			
			 $userId = $session->get('userId');		 
		
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsAdvertisement');
			$Advertisements = $repository->findBy(array('status' => '1'));
			
			$em = $this->getDoctrine()->getEntityManager();
			$Advertisements = $em->createQueryBuilder()
			->select('adver, salon.name')
			->from('SalonSolutionSalonBundle:SalonsolutionsAdvertisement',  'adver')
			->leftJoin('SalonSolutionSalonBundle:SalonsolutionsSalon', 'salon', "WITH", "salon.id=adver.salonId")
			->where('salon.ownerId = :ownerId')
			->setParameter('ownerId', $userId)				
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
		
			
			return $this->render('SalonSolutionSalonBundle:Page:manage_advertisements.html.twig', array('Advertisements'=> $arrAdvertisements));
			

			
		}
		/*------------------------------- End : Function to Manage Advertisements ------------------------------*/
		
		
		
		/**************************** Begin : Function to Manage Advertisements ********************************/
	  
		public function manageAdvertisementDisplayAction(Request $request)
		{
			$session = $this->getRequest()->getSession(); 	

			if($session->get('userId') && $session->get('userId') != '')					
				$userId = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_salon_login') );


			
			  $userId = $session->get('userId');		 // die;
		
			$em = $this->getDoctrine()->getEntityManager();
			$advertisementDisplaySalon = $em->createQueryBuilder()
			->select('adver, salon.advertisementDisplay') 
			->from('SalonSolutionSalonBundle:SalonsolutionsAdvertisementType',  'adver')
			->leftJoin('SalonSolutionSalonBundle:SalonsolutionsSalon', 'salon', "WITH", "salon.advertisementDisplay=adver.id")
			->where('salon.ownerId = :ownerId')
			->setParameter('ownerId', $userId)				
			->getQuery()
			->getArrayResult();
			
			$arrAdvertisementDisplay = array();
			
			foreach($advertisementDisplaySalon as $advDisplay)
			{
				$arrAdvertisementDisplay[$advDisplay[0]['id']] = $advDisplay[0]; 
				$arrAdvertisementDisplay[$advDisplay[0]['id']]['advertisementDisplay'] = $advDisplay['advertisementDisplay']; 
			}
			
				//echo "<pre>";	print_r($arrAdvertisementDisplay);   die();
		
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsAdvertisementType');
			$advertisementType = $repository->findAll();
					//echo "<pre>";	print_r($advertisementType);   die();	
			
				if($request->getMethod() == 'POST')
			{				
				 $advertisementDisplay = $request->get("advertisementDisplay");   	 
			
		
				$em = $this->getDoctrine()->getEntityManager();
				$advertisementType = $em->createQueryBuilder() 
				->select('tblAdvertisementType')
				->update('SalonSolutionSalonBundle:SalonsolutionsSalon',  'tblAdvertisementType')
				->set('tblAdvertisementType.advertisementDisplay', ':advertisementDisplay')
				->setParameter('advertisementDisplay', $advertisementDisplay)				
				->where('tblAdvertisementType.ownerId = :ownerId')
				->setParameter('ownerId', $userId)
				->getQuery()
				->getResult();
					
								
				return $this->redirect($this->generateUrl('salon_solution_salon_manageAdvertisements'));
				
			}	
		
			
			
			return $this->render('SalonSolutionSalonBundle:Page:manage_advertisement_type.html.twig', array('advertisementType'=> $advertisementType , 'arrAdvertisementDisplay'=>$arrAdvertisementDisplay));
			

			
		}
		/*------------------------------- End : Function to Manage Advertisements ------------------------------*/
		
		
		
		/**************************** Begin : Function to ADD Advertisements ********************************/
	  
		public function addAdvertisementAction(Request $request)
		{
			
			$session = $this->getRequest()->getSession(); 			
			
			if($session->get('userId') && $session->get('userId') != '')					
				$userId = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_salon_login') );

			
			$userId = $session->get('userId');		 
		
			$em = $this->getDoctrine()->getEntityManager();
			$salons = $em->createQueryBuilder()
			->select('salon')
			->from('SalonSolutionSalonBundle:SalonsolutionsSalon', 'salon')
			->where('salon.ownerId = :ownerId')
			->setParameter('ownerId', $userId)				
			->getQuery()
			->getArrayResult();
			
				//echo "<pre>";	print_r($salons);   die();
			
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsSalon');
			$salonDetails = $repository->findBy(array('status' => '1'));
				
				//echo "<pre>";	print_r($salonDetails);   die();
		
			if($request->getMethod() == 'POST')
				{			
					$title = $request->get("title");  				
					$description = $request->get("description");	
					$url = $request->get("url");	
					$salonId = $request->get("salonId");	
					
				 	 $basePath = $this->getBasePathAction();	 					
					 $image = $_FILES['image']['name'];  
					 
					$limit_size= 255000;
					$file_size = $_FILES['image']['size'];	
					
					if($file_size <= $limit_size){
					
					$ranPhotoUpload = rand(1,10000);  							
					 $targetImageAdvertisement = $basePath."/".$this->container->getParameter('gbl_uploadPath_advertisements').$ranPhotoUpload.$image ;   
						
						move_uploaded_file($_FILES['image']['tmp_name'], $targetImageAdvertisement);					
						$addAdvertisement = new SalonsolutionsAdvertisement();
								
						$addAdvertisement->setTitle($title);  						 
						$addAdvertisement->setDescription($description);  	
						$addAdvertisement->setUrl($url);  	
						$addAdvertisement->setSalonId($salonId);  	
						$addAdvertisement->setImage($ranPhotoUpload.$image);  	
						$addAdvertisement->setStatus('1');	
					
						$em = $this->getDoctrine()->getEntityManager();
						$em->persist($addAdvertisement);
						$em->flush();
					
							
					}else{
						
						$this->get('session')->getFlashBag()->add('messageImage', 'Image size is too big , Please Upload min Size 255KB');
							return $this->redirect($this->generateUrl('salon_solution_salon_addAdvertisement'));
						
						}
					 
					
					
					
					return $this->redirect($this->generateUrl('salon_solution_salon_manageAdvertisements'));  // redirect the page
					
				}
		
			return $this->render('SalonSolutionSalonBundle:Page:add_advertisement.html.twig', array('salons'=> $salons));


			
		}
		/*------------------------------- End : Function to ADD Advertisements ------------------------------*/
		
		
		
		
			/**************************** Begin : Function to View Salons ********************************/
	  
		public function viewAdvertisementAction($id, Request $request)
		{
			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsAdvertisement');
			$viewAdvertisement = $repository->findBy(array('id' =>  $id));			
			
			return $this->render('SalonSolutionSalonBundle:Page:view_advertisement.html.twig', array('viewAdvertisement'=> $viewAdvertisement));

			
		}
		/*------------------------------- End : Function to View Salons ------------------------------*/
		
		
		
			/**************************** Begin : Function to View Salons ********************************/
	  
		public function deleteAdvertisementAction($id, Request $request)
		{
			
			$em = $this->getDoctrine()->getEntityManager();
					$del = $em->getRepository('SalonSolutionSalonBundle:SalonsolutionsAdvertisement')->find($id);				
			
					if ($del) {
					$em->remove($del);
					$em->flush();
						return $this->redirect($this->generateUrl('salon_solution_salon_manageAdvertisements'));  // redirect the page
					}
			
			return $this->render('SalonSolutionSalonBundle:Page:delete_advertisement.html.twig');

			
		}
		/*------------------------------- End : Function to View Salons ------------------------------*/
		
		
		
			/**************************** Begin : Function to View Salons ********************************/
	  
		public function editAdvertisementAction($id, Request $request)
		{
			
			$session = $this->getRequest()->getSession(); 			
		
			if($session->get('userId') && $session->get('userId') != '')					
				$userId = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_salon_login') );

			$userId = $session->get('userId');		 
		
			$em = $this->getDoctrine()->getEntityManager();
			$salons = $em->createQueryBuilder()
			->select('salon')
			->from('SalonSolutionSalonBundle:SalonsolutionsSalon', 'salon')
			->where('salon.ownerId = :ownerId')
			->setParameter('ownerId', $userId)				
			->getQuery()
			->getArrayResult();
			
			$em = $this->getDoctrine()->getEntityManager();
			$editAdvertisement = $em->getRepository('SalonSolutionSalonBundle:SalonsolutionsAdvertisement')->findBy(array('id' =>  $id));	
			
			
			if($request->getMethod() == 'POST')
			{				
				$title = $request->get("title");  	
				$description = $request->get("description");  	
				$url = $request->get("url");  		
				$salonId = $request->get("salonId");  	  	
			
		
				$em = $this->getDoctrine()->getEntityManager();
				$confirmedSubscribe = $em->createQueryBuilder() 
				->select('tblAdvertisement')
				->update('SalonSolutionSalonBundle:SalonsolutionsAdvertisement',  'tblAdvertisement')
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
								
				return $this->redirect($this->generateUrl('salon_solution_salon_manageAdvertisements'));
				
			}	
		
			return $this->render('SalonSolutionSalonBundle:Page:edit_advertisement.html.twig', array('editAdvertisement'=> $editAdvertisement,'salons'=> $salons));

			
		}
		/*------------------------------- End : Function to View Salons ------------------------------*/
		
			
			
					
			
		/**************************** Begin : Function to  Manage Employee ********************************/
	  
		public function manageEmployeeAction(Request $request)
		{
			$session = $this->getRequest()->getSession(); 			
			
			if($session->get('userId') && $session->get('userId') != '')					
				$userId = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_salon_login') );

			$userId = $session->get('userId');		 
		
			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsUser');
			$employeeDetail = $repository->findBy(array('type' =>  4 , 'parentId' =>$userId));			
			
			
				//echo "<pre>"; print_r($employeeDetail); die;
			
			return $this->render('SalonSolutionSalonBundle:Page:manage_employee.html.twig', array('employeeDetail'=> $employeeDetail));
			
		}
		/*------------------------------- End : Function to  Manage Employee  ------------------------------*/
		
		/**************************** Begin : Function to  Add Employee ********************************/
	  
		public function addEmployeeAction(Request $request)
		{
			$session = $this->getRequest()->getSession(); 			
			$userId = $session->get('userId');		 
		
			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsSalon');
			$salonDetails = $repository->findBy(array('status' =>  1 , 'ownerId' =>$userId));			
			
			
			//echo "<pre>"; print_r($salonId); die;
		
		
			if ($request->getMethod() == 'POST')
					{						
						$firstName = $request->get("firstName");  	
						$lastName = $request->get("lastName");
						$email = $request->get("email");
						$username = $request->get("username");	
						$password = md5($request->get("password"));	
						$country = $request->get("country");						
						$state = $request->get("state");
						$address = $request->get("address");						
						$city = $request->get("city");						
						$zip = $request->get("zip"); 
						$mobile = $request->get("mobile"); 
						$landline = $request->get("landline"); 
						//$parentId = $request->get("parentId"); 
						$salonId = $request->get("salonId"); 
						$basePath = $this->getBasePathAction();	  
						$photo = $_FILES['photo']['name'];  	
							$ranPhotoUpload = rand(1,10000);  		
							$targetFilePhoto = $basePath."/".$this->container->getParameter('gbl_uploadPath_consumers').$ranPhotoUpload.$photo;            //getBasePathAction() defined into upper 
							move_uploaded_file($_FILES['photo']['tmp_name'], $targetFilePhoto);					
					
						
						$employee = new SalonsolutionsUser();
						
						$employee->setFirstName($firstName);   
						$employee->setLastName($lastName);
						$employee->setUserName($username);
						$employee->setPassword($password);
						$employee->setEmail($email);
						$employee->setCountry($country);								
						$employee->setState($state);								
						$employee->setAddress($address);								
						$employee->setCity($city);								
						$employee->setZip($zip);									
						$employee->setMobile($mobile);									
						$employee->setLandline($landline);									
						//$employee->setParentId($parentId);									
						$employee->setPhoto($ranPhotoUpload.$photo);
						$employee->setType('4');
						$employee->setParentId($userId);
						$employee->setSalonId($salonId);
						$employee->setStatus('1');	
							
						$em = $this->getDoctrine()->getEntityManager();
						$em->persist($employee);
						$em->flush();
									
						return $this->redirect($this->generateUrl('salon_solution_salon_manageEmployee')); 
				
					} 
			
				//echo "<pre>"; print_r($employeeDetail); die;
			
			return $this->render('SalonSolutionSalonBundle:Page:add_employee.html.twig' , array('salonDetails'=>$salonDetails));
			
		}
		/*------------------------------- End : Function to  Manage Employee  ------------------------------*/
		
				
		/**************************** Begin : Function to Edit Employee ********************************/
	  
		public function editEmployeeAction($id, Request $request)
		{
			
			
			$session = $this->getRequest()->getSession(); 			
			if($session->get('userId') && $session->get('userId') != '')					
				$userId = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_salon_login') );

		
		   $userId = $session->get('userId');		
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsUser');
			 $updateEmployeeInformation = $repository->findBy(array('id' =>  $id));			
		
			if($request->getMethod() == 'POST')
			{				
				$firstName = $request->get("firstName");  	
				$lastName = $request->get("lastName");
				$email = $request->get("email");
				$username = $request->get("username");
				$address = $request->get("address");
				$country = $request->get("country");
				$state = $request->get("state");
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
				->update('SalonSolutionSalonBundle:SalonsolutionsUser',  'tblconsumer')
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
				->setParameter('id', $id)
				->getQuery()
				->getResult();		
				//echo "<pre>";	print_r($resultConsumer);   die();
				return $this->redirect($this->generateUrl('salon_solution_salon_manageEmployee'));
			}				
			
			return $this->render('SalonSolutionSalonBundle:Page:edit_employee.html.twig',array('updateEmployeeInformation'=> $updateEmployeeInformation));
			
			
		}		

		/*------------------------------- End : Function to Edit Employee ------------------------------*/
		
		
		
		
			/**************************** Begin : Function to View Employee Details ********************************/
	  
		public function viewEmployeeAction($id, Request $request)
		{
		
			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsUser');
			$employeeDetails = $repository->findBy(array('id' =>  $id));			
				
			
			return $this->render('SalonSolutionSalonBundle:Page:view_employee.html.twig',array('employeeDetails'=> $employeeDetails));

			
		}
		/*------------------------------- End : Function to View Employee Details ------------------------------*/
		
			
			
			
		
			/**************************** Begin : Function to Delete Employee  ********************************/
	  
		public function deleteEmployeeAction($id, Request $request)
		{
			
			$em = $this->getDoctrine()->getEntityManager();
					$del = $em->getRepository('SalonSolutionSalonBundle:SalonsolutionsUser')->find($id);				
			
					if ($del) {
					$em->remove($del);
					$em->flush();
						return $this->redirect($this->generateUrl('salon_solution_salon_manageEmployee'));  // redirect the page
					}
			
			return $this->render('SalonSolutionSalonBundle:Page:delete_advertisement.html.twig');

			
		}
		/*------------------------------- End : Function to Delete Employee  ------------------------------*/
		
		
		
		
		
			
		
		/**************************** Begin : Function to Manage Business Hours ********************************/
	  
		public function manageBusinessHoursAction( Request $request)
		{
			$session = $this->getRequest()->getSession(); 			
			
			if($session->get('userId') && $session->get('userId') != '')					
				$userId = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_salon_login') );

			
			$userId = $session->get('userId');		

			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsSalonHours');
			$updateSalonHours = $repository->findBy(array('salonId' =>  $userId));			
		
			//echo "<pre>";	print_r($updateSalonHours);   die();
		
		
				 if ($request->getMethod() == 'POST')
				  {	
					 
					$name1  = $request->get("sfmon");
					$name2  = $request->get("efmon");
					$name3  = $request->get("ssmon");
					$name4  = $request->get("esmon");
					$name5  = $request->get("sftues");
					$name6  = $request->get("eftues");
					$name7  = $request->get("sstues");
					$name8  = $request->get("estues");
					$name9  = $request->get("sfwed");
					$name10 = $request->get("efwed");
					$name11 = $request->get("sswed");
					$name12 = $request->get("eswed");
					$name13 = $request->get("sfthu");
					$name14 = $request->get("efthu");
					$name15 = $request->get("ssthu");
					$name16 = $request->get("esthu");
					$name17 = $request->get("sffri");
					$name18 = $request->get("effri");
					$name19 = $request->get("ssfri");
					$name20 = $request->get("esfri");
					$name21 = $request->get("sfsat");
					$name22 = $request->get("efsat");
					$name23 = $request->get("sssat");
					$name24 = $request->get("essat");
					$name25 = $request->get("sfsun");
					$name26 = $request->get("efsun");
					$name27 = $request->get("sssun");
					$name28 = $request->get("essun");
					
					$em = $this->getDoctrine()->getEntityManager();
					$resultConsumer = $em->createQueryBuilder() 
					->select('tblHours')
					->update('SalonSolutionSalonBundle:SalonsolutionsSalonHours',  'tblHours')
					->set('tblHours.monFhalfStart', ':monFhalfStart')
					->setParameter('monFhalfStart', $name1)
					->set('tblHours.monFhalfEnd', ':monFhalfEnd')
					->setParameter('monFhalfEnd', $name2)
					->set('tblHours.monShalfStart', ':monShalfStart')
					->setParameter('monShalfStart', $name3)
					->set('tblHours.monShalfEnd', ':monShalfEnd')
					->setParameter('monShalfEnd', $name4)
					->set('tblHours.tuesFhalfStart', ':tuesFhalfStart')
					->setParameter('tuesFhalfStart', $name5)
					->set('tblHours.tuesFhalfEnd', ':tuesFhalfEnd')
					->setParameter('tuesFhalfEnd', $name6)
					->set('tblHours.tuesShalfStart', ':tuesShalfStart')
					->setParameter('tuesShalfStart', $name7)
					->set('tblHours.tuesShalfEnd', ':tuesShalfEnd')
					->setParameter('tuesShalfEnd', $name8)
					->set('tblHours.wedFhalfStart', ':wedFhalfStart')
					->setParameter('wedFhalfStart', $name9)
					->set('tblHours.wedFhalfEnd', ':wedFhalfEnd')
					->setParameter('wedFhalfEnd', $name10)
					->set('tblHours.wedShalfStart', ':wedShalfStart')
					->setParameter('wedShalfStart', $name11)
					->set('tblHours.wedShalfEnd', ':wedShalfEnd')
					->setParameter('wedShalfEnd', $name12)
					->set('tblHours.thuFhalfStart', ':thuFhalfStart')
					->setParameter('thuFhalfStart', $name13)
					->set('tblHours.thuFhalfEnd', ':thuFhalfEnd')
					->setParameter('thuFhalfEnd', $name14)
					->set('tblHours.thuShalfStart', ':thuShalfStart')
					->setParameter('thuShalfStart', $name15)
					->set('tblHours.thuShalfEnd', ':thuShalfEnd')
					->setParameter('thuShalfEnd', $name16)
					->set('tblHours.friFhalfStart', ':friFhalfStart')
					->setParameter('friFhalfStart', $name17)
					->set('tblHours.friFhalfEnd', ':friFhalfEnd')
					->setParameter('friFhalfEnd', $name18)
					->set('tblHours.friShalfStart', ':friShalfStart')
					->setParameter('friShalfStart', $name19)
					->set('tblHours.friShalfEnd', ':friShalfEnd')
					->setParameter('friShalfEnd', $name20)
					->set('tblHours.satFhalfStart', ':satFhalfStart')
					->setParameter('satFhalfStart', $name21)
					->set('tblHours.satFhalfEnd', ':satFhalfEnd')
					->setParameter('satFhalfEnd', $name22)
					->set('tblHours.satShalfStart', ':satShalfStart')
					->setParameter('satShalfStart', $name23)
					->set('tblHours.satShalfEnd', ':satShalfEnd')
					->setParameter('satShalfEnd', $name24)
					->set('tblHours.sunFhalfStart', ':sunFhalfStart')
					->setParameter('sunFhalfStart', $name25)
					->set('tblHours.sunFhalfEnd', ':sunFhalfEnd')
					->setParameter('sunFhalfEnd', $name26)
					->set('tblHours.sunShalfStart', ':sunShalfStart')
					->setParameter('sunShalfStart', $name27)
					->set('tblHours.sunShalfEnd', ':sunShalfEnd')
					->setParameter('sunShalfEnd', $name28)
					->where('tblHours.salonId = :salonId')
					->setParameter('salonId', $userId)
					->getQuery()
					->getResult();		
					
						return $this->redirect($this->generateUrl('salon_solution_salon_manageBusinessHours'));  
					/*
					 * 
					 * 	$hour = new SalonsolutionsSalonHours();
				
					$hour->setSalonId($userId);
					$hour->setMonFhalfStart($name1);
					$hour->setMonFhalfEnd($name2);
					$hour->setMonShalfStart($name3);
					$hour->setMonShalfEnd($name4);
					$hour->setTuesFhalfStart($name5);
					$hour->setTuesFhalfEnd($name6);
					$hour->setTuesShalfStart($name7);
					$hour->setTuesShalfEnd($name8);
					$hour->setWedFhalfStart($name9);
					$hour->setWedFhalfEnd($name10);
					$hour->setWedShalfStart($name11);
					$hour->setWedShalfEnd($name12);
					$hour->setThuFhalfStart($name13);
					$hour->setThuFhalfEnd($name14);
					$hour->setThuShalfStart($name15);
					$hour->setThuShalfEnd($name16);
					$hour->setFriFhalfStart($name17);
					$hour->setFriFhalfEnd($name18);
					$hour->setFriShalfStart($name19);
					$hour->setFriShalfEnd($name20);
					$hour->setSatFhalfStart($name21);
					$hour->setSatFhalfEnd($name22);
					$hour->setSatShalfStart($name23);
					$hour->setSatShalfEnd($name24);
					$hour->setSunFhalfStart($name25);
					$hour->setSunFhalfEnd($name26);
					$hour->setSunShalfStart($name27);
					$hour->setSunShalfEnd($name28);					
					
					$em = $this->getDoctrine()->getEntityManager();
					$em->persist($hour);
					$em->flush(); */
			   }
								
			return $this->render('SalonSolutionSalonBundle:Page:manage_business_hours.html.twig' , array('updateSalonHours' => $updateSalonHours));
		}	
		
		/*------------------------------- End : Function to Manage Business Hours ------------------------------*/
		
		
			/**************************** Begin : Function to Closed Days  ********************************/
	  
		public function closedDaysAction( Request $request)
		{			
			$session = $this->getRequest()->getSession(); 			
			
			if($session->get('userId') && $session->get('userId') != '')					
				$userId = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_salon_login') );

			$userId = $session->get('userId');		

			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsBusinessClosedHours');
			$businessClosedHours = $repository->findAll();			
			//echo "<pre>";	print_r($businessClosedHours); 	 die();
			
			return $this->render('SalonSolutionSalonBundle:Page:closed_days.html.twig' , array('businessClosedHours' => $businessClosedHours));

		}
		/*------------------------------- End : Function to Closed Days ------------------------------*/
		
		
		
			/**************************** Begin : Function to Add closed Hours  ********************************/
	  
		public function addClosedHourAction( Request $request)
		{	
			$session = $this->getRequest()->getSession(); 			
			if($session->get('userId') && $session->get('userId') != '')					
				$userId = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_salon_login') );

			
			$userId = $session->get('userId');		

			
			if ($request->getMethod() == 'POST')
					{						
						$date = $request->get("date");  	
						$closedFromTime = $request->get("closedFromTime");
						$closedToTime = $request->get("closedToTime");						
						
						$closedHour = new SalonsolutionsBusinessClosedHours();
						
						$closedHour->setDate($date);   
						$closedHour->setSalonId($userId);   
						$closedHour->setClosedFromTime($closedFromTime);
						$closedHour->setClosedToTime($closedToTime);
							
						$em = $this->getDoctrine()->getEntityManager();
						$em->persist($closedHour);
						$em->flush();
									
						return $this->redirect($this->generateUrl('salon_solution_salon_closedDays')); 
				
					} 
			
					
			return $this->render('SalonSolutionSalonBundle:Page:add_closed_hour.html.twig');

		}
		/*------------------------------- End : Function to Add closed Hours ------------------------------*/
		
		
		
		/**************************** Begin : Function to  Edit closed Hours  ********************************/
	  
		public function editClosedHourAction($id,  Request $request)
		{			
			$session = $this->getRequest()->getSession(); 			
			
			if($session->get('userId') && $session->get('userId') != '')					
				$userId = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_salon_login') );

			
			$userId = $session->get('userId');		

			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsBusinessClosedHours');
			$updateClosedHour = $repository->findBy(array('id' =>  $id));			
		
			//echo "<pre>";	print_r($updateSalonHours);   die();
		
			if($request->getMethod() == 'POST')
			{				
				$date           =	 $request->get("date");  	
				$closedFromTime = $request->get("closedFromTime");			
				$closedToTime = $request->get("closedToTime");			
		
				$em = $this->getDoctrine()->getEntityManager();
				$resultConsumer = $em->createQueryBuilder() 
				->select('tblClosedHours')
				->update('SalonSolutionSalonBundle:SalonsolutionsBusinessClosedHours',  'tblClosedHours')
				->set('tblClosedHours.date', ':date')
				->setParameter('date', $date)
				->set('tblClosedHours.closedFromTime', ':closedFromTime')
				->setParameter('closedFromTime', $closedFromTime)
				->set('tblClosedHours.closedToTime', ':closedToTime')
				->setParameter('closedToTime', $closedToTime)
				->where('tblClosedHours.salonId = :salonId')
				->setParameter('salonId', $userId)
				->getQuery()
				->getResult();
						
				//echo "<pre>";	print_r($resultConsumer);   die();
				
				return $this->redirect($this->generateUrl('salon_solution_salon_closedDays'));
			}		
		
			return $this->render('SalonSolutionSalonBundle:Page:edit_closed_hour.html.twig' , array('updateClosedHour'=> $updateClosedHour));

		}
		/*------------------------------- End : Function to Edit closed Hours ------------------------------*/
		
		
		
		/**************************** Begin : Function to  View closed Hours  ********************************/
	  
		public function viewClosedHourAction($id,  Request $request)
		{			
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsBusinessClosedHours');
			$viewClosedHour = $repository->findBy(array('id' =>  $id));			
				
		
			return $this->render('SalonSolutionSalonBundle:Page:view_closed_hour.html.twig', array('viewClosedHour'=> $viewClosedHour));

		}
		/*------------------------------- End : Function to Edit closed Hours ------------------------------*/
		
		
		
		/**************************** Begin : Function to  Delete closed Hours  ********************************/
	  
		public function deleteClosedHourAction($id,  Request $request)
		{			
			
			$em = $this->getDoctrine()->getEntityManager();
					$del = $em->getRepository('SalonSolutionSalonBundle:SalonsolutionsBusinessClosedHours')->find($id);				
			
					if ($del) {
					$em->remove($del);
					$em->flush();
						return $this->redirect($this->generateUrl('salon_solution_salon_closedDays'));  // redirect the page
					}
			
			
			return $this->render('SalonSolutionSalonBundle:Page:delete_closed_hour.html.twig');

		}
		/*------------------------------- End : Function to Delete closed Hours ------------------------------*/
		
		
		
			
			
			
			
			
			
			
			

			
		
		
			
		/**************************** Begin : Function to  Manage Employee Messages ********************************/
	  
		public function manageEmployeeMessagesAction(Request $request)
		{
			$session = $this->getRequest()->getSession(); 			
			
			if($session->get('userId') && $session->get('userId') != '')					
				$userId = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_salon_login') );

			
			$userId = $session->get('userId');		 
		
			$em = $this->getDoctrine()->getEntityManager();
			$employeeMessage = $em->createQueryBuilder()
			->select('tblMessage')
			->from('SalonSolutionSalonBundle:SalonsolutionsEmployeeMessages', 'tblMessage' )
			->where('tblMessage.salonOwnerId = :salonOwnerId')
			->setParameter('salonOwnerId', $userId)
			//->addOrderBy('tblMessage.id', 'DESC')
			//->setMaxResults(1)			
			->getQuery()
			->getResult();
			
				//echo "<pre>"; print_r($employeeMessage); die;
			
			return $this->render('SalonSolutionSalonBundle:Page:manage_employee_message.html.twig', array('employeeMessage'=> $employeeMessage));
			
		}
		/*------------------------------- End : Function to  Manage Employee Messages  ------------------------------*/
		
		
		
			
		/**************************** Begin : Function to Add Employee Messages ********************************/
	  
		public function addEmployeeMessageAction(Request $request)
		{
			$session = $this->getRequest()->getSession(); 			
			
			if($session->get('userId') && $session->get('userId') != '')					
				$userId = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_salon_login') );

			
			$userId = $session->get('userId');		 
		
			
				if ($request->getMethod() == 'POST')
					{						
						$message = $request->get("message");  
					
						$employeeMessage = new SalonsolutionsEmployeeMessages();
						
						$employeeMessage->setMessage($message);   
						$employeeMessage->setSalonOwnerId($userId); 
						$employeeMessage->setStatus(1); 
							
						$em = $this->getDoctrine()->getEntityManager();
						$em->persist($employeeMessage);
						$em->flush();
									
						return $this->redirect($this->generateUrl('salon_solution_salon_manageEmployeeMessages')); 
				
			
					} 
					
			return $this->render('SalonSolutionSalonBundle:Page:add_employee_message.html.twig');
			
		}
		/*------------------------------- End : Function to   add Employee Messages  ------------------------------*/
		
		/**************************** Begin : Function to delete  Employee Messages ********************************/
	  
		public function deleteEmployeeMessageAction($id, Request $request)
		{
			
			$em = $this->getDoctrine()->getEntityManager();
					$del = $em->getRepository('SalonSolutionSalonBundle:SalonsolutionsEmployeeMessages')->find($id);				
			
					if ($del) {
					$em->remove($del);
					$em->flush();
						return $this->redirect($this->generateUrl('salon_solution_salon_manageEmployeeMessages'));  // redirect the page
					}
			
			return $this->render('SalonSolutionSalonBundle:Page:delete_employee_message.html.twig');

			
		}
		/*------------------------------- End : Function to delete  Employee Messages ------------------------------*/
		
			
		/**************************** Begin : Function to View  Employee Messages ********************************/
	  
		public function viewEmployeeMessageAction($id, Request $request)
		{
			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsEmployeeMessages');
			$viewEmployeeMessage = $repository->findBy(array('id' =>  $id));			
			
			
			return $this->render('SalonSolutionSalonBundle:Page:view_employee_message.html.twig', array('viewEmployeeMessage'=> $viewEmployeeMessage));

			
		}
		/*------------------------------- End : Function to View  Employee Messages ------------------------------*/
		
		
		/**************************** Begin : Function to Edit  Employee Messages ********************************/
	  
		public function editEmployeeMessageAction($id, Request $request)
		{
			$session = $this->getRequest()->getSession();
			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsEmployeeMessages');
			$updateEmployeeMessage = $repository->findBy(array('id' =>  $id));			
		
			if($request->getMethod() == 'POST')
			{				
				$message = $request->get("message");  
			
		
				$em = $this->getDoctrine()->getEntityManager();
				$confirmedSubscribe = $em->createQueryBuilder() 
				->select('tblEmployeeMessage')
				->update('SalonSolutionSalonBundle:SalonsolutionsEmployeeMessages',  'tblEmployeeMessage')
				->set('tblEmployeeMessage.message', ':message')
				->setParameter('message', $message)				
				->set('tblEmployeeMessage.status', ':status')
				->setParameter('status', 1)				
				->where('tblEmployeeMessage.id = :id')
				->setParameter('id', $id)
				->getQuery()
				->getResult();
				
							
				return $this->redirect($this->generateUrl('salon_solution_salon_manageEmployeeMessages'));
				
			}			
			
			return $this->render('SalonSolutionSalonBundle:Page:edit_employee_message.html.twig',array('updateEmployeeMessage'=> $updateEmployeeMessage));

			
		}
		/*------------------------------- End : Function to Edit  Employee Messages ------------------------------*/
		
		 
			
		
		/**************************** Begin : Function to Appointments Per Day ********************************/
	  
		public function manageAppointmentsPerDayAction(Request $request)
		{
			$session = $this->getRequest()->getSession(); 			
			
			if($session->get('userId') && $session->get('userId') != '')					
				$userId = $session->get('userId');	
			else
				return $this->redirect( $this->generateUrl('salon_solution_salon_login') );

			$userId = $session->get('userId');	
			
			$repository = $this->getDoctrine()->getRepository('SalonSolutionSalonBundle:SalonsolutionsSalon');
			$appointmentsPerDay = $repository->findBy(array('ownerId' =>  $userId));			
		
			if($request->getMethod() == 'POST')
			{				
				$maxBookingsCustom = $request->get("maxBookingsCustom");  			
		
				$em = $this->getDoctrine()->getEntityManager();				
				$confirmedSubscribe = $em->createQueryBuilder() 
				->select('tblSalon')
				->update('SalonSolutionSalonBundle:SalonsolutionsSalon',  'tblSalon')
				->set('tblSalon.maxBookingsCustom', ':maxBookingsCustom')
				->setParameter('maxBookingsCustom', $maxBookingsCustom)
				->where('tblSalon.ownerId = :ownerId')
				->setParameter('ownerId', $userId)
				->getQuery()
				->getResult();
				
							
				return $this->redirect($this->generateUrl('salon_solution_salon_manageAppointmentsPerDay'));
				
			}	
				
			//echo "<pre>"; print_r($appointmentsPerDay); die;
			
			return $this->render('SalonSolutionSalonBundle:Page:manage_employee_appointment_per_day.html.twig' , array('appointmentsPerDay'=> $appointmentsPerDay ));

			
		}
		/*------------------------------- End : Function to Appointments Per Day ------------------------------*/
		

		
		
		
		
		
		
		
		
	
}
