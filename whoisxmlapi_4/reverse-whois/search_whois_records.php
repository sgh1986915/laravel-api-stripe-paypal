<?php
error_reporting(0); // modified
@ini_set('display_errors', 0); // modified
set_time_limit(100);
//print_r($_REQUEST);

$MAX_SEARCH_TERMS = 4;
$time = microtime(true);
function all_search_terms_empty(){
  global $MAX_SEARCH_TERMS;

  for($i=1;$i<=$MAX_SEARCH_TERMS;$i++){

    if(isset($_REQUEST["term$i"]) && trim($_REQUEST["term$i"])){
      return false;
    }
    if(isset($_REQUEST["exclude_term$i"]) && trim($_REQUEST["exclude_term$i"])){
      return false;
    }
  }
  return true;
}
function limit_max_search($res){
	global $MAX_DOMAIN_SEARCH_MATCH;
	if($res->stats){
		$stats = $res->stats;
		$recomp=false;
		if(isset($stats['current_total_count']) && $stats['current_total_count'] > $MAX_DOMAIN_SEARCH_MATCH){
			$res->stats['current_total_count'] = $MAX_DOMAIN_SEARCH_MATCH;
			$recomp=true;
		}
		if(isset($stats['history_total_count']) && $stats['history_total_count'] > $MAX_DOMAIN_SEARCH_MATCH){
			$res->stats['history_total_count'] = $MAX_DOMAIN_SEARCH_MATCH;
			$recomp=true;
		}
		if($recomp){
			$res->stats['current_report_price'] = compute_current_report_price(array(
                                      'current_total_count'=>$res->stats['current_total_count']
                              ));
			$res->stats['history_report_price'] = compute_history_report_price(array('history_total_count'=>$res->stats['history_total_count'],
                                      'current_total_count'=>$res->stats['current_total_count']
                                      ));
		}

	}
}

if(all_search_terms_empty())return;
if(isset($V2))
  	require_once dirname(__FILE__) . "/../reverse-whois-v2/config.php";
else if(isset($V1)){
  	require_once dirname(__FILE__) . "/../reverse-whois-v1/config.php";
}
else {
	require_once dirname(__FILE__) . "/config.php";
}
if(isset($_REQUEST['unlimited']) && $_REQUEST['unlimited']){
	$MAX_DOMAIN_SEARCH_MATCH = 500000;
	$MAX_SEARCH_CUTOFF= 500000;
}

require_once(__DIR__ ."/../util.php");
require_once(__DIR__ ."/prices.php");
require_once(__DIR__ ."/../models/report.php");

global $_debug_noncryptic_;

$search_type = 1;//current
if(isset($_REQUEST['search_type'])) $search_type = $_REQUEST['search_type'];




$page = $_GET['page']; // get the requested page
$limit = $_GET['rows']; // get how many rows we want to have into the grid
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
$sord = $_GET['sord']; // get the direction
if(!$sidx) $sidx = 'id';
if(!$limit)$limit=10;

// connect to the database
if(!connect_to_whoiscrawler_index_db())return;



$search_terms = Report::get_search_terms_from_request($MAX_SEARCH_TERMS);

if(empty($search_terms) || (!$search_terms['include'] && !$search_terms['exclude'])){
  echo '';
  return;
}
//if ($page > $total_pages)
//  $page=$total_pages;
$start = $limit*$page - $limit;
if($start<0)$start=0;


//echo "limit is $limit, page is $page";
// do not put $limit*($page - 1)
//$SQL = "select domain_name from whois_index where match ('" . $term. "') ORDER BY $sidx $sord LIMIT $start , $limit";

$res = '';
if($search_type == 2){
  $res = Report::get_history_whois_records_preview(array('start'=>$start,'page'=>$page,'limit'=>$limit, 'search_type'=>$search_type, 'search_terms'=>$search_terms));

}
else{
  $res = Report::get_current_whois_records_preview(array('start'=>$start,'page'=>$page,'limit'=>$limit, 'search_type'=>$search_type, 'search_terms'=>$search_terms));

}
if(is_object($res)){
  $res->search_type = $search_type;
  limit_max_search($res);
  if(isset($SHOW_QUERY_TIME)){
  	$res->time= number_format(microtime(true) - $time, 2);
  }

  $res = json_encode($res);
}
echo $res;

?>
