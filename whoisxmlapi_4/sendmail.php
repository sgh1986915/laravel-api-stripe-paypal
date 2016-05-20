<?php
//require_once "Mail.php"; //requires pear which is not working yet on production
require_once 'swift_required.php';

$email_host = "smtpout.secureserver.net";
$smtp_username = "support@whoisxmlapi.com";
$smtp_password = "PASSWORD";
function sendmail($from, $to, $subject, $body){

		//Create the Transport
		$transport = Swift_SmtpTransport::newInstance('smtpout.secureserver.net', 80)
  			->setUsername('support@domainsearchguru.com')
  			->setPassword('PASSWORD');
		$mailer = Swift_Mailer::newInstance($transport);
		$message = Swift_Message::newInstance($subject)
  				->setTo($to)
  				->setBody($message);
	
		if(isset($this->from))$message->setFrom($this->from);
		if(isset($this->type))$message->setContentType($this->type);
		if(isset($this->contentType))$message->setCharset($this->contentType);
		if(isset($this->cc))$message->setCc($this->cc);
		if(isset($this->bcc))$message->setBcc($this->bcc);
		if(isset($this->replyTo))$message->setReplyTo($this->replyTo);
		if(isset($this->returnPath))$message->setReturnPath($this->returnPath);
		
		return $numSent = $mailer->send($message);

		
 	
	
}
/*
function sendmail($from, $to, $subject, $body){
	global $email_host, $smtp_username, $smtp_password;
	$headers = array ('From' => $from,
  		'To' => $to,
  		'Subject' => $subject);
	$smtp = Mail::factory('smtp',
  		array ('host' => $email_host,
    		'auth' => true,
    		'username' => $smtp_username,
    		'password' => $smtp_password));	
	$mail = $smtp->send($to, $headers, $body);
	
	if (PEAR::isError($mail)) {
  		throw new Exception($mail->getMessage());
 	}
}
*/







?>