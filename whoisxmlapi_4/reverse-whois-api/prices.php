<?php
  $historic_price_discount = 0;
  $current_price_discount = 0;
  
  //$price_interval=array(10,100,250,500,1000,2500,5000,10000,-1);
  //$current_price=array(49,99,199,299,399,499,749,999,0.10);
  //$historic_price = array(147,297,597,897,1197,1497,2247,2997,0.30);
  
  	$price_interval=array(10,100,250,500,1000,2500,5000,10000,-1);
  	$current_price=array(19, 29, 39, 49, 59, 69, 79, 89, 0.01);
  	$historic_price=array(19, 29, 39, 49, 59, 69, 79, 89, 0.01);
  
  
	$rwQueryPrices = array(5=>9, 10=>15, 25=>30, 50=>50,
	100=>80, 250=>150, 500=>250,
	1000=>400, 2500=>800, 5000=>1200,
	10000=>1800, 25000=>3000, 50000=>5000,
	
	);
	$rwMembershipPrices = array(5=>9, 10=>15, 25=>30, 50=>50,
	100=>80, 250=>150, 500=>250,
	1000=>400, 2500=>800, 5000=>1200,
	10000=>1800, 25000=>3000, 50000=>5000,
	
	);
	
	foreach($rwQueryPrices as $key=>$val){
		$rwQueryPrices[$key] = $val * 6;
	}
	$rwQueryCount = count($rwQueryPrices);
	$rwQueryAmount = array_keys($rwQueryPrices);
	foreach($rwMembershipPrices as $key=>$val){
		$rwMembershipPrices[$key] = $val * 4;
	}
	
	
	
	$rwMembershipCount = count($rwMembershipPrices);
	$rwMembershipAmount = array_keys($rwMembershipPrices);
	
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
 
    
  function compute_history_report_price($options){
    global $price_interval, $current_price, $historic_price, $historic_price_discount,  $current_price_discount;
    /*
    $h=$options['history_total_count'];
    $c =$options['current_total_count'];
    $n = count($price_interval);
    //echo "h=$h c=$c n=$n";
    $price = compute_report_price($h, $price_interval, $historic_price)  + compute_report_price($c, $price_interval, $current_price);
    return $price * (1-$historic_price_discount);
    */
    
    $c = $options['total_count'] ;
    if(!$c) $c=$options['history_total_count'];
    
    return compute_report_price($c, $price_interval, $historic_price) * (1-$historic_price_discount);
  }
  function compute_current_report_price($options){
    global $price_interval, $current_price, $historic_price, $current_price_discount;
   $c = $options['total_count'] ;
    if(!$c) $c=$options['current_total_count'];
    
    return compute_report_price($c, $price_interval, $current_price) * (1-$current_price_discount);
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