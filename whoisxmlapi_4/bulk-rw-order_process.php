<?php require_once __DIR__ . "/util.php";

	require_once __DIR__ . "/reverse-whois/prices.php";
	require_once __DIR__ ."/business_def.php";

	init(); // modified // for getting rwQueryPrices from /reverse-whois/prices.php


	function getMembership(){
		$membership = false;
		foreach($_REQUEST as $rkey=>$rval){
			if($rkey!=null){
				if(stripos($rkey, "bill_yearly_") ===0){
					$membership = array();
					$membership['query_amount'] = substr($rkey,strlen("bill_yearly_"));
					$membership['payperiod']='year';
				}
				else if(stripos($rkey, "bill_monthly_") ===0){
					$membership = array();
					$membership['query_amount'] = substr($rkey,strlen("bill_monthly_"));
					$membership['payperiod']='month';
				}
			}
		}
		return $membership;
	}
	$PAYMENT_TEST =0;
	$sandbox = get_from_post_get("sandbox");
	$PAYPAL_EMAIL = getRandomPaymentEmail();

	//software packages or whois api client software
	$order_type = isset($_REQUEST['order_type']) ? $_REQUEST['order_type'] : '';



	//service
	$PAYMENT_TEST = 0;
	$order_username = $_REQUEST['order_username'];
	$pay_choice = get_from_post_get("pay_choice");
	$query_quantity = get_from_post_get("query_quantity");
	$membership = getMembership();

	$order_error = false;
	if(!$order_username || strlen($order_username) <= 0){
		$order_error ="You must enter a username for the account you wish to fill";
	}
	if(!$pay_choice){
		$order_error = "You must select a payment choice.";
	}
	else {
		if(!$membership && !$query_quantity)
			$order_error = "You must either select the number of reverse whois lookups you wish to purchase or pick a membership.";
	}
	if($order_error){
		// include 'bulk-reverse-whois-order.php';
		// modified
		setcookie('order_error',$order_error,time()+3600,'/');
		header('Location: ../bulk-reverse-whois-order.php');
		exit();
	}


	$url = false;



	if(!strcmp($pay_choice,"pp")){

		if($sandbox){
			$url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		}
		else $url = 'https://www.paypal.com/cgi-bin/webscr';
		$params = array(//'cmd' => '_ext-enter', 'redirect_cmd'=>'_xclick',
			'business' => $PAYPAL_EMAIL, //8JF5ZE4L52TT6"
			'custom' =>'whoisapi',
			'page_style' =>'whoisxmlapi',
			 'no_shipping'=>1,
			 'return'=>'http://www.whoisxmlapi.com/thankyou_rw.php?order_type=credit'
		);
		if($membership){
			$rwMonthlyQueryAmount = $membership['query_amount'];


			$payperiod = $membership['payperiod'];
			$cost = (strcasecmp($payperiod, 'month')===0? $rwMembershipPrices[$rwMonthlyQueryAmount] : $rwMembershipPrices[$rwMonthlyQueryAmount]*10);

			$params['cmd']='_xclick-subscriptions';
			$itemDescription = ucwords($payperiod) . "ly membership subscription of $rwMonthlyQueryAmount Reverse Whois lookups/month for $order_username";

			$params['item_name']=$itemDescription;
			$params['a3'] = $PAYMENT_TEST ? 1: $cost;
			$params['p3'] = 1;
			$params['t3'] = strtoupper(substr($payperiod,0,1));
			$params['src']=1;
			$params['sra']=1;

		}
		else{
			$params['cmd'] = '_xclick';
			$params['item_name'] = "$query_quantity "  . ' Reverse Whois lookups for '.$order_username;
			$params['amount'] = $PAYMENT_TEST ? 1: $rwQueryPrices[$query_quantity];

		}
	}

	$url .= '?'. http_build_query($params);

	if($url)header('location:' . $url);
	//print_r($params);

?>
