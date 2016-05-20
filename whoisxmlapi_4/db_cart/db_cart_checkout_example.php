<?php 
require($_SERVER['DOCUMENT_ROOT']."/classes/db_cart/db_cart_class.php");

// custom formfield function more at http://www.finalwebsites.com/
function create_form_field($formelement, $label = "", $db_value = "", $length = 25) {
    $form_field = ($label != "") ? "<label for=\"".$formelement."\">".$label."</label>\n" : "";
    $form_field .= "  <input name=\"".$formelement."\" type=\"text\" size=\"".$length."\" value=\"";
    if (isset($_REQUEST[$formelement])) {
        $form_field .= $_REQUEST[$formelement];
    } elseif (isset($db_value) && !isset($_REQUEST[$formelement])) {
        $form_field .= $db_value;
    } else {
        $form_field .= "";
    }
    $form_field .= "\">\n";
    return $form_field;
}
function create_text_area($formelement, $label = "", $db_value = "", $rows = 5, $cols = 20) {
    $form_field = ($label != "") ? "  <label for=\"".$formelement."\">".$label."</label>\n" : "";
    $form_field .= "  <textarea name=\"".$formelement."\" cols=\"".$cols."\" rows=\"".$rows."\">";
    if (isset($_REQUEST[$formelement])) {
        $form_field .= $_REQUEST[$formelement];
    } elseif (isset($db_value) && !isset($_REQUEST[$formelement])) {
        $form_field .= $db_value;
    } else {
        $form_field .= "";
    }
    $form_field .= "</textarea>\n";
    return $form_field;
}
// example data from the example page
$cust_no = $_SESSION['custom_num'];
$cust_email = $_SESSION['email'];
// use the DB constants or some diffenrent

$myCheckout = new db_cart(8000);

// cancel the order (with all rows and information) // this function be at the TOP ! 
if (isset($_GET['action']) && $_GET['action'] == "cancel") {
	$myCheckout->cancel_order();
}
// update a single order row
if (isset($_POST['add']) && $_POST['add'] == "Update") { 
	$myCheckout->update_row($_POST['row_id'], $_POST['quantity']);
}
// update shipment and process or go back to products
if (isset($_POST['submit'])) {
	// first update eventually modified data
	$myCheckout->update_shipment($_POST['name'], $_POST['name2'], $_POST['address'], $_POST['address2'], $_POST['postal_code'], $_POST['place'], $_POST['country'], $_POST['message']);
	if ($_POST['submit'] == "Order now!") {
		$myCheckout->check_out($cust_email); // place here the mail from your customer or a variable
	} else {
		header("Location: ".PROD_IDX);
	}
}
if (!$myCheckout->check_return_shipment()) {
	// get the external customer data here
	$cust_conn = mysql_connect(DB_SERVER, DB_USER, DB_PASSWORD);
	mysql_select_db(DB_NAME, $cust_conn);
	// the exampple query for customer data (the default shipment address)
	$cust_sql = sprintf("SELECT name, name2, address, address2, postal_code, place, country FROM db_cart_example_customer WHERE cust_no = %d", $cust_no);
	$cust_result = mysql_query($cust_sql) or die(mysql_error());
	$cust_obj = mysql_fetch_object($cust_result);
	$myCheckout->ship_name = $cust_obj->name;
	$myCheckout->ship_name2 = $cust_obj->name2;
	$myCheckout->ship_address = $cust_obj->address;
	$myCheckout->ship_address2 = $cust_obj->address2;
	$myCheckout->ship_pc = $cust_obj->postal_code;
	$myCheckout->ship_city = $cust_obj->place;
	$myCheckout->ship_country = $cust_obj->country;
	mysql_free_result($cust_result);
	$myCheckout->insert_new_shipment();		
} else {
	$myCheckout->set_shipment_data();
}
// show all rows in this order
  $myCheckout->show_ordered_rows();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>DB_cart &quot;checkout&quot; expample</title>
<style type="text/css">
<!--
label {
	width:100px;
	display:block;
	float:left;
	margin-left:20px;
}
table {
	border-collapse:collapse;
}
th {
	text-align:left;
	padding:5px 0 0 10px;
	border-bottom:1px solid #666666;
}
td {
	padding:5px 10px;
}
form {
	margin:0;
}
-->
</style>
</head>

<body>
<h2>DB_cart &quot;checkout&quot; expample</h2>
<p>Try on this page the methods you need during the checkout.</p>
<p style="color:#FF0000;font-weight:bold;margin:10px 0;"><?php echo $myCheckout->error; ?></p>
<?php if ($myCheckout->get_number_of_records() > 0) { ?>
<h3 style="width:480px;"><span style="float:right;"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?action=cancel">Cancel all!</a></span>Your order:</h3>
<table>
  <tr>
    <th>Art. no.</th>
    <th>Product</th>
	<th>Price</th>
	<th>Amount</th>
	<th>Quantity</th>
  </tr>
  <?php foreach ($myCheckout->order_array as $val) { ?>
  <tr>
    <td><?php echo $val['product_id']; ?></td>
	<td><?php echo $val['product_name']; ?></td>
	<td align="right"><?php echo $myCheckout->format_value($val['price']); ?></td>
	<td align="right"><?php echo $myCheckout->format_value($val['price'] * $val['quantity']); ?></td>
	<td>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	    <input type="hidden" name="row_id" value="<?php echo $val['id']; ?>">
	    <input type="text" name="quantity" size="5" value="<?php echo $val['quantity']; ?>">
	    <input type="submit" name="add" value="Update">
      </form>
	</td>
  </tr>
  <?php } // end foreach loop ?>
</table>
<p>Total value of this cart: <b><?php echo $myCheckout->format_value($myCheckout->show_total_value()); ?></b></p>
<p>Total value VAT: <b><?php echo $myCheckout->format_value($myCheckout->create_total_VAT()); ?></b></p>
<p>A copy of this orderform will be send to: <b><?php echo $cust_email; ?></b></p>
<h3>Shipment to:</h3>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" style="width:480px;">
  <?php
  echo create_form_field("name", "Name:", $myCheckout->ship_name, 30)."<br>";
  echo create_form_field("name2", "Name row 2:", $myCheckout->ship_name2, 30)."<br>";
  echo create_form_field("address", "Address:", $myCheckout->ship_address, 30)."<br>";
  echo create_form_field("address2", "Address row 2:", $myCheckout->ship_address2, 30)."<br>";
  echo create_form_field("postal_code", "PC / place:", $myCheckout->ship_pc, 8);
  echo create_form_field("place", "", $myCheckout->ship_city, 17)."<br>";
  echo create_form_field("country", "Country:", $myCheckout->ship_country, 30)."<br>";
  echo create_text_area("message", "Message:", $myCheckout->ship_msg, 3, 30)."<br>";
  ?>
  <p>
  <input type="submit" name="submit" value="&lt;&lt; Continue shopping">
  <input type="submit" name="submit" value="Order now!">
  </p>
</form>
<?php } // end if cart is not empty ?>
</body>
</html>