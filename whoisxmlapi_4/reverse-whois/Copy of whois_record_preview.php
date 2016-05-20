<?php include dirname(__FILE__) . "/../users/whois_server.inc";
  if(isset($V2))
  	include dirname(__FILE__) . "/../reverse-whois-v2/config.php";
  else if(isset($V1)){ 
  	require_once dirname(__FILE__) . "/../reverse-whois-v1/config.php";
  }
    	
  else include dirname(__FILE__) . "/config.php";
  
  include dirname(__FILE__)."/../util/string_util.php";

  include dirname(__FILE__)."/whois_record_util.php";
  
  global $_debug_noncryptic_;
  
  $GET_CURRENT_WHOIS_REAL_TIME = 0;  //not necessary since index is not realtime
  $domain_name = $_REQUEST['d'];
  $updated_date = $_REQUEST['w'];
  $current = $_REQUEST['c'];
  $whois_record_id=$_REQUEST['i'];
  
  if(!$domain_name)return;
  $domain_name = StringUtil::dehash_dn($domain_name);
  $search_terms = array_key_pick($_REQUEST, 'term');
 
  
 
 
  
  
 
  if($current){
    $raw = get_current_whois_raw(array('domain_name'=>$domain_name, 'whois_record_id'=>$whois_record_id));
  }
  else {
    $raw = get_whois_raw(array('domain_name'=>$domain_name, 'updated_date'=>$updated_date, 'whois_record_id'=>$whois_record_id));
  }
  
 
 
  
  if(isset($raw)){
    $obscure = !$_debug_noncryptic_ && !verify_report_owner(array('domain_name'=>$domain_name));
    echo hili($raw, $search_terms, $obscure);      
  
  }
    
  echo '';
 
?>
