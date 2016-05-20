<?php

class RemindersController extends Controller {

	public function __construct()
	{
		Session::forget('success');
		Session::forget('successType');
		setcookie("order_error", "", time() - 3600, '/');
	}

	public function getRemind()
	{
		return View::make('password.remind');
	}

	public function postRemind()
	{
		if(!Input::has('email')) {
			return Redirect::back()->withErrors(array('success' => 'Enter an email address.','successType' => 'danger'));
		}

		$response = Password::remind(Input::only('email'), function($message)
		{
			$message->subject('Password Reminder');
		});

		$msg = Lang::get($response);

		switch ($response)
		{
			case Password::INVALID_USER:
			return Redirect::back()->withErrors(array('success' => $msg,'successType' => 'danger'));

			case Password::REMINDER_SENT:
			return Redirect::to('login.php')->withErrors(array('success' => $msg));
		}
	}

	public function postRemindUsername()
	{
		if(!Input::has('email')) {
			return Redirect::back()->withErrors(array('success' => 'Enter an email address.','successType' => 'danger'));
		}

		$email = Input::get('email');
		$User = User::where('email','=',$email)->first();
		if(!empty($email) && count($User))
		{
			$userdata = array('username' => $User->username);
			Mail::send('emails.recover_username', $userdata, function($message) use ($email) {
				$message->to($email, $email)
				->subject('Username recovery');
			});
			return Redirect::to('login.php')->withErrors(array('success' => 'Your Username has been sent to your email address.'));
		}
		return Redirect::back()->withErrors(array('success' => Lang::get('reminders.user'),'successType' => 'danger'));
	}

	public function getReset($token = null)
	{
		if (is_null($token)) App::abort(404);

		return View::make('password.reset')->with('token', $token);
	}

	public function postReset()
	{
		$credentials = Input::only(
			'email', 'password', 'password_confirmation', 'token'
			);

		$response = Password::reset($credentials, function($user, $password)
		{
			$user->hashed_password = Hash::make($password);
			$user->password = $password;
			$user->status = 'enabled';
			$user->save();
		});

		$msg = Lang::get($response);

		switch ($response)
		{
			case Password::INVALID_PASSWORD:
			case Password::INVALID_TOKEN:
			case Password::INVALID_USER:
			return Redirect::back()->with(array('success' =>$msg,'successType' => 'danger'));

			case Password::PASSWORD_RESET:
				/*$email = Input::get('email');
				$password = Input::get('password');
				if (Auth::attempt(array('email' => $email, 'password' => $password), true))
				{
	    			echo "Login Success with pass: ".$password;
				} else {
					echo "Login Failed with pass: ".$password;
				}
				$password = 'admin';
				if (Auth::attempt(array('email' => $email, 'password' => $password), true))
				{
	    			echo "Login Success with pass: ".$password;
				} else {
					echo "Login Failed with pass: ".$password;
				}
				exit();*/
				return Redirect::to('login.php')->with('success', $msg);
			}
		}
	}
