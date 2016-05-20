<?php 
require_once __DIR__."/../util/date_util.php";
require_once __DIR__."/../models/user.php";
require_once __DIR__."/../manager/AccountManager.php";

$MAX_SEARCH_RESULT=500;
$MAX_DAYS_BACK=12;
$MAX_SEARCH_TERMS = 4;
$time = microtime(true);

require_once 'XML/Serializer.php';
require_once dirname(__FILE__) . "/config.php";

require_once __DIR__ . "/../api-commons/constant.php";
require_once(__DIR__ ."/registrant_alert.php");
require_once(__DIR__ ."/validation.php");
require_once(__DIR__ ."/../api-commons/io.php");
require_once(__DIR__ ."/../reverse-whois-api/report.php");

?>





<?php


$output_format='json';
if(isset($_REQUEST['output_format']))$output_format=$_REQUEST['output_format'];

if(isset($_REQUEST['since_date']))$since_date=$_REQUEST['since_date'];

if(isset($_REQUEST['domain_status']))$domain_status=$_REQUEST['domain_status'];

if(isset($_REQUEST['days_back']))$days_back=$_REQUEST['days_back'];
?>

<?php
if(!validate_input())return;
if(!$since_date && !$days_back)$days_back=1;

$limit=$MAX_SEARCH_RESULT;


$search_terms = get_search_terms_from_request($MAX_SEARCH_TERMS);

if(empty($search_terms) || (!$search_terms['include'] && !$search_terms['exclude'])){
  echo '';
  return;
}

$start = $limit*$page - $limit; 
if($start<0)$start=0;


$res = '';
$options = array( 'start'=>'0', 'limit'=>$limit,  'search_terms'=>$search_terms, 'since_date'=>$since_date, 'days_back'=>$days_back);

	
		list($error_code, $error_msg) = AccountManager::insufficientUserCredit($_REQUEST['username'], 2, 'ra_query_balance');
		
		if($error_code){
			if($error_code == -1)
				output_error(INSUFFICIENT_CREDIT, "account ".$_REQUEST['username']. " has insufficient credit to perform this registrant alert query.  Please refill.");
			else output_error(GENERAL_DB_ERR, " unable to retrieve registrant alert query balance for account ".$_REQUEST['username'].".  Please try again later."); 
			return;
		}
		
		//$res = RegistrantAlert::getDomains($options);
		$res = Report::get_current_whois_records($options,true);
		if($res->records > 0){
			list($error_code, $error_msg) = AccountManager::deductUserAccountCredit($_REQUEST['username'], 2, 'ra_query_balance',1);
			
			//print_r($ret);
		}

 	

if(is_object($res)){
  

  if(isset($SHOW_QUERY_TIME)){
  	$res->time= number_format(microtime(true) - $time, 2);
  }
 	if(strcasecmp($output_format, 'xml')){
 		 output_json($res);
 	}
 	else output_xml($res);	
 
}
echo '';
  
?>
