<?php 
	$item_name = $_REQUEST['item_name'];
	$payperiod=$_REQUEST['payperiod'];
	$price=$_REQUEST['price'];
	$payperiod_multiple=$_REQUEST['payperiod_multiple'];
	$trial_price=$_REQUEST['trial_price'];
	$trial_duration=$_REQUEST['trial_duration'];
	$trial_duration_unit=$_REQUEST['trial_duration_unit'];
?>
<h3>Customized Order</h3>
<table align="center" cellspacing="1" cellpadding="7" border="0" class="colored" width="90%">
	<tr>
		<td width="20%" class="oddcell">Item Name:</td>
		<td class="oddcell"> <input style="width:90%" name="item_name" type="text" value="<?php echo $item_name;?>"/></td>
	</tr>
	<?php if ($payperiod){?>
	<tr>
		<td class="oddcell">Pay Period:</td>
		<td class="oddcell"> <input name="payperiod" type="text" value="<?php echo $payperiod;?>"/> <?php echo ($payperiod_multiple?"x".$payperiod_multiple:"");?></td>
	</tr>
	<?php }?>
	<tr>
		<td class="oddcell">Price:</td>
		<td class="oddcell">  <input name="price" type="text" value="<?php echo $price?>"/></td>
	</tr>
	<?php if ($trial_price){?>
	<tr>
		<td class="oddcell">Initial payment:</td>
		<td class="oddcell"> <input name="trial_price" type="text" value="<?php echo $trial_price;?>"/></td>
		<input type="hidden" name="trial_duration" value="<?php echo $trial_duration?>"/>
		<input type="hidden" name="trial_duration_unit" value="<?php echo $trial_duration_unit?>"/>
	</tr>
	<?php }?>
		
		

</table>
	<input type="hidden" name="special_order" value="1"/>
	<input type="image" src="images/next.png" class="next_but"/>
