@extends('laravel-cms::layout')
@section('content')

<blog-manager
	uri-prefix="/admin"
	editor-css="{{ mix('css/tinymce.css') }}">	
</blog-manager>

@endsection