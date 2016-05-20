<?php

/* modified */
global $promo_start;
global $promo_end;
global $current_price_discount;
global $price_interval;
global $current_price;
global $riQueryPrices;
global $riMembershipPrices;
global $monthlyFactor;
global $payAsYouGoFactor;
global $riQueryCount;
global $riQueryAmount;
global $riMembershipCount;
global $riMembershipAmount;
global $riLowestPricePerReport;
global $riRegularReportPrice;

$promo_start = date( "M d", strtotime ( '-3 day' , time() ));
$promo_end = date( "M d", strtotime ( '+3 day' , time() ));


$current_price_discount = 0;//0.6;

$price_interval=array(1000);
///$current_price=array(49,99,199,299,399,499,749,999,0.10);
$current_price=array(19);
/*
$discount_fac=3;
foreach($current_price as $key=>$val){
    $current_price[$key]=floor($val/$discount_fac);
}
*/


//double=>1.5
$riQueryPrices = array(
    1000=>15, 2500=>30, 5000=>50,
	10000=>80, 25000=>150, 50000=>250,
	100000=>400/*,  250000=>800,, 500000=>1200
	1000000=>1800, 2500000=>2500*/

);
$riMembershipPrices = array(
  2000=>15, 5000=>30,
 10000=>50,20000=>80, 50000=>150, 100000=>250,
		200000=>400, /*500000=>800, 1000000=>1200,
		2000000=>1800,*/
    'unlimited'=>999

);




$monthlyFactor=2;

$payAsYouGoFactor=4;
foreach($riQueryPrices as $key=>$val){
    $riQueryPrices[$key] = $val * $payAsYouGoFactor;
}

foreach($riMembershipPrices as $key=>$val){
    if($key!='unlimited') $riMembershipPrices[$key] = $val * $monthlyFactor;
}


$riQueryCount = count($riQueryPrices);
$riQueryAmount = array_keys($riQueryPrices);




$riMembershipCount = count($riMembershipPrices);
$riMembershipAmount = array_keys($riMembershipPrices);

$riLowestPricePerReport = $riMembershipPrices[$riMembershipAmount[count($riMembershipAmount)-2]] / $riMembershipAmount[count($riMembershipAmount)-2];
$riRegularReportPrice=19;

/*
function print_pricing(){
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


function compute_current_report_price($options){
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
*/
?>