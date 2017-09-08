@extends('laravel-cms::layout')
@section('content')

@if($fieldset->single)

	<element-manager
		uri-prefix="/admin"
		fieldset-id="{{ $fieldset->id }}"
		editor-css="{{ mix('css/tinymce.css') }}"
		:editor-options="{{ json_encode(config('laravel-cms.editorOptions')) }}"
		editor-assets-url="/admin/api/media?limit=0">
	</element-manager>

@else

	<elements-manager
		uri-prefix="/admin"
		fieldset-id="{{ $fieldset->id }}"
		editor-css="{{ mix('css/tinymce.css') }}"
		:editor-options="{{ json_encode(config('laravel-cms.editorOptions')) }}"
		editor-assets-url="/admin/api/media?limit=0">
	</elements-manager>

@endif

@endsection