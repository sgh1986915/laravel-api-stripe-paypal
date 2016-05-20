<?php
require_once dirname(__FILE__) . "/users/whois_server.inc";
	function delete_report($username, $search_terms){ 
		if(!connect_to_whoisserver_db())return;
		$sql = sprintf("delete from whois_report where username='%s' and search_terms='%s'", $username, $search_terms); 
		mysql_query( $sql ) or die("Couldn t execute query.".mysql_error() . " <br/>query:$SQL");
	}
	
	if($_REQUEST['function']=='delete_report'){
		delete_report($_REQUEST['username'], $_REQUEST['search_terms']);
	}
	
?>
