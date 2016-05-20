<?php
$secretKey="xxx";
function generateCaptchaAns($s, $salt){
		//crypt with salt hangs in 5.3 on windows
		return md5($s.$salt);
	}
function generateCaptchaQuestion($total){
		$a=rand(1, $total);
		return array($a,$total-$a);

}
?>