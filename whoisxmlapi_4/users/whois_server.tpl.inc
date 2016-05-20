<?php
# database connect information
$WHOISSERVER_DB     = "@WHOISSERVER_DB@";
$WHOISSERVER_DBUSER = "@WHOISSERVER_DBUSER@";
$WHOISSERVER_DBPASS = "@WHOISSERVER_DBPASS@";
$WHOISSERVER_DBHOST = "@WHOISSERVER_DBHOST@";

$WHOISCRAWLER_DB     = "@WHOISCRAWLER_DB@";
$WHOISCRAWLER_DBUSER = "@WHOISCRAWLER_DBUSER@";
$WHOISCRAWLER_DBPASS = "@WHOISCRAWLER_DBPASS@";
$WHOISCRAWLER_DBHOST = "@WHOISCRAWLER_DBHOST@";

$WHOISSERVER_DB_MAP=array(
	//'demo'=>array('db_host'=>'@WHOISSERVER_DBHOST2@', 'db'=>'@WHOISSERVER_DB2@', 'db_user'=>'@WHOISSERVER_DBUSER2@', 'db_pass'=>'@WHOISSERVER_DBPASS2@')
	//,'paulinternet'=>array('db_host'=>'@WHOISSERVER_DBHOST2@', 'db'=>'@WHOISSERVER_DB2@', 'db_user'=>'@WHOISSERVER_DBUSER2@', 'db_pass'=>'@WHOISSERVER_DBPASS2@')
	//,'securence'=>array('db_host'=>'@WHOISSERVER_DBHOST2@', 'db'=>'@WHOISSERVER_DB2@', 'db_user'=>'@WHOISSERVER_DBUSER2@', 'db_pass'=>'@WHOISSERVER_DBPASS2@')
	//,'riskiqdev'=>array('db_host'=>'@WHOISSERVER_DBHOST2@', 'db'=>'@WHOISSERVER_DB2@', 'db_user'=>'@WHOISSERVER_DBUSER2@', 'db_pass'=>'@WHOISSERVER_DBPASS2@')
	//,'sharpspring'=>array('db_host'=>'@WHOISSERVER_DBHOST2@', 'db'=>'@WHOISSERVER_DB2@', 'db_user'=>'@WHOISSERVER_DBUSER2@', 'db_pass'=>'@WHOISSERVER_DBPASS2@')
	//,'sedoproduction'=>array('db_host'=>'@WHOISSERVER_DBHOST2@', 'db'=>'@WHOISSERVER_DB2@', 'db_user'=>'@WHOISSERVER_DBUSER2@', 'db_pass'=>'@WHOISSERVER_DBPASS2@')
	//,'johnkey'=>array('db_host'=>'@WHOISSERVER_DBHOST2@', 'db'=>'@WHOISSERVER_DB2@', 'db_user'=>'@WHOISSERVER_DBUSER2@', 'db_pass'=>'@WHOISSERVER_DBPASS2@')
	

);

function get_whoisserver_db_server($account_username=false){
	global $WHOISSERVER_DB_MAP;
	if($account_username){
		if($WHOISSERVER_DB_MAP[$account_username]) return $WHOISSERVER_DB_MAP[$account_username];
		
	}
	return array("db_host" => "@DEFAULT_WHOISSERVER_DBHOST@", "db" => "@DEFAULT_WHOISSERVER_DB@", "db_user"=> "@DEFAULT_WHOISSERVER_DBUSER@", "db_pass"=>"@DEFAULT_WHOISSERVER_DBPASS@" );
	
			
}
function connect_to_whoisserver_db($account_username=false) {
  $db_server = get_whoisserver_db_server($account_username);
  
  if (!mysql_connect($db_server['db_host'], $db_server['db_user'], $db_server['db_pass'])) {
    // couldn't connect
    error_log( "could not connect to " . $db_server['db_host']); 	
  	return false;
  }
  if (!mysql_select_db($db_server['db'])) {
    // couldn't connect
    error_log("could not select " . $db_server['db']); 	
  	return false;
  }
  return true;
}

//NOT IN USE!
function connect_to_whoiscrawler_db() {
  global $WHOISCRAWLER_DBHOST,$WHOISCRAWLER_DB, $WHOISCRAWLER_DBUSER, $WHOISCRAWLER_DBPASS;
  //echo "$WHOISCRAWLER_DBHOST, $WHOISCRAWLER_DBUSER, $WHOISCRAWLER_DBPASS";
  $res = mysql_connect($WHOISCRAWLER_DBHOST, $WHOISCRAWLER_DBUSER, $WHOISCRAWLER_DBPASS);
  if (!$res) {
    // couldn't connect
    error_log( "could not connect ($WHOISCRAWLER_DBHOST)");  
    return false;
  }
  if (!mysql_select_db($WHOISCRAWLER_DB)) {
    // couldn't connect
    error_log("could not select ($WHOISCRAWLER_DB)");  
    return $res;
  }
  return $res;
}

?>


