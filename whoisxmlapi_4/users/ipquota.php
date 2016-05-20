<?php 
require_once("whois_server.inc");
require_once("dbutils.php");

function getIPQuota($ip){
	$act = new IPQuota($ip);
	$act->load_from_db();
	return $act;
}
class IPQuota{
	var $ip;
	var $balance;
	var $reserve;
	
	function __construct($ip) {
		$this->ip = $ip;
	}
	function load_from_db(){
		 global $WHOISSERVER_DB;
	
		 if(!connect_to_whoisserver_db())return;
    	$sql = "SELECT *
    FROM $WHOISSERVER_DB.ip_quota 
    WHERE ip=".quote($this->ip);
		$query = mysql_query($sql); 
        if(!$query){
       		error_log("The query failed! (".mysql_error()."): $sql");
			return;
		} 
    	if ($query && (mysql_num_rows($query) > 0)) {
      		$res = mysql_fetch_assoc($query);
			$this->balance = $res['balance'];
			$this->reserve = $res['reserve'];
    	}
	}	
	
}
?>