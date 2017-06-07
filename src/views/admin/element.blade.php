@extends('laravel-cms::admin.layout')
@section('content')
<div id="element">
	
	<div class="container">

		<element-manager
			table-id="{{ $table_id }}"
			assets-base-url="{{ asset('/') }}"
			queries-base-url="{{ url('/admin') }}/"
			editor-css="{{ mix('css/tinymce.css') }}">
		</element-manager>

	</div>

</div>
@endsection