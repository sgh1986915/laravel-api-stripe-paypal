<div class="wa-tab-content wa-tab-content-profile">
	<div class="row wa-row-content">
		<div class="col-sm-6 col-xs-12 wa-field-section">
			<div id="tp-fn" class="wa-field-title wa-field-title-viewuser">Name</div>
			<div class="tp-field-value tp-field-value-viewuser">{{ $user['firstname'] }}  {{ $user['lastname'] }}</div>
		</div>
		<div class="col-sm-6 col-xs-12 wa-field-section">
			<div id="tp-fn" class="wa-field-title wa-field-title-viewuser">Username</div>
			<div class="tp-field-value tp-field-value-viewuser">{{ $user['username'] }}</div>
		</div>
		<div class="col-sm-6 col-xs-12 wa-field-section">
			<div id="tp-fn" class="wa-field-title wa-field-title-viewuser">Email</div>
			<div class="tp-field-value tp-field-value-viewuser">{{ $user['email'] }}</div>
		</div>
		<div class="col-sm-6 col-xs-12 wa-field-section">
			<div id="tp-fn" class="wa-field-title wa-field-title-viewuser">Organization</div>
			<div class="tp-field-value tp-field-value-viewuser">{{ $user['organization'] }}</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<button type="button" class="btn btn-default wa-btn wa-btn-orange wa-btn-edit-profile" id="wa-btn-edit-profile">EDIT PROFILE</button>
		</div>
	</div>
</div>