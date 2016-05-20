<?php require_once "util.php"?>
<?php
	$hide = isset($_REQUEST['_h']) && $_REQUEST['_h'];
?>
<!--left panel start -->
<div id="left">

	
<div id="left1">

	<p>
		<span class="span_n"><a class="ajax_left ignore_jssm" href="whois-api.php">Hosted Whois API Webservice </a></span>
	
		Provides <b>consistent, well-structured whois data in XML & JSON</b>. 
		Keeps the <b>most updated, accurate</b> whois data accessible to your application 24/7.  
		Satisfies your application's requirement for <b>high-volume and high traffic whois lookup</b>
		without getting choked by the whois registrars
	</p>
	<br class="spacer"/>
</div>

<div id="left1">
	<?php if(!$hide){?>
	<p>
			<span class="span_n"><a style="text-decoration:none" class="ajax_left ignore_jssm" href="whois-database-download.php">Whois Database Download</a></span>
			<br/>
			Provide historic Whois Database download in both parsed and raw format as csv files
			
		</p>
	<?php
		
	}?>
	<br class="spacer"/>
	
</div>

<div id="left1">

	<p>
		<span class="span_n"><a class="ajax_left ignore_jssm" href="domain-availability-api.php">Domain Availability API </a></span>
	
		is the most accurate domain availability checker available to the public.  Checks wheather a domain is available to be registered and returns result in XML/JSON.
	</p>
	<br class="spacer"/>
</div>


<div id="left1">
	<?php if($hide){?>
	<p>
		<span class="span_n">Bulk Whois Client <em>(coming soon)</em></span>
		Use the <b>best whois client software</b> backed by Whois API to collect, display, import and export parsed whois data in bulk
	</p>
	<?php
	}else{?>
		<p>
			<span class="span_n"><a style="text-decoration:none" class="ajax_left ignore_jssm" href="bulk-whois-lookup.php">
			Bulk Whois Lookup</a></span><br/>
			use our fast-track service or software 
			to check domain names and collect parsed whois data in bulk
		</p>
	<?php	
	}?>
	<br class="spacer"/>
	
</div>




<div id="left1">
<p >
		<span class="span_n"><a class="ajax_left ignore_jssm" href="whois-api-software.php">Whois API Software Package</a></span>
		<br/>
		Use the exact same technology we are using for "Hosted Whois Webservice", this is the right choice for you 
		if you believe you have the necessary infrastructure and support power to run our whois webservice in house.	</p>
	<br class="spacer"/>
	
</div>

<!--
<div id="left1">
	<p>
			<span class="span_n"><a style="text-decoration:none" class="ajax_left  ignore_jssm" >Domain Name Management</a></span>
			<br/>
			<span style="margin-left:10px;margin-top:5px;" ><a href="http://register.whoisxmlapi.com" class="ignore_jssm" style="text-decoration:none;">Register your domain name</a></span>
			
		</p>
	<br class="spacer"/>
	
</div>
-->

    <div id="left1">

        <p>
            <span class="span_n"><a style="text-decoration:none" class="ajax_left  ignore_jssm" href="white-papers.php">White Papers</a></span>
            <br/>
          View white papers on Cybersecurity and Whois API Solutions
        </p>

        <br class="spacer"/>

    </div>

<div id="left1">

	<p>
			<span class="span_n"><a style="text-decoration:none" class="ajax_left  ignore_jssm" href="registrar-whois-services.php">Registrar Whois Service</a></span>
			<br/>
			Setup and manage whois servers for registrars and businesses.  
			Outsource part or all of whois services and other domain management services to us.  
			Provide consulting on all your whois and domain management needs. 
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
<?php if($SHOW_ADS) include __DIR__."/ads.php";?>

<br class="spacer" />


