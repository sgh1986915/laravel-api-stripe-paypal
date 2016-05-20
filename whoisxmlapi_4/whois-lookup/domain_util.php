<?php
$URL_PAT='(((http|https|ftp):\/\/)?([a-zA-Z0-9-]+\\.)+[a-zA-Z]{2,}([\/a-zA-Z0-9-\.~]*))'; 
$EMAIL_PAT='[a-zA-Z0-9._%-]+@([a-zA-Z0-9-]+\\.)+[a-zA-Z]{2,4}';
$IP_PAT= '\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}';

function clean_domain($q){
    $q = trim($q);
    return preg_replace('/[^a-zA-Z0-9-\.]+/', '', $q);
  }
function clean_date($s){
	return trim_wild_chars($s);
}
function trim_wild_chars($s){
	if(!$s)return $s;
	$char_set='.:';
	return ltrim(rtrim($s,$char_set), $char_set);
}
function clean_replacements($array){
	$ret=array();
	foreach($array as $val){
		$ret[]=trim($val);
	}
	return $ret;
}
function add_to_array(&$array, $item, &$replaced){
	if(!in_array($item,$replaced)){
		//echo "$item is not in replaced<br/>"; 
		array_push($array,$item);
		array_push($replaced,$item);
	}
}
function linkify_str_regex($whois_record, $s, $link_base, &$replaced){
	global $URL_PAT, $EMAIL_PAT, $IP_PAT;
  $pat = implode('|' ,array($EMAIL_PAT, $IP_PAT, $URL_PAT));
  $matched =array();
  preg_match_all( '/' . $pat. '/', $s, $matched);
  
  foreach($matched as $match){
  	foreach($match as $m)
  		add_to_array($replaced,$m, $replaced);
  }
  $res = preg_replace('/' . $pat. '/', '<a title="click to reverse whois search" style="text-decoration:none;" target="_blank" rel="nofollow" href="' . $link_base . '\\0">'  . "\\0</a>" , $s);
  
  return $res;	
}
function linkify_str_parsed($whois_record, $s, $link_base, &$replaced){
	$fields = array();
	
	$dates = array(
				clean_date($whois_record->createdDate),
				clean_date($whois_record->updatedDate), 
				clean_date($whois_record->expiresDate)			
	);
	foreach($dates as $date)add_to_array($fields, $date, $replaced);
	if($whois_record->registrarName)add_to_array($fields,$whois_record->registrarName,$replaced);
	if($whois_record->whoisServer)add_to_array($fields,$whois_record->whoisServer,$replaced);
	if($whois_record->referralURL)add_to_array($fields,$whois_record->referralURL,$replaced);
	if($whois_record->nameServers){
		//print_r( $whois_record->nameServers);
		$ns = $whois_record->nameServers;
		if($ns){
			if($ns->hostNames){
				foreach($ns->hostNames as $dns){
					add_to_array($fields,$dns,$replaced);
				}
			}
			if($ns->ips){
				foreach($ns->ips as $dns){
					add_to_array($fields,$dns,$replaced);
				}
			}
		}
	}
	$contacts = array($whois_record->registrant, $whois_record->administrativeContact, $whois_record->billingContact, $whois_record->technicalContact, $whois_record->zoneContact);

	foreach($contacts as $contact){
		if($contact){
			if($contact->name)add_to_array($fields, $contact->name,$replaced);
			if($contact->organization)add_to_array($fields, $contact->organization,$replaced);
			if($contact->telephone)add_to_array($fields, trim_wild_chars($contact->telephone),$replaced);
		
		}
	}
	
	
	$fields = clean_replacements($fields);
	
	foreach($fields as $search){ 
		if($search && strlen($search)>0){ 
  			$rep = '<a title="click to do reverse whois search on '. $search . '" style="text-decoration:none;" target="_blank" rel="nofollow" href="' . $link_base . $search. '">'  . "$search</a>";
  			
  			$s = str_replace($search, $rep , $s);
		}
	}
	return $s;	
}
function linkify_str($whois_record, $res, $link_base){
	$replaced = array();
	$res = linkify_str_regex($whois_record,$res,$link_base, $replaced);
	
	//print_r($replaced);
	$res = linkify_str_parsed($whois_record,$res,$link_base, $replaced);
	
	return $res;
	
}  


  
?>