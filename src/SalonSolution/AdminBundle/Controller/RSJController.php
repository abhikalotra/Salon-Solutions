<?php

	namespace SalonSolution\AdminBundle\Controller;

	use dompdf;
	use swift;
	
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



class RSJController extends Controller
{
	
    
    /************** Begin : Function to  Good Morning send Scheduled Mail To Admin Start ********************/
	
		public function sendScheduledMailToAdminAction(Request $request)
		{
			$currentDate =  date("d-m-Y"); 
			
			$em = $this->getDoctrine()->getEntityManager();
			$getCurrentDate = $em->createQueryBuilder()
				->select('SalonsolutionsAppointment', 'SalonsolutionsSalon.ownerId', 'SalonsolutionsUser.email', 'SalonsolutionsUser.firstName', 'SalonsolutionsUser.lastName', 'SalonsolutionsService.displayName')
				->from('SalonSolutionAdminBundle:SalonsolutionsAppointment', 'SalonsolutionsAppointment')
				->leftJoin('SalonSolutionAdminBundle:SalonsolutionsSalon', 'SalonsolutionsSalon', "WITH", "SalonsolutionsSalon.id=SalonsolutionsAppointment.salonId")
				->leftJoin('SalonSolutionAdminBundle:SalonsolutionsUser', 'SalonsolutionsUser', "WITH", "SalonsolutionsUser.id=SalonsolutionsSalon.ownerId")
				->leftJoin('SalonSolutionAdminBundle:SalonsolutionsService', 'SalonsolutionsService', "WITH", "SalonsolutionsService.id=SalonsolutionsAppointment.serviceId")
			
				->where('SalonsolutionsAppointment.scheduledDate = :scheduledDate')
				->setParameter('scheduledDate', $currentDate)
				->getQuery()
				->getArrayResult();
				//echo "<pre>"; print_r($getCurrentDate); die;
				
			$arrEmailAppointment = array();
				 
			foreach($getCurrentDate as $currentDetail)
			{
				$arrEmailAppointment[$currentDetail[0]['id']] = $currentDetail[0]; 
				$arrEmailAppointment[$currentDetail[0]['id']]['currentEmail'] = $currentDetail['email']; 	$arrEmailAppointment[$currentDetail[0]['id']]['currentFirstName'] = $currentDetail['firstName']; 
				$arrEmailAppointment[$currentDetail[0]['id']]['currentLastName'] = $currentDetail['lastName']; 
				$arrEmailAppointment[$currentDetail[0]['id']]['currentServiveName'] = $currentDetail['displayName']; 
					
					
			if($arrEmailAppointment[$currentDetail[0]['id']]['currentEmail'])
				{		
					 	$html = '<table width="550" style=" border-collapse:collapse; border:1px solid #CCC;">
								<tr  >
									<th style="background-color:#b9c9fe; margin-top: 15px;"  colspan="4">Appointment Detail</th>
								</tr>
							
															

								<tr >
									<td style="background-color:white; padding:5px; border:1px solid #CCC; border-width:1px 0; font-weight: bold;">  Name </td>
									
									<td style="background-color:White; padding:5px; border:1px solid #CCC; border-width:1px 0; font-weight: bold;"> Service </td>
									
									<td style="background-color:White; padding:5px; border:1px solid #CCC; border-width:1px 0; font-weight: bold;">  Date </td>
									
									<td style="background-color:white; padding:5px; border:1px solid #CCC; border-width:1px 0; font-weight: bold;">  Time </td>
									
								</tr>
								<tr >
									
									<td style = "background-color:white;"> '  .$arrEmailAppointment[$currentDetail[0]['id']]['currentFirstName'].'     '.$arrEmailAppointment[$currentDetail[0]['id']]['currentLastName'].  '</td>
									 
									<td  style = "background-color:white;">'  .$arrEmailAppointment[$currentDetail[0]['id']]['currentServiveName'].  '</td> 
									
									<td  style = "background-color:white;">'  .$arrEmailAppointment[$currentDetail[0]['id']]['scheduledDate'].  '</td>
									
									<td style = "background-color:white;">'  .$arrEmailAppointment[$currentDetail[0]['id']]['scheduledTime'].  '</td>
								</tr>
								</table>
							';
						$dompdf = $this->get('slik_dompdf');
					  $dompdf->getpdf($html);
						$pdfoutput = $dompdf->output();
						
				
						$message = \Swift_Message::newInstance()
						->setSubject('Appointment Schedule Details')
						->setFrom('support@salonSolution.com')
						->setTo($arrEmailAppointment[$currentDetail[0]['id']]['currentEmail'])
						->setBody('Hello '  .$arrEmailAppointment[$currentDetail[0]['id']]['currentFirstName'].' , 
						Your Appointment Time Scheduled into the Attachment 
						')
						->attach(\Swift_Attachment::newInstance($pdfoutput, 'Scheduled.pdf', 'application/pdf')); // Attach 
					
					
					
					
					$mailer = $this->get('mailer');

					$mailer->send($message);

					$spool = $mailer->getTransport()->getSpool();
					$transport = $this->get('swiftmailer.transport.real');

					$spool->flushQueue($transport);
					
						
						
				}else{
				echo "no email";

				} 
			}


			
		}
		
		
		/*---------------- End : Function to  Good Morning send Scheduled Mail To Admin Admin  End -----------------*/
			 
    /******************* Begin : Function to  Good Morning send Scheduled Mail ToConsumer Start ****************/
	
	
		public function sendScheduledMailToConsumerAction(Request $request)
		{
			$currentDate =  date("d-m-Y"); 
			
			$em = $this->getDoctrine()->getEntityManager();
			$getCurrentDateConsumer = $em->createQueryBuilder()
				->select('SalonsolutionsAppointment', 'SalonsolutionsSalon.ownerId','SalonsolutionsSalon.domain','SalonsolutionsSalon.logo', 'SalonsolutionsUser.email', 'SalonsolutionsUser.firstName', 'SalonsolutionsUser.lastName', 'SalonsolutionsService.displayName')
				->from('SalonSolutionAdminBundle:SalonsolutionsAppointment', 'SalonsolutionsAppointment')
				->leftJoin('SalonSolutionAdminBundle:SalonsolutionsSalon', 'SalonsolutionsSalon', "WITH", "SalonsolutionsSalon.id=SalonsolutionsAppointment.salonId")
				->leftJoin('SalonSolutionAdminBundle:SalonsolutionsUser', 'SalonsolutionsUser', "WITH", "SalonsolutionsUser.id=SalonsolutionsAppointment.consumerId")
				->leftJoin('SalonSolutionAdminBundle:SalonsolutionsService', 'SalonsolutionsService', "WITH", "SalonsolutionsService.id=SalonsolutionsAppointment.serviceId")
			
			->where('SalonsolutionsAppointment.scheduledDate = :scheduledDate')
				->setParameter('scheduledDate', $currentDate)
				->getQuery()
				->getArrayResult(); 
				
				//echo "<pre>"; print_r($getCurrentDateConsumer); die;	
			
			
			 $arrGetCurrentDateConsumer = array();
					
				foreach($getCurrentDateConsumer as $getConsumerDetail)
				{
					$arrGetCurrentDateConsumer[$getConsumerDetail[0]['id']] = $getConsumerDetail[0]; 
					$arrGetCurrentDateConsumer[$getConsumerDetail[0]['id']]['currentEmail'] = $getConsumerDetail['email']; 
					$arrGetCurrentDateConsumer[$getConsumerDetail[0]['id']]['currentFirstName'] = $getConsumerDetail['firstName']; 
					$arrGetCurrentDateConsumer[$getConsumerDetail[0]['id']]['currentLastName'] = $getConsumerDetail['lastName']; 
					$arrGetCurrentDateConsumer[$getConsumerDetail[0]['id']]['currentServiveName'] = $getConsumerDetail['displayName']; 
					$arrGetCurrentDateConsumer[$getConsumerDetail[0]['id']]['currentDomain'] = $getConsumerDetail['domain']; 
					$arrGetCurrentDateConsumer[$getConsumerDetail[0]['id']]['currentLogo'] = $getConsumerDetail['logo']; 
					//$arrGetCurrentDateConsumer[$getConsumerDetail[0]['id']]['currentScheduledTime'] = $getConsumerDetail['scheduledTime']; 
					
				//echo "<pre>"; print_r($arrGetCurrentDateConsumer[$getConsumerDetail[0]['id']]['scheduledTime']); die;	
				
			if($arrGetCurrentDateConsumer[$getConsumerDetail[0]['id']]['currentEmail'])
				{		
					
					$html = '<table width="550"  style=" border-collapse:collapse; border:1px solid #CCC;">
														
								<tr  >										
									<th style="background-color:#b9c9fe; margin-top: 15px;" colspan="4">    '  .$arrGetCurrentDateConsumer[$getConsumerDetail[0]['id']]['currentDomain'].' :  Appointment Detail</th>
								</tr>
							
															

								<tr >
									<td style="background-color:#D2E4FC; padding:5px; border:1px solid #CCC; border-width:1px 0; font-weight: bold;  margin-top: 15px; text-align: center;"> Name </td>
									
									<td style="background-color:#D2E4FC; padding:5px; border:1px solid #CCC; border-width:1px 0; font-weight: bold;  margin-top: 15px; text-align: center;">  Service </td>
									
									<td style="background-color:#D2E4FC; padding:5px; border:1px solid #CCC; border-width:1px 0; font-weight: bold;  margin-top: 15px; text-align: center;">  Date </td>
									
									<td style="background-color:#D2E4FC; padding:5px; border:1px solid #CCC; border-width:1px 0; font-weight: bold;  margin-top: 15px; text-align: center;" >  Time </td>
									
								</tr>
								<tr >
									
									<td style = "background-color:#D2E4FC;   margin-top: 15px; text-align: center;"> '  .$arrGetCurrentDateConsumer[$getConsumerDetail[0]['id']]['currentFirstName'].'     '.$arrGetCurrentDateConsumer[$getConsumerDetail[0]['id']]['currentLastName'].  '</td>
									
									<td  style = "background-color:#D2E4FC;   margin-top: 15px; text-align: center;">'  .$arrGetCurrentDateConsumer[$getConsumerDetail[0]['id']]['currentServiveName'].  '</td>
									
									<td  style = "background-color:#D2E4FC;   margin-top: 15px; text-align: center;">'  .$arrGetCurrentDateConsumer[$getConsumerDetail[0]['id']]['scheduledDate'].  '</td>
																		
									<td style = "background-color:#D2E4FC;   margin-top: 15px; text-align: center;">'  .$arrGetCurrentDateConsumer[$getConsumerDetail[0]['id']]['scheduledTime'].  '</td>
								</tr>
								</table>
							';
							
						$dompdf = $this->get('slik_dompdf');
					  $dompdf->getpdf($html);
						$pdfoutput = $dompdf->output();
						
				
						$message = \Swift_Message::newInstance()
						->setSubject('Appointment Schedule Details')
						->setFrom('support@salonSolution.com')
						->setTo($arrGetCurrentDateConsumer[$getConsumerDetail[0]['id']]['currentEmail'])
						->setBody('Hello '  .$arrGetCurrentDateConsumer[$getConsumerDetail[0]['id']]['currentFirstName'].' , 
						Your Appointment Time Scheduled into the Attachment 
						')
						->attach(\Swift_Attachment::newInstance($pdfoutput, 'Scheduled.pdf', 'application/pdf')); // Attach 
					
					
					
					
					$mailer = $this->get('mailer');

					$mailer->send($message);

					$spool = $mailer->getTransport()->getSpool();
					$transport = $this->get('swiftmailer.transport.real');

					$spool->flushQueue($transport);
					
						
			/////////////////////
			
			
			
					/* $txta = 'hello';			
					$filename = $txta.'.doc';
				 
					$email = $arrGetCurrentDateConsumer[$getConsumerDetail[0]['id']]['currentEmail'];
					$to = $email;
					$subject = "Appointment Schedule Details";
					
					$txt=   '<table width="700"  style=" border-collapse:collapse; border:1px solid #CCC;">
								
								
								<tr  >										
									<th style="background-color:#b9c9fe; margin-top: 15px;" colspan="4">    '  .$arrGetCurrentDateConsumer[$getConsumerDetail[0]['id']]['currentDomain'].' :  Appointment Detail</th>
								</tr>
							
															

								<tr >
									<td style="background-color:#D2E4FC; padding:5px; border:1px solid #CCC; border-width:1px 0; font-weight: bold;  margin-top: 15px; text-align: center;"> Name </td>
									
									<td style="background-color:#D2E4FC; padding:5px; border:1px solid #CCC; border-width:1px 0; font-weight: bold;  margin-top: 15px; text-align: center;">  Service </td>
									
									<td style="background-color:#D2E4FC; padding:5px; border:1px solid #CCC; border-width:1px 0; font-weight: bold;  margin-top: 15px; text-align: center;">  Date </td>
									
									<td style="background-color:#D2E4FC; padding:5px; border:1px solid #CCC; border-width:1px 0; font-weight: bold;  margin-top: 15px; text-align: center;" >  Time </td>
									
								</tr>
								<tr >
									
									<td style = "background-color:#D2E4FC;   margin-top: 15px; text-align: center;"> '  .$arrGetCurrentDateConsumer[$getConsumerDetail[0]['id']]['currentFirstName'].'     '.$arrGetCurrentDateConsumer[$getConsumerDetail[0]['id']]['currentLastName'].  '</td>
									
									<td  style = "background-color:#D2E4FC;   margin-top: 15px; text-align: center;">'  .$arrGetCurrentDateConsumer[$getConsumerDetail[0]['id']]['currentServiveName'].  '</td>
									
									<td  style = "background-color:#D2E4FC;   margin-top: 15px; text-align: center;">'  .$arrGetCurrentDateConsumer[$getConsumerDetail[0]['id']]['scheduledDate'].  '</td>
																		
									<td style = "background-color:#D2E4FC;   margin-top: 15px; text-align: center;">'  .$arrGetCurrentDateConsumer[$getConsumerDetail[0]['id']]['scheduledTime'].  '</td>
								</tr>
								</table>
							';				 				
					$headers = "MIME-Version: 1.0" . "\r\n";
					$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
					$headers .= 'From: <todayAppointment@salonSolution.com>' . "\r\n";
					$headers .= "Content-Disposition: attachment; filename=\"" . $filename . "\"\r\n\r\n";

					
					$retval = mail($to,$subject,$txt,$headers);	
					 if( $retval == true )  {						
							  echo "Message sent successfully...";  
						   }  else  {
								echo "Message could not be sent...";
						   }   */
						
						
				}else{
				echo "no email";

				} 

			}
		

		
		}
		
		
		/*----------- End : Function to Good Morning send Scheduled Mail To Consumer End ------------------*/
			
		
			
	
	
}