<?php require_once __DIR__ . "/util.php";
	require_once __DIR__ ."/order_util.php";
	//$cc=1;
	//validation
	
	$order_username=$_REQUEST['order_username'];
	$order_type=$_REQUEST['order_type'];
	$query_quantity = get_from_post_get("query_quantity");
	$membership = getMembership();
	if(isset($_REQUEST['submit'])){ 
		if($order_type=='whoisapi'){
			$order_error = false;
			if(!$order_username || strlen($order_username) <= 0){
				$order_error ="You must enter a username for the account you wish to fill.  If you don't have one, you may either create an account now, or create an account with the username specified here after the purchase.";
			}
			else if(!validate_username($order_username)){
				$order_error = "The account username must contain only letters, numbers, dot, underscores and @.";
			}
	
			if(!$membership && !$query_quantity){
				$order_error = "You must either select the number of queries you wish to purchase or pick a membership.";
			}
			if($order_error){
				include 'order.php';
				exit();
			}
			
		}
		else if($order_type == 'custom_wdb'){
			$custom_wdb_ids=$_REQUEST['custom_wdb_ids'];
			$custom_wdb_order_error=false;
			if(!$custom_wdb_ids || count($custom_wdb_ids)==0){
				$custom_wdb_order_error = "You must select an Alexa/Quantcast Whois database to purchase.";
			}
			if($custom_wdb_order_error){
				include 'order.php';
				exit;
			}
		}
		else if($order_type == 'cctld_wdb'){
			
			$cctld_wdb_ids=$_REQUEST['cctld_wdb_ids'];
			$cctld_wdb_order_error=false;
			if(!$cctld_wdb_ids || count($cctld_wdb_ids)==0){
				$cctld_wdb_order_error = "You must select at least one cctld Whois database to purchase.";
			}
			if($cctld_wdb_order_error){
				include 'order.php';
				exit;
			}
		}		
	}
	
	
	
	$pages = array('right' => 'cc_main.php');	
	include "template.php";
?>
