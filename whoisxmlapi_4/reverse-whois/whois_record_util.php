<?php
  require_once dirname(__FILE__)."/../util.php";
  require_once dirname(__FILE__)."/../models/report_util.php";
  
  function get_whois_raw($options){ 
    if(!connect_to_rw_whoiscrawler_db($options['whois_record_id']))return;
    $domain_name = $options['domain_name'];    
    $domain_name = mysql_real_escape_string($domain_name);
    $updated_date = $options['updated_date'];
    
 
    //$SQL = sprintf("select ifnull(w.raw_text, r.raw_text) as raw_text from whois_record w, registry_data r where w.registry_data_id = r.registry_data_id and w.domain_name='%s' and w.audit_updated_date =from_unixtime(%d)", $domain_name, $updated_date);
	//$SQL = sprintf("select w.raw_text as w_raw_text, r.raw_text as r_raw_text from whois_record w, registry_data r where w.registry_data_id = r.registry_data_id and w.domain_name='%s' " 
	//." and ( w.audit_updated_date =from_unixtime(%d) or ( w.audit_updated_date >= from_unixtime(%d-10000)  and w.audit_updated_date <= from_unixtime(%d+10000) ) ) ", $domain_name, $updated_date, $updated_date, $updated_date); //timestamp is inaccurate from sphinx indexer and mysql for some reason
	$SQL = sprintf("select w.raw_text as w_raw_text, r.raw_text as r_raw_text from whois_record w, registry_data r where w.registry_data_id = r.registry_data_id and w.domain_name='%s' " 
	." and ( unix_timestamp(w.audit_updated_date) =%d or ( unix_timestamp(w.audit_updated_date) >= %d-10000  and unix_timestamp(w.audit_updated_date) <= %d+10000) )  ", $domain_name, $updated_date, $updated_date, $updated_date); //timestamp is inaccurate from sphinx indexer and mysql for some reason
	//tolerant within 10000 seconds
	
    //echo $SQL;
    $res = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error() . " <br/>query:$SQL");
  	$raw="";
    if($row = mysql_fetch_array($res, MYSQL_ASSOC)){
      $w_raw = $row['w_raw_text'];
      $r_raw = $row['r_raw_text'];
      if(!empty($w_raw)){
      	$raw.=$w_raw;
      }
      if(!empty($r_raw)){
      	if(!empty($raw))$raw.="\n\n<h3>Whois Registry Record:</h3>\n";
      	$raw.=$r_raw;
      }      
    }
    return $raw;
  }
  function get_current_whois_raw($options){
    global $GET_CURRENT_WHOIS_REAL_TIME;
    if($GET_CURRENT_WHOIS_REAL_TIME){ 
      return get_current_whois_raw_rt($options);
    }
    else{ 
      return get_current_whois_raw_db($options);
    }
  }
  function get_current_whois_raw_rt($options){
    $domain_name = $options['domain_name']; 
    $username = "root";	
	$password = "bigfish";
	$format = "JSON"; //or XML
	$url = 'http://www.whoisxmlapi.com/whoisserver/WhoisService?domainName='. $domain_name .'&username='. $username .'&password='. $password .'&outputFormat='. $format . "";
	$result = json_decode(file_get_contents($url));
	$raw_text = "";
	
	//print_r($result->WhoisRecord);
	$data_error=0;
	
	if($result->WhoisRecord){
		
		if($result->WhoisRecord->registryData && $result->WhoisRecord->registryData->dataError=='MISSING_WHOIS_DATA'){
			$raw_text = "$domain_name is not currently registered.  Your search term exists in one of the historic whois records. Please close this window and then click on each of the time stamps to view the historic whois record.";
		}
		else $raw_text=$result->WhoisRecord->rawText;
		if($result->WhoisRecord->dataError=='INCOMPLETE_DATA'){
			$data_error=1;
		}
		else if($result->WhoisRecord->registryData && $result->WhoisRecord->registryData->dataError=='INCOMPLETE_DATA'){
			$data_error=1;
		}
		
	}
	
	if($result->WhoisRecord->registryData){
				
		$r_raw=$result->WhoisRecord->registryData->rawText;
	
      	if(!empty($r_raw)){
      		if(!empty($raw_text))$raw_text.="\n\n<h3>Whois Registry Record:</h3>\n";
      		$raw_text.=$r_raw;
      	}      
	}

	
	if(!$raw_text || $data_error){
		return "<b>We are unable to obtain the realtime current whois record for $domain_name right now, please try again later.  The following is the most recent historic whois record. </b><br/>" 
		. get_current_whois_raw_db($options);
	}
    return $raw_text;
  }
  function get_current_whois_raw_db($options){
  	if(!connect_to_rw_whoiscrawler_db($options['whois_record_id']))return;
	
    $domain_name = $options['domain_name'];    
    $domain_name = mysql_real_escape_string($domain_name);
    $SQL = sprintf("select w.raw_text as w_raw_text, r.raw_text as r_raw_text from whois_record w, registry_data r where w.domain_name='%s' and w.registry_data_id = r.registry_data_ID order by w.whois_record_id desc limit 1", $domain_name);
    $res = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error() . " <br/>query:$SQL");
  
  	$raw="";
    if($row = mysql_fetch_array($res, MYSQL_ASSOC)){
      $w_raw = $row['w_raw_text'];
      $r_raw = $row['r_raw_text'];
      if(!empty($w_raw)){
      	$raw.=$w_raw;
      }
      if(!empty($r_raw)){
      	if(!empty($raw))$raw.="\n\n<h3>Whois Registry Record:</h3>\n";
      	$raw.=$r_raw;
      }      
    }
    return $raw;
  }
  function get_search_terms_from_report($report_id){
    $report_util=new report_util();
    $rep = $report_util->get_report_by_id($report_id);
    
    return $rep->search_terms;
  }
  function verify_report_owner($options){
    my_session_start();
    $user = $_SESSION['myuser'];
    if(!is_object($user))return false;
    $report_id = $_REQUEST['r'];
    if(!$report_id)return false;
    $domain_name=$options['domain_name'];
    $report_util = new report_util();
    $report = $report_util->get_report_by_id(StringUtil::dehash_dn($report_id));
    if(!$report)return false;
    
    return in_arrayi($domain_name, $report->domain_names) !== false;
    
  }
  function hili($text, $search_terms, $obscure=true){
    if(!connect_to_rw_whoiscrawler_db())return;  
    $search_terms = arrayMap(function($val){
      return mysql_real_escape_string(urldecode(trim($val)));
    }, $search_terms);
    $text = mysql_real_escape_string($text);
     
    if(!connect_to_whoiscrawler_index_db())return;
    //print_r($search_terms);
    
    $match = array();
    
    foreach($search_terms as $key=>$val){
     if(!empty($val)) $match[] = " \"$val\"";
      
    }
    $match =  implode(" | ", $match);
    
    
    if($obscure){
      $str=get_obscure_text($text,$match);
    }
    else $str = get_regular_text($text,$match);
    
    return '<div><div id="preview_order_report"></div><pre>'.$str . '</pre></div>';  
  
  }
  function get_obscure_text($text,$match){
  	global $SNIPPET_INDEX;
    $before_match_replace='<FONT style="BACKGROUND-COLOR: yellow; font-weight:bold">';
    $after_match_replace='</FONT>';
    $before_match='<xxxxxx>';
    $after_match='</xxxxxx>';
    $SQL= sprintf("call snippets ('%s', '%s', '(%s)', 1000000 as limit, 10000 as around
      ,'$before_match' as before_match, '$after_match' as after_match, 0 as exact_phrase, 1 as query_mode
    )", 
        $text, $SNIPPET_INDEX, $match);
    
   // echo $SQL;
    
    $res = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error() . " <br/>query:$SQL");
    $str='';
    while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
       $str .= $row['snippet'];
    }
    $str = obscure($str, array('before_match'=>$before_match, 'after_match'=>$after_match, 'before_match_replace'=>$before_match_replace, 'after_match_replace'=>$after_match_replace));
    return $str;
  }
  function get_regular_text($text,$match){
    global $SNIPPET_INDEX;
    $before_match='<FONT style="BACKGROUND-COLOR: yellow; font-weight:bold">';
    $after_match='</FONT>';
    
    $SQL= sprintf("call snippets ('%s', '%s', '(%s)', 1000000 as limit, 10000 as around
      ,'$before_match' as before_match, '$after_match' as after_match, 1 as exact_phrase, 1 as query_mode
    )", 
        $text, $SNIPPET_INDEX, $match);
	//echo $SNIPPET_INDEX;
    $res = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error() . " <br/>query:$SQL");
    $str='';
    while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
       $str .= $row['snippet'];
       //echo"<br/>---------------------------------------------------------------------------";
       //echo "snipet is ".$row['snippet'];
       //echo"<br/>---------------------------------------------------------------------------";
    }
    return $str;
  }
  function obscure_word($w){
    return preg_replace('/\S/', "_", $w);
  }
  function obscure($str, $options){
  
      $a=$options['before_match'];
      $b=$options['after_match'];
      $ar=$options['before_match_replace'];
      $br=$options['after_match_replace'];
    
      $reg = "/(<\/?[a-z][a-z0-9]*[^<>]*>)/";
      $toks = preg_split($reg, $str, -1, PREG_SPLIT_DELIM_CAPTURE);
      
      $res="";
      $inline=false;
      $i=0;
      foreach($toks as $tok){
        if($tok == $a){
          $inline = true;
        }
        else if($tok == $b){
          $inline = false;
        }
        else if(!$inline){
          //echo " outline token is $tok<br/><br/>";
          $toks[$i] = obscure_word($tok);
        }
        /*
else{
  echo " inline token is $tok<br/><br/>";
}*/
        $i++;
      }
      foreach($toks as $tok){
        if($tok == $a)
          $res.=$ar;
        
        else if($tok == $b) $res.=$br;
        else $res.= $tok;  
      }
   
      return $res;
  }
?>