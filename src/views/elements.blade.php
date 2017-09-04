@extends('laravel-cms::layout')
@section('content')

<elements-manager
	uri-prefix="/admin"
	fieldset-id="{{ $fieldset_id }}">
</elements-manager>

@endsection