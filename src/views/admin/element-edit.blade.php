@extends('laravel-cms::admin.layout')
@section('content')
<div id="element-edit">
	
	<div class="container">

		<element-updater
			:id="{{ $id }}"
			:table-id="{{ $table_id }}"
			editor-css="{{ mix('css/tinymce.css') }}">
		</element-updater>

	</div>

</div>
@endsection
