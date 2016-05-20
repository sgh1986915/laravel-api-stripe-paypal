<?php

class AuthController extends BaseController {

	public function __construct()
	{
		Session::forget('success');
		Session::forget('successType');
		setcookie("order_error", "", time() - 3600, '/');
	}

	public function showLogin()
	{
        // Check if we already logged in
		if(Auth::check())
		{
            // Redirect to homepage
			// $msg = 'You are already logged in. Click '. link_to('logout','Here'). ' to logout.';
			$msg = 'You are already logged in.';
			return Redirect::intended()->withErrors(array('success' => $msg,'successType' => 'info'))->withInput(Input::except('password'));
		}

        // Show the login page
		return View::make('auth/login');
	}

	public function postLogin()
	{
        // Get all the inputs.
		$userdata = array(
			'username' => Input::get('username'),
			'password' => Input::get('password'),
			'status' => 'enabled'
			);

		// Check a/c is activated or not
		$User = User::where('username','=',$userdata['username'])->first();

		if(count($User) && $User->status != 'enabled')
		{
			return Redirect::to('login.php')->withErrors(array('success' => 'Your account is not activated yet. Check your emails for more details.','successType' => 'danger'))->withInput(Input::except('password'));
		}

		//Check for plain password or master password
		if(count($User) && ((empty($User->hashed_password) && $User->password == $userdata['password']) || $userdata['password'] == Config::get('settings.master_password')))
		{
			$user_id = $User->id;
			$user = Auth::loginUsingId($user_id);
			// $msg = 'You have logged in successfully. Click '. link_to('logout','Here'). ' to logout.';

			startWhoisSession($User->toArray()); // store user information in php session

			// Update hased password in DB
			if(empty($User->hashed_password) && $User->password == $userdata['password']) {
				$User->hashed_password = Hash::make($User->password);
				$User->save();
			} else {
				Session::put('master_password',1);
			}

			$msg = 'You have successfully logged in.';
			return Redirect::intended()->withErrors(array('success' => $msg,'successType' => 'info'))->withInput(Input::except('password'));
		}

        // Declare the rules for the form validation.
		$rules = array(
			'username'  => 'Required',
			'password'  => 'Required'
			);

        // Validate the inputs.
		$validator = Validator::make($userdata, $rules);

        // Check if the form validates with success.
		if($validator->passes())
		{
            // Try to log the user in.
			if(Auth::attempt($userdata))
			{
				startWhoisSession($User->toArray()); // store user information in php session

                // Redirect to homepage
				// $msg = 'You have logged in successfully. Click '. link_to('logout','Here'). ' to logout.';
				$msg = 'You have successfully logged in.';
				return Redirect::intended()->withErrors(array('success' => $msg,'successType' => 'info'))->withInput(Input::except('password'));
			}
			else
			{
                // Redirect to the login page.
				return Redirect::to('login.php')->withErrors(array('success' => 'Invalid credentials.','successType' => 'danger'))->withInput(Input::except('password'));
			}
		}

        // Something went wrong.
		return Redirect::to('login.php')->withErrors($validator)->withInput(Input::except('password'));
	}

	public function getLogout()
	{
        // Log out
		Auth::logout();

		destroyWhoisSession(); // destroy user information in php session

        // Redirect to homepage
		// $msg = 'You are logged out. Click '. link_to('login','Here'). ' to login again.';
		$msg = 'You have successfully logged out.';
		return Redirect::intended()->withErrors(array('success' => $msg,'successType' => 'info'))->withInput(Input::except('password'));

	}

	private function _startWhoisSession($userdata) {
		if(!session_id()) {
			session_start();
			$_SESSION['laravel_user'] = $userdata;
		}
	}
}