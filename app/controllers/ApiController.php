<?php
class ApiController extends BaseController {

	public function __construct()
	{
		Session::forget('success');
		Session::forget('successType');
		setcookie("order_error", "", time() - 3600, '/');
	}

	public function show()
	{
		$api_keys = ApiKey::where('userid', '=',Auth::id())->get()->toArray();
		return View::make('api/view',array('api_keys' => $api_keys));
	}

	public function create()
	{
		$api_keys = create_api_keys();
		return View::make('api/create',array('api_keys' => $api_keys));
	}

	public function save()
	{
		$api_key = Input::get('api-key');
		$secret_key = Input::get('api-secret');
		$description = Input::get('api-description');
		$is_active = Input::get('api-status');

		$ApiKey = new ApiKey;
		$ApiKey->userid = Auth::id();
		$ApiKey->api_key = $api_key;
		$ApiKey->secret_key = $secret_key;
		$ApiKey->description = $description;
		$ApiKey->is_active = $is_active;

		if($ApiKey->save()) {
			return Redirect::to('user/management.php#api-key-management')->with('success', 'API Keys added successfully.');
		} else {
			return Redirect::to('user/management.php#api-key-management')->with('success', 'Something went wrong. Try again later.');
		}
	}

	public function update()
	{
		$ApiKey = ApiKey::where('id', '=', Input::get('ApiKeyID'))->first();
		$ApiKey->Description = Input::get('Description');
		$ApiKey->is_active = Input::get('is_active');
		return ($ApiKey->save()) ? 1 : 0;
	}

	public function delete()
	{
		$affectedRows = ApiKey::where('id', '=', Input::get('ApiKeyID'))->delete();
		return $affectedRows;
	}

}