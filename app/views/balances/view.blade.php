<div class="wa-tab-content wa-tab-content-balance">
	@if (!empty($balances['error']))
	<div class="alert alert-danger alert-block">{{ $balances['error'] }}</div>
	@else
	<div class="row">
		@foreach ($balances as $key => $balance)
		<div class="col-sm-6 col-xs-12 wa-field-section">
			<?php $displayName = ucwords(str_replace('_', ' ', $key)); ?>
			<div id="{{$key}}" class=" wa-field-title wa-field-title-balance">{{ $displayName }} : </div>
			<div class="tp-field-value tp-field-value-balance"> {{ $balance }}</div>
		</div>
		@endforeach
	</div>
	@endif
</div>