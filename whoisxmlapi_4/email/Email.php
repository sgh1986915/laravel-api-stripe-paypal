<?php
	// include dirname(__FILE__) . '/../../PHPLib/swiftmail/lib/swift_required.php';
// modified
if(!class_exists('Swift')) {
	include dirname(__FILE__) . '/../../PHPLib/swiftmail/lib/swift_required.php';
}
class Email{
   	public function send_mail($to,$subject,$message, $headers, $mime_type="text/plain", $attachment=false){
		//Create the Transport
		/*
		$transport = Swift_SmtpTransport::newInstance('smtpout.secureserver.net', 80)
  			->setUsername('support@whoisxmlapi.com')
  			->setPassword('zhang1');
  			*/
   		/*
   		$transport = Swift_SmtpTransport::newInstance('smtp-relay.gmail.com', 587, 'tls')
   		->setUsername('support@whoisxmlapi.com')
   		->setPassword('p1ssw2rd');
		*/

   		$transport = Swift_SmtpTransport::newInstance()
   		->setHost('smtp.gmail.com')
   		->setPort(465)
   		->setEncryption('ssl')
   		->setUsername('support@whoisxmlapi.com')
   		->setPassword('p1ssw2rd');
   		;
		$mailer = Swift_Mailer::newInstance($transport);
		$message = Swift_Message::newInstance($subject)
  				->setTo($to)
  				->setBody($message,$mime_type);

		if($attachment){
			if(is_array($attachment)){
				foreach ($attachment as $a){
					$message->attach(Swift_Attachment::fromPath($a));
				}
			}
			else{
				$message->attach(Swift_Attachment::fromPath($attachment));
			}
		}

		if(isset($this->from))$message->setFrom($this->from);
		if(isset($this->type))$message->setContentType($this->type);
		if(isset($this->contentType))$message->setCharset($this->contentType);
		if(isset($this->cc))$message->setCc($this->cc);
		if(isset($this->bcc))$message->setBcc($this->bcc);
		if(isset($this->replyTo))$message->setReplyTo($this->replyTo);
		if(isset($this->returnPath))$message->setReturnPath($this->returnPath);

		return $numSent = $mailer->send($message);



	}


}
?>
