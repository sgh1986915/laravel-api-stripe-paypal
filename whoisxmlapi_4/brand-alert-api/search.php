<?php 
require_once __DIR__."/../util/date_util.php";
require_once __DIR__."/../models/user.php";
require_once __DIR__."/../manager/AccountManager.php";

//set_time_limit(100);
$MAX_DAYS_BACK=12;
$MAX_SEARCH_TERMS = 4;
$time = microtime(true);


require_once 'XML/Serializer.php';
require_once dirname(__FILE__) . "/config.php";

//require_once(__DIR__ ."/../util.php");
//require_once(__DIR__ ."/prices.php");
require_once(__DIR__ ."/brand_alert.php");

define ('ALL_SEARCH_TERMS_EMPTY', 10);
define ('INVALID_LOGIN', 20);
define ('INSUFFICIENT_CREDIT', 30);
define ('INVALID_SINCE_DATE', 40);
define ('OUT_OF_RANGE_SINCE_DATE', 41);
define ('INVALID_DAYS_BACK', 42);
define ('OUT_OF_RANGE_DAYS_BACK', 43);
define ('GENERAL_DB_ERR', 100);

?>
<?php
function validate_input(){
	if(!validate_user_account())return false;
	if(!validate_search_terms())return false;
	if(!validate_other_inputs())return false;
	
	return true;
}
function validate_other_inputs(){
	if(isset($_REQUEST['since_date'])){
		if(!validate_since_date()){
			return false;
		}
	}
	else if(isset($_REQUEST['days_back'])){
		if(!validate_days_back()){
			return false;
		}
	}
	return true;
	
}
function validate_since_date(){
	global $MAX_DAYS_BACK;
	$since_date=$_REQUEST['since_date'];
	if(!DateUtil::checkDateFormat($since_date)){
		output_error(INVALID_SINCE_DATE, "invalid since_date format, the correct date format is YYYY-MM-dd (eg. 2012-04-01)");
		return false;
	}
	$since_date_unix_time = strtotime($since_date);
	$today_unix_time = strtotime(date("Y-m-d"));
	if($today_unix_time-$since_date_unix_time > $MAX_DAYS_BACK * 24 * 3600){
		output_error(OUT_OF_RANGE_SINCE_DATE, "since_date $since_date is more than $MAX_DAYS_BACK days ago.");
		return false;
	}
	return true;
	
}
function validate_days_back(){
	global $MAX_DAYS_BACK;
	$days_back=$_REQUEST['days_back'];
	
	if(!StringUtil::is_nonnegative_integer($days_back)){
		output_error(INVALID_DAYS_BACK, "days_back must be a postive integer.");
		return false;
	}
	else if($days_back>$MAX_DAYS_BACK){
		output_error(OUT_OF_RANGE_DAYS_BACK, "days_back is more than $MAX_DAYS_BACK days ago.");
		return false;
	}
	return true;
}
function validate_user_account(){
	$username = isset($_REQUEST['username'])?$_REQUEST['username']:false;
	$password = isset($_REQUEST['password'])?$_REQUEST['password']:false;
	if(strlen($username)<4 && strlen($password)<4){
		output_error(INVALID_LOGIN, "invalid username and password combination");
		return false;	
	}
	$user = new WUser($username, $password);
	
	list($error_code, $error_msg) = $user->validate_user();
	if($error_code !== false){
		output_error(INVALID_LOGIN, $error_msg);
		return false;
	}
	return true;
}
function validate_search_terms(){
	if(all_search_terms_empty()){
		output_error(ALL_SEARCH_TERMS_EMPTY, "seach term can not be empty");
		return false;
	}
	return true;
	
}
function all_search_terms_empty(){
  global $MAX_SEARCH_TERMS;
 
  for($i=1;$i<=$MAX_SEARCH_TERMS;$i++){
    
    if(isset($_REQUEST["term$i"]) && trim($_REQUEST["term$i"])){
      return false;
    }
    if(isset($_REQUEST["exclude_term$i"]) && trim($_REQUEST["exclude_term$i"])){
      return false;
    }    
  }
  return true;
}
function limit_max_search($res){
	global $MAX_DOMAIN_SEARCH_MATCH;
	
}
?>



<?php
	
function output_json($s){
	echo json_encode($s);
}
function output_xml($s){
	$options = array(
  XML_SERIALIZER_OPTION_INDENT        => '    ',
  XML_SERIALIZER_OPTION_RETURN_RESULT => true,
   "encoding"        => "UTF-8",
   "rootName"=>"response",
   "defaultTagName"=>"item"
  );
 
	$serializer = new XML_Serializer($options);
	$str = $serializer->serialize($s);
	header ("content-type: text/xml");
	echo $str;
}
?>


<?php
global $_debug_noncryptic_;
global $MAX_DOMAIN_SEARCH_MATCH;

$output_format='json';
if(isset($_REQUEST['output_format']))$output_format=$_REQUEST['output_format'];

if(isset($_REQUEST['since_date']))$since_date=$_REQUEST['since_date'];

if(isset($_REQUEST['domain_status']))$domain_status=$_REQUEST['domain_status'];

if(isset($_REQUEST['days_back']))$days_back=$_REQUEST['days_back'];
?>
<?php
	//output functions
	function output_error($error_code, $msg){
		global $output_format;
		$res = array('ErrorMessage'=>array('error_code'=>$error_code, 'msg'=>$msg));
		if(strcasecmp('xml',$output_format)==0) output_xml($res);
		else output_json($res);
	}
?>
<?php
if(!validate_input())return;

$page = $_GET['page']; // get the requested page 
$limit = $_GET['rows']; // get how many rows we want to have into the grid 
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort 
$sord = $_GET['sord']; // get the direction 
if(!$sidx) $sidx = 'id'; 
if(!$page)$page=1;
if(!$limit)$limit=$MAX_DOMAIN_SEARCH_MATCH;



$search_terms = BrandAlert::get_search_terms_from_request($MAX_SEARCH_TERMS);

if(empty($search_terms) || (!$search_terms['include'] && !$search_terms['exclude'])){
  echo '';
  return;
}

$start = $limit*$page - $limit; 
if($start<0)$start=0;


//echo "limit is $limit, page is $page";
// do not put $limit*($page - 1) 
//$SQL = "select domain_name from whois_index where match ('" . $term. "') ORDER BY $sidx $sord LIMIT $start , $limit";

$res = '';
$options = array( 'start'=>$start,'page'=>$page,'limit'=>$limit,  'search_terms'=>$search_terms, 'since_date'=>$since_date, 'days_back'=>$days_back, 'domain_status'=>$domain_status);

	
		list($error_code, $error_msg) = AccountManager::insufficientUserCredit($_REQUEST['username'], 2, 'ba_query_balance');
		
		if($error_code){
			if($error_code == -1)
				output_error(INSUFFICIENT_CREDIT, "account ".$_REQUEST['username']. " has insufficient credit to perform this brand alert query.  Please refill.");
			else output_error(GENERAL_DB_ERR, " unable to retrieve brand alert query balance for account ".$_REQUEST['username'].".  Please try again later."); 
			return;
		}
		
		$res = BrandAlert::getDomains($options);
		
		
		if($res->total > 0){
			list($error_code, $error_msg) = AccountManager::deductUserAccountCredit($_REQUEST['username'], 2, 'ba_query_balance',1);
			
			//print_r($ret);
		}

 	

if(is_object($res)){
  
  limit_max_search($res);
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
