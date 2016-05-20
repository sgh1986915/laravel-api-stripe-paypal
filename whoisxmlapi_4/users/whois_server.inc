<?php
# database connect information
global $WHOISSERVER_DB_MAP,$WHOISSERVER_DB, $WHOISSERVER_DBUSER, $WHOISSERVER_DBPASS, $WHOISSERVER_DBHOST ;

$WHOISSERVER_DB     = "whoisapi";
$WHOISSERVER_DBUSER = "writer_dev";
$WHOISSERVER_DBPASS = "RQgTQxGzzTeA";
$WHOISSERVER_DBHOST = "localhost";

$WHOISCRAWLER_DB     = "whoiscrawler";
$WHOISCRAWLER_DBUSER = "reader_dev";
$WHOISCRAWLER_DBPASS = "RQgTQxGzzTeA";
$WHOISCRAWLER_DBHOST = "206.80.35.74";

$WHOISSERVER_DB_MAP=array(


);

function get_whoisserver_db_server($account_username=false){
	global $WHOISSERVER_DB_MAP,$WHOISSERVER_DB, $WHOISSERVER_DBUSER, $WHOISSERVER_DBPASS, $WHOISSERVER_DBHOST ;

	
	if($account_username){
		if($WHOISSERVER_DB_MAP[$account_username]) {
			return $WHOISSERVER_DB_MAP[$account_username];
		}

	}
  // return array("db_host" => "66.226.77.35", "db" => "whoisapi", "db_user"=> "writer_dev", "db_pass"=>"PASSWORD" );
 // return array("db_host" => "localhost", "db" => "whoisapi", "db_user"=> "whoisapi", "db_pass"=>"whoisapi" ); // modified // server
	// return array("db_host" => "localhost", "db" => "whois", "db_user"=> "root", "db_pass"=>"" ); // modified // local
	//echo "Db_host is $WHOISSERVER_DBHOST, username is $account_username";
	return array("db_host" => "$WHOISSERVER_DBHOST", "db" => "$WHOISSERVER_DB", "db_user"=> "$WHOISSERVER_DBUSER", "db_pass"=>"$WHOISSERVER_DBPASS" ); 

}
function connect_to_whoisserver_db($account_username=false) {

  $db_server = get_whoisserver_db_server($account_username);
	//print_r($db_server);
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