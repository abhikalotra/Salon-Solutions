<?php 
	function runStripe()
	{
		require_once('Stripe/lib/Stripe.php');
			Stripe::setApiKey("sk_test_ZPmfNFOUBUAY3YyiSSzUPMA8");
			//Stripe::setApiKey("sk_test_76rvqe8M1VH2uCa1sjVfL0Au");
			
			$charge = Stripe_Charge::create(array(
				  "amount" => 1500,
				  "currency" => "usd",
				  "card" => $_POST['stripeToken'],
				  "description" => "Charge for Facebook Login code."
				));
				
				 //echo "<pre>"; print_r($charge); 	  die;
			//send the file, this line will be reached if no error was thrown above
			return "<h1>Your payment has been completed. We will send you the Facebook Login code in a minute.</h1>";


		
	}
?>
