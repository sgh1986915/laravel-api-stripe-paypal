<?php

 	 require_once __DIR__."/../util.php";

	//require_once "price.php";
	require_once dirname(__FILE__)."/../business_def.php";
	require_once dirname(__FILE__)."/../httputil.php";
  	require_once dirname(__FILE__) . "/../db_cart/db_cart_class.php";
  	require_once dirname(__FILE__) . "/../users/account.php";
  	require_once dirname(__FILE__) . "/..//users/users.conf";
  	require_once dirname(__FILE__) . "/..//payment/payment_util.php";

	$PAYMENT_TEST = 0;
	$sandbox = $_REQUEST["sandbox"];
	$PAYPAL_EMAIL = getRandomPaymentEmail();




	$order_username = $_REQUEST['order_username'];
	$pay_choice = $_REQUEST["pay_choice"];



	$order_error = false;
	if(!$order_username || strlen($order_username) <= 0){
		$order_error ="You must enter a username for the account you wish to make the purchase for";
	}
	else if(!validate_username($order_username)){
		$order_error ="The username may not contain characters other than letters, numbers, @, _ or -";
	}
  else if(!$pay_choice){
		$order_error = "You must select a payment choice.";
	}
  else{
    my_session_start();
    $cart = $_SESSION['cart'];
     if(!is_object($cart)){
      $order_error = "You didn't order any reports. " ;
     }
  }


  if(!$order_error){
  	$order_error = check_credits();
  }


  if(!$order_error){
    $customer = false;
    if(is_object($user = $_SESSION['myuser'])){
      $customer = $user->username;
    }
    $order_params = array('order_for'=>$order_username);
    if($customer)$order_params['customer']=$customer;
    if(!$cart->update_order($order_params, array('username'=>$order_username), $_SESSION['order_id'])){
      $order_error = $cart->error;
    }
  }

  if(!$order_error){
	if(is_object($user = $_SESSION['myuser'])){
		$userAccount = getUserAccount( $user->username);
		if($userAccount){
			$balance = max(0, $userAccount->reverse_whois_balance) + max(0, $userAccount->reverse_whois_monthly_balance);

			$num_items_cart = $cart->get_num_items_session();
			$num_credits_needed=$cart->get_num_credits_session();

			if($num_items_cart > 0 && $balance > 0 && $balance  >= $num_credits_needed){


					//success fulfill rw order with credits
					 $payment_util = new payment_util();

    				if(!$payment_util->fill_order($_SESSION['order_id'])){
    					$order_error =  $payment_util->error;
    				}
					else {
						if(!$userAccount->deduct_rw_credits($num_credits_needed)){
							$order_error = $userAccount->error;
						}
						else{
							header('location:' . "$USERS_BASE_URL/thankyou_rw.php?order_type=report");
							exit();
						}
					}


			}
		}
	}
  }

	if($order_error){
		setcookie('order_error',$order_error,time()+3600,'/'); // modified
		$redirectUrl = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../reverse-whois-order.php'; // modified
		header('Location: '.$redirectUrl); // modified
		exit(); // modified
		include dirname(__FILE__).'/../reverse-whois-order.php';
		exit();
	}
	$url = false;


	if(!strcmp($pay_choice,"pp")){
		$order_id = $_SESSION['order_id'];
		if($sandbox){
			$url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		}
		else $url = 'https://www.paypal.com/cgi-bin/webscr';
		$params = array(//'cmd' => '_ext-enter', 'redirect_cmd'=>'_xclick',
			'business' => $PAYPAL_EMAIL, //8JF5ZE4L52TT6"
			'custom' =>'whoisapi',
			'page_style' =>'whoisxmlapi',
			 'no_shipping'=>1,
			 'cancel_return'=>'http://www.whoisxmlapi.com/reverse-whois/order.php',
		   'return'=>'http://www.whoisxmlapi.com/thankyou_rw.php',
		   'invoice'=>$order_id
    	);

		$params['cmd'] = '_xclick';
		//$params['cmd'] = '_cart';
    //$params['upload']='1';
    $params['item_number'] ="$order_id";
    //$cart->show_ordered_rows_by_type('R');
	  $params['item_name'] = "Reverse Whois Reports for $order_username";
    $params['amount'] = $PAYMENT_TEST ? 1: $cart->show_total_value_session();
	}

	$url .= '?'. http_build_query($params);

	if($url)header('location:' . $url);


?>

<?php
	function check_credits(){
		return false;
	}
?>
