<?php   
	require_once(dirname(__FILE__) . '/email/Email.php');
	require_once(dirname(__FILE__) . '/payment/payment_util.php');
	require_once(__DIR__ ."/business_def.php");
	//print_r($_REQUEST);
	handle_order();	

	function handle_order()
	{
		$params=array_merge(array(
			'error_page'=>'creditcard_form.php',
			'order_type'=>'arbitrary',
			'item_name'=>$_REQUEST['item_des'],
			'public_key'=>$_REQUEST['public_key']
		), $_REQUEST);
		if(handle_adhoc_cc_order($params)){
			//header('Location: thankyou.php');
			header('Location: /thankyou.php');
			try{ 
				$customer_email = $_REQUEST['customer_email'];
				
					emailOrderProcessed($customer_email,$params);
			}catch (Exception $e) {
    				
  			}
  			try{ 	
				notifyMeOrderProcessed(array('subject'=>'Credit card order',
 					'body'=>print_r($_REQUEST,1),
 		
 				));
 			}catch (Exception $e) {
    				
  			}	
 			
		}
	}

	function handle_adhoc_cc_order($params)
	{
		
		require_once(__DIR__."/../stripe-php/stripe-php-1.6.5/lib/Stripe.php");
		$customer_email=$_REQUEST['customer_email'];
		$item_name=$params['item_name'];
		$item_price=$params['item_price'];
		$payment_test=$params['payment_test'];
		$plan_id=$params['plan_id'];
		$order_cc_error = false;
		$error_page=$params['error_page'];
		$public_key=$params['public_key'];
		
		if (cc_input_validate($order_cc_error) && $_POST) {
  			Stripe::setApiKey(getStripePrivateKey($public_key));
  			
  			try {
      			$token = $_POST['stripeToken'];
  
      				$price=$item_price * 100;
      				$description = $item_name . " email:$customer_email";
      				if($payment_test)$price=100;
      			
      				 // create the charge on Stripe's servers - this will charge the user's card
      				if($plan_id){ 
						$customer = Stripe_Customer::create(array(
  							"card" => $token,
  							"plan" => $plan_id,
  							"email" => $customer_email
  							)
						);
						$charge=$customer;
      				}
      				else{
  						$charge = Stripe_Charge::create(array(
   							"amount" => $price, // amount in cents, again
   							"currency" => "usd",
  	 						"card" => $token,
   							"description" => $description)
 						);
      				}
 					
 					
      			
  			}			
  			catch (Exception $e) {
    			$order_cc_error = $e->getMessage();
  			}
  			
  			if(!$charge){
  				$order_cc_error="Failed to charge credit card.";
				
			}
				
		}
		
		if ($order_cc_error) {
			//include "$error_page";
			header('Location: /creditcard_form.php?item_des=$item_name&item_price=$item_price');
			return false;
	
		}
		//success
				
		return true;
	}

	function cc_input_validate(&$order_cc_error)
	{
		if (!isset($_POST['stripeToken'])){
      		$order_cc_error= "The Stripe Token was not generated correctly.  Please try again. ".print_r($_POST,1);
		
		}
		else if(!filter_var($_REQUEST['customer_email'], FILTER_VALIDATE_EMAIL)) {
    		$order_cc_error = "!Please enter a valid email. ";
			
		}
		if($order_cc_error)return false;			
      	return true;			
	}
?>

?>
