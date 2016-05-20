<?php 
require($_SERVER['DOCUMENT_ROOT']."/classes/db_cart/db_cart_4stock.php");

// the next rows are an example to read product data from a mysql table
// use the DB constants or some different
$prod_conn = mysql_connect(DB_SERVER, DB_USER, DB_PASSWORD);
mysql_select_db(DB_NAME, $prod_conn);

// reading products with "on stock" amount from the database
$query_new = "SELECT art_no, amount AS on_stock, price, art_descr FROM db_cart_stock_article_example ORDER BY art_no";
$res_new = mysql_query($query_new);
$row_new = mysql_fetch_assoc($res_new);

// example to obtain customer data
$cust_sql = "SELECT cust_no, email FROM db_cart_example_customer WHERE id = 1";
$cust_result = mysql_query($cust_sql) or die(mysql_error());
$cust = mysql_fetch_object($cust_result);
$_SESSION['custom_num'] = $cust->cust_no;
$_SESSION['email'] = $cust->email;
mysql_free_result($cust_result);
// end het customer data

// create a new cart object
$myCart = new db_stock_cart($_SESSION['custom_num']);

if (!$_SESSION['checked_cart']) { // to check this ony ones
	$search_in = $myCart->get_order_num_string(); // this generates a article number string to search with in ext. product tabel
	// reading art_no and stock from the database to test against existing order rows
	$query_stock = sprintf("SELECT art_no, amount AS on_stock, price FROM db_cart_stock_article_example WHERE art_no IN (%s) ORDER BY art_no", $search_in);
	$res_stock = mysql_query($query_stock) or die(mysql_error());
	if (mysql_num_rows($res_stock) > 0) {
		$i = 0;
		while ($stock = mysql_fetch_assoc($res_stock)) {
			$stock_array[$i]['new_stock'] = $stock['on_stock']; // use the same array keys
			$stock_array[$i]['new_price'] = $stock['price'];
			$stock_array[$i]['prod_id'] = $stock['art_no'];
			$i++;
		} // end loop storing stock values into an array to use it next in the update method
		$myCart->update_stock_values($stock_array); // run this command to update ones the order rows against the modified stock / price
		mysql_free_result($res_stock);
	} 
}

$num_rows = $myCart->get_number_of_records();
// handle checkout link
if (!empty($_GET['get_msg'])) $myCart->messages($_GET['get_msg']); // this will create a message after the script is redirected and the setting SHOW_CONTINUE = false


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
<title>DB_cart expample (checking against a stock value) </title>
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
<h2>DB_cart expample (checking against a stock value) </h2>
<p>This page while show you the functionality of a product list with only an &quot;Add to cart&quot; button.</p>
<p>Additionally a stock value is used in this example, if a product is not on stock its not possible to order. </p>
<p style="color:#FF0000;font-weight:bold;margin:10px 0;"><?php echo $myCart->error; ?></p>
<table width="520" border="0" cellpadding="0" cellspacing="0"<?php echo (mysql_num_rows($res_new) < 5) ? " style=\"margin-bottom:90px;\"" : ""; ?>>
  <tr>
	<th width="340">Description</th>
	<th>Amount</th>
	<th style="text-align:center;">&nbsp;</th>
  </tr>
  <?php do { ?>
  <tr>
	<td><?php echo $row_new['art_no']; ?> - <?php echo $row_new['art_descr']; ?> - <b>&euro; <?php echo $row_new['price']; ?></b>
	</td>
	<td><?php echo $row_new['on_stock']; ?></td>
	<td align="center">
	  <form action="<?php echo CONTINUE_SCRIPT; ?>" method="post" style="margin: 0;text-align:center;padding:0;">
	    <input type="hidden" name="stock" value="<?php echo $row_new['on_stock']; ?>">
        <input type="hidden" name="art_no" value="<?php echo $row_new['art_no']; ?>">
        <input type="hidden" name="product" value="<?php echo $row_new['art_descr']; ?>">
        <input type="hidden" name="price" value="<?php echo $row_new['price']; ?>">
        <input name="add" type="image"  value="submit" src="add2cart.gif" alt="Order!" width="100" height="22">
      </form>
	</td>
  </tr>
  <?php 
  } while ($row_new = mysql_fetch_assoc($res_new)); 
  mysql_free_result($res_new);
  ?>
</table>
<p>There are currently <b><?php echo $num_rows; ?> unique</b> products in your cart.</p>
<p><a href="<?php echo $_SERVER['PHP_SELF']; ?>?action=checkout">&gt;&gt; Checkout! </a></p>
</body>
</html>