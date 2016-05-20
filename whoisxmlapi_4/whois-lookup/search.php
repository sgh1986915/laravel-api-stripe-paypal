<!doctype html>
<?php require_once  __DIR__ . "/domain_util.php";
	$_STATS_DEBUG = 1;
	$domain_name = clean_domain($_REQUEST['q']);
?>
<head>
    <title><?php echo $domain_name?> - Whois Lookup</title>
      <META NAME="DESCRIPTION" CONTENT="Whois Lookup">
	<meta name="keywords" content="Whois Lookup" />
	
 	<script type="text/javascript" src="js/yetii.js"></script>
 <script src="http://www.google.com/jsapi"></script>
  		<script type="text/javascript">

     
     		google.load("jquery", "1.4.2");
     		google.setOnLoadCallback(function() {
        	 init();
     		});
     		function init(){
     			var time = new Date().getTime();
     			
				var url = 'whois_recordc.php?q=<?php echo $domain_name?>';
				$('#res').load(url,function(){
					$('#res').removeClass('ajax_buzy');
					setDebugTime((new Date().getTime() - time)/1000);
				});
     		}
     		function setDebugTime(time){
     			var d = $('#duration');
     			if(d){
     				$('#status_ct').show();
     				d.html(time);
     			}
     		}
     	</script>
     	
 
  <link href="css/custom.css" rel="stylesheet" type="text/css"/>
 <link href="css/search.css" rel="stylesheet" type="text/css"/>
 <link href="css/footer.css" rel="stylesheet" type="text/css"/>

</head>
<body dir="ltr" lang="en" topmargin="3" marginheight="3" id="gsr">
    
  <?php include "header.php";?>
    
    
    
    <div id="main">
        <div>
            <div id="cnt">
               
                <div class="mw">
                    <div id="sfcnt">
                       
                        <form action="search.php" id="tsf" method="GET" onSubmit="return q.value!=''"
                        role="search" name="f" style="display:block;background:none">
                          
                            <div class="tsf-p" style="position:relative">
                               
                                <div style="padding-bottom:2px;padding-top:1px">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td width="100%">
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="position:relative;border-bottom:1px solid transparent">
                                                    <tr>
                                                        <td class="lst-td" id="sftab" width="100%" style="border:0">
                                                            <div class="lst-d lst-tbb">
                                                                <input class="lst lst-tbb" title="Search" size="41" type="text"
                                                                autocomplete="off" id="lst-ib" name="q" maxlength="2048" value="<?php echo $_REQUEST['q']?>"/>
                                                                
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <input value="Submit" name="btnK" type="submit"/>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                           
                                        </tr>
                                        
                                </table>
                            </div>
                    </div>
                    </form>
                </div>
                
            </div>
           
            
            <div class="mw">
               
                <div id="rcnt" style="clear:both;position:relative;zoom:1">
                   
                    <div id="center_col">
                     
                       <div class="med ajax_buzy" id="res" role="main" style="min-height:300px;">
                       		<?php 
                       			//include "whois_recordc.php"
                       		?>
                       
                       </div>
                        
                    </div>
                    
                </div>
			
    
        </div>
    </div>
    </div>
    
    <?php if($_STATS_DEBUG){?>
     <div id="status_ct" style="display:none">
           
                
                <div style="position:relative">
                    <div>
                        <div id=resultStats>
                            <nobr>Whois Lookup Lookup took <span id="duration"></span>&nbsp; seconds &nbsp;</nobr>
                        </div>
                    </div>
                </div>
      
            </div>
            <?php }?>
            	<?php include __DIR__."/footer.php";?>
            
</body>