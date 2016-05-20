{{ Form::model($user,array('url' => 'user/edit.php','id' => 'wa-update-profile-form')) }}
<div class="form-group col-sm-6 col-xs-12 {{{ $errors->has('firstname') ? 'has-error' : '' }}}">
	{{ Form::label('Firstname', 'Firstname ', array('class' => 'control-label wa-field-title')) }}
	{{ Form::text('firstname', null ,array('id' => 'wa-update-profile-firstname', 'class' => 'form-control wa-formcontent-registration')) }}
	@if ($errors->first('firstname'))
	<div class="help-block wa-input-error">{{ $errors->first('firstname') }}</div>
	@endif
</div>

<div class="form-group col-sm-6 col-xs-12{{{ $errors->has('lastname') ? 'has-error' : '' }}}">
	{{ Form::label('Lastname', 'Lastname ', array('class' => 'control-label wa-field-title')) }}
	{{ Form::text('lastname', null ,array('id' => 'wa-update-profile-lastname', 'class' => 'form-control wa-formcontent-registration')) }}
	@if ($errors->first('lastname'))
	<div class="help-block wa-input-error">{{ $errors->first('lastname') }}</div>
	@endif
</div>

<div class="clearfix"></div>

<div class="form-group col-sm-6 col-xs-12{{{ $errors->has('username') ? 'has-error' : '' }}}">
	{{ Form::label('Username', 'Username ', array('class' => 'control-label wa-field-title')) }}
	{{ Form::text('username', null ,array('id' => 'wa-update-profile-username', 'class' => 'form-control wa-formcontent-registration')) }}
	@if ($errors->first('username'))
	<div class="help-block wa-input-error">{{ $errors->first('username') }}</div>
	@endif
</div>

<div class="form-group col-sm-6 col-xs-12{{{ $errors->has('email') ? 'has-error' : '' }}}">
	{{ Form::label('Email', 'Email ', array('class' => 'control-label wa-field-title')) }}
	{{ Form::email('email', null ,array('id' => 'wa-update-profile-email','class' => 'form-control wa-formcontent-registration')) }}
	@if ($errors->first('email'))
	<div class="help-block wa-input-error">{{ $errors->first('email') }}</div>
	@endif
</div>
<div class="form-group col-sm-6 col-xs-12{{{ $errors->has('organization') ? 'has-error' : '' }}}">
	{{ Form::label('Organization', 'Organization ', array('class' => 'control-label wa-field-title')) }}
	{{ Form::text('organization', null ,array('id' => 'wa-update-profile-organization', 'class' => 'form-control wa-formcontent-registration')) }}
	@if ($errors->first('organization'))
	<div class="help-block wa-input-error">{{ $errors->first('organization') }}</div>
	@endif
</div>

<div class="clearfix"></div>

<div class="wa-line-separator"></div>

<div class="form-group col-sm-6 col-xs-12{{{ $errors->has('password') ? 'has-error' : '' }}}">
	{{ Form::label('password', 'Current Password ', array('class' => 'control-label wa-field-title')) }}
	{{ Form::password('password', array('id' => 'wa-update-profile-password','class' => 'form-control wa-formcontent-registration')) }}
	@if ($errors->first('password'))
	<div class="help-block wa-input-error">{{ $errors->first('password') }}</div>
	@endif
</div>

<div class="clearfix"></div>

<div class="form-group col-sm-6 col-xs-12{{{ $errors->has('password') ? 'has-error' : '' }}}">
	{{ Form::label('newpassword', 'New Password ', array('class' => 'control-label wa-field-title')) }}
	{{ Form::password('newpassword', array('id' => 'wa-update-profile-newpassword','class' => 'form-control wa-formcontent-registration')) }}
	@if ($errors->first('newpassword'))
	<div class="help-block wa-input-error">{{ $errors->first('newpassword') }}</div>
	@endif
</div>

<div class="form-group col-sm-6 col-xs-12{{{ $errors->has('password') ? 'has-error' : '' }}}">
	{{ Form::label('repassword', 'Re-Enter New Password ', array('class' => 'control-label wa-field-title')) }}
	{{ Form::password('repassword', array('id' => 'wa-update-profile-re-pass','class' => 'form-control wa-formcontent-registration')) }}
	@if ($errors->first('repassword'))
	<div class="help-block wa-input-error">{{ $errors->first('repassword') }}</div>
	@endif
</div>

<div class="clearfix"></div>

<div class="wa-line-separator"></div>

<div class="form-group col-xs-6 col-sm-3" style="margin-bottom: 0px;">
	{{ Form::submit('Update', array('id' => 'wa-update-profile','class' => 'btn wa-btn-orange wa-btn-update btn-lg')) }}
</div>
<div class="clearfix"></div>
{{ Form::close() }}
