<?php
class NewsletterController extends BaseController {

	public function subscribe()
	{
		$userdata = array('email' => Input::get('email'));
		$rules = array('email' => 'required|email|unique:newsletters');
		$messages = array('unique' => 'The :attribute already been registered.');
		$validator = Validator::make($userdata, $rules,$messages);
		if ($validator->passes()) {
			$Newsletter = new Newsletter;
			$Newsletter->email = Input::get('email');
			if($Newsletter->save()) {
				return array('status' =>1,'message' => 'You have successfully subscribed to our newsletter.');
			} else {
				return array('status' =>0,'message' => 'Something went wrong. Try again later.');
			}
		} else {
			return array('status' =>0,'message' => $validator->messages()->first('email'));
		}
	}
}