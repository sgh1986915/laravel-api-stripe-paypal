

<img id="top" src="images/top.png" alt="">
<div class="form_container">
	<h1><a>Invoice Management</a></h1>
	
</div>

<table id="myReportsGrid"></table> 
<div id="myReportsPager"></div> 
<script type ="text/javascript">

jQuery(document).ready(function(){
jQuery("#myReportsGrid").jqGrid({ 
  url:'invoice/get_my_invoices.php',
  loadError:function(xhr, status, error){
      jQuery("#myReportsSummary").html(error);
  },
  hidegrid : false,
  datatype: "json", 
  colNames:['Invoice Date', 'Invoice Number', 'Invoice Description', 'Amount'], 
  colModel:[//ISO8601Long
    {name:'invoice_date', index:'invoice_date', width:100, formatter:'date', formatoptions:{srcformat:'H:i:s M d, Y T', newformat:'m/d/Y H:i:s T'}},
    {name:'invoice_num',index:'invoice_num', width:110}, 
    {name:'invoice_desc',index:'invoice_desc', width:110}, 
    {name:'invoice_amount',index:'invoice_amount', width:30}
    
  ], 
  rowNum:10, 
  rowList:[10,20,30], 
  pager: '#myReportsPager', 
  sortname: 'invoice_date',
  viewrecords: true, 
  sortorder: "desc", 
  caption:"My Invoices",
  autowidth:true ,
  //width:'100%',
  height:'100%'
  <?php if(isset($_REQUEST['term'])) echo ",postData:{'term' : '" . addslashes($_REQUEST['term']) . "'}";
  ?>
}); 

jQuery("#myReportsGrid").jqGrid('navGrid','#myReportsPager',{edit:false,add:false,del:false,search:false});
});
</script> 
<img id="bottom" src="images/bottom.png" alt="">



