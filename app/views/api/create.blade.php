{{ Form::open(array('url' => 'api/create.php', 'class' => 'form-horizontal','id' => 'createapi')) }}
<div class="form-group wa-form-group-apikey">
	{{ Form::label('api-key', 'API Key: ', array('class' => 'col-sm-4 col-xs-6 control-label wa-field-title')) }}

	<div class="col-sm-6 col-xs-12">
		{{ Form::text('api-key', $api_keys['apikey'] ,array('class' => 'form-control wa-formcontent-registration','readonly' => 'readonly','size' => '50')) }}
	</div>
</div>

<div class="form-group">
	{{ Form::label('api-secret', 'API Secret: ', array('class' => 'col-sm-4 col-xs-6 control-label wa-field-title')) }}

	<div class="col-sm-6 col-xs-12">
		{{ Form::textarea('api-secret', $api_keys['secret'] ,array('class' => 'form-control wa-formcontent-registration','readonly' => 'readonly','cols' => '80')) }}
	</div>
</div>

<div class="form-group">
	{{ Form::label('api-description', 'Description', array('class' => 'col-sm-4 col-xs-6 control-label wa-field-title')) }}
	<div class="col-sm-6 col-xs-12">
		{{ Form::text('api-description','New Key',array('class' => 'form-control wa-formcontent-registration')) }}
	</div>
</div>
<div class="form-group">
	{{ Form::label('api-status', 'Status', array('class' => 'col-sm-4 col-xs-6 control-label wa-field-title')) }}
	<div class="col-sm-6 col-xs-12">
		{{ Form::radio('api-status', '1',false, array('class' => 'pull-left tp-cursor wa-field-input-selection wa-field-input-selection-radio wa-field-input-selection-radio-active  wa-field-input-selection-crateapi','id'=>'api-status-active')) }}
		{{ Form::label('api-status-active', 'Active', array('class' => 'pull-left control-label wa-field-title wa-field-title-status wa-field-title-active')) }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		{{ Form::radio('api-status', '0',true, array('class' => 'pull-left tp-cursor wa-field-input-selection wa-field-input-selection-radio  wa-field-input-selection-radio-inactive wa-field-input-selection-crateapi','id'=>'api-status-inactive')) }}
		{{ Form::label('api-status-inactive', 'Inactive', array('class' => 'pull-left control-label wa-field-title wa-field-title-status wa-field-title-inactive')) }}
	</div>
</div>

<div class="form-group">
	<div class="col-sm-6 col-xs-12">
		{{ Form::submit('CREATE', array('class' => 'btn btn-default wa-btn-orange wa-btn-create-apikey')) }}
	</div>
	<div class="col-sm-6 col-xs-12">
		<button class="Pull-left btn btn-default wa-btn-orange wa-btn-cancel-apikey" type="button" id="wa-btn-cancel-apikey">CANCEL</button>
	</div>
</div>

{{ Form::close() }}
