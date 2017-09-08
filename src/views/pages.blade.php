@extends('laravel-cms::layout')
@section('content')

<page-manager
	uri-prefix="/admin"
	editor-css="{{ mix('css/tinymce.css') }}"
	:editor-options="{{ json_encode(config('laravel-cms.editorOptions')) }}"
	editor-assets-url="/admin/api/media?limit=0">
</page-manager>

@endsection