<?php
class BalanceController extends BaseController {

	public function show()
	{
		$username = Auth::user()->username;
		$password = Auth::user()->password;
		//$url = 'http://www.whoisxmlapi.com/accountServices.php?servicetype=accountbalance&username='.urlencode($username).'&password='.urlencode($password).'&output_format=JSON';
		$url = URL::to('/').'/whoisxmlapi_4/accountServices.php?servicetype=accountbalance&username='.urlencode($username).'&password='.urlencode($password).'&output_format=JSON';
		
		$balances = json_decode(file_get_contents($url), true);
		return View::make('balances.view')->with('balances',$balances);
	}
}