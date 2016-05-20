<?php
	//require_once('FirePHPCore/FirePHP.class.php');
	//ob_start();
	//$firephp = FirePHP::getInstance(true);
	require_once("users/users.inc");
	require_once("util.php");
	require_once("httputil.php");

  //require_once(dirname(__FILE__)."/db_cart/db_cart_class.php");
	
	my_session_start();
  //echo "session id is ".session_id();
  //print_r($_SESSION);
if (isset($_REQUEST['returnto'])) {
  $returnto = $_REQUEST['returnto'];
} else if (isset($returnto)) {
  // do nothing
} else {
  $returnto = returnto_url();
}
$title = "Whois API, Whois XML API";
$keywords = "Whois API, Whois web service, Whois service";
$description = "Whois API, Whois web service";

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
<?php require_once "affiliate_track.php"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!--<META NAME="DESCRIPTION" CONTENT="Whois API, best Whois, Whois Lookup, Whois Parser">-->
<META NAME="DESCRIPTION" CONTENT="<?php echo $description?>">
<meta name="verify-v1" content="wGPlYOrtmV+YmkfnVbZIrEuBNucafQA8kk6lnWyMWWQ=" />
<!--<meta name="keywords" content="Whois API, Whois Lookup API, Whois Parser" />-->
<meta name="keywords" content="<?php echo $keywords?>" />

<title><?php echo $title?></title>
<!--
<link href="themes/base/ui.all.css" rel="stylesheet" type="text/css" />
<link href="themes/base/ui.tabs.css" rel="stylesheet" type="text/css" />
<link href="style.css" rel="stylesheet" type="text/css" />
<link href="XMLDisplay.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="view.css" media="all">
<link href="css/colorbox.css" rel="stylesheet" type="text/css" />
-->

<link href="whoisxmlapi_prod.css" rel="stylesheet" type="text/css"/>
<!--<link href="whoisxmlapi_local.css" rel="stylesheet" type="text/css"/>-->

  <script src="http://www.google.com/jsapi"></script>
  <script type="text/javascript">

     // Load jQuery
     google.load("jquery", "1.4.2");
    google.load("jqueryui", "1.7.3");
     google.setOnLoadCallback(function() {
         init();
     });

 </script>

	<script type ="text/javascript" src="js/prod/jquery.colorbox-min.js"></script>
  <script type ="text/javascript" src="js/prod/whoisxmlapi.js"></script>
 <script type ="text/javascript" src="<?php echo $app_root?>/js/prod/socialite.min.js"></script>
 <!--
   <script type="text/javascript" src="js/dev/jquery.form.js"></script>   
   <script type="text/javascript" src="js/dev/jquery.validate.js"></script>
   <script type="text/javascript" src="js/dev/jquery.highlight.js"></script>
   <script type="text/javascript" src="js/dev/XMLDisplay.js"></script>
   <script type="text/javascript" src="js/dev/json.js"></script>
	<script type="text/javascript" src="js/dev/view.js"></script>
	<script type="text/javascript" src="js/dev/urlEncode.js"></script>
	<script type="text/javascript" src="js/dev/util.js"></script>
	<script type="text/javascript" src="js/dev/json2.js"></script>
	<script type="text/javascript" src="js/dev/jquery.jssm.js"></script>
	<script type="text/javascript" src="js/dev/jquery.jssm.config.js"></script>
-->

  <script type="text/javascript">
 	function init(){
		$("a:not(.ignore_jssm)", $(document)).jssm('click');
		$("form:not(.ignore_jssm)",$(document)).jssm('submit');
		Socialite.load();
	}





</script>

</head>
<body>
<script type="text/javascript">jssm.inline();</script>
<!--header start -->
<div id="header">
<?php include "header_nav.php"?>




<div id="header_center">

	
    	<form id="whoisform" name="whoisform" action="whoisserver/WhoisService">
    		<div class="example">
    			(example: google.com or 74.125.45.100)
    		</div>
        	<a name="wi">
				<strong>Whois Lookup:</strong>
			</a>
			<!--<input type="hidden" value="FILE" name="srcMode"/>-->
			<input type="text" size="40" name="domainName" value=""/>
			<input type="submit" size="40" value="Search"/>
			<input type="radio" name="outputFormat" value="xml" id="f_xml" checked/><label for="xml">XML</label>
			<input type="radio" name="outputFormat" value="json" id="f_json"/><label for="json">JSON</label>
			<?php
				if(isset($_SESSION['myuser'])){
					$user=$_SESSION['myuser'];
					echo '<input type="hidden" name="userName" value="' . $user->username . '"/>';
					echo '<input type="hidden" name="password" value="' . $user->password . '"/>';

				}
			?>
			<a  href="order_paypal.php" class="ignore_jssm" title="Order Now">Order Now!</a>
		</form>
  			<div id="user_stats">


   				<?php include __DIR__ . "/user_stats.php"?>


			</div>

</div>








</div>
<!--header end -->
<!--body start -->
<div id="body">
	<?php $body = ((isset($pages) && element_exists($pages,'body')) ? $pages['body'] : "doc_main.php");

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
