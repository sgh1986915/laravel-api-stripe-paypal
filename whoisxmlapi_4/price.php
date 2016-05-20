<?php
	$queryPrices = array(1000=>15, 2500=>30, 5000=>50,
	10000=>80, 25000=>150, 50000=>250,
	100000=>400, 250000=>800, 500000=>1200,
	1000000=>1800, 2500000=>2500
	 );
    $extendedQueryPrices=$queryPrices;
    $extendedQueryPrices[5000000]=3750;
    $extendedQueryPrices[10000000]=5625;
    $extendedQueryPrices[20000000]=8437;
	
    extend_price($extendedQueryPrices, 10);

	$membershipPrices=array(2000=>15, 5000=>30, 10000=>50,
		20000=>80, 50000=>150, 100000=>250,
		200000=>400, 500000=>800, 1000000=>1200,
		2000000=>1800//, 5000000=>3000
	);
	$extendedMembershipPrices=$membershipPrices;
    $extendedMembershipPrices[4000000]=2700;
    $extendedMembershipPrices[8000000]=4050;
    $extendedMembershipPrices[16000000]=6075;

    extend_price($extendedMembershipPrices, 10);


	$spkPrices = array(899, 4999, 8899);
	$spkEditions = array('Lite', 'Professional', 'Enterprise');

	//$spkDiscounts = array(0.25, 0.45, 0.25);
	$spkDiscounts = array(0, 0, 0);

	$domainNameDataPrices = array(19, 29, 109, 259, 359);
	$domainNameDataEditions = array('Lite', 'Professional', 'Enterprise', 'Custom1', 'Custom2');
	$domainNameDataDiscounts = array(0,0,0,0,0);
	$domainNameDataYearlyDiscount=0.1;

	/* modified */
    $GLOBALS['domainNameDataPrices'] = $domainNameDataPrices;
    $GLOBALS['domainNameDataEditions'] = $domainNameDataEditions;
    $GLOBALS['domainNameDataDiscounts'] = $domainNameDataDiscounts;
    $GLOBALS['domainNameDataYearlyDiscount'] = $domainNameDataYearlyDiscount;
    /* ------- */

	/*
	if(isset($_REQUEST['custom_order'])){

		include __DIR__ . "/custom_price.php";

		global $customMembershipPrices;
		//array_push($membershipPrices, $customMembershipPrices);
		foreach($customMembershipPrices as $key=>$val){
			$membershipPrices[$key]=$val;
		}

	}*/

	$queryCount = count($queryPrices);
	$queryAmount = array_keys($queryPrices);

	$membershipCount = count($membershipPrices);
	$membershipAmount = array_keys($membershipPrices);

	function computeDomainNameDataYearlyPrice($edition){
		global $domainNameDataPrices, $domainNameDataEditions,$domainNameDataDiscounts,$domainNameDataYearlyDiscount;
		return round($domainNameDataPrices[$edition] * (1.0-$domainNameDataDiscounts[$edition]) * 12 * (1.0-$domainNameDataYearlyDiscount));
	}
	function computeDomainNameDataMonthlyPrice($edition){
		global $domainNameDataPrices, $domainNameDataEditions,$domainNameDataDiscounts,$domainNameDataYearlyDiscount;
		return round($domainNameDataPrices[$edition] * (1.0-$domainNameDataDiscounts[$edition]));
	}
	function extend_price(&$base, $n){
		$max_key=max(array_keys($base));
		
		$cur_key=$max_key;
		for($i=0;$i<$n;$i++){
			$prev_key=$cur_key;
			$cur_key*=2;
			
			$base[$cur_key]=$base[$prev_key]*1.5;
			//echo "cur key is $cur_key<br/>";
		}
		//echo "<br/>";
	}
?>