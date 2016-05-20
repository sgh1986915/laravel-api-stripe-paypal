<?php
	$dipDBDiscount = 0.7;
	$dipDBPrices = array(1=>500/*, 2=>1200, 5=>2400,*/
	 );
	
	$dipDBCount = count($dipDBPrices);
	$dipDBAmount = array_keys($dipDBPrices);
	
	function compute_dip_db_price($quantity, $type){
		global $dipDBPrices;
		return $dipDBPrices[$quantity];
	}
	function compute_real_dip_db_price($quantity, $type){
		global $dipDBDiscount;
		return (1-$dipDBDiscount) * compute_dip_db_price($quantity, $type);
	}	
	
	
?>