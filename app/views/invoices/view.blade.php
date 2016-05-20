@if (empty($invoices))
<div class="alert alert-warning alert-block">
	<h4>No Invoice Found.</h4>
</div>
@else

<table class="table table-bordered wa-table-responsive wa-table-account-management wa-table-invoices wa-cursor" id="wa-table-ak" style="display:none;">
	<thead>
		<tr>
			<th>Invoice Date</th>
			<th>Invoice Number</th>
			<th>Invoice Description</th>
			<th>Amount</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($invoices as $i => $invoice)
		<tr invoiceid="{{ $invoice['invoice_id'] }}">
			<td>{{ $invoice['invoice_date'] }}</td>
			<td>{{ $invoice['invoice_num'] }}</td>
			<td>{{ $invoice['invoice_desc'] }}</td>
			<?php $invoice_content = json_decode($invoice['invoice_content'],true); ?>
			<td>{{ $invoice_content['totalPrice'] }}</td>
		</tr>
		@endforeach
	</tbody>
</table>
<table id="myReportsGrid"></table>

@endif


<script type ="text/javascript">

	jQuery(document).ready(function(){
		jQuery("#myReportsGrid").jqGrid({
			url:BASE_URL+'whoisxmlapi_4/invoice/get_my_invoices.php',
			loadError:function(xhr, status, error){
				jQuery("#myReportsSummary").html(error);
			},
			hidegrid : false,
			datatype: "json",
			colNames:['Invoice Date', 'Invoice Number', 'Invoice Description', 'Amount'],
			colModel:[
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
			height:'100%'
			<?php if(isset($_REQUEST['term'])) echo ",postData:{'term' : '" . addslashes($_REQUEST['term']) . "'}";
			?>
		});

		jQuery("#myReportsGrid").jqGrid('navGrid','#myReportsPager',{edit:false,add:false,del:false,search:false});
	});
</script>