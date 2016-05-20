<?php 
require($_SERVER['DOCUMENT_ROOT']."/classes/db_cart/db_cart_class.php");

// create a new cart object
$myConfirm = new db_cart();
// the next methods are only used to store the data into arrays and variables
$myConfirm->show_ordered_rows();
$myConfirm->set_shipment_data();
// add here extra data like total or vat if you like

// IMPORTANT !
// after all destroy the session
$myConfirm->close_order();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>DB_cart example page</title>
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
<h2>DB_cart &quot;confirmation&quot; expample</h2>
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