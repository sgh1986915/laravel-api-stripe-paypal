<?php

function create_api_keys() {
	$new_keys = array();

	$configFile = public_path() . DIRECTORY_SEPARATOR . 'openssl.cnf';
	$config = array('config' => $configFile,"digest_alg" => "sha512","private_key_bits" => 512,"private_key_type" => OPENSSL_KEYTYPE_RSA);

	$rsa = openssl_pkey_new($config);
	openssl_pkey_export($rsa, $privatekey, null, $config);

	/*$publickey = openssl_pkey_get_details($rsa);
	$publickey = $publickey['key'];*/

	$privatekey = str_replace('-----BEGIN PRIVATE KEY-----', '', $privatekey);
	$privatekey = str_replace('-----END PRIVATE KEY-----', '', $privatekey);
	$privatekey = str_replace("\n", '', $privatekey);
	$privatekey = str_replace("\r", '', $privatekey);
	$new_keys['secret'] = trim($privatekey);
	$new_keys['apikey'] = get_v4UUID();

	return $new_keys;
}

function get_v4UUID() {
	return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

		mt_rand(0, 0xffff), mt_rand(0, 0xffff),

		mt_rand(0, 0xffff),

		mt_rand(0, 0x0fff) | 0x4000,

		mt_rand(0, 0x3fff) | 0x8000,

		mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
		);
}

/* Sync Laravel session and normal PHP session */
function startWhoisSession($userdata) {
	if(!session_id()) {
		session_start();
	}
	$_SESSION['laravel_user'] = $userdata;
}

function destroyWhoisSession() {
	if(!session_id()) {
		session_start();
	}
	unset($_SESSION['laravel_user']);
	unset($_SESSION['myuser']);
	unset($_SESSION['order_id']);
	unset($_SESSION['cart']);
	unset($_SESSION['_affiliate']);
	unset($_SESSION);
	session_destroy();
}
?>