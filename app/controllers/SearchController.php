<?php
class SearchController extends BaseController {

	public function whoislookup()
	{
		$domainName = Input::get('domainName');
		$outputFormat = Input::get('outputFormat', 'xml');
		$userParam = '';
		if (count(Auth::user())) {
			//$userParam = '&username='.Auth::user()->username.'&password='.Auth::user()->password;
			//Date 1/4/2016 modified infonius
			$userParam = '&username='.urlencode(Auth::user()->username).'&password='.urlencode(Auth::user()->password);
		}
		$url = Config::get('settings.API_URL').'whoisserver/WhoisService?domainName=' . $domainName . '&outputFormat=' . $outputFormat.$userParam;
		/*$strCookie = (!empty($_COOKIE['PHPSESSID']))? 'PHPSESSID=' . $_COOKIE['PHPSESSID'] . '; path=/' : '';
		session_write_close();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt( $ch, CURLOPT_COOKIE, $strCookie );
		$response = curl_exec($ch);
		curl_close($ch);

		if($response === false) {
			exit(1);
		}
		if ($outputFormat == 'xml') {
			$NEW_LINE = "NEW_LINE";
			$response = addslashes(preg_replace('~>\s+<~', '><',  $response));
			$response = preg_replace( "/\r|\n/", $NEW_LINE, $response); // replace "NEW_LINE" with "<br>" for rawText with JS
			$response = rtrim($response,$NEW_LINE);
		} else {
			$response = addslashes(preg_replace('/\s+/', " ",  $response));
		}*/

		$data = array('url' => $url,'outputFormat' => $outputFormat);
		return View::make('whoislookup')->with('data', $data);
	}

	public function reversewhoislookup()
	{
		$search_type = (Input::has('search_type')) ? Input::has('search_type') : 1;
		$rid = Session::get('rid',1);
		$post_data = Input::all();
		$post_data['rid'] = $rid;
		$url = URL::to('/'). "/whoisxmlapi_4/reverse-whois/search_whois_records.php?".http_build_query($post_data);
		$strCookie = (!empty($_COOKIE['PHPSESSID']))? 'PHPSESSID=' . $_COOKIE['PHPSESSID'] . '; path=/' : ''; /* Session Transfer */
		session_write_close();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt( $ch, CURLOPT_COOKIE, $strCookie );
		$result = curl_exec($ch);
		curl_close($ch);

		Session::put('rid', ++$rid);
		return $result;
	}

	public function reversewhoismyreport()
	{
		$search_type = (Input::has('search_type')) ? Input::has('search_type') : 1;
		$post_data = Input::all();
		$url =  "http://localhost/whoisapi_svn/whoisxmlapi_4/reverse-whois/get_my_reports.php?".http_build_query($post_data);
		$strCookie = (!empty($_COOKIE['PHPSESSID']))? 'PHPSESSID=' . $_COOKIE['PHPSESSID'] . '; path=/' : ''; /* Session Transfer */
		session_write_close();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt( $ch, CURLOPT_COOKIE, $strCookie );
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
}