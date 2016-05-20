<?php function compute_bwl_price($n, $bwl_speed){
		if($bwl_speed=='regular'){
			if($n<=100000){
				return 200;
			}
			else if($n<=5000000){
				return 500 + floor(0.0005 * $n);
			}
			return 2500 + floor(0.0001 * $n);
		}
		else if($bwl_speed=='expedited'){
			if($n<=100000){
				return 500;
			}
			else if($n<=5000000){
				return 500 + floor(0.0001 * $n);
			}
			return 2500 + floor(0.0002 * $n);
		}
	}
	function compute_bwl_time($n, $bwl_speed){
		if($bwl_speed=='regular'){
			if($n<=100000){
				return 2;
			}
			else if($n<=5000000){
				return 1 + ceil(0.000005 * $n);
			}
			return 17 + ceil(0.000001 * $n);
		}
		else if($bwl_speed=='expedited'){
			if($n<=100000){
				return 1;
			}
			else if($n<=5000000){
				return 1 + ceil(0.000001 * $n);
			}
			return 7 + ceil(0.0000005 * $n);
		}
	}

?>