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
	$params=array('item_name'=>"domain_name_data_enterprise_yearly",	
			'customerEmail'=>'topcoder1@gmail.com',
			'username'=>'topcoder1@gmail.com',
			'price'=>'109.0',
			'payment_type'=>'cc',
			'order_date'=>date('m/d/Y')
			);
	$output=array();		
	echo DomainNameDataAccountManager::createAccountFromOrderItem($params, $output);
	
?>