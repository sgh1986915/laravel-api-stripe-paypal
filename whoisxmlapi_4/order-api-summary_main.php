<?php

	require_once __DIR__ . "/api-products.php";
	require_once __DIR__ . "/httputil.php";
	require_once __DIR__ . "/util.php";
	
	require_once __DIR__ . "/users/utils.inc";
	require_once __DIR__ . "/users/users.inc";
	require_once __DIR__ . "/util/number_util.php";
	
?>
<?php
	//get product selection from previous page
	
	
	
?>

<?php if(!isLoggedIn()){?>
	<div class="right_sec" style="text-align:center">
		<?php echo display_login_msg();?>
	</div>	
<?php
} else{
	my_session_start();
	$user=$_SESSION['myuser'];
	
	?>

<p class="rightTop"/>
<?php if(isset($order_error)){
	show_error($order_error);
}
?>
<div class="right_sec">

<form id="order-api-summary-form" action="order-api-add-selection-to-cart.php" class="ignore_jssm">


<h3>Confirm Product Selections</h3>
<p class="rightTxt3">
	You have selected the API products below. Please confirm your selections and add them to your shopping cart. 
</p>

<table cellspacing="1" cellpadding="7" border="0" class="colored" align="center">
              <tr>
			  	
                <td  align="left" class="header">Product name</td>
           		<td align="left" class="header">Monthly Subscription</td>
                <td align="left" class="header">Access Price</td>
                <td align="left" class="header" >Total</td>
             	<td align="left" class="header" colspan=2></td>
              </tr>
			 <?php 
			 	
			 	$i=0;
			 	$DEFAULT_QUERIES_VALUE=1000;
			 	foreach(APIProducts::$api_products as $product_id=>$product_info){
			 		$checked = $_REQUEST[$product_id];
			 		
			 		$cl = ($i++%2==0?"evcell":"oddcell");
			 		$monthly_price=$product_info['monthly_price'];
			 		$unit_price=$product_info['unit_price'];
			 		$name=$product_info['name'];
			 		
			 		$queries_input_name=$product_id."_queries";
			 		$queries_input_value=$_REQUEST[$queries_input_name];
			 		if(!(is_numeric_int($queries_input_value) && $queries_input_value>=0)){
			 			$queries_input_value = $DEFAULT_QUERIES_VALUE;
			 		}
			 		$subscribed= !$checked;
			 		
			 		if(!$subscribed)$subscribed = APIProducts::isUserSubscribed($user, $product_id);
			 		
			 		if(!$subscribed){	
			 	?>
			 	
              		<tr>
              		
                		<td align="left" class="<?php echo $cl?>"><?php echo $name;?>
                			<input type="hidden" name="<?php echo $product_id?>" value="1"?>
                		</td>
                		<td align="left" class="<?php echo $cl?>">$<?php echo $monthly_price?>/month </td>
                		<td align="left" class="<?php echo $cl?>"> $<?php echo $unit_price;?>/query</td>
                		<td align="left" class="<?php echo $cl?>">$<?php echo $monthly_price?></td>
                		
                		<td colspan=2 align="right" class="<?php echo $cl?>"><a href="" class="ignore_jssm remove">remove </a></td>
              		</tr>
              		<?php
              		}?>
              	<?php 
              		if(isset($_REQUEST[$queries_input_name])){
              			
			 			$queries_price=APIProducts::getQueryPrice($product_id,$queries_input_value);
              	?>	
              	<tr>
                	<td align="left" colspan="2" class="<?php echo $cl?>">Add <input name="<?php echo $queries_input_name?>" type="text" size="4" value="<?php echo $queries_input_value?>" class="element text xsmall"/> <?php echo $name;?> queries at $<?php echo $unit_price;?>/query </td>
                	<td align="left" class="<?php echo $cl?>"><?php echo format_price($queries_price)?></td>
                	<td align="left" class="<?php echo $cl?>"></td>
                	<td align="left" class="<?php echo $cl?>"><a href="" class="ignore_jssm update_price">update </a></td>
                	<td align="left" class="<?php echo $cl?>"><a href="" class="ignore_jssm remove">remove </a></td>
              	</tr>
              	<?php }?>
			
		     <?php }?> 	
</table>
<table width="95%">
	<tr>
		<td align="left">
			<a href="<?php echo build_url('order-api.php', $_REQUEST)?>" class="ignore_jssm"><img style="align:left" src="<?php echo $app_root?>/images/previous.png" /></a>
		</td>
		<td align="right">
			<a id="next" class="ignore_jssm" href="javascript:next();"><img style="align:left" src="<?php echo $app_root?>/images/next.png" /></a>
		</td>
	<tr>
	
</table>

	
</div>


<script type="text/javascript">
	function next(){ 
			$("#order-api-summary-form").submit();
			return false;
	};

	$(document).ready(function(){
		$('a.update_price').click(function(ev){
			ev.preventDefault();
			var form = $(this).closest('form').get(0);
			$(form).attr('action','order-api-summary.php');
			form.submit();
		});
		$('a.remove').click(function(ev){
			ev.preventDefault();
			var form = $(this).closest('form').get(0);
			$(form).attr('action','order-api-summary.php');
			var input=$(this).closest('tr');
			$(input).remove();
			
			form.submit();
		});		
	});
</script>
<?php
} ?>