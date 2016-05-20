<?php 
	include __DIR__."/PriceCalc.php";
	
	$input_units = $_REQUEST['units'];
	$input_discount = $_REQUEST['discount'];
	
	if (!$input_units) {
		echo "must specified units";
		exit;
	}

	if (!$input_discount) {
		$input_discount = 0;
	}
	
	$resPrice = PriceCalc::calculateWhoisQueryPrice($input_units, $_REQUEST);

	$resPrice = $resPrice - $resPrice/100*$input_discount;

	echo "Price for $input_units units is $resPrice";
	echo "<br>Discount is $input_discount";
	echo '<br><a href="/custom_order.php?special_order=1&item_name=%24num_queries&price='.$resPrice.'">Custom Payment</a>';
?>

