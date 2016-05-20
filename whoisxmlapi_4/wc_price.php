<?php
	$group_license_price=499;
	$source_license_price=999;
	

	function compute_wc_license_price($license_type, $num=0){
		
		global $group_license_price, $source_license_price;
		if($license_type=='group_license')return $group_license_price;
		else if($license_type=='sourcecode_license'){
		
			return $source_license_price;
		}
		return compute_user_license_price($num);	
	}
	
	function compute_user_license_price($n){
		global $group_license_price;
		
		return compute_user_license_unit_price($n)*$n;
	}
	function compute_user_license_unit_price($n){
		if($n<6)return 59;
		else if ($n<11)return 49;
	}
?>