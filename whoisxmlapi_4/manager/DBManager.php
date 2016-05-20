<?php

class DBManager{
	public static function getWhoisAPIUserDBConnection(){
		global $USERS_DB, $USERS_DBHOST, $USERS_DBUSER, $USERS_DBPASS;
		$dbh = new PDO(DBManager::get_dsn($USERS_DBHOST,$USERS_DB), $USERS_DBUSER, $USERS_DBPASS);
		return $dbh;
	}
	public static function get_dsn($host, $dbname){
		return "mysql:dbname=$dbname;host=$host";
	}
}
?>