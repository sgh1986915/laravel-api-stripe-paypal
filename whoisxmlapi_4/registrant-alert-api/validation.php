<?php 
require_once(__DIR__."/../api-commons/validation.php");
function validate_input(){
	global $MAX_SEARCH_TERMS;
	if(!validate_user_account())return false;
	if(!validate_search_terms($MAX_SEARCH_TERMS))return false;
	if(!validate_other_inputs())return false;
	return true;
}
function validate_other_inputs(){
	global $MAX_DAYS_BACK;
	if(isset($_REQUEST['since_date'])){
		if(!validate_since_date($MAX_DAYS_BACK)){
			return false;
		}
	}
	else if(isset($_REQUEST['days_back'])){
		if(!validate_days_back($MAX_DAYS_BACK)){
			return false;
		}
	}
	return true;
	
}

?>
