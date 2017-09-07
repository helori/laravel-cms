@extends('laravel-cms::layout')
@section('content')

@if($fieldset->single)

	<element-manager
		uri-prefix="/admin"
		fieldset-id="{{ $fieldset->id }}"
		editor-css="{{ mix('css/tinymce.css') }}">
	</element-manager>

@else

	<elements-manager
		uri-prefix="/admin"
		fieldset-id="{{ $fieldset->id }}"
		editor-css="{{ mix('css/tinymce.css') }}">
	</elements-manager>

@endif

@endsection