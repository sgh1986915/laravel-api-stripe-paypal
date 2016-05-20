<?php

function ra_db_param_struct($db, $username, $password, $host){
	return array('db'=>$db, 'username'=>$username,'password'=>$password, 'db_host'=>$host);
}
		
function connect_to_registrant_alert_index_db() {
  global $RA_INDEX_DBHOST,$RA_INDEX_DB, $RA_INDEX_DBUSER, $RA_INDEX_DBPASS;

  if (!mysql_connect($RA_INDEX_DBHOST, $RA_INDEX_DBUSER, $RA_INDEX_DBPASS)) {
    // couldn't connect
    error_log( "could not connect ($RA_INDEX_DBHOST)");  
    return false;
  }
  
  return true;
}
/*
function connect_to_whoiscrawler_index_db_i() {
  global $DBHOST,$DB, $DBUSER, $DBPASS;

  $link = mysqli_connect($DBHOST, $DBUSER, $DBPASS);
  if ( mysqli_connect_errno() )die ( "connect failed: " . mysqli_connect_error() );
  return $link;
}
*/


function connect_to_registrant_alert_user_db(){//correct this later
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