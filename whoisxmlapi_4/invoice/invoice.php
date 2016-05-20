<?php require_once __DIR__ . "/../util/db_util.php";
require_once __DIR__ . "/invoice_cfg.php";

class Invoice{
	private $raw_data;
	private $invoice_content;
	public function __construct($row){
		$this->raw_data=$row;
		$this->invoice_content = json_decode($row['invoice_content'], true);
	}
	public function getRawData(){
		return $this->raw_data;
	}
	public function getInvoiceContentData($field){
		return $this->invoice_content[$field];
	}
	public function getInvoiceRawData($field){
		
		return $this->raw_data[$field];
	}
	
	public function getInvoiceAmount(){
		return isset($this->invoice_content['totalPrice']) ? $this->invoice_content['totalPrice'] : '';
	}
	public function getDescription(){
		return isset($this->invoice_content['item1Description']) ? $this->invoice_content['item1Description'] : '';
	}
	public static function get_invoice_from_db_row($row){
		$invoice = new Invoice($row);
	
		return $invoice;
	}
	public static function markInvoiceToCreate($params){
		$s="insert into invoice (invoice_num, invoice_desc, username, invoice_date, invoice_content, invoice_file_path, email_to, email_attempt, email_ccs, email_bccs) values(?, ?, ?, NOW(), ?, ?, ?, 3, ?, ?)";
		$err=false;
		$db_params=array(&$params['invoice_num'], &$params['invoice_desc'], &$params['username'], &$params['invoice_content'], &$params['invoice_file_path'], &$params['email_to'], &$params['email_ccs'], &$params['email_bccs']);
		$db_param_types='ssssssss';
		if(!DBUtil::executeUpdate($s, $db_params, $db_param_types, DBUtil:: $WHOISAPI_USER_ACCOUNT_DB,  $err)){
			echo "Failed to markInvoiceToCreate ".$err;
			return false;
		}
		return true;
		
	}
	public static function markUnPaidInvoiceToCreate($params){
		$s="insert into invoice (invoice_num, invoice_desc, username, invoice_date, invoice_content, invoice_file_path, email_to, email_attempt, paid, email_ccs, email_bccs) values(?, ?, ?, NOW(), ?, ?, ?, 3, 0, ?, ?)";
		$err=false;
		$db_params=array(&$params['invoice_num'], &$params['invoice_desc'], &$params['username'], &$params['invoice_content'], &$params['invoice_file_path'], &$params['email_to'], &$params['email_ccs'], &$params['email_bccs']);
		$db_param_types='ssssssss';
		if(!DBUtil::executeUpdate($s, $db_params, $db_param_types, DBUtil:: $WHOISAPI_USER_ACCOUNT_DB,  $err)){
			echo "Failed to markInvoiceToCreate ".$err;
			return false;
		}
		return true;
		
	}	
	public static function getInvoice($params){
		$s = "select * from invoice where ";
		$db_param_types="";
		$db_params=array();
		$i=0;
		foreach($params as $param_key=>$param_val){
			if($i++>0)$s.=" and ";
			$s.=" $param_key = ?";
			$db_param_types.="s";
			$db_params[]=&$param_val;
		
		}
		$err=false;
		$res = DBUtil::executeQuery($s, $db_params, $db_param_types, DBUtil::$WHOISAPI_USER_ACCOUNT_DB, $err);
		if($res && count($res)>0){
			return Invoice::get_invoice_from_db_row($res[0]);
		}
		return false;
	}
	public static function testMarkInvoiceToCreate(){
		
		$invoiceContent = array(
			"invoiceNumber"=>"13458",
			"sendTo"=>"Adrian Barret, adrian@exonar.com <br/>Exonar Ltd",
			"item1Quantity"=> "1",
			"item1Number"=>"1",
			"item1Description"=>"100 Reverse Whois queries to account Baldrick",
			"item1UnitPrice"=>"$480",
			"item1Price"=>"$480",
			"subtotal"=>"$480",
			"totalPrice"=>"$480"
		);
		Invoice::markInvoiceToCreate(array('invoice_num'=>'12345', 'invoice_desc'=>'test invoice', 'username'=>'root', 'invoice_content'=>self::arrayToJson($invoiceContent), 'invoice_file_path'=>'c:\\tmp\\test_invoice.pdf'));
		
	}
	public static function generateInvoiceFilePath($param){
		$invoice_num=$param['invoice_num'];
		$username=$param['username'];
		$email_to=$param['email_to'];
		$res=InvoiceCfg::$INVOICE_ROOT ;
		if($username){
			$res.="/$username";
		}
		else if($email_to){
			$res.="/$email_to";
		}
		$res.="/$invoice_num.pdf";
		return $res;
	}
	public static function arrayToJson($array){
		return json_encode($array);
		
	}
	public static function normalizeInvoiceDate($s){
		if(is_numeric($s)){
			return date('H:i:s M d, Y T',$s);
		}
		return $s;
	}
}
//Invoice::testMarkInvoiceToCreate();