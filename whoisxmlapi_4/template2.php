<?php
	//require_once('FirePHPCore/FirePHP.class.php');
	//ob_start();
	//$firephp = FirePHP::getInstance(true);


  //echo $_SERVER['SCRIPT_NAME'];
  //echo '<br/>';
  include dirname(__FILE__)."/CONFIG.php";

	require_once(dirname(__FILE__)."/users/users.inc");
	require_once(dirname(__FILE__)."/util.php");
	require_once(dirname(__FILE__)."/httputil.php");

  require_once dirname(__FILE__) . "/models/report.php";

  require_once dirname(__FILE__) . "/db_cart/db_cart_class.php"; 

  require_once dirname(__FILE__) . "/db_cart/cart_util.php"; 

 
  if(isset($V2)){
  	require_once dirname(__FILE__) . "/reverse-whois-v2/config.php";
  }
  else if(isset($V1)){
  	require_once dirname(__FILE__) . "/reverse-whois-v1/config.php";
  }
  else require_once dirname(__FILE__) . "/reverse-whois/config.php";

  //echo "session id is ".session_id();
	my_session_start();
  //print_r($_SESSION);
if (isset($_REQUEST['returnto'])) {
  $returnto = $_REQUEST['returnto'];
} else if (isset($returnto)) {
  // do nothing
} else {
  $returnto = returnto_url();
}
//echo "return to is $returnto";
//$firephp->log($_SESSION);
  $hasMoreItems = false;
  for($i=1;$i<5;$i++){
    if($i>1 && isset($_REQUEST["term$i"]) && $_REQUEST["term$i"]){
      $hasMoreItems = true;
      break;
    }
    if(isset($_REQUEST["exclude_term$i"]) && $_REQUEST["exclude_term$i"]){
      $hasMoreItems = true;
      break;
    }
  }
  $search_type = ($_REQUEST['search_type']?$_REQUEST['search_type']:1);
?>
<?php
$title = "Reverse Whois, Registrant Search";
$keywords = "Reverse Whois, Registrant Search";
$description = "Reverse Whois, Registrant Search";
if(isset($pages)){
	if(isset($pages['title'])){
		$title = $pages['title'];
	}
	if(isset($pages['keywords'])){
		$keywords = $pages['keywords'];
	}
	if(isset($pages['description'])){
		$description = $pages['description'];
	}		
}

$SHOW_SOCIAL = 1;
?>


<?php //require_once "affiliate_track.php"
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<META NAME="DESCRIPTION" CONTENT="<?php echo $description?>">
<meta name="verify-v1" content="wGPlYOrtmV+YmkfnVbZIrEuBNucafQA8kk6lnWyMWWQ=" />

<meta name="keywords" content="<?php echo $keywords;?>" />

<?php $version = "";
if(isset($V2))$version="V2";
else if(isset($V1))$version="V1";

?>
<title><?php echo $title?></title>

<!--
<link href="<?php echo $app_root?>/themes/base/ui.all.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $app_root?>/themes/base/ui.tabs.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $app_root?>/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $app_root?>/XMLDisplay.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?php echo $app_root?>/view.css" media="all">
<link href="<?php echo $app_root?>/css/colorbox.css" rel="stylesheet" type="text/css" />
-->

<link href="<?php echo $app_root?>/whoisxmlapi_prod.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $app_root?>/css/jqtree.css" rel="stylesheet" type="text/css" />

<!--<link href="<?php echo $app_root?>/whoisxmlapi_local.css" rel="stylesheet" type="text/css"/>-->

  <script src="https://www.google.com/jsapi"></script>
  <script type="text/javascript">

     // Load jQuery
     google.load("jquery", "1.4.2");
    google.load("jqueryui", "1.7.3");
     google.setOnLoadCallback(function() {
         init();
     });

 </script>

	 <script type ="text/javascript" src="<?php echo $app_root?>/js/prod/jquery.colorbox-min.js"></script>
 <script type ="text/javascript" src="<?php echo $app_root?>/js/prod/whoisxmlapi.js"></script>
 <script type ="text/javascript" src="<?php echo $app_root?>/js/prod/socialite.min.js"></script>
 <script type ="text/javascript" src="<?php echo $app_root?>/js/dev/tree.jquery.js"></script>
 <!--
 	
   <script type="text/javascript" src="<?php echo $app_root?>/js/dev/jquery.form.js"></script>   
   <script type="text/javascript" src="<?php echo $app_root?>/js/dev/jquery.validate.js"></script>
   <script type="text/javascript" src="<?php echo $app_root?>/js/dev/jquery.highlight.js"></script>
   <script type="text/javascript" src="<?php echo $app_root?>/js/dev/XMLDisplay.js"></script>
   <script type="text/javascript" src="<?php echo $app_root?>/js/dev/json.js"></script>
	<script type="text/javascript" src="<?php echo $app_root?>/js/dev/view.js"></script>
	<script type="text/javascript" src="<?php echo $app_root?>/js/dev/urlEncode.js"></script>
	<script type="text/javascript" src="<?php echo $app_root?>/js/dev/util.js"></script>
	<script type="text/javascript" src="<?php echo $app_root?>/js/dev/json2.js"></script>
	<script type="text/javascript" src="<?php echo $app_root?>/js/dev/jquery.jssm.js"></script>
	<script type="text/javascript" src="<?php echo $app_root?>/js/dev/jquery.jssm.config.js"></script>

	<script type ="text/javascript" src="<?php echo $app_root?>/js/dev/price_util.js"></script>
	<script type ="text/javascript" src="<?php echo $app_root?>/js/dev/jquery.colorbox-min.js"></script>
-->

  <script type="text/javascript">

	
 	function init(){ 
		$("a:not(.ignore_jssm)", $(document)).jssm('click');
		$("form:not(.ignore_jssm)",$(document)).jssm('submit');
    	init_search_options();
		$(document).ready(function(){ 
			Socialite.load();
			 var data = [
    {
        label: 'node1',
        children: [
            { label: 'child1' },
            { label: 'child2' }
        ]
    },
    {
        label: 'node2',
        children: [
            { label: 'child3' }
        ]
    }
];

	   $('#data').tree({data:data, autoOpen:1});
                
		});
	}
	function flip_text(text, choice){

	   if(text==choice[0])return choice[1];
	   return choice[0];
	}
	
  function init_search_options(){
    $('#search_more_options').click(function(){
      var but = $(this);
      but.attr('disabled', 'disabled');
      $('#search_more_terms').toggle('blind', false, 'fast', function(){
          but.val(flip_text(but.val(), ['More Options', 'Less Options']));
          but.removeAttr('disabled');
      });
      return false;
    });
   	
  }




</script>


<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $app_root?>/css/custom-theme/jquery-ui-1.8.8.custom.css" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $app_root?>/css/ui.jqgrid.css" />

<script src="<?php echo $app_root?>/js/dev/i18n/grid.locale-en.js" type="text/javascript"></script>
<script src="<?php echo $app_root?>/js/dev/jquery.jqGrid.min.js" type="text/javascript"></script>

<style>
.jqtree-title span{
	color:red;
	
}
.jsonview div{
	color: green;
}
</style>


</head>
<body>
<script type="text/javascript">jssm.inline();</script>




<!--header start -->
<div id="header_rw">
  <div id="header_nav">
	<?php include "header_menu.php"?>
  </div>
  <a href="<?php echo $app_root?>/" class="ignore_jssm"><img src="<?php echo $app_root?>/images/logo.png" alt="whois API"  border="0" class="logo" /></a>
  <div class="styleh2txt">a unified, consistent Whois API and Whois Parser System</div>

  <img src="<?php echo $app_root?>/images/top_icon.png" alt="Whois API"  class="icon" />

  <div id="member">
	 <?php
		$member = ((isset($pages) && element_exists($pages,'member')) ? $pages['member'] : "$app_file_root/login_main.php");


		if(!isset($_SESSION['myuser'])){
  			include $member;
	 }
	?>

  </div>

</div>
<div id="header_bottom_rw_wrap">
  <div id="header_bottom_rw">
    <div id="header_center_rw">
		<?php
			$action =  "$app_root/reverse-whois-lookup.php"; 
			if(isset($V2))$action = "$app_root/reverse-whois-lookup-v2.php" ;
			else if(isset($V1))$action = "$app_root/reverse-whois-lookup-v1.php" ;
			
		?>
    	<form id="whoisform" name="whoisform" action="<?php echo $action?>" >
    		<div class="example_rw">
    			(example: John Smith, test@gmail.com)
    		</div>
        	<a name="wi">
				    <strong>Reverse Whois Lookup:</strong>
			   </a>
		
			
		 
		    <input type="text" size="40" name="term1" value="<?php echo (isset($_REQUEST['term1'])?$_REQUEST['term1']:'')?>"/>	   
			   
			   <?php 
			   	if (isset($_REQUEST['unlimited']) && $_REQUEST['unlimited']){
			   		echo "<input name=\"unlimited\" type=\"hidden\" value=\"1\"/>";
			    }
			   ?>
			   
			   <input type="submit" size="40" value="Search"/>
			   <input type="button" size="40" value="More Options" id="search_more_options"/> 
			 
			     <a href="<?php echo $app_root?>/reverse-whois-order.php" class="ignore_jssm">View Shopping Cart</a>
			
			   <div id="search_more_terms" <?php echo $hasMoreItems?'':'style="display:none"'?>>
			   	<p class="des_rw"><input type="checkbox" name="search_type" value="2"/>Include Historic Records</p>
			      <p class="des_rw"> include whois records containing ALL of the following terms in addition to the primary search term above:</p> 
            <input type="text" size="40" name="term2" value="<?php echo (isset($_REQUEST['term2'])?$_REQUEST['term2']:'')?>" style="display:hidden"/>
            <input type="text" size="40" name="term3" value="<?php echo (isset($_REQUEST['term3'])?$_REQUEST['term3']:'')?>" style="display:hidden"/>
            
            <input type="text" size="40" name="term4" value="<?php echo (isset($_REQUEST['term4'])?$_REQUEST['term4']:'')?>" style="display:hidden"/>
            <br/>
            
           
             <p class="des_rw"> exclude whois records containing ANY of following terms:</p> 
            <input type="text" size="40" name="exclude_term1" value="<?php echo (isset($_REQUEST['exclude_term1'])?$_REQUEST['exclude_term1']:'')?>" style="display:hidden"/>
            <input type="text" size="40" name="exclude_term2" value="<?php echo (isset($_REQUEST['exclude_term2'])?$_REQUEST['exclude_term2']:'')?>" style="display:hidden"/>
            <input type="text" size="40" name="exclude_term3" value="<?php echo (isset($_REQUEST['exclude_term3'])?$_REQUEST['exclude_term3']:'')?>" style="display:hidden"/>
          </div>
     
      
			
		</form>

  </div>
</div>
</div>




<div style="clear:both"></div>


<!--header end -->
<!--body start -->
<div id="body">
	<?php $body = ((isset($pages) && element_exists($pages,'body')) ? $pages['body'] : "reverse-whois_main.php");

		include $body; 
		?>

</div>

<!--body end -->
<!--bodyBottom start -->

<!--bodyBottom end -->
<!--footer start -->

<?php include "footer.php";?>
<?php if($SHOW_SOCIAL) include __DIR__."/socialite.php";?>
</body>
</html>
