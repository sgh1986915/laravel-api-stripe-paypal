<?php
function filter_array($a, $filter){
	$b=array();
	foreach($a as $key=>$val){
		if(in_array_match($key,$filter))$b[$key]=$val;
	}
	return $b;
	
}
function in_array_match($needle, $haystack){
	foreach($haystack as $val){
		if(strpos($needle, $val) !== false)return true;
	}
	return false;
}
function array_copy($a){
	$obj = new ArrayObject($a);

	// create a copy of the array
	$copy = $obj->getArrayCopy();
	return $copy;
}

function array_has_keys($array, $keys){
	
	foreach($keys as $key){
		if(!array_key_exists($key,$array))return false;
	}
	
	return true;
}
?>