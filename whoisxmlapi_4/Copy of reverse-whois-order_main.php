<?php 
  require_once dirname(__FILE__) . "/models/report.php";
  require_once dirname(__FILE__) . "/db_cart/db_cart_class.php"; 
   if(isset($V2)){
  	require_once dirname(__FILE__) . "/reverse-whois-v2/config.php";
  }
   else if(isset($V1)){ 
  	require_once dirname(__FILE__) . "/reverse-whois-v1/config.php";
  }
  else require_once dirname(__FILE__) . "/reverse-whois/config.php";
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
     $report = Report::get_report_from_request();

     if(is_object($report) && $report->num_total_d>0){ 
      if(!check_report_exists($report, $cart->order_array)){
       
        if(!$report->save_report(array('username'=>$order_username))){
          $order_error = $report->error;
        }
        
        if(!$order_error){ 
          if(!$cart->handle_cart_row($report->report_id, $report->name, 'R', 1, $report->price, "yes")){ //insert or update cart_row
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
      if($cart->show_ordered_rows_by_type('R') === false) {
        $order_error = $cart->error;
      
      }
    }
  }
  
 
    //$reports = array(array('date'=>date('F j, Y'), 'domains'=>7, 'searchTerm'=>'Jonathan', 'price'=>99.99));
  $num_items = count($cart->order_array);

  $credits_before_purchase = 0;	 
  $total_credits_needed = $num_items;
  $has_credit = false;
  if( isLoggedIn()){
  	$user = $_SESSION['myuser'];
  	$userAccount = getUserAccount($user->username);
  
  	if(is_object($userAccount)){
  		$credits_before_purchase += max(0, $userAccount->reverse_whois_monthly_balance);
  		$credits_before_purchase += max(0, $userAccount->reverse_whois_balance);
  		
  		if($userAccount->reverse_whois_monthly_balance > 0){
  			if($userAccount->reverse_whois_monthly_balance > $total_credits_needed){
  				$userAccount->reverse_whois_monthly_balance-=$total_credits_needed;
  				$total_credits_needed = 0;
  				
  			}
  			else{
  				$total_credits_needed -= $userAccount->reverse_whois_monthly_balance;
  				$userAccount->reverse_whois_monthly_balance = 0;
  				
  			}
  			$has_credit=true;
  		}
  		if($total_credits_needed > 0){
  			if($userAccount->reverse_whois_balance > 0){
  				if($userAccount->reverse_whois_balance > $total_credits_needed){
  					$userAccount->reverse_whois_balance-=$total_credits_needed;
  					$total_credits_needed = 0;
  					
  				}
  				else{
  					$total_credits_needed -= $userAccount->reverse_whois_balance;
  					$userAccount->reverse_whois_balance = 0;
  					
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
<form action="<?php echo $app_root?>/reverse-whois/order_process.php" class="ignore_jssm">
		<!--<input type="hidden" name="sandbox" value="1"/>-->
		<?php 
		    $order_id = $_SESSION['order_id'];
		    echo "<input type=\"hidden\" name=\"order_id\" value=\"$order_id\"/>";
   
		?>
		<div>
			<label for="order_username" class="description">Username of the account to make purchase for:</label>
			<input type="text"  maxlength="255" class="element text medium" name="order_username" id="order_username" value="<?php echo $order_username?>"/>
		</div>	

<h3>Order the following Reverse Whois Report</h3>





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
                 <td calign="right" class="header" >Search Terms</td>
                 <td calign="right" class="header">Type</td>
                  <td calign="right" class="header">Domains</td>
                <td align="right" class="header">Price (USD)</td>
              </tr>
			  <?php 
			   $total_price = 0;
			   for($i=0;$i<$num_items; $i++){
			  		
					$cl = ($i%2==0?"evcell":"oddcell");
					$item = $cart->order_array[$i];
         			 $report = $item['report'];
				  	$report->compute_price();
          
          			$search_type = ($report->search_type ==2? "Historic" : " Current only");
          			
          	
          			
          			if($has_credit && $i < $num_items - $total_credits_needed){
          				$unit_price_display = "1 Credit";
          				$total_credits_used++;
          			
          			}
          			else {
          				
          				
          				$total_price+=$report->price;
          				$unit_price_display = "$".$report->price;
          				
          			}
          			
				?>
              	<tr>
              	<td class="<?php echo $cl?>">
              	
              	  <a href ="<?php echo build_url("$app_root/reverse-whois-order.php", array('row_id'=>$item['id'], 'update'=>'remove')); ?>" class="ignore_jssm">
              	   Remove</a>
              	</td>
              		
                	<td align="right" class="<?php echo $cl?>"><?php echo $report->render_search_terms()  ;?></td>
                	<td align="right" class="<?php echo $cl?>"><?php echo $search_type;?></td>
                	<td align="right" class="<?php echo $cl?>"><?php echo $report->num_total_d?></td>
                	<td align="right" class="<?php echo $cl?>"><b><?php echo $unit_price_display?> </b></td>
              	</tr>
			<?php }?>			
              <tr>
              
                  <td align="right" class="header" colspan=4 >Total Price:</td>
                <td align="right" class="header"><?php if($total_price > 0) echo "$".$total_price;?> <?php if ($total_credits_used >0)echo ($total_price>0?"and":"") . " $total_credits_used credit(s)"?></td>
              </tr>		      	
</table>

<?php }
  $next_image = "$app_root/images/" .($num_items > 0 ? "next.png" : "next_disable.png");
?>
<br/>

<div>
	<?php if($num_items > $credits_before_purchase){?>
		<p>
			You currently have <?php echo $credits_before_purchase?> credits.  
			This transaction requires <?php echo $num_items?> credits.
	 		<a class="ignore_jssm" href="bulk-reverse-whois-order.php" style="color:red;font-weight:bold;"> 
	  		Click here to order more Reverse whois reports(credits) in Bulk for as low as $0.6 per report (credit)!</a> 
	  </p>
	  <?php }
	  
	  else{?>
	  	<p>
			You currently have <?php echo $credits_before_purchase?> credits.  
	
	 		<a class="ignore_jssm" href="bulk-reverse-whois-order.php" style="color:red;font-weight:bold;">
	  			Click here to order Reverse whois reports(credits) in Bulk for as low as $0.6 per report (credit)!</a> 
	  	</p>
	  	
	  <?php
	  }?>
</div>
<br/>
<?php 
	
	if(($num_items <= $credits_before_purchase || $credits_before_purchase == 0) && $num_items > 0 ){?>
	<input type="image" src="<?php echo $next_image?>" class="next_but" />
<?php }?>	
<br/>


<h3>Payment Policy</h3>


	
		<span><img src="<?php echo $app_root?>/images/paypal_cc.png"/><br> <b style="color:#444444">Paypal accepts credit card</b></span>
	
	
	<input id="choice_cc" name="pay_choice" type="hidden" value="pp"/>
	

<p class="rightTxt3">
All transactions are done via paypal for safety and security. 
Your Reverse Whois Reports will be saved in your account so that you may access them any time.
Please <a class="ignore_jssm" href ="mailto:support@whoisxmlapi.com">contact us</a> if you encounter any issue with the checkout proccess.<br/>


</p>




</form>		
</div>