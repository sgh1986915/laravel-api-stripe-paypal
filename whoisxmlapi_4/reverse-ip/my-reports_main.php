<?php if(isset($V2)) require_once dirname(__FILE__) . "/../reverse-ip-v2/config.php";
else require_once dirname(__FILE__) . "/config.php";
?>
<table id="myReportsTable" style="display:none">
  <tr>
    <td>
      <div id="myReportsSummary">
  
      </div>
    </td>
  
  </tr>
</table>


<table id="myReportsGrid"></table> 
<div id="myReportsPager"></div> 
<script type ="text/javascript">
function build_url(url, params){
  for(var key in params){
    url+="&"+key+"="+params[key];
  }
  return url;
}
function tab_title_html(title){

  var truncated = truncate(title,10);

  return '<span title="'+title+'"> Report ('+truncated+')</span></title>';
}
function truncate(text, max){
  
  if(text.length<max)return text;
  var tokens=text.split(/\s/);
  
  var res='';
     
  for(var i=0;i<tokens.length;i++){
    var len = res.length;
   
    if(len+tokens[i].length < max){

      res+=tokens[i];
    }
    else if(res==''){
      res+=" "+tokens[i].substring(0, len + tokens[i].length - max);
      break;
    }    
  
  }
  if(res.length<text.length)res+='...';
  return res;
  
}

function get_report_tab_index(tabs, report_id){
  var reports=tabs.data('reports');
  if(reports){
    return reports[report_id];
  }
  return -1;
}
function put_report_tab_index(tabs, report_id, index){
  var reports=tabs.data('reports');
  if(!reports){
 
    tabs.data('reports',{});
    reports = tabs.data('reports');
  }
  reports[report_id]=index;
  //tabs.data('reports',reports);
}
jQuery(document).ready(function(){
jQuery("#myReportsGrid").jqGrid({ 
  url:'reverse-ip/get_my_reports.php', 
  loadComplete:function(data){
      //console.log(data);
     
      if(data){
        
        //$('#rw_main').tabs('option','ajaxOptions',{'dataType': "script"});
        //alert($('#rw_main').tabs('option','ajaxOptions')['dataType']);
        $('.tabfy').click(function(evt){
        try{
          var href = $(this).attr('href');

          var title = $(this).parent().attr('title');


          var report_id = getParameterByName(href,'report_id');
          var tabs=$('#rw_main');
          var index = -1;

          if((index = get_report_tab_index(tabs, report_id)) > 0){
            tabs.tabs('url',href,index);
            tabs.tabs('select',index);
            tabs.tabs('load',index);

          }
          else{
            index = tabs.tabs('length');
            tabs.tabs('add', href, tab_title_html(title));
            tabs.tabs('select',index);
            put_report_tab_index(tabs, report_id, index);

          }

          
        }catch(e){
          alert(e);
        }
          
          
          return false;
        });
        
      }
  },
  loadError:function(xhr, status, error){
      jQuery("#myReportsSummary").html(error);
  },
  hidegrid : false,
  datatype: "json", 
  colNames:['Created Date', 'Input', 'Domains', 'Report Price'],
  colModel:[
    {name:'created_date', index:'created_date', width:150, formatter:'date', formatoptions:{srcformat:'ISO8601Long', newformat:'m/d/Y H:i:s'}},
    {name:'input',index:'input', width:250},
    {name:'domains',index:'domains', width:80}, 
    {name:'price',index:'price', width:80, formatter:'currency', formatoptions:{prefix: "$"}}
    
    /*,
    {name:'amount',index:'amount', width:80, align:"right"},
    {name:'tax',index:'tax', width:80, align:"right"},
    {name:'total',index:'total', width:80,align:"right"},
    {name:'note',index:'note', width:150, sortable:false}
    */
    
  ], 
  rowNum:10, 
  rowList:[10,20,30], 
  pager: '#myReportsPager', 
  sortname: 'created_date',
  viewrecords: true, 
  sortorder: "desc", 
  caption:"Purchased Reports",
  autowidth:true ,
  //width:'100%',
  height:'100%'
  <?php if(isset($_REQUEST['term'])) echo ",postData:{'term' : '" . addslashes($_REQUEST['term']) . "'}";
  ?>
}); 

jQuery("#myReportsGrid").jqGrid('navGrid','#myReportsPager',{edit:false,add:false,del:false,search:false});
});
</script> 