<?php
function myErrorHandler($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting
        return;
    }
	
    switch ($errno) {
    	
  

    	case E_WARNING:
      	//	  echo "<b>My WARNING</b> [$errno] $errstr<br />\n";
     
        	throw new Exception($errstr);
        	return true;
        
        
    }
    /* Don't execute PHP internal error handler */
    return false;
}
?>


<?php require_once __DIR__ . "/config.php";
	require_once __DIR__. "/domain_util.php";
	
	$q =  $_REQUEST['q'];
	
	$url = $whoisapi_server_base_url . "WhoisService?domainName=$q&outputFormat=json";
	echo $url;
	exit;
	if($ip_pass_thru) $url.="&_ipPassThru=".$_SERVER['REMOTE_ADDR'];
	$old_error_handler=set_error_handler("myErrorHandler");
	$err = false;
	try{
		$res = file_get_contents($url);
	}catch(Exception $e){
		$err = "Whois lookup failed due to Internal Server Error, please contact <a href=\"mailtosupport@whoisxmlapi.com\">tech support</a>. ";
		$res = json_encode(array('ErrorMessage'=>array('msg'=>$err)));
		
		
	}
	if($old_error_handler)set_error_handler($old_error_handler);
	//echo $res;
	if(!$res)exit;
	
	$json=json_decode($res);
	if(!$json)exit;
	
		
	
?>


	
<?php
	function render_whois($s){
		
		$res="";
		$w = $s->WhoisRecord;
		if($w){
			
			if(empty($w->rawText)){
				$r = $w->registryData;
				if($r){
					$res .= render_text($r);
				}
			}
			else $res .= render_text($w, 1);
			
		}
		
		$res = trim($res);
		$res = "<pre>$res</pre>";
		return $res;
		//return print_r($s,1);
	}
	function render_registration($s){
		//return render_registration_parsed($s);
		return render_registration_raw($s);
	}
	function render_registration_parsed($s){
		$w = $s->WhoisRecord;
		if($w){
			
		}
	}
	function render_registration_raw($s){
		$res="";
		$w = $s->WhoisRecord;
		if($w){
			$r = $w->registryData;
			if($r){
				$res = render_text($r);
			}

		}
		
		
		$res = trim($res);
		$res = "<pre>$res</pre>";
		return $res;
	}
	function render_text($w, $raw=0){
		global $whois_email_link;
		if(!$w)return "";
		$res = "";
		if($raw){
			$res=$w->rawText;
		}
		else{ 
			$res = $w->strippedText;
			$res=trim($res);
			if(empty($res) && $w->rawText){
				$res = trim($w->rawText);
			}
		}
		return linkify_str($w, $res, $whois_email_link);
		
	}
	function invalidWhois($json){
		if(!$json)return true;
		if(!$json->WhoisRecord)return true;
		return false;
			
	}
	function render_error($json, $domain_name){
		$errorMsg = $json->ErrorMessage;
		if($errorMsg)return $errorMsg->msg;
		return "whois lookup for $domain_name yields no result.";
	}
?>
<?php if(invalidWhois($json)){
	$tmp = render_error($json,$q);
	
	throw new Exception($tmp);
	
}
else{?>
<div id="tab-container-1" class="demolayout" >

    <ul id="tab-container-1-nav" class="demolayout">
    <li><a href="#tab1">Whois Record</a></li>
    <li><a href="#tab2">Registration</a></li>
    </ul>
	<div class="tabs-container">
    <div class="tab" id="tab1" >
    	<?php 
    		
    		echo render_whois($json);
    	?>
   
    </div>
    
    <div class="tab" id="tab2">
 	 <?php 
    		
    		echo render_registration($json);
    	?>
    </div>
    </div>
</div>
<script type="text/javascript">
var tabber1 = new Yetii({
id: 'tab-container-1'
});
</script>	
<?}?>
<?php
echo "";
	if($err)throw new Exception($err); //for cache manager(whois_recordc)  to know not to save it
	
?>

