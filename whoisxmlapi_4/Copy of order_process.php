<?php require_once "util.php";

	require_once "price.php";
	require_once "business_def.php";
	
	
	function handle_adhoc_order(&$order_error_var, $order_error_msg, $order_err_page, $return_page,  $cancel_return_page, $item_name, $item_price, $paypal_email, $payment_test=false, $sandbox=false){
		if(!$item_name){
			$order_error_var = $order_error_msg;
		}
		
		if($order_error_var) {
			return;
		}
		if($sandbox){
			$url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		}
		else $url = 'https://www.paypal.com/cgi-bin/webscr';
		$params = array(
			'business' => $paypal_email, 
			'custom' =>'whoisapi',
			'page_style' =>'whoisxmlapi',
			 'no_shipping'=>1,
			  'cancel_return'=>$cancel_return_page,
			  'return'=>$return_page
			  
		);
		$params['cmd'] = '_xclick';
		$params['item_name'] = $item_name;
		$params['amount'] = $payment_test ? 1: $item_price;
			

		$url .= '?'. http_build_query($params);
		if($url)header('location:' . $url);
		//	print_r($params);
	}
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
	$PAYMENT_TEST =0;
	$sandbox = get_from_post_get("sandbox");
	$PAYPAL_EMAIL = getRandomPaymentEmail();
	
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
			include 'order.php';			
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
			  'cancel_return'=>'http://www.whoisxmlapi.com/order.php'.'?'
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
	if($order_type == 'bwl'){
		require_once __DIR__ . "/bulk-whois-lookup-price.php";
		$bwl_num_domains = $_REQUEST['bwl_num_domains'];
		$bwl_speed =  $_REQUEST['bwl_speed'] ;
		
		
		if($bwl_num_domains <= 0){
			$bwl_order_error = "The number of domains must be a positive integer.";
		}
		
		
		if($bwl_order_error) {
			include 'order.php';			
		}
		
		$price = compute_bwl_price($bwl_num_domains, $bwl_speed);
		$days_to_complete = compute_bwl_time($bwl_num_domains, $bwl_speed);
		$item_name = "Bulk Whois Lookup for $bwl_num_domains domains on a $bwl_speed schedule with $days_to_complete days to complete." ;
		
		if($sandbox){
			$url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		}
		else $url = 'https://www.paypal.com/cgi-bin/webscr';
		$params = array(//'cmd' => '_ext-enter', 'redirect_cmd'=>'_xclick',
			'business' => $PAYPAL_EMAIL, //8JF5ZE4L52TT6"
			'custom' =>'whoisapi',
			'page_style' =>'whoisxmlapi',
			 'no_shipping'=>1,
			  'cancel_return'=>'http://www.whoisxmlapi.com/order.php'.'?'
			  	.http_build_query(array('bwl_num_domains'=>$bwl_num_domains, 'bwl_speed'=>$bwl_speed))
			  	.'#bulk_whois_lookup_service',
			  'return'=>'http://www.whoisxmlapi.com/thankyou.php'
		);
		$params['cmd'] = '_xclick';
		$params['item_name'] = $item_name;
		$params['amount'] = $PAYMENT_TEST ? 1: $price;
		
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
			include 'order.php';			
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
			  'cancel_return'=>'http://www.whoisxmlapi.com/order.php'.'?'
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
		//print_r($params);
		if($url)header('location:' . $url);		
		exit;
	}
	if($order_type=='domain_data'){
		$order_error = false;
		$data_edition = (isset($_REQUEST['data_edition']) ? $_REQUEST['data_edition'] : false);
		
		if($data_edition === false){
			$order_error = "You must select the edition of the domain name data you wish to purchase.";
		}
		
		if($order_error) {
			include 'newly-registered-domains.php';
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
		$dataPrice = sprintf("%d", $domainNameDataPrices[$data_edition] * (1-$domainNameDataDiscounts[$data_edition]));
		$params['cmd']='_xclick-subscriptions';
		$params['item_name'] = 'Daily Domain Name Data '. $domainNameDataEditions[$data_edition];
		
			$params['a3'] = $PAYMENT_TEST ? 1: $dataPrice;	
			$params['p3'] = 1;
			$params['t3'] = 'M';
			$params['src']=1;
			$params['sra']=1;
			
			

		$url .= '?'. http_build_query($params);
		if($url)header('location:' . $url);
	
		exit;		
	}
	if($order_type=='cctld_wdb'){
		require_once __DIR__ . "/cctld-whois-database-price.php";
		$cctld_wdb_order_error = false;
		$order_error_msg = "";
		$order_err_page = __DIR__. "/order.php";
		$return_page="http://www.whoisxmlapi.com/thankyou.php";
		$cctld_whois_db_name=$_REQUEST['cctld_whois_db_name'];
		$cctld_whois_db_type=$_REQUEST['cctld_whois_db_type'];
		$cancel_return_page = 'http://www.whoisxmlapi.com/order.php'.'?'
			  	.http_build_query(array('cctld_whois_db_name'=>$cctld_whois_db_name, 'cctld_whois_db_type'=>$cctld_whois_db_type))
			  	.'#cctld_whois_database';
		
		
		$item_name="$cctld_whois_db_name $cctld_whois_db_type";
		$item_price=get_cctld_whois_db_price($cctld_whois_db_name, $cctld_whois_db_type); 	
		
		if(!$cctld_whois_db_name){
				$cctld_wdb_order_error = 'You must choose a cctld whois database to purchase.';
		}
		else if(!$cctld_whois_db_type){
				$cctld_wdb_order_error = 'You must choose a cctld whois database to purchase(domain names only or with whois data).';
		}
		if($cctld_wdb_order_error){
			include $order_err_page;
			exit;
		}
		handle_adhoc_order($cctld_wdb_order_error, $order_error_msg, $order_err_page, $return_page,  $cancel_return_page, $item_name, $item_price, $PAYPAL_EMAIL);
		
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
		$order_error ="You must enter a username for the account you wish to fill.  If you don't have one, you may either create an account now, or create an account with the username specified here after the purchase.";
	}
	else if(!validate_username($order_username)){
		$order_error = "The account username must contain only letters, numbers, underscores and @.";
	}
	if(!$pay_choice){
		$order_error = "You must select a payment choice.";
	}
	else {
		if(!$membership && !$query_quantity)
			$order_error = "You must either select the number of queries you wish to purchase or pick a membership.";
	}
	
	if($order_error){
		include 'order.php';
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
