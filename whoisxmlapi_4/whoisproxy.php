<?php 
	
	require_once "util.php";
	require_once "httputil.php";
	require_once "users/users.inc";
	require_once "setup.php";
	global $WHOIS_SERVICE_URL;
	
	$param = array();
    $domainName = get_from_post_get("domainName");
	$outputFormat = get_from_post_get("outputFormat");
	if($domainName) $param['domainName'] = $domainName;
	if($outputFormat) $param['outputformat'] = $outputFormat;
	my_session_start();
	if(isset($_SESSION['myuser'])){
		$user = $_SESSION['myuser'];
		$param['userName'] = $user>username;
		$param['password'] = $user->password;
	}
	
	
	$query = false;
	if(count($param) > 0) $query = http_build_query($param);
	
	$url = $WHOIS_SERVICE_URL. ($query ? "?".$query:"");
	$content = file_get_contents($url);
	
	if(strcasecmp("json", $outputFormat) == 0)
		Header("Content-type: text/html; charset=UTF-8");
	else Header("Content-type: text/xml; charset=UTF-8");
	
	echo($content);
	
?>
