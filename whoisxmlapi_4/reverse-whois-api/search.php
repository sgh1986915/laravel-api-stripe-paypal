<?php require_once __DIR__."/model/user.php";
require_once __DIR__."/manager/AccountManager.php";

//set_time_limit(100);
$SET_RATE_LIMIT=0;
$MAX_SEARCH_TERMS = 4;
$time = microtime(true);

if($SET_RATE_LIMIT){
	require_once __DIR__ ."/../rate_limit/RateLimit.php";
}

require_once 'XML/Serializer.php';

if(isset($V2))require_once dirname(__FILE__) . "/../reverse-whois-api-v2/config.php";
  else if(isset($V1)){ 
  	require_once dirname(__FILE__) . "/../reverse-whois-api-v1/config.php";
  }
else {
	require_once dirname(__FILE__) . "/../reverse-whois-api/config.php";
}

require_once(__DIR__ ."/../util.php");
require_once(__DIR__ ."/prices.php");
require_once(__DIR__ ."/../reverse-whois-api/report.php");

define ('ALL_SEARCH_TERMS_EMPTY', 10);
define ('INVALID_LOGIN', 20);
define ('INSUFFICIENT_RW_CREDIT', 30);
define ('GENERAL_DB_ERR', 100);
define ('RATE_LIMIT_ERR', 101);

?>
<?php
function check_rate_limit(){
	$type="reverse_whois";
	$username=$_REQUEST['username'];
	if(RateLimit::checkRateLimit($type, $username))
		RateLimit::incrUsage($type, $username);
	else{
		output_error(RATE_LIMIT_ERR, "$username has exceeded rate limit of ". RateLimit::getRateLimitPolicy($type, $username));
		exit;
	}
}
function validate_input(){
	if(!validate_user_account())return false;
	if(!validate_search_terms())return false;
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
	if($res->stats){
		$stats = $res->stats;
		$recomp=false;
		if(isset($stats['current_total_count']) && $stats['current_total_count'] > $MAX_DOMAIN_SEARCH_MATCH){
			$res->stats['current_total_count'] = $MAX_DOMAIN_SEARCH_MATCH;	
			$recomp=true;		
		}
		if(isset($stats['history_total_count']) && $stats['history_total_count'] > $MAX_DOMAIN_SEARCH_MATCH){
			$res->stats['history_total_count'] = $MAX_DOMAIN_SEARCH_MATCH;			
			$recomp=true;
		}		
		if($recomp){
			$res->stats['current_report_price'] = compute_current_report_price(array(
                                      'current_total_count'=>$res->stats['current_total_count']
                              ));
			$res->stats['history_report_price'] = compute_history_report_price(array('history_total_count'=>$res->stats['history_total_count'],
                                      'current_total_count'=>$res->stats['current_total_count']
                                      ));               
		}
		
	}
}
?>



<?php
	
function output_json($s){
	header("content-type: application/json");
	
	echo json_encode($s);
}
function output_xml($s){
	$options = array(
  XML_SERIALIZER_OPTION_INDENT        => '    ',
  XML_SERIALIZER_OPTION_RETURN_RESULT => true,
   "encoding"        => "UTF-8",
   "rootName"=>"ReverseWhois",
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

$search_type = 'current';
if(isset($_REQUEST['search_type'])) $search_type = $_REQUEST['search_type'];
$mode = 'preview';
if(isset($_REQUEST['mode'])) $mode=$_REQUEST['mode'];
$output_format='json';
if(isset($_REQUEST['output_format']))$output_format=$_REQUEST['output_format'];

if(isset($_REQUEST['since_date']))$since_date=$_REQUEST['since_date'];

?>
<?php
	//output functions
	function output_error($error_code, $msg){
		global $output_format;
		$res = array('ErrorMessage'=>array('error_code'=>$error_code, 'msg'=>$msg));
		if(strcasecmp('xml',$output_format)==0) output_xml($res);
		else output_json($res);
	}
	function get_sample_report($search_terms){
		$r== new StdClass;
			$r->domains =array("whoisxmlapi.com","domainwhoisdatabase.com", "bestwhois.org");
			$r->records = "3";
			$r->stats = array(
				'current_total_count' => "3",
				'history_total_count' => 0,
				'current_report_price' => 19,
				'history_report_price' => 19,
				'historic_price_discount' => 0,
				'current_price_discount' => 0
			);
			$r->search_terms=$search_terms; 

		
		return $r;
	
	}
?>
<?php
if($mode == 'sample_purchase'){
	$res= get_sample_report($search_terms);
	output_res($res);
	return;
}
if(!validate_input())return;

$page = $_GET['page']; // get the requested page 
$limit = $_GET['rows']; // get how many rows we want to have into the grid 
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort 
$sord = $_GET['sord']; // get the direction 
if(!$sidx) $sidx = 'id'; 
if(!$page)$page=1;
if(!$limit)$limit=$MAX_DOMAIN_SEARCH_MATCH;

// connect to the database 
if(!connect_to_whoiscrawler_index_db())return;



$search_terms = Report::get_search_terms_from_request($MAX_SEARCH_TERMS);

if(empty($search_terms) || (!$search_terms['include'] && !$search_terms['exclude'])){
  echo '';
  return;
}

$start = $limit*$page - $limit; 
if($start<0)$start=0;


//echo "limit is $limit, page is $page";
// do not put $limit*($page - 1) 
//$SQL = "select domain_name from whois_index where match ('" . $term. "') ORDER BY $sidx $sord LIMIT $start , $limit";
#currently search_type is ignored, it always returns current and historic results
$res = '';
$options = array( 'start'=>$start,'page'=>$page,'limit'=>$limit, 'search_type'=>$search_type, 'search_terms'=>$search_terms, 'since_date'=>$since_date, 'days_back'=>$_REQUEST['days_back'], 'show_whois'=>$_REQUEST['show_whois']);

if($SET_RATE_LIMIT){
	check_rate_limit();
}
	if($mode=='purchase') {
		
		list($error_code, $error_msg) = AccountManager::insufficientRWUserCredit($_REQUEST['username'],1);
		
		if($error_code){
			if($error_code == -1)
				output_error(INSUFFICIENT_RW_CREDIT, "account ".$_REQUEST['username']. " has insufficient credit to perform this reverse whois lookup.  Please refill.");
			else output_error(GENERAL_DB_ERR, " unable to retrieve reverse whois balance for account ".$_REQUEST['username'].".  Please try again later."); 
			return;
		}
	
		if($search_type=='historic'){ 
			$res = Report::get_history_whois_records($options, true);
		}
		else $res = Report::get_current_whois_records($options,true);
		
		if($res->records  > 0){
			$num_credits=Report::compute_credits_required($res->records);
			list($error_code, $error_msg) = AccountManager::insufficientRWUserCredit($_REQUEST['username'], $num_credits);
		
			if($error_code){
				if($error_code == -1)
					output_error(INSUFFICIENT_RW_CREDIT, "account ".$_REQUEST['username']. " has insufficient credit to perform this reverse whois lookup.  Please refill.");
				else output_error(GENERAL_DB_ERR, " unable to retrieve reverse whois balance for account ".$_REQUEST['username'].".  Please try again later."); 
				return;
			}
		
			list($error_code, $error_msg) = AccountManager::deductRWUserCredit($_REQUEST['username'], $num_credits);
			if($error_code){
				if($error_code == -1)
					output_error(INSUFFICIENT_RW_CREDIT, "account ".$_REQUEST['username']. " has insufficient credit to perform this reverse whois lookup.  Please refill.");
				else output_error(GENERAL_DB_ERR, " unable to deduct reverse whois balance for account ".$_REQUEST['username'].".  Please try again later."); 
				return;
			}			
		}
	}
	
  	else {
	  /*
	  list($error_code, $error_msg) = AccountManager::insufficientRWUserCredit($_REQUEST['username']);
		
		if($error_code){
			if($error_code == -1)
				output_error(INSUFFICIENT_RW_CREDIT, "account ".$_REQUEST['username']. " has insufficient credit to perform this reverse whois lookup.  Please refill.");
			else output_error(GENERAL_DB_ERR, " unable to retrieve reverse whois balance for account ".$_REQUEST['username'].".  Please try again later."); 
			return;
		}
	  */
  		if ($search_type=='historic') $res = Report::get_historic_quote($options);
  		else $res = Report::get_current_quote($options);
		/*	
		if($res->records  > 0){
			list($error_code, $error_msg) = AccountManager::deductRWUserCredit($_REQUEST['username'], 1);
			if($error_code){
				if($error_code == -1)
					output_error(INSUFFICIENT_RW_CREDIT, "account ".$_REQUEST['username']. " has insufficient credit to perform this reverse whois lookup.  Please refill.");
				else output_error(GENERAL_DB_ERR, " unable to deduct reverse whois balance for account ".$_REQUEST['username'].".  Please try again later."); 
				return;
			}			
		} 
		*/
  	}
 	//print_r($res);

	output_res($res, array_merge(array('time'=>$time, 'output_format'=>$output_format), $options));
  
?>
<?php
	function output_res($res, $options){
		global $SHOW_QUERY_TIME;
		if(is_object($res)){
			$search_type=$options['search_type'];
			$output_format=$options['output_format'];
			$time=$options['time'];
  			$res->search_type = $search_type;
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
	}