<?php
require($_SERVER['DOCUMENT_ROOT']."/classes/db_cart/db_cart_class.php");
$msg = "";

$myCart = new db_cart(8000);
$old_amount = $myCart->get_amount_from_row($_POST['art_no']);
if ($old_amount == 0) $old_amount = 1;

if (isset($_POST['add'])  || isset($_POST['product'])) {
	if (isset($_POST['Submit'])) {
		$myCart->check_existing_row($_POST['art_no']);
		$myCart->update_row($myCart->curr_product, $_POST['new_amount']);
	} else {
		$myCart->handle_cart_row($_POST['art_no'], $_POST['product'], $old_amount, $_POST['price'], "yes");
		if (!SHOW_CONTINUE) {
			header("Location: ".PROD_IDX."?get_msg=11"); // the query string will create a message on the next page
			exit;
		}
	}
} else {
	header("Location: ".PROD_IDX);
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Continue shopping?</title>
</head>

<body>
  <div id="content">
	<h2>Product: <?php echo $_POST['product']; ?></h2>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<ul>
	  <li>
		<span>Art. no.</span>
		<?php echo $_POST['art_no']; ?>
		<input type="hidden" name="art_no" value="<?php echo $_POST['art_no']; ?>">
	  </li>
	  <li>
		<span>Price</span>
		<?php echo "&euro; ".$_POST['price']; ?>
		<input type="hidden" name="price" value="<?php echo $_POST['price']; ?>">
	  </li>
	  <li>
		<span>Current Amount</span>
		<input type="text" name="new_amount" size="3" value="<?php echo (isset($_POST['new_amount'])) ? $_POST['new_amount'] : $old_amount; ?>">
		<input type="hidden" name="stock" value="<?php echo $_POST['stock']; ?>">
		<input type="hidden" name="product" value="<?php echo $_POST['product']; ?>">
		<input type="submit" name="Submit" value="Update">
	  </li>
	</ul>
	</form>
	<p style="color:#FF0000;"><?php echo $msg; ?></p>
	
	<p>&lt; <a href="<?php echo PROD_IDX; ?>">continue shopping</a> | <a href="<?php echo CHECKOUT; ?>">checkout</a> &gt;</p>
  </div>
</body>
</html>
