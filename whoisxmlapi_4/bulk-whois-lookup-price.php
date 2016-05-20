<?php 
	$regular_bwl_prices=array('100000'=>400, '250000'=>800,  '500000'=>1000, '1000000'=>1500, '2500000'=>2000);
	$expedited_bwl_prices=array();
	$regular_bwl_speed=array('100000'=>3, '250000'=>3,  '500000'=>5, '1000000'=>7, '2500000'=>9);
	$expedited_bwl_speed=array();
	foreach($regular_bwl_speed as $key=>$val){
		$expedited_bwl_speed[$key]=max(2,round($val*0.5));
	}
	foreach($regular_bwl_prices as $key=>$val){
		$expedited_bwl_prices[$key]=round($val*1.4);
	}
	
	function compute_bwl_price($n, $bwl_speed){
		global $regular_bwl_prices, $expedited_bwl_prices;
		if($bwl_speed=='regular'){
			return $regular_bwl_prices[$n];
		}
		else if($bwl_speed=='expedited'){
			return $expedited_bwl_prices[$n];
		}
	}
	function compute_bwl_time($n, $bwl_speed){
		global $regular_bwl_speed, $expedited_bwl_speed;
		if($bwl_speed=='regular'){
			return $regular_bwl_speed[$n];
		}
		else if($bwl_speed=='expedited'){
			return $expedited_bwl_speed[$n];
		}
	}

?>