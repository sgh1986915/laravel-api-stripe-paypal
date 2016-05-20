<?php require_once "util.php"?>

<!--left panel start -->
<div id="left">

	
<div id="left1">

	<p>
		<span class="span_n"><a class="ajax_left" href="domain-availability-api.php">Domain Availability API </a></span>
		Checks domain name availability quickly and accurately for all available tlds.
		Provides <b>consistent, well-structured domain availiability info in XML & JSON</b>. 
		
	</p>
	<br class="spacer"/>
</div>


</div>
<!--left panel end -->
<!--right panel start -->
<div id="right">
	<?php 
		$incpg = ((isset($pages) && element_exists($pages,'right')) ? $pages['right'] : 'domain-availability-api_main.php');
		
		include $incpg;
	?>
</div>
<!--right panel end -->
<br class="spacer" />


