<?php

function db_param_struct($db, $username, $password, $host){
	return array('db'=>$db, 'username'=>$username,'password'=>$password, 'db_host'=>$host);
}
		
function connect_to_whoiscrawler_index_db() {
  global $DBHOST,$DB, $DBUSER, $DBPASS;

  if (!mysql_connect($DBHOST, $DBUSER, $DBPASS)) {
    // couldn't connect
    error_log( "could not connect ($DBHOST)");  
    return false;
  }
  
  return true;
}
function connect_to_whoiscrawler_index_db_i() {
  global $DBHOST,$DB, $DBUSER, $DBPASS;

  $link = mysqli_connect($DBHOST, $DBUSER, $DBPASS);
  if ( mysqli_connect_errno() )die ( "connect failed: " . mysqli_connect_error() );
  return $link;
}

function connect_to_rw_whoiscrawler_db($whois_record_id=0) {
  global $RW_WHOISCRAWLER_DBS;
  
	$cur_db_info=$RW_WHOISCRAWLER_DBS['0'];	
	$prev_val=$RW_WHOISCRAWLER_DBS['0'];
	foreach($RW_WHOISCRAWLER_DBS as $min_id=>$db_info){
	
		if($whois_record_id<$min_id){
			
			$cur_db_info=$prev_val;
			break;
		}
		$prev_val=$db_info;
	}
	//print_r($cur_db_info);
  $res = mysql_connect($cur_db_info['db_host'], $cur_db_info['username'], $cur_db_info['password']);
  
  if (!$res) {
    // couldn't connect
    error_log( "could not connect (" . $cur_db_info['db_host'] . ")");  
    return false;
  }
  if (!mysql_select_db($cur_db_info['db'])) {
    // couldn't connect
    error_log("could not select (" . $cur_db_info['db'] .")");  
    
    return false;
  }

  return $res;
}
/*
connect_to_rw_whoiscrawler_db(1000);
echo "<br/>--";
connect_to_rw_whoiscrawler_db(3100000001);
echo "<br/>--";
connect_to_rw_whoiscrawler_db(999100000000);
*/

function connect_to_rw_whoisapi_db(){
  global $RW_WHOISAPI_DBHOST,$RW_WHOISAPI_DB, $RW_WHOISAPI_DBUSER, $RW_WHOISAPI_DBPASS;
 
  $res = mysql_connect($RW_WHOISAPI_DBHOST, $RW_WHOISAPI_DBUSER, $RW_WHOISAPI_DBPASS);
  if (!$res) {
    // couldn't connect
    error_log( "could not connect ($RW_WHOISAPI_DBHOST)");  
    return false;
  }
  if (!mysql_select_db($RW_WHOISAPI_DB)) {
    // couldn't connect
    error_log("could not select ($RW_WHOISAPI_DB)");  
    return $res;
  }
  return $res;	
}
?>