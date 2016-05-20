<?php 
	require_once __DIR__ ."/httputil.php";
	require_once __DIR__ ."/util/string_util.php";
	require_once __DIR__ ."/users/users.inc";
	require_once __DIR__ ."/api-products.php";
	require_once __DIR__ ."/business_def.php";
	require_once __DIR__ ."/payment/order.php";
	$PAYMENT_TEST=0;
	handle_api_order();
	
?>

<?php
	
	function handle_api_order(){
		global $PAYMENT_TEST;
		
		my_session_start();
		
		$products=&$_SESSION['products'];
		
		if(!$products){
			$error="No product exits in your shopping cart!";
		}
		else{
			APIProducts::check_update_cart_product();
			
			$pay_choice=$_REQUEST['pay_choice'];
			if($pay_choice=='pp'){
				api_paypal_order($products, $_SESSION['myuser']);	
			}
			else{
				api_cc_order($products,  $_SESSION['myuser']);	
			}
		}		
	}
	function api_cc_order($products, $user){
		global $STRIP_API_CURRENT_SECRET_KEY, $PAYMENT_TEST;
		require_once(__DIR__."/../stripe-php/stripe-php-1.6.5/lib/Stripe.php");
	
		
		$username=$user->username;
		$customer_email=$user->email;
		
		$order_cc_error = false;
		
		if (cc_input_validate($order_cc_error) && $_POST) {
  			Stripe::setApiKey($STRIP_API_CURRENT_SECRET_KEY);
  			
  			try {		
      			$token = $_POST['stripeToken'];
      			
      			$agg = APIProducts::get_product_aggregate_info($products);
      			
 				if($agg['has_onetime_items']){
					$customer = Stripe_Customer::create(array(
						'card'=>$token,
						'description'=>$customer_email
					));
					
					Stripe_Charge::create(array(
						'customer'=>$customer['id'],
						'amount'=> ($PAYMENT_TEST? 1 : $agg['onetime_items_price']) * 100,
						'currency'=>'usd',
						'description'=>$agg['onetime_items_description']
					));
				
				}       		
      			if($agg['has_subscription']){
      				$plan_id=$agg['subscription_id'];
      			
      				//create plan
      				try{
      					$plan=Stripe_Plan::create(array(
      						'amount'=>$agg['subscription_price']*100,
      						'interval'=>'month',
      						'name'=>$agg['subscription_description'],
							'id'=>$plan_id,
      						'currency'=>'usd'	
      					));
      				}catch(Exception $e){
      					if(stripos($e->getMessage(), "exists")===false)
      						throw $e;
      					
      				}
      				if(isset($customer)){
      					$customer->updateSubscription(array("plan" => $plan_id));
      				}
      				else{ 
						$customer = Stripe_Customer::create(array(
  							"card" => $token,
  							"plan" => $plan_id,
  							"email" => $customer_email,
  							"description"=>$agg['subscription_description']
  							)
						);
      				}
					
      			}
      			
    			
      			
  			}			
  			catch (Exception $e) {
    			$order_cc_error = "unexpected error: ".$e->getMessage();
  			}
		}
		try{ 
			
			if($order_cc_error){	
				//include basename($_SERVER['HTTP_REFERER']);
				include "order-api-checkout.php";
				return;
	
			}
			else{
				fill_api_order($products, $agg, $user);
			}
		}catch(Exception $e){
			
		}
		//success
		
		//header('Location: thankyou.php');			
	}
	function api_paypal_order($products, $user){
		global $PAYMENT_TEST;
		
		//print_r($user);
		//print_r($products);
		
		
		$params=array();

		$params['products']=$products;

		$params['paypal_email']= getRandomPaymentEmail();
		//$cancel_return_page='http://www.whoisxmlapi.com/order_paypal.php'.'?'
			//  	.http_build_query(array('bwl_num_domains'=>$bwl_num_domains, 'bwl_speed'=>$bwl_speed))
			  //	.'#bulk_whois_lookup_service';
		$return_page = 'http://www.whoisxmlapi.com/thankyou.php';
		$params['return_page']=$return_page;
		$params['payment_test'] = $PAYMENT_TEST;
		$params['user']=array('username'=>$user->username,'email'=>$user->email);
		handle_multiple_paypal_subscription($params);
		
	}
	

	function cc_input_validate(&$order_cc_error){
		if (!isset($_POST['stripeToken'])){
      		$order_cc_error= "The Stripe Token was not generated correctly.  Please try again. ".print_r($_POST,1);
		
		}
		
		if($order_cc_error)return false;			
      	return true;			
	}
?>

<?php
	
	function handle_multiple_paypal_subscription($input_params){
		
		$api_products = APIProducts::$api_products;
		$return_page =  $input_params['return_page'];  
		$cancel_return_page = $input_params['cancel_return_page'];
		$paypal_email = $input_params['paypal_email'];
		$payment_test=  $input_params['payment_test'];
		$sandbox=  $input_params['sandbox'];
		$products= $input_params['products'];	
		$user=$input_params['user'];
		$agg=APIProducts::get_product_aggregate_info($products);
		
		if($sandbox){
			$url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		}
		else $url = 'https://www.paypal.com/cgi-bin/webscr';
		$params = array(
			'business' => $paypal_email, 
			'page_style' =>'whoisxmlapi',
			 'no_shipping'=>1,
			  'cancel_return'=>$cancel_return_page,
			  'return'=>$return_page,
			  'upload'=>1
			  
		);
		$item_name= $agg['description'];
		$params['item_name']=$item_name;
		
		if($agg['has_subscription']){
			$monthly_total=$agg['subscription_price'];
			$first_month_total=$agg['subscription_price'] + $agg['onetime_items_price'];
			$params['cmd'] = '_xclick-subscriptions';
			$params['a1']=$first_month_total;
			$params['t1']='M';
			$params['p1']='1';
			$params['a3']=$monthly_total;
			$params['t3']='M';
			$params['p3']=1;
			$params['src']=1;
			$params['sra']=1;
		
			if($payment_test){
				$params['a1']=$params['a3']=1;
			}
		}
		else{
			$params['cmd'] = '_xclick';
			$params['amount'] = $payment_test ? 1: $agg['onetime_items_price'];
		}
		$params['custom']='api-orders';
		$order_info = json_encode(array(
			'products'=>$products,
			'first_payment_date'=>time(),
			'user'=>array('username'=>$user['username'], 'email'=>$user['email'])
		));
		$order_info = urlencode(StringUtil::hash_dn($order_info));
		$params['notify_url']='http://www.whoisxmlapi.com/paypal/paypal.php?action=ipn&oif='.$order_info;
		$url .= '?'. http_build_query($params);
	
		if($url)header('location:' . $url);
		
	}
	
	function copy_array_keys_match($from, &$to, $needles){
		foreach($from as $key=>$val){
			if(array_contains_match($key,$needles)){
				
				$to[$key]=$val;
			}
		}
	}
	function array_contains_match($element, $array){
		foreach($array as $val){
			if(stripos($element, $val)!==false){
				return true;
			}
		}
		return false;
	}
	function set_array_value_by_keys_match($array,&$match, $val){
		foreach($array as $key=>$val){
			if(stripos($key, $match)!==false){
				$array[$key]=$val;
			}
		}
	}
?>

<?php
	//backend fill functions
	function fill_api_order($products, $agg, $user){
		Order::fill_api_order($products, $agg, $user);
	}

?>