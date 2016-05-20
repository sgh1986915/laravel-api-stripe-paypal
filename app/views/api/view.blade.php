@if (empty($api_keys))

<div class="alert alert-info alert-block">No API Keys Found.</div>

@else

<div class="table-responsive" id="viewapi">
	<table class="table table-bordered wa-table-responsive wa-table-account-management wa-table-api-o-view " id="wa-table-ak">
		<thead>
			<tr>
				<th>Key</th>
				<th>Secret key</th>
				<th>Status</th>
				<th>Description</th>
				<th class="text-center">Modify</th>
				<th class="text-center">Delete</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($api_keys as $i=>$api_key)
			<tr apikeyid="{{ $api_key['id'] }}">
				<td>{{ $api_key['api_key'] }}</td>
				<td class="wa-word-wrap">{{ $api_key['secret_key'] }}</td>
				<td><div class="wa-lbl-ApiKeyStatus" style="display: block;">{{ ($api_key['is_active']) ? "Active" : "Inactive" }}</div>{{ Form::select('status', array('1' => 'Active', '0' => 'Inactive'), $api_key['is_active'], array('style'=> 'display:none','class' => 'wa-ApiKeyStatus','currstatus' => $api_key['is_active'])) }} </td>
				<td><div class="wa-inline-input wa-resetInput wa-description" currdescription="{{ $api_key['description'] }}">{{ $api_key['description'] }}</div></td>
				<td class="text-center"><span class="glyphicon glyphicon-pencil wa-cursor wa-modifyApiKey wa-modify-icon"></span><span class="glyphicon glyphicon-ok wa-cursor wa-updateApiKey wa-update-icon" style="display:none;"></span><span class="glyphicon glyphicon-remove wa-cursor wa-cancelApiKey wa-cancel-icon" style="display:none;"></span></td>
				<td class="text-center"><span class="glyphicon glyphicon-trash wa-cursor wa-deleteApiKey wa-delete-icon"></span></td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>

@endif

<div style="margin:10px;">
	<button class="btn btn-default wa-btn-orange wa-btn-createapikey" id="wa-btn-createapikey">Create New Keys</button>
</div>
