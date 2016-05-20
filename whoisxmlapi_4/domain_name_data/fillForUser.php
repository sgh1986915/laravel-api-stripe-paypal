<?php require_once __DIR__ ."/DomainNameDataAccountManager.php";
	//DomainNameDataAccountManager::createAccount('tester','tester','lite');
	/*
	$params=array('item_name'=>"Domain Name Data Enterprise",	
			'customerEmail'=>'topcoder1@gmail.com',
			'username'=>'topcoder1@gmail.com',
			'price'=>'109.0',
			'payment_type'=>'paypal',
			'order_date'=>date('m/d/Y')
			);
	$output=array();		
	echo DomainNameDataAccountManager::createAccountFromOrderItem($params, $output);
	*/
	/*
	$params=array('item_name'=>"domain_name_data_enterprise",	
			'customerEmail'=>'topcoder1@gmail.com',
			'username'=>'topcoder1@gmail.com',
			'price'=>'109.0',
			'payment_type'=>'cc',
			'order_date'=>date('m/d/Y')
			);
	$output=array();		
	echo DomainNameDataAccountManager::createAccountFromOrderItem($params, $output);
	*/
$item_name=$_REQUEST['item_name'];
$customer_email=$_REQUEST['customer_email'];
$username=$_REQUEST['username'];
$price=$_REQUEST['price'];
$payment_type=$_REQUEST['payment_type'];
$active_days=$_REQUEST['active_days'];

if(empty($item_name)){
  echo "must specify an item_name (eg. domain_name_data_lite_yearly)";
  exit;
}
if(empty($customer_email)){
  echo "must specify an email";
  exit;
}
if(empty($price)){
  echo "must specify a price";
  exit;
}
if(empty($payment_type)){
  echo "must specify a payment_type(eg.cc,pp)";
  exit;
}
	$params=array('item_name'=>"$item_name",	
			'customerEmail'=>"$customer_email",
			'username'=>"$customer_email",
			'price'=>"$price",
			'payment_type'=>"$payment_type",
			'order_date'=>date('m/d/Y'),
			
			);
	if($active_days)$params['active_days']=$active_days;
			
	$output=array();		
	echo DomainNameDataAccountManager::createAccountFromOrderItem($params, $output);
	
?>