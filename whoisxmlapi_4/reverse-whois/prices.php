<?php

// modified
global $promo_start;
global $promo_end;
global $historic_price_discount;
global $current_price_discount;
global $price_interval;
global $current_price;
global $historic_price;
global $rwQueryPrices;
global $rwMembershipPrices;
global $payAsYouGoFactor;
global $monthlyFactor;
global $rwQueryCount;
global $rwQueryAmount;
global $rwMembershipCount;
global $rwMembershipAmount;
global $lowestPricePerReport;


function init() { // modified
    global $promo_start;
    global $promo_end;
    global $historic_price_discount;
    global $current_price_discount;
    global $price_interval;
    global $current_price;
    global $historic_price;
    global $rwQueryPrices;
    global $rwMembershipPrices;
    global $payAsYouGoFactor;
    global $monthlyFactor;
    global $rwQueryCount;
    global $rwQueryAmount;
    global $rwMembershipCount;
    global $rwMembershipAmount;
    global $lowestPricePerReport;// modified

  	$promo_start = date( "M d", strtotime ( '-3 day' , time() ));
  	$promo_end = date( "M d", strtotime ( '+3 day' , time() ));

  $historic_price_discount = 0;
  $current_price_discount = 0;//0.6;

  $price_interval=array(10,100,250,500,1000,2500,5000,10000,-1);
  ///$current_price=array(49,99,199,299,399,499,749,999,0.10);
  $current_price=array(19, 29, 59, 99, 129, 159, 249, 329, 0.02);
  /*
  $discount_fac=3;
  foreach($current_price as $key=>$val){
  	$current_price[$key]=floor($val/$discount_fac);
  }
  */

  $historic_price=$current_price;
  //$historic_price = array(147,297,597,897,1197,1497,2247,2997,0.30);


  	//$price_interval=array(10,100,250,500,1000,2500,5000,10000,-1);
  	//$current_price=array(19, 29, 39, 49, 59, 69, 79, 89, 0.01);
  	//$historic_price=array(19, 29, 39, 49, 59, 69, 79, 89, 0.01);
  /*
 	$rwQueryPrices = array(50=>9, 100=>15, 250=>30, 500=>50,
	1000=>80, 2500=>150, 5000=>250,
	10000=>400, 25000=>800, 50000=>1200

	);
	$rwMembershipPrices = array(50=>9, 100=>15, 250=>30, 500=>50,
	1000=>80, 2500=>150, 5000=>250,
	10000=>400, 25000=>800, 50000=>1200,
	100000=>1800, 250000=>3000, 500000=>5000,

	);
	*/

	$rwQueryPrices = array(5=>9, 10=>15, 25=>30, 50=>50,
	100=>80, 250=>150, 500=>250

	);
	$rwMembershipPrices = array(5=>9, 10=>15, 25=>30, 50=>50,
	100=>80, 250=>150, 500=>250, 'unlimited'=>375

	);



	$payAsYouGoFactor=6;
	$monthlyFactor=4;
	foreach($rwQueryPrices as $key=>$val){
		$rwQueryPrices[$key] = $val * $payAsYouGoFactor;
	}
	$rwQueryCount = count($rwQueryPrices);
	$rwQueryAmount = array_keys($rwQueryPrices);
	foreach($rwMembershipPrices as $key=>$val){
		$rwMembershipPrices[$key] = $val * $monthlyFactor;
	}



	$rwMembershipCount = count($rwMembershipPrices);
	$rwMembershipAmount = array_keys($rwMembershipPrices);

	$lowestPricePerReport = $rwMembershipPrices[$rwMembershipAmount[count($rwMembershipAmount)-2]] / $rwMembershipAmount[count($rwMembershipAmount)-2];

}// modified
  /*
  //implicit discount
  $cur_discount = 10;
  $his_discount = 20;
  foreach($current_price as $key=>$val){
    if($val>1)$current_price[$key]-=$cur_discount;
  }
  foreach($historic_price as $key=>$val){
    if($val>1)$historic_price[$key]-=$his_discount;
  }
  */
  //explicit discount
 //print_pricing();
  function print_pricing(){
    init(); // modified
  	global $current_price, $price_interval;
  	$i=0;
  	$SEP="&nbsp;&nbsp;&nbsp;&nbsp;";

  	foreach($current_price as $val){
  		$unit_price = $val/$price_interval[$i];
  		$total_price = $val;

  		echo $price_interval[$i].$SEP.$unit_price. $SEP . $total_price;
  		echo "<br/>";
  		$i++;
 	 }
  }

  function compute_history_report_price($options){
    init(); // modified
    global $price_interval, $current_price, $historic_price, $historic_price_discount,  $current_price_discount;
    $h=$options['history_total_count'];
    $c =$options['current_total_count'];
    $n = count($price_interval);
    //echo "h=$h c=$c n=$n";
    $price = compute_report_price($h, $price_interval, $historic_price)  + compute_report_price($c, $price_interval, $current_price);
    return $price * (1-$historic_price_discount);
  }
  function compute_current_report_price($options){
    init(); // modified
    global $price_interval, $current_price, $historic_price, $current_price_discount;
    $c =$options['current_total_count'];

    return round(compute_report_price($c, $price_interval, $current_price) * (1-$current_price_discount), 1);
  }
  function compute_report_price($num, $price_interval, $price){
    if($num<=0)return 0;
    $n = count($price_interval);
    for($i=0;$i<$n;$i++){
      if($price_interval[$i] < 0){
        return $num * $price[$i];
      }
      if($num<$price_interval[$i]){
        return $price[$i];
      }
    }
    return 0;
  }
?>