<?php

require_once __DIR__ . "/constant.php";

function validate_since_date($max_days_back){
	$since_date=$_REQUEST['since_date'];
	if(!DateUtil::checkDateFormat($since_date)){
		output_error(INVALID_SINCE_DATE, "invalid since_date format, the correct date format is YYYY-MM-dd (eg. 2012-04-01)");
		return false;
	}
	$since_date_unix_time = strtotime($since_date);
	$today_unix_time = strtotime(date("Y-m-d"));
	if($today_unix_time-$since_date_unix_time > $max_days_back * 24 * 3600){
		output_error(OUT_OF_RANGE_SINCE_DATE, "since_date $since_date is more than $max_days_back days ago.");
		return false;
	}
	return true;
	
}
function validate_days_back($max_days_back){
	$days_back=$_REQUEST['days_back'];
	if(!StringUtil::is_nonnegative_integer($days_back)){
		output_error(INVALID_DAYS_BACK, "days_back must be a postive integer.");
		return false;
	}
	else if($days_back>$max_days_back){
		output_error(OUT_OF_RANGE_DAYS_BACK, "days_back is more than $max_days_back days ago.");
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
function validate_search_terms($max_search_terms){
	if(all_search_terms_empty($max_search_terms)){
		output_error(ALL_SEARCH_TERMS_EMPTY, "seach term can not be empty");
		return false;
	}
	return true;
	
}
function all_search_terms_empty($max_search_terms){
  for($i=1;$i<=$max_search_terms;$i++){
    
    if(isset($_REQUEST["term$i"]) && trim($_REQUEST["term$i"])){
      return false;
    }
    if(isset($_REQUEST["exclude_term$i"]) && trim($_REQUEST["exclude_term$i"])){
      return false;
    }    
  }
  return true;
}
?>