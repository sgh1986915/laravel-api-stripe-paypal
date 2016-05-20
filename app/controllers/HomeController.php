<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function __construct()
	{
		if(!Auth::check()) {
			if(!session_id()) {
				session_start();
			}
			if(!empty($_SESSION['laravel_user']) || !empty($_SESSION['myuser'])) {
				destroyWhoisSession();
			}
		}
		if(!empty($_COOKIE['order_error'])) {
			$order_error = $_COOKIE['order_error'];
			Session::flash('success', $order_error);
			Session::flash('successType', 'danger');
		} else {
			Session::forget('success');
			Session::forget('successType');
		}
		setcookie("order_error", "", time() - 3600, '/');
	}

	public function home()
	{
		return View::make('home');
	}

	public function innerpage()
	{
		return View::make('innerpage');
	}

	public function whoisapidoc()
	{
		return View::make('whoisapidoc');
	}

	public function whoisdd()
	{
		return View::make('whoisdd');
	}

	public function whoissp()
	{
		$form_action =  URL::to('whoisxmlapi_4/order_process.php');
		$user_email = (Auth::check() && !empty(Auth::user()->email)) ? Auth::user()->email : '';
		$data = array('form_action' => $form_action,'user_email' => $user_email);
		return View::make('whoissp')->with('data',$data);
	}

	public function domainWhois()
	{
		return View::make('domainWhois');
	}

	public function brandalert()
	{
		return View::make('brandalert');
	}

	public function registeralert()
	{
		return View::make('registeralert');
	}

	public function bulkWhois()
	{
		return View::make('bulkWhois');
	}

	public function reverseip()
	{
		return View::make('reverseip');
	}

	public function reverseipApi()
	{
		return View::make('reverseipApi');
	}

	public function reversewhoisapi()
	{
		return View::make('reversewhoisapi');
	}

	public function registrarwhois()
	{
		return View::make('registrarwhois');
	}

	public function newDomain()
	{
		$user_email = (Auth::check() && !empty(Auth::user()->email)) ? Auth::user()->email : '';
		return View::make('newDomain')->with('user_email',$user_email);
	}
	//function for all registered domains
	public function allDomain()
	{
		$user_email = (Auth::check() && !empty(Auth::user()->email)) ? Auth::user()->email : '';
		return View::make('allDomain')->with('user_email',$user_email);
	}

	public function contactUs()
	{
		return View::make('contactUs');
	}
	public function thankyou()
	{
		return View::make('thankyou');
	}
	public function thankyou_rw()
	{
		return View::make('thankyou_rw');
	}
	public function orderapi()
	{
		$form_action = URL::to('order-api-summary.php');
		$data = array('form_action' => $form_action);
		return View::make('orderapi')->with('data',$data);
	}
	public function sitemap()
	{
		return View::make('sitemap');
	}
	public function reversewhoislookuppricing()
	{
		$pay_choice = 'pp' ;
		$form_action = URL::to('whoisxmlapi_4/bulk-rw-order_process.php');
		$username = (Auth::check() && !empty(Auth::user()->username)) ? Auth::user()->username : '';
		$data = array('pay_choice' => $pay_choice,'form_action' => $form_action,'username' => $username);
		return View::make('reversewhoislookuppricing')->with('data',$data);
	}
	public function orderapiSummary()
	{
		$form_action = URL::to('order-api-checkout.php');
		$data = array('form_action' => $form_action);
		return View::make('orderapiSummary')->with('data',$data);
	}
	public function orderapiCheckout()
	{
		$queryString = '?'.http_build_query(Input::except('update','remove','pay_choice'));
		$form_action = URL::to('whoisxmlapi_4/order-api-process.php').$queryString;
		$cancel_action = URL::to('order-api.php').$queryString;
		$previous_action = URL::to('order-api-summary.php').$queryString;
		$data = array('form_action' => $form_action,'cancel_action' => $cancel_action,'previous_action' => $previous_action,'queryString' => $queryString);
		return View::make('orderapiCheckout')->with('data',$data);
	}

	public function orderNow()
	{
		Session::put('pay_choice', 'pp');
		$pay_choice = 'pp' ;
		$form_action = URL::to('whoisxmlapi_4/order_process.php');
		$username = (Auth::check() && !empty(Auth::user()->username)) ? Auth::user()->username : '';
		$data = array('pay_choice' => $pay_choice,'form_action' => $form_action,'username' => $username);
		return View::make('orderNow')->with('data',$data);
	}

	public function orderCC()
	{
		Session::put('pay_choice', 'cc');
		$pay_choice = 'cc' ;
		$form_action =  URL::to('cc.php');
		$username = (Auth::check() && !empty(Auth::user()->username)) ? Auth::user()->username : '';
		$data = array('pay_choice' => $pay_choice,'form_action' => $form_action,'username' => $username);
		return View::make('orderNow')->with('data',$data);
	}

	public function loginNew()
	{
		return View::make('loginNew');
	}

	public function viewShopping()
	{
		return View::make('viewShopping');

	}
	public function pricingchart()
	{
		return View::make('pricingchart');
	}
	public function domainIp()
	{
		return View::make('domainIp');

	}
	public function whitepapers()
	{
		return View::make('whitepapers');

	}
	public function registration()
	{
		return View::make('registration');
	}

	public function termsofservice()
	{
		return View::make('termsofservice');
	}

	public function reverseIppurchase()
	{
		$pay_choice = 'pp' ;
		$form_action = URL::to('whoisxmlapi_4/bulk-ri-order_process.php');
		$username = (Auth::check() && !empty(Auth::user()->username)) ? Auth::user()->username : '';
		$data = array('pay_choice' => $pay_choice,'form_action' => $form_action,'username' => $username);
		return View::make('reverseIppurchase')->with('data',$data);
	}

	public function orderCredit_reverseWhoisLookup()
	{
		Session::put('pay_choice', 'pp');
		$pay_choice = 'pp' ;
		$form_action = URL::to('whoisxmlapi_4/bulk-rw-order_process.php');
		$username = (Auth::check() && !empty(Auth::user()->username)) ? Auth::user()->username : '';
		$data = array('pay_choice' => $pay_choice,'form_action' => $form_action,'username' => $username);
		return View::make('orderCredit_reverseWhoisLookup')->with('data',$data);
	}

	public function reverseWhoisLookup()
	{
		if(!Request::ajax()){
			return View::make('innerpage');
		}
		$rid = Session::get('rid',1);
		$post_data = Input::all();
		$post_data['rid'] = $rid;
		$post_data['add_report'] = 1;
		$post_data['search_type'] = Input::get('search_type',1);
		$queryString = '?'.http_build_query($post_data);
		return View::make('reverseWhoisLookup')->with('queryString',$queryString);
	}

	public function order_rw_report()
	{
		/*$pay_choice = Input::has('order_username') ? Input::get('order_username') : 'pp';
		$session_user = false;
		if(Auth::check()) {
			$session_user = User::find(Auth::id())->toArray();
		}
		$order_username = "";
		if(Input::has('order_username')) {
			$order_username = Input::get('order_username');
		} else if(!empty($session_user['username'])) {
			$order_username = $session_user['username'];
		}
		$order_error = false;
		$cart = Session::get('cart', false);
		$order_id = Session::get('order_id', false);
		if (empty($cart)) {
			if(!empty($order_id)) {
				$cart = DbCartOrder::find($order_id)->toArray();
			} else {
				if(!Auth::check()) {
					$DbCartOrder = new DbCartOrder;
					$DbCartOrder->customer = NULL;
					$DbCartOrder->order_for = $order_username;
					if($DbCartOrder->save()) {
						$order_id = $DbCartOrder->id;
					} else {
						$order_error = 'Unknown database error, please try it again.';
					}
				} else {
					$cart = DbCartOrder::where('customer', '=', $session_user['username'])->where('status', '=', 'O')->orderBy('id', 'DESC')->get()->toArray();
					if(!empty($cart[0]['id'])) {
						$order_id = $cart[0]['id'];
					} else {
						$DbCartOrder = new DbCartOrder;
						$DbCartOrder->customer = $session_user['username'];
						$DbCartOrder->order_for = $order_username;
						if($DbCartOrder->save()) {
							$order_id = $DbCartOrder->id;
						} else {
							$order_error = 'Unknown database error, please try it again.';
						}
					}
				}
				Session::put('order_id', $order_id);
			}
		}

		$data = array('session_user' => $session_user,'pay_choice' => $pay_choice,'order_username' => $order_username,'order_id' => $order_id,'order_error' => $order_error,'cart' => $cart);
		/*print_r($data);
		exit();
		return View::make('order_rw_report')->with('data',$data);*/
		Session::put('pay_choice', 'pp');
		$pay_choice = 'pp' ;
		$form_action = URL::to('whoisxmlapi_4/reverse-whois/order_process.php');
		$username = (Auth::check() && !empty(Auth::user()->username)) ? Auth::user()->username : '';
		$data = array('pay_choice' => $pay_choice,'form_action' => $form_action,'username' => $username);
		return View::make('order_rw_report')->with('data',$data);
	}

	public function reverseiplookup()
	{
		return View::make('reverseiplookup');
	}
	public function affiliate_Program()
	{
		return View::make('affiliate_Program');
	}
	public function privacyPolicy()
	{
		return View::make('privacypolicy');
	}
	public function support()
	{
		return View::make('support');
	}
	public function popup()
	{
		return View::make('popup');
	}
	public function custom_order()
	{
		$custom_form_action = URL::to('whoisxmlapi_4/custom_order_process.php');
		$order_form_action = URL::to('whoisxmlapi_4/order_process.php');
		$username = (Auth::check() && !empty(Auth::user()->username)) ? Auth::user()->username : '';
		$data = array('custom_form_action' => $custom_form_action,'order_form_action' => $order_form_action,'username' => $username);
		return View::make('custom_order')->with('data',$data);
	}
	public function orderstatus()
	{
		$libPath = base_path(). "/whoisxmlapi_4";
		require_once $libPath ."/order_util.php";

		$order_username = Input::get('order_username', '');
		$order_type = Input::get('order_type', '');
		$query_quantity = Input::get('query_quantity', '');
		$membership = getMembership();
		if(Input::has('submit')) {
			if($order_type=='whoisapi'){
				$order_error = false;
				if(!$order_username || strlen($order_username) <= 0){
					$order_error ="You must enter a username for the account you wish to fill.  If you don't have one, you may either create an account now, or create an account with the username specified here after the purchase.";
				}
				else if(!$this->_validate_username($order_username)){
					$order_error = "The account username must contain only letters, numbers, dot, underscores and @.";
				}

				if(!$membership && !$query_quantity){
					$order_error = "You must either select the number of queries you wish to purchase or pick a membership.";
				}
				if($order_error){
					setcookie('order_error',$order_error,time()+3600,'/');
					return Redirect::to('order.php');
				}

			}
			else if($order_type == 'custom_wdb'){
				$custom_wdb_ids=Input::get('custom_wdb_ids');
				$custom_wdb_order_error=false;
				if(!$custom_wdb_ids || count($custom_wdb_ids)==0){
					$custom_wdb_order_error = "You must select an Alexa/Quantcast Whois database to purchase.";
				}
				if($custom_wdb_order_error){
					setcookie('order_error',$custom_wdb_order_error,time()+3600,'/');
					return Redirect::to('order.php');
				}
			}
			else if($order_type == 'cctld_wdb'){

				$cctld_wdb_ids=Input::get('cctld_wdb_ids');
				$cctld_wdb_order_error=false;
				if(!$cctld_wdb_ids || count($cctld_wdb_ids)==0){
					$cctld_wdb_order_error = "You must select at least one cctld Whois database to purchase.";
				}
				if($cctld_wdb_order_error){
					setcookie('order_error',$cctld_wdb_order_error,time()+3600,'/');
					return Redirect::to('order.php');
				}
			}
		}
		$form_action =  URL::to('whoisxmlapi_4/order_process.php').'?'.http_build_query(Input::all());
		$user_email = (Auth::check() && !empty(Auth::user()->email)) ? Auth::user()->email : '';
		$data = array('form_action' => $form_action,'user_email' => $user_email);
		return View::make('orderstatus')->with('data',$data);
	}

	private function _validate_username($str){
		return preg_match('/^[A-Za-z0-9_@\-\.]+$/',$str);
	}
	
	public function cybersecurityDataSolution()
	{
		return View::make('cybersecurityDataSolution');
	
	}
	//function for lookup search....
	public function whoisLookupSearch()
	{
		return View::make('whoisLookupSearch');
	}
	
	public function whoisLookupSearchResult( $domainName )
	{	
		return View::make('whoisLookupSearchResult')->with('q',$domainName);
	}

	public function creditcard()
	{
		$form_action = URL::to('whoisxmlapi_4/creditcard_handler.php');
		$data = array(
			'form_action' => $form_action
		);

		return View::make('creditcard')->with('data', $data);
	}
}
