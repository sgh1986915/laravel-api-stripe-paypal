<?php 
  require_once dirname(__FILE__) . "/../util.php";
  require_once dirname(__FILE__) . "/../users/whois_server.inc";
  require_once dirname(__FILE__) . "/../util/string_util.php";
  require_once dirname(__FILE__) . "/../reverse-whois/prices.php";
  date_default_timezone_set('America/Los_Angeles');
  ini_set('memory_limit','-1');
  ini_set("max_execution_time", 0);
  
  if(isset($V2)){ 
  	require_once dirname(__FILE__) . "/../reverse-whois-v2/config.php";
  }
  else if(isset($V1)){ 
  	require_once dirname(__FILE__) . "/../reverse-whois-v1/config.php";
  }
  else require_once dirname(__FILE__) . "/../reverse-whois/config.php";
class Report{
  public static $TERM_GLUE = "\t";
  var $error=false;
  var $search_terms=array();
  var $exclude_terms=array();
  var $domain_names=array();
  var $username;
  var $price;
  var $num_cur_d = 0;
  var $num_his_d = 0;
  var $num_total_d = 0;
  var $search_type;
  function equals($report){
      

        
    if(!is_object($report))return false;
    if($this->search_type!=$report->search_type)return false;
    if(count(array_diff_i($this->search_terms, $report->search_terms)) > 0)return false;
    if(count(array_diff_i($this->exclude_terms, $report->exclude_terms)) > 0)return false;
    return true;
  }
  function compute_price(){
    $this->price = ($this->search_type == 2 ? compute_history_report_price(array('history_total_count'=>$this->num_his_d, 'current_total_count' => $this->num_cur_d)) : compute_current_report_price(array('current_total_count' => $this->num_cur_d)));
    
  }
  public static function compute_credits_required($num_domains){
  	
  	if($num_domains<=0) return 0;
  	else return ceil($num_domains/10000);
  }
  function compute_credits(){
  	$this->num_credits=Report::compute_credits_required($this->num_total_d);
  	
  }
  function db_init(){
    if(!connect_to_whoisserver_db()){
      $this->error = "Report->db_init failed: ".mysql_error();
      return false;
    }
    return true;
    
  }
  public static function get_report_from_db_row($row){
    $r=new Report();
   
    
    StringUtil::copy_obj($row, $r, array('domain_names' => 'Report::explode_terms_strlower', 'search_terms' => 'Report::explode_terms','exclude_terms' => 'Report::explode_terms'));
    //echo "create_report_from_db_row ".print_r($r,1);
    $r->num_total_d = $r->num_cur_d + $r->num_his_d;
    return $r;
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
  private static function get_default_report_params(){
    return array('start'=>'0', 'limit'=>'10');
  }
  public static function get_report_from_request(){
    $r = new Report();
    $terms = Report::get_search_terms_from_request();

    $r->search_terms = $terms['include'];
    $r->exclude_terms = $terms['exclude'];
    $r->search_type = $_REQUEST['search_type'];

    if(count($r->search_terms)==0 && count($r->exclude_terms) == 0){
      return $r;
    }
    $input = array_merge(Report::get_default_report_params(), $_REQUEST, array('search_terms'=>$terms));

    if($r->search_type== 2){
      $his = Report::get_historical_stats($input);
      $cur = Report::get_current_stats($input);
      $r->num_cur_d = $cur['total'];
      $r->num_his_d = $his['total'] - $cur['total'];
      
      $r->price = compute_history_report_price(array('history_total_count'=>$r->num_his_d,
                                      'current_total_count'=>$r->num_cur_d 
                              ));

    }
    else{
      $cur = Report::get_current_stats($input);
      $r->num_cur_d = $cur['total'];
      $r->price = compute_current_report_price(array(
                                      'current_total_count'=>$r->num_cur_d
                              ));
    }
    $r->num_total_d = $r->num_cur_d + $r->num_his_d;


    return $r;
  }

  
  public static function render_search_terms_s($include, $exclude){
  	$res = "";
    
    if(count($include)>0)$res = StringUtil::wrap_implode($include, "\"", " AND ");
    if(count($include)>0 && count($exclude)>0) $res .=" AND NOT ";
    if(count($exclude)>0)$res .= StringUtil::wrap_implode($exclude, "\"", " AND NOT ");
   
    return $res;
  }
  function render_search_terms(){
    	return Report::render_search_terms_s($this->search_terms, $this->exclude_terms);
    
  }

  function save_report($param){
    if(!$this->db_init())return false;
    
    $SQL = sprintf("insert into whois_report(username, search_terms, exclude_terms, domain_names, price, num_cur_d, num_his_d, search_type, created_date, updated_date) value('%s', '%s', '%s', '%s', %f, %d, %d, %d, now(), now())",
             $param['username'],
             implode(Report::$TERM_GLUE, $this->search_terms), implode(Report::$TERM_GLUE,$this->exclude_terms), implode(Report::$TERM_GLUE, $this->domain_names),
             $this->price, $this->num_cur_d, $this->num_his_d, $this->search_type  
           );
    //echo $SQL;
   
    $res = mysql_query($SQL);
    if(!$res){
      $this->error = 'Report->save_report() failed: '.mysql_error();
      return false;
    }
    $this->report_id = mysql_insert_id();
    return true;
   }
  public static function explode_terms($str){
    
    return StringUtil::explode_trim(Report::$TERM_GLUE, $str);
  }
  public static function explode_terms_strlower($str){
    
    return StringUtil::explode_trim_strlower(Report::$TERM_GLUE, $str);
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
  public static function get_whois_record_preview_path(){
  	global $V2;
  	if(isset($V2))return "reverse-whois/whois_record_preview_v2.php";
  	return "reverse-whois/whois_record_preview.php";
  }
  public static function get_whois_record_path(){
  	global $V2;
  	if(isset($V2))return "reverse-whois/whois_record_v2.php";
  	return "reverse-whois/whois_record.php";
  }
  public static function get_history_whois_records_preview($options){
      return Report::get_history_whois_records(array_merge($options, array('whois_record_path'=>Report::get_whois_record_preview_path())), false);
  }
  public static function get_history_whois_records($options, $noncryptic=false){
  	global $WHOIS_HISTORY_INDEX;
  	return self::get_history_whois_records_core(array('index'=>$WHOIS_HISTORY_INDEX), $options,$noncryptic);
  }
  
  /*     
  public static function get_history_whois_records($options, $noncryptic=false){
    global $_debug_noncryptic_, $MAX_DOMAIN_SEARCH_MATCH, $MAX_SEARCH_CUTOFF, $historic_price_discount, $current_price_discount, $WHOIS_HISTORY_INDEX;
    $whois_record_path = $options['whois_record_path'];
    $meta_only = $options['meta_only'];
    $domain_name_only = $options['domain_name_only'];
   
    $page = $options['page'];
    $start = $options['start'];
    $limit = $options['limit'];
    $match = Report::construct_query_match($options['search_terms']);
    //$SQL = "select * from whois_history_index where match ('$match') group by domain_id LIMIT $start , $limit option  max_matches = $MAX_DOMAIN_SEARCH_MATCH, cutoff = $MAX_SEARCH_CUTOFF";
    //$SQL = "select * from whois_history_index where match ('$match')  LIMIT $start , $limit option  max_matches = $MAX_DOMAIN_SEARCH_MATCH, cutoff = $MAX_SEARCH_CUTOFF";
    $SQL = "select * from $WHOIS_HISTORY_INDEX where match ('$match') group by domain_name  LIMIT $start , $limit option max_matches = $MAX_DOMAIN_SEARCH_MATCH, cutoff = $MAX_SEARCH_CUTOFF";
	
  //echo $SQL;
  
    connect_to_whoiscrawler_index_db();
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error() . " <br/>query:$SQL");
    $groups = array();
    if(!$meta_only){
      
     
      while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
        $groups[]=$row;
      }
    }
    $meta_data = Report::get_meta_data();
  
   	$domain_dates = array();
   	if($n=count($groups)>0){
   		
    	$s = "select * from $WHOIS_HISTORY_INDEX where domain_id in ( ";
   		$i=0;
   		foreach($groups as $domain){
   			if($i>0)$s.=",";
   			$s .= $domain['domain_id'];
   			$i++;
   		}
   		$s.=")";
   		//echo $s;
   		$result2 = mysql_query( $s) or die("Couldn t execute query.".mysql_error() . " <br/>query:$s");
   		
   		while($row = mysql_fetch_array($result2,MYSQL_ASSOC)) {
        	$domain_name=$row['domain_name'];
        	if(!$domain_dates[$domain_name])$domain_dates[$domain_name]=array();
        	array_push($domain_dates[$domain_name], $row); 	
      	}
   	}
   	
   	
    if(!$meta_only){
      if($domain_name_only){
        $response=array();
       //print_r($groups);
        foreach($groups as $group){
          $response[]=$group['domain_name'];
          
        }
       
        return $response;
      }
      $i=0;
      foreach($groups as $group){
      	    $clear_domain_name=$group['domain_name'];	 
    		if($_debug_noncryptic_ || $noncryptic) {
          		$domain_name = $clear_domain_name;
        	}
        	else{
          		$domain_name = StringUtil::crypt_dn($group[domain_name]);         
        	}
        	$domain_name_hash = StringUtil::hash_dn($group[domain_name]);
        	$whois_records = array();
        	$whois_records[] = "<a href=\"".
            	build_url($whois_record_path, array_merge(array('d'=>$domain_name_hash, 'c'=>1, 'search_type'=>$options['search_type']),
                                                                 array_key_pick($_REQUEST, 'term')
                                                                )
                                                            )
            	. "\" class=\"whois_record\">" . 'Current' . '</a>';   
            $dates=$domain_dates[$clear_domain_name];
           
            if($dates){	
            	foreach($dates as $date){
            		if($date['id']!=$group['id']){//not current date
            			$whois_records[] = "<a href=\"".
            				build_url($whois_record_path, array_merge(array('d'=>$domain_name_hash, 'i'=>$date['id'], 'w'=>$date['audit_updated_date'], 'search_type'=>$options['search_type']),
                                                                 array_key_pick($_REQUEST, 'term')
                                                                )
                                                            )
            					. "\" class=\"whois_record\">" . date('m/d/Y',$date['audit_updated_date']) . '</a>';  
            		}
            	} 
            }
            $response->rows[$i]['cell']=array($domain_name, implode(' ', $whois_records));
        	$i++;	
      }	
   		
    }
   
    
    if(!$meta_only){
      $response->page=$page;
      $response->records = $meta_data['total_found']; 
      $response->total = ceil($response->records/$limit); 
      
      //$current_stats = Report::get_current_stats($options);
      $current_stats=$meta_data; //cheats
     
      
      $response->stats['current_total_count'] = $current_stats['total_found'];
      $response->stats['history_total_count'] = $response->records - $response->stats['current_total_count'] ;
      if($response->stats['history_total_count'] <0) $response->stats['history_total_count'] = 0;
     // echo $response->records . " ". print_r($response->stats,1);
      $response->stats['current_report_price'] = compute_current_report_price($response->stats);
      $response->stats['history_report_price'] = compute_history_report_price($response->stats);
      $response->stats['historic_price_discount'] = $historic_price_discount;
      $response->stats['current_price_discount'] = $current_price_discount;
      $search_terms = $options['search_terms'];
      $response->search_terms_disp = Report::render_search_terms_s($search_terms['include'], $search_terms['exclude']);
      $response->search_type = $options['search_type'];
    }
    if($meta_only)return $meta_data;
  	Report::get_errors($response);
    return $response;  
  }
  */
  
  function implode_array_element($delim, $array, $key){
    $res=array();
    foreach($array as $a){
      $res[]=$a[$key];
    }
    //print_r($res);
    return implode($delim, $res);
  }
  
  public static function get_historical_stats($options){
    $meta_data = Report::get_history_whois_records(array_merge($options, array('meta_only'=>1)));
    return $meta_data;
  }
  public static function get_current_stats($options){
    $meta_data = Report::get_current_whois_records(array_merge($options, array('meta_only'=>1)));
    return $meta_data;
  }  
  public static function get_current_whois_records_preview($options){
      return Report::get_current_whois_records(array_merge($options, array('whois_record_path'=>Report::get_whois_record_preview_path())), false);
  }
  public static function get_current_whois_records($options, $noncryptic=false){
  	global $WHOIS_CURRENT_INDEX;
  	return self::get_current_whois_records_core(array('index'=>$WHOIS_CURRENT_INDEX), $options,$noncryptic);
  }
  
  public static function get_current_whois_records_core($core_options, $options, $noncryptic=false){
    global  $_debug_noncryptic_, $MAX_DOMAIN_SEARCH_MATCH, $MAX_SEARCH_CUTOFF, $historic_price_discount, $current_price_discount;
 
    $index=$core_options['index'];
    $whois_record_path = $options['whois_record_path'];
    $meta_only = $options['meta_only'];
    $domain_name_only = $options['domain_name_only'];
   
    $page = $options['page'];
    $start = $options['start'];
    $limit = $options['limit'];
    $match = Report::construct_query_match($options['search_terms']);
  	
  	$time_lower_bound=time() - 3600*24*30*6;
   //	$SQL = "select * from $index where match ('$match') group by domain_name  order by  audit_updated_date desc LIMIT $start , $limit option max_matches = $MAX_DOMAIN_SEARCH_MATCH, cutoff = $MAX_SEARCH_CUTOFF";
	$SQL = "select * from $index where match ('$match') and audit_updated_date> $time_lower_bound group by domain_name  order by  audit_updated_date desc " 
	. "LIMIT $start , $limit option max_matches = $MAX_DOMAIN_SEARCH_MATCH, cutoff = $MAX_SEARCH_CUTOFF";
	
    connect_to_whoiscrawler_index_db();
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error() . " <br/>query:$SQL");
    $groups = array();
    if(!$meta_only){
      
     
      while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
        $groups[]=$row;
      }
    }
    
    $meta_data = Report::get_meta_data();
  	
   
   	
   	
    if(!$meta_only){
      if($domain_name_only){
        $response=array();
       //print_r($groups);
        foreach($groups as $group){
          $response[]=$group['domain_name'];
          
        }
       
        return $response;
      }
      $i=0;
      foreach($groups as $group){
      	    $clear_domain_name=$group['domain_name'];	 
    		if($_debug_noncryptic_ || $noncryptic) {
          		$domain_name = $clear_domain_name;
        	}
        	else{
          		$domain_name = StringUtil::crypt_dn($group[domain_name]);         
        	}
        	$domain_name_hash = StringUtil::hash_dn($group[domain_name]);
        	$whois_records = array();
        	
        	$params = array_merge(array('d'=>$domain_name_hash, 'c'=>1, 'i'=>$group['id'],'search_type'=>$options['search_type']),
                                                                 array_key_pick($_REQUEST, 'term')
                                                                
                                                            );
                                                        
        	$whois_records = array("<a href=\"".
            	build_url($whois_record_path, $params)
            	. "\" class=\"whois_record\">" . 'Current' . '</a>');   
          
            
            			$cur_param = array_merge(array('d'=>$domain_name_hash, 'i'=>$group['id'], 'w'=>$group['audit_updated_date'], 'search_type'=>$options['search_type']),
                                                                 array_key_pick($_REQUEST, 'term')
                                     );
                                  
            			$whois_records[] = "<a href=\"".
            				build_url($whois_record_path, $cur_param)
            					. "\" class=\"whois_record\">" . date('m/d/Y',$group['audit_updated_date']) . '</a>';  
            		
            
          
            $response->rows[$i]['cell']=array($domain_name, implode(' ', $whois_records));
        	$i++;	
      }	
   		
    }
    if($domain_name_only)return $response;
    
   
    
    if(!$meta_only){
      $response->page=$page;
      $response->records = $meta_data['total_found']; 
      $response->total = ceil($response->records/$limit); 
      $response->stats['current_total_count'] =  $response->records;
      $response->stats['num_credits']= Report::compute_credits_required(min($MAX_DOMAIN_SEARCH_MATCH,$response->stats['current_total_count']));		
      $response->stats['current_report_price'] = compute_current_report_price($response->stats);
      $response->stats['current_price_discount'] = $current_price_discount;
      
       $search_terms = $options['search_terms'];
      $response->search_terms_disp = Report::render_search_terms_s($search_terms['include'], $search_terms['exclude']);
      $response->search_type = $options['search_type'];
    }
    if($meta_only)return $meta_data;
   
   	Report::get_errors($response);
   
    return $response;  
  }
  public static function get_history_whois_records_core($core_options, $options, $noncryptic=false){
    global  $_debug_noncryptic_, $MAX_DOMAIN_SEARCH_MATCH, $MAX_SEARCH_CUTOFF, $historic_price_discount, $current_price_discount;
 
    $index=$core_options['index'];
    $whois_record_path = $options['whois_record_path'];
    $meta_only = $options['meta_only'];
    $domain_name_only = $options['domain_name_only'];
   
    $page = $options['page'];
    $start = $options['start'];
    $limit = $options['limit'];
    $match = Report::construct_query_match($options['search_terms']);
  
   //	$SQL = "select * from $index where match ('$match') group by domain_name  order by  audit_updated_date desc LIMIT $start , $limit option max_matches = $MAX_DOMAIN_SEARCH_MATCH, cutoff = $MAX_SEARCH_CUTOFF";
	$SQL = "select * from $index where match ('$match') group by domain_name order by  audit_updated_date desc LIMIT $start , $limit option max_matches = $MAX_DOMAIN_SEARCH_MATCH, cutoff = $MAX_SEARCH_CUTOFF";
	
    connect_to_whoiscrawler_index_db();
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error() . " <br/>query:$SQL");
    $groups = array();
    if(!$meta_only){
      
     
      while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
        $groups[]=$row;
      }
    }
    
    $meta_data = Report::get_meta_data();
  	
   	$domain_dates = array();
   	if($n=count($groups)>0){
    	$s = "select * from $index where domain_id in ( ";
   		$i=0;
   		foreach($groups as $domain){
   			if($i>0)$s.=",";
   			$s .= $domain['domain_id'];
   			$i++;	
   		}
   		$s.=") order by audit_updated_date desc LIMIT 1000";
   		//echo $s;
   		$result2 = mysql_query( $s) or die("Couldn t execute query.".mysql_error() . " <br/>query:$s");
   		
   		while($row = mysql_fetch_array($result2,MYSQL_ASSOC)) {
        	$domain_name=$row['domain_name'];
        	if(!$domain_dates[$domain_name])$domain_dates[$domain_name]=array();
        	array_push($domain_dates[$domain_name], $row); 	
      	}
   	}
   	
   	
    if(!$meta_only){
      if($domain_name_only){
        $response=array();
       //print_r($groups);
        foreach($groups as $group){
          $response[]=$group['domain_name'];
          
        }
       
        return $response;
      }
      $i=0;
      foreach($groups as $group){
      	    $clear_domain_name=$group['domain_name'];	 
    		if($_debug_noncryptic_ || $noncryptic) {
          		$domain_name = $clear_domain_name;
        	}
        	else{
          		$domain_name = StringUtil::crypt_dn($group[domain_name]);         
        	}
        	$domain_name_hash = StringUtil::hash_dn($group[domain_name]);
        	$whois_records = array();
        	
        	$params = array_merge(array('d'=>$domain_name_hash, 'c'=>1, 'i'=>$group['id'],'search_type'=>$options['search_type']),
                                                                 array_key_pick($_REQUEST, 'term')
                                                                
                                                            );
                                                        
        	$whois_records[] = "<a href=\"".
            	build_url($whois_record_path, $params)
            	. "\" class=\"whois_record\">" . 'Current' . '</a>';   
            $dates=$domain_dates[$clear_domain_name];
           
            if($dates){	
            	foreach($dates as $date){
            		//if($date['id']!=$group['id']){//not current date
            			$cur_param = array_merge(array('d'=>$domain_name_hash, 'i'=>$date['id'], 'w'=>$date['audit_updated_date'], 'search_type'=>$options['search_type']),
                                                                 array_key_pick($_REQUEST, 'term')
                                     );
                                  
            			$whois_records[] = "<a href=\"".
            				build_url($whois_record_path, $cur_param)
            					. "\" class=\"whois_record\">" . date('m/d/Y',$date['audit_updated_date']) . '</a>';  
            		//}
            	} 
            }
            $response->rows[$i]['cell']=array($domain_name, implode(' ', $whois_records));
        	$i++;	
      }	
   		
    }
    if($domain_name_only)return $response;
    
   
    
    if(!$meta_only){
      $response->page=$page;
      $response->records = $meta_data['total_found']; 
      $response->total = ceil($response->records/$limit); 
      $response->stats['current_total_count'] =  $response->records;
      $response->stats['num_credits']= Report::compute_credits_required(min($MAX_DOMAIN_SEARCH_MATCH,$response->stats['current_total_count']));		
      $response->stats['current_report_price'] = compute_current_report_price($response->stats);
      $response->stats['current_price_discount'] = $current_price_discount;
      
       $search_terms = $options['search_terms'];
      $response->search_terms_disp = Report::render_search_terms_s($search_terms['include'], $search_terms['exclude']);
      $response->search_type = $options['search_type'];
    }
    if($meta_only)return $meta_data;
   
   	Report::get_errors($response);
   
    return $response;  
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
  	if($response->stats['current_total_count'] > $MAX_DOMAIN_SEARCH_MATCH){
    	
    	//$response->current_error=Report::createMaxErrorMsg($MAX_DOMAIN_SEARCH_MATCH);
    	$response->current_warning=Report::createMaxWarningMsg($MAX_DOMAIN_SEARCH_MATCH);
    }
    else if($response->stats['current_total_count']  <=0){
    	
    	$response->current_error=Report::createMInErrorMsg();
    	
    }
    if($response->stats['history_total_count'] + $response->stats['current_total_count'] > $MAX_DOMAIN_SEARCH_MATCH){
    	
    	$response->history_error=Report::createMaxErrorMsg($MAX_DOMAIN_SEARCH_MATCH);
    }
    else if($response->stats['history_total_count'] + $response->stats['current_total_count'] <=0){
    	$response->history_error=Report::createMInErrorMsg();
    }
  
    if($response->search_type == 2){
    	$response->error = $response->history_error;
    }
    else $response->error = $response->current_error;
  }
  public static function createMaxErrorMsg($max)  { 
  	return "Your report is too large to process.<br/>
Your search has surpassed our results limit of $max domains. " .
		"Please refine your search or <a href=\"mailto:support@whoisxmlapi.com\">contact us</a> to help you creating a search query suitable for your specific needs.";
	}
	
  public static function createMaxWarningMsg($max)  { 
  	return "Your search result is truncated down to our limit of $max domains";
}
		
  public static function createMinErrorMsg()  { 
  	return "Your search did not yeild any result.<br/> " .
		"Please modify your search or <a href=\"mailto:support@whoisxmlapi.com\">contact us</a> to help you creating a search query suitable for your specific needs.";
  
	}	
}

?>