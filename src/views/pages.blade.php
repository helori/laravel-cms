@extends('laravel-cms::layout')
@section('content')

<page-manager
	uri-prefix="/admin"
	editor-css="{{ mix('css/tinymce.css') }}">
</page-manager>

@endsection