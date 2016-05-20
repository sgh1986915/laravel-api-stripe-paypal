<?php
$libPath = base_path(). "/whoisxmlapi_4";
?>
@extends('layouts.blank')

@section('title')
Popup
@stop

@section('styles')
@parent
{{ HTML::style('css/popup.css') }}
@stop

@section('content')
<div class="container wa-container-popup" id="wa-popup">
	<div class="row">
		<div class="col-xs-12">
			<span class="glyphicon glyphicon-remove wa-glyphicon-remove pull-right" id="wa-popup-btn-close"></span>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<div class="wa-header-popup" id="wa-header-popup">Header</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<div class="wa-msg-popup" id="wa-msg-popup">Message</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<button type="button" class="pull-right btn btn-default wa-btn-orange" id="wa-popup-btn-ok">OK</button>
		</div>
	</div>
</div>
@stop