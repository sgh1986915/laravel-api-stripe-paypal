<?php 
if(isset($V2)) require_once dirname(__FILE__) . "/../reverse-whois-v2/config.php";
  else if(isset($V1)){ 
  	require_once dirname(__FILE__) . "/../reverse-whois-v1/config.php";
  }
else require_once dirname(__FILE__) . "/config.php";
	require_once __DIR__ . "/prices.php";
  $search_type = isset($_REQUEST['search_type']) ? $_REQUEST['search_type'] : 1;
  
  $price_keys = array_keys($rwQueryPrices);
 
   $last_key = $price_keys[count($price_keys)-1];	
   	$lowestPricePerReport = $rwQueryPrices[$last_key]/$last_key;
		  
  function build_history_order_link(){
    global $app_root;
    return "$app_root/reverse-whois-order.php?".build_order_report_params(array('search_type'=>2,'add_report'=>1));
  }
  function build_current_order_link(){
    global $app_root;
    return "$app_root/reverse-whois-order.php?".build_order_report_params(array('search_type'=>1, 'add_report'=>1)) ;
  }  
  function build_order_report_params($extra_params=array()){
    return http_build_query(array_merge($_REQUEST, $extra_params));
    //return http_build_query($_REQUEST); 
  }
  
  function build_search_report_params($extra_params=array()){
    return http_build_query(array_merge($_REQUEST, $extra_params));
    //return http_build_query($_REQUEST); 
  }
  function preview_history_toggle($search_type){
     global $app_root;
   
     if($search_type == 1){
      return "<a href=\"$app_root/reverse-whois-lookup.php?" . build_search_report_params(array('search_type'=>2)). "\">Preview Historical Report</a>";    
     }
    else{
      return "View Historical Report below";  
    }
  }
  function preview_current_toggle($search_type){
     global $app_root;
   
     if($search_type == 2){
      return "<a href=\"$app_root/reverse-whois-lookup.php?" . build_search_report_params(array('search_type'=>1)) . "\">Preview Current Report</a>";    
     }
    else{
      return "View Current-Only Report below";  
    }
  }  
?>
<script type="text/javascript">
   function get_url_params_str(url){
    var i=url.indexOf('?');
    if(i>0){
      return url.substring(i);
    }
    return '';
  }
  
  $(document).ready(function(){

    $('#preview_historic_reports_b a').click(function(){
 
      var params= get_url_params_str($(this).attr('href'));
      jQuery("#revWhoisGrid").jqGrid('method', 'setGridParam",'postData', params).trigger('reload');
      return false;
    });
    
  });
</script>
<table id="revWhoisTable" style="width:100%;margin:auto;">
  <tr id="rw_stats" style="display:none">
  	<td colspan="3">
  		<div id="rw_search_terms"> </div>
  		<div id="rw_search_time" class=""></di>
  	</td>
  </tr>
  <tr>
  	<td id = "revWhoisError" colspan="3" class = "errorMsg" style="display:none">	
  		
  	</td>
  </tr> 	
  <tr>
    <td>
      <table>
        <tr>
          <td colspan = "2">
            Historical Records
          </td>
        </tr>
          
        <tr>
          <td>Current Domains</td>
          <td id="num_current_domains_h"></td>
        </tr>
        <tr>
          <td>Historical Domains</td>
          <td id="num_historic_domains"></td>
        </tr>
        <tr>
          <td>Standard Report Price</td>
          <td id="historic_report_price"></td>
        </tr>
         <tr>
          <td colspan=2 style="color:red;font-weight:bold;">  <a class="ignore_jssm" href="bulk-reverse-whois-order.php"> Order in bulk
          for as low as $<?php echo $lowestPricePerReport?> / report!<a/></td>
        </tr>
        
        <tr>
         <td id="preview_historic_reports_b" class="important_link">
                   <?php echo preview_history_toggle($search_type);?>
         </td>
        </tr>
         
        <tr>
          <td>
            <a id="order_historic_reports_b"  class="ignore_jssm important_link" href="<?php echo build_history_order_link();?>"><span>Order Historical</span></a>
          </td>
        </tr>
        
  
     
      </tr>
    </table>
  </td>

  <td>
     <div class="relative-left vertical-seperator"/>
  </td>    
  <td> 
    <table>
      <tr>
        <td colspan="2">
          Current Records Only
        </td>
      </tr>  
      <tr>
          <td>Current Domains</td>
          <td id="num_current_domains"></td>
      </tr>
      <tr>
          <td>Historical Domains</td>
          <td>Not Included</td>
      </tr>
        <tr>
          <td>Standard Report Price</td>
          <td id="current_report_price"></td>
        </tr>
         <tr>
          <td colspan=2 style="color:red;font-weight:bold;">  <a class="ignore_jssm" href="bulk-reverse-whois-order.php"> Order in bulk
          for as low as $<?php echo $lowestPricePerReport?> / report!<a/></td>
        </tr>
        <tr> 
          <td id="preview_cur_reports_b" class="important_link">
                      <?php echo preview_current_toggle($search_type);?>
         </td>
       </tr>   
            <tr>
              <td>
                <a id="order_cur_reports_b" onclick="" class="ignore_jssm important_link" a href="<?php echo build_current_order_link();?>"><span>Order Current</span></a>
              </td>
            </tr>
        
       
      </table>

  </td>  
 </tr>
</table>