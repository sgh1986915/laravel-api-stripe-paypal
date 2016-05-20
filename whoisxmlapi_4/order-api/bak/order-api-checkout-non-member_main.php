<p class="rightTop"/>
<?php if(isset($order_error)){
	show_error($order_error);
}
?>
<div class="right_sec">




<h3>Are you a Whois API member?</h3>
<p class="rightTxt3">
	
</p>
<div>
	<input id="is_nonmember" name="membertype" value="nonmember" type="radio"/>No, I don't have a Whois API account. <br/>
	<input id="is_member" name="membertype" value="member" type="radio"/>I already have a Whois API account.
	<div>
		<?php 
			$returnto="order-api-payment.php";
			include __DIR__."/../login_form.php"; 
		
		?>
	</div>
</div>
<table cellspacing="1" cellpadding="7" border="0" class="colored" align="center">
              <tr>
			  	
            </tr>
</table>
<table width="95%">
	<tr>
		<td align="left">
			<a href=""><img style="align:left" src="<?php echo $app_root?>/images/cancel.png" /></a>
		</td>
		<td align="right">
			<a href=""><img style="align:left" src="<?php echo $app_root?>/images/previous.png" /></a>
			<a id="next" onclick="return next();"><img style="align:left" src="<?php echo $app_root?>/images/next.png" /></a>
		</td>
	<tr>
	
</table>

	
</div>



<script type="text/javascript">
	function next(){ 
			$("#order-form").submit();
			return false;
	};
</script>
