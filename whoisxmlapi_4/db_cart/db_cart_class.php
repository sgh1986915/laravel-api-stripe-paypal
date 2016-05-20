<?php
/************************************************************************
db_cart Class ver 1.13 -
This universal shopping cart script is powered by MySQL and works with external customer and product related data.

Copyright (c) 2005 - 2006, Olaf Lederer
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

    * Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
    * Neither the name of the finalwebsites.com nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

_________________________________________________________________________
available at http://www.finalwebsites.com/snippets.php?id=32
Comments & suggestions: http://www.finalwebsites.com/forums/forum/php-classes-support-forum

*************************************************************************/
require_once dirname(__FILE__)."/../CONFIG.php";
require_once(dirname(__FILE__) . "/db_config.php");
require_once(dirname(__FILE__) . "/../users/whois_server.inc");
require_once(dirname(__FILE__) . "/../models/report.php");
require_once(dirname(__FILE__) . "/../models/reverse_ip_report.php");

//if (!session_id()) session_start();
//error_reporting(E_ALL);
class db_cart {
	
	var $error;
	var $customer;
	var $curr_product;
	
	var $ship_id;
	var $ship_name;
	var $ship_name2;
	var $ship_address;
	var $ship_address2;
	var $ship_pc;
	var $ship_city;
	var $ship_country; 
	var $ship_msg;
	
	var $language = "en";
	
	var $order_array = array();
	
	function __construct()
    {
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this,$f='__construct'.$i)) {
            call_user_func_array(array($this,$f),$a);
        }
        
    }
   
    function __construct1($cart_param)
    {
        $this->error = false;
      if (!isset($_SESSION['order_id'])) {
     
        $this->get_order($cart_param);
       //$this->remove_old_orders(true); // use false if everyting older 1 day should be removed

      }
    } 
	
  function init_db(){
    if(!connect_to_whoisserver_db()) {
      $this->error = 'db_cart->init_db(): '.mysql_error();
      return false;
    }
    return true;
  }
	// get all messages from here (add your own language here)
	function messages($number) {
		$msg = array();
		switch ($this->language) {
			case "nl":
			$msg[1] = "Onbekend database fout, probeer het opnieuw.";
			$msg[2] = "Onbekend applicatie fout, contacteer de systeem administrator.";
			$msg[11] = "Het product is toegevoegd.";
			$msg[12] = "Het productaantal is gewijzigd.";
			$msg[13] = "Het productaantal kan niet gewijzigd of toegevoegd worden, perobeer het opnieuw.";
			$msg[14] = "Kies welk product moet worden toegevoegd aan de winkelwagen...";
			$msg[15] = "Het product is verwijderd.";
			$msg[16] = "Uw winkelwagen is leeg!";
			$msg[21] = "Het afleveradres is gewijzigd.";
			$msg[22] = "Uw order is verwerkt en een kopie hiervan is naar uw e-mailadres verzonden.";	
			$msg[31] = "Aantal";
			$msg[32] = "Omschrijving";
			$msg[33] = "Art. nr.";
			$msg[34] = "Stuksprijs";
			$msg[51] = "Bestelling verstuurd via ".$_SERVER['HTTP_HOST']." op ".date(DATE_FORMAT);
			$msg[52] = "incl. BTW";
			$msg[53] = "nvt.";
			break;
			case "de":
			$msg[1] = "Unbekannter Datenbankfehler, bitte probieren Sie es erneut.";
			$msg[2] = "Unbekannter Programmfehler, bitte wenden Sie sich an den Systemverwalter.";
			$msg[11] = "Das Product wurde hinzugef�gt.";
			$msg[12] = "Die Produktanzahl wurde ge�ndert.";
			$msg[13] = "Die Produktanzahl kann nicht ge�ndert oder hinzugef�gt werden, bitte probieren Sie es erneut.";
			$msg[14] = "Bitte w�hlen Sie ein Produkt dass an den Warenkorb inzugef�gt werden muss...";
			$msg[15] = "Das Produkt wurde gel�scht.";
			$msg[16] = "Ihr Warenkorb ist leer!";
			$msg[21] = "Die Lieferanscrift wurde ge�ndert.";
			$msg[22] = "Ihr Auftrag wurde verarbeitet und eine Kopie des Auftrages wurde an Ihre E-mailadresse versand.";	
			$msg[31] = "Anzahl";
			$msg[32] = "Beschreibung";
			$msg[33] = "Art.-Nr.";
			$msg[34] = "Einzelpreis";
			$msg[51] = "Bestellung versand via ".$_SERVER['HTTP_HOST']." am ".date(DATE_FORMAT);
			$msg[52] = "inkl. MwSt.";
			$msg[53] = "nicht zutreffend";
			break;
			default:
			// general error messages
			$msg[1] = "Unknown database error, please try it again.";
			$msg[2] = "Unknown application error, please contact the technical support.";
			// messages related to cart activity
			$msg[11] = "Added the product to your cart.";
			$msg[12] = "Updated the order row in your cart.";
			$msg[13] = "Can't add/update the product, please try it again.";
			$msg[14] = "Add some products to the cart...";
			$msg[15] = "Removed the order row from your cart.";
			$msg[16] = "The shopping cart is emtpy!";
			// checkout related messages
			$msg[21] = "Shipment address is successfully modified.";
			$msg[22] = "Your order is processed and a copy is send to you by e-mail.";		
			// labels product data
			$msg[31] = "Amount";
			$msg[32] = "Description";
			$msg[33] = "Art. no.";
			$msg[34] = "Single price";
			// messages used inside the mail
			$msg[51] = "Order posted via ".$_SERVER['HTTP_HOST']." on ".date(DATE_FORMAT);
			$msg[52] = "incl. VAT";
			$msg[53] = "n/a";
		}
		return $msg[$number];
	}
	// mail message to send critical error's to the admin
	function send_admin_mail() {
	    // mail to admin (comes with the next version)
	}
	// function to handle old orders
	// a method to clean up old orders, $remove_only_zeros is an option to handle records for records with a customer number
	function remove_old_orders($customer, $remove_only_zeros = true) {
		if (RECOVER_ORDER && $customer > 0) {
			$sql = sprintf("DELETE FROM %s WHERE open = 'y' AND customer = %d AND order_date < (NOW() - %d)", ORDERS, $customer, VALID_UNTIL * 86400);
		} else {
			$sql = sprintf("DELETE FROM %s WHERE open = 'y' AND order_date < (NOW() - %d)", ORDERS, VALID_UNTIL * 86400);
		}
		$sql .= ($remove_only_zeros) ? " AND customer = 0" : "";
		mysql_query($sql);
	}
  
  
  //if user is logged in, get the order from db(or crate a new one), 
    //otherwise create an anonymous order and save order_id in session 
  function get_order($options){
    $customer_id = $options['customer_id'];
    if($customer_id)return $this->get_order_db($options);
    else{
      return $this->get_order_session($options);
    }
  }
  function get_order_session($options){
    if(!$this->init_db())return false;
     $sql_new = sprintf("INSERT INTO %s (customer, order_date, order_for) VALUES (NULL, NOW(), '%s')", ORDERS,  $options['order_for']);
     if (mysql_query($sql_new)) {
          $_SESSION['order_id'] = mysql_insert_id();
     } else {
          $this->error = $this->messages(1) . ' get_order_session: '.mysql_error();
          return false;
     }   
     return true;
  }
  
	// get an existing order for a customer, or insert a new one id none exist
	function get_order_db($options) {
	  
	  $customer_id = $options['customer_id'];
    $order_for = $options['order_for'];
    
    $sql_check = sprintf("SELECT id FROM %s WHERE customer = '%s' AND status = 'O' order by id desc", ORDERS, $customer_id);
      
    if(!$this->init_db())return false;
		if ($res_check = mysql_query($sql_check)) {
			if (mysql_num_rows($res_check) > 0 && $customer_id) {
				$_SESSION['order_id'] = mysql_result($res_check, 0, "id");
			} else {
				$sql_new = sprintf("INSERT INTO %s (customer, order_date, order_for) VALUES ('%s', NOW(), '%s')", ORDERS, $customer_id, $order_for);
				if (mysql_query($sql_new)) {
					$_SESSION['order_id'] = mysql_insert_id();
				} else {
					$this->error = $this->messages(1) . 'get_order_db: '.mysql_error();
					return false;
				}
			}
		} else {
			$this->error = $this->messages(1) . 'get_order_db: '.mysql_error();
      return false;
		}
		return true;
	}
	// this method will chek if a order row for this product already exist
    //default to reverse whois product
    //possible product_types: 'R', 'RI'
	function check_existing_row($product, $product_type='R') {
	  
	  if(!$this->init_db())return false;
   
		$sql = sprintf("SELECT id FROM %s WHERE order_id = %d AND product_id = '%s' AND product_type='%s' ", ORDER_ROWS, $_SESSION['order_id'], $product, $product_type);
		if ($result = mysql_query($sql)) {
			if (mysql_num_rows($result) == 1) {
				$this->curr_product = mysql_result($result, 0, "id");
				return "old";
			} else {
				return "new";
			}
		} else { 
			return false;
		}	
	}




	// insert a not existing row to the current order
	function insert_row($prod_id, $prod_name, $prod_type, $quantity, $price, $vat_amount = VAT_VALUE) {
		if(!$this->init_db())return false;  
		$sql = sprintf("INSERT INTO %s (order_id, product_id, product_name, product_type, price, tax_perc, quantity) VALUES (%d, '%s', '%s', '%s', %f, %f, %d)", ORDER_ROWS, $_SESSION['order_id'], $prod_id, $prod_name, $prod_type, $price, $vat_amount, $quantity);
		//echo $sql;
		if (!mysql_query($sql)) {
			
			$this->error = $this->messages(1) . " insert_row: ".mysql_error();
		    return false;
        }
        return true;
	}
	// update/replace a single order row with the new quantity
	function update_row($row_id, $quantity, $replace = "yes") {
	  
		if ($quantity == 0) {
			return $this->delete_row($row_id);
		} else {
		  if(!$this->init_db())return false;
			$new_quant = ($replace == "no") ? "quantity + ".$quantity : $quantity;
			$sql = sprintf("UPDATE %s SET quantity = %s WHERE id = %d AND order_id = %d", ORDER_ROWS, $new_quant, $row_id, $_SESSION['order_id']);
			if (!mysql_query($sql)) {
      
        $this->error = $this->messages(1) . " update_row: ".mysql_error();
        return false;
      } 
     
		}
    return true;
	}
	// function to delete a single row
	function delete_row($row_id) {
	  if(!$this->init_db())return false;
		$sql = sprintf("DELETE FROM %s WHERE id = %d AND order_id = %d", ORDER_ROWS, $row_id, $_SESSION['order_id']);

		if (mysql_query($sql)) {
			return true;
		} else {
			$this->error = $this->messages(1) . " delete_row: $row_id ".mysql_error();
      return false;
		}	
	}
	// handle a order row while using the methodes above
	function handle_cart_row($prod_id, $prod_name, $prod_type, $quantity, $price, $replace = "no", $vat_amount = VAT_VALUE) {
		$check_row = $this->check_existing_row($prod_id, $prod_type);

		if ($check_row == "old") {
			return $this->update_row($this->curr_product, $quantity, $replace);
		} elseif ($check_row == "new") {

			return $this->insert_row($prod_id, $prod_name, $prod_type, $quantity, $price, $vat_amount);
		} else {
			$this->error = $this->messages(13) . " handle_cart_row($prod_id): ".mysql_error();
		  return false;
    }
	}
	// new method to get the old amount from a single order row (NEW in 1.12)
	function get_amount_from_row($product) {
		$sql = sprintf("SELECT quantity FROM %s WHERE order_id = %d AND product_id = '%s'", ORDER_ROWS, $_SESSION['order_id'], $product);
		if ($result = mysql_query($sql)) {
			if (mysql_num_rows($result) == 1) {
				return mysql_result($result, 0, "quantity");
			} else {
				return 0;
			}
		} else {
			return 0;
		}	
	}
	// get the number of ordered rows which belong to this customer
	function get_number_of_records() {
		$sql = sprintf("SELECT COUNT(*) AS num FROM %s AS r, %s AS ord WHERE ord.id = r.order_id AND ord.id = %d AND ord.status = 'O'", ORDER_ROWS, ORDERS, $_SESSION['order_id']);
		if ($result = mysql_query($sql)) {
			return mysql_result($result, 0, "num");
		} else {
			$this->error = $this->messages(1);
			return;
		}
	}
	// get all order rows from the DB and store them in to an array
	function show_ordered_rows() {
	  if(!$this->init_db())return false;
		$sql = sprintf("SELECT r.id, r.product_id, r.product_name, r.price, r.tax_perc, r.quantity FROM %s AS r, %s AS ord WHERE ord.id = r.order_id AND ord.id = %d AND ord.status = 'O'", ORDER_ROWS, ORDERS, $_SESSION['order_id']);
		if ($result = mysql_query($sql)) {
			if (mysql_num_rows($result) > 0) {
				$counter = 0;
				while ($row = mysql_fetch_assoc($result)) {
					foreach($row as $key => $val) {
						$this->order_array[$counter][$key] = $val;
					}
					$counter++;
				}
			} 
		} else {
			$this->error = $this->messages(1) ." show_ordered_rows: ".mysql_error();
      return false;
		}
    return true;
	}
	
  function show_ordered_rows_by_type($type) {
      $order_array = $this->get_ordered_rows_by_type($type, $_SESSION['order_id']);
      if($order_array === false)return false;
      
      return $this->order_array = $order_array;
  }
  
  function get_ordered_rows_by_type($type, $order_id, $order_status='O') {
    if(!$this->init_db())return false;
    $rows = array();
    $table="";
    if($type=='R'){
        $table="whois_report";
    }
    else if($type=='RI'){
        $table="reverseip_report";
    }
      $raw_sql = "SELECT unix_timestamp(rep.created_date) as created_date_long, unix_timestamp(rep.updated_date) as updated_date_long, 
        rep.*, r.id, r.product_id, r.product_name, r.price, r.tax_perc, r.quantity FROM %s AS r, %s AS ord, $table as rep WHERE ord.id = r.order_id AND ord.id = %d "
        . ($order_status? " AND ord.status = '$order_status' ":"") ." AND rep.report_id = r.product_id and r.product_type='%s'";

      $sql = sprintf($raw_sql, ORDER_ROWS, ORDERS, $order_id, $type);
    
    //echo $sql;

    
    if ($result = mysql_query($sql)) {
      $this->order_array=array();  
      if (mysql_num_rows($result) > 0) {
        $counter = 0;
        while ($row = mysql_fetch_assoc($result)) {
          foreach($row as $key => $val) {
            $rows[$counter][$key] = $val;
          }
          $counter++;
        }
      } 
    } else {
      $this->error = $this->messages(1) ." get_ordered_rows: ".mysql_error();
      return false;
    }
    if($type == 'R'){
      foreach($rows as $index=>$row){
 
        $report = Report::get_report_from_db_row($row);
        $rows[$index]['report'] = $report;
      }
    }
    else if ($type=='RI'){
        foreach($rows as $index=>$row){

            $report = ReverseIPReport::get_report_from_db_row($row);
            $rows[$index]['report'] = $report;
        }
    }
    return $rows;
  }
  function get_all_ordered_rows_by_type($type, $order_id) {
    return $this->get_ordered_rows_by_type($type, $order_id, false);
  }

  
  function show_total_value_session(){
    $t=0;
    foreach($this->order_array as $item){
      
      $t+=$item['price'];
    }
    return $t;
  } 
   function get_num_items_session(){
    $t=0;
   
    foreach($this->order_array as $item){
   
      $t++;
    }
   
    return $t;
  } 
  
  function get_num_credits_session(){
    $t=0;
   
    foreach($this->order_array as $item){
   		$report=$item['report'];
   		if($report){ 
      		$report->compute_credits();
      		$t+=$report->num_credits;
    	}
    }
   
    return $t;
  } 
    
	// show total value of the current cart
	function show_total_value() {
		$sql = sprintf("SELECT SUM(quantity * price) AS total FROM %s WHERE order_id = %d", ORDER_ROWS, $_SESSION['order_id']);
		if (!$result = mysql_query($sql)) {
			$this->error = $this->messages(1);
			return;
		} else {
			$total_amount = mysql_result($result, 0, "total");
			mysql_free_result($result);
			return $total_amount;
		}
	}
	// calculate VAT, switch between true and false to handle netto or brutto prices
	function create_total_VAT() {
		$sql = sprintf("SELECT price, tax_perc, quantity FROM %s WHERE order_id = %d", ORDER_ROWS, $_SESSION['order_id']);
		if (!$result = mysql_query($sql)) {
			$this->error = $this->messages(1);
		} else {
			$vat = 0;
			if (mysql_num_rows($result) > 0) {
				while ($obj = mysql_fetch_object($result)) {
					$vat_dec = $obj->tax_perc / 100;
					if (INCL_VAT) {
						$vat = $vat + ($obj->price * $obj->quantity) / (1 + $vat_dec) * $vat_dec;
					} else {
						$vat = $vat + ($obj->price * $obj->quantity) * $vat_dec;
					}
				}
			}
		}
		mysql_free_result($result);
		return $vat;
	}
	// check if already an shipment record exist, if yes return the data
	function check_return_shipment() {
		$sql = sprintf("SELECT COUNT(*) AS test FROM %s WHERE order_id = %d", SHIP_ADDRESS, $_SESSION['order_id']);
		if ($result = mysql_query($sql)) {
			if (mysql_result($result, 0, "test") == 1) {
				return true;
			} else {
				return false;
			}
		} else {
			$this->error = $this->messages(1);
			return false; 
		}
	}
	// read the current shipment data and set the variabels
	function set_shipment_data() {
		if (!$this->check_return_shipment()) { // create an empty record if there is no shipment data
			$this->insert_new_shipment();
		}
		$sql = sprintf("SELECT * FROM %s WHERE order_id = %d", SHIP_ADDRESS, $_SESSION['order_id']);
		if ($result = mysql_query($sql)) {
			$obj = mysql_fetch_object($result);
			$this->ship_name = $obj->name;
			$this->ship_name2 = $obj->name2;
			$this->ship_address = $obj->address;
			$this->ship_address2 = $obj->address2;
			$this->ship_pc = $obj->postal_code;
			$this->ship_city = $obj->place;
			$this->ship_country = $obj->country;
			$this->ship_msg = $obj->message;
		} else {
			$this->error = $this->messages(1);
		}
	}
	// function to insert a new shipment record
	function insert_new_shipment() {
		$sql = sprintf("INSERT INTO %s (order_id, name, name2, address, address2, postal_code, place, country) VALUES (%d, %s, %s, %s, %s, %s, %s, %s)",
			SHIP_ADDRESS, 
			$_SESSION['order_id'],
			$this->prepare_string_value($this->ship_name), 
			$this->prepare_string_value($this->ship_name2), 
			$this->prepare_string_value($this->ship_address), 
			$this->prepare_string_value($this->ship_address2), 
			$this->prepare_string_value($this->ship_pc), 
			$this->prepare_string_value($this->ship_city), 
			$this->prepare_string_value($this->ship_country));
		if (!mysql_query($sql)) {
			$this->error = $this->messages(1);
		}
	}
	// insert/update the shipment address vor the current order
	function update_shipment($name, $name2 = "", $address, $address2 = "", $postal, $city, $country, $msg) {
		if ($this->check_return_shipment()) {
			$sql = sprintf("UPDATE %s SET name = %s, name2 = %s, address = %s, address2 = %s, postal_code = %s, place = %s, country = %s, message = %s WHERE order_id = %d", 
				SHIP_ADDRESS, 
				$this->prepare_string_value($name), 
				$this->prepare_string_value($name2), 
				$this->prepare_string_value($address), 
				$this->prepare_string_value($address2), 
				$this->prepare_string_value($postal), 
				$this->prepare_string_value($city), 
				$this->prepare_string_value($country),
				$this->prepare_string_value($msg),
				$_SESSION['order_id']);
			if (mysql_query($sql)) {
				$this->error = $this->messages(21);
			} else {
				$this->error = $this->messages(1);
			}
		}	
	}
	// this method will delete all records for the current order and the visitor is redirect to the main page
	function cancel_order() {
		$err_level = 0;
		if (!mysql_query(sprintf("DELETE FROM %s WHERE order_id = %d", SHIP_ADDRESS, $_SESSION['order_id']))) $err_level++;
		if (!mysql_query(sprintf("DELETE FROM %s WHERE order_id = %d", ORDER_ROWS, $_SESSION['order_id']))) $err_level++;
		if (!mysql_query(sprintf("DELETE FROM %s WHERE id = %d", ORDERS, $_SESSION['order_id']))) $err_level++;
		if ($err_level > 0) {
			$this->error = $this->messages(1);
		} else {
			unset($_SESSION['order_id']);
			header("Location: ".PROD_IDX);
		}	
	}
	// use the property $show_address to show/hide the shipment in the confirmation mail
	function check_out($mailto, $show_address = true) {
		$this->set_shipment_data();
		if ($this->mail_order($mailto, $show_address)) {
			header("Location: ".CONFIRM);
		} else {
			$this->error = $this->messages(2);
		}
	}
	function parse_mail_template($tags, $template) {
		$tpl_str = file_get_contents($template);
		foreach ($tags as $tag => $val) {
			$tpl_str = str_replace("{".$tag."}", $val, $tpl_str);
		}
		return $tpl_str;
	}
	function mail_order($to, $show_shipment) {
		$tags = array();
		$tags['order_head'] = sprintf("\t%-10s  %-30s  %-20s  %-10s\r\n", $this->messages(31), $this->messages(32), $this->messages(33), $this->messages(34));
		$this->show_ordered_rows(); // rows in the order_array
		$rows = "";
		foreach ($this->order_array as $val) {
			$rows .= sprintf("\t%-10s  %-30s  %-20s  %-10s\r\n", $val['quantity'], $val['product_name'], $val['product_id'], $this->format_value($val['price'], false));
		}
		$tags['order_rows'] = $rows;
		$tags['total_amount'] = $this->format_value($this->show_total_value(), false);
		$tags['vat_info'] = (INCL_VAT) ? "(".$this->messages(52).")" : "";
		$tags['vat_amount'] = $this->format_value($this->create_total_VAT(), false);
		$tags['message'] = $this->ship_msg; 
		if ($show_shipment) {
			// start building shipment
			$ship_to_str = $this->ship_name;
			if (!empty($this->ship_name2)) $ship_to_str .= "\r\n".$this->ship_name2;
			$ship_to_str .= "\r\n".$this->ship_address;
			if (!empty($this->ship_address2)) $ship_to_str .= "\r\n".$this->ship_address2;
			$ship_to_str .= sprintf("\r\n%s %s\r\n%s", $this->ship_pc, $this->ship_city, $this->ship_country);
			// shipment
		} else {
			$ship_to_str = $this->messages(53);
		}
		$tags['ship_to'] = $ship_to_str;
		$tags['kr_name'] = SITE_MASTER;
		$body = $this->parse_mail_template($tags, $_SERVER['DOCUMENT_ROOT'].CART_CLASS_PATH.$this->language."/".ORDER_TMPL);
		if ($this->send_mail($to, $body, $this->messages(51))) {
			return true;
		} else {
			return false;
		}
	}
	function send_mail($to, $body, $subject) { 
		$header = "From: \"".SITE_MASTER."\" <".SITE_MASTER_MAIL.">\r\n";
		$header .= "Cc: ".SITE_MASTER_MAIL."\r\n";
		$header .= "MIME-Version: 1.0\r\n";
		$header .= "Content-Type: text/plain; charset=\"".MAIL_ENCODING."\"\r\n";
		$header .= "Content-Transfer-Encoding: 7bit\r\n";
		if (mail($to, $subject, $body, $header)) {
			return true;
		} else {
			return false;
		}
	}
	function close_order() {
		$sql = sprintf("UPDATE %s SET processed_on = NOW(), open = 'n' WHERE id = %d", ORDERS, $_SESSION['order_id']);
		if (mysql_query($sql)) {
			$this->error = $this->messages(22);
			unset($_SESSION['order_id']);
		} else {
			$this->error = $this->messages(1);
		}
	}
	// format a decimal number into an euro amount 
	// $encoding is used for the browser and 
	function format_value($value, $encoding = true) {
		if ($encoding) {
			$curr = (ord(CURRENCY) == "128") ? "&#8364;" : htmlentities(CURRENCY);
		} else {
			$curr= CURRENCY;
		}
		$formatted = $curr." ".number_format($value, 2, ",", ".");
		return $formatted;
	}
	// simple string preperation to prepend SQL injections
	function prepare_string_value($value) {
		$new_value = (!get_magic_quotes_gpc()) ? addslashes($value) : $value;
		$new_value = ($value != "") ? "'".trim($value)."'" : "''";
		return $new_value;
	}
  function update_order($order_params, $report_params,  $order_id){
    if(!$this->init_db())return false;
    if(!$this->update_order_helper($order_params, $order_id))return false;
    if(!$this->update_order_reports_helper($report_params, $order_id)) return false;
    return true;
  }
  function update_order_helper($params, $order_id){
     $SQL = vsprintf("update db_cart_orders set ". 
              implode(',', 
                array_map(function($key) use($params){
                  $val = $params[$key];
                  return "$key = ". (is_numeric($val)?"%s":"'%s'");
                }, array_keys($params))
              ) 
              . " where id = $order_id", 
              array_map(function($key) use($params){             
                  return $params[$key]; 
              }, array_keys($params))
          );
     
    if(!mysql_query($SQL)){
      $this->error = "update_order($order_id): ($SQL): ".mysql_error();  
      return false;
    }         
    return true;    
  }
  
  function update_order_reports($report_params, $order_id){
    if(!$this->init_db())return false;
    return $this->update_order_reports_helper($report_params, $order_id);
             
  }
  function update_order_reports_helper($report_params,  $order_id){
        $SQL = vsprintf("update whois_report w, db_cart_rows r set ". 
              implode(',', 
                array_map(function($key) use($report_params){
                  $val = $report_params[$key];
                  return "w.$key = ". (is_numeric($val)?"%s":"'%s'");
                }, array_keys($report_params))
              ) 
              . " where w.report_id = r.product_id and r.order_id = $order_id", 
              array_map(function($key) use($report_params){             
                  return $report_params[$key]; 
              }, array_keys($report_params))
          );
     
    if(!mysql_query($SQL)){
      $this->error = "update_order_reports($report_id) ($SQL): ".mysql_error();  
      return false;
    }         
    return true;
  }
  function update_report($report_params, $report_id){
    if(!$this->init_db())return false;
        $SQL = vsprintf("update whois_report w set ". 
              implode(',', 
                array_map(function($key) use($report_params){
                  $val = $report_params[$key];
                  return "w.$key = ". (is_numeric($val)?"%s":"'%s'");
                }, array_keys($report_params))
              ) 
              . " where w.report_id = $report_id", 
              array_map(function($key) use($report_params){             
                  return $report_params[$key]; 
              }, array_keys($report_params))
          );
     
    if(!mysql_query($SQL)){
      $this->error = "update_report($report_id) ($SQL): ".mysql_error();  
      return false;
    }         
    return true;    
  }
    function update_reverseip_report($report_params, $report_id){
        if(!$this->init_db())return false;
        $SQL = vsprintf("update reverseip_report w set ".
            implode(',',
                array_map(function($key) use($report_params){
                    $val = $report_params[$key];
                    return "w.$key = ". (is_numeric($val)?"%s":"'%s'");
                }, array_keys($report_params))
            )
            . " where w.report_id = $report_id",
            array_map(function($key) use($report_params){
                return $report_params[$key];
            }, array_keys($report_params))
        );

        if(!mysql_query($SQL)){
            $this->error = "update_reverseip_report($report_id) ($SQL): ".mysql_error();
            return false;
        }
        return true;
    }
}
?>