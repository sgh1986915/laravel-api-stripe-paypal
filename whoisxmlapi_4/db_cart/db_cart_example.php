<?php 
require($_SERVER['DOCUMENT_ROOT']."/classes/db_cart/db_cart_class.php");

// the next rows are an example to read product data from a mysql table
// use the DB constants or some different
$prod_conn = mysql_connect(DB_SERVER, DB_USER, DB_PASSWORD);
mysql_select_db(DB_NAME, $prod_conn);

// get data from the products table
$prod_sql = "SELECT * FROM db_cart_example_products";
$prod_result = mysql_query($prod_sql) or die(mysql_error());
// end get product data

// example to obtain customer data
$cust_sql = "SELECT cust_no, email FROM db_cart_example_customer WHERE id = 1";
$cust_result = mysql_query($cust_sql) or die(mysql_error());
$cust = mysql_fetch_object($cust_result);
$_SESSION['custom_num'] = $cust->cust_no;
$_SESSION['email'] = $cust->email;
mysql_free_result($cust_result);
// end het customer data

// create a new cart object
$myCart = new db_cart(8000);

// this method will store the order rows into an array, we use the array later in a table
$myCart->show_ordered_rows();
// handle checkout link
if (isset($_GET['action']) && $_GET['action'] == "checkout") {
	if ($myCart->get_number_of_records() > 0) {
		header("Location: ".CHECKOUT); // change the file name if you need
	} else {
		$myCart->error = "Your cart is currently empty!";
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>DB_cart example page</title>
<style type="text/css">
<!--
label {
	width:340px;
	display:block;
	float:left;
	margin-left:20px;
}
form {
	margin:5px 0;
	border-bottom:1px solid silver;
	width:540px;
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
-->
</style>
</head>

<body>
<h2>DB_cart expample (order form) </h2>
<p>This page while show you the functionality of all important (order handling/info) methods.</p>
<p style="color:#FF0000;font-weight:bold;margin:10px 0;"><?php echo $myCart->error; ?></p>
<?php while ($prod_obj = mysql_fetch_object($prod_result)) { ?>
  <form action="<?php echo CONTINUE_SCRIPT; ?>" method="post">
    <label for="prod_<?php echo $prod_obj->id; ?>">
    <b><?php echo $prod_obj->name; ?></b><br>
    <?php echo $prod_obj->description." - price: ".$myCart->format_value($prod_obj->price); ?>
    </label>
    <input type="text" name="new_amount" size="5" value="0">
	<input type="hidden" name="price" value="<?php echo $prod_obj->price; ?>">
	<input type="hidden" name="art_no" value="<?php echo $prod_obj->art_no; ?>">
	<input type="hidden" name="product" value="<?php echo $prod_obj->name; ?>">
    <input type="submit" name="submit" value="Add to cart"><br clear="left">
  </form>
<?php 
} // end while loop 
if ($myCart->get_number_of_records() > 0) { 
?>
<h3>Your current cart:</h3>
<table>
  <tr>
    <th>Art. no.</th>
    <th>Product</th>
	<th>Quantity</th>
	<th>Price</th>
	<th>Amount</th>
  </tr>
  <?php foreach ($myCart->order_array as $val) { ?>
  <tr>
    <td><?php echo $val['product_id']; ?></td>
	<td><?php echo $val['product_name']; ?></td>
	<td align="center"><?php echo $val['quantity']; ?></td>
	<td align="right"><?php echo $myCart->format_value($val['price']); ?></td>
	<td align="right"><?php echo $myCart->format_value($val['price'] * $val['quantity']); ?></td>
  </tr>
  <?php } // end foreach loop ?>
</table>
<p>Total value of this cart: <b><?php echo $myCart->format_value($myCart->show_total_value()); ?></b></p>
<p>Total value VAT: <b><?php echo $myCart->format_value($myCart->create_total_VAT()); ?></b></p>
<?php } // end if cart is not empty ?>
<p><a href="<?php echo $_SERVER['PHP_SELF']; ?>?action=checkout">&gt;&gt; Checkout! </a></p>
<p><a href="/classes/db_cart/db_cart_example_btn_only.php">Switch to</a> &quot;Add to..&quot; button styled form </p>
</body>
</html>
<?php mysql_free_result($prod_result); ?>