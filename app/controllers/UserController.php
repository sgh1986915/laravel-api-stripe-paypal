<?php
class UserController extends BaseController {

	public function __construct()
	{
		Session::forget('success');
		Session::forget('successType');
		setcookie("order_error", "", time() - 3600, '/');
	}

	public function show()
	{
		if (Auth::check())
		{
			return View::make('users.management');
		}
		return View::make('login');
	}
	public function view()
	{
		if (Auth::check())
		{
			$user = User::find(Auth::id())->toArray();
			return View::make('users.view')->with('user',$user);
		}
		return View::make('login');
	}

	public function create()
	{
		return View::make('users.create');
	}

	public function save()
	{

		$firstname = Input::get('firstname');
		$lastname = Input::get('lastname');
		$username = Input::get('username');
		$email = Input::get('email');
		$password = Input::get('password');
		$password_confirmation = Input::get('password_confirmation');
		$organization = Input::get('organization');
		$status = 'unconfirmed';
		$activation = str_random(30);
		$captcha = Input::get('captcha');;

		$userdata = array(
			'firstname' => $firstname ,
			'lastname' => $lastname ,
			'username' => $username ,
			'email' => $email,
			'password' => $password ,
			'password_confirmation' => $password_confirmation ,
			'organization' => $organization ,
			'status' => $status,
			'activation' => $activation,
			'captcha' => $captcha
			);

		$rules = array(
			// 'firstname'  => 'Required',
			// 'lastname'  => 'Required',
			'username'  => 'required|unique:users',
			'email' => 'required|email|unique:users',
			'password'  => 'Required|min:6|confirmed',
			'password_confirmation'  => 'Required|min:6',
			// 'organization'  => 'Required',
			'captcha'  => 'Required|captcha'
			);

		$validator = Validator::make($userdata, $rules);

		if ($validator->passes())
		{
			$User = new User;
			$User->firstname = $firstname;
			$User->lastname = $lastname;
			$User->username = $username;
			$User->email = $email;
			$User->hashed_password = Hash::make($password);
			$User->password = $password;
			$User->organization = $organization;
			$User->status = $status;
			$User->activation = $activation;

			if($User->save()) {
				Mail::send('emails.verify', $userdata, function($message) use ($userdata) {
					$message->to($userdata['email'], $userdata['email'])
					->subject('Verify your email address');
				});

				// Update user_account
				$UsersAccount = UsersAccount::where('username','=',$username)->first();
				if(!count($UsersAccount)) // Check user_account is already set or not
				{
					$UsersAccount = new UsersAccount;
					$UsersAccount->username = $username;
					$UsersAccount->balance = Config::get('settings.DEFAULT_RESERVE');
					$UsersAccount->reserve = Config::get('settings.DEFAULT_RESERVE');
					$UsersAccount->warn_threshold = Config::get('settings.DEFAULT_THRESHOLD');
					$UsersAccount->save();
				}

				// Update user_account2
				$UsersAccount2 = UsersAccount2::where('username','=',$username)->first();
				if(!count($UsersAccount2)) // Check user_account2 is already set or not
				{
					$UsersAccount2 = new UsersAccount2;
					$UsersAccount2->username = $username;
					$UsersAccount2->version = Config::get('settings.UsersAccount2_version');
					$UsersAccount2->save();
				}

				// Update profiles
				$Profile = new Profile;
				$Profile->username = $username;
				$Profile->firstName = $firstname;
				$Profile->lastName = $lastname;
				$Profile->save();

				// Update ApiKey
				$api_keys = create_api_keys();
				$ApiKey = new ApiKey;
				$ApiKey->userid = $User->id;
				$ApiKey->api_key = $api_keys['apikey'];
				$ApiKey->secret_key = $api_keys['secret'];
				$ApiKey->description = 'New API Keys';
				$ApiKey->is_active = 0;
				$ApiKey->save();

				return Redirect::to('login.php')->withErrors(array('success' => 'You have registered successfully. Check your emails for more details.'));
			} else {
				return Redirect::to('login.php')->withErrors(array('success', 'Something went wrong. Try again later.','successType' => 'danger'));
			}
		}

		/*print_r($validator->messages());
		exit();*/
		return Redirect::to('user/create.php')->withErrors($validator)->withInput(Input::except('captcha'));

	}

	public function confirm($confirmation_code)
	{
		if(!$confirmation_code)
		{
			return Redirect::to('login.php')->with('success', 'Invalid Confirmation Code.');
		}

		$User = User::where('activation','=',$confirmation_code)->first();

		if (!$User)
		{
			return Redirect::to('login.php')->with('success', 'Invalid Confirmation Code.');
		}

		$User->status = 'enabled';
		$User->activation = null;
		$User->save();

		return Redirect::to('login.php')->withErrors(array('success', 'You have successfully verified your account. You can login now.'));
	}

	public function edit()
	{
		$user = User::find(Auth::id())->toArray();
		return View::make('users.edit')->with('user',$user);
	}

	public function update()
	{
		$firstname = Input::get('firstname');
		$lastname = Input::get('lastname');
		$username = Input::get('username');
		$email = Input::get('email');
		$password = Input::get('password');
		$newpassword = Input::get('newpassword');
		$organization = Input::get('organization');
		$status = Auth::user()->status;
		$activation = Auth::user()->activation;
		$verifyEmail = false;
		if(Auth::user()->email != $email) {
			$verifyEmail = true;
			$status = 'unconfirmed';
			$activation = str_random(30);
		}

		$currUserId = Auth::user()->id;
		$oldUserName = Auth::user()->username;

		$userdata = array(
			'firstname' => $firstname ,
			'lastname' => $lastname ,
			'username' => $username ,
			'email' => $email,
			'password' => $password ,
			'newpassword' => $newpassword ,
			'organization' => $organization ,
			'status' => $status,
			'activation' => $activation
			);

		$rules = array(
			// 'firstname'  => 'Required',
			// 'lastname'  => 'Required',
			'username'  => 'required',
			'email' => 'required|email|unique:users,email,'.$currUserId,
			'password'  => 'min:6',
			'newpassword'  => 'min:6',
			// 'organization'  => 'Required',
			);
		if($oldUserName != $username){
			$rules['username'] = 'required|unique:users';
		}

		$validator = Validator::make($userdata, $rules);

		if ($validator->passes())
		{
			if((!empty($newpassword) && empty($password)) || (!empty($newpassword) && !empty($password) && !Hash::check($password, Auth::user()->hashed_password))) {
				return array('status' =>0,'password' => 'The password you entered is incorrect. Please try again.'); // Ajax response
				// return Redirect::to('user/edit')->withErrors(array('password' => 'The password you entered is incorrect. Please try again.'))->withInput();
			} else {
				$User = User::find($currUserId);
				$User->firstname = $firstname;
				$User->lastname = $lastname;
				$User->username = $username;
				$User->email = $email;
				$User->organization = $organization;
				$User->status = $status;
				$User->activation = $activation;

				/* Check if password matches old password & then only set new password */
				if(!empty($password) && !empty($newpassword) && Hash::check($password, Auth::user()->hashed_password)) {
					$User->hashed_password = Hash::make($newpassword);
					$User->password = $newpassword;
				}

				if($User->save()) {
					if($verifyEmail) {
						Mail::send('emails.verify', $userdata, function($message) use ($userdata) {
							$message->to($userdata['email'], $userdata['email'])
							->subject('Verify your email address');
						});
					}

					// Update user_account
					$UsersAccount = UsersAccount::where('username','=',$oldUserName)->first();
					if(count($UsersAccount))
					{
						$UsersAccount->username = $username;
						$UsersAccount->save();
					}

					// Update user_account2
					$UsersAccount2 = UsersAccount2::where('username','=',$oldUserName)->first();
					if(count($UsersAccount2))
					{
						$UsersAccount2->username = $username;
						$UsersAccount2->save();
					}

					// Update profiles
					$Profile = Profile::where('username','=',$oldUserName)->first();
					if(count($Profile))
					{
						$Profile->username = $username;
						$Profile->firstName = $firstname;
						$Profile->lastName = $lastname;
						$Profile->save();
					}

					Session::flash('success', 'Profile modified successfully.');
					return array('status' =>1,'message' =>'Profile modified successfully.'); // Ajax response
					// return Redirect::to('user/management.php')->with('success', 'Profile modified successfully.');
				} else {
					return array('status' =>0,'message' =>'Something went wrong. Try again later.'); // Ajax response
					// return Redirect::to('user/management.php')->with('success', 'Something went wrong. Try again later.');
				}
			}
		}

		return json_encode($validator->messages()); // Ajax response
		// return Redirect::to('user/edit')->withErrors($validator)->withInput();
	}

	public function delete()
	{
		$affectedRows = User::where('id', '=', Input::get('userid'))->delete();
		return $affectedRows;
	}

}