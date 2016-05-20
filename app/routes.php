<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'HomeController@home');
Route::get('home.php', 'HomeController@home');

Route::get('/db_schemas/whoiscrawler_schema_mssql.sql', 'HomeController@home');/*added*/
Route::get('/db_schemas/whoiscrawler_schema_mysql.sql', 'HomeController@home');/*added*/
Route::get('/code/perl/whois.txt', 'HomeController@home');/*added*/
Route::get('/mobile', 'HomeController@home');/*added*/
Route::get('/domain', 'HomeController@home');/*added*/
Route::get('/m', 'HomeController@home');/*added*/
Route::get('/CachedWhois', 'HomeController@home');/*added*/
Route::get('/newly', 'HomeController@home');/*added*/
Route::get('/order_process.php', 'HomeController@home');/*added*/
Route::get('/creditcard_form.php', 'HomeController@creditcard');/*added*/
Route::get('/hosted_pri', 'HomeController@home');/*added*/

Route::get('reverse-whois.php', 'HomeController@innerpage');
Route::get('whois-api-doc.php', 'HomeController@whoisapidoc');

Route::get('whois-api-support.php', 'HomeController@whoisapidoc');/*added*/
Route::get('whois-api.php', 'HomeController@whoisapidoc');/*added*/

Route::get('whois-database-download.php', 'HomeController@whoisdd');

Route::get('whois-database', 'HomeController@whoisdd');/*added*/
Route::get('whois-database-download', 'HomeController@whoisdd');/*added*/

Route::get('whois-api-software.php', 'HomeController@whoissp');
Route::get('domain-availability-api-doc.php', 'HomeController@domainWhois');

Route::get('domain-availability-api.php', 'HomeController@domainWhois');/*added*/
Route::get('domain-availability.php', 'HomeController@domainWhois');/*added*/

Route::get('brand-alert-api.php', 'HomeController@brandalert');
Route::get('registrant-alert-api.php', 'HomeController@registeralert');
Route::get('bulk-whois-lookup.php', 'HomeController@bulkWhois');

Route::get('bulk-reverse', 'HomeController@bulkWhois');/*added*/

Route::get('reverse-ip.php', 'HomeController@reverseip');
Route::get('reverse-ip-api.php', 'HomeController@reverseipApi');
Route::get('domain-availability-api.php', 'HomeController@domainWhois');
Route::get('reverse-whois-api.php', 'HomeController@reversewhoisapi');

Route::get('reversewhoisapi-whois.php', 'HomeController@reversewhoisapi');/*added*/
Route::get('reverse-whois-intro.php', 'HomeController@reversewhoisapi');/*added*/
Route::get('my-reports_main.php', 'HomeController@reversewhoisapi');/*added*/
Route::get('reverse-whois-pricing_main.php', 'HomeController@reversewhoisapi');/*added*/
Route::get('reverse-whois/whois_record_preview.php', 'HomeController@reversewhoisapi');/*added*/

Route::get('registrar-whois-services.php', 'HomeController@registrarwhois');
Route::get('newly-registered-domains.php', 'HomeController@newDomain');
// new all registered domains page modified....
Route::get('all-registered-domains.php', 'HomeController@allDomain');
Route::get('whois-api-contact.php', 'HomeController@contactUs');
Route::get('order_paypal.php', 'HomeController@orderNow');
Route::get('loginNew.php', 'HomeController@loginNew');
Route::get('hosted_pricing.php', 'HomeController@pricingchart');
Route::get('domain-ip-database.php','HomeController@domainIp');
Route::get('whitepapers.php', 'HomeController@whitepapers');
Route::get('registration.php', 'HomeController@registration');
Route::get('terms-of-service.php', 'HomeController@termsofservice');
Route::get('bulk-reverse-ip-order.php', 'HomeController@reverseIppurchase');
Route::get('bulk-reverse-whois-order.php', 'HomeController@orderCredit_reverseWhoisLookup');
Route::get('reverse-whois-lookup.php', 'HomeController@reverseWhoisLookup');
Route::get('reverse-whois-order.php', 'HomeController@order_rw_report');
Route::get('reverse-ip-lookup.php', 'HomeController@reverseiplookup');
Route::get('affiliate-program.php', 'HomeController@affiliate_Program');
Route::get('privacy.php', 'HomeController@privacypolicy');
Route::get('support.php', 'HomeController@support');
Route::get('cc.php', 'HomeController@orderstatus');
Route::get('thankyou.php', 'HomeController@thankyou');
Route::get('thankyou_rw.php', 'HomeController@thankyou_rw');
Route::get('sitemap.php', 'HomeController@sitemap');
Route::get('reverse-whoislookup-pricing.php', 'HomeController@reversewhoislookuppricing');
Route::get('order-api-summary.php', 'HomeController@orderapiSummary');
Route::any('order-api-checkout.php', 'HomeController@orderapiCheckout');
Route::any('popup.php', 'HomeController@popup');
Route::any('custom_order.php', 'HomeController@custom_order');
Route::get('cybersecurity-data-solution.php', 'HomeController@cybersecurityDataSolution');


// Route::controller('password', 'RemindersController'); // password/remind
Route::get('password/remind.php', 'RemindersController@getRemind');
Route::post('password/remind.php', 'RemindersController@postRemind');
Route::post('password/remind-username.php', 'RemindersController@postRemindUsername');
Route::get('password/reset.php/{token}', 'RemindersController@getReset');
Route::post('password/reset.php', 'RemindersController@postReset');


// Authentication
Route::get('login.php', 'AuthController@showLogin');
Route::post('login.php', 'AuthController@postLogin');
Route::get('logout.php', 'AuthController@getLogout');
Route::get('user/create.php', 'UserController@create');
Route::post('user/create.php', 'UserController@save');

// Secure-Routes
Route::group(array('before' => 'auth'), function()
{
	Route::get('secret.php', 'HomeController@showSecret');
	Route::get('api.php', 'ApiController@show');
	Route::get('api/create.php', 'ApiController@create');
	Route::post('api/create.php', 'ApiController@save');
	Route::post('api/update.php', 'ApiController@update');

	//Route::get('user.php', 'UserController@show');
	Route::get('user/management.php', 'UserController@show');
	Route::get('user/view.php', 'UserController@view');
	Route::post('api/delete.php', 'ApiController@delete');
	Route::get('user/edit.php', 'UserController@edit');
	Route::post('user/edit.php', 'UserController@update');
	Route::get('invoice.php', 'InvoiceController@show');
	Route::get('balance.php', 'BalanceController@show');

	Route::get('order-api.php', 'HomeController@orderapi');
});

Route::get('register/verify.php/{confirmationCode}', [
	'as' => 'account-confirmation',
	'uses' => 'UserController@confirm'
]);

Route::any('whoislookup.php', 'SearchController@whoislookup');
Route::any('reversewhoislookup.php', 'SearchController@reversewhoislookup');
Route::any('reversewhoismyreport.php', 'SearchController@reversewhoismyreport');

Route::get('order.php', 'HomeController@orderCC');

// Newsletter
Route::post('subscribe.php', 'NewsletterController@subscribe');

// Redirect to new page with old URL's
// Route::get('thankyou.php', 'HomeController@thankyou');
// Route::get('order-api-checkout.php', 'HomeController@orderapiCheckout');

Route::get('getenv.php', function(){
	echo "environment = ", App::environment(), "<br>";
	echo "gethostname = ",  gethostname(), "<br>";
});

/*
Route::get('accountServices.php', function(){
	error_reporting(0);
	@ini_set('display_errors', 0);
	$inputs = Request::all();
	$fileURL = URL::to('/'). '/whoisxmlapi_4/accountServices.php?'.http_build_query($inputs);
	$content = file_get_contents($fileURL);

	if(!empty($inputs['output_format']) && strtolower($inputs['output_format']) == 'json') {
		return Response::make($content, '200')->header('Content-Type', 'text/json');
	} else {
		return Response::make($content, '200')->header('Content-Type', 'text/xml');
	}
});*/

	Route::any('whoisxmlapi_4/paypal.php', function(){
		$inputs = Request::all();
		$fileURL = URL::to('/'). '/whoisxmlapi_4/paypal.php?'.http_build_query($inputs);
		$content = file_get_contents($fileURL);
	
		return $content;
	});

