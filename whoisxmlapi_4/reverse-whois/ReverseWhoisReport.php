<?php 
if(isset($V2)) require_once dirname(__FILE__) . "/../reverse-whois-v2/config.php";
  else if(isset($V1)){ 
  	require_once dirname(__FILE__) . "/../reverse-whois-v1/config.php";
  }
else require_once dirname(__FILE__) . "/config.php";
  require_once dirname(__FILE__)."/../users/whois_server.inc";
  
class ReverseWhoisReport{
  protected $searchTerm;
  protected $defaultLimit = 10000;
  
  public function __construct($searchTerm){
    $this->searchTerm = $searchTerm;
  }
  public function generateReport($options){
    if(!connect_to_whoiscrawler_index_db())return;
    $term = mysql_escape_string($this->searchTerm);
    if(isset($options['limit']) && ctype_digit($options['limit'])) $limit = $options['limit'];
    else $limit = $this->defaultLimit;
    $SQL = "select domain_name from whois_index where match ('@raw_text " . $term. "') LIMIT $limit";
    $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error() . " <br/>query:$SQL");
    $domainNames = array();
    while($row = mysql_fetch_array($result,MYSQL_ASSOC)) { 
      $domainNames[] = $row['domain_name'];
    }
   
    if(isset($options['save_to'])) $this->saveWhoisReport(array('domain_names'=>$domainNames, 'search_term' => $this->searchTerm, 'save_to', $options['save_to']));
    return $domainNames;
  }
  public function saveWhoisReport($options){
     echo "saveWoisReport ".print_r($options,1);
    if(!connect_to_whoisserver_db())return;
    $SQL = sprintf("insert into whois_report (search_term, username, domain_names, created_date, updated_date) ".
            "values ('%s', '%s', '%s', now(), now())", 
            mysql_real_escape_string($options['search_term']), 
            mysql_real_escape_string($options['save_to']),
            mysql_real_escape_string(implode(" ",$options['domain_names']))
           );
    mysql_query($SQL) or die("error in saveWhoisReport ".mysql_error(). " <br/>query: $SQL");
      
            
            
  }
}
?>