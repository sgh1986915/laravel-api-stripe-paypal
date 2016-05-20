<?php 
	//NOT IN USE!
include __DIR__."/config.php";
	include __DIR__."/domain_util.php";
	
	global $whois_cache_age;
	if(isset($_REQUEST['q'])){
		
		$q = clean_domain($_REQUEST['q']);
		
		if(!empty($q))$q = strtolower($q);
		$res = "";
		$file = get_cache_file($q);
		//echo "whois_cache_age is $whois_cache_age time is " .time(). " filemtime($file) is " .filemtime($file). " diff is " . (time()- filemtime($file));
		if(is_file($file) && time() - filemtime($file) < $whois_cache_age){ 
			if($res = get_cache($q)){
				if(containsErr($res)){
					$res = "";
				}
			}
				
		
		}
		if($res && strlen($res>1))echo $res;
		
		else {
			$hasErr=0;
			ob_start();
			
			include __DIR__."/search.php";
		    
			$res = ob_get_flush();
			error_log("res is $res");
			if($res && strlen($res) > 1 && !containsErr($res)) file_put_contents($file,$res);
		}
	}
	function containsErr($s){
		return stripos($s, "Whois lookup failed due to Internal Server Error")!==false;
	}
	function get_cache_file($q){
		global $cache_dir;
		$base_dir=$cache_dir;
		$file = $base_dir . $q;
		return $file;
	}
	function get_cache($q){
		$file = get_cache_file($q);
		if(file_exists($file))
			return file_get_contents($file);
		return false;
	}
	
?>