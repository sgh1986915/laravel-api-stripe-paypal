<?php require_once "util.php";

	require_once "custom_price.php";
	require_once "business_def.php";



	function affiliateExists(){
		if(!session_id()){
			session_start();


		}
		if(isset($_SESSION['_affiliate'])) return $_SESSION['_affiliate'];
		return false;
	}
	function generateClickBankLink($queryPrices, $query_quantity,$membership, $order_username){
	 		$queryCount = count($queryPrices);
			$queryAmount = array_keys($queryPrices);

			$pdn="";
			$pd_offset=2;
			$addon = 0;
			if($membership){
				$mqa=$membership['query_amount'];
				$mpayperiod=$membership['payperiod'];
				$addon = (strcasecmp($mpayperiod, 'month') ? 1 : 2);
			}
			for($i=0;$i<$queryCount;$i++){
						if($query_quantity == $queryAmount[$i]){
							$pdn=$pd_offset  + 3*$i + $addon;
						}
			}


			return "http://$pdn.whoisxml.pay.clickbank.net?order_username=$order_username";

	}
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

	function handle_special_item($order, $PAYPAL_EMAIL,  $PAYMENT_TEST = 0)
	{
		$url = 'https://www.paypal.com/cgi-bin/webscr';
		$params = array(//'cmd' => '_ext-enter', 'redirect_cmd'=>'_xclick',
			'business' => $PAYPAL_EMAIL, //8JF5ZE4L52TT6"
			'custom' =>'whoisapi',
			'page_style' =>'whoisxmlapi',
			 'no_shipping'=>1,
			 'return'=>'http://www.whoisxmlapi.com/thankyou.php'
		);

		if($order['membership']) {
			$payperiod = $order['payperiod'];
			$price = $order['price'];
			$payperiod_multiple = $order['payperiod_multiple'];

			$params['cmd'] ='_xclick-subscriptions';
			$itemDescription = ucwords($payperiod) . "ly membership for " . $order['item_name'] ;

			$params['item_name'] = $itemDescription;
			$params['a3'] = $PAYMENT_TEST ? 1: $price;

			$params['p3'] = ($payperiod_multiple?$payperiod_multiple:1);
			$params['t3'] = strtoupper(substr($payperiod,0,1));
			$params['src'] = 1;
			$params['sra'] = 1;

			$params['expdate_month'] = $order['expdate_month'];
			$params['expdate_year'] = $order['expdate_year'];

			if($order['trial_price'])$params['a1']=$order['trial_price'];
			if($order['trial_duration'])$params['p1']=$order['trial_duration'];
			if($order['trial_duration_unit'])$params['t1']=$order['trial_duration_unit'];

		} else {
			$params['cmd'] = '_xclick';
			$params['item_name'] = $order['item_name'];
			$params['amount'] = $PAYMENT_TEST ? 1: $order['price'];
		}


		$url .= '?'. http_build_query($params);
		//print_r($params);
		//echo $url;
		//exit;
		if($url)header('location:' . $url);
	}


	$PAYMENT_TEST =0;
	$sandbox = get_from_post_get("sandbox");
	$PAYPAL_EMAIL = getRandomPaymentEmail();
	$payto=$_REQUEST['payto'];
	
	if ($payto) {
		$PAYPAL_EMAIL = $payto;
	}

	if ($_REQUEST['special_order']) {

		$order = array(
			'item_name' => $_REQUEST['item_name'], 
			'price' => $_REQUEST['price'], 
			'membership' => $_REQUEST['payperiod'] ? true : false,
			'payperiod' => $_REQUEST['payperiod'], 
			'payperiod_multiple' => $_REQUEST['payperiod_multiple'],
			'trial_price' => $_REQUEST['trial_price'],
			'trial_duration' => $_REQUEST['trial_duration'],
			'trial_duration_unit' => $_REQUEST['trial_duration_unit'],
			'expdate_month' => $_REQUEST['expdate_month'],
			'expdate_year' => $_REQUEST['expdate_year']
		);

		handle_special_item($order, $PAYPAL_EMAIL,  $PAYMENT_TEST);
		exit();
	}
	//software packages or whois api client software
	$order_type = isset($_REQUEST['order_type']) ? $_REQUEST['order_type'] : '';

	if($order_type == 'wc'){
		require_once __DIR__ . "/wc_price.php";
		$wc_license_type = (isset($_REQUEST['wc_license_type']) ? $_REQUEST['wc_license_type'] : false);
		$wc_order_username = $_REQUEST['wc_order_username'];
		if($wc_license_type === false){
			$wc_order_error = "You must select the type of whois api client license you wish to purchase.";
		}
		else if($wc_license_type == 'per_user_license'){
			$num_user_license = $_REQUEST['num_user_license'];
			$num_user_license_int = str2int($num_user_license);
			if($num_user_license_int <= 0){
				$wc_order_error = "You must enter a valid integer for the number of user licenses";
			}
		}
		else if(!$wc_order_username || strlen($wc_order_username) <= 0){
			$wc_order_error ="You must enter a username for the account you wish to purchase the whois api client license(s) for.";
		}
		if($wc_order_error) {
			setcookie('order_error',$wc_order_error,time()+3600,'/'); // modified
			header('Location: ../custom_order.php?'.$_SERVER['QUERY_STRING']); // modified
			exit(); // modified
			include 'custom_order.php';
		}

		$price = compute_wc_license_price($wc_license_type, $num_user_license);

		$item_name = '';
		if($wc_license_type == 'sourcecode_license')$item_name = "Whois API Client Application Source Code License";
		else if($wc_license_type =='group_license')$item_name="Whois API Client Application Group License";
		else $item_name = "$num_user_license_int Whois API Client User License";
		if($sandbox){
			$url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		}
		else $url = 'https://www.paypal.com/cgi-bin/webscr';
		$params = array(//'cmd' => '_ext-enter', 'redirect_cmd'=>'_xclick',
			'business' => $PAYPAL_EMAIL, //8JF5ZE4L52TT6"
			'custom' =>'whoisapi',
			'page_style' =>'whoisxmlapi',
			 'no_shipping'=>1,
			  'cancel_return'=>'http://www.whoisxmlapi.com/custom_order.php'.'?'
			  	.http_build_query(array('wc_license_type'=>$wc_license_type, 'num_user_license'=>$num_user_license, 'order_username'=>$wc_order_username))
			  	.'#whois_api_client',
			  'return'=>'http://www.whoisxmlapi.com/thankyou.php'
		);
		$params['cmd'] = '_xclick';
		$params['item_name'] = $item_name;
		$params['amount'] = $PAYMENT_TEST ? 1: $price;
		$url .= '?'. http_build_query($params);

		if($url)header('location:' . $url);
		exit;
	}

	else if($order_type == 'spk'){
		$order_error = false;
		$spk_sel = (isset($_REQUEST['spk_sel']) ? $_REQUEST['spk_sel'] : false);
		if($spk_sel === false){
			$order_error = "You must select the edition of the software package you wish to purchase.";
		}

		if($order_error) {
			include 'whois-api-software.php';
			exit;
		}
		if($sandbox){
			$url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		}
		else $url = 'https://www.paypal.com/cgi-bin/webscr';
		$params = array(//'cmd' => '_ext-enter', 'redirect_cmd'=>'_xclick',
			'business' => $PAYPAL_EMAIL, //8JF5ZE4L52TT6"
			'custom' =>'whoisapi',
			'page_style' =>'whoisxmlapi',
			 'no_shipping'=>1,
			 'return'=>'http://www.whoisxmlapi.com/thankyou.php'
		);
		$params['cmd'] = '_xclick';
		$params['item_name'] = 'Whois API Software Package '. $spkEditions[$spk_sel];
		$spkPrice = sprintf("%d", $spkPrices[$spk_sel] * (1-$spkDiscounts[$spk_sel]));
		$params['amount'] = $PAYMENT_TEST ? 1: $spkPrice;
		$url .= '?'. http_build_query($params);
		if($url)header('location:' . $url);
		exit;
	}

	if($order_type == 'wdb'){
		require_once __DIR__ . "/whois-database-price.php";
		$wdb_quantity = (isset($_REQUEST['wdb_quantity']) ? $_REQUEST['wdb_quantity'] : false);
		$wdb_type = (isset($_REQUEST['wdb_type']) ? $_REQUEST['wdb_type'] : 'both');
		$wdb_quantity = str2int($wdb_quantity);
		$wdb_prefix = (isset($_REQUEST['wdb_prefix']) ? $_REQUEST['wdb_prefix'] : false);

		if($wdb_quantity <= 0){
				$wdb_order_error = "You select the number of whois records(in millions) you wish to download.";
		}


		if($wdb_order_error) {
			setcookie('order_error',$wdb_order_error,time()+3600,'/'); // modified
			header('Location: ../custom_order.php?'.$_SERVER['QUERY_STRING']); // modified
			exit(); // modified
			include 'custom_order.php';
		}

		$price = compute_real_db_price($wdb_quantity, $wdb_type);
		$item_name = "Whois Database download: $wdb_quantity million ".($wdb_type=='raw' ? "Raw" : "Parsed and Raw ") . " whois records" ;

		if($sandbox){
			$url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		}
		else $url = 'https://www.paypal.com/cgi-bin/webscr';
		$params = array(//'cmd' => '_ext-enter', 'redirect_cmd'=>'_xclick',
			'business' => $PAYPAL_EMAIL, //8JF5ZE4L52TT6"
			'custom' =>'whoisapi',
			'page_style' =>'whoisxmlapi',
			 'no_shipping'=>1,
			  'cancel_return'=>'http://www.whoisxmlapi.com/custom_order.php'.'?'
			  	.http_build_query(array('wdb_quantity'=>$wdb_quantity, 'wdb_type'=>$wdb_type))
			  	.'#whois_database',
			  'return'=>'http://www.whoisxmlapi.com/thankyou.php'
		);
		$params['cmd'] = '_xclick';
		$params['item_name'] = $item_name;
		$params['amount'] = $PAYMENT_TEST ? 1: $price;
		if($wdb_prefix!==false && $wdb_prefix!=''){

			$params['on0']='prefix';
			$params['os0'] = $wdb_prefix;
		}
		$url .= '?'. http_build_query($params);

		if($url)header('location:' . $url);
		exit;
	}



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
			$order_error = "You must either select the number of queries you wish to purchase or pick a membership.";
	}
	if($order_error){
		setcookie('order_error',$order_error,time()+3600,'/'); // modified
		header('Location: ../custom_order.php?'.$_SERVER['QUERY_STRING']); // modified
		exit(); // modified
		include 'custom_order.php';
		exit();
	}


	$url = false;





	//$clickbank = "http://ITEM.VENDOR.pay.clickbank.net";
	//hop http://whoisxmlaf.whoisxml.hop.clickbank.net

	if(affiliateExists()){
		$clickbank = generateClickbankLink($queryPrices, $query_quantity,$membership, $order_username);
		//echo $clickbank;
		header('location:' . $clickbank);
		return;
	}


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
			 'return'=>'http://www.whoisxmlapi.com/thankyou.php'
		);
		if($membership){
			$monthlyQueryAmount = $membership['query_amount'];


			$payperiod = $membership['payperiod'];
			$cost = (strcasecmp($payperiod, 'month')===0? $membershipPrices[$monthlyQueryAmount] : $membershipPrices[$monthlyQueryAmount]*10);

			$params['cmd']='_xclick-subscriptions';
			$itemDescription =ucwords($payperiod) . "ly membership subscription of $monthlyQueryAmount queries/month for $order_username";

			$params['item_name']=$itemDescription;
			$params['a3'] = $PAYMENT_TEST ? 1: $cost;
			$params['p3'] = 1;
			$params['t3'] = strtoupper(substr($payperiod,0,1));
			$params['src']=1;
			$params['sra']=1;

		}
		else{
			$params['cmd'] = '_xclick';
			$params['item_name'] = $query_quantity . ' whois queries for '.$order_username;
			$params['amount'] = $PAYMENT_TEST ? 1: $queryPrices[$query_quantity];

		}
	}

	$url .= '?'. http_build_query($params);

	if($url)header('location:' . $url);


?>
