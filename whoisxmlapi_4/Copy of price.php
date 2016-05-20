<?php
	$queryPrices = array(1000=>15, 2500=>30, 5000=>50,
	10000=>80, 25000=>150, 50000=>250,
	100000=>400, 250000=>800, 500000=>1200,
	1000000=>1800, 2500000=>3000, 5000000=>5000,
	
	 );
	$queryCount = count($queryPrices);
	$queryAmount = array_keys($queryPrices);


	$membershipPrices=array(2000=>15, 5000=>30, 10000=>50,
		20000=>80, 50000=>150, 100000=>250,
		200000=>400, 500000=>800, 1000000=>1200,
		2000000=>1800, 5000000=>3000
	);
	$membershipCount = count($membershipPrices);
	$membershipAmount = array_keys($membershipPrices);
	
?>