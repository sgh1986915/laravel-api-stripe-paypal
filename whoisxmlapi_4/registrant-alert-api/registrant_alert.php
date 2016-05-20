<?php 

  require_once dirname(__FILE__) . "/../util/string_util.php";
  require_once dirname(__FILE__) . "/../api-commons/db_util.php";

 require_once dirname(__FILE__) . "/config.php";
 
class RegistrantAlert{
  public static function mysql_fetch_domains($result, $match_type){
      $i=0; 
      $domains=array();
      while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
      	$domain_name = $row[domain_name];
      	$current_owner=$row['registrant_name'] ? $row['registrant_name'] :$row['registrant_org'];
      	if(!$current_owner){
      		$current_owner = StringUtil::first_nonempty_line($row['registrant_raw_text']);
      	}
      //	print_r($row);
        $domains[]=array('domain'=>$domain_name, 'match_type'=>$match_type, 'current_owner'=>$current_owner);  
        $i++; 
      }
      return $domains;
  }
  public static function getDomains($options){
    global  $MAX_DOMAIN_SEARCH_MATCH;
    $since_date=$options['since_date'];
    $days_back = $options['days_back']; 
 	$include_dropped = $options['include_dropped'];
    $time_filter="";
    if($since_date){
    	$since_date_unix_time = strtotime($since_date);
    }
   	else {
   		if(!$days_back)$days_back=0;
    	$since_date_unix_time =  strtotime(date("Y-m-d")) - $days_back * 24 * 3600;
    	//echo date('Y-m-d',$since_date_unix_time);
    }
    $time_filter=" and audit_updated_date >= $since_date_unix_time ";
    $meta_only = $options['meta_only'];

  

    $start = $options['start'];
    $limit = $options['limit'];
    //print_r($options);
    $match = RegistrantAlert::construct_query_match($options['search_terms']);
   	  
    
    connect_to_registrant_alert_index_db();
    
    
    $domains=array();
    $meta_data=array('total_found'=>0);
  
  	$SQL = "select * from registrant_alert_whois_new_index where match ('$match') $time_filter group by domain_name  LIMIT $start , $limit option max_matches = $MAX_DOMAIN_SEARCH_MATCH";
	//echo $SQL;
	$result = mysql_query( $SQL ) or die("get_domains Couldn t execute query.".mysql_error() . " <br/>query:$SQL");
	$domains= RegistrantAlert::mysql_fetch_domains($result, 'new');
    $meta_data = RegistrantAlert::get_meta_data();//might remove later
    if($include_dropped){

   	}
    
    if(!$meta_only){
    	$response->alerts=$domains;
      	$response->total = $meta_data['total_found']; 
      	$response->search_terms = $options['search_terms'];
      	$response->limit=$limit;
    }
    if($meta_only)return $meta_data;
   
   	RegistrantAlert::get_errors($response);
   
    return $response;  
  }
 
  public static function construct_query_match($search_terms){
	return construct_query_match($search_terms);
  }
  
  public static function get_meta_data(){
	return get_meta_data();
  }
  public static function get_errors($response){
  	global $MAX_DOMAIN_SEARCH_MATCH;
   
  }
  public static function createMaxErrorMsg($max)  { 
  	return "Your Registrant Alert is too large to process.
Your search has surpassed our results limit of $max domains for any given Registrant Alert. " .
		"Please refine your search or email support@whoisxmlapi.com to help you creating a search query suitable for your specific needs.";
  
	}
}

?>