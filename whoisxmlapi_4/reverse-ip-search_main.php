<?php require_once dirname(__FILE__)."/CONFIG.php";?>
<table style="width:100%;margin:auto;">
<tr>
    <td id = "revIPError" colspan="3" class = "errorMsg" style="display:none">

    </td>
</tr>
</table>

<?php

  function rev_ip_report_table_title(){

    return "Reverse IP Lookup Results";
  }

?>
<table id="revIPGrid"></table>
<div id="revIPPager"></div>

<?php include dirname(__FILE__)."/reverse-ip/rev_ip_summary.php"?>



<script type ="text/javascript">
var $dialog;
function updateRIResultTitle(data){
    var title = "Reverse IP Lookup Results";
    if(data && data.grand_total){
        title +=" -- " + data.grand_total + " Domains hosted on IP Address";
        if(data.ips){
            if(data.ips.length>1){
                title+="es ";
            }
            title+=data.ips;
        }
    }
    jQuery("#revIPGrid").setCaption(title);

}

function updateOrderStats(data){
    var title = "Reverse IP Lookup Results";
    if(data && data.more_domains>0){
        $('#revIPStatsTable').show();

        $('#rev_ip_num_other_domains').text(data.more_domains);

    }
    else{
        $('#revIPStatsTable').hide();
        $('#rev_ip_num_other_domains').text('');
    }


}


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
  return '$'+round_number(x,1);
}

function dispNumCurrentDomains(data){
  $('#num_current_domains_h').text(data.stats['current_total_count']);
  $('#num_current_domains').text(data.stats['current_total_count']);
}


function round_number(num, dec) {
    return Math.round(num * Math.pow(10, dec)) / Math.pow(10, dec);
}
function dispCurrentReportPrice(data){
  var discounted_price = discount_price(data.stats['current_report_price'], data.stats['current_price_discount']);
  $('#current_report_price').html(discounted_price);

  var num_credits=data.stats['num_credits'];

   $('#num_credits').html(num_credits);

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
	$('#rw_search_time').html('searching for Current and Historic Whois Records took '+data.time+' seconds.');
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
	var err = response.error || response.warning;
	if(err){
		$('#revIPError').html(err).show();
	}
	else {
		$('#revIPError').empty().hide();
	}

	if(response.current_error ){/*
		$('#order_cur_reports_b').unbind('click');
		$('#order_cur_reports_b').click(function(e){
			e.preventDefault();
			errorBox(response.current_error);
			return false;
		});*/
	}
}

function get_order_link(search_type){
  return search_type == 2 ? $('#order_historic_reports_b').attr('href') : $('#order_cur_reports_b').attr('href');

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

  var post_data = {'showDomainsDetails':1};
  <?php


    if(isset($_REQUEST['input'])) echo "post_data['input'] = '".$_REQUEST['input'] ."';";
  ?>

<?php
	$whois_url = "reverse-ip/reverse-ip-lookup.php";
	if(isset($V2))$whois_url="reverse-ip-v2/reverse-ip-lookup.php";

	if(isset($unlimited) && $unlimited){
		echo "post_data['unlimited']=1;";
	}

?>

jQuery("#revIPGrid").jqGrid({
  url:'<?php echo $whois_url;?>',
  beforeRequest:function(){
  	 $('#whoisform input[type=submit]').attr('disabled', 'disabled');

  },
  loadComplete:function(data){
      //console.log(data);
      $('#whoisform input[type=submit]').removeAttr('disabled');
      if(data){
          updateRIResultTitle(data);
          updateOrderStats(data);
          /*
        dispNumCurrentDomains(data);
        dispNumHistoricDomains(data);
        dispCurrentReportPrice(data);
        dispHistoricReportPrice(data);
        dispStats(data);
        linkfyWhoisRecords();

        */
      }

      dispSearchError(data);

  },
  loadError:function(xhr, status, error){

      jQuery("#revIPSummary").html(error);
       $('#whoisform input[type=submit]').removeAttr('disabled');
       var error = 'Search failed due to server error, please contact support@whoisxmlapi.com.';
       dispSearchError({error:error,history_error:error, current_error:error});
  },
  loadtext:'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>',
  loadui:'block',
  hidegrid : false,
  datatype: "json",
  colNames:['Domain name', 'Whois Record', "Hosting IPs"],
  colModel:[
    {name:'domain_name', index:'domain_name'},
    {name:'whois_records', index:'whois_records'},
    {name:'ips', index:'ips', width:'250'}

  ],
  shrinkToFit:true,
  rowNum:10,
  rowList:[10,20,30],
  pager: '#revIPPager',
  sortname: 'domain_name',
  viewrecords: true,
  sortorder: "asc",
  caption: "<?php echo rev_ip_report_table_title();?>",
  autowidth:true ,
  height:'100%',
  postData:post_data
}); 

jQuery("#revIPGrid").jqGrid('navGrid','#revIPPager',{edit:false,add:false,del:false,search:false});
});
</script> 