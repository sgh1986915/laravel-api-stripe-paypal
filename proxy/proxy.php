<?php
header("Access-Control-Allow-Origin: *");

$reqStartTime = microtime(true);

/* Debug and Error Logging */
define('DEBUG_MODE', true);
define('LOG_MODE', true);
define('LOG_DIRECTORY', dirname(__FILE__).DIRECTORY_SEPARATOR.'logs');
define('LOG_FILE', LOG_DIRECTORY.DIRECTORY_SEPARATOR.'php_error.log');

date_default_timezone_set('UTC');
@ini_set('error_log', LOG_FILE);
@ini_set('error_reporting', E_ALL | E_STRICT);
@ini_set('log_errors', 1);
if(DEBUG_MODE) {
	@ini_set('display_errors', 1);
} else {
	@ini_set('display_errors', 0);
}
/* ------- */

/* DB config */
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'p1ssw2rd');
define('DB_NAME', 'whoisapi');
/* ------- */


/* API User config */
define('API_USERNAME', 'mobileapp_dev');
define('API_PASSWORD', '3FEwtQgWqFDDSAWrdB1GedFS4WAA');
/* ------- */

/* Request config */
define('REQ_TIMEOUT',60000); // in milliseconds
/* ------- */


if(empty($_POST['action']) || empty($_POST['requestObject']) || empty($_POST['digest'])) {
	errorLog("No Request Action: \nTiming:  ".getReqTime($reqStartTime)."\n","Request_timing.log");
	exit('Nothing to look here !!!');
}

$API_URLs = array('whoislookup' => 'https://www.whoisxmlapi.com/whoisserver/WhoisService', 'reverselookup' => 'https://www.whoisxmlapi.com/reverse-whois-api/search.php');

$postData = array();
$response = array();
$action = $_POST['action'];

$requestObject = json_decode($_POST['requestObject'],true);
$requestObject['username'] = API_USERNAME;
$requestObject['password'] = API_PASSWORD;

/* Authenticate Request */
validateRequest();

switch ($action) {

	case 'whoislookup':
	$requestObject =  json_decode($_POST['requestObject'],true);
	$postData = array("requestObject" => $_POST['requestObject'],"digest" => $_POST['digest'],'domainName' => $requestObject['domainName'],'outputFormat' => $requestObject['outputFormat']);
	$response = doRequest($action, $postData);
	break;

	case 'reverselookup':
	$requestObject['mode'] = 'purchase';
	$response = doRequest($action,$requestObject);
	break;

	default:
	$response = array("ErrorMessage" => array("error_code" => 300,"msg"=>"Invalid request"));
	break;
}

echo json_encode($response);

errorLog("Request Action: ".$action."\nTiming:  ".getReqTime($reqStartTime)."\n","Request_timing.log");
exit();

/* Send Request to API */
function doRequest($action,$postData = array()) {
	global $API_URLs;
	$output = false;
	$url = $API_URLs[$action];
	$cPostData = http_build_query($postData);

	if (function_exists('curl_version')) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch,CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST, count($postData));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $cPostData);
		$output = curl_exec($ch);
		$res_error = curl_error($ch);
		$res_errorno = curl_errno($ch);
		$res_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close ($ch);
	}
	if($output === false){
		$ErrorMessage = array("ErrorMessage" => array("error_code" => $res_status,"msg"=>"Something went wrong. Please try again later.",'Req_URL' => $url, 'Param sent' => $postData ,'Res' => json_decode($output,true)));
		if(DEBUG_MODE) {
			$ErrorMessage['error'] = $res_error;
			$ErrorMessage['errorno'] = $res_errorno;
		}
		errorLog("\nResponse: ".print_r($ErrorMessage,true)."\n", "API_Response.log");
		exit(json_encode($ErrorMessage));
	}
	$output = iconv('UTF-8', 'UTF-8//IGNORE', utf8_encode($output));
	$temp = json_decode($output,true);

	if(DEBUG_MODE) {
		$temp['Debug'] = array('Req_URL' => $url, 'Param sent' => $postData ,'Res' => json_decode($output,true));
	}

	errorLog("\nResponse: ".print_r($temp['Debug'],true)."\n", "API_Response.log");

	return $temp;
}

function errorLog($data,$fname = '') {
	$fname = (empty($fname)) ? LOG_FILE : LOG_DIRECTORY.DIRECTORY_SEPARATOR.$fname;
	if(LOG_MODE) {
		if (!is_dir(LOG_DIRECTORY)) {
			mkdir(LOG_DIRECTORY, 0777, true);
		}
		$fname = preg_replace('/.([^.]*)$/', date('_m_d_Y').'.$1', $fname);
		error_log("[".date('m/d/Y h:i:s a', time()+(5.5*60*60))."]\n".$data."\n", 3, $fname);
	}
}

function getReqTime($reqStartTime) {
	return number_format(microtime(true) - $reqStartTime,10);
}

function getUserIP() {
	$client  = @$_SERVER['HTTP_CLIENT_IP'];
	$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	$remote  = $_SERVER['REMOTE_ADDR'];

	if(filter_var($client, FILTER_VALIDATE_IP)) {
		$ip = $client;
	} elseif(filter_var($forward, FILTER_VALIDATE_IP)) {
		$ip = $forward;
	} else {
		$ip = $remote;
	}

	return $ip;
}

/* Usage */
// $user_ip = getUserIP();

/* Validate Request */
function validateRequest() {
	global $reqStartTime,$requestObject;
	$PRIVATE_KEY = '';
	/*if($conn = @mysql_connect(DB_HOST,DB_USERNAME,DB_PASSWORD)) {
		mysql_select_db(DB_NAME, $conn);
		$sql = "SELECT secret_key FROM `api_keys`as a join users as u on a.`userid`= u.id where u.username = '".API_USERNAME."' and a.is_active = 1 and a.api_key = '".mysql_real_escape_string($requestObject['ApiKey'])."' limit 1";
		$query = mysql_query($sql);
		$row = mysql_fetch_assoc($query);
		$PRIVATE_KEY = $row['secret_key'];
	} else {
		errorLog("DB Error: \nTiming:  ".getReqTime($reqStartTime)."\n","Request_timing.log");
		exit(json_encode(array("ErrorMessage" => array("error_code" => 300,"msg"=>"Something went wrong. Please try again later."))));
	}*/

	$PRIVATE_KEY = 'MIIBVQIBADANBgkqhkiG9w0BAQEFAASCAT8wggE7AgEAAkEAu33dSrSWA7yNY/qu6WxPt7eh3Hj75jrtQbzUvjKkzpjHL+l34DjaTmxm8AhuHkNUsop9RSgtLxnw6i5Bm2mU7QIDAQABAkBY2u6MIAdUYACWGFDauQUSqUlhZkjjNJwKYoZkWTX33mjip4I/omgpW7VoEqU6fdtGPP4zNtcGw6EZPms2wFJVAiEA9KzR9kfwzm8H2+WeEF1z6lVWDTf3yq6AoQxi5h5/LhMCIQDEK3jblX13hzu8r7MPvwVbBBgjVGtXLB4KRM/DZfCQ/wIhAMC/VDgbvEwjk6FbZgWmWSaFS2DmckIs7g/w3ghChhYZAiBu+bTWbDxdaTinJrJivwq1kZxiKDjNSNz5rDHo9XthWwIhAN71JtvWDWi/zEVasS+N5ETF2GtELqUWETR6uBKicmLm';

	$digest = hash_hmac('md5', $_POST['requestObject'], $PRIVATE_KEY);
	if($_POST['digest'] != $digest) {
		errorLog("Invalid Request(Digest error): \nTiming:  ".getReqTime($reqStartTime)."\n","Request_timing.log");
		exit(json_encode(array("ErrorMessage" => array("error_code" => 401,"msg"=>"Invalid request."))));
	}
	if(empty($requestObject['TimeStamp']) || ((intval($reqStartTime) * 1000) - $requestObject['TimeStamp'] > REQ_TIMEOUT)) {
		errorLog("Invalid Request(TimeStamp error):\n reqStartTime: ".(intval($reqStartTime) * 1000)."\n req TimeStamp: ".($requestObject['TimeStamp'])."\n Difference: ".((intval($reqStartTime) * 1000) - $requestObject['TimeStamp'])." \nTiming:  ".getReqTime($reqStartTime)."\n","Request_timing.log");
		exit(json_encode(array("ErrorMessage" => array("error_code" => 408,"msg"=>"Invalid request."))));
	}
}
?>