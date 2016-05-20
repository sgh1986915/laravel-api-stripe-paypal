<?php 
	require_once __DIR__."/CustomInvoiceGen.php";
	global $invoice_template_params, $submit_action;
	
	
		$item_name=$_REQUEST['ig_item_name'];
		$price=$_REQUEST['ig_price'];
		$email=$_REQUEST['ig_email'];
		$email_bccs=$_REQUEST['ig_email_bccs'];
		$username=$_REQUEST['ig_username'];
		$invoice_date=$_REQUEST['ig_invoice_date'];
		$invoice_num=$_REQUEST['ig_invoice_num'];
		$invoice_desc=$_REQUEST['ig_invoice_desc'];
		
		if(!$username){
			$username=$invoice_template_params['username'];
		}
		if(!$email){
			$email=$invoice_template_params['email'];
		}
		if(!$item_name){
			$item_name=$invoice_template_params['item_name'];
		}
		if(!$invoice_date){
			$invoice_date=date('F j, Y');
		}
		if(!$invoice_num){
			$invoice_num=$username."_".date('Y_m_d');
		}
		if(!$email_bccs){
			$email_bccs="topcoder1@gmail.com";
		}
		$errors=false;
	if ($_REQUEST['submit'] && validate_input($errors)){
		
		$params=array(
	      'item_name'=>$item_name,
	      'price'=>$price,
	      'email_to'=>$email,
	      'email_bccs'=>$email_bccs,
	      'username'=>$username,
	      'invoice_date'=>$invoice_date,
	      'invoice_num'=>$invoice_num,
	      'invoice_desc'=>$invoice_desc

		);
		if(!CustomInvoiceGen::generateSimpleUnPaidInvoice($params)){
			$errors="Failed to generate invoice ".print_r($params,1);
		}
		else echo "Invoice generated";
	}
	//echo "Errors is ".print_r($errors,1);
	
	function validate_input(&$errors){
		
		if(strlen($_REQUEST['ig_username'])<1){
			$errors="Invalid username";
		}
		else if(strlen($_REQUEST['ig_invoice_num'])<1){
			$errors="Invalid invoice number";
		}
		else if(strlen($_REQUEST['ig_item_name'])<1){
			$errors="Invalid item name";
		}
		else if(strlen($_REQUEST['ig_price'])<1){
			$errors="Invalid price";
		}
		else if(strlen($_REQUEST['ig_email'])<1){
			$errors="Invalid email";
		}
		else return true;
		return false;
		
		
	}
?>


	<img id="top" src="images/top.png" alt="">
	<div class="form_container">

		<h1><a>Invoice Generator</a></h1>
		<?php if(isset($errors) && strlen($errors)>0){
				show_error($errors);
		}?>
		<form id="regform" class="appnitro ignore_jssm"  action="<?php echo $submit_action?>" method="post">
					<div class="form_description">
		

		
		</div>
			<ul >
		<li id="li_1" >

		<div>
			<label class="description" for="ig_username">*Username: </label>
			<input id="ig_username" name="ig_username" class="element text medium" type="text" maxlength="255" <?php echo isset($username)?'value="'.$username.'"':""?> "/>
		</div>
		</li>	
		<li id="li_1" >

		<div>
			<label class="description" for="ig_invoice_num">*Invoice #: </label>
			<input id="ig_invoice_num" name="ig_invoice_num" class="element text medium" type="text" maxlength="255" <?php echo isset($invoice_num)?'value="'.$invoice_num.'"':""?> "/>
		</div>
		</li>		<li id="li_2" >
		<label class="description" for="ig_item_name">*Item name: </label>
		<div>
			<textarea id="ig_item_name" name="ig_item_name" class="element text medium" type="text" maxlength="255"><?php echo isset($item_name)?$item_name:""?></textarea>
		</div>
		</li>
				<li id="li_3" >
		<label class="description" for="ig_price">*Item Price: </label>
		<div>
			<input id="ig_price" name="ig_price" class="element text medium" type="text" maxlength="255" <?php echo isset($price)?'value="'.$price.'"':""?>/>
		</div>
		</li>		<li id="li_4" >
		<label class="description" for="ig_email">*Email: </label>
		<div>
			<input id="ig_email" name="ig_email" class="element text medium" type="text" maxlength="255" <?php echo isset($email)?'value="'.$email.'"':""?>/>
		</div>
		</li>	
		
				<li id="li_4" >
		<label class="description" for="ig_email_bccs">*Email BCCs: </label>
		<div>
			<input id="ig_email_bccs" name="ig_email_bccs" class="element text medium" type="text" maxlength="255" <?php echo isset($email_bccs)?'value="'.$email_bccs.'"':""?>/>
		</div>
		</li>	
			<li id="li_4" >
		<label class="description" for="ig_invoice_date">*Invoice Date: </label>
		<div>
			<input id="ig_invoice_date" name="ig_invoice_date" class="element text medium" type="text" maxlength="255" <?php echo isset($invoice_date)?'value="'.$invoice_date.'"':""?>/>
		</div>
		</li>	
				
		<li id="li_2" >
		<label class="description" for="ig_invoice_desc">Invoice Description (Shows up in the email): </label>
		<div>
			<textarea id="ig_invoice_desc" name="ig_invoice_desc" class="element text medium" type="text" maxlength="255"><?php echo isset($invoice_desc)?$invoice_desc:""?></textarea>
		</div>
		</li>
		<li class="buttons">
			    
				<input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
		</li>
			</ul>
		</form>

	</div>
	<img id="bottom" src="images/bottom.png" alt="">

<script type="text/javascript">
$(document).ready(function(){
	$('#regform').validate({

		rules:{
			item_name: {
				required:true,
				minlength : 4
			},
			price:{
				required:true,
				minlength: 4
			},
			invoice_num:{
				required:true,
				minlength : 4
			},
			email_to:{
				required:true,
				email:true
			}
		}
	});





});
	//ajax_reg();
</script>



