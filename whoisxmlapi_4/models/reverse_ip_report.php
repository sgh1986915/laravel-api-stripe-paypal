<?php 
  require_once dirname(__FILE__) . "/../util.php";
  require_once dirname(__FILE__) . "/../users/whois_server.inc";
  require_once dirname(__FILE__) . "/../util/string_util.php";
  require_once dirname(__FILE__) . "/../reverse-ip/prices.php";
  date_default_timezone_set('America/Los_Angeles');
  ini_set('memory_limit','-1');
  ini_set("max_execution_time", 0);
  
  require_once dirname(__FILE__) . "/../reverse-ip/config.php";
class ReverseIPReport{
  public static $TERM_GLUE = " ";
 //http://localhost/api/reverseip/ripple.coop?outputFormat=XML&username=root&password=PASSWORD
  var $error=false;
  var $input="";
  var $results=false;
  var $num_credits=1;

    public function isInputIP(){
        return filter_var($this->input,FILTER_VALIDATE_IP);
    }
    public function compute_credits(){
        $this->num_credits=1;
    }


    function getReportName(){
        return $this->input;
    }
    function getPrice(){
        global $riRegularReportPrice;
        return $riRegularReportPrice;
    }
    function equals($report){
        if(!is_object($report))return false;
        return strcasecmp($this->input, $report->input) == 0;
    }

    public function getNumResults(){
        if(is_object($this->results)){
            return $this->results->grand_total;
        }
        if(isset($this->num_domains)){
            return $this->num_domains;
        }
        return 0;
    }
    public function getDomainsDetails(){
        if(is_object($this->results)){
            return $this->results->domains_details;
        }
        if(is_object($this->domains_details)){
            return $this->domains_details;
        }
        return false;
    }
    public function getIPs(){
        if(is_object($this->results)){
            return $this->results->ips;
        }
        if(isset($this->ips)){
            return $this->ips;
        }
        return false;
    }
    public function getDomainNames(){
        if(is_object($this->results)){
            return $this->results->domain_names;
        }
        if(isset($this->domain_names)){
            return $this->domain_names;
        }
        return "";

    }

        //(array('start'=>$start,'page'=>$page,'limit'=>$limit,  'input'=>$input)
    public static function get_reverseip_preview($params){
       $MAX_DOMAINS_PREVIEW=3;
        $res = self::transform_api_results(self::call_reverseip_api($params), $params);
        if($res){
            $domains_preview=array();
            $domains=$res->rows;

            $n=min($MAX_DOMAINS_PREVIEW, $res->records);
            for($i=0;$i<$n;$i++){
                $domains_preview[]=$domains[$i];
            }
            $limit=$params['limit'];
            $res->rows=$domains_preview;
            $res->records=count($res->domains);
            $res->total = $res->records/$limit;
            $res->more_domains=$res->grand_total-$MAX_DOMAINS_PREVIEW;
        }
        return $res;
    }

    public static function get_report_from_request(){
        global $riRegularReportPrice;
        $r = new ReverseIPReport();
        $r->input=$_REQUEST['input'];
        $r->price=$riRegularReportPrice;
        $r->results=self::call_reverseip_api(array('input'=>$r->input, 'showDomainsDetails'=>1));
        return $r;
    }

    //(array('start'=>$start,'page'=>$page,'limit'=>$limit,  'input'=>$input)
    public static function get_reverseip_results($params){
        return self::transform_api_results(self::call_reverseip_api($params), $params);

    }
    public static function transform_api_results($api_res, $params){
        $cells=array();
        $limit=$params['limit'];
        //print_r($api_res->domains_details);
        if(is_array($api_res->domain_names)){
            $domains_details=$api_res->domains_details;

            foreach($api_res->domain_names as $domain_name){
                $whois_url="http://whois.whoisxmlapi.com/$domain_name";
                $view_link="<a href=\"$whois_url\" class=\"ignore_jssm\" target=_blank>View</a>";
                $view_ips="";

                if($domains_details && $domains_details->$domain_name){

                    $domain_detail=$domains_details->$domain_name;


                    if($domain_detail->ips){
                        $view_ips=implode(' ',$domain_detail->ips);
                    }
                }

                $cells[]=array('cell'=>array($domain_name,$view_link, $view_ips));

            }
        }
        $res = new stdClass();
        $res->rows=$cells;

        $res->page=$params['page'];
        if($api_res){
            $res->records = $api_res->grand_total;
            $res->total = $res->records/$limit;
            $res->ips=$api_res->ips;
            $res->grand_total=$api_res->grand_total;

        }

        return $res;
    }

    public static function call_reverseip_api($params){
        global $REVERSE_IP_API_URL;
        $input=$params['input'];
        $page=$params['page'];
        $limit=$params['limit'];
        $start=$params['start'];
        $showDomainsDetails=$params['showDomainsDetails'];

        $url="$REVERSE_IP_API_URL/$input?outputFormat=JSON&username=root&password=PASSWORD&offset=$start&limit=$limit&page=$page&showDomainsDetails=$showDomainsDetails";

        $res=file_get_contents($url);

        if($res){
           return json_decode($res);
        }
        return false;
    }
    function db_init(){
        if(!connect_to_whoisserver_db()){
            $this->error = "ReverseIPReport->db_init failed: ".mysql_error();
            return false;
        }
        return true;

    }
    function save_report($param){
        if(!$this->db_init())return false;

        $SQL = sprintf("insert into reverseip_report(username, input, domain_names, domains_details, price, num_domains, ips, created_date, updated_date)
                value('%s','%s', '%s', '%s',  %f, %d,  '%s', now(), now())",
            $param['username'],
            $this->input,
            implode(ReverseIPReport::$TERM_GLUE, $this->getDomainNames()),
            json_encode($this->getDomainsDetails()),
            $this->getPrice(),
            $this->getNumResults(),
            implode(ReverseIPReport::$TERM_GLUE,$this->getIPs())
        );

        $res = mysql_query($SQL);
        if(!$res){
            $this->error = 'ReverseIPReport->save_report() failed: '.mysql_error();
            return false;
        }
        $this->report_id = mysql_insert_id();
        return true;
    }

    public static function get_report_from_db_row($row){
        $r=new ReverseIPReport();

       // print_r($row);

        StringUtil::copy_obj($row, $r, array('domain_names'=>'ReverseIPReport::explode_terms_strlower', 'ips'=>'ReverseIPReport::explode_terms_strlower',
            'domains_details'=>'json_decode'
        ));

        return $r;
    }
    public static function explode_terms($str){

        return StringUtil::explode_trim(self::$TERM_GLUE, $str);
    }
    public static function explode_terms_strlower($str){

        return StringUtil::explode_trim_strlower(self::$TERM_GLUE, $str);
    }

}

?>