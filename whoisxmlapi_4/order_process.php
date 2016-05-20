<?php
@ob_start();
error_reporting(0);
@ini_set('display_errors', 0);
	require_once "util.php";
	require_once __DIR__."/order_util.php";
	require_once __DIR__."/payment/payment_util.php";
	require_once __DIR__."/util/array_util.php";
	require_once "price.php";
	require_once "business_def.php";
	 require_once __DIR__ ."/domain_name_data/DomainNameDataAccountManager.php"; // modified
	require_once __DIR__ . "/wc_price.php";
	require_once __DIR__ . "/whois-database-price.php";
	require_once __DIR__ . "/invoice/invoice.php";

	date_default_timezone_set('America/Los_Angeles');

	function handle_adhoc_paypal_order($input_params){

		$return_page =  $input_params['return_page'];
		$cancel_return_page = $input_params['cancel_return_page'];
		$item_name = $input_params['item_name'];
		$item_price = $input_params['item_price'];
		$paypal_email = $input_params['paypal_email'];
		$payment_test=  $input_params['payment_test'];
		$sandbox=  $input_params['sandbox'];

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

		if(isset($notify_url))$params['notify_url'] = $notify_url;
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
    function affiliate_accept_query_quantity($query_quantity, $membership){

        if(!$membership && $query_quantity<=5000)return true;
        if($membership && $membership['query_amount']<10000)return true;
        return false;
    }


	function generateClickBankLink($queryPrices, $query_quantity,$membership,$membershipPrices, $order_username){
	 		$queryCount = count($queryPrices);
			$queryAmount = array_keys($queryPrices);

			$pdn="";
			$pd_offset=2;
			$addon = 0;
			if($membership){
				$query_quantity=$membership['query_amount'];
                $queryCount = count($membershipPrices);
                $queryAmount=array_keys($membershipPrices);
				$mpayperiod=$membership['payperiod'];
				$addon = (strcasecmp($mpayperiod, 'month') ? 2 : 1);

			}

			for($i=0;$i<$queryCount;$i++){
						if($query_quantity == $queryAmount[$i]){
							$pdn=$pd_offset  + 3*$i + $addon;
						}
			}

			return "http://$pdn.whoisxml.pay.clickbank.net?order_username=$order_username";

	}

	$order_error_msg = ''; // modified


	$sandbox = get_from_post_get("sandbox");
	$PAYPAL_EMAIL = getRandomPaymentEmail();
	$pay_choice= $_REQUEST['pay_choice'];

	//software packages or whois api client software
	$order_type = isset($_REQUEST['order_type']) ? $_REQUEST['order_type'] : '';

	if($order_type == 'wc'){
		require_once __DIR__ . "/wc_price.php";
		$wc_license_type = (isset($_REQUEST['wc_license_type']) ? $_REQUEST['wc_license_type'] : false);
		//$wc_order_username = $_REQUEST['wc_order_username'];
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
		/*else if(!$wc_order_username || strlen($wc_order_username) <= 0){
			$wc_order_error ="You must enter a username for the account you wish to purchase the whois api client license(s) for.";
		}*/
		if($wc_order_error) {
			$order_error_msg = $wc_order_error; // modified
			include_orderpage($pay_choice);
		}

		$price = compute_wc_license_price($wc_license_type, $num_user_license);

		$item_name = '';
		if($wc_license_type == 'sourcecode_license')$item_name = "Whois API Client Application Source Code License";
		else if($wc_license_type =='group_license')$item_name="Whois API Client Application Group License";
		else $item_name = "$num_user_license_int Whois API Client User License";

		$input_params=array('order_type'=>'whois_api_client_license',
	'sandbox'=>$sandbox,'payment_test'=>$PAYMENT_TEST, 'paypal_email'=>$PAYPAL_EMAIL, 'item_price'=>$price,'item_name'=>$item_name, 'wc_license_type'=>$wc_license_type, 'num_user_license'=>$num_user_license);

		if($pay_choice=='pp'){
			if(isset($notify_url))$input_params['notify_url'] = $notify_url;
			purchase_wc_paypal($input_params);
		}
		else{
			purchase_wc_cc($input_params);
		}

		exit;
	}

	else if($order_type == 'spk'){
		$order_error = false;
		$spk_sel = (isset($_REQUEST['spk_sel']) ? $_REQUEST['spk_sel'] : false);
		if($spk_sel === false){
			$order_error = "You must select the edition of the software package you wish to purchase.";
		}

		if($order_error) {
			setcookie('order_error',$order_error,time()+3600,'/'); // modified
			header('Location: ../whois-api-software.php?'.$_SERVER['QUERY_STRING']); // modified
			exit(); // modified
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

		if(isset($notify_url))$params['notify_url'] = $notify_url;
		$url .= '?'. http_build_query($params);
		if($url)header('location:' . $url);
		exit;
	}
	if($order_type == 'bwl'){
		require_once __DIR__ . "/bulk-whois-lookup-price.php";
		$bwl_num_domains = $_REQUEST['num_domains'];
		$bwl_speed =  $_REQUEST['bwl_speed'] ;
		if(!$bwl_num_domains){
			$bwl_order_error="You must select number of domains. ";
		}
		if($bwl_order_error) {
			$order_error_msg = $bwl_order_error; // modified
			include_orderpage($pay_choice);
			exit();
		}

		$price = compute_bwl_price($bwl_num_domains, $bwl_speed);
		$days_to_complete = compute_bwl_time($bwl_num_domains, $bwl_speed);
		$item_name = "Bulk Whois Lookup for $bwl_num_domains domains on a $bwl_speed schedule with $days_to_complete days to complete." ;
		$input_params=array('sandbox'=>$sandbox, 'payment_test'=>$PAYMENT_TEST, 'paypal_email'=>$PAYPAL_EMAIL, 'item_price'=>$price,'days_to_complete'=>$days_to_complete,'item_name'=>$item_name, 'order_type'=>'arbitrary');

		if($pay_choice=='pp'){
			if(isset($notify_url))$input_params['notify_url'] = $notify_url;
			purchase_bwl_paypal($input_params);
		}
		else{
			purchase_bwl_cc($input_params);
		}

		exit;
	}
	if($order_type == 'wdb'){
		require_once __DIR__ . "/whois-database-price.php";
		$wdb_quantity = (isset($_REQUEST['wdb_quantity']) ? $_REQUEST['wdb_quantity'] : false);
		$wdb_type = (isset($_REQUEST['wdb_type']) ? $_REQUEST['wdb_type'] : 'both');
		$wdb_quantity = str2int($wdb_quantity);


		if($wdb_quantity <= 0){
			$wdb_order_error = "You select the number of whois records(in millions) you wish to download.";
		}
		if($wdb_order_error) {
			$order_error_msg = $wdb_order_error; // modified
			include_orderpage($pay_choice);
			return;
		}

		$price = compute_real_db_price($wdb_quantity, $wdb_type);
		$item_name = "Whois Database download: $wdb_quantity million ".($wdb_type=='raw' ? "Raw" : "Parsed and Raw ") . " whois records" ;

		$input_params=array('sandbox'=>$sandbox,'payment_test'=>$PAYMENT_TEST, 'paypal_email'=>$PAYPAL_EMAIL, 'item_price'=>$price,'item_name'=>$item_name, 'wdb_quantity'=>$wdb_quantity, 'wdb_type'=>$wdb_type, 'order_type'=>'arbitrary');

		if($pay_choice=='pp'){
			if(isset($notify_url))$input_params['notify_url'] = $notify_url;
			purchase_wdb_paypal($input_params);
		}
		else{
			purchase_wdb_cc($input_params);
		}


		exit;
	}
	if($order_type == 'dipdb'){
		require_once __DIR__ . "/domain-ip-database-price.php";
		$dipdb_quantity = (isset($_REQUEST['dip_db_quantity']) ? $_REQUEST['dip_db_quantity'] : false);
		$dipdb_type = (isset($_REQUEST['dipdb_type']) ? $_REQUEST['dipdb_type'] : 'both');
		$dipdb_quantity = str2int($dipdb_quantity);


		if($dipdb_quantity <= 0){
			$dipdb_order_error = "You select the number of domain to ip records(in millions) you wish to download.";
		}
		if($dipdb_order_error) {
			$order_error_msg = $dipdb_order_error; // modified
			include_orderpage($pay_choice);
			return;
		}

		$price = compute_real_dip_db_price($dipdb_quantity, $dipdb_type);
		$item_name = "Domain IP Database download: $dipdb_quantity million ".($dipdb_type=='raw' ? "Raw" : "Parsed and Raw ") . " whois records" ;

		$input_params=array('sandbox'=>$sandbox,'payment_test'=>$PAYMENT_TEST, 'paypal_email'=>$PAYPAL_EMAIL, 'item_price'=>$price,'item_name'=>$item_name, 'dipdb_quantity'=>$dipdb_quantity, 'dipdb_type'=>$dipdb_type, 'order_type'=>'arbitrary');

		if($pay_choice=='pp'){
			if(isset($notify_url))$input_params['notify_url'] = $notify_url;
			purchase_dipdb_paypal($input_params);
		}
		else{
			purchase_dipdb_cc($input_params);
		}


		exit;
	}
	if($order_type=='domain_data'){

		$order_error = false;
		$data_edition = (isset($_REQUEST['data_edition']) ? $_REQUEST['data_edition'] : false);

		if($data_edition === false){
			$order_error = "You must select the edition of the domain name data you wish to purchase.";
		}

		if($order_error) {
			setcookie('order_error',$order_error,time()+3600,'/'); // modified
			header('Location: ../newly-registered-domains.php?'.$_SERVER['QUERY_STRING']); // modified
			exit(); // modified
			//include 'newly-registered-domains.php';
			include basename($_SERVER['HTTP_REFERER']);
			exit;
		}
		$payparams=array(
				'sandbox' => $sandbox,
				'PAYPAL_EMAIL'  => $PAYPAL_EMAIL,
				'payment_test' => $PAYMENT_TEST,
				'domainNameDataPrices' => $domainNameDataPrices,
				'domainNameDataEditions'=> $domainNameDataEditions,
				'data_edition' => $data_edition,
				'domainNameDataDiscounts' => $domainNameDataDiscounts,
				'customerEmail'=>$_REQUEST['customer_email'],
				'pay_yearly'=>$_REQUEST['pay_yearly'],
				'yearlyDiscount'=>$domainNameDataYearlyDiscount
		);

		if($pay_choice=='cc'){
			purchase_domain_data_cc($payparams);
		}
		else{
			//paypal
			if(isset($notify_url))$payparams['notify_url'] = $notify_url;
			purchase_domain_data_paypal($payparams);
		}

		exit;
	}
	if($order_type=='cctld_wdb'){
		require_once __DIR__ . "/models/cctld_whois_database_product.php";
		$cctld_wdb_ids = $_REQUEST['cctld_wdb_ids'];
		$cctld_whois_db_type = $_REQUEST['cctld_whois_db_type'];

		//vaildation
		$cctld_wdb_order_error = false;
		if(!$cctld_wdb_ids || count($cctld_wdb_ids) == 0){
				$cctld_wdb_order_error = 'You must choose a cctld whois database to purchase.';
		}
		else if(!$cctld_whois_db_type){
				$cctld_wdb_order_error = 'You must choose a cctld whois database to type purchase(domains only or with whois data).';
		}
		if($cctld_wdb_order_error){
			$order_error_msg = $cctld_wdb_order_error; // modified
			include_orderpage($pay_choice);
			exit;
		}
		$cctldWhoisDatabaseProduct = new CCTLDWhoisDatabaseProduct();

		$item_name = ($cctld_whois_db_type=='domain_names' ? 'Domain Names Only: ' : 'Whois Records: ' ) . $cctldWhoisDatabaseProduct->concat_product_item_names_by_ids($cctld_wdb_ids, ', ');

		$items = $cctldWhoisDatabaseProduct->get_product_items_by_ids($cctld_wdb_ids);

		$item_price=$cctldWhoisDatabaseProduct->get_product_items_price($items, ($cctld_whois_db_type=='domain_names'?'domain_names_price' : 'parsed_whois_price'));

		$input_params=array(
				'cctld_wdb_ids'=>$cctld_wdb_ids,
				'cctld_whois_db_type'=>$cctld_whois_db_type,
				'item_name'=>$item_name,
				'item_price'=>$item_price,
				'paypal_email'=>$PAYPAL_EMAIL,
				'order_type'=>'arbitrary',
				'payment_test'=>$PAYMENT_TEST
		);
		if($pay_choice=='pp'){
			if(isset($notify_url))$input_params['notify_url'] = $notify_url;
			purchase_cctld_wdb_paypal($input_params);
		}
		else{
			purchase_cctld_wdb_cc($input_params);
		}

		exit;
	}

	if($order_type=='custom_wdb'){
		require_once __DIR__ . "/models/custom_whois_database_product.php";
		$custom_wdb_ids=$_REQUEST['custom_wdb_ids'];

		//vaildation
		$custom_wdb_order_error = false;
		if(!$custom_wdb_ids || count($custom_wdb_ids)<=0){
			$custom_wdb_order_error = 'You must choose an Alexa or Quantcast whois database to purchase.';
		}

		if($custom_wdb_order_error){
			$order_error_msg = $custom_wdb_order_error; // modified
			include_orderpage($pay_choice);
			exit;
		}
		$customWhoisDatabaseProduct = new CustomWhoisDatabaseProduct();
		$items=$customWhoisDatabaseProduct->get_product_items_by_ids($custom_wdb_ids);
		$item_name=$customWhoisDatabaseProduct->concat_product_item_names($items);
		$item_price=$customWhoisDatabaseProduct->get_product_items_price($items);
		$input_params=array(
				'custom_wdb_ids'=>$custom_wdb_ids,
				'item_name'=>$item_name,
				'item_price'=>	 $item_price,
				'paypal_email'=>$PAYPAL_EMAIL,
				'order_type'=>'arbitrary',
				'payment_test'=>$PAYMENT_TEST
		);
		if($pay_choice=='pp'){
			if(isset($notify_url))$input_params['notify_url'] = $notify_url;
			purchase_custom_wdb_paypal($input_params);
		}
		else{
			purchase_custom_wdb_cc($input_params);

		}

		exit;
	}
	//regular whois api service
	$order_username = $_REQUEST['order_username'];
	$pay_choice = get_from_post_get("pay_choice");

	$query_quantity = get_from_post_get("query_quantity");
	$membership = getMembership();

	$order_error = false;
	if(!$order_username || strlen($order_username) <= 0){
		$order_error ="You must enter a username for the account you wish to fill.  If you don't have one, you may either create an account now, or create an account with the username specified here after the purchase.";
	}
	else if(!validate_username($order_username)){
		$order_error = "The account username must contain only letters, numbers, underscores, dot and @.";
	}
	if(!$pay_choice){
		$order_error = "You must select a payment choice.";
	}
	else {
		if(!$membership && !$query_quantity)
			$order_error = "You must either select the number of queries you wish to purchase or pick a membership.";
	}


	if($order_error){
		$order_error_msg = $order_error; // modified
		include_orderpage($pay_choice);
		exit();
	}

	if(affiliateExists() && affiliate_accept_query_quantity($query_quantity, $membership)){
		$clickbank = generateClickbankLink($queryPrices, $query_quantity,$membership, $membershipPrices, $order_username);

		header('location:' . $clickbank);
		return;
	}

	$url = false;

		$params=array(
			'membership'=>$membership,
			'membershipPrices'=>$membershipPrices,
			'PAYPAL_EMAIL'=>$PAYPAL_EMAIL,
			'sandbox'=>$sandbox,
			'order_username'=>$order_username,
			'query_quantity'=>$query_quantity,
			'queryPrices'=>$queryPrices,
			'payment_test'=>$PAYMENT_TEST,
			'customer_email'=>$_REQUEST['customer_email']//strip only


		);

	if(!strcmp($pay_choice,"pp")){ //eq to pp
		if(isset($notify_url))$params['notify_url'] = $notify_url;
		purchase_whoisapi_paypal($params);
	}
	else{

		purchase_whoisapi_cc($params);
	}




?>

<?php
	function purchase_whoisapi_paypal($params){
		$membership = $params['membership'];
		$PAYPAL_EMAIL = $params['PAYPAL_EMAIL'];
		$sandbox = $params['sandbox'];
		$PAYMENT_TEST =$params['payment_test'];
		$membershipPrices=$params['membershipPrices'];
		$query_quantity=$params['query_quantity'];
		$queryPrices=$params['queryPrices'];
		$order_username=$params['order_username'];
		$notify_url=$params['notify_url'];

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
		if($notify_url)$params['notify_url'] = $notify_url;

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
		$url .= '?'. http_build_query($params);
		if($url)header('location:' . $url);

	}
	function purchase_whoisapi_cc($params){
		global $STRIP_API_CURRENT_SECRET_KEY;
		require_once(__DIR__."/../stripe-php/stripe-php-1.6.5/lib/Stripe.php");

		$membership = $params['membership'];
		$PAYPAL_EMAIL = $params['PAYPAL_EMAIL'];
		$sandbox = $params['sandbox'];
		$payment_test =$params['payment_test'];
		$membershipPrices=$params['membershipPrices'];
		$query_quantity=$params['query_quantity'];
		$queryPrices=$params['queryPrices'];
		$order_username=$params['order_username'];
		$customer_email=$params['customer_email'];

		//print_r($params);
		$order_cc_error = false;

		if (cc_input_validate($order_cc_error) && $_POST) {
  			Stripe::setApiKey($STRIP_API_CURRENT_SECRET_KEY);

  			try {

      			$token = $_POST['stripeToken'];
      			if($membership){
      				$query_amount=$membership['query_amount'];
      				$payperiod=$membership['payperiod'];
      				$plan_id="whoisapi_$payperiod" . "_$query_amount";
      				$description=ucwords($payperiod) . "ly membership subscription of $query_amount queries/month for $order_username";

					$customer = Stripe_Customer::create(array(
  						"card" => $token,
  						"plan" => $plan_id,
  						"email" => $customer_email,
  						"description"=>$description
  						)
					);

					if(!$customer){
						$order_cc_error="Failed to charge credit card.";
					}

      			}
      			else{
      				$price=$queryPrices[$query_quantity] * 100;
      				$description =  "$query_quantity  whois queries for $order_username, email: $customer_email";
      				if($payment_test)$price=100;
      				 // create the charge on Stripe's servers - this will charge the user's card

  					$charge = Stripe_Charge::create(array(
   						"amount" => $price, // amount in cents, again
   						"currency" => "usd",
  	 					"card" => $token,
   						"description" => $description)
 					);
 					if(!$charge){
						$order_cc_error="Failed to charge credit card. ";
					}

      			}
  			}
  			catch (Exception $e) {
    			$order_cc_error = $e->getMessage();
  			}
		}
		try{
		if(!$order_cc_error){
			if($membership){
				//$fulfillRes = whoisFillQuantity($query_amount, $order_username, array('membership_payperiod'=>$payperiod));
				$fulfillRes=true;//it will be filled by webhooks
				if($fulfillRes) emailOrderProcessed($customer_email, array('accountUserName'=>$order_username,
							'orderQuantity'=>$query_amount, 'item_name'=>$description,
							'extraBody' =>"Your account's query balance will be reset to $query_amount every month on day ".date('d') ."."
				));
			}
			else{
				$fulfillRes = whoisAddQuantity($query_quantity, $order_username);
				if($fulfillRes) emailOrderProcessed($customer_email, array('accountUserName'=>$order_username,
							'orderQuantity'=>$query_quantity, 'item_name'=>$description));
			}
		}
		if($order_cc_error){
			setcookie('order_error',$order_cc_error,time()+3600,'/'); // modified
			header('Location: ../cc.php?'.$_SERVER['QUERY_STRING']); // modified
			exit(); // modified
			//include basename($_SERVER['HTTP_REFERER']);
			include "cc.php";
			return;

		}
		}catch(Exception $e){

		}
		//success
		//mark invoice to create
		if(!$order_cc_error && !$membership){
			$invoice_data=array('username'=>$order_username,
							 'item_name'=>$description,
							 	'email_to'=>$customer_email,
							 	'invoice_num'=>"cc_".$charge->id,
							 	'payment_gross'=>$price,
							 	'payment_date'=>time()
							 );

			markInvoiceToGenerate($invoice_data);
		}
		header('Location: ../thankyou.php'); // modified

	}

	//for cc only(with the exception of whois api membership);
function markInvoiceToGenerate( $invoice_data){

	$item_name=$invoice_data['item_name'];
	$username=$invoice_data['username'];
	$email_to=$invoice_data['email_to'];
	$invoice_num=$invoice_data['invoice_num'];

	$invoice_desc="";
	$invoice_file_path=Invoice::generateInvoiceFilePath(array('username'=>$username, 'email_to'=>$email_to, 'invoice_num'=>$invoice_num));

	//content data
	$sendTo=$email_to;
	$paymentGross="$" . $invoice_data['payment_gross']/100;
	$paymentDate=Invoice::normalizeInvoiceDate($invoice_data['payment_date']);

	$invoiceContent = array(
			"invoiceNumber"=>$invoice_num,
			"invoiceDate"=>$paymentDate,
			"sendTo"=>$sendTo,
			"item1Quantity"=> "1",
			"item1Number"=>"1",
			"item1Description"=>$item_name,
			"item1UnitPrice"=>$paymentGross,
			"item1Price"=>$paymentGross,
			"subtotal"=>$paymentGross,
			"totalPrice"=>$paymentGross
		);
	Invoice::markInvoiceToCreate(array('invoice_num'=>$invoice_num,
										'invoice_desc'=>$invoice_desc,
										'username'=>$username,
										'invoice_content'=>json_encode($invoiceContent),
										 'invoice_file_path'=>$invoice_file_path,
										 'email_to'=>$email_to
							));

}
	function purchase_bwl_paypal($input_params){
		$bwl_num_domains=$input_params['bwl_num_domains'];
		$bwl_speed=$input_params['bwl_speed'];

		$cancel_return_page='http://www.whoisxmlapi.com/order_paypal.php'.'?'
			  	.http_build_query(array('bwl_num_domains'=>$bwl_num_domains, 'bwl_speed'=>$bwl_speed))
			  	.'#bulk_whois_lookup_service';
		$return_page = 'http://www.whoisxmlapi.com/thankyou.php';


		$input_params=array_copy($input_params);
		$input_params['return_page']=$return_page;
		$input_params['cancel_return_page']=$cancel_return_page;

		handle_adhoc_paypal_order($input_params);

	}
	function purchase_bwl_cc($params){
        $success_callback="processBulkWhoisLookupOrder";
        $success_callback_params=array('item_name'=>$params['item_name'], 'email_to'=>$_REQUEST['customer_email']);
        handle_adhoc_cc_order($params, $success_callback,  $success_callback_params);


    }
	function purchase_wc_paypal($params){
		$wc_license_type=$params['wc_license_type'];
		$num_user_license=$params['num_user_license'];

		$cancel_return_page='http://www.whoisxmlapi.com/order_paypal.php'.'?'
			  	.http_build_query(array('wc_license_type'=>$wc_license_type, 'num_user_license'=>$num_user_license))
			  	.'#whois_api_client';
		$return_page = 'http://www.whoisxmlapi.com/thankyou.php';


		$input_params=array_copy($params);
		$input_params['return_page']=$return_page;
		$input_params['cancel_return_page']=$cancel_return_page;

		handle_adhoc_paypal_order($input_params);

	}
	function purchase_wc_cc($params){
		handle_adhoc_cc_order($params);
	}
	function purchase_wdb_paypal($params){
		$wdb_quantity=$params['wdb_quantity'];
		$wdb_type=$params['wdb_type'];

		$cancel_return_page='http://www.whoisxmlapi.com/order_paypal.php'.'?'
			  	.http_build_query(array('wdb_quantity'=>$wdb_quantity, 'wdb_type'=>$wdb_type))
			  	.'#whois_database';
		$return_page = 'http://www.whoisxmlapi.com/thankyou.php';


		$input_params=array_copy($params);
		$input_params['return_page']=$return_page;
		$input_params['cancel_return_page']=$cancel_return_page;

		handle_adhoc_paypal_order($input_params);

	}
	function purchase_wdb_cc($params){
		handle_adhoc_cc_order($params);
	}
	function purchase_dipdb_paypal($params){
		$dipdb_quantity=$params['dipdb_quantity'];


		$cancel_return_page='http://www.whoisxmlapi.com/order_paypal.php'.'?'
			  	.http_build_query(array('dipdb_quantity'=>$dipdb_quantity))
			  	.'#domain_ip_database';
		$return_page = 'http://www.whoisxmlapi.com/thankyou.php';


		$input_params=array_copy($params);
		$input_params['return_page']=$return_page;
		$input_params['cancel_return_page']=$cancel_return_page;

		handle_adhoc_paypal_order($input_params);

	}
	function purchase_dipdb_cc($params){
		handle_adhoc_cc_order($params);
	}
	function purchase_cctld_wdb_paypal($params){
		$cctld_whois_db_name=$params['cctld_whois_db_name'];
		$cctld_whois_db_type=$params['cctld_whois_db_type'];

		$return_page="http://www.whoisxmlapi.com/thankyou.php";
		$cancel_return_page = 'http://www.whoisxmlapi.com/order_paypal.php'.'?'
			  .http_build_query(array('cctld_whois_db_name'=>$cctld_whois_db_name, 'cctld_whois_db_type'=>$cctld_whois_db_type))
			  .'#cctld_whois_database';
		$input_params=array_copy($params);
		$input_params['return_page']=$return_page;
		$input_params['cancel_return_page']=$cancel_return_page;

		handle_adhoc_paypal_order($input_params);
	}
	function purchase_cctld_wdb_cc($params){
		handle_adhoc_cc_order($params);

	}

	function purchase_custom_wdb_paypal($params){
		$custom_wdb_ids=$params['custom_wdb_ids'];


		$return_page="http://www.whoisxmlapi.com/thankyou.php";
		$cancel_return_page = 'http://www.whoisxmlapi.com/order_paypal.php'.'?'
			  .http_build_query(array('custom_wdb_ids'=>$custom_wdb_ids))
			  .'#custom_whois_database';
		$input_params=array_copy($params);
		$input_params['return_page']=$return_page;
		$input_params['cancel_return_page']=$cancel_return_page;

		handle_adhoc_paypal_order($input_params);
	}
	function purchase_custom_wdb_cc($params){
		handle_adhoc_cc_order($params);
		//print_r($params);
	}
	function handle_adhoc_cc_order($params, $success_callback=false, $success_calllback_param=false){
		global $STRIP_API_CURRENT_SECRET_KEY;
		require_once(__DIR__."/../stripe-php/stripe-php-1.6.5/lib/Stripe.php");
		$customer_email=$_REQUEST['customer_email'];
		$item_name=$params['item_name'];
		$item_price=$params['item_price'];
		$payment_test=$params['payment_test'];
		$order_cc_error = false;

		if (cc_input_validate($order_cc_error) && $_POST) {
  			Stripe::setApiKey($STRIP_API_CURRENT_SECRET_KEY);

  			try {
      			$token = $_POST['stripeToken'];

      				$price=$item_price * 100;
      				$description = $item_name . " email:$customer_email";
      				if($payment_test)$price=100;

      				 // create the charge on Stripe's servers - this will charge the user's card

  					$charge = Stripe_Charge::create(array(
   						"amount" => $price, // amount in cents, again
   						"currency" => "usd",
  	 					"card" => $token,
   						"description" => $description)
 					);

 					if($charge){
						emailOrderProcessed($customer_email,$params);
					}
					else{
						$order_cc_error="Failed to charge credit card.";
					}

  			}
  			catch (Exception $e) {
    			$order_cc_error = $e->getMessage();
  			}
		}

		if($order_cc_error){
			setcookie('order_error',$order_cc_error,time()+3600,'/'); // modified
			header('Location: ../cc.php?'.$_SERVER['QUERY_STRING']); // modified
			exit(); // modified
			include "cc.php";
			return false;

		}
		//success
		$invoice_data=array(
							 'item_name'=>$description,
							 	'email_to'=>$customer_email,
							 	'invoice_num'=>"cc_".$charge->id,
							 	'payment_gross'=>$price,
							 	'payment_date'=>time()
							 );

		markInvoiceToGenerate($invoice_data);
		if($success_callback)call_user_func($success_callback, $success_calllback_param);
		header('Location: ../thankyou.php'); // modified
	    return true;

	}

	function purchase_domain_data_paypal($params){
		$sandbox = $params['sandbox'];
		$PAYPAL_EMAIL = $params['PAYPAL_EMAIL'];
		$PAYMENT_TEST=$params['payment_test'];
		$domainNameDataPrices = $params['domainNameDataPrices'];
		$domainNameDataEditions=$params['domainNameDataEditions'];
		$data_edition = $params['data_edition'];
		$domainNameDataDiscounts = $params['domainNameDataDiscounts'];
		$pay_yearly= $params['pay_yearly'];
		$yearlyDiscount=$params['yearlyDiscount'];

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

		$params['a3'] = $dataPrice;
		$params['p3'] = 1;
		$params['t3'] = 'M';
		$params['src']=1;
		$params['sra']=1;

		if($pay_yearly){
			$params['item_name'] .= " Yearly";
			$params['t3']='Y';
			$params['a3']= round($dataPrice *12 *(1.0-$yearlyDiscount));
		}

		if($PAYMENT_TEST)$params['a3']=1;

		$url .= '?'. http_build_query($params);
		if($url)header('location:' . $url);
	}

	function purchase_domain_data_cc($params){
		global $STRIP_API_CURRENT_SECRET_KEY;
		require_once(__DIR__."/../stripe-php/stripe-php-1.6.5/lib/Stripe.php");

		$sandbox = $params['sandbox'];
		$PAYPAL_EMAIL = $params['PAYPAL_EMAIL'];
		$PAYMENT_TEST=$params['payment_test'];
		$domainNameDataPrices = $params['domainNameDataPrices'];
		$domainNameDataEditions=$params['domainNameDataEditions'];
		$data_edition = $params['data_edition'];
		$domainNameDataDiscounts = $params['domainNameDataDiscounts'];
		$customerEmail=$params['customerEmail'];
		$pay_yearly= $params['pay_yearly'];
		$yearlyDiscount=$params['yearlyDiscount'];
		$price = computeDomainNameDataMonthlyPrice($data_edition);
		if($pay_yearly){
			$price= computeDomainNameDataYearlyPrice($data_edition);
		}

		//print_r($params);
		$order_cc_error = false;

		if (cc_input_validate($order_cc_error) && $_POST) {
  			Stripe::setApiKey($STRIP_API_CURRENT_SECRET_KEY);


  			try {

      			$token = $_POST['stripeToken'];
      			$plan_id=strtolower("domain_name_data_".$domainNameDataEditions[$data_edition]);
      			if($pay_yearly){
      				$plan_id.="_yearly";
      			}
				$customer = Stripe_Customer::create(array(
  					"card" => $token,
  					"plan" => $plan_id,
  					"email" => $customerEmail)
				);

  			}
  			catch (Exception $e) {
    			$order_cc_error = $e->getMessage();
  			}
		}

		if($order_cc_error){
			setcookie('order_error',$order_cc_error,time()+3600,'/'); // modified
			header('Location: ../cc.php?'.$_SERVER['QUERY_STRING']); // modified
			exit(); // modified
			//include basename($_SERVER['HTTP_REFERER']);
			include "newly-registered-domains.php";
			return;

		}
		//success

		header('Location: ../thankyou.php'); // modified

		//send emails
		require_once __DIR__ . "/payment/payment_util.php";
		error_log("sending email $customerEmail");
		emailOrderProcessed($customerEmail, array("item_name"=>$plan_id, "order_type"=>"domain_data"));

		$subject="$plan_id was ordered by $customerEmail";
		$body=$subject;
		notifyMeOrderProcessed(array('subject'=>$subject, 'body'=>$body));

							//fulfill order(create account and email account info)
						$params=array('item_name'=>$plan_id,
							'customerEmail'=>$customerEmail,
							'username'=>$customerEmail,
							'price'=>$price,
							'payment_type'=>'cc',
							'order_date'=>date('m/d/Y')
						);
						$output=array();
						DomainNameDataAccountManager::createAccountFromOrderItem($params, $output);
	}

	function cc_input_validate(&$order_cc_error){
		if (!isset($_POST['stripeToken'])){
      		$order_cc_error= "The Stripe Token was not generated correctly.  Please try again. ".print_r($_POST,1);

		}
		else if(!filter_var($_REQUEST['customer_email'], FILTER_VALIDATE_EMAIL)) {
    		$order_cc_error = "!Please enter a valid email. ";

		}
		if($order_cc_error)return false;
      	return true;
	}

	function include_orderpage($pay_choice){
		// modified
		global $order_error_msg;
		/*require __DIR__ .'/../vendor/autoload.php';
		$laravel_app = require __DIR__ .'/../bootstrap/start.php';
		$laravel_app->boot();
		// boot session
		require __DIR__ .'/laravelManager.php';
		$manager = new laravelSessionManager($laravel_app);
		$manager->startSession();

		require __DIR__ .'/../bootstrap/autoload.php';
		$app = require_once __DIR__ .'/../bootstrap/start.php';

		$request = $app['request'];
		$client = (new \Stack\Builder)
		            ->push('Illuminate\Cookie\Guard', $app['encrypter'])
		            ->push('Illuminate\Cookie\Queue', $app['cookie'])
		            ->push('Illuminate\Session\Middleware', $app['session'], null);
		$stack = $client->resolve($app);
		$stack->handle($request);
		Session::flash('url.intended', $order_error);
		Session::put('success', $order_error);
		return Redirect::intended('/');*/
		// print_r(Session::get('success'));
		// return Redirect::intended('./order_paypal')->with('success', 'You have registered successfully.');
		if($pay_choice=='pp'){
			setcookie('order_error',$order_error_msg,time()+3600,'/');
			$redirectUrl = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../order_paypal.php';
			header('Location: '.$redirectUrl);
		} else {
			setcookie('order_error',$order_error_msg,time()+3600,'/');
			$redirectUrl = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../order.php';
			header('Location: '.$redirectUrl);
		}
		exit();
		/*
		global $PAYMENT_TEST;
		if($pay_choice=='pp'){
			$page=__DIR__."/order_paypal.php";
			if($PAYMENT_TEST)$page=__DIR__."/order_paypal_test.php";
			include $page;
		}
		else{
			include __DIR__."/order.php";
		}*/
	}

?>
