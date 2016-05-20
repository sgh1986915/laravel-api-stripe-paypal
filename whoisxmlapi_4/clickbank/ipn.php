<?php
require_once(dirname(__FILE__) . '/../payment/payment_util.php');

  function cb_ipn() {

	$key='TOPCODER1';
	$ccustname = $_REQUEST['ccustname'];
	$ccustemail = $_REQUEST['ccustemail'];
	$ccustcc = $_REQUEST['ccustcc'];
	$ccuststate = $_REQUEST['ccuststate'];
	$ctransreceipt = $_REQUEST['ctransreceipt'];
	$cproditem = $_REQUEST['cproditem'];
	$ctransaction = $_REQUEST['ctransaction'];
	$ctransaffiliate = $_REQUEST['ctransaffiliate'];
	$ctranspublisher = $_REQUEST['ctranspublisher'];
	$cprodtype = $_REQUEST['cprodtype'];
	$cprodtitle = $_REQUEST['cprodtitle'];
	$ctranspaymentmethod = $_REQUEST['ctranspaymentmethod'];
	$ctransamount = $_REQUEST['ctransamount'];
	$caffitid = $_REQUEST['caffitid'];
	$cvendthru = $_REQUEST['cvendthru'];
	$cbpop = $_REQUEST['cverify'];

	$xxpop = sha1("$ccustname|$ccustemail|$ccustcc|$ccuststate|$ctransreceipt|$cproditem|$ctransaction|"
		."$ctransaffiliate|$ctranspublisher|$cprodtype|$cprodtitle|$ctranspaymentmethod|$ctransamount|$caffitid|$cvendthru|$key");

    $xxpop=strtoupper(substr($xxpop,0,8));
	$log_msg =  '['.date('m/d/Y g:i A').'] - \n'  . print_r($_REQUEST,1);

    if ($cbpop==$xxpop) {
		$log_msg.="\nVerified";
    	if(strcasecmp($ctransaction, 'SALE')===0){//first time sale or bill
    		$log_msg.="\nSALE";
    		$accountUserName="";
			$userdata = explode('&', $cvendthru);
			for($i=0;$i<count($userdata);$i++){
				$log_msg.="\n userdata $i: ".print_r($userdata[$i],1);
				$pair = explode('=',$userdata[$i]);
				if(count($pair)>1){
					if($pair[0] == 'order_username'){
						$accountUserName = $pair[1];
					}
				}
			}
    		if(strcasecmp($cprodtype, 'STANDARD') === 0){
    			$log_msg.="\n userName is $accountUserName, cprodtitle is $cprodtitle";
    			fillStandardSale($cprodtitle, $accountUserName, $ccustemail);
				$log_msg .="\n STANDARD SALE";

			}
			else if(strcasecmp(cprodtype, 'RECURRING') === 0){//recurring
				fillRecurringSale(cprodtitle, $accountUserName, $ccustemail);
				$log_msg .="\n RECURRING SALE";
			}
		}
		else if (strcasecmp(ctransaction, 'BILL')){//auto refill later

		}

		ipn_log($log_msg);
	}
	else {
		ipn_log($log_msg);
		return 0;
	}

}
function ipn_log($text){
	 $fp=fopen("ipn_results.log",'a');
      fwrite($fp, $text . "\n\n");

      fclose($fp);  // close file
}
function fillStandardSale($item_name, $accountUserName, $ccustemail){
	$index = stripos($item_name, " whois queries");
		error_log("index is $index");
			if($index > 0){
				$quantity = trim(substr($item_name, 0, $index));
				//$accountUserName = trim(substr($item_name, $index+strlen(" whois queries")));
				error_log("$quantity  $accountUserName");
				if(is_numeric($quantity) && !empty($accountUserName)){
					$fulfillRes = whoisAddQuantity($quantity, $accountUserName);
					error_log("fulfill $fulfillRes");

					if($fulfillRes) emailOrderProcessed($ccustemail, array('accountUserName'=>$accountUserName,
						'orderQuantity'=>$quantity, 'item_name'=>$item_name));

				}
			}
}
function fillRecurringSale($item_name, $accountUserName, $ccustemai){
				$occ="ly membership subscription of ";
				$index = stripos($item_name, $occ);
				if($index >0){
					$membership_payperiod = substr($item_name, 0, $index);
					$quantity = substr($item_name, $index + strlen($occ), strrpos($item_name, "queries")-strlen($occ)-$index-1);
					error_log($item_name);
					error_log("hi $quantity, $membership_payperiod, $accountUserName, ". strrpos($item_name, "queries"));
					if(is_numeric($quantity) && !empty($accountUserName)){
						$fulfillRes = whoisFillQuantity($quantity, $accountUserName, array('membership_payperiod'=>$membership_payperiod));

						if($fulfillRes) emailOrderProcessed($ccustemail, array('accountUserName'=>$accountUserName,
							'orderQuantity'=>$quantity, 'item_name'=>$item_name,
							'extraBody' =>"Your account's query balance will be reset to $quantity every month on day ".date('d') ."."
							));

					}
				}
}

?>

<?php
	cb_ipn();
	//fillStandardSale("1000 whois queries", "root", 'topcoder1@gmail.com');
?>
