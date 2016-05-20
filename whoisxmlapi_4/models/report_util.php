<?php require_once dirname(__FILE__) . "/report.php";
require_once dirname(__FILE__) . "/reverse_ip_report.php";
class report_util{
  var $error = false;
   function db_init(){
    if(!connect_to_whoisserver_db()){
      $this->error = "Report->db_init failed: ".mysql_error();
      return false;
    }
    return true;
    
  }
  public function get_report_by_id($report_id, $report_type='R'){
    if(!$this->db_init()) return false;
    $table="";
    if($report_type=='R')$table="whois_report";
    else if ($report_type)$table="reverseip_report";
    $SQL = "select unix_timestamp(r.created_date) as created_date_long, r.* from $table r where report_id=$report_id";
    if($result=(mysql_query($SQL))){
      if($row = mysql_fetch_assoc($result)){
        if($report_type=='R')$rep = Report::get_report_from_db_row($row);
        else if ($report_type=='RI')$rep=ReverseIPReport::get_report_from_db_row($row);
        return $rep;
      }
      $this->error = "No report is found. ";
      return false;
    }
    else{
      $this->error = "get report by id $report_id failed: ".mysql_error();
      return false;
    }
  }
}
?>