

<?php include __DIR__."/PriceCalc.php";
	$input_units=$_REQUEST['units'];
	if(!$input_units){
		echo "must specified units";
		exit;
	}
	$username=$_REQUEST['username'];
	if(!$username){
		echo "username is missing";
		exit;
	}
	
	$resPrice = PriceCalc::calculateWhoisQueryPrice($input_units,$_REQUEST);
	if($_REQUEST['one_time']){
		$s="$input_units whois queries for $username";
	}
	else{
		$s="Monthly membership subscription of $input_units queries/month for $username&payperiod=month";
	}
	$s="https://www.whoisxmlapi.com/custom_order.php?special_order=1&item_name=$s&price=$resPrice";
	echo "$s";
?>