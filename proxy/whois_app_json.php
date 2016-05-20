<?php
header('content-type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

/*
quota: day/week/month/year
duration: positive integer
*/
$data = array (
	'whois' =>
	array (
		'quota' => 50,
		'duration' => 'week',
		),
	'reverse_whois' =>
	array (
		'quota' => 20,
		'duration' => 'week',
		),
	);
if(!empty($_GET['callback'])) {
	echo $_GET['callback'] . '('.json_encode($data).')';
} else {
	echo json_encode($data);
}
?>