<?php 
  require_once dirname(__FILE__) . "/models/reverse_ip_report.php";
  require_once dirname(__FILE__) . "/db_cart/db_cart_class.php"; 
   if(isset($V2)){
  	require_once dirname(__FILE__) . "/reverse-ip-v2/config.php";
  }

  else require_once dirname(__FILE__) . "/reverse-ip/config.php";
  require_once dirname(__FILE__) . "/users/users.inc";
  require_once dirname(__FILE__) . "/util.php";
  require_once dirname(__FILE__) . "/users/account.php";
  

?>
<?php

  
  
  
  function check_report_exists($report, $cart_rows){
      if(!$cart_rows)return false;
      foreach($cart_rows as $row){
        if($report->equals($row['report']))return true;
      }
      return false;
  }
  function count_credits_needed($cart_rows){
  	return count($cart_rows);
  }
  
?>

<?php
 
  my_session_start();
  
 
  
  
  $pay_choice = isset($_REQUEST['pay_choice'])?$_REQUEST['pay_choice']:false;
  if(!$pay_choice) $pay_choice = "pp";

  $order_username = "";
  $session_user = (isset($_SESSION['myuser']) ?  $_SESSION['myuser'] : false);
  if(isset($_REQUEST['order_username'])){
    $order_username = $_REQUEST['order_username'];
  }
  else if(isset($_SESSION['myuser'])){
    $order_username = $_SESSION['myuser']->username;  
  }
  
 
  
  $cart = isset($_SESSION['cart'])? $_SESSION['cart'] : false;
  if(!is_object($cart)){
    $cart_param=array('order_for' => $order_username);
    if(is_object($session_user)){
      $cart['customer_id'] = $session_user->username;
    }
  
    else {
      $cart_param['session_id'] = session_id();
    }
    $cart = new db_cart($cart_param);
    if($cart->error){
      $order_error = $cart->error;
    }
    else $_SESSION['cart'] = $cart;
  }

  if(!$order_error){
    if (isset($_REQUEST['add_report']) && $_REQUEST['add_report'] == 1) {
     $report = ReverseIPReport::get_report_from_request();
	
     if(is_object($report) && $report->getNumResults()>0){
      if(!check_report_exists($report, $cart->order_array)){

        if(!$report->save_report(array('username'=>$order_username))){
          $order_error = $report->error;
        }

        if(!$order_error){
            //$prod_id, $prod_name, $prod_type, $quantity, $price, $replace = "no", $vat_amount = VAT_VALUE
          if(!$cart->handle_cart_row($report->report_id, $report->getReportName(), 'RI', 1, $report->getPrice(), "yes")){ //insert or update cart_row
            $order_error = $cart->error;
          }
      
        }
      }
    

     }
    }
    else if (isset($_REQUEST['update']) && $_REQUEST['update'] == "remove") {
      if(!$cart->update_row($_REQUEST['row_id'], 0))$order_error = $cart->error;
    }
    if(!$order_error){ 
      if($cart->show_ordered_rows_by_type('RI') === false) {
        $order_error = $cart->error;
      }
    }
  }
  
 
    //$reports = array(array('date'=>date('F j, Y'), 'domains'=>7, 'searchTerm'=>'Jonathan', 'price'=>99.99));
  $num_items = count($cart->order_array);

  $credits_before_purchase = 0;	 
  $total_credits_needed = count_credits_needed($cart->order_array);
  $total_credits_required=$total_credits_needed;
  
  $has_credit = false;
  if( isLoggedIn()){
  	$user = $_SESSION['myuser'];
  	$userAccount = getUserAccount($user->username);
  
  	if(is_object($userAccount)){
  		$credits_before_purchase += max(0, $userAccount->reverse_ip_monthly_balance);
  		$credits_before_purchase += max(0, $userAccount->reverse_ip_balance);
  		
  		if($userAccount->reverse_ip_monthly_balance > 0){
  			if($userAccount->reverse_ip_monthly_balance > $total_credits_needed){
  				$userAccount->reverse_ip_monthly_balance-=$total_credits_needed;
  				$total_credits_needed = 0;
  				
  			}
  			else{
  				$total_credits_needed -= $userAccount->reverse_ip_monthly_balance;
  				$userAccount->reverse_ip_monthly_balance = 0;
  				
  			}
  			$has_credit=true;
  		}
  		if($total_credits_needed > 0){
  			if($userAccount->reverse_ip_balance > 0){
  				if($userAccount->reverse_ip_balance > $total_credits_needed){
  					$userAccount->reverse_ip_balance-=$total_credits_needed;
  					$total_credits_needed = 0;
  					
  				}
  				else{
  					$total_credits_needed -= $userAccount->reverse_ip_balance;
  					$userAccount->reverse_ip_balance = 0;
  					
  				}	
  				$has_credit = true;
  			}
  		}	
  		
  	}	
  }	
 // echo "total credist needed $total_credits_needed HAS CREDIT $has_credit";
   $total_credits_used = 0;	
?>
<?php
  function escapeString($s){
    return addSlashes($s);
  }
?>
<p class="rightTop"/>
<?php if(isset($order_error)){
	show_error($order_error);
}
?>
<div class="right_sec">
<h3>Secure Order Form</h3>
<p class="rightTxt3">
	
	
</p>
<form action="<?php echo $app_root?>/reverse-ip/order_process.php" class="ignore_jssm">
		<!--<input type="hidden" name="sandbox" value="1"/>-->
		<?php 
		    $order_id = $_SESSION['order_id'];
		    echo "<input type=\"hidden\" name=\"order_id\" value=\"$order_id\"/>";
   			if($unlimited){
   				echo "<input type=\"hidden\" name=\"unlimited\" value=\"1\"/>";
   			}		
   		
		?>
		<div>
			<label for="order_username" class="description">Username of the account to make purchase for:</label>
			<input type="text"  maxlength="255" class="element text medium" name="order_username" id="order_username" value="<?php echo $order_username?>"/>
		</div>	

<h3>Order the following Reverse IP Report</h3>





<?php if($num_items <=0){?>
<p class="rightTxt3" style="text-align:center">
  Your shopping cart is currently empty
</p>
<?php 
}
else{
?>
<table cellspacing="1" cellpadding="7" border="0" class="colored" align="center">
              <tr>
			  	   
                <td class ="header"></td>
                 <td calign="right" class="header" >Input</td>

                  <td calign="right" class="header">Domains</td>
                <td align="right" class="header">Regular Price or
                Credits required</td>
              </tr>
			  <?php 
			   $total_price = 0;

			   for($i=0;$i<$num_items; $i++){
			  		
					$cl = ($i%2==0?"evcell":"oddcell");
					$item = $cart->order_array[$i];
         			$report = $item['report'];


          			
          			if($current_price_discount>0){
          				
          				$originalPrice = $report->getPrice() / (1-$current_price_discount);
          				$total_price+=$report->getPrice();
          				$unit_price_display ="<div style=\"color:red\" title=\"" . 100* $current_price_discount . "% off\"><del >" . $originalPrice . "</del><br/>". "$".$report->price . " </div>";
          			}
          			else{
          				
          				$perReportPrice = $report->getPrice();
          				$total_price+=$perReportPrice;
          				$unit_price_display = "$" . $perReportPrice;
          				
          				
          			}
          			
				?>
              	<tr>
              	<td class="<?php echo $cl?>">
              	
              	  <a href ="<?php echo build_url("$app_root/reverse-ip-order.php", array('row_id'=>$item['id'], 'update'=>'remove')); ?>" class="ignore_jssm">
              	   Remove</a>
              	</td>

                	<td align="right" class="<?php echo $cl?>"><?php echo $report->input  ;?></td>
                	<td align="right" class="<?php echo $cl?>"><?php echo $report->getNumResults()?> </td>
                	<td align="right" class="<?php echo $cl?>"><b><?php echo $unit_price_display?> or
                	1 credit</td>
              	</tr>
			<?php }?>			
              <tr>
              
                  <td align="right" class="header" colspan=3 >Total Price:</td>
                <td align="right" class="header"><?php if($total_price > 0) echo "$".$total_price;?>  <?php if ($total_credits_required >0) echo "or $total_credits_required credit(s)"?></td>
              </tr>		      	
</table>

<?php }
  $next_image = "$app_root/images/" .($num_items > 0 ? "next.png" : "next_disable.png");
?>
<br/>

<div>
	<?php if($current_price_discount>0){
		echo "<p style=\"color:red;font-weight:bold;\">
			Promotional discount of " . 100* $current_price_discount. "% is only available from $promo_start  to $promo_end !
		</p>";
	}?>
	<?php if($total_credits_needed  > $credits_before_purchase){?>
		<p>
			You currently have <?php echo $credits_before_purchase?> credits.  
			This transaction requires <?php echo $total_credits_required?> credits.
	 		<a class="ignore_jssm" href="bulk-reverse-ip-order.php" style="color:red;font-weight:bold;"> 
	  		Click here to order more Reverse IP reports(credits) in Bulk for as low as $<?php echo $riLowestPricePerReport;?> per report (credit)!</a>
	  </p>
	  <?php }
	  
	  else{?>
	  	<p>
			You currently have <?php echo $credits_before_purchase?> credits.  
	
	 		<a class="ignore_jssm" href='<?php echo "$app_root/bulk-reverse-ip-order.php"?>' style="color:red;font-weight:bold;">
	  			Click here to order more Reverse IP report credits in Bulk for as low as $<?php echo $riLowestPricePerReport;?> per credit!</a>
	  	</p>
	  	
	  <?php
	  }?>
</div>
<br/>
<?php 
	
	if(($total_credits_needed  <= $credits_before_purchase || $credits_before_purchase == 0) && $num_items > 0 ){?>
	<input type="image" src="<?php echo $next_image?>" class="next_but" />
<?php }?>	
<br/>


<h3>Payment Policy</h3>


	
		<span><img src="<?php echo $app_root?>/images/paypal_cc.png"/><br> <b style="color:#444444">Paypal accepts credit card</b></span>
	
	
	<input id="choice_cc" name="pay_choice" type="hidden" value="pp"/>
	

<p class="rightTxt3">
All transactions are done via paypal for safety and security. 
Your Reverse IP Reports will be saved in your account so that you may access them any time.
Please <a class="ignore_jssm" href ="mailto:support@whoisxmlapi.com">contact us</a> if you encounter any issue with the checkout proccess.<br/>


</p>




</form>		
</div>