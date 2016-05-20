<?php

class DBManager{
	public static function getRW_WhoisAPI_DBConnection(){
		global $RW_WHOISAPI_DBHOST,$RW_WHOISAPI_DB, $RW_WHOISAPI_DBUSER, $RW_WHOISAPI_DBPASS;
		$dbh = new PDO(DBManager::get_dsn($RW_WHOISAPI_DBHOST,$RW_WHOISAPI_DB), $RW_WHOISAPI_DBUSER, $RW_WHOISAPI_DBPASS);
		
		return $dbh;
	}
	public static function get_dsn($host, $dbname){
		return "mysql:dbname=$dbname;host=$host";
	}
}
?>