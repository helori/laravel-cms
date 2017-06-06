@extends('laravel-cms::admin.layout')
@section('content')
<div id="table-manager">
	
	<table-manager
		table-id="{{ $table_id }}"
		assets-base-url="{{ asset('/') }}"
		queries-base-url="{{ url('/admin') }}/"
		editor-css="{{ mix('css/tinymce.css') }}">
	</table-manager>

</div>
@endsection
