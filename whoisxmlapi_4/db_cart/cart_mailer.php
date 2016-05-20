<?php
include_once($_SERVER['DOCUMENT_ROOT']."/classes/attach_mailer/attach_mailer_class.php");

class Cart_mailer {
	
	var $mail_obj;
	
	// the constants are defined via the 
	function Cart_mailer($name, $from, $to, $subject, $cc = "", $bcc = "") {
		$this->mail_obj = new attach_mailer($name, $from, $to, $cc, $bcc, $subject);
	}
	function add_embed_images($img_array) {
		if (count($img_array) > 0) {
			foreach ($img_array as $var) {
				$this->mail_obj->add_html_image($var);
			}
		}
	}
	function add_attachments($att_array) {
		if (count($att_array) > 0) {
			foreach ($att_array as $var) {
				$this->mail_obj->add_attach_file($var);
			}
		}
	}
	function add_html_part($html_code) {
		$this->mail_obj->html_body = $html_code;
	}
	function add_text_part($txt) {
		$this->mail_obj->text_body = $txt;
	}
	function send_mail() {
		$this->mail_obj->process_mail();
	}
}
?>