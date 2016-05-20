<?php 
if(isset($V2)) require_once dirname(__FILE__) . "/../reverse-ip-v2/config.php";

else require_once dirname(__FILE__) . "/config.php";
	require_once __DIR__ . "/prices.php";

  $price_keys = array_keys($riQueryPrices);
 
   $last_key = $price_keys[count($price_keys)-1];	
   	$lowestPricePerReport = $riQueryPrices[$last_key]/$last_key;
		  

  function build_current_order_link(){
    global $app_root;
    return "$app_root/reverse-ip-order.php?".build_order_report_params(array( 'add_report'=>1)) ;
  }  
  function build_order_report_params($extra_params=array()){
    return http_build_query(array_merge($_REQUEST, $extra_params));
    //return http_build_query($_REQUEST); 
  }
  
  function build_search_report_params($extra_params=array()){
    return http_build_query(array_merge($_REQUEST, $extra_params));
    //return http_build_query($_REQUEST); 
  }

?>

<table id="revIPStatsTable" style="width:100%;margin:auto;display:none">

  <tr>

  <td> And <span id="rev_ip_num_other_domains"></span> other domains.<br/>
      <a id="order_cur_reports_b" onclick="" class="ignore_jssm important_link" style="color:red;font-weight:bold;" href="<?php echo build_current_order_link();?>">
          <span>Order This Report for $<?php echo $riRegularReportPrice?> or 1 credit</span></a> or

      <a class="ignore_jssm important_link"  href="<?php echo  $app_root . '/bulk-reverse-ip-order.php'?>"> Order reports in bulk
          for as low as $<?php echo $riLowestPricePerReport?> / report <a/></td>
        </tr>



  </td>  
 </tr>
</table>