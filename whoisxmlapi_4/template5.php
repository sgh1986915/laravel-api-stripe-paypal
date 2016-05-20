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
  

  require_once dirname(__FILE__) . "/db_cart/db_cart_class.php"; 
  require_once dirname(__FILE__) . "/db_cart/cart_util.php"; 
  if(isset($V2)){
  	require_once dirname(__FILE__) . "/reverse-ip-v2/config.php";
  }

  else require_once dirname(__FILE__) . "/reverse-ip/config.php";

 
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


?>
<?php
$title = "Reverse IP";
$keywords = "Reverse IP";
$description = "Reverse IP";
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
<link href="<?php echo $app_root?>/css/jquery.snippet.min.css" rel="stylesheet" type="text/css"  />
<!--<link href="<?php echo $app_root?>/whoisxmlapi_local.css" rel="stylesheet" type="text/css"/>-->

  <script src="https://www.google.com/jsapi"></script>
  <script type="text/javascript">

     // Load jQuery
     google.load("jquery", "1.4.2");
      google.load("jqueryui", "1.7.3);
     google.setOnLoadCallback(function() {
         init();
     });

 </script>

	 <script type ="text/javascript" src="<?php echo $app_root?>/js/prod/jquery.colorbox-min.js"></script>
 <script type ="text/javascript" src="<?php echo $app_root?>/js/prod/whoisxmlapi.js"></script>
 <script type ="text/javascript" src="<?php echo $app_root?>/js/prod/socialite.min.js"></script>
    <script type="text/javascript" src="<?php echo $app_root?>/js/prod/jquery.snippet.min.js"></script>

    <script type="text/javascript">


 	function init(){
		$("a:not(.ignore_jssm)", $(document)).jssm('click');
		$("form:not(.ignore_jssm)",$(document)).jssm('submit');

		$(document).ready(function(){ 
			Socialite.load();
		});
	}
	function flip_text(text, choice){

	   if(text==choice[0])return choice[1];
	   return choice[0];
	}







</script>
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




<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $app_root?>/css/custom-theme/jquery-ui-1.8.8.custom.css" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $app_root?>/css/ui.jqgrid.css" />

<script src="<?php echo $app_root?>/js/dev/i18n/grid.locale-en.js" type="text/javascript"></script>
<script src="<?php echo $app_root?>/js/dev/jquery.jqGrid.min.js" type="text/javascript"></script>



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
			$action =  "$app_root/reverse-ip-lookup.php"; 
			if(isset($V2))$action = "$app_root/reverse-ip-lookup-v2.php" ;

		?>
    	<form id="whoisform" class="ignore_jssm" name="whoisform" action="<?php echo $action?>" >
    		<div class="example_rw">
    			example: 208.64.121.161 or 208.64.121.% or %.64.121.161 or test.com
    		</div>
        	<a name="wi">
				    <strong>Reverse IP Lookup:</strong>
			   </a>
		
			
		 
		    <input type="text" size="40" name="input" value="<?php echo (isset($_REQUEST['input'])?$_REQUEST['input']:'')?>"/>

			   <input type="submit" size="40" value="Search"/>
			   
			 
			     <a href="<?php echo $app_root?>/reverse-ip-order.php" class="ignore_jssm">View Shopping Cart</a>
			
			  
      
			
		</form>

  </div>
</div>
</div>




<div style="clear:both"></div>


<!--header end -->
<!--body start -->
<div id="body">
	<?php $body = ((isset($pages) && element_exists($pages,'body')) ? $pages['body'] : "reverse-ip_main.php");

		include $body; ?>

</div>
<!--body end -->
<!--bodyBottom start -->

<!--bodyBottom end -->
<!--footer start -->

<?php include "footer.php";?>
<?php if($SHOW_SOCIAL) include __DIR__."/socialite.php";?>
</body>
</html>
