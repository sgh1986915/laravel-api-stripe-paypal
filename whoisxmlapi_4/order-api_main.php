<?php

	require_once __DIR__."/api-products.php";
	require_once __DIR__."/httputil.php";
	require_once __DIR__."/util.php";
	
	require_once __DIR__."/users/utils.inc";
	require_once __DIR__."/users/users.inc";
	
	
?>


<p class="rightTop"/>


<?php if(isset($order_error)){
	show_error($order_error);
}
?>
<span id="order_api_error" class="errorMsg payment-errors">

</span>
<?php if(!isLoggedIn()){?>
	<div class="right_sec" style="text-align:center">
		<?php echo display_login_msg();?>
	</div>	
<?php
}
else{?>
<div class="right_sec">


<form id="order-api-form" action="order-api-summary.php" class="ignore_jssm" onsubmit="return prepare_form();">
	

<h3>Select the API Products you need</h3>
<p class="rightTxt3">
	
</p>

<table cellspacing="1" cellpadding="7" border="0" class="colored" align="center">
              <tr>
			  	
                <td colspan="2" align="right" class="header">Product name</td>
           		<td align="right" class="header">Description</td>
                <td align="right" class="header">Price (USD)</td>
              </tr>
			 <?php 
			 	$i=0;
			 	$DEFAULT_QUERIES_VALUE=1000;
			 	foreach(APIProducts::$api_products as $product_id=>$product_info){
			 		$checked = $_REQUEST[$product_id] || isset($_REQUEST[$product_id."_queries"]);
			 		$cl = ($i++%2==0?"evcell":"oddcell");
			 		$monthly_price=$product_info['monthly_price'];
			 		$unit_price=$product_info['unit_price'];
			 		$name=$product_info['name'];
			 		$des=$product_info['description'];
			 		$subscribed = APIProducts::isUserSubscribed($user, $product_id);
			 		$queries_input_name=$product_id."_queries";
			 		
			 		$num_queries = isset($_REQUEST[$queries_input_name]) ? $_REQUEST[$queries_input_name] : $DEFAULT_QUERIES_VALUE;
			 		
			 		if(!(is_numeric_int($num_queries) && $num_queries>=0)){
			 			$num_queries = $DEFAULT_QUERIES_VALUE;
			 		}
			 		
			 ?>
              	<tr>
              		<td align="right" class="<?php echo $cl?>"><input type="checkbox" value="1" name="<?php echo $product_id?>" <?php echo $checked  ?"checked":"";?>   />
              			<input type="hidden" name="<?php echo $queries_input_name?>" value="<?php echo $num_queries?>" >
              		</td>
                	<td align="right" class="<?php echo $cl?>"><?php echo $name;?> <?php if($subscribed){?><br/><span class="errorMsg">(Already Subscribed)</span><?php }?></td>
                	<td align="right" class="<?php echo $cl?>"><?php echo $des;?></td>
                	<td align="right" class="<?php echo $cl?>">$<?php echo $monthly_price?>/month + $<?php echo $unit_price;?>/query</td>
              	</tr>
			
		     <?php }?> 	
</table>

<input type="image" src="<?php echo $app_root?>/images/next.png" class="next_but"/>
<br/>

	
</div>

<script type="text/javascript">
	function prepare_form(){
		if(!validate_form())return false;
		var form = $('#order-api-form');
		var chkboxes=form.find('input[type=checkbox]');
		for(var i=0;i<chkboxes.length;i++){
			var ck=$(chkboxes[i]);
			if(!ck.attr('checked')){
				ck.siblings('input[type=hidden]').remove();
					
			}
		}
		return true;
	}
	function validate_form(){
		var chkboxes= $('#order-api-form').find('input[type=checkbox]');
		for(var i=0;i<chkboxes.length;i++){
			if($(chkboxes[i]).attr('checked')){
				return true;
			}
		}
		show_error('You must select at least one API Product.');
		return false;
	}
	function show_error(msg){
		$('#order_api_error').html(msg);
	}
	
</script>

<?php
}
?>

