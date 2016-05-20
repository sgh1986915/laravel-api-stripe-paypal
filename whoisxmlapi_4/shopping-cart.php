<?php

	require_once "api-products.php";
	require_once "httputil.php";
	require_once "util.php";
	
	require_once "users/utils.inc";
	require_once "users/users.inc";
	require_once "util/number_util.php";
	require_once "util/string_util.php";
?>
<?php

?>
<?php
	//get product selection from previous page
	
	$DEFAULT_QUERIES_VALUE=1000;
	
	my_session_start();
	
			
	APIProducts::check_remove_cart_product();
	APIProducts::check_update_cart_product();
	$products= $_SESSION['products'];
	//print_r($products);
?>


<p class="rightTop"/>
<?php if(isset($order_error)){
	show_error($order_error);
}
?>
<?php
	if(APIProducts::shopping_cart_empty()){?>


<h3>Shopping Cart</h3>	
		<div style="text-align:center;" >Shopping Cart is Empty</div>

<?php		
}
else{
	?>



<h3>Shopping Cart</h3>
<p class="rightTxt3">
	
</p>
<input type="hidden" name="update" value="0"/>
<input type="hidden" name="remove">
<table cellspacing="1" cellpadding="7" border="0" class="colored" align="center">
              <tr>
			  	
                <td  align="left" class="header">Product name</td>
           		<td align="left" class="header">Monthly Subscription</td>
                <td align="left" class="header">Access Price</td>
                <td align="left" class="header" >Total</td>
                <td colspan=2 align="left" class="header" ></td>
            
              </tr>
			 <?php 
			 	$i=0;
			 	$link_params=array();
			 	$total_price = APIProducts::computeTotalPrice($products);
			 	foreach($products as $product){
			 		$product_id=$product['product_id'];
			 		
			 		$cl = ($i++%2==0?"evcell":"oddcell");
			 		$product_info=APIProducts::$api_products[$product_id];
			 		
			 		$monthly_price=$product_info['monthly_price'];
			 		$unit_price=$product_info['unit_price'];
			 		$name=$product_info['name'];
			 		
			 		
			 		
			 ?>
			 	<?php if($product['subscription']){
			 		$params = array_merge( $_REQUEST, array('remove'=>$product_id."_subscription"));
              		
              		$remove_url = build_url( $_SERVER['PHP_SELF'], $params);
              		
			 		$link_params[$product_id]=1;//for previous button
			 	?>
              	<tr>
              		
                	<td align="left" class="<?php echo $cl?>"><?php echo $name;?>
                		<input type="hidden" name="<?php echo $product_id?>" value="1"/>
                	</td>
                	<td align="left" class="<?php echo $cl?>">$<?php echo $monthly_price?>/month </td>
                	<td align="left" class="<?php echo $cl?>"> $<?php echo $unit_price;?>/query</td>
                	<td align="left" class="<?php echo $cl?>">$<?php echo $monthly_price?></td>
                
                	<td colspan=2 align="right" class="<?php echo $cl?>"><a href="" class="ignore_jssm remove">remove </a></td>
                	
              	</tr>
              	<?php }
              	else if (isset($product['queries'])){
              		$queries_input_name=$product_id."_queries";
			 		$queries_input_value=$product['queries'];
			 		
			 		$queries_price=APIProducts::getQueryPrice($product_id,$queries_input_value);
			 		
			 		$link_params[$queries_input_name]=$queries_input_value;
              		
              	?>
              	<tr>
              		
                <td align="left" colspan="2" class="<?php echo $cl?>">Add <input name="<?php echo $queries_input_name?>" type="text" size="4" value="<?php echo $queries_input_value?>" class="element text xsmall"/> <?php echo $name;?> </td>
                	<td align="left" class="<?php echo $cl?>">  $<?php echo $unit_price;?>/query</td>
                	<td align="left" class="<?php echo $cl?>"><?php echo format_price($queries_price)?></td>
                	<td align="left" class="<?php echo $cl?>"><a href="" class="ignore_jssm update_price">update </a></td>
                	<td align="left" class="<?php echo $cl?>"><a href="" class="ignore_jssm remove">remove </a></td>
              	</tr>
				
		     <?php 
              	}
		     }?> 	
		     <tr>
		     	<td class="header" colspan=6 align="right">Total Price: <?php echo format_price($total_price)?></td>
		     </tr>
		     
</table>


	

<script type="text/javascript">
	$(document).ready(function(){
		$('a.update_price').click(function(ev){
			ev.preventDefault();
			var form = $(this).closest('form').get(0);
			$(form).attr('action','order-api-checkout.php');
			$(form).find('input[name=update]').val(1);	
			form.submit();
		});
		$('a.remove').click(function(ev){
			ev.preventDefault();
			var form = $(this).closest('form').get(0);
			$(form).attr('action','order-api-checkout.php');
			$(form).find('input[name=update]').val(1);	
			var input=$(this).closest('tr').find('input').get(0);
			
			var to_remove=$(input).attr('name');
			$(form).find('input[name=remove]').val(to_remove);
			
			
			form.submit();
		});		
	});
</script>
<?php
}?>

