<?php require_once dirname(__FILE__) . "/../util/common_init.php";
  //require_once dirname(__FILE__) . "/../util.php";
//  require_once dirname(__FILE__) . "/../users/whois_server.inc";
  require_once dirname(__FILE__) . "/../util/string_util.php";
  //require_once dirname(__FILE__) . "/../reverse-whois-api/prices.php";

 require_once dirname(__FILE__) . "/config.php";
 
class BrandAlert{
  public static $TERM_GLUE = "\t";
  public static $MAX_DAYS_BACK=12;
  var $error=false;
  var $search_terms=array();
  var $exclude_terms=array();
  var $domain_names=array();
  var $username;
  

  public static function mysql_fetch_domains($result, $status){
      $i=0; 
      $domains=array();
      while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
      	$domain_name = $row[domain_name];
        $domains[]=array('domain'=>$domain_name, 'status'=>$status);  
        $i++; 
      }
      return $domains;
  }
  public static function getDomainsAsArray($options, $status='new'){
    $ret=array();
      $res=self::getDomains($options);

      if(!$res)return $ret;
      $alerts=$res->alerts;

      if(!$alerts)return $ret;

        foreach($alerts as $alert){
            if($alert['status']==$status){
                $ret[]=$alert['domain'];
            }
        }
      return $ret;
  }
  //by default is no days_back or since_date is specified, it gets the most recent data from db
  public static function getDomains($options){
    global $_debug_noncryptic_, $MAX_DOMAIN_SEARCH_MATCH, $MAX_SEARCH_CUTOFF;
    $since_date=$options['since_date']; //2011-03-27 04:51:14 //add a restriction later
    $days_back = $options['days_back']; //maybe/maybe not implement later
    $status_new=false;
    $status_on_hold=false;
   
    if($options['domain_status']){
    	$status=explode('|',$options['domain_status']);
    	if(in_array('new',$status)){
    		$status_new=true;
    	}
    	if(in_array('on_hold', $status)){
    		$status_on_hold=true;
    	}
    	
    }
    if($status_new==false && $status_on_hold==false)$status_new=true;
    $time_filter="";
    if(!isset($options['since_date']) && !isset($options['days_back'])){
    	$since_date_unix_time=self::getMaxUpdatedDate('domain_name_new_index');
    	
    }
    else if($since_date){
    	$since_date_unix_time = strtotime($since_date);
    	
    }
   	else {
   		if(!$days_back)$days_back=0;
    	$since_date_unix_time =  strtotime(date("Y-m-d")) - $days_back * 24 * 3600;
   		
    	//echo date('Y-m-d',$since_date_unix_time);
    }
    $time_filter=" and updated_date >= $since_date_unix_time ";
    $meta_only = $options['meta_only'];
    $exclude_domains = $options['exclude_domains'];
  
    $page = $options['page'];
    $start = $options['start'];
    $limit = $options['limit'];
    //print_r($options);
    $match = BrandAlert::construct_query_match($options['search_terms']);
   	  

    connect_to_brand_monitor_index_db();
    
    
    $domains=array();
    $meta_data=array('total_found'=>0);
  
    if($status_new){
    	$SQL = "select * from domain_name_new_index  where match ('@domain_name $match') $time_filter group by domain_name  LIMIT $start , $limit option max_matches = $MAX_DOMAIN_SEARCH_MATCH";
    	//echo $SQL;
        $result = mysql_query( $SQL ) or die("get_domains Couldn t execute query.".mysql_error() . " <br/>query:$SQL");
		$domains= BrandAlert::mysql_fetch_domains($result, 'new');
    	$meta_data = BrandAlert::get_meta_data();
    	$response->status_new=$status_new;
    }
 
    
    if($status_on_hold){
    	
   		$SQL = "select * from domain_name_dropped_index  where match ('@domain_name $match') $time_filter group by domain_name  LIMIT $start , $limit option max_matches = $MAX_DOMAIN_SEARCH_MATCH";
		$result = mysql_query( $SQL ) or die("get_domains Couldn t execute query.".mysql_error() . " <br/>query:$SQL");
   		$domains=array_merge($domains, BrandAlert::mysql_fetch_domains($result, 'on_hold'));
   		$meta_data2=BrandAlert::get_meta_data();
   		$meta_data['total_found']+=$meta_data2['total_found'];
   		$response->status_on_hold=$status_on_hold;
   	}
    
    if(!$meta_only){
    	$response->alerts=$domains;
     // $response->page=$page;
      	$response->total = $meta_data['total_found']; 
      //$response->total_pages = ceil($response->records/$limit); 
     
      $response->search_terms = $options['search_terms'];
    }
    if($meta_only)return $meta_data;
   
   	BrandAlert::get_errors($response);
   
    return $response;  
  }
 public static function getMaxUpdatedDate($index_name){
 	connect_to_brand_monitor_index_db();
 	
 	$SQL = "select max(updated_date) as max_date from $index_name";
 	
	$result = mysql_query( $SQL ) or die("get_domains Couldn t execute query.".mysql_error() . " <br/>query:$SQL");
	 if($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
	 	return $row['max_date'];
	 }
 }
 	
  public static function construct_query_match($search_terms){
   
  
    $res="";
    $include = $search_terms['include'];
    $exclude = $search_terms['exclude'];
    
    foreach($include as $s){
        $res .= ' "' .  StringUtil::hyphen_fix($s) . '"';
      
    }
    foreach($exclude as $s){
        $res .= ' !"' .  StringUtil::hyphen_fix($s) . '"';
      
    }
    //return $res = "@raw_text $res";
    return $res;
  }
  
  public static function get_meta_data(){
    $SQL ="show meta";
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error() . " <br/>query:$SQL");
    $meta_data=array();
    while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
      $meta_data[$row['Variable_name']] = $row['Value'];
    }
    return $meta_data;
  }
  public static function get_errors($response){
  	global $MAX_DOMAIN_SEARCH_MATCH;
  	if($response->stats['total'] > $MAX_DOMAIN_SEARCH_MATCH){
    	
    	$response->error=BrandAlert::createMaxErrorMsg($MAX_DOMAIN_SEARCH_MATCH);
    	
    }
   
   
  }
  public static function createMaxErrorMsg($max)  { 
  	return "Your BrandAlert is too large to process.
Your search has surpassed our results limit of $max domains for any given BrandAlert. " .
		"Please refine your search or email support@whoisxmlapi.com to help you creating a search query suitable for your specific needs.";
  
	}

public static function get_search_terms_from_request($max_search_terms=4){
    $include=array();
    $exclude=array(); 
    for($i=1;$i<=$max_search_terms;$i++){
      if(isset($_REQUEST["term$i"]) && ($s=trim(urldecode($_REQUEST["term$i"])))){
          $include[]=$s;
      }
      if(isset($_REQUEST["exclude_term$i"]) && ($s=trim(urldecode($_REQUEST["exclude_term$i"])))){
          $exclude[]=$s;
      }      
    }

    return array('include'=>$include, 'exclude'=>$exclude, 'max_search_terms'=>$max_search_terms);
  }
	
}
?>