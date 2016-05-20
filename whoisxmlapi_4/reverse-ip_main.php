<?php require_once "util.php"?>

<!--left panel start -->
<div id="left">
<!--left1 start -->
  
<div id="left1">

  <p class="boxTxt1">
    <span class="span_n"><a class="ajax_left" href='<?php echo "$app_root/reverse-ip-intro.php"?>'>Reverse IP Lookup</a></span>
  	<span style="font-weight:bold;">
    	Reverse IP lets you find all the connected domain names hosted on the same IP address.
    </span>
  
  </p>
  <br class="spacer"/>
</div>


<div id="left1">

  <p class="boxTxt1">
    <span class="span_n"><a class="ignore_jssm" href='<?php echo "$app_root/reverse-ip-api.php"?>'>Reverse IP API</a></span>
  	<span style="font-weight:bold;">
    	Reverse IP API provides a RESTful webservice for Reverse IP lookup with XML and JSON response
    </span>
  	<br/>
  	
  </p>
  <br class="spacer"/>
</div>

    <div id="left1">

        <p class="boxTxt1">
            <span class="span_n"><a class="ignore_jssm" href='<?php echo "$app_root/domain-ip-database.php"?>'>Domain IP Database</a></span>
  	<span style="font-weight:bold;">
    	Provides ip addresses for hundreds of millions of domain names via bulk download.
    </span>
            <br/>

        </p>
        <br class="spacer"/>
    </div>


<?php if(isLoggedIn()){?>
<div id="left1">

  <p class="boxTxt1">
    <span class="span_n"><a class="ignore_jssm" href="reverse-ip-lookup.php?tab=my-reverse-ip-reports">My Reverse IP Reports</a></span>
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
    $incpg = ((isset($pages) && element_exists($pages,'right')) ? $pages['right'] : 'reverse-ip-intro_main.php');
    
    include $incpg;
  ?>
</div>
<!--right panel end -->
<br class="spacer" />


