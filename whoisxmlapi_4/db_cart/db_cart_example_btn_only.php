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
$myCart = new db_cart($_SESSION['custom_num']);

$num_rows = $myCart->get_number_of_records();
// handle checkout link
if (isset($_GET['action']) && $_GET['action'] == "checkout") {
	if ($num_rows > 0) {
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
<title>DB_cart example page (add to cart button)</title>
<style type="text/css">
<!--
label {
	width:340px;
	display:block;
	float:left;
	margin-left:20px;
}
.divider {
	margin:5px 0;
	width:540px;
	border-top:1px solid silver;
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
<h2>DB_cart expample (order form button only) </h2>
<p>This page while show you the functionality of a product list with only an &quot;Add to cart&quot; button.</p>
<p style="color:#FF0000;font-weight:bold;margin:10px 0;"><?php echo $myCart->error; ?></p>
<?php while ($prod_obj = mysql_fetch_object($prod_result)) { ?>
  <form action="<?php echo CONTINUE_SCRIPT; ?>" method="post">
    <p class="divider">
    <label for="prod_<?php echo $prod_obj->id; ?>">
    <b><?php echo $prod_obj->name; ?></b><br>
    <?php echo $prod_obj->description." - price: ".$myCart->format_value($prod_obj->price); ?>
    </label>
	  <input type="hidden" name="price" value="<?php echo $prod_obj->price; ?>">
	  <input type="hidden" name="art_no" value="<?php echo $prod_obj->art_no; ?>">
	  <input type="hidden" name="product" value="<?php echo $prod_obj->name; ?>">
	  <input name="add" type="image" value="submit" src="add2cart.gif" alt="Add to cart" width="100" height="22">
	</p>
	<br clear="all">
  </form>
<?php } // end while loop ?>
<p>There are currently <b><?php echo $num_rows; ?> unique</b> products in your cart.</p>
<p><a href="<?php echo $_SERVER['PHP_SELF']; ?>?action=checkout">&gt;&gt; Checkout! </a></p>
<p><a href="/classes/db_cart/db_cart_example.php">Switch to</a> more advanced order form </p>
</body>
</html>
<?php mysql_free_result($prod_result); ?>