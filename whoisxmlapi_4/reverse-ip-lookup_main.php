<?php 
  require_once dirname(__FILE__)."/CONFIG.php";
  require_once dirname(__FILE__).'/util.php';
  $tab="reverse-ip-lookup";
  if(isset($_REQUEST['tab'])){
    $tab = $_REQUEST['tab'];
  }
  $unlimited=0;
  if(isset($_REQUEST['unlimited']) && $_REQUEST['unlimited']){
  	$unlimited=1;
  }

?>

<div id="rw_main" class="ui-tabs">
   <ul>
      <li><a href="#reverse-ip-lookup" class="ignore_jssm"  class="ui-tabs-hide">New Reverse IP Lookup</a></li>
      <li><a href="#reverse-ip-pricing" class="ignore_jssm">Pricing</a></li>
      <?php if(isLoggedIn()){?>
        <li><a href="#my-reverse-ip-reports" class="ignore_jssm">My Reverse IP Reports</a></li>
      <?php }?>  
   </ul>
   <div id="reverse-ip-lookup" class="ui-tabs-hide">
      <?php include dirname(__FILE__)."/reverse-ip-search_main.php";?>
   </div>
   <div id="reverse-ip-pricing" class="ui-tabs-hide">
       <?php include dirname(__FILE__)."/reverse-ip/reverse-ip-pricing_main.php";?>
   </div>
   <?php if(isLoggedIn()){?>
      <div id="my-reverse-ip-reports" class="ui-tabs-hide">
        <?php include dirname(__FILE__)."/reverse-ip/my-reports_main.php";?>
      </div>   
   <?php }?>  
</div>
<script type="text/javascript">
$(document).ready(function(){ 
  $('#rw_main').tabs();
  $('#rw_main').tabs('option','ajaxOptions',{dataType:'html'});
  $('#rw_main').tabs('option','cache',true);
  $('#rw_main').tabs('select', '<?php echo $tab;?>');
  

});
</script> 