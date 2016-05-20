<?php

		$affiliate = getAffiliate();
		if($affiliate){
			if(!session_id()){
				session_start();
			}
			$_SESSION['_affiliate'] = $affiliate;


		}




	function getAffiliate(){
		if(isset($_REQUEST['hop'])){
			return 	$_REQUEST['hop'];
		}
		if(isset($_SERVER['HTTP_REFERER'])){
			$referer = $_SERVER['HTTP_REFERER'];
			$referer=getAffiliateFromReferer($referer);
			return $referer;
		}
		return false;
	}

	function getAffiliateFromReferer($url){
		if(!$url)return false;
		$ret=parse_url($url);
		//print_r($ret);
		if($ret && isset($ret['host']) && strcasecmp($ret['host'], 'clickbank.com')===0){
			return $ret['host'];
		}
		return false;
	}
?>
