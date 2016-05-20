<?php
error_reporting(0); // modified
@ini_set('display_errors', 0); // modified
  require_once dirname(__FILE__)."/CONFIG.php";
  require_once dirname(__FILE__).'/util.php';
  $tab="reverse-whois-lookup";
  if(isset($_REQUEST['tab'])){
    $tab = $_REQUEST['tab'];
  }
  $unlimited=0;
  if(isset($_REQUEST['unlimited']) && $_REQUEST['unlimited']){
  	$unlimited=1;
  }

  //echo "unlimited is $unlimited";
?>

<div id="rw_main" class="ui-tabs">
   <ul>
      <li><a href="#reverse-whois-lookup" class="ignore_jssm"  class="ui-tabs-hide">New Reverse Whois Lookup</a></li>
      <li><a href="#reverse-whois-pricing" class="ignore_jssm">Pricing</a></li>
      <?php if(isLoggedIn()){?>
        <li><a href="#my-reverse-whois-reports" class="ignore_jssm">My Reverse Whois Reports</a></li>
      <?php }?>
   </ul>
   <div id="reverse-whois-lookup" class="ui-tabs-hide">
      <?php include dirname(__FILE__)."/reverse-whois-search_main.php";?>
   </div>
   <div id="reverse-whois-pricing" class="ui-tabs-hide">
       <?php include dirname(__FILE__)."/reverse-whois/reverse-whois-pricing_main.php";?>
   </div>
   <?php if(isLoggedIn()){?>
      <div id="my-reverse-whois-reports" class="ui-tabs-hide">
        <?php include dirname(__FILE__)."/reverse-whois/my-reports_main.php";?>
      </div>
   <?php }?>
</div>
<script type="text/javascript">
$(document).ready(function(){
  $('#rw_main').tabs();
  $('#rw_main').tabs('option','ajaxOptions',{dataType:'html'});
  $('#rw_main').tabs('option','cache',true);
  // $('#rw_main').tabs('select', '<?php echo $tab;?>');

 populate_rw_from_request();
});
function populate_rw_from_request(){
	<?php
		$terms=array('term1','term2','term3','term4', 'exclude_term1', 'exclude_term2', 'exclude_term3');
		foreach($terms as $term){
			if(isset($_REQUEST[$term])){
				$val=$_REQUEST[$term];

				echo "jQuery('#whoisform input[name=$term]').val(\"$val\");";
			}
		}
	?>


}
</script>