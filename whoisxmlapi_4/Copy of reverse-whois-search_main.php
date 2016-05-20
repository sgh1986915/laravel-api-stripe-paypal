<?php require_once dirname(__FILE__)."/CONFIG.php";?>
<?php include dirname(__FILE__)."/reverse-whois/rev_whois_summary.php"?>


<?php 
  $search_type = isset($_REQUEST['search_type'])? $_REQUEST['search_type'] : 1;
  function rev_whois_report_table_title(){
    global $search_type;
    return $search_type==1?"Current-Only Report Preview":"Historic Report Preview";
  }
  
?>
<table id="revWhoisGrid"></table> 
<div id="revWhoisPager"></div> 



<script type ="text/javascript">
var $dialog;

function epocToDate(s){
  return new Date(s*1000);
  
}
function stdFormatDate(d){
 
  var curr_date = d.getDate();
  var curr_month = d.getMonth();
  var curr_year = d.getFullYear();
  return (curr_month +1)+ "/" + curr_date + "/" + curr_year;

}

function price_format(x){
  return '$'+x;
}

function dispNumCurrentDomains(data){  
  $('#num_current_domains_h').text(data.stats['current_total_count']);
  $('#num_current_domains').text(data.stats['current_total_count']);
}

function dispNumHistoricDomains(data){
  $('#num_historic_domains').text(data.stats['history_total_count']);

}
function dispCurrentReportPrice(data){ 
  var discounted_price = discount_price(data.stats['current_report_price'], data.stats['current_price_discount']);
  $('#current_report_price').html(discounted_price);
}
function dispHistoricReportPrice(data){
  //alert(data);
  var discounted_price = discount_price(data.stats['history_report_price'], data.stats['historic_price_discount']);
  $('#historic_report_price').html(discounted_price);
 
}
function discount_price(report_price, discount){
	if(discount && discount > 0 && discount < 1){
		orig_price = report_price / (1-discount);
		
		return "<div style=\"font-weight:bold\"><del>" + price_format(orig_price) +"</del><br/> " + price_format(report_price) +" ("+ (discount*100) +"%)" + "</div>";
	}
	else return price_format(report_price);
	
}
function dispStats(data){
	$('#rw_stats').show();
	$('#rw_search_terms').html('<b>Search Terms:</b> '+data.search_terms_disp);
	$('#rw_search_time').html('searching for '+ (data.search_type==1?'Current':'Historic')+' whois records took '+data.time+' seconds.');
}
function errorBox(err){
	$('<div class="errorMsg">'+err+'</div>').dialog({
      autoOpen: true,
      title: 'Error',
     // minWidth: 600,
      maxHeight: 200,
      width:300,
      autoResize:false
    });
}
function dispSearchError(response){
	response = response||{};
	var err = response.error;
	if(err){
		$('#revWhoisError').html(err).show();
	}
	else {
		$('#revWhoisError').empty().hide();
	}
	if(response.history_error){
		$('#order_historic_reports_b').unbind('click');
		$('#order_historic_reports_b').click(function(e){
			e.preventDefault();
			errorBox(response.history_error);
			return false;
		});
		
	}
	if(response.current_error){
		$('#order_cur_reports_b').unbind('click');
		$('#order_cur_reports_b').click(function(e){
			e.preventDefault();
			errorBox(response.current_error);
			return false;
		});
	}
}
function setSearchType(data){
  if(data && data.search_type){
    $('#whoisform [name=search_type]').val(data.search_type);
  }
}

function get_order_link(search_type){
  return search_type == 2 ? $('#order_historic_reports_b').attr('href') : $('#order_cur_reports_b').attr('href');

}

function linkfyWhoisRecords(){
$('.whois_record').click(function(event) {
    var href = $(this).attr('href');
    $dialog.html('<p><img src="images/icons/ajax-loader-bar.gif" width="400" height="19" /></p>');
    var domain_name=getParameterByName(href,'d');
    var search_type=getParameterByName(href,'search_type');
    var date=stdFormatDate(epocToDate(getParameterByName(href, 'w')));
    //$dialog.dialog('option', 'title', 'loading whois record for '+domain_name);
    $dialog.load(href, function(){
      //$(this).dialog('option','title','Whois Record ('+domain_name+') Compiled on '+date+" <a href=\"\">Order Report</a>");  
      $('#preview_order_report').html('<div style="font-size:12px;width:100%">The following is a preview of the Whois Record. Whois records referenced in this Reverse Whois Report will be available to you once you purchase the full report.  <a href="'+ 
      get_order_link(search_type)
      +'">Order Now</a></div>');  
   
    }).dialog('open');
    
    //$dialog.
    // prevent the default action, e.g., following a link
    return false;
  });
}
jQuery(document).ready(function(){

  $dialog = $('<div style="width:auto"></div>')
    .dialog({
      autoOpen: false,
      title: 'Whois Record',
     // minWidth: 600,
      maxHeight: 500,
      width:600,
      autoResize:true,
      height:500
    });
   
  var post_data = {};
  <?php 
    
    for($i=1;$i<5;$i++){
      if(isset($_REQUEST["term$i"])) echo "post_data['term$i'] = '". addslashes($_REQUEST["term$i"]) . "';\n";
      if(isset($_REQUEST["exclude_term$i"])) echo "post_data['exclude_term$i'] = '". addslashes($_REQUEST["exclude_term$i"]) . "';\n";
    }
    echo "post_data['search_type'] = ".$search_type .";";
  ?>

<?php
	$whois_url = "reverse-whois/search_whois_records.php";
	if(isset($V2))$whois_url="reverse-whois-v2/search_whois_records.php";
	else if(isset($V1))$whois_url="reverse-whois-v1/search_whois_records.php";
?>
jQuery("#revWhoisGrid").jqGrid({ 
  url:'<?php echo $whois_url;?>', 
  beforeRequest:function(){
  	 $('#whoisform input[type=submit]').attr('disabled', 'disabled');
  		
  },
  loadComplete:function(data){
      //console.log(data);
      $('#whoisform input[type=submit]').removeAttr('disabled');
      if(data){
        dispNumCurrentDomains(data);
        dispNumHistoricDomains(data);
        dispCurrentReportPrice(data);
        dispHistoricReportPrice(data);
        dispStats(data);
        linkfyWhoisRecords();
        setSearchType(data);
        
      }
     
      dispSearchError(data);
      
  },
  loadError:function(xhr, status, error){
      jQuery("#revWhoisSummary").html(error);
       $('#whoisform input[type=submit]').removeAttr('disabled');
       var error = 'Search failed due to server error, please contact support@whoisxmlapi.com.';
       dispSearchError({error:error,history_error:error, current_error:error});
  },
  loadtext:'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>',
  loadui:'block',
  hidegrid : false,
  datatype: "json", 
  colNames:['Domain name', 'Whois Records'], 
  colModel:[
    {name:'domain_name', index:'domain_name', width:200},
    {name:'whois_records', index:'whois_records', width:360}
    /*
    {name:'id',index:'id', width:55}, 
    {name:'invdate',index:'invdate', width:90}, 
    {name:'name',index:'name asc, invdate', width:100},
    {name:'amount',index:'amount', width:80, align:"right"},
    {name:'tax',index:'tax', width:80, align:"right"},
    {name:'total',index:'total', width:80,align:"right"},
    {name:'note',index:'note', width:150, sortable:false}
    */
    
  ], 
  shrinkToFit:true,
  rowNum:10, 
  rowList:[10,20,30], 
  pager: '#revWhoisPager', 
  sortname: 'domain_name',
  viewrecords: true, 
  sortorder: "asc", 
  caption: "<?php echo rev_whois_report_table_title();?>",
  autowidth:true ,
  height:'100%',
  postData:post_data
}); 

jQuery("#revWhoisGrid").jqGrid('navGrid','#revWhoisPager',{edit:false,add:false,del:false,search:false});
});
</script> 