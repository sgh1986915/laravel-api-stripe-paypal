<?php require_once "users/users.conf";?>
<p class="rightTop"></p>
<h1>Bulk Whois Lookup / Bulk Domain Checker</h1> 
<p class="rightTxt1">
	You can rely on our <a href="#bulk_whois_lookup_service" class="ignore_jssm">bulk whois lookup services</a> for fast-track service. Our robust API conducts super-quick lookup on an array of domain names through our backend processes. The service can be availed for a manual processing fee. The <a href="#whois_api_client" class="ignore_jssm">application</a> performs the lookup efficiently and exports relevant data with a convenient graphical user interface, thus providing the user complete control of the process. The service as well as the software require a <a href="whois-api.php">whois API webservice</a> account for the process.
	
	
</p>



<div class="rightTxt2">
<a name="whois_api_client"/>
<h2>Bulk Whois Lookup Application</h2> 

	Bulk Whois Lookup API is a robust desktop graphical user interface application that communicates with Whois API Webservice. Transmitting data seamlessly, the API allows users to bulk load and mass query. It facilitates import and export of whois data.
	<a href="order_paypal.php?goto=whois_api_client" id="wc_order" class="ignore_jssm new_bold" title="Order Now"> Order now</a> <br/>
	<br/>
	<a id="wc_screen1" rel="example1" href="images/whois_api_client_screen1.gif" class="ignore_jssm"  ><img src="images/whois_api_client_screen1.gif" width="580" height="327" /></a>
	<a id="wc_screen2" rel="example1" href="images/whois_api_client_screen2.gif" class="ignore_jssm" style="display:hidden"></a>

<div id="bwla_video" style="margin:10px"></div>
</div>
	
<div class="rightTxt2">
	<span>Key Features:</span> 
	<ul>
		<li>
			Mass query on an array of domain names in automated manner
		</li>
		<li>
			Display of parsed and raw whois data in a grid
		</li>
		<li>
			Export both parsed and raw whois data into Comma Separated Values (.csv) or Excel (.xls and xlsx) files
		</li>
		
		<li>
			Full control over the whois data query process
		</li>
		
		<li>
			No installation required; system uses Java Webstart to deliver and update over the web seamlessly  
		</li>			
		
	</ul>	
	

</div>

<p class="rightBottom"></p>
<br class="spacer" />








<div class="rightTxt2">
<a name="bulk_whois_lookup_service"/>
<h2>Bulk Whois Lookup fast-track Service</h2> 

	Our backend process facilitates extraction of fresh whois data directly for a domain list of any size. The outcome will be Mysql or Mssql-compatible database dump files available for download.  <a href="download.php?file=documentation/sample_bulk_whois.csv" class="ignore_jssm">Sample csv file</a> or database schema in <a target="_blank" href="db_schemas/whoiscrawler_schema_mysql.sql" class="ignore_jssm" rel=nofollow>Mysql</a> and <a target="_blank" href="db_schemas/whoiscrawler_schema_mssql.sql" class="ignore_jssm" rel=nofollow>Mssql</a> can be downloaded. Data in other formats is available as well; however, it will require extra delivery time for that. Processing fee and the time required are based on the table given.

		 
	<a href="order_paypal.php?goto=bulk_whois_lookup_service" id="wc_order" class="ignore_jssm new_bold" title="Order Now"> Order now</a> <br/>
	<br/>
	<?php include "bulk_whois_lookup_pricing_table.php";?>
       
       	
<div id="bwls_video" style="margin:10px">

</div>
 
     
</div>


<script type="text/javascript">
$(document).ready(function(){
		
			$("a[rel=example1]").colorbox();
});

      var tag = document.createElement('script');

      tag.src = "https://www.youtube.com/iframe_api";
      var firstScriptTag = document.getElementsByTagName('script')[0];
      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

      // 3. This function creates an <iframe> (and YouTube player)
      //    after the API code downloads.
      var player;
      var player2;
      function onYouTubeIframeAPIReady() {
        player = new YT.Player('bwla_video', {
          height: '360',
          width: '100%',
           playerVars: {rel: 0},
          videoId: 'aWxvgNhvZgw'/*,
          events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
          }*/
        });
        
        player2 = new YT.Player('bwls_video', {
          height: '360',
          width: '100%',
          playerVars: {rel: 0},
          videoId: 'xh6y2lKRLlc'/*,
          events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
          }*/
        });
        
      }
  

</script>
