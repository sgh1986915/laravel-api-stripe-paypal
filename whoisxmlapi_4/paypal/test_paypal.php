<?php

/*  PHP Paypal IPN Integration Class Demonstration File
 *  4.16.2005 - Micah Carrick, email@micahcarrick.com
 *
 *  This file demonstrates the usage of paypal.class.php, a class designed
 *  to aid in the interfacing between your website, paypal, and the instant
 *  payment notification (IPN) interface.  This single file serves as 4
 *  virtual pages depending on the "action" varialble passed in the URL. It's
 *  the processing page which processes form data being submitted to paypal, it
 *  is the page paypal returns a user to upon success, it's the page paypal
 *  returns a user to upon canceling an order, and finally, it's the page that
 *  handles the IPN request from Paypal.
 *
 *  I tried to comment this file, aswell as the acutall class file, as well as
 *  I possibly could.  Please email me with questions, comments, and suggestions.
 *  See the header of paypal.class.php for additional resources and information.
*/

// Setup class
require_once dirname(__FILE__) . "/../business_def.php";
require_once('paypal.class.php');  // include the class file

require_once(dirname(__FILE__) . '/../payment/payment_util.php');
require_once __DIR__ ."/../domain_name_data/DomainNameDataAccountManager.php";
require_once __DIR__ ."/../payment/order.php";
require_once __DIR__ ."/../invoice/invoice.php";

echo "hi";
/*
	$params=array(
			'item_name'=>"Daily Domain Name Data Lite",
			'subscr_date'=>"22:26:40 Feb 07, 2015 PST",
			'payer_email'=>"topcoder1@gmail.com"
	);

	$output=array();
	if(!DomainNameDataAccountManager::deleteAccountFromOrderItem($params, $output)){
		
		
	}
	echo "finished";*/

exit;

$p = new paypal_class;             // initiate an instance of the class
//$p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';   // testing paypal url
$p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';     // paypal url

// setup a variable for this script (ie: 'http://www.micahcarrick.com/paypal.php')
$this_script = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

// if there is not action variable, set the default action of 'process'
//if (empty($_GET['action'])) $_GET['action'] = 'process';
			       payment_log("test_paypal");
if(!isset($_GET['action']))return;
payment_log($_GET['action']);


switch ($_GET['action']) {

   case 'process':      // Process and order...

      // There should be no output at this point.  To process the POST data,
      // the submit_paypal_post() function will output all the HTML tags which
      // contains a FORM which is submited instantaneously using the BODY onload
      // attribute.  In other words, don't echo or printf anything when you're
      // going to be calling the submit_paypal_post() function.

      // This is where you would have your form validation  and all that jazz.
      // You would take your POST vars and load them into the class like below,
      // only using the POST values instead of constant string expressions.

      // For example, after ensureing all the POST variables from your custom
      // order form are valid, you might have:
      //
      // $p->add_field('first_name', $_POST['first_name']);
      // $p->add_field('last_name', $_POST['last_name']);

      $p->add_field('business', 'YOUR PAYPAL (OR SANDBOX) EMAIL ADDRESS HERE!');
      $p->add_field('return', $this_script.'?action=success');
      $p->add_field('cancel_return', $this_script.'?action=cancel');
      $p->add_field('notify_url', $this_script.'?action=ipn');
      $p->add_field('item_name', 'Paypal Test Transaction');
      $p->add_field('amount', '1.99');

      $p->submit_paypal_post(); // submit the fields to paypal
      //$p->dump_fields();      // for debugging, output a table of all the fields
      break;

   case 'success':      // Order was successful...

      // This is where you would probably want to thank the user for their order
      // or what have you.  The order information at this point is in POST
      // variables.  However, you don't want to "process" the order until you
      // get validation from the IPN.  That's where you would have the code to
      // email an admin, update the database with payment status, activate a
      // membership, etc.

      echo "<html><head><title>Success</title></head><body><h3>Thank you for your order.</h3>";
      foreach ($_POST as $key => $value) { echo "$key: $value<br>"; }
      echo "</body></html>";

      // You could also simply re-direct them to another page, or your own
      // order status page which presents the user with the status of their
      // order based on a database (which can be modified with the IPN code
      // below).

      break;

   case 'cancel':       // Order was canceled...

      // The order was canceled before being completed.

      echo "<html><head><title>Canceled</title></head><body><h3>The order was canceled.</h3>";
      echo "</body></html>";

      break;

   case 'ipn':          // Paypal is calling page for IPN validation...

      // It's important to remember that paypal calling this script.  There
      // is no output here.  This is where you validate the IPN data and if it's
      // valid, update your database to signify that the user has payed.  If
      // you try and use an echo or printf function here it's not going to do you
      // a bit of good.  This is on the "backend".  That is why, by default, the
      // class logs all IPN data to a text file.
	
     if ($p->validate_ipn()) {

         // Payment has been recieved and IPN is verified.  This is where you
         // update your database to activate or process the order, or setup
         // the database with the user's order details, email an administrator,
         // etc.  You can access a slew of information via the ipn_data() array.

         // Check the paypal documentation for specifics on what information
         // is available in the IPN POST variables.  Basically, all the POST vars
         // which paypal sends, which we send back for validation, are now stored
         // in the ipn_data() array.

         // For this example, we'll just email ourselves ALL the data.
		payment_log("ipn validated! ". print_r($p->ipn_data,1));

		 if(in_array($p->ipn_data['business'], $ALL_PAYPAL_EMAILS)){
		 	$to = $p->ipn_data['payer_email'];
		 	$item_name=$p->ipn_data['item_name'];
		 	$tmp_invoice_data=array();
		 	if(check_api_orders($p->ipn_data, $_REQUEST['oif'], $tmp_invoice_data)){
		 		markInvoiceToGenerate($p->ipn_data, $tmp_invoice_data);
		 		return;
		 	}
		 	//domain name data only needs processing for the inital subscription signup(txn_type='subscr_signup', it doesn't have payment_status="Completed"
		 	if(isset($p->ipn_data['txn_type']) && ( stripos($p->ipn_data['item_name'], 'Daily Domain Name Data') !==false)){//daily domain name data
				payment_log("daily domain name data ".print_r($p->ipn_data));
				if($p->ipn_data['txn_type'] == 'subscr_signup'){ 
					$price=$p->ipn_data['amount3'];
					
					emailOrderProcessed($to, array("item_name"=>$item_name, "order_type"=>"domain_data"));
					payment_log("emailed to $to");
					try{
					//fulfill order(create account and email account info)
						$params=array('item_name'=>$item_name,	
							'customerEmail'=>$to,
							'username'=>$to,
							'price'=>$price,
							'payment_type'=>'paypal',
							'order_date'=>date('m/d/Y')
						);
						$output=array();		
						DomainNameDataAccountManager::createAccountFromOrderItem($params, $output);
					} catch (Exception $e) {
    						error_log('Caught exception: '.  $e->getMessage(). "\n");
					}
					$invoice_data=array('email_to'=>$to);
					
				}
			}

			//modified by infonius...april/12

			if(isset($p->ipn_data['txn_type']) && ( stripos($p->ipn_data['item_name'], 'Zone file / Domain List') !==false)){//daily domain name data
				payment_log("Zone file / Domain List ".print_r($p->ipn_data));
				if($p->ipn_data['txn_type'] == 'subscr_signup'){ 
					$price=$p->ipn_data['amount3'];
					
					emailOrderProcessed($to, array("item_name"=>$item_name, "order_type"=>"domain_data_zone_file"));
					payment_log("emailed to $to");
					try{
					//fulfill order(create account and email account info)
						$params=array('item_name'=>$item_name,	
							'customerEmail'=>$to,
							'username'=>$to,
							'price'=>$price,
							'payment_type'=>'paypal',
							'order_date'=>date('m/d/Y')
						);
						$output=array();		
						DomainNameZoneFileDataAccountManager::createAccountFromOrderItem($params, $output);
					} catch (Exception $e) {
    						error_log('Caught exception: '.  $e->getMessage(). "\n");
					}
					$invoice_data=array('email_to'=>$to);
					
				}
			}		   
		 	
			//**************************************************************************//

		   	if(!isset($p->ipn_data['payment_status']) || strcasecmp($p->ipn_data['payment_status'], 'Completed') !== 0  ){
		    	return; //need test
		   	}
			if(isset($p->ipn_data['reason_code']) && $p->ipn_data['reason_code'] =='refund')return; //refund notice, to be taken out
			 
         	
		 	$spk_index = stripos($item_name, "Whois API Software Package ");		 	
			$index = stripos($item_name, " whois queries for ");
			
			payment_log("index is $index");
					
			if($spk_index !==false){
				$spkEdition = trim(substr($item_name, $spk_index + strlen("Whois API Software Package ")));
				emailOrderProcessed($to, array("item_name"=>$item_name, "order_type"=>"spk"));
				
				$subject="$item_name order received from $to";
				$body ="$item_name order received from $to\n";
				$body .=date('m/d/Y g:i A')."\n";
		 		$emailer=new Email;
		 		$emailer->from="support@whoisxmlapi.com";
		 		$emailer->send_mail("topcoder1@gmail.com",$subject,$body,null);

		
			}
			else if($index > 0){//whoisapi queries
				$quantity = trim(substr($item_name, 0, $index));
				$accountUserName = trim(substr($item_name, $index+strlen(" whois queries for ")));
				if(is_numeric($quantity) && !empty($accountUserName)){
					$fulfillRes = whoisAddQuantity($quantity, $accountUserName);
					payment_log("fulfill $fulfillRes");
					if($fulfillRes) emailOrderProcessed($to, array('accountUserName'=>$accountUserName,
						'orderQuantity'=>$quantity, 'item_name'=>$item_name));
					
					$invoice_data=array('username'=>$accountUserName);
				}
			}
      		else if(isset($p->ipn_data['txn_type']) && stripos($p->ipn_data['item_name'], 'Reverse Whois') !==false ){//reverse whois
      			$bulk_rw_index = stripos($item_name, "Reverse Whois Lookups");
      			$bulk_rw_month_index = stripos($item_name, "Reverse Whois Lookups/month");
      			
      			if($bulk_rw_month_index>0){
      				$occ="ly membership subscription of ";
					$index = stripos($item_name, $occ);
      				
      				$accountUserName = trim(substr($item_name, strrpos($item_name, " for ") + strlen(" for ")));
      			
					$membership_payperiod = substr($item_name, 0, $index);
					
					$quantity = substr($item_name, $index + strlen($occ), strrpos($item_name, "Reverse Whois lookups")-strlen($occ)-$index-1);
					payment_log($item_name);
					payment_log("reverse whois membership vars: $quantity, $membership_payperiod, $accountUserName, ");
					
				
					if(is_numeric($quantity) && !empty($accountUserName)){
					
						$fulfillRes = reverseWhoisFillMonthlyQuantity($quantity, $accountUserName, array('membership_payperiod'=>$membership_payperiod));
						if($fulfillRes) emailOrderProcessed($to, array('accountUserName'=>$accountUserName,
							'orderQuantity'=>$quantity, 'item_name'=>$item_name, 'order_type'=>'reverse_whois_credits_monthly',
							'extraBody' =>"Your account's reverse whois lookup balance will be reset to $quantity every month on day ".date('d') ."."
							));
						$invoice_data=array('username'=>$accountUserName);	

					}	
      			}
      			else if($bulk_rw_index > 0){
      				$quantity = trim(substr($item_name, 0, $bulk_rw_index));
					$accountUserName = trim(substr($item_name, $bulk_rw_index+strlen("Reverse Whois lookups for ")));
					if(is_numeric($quantity) && !empty($accountUserName)){
						$fulfillRes = reverseWhoisAddQuantity($quantity, $accountUserName);
						payment_log("fulfill $fulfillRes");
						if($fulfillRes) emailOrderProcessed($to, array('accountUserName'=>$accountUserName,
							'orderQuantity'=>$quantity, 'item_name'=>$item_name, 'order_type'=>'reverse_whois_credits'));
						$invoice_data=array('username'=>$accountUserName);
					}
      			}
      			
      			else{//standard report pricing
      				
      			
          			$error = false;
          			$order_id = $p->ipn_data['item_number'];
          			$payment_util = new payment_util();
    	  			if(!$payment_util->fill_order($order_id)) $error= $payment_util->error;
          			if($error) payment_log("reverse whois report error: $error");
          			else {
          				payment_log("reverse whois report filled: ".$p->ipn_data['item_name']);
          				emailOrderProcessed($to, array("item_name"=>$item_name, "order_type"=>"reverse_whois_report"));
          			}
          			$invoice_data=array('email_to'=>$to);
      			}
      		}
			else if(isset($p->ipn_data['txn_type']) && ( stripos($p->ipn_data['item_name'], 'Whois API Client User License') !==false || stripos($p->ipn_data['item_name'], 'Whois API Client Application Group License') !==false )){
          		
          		payment_log("Whois API Client user license filled: ".$p->ipn_data['item_name']);
          		emailOrderProcessed($to, array("item_name"=>$item_name, "order_type"=>"whois_api_client_license"));
				$invoice_data=array('email_to'=>$to);
			} 
			  		      		
			else{ 
				if(!isset($p->ipn_data['txn_type']) || $p->ipn_data['txn_type']!='subscr_payment')return;
			
				$occ="ly membership subscription of ";
				$index = stripos($item_name, $occ);
				if($index >0){
					$accountUserName = trim(substr($item_name, strrpos($item_name, " for ") + strlen(" for ")));
					$membership_payperiod = substr($item_name, 0, $index);
					$quantity = substr($item_name, $index + strlen($occ), strrpos($item_name, "queries")-strlen($occ)-$index-1);
					payment_log($item_name);
					payment_log("membership vars: $quantity, $membership_payperiod, $accountUserName, ". strrpos($item_name, "queries"));
					
					/*if(check_monthly_skips(array('quantity'=>$quantity, 'accountUserName'=>$accountUserName))){
						return;
					}*/
					if(is_numeric($quantity) && !empty($accountUserName)){
						//$fulfillRes = whoisAddQuantity($quantity, $accountUserName, array('membership_payperiod'=>$membership_payperiod));
						$fulfillRes = whoisFillQuantity($quantity, $accountUserName, array('membership_payperiod'=>$membership_payperiod));
						if($fulfillRes) emailOrderProcessed($to, array('accountUserName'=>$accountUserName,
							'orderQuantity'=>$quantity, 'item_name'=>$item_name,
							'extraBody' =>"Your account's query balance will be reset to $quantity every month on day ".date('d') ."."
							));
						$invoice_data=array('email_to'=>$to);
						
					}	
				}

			}
    	}
	}

 }
payment_log( "invoice_data is ".print_r($invoice_data,1));

if($invoice_data && $p && $p->ipn_data){
	markInvoiceToGenerate($p->ipn_data, $invoice_data);
}
function markInvoiceToGenerate($ipn_data, $invoice_data){
	$to = $ipn_data['payer_email'];
	$item_name=$ipn_data['item_name'];
	$username=$invoice_data['username'];
	$email_to=$invoice_data['email_to'];
	
	$invoice_num="pp_".$ipn_data['txn_id'];
	$invoice_desc="";
	$invoice_file_path=Invoice::generateInvoiceFilePath(array('username'=>$username, 'email_to'=>$email_to, 'invoice_num'=>$invoice_num));
	
	//content data
	$sendTo=$ipn_data['first_name']. " ".$ipn_data['last_name']. "<br/>".$to;
	$paymentGross="$" . $ipn_data['payment_gross'];
	$paymentDate=$ipn_data['payment_date'];
	
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
function payment_log($s){
   $text = '['.date('m/d/Y g:i A').'] - ';
  $f=fopen("payment.log","a") or error_log("can't open file");
  if($f){
    fwrite($f,"$text $s\n");
    fclose($f);
  }
}
function check_api_orders($ipn_data, $order_info, &$invoice_data){
	return Order::fill_api_order_paypal($ipn_data, $order_info, $invoice_data);
}
//no longer used
function check_monthly_skips($prop){
	  $MONTHLY_SKIPS=array(array('quantity'=>2000, 'accountUserName'=>'root'), array('quantity'=>5000, 'accountUserName'=>'jarrodhunt'), array('quantity'=>5000, 'accountUserName'=>'nSphere'), array('quantity'=>50000, 'accountUserName'=>'nSphere'));
	foreach($MONTHLY_SKIPS as $skip){
		if(array_match($skip, $prop))return true;
	}
	return false;
}
function array_match($a, $b){
	foreach($a as $key=>$val){
		if($a[$key] != $b[$key]){
			return false;
		}
	}
	return true;
}
?>