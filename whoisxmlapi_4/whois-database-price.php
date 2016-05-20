<?php
	$dbDiscount = 0.7;
	$dbRawPrices = array(1=>800/*, 2=>1200, 5=>2400,*/
	 );
	$dbParsedPrices = array();
	foreach($dbRawPrices as $key=>$val){
		$dbParsedPrices[$key] = $val * 1.2;
	} 
	$dbCount = count($dbRawPrices);
	$dbAmount = array_keys($dbRawPrices);
	
	function compute_db_price($quantity, $type){
		global $dbRawPrices, $dbParsedPrices;
		if($type=='raw')return $dbRawPrices[$quantity];
		else return $dbParsedPrices[$quantity];
	}
	function compute_real_db_price($quantity, $type){
		global $dbDiscount;
		return (1-$dbDiscount) * compute_db_price($quantity, $type);
	}	
	function discount($price, $discount){
	$new_price = $price * (1-$discount);
	$per = $discount * 100;
	$s = "<div style=\"color:red; font-weight:bold;background:transparent;\"><del>$" . "$price</del><br/> $" . "$new_price ($per % off)</div>";
	return $s;
}
	
?>