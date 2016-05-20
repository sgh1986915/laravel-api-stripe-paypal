<?php
	$hide = isset($_REQUEST['_h']) && $_REQUEST['_h'];
?>

<div id="header_nav">
	<?php include "header_menu.php"?>
</div>
<a href="<?php echo $app_root?>/" class="ignore_jssm"><img src="<?php echo $app_root?>/images/logo.png" alt="whois API"  border="0" class="logo" /></a>
<div style="width:320px;height:32px;color:white;position:relative;left:430px;top:50px;font-size: 1.5em;font-weight:bold;display:block;">a unified, consistent Whois API and Whois Parser System</div>


<img src="<?php echo $app_root?>/images/top_icon.png" alt="Whois API"  class="icon" />

<div id="member">
  <?php
    $member = ((isset($pages) && element_exists($pages,'member')) ? $pages['member'] : "login_main.php");


    if(!isset($_SESSION['myuser'])){
        include $member;
  }
  ?>

</div>
