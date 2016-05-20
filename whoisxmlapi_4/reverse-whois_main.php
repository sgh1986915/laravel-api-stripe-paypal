<?php require_once "util.php"?>

<!--left panel start -->
<div id="left">
<!--left1 start -->
  
<div id="left1">

  <p class="boxTxt1">
    <span class="span1"><a class="ajax_left" href='<?php echo "$app_root/reverse-whois-intro.php"?>'>Reverse Whois Search</a></span>
  	<span style="font-weight:bold;">
    	Reverse Whois lets you find all the domain names registered in the name of any specific owner.
    </span>
  	<br/>
  	<span>
  	Bulk Lookup allow you you perform a fixed number of lookups
  	regardless of how many domain names appear in results (lookup that yields 0 domain names does not count), 
  	see <a class="ignore_jssm" href='<?php echo "$app_root/bulk-reverse-whois-order.php"?>'  style="color:red;font-weight:bold;text-decoration:none">Bulk Pricing</a></span>
  	<!--<span style="color:red;font-weight:bold;">Updated Pricing: 50% off all reports during introductory period</span>-->
  </p>
  <br class="spacer"/>
</div>
<!--left1 end -->

<div id="left1">

  <p class="boxTxt1">
    <span class="span2"><a class="ajax_left" href='<?php echo "$app_root/reverse-whois-api.php"?>'>Reverse Whois API</a></span>
  	<span style="font-weight:bold;">
    	Reverse Whois API provides a RESTful webservice for reverse search
    </span>
  	<br/>
  	
  </p>
  <br class="spacer"/>
</div>




<?php if(isLoggedIn()){?>
<div id="left1">

  <p class="boxTxt1">
    <span class="span3"><a class="ajax_left" href="reverse-whois-lookup.php?tab=my-reverse-whois-reports">My Reverse Whois Reports</a></span>
  </p>
  <br class="spacer"/>
</div>

<?php }
?>

</div>
<!--left panel end-->
<!--right panel start -->
<div id="right">
  <?php 
    $incpg = ((isset($pages) && element_exists($pages,'right')) ? $pages['right'] : 'reverse-whois-intro_main.php');
    
    include $incpg;
  ?>
</div>
<!--right panel end -->
<br class="spacer" />


