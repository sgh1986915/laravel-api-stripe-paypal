<?php 
require($_SERVER['DOCUMENT_ROOT']."/classes/db_cart/db_cart_4stock.php");

// create a new cart object
$myConfirm = new db_stock_cart($_SESSION['custom_num']);

if ($myConfirm->get_number_of_records() == 0) header("Location: ".PROD_IDX); // redirect if there is no data (anymore)

// the next methods are only used to store the data into arrays and variables
$myConfirm->show_ordered_rows();

// request the last version of the shipment information
$myConfirm->set_shipment_data();

// at least modify the stock amount in the product tabel and set the last modified data
$sql_errors = 0;
foreach ($myConfirm->order_array as $val) {
	$update_stock = sprintf("UPDATE db_cart_stock_article_example SET amount = amount - %d, last_buy = NOW() WHERE art_no = '%s'", $val['quantity'], $val['product_id']);
	if (!mysql_query($update_stock)) {
		$sql_errors++;
	}
}
if ($sql_errors == 0) {
	$myConfirm->close_order();
} else {
	$myConfirm->error = $myConfirm->messages(1);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>DB_cart &quot;confirmation&quot; expample (with handling stock values) </title>
<style type="text/css">
<!--
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
<h2>DB_cart &quot;confirmation&quot; expample (with handling stock values) </h2>
<p>This page is an example to show the processed order.</p>
<p style="color:#FF0000;font-weight:bold;margin:10px 0;"><?php echo $myConfirm->error; ?></p>
<h3>Your order:</h3>
<table>
  <tr>
    <th>Art. no.</th>
    <th>Product</th>
	<th>Quantity</th>
	<th>Price</th>
	<th>Amount</th>
  </tr>
  <?php foreach ($myConfirm->order_array as $val) { ?>
  <tr>
    <td><?php echo $val['product_id']; ?></td>
	<td><?php echo $val['product_name']; ?></td>
	<td align="center"><?php echo $val['quantity']; ?></td>
	<td align="right"><?php echo $myConfirm->format_value($val['price']); ?></td>
	<td align="right"><?php echo $myConfirm->format_value($val['price'] * $val['quantity']); ?></td>
  </tr>
  <?php } // end foreach loop ?>
</table>
<h3>will be shipped to:</h3>
<p>
<?php
echo $myConfirm->ship_name."<br>";
echo $myConfirm->ship_name2."<br>";
echo $myConfirm->ship_address."<br>";
echo $myConfirm->ship_address2."<br>";
echo $myConfirm->ship_pc." ".$myConfirm->ship_city."<br>";
echo $myConfirm->ship_country;
?>
</p>
<?php echo ($myConfirm->ship_msg != "") ? "<p><b>The message:</b><br>".nl2br($myConfirm->ship_msg)."</p>" : ""; ?>
<p><a href="./<?php echo PROD_IDX; ?>">&gt;&gt; Back to main... </a></p>
</body>
</html>