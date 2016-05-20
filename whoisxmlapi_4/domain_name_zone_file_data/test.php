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
	$params=array('item_name'=>"domain_name_data_professional",	
			'customerEmail'=>'topcoder1@gmail.com',
			'username'=>'topcoder1@gmail.com',
			'price'=>'109.0',
			'payment_type'=>'cc',
			'order_date'=>date('m/d/Y')
			);
	$output=array();		
	//echo DomainNameDataAccountManager::createAccountFromOrderItem($params, $output);

//txn_type=subscr_cancel, item_name=Daily Domain Name Data Enterprise, subscr_date=09:56:35 Jan 13, 2014 PST

    $params=array(
        'item_name'=>"Daily Domain Name Data Enterprise",
        'subscr_date'=>"09:56:35 Jan 13, 2014 PST",
        'payer_email'=>'support@whoisxmlapi.com'
    );

    $output=array();
    if(!DomainNameZoneFileDataAccountManager::deleteAccountFromOrderItem($params, $output)){
        echo "failed";


    }
?>