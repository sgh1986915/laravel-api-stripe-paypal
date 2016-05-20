<?php
		$url = 'https://www.paypal.com/cgi-bin/webscr';
		$params = array(//'cmd' => '_ext-enter', 'redirect_cmd'=>'_xclick',
			'business' => "support@whoiswebservice.net",
			//'custom' =>'whoisapi',
			//'page_style' =>'whoisxmlapi',
			 'no_shipping'=>1
			 //'return'=>'http://www.whoisxmlapi.com/thankyou.php'
		);
			
			$payperiod = "M";
			$cost = 200;
		
			$params['cmd']='_xclick-subscriptions';
			$itemDescription ="recent registrant monthly list subscription for group2market";
						
			$params['item_name']=$itemDescription;
			$params['a3'] =  $cost;	
			$params['p3'] = 1;
			$params['t3'] = $payperiod;
			$params['src']=1;
			$params['sra']=1;
			
		
		
	
	
	$url .= '?'. http_build_query($params);
	//echo $url;
	if($url)header('location:' . $url);
	//print_r($params);
?>