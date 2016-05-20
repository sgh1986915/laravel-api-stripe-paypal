<?php require_once "util.php"?>
<?php
	$hide = isset($_REQUEST['_h']) && $_REQUEST['_h'];
?>
<!--left panel start -->
<div id="left">

	
<div id="left1">

	<p>
		<span class="span_n"><a class="ajax_left ignore_jssm" href="whois-api-doc.php">Whois API </a></span>
	
	</p>
	<br class="spacer"/>
</div>
	
<div id="left1">

	<p>
		<span class="span_n"><a class="ajax_left ignore_jssm" href="reverse-whois-api-doc.php">Reverse Whois API </a></span>
	
	</p>
	<br class="spacer"/>
</div>

<div id="left1">

	<p>
		<span class="span_n"><a class="ajax_left ignore_jssm" href="brand-alert-api.php">Brand Alert API </a></span>
	
		
	</p>
	<br class="spacer"/>
</div>
<div id="left1">

	<p>
		<span class="span_n"><a class="ajax_left ignore_jssm" href="registrant-alert-api.php">Registrant Alert API </a></span>
	
	</p>
	<br class="spacer"/>
</div>
<div id="left1">

	<p>
		<span class="span_n"><a class="ajax_left ignore_jssm" href="domain-availability-api-doc.php">Domain Availability API </a></span>
	
	</p>
	<br class="spacer"/>
</div>




<br class="spacer" />
</div>
<!--left panel end -->
<!--right panel start -->
<div id="right">
	<?php 
		$incpg = ((isset($pages) && element_exists($pages,'right')) ? $pages['right'] : 'whois-api_main.php');
		
		include $incpg;
	?>
</div>
<!--right panel end -->
<br class="spacer" />


