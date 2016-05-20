<?php if(isset($V2)) require_once dirname(__FILE__) . "/../reverse-whois-v2/config.php";
  else if(isset($V1)){
  	require_once dirname(__FILE__) . "/../reverse-whois-v1/config.php";
  }
else require_once dirname(__FILE__) . "/config.php";
require_once dirname(__FILE__)."/../util/string_util.php";
require_once dirname(__FILE__)."/../util.php";

?>

<?php
$report_id = StringUtil::dehash_dn($_REQUEST['report_id']);
$grid_id = "reportDetailGrid_".$report_id;
$grid_pager_id ="reportDetailPager_".$report_id;

?>
<!--
<div class="ui-jqgrid-titlebar ui-widget-header ui-corner-top ui-helper-clearfix">
<a href="javascript:void(0)" role="link" class="ui-jqgrid-titlebar-close HeaderButton" style="right: 0px; float:right;">
<span class="ui-icon ui-icon-close"/></a></div>
-->

<div class="button_bar">
  <a href="<?php echo build_url("$app_root/reverse-whois/report_export.php", array('r'=>$_REQUEST['report_id'])); ?>">CSV</a>
 | <a href="<?php echo build_url("$app_root/reverse-whois/report_export.php", array('f'=>'pdf', 'r'=>$_REQUEST['report_id'])); ?>">PDF</a>
</div><br/>

<table id="<?php echo $grid_id?>"></table>
<div id="<?php echo $grid_pager_id?>"></div>


<script type ="text/javascript">

var whois_dialog;

function epocToDate(s){
  return new Date(s*1000);

}
function stdFormatDate(d){

  var curr_date = d.getDate();
  var curr_month = d.getMonth();
  var curr_year = d.getFullYear();
  return (curr_month +1)+ "/" + curr_date + "/" + curr_year;

}


function get_order_link(search_type){
  return search_type == 2 ? $('#order_historic_reports_b').attr('href') : $('#order_cur_reports_b').attr('href');

}
function linkfyWhoisRecords(){
$('#<?php echo $grid_id;?>').find('.whois_record').click(function(event) {
    var href = $(this).attr('href');

    whois_dialog.html('<img src="images/icons/ajax-loader-bar.gif" width="400" height="19" />');

    var domain_name=getParameterByName(href,'d');
    var search_type=getParameterByName(href,'search_type');
    var date=stdFormatDate(epocToDate(getParameterByName(href, 'w')));
    //dialog.dialog('option', 'title', 'loading whois record for '+domain_name);
    whois_dialog.load(href, function(){
      //$(this).dialog('option','title','Whois Record ('+domain_name+') Compiled on '+date+" <a href=\"\">Order Report</a>");
     /*
      $('#preview_order_report').html('<div style="font-size:12px;width:100%">The following is a preview of the Whois Record. Whois records referenced in this Reverse Whois Report will be available to you once you purchase the full report.  <a href="'+
      get_order_link(search_type)
      +'">Order Now</a></div>');
    */
    }).dialog('open');

    //dialog.
    // prevent the default action, e.g., following a link
    return false;
  });
}
jQuery(document).ready(function(){

  whois_dialog = $('<div style="width:auto"><img src="images/icons/ajax-loader-bar.gif" width="400" height="19" /></div>')
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

    echo "post_data['report_id'] = '".$_REQUEST['report_id'] ."';";
	if(isset($_REQUEST['unlimited']) && $_REQUEST['unlimited']){
		echo "post_data['unlimited'] = '1';";
	}
  ?>

jQuery("#<?php echo $grid_id;?>").jqGrid({
  url: BASE_URL+'whoisxmlapi_4/reverse-whois/get_report.php',
  loadComplete:function(data){
      //console.log(data);


      if(data){

        linkfyWhoisRecords();

      }


  },
  loadui:'block',
  //caption:'<a href="javascript:void(0)" role="link" class="ui-jqgrid-titlebar-close HeaderButton"><span class="ui-icon ui-icon-close "/></a>',
  loadError:function(xhr, status, error){
      jQuery("#revWhoisSummary").html(error);
  },
  hidegrid : false,
  caption:'&nbsp;',
  datatype: "json",
  colNames:['Domain name', 'Whois Records'],
  colModel:[
    {name:'domain_name', index:'domain_name', width:200},
    {name:'whois_records', index:'whois_records', width:400}
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
  pager: '#<?php echo $grid_pager_id;?>',
  sortname: 'domain_name',
  viewrecords: true,
  sortorder: "asc",
  autowidth:true ,
  height:'100%',
  postData:post_data
});

jQuery("#<?php echo $grid_id;?>").jqGrid('navGrid','#<?php echo $grid_pager_id;?>',{edit:false,add:false,del:false,search:false});
      var close_but=jQuery("#gview_<?php echo $grid_id;?>").find('a.ui-jqgrid-titlebar-close');
      close_but.unbind('click');
      close_but.click(function(){
        confirm_remove_report_tab();

        return false;
     });


});

function confirm_remove_report_tab(){
  $( '<div id="dialog-confirm" title="Remove this tab?"><p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>This Report tab will be closed.  Are you sure?</p></div>' ).dialog({
      resizable: false,
      height:140,
      modal: false,
      buttons: {
        "Yes": function() {
          $( this ).dialog( "close" );
          remove_sel_report_tab();

        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      }
  });

}

function remove_sel_report_tab(){
        var sel=$('#rw_main').tabs('option', "selected");
        $('#rw_main').tabs('remove',sel);
        var reports=$('#rw_main').data('reports');
        if(reports){

          for(var report_id in reports){
            if(reports[report_id]==sel){
              delete reports[report_id];
            }
            else if(reports[report_id]>sel){
              reports[report_id]--;
            }
          }
        }
}
</script>