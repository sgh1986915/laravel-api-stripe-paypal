<?php if(isset($V2)) require_once dirname(__FILE__) . "/../reverse-ip-v2/config.php";

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
  <a class="ignore_jssm" href="<?php echo build_url("$app_root/reverse-ip/report_export.php", array('r'=>$_REQUEST['report_id'])); ?>">CSV</a>
 | <a class="ignore_jssm" href="<?php echo build_url("$app_root/reverse-ip/report_export.php", array('f'=>'pdf', 'r'=>$_REQUEST['report_id'])); ?>">PDF</a>
</div><br/>

<table id="<?php echo $grid_id?>"></table>
<div id="<?php echo $grid_pager_id?>"></div>


<script type ="text/javascript">



function epocToDate(s){
  return new Date(s*1000);

}
function stdFormatDate(d){

  var curr_date = d.getDate();
  var curr_month = d.getMonth();
  var curr_year = d.getFullYear();
  return (curr_month +1)+ "/" + curr_date + "/" + curr_year;

}



jQuery(document).ready(function(){


  var post_data = {};
  <?php

    echo "post_data['report_id'] = '".$_REQUEST['report_id'] ."';";
	if(isset($_REQUEST['unlimited']) && $_REQUEST['unlimited']){
		echo "post_data['unlimited'] = '1';";
	}
  ?>

jQuery("#<?php echo $grid_id;?>").jqGrid({
  url:'reverse-ip/get_report.php',
  loadComplete:function(data){
      $('.new_window').click(function(evt){
          evt.preventDefault();
             var href = $(this).attr('href');
          var win=window.open(href, '_blank');
          win.focus();
            return false;
      });
    var title=data.report_name;

    $(this).setCaption(title);

      
  },
  loadui:'block',
  //caption:'<a href="javascript:void(0)" role="link" class="ui-jqgrid-titlebar-close HeaderButton"><span class="ui-icon ui-icon-close "/></a>',
  loadError:function(xhr, status, error){
      jQuery("#revWhoisSummary").html(error);
  },
  hidegrid : true,
  caption:'&nbsp;',
  datatype: "json", 
  colNames:['Domain name', 'Whois Record', "Hosting IPs"],
  colModel:[
    {name:'domain_name', index:'domain_name'},
    {name:'whois_record', index:'whois_record'},
    {name:'ips', index:'ips'}
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