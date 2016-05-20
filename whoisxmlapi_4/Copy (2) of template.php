<?php
	//require_once('FirePHPCore/FirePHP.class.php');
	//ob_start();
	//$firephp = FirePHP::getInstance(true);

	require_once("users/users.inc");
	require_once("util.php");
	require_once("httputil.php");

	my_session_start();

//$firephp->log($_SESSION);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Whois XML API</title>
<!-- //dev
<link href="themes/base/ui.all.css" rel="stylesheet" type="text/css" />
<link href="themes/base/ui.tabs.css" rel="stylesheet" type="text/css" />
<link href="style.css" rel="stylesheet" type="text/css" />
<link href="XMLDisplay.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="view.css" media="all">
-->
<link href="/min?g=whoisxmlapi_css" rel="stylesheet" type="text/css"/>

  <script src="http://www.google.com/jsapi"></script>
  <script type="text/javascript">
     // Load jQuery
     google.load("jquery", "1.3.2");

     google.setOnLoadCallback(function() {
         init();
     });
 </script>
 <script type ="text/javascript" src="/min/?g=whoisxmlapi_js"></script>
<!-- //debug
  <script type='text/javascript'
        src='http://getfirebug.com/releases/lite/1.2/firebug-lite-compressed.js'></script>
	-->
 <!--  //development js
  <script type="text/javascript" src="js/dev/ui.core.js"></script>
   <script type="text/javascript" src="js/dev/ui.tabs.js"></script>
   <script type="text/javascript" src="js/dev/jquery.form.js"></script>
   <script type="text/javascript" src="js/dev/jquery.validate.js"></script>
   <script type="text/javascript" src="js/dev/XMLDisplay.js"></script>
   <script type="text/javascript" src="js/dev/json.js"></script>
	<script type="text/javascript" src="js/dev/view.js"></script>

	<script type="text/javascript" src="js/dev/util.js"></script>
	<script type="text/javascript" src="js/dev/json2.js"></script>
	<script type="text/javascript" src="js/dev/jquery.jssm.js"></script>
	<script type="text/javascript" src="js/dev/jquery.jssm.config.js"></script>
-->

  <script type="text/javascript">
  	$(document).ready(function(){
		init();
	});
 	function init(){
		$('a[class!=ignore_jssm]', $(document)).jssm('click');
		$('form[class!=ignore_jssm]', $(document)).jssm('submit');
	}
</script>
</head>
<body>
<script type="text/javascript">jssm.inline();</script>
<!--header start -->
<div id="header">
<div id="header_nav">
<ul>
<li><a href="index.php" id="top_home" rel="history">Home</a></li>
<li><a href="index.php" id="top_products">Products & Services</a></li>
<li><a href="whois-api-doc.php" id="top_doc" rel="history">Documentation</a></li>
<li><a href="support.php" id="top_support">Support</a></li>
<li><a href="contactus.php" id="top_contactus" rel="history">Contact Us</a></li>
<?php if(array_key_exists('user',$_SESSION)){?>
	<li class="last"><a href="logout.php" class="ignore_jssm">Logout</a></li>
<?php }
?>
</ul>
</div>
<a href="index.php" class="ignore_jssm"><img src="images/logo.png" alt="whois API"  border="0" class="logo" /></a>
<h1>a unified, consistent, machine-readable Whois Lookup system</h1>
<h2></h2>

<img src="images/top_icon.png" alt="Whois API"  class="icon" />

<div id="member">
	<?php
		$member = ((isset($pages) && element_exists($pages,'member')) ? $pages['member'] : "login_main.php");


		if(!array_key_exists('user',$_SESSION)){
  			include $member;
	}
	?>

</div>





<div id="header_center">


    	<form id="whoisform" name="whoisform" action="/whoisserver/WhoisService">
        	<a name="wi">
				<strong>Whois Lookup:</strong>
			</a>
			<!--<input type="hidden" value="FILE" name="srcMode"/>-->
			<input type="text" size="40" name="domainName" value=""/>
			<input type="submit" size="40" value="Search"/>
			<input type="radio" name="outputFormat" value="xml" id="f_xml" checked/><label for="xml">XML</label>
			<input type="radio" name="outputFormat" value="json" id="f_json"/><label for="json">JSON</label>
			<?php
				if(array_key_exists('user',$_SESSION) && is_object($_SESSION['laravel_user'])){
					echo '<input type="hidden" name="userName" value="' . $_SESSION['laravel_user']->username . '"/>';
					echo '<input type="hidden" name="password" value="' . $_SESSION['laravel_user']->password . '"/>';

				}
			?>
			<a  href="order.php">Order Now!</a>
		</form>
  			<div id="user_stats">


   				<?php include "user_stats.php"?>


			</div>

</div>








</div>
<!--header end -->
<!--body start -->
<div id="body">
	<?php $body = ((isset($pages) && element_exists($pages,'body')) ? $pages['body'] : "index_main.php");

		include $body; ?>

</div>
<!--body end -->
<!--bodyBottom start -->

<!--bodyBottom end -->
<!--footer start -->
<div id="test" style="clear:left">

</div>
<div id="footer">
<ul>
	<li><a href="index.php" id="footer_home">Home</a>|</li>
	<li><a href="index.php" id="footer_products">Products & Services</a>|</li>
	<li><a href="whois-api-doc.php"  id="footer_doc">Documentation</a>|</li>
	<li><a href="support.php" id="footer_support">Support</a>|</li>
	<li><a href="contactus.php" id="footer_contactus">Contact Us</a></li>
  </ul>
   <p class="copyright">&copy;Whois XML API Inc. All rights reserved.</p>




</body>
</html>
