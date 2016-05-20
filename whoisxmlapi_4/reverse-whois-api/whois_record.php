<?php 
  $GET_CURRENT_WHOIS_REAL_TIME = 1;  //if 0, it will be the most recent one from db
include dirname(__FILE__) . "/../users/whois_server.inc";
  if(isset($V2))
  	 require_once dirname(__FILE__) . "/../reverse-whois-v2/config.php";
  else if(isset($V1)){ 
  	require_once dirname(__FILE__) . "/../reverse-whois-v1/config.php";
  }  	
  else require_once dirname(__FILE__) . "/config.php";
  
  require_once dirname(__FILE__)."/../util.php";
  require_once dirname(__FILE__)."/../util/string_util.php";
  //require_once dirname(__FILE__)."/../models/report_util.php";
   require_once dirname(__FILE__)."/whois_record_util.php";
   
//print_r($_REQUEST);


  $domain_name = $_REQUEST['d'];
  $updated_date = $_REQUEST['w'];
  $current = $_REQUEST['c'];
  $report_id=$_REQUEST['r'];
  $whois_record_id=$_REQUEST['i'];
 

 // if(!$domain_name || !$report_id)return;

  $domain_name = StringUtil::dehash_dn($domain_name);
  //$report_id = StringUtil::dehash_dn($report_id);
  
  //$search_terms = get_search_terms_from_report($report_id);  




 
  if($current){ 
    $raw = get_current_whois_raw(array('domain_name'=>$domain_name, 'whois_record_id'=>$whois_record_id));
  }
  else {
    $raw = get_whois_raw(array('domain_name'=>$domain_name, 'updated_date'=>$updated_date, 'whois_record_id'=>$whois_record_id));
  }
  
 
  if(isset($raw)){
    //$obscure = !verify_report_owner(array('domain_name'=>$domain_name));
    //echo hili($raw, $search_terms, $obscure);      
  	echo $raw;
  }
    
  echo '';
 
 
?>
