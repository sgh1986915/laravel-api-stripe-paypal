<?php
require($_SERVER['DOCUMENT_ROOT']."/classes/db_cart/db_cart_class.php");

// this extenstion is used to handle carts where the "on stock" amount is important

class db_stock_cart extends db_cart {

	// this constructor checks first if an old cart is updated against stock values, 
	// if not redirect to produsct index (the script where the update will happen
	// $stock_main_page is the page where the stock update is done
	function db_stock_cart($customer_no = 0, $test_stock = true, $stock_main_page = PROD_IDX) {
		$conn = mysql_connect(DB_SERVER, DB_USER, DB_PASSWORD);
		mysql_select_db(DB_NAME, $conn);
		$this->error = "&nbsp;";
		if ($test_stock) {
			if (!empty($_SESSION['checked_cart'])) {
				header("Location: ".$stock_main_page);
				exit;
			}
		}
		if (!isset($_SESSION['order_id'])) {
			$this->get_order($customer_no);
			$this->remove_old_orders(true); // use false if everyting older 1 day should be removed
		}
	}
	// additional messages need by this extension
	function stock_msg($num, $extra_info = "") {
		$msg = array();
		switch ($this->language) {
			case "nl":
			$msg[1] = "De ingevoerde waarde is hoger dan de voorraad.";
			$msg[2] = "There are ".$extra_info." article(s) sold out sice your last visit, the items ara removed.";
			$msg[3] = "Because the stock value is lower then in your last order, the amount of ".$extra_info." article(s) is changed.";
			$msg[4] = "<br>Eventually price changes are processed too.";
			break;
			case "de":
			$msg[1] = "Die eigegebene Anzahl is höher als der vorhandene Vorrat.";
			$msg[2] = "Seit Ihrer letzten Bestellung sind ".$extra_info." Produkte ausverkauft, diese Produkte wurden aus Ihrem Warenkorb entfernt.";
			$msg[3] = "Seit Ihrer letzten Bestellung hat sich der Vorrat von ".$extra_info." Produkten verkleinert, die Anzahlen in Ihrem Warenkorb wurden emtsprechend geändert.";
			$msg[4] = "<br>Eventuelle Preisänderungen wurden durchgeführt.";
			break;
			default:
			$msg[1] = "The entered value is higher then the available stock amount.";
			$msg[2] = "There are ".$extra_info." article(s) sold out sice your last visit, the items ara removed.";
			$msg[3] = "Because the stock value is lower then in your last order, the amount of ".$extra_info." article(s) is changed.";
			$msg[4] = "<br>Eventually price changes are processed too.";
		}
		return $msg[$num];
	}
	// function to check the order amount against an (stock) value
	function check_against_stock($stock_val, $amount) {
		if ($amount <= $stock_val) {
			return true;
		} else {
			$this->error = $this->stock_msg(1);
			return false;
		}
	}
	// function to check the quantity against the value of an older rows during login 
	function get_value_for_row($art_id) {
		$sql = sprintf("SELECT id, price, quantity FROM %s WHERE product_id = '%s' AND order_id = %d", ORDER_ROWS, $art_id, $_SESSION['order_id']);
		if ($result = mysql_query($sql)) {
			$data['id'] = mysql_result($result, 0, "id");
			$data['price'] = mysql_result($result, 0, "price");
			$data['quantity'] = mysql_result($result, 0, "quantity");
			return $data;
		} else {
			$this->error = $this->messages(1);
			return;
		}
	}
	// function to update old orders about available products and new prices
	// $product_data is an associative array
	// example:
	// $product_data['art_no']['price'] and $product_data['art_no']['stock']
	function proces_old_order($new_price, $new_stock, $for_product) {
		$old_data = $this->get_value_for_row($for_product);
		if ($new_stock == 0) {
			$sql = sprintf("DELETE FROM %s WHERE id = %d", ORDER_ROWS, $old_data['id']);
			if (mysql_query($sql)) {
				$_SESSION['deleted']++;
			} else {
				$this->error = $this->messages(1);
			}
		} elseif ($new_stock < $old_data['quantity']) {
			$sql = sprintf("UPDATE %s SET quantity = %d, price = %f WHERE id = %d", ORDER_ROWS, $new_stock, $new_price, $old_data['id']);
			if (mysql_query($sql)) {
				$_SESSION['updated']++;
			} else {
				$this->error = $this->messages(1);
			}
		} else {
			$sql = sprintf("UPDATE %s SET price = %f WHERE id = %d", ORDER_ROWS, $new_price, $old_data['id']);
			if (mysql_query($sql)) {
				$_SESSION['updated']++;
			} else {
				$this->error = $this->messages(1);
			}
		}
	}
	// the function will handle all tasks to update the old order
	function update_stock_values($ext_prod_array) {
		if (is_array($ext_prod_array)) {
			$_SESSION['deleted'] = 0;
			$_SESSION['updated'] = 0;
			foreach ($ext_prod_array as $val) {
				$stock_val = $val['new_stock'];
				$price_val = $val['new_price'];
				$prod_val = $val['prod_id'];
				$this->proces_old_order($price_val, $stock_val, $prod_val);
			}
			$this->error = ($_SESSION['deleted'] > 0) ? $this->stock_msg(2, $_SESSION['deleted']) : "";
			$this->error .= ($_SESSION['deleted'] > 0 && $_SESSION['updated'] > 0) ? "<br>" : "";
			$this->error .= ($_SESSION['updated'] > 0) ? $this->stock_msg(3, $_SESSION['updated']) : "";
			$this->error .= $this->stock_msg(4);
			$_SESSION['checked_cart'] = true;
		} else {
			$_SESSION['checked_cart'] = true;
		}
	}
	// this function will build a string with the article number,
	// which is later used to obtain the new stock values from the product catalogue
	function get_order_num_string() {
		$str = "";
		if ($this->show_ordered_rows()) {
			foreach ($this->order_array as $val) {
				$str .= "'".$val['product_id']."', ";
			}
			$str = rtrim($str, ", ");
		} else {
			$str = "''"; // bugfix in ver. 1.12
		}
		return $str;
	}
}
?>